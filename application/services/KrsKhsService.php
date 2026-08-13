<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class KrsKhsService extends MY_Service {

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'akademik/Mahasiswa_model',
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/Ketua_jurusan_model',
            'jurusan/program_studi/nama_jurusan_model',
            'akademik/Krs_model',
            'akademik/Khs_model',
            'akademik/Krs_detail_model',
            'jurusan/kurikulum/m_matakuliah',
            'jurusan/program_studi/jenjang_model'
        ));
    }

    public function status_krs($ta, $prodi, $angkatan, $status) {
        if (!$ta) {
            $ta = $this->m_tahun_akademik->get_aktif();
        }
        if (!$prodi) {
            $prodi = 1;
        }

        $this->db->select('mhs.nim, mhs.nama_mahasiswa, krs.kode_krs')
            ->from('status_perkuliahan as sp')
            ->join('mahasiswa as mhs', 'mhs.nim = sp.nim')
            ->join('krs', 'krs.nim = sp.nim AND krs.kode_tahun_akademik = ' . $this->db->escape($ta), 'left')
            ->where('sp.kode_tahun_akademik', $ta)
            ->where('mhs.program_studi_kode', $prodi)
            ->where('sp.pembayaran_spp', '1');
            
        if ($status != 1) {
            $this->db->where('krs.nim', null);
        } else {
            $this->db->where('krs.nim !=', null);
        }

        if ($angkatan != 'semua' && $angkatan) {
            $this->db->where('substr(sp.nim,1,2)', substr($angkatan, 2, 2));
        }
        
        return $this->db->get()->result_object();
    }

    public function get_krs_by_angkatan_jurusan_semester($nama_angkatan, $nama_jurusan, $semester, $limit, $offset) {
        $tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $data_count = count($this->Krs_model->count_mahasiswa_by_angkatan_jurusan_semester($tahun_akademik, $nama_angkatan, $nama_jurusan, $semester));
        $query_jurusan = $this->nama_jurusan_model->get_all_byid($nama_jurusan);
        $data_mahasiswa = $this->Krs_model->get_mahasiswa_by_angkatan_jurusan_semester($tahun_akademik, $nama_angkatan, $nama_jurusan, $semester, $limit, $offset);

        return array(
            'count' => $data_count,
            'singkatan_program_studi' => $query_jurusan ? $query_jurusan->singkatan_program_studi : '',
            'data' => $data_mahasiswa
        );
    }

    public function maksimum_sks($nim, $semester, $kode_program_studi, $status_pendaftaran = '') {
        $data_penilaian = data_penilaian($nim, $semester - 1);
        if ($semester !== 1) {
            if ($semester >= 2 && $status_pendaftaran !== 'B') {
                $tahun_akademik = $this->m_tahun_akademik->get_kode_tahun_akademik_by_semester($semester, $nim) - 1;
                $kode_kr = $this->Krs_model->get_kode_krs($nim, $tahun_akademik);
                
                if ($kode_kr == 0) {
                    $kode_krs = $this->Krs_model->get_krs_konversi($nim);
                } else {
                    $kode_krs = $this->Krs_model->get_kode_krs($nim, $tahun_akademik);
                }

                $data_krs = $this->Khs_model->khs($kode_krs);
                $sksn = 0;
                $sks = 0;
                foreach ($data_krs as $row) {
                    $nilai_akhir = $row->nilai_akhir * 1;
                    $bobot = 0;
                    foreach ($data_penilaian as $key) {
                        if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
                            $bobot = $key['bobot_nilai'];
                        }
                    }
                    $sksn += ($bobot * $row->sks);
                    $sks += $row->sks;
                }
                
                if ($sks == 0) $sks = 1;
                $ipk_semester_lalu = $sksn / $sks;

            } else {
                $tahun_akademik = $this->m_tahun_akademik->get_kode_tahun_akademik_by_semester($semester, $nim) - 1;
                for ($x = 0; $x <= 3; $x++) {
                    $kode_krs = $this->Krs_model->get_kode_krs($nim, $tahun_akademik);
                    if ($kode_krs > 0) {
                        break;
                    } else {
                        $tahun_akademik = $tahun_akademik - 1;
                    }
                }

                $data_krs = $this->Khs_model->khs($kode_krs);
                $sksn = 0;
                $sks = 0;
                foreach ($data_krs as $row) {
                    $nilai_akhir = $row->nilai_akhir * 1;
                    $bobot = 0;
                    foreach ($data_penilaian as $key) {
                        if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
                            $bobot = $key['bobot_nilai'];
                        }
                    }
                    $sksn += ($bobot * $row->sks);
                    $sks += $row->sks;
                }
                if ($sks == 0) $sks = 1;
                $ipk_semester_lalu = $sksn / $sks;
            }
        } elseif ($semester == 1 && $status_pendaftaran !== 'B') {
            $tahun_akademik = $this->m_tahun_akademik->get_aktif();
            $kode_krs = $this->Krs_model->get_kode_krs_konversi($nim, $tahun_akademik);
            $data_krs = $this->Khs_model->khs($kode_krs);
            $sksn = 0;
            $sks = 0;
            foreach ($data_krs as $row) {
                $nilai_akhir = $row->nilai_akhir * 1;
                $bobot = 0;
                foreach ($data_penilaian as $key) {
                    if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
                        $bobot = $key['bobot_nilai'];
                    }
                }
                $sksn += ($bobot * $row->sks);
                $sks += $row->sks;
            }
            if ($sks == 0) $sks = 1;
            $ipk_semester_lalu = $sksn / $sks;
        } else {
            $ipk_semester_lalu = 0;
        }

        if ($ipk_semester_lalu >= 3.5) {
            $jumlah_maksimum_sks = 24;
        } elseif ($ipk_semester_lalu >= 3.25) {
            $jumlah_maksimum_sks = 23;
        } elseif ($ipk_semester_lalu >= 3) {
            $jumlah_maksimum_sks = 22;
        } elseif ($ipk_semester_lalu >= 2.75) {
            $jumlah_maksimum_sks = 21;
        } elseif ($ipk_semester_lalu >= 2.5) {
            $jumlah_maksimum_sks = 20;
        } elseif ($ipk_semester_lalu >= 2.25) {
            $jumlah_maksimum_sks = 19;
        } elseif ($ipk_semester_lalu >= 2) {
            $jumlah_maksimum_sks = 18;
        } elseif ($ipk_semester_lalu >= 1.75) {
            $jumlah_maksimum_sks = 16;
        } elseif ($ipk_semester_lalu >= 1.5) {
            $jumlah_maksimum_sks = 14;
        } else {
            $jumlah_maksimum_sks = 12;
        }

        return array(
            'ip_semester_lalu' => $ipk_semester_lalu,
            'beban_sks' => $jumlah_maksimum_sks
        );
    }

    public function getDosenSignature($nik) {
        return $this->db->select('signature')->from('dosen')->where('nik', $nik)->get()->row_object();
    }

    public function searchKrs($keyword) {
        return $this->db->select('krs.kode_krs, krs.nim, mah.nama_mahasiswa, semester')
            ->from('krs')
            ->join('mahasiswa as mah', 'krs.nim=mah.nim')
            ->join('krs_detail as kd', 'kd.kode_krs=krs.kode_krs')
            ->where('(krs.nim like "%'.$keyword.'%" or mah.nama_mahasiswa like "%'.$keyword.'%")')
            ->where_not_in('krs.semester','K')
            ->order_by('krs.semester','DESC')
            ->where_not_in('kd.status','K')
            ->group_by('krs.kode_krs')
            ->limit(14)
            ->get()->result();
    }

    public function generate_khs_data($kode_krs, $nim) {
        $get_semester = $this->db->get_where('krs', array('kode_krs' => $kode_krs))->row_object();
        if (!$get_semester) return null;
        
        $semester = $get_semester->semester;
        $data_krs = $this->Khs_model->khs($kode_krs);
        $data_penilaian = data_penilaian($nim, $semester);
        $program_studi = get_kode_prodi($nim);

        $khs = array();
        $khs['nim'] = $nim;
        $khs['nama_mahasiswa'] = $this->db->get_where('mahasiswa', array('nim' => $nim))->row_object()->nama_mahasiswa;
        $khs['tahun_akademik'] = $this->m_tahun_akademik->get_byid($get_semester->kode_tahun_akademik);
        $khs['semester'] = $semester;
        $khs['kurikulum'] = nama_kurikulum_nama($nim);
        $khs['data_nilai'] = array();
        
        $sksn_total = 0;
        $sks_total = 0;
        $i = 0;
        foreach ($data_krs as $row) {
            $khs['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
            $khs['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
            $khs['data_nilai'][$i]['sks'] = $row->sks;
            $khs['data_nilai'][$i]['tb'] = $row->tidak_berhak;

            $nilai_akhir = $row->nilai_akhir * 1;
            $grade = '';
            $sksn = 0;
            foreach ($data_penilaian as $key) {
                if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
                    $grade = $key['grade'];
                    $sksn = $key['bobot_nilai'] * $row->sks;
                }
            }
            $khs['data_nilai'][$i]['grade'] = $grade;
            $khs['data_nilai'][$i]['sksn'] = $sksn;
            
            $sksn_total += $sksn;
            $sks_total += $row->sks;
            $i++;
        }
        $khs['sksn_total'] = $sksn_total;
        $khs['sks_total'] = $sks_total;
        if($program_studi) {
            $khs['kaprodi'] = $this->Ketua_jurusan_model->get_kaprodi($program_studi->kode_program_studi);
            $max_sks_data = $this->maksimum_sks($nim, $get_semester->kode_tahun_akademik, $program_studi->kode_program_studi);
            $khs['maksimum_sks'] = $max_sks_data['beban_sks'];
        }
        $khs['prodi'] = $program_studi;

        return $khs;
    }

    public function get_rekapitulasi_matakuliah($id_matakuliah, $kode_tahun_akademik) {
        $query = $this->db->select('sp.kode_status_perkuliahan,krs.nim, mahasiswa.nama_mahasiswa')
            ->from('krs')
            ->join('krs_detail', 'krs_detail.kode_krs=krs.kode_krs')
            ->join('mahasiswa', 'krs.nim=mahasiswa.nim')
            ->join('status_perkuliahan as sp', "sp.nim=krs.nim and sp.kode_tahun_akademik=".$this->db->escape($kode_tahun_akademik)." and sp.pembayaran_sks !='0'")
            ->where(array('krs.kode_tahun_akademik' => $kode_tahun_akademik, 'krs_detail.id_matakuliah' => $id_matakuliah))
            ->where_in('krs_detail.status', array('B', 'U'))
            ->where_not_in('krs.semester', 'K')
            ->group_by('krs.nim')
            ->order_by('krs.kode_krs', 'ASC')
            ->get()->result();
        
        return $query;
    }
}
