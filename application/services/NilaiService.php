<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class NilaiService extends MY_Service {

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'akademik/Petikan_nilai_model',
            'akademik/Mahasiswa_model',
            'jurusan/m_tahun_akademik'
        ));
    }

    /**
     * Get Petikan Nilai Data
     * 
     * @param string $nim
     * @param string $type  'all', 'ganjil', 'genap'
     * @return array
     */
    public function get_petikan_nilai_data($nim, $type = 'all') {
        $kode_nama_kurikulum = kode_nama_kurikulum($nim);
        $tahun_akademik_aktif = tahun_akademik();
        $tahun_akademik_id = $tahun_akademik_aktif->kode_tahun_akademik - 1;

        $mahasiswa = $this->Mahasiswa_model->get($nim);
        $ta_data = $this->db->select('*, tahun_akademik as ta')
            ->from('tahun_akademik')
            ->where('kode_tahun_akademik', $tahun_akademik_id)
            ->get()->row_object();
        $prodi = get_kode_prodi($nim);

        $data_petikan = null;
        $semester_target = null;

        if ($type === 'all') {
            $data_petikan = $this->Petikan_nilai_model->petikan_nilai($nim, $kode_nama_kurikulum);
        } else {
            // Logic for ganjil/genap
            $semester_record = $this->db->select('semester')
                ->from('krs')
                ->where('nim', $nim)
                ->order_by('kode_krs', 'desc')
                ->limit(1)
                ->get()->row();
                
            $semester = $semester_record ? $semester_record->semester : 1;
            
            // Calculate running semester (semester jalan)
            $angkatan = substr($nim, 0, 2);
            $tahun_akademik_year = substr($tahun_akademik_aktif->tahun_akademik, -2);
            $semester_jalan_awal = $tahun_akademik_year - $angkatan;

            if ($tahun_akademik_aktif->semester == 1) {
                $semester_jalan = ($semester_jalan_awal - 1) * 2 + 1;
            } else {
                $semester_jalan = ($semester_jalan_awal - 1) * 2 + 2;
            }

            if ($semester < $semester_jalan) {
                $semester = $semester_jalan;
            } else {
                if ($type === 'ganjil') {
                    if ($semester % 2 === 0) {
                        $semester = $semester - 1;
                    }
                } else if ($type === 'genap') {
                    if ($semester % 2 != 0) {
                        $semester = $semester - 1;
                    }
                }
            }
            
            $semester_target = $semester + 1;
            $data_petikan = $this->Petikan_nilai_model->petikan_nilai_new($nim, $kode_nama_kurikulum, $semester_target);
            
            // fix_ta logic
            if ($type === 'ganjil') {
                if ($tahun_akademik_aktif->kode_tahun_akademik % 2 == 1) { // sometimes the original code checks == 2 for ganjil which might be a bug, let's keep it safe or map to original
                    $fix_ta = $tahun_akademik_aktif->kode_tahun_akademik;
                } else {
                    $fix_ta = $tahun_akademik_aktif->kode_tahun_akademik - 1;
                }
                $ta_data = $this->db->select('*, tahun_akademik as ta')->from('tahun_akademik')->where('kode_tahun_akademik', $fix_ta)->get()->row_object();
            } else if ($type === 'genap') {
                if ($tahun_akademik_aktif->kode_tahun_akademik % 2 == 0) {
                    $fix_ta = $tahun_akademik_aktif->kode_tahun_akademik;
                } else {
                    $fix_ta = $tahun_akademik_aktif->kode_tahun_akademik - 1;
                }
                $ta_data = $this->db->select('*, tahun_akademik as ta')->from('tahun_akademik')->where('kode_tahun_akademik', $fix_ta)->get()->row_object();
            }
        }

        // Signature (TTD)
        $nik_data = bodo_kop($nim);
        $ttd = '';
        if ($nik_data && isset($nik_data['nik'])) {
            $dosen = $this->db->select('signature')->from('dosen')->where('nik', $nik_data['nik'])->get()->row_object();
            $ttd = $dosen ? $dosen->signature : '';
        }

        return array(
            'sub_judul' => $nim,
            'data' => $data_petikan,
            'mahasiswa' => $mahasiswa,
            'tahun_akademik' => $ta_data,
            'prodi' => $prodi,
            'semester' => $semester_target,
            'semester_jalan' => isset($semester_jalan) ? $semester_jalan : 0,
            'ttd' => $ttd
        );
    }

    public function validasi_kelas_nilai($kelas_id, $tipe) {
        $nilai = $this->db->select('dn.*')
            ->from('kelas_mahasiswa as km')
            ->join('dummy_nilai as dn', 'dn.kode_krs_detail=km.kode_krs_detail')
            ->where('kelas_id', $kelas_id)
            ->get()->result();

        $this->load->model('akademik/nilai_model');
        foreach ($nilai as $row) {
            $data_nilai = array(
                'nilai_harian' => $row->dummy_harian,
                'nilai_uts' => $row->dummy_uts,
                'nilai_uas' => $row->dummy_uas,
                'nilai_akhir' => $row->dummy_na,
            );
            $this->nilai_model->validasi_dekan($row->dummy_id, $data_nilai);
        }

        if ($tipe == 'uts') {
            $this->db->where('kelas_id', $kelas_id)->update('kelas', array('status_nilai_uts' => 'T', 'validasi_nilai_uts' => 'T', 'validasi_dekan_uts' => 'T', 'valid_uts' => '2'));
            $this->db->insert('kelas_validasi', ['kelas_id' => $kelas_id, 'isian_uts' => 'T', 'validasi_prodi_uts' => 'T', 'validasi_dekan_uts' => 'T']);
        } else {
            $this->db->where('kelas_id', $kelas_id)->update('kelas', array('status_nilai' => 'T', 'validasi_nilai' => 'T', 'validasi_dekan' => 'T', 'param_uas' => '', 'status_revisi_uas' => ''));
            $this->db->insert('kelas_validasi', ['kelas_id' => $kelas_id, 'isian' => 'T', 'validasi_prodi' => 'T', 'validasi_dekan' => 'T']);
        }
        return true;
    }

    public function get_grade($nim, $semester, $nilai_akhir) {
        $data_penilaian = data_penilaian($nim, $semester);
        $grade = "";
        $keterangan = "";
        $na = $nilai_akhir * 1;
        foreach ($data_penilaian as $key) {
            if (($key['nilai_minimum'] <= $na) && ($na <= $key['nilai_maksimum'])) {
                $grade = $key['grade'];
                $keterangan = $key['keterangan'];
            }
        }
        return array('grade' => $grade, 'keterangan' => $keterangan);
    }

    public function get_persentase_penginputan_prodi($kode_program_studi, $kode_tahun_akademik) {
        $this->load->model('akademik/nilai_model');
        $matakuliah = $this->nilai_model->get_all_matakuliah_by_tahun_akademik_and_jurusan($kode_tahun_akademik, $kode_program_studi);
        $hasil = [];
        $i = 0;
        foreach ($matakuliah as $row) {
            $total = $this->db->select('kd.kode_krs_detail,mak.kode_matakuliah, nama_matakuliah,nilai_akhir')
                ->from('krs_detail as kd')
                ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
                ->join('matakuliah as mak', 'mak.id_matakuliah=kd.id_matakuliah')
                ->join('krs', 'krs.kode_krs=kd.kode_krs')
                ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
                ->where_not_in('status', ['K'])
                ->where('kd.id_matakuliah', $row->id_matakuliah)
                ->get()->num_rows();

            $terisi = $this->db->select('kd.kode_krs_detail,mak.kode_matakuliah, nama_matakuliah,nilai_akhir')
                ->from('krs_detail as kd')
                ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
                ->join('matakuliah as mak', 'mak.id_matakuliah=kd.id_matakuliah')
                ->join('krs', 'krs.kode_krs=kd.kode_krs')
                ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
                ->where_not_in('status', ['K'])
                ->where('nilai_akhir IS NOT NULL')
                ->where('kd.id_matakuliah', $row->id_matakuliah)
                ->get()->num_rows();

            $hasil[$i]['semua'] = $total;
            $hasil[$i]['terisi'] = $terisi;
            $hasil[$i]['nama_matakuliah'] = $row->nama_matakuliah;
            $hasil[$i]['kode_matakuliah'] = $row->kode_matakuliah;
            $hasil[$i]['id_matakuliah'] = $row->id_matakuliah;
            $hasil[$i]['persen'] = $total > 0 ? number_format($terisi / $total * 100, 2) : 0;
            $hasil[$i]['status'] = $total == $terisi ? true : false;
            $i++;
        }
        return $hasil;
    }

    public function get_persentase_penginputan_semua($kode_tahun_akademik) {
        $this->load->model('jurusan/program_studi/nama_jurusan_model');
        $prodi = $this->nama_jurusan_model->get();
        $persen = [];
        $j = 0;
        foreach ($prodi as $item) {
            $persen[$j]['kode_program_studi'] = $item->kode_program_studi;
            $persen[$j]['nama_program_studi'] = $item->nama_program_studi;
            
            $hasil = $this->get_persentase_penginputan_prodi($item->kode_program_studi, $kode_tahun_akademik);
            
            $persen_semua = count($hasil);
            $persen_terisi = 0;
            $persen_makul = 0;
            foreach ($hasil as $h) {
                if ($h['status']) $persen_terisi++;
                $persen_makul += $h['persen'];
            }

            $persen[$j]['semua'] = $persen_semua;
            $persen[$j]['terisi'] = $persen_terisi;
            $persen[$j]['persen'] = $persen_semua > 0 ? $persen_makul / $persen_semua : 0;
            $persen[$j]['status'] = $persen_semua == $persen_terisi && $persen_semua != 0 ? true : false;
            $j++;
        }
        return $persen;
    }

    public function get_distribusi_nilai($kode_tahun_akademik, $kode_program_studi) {
        $data_kelas = $this->db->select('kelas.kelas_id, nama_matakuliah, nama_kelas, semester, group_concat(nama_dosen) as nama_dosen')
            ->from('kelas')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->join('nama_kelas', 'nama_kelas.nama_kelas_id=kelas.nama_kelas_id')
            ->join('mengajar', 'mengajar.kelas_id=kelas.kelas_id', 'left')
            ->join('dosen', 'mengajar.kode_dosen=dosen.kode_dosen', 'left')
            ->where('kelas.kode_tahun_akademik', $kode_tahun_akademik)
            ->where('kelas.kode_program_studi', $kode_program_studi)
            ->order_by('mak.nama_matakuliah')
            ->group_by('kelas.kelas_id')
            ->get()->result_object();

        $data = array();
        $i = 0;
        foreach ($data_kelas as $row) {
            if (!$row->nama_dosen) continue;
            
            $data[$i]['nama_kelas'] = $row->nama_kelas;
            $data[$i]['nama_matakuliah'] = $row->nama_matakuliah;
            $data[$i]['semester'] = $row->semester;
            $data[$i]['nama_dosen'] = $row->nama_dosen;

            $total = $this->db->select('*')
                ->from('kelas_mahasiswa as km')
                ->join('krs_detail as kd', 'kd.kode_krs_detail=km.kode_krs_detail', 'left')
                ->join('khs_detail as khd', 'khd.kode_krs_detail=kd.kode_krs_detail', 'left')
                ->where('kelas_id', $row->kelas_id)
                ->get()->num_rows();

            $data[$i]['total'] = $total;

            $sub_query = $this->db->select('grade, nilai_akhir, count(kd.kode_krs_detail) as jumlah')
                ->from('kelas_mahasiswa as km, sistem_penilaian_detail as spd')
                ->join('krs_detail as kd', 'kd.kode_krs_detail=km.kode_krs_detail', 'left')
                ->join('khs_detail as khd', 'khd.kode_krs_detail=kd.kode_krs_detail', 'left')
                ->where('kelas_id', $row->kelas_id)
                ->where('spd.kode_sistem_penilaian', '1')
                ->where('spd.nilai_minimum <= khd.nilai_akhir')
                ->where('spd.nilai_maksimum >= khd.nilai_akhir')
                ->group_by('grade')->get_compiled_select();

            $data[$i]['data'] = $this->db->select("jumlah, (jumlah/$total)*100 as persen")
                ->from("($sub_query) as m")
                ->join('sistem_penilaian_detail as spd', 'spd.grade=m.grade', 'right')
                ->where('spd.kode_sistem_penilaian', '1')
                ->get()->result_object();
            
            $i++;
        }
        return $data;
    }

    public function get_cetak_nilai_data($kelas_id) {
        $data['query1'] = $this->db->select('*,kl.semester as kls, ta.semester as tas, mt.kode_matakuliah as mtkm')
            ->from('kelas as kl')
            ->join('tahun_akademik as ta', 'ta.kode_tahun_akademik=kl.kode_tahun_akademik')
            ->join('matakuliah as mt', 'mt.id_matakuliah=kl.id_matakuliah')
            ->join('program_studi as ps', 'ps.kode_program_studi=kl.kode_program_studi')
            ->join('fakultas as pt', 'pt.kode_fakultas=ps.kode_fakultas')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kl.nama_kelas_id')
            ->join('mengajar as mj', 'mj.kelas_id=kl.kelas_id')
            ->join('dosen as ds', 'ds.kode_dosen=mj.kode_dosen')
            ->join('kelas_mahasiswa as km', 'km.kelas_id=kl.kelas_id')
            ->join('krs_detail as kde', 'kde.kode_krs_detail=km.kode_krs_detail')
            ->join('krs as rs', 'rs.kode_krs=kde.kode_krs')
            ->join('persentasi_nilai_dosen as pnd', 'pnd.kelas_id=kl.kelas_id')
            ->where('kl.kelas_id=', $kelas_id)
            ->get()->row();

        $data['query2'] = $this->db->select('mah.nim, nama_mahasiswa, nilai_harian, nilai_uts, nilai_uas, nilai_akhir,block.id as block_id,mbkm.id as mbkm_id')
            ->from('kelas_mahasiswa as km, sistem_penilaian_detail as spd')
            ->join('kelas', 'kelas.kelas_id=km.kelas_id')
            ->join('krs_detail as kd', 'kd.kode_krs_detail=km.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('mahasiswa as mah', 'krs.nim=mah.nim')
            ->join('block','block.nim = mah.nim','left')
            ->join('mbkm','mbkm.nim = mah.nim','left')
            ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->where('kelas.kelas_id', $kelas_id)
            ->where('spd.kode_sistem_penilaian', 1)
            ->group_by('nama_mahasiswa')
            ->order_by('substr(mah.nim,1,2) asc')
            ->order_by('substr(mah.nim,6,1) asc')
            ->order_by('substr(mah.nim,-4,4) asc')
            ->get()->result();

        $data['query3'] = $this->db->select('ds.nama_dosen as dosen_fakultas, ds.nik as nik_dosen_fakultas')
            ->from('kelas as kl')
            ->join('program_studi as ps', 'ps.kode_program_studi=kl.kode_program_studi')
            ->join('fakultas as pt', 'pt.kode_fakultas=ps.kode_fakultas')
            ->join('dosen as ds', 'ds.kode_dosen=pt.dekan')
            ->where('kl.kelas_id=', $kelas_id)
            ->get()->row();

        $data['query4'] = $this->db->select('*')
            ->from('sistem_penilaian_detail')
            ->where('kode_sistem_penilaian=', 1)
            ->order_by('bobot_nilai', 'desc')
            ->get()->result();
      
        $data['nama_dosen'] = $this->db->select('ps.singkatan_program_studi,status_nilai, validasi_nilai, validasi_dekan, nama_kelas, nama_matakuliah,kelas.kelas_id, mengajar_id, GROUP_CONCAT(nama_dosen SEPARATOR "/") as nama_dosen, GROUP_CONCAT(nik SEPARATOR "/") as nik, mak.kode_matakuliah')
            ->from('kelas')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->join('mengajar as meng', 'meng.kelas_id=kelas.kelas_id', 'left')
            ->join('dosen', 'meng.kode_dosen=dosen.kode_dosen', 'left')
            ->join('program_studi as ps', 'ps.kode_program_studi=kelas.kode_program_studi')
            ->where('kelas.kelas_id', $kelas_id)
            ->get()->row();
        
        return $data;
    }

    public function cari_mak_mahasiswa($nim) {
        $hasil = $this->db->select('*,kd.kode_krs_detail, mak.kode_matakuliah, max(nilai_akhir)')
            ->from('krs')
            ->join('krs_detail as kd', 'krs.kode_krs=kd.kode_krs')
            ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('matakuliah as mak', 'kd.id_matakuliah=mak.id_matakuliah')
            ->where('nim', $nim)
            ->group_by('mak.kode_matakuliah')
            ->order_by('mak.kode_matakuliah','ASC')
            ->order_by('nilai_akhir','DESC')
            ->get()->result();
        $mahasiswa = $this->db->where('nim', $nim)->get('mahasiswa')->row_object();
        
        return array('hasil' => $hasil, 'mahasiswa' => $mahasiswa);
    }

    public function hapus_mak($nim, $kode_matakuliah, $kode_krs_detail) {
        $total_sks = $this->db->select('*,kd.kode_krs_detail, mak.kode_matakuliah, max(nilai_akhir)')
            ->from('krs')
            ->join('krs_detail as kd', 'krs.kode_krs=kd.kode_krs')
            ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('matakuliah as mak', 'kd.id_matakuliah=mak.id_matakuliah')
            ->where('nim', $nim)
            ->group_by('mak.kode_matakuliah')
            ->order_by('mak.kode_matakuliah','ASC')
            ->order_by('nilai_akhir','DESC')
            ->get()->result();

        $cek_mak_pilihan = array('13','16','17','19','14','15');
        $his_pilihan = substr($kode_matakuliah,6,2);
        $sksn = 0;
        $sks_batal = substr($kode_matakuliah,4,1);
        foreach ($total_sks as $row) {
            $sksn = $sksn + substr($row->kode_matakuliah,4,1);
        }

        $hasil = $sksn - $sks_batal;
        if (in_array($his_pilihan, $cek_mak_pilihan)) {
            if ($hasil < 144) {
                return array('status' => false, 'message' => 'Tidak bisa melakukan penghapusan, SKSN akan menjadi kurang dari 144');
            } else {
                $this->catat_hapus_khs($kode_krs_detail, 'perubahan');
                $del = $this->db->where('kode_krs_detail', $kode_krs_detail)->delete('krs_detail');
                if ($del) {
                    return array('status' => true, 'message' => 'Matakuliah berhasil di hapus');
                } else {
                    return array('status' => false, 'message' => 'Matakuilah gagal di hapus');
                }
            }
        } else {
            return array('status' => false, 'message' => 'Tidak bisa melakukan penghapusan, Karena merupakan matakuliah wajib');
        }
    }

    public function get_all_tahun_akademik() {
        return $this->db->order_by('kode_tahun_akademik', 'DESC')->get('tahun_akademik')->result_object();
    }

    public function get_tahun_akademik_by_kode($kode) {
        return $this->db->where('kode_tahun_akademik', $kode)->get('tahun_akademik')->row_object();
    }

    public function get_program_studi_by_kode($kode) {
        return $this->db->where('kode_program_studi', $kode)->get('program_studi')->row_object();
    }

    public function update_khs_detail_field($kode_khs_detail, $field, $value) {
        $row = $this->db->where('kode_khs_detail', $kode_khs_detail)->get('khs_detail')->row();
        $lama = $row && isset($row->$field) ? $row->$field : null;
        $this->db->where('kode_khs_detail', $kode_khs_detail)->update('khs_detail', array($field => $value));
        if ($lama != $value) {
            log_aktivitas_nilai('update', $field, $lama, $value, 'perubahan', $kode_khs_detail);
        }
        return true;
    }

    public function set_tidak_berhak_status($kode_khs_detail, $status) {
        $row = $this->db->where('kode_khs_detail', $kode_khs_detail)->get('khs_detail')->row();
        $data = ($status == 2) ? array('tidak_berhak' => 'A', 'nilai_uas' => '0') : array('tidak_berhak' => 'N');
        $this->db->where('kode_khs_detail', $kode_khs_detail)->update('khs_detail', $data);
        $this->catat_diff_khs($row, $data, 'perubahan', $kode_khs_detail);
    }

    public function delete_khs_detail($kode_khs_detail) {
        $row = $this->db->where('kode_khs_detail', $kode_khs_detail)->get('khs_detail')->row();
        $this->db->where('kode_khs_detail', $kode_khs_detail)->update('khs_detail', array('deleted' => 1));
        if ($row) {
            log_aktivitas_nilai('soft_delete', 'nilai_harian,nilai_uts,nilai_uas,nilai_akhir', $this->nilai_json($row), null, 'perubahan', $kode_khs_detail);
        }
        return true;
    }

    public function restore_khs_detail($kode_khs_detail) {
        $this->db->where('kode_khs_detail', $kode_khs_detail)->update('khs_detail', array('deleted' => 0));
        log_aktivitas_nilai('restore', 'deleted', '1', '0', 'perubahan', $kode_khs_detail);
        return true;
    }

    public function get_id_matakuliah_by_kode_kurikulum($kode_matakuliah, $kode_nama_kurikulum) {
        $result = $this->db->select('mak.id_matakuliah')
            ->from('kurikulum as kur')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kur.id_matakuliah')
            ->where('kur.kode_nama_kurikulum', $kode_nama_kurikulum)
            ->where('mak.kode_matakuliah', $kode_matakuliah)
            ->get()->row_object();
        return $result ? $result->id_matakuliah : null;
    }

    public function get_khs_detail_by_nim_matakuliah($nim, $id_matakuliah) {
        return $this->db->select('kode_khs_detail')
            ->from('krs')
            ->join('krs_detail as krd', 'krs.kode_krs=krd.kode_krs')
            ->join('khs_detail as khd', 'khd.kode_krs_detail=krd.kode_krs_detail')
            ->where('krs.nim', $nim)
            ->where('krd.id_matakuliah', $id_matakuliah)
            ->get()->row();
    }

    public function update_khs_detail($kode_khs_detail, $data) {
        $row = $this->db->where('kode_khs_detail', $kode_khs_detail)->get('khs_detail')->row();
        $this->db->where('kode_khs_detail', $kode_khs_detail)->update('khs_detail', $data);
        $this->catat_diff_khs($row, $data, 'perubahan', $kode_khs_detail);
        return true;
    }

    public function get_krs_by_nim_ta($nim, $kode_tahun_akademik) {
        return $this->db->where(array('nim' => $nim, 'kode_tahun_akademik' => $kode_tahun_akademik))->get('krs')->row_object();
    }

    public function edit_khs_detail_full($kode_krs_detail, $nilai_harian, $nilai_uts, $nilai_uas, $nilai_akhir, $tidak_berhak) {
        $row = $this->db->where('kode_krs_detail', $kode_krs_detail)->get('khs_detail')->row();
        if (!$row) {
            // Baris khs_detail belum ada (yaim): buat dulu agar UPDATE tidak mengenai 0 baris
            $this->db->insert('khs_detail', array('kode_krs_detail' => $kode_krs_detail));
            $row = (object) array(
                'nilai_harian' => null,
                'nilai_uts' => null,
                'nilai_uas' => null,
                'nilai_akhir' => null,
            );
        }
        $data = array(
            'nilai_harian' => $nilai_harian,
            'nilai_uts' => $nilai_uts,
            'nilai_uas' => $nilai_uas,
            'nilai_akhir' => $nilai_akhir,
            'tidak_berhak' => $tidak_berhak
        );
        $this->db->where('kode_krs_detail', $kode_krs_detail)->update('khs_detail', $data);
        $this->catat_diff_khs($row, $data, 'perubahan', null, $kode_krs_detail);
        return true;
    }

    public function delete_krs_detail_cascade($kode_krs_detail) {
        $this->catat_hapus_khs($kode_krs_detail, 'perubahan');
        $this->db->where('kode_krs_detail', $kode_krs_detail)->delete('krs_detail');
        $this->db->where('kode_krs_detail', $kode_krs_detail)->delete('khs_detail');
    }

    private function nilai_json($row) {
        return array(
            'nilai_harian' => isset($row->nilai_harian) ? $row->nilai_harian : null,
            'nilai_uts' => isset($row->nilai_uts) ? $row->nilai_uts : null,
            'nilai_uas' => isset($row->nilai_uas) ? $row->nilai_uas : null,
            'nilai_akhir' => isset($row->nilai_akhir) ? $row->nilai_akhir : null,
        );
    }

    private function catat_diff_khs($row, $data, $sumber, $kode_khs_detail = null, $kode_krs_detail = null) {
        if (!$row) return;
        $lama = array();
        $baru = array();
        foreach (array('nilai_harian', 'nilai_uts', 'nilai_uas', 'nilai_akhir') as $field) {
            if (array_key_exists($field, $data)) {
                $l = isset($row->$field) ? $row->$field : null;
                if ($l != $data[$field]) {
                    $lama[$field] = $l;
                    $baru[$field] = $data[$field];
                }
            }
        }
        if (!empty($lama)) {
            log_aktivitas_nilai('update', array_keys($lama), $lama, $baru, $sumber, $kode_khs_detail, $kode_krs_detail);
        }
    }

    private function catat_hapus_khs($kode_krs_detail, $sumber) {
        $row = $this->db->where('kode_krs_detail', $kode_krs_detail)->get('khs_detail')->row();
        if (!$row) return;
        log_aktivitas_nilai('delete', 'nilai_harian,nilai_uts,nilai_uas,nilai_akhir', $this->nilai_json($row), null, $sumber, null, $kode_krs_detail);
    }

    public function get_user_by_username($username) {
        return $this->db->where('username', $username)->get('users')->row_object();
    }

    public function getAllMatakuliahByTahunAkademikAndJurusan($kode_tahun_akademik, $kode_jurusan_jenjang) {
        $sql = "SELECT DISTINCT matakuliah.id_matakuliah,matakuliah.kode_matakuliah, matakuliah.nama_matakuliah, substr(matakuliah.kode_matakuliah,6,1) as semester FROM krs ";
        $sql .= " INNER JOIN matakuliah ON krs.id_matakuliah=matakuliah.id_matakuliah";
        $sql .= " WHERE krs.kode_tahun_akademik=? AND substr(krs.nim,3,3)=? ";
        $sql .= " ORDER BY substr(matakuliah.kode_matakuliah,6,1), right(matakuliah.kode_matakuliah,4), matakuliah.nama_matakuliah ";
        return $this->db->query($sql, array($kode_tahun_akademik, $kode_jurusan_jenjang))->result();
    }
}
?>
