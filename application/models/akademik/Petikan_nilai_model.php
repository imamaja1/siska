<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Petikan_nilai_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function filter($angkatan, $kode_program_studi, $limit, $offset) {

        $query = $this->db->select('krs.kode_krs, krs.nim, mah.nama_mahasiswa, semester')
                        ->from('krs')
                        ->join('mahasiswa as mah', 'krs.nim=mah.nim')
                        ->where('mah.program_studi_kode', $kode_program_studi)
                        ->where('substring(krs.nim,1,2)', $angkatan)
                        ->limit($limit, $offset)
                        ->group_by('krs.nim')
                        ->order_by('krs.nim ASC')
                        ->get()->result();

        return $query;
    }

    public function count_data_filter($angkatan, $kode_program_studi) {

        $query = $this->db->select('krs.kode_krs, krs.nim, mah.nama_mahasiswa, semester')
                        ->from('krs')
                        ->join('mahasiswa as mah', 'krs.nim=mah.nim')
                        ->where('substring(krs.nim,1,2)', $angkatan)
                        ->where('mah.program_studi_kode', $kode_program_studi)
                        ->group_by('krs.nim')
                        ->order_by('krs.nim ASC')
                        ->get()->result();

        return $query;
    }

    public function cari($keyword, $limit, $offset) {
        $query = $this->db->select('krs.kode_krs, krs.nim, mah.nama_mahasiswa, semester')
                        ->from('krs')
                        ->join('mahasiswa as mah', 'krs.nim=mah.nim')
                        ->like('nama_mahasiswa', $keyword, 'both')
                        ->or_like('mah.nim', $keyword, 'both')
                        ->limit($limit, $offset)
                        ->group_by('krs.nim')
                        ->order_by('krs.nim ASC')
                        ->get()->result();

        return $query;
    }

    public function count_cari($keyword) {
        $query = $this->db->select('krs.kode_krs, krs.nim, mah.nama_mahasiswa, semester')
                        ->from('krs')
                        ->join('mahasiswa as mah', 'krs.nim=mah.nim')
                        ->like('nama_mahasiswa', $keyword, 'both')
                        ->or_like('mah.nim', $keyword, 'both')
                        ->group_by('krs.nim')
                        ->order_by('krs.nim ASC')
                        ->get()->result();

        return $query;
    }

    public function petikan_nilai($nim, $kode_nama_kurikulum) {
        $n = 0;
        $t_sks = 0;
        $t_sksn = 0;
        $dp_cache = [];
        for ($j = 1; $j <= 8; $j++) {
            $data_kurikulum = $this->db->select('kur.*, mak.*')
                            ->from('kurikulum as kur')
                            ->join('matakuliah as mak', 'kur.id_matakuliah=mak.id_matakuliah')
                            ->where('kode_nama_kurikulum', $kode_nama_kurikulum)
                            ->where('semester', $j)
                            ->order_by('substr(mak.kode_matakuliah,-4,4) ASC')
                            ->get()->result();
            if (count($data_kurikulum) <= 0) {
                break;
            }
            $data[$n]['semester'] = $j;
            $i = 0;
            foreach ($data_kurikulum as $row) {
                $cek = $this->db->select('*,count(kd.kode_krs_detail) as jumlah')
                                ->from('krs as k')
                                ->join('krs_detail as kd', 'k.kode_krs=kd.kode_krs')
                                ->join('khs_detail as hd', 'kd.kode_krs_detail=hd.kode_krs_detail')
                                ->join('matakuliah as m', 'kd.id_matakuliah=m.id_matakuliah')
                                ->where('m.kode_matakuliah', $row->kode_matakuliah)
                                ->where('k.nim', $nim)
                                ->get()->row_object();
                if (isset($row->kompetensi)) {
                    $data[$n]['data_nilai'][$i]['mk_pilihan'] = true;
                }
                $sks = $row->sks_teori + $row->sks_praktek + $row->sks_praktikum;
                if ($cek) {
                    $data[$n]['data_nilai'][$i]['jumlah_data'] = $cek->jumlah;
                    $data[$n]['data_nilai'][$i]['kode_krs'] = $cek->kode_krs_detail;
                    $data[$n]['data_nilai'][$i]['semester'] = $cek->semester;
                    $data[$n]['data_nilai'][$i]['nilai_harian'] = $cek->nilai_harian;
                    $data[$n]['data_nilai'][$i]['nilai_uts'] = $cek->nilai_uts;
                    $data[$n]['data_nilai'][$i]['nilai_uas'] = $cek->nilai_uas;
                    $data[$n]['data_nilai'][$i]['nilai_akhir'] = $cek->nilai_akhir;
                    $data[$n]['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
                    $data[$n]['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
                    $data[$n]['data_nilai'][$i]['param1'] = $row->param1;
                    $data[$n]['data_nilai'][$i]['sks_teori'] = $row->sks_teori;
                    $data[$n]['data_nilai'][$i]['sks_praktek'] = $row->sks_praktek;
                    $data[$n]['data_nilai'][$i]['sks_praktikum'] = $row->sks_praktikum;
                    if ($cek->jumlah == 1) {
                        $data_penilaian = data_penilaian($nim, $cek->semester);
                        $data[$n]['data_nilai'][$i]['sks'] = $sks;
                        $nilai_akhir = $cek->nilai_akhir * 1;
                        foreach ($data_penilaian as $key) {
                            if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
                                $data[$n]['data_nilai'][$i]['grade'] = $key['grade'];
                                $data[$n]['data_nilai'][$i]['sksn'] = $key['bobot_nilai'] * $sks;
                                $data[$n]['data_nilai'][$i]['sks'] = $sks;
                            }
                        }
                    } elseif ($cek->jumlah > 1) {
                        $semua = $this->db->select('*')
                                ->from('krs')
                                ->join('krs_detail as kd', 'krs.kode_krs=kd.kode_krs')
                                ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
                                ->join('matakuliah as mak', 'kd.id_matakuliah=mak.id_matakuliah')
                                ->where('nim', $nim)
                                ->where('mak.kode_matakuliah', $row->kode_matakuliah)
                                ->order_by('krs.semester', 'ASC')
                                ->get()->result_object();
                        $attempts = [];
                        $max_nilai = 0;
                        $max_grade = '-';
                        $max_sksn = 0;
                        foreach ($semua as $s) {
                            $penilaian = data_penilaian($nim, $s->semester);
                            $na = $s->nilai_akhir * 1;
                            $grade = '-';
                            $sksn = 0;
                            foreach ($penilaian as $key) {
                                if (($key['nilai_minimum'] <= $na) && ($na <= $key['nilai_maksimum'])) {
                                    $grade = $key['grade'];
                                    $sksn = $key['bobot_nilai'] * $sks;
                                    break;
                                }
                            }
                            $attempts[] = [
                                'semester' => $s->semester,
                                'nilai_akhir' => $na,
                                'grade' => $grade,
                                'sksn' => $sksn,
                            ];
                            if ($na > $max_nilai) {
                                $max_nilai = $na;
                                $max_grade = $grade;
                                $max_sksn = $sksn;
                            }
                        }
                        $data[$n]['data_nilai'][$i]['attempts'] = $attempts;
                        $data[$n]['data_nilai'][$i]['semester'] = end($attempts)['semester'];
                        $data[$n]['data_nilai'][$i]['nilai_akhir'] = $max_nilai;
                        $data[$n]['data_nilai'][$i]['grade'] = $max_grade;
                        $data[$n]['data_nilai'][$i]['sksn'] = $max_sksn;
                        $data[$n]['data_nilai'][$i]['sks'] = $sks;
                        $data[$n]['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
                        $data[$n]['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
                        $data[$n]['data_nilai'][$i]['param1'] = $row->param1;
                        $data[$n]['data_nilai'][$i]['sks_teori'] = $row->sks_teori;
                        $data[$n]['data_nilai'][$i]['sks_praktek'] = $row->sks_praktek;
                        $data[$n]['data_nilai'][$i]['sks_praktikum'] = $row->sks_praktikum;
                    } else {
                        $data[$n]['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
                        $data[$n]['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
                        $data[$n]['data_nilai'][$i]['param1'] = $row->param1;
                        $data[$n]['data_nilai'][$i]['sks_teori'] = $row->sks_teori;
                        $data[$n]['data_nilai'][$i]['sks_praktek'] = $row->sks_praktek;
                        $data[$n]['data_nilai'][$i]['sks_praktikum'] = $row->sks_praktikum;
                        $data[$n]['data_nilai'][$i]['grade'] = '-';
                        $data[$n]['data_nilai'][$i]['sksn'] = 0;
                        $data[$n]['data_nilai'][$i]['sks'] = 0;
                    }
                } else {
                    $data[$n]['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
                    $data[$n]['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
                    $data[$n]['data_nilai'][$i]['param1'] = $row->param1;
                    $data[$n]['data_nilai'][$i]['sks_teori'] = $row->sks_teori;
                    $data[$n]['data_nilai'][$i]['sks_praktek'] = $row->sks_praktek;
                    $data[$n]['data_nilai'][$i]['sks_praktikum'] = $row->sks_praktikum;
                    $data[$n]['data_nilai'][$i]['jumlah_data'] = 0;
                    $data[$n]['data_nilai'][$i]['grade'] = '-';
                    $data[$n]['data_nilai'][$i]['sksn'] = 0;
                    $data[$n]['data_nilai'][$i]['sks'] = 0;
                }
                $t_sks = $t_sks + $data[$n]['data_nilai'][$i]['sks'];
                $t_sksn = $t_sksn + $data[$n]['data_nilai'][$i]['sksn'];
                $i++;
            }
            $data[$n]['sks'] = $t_sks;
            $data[$n]['sksn'] = $t_sksn;
            $n++;
        }
        return $data;
    }

    //Fungsi Pendukung
    public function get_kode_krs($nim) {
        $query = $this->db->where('nim', $nim)->get('krs')->result();

        $data = array();
        foreach ($query as $row) {
            $data[] = $this->get_kode_krs_detail($row->kode_krs);
        }

        return $data;
    }

    public function get_kode_krs_detail($kode_krs) {
        // $query = $this->db->where('kode_krs', $kode_krs)->get('krs_detail')->result();
        $query = $this->db->query("SELECT *, krs.semester FROM krs, krs_detail, khs_detail, matakuliah WHERE krs_detail.kode_krs=krs.kode_krs and krs_detail.kode_krs_detail=khs_detail.kode_krs_detail and krs_detail.id_matakuliah=matakuliah.id_matakuliah and krs_detail.kode_krs=?", array($kode_krs))->result();
        return $query;
    }

    public function sistem_penilaian($nim) {
        $angkatan = substr($nim, 0, 2);
        $kode_jurusan = substr($nim, 2, 2);
        $kode_jenjang = substr($nim, 4, 1);

        $kode_program_studi = $this->get_kode_prodi($kode_jurusan, $kode_jenjang);

        $penilaian = $this->db->query("SELECT * FROM (SELECT distinct kode_sistem_penilaian_detail, mid(angkatan,-2) as angkatan, nama_kurikulum.kode_nama_kurikulum, nilai_minimum, nilai_maksimum, grade, bobot_nilai, kategori, keterangan, nama_kurikulum, kode_program_studi FROM nama_kurikulum, kurikulum, sistem_penilaian, sistem_penilaian_detail WHERE nama_kurikulum.kode_nama_kurikulum=kurikulum.kode_nama_kurikulum and nama_kurikulum.kode_nama_kurikulum=sistem_penilaian.kode_nama_kurikulum and sistem_penilaian.kode_sistem_penilaian=sistem_penilaian_detail.kode_sistem_penilaian) as mhs WHERE angkatan=? and kode_program_studi=?", array($angkatan, $kode_program_studi))->result_array();

        return $penilaian;
    }

    public function get_kode_prodi($kode_jurusan, $kode_jenjang) {
        $query = $this->db->query("SELECT kode_program_studi FROM program_studi, jurusan, jenjang WHERE program_studi.id_jenjang=jenjang.id_jenjang and program_studi.id_jurusan=jurusan.id_jurusan and kode_jenjang=? and kode_jurusan=?", array($kode_jenjang, $kode_jurusan))->row_object();
        return $query->kode_program_studi;
    }

    public function petikan_nilai_new($nim, $kode_nama_kurikulum, $mhs_semester) {
        $n = 0;
        $t_sks = 0;
        $t_sksn = 0;
        for ($j = 1; $j <= 8; $j++) {
            $data_kurikulum = $this->db->select('*,mk.id_matakuliah as kompetensi')
                            ->from('kurikulum as kur')
                            ->join('matakuliah as mak', 'kur.id_matakuliah=mak.id_matakuliah')
                            ->join('matakuliah_kompetensi as mk', 'mk.id_matakuliah=mak.id_matakuliah','left')
                            ->where('kode_nama_kurikulum', $kode_nama_kurikulum)
                            ->where('semester', $j)
                            ->order_by('substr(mak.kode_matakuliah,-4,4) ASC')
                            ->group_by('mak.id_matakuliah')
                            ->get()->result();
            if (count($data_kurikulum) <= 0) {
                break;
            }
            $data[$n]['semester'] = $j;
            $i = 0;
            foreach ($data_kurikulum as $row) {
                $cek = $this->db->select('*,count(kd.kode_krs_detail) as jumlah')
                                ->from('krs as k')
                                ->join('krs_detail as kd', 'k.kode_krs=kd.kode_krs')
                                ->join('khs_detail as hd', 'kd.kode_krs_detail=hd.kode_krs_detail')
                                ->join('matakuliah as m', 'kd.id_matakuliah=m.id_matakuliah')
                                ->where('m.kode_matakuliah', $row->kode_matakuliah)
                                ->where('k.nim', $nim)
                                ->where('k.semester < ', $mhs_semester)
                                ->get()->row_object();
                if ($row->kode_kompetensi) {
                    $data[$n]['data_nilai'][$i]['mk_pilihan'] = true;
                }
                $sks = $row->sks_teori + $row->sks_praktek + $row->sks_praktikum;
                if (!$cek) {
                    $data[$n]['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
                    $data[$n]['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
                    $data[$n]['data_nilai'][$i]['param1'] = $row->param1;
                    $data[$n]['data_nilai'][$i]['sks_teori'] = $row->sks_teori;
                    $data[$n]['data_nilai'][$i]['sks_praktek'] = $row->sks_praktek;
                    $data[$n]['data_nilai'][$i]['sks_praktikum'] = $row->sks_praktikum;
                    $data[$n]['data_nilai'][$i]['jumlah_data'] = 0;
                    $data[$n]['data_nilai'][$i]['grade'] = '-';
                    $data[$n]['data_nilai'][$i]['sksn'] = 0;
                    $data[$n]['data_nilai'][$i]['sks'] = 0;
                } else {
                    $data[$n]['data_nilai'][$i]['jumlah_data'] = $cek->jumlah;
                    $data[$n]['data_nilai'][$i]['kode_krs'] = $cek->kode_krs_detail;
                    $data[$n]['data_nilai'][$i]['semester'] = $cek->semester;
                    $data[$n]['data_nilai'][$i]['nilai_harian'] = $cek->nilai_harian;
                    $data[$n]['data_nilai'][$i]['nilai_uts'] = $cek->nilai_uts;
                    $data[$n]['data_nilai'][$i]['nilai_uas'] = $cek->nilai_uas;
                    $data[$n]['data_nilai'][$i]['nilai_akhir'] = $cek->nilai_akhir;
                    $data[$n]['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
                    $data[$n]['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
                    $data[$n]['data_nilai'][$i]['param1'] = $row->param1;
                    $data[$n]['data_nilai'][$i]['sks_teori'] = $row->sks_teori;
                    $data[$n]['data_nilai'][$i]['sks_praktek'] = $row->sks_praktek;
                    $data[$n]['data_nilai'][$i]['sks_praktikum'] = $row->sks_praktikum;
                    if ($cek->jumlah == 1) {
                        $data_penilaian = data_penilaian($nim, $cek->semester);
                        $data[$n]['data_nilai'][$i]['sks'] = $sks;
                        $nilai_akhir = $cek->nilai_akhir * 1;
                        foreach ($data_penilaian as $key) {
                            if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
                                $data[$n]['data_nilai'][$i]['grade'] = $key['grade'];
                                $data[$n]['data_nilai'][$i]['sksn'] = $key['bobot_nilai'] * $sks;
                                $data[$n]['data_nilai'][$i]['sks'] = $sks;
                            }
                        }
                    } elseif ($cek->jumlah > 1) {
                        $semua = $this->db->select('*')
                                ->from('krs')
                                ->join('krs_detail as kd', 'krs.kode_krs=kd.kode_krs')
                                ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
                                ->join('matakuliah as mak', 'kd.id_matakuliah=mak.id_matakuliah')
                                ->where('nim', $nim)
                                ->where('mak.kode_matakuliah', $row->kode_matakuliah)
                                ->order_by('krs.semester', 'ASC')
                                ->get()->result_object();
                        $attempts = [];
                        $max_nilai = 0;
                        $max_grade = '-';
                        $max_sksn = 0;
                        foreach ($semua as $s) {
                            $penilaian = data_penilaian($nim, $s->semester);
                            $na = $s->nilai_akhir * 1;
                            $grade = '-';
                            $sksn = 0;
                            foreach ($penilaian as $key) {
                                if (($key['nilai_minimum'] <= $na) && ($na <= $key['nilai_maksimum'])) {
                                    $grade = $key['grade'];
                                    $sksn = $key['bobot_nilai'] * $sks;
                                    break;
                                }
                            }
                            $attempts[] = [
                                'semester' => $s->semester,
                                'nilai_akhir' => $na,
                                'grade' => $grade,
                                'sksn' => $sksn,
                            ];
                            if ($na > $max_nilai) {
                                $max_nilai = $na;
                                $max_grade = $grade;
                                $max_sksn = $sksn;
                            }
                        }
                        $data[$n]['data_nilai'][$i]['attempts'] = $attempts;
                        $data[$n]['data_nilai'][$i]['semester'] = end($attempts)['semester'];
                        $data[$n]['data_nilai'][$i]['nilai_akhir'] = $max_nilai;
                        $data[$n]['data_nilai'][$i]['grade'] = $max_grade;
                        $data[$n]['data_nilai'][$i]['sksn'] = $max_sksn;
                        $data[$n]['data_nilai'][$i]['sks'] = $sks;
                        $data[$n]['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
                        $data[$n]['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
                        $data[$n]['data_nilai'][$i]['param1'] = $row->param1;
                        $data[$n]['data_nilai'][$i]['sks_teori'] = $row->sks_teori;
                        $data[$n]['data_nilai'][$i]['sks_praktek'] = $row->sks_praktek;
                        $data[$n]['data_nilai'][$i]['sks_praktikum'] = $row->sks_praktikum;
                    } else {
                        $data[$n]['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
                        $data[$n]['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
                        $data[$n]['data_nilai'][$i]['param1'] = $row->param1;
                        $data[$n]['data_nilai'][$i]['sks_teori'] = $row->sks_teori;
                        $data[$n]['data_nilai'][$i]['sks_praktek'] = $row->sks_praktek;
                        $data[$n]['data_nilai'][$i]['sks_praktikum'] = $row->sks_praktikum;
                        $data[$n]['data_nilai'][$i]['grade'] = '-';
                        $data[$n]['data_nilai'][$i]['sksn'] = 0;
                        $data[$n]['data_nilai'][$i]['sks'] = 0;
                    }
                }
                $t_sks = $t_sks + $data[$n]['data_nilai'][$i]['sks'];
                $t_sksn = $t_sksn + $data[$n]['data_nilai'][$i]['sksn'];
                $i++;
            }
            $data[$n]['sks'] = $t_sks;
            $data[$n]['sksn'] = $t_sksn;
            $n++;
        }
        return $data;
    }

}

/* End of file Petikan_nilai_model.php */
/* Location: ./application/models/Petikan_nilai_model.php */
?>