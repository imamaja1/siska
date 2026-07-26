<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PerkuliahanService extends MY_Service {

    public function getProgramStudi() {
        return $this->db->get('program_studi')->result_object();
    }

    public function get_rekap_status_perkuliahan($kode_program_studi, $kode_tahun_akademik) {
        $prodi = $this->db->select('*')
                ->from('program_studi ps')
                ->join('jurusan as jur','ps.id_jurusan=jur.id_jurusan','left')
                ->join('jenjang as jen','ps.id_jenjang=jen.id_jenjang','left')
                ->where('ps.kode_program_studi', $kode_program_studi)
                ->get()->row_object();
                
        $tahun = $this->db->select('mid(tahun_akademik,3,2) as tahun_angkatan, mid(tahun_akademik,1,4) as angkatan')
                ->from('tahun_akademik')
                ->order_by('tahun_akademik','DESC')
                ->group_by('tahun_akademik')
                ->limit(7)
                ->get()->result_object();
                
        $i=0;
        $data = array();
        foreach ($tahun as $row){
                $data[$i]['angkatan'] = $row->angkatan;
                $data[$i]['total'] = $this->db->select('*')
                        ->from('status_perkuliahan as sp')
                        ->join('mahasiswa as mah','sp.nim=mah.nim')
                        ->where('kode_tahun_akademik', $kode_tahun_akademik)
                        ->where('pengumpulan_krs', '1')
                        ->where('mah.program_studi_kode', $kode_program_studi)
                        ->where('mid(sp.nim,1,2)', $row->tahun_angkatan)
                        ->get()->num_rows();
                        
                $data[$i]['laki'] = $this->db->select('*')
                        ->from('status_perkuliahan as sp')
                        ->join('mahasiswa as mah','sp.nim=mah.nim')
                        ->where('kode_tahun_akademik', $kode_tahun_akademik)
                        ->where('pengumpulan_krs', '1')
                        ->where('mah.program_studi_kode', $kode_program_studi)
                        ->where('mid(sp.nim,1,2)', $row->tahun_angkatan)
                        ->where('jenis_kelamin', 'L')
                        ->get()->num_rows();
                        
                $data[$i]['perempuan'] = $this->db->select('*')
                        ->from('status_perkuliahan as sp')
                        ->join('mahasiswa as mah','sp.nim=mah.nim')
                        ->where('kode_tahun_akademik', $kode_tahun_akademik)
                        ->where('pengumpulan_krs', '1')
                        ->where('mah.program_studi_kode', $kode_program_studi)
                        ->where('mid(sp.nim,1,2)', $row->tahun_angkatan)
                        ->where('jenis_kelamin', 'P')
                        ->get()->num_rows();

            $i++;
        }
        
        return array('data' => $data, 'prodi' => $prodi);
    }

    public function get_list_rekap_status_perkuliahan($angkatan, $kode_program_studi, $kode_tahun_akademik) {
        $angkatan = substr($angkatan,-2,2);
        
        $mk_skripsi = get_kode_matakuliah_skripsi();
        $mk_kkp = get_kode_matakuliah_kkp();
        
        $normal = $this->db->select('mah.nim, nama_mahasiswa, jenis_kelamin, (sum(sks_teori) + sum(sks_praktek) + sum(sks_praktikum)) as total_sks, sum(sks_praktikum) as praktikum, "" as skripsi, "" as kkp')
                ->from('krs')
                ->join('krs_detail as kd','kd.kode_krs=krs.kode_krs')
                ->join('mahasiswa as mah','krs.nim=mah.nim')
                ->join('matakuliah as mak','mak.id_matakuliah=kd.id_matakuliah')
                ->join('status_perkuliahan as sp',"krs.nim=sp.nim AND sp.kode_tahun_akademik=".$this->db->escape($kode_tahun_akademik))
                ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
                ->where('mid(mah.nim,1,2)', $angkatan)
                ->where('sp.pengumpulan_krs', '1')
                ->where('mah.program_studi_kode', $kode_program_studi)
                ->where_not_in('kd.status', ['K'])
                ->where_not_in('krs.semester', ['K'])
                ->group_by('mah.nim')
                ->get_compiled_select();
                
        $skripsi =  $this->db->select('mah.nim, nama_mahasiswa, jenis_kelamin, "" as total_sks, "" as praktikum, (sum(sks_teori) + sum(sks_praktek)) as skripsi, "" as kkp')
                ->from('krs')
                ->join('krs_detail as kd','kd.kode_krs=krs.kode_krs')
                ->join('mahasiswa as mah','krs.nim=mah.nim')
                ->join('matakuliah as mak','mak.id_matakuliah=kd.id_matakuliah')
                ->join('status_perkuliahan as sp',"krs.nim=sp.nim AND sp.kode_tahun_akademik=".$this->db->escape($kode_tahun_akademik))
                ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
                ->where('mid(mah.nim,1,2)', $angkatan)
                ->where('sp.pengumpulan_krs', '1')
                ->where('mah.program_studi_kode', $kode_program_studi)
                ->where_not_in('kd.status', ['K'])
                ->where_in('mak.kode_matakuliah', $mk_skripsi)
                ->group_by('mah.nim')
                ->get_compiled_select();

        $kkp =  $this->db->select('mah.nim, nama_mahasiswa, jenis_kelamin, "" as total_sks, "" as praktikum, "" as skripsi, (sum(sks_teori) + sum(sks_praktek)) as kkp')
                ->from('krs')
                ->join('krs_detail as kd','kd.kode_krs=krs.kode_krs')
                ->join('mahasiswa as mah','krs.nim=mah.nim')
                ->join('matakuliah as mak','mak.id_matakuliah=kd.id_matakuliah')
                ->join('status_perkuliahan as sp',"krs.nim=sp.nim AND sp.kode_tahun_akademik=".$this->db->escape($kode_tahun_akademik))
                ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
                ->where('mid(mah.nim,1,2)', $angkatan)
                ->where('sp.pengumpulan_krs', '1')
                ->where('mah.program_studi_kode', $kode_program_studi)
                ->where_not_in('kd.status', ['K'])
                ->where_in('mak.kode_matakuliah', $mk_kkp)
                ->group_by('mah.nim')
                ->get_compiled_select();

        $data = $this->db->query("select nim, nama_mahasiswa, jenis_kelamin, sum(total_sks) as total_sks, sum(praktikum) as praktikum, sum(skripsi) as skripsi, sum(kkp) as kkp from (
                $normal UNION $skripsi UNION $kkp 
            ) as m group by nim")->result_object();
            
        $prodi = $this->db->select('*')
                ->from('program_studi ps')
                ->join('jurusan as jur','ps.id_jurusan=jur.id_jurusan','left')
                ->join('jenjang as jen','ps.id_jenjang=jen.id_jenjang','left')
                ->where('ps.kode_program_studi', $kode_program_studi)
                ->get()->row_object();
                
        return array('data' => $data, 'prodi' => $prodi);
    }
    
    public function get_rekap_pembayaran_sks($kode_tahun_akademik, $skripsi = false) {
        $kkp_skripsi = get_kode_matakuliah_skripsi();
        
        $this->db->select('pengumpulan_krs,kode_status_perkuliahan,pembayaran_spp, pembayaran_sks, pembayaran_lab, krs.semester,mah.nim, nama_mahasiswa, jenis_kelamin, (sum(sks_teori) + sum(sks_praktek)) as teori, sum(sks_praktikum) as praktikum')
                ->from('krs')
                ->join('krs_detail as kd','kd.kode_krs=krs.kode_krs')
                ->join('mahasiswa as mah','krs.nim=mah.nim')
                ->join('matakuliah as mak','mak.id_matakuliah=kd.id_matakuliah')
                ->join('status_perkuliahan as sp',"krs.nim=sp.nim AND sp.kode_tahun_akademik=".$this->db->escape($kode_tahun_akademik))
                ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
                ->where_not_in('kd.status', ['K']);
                
        if ($skripsi) {
            $this->db->where_in('mak.kode_matakuliah', $kkp_skripsi);
        } else {
            $this->db->where_not_in('mak.kode_matakuliah', $kkp_skripsi);
        }
        
        $this->db->group_by('mah.nim');
        return $this->db->get()->result();
    }
    
    public function toggle_pengumpulan_krs($kode_status_perkuliahan) {
        $sp = $this->db->where('kode_status_perkuliahan', $kode_status_perkuliahan)->get('status_perkuliahan')->row_object();
        $val = ($sp->pengumpulan_krs == '0') ? '1' : '0';
        
        $ubah = $this->db->where('kode_status_perkuliahan', $kode_status_perkuliahan)
                ->update('status_perkuliahan', array('pengumpulan_krs'=> $val));
                
        return array('status' => $ubah ? 'true' : 'false', 'val' => $val);
    }
    
    public function simpan_konversi($nim, $id_matakuliah, $nilai_akhir) {
        $tahun_angkatan = substr($nim, 0, 2);
        $this->load->model('jurusan/m_tahun_akademik');
        $tahun = $this->m_tahun_akademik->get_semester();
        $kode_tahun_akademik = $tahun->kode_tahun_akademik;

        $this->load->model('akademik/Krs_model');
        $data_krs = array(
            'kode_tahun_akademik' => $kode_tahun_akademik,
            'nim' => $nim,
            'semester' => 'K',
        );
        $kode_krs = $this->Krs_model->simpan_krs($data_krs);
        
        if ($kode_krs !== null) {
            $this->load->model('akademik/Khs_model');
            $data_khs = array(
                'kode_krs' => $kode_krs,
            );
            $this->Khs_model->simpan_khs($data_khs);
            
            $this->load->model('akademik/Krs_detail_model');
            $i = 0;
            $data_konversi = [];
            foreach ($id_matakuliah as $key => $value) {
                $data_konversi[$i]['id_matakuliah'] = $value;
                $data_konversi[$i]['nilai_harian'] = $nilai_akhir[$i] * 0.2;
                $data_konversi[$i]['nilai_uts'] = $nilai_akhir[$i] * 0.3;
                $data_konversi[$i]['nilai_uas'] = $nilai_akhir[$i] * 0.5;
                $data_konversi[$i]['nilai_akhir'] = $nilai_akhir[$i];
                $i++;
            }
            foreach ($data_konversi as $row) {
                $data_krs_detail = array(
                    'kode_krs' => $kode_krs,
                    'id_matakuliah' => $row['id_matakuliah'],
                );

                $kode_krs_detail = $this->Krs_detail_model->simpan_krs($data_krs_detail);
                $data_khs_detail = array(
                    'kode_krs_detail' => $kode_krs_detail,
                    'nilai_harian' => $row['nilai_harian'],
                    'nilai_uts' => $row['nilai_uts'],
                    'nilai_uas' => $row['nilai_uas'],
                    'nilai_akhir' => $row['nilai_akhir'],
                );

                $this->Krs_detail_model->simpan_khs($data_khs_detail);
            }
            return true;
        }
        return false;
    }

    public function get_edit_konversi_data($nim) {
        $this->load->model('jurusan/m_tahun_akademik');
        $this->load->model('akademik/Krs_model');
        $this->load->model('jurusan/program_studi/Ketua_jurusan_model');
        $this->load->model('jurusan/kurikulum/m_data_kurikulum');
        
        $tahun_akademik = $this->m_tahun_akademik->get_aktif();
        $kode_krs = $this->Krs_model->get_krs_konversi($nim, $tahun_akademik);

        $program_studi = get_kode_prodi($nim);
        $data_penilaian = sistem_penilaian($nim);
        $data_krs = $this->Krs_model->khs($kode_krs);

        $khs = [];
        $khs['sksn'] = 0;
        $khs['total_sks'] = 0;
        $khs['total_bobot'] = 0;
        $i = 0;
        foreach ($data_krs as $row) {
            $khs['nim'] = $row->nim;
            $khs['kode_krs'] = $row->kode_krs;
            $khs['nama_mahasiswa'] = $row->nama_mahasiswa;
            $khs['tahun_akademik'] = $this->m_tahun_akademik->get_byid($row->kode_tahun_akademik);
            $khs['semester'] = $row->semester;
            $khs['kurikulum'] = $data_penilaian[0]['nama_kurikulum'];
            $khs['data_nilai'][$i]['kode_krs_detail'] = $row->kode_krs_detail;
            $khs['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
            $khs['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
            $khs['data_nilai'][$i]['nilai_harian'] = $row->nilai_harian;
            $khs['data_nilai'][$i]['nilai_uts'] = $row->nilai_uts;
            $khs['data_nilai'][$i]['nilai_uas'] = $row->nilai_uas;
            $khs['data_nilai'][$i]['sks'] = $row->sks_teori + $row->sks_praktek + $row->sks_praktikum;
            $khs['data_nilai'][$i]['tb'] = $row->tidak_berhak;
            $nilai_akhir = $row->nilai_akhir* 1;
            $khs['data_nilai'][$i]['nilai_akhir'] = $nilai_akhir;
            foreach ($data_penilaian as $key) {
                if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
                    $khs['data_nilai'][$i]['grade'] = $key['grade'];
                    $khs['data_nilai'][$i]['sksn'] = $key['bobot_nilai'] * ($row->sks_teori + $row->sks_praktek + $row->sks_praktikum);
                }
            }
            $khs['sksn'] = $khs['sksn'] + $khs['data_nilai'][$i]['sks'];
            $khs['kaprodi'] = $this->Ketua_jurusan_model->get_kaprodi($program_studi->kode_program_studi);

            $i++;
        }

        $matakuliah = $this->m_data_kurikulum->get_matakuliah_bynim($nim);
        return array('khs' => $khs, 'matakuliah' => $matakuliah, 'prodi' => $program_studi);
    }

    public function simpan_tambah_konversi($kode_krs, $id_matakuliah, $nilai_akhir) {
        $this->load->model('akademik/Krs_detail_model');
        $nilai_harian = $nilai_akhir * 0.2;
        $nilai_uts =  $nilai_akhir * 0.3;
        $nilai_uas =  $nilai_akhir * 0.5;

        $data_krs = array(
            'kode_krs' => $kode_krs,
            'id_matakuliah' => $id_matakuliah,
        );
        $id = $this->Krs_detail_model->simpan_krs($data_krs);

        if ($id !== null) {
            $data_khs = array(
                'nilai_harian' => $nilai_harian,
                'nilai_uts' => $nilai_uts,
                'nilai_uas' => $nilai_uas,
                'nilai_akhir' => $nilai_akhir,
                'kode_krs_detail' => $id,
            );
            return $this->Krs_detail_model->simpan_khs($data_khs);
        }
        return false;
    }

    public function ubah_krs_nilai_konversi($input) {
        $nilai_harian = $input['edit_nilai_akhir'] * 0.2;
        $nilai_uts = $input['edit_nilai_akhir'] * 0.3;
        $nilai_uas = $input['edit_nilai_akhir'] * 0.5;
        $nilai_akhir = $input['edit_nilai_akhir'];
        $tidak_berhak = $input['tidak_berhak'];

        if ($input['action'] === 'edit') {
            $this->db->set('nilai_harian', $nilai_harian)
                ->set('nilai_uts', $nilai_uts)
                ->set('nilai_uas', $nilai_uas)
                ->set('nilai_akhir', $nilai_akhir)
                ->set('tidak_berhak', $tidak_berhak)
                ->where('kode_krs_detail', $input['kode_krs_detail'])
                ->update('khs_detail');
        } else if ($input['action'] === 'delete') {
            $this->db->where('kode_krs_detail', $input['kode_krs_detail'])->delete('krs_detail');
        } else if ($input['action'] === 'restore') {
            $this->db->set('deleted', 0)->where('kode_khs_detail', $input['kode_khs_detail'])->update('khs_detail');
        }
        return $input;
    }
    
    public function get_kompetensi($prodi) {
        $this->load->model('jurusan/program_studi/Kompetensi_model');
        return $this->Kompetensi_model->get_kompetensi($prodi);
    }

    public function get_kompetensi_jurusan_mahasiswa($kata_kunci) {
        $this->load->model('jurusan/program_studi/Kompetensi_model');
        return $this->Kompetensi_model->get_kompetensi_jurusan_mahasiswa($kata_kunci);
    }

    public function ubah_kompetensi_mahasiswa($data, $kode_kompetensi_mahasiswa) {
        $this->load->model('jurusan/program_studi/Kompetensi_model');
        return $this->Kompetensi_model->ubah_kompetensi_mahasiswa($data, $kode_kompetensi_mahasiswa);
    }
}
?>
