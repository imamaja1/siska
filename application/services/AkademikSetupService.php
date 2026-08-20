<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AkademikSetupService extends MY_Service {

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/nama_jurusan_model'
        ));
    }

    // --- TAHUN AKADEMIK LOGIC ---

    public function getTahunAkademikLengkap() {
        return $this->m_tahun_akademik->get();
    }

    public function getTahunAkademikById($id) {
        return $this->db->where('kode_tahun_akademik', $id)->get('tahun_akademik')->row_object();
    }

    public function simpanTahunAkademik($post_data) {
        $data_tahun = array(
            'tahun_akademik' => $post_data['tahun_akademik'],
            'tanggal_mulai' => $post_data['tanggal_mulai'],
            'tanggal_berakhir' => $post_data['tanggal_berakhir'],
            'semester' => $post_data['semester'],
            'status' => 'N',
        );
        if ($this->m_tahun_akademik->simpan($data_tahun)) {
            return array('status' => true, 'msg' => 'Data berhasil disimpan');
        } else {
            return array('status' => false, 'msg' => 'Data gagal disimpan');
        }
    }

    public function ubahTahunAkademik($id, $post_data) {
        $data_tahun = array(
            'tahun_akademik' => $post_data['tahun_akademik'],
            'tanggal_mulai' => $post_data['tanggal_mulai'],
            'tanggal_berakhir' => $post_data['tanggal_berakhir'],
            'semester' => $post_data['semester'],
        );
        if ($this->m_tahun_akademik->ubah($data_tahun, $id)) {
            return array('status' => true, 'msg' => 'Data berhasil diubah');
        } else {
            return array('status' => false, 'msg' => 'Data gagal diubah');
        }
    }

    public function hapusTahunAkademik($id) {
        if ($this->m_tahun_akademik->hapus($id)) {
            return array('status' => true, 'msg' => 'Data berhasil dihapus');
        } else {
            return array('status' => false, 'msg' => 'Data gagal dihapus');
        }
    }

    public function ubahStatusTahunAkademik($id, $status) {
        $this->db->trans_start();
        
        if ($status == 'A') {
            $this->db->set('status', 'N')->update('tahun_akademik', array('status' => 'N'));
        }
        
        $this->m_tahun_akademik->ubah(array('status' => $status), $id);
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status()) {
            return array('status' => true, 'msg' => 'Status berhasil diubah');
        } else {
            return array('status' => false, 'msg' => 'Status gagal diubah');
        }
    }

    // --- STUDENT BODY LOGIC ---

    public function getTahunAngkatan() {
        return $this->m_tahun_akademik->get_tahun();
    }

    public function getProgramStudi() {
        return $this->nama_jurusan_model->get();
    }

    public function getDataMahasiswaBody($kode_program_studi, $angkatan) {
        $jurusan = $this->nama_jurusan_model->get_kode_by_program_studi($kode_program_studi);

        return $this->db->select('*')
            ->from('mahasiswa as mah')
            ->where('substr(nim,1,2)', $angkatan)
            ->where('substr(nim,3,2)', $jurusan->kode_jurusan)
            ->where('substr(nim,5,1)', $jurusan->kode_jenjang)
            ->get()->result();
    }

    public function getIpMahasiswa($nim) {
        $data_krs = $this->db->select('*')
            ->from('krs')
            ->join('krs_detail as kd','krs.kode_krs=kd.kode_krs')
            ->where_not_in('status','K')
            ->where_not_in('semester','K')
            ->where('nim',$nim)
            ->group_by('krs.kode_krs')
            ->get()->result();
            
        $ip = array();
        foreach ($data_krs as $row) {
            $sistem_penilaian = stup_grade(kode_nama_kurikulum($nim), $row->semester) ?: sistem_penilaian($nim);

            $krs = $this->db->select('(sks_teori+sks_praktek+sks_praktikum) as sks, nilai_akhir')
                ->from('krs')
                ->join('krs_detail as kd','krs.kode_krs=kd.kode_krs')
                ->join('khs_detail as khd','kd.kode_krs_detail=khd.kode_krs_detail')
                ->join('matakuliah as mak','kd.id_matakuliah=mak.id_matakuliah')
                ->where('krs.kode_krs',$row->kode_krs)
                ->get()->result();
                
            $total_sksn = 0;
            $total_sks = 0;
            
            foreach ($krs as $item) {
                foreach ($sistem_penilaian as $val) {
                    if ($item->nilai_akhir >= $val['nilai_minimum'] && $item->nilai_akhir <= $val['nilai_maksimum']) {
                        $total_sksn = $total_sksn + ($val['bobot_nilai']*$item->sks);
                    }
                }
                $total_sks = $total_sks + $item->sks;
            }
            
            if ($total_sks == 0) {
                $ip[] = 0;
            } else {
                $ip[] = number_format($total_sksn/$total_sks, 2);
            }
        }

        $param = 0;
        $ket = array();
        foreach ($ip as $value) {
            if ($value < 2.0) {
                $param = $param + 1;
                $ket[] = $param;
            } else {
                $param = 0;
                $ket[] = $param;
            }
        }

        $keterangan = array();
        foreach ($ket as $val) {
            if ($val == 2) {
                $keterangan[] = "SP1";
            } elseif ($val == 3) {
                $keterangan[] = "SP2";
            } elseif ($val == 4) {
                $keterangan[] = "SP4";
            } elseif ($val == 5) {
                $keterangan[] = "DO";
            } else {
                $keterangan[] = "-";
            }
        }
        return $keterangan;
    }
}
