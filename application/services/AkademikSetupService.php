<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AkademikSetupService extends MY_Service {

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'jurusan/m_tahun_akademik',
            'jurusan/m_periode_krs',
            'jurusan/program_studi/nama_jurusan_model'
        ));
    }

    /**
     * Normalisasi string tanggal menjadi format Y-m-d, atau null jika kosong, atau false jika tidak valid.
     */
    private function normalizeDate($val) {
        $val = is_string($val) ? trim($val) : '';
        if ($val === '' || $val === '0000-00-00') {
            return null;
        }
        $clean = str_replace('/', '-', $val);
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $clean) || strtotime($clean) === false) {
            return false;
        }
        return date('Y-m-d', strtotime($clean));
    }

    /**
     * Validasi dan ekstrak data periode KRS dari input form.
     */
    private function validateAndExtractPeriodeKrs($post_data) {
        $tgl_awal_krs = $this->normalizeDate(isset($post_data['tgl_awal_krs']) ? $post_data['tgl_awal_krs'] : '');
        $tgl_batas_krs = $this->normalizeDate(isset($post_data['tgl_batas_krs']) ? $post_data['tgl_batas_krs'] : '');

        if ($tgl_awal_krs === false || $tgl_batas_krs === false) {
            return array('valid' => false, 'msg' => 'Format tanggal KRS tidak valid');
        }
        if ($tgl_awal_krs !== null && $tgl_batas_krs !== null && $tgl_batas_krs < $tgl_awal_krs) {
            return array('valid' => false, 'msg' => 'Tanggal batas akhir KRS tidak boleh lebih awal dari tanggal mulai KRS');
        }

        $status_krs = (isset($post_data['status_krs']) && $post_data['status_krs'] === 'A') ? 'A' : 'N';
        $updated_by = $this->session->userdata('id') ? (int) $this->session->userdata('id') : null;

        return array(
            'valid' => true,
            'data'  => array(
                'tgl_awal_krs'  => $tgl_awal_krs,
                'tgl_batas_krs' => $tgl_batas_krs,
                'status_krs'    => $status_krs,
                'updated_by'    => $updated_by,
            ),
        );
    }

    // --- TAHUN AKADEMIK LOGIC ---

    public function getTahunAkademikLengkap() {
        return $this->db->select('ta.*, pk.id_periode_krs, pk.tgl_awal_krs, pk.tgl_batas_krs, COALESCE(pk.status_krs, "N") as status_krs')
            ->from('tahun_akademik as ta')
            ->join('periode_krs as pk', 'pk.kode_tahun_akademik = ta.kode_tahun_akademik', 'left')
            ->order_by('ta.tahun_akademik', 'DESC')
            ->order_by('ta.semester', 'DESC')
            ->get()->result();
    }

    public function getTahunAkademikById($id) {
        return $this->db->select('ta.*, pk.id_periode_krs, pk.tgl_awal_krs, pk.tgl_batas_krs, COALESCE(pk.status_krs, "N") as status_krs')
            ->from('tahun_akademik as ta')
            ->join('periode_krs as pk', 'pk.kode_tahun_akademik = ta.kode_tahun_akademik', 'left')
            ->where('ta.kode_tahun_akademik', $id)
            ->get()->row_object();
    }

    public function simpanTahunAkademik($post_data) {
        $cek = $this->validateAndExtractPeriodeKrs($post_data);
        if (!$cek['valid']) {
            return array('status' => false, 'msg' => $cek['msg']);
        }

        $data_tahun = array(
            'tahun_akademik' => $post_data['tahun_akademik'],
            'tanggal_mulai' => $post_data['tanggal_mulai'],
            'tanggal_berakhir' => $post_data['tanggal_berakhir'],
            'semester' => $post_data['semester'],
            'status' => 'N',
        );

        $this->db->trans_start();
        $this->m_tahun_akademik->simpan($data_tahun);
        $new_id = $this->db->insert_id();
        if ($new_id) {
            $this->m_periode_krs->upsert_by_ta($new_id, $cek['data']);
        }
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            return array('status' => true, 'msg' => 'Data berhasil disimpan');
        } else {
            return array('status' => false, 'msg' => 'Data gagal disimpan');
        }
    }

    public function ubahTahunAkademik($id, $post_data) {
        $cek = $this->validateAndExtractPeriodeKrs($post_data);
        if (!$cek['valid']) {
            return array('status' => false, 'msg' => $cek['msg']);
        }

        $data_tahun = array(
            'tahun_akademik' => $post_data['tahun_akademik'],
            'tanggal_mulai' => $post_data['tanggal_mulai'],
            'tanggal_berakhir' => $post_data['tanggal_berakhir'],
            'semester' => $post_data['semester'],
        );

        $this->db->trans_start();
        $this->m_tahun_akademik->ubah($data_tahun, $id);
        $this->m_periode_krs->upsert_by_ta($id, $cek['data']);
        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            return array('status' => true, 'msg' => 'Data berhasil diubah');
        } else {
            return array('status' => false, 'msg' => 'Data gagal diubah');
        }
    }

    public function ubahStatusKrsTahunAkademik($id, $status) {
        $ta = $this->m_tahun_akademik->get_tahun_akademik_by_kode($id);
        if (!$ta) {
            return array('status' => false, 'msg' => 'Tahun akademik tidak ditemukan');
        }
        $status_clean = ($status === 'A') ? 'A' : 'N';
        $updated_by = $this->session->userdata('id') ? (int) $this->session->userdata('id') : null;
        $ok = $this->m_periode_krs->ubah_status($id, $status_clean, $updated_by);
        return array(
            'status' => (bool) $ok,
            'msg'    => $ok ? 'Status KRS berhasil diubah' : 'Status KRS gagal diubah',
        );
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
