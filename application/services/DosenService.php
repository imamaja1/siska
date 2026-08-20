<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DosenService extends MY_Service {

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'jurusan/m_dosen',
            'jurusan/program_studi/nama_jurusan_model',
        ));
        $this->load->library(array('pagination', 'form_validation'));
    }

    public function getHomebase() {
        return $this->nama_jurusan_model->get_homebase();
    }

    public function getDosenCount() {
        return count($this->m_dosen->get_count());
    }

    public function getDosenPagination($limit, $offset) {
        return $this->m_dosen->get_pagination($limit, $offset);
    }

    public function getDosenById($kode_dosen) {
        return $this->m_dosen->get_dosen_by_kode($kode_dosen);
    }

    public function getSearchPagination($kata_kunci) {
        return $this->m_dosen->get_pagination_search($kata_kunci);
    }

    public function searchByKodeDosen($kode_dosen) {
        return $this->m_dosen->search_by_kode_dosen($kode_dosen);
    }

    public function simpanDosen($post_data) {
        $this->form_validation->set_rules('nik', 'nik', 'required', array('required' => 'Field NIK harus diisi'));
        $this->form_validation->set_rules('nama_dosen', 'nama_dosen', 'required', array('required' => 'Field Nama Dosen harus diisi'));
        $this->form_validation->set_rules('field_studi', 'field_studi', 'required', array('required' => 'Field Studi harus diisi'));
        $this->form_validation->set_rules('alumni', 'alumni', 'required', array('required' => 'Field Alumni harus diisi'));
        $this->form_validation->set_rules('status_dosen', 'status_dosen', 'required', array('required' => 'Field Status Dosen harus dipilih'));
        $this->form_validation->set_rules('homebase', 'homebase', 'required', array('required' => 'Field Homebase harus dipilih'));
        $this->form_validation->set_rules('alamat_email', 'alamat_email', 'required|valid_email', array('required' => 'Field Alamat Email harus diisi', 'valid_email' => 'Email tidak valid'));
        $this->form_validation->set_rules('password', 'password', 'required|min_length[8]|max_length[20]', array('required' => 'Field Password harus diisi', 'min_length' => 'Field Password minimal 6 Karakter', 'max_length' => 'Field Password Maksimal 8 Karakter'));
        $this->form_validation->set_rules('ulangi_password', 'ulangi_password', 'required|matches[password]', array('required' => 'Field Ulangi Password harus diisi', 'matches' => 'Password tidak cocok'));
        $this->form_validation->set_rules('status_login', 'status_login', 'required', array('required' => 'Field Status Login harus dipilih'));

        if ($this->form_validation->run() == false) {
            return array('status' => false);
        }

        $this->db->trans_start();
        
        $data_dosen = array(
            'nik' => $post_data['nik'],
            'nama_dosen' => $post_data['nama_dosen'],
            'field_studi' => $post_data['field_studi'],
            'alumni' => $post_data['alumni'],
            'status_dosen' => $post_data['status_dosen'],
            'homebase' => $post_data['homebase'],
            'alamat_email' => $post_data['alamat_email'],
            'sandi_pengguna' => md5($post_data['password']),
            'status_login' => $post_data['status_login'],
            'no_telp' => $post_data['no_telp']
        );
        $this->m_dosen->add($data_dosen);
        
        $data_arr = array(
            'key_ref' => $this->db->insert_id(),
            'role' => '3',
            'name' => $post_data['nama_dosen'],
            'first_name' => $post_data['nama_dosen'],
            'email' => $post_data['alamat_email'],
            'username' => $post_data['alamat_email'],
            'active' => '1',
            'password' => password_hash($post_data['password'], PASSWORD_BCRYPT),
        );
        $this->db->insert('users', $data_arr);
        $new_id = $this->db->insert_id();
        $this->db->where('id_user', $new_id)->update('users', array('id' => $new_id));
        
        $this->db->trans_complete();

        return array('status' => $this->db->trans_status());
    }

    public function ubahPassword($post_data) {
        $kode_dosen = $post_data['kode_dosen_password'];
        $this->form_validation->set_rules('password', 'password', 'required|min_length[8]|max_length[20]', array('required' => 'Field Password harus diisi', 'min_length' => 'Field Password minimal 6 Karakter', 'max_length' => 'Field Password Maksimal 8 Karakter'));
        $this->form_validation->set_rules('ulangi_password', 'ulangi_password', 'required|matches[password]', array('required' => 'Field Ulangi Password harus diisi', 'matches' => 'Password tidak cocok'));

        if ($this->form_validation->run() == false) {
            return array('status' => false);
        }

        $dosen = array(
            'sandi_pengguna' => md5($post_data['password'])
        );
        
        $this->db->trans_start();
        $this->m_dosen->ubah_sandi($kode_dosen, $dosen);
        
        $data_arr = array(
            'password' => password_hash($post_data['password'], PASSWORD_BCRYPT),
        );
        $this->db->where('key_ref', $kode_dosen)->where('role', '3')->update('users', $data_arr);
        
        $this->db->trans_complete();
        return array('status' => $this->db->trans_status());
    }

    public function ubahDosen($post_data) {
        $kode_dosen = $post_data['kode_dosen_biodata'];

        $this->form_validation->set_rules('nik', 'nik', 'required', array('required' => 'Field NIK harus diisi'));
        $this->form_validation->set_rules('nama_dosen', 'nama_dosen', 'required', array('required' => 'Field Nama Dosen harus diisi'));
        $this->form_validation->set_rules('field_studi', 'field_studi', 'required', array('required' => 'Field Studi harus diisi'));
        $this->form_validation->set_rules('alumni', 'alumni', 'required', array('required' => 'Field Alumni harus diisi'));
        $this->form_validation->set_rules('status_dosen', 'status_dosen', 'required', array('required' => 'Field Status Dosen harus dipilih'));
        $this->form_validation->set_rules('homebase', 'homebase', 'required', array('required' => 'Field Homebase harus dipilih'));
        $this->form_validation->set_rules('alamat_email', 'alamat_email', 'required|valid_email', array('required' => 'Field Alamat Email harus diisi', 'valid_email' => 'Email tidak valid'));
        $this->form_validation->set_rules('status_login', 'status_login', 'required', array('required' => 'Field Status Login harus dipilih'));

        if ($this->form_validation->run() == false) {
            return array('status' => false);
        }

        $this->db->trans_start();
        $data_dosen = array(
            'nik' => $post_data['nik'],
            'nama_dosen' => $post_data['nama_dosen'],
            'field_studi' => $post_data['field_studi'],
            'alumni' => $post_data['alumni'],
            'status_dosen' => $post_data['status_dosen'],
            'homebase' => $post_data['homebase'],
            'alamat_email' => $post_data['alamat_email'],
            'status_login' => $post_data['status_login'],
            'no_telp' => $post_data['no_telp']
        );
        $this->m_dosen->update($kode_dosen, $data_dosen);

        $data_arr = array(
            'name' => $post_data['nama_dosen'],
            'first_name' => $post_data['nama_dosen'],
            'email' => $post_data['alamat_email'],
            'username' => $post_data['alamat_email'],
        );
        $this->db->where('key_ref', $kode_dosen)->where('role', '3')->update('users', $data_arr);
        $this->db->trans_complete();

        return array('status' => $this->db->trans_status());
    }

    public function hapusDosen($kode_dosen) {
        // Save current debug state and disable it temporarily to prevent fatal error screen
        $db_debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;
        
        $this->db->trans_start();
        $this->m_dosen->hapus($kode_dosen);
        $this->db->where('key_ref', $kode_dosen)->where('role', '3')->delete('users');
        $this->db->trans_complete();

        $error = $this->db->error();
        // Restore debug state
        $this->db->db_debug = $db_debug;

        if ($error['code'] == 1451) {
            return array('status' => false, 'msg' => 'Data tidak dapat dihapus!');
        }

        return array('status' => $this->db->trans_status(), 'msg' => 'Dosen berhasil dihapus');
    }

    public function generateSandi($kode_dosen) {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$+-*&?';
        $password = array();
        $alpha_length = strlen($alphabet) - 1;
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alpha_length);
            $password[] = $alphabet[$n];
        }
        $string = implode($password);

        $password_baru = md5($string);
        $password_api = password_hash($string, PASSWORD_BCRYPT);
        
        $this->db->trans_start();
        $sukses = $this->m_dosen->update($kode_dosen, array('sandi_pengguna' => $password_baru));
        $this->db->where('key_ref', $kode_dosen)->where('role', '3')->update('users', array('password' => $password_api));
        $this->db->trans_complete();
        
        return array(
            'status' => $this->db->trans_status(),
            'password_string' => $string
        );
    }
    
    public function searchValidator() {
        $this->form_validation->set_rules('nama_dosen', 'nama_dosen', 'required', array('required' => 'Field Nama Dosen harus diisi'));
        return $this->form_validation->run();
    }
    
    public function uploadImage($kode_dosen) {
        $dosen = $this->db->get_where('dosen', array('kode_dosen' => $kode_dosen))->row_object();
        if (empty($dosen)) {
            return array('status' => false, 'msg' => 'Data dosen tidak ditemukan');
        }

        $config['upload_path'] = './assets/signature-dosen/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = 1024;
        $config['file_name'] = $dosen->kode_dosen;

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('foto')) {
            $error = $this->upload->display_errors();
            return array('status' => false, 'msg' => $error);
        } else {
            if (!empty($dosen->signature)) {
                if (file_exists('./assets/signature-dosen/' . $dosen->signature)) {
                    unlink('./assets/signature-dosen/' . $dosen->signature);
                }
            }
            $this->db->where('kode_dosen', $kode_dosen)->update('dosen', array('signature' => $this->upload->data('file_name')));
            return array('status' => true, 'msg' => "Upload gambar berhasil");
        }
    }

    // ========== BIDANG ILMU ==========

    public function getDosenHomebase($kode_dosen) {
        return $this->db->select('*')
            ->from('dosen')
            ->where('kode_dosen', $kode_dosen)
            ->get()
            ->row_array();
    }

    public function getBidangIlmuByProdi($kode_program_studi) {
        return $this->db->select('*')
            ->from('bidang_ilmu')
            ->where('kode_program_studi', $kode_program_studi)
            ->get()
            ->result();
    }

    public function checkBidangIlmuDetail($kode_dosen, $id_bidang) {
        return $this->db->select('*')
            ->from('bidang_ilmu_detail')
            ->where('kode_dosen', $kode_dosen)
            ->where('id_bidang_ilmu', $id_bidang)
            ->get();
    }

    public function insertBidangIlmuDetail($data) {
        $this->db->insert('bidang_ilmu_detail', $data);
    }

    public function getBidangIlmuDetailDosen($kode_dosen) {
        return $this->db->select('*')
            ->from('bidang_ilmu_detail')
            ->join('bidang_ilmu', 'bidang_ilmu.id_bidang_ilmu=bidang_ilmu_detail.id_bidang_ilmu')
            ->where('kode_dosen', $kode_dosen)
            ->get()
            ->result();
    }

    public function deleteBidangIlmuDetail($id_bidang_ilmu_detail) {
        $this->db->delete('bidang_ilmu_detail', array('id_bidang_ilmu_detail' => $id_bidang_ilmu_detail));
    }

    public function getKaprodiByDosen($kode_dosen) {
        return $this->db->select('*')
            ->from('kaprodi')
            ->where('kode_dosen', $kode_dosen)
            ->get();
    }

    public function getBidangIlmuByProdiDistinct($kode_program_studi) {
        return $this->db->select('distinct(nama_bidang) as nama_bidang, nama_program_studi, bidang_ilmu_detail.id_bidang_ilmu')
            ->from('bidang_ilmu')
            ->join('bidang_ilmu_detail', 'bidang_ilmu_detail.id_bidang_ilmu=bidang_ilmu.id_bidang_ilmu')
            ->join('program_studi', 'program_studi.kode_program_studi=bidang_ilmu.kode_program_studi')
            ->where('bidang_ilmu.kode_program_studi', $kode_program_studi)
            ->get()
            ->result();
    }

    public function getBidangIlmuDetailByProdi($kode_program_studi) {
        return $this->db->select('*')
            ->from('bidang_ilmu')
            ->join('bidang_ilmu_detail', 'bidang_ilmu_detail.id_bidang_ilmu=bidang_ilmu.id_bidang_ilmu')
            ->join('program_studi', 'program_studi.kode_program_studi=bidang_ilmu.kode_program_studi')
            ->join('dosen', 'dosen.kode_dosen=bidang_ilmu_detail.kode_dosen')
            ->where('bidang_ilmu.kode_program_studi', $kode_program_studi)
            ->get()
            ->result();
    }

    public function getDosenByHomebase($kode_program_studi) {
        return $this->db->select('*')
            ->from('dosen')
            ->where('homebase', $kode_program_studi)
            ->get()
            ->result();
    }

    public function getDosenSudahBidang($kode_program_studi) {
        return $this->db->select('distinct(bidang_ilmu_detail.kode_dosen), nama_dosen')
            ->from('dosen')
            ->join('bidang_ilmu_detail', 'bidang_ilmu_detail.kode_dosen=dosen.kode_dosen')
            ->where('dosen.homebase', $kode_program_studi)
            ->get()
            ->result();
    }

    public function getDosenBelumBidang($kode_program_studi) {
        return $this->db->select('*')
            ->from('dosen')
            ->where('homebase', $kode_program_studi)
            ->where_not_in('kode_dosen', '(select bidang_ilmu_detail.kode_dosen from bidang_ilmu join bidang_ilmu_detail on bidang_ilmu_detail.id_bidang_ilmu=bidang_ilmu.id_bidang_ilmu where bidang_ilmu.kode_program_studi=' . $kode_program_studi . ')', false)
            ->get()
            ->result();
    }

    public function getFakultasByDekan($kode_dosen) {
        return $this->db->select('*')
            ->from('fakultas')
            ->join('program_studi', 'program_studi.kode_fakultas=fakultas.kode_fakultas')
            ->where('dekan', $kode_dosen)
            ->get();
    }

    public function getBidangIlmuByProdiSub($kode_dosen) {
        return $this->db->select('distinct(nama_bidang) as nama_bidang, nama_program_studi, bidang_ilmu_detail.id_bidang_ilmu')
            ->from('bidang_ilmu')
            ->join('bidang_ilmu_detail', 'bidang_ilmu_detail.id_bidang_ilmu=bidang_ilmu.id_bidang_ilmu')
            ->join('program_studi', 'program_studi.kode_program_studi=bidang_ilmu.kode_program_studi')
            ->where('bidang_ilmu.kode_program_studi in', '(select program_studi.kode_program_studi from fakultas join program_studi on program_studi.kode_fakultas=fakultas.kode_fakultas where dekan =' . $kode_dosen . ')', false)
            ->get()
            ->result();
    }

    public function getBidangIlmuDetailByProdiSub($kode_dosen) {
        return $this->db->select('*')
            ->from('bidang_ilmu')
            ->join('bidang_ilmu_detail', 'bidang_ilmu_detail.id_bidang_ilmu=bidang_ilmu.id_bidang_ilmu')
            ->join('program_studi', 'program_studi.kode_program_studi=bidang_ilmu.kode_program_studi')
            ->join('dosen', 'dosen.kode_dosen=bidang_ilmu_detail.kode_dosen')
            ->where('bidang_ilmu.kode_program_studi in', '(select program_studi.kode_program_studi from fakultas join program_studi on program_studi.kode_fakultas=fakultas.kode_fakultas where dekan =' . $kode_dosen . ')', false)
            ->get()
            ->result();
    }

    public function getDosenByHomebaseSub($kode_dosen) {
        return $this->db->select('*')
            ->from('dosen')
            ->where('homebase in', '(select program_studi.kode_program_studi from fakultas join program_studi on program_studi.kode_fakultas=fakultas.kode_fakultas where dekan =' . $kode_dosen . ')', false)
            ->get()
            ->result();
    }

    public function getDosenSudahBidangSub($kode_dosen) {
        return $this->db->select('distinct(bidang_ilmu_detail.kode_dosen), nama_dosen')
            ->from('dosen')
            ->join('bidang_ilmu_detail', 'bidang_ilmu_detail.kode_dosen=dosen.kode_dosen')
            ->where('dosen.homebase in', '(select program_studi.kode_program_studi from fakultas join program_studi on program_studi.kode_fakultas=fakultas.kode_fakultas where dekan =' . $kode_dosen . ')', false)
            ->get()
            ->result();
    }

    public function getDosenBelumBidangSub($kode_dosen) {
        return $this->db->select('*')
            ->from('dosen')
            ->where('dosen.homebase in', '(select program_studi.kode_program_studi from fakultas join program_studi on program_studi.kode_fakultas=fakultas.kode_fakultas where dekan =' . $kode_dosen . ')', false)
            ->get()
            ->result();
    }

    public function getJumlahDosenBidang($id) {
        return $this->db->select('*')
            ->from('bidang_ilmu_detail')
            ->join('dosen', 'dosen.kode_dosen=bidang_ilmu_detail.kode_dosen')
            ->join('bidang_ilmu', 'bidang_ilmu.id_bidang_ilmu=bidang_ilmu_detail.id_bidang_ilmu')
            ->where('bidang_ilmu_detail.id_bidang_ilmu', $id)
            ->get()
            ->result();
    }

    // ========== BIMBINGAN KKP ==========

    public function getBimbinganKkp($kode_dosen) {
        return $this->db->select('*, pk.id_pembimbing_kkp')
            ->from('pembimbing_kkp as pk')
            ->join('mahasiswa as mah', 'pk.nim=mah.nim')
            ->join('nilai_kkp as nk', 'pk.id_pembimbing_kkp=nk.id_pembimbing_kkp', 'left')
            ->where('mah.status', 'A')
            ->where('kode_dosen', $kode_dosen)
            ->get()->result();
    }

    public function getNilaiKkp($id_pembimbing_kkp) {
        return $this->db->select('*')->from('nilai_kkp')->where('id_pembimbing_kkp', $id_pembimbing_kkp)->get()->row_object();
    }

    public function insertNilaiKkp($data) {
        return $this->db->insert('nilai_kkp', $data);
    }

    public function updateNilaiKkp($id, $data) {
        return $this->db->where('id_pembimbing_kkp', $id)->update('nilai_kkp', $data);
    }

    // ========== BOT DOSEN ==========

    public function getChatIdDosenByKode($kode_dosen) {
        return $this->db->select('chatid')->from('dosen')->where('kode_dosen', $kode_dosen)->get()->row_array();
    }

    public function updateChatIdDosen($cid, $alamat_email) {
        $this->db->set('chatid', $cid)->where('alamat_email', $alamat_email)->update('dosen');
    }

    // ========== GANTI SANDI ==========

    public function updateUserPassword($kode_dosen, $password_api) {
        $this->db->where('key_ref', $kode_dosen)->update('users', array('password' => $password_api));
    }

    // ========== KURIKULUM ==========

    public function getProdiFromKurikulum($kode_nama_kurikulum) {
        return $this->db->select('*')
            ->from('nama_kurikulum as nk')
            ->join('program_studi as prodi', 'nk.kode_program_studi=prodi.kode_program_studi')
            ->where('kode_nama_kurikulum', $kode_nama_kurikulum)
            ->get()->row_object();
    }

    public function getKompetensiByProdi($kode_program_studi) {
        return $this->db->select('makom.id_matakuliah as id_matakuliah, kom.nama_kompetensi as nama')
            ->from('kompetensi as kom')
            ->join('matakuliah_kompetensi as makom', 'makom.kode_kompetensi=kom.kode_kompetensi')
            ->where('kom.kode_program_studi', $kode_program_studi)
            ->get()->result_array();
    }

    // ========== MATAKULIAH PRASYARAT ==========

    public function getProdiFromNamaKurikulum($kode_nama_kurikulum) {
        return $this->db->select('*')
            ->from('nama_kurikulum as nk')
            ->join('program_studi as prodi', 'nk.kode_program_studi=prodi.kode_program_studi')
            ->where('kode_nama_kurikulum', $kode_nama_kurikulum)
            ->get()->row_object();
    }

    // ========== MBKM ==========

    public function getKaprodiKode($kode_dosen) {
        return $this->db->select('kode_program_studi')->from('kaprodi')->where('kode_dosen', $kode_dosen)->get()->row_array();
    }

    public function getMahasiswaMbkm($kode_program_studi, $ta) {
        return $this->db->select('mahasiswa.nim,mahasiswa.nama_mahasiswa,nama_program_studi,tahun_akademik.semester,mbkm.id as id_fix')
            ->from('mbkm')
            ->join('mahasiswa', 'mahasiswa.nim = mbkm.nim')
            ->join('tahun_akademik', 'tahun_akademik.kode_tahun_akademik = mbkm.kode_ta')
            ->join('program_studi', 'program_studi.kode_program_studi = mahasiswa.program_studi_kode')
            ->where('mbkm.kode_ta', $ta)
            ->where('program_studi_kode', $kode_program_studi)
            ->order_by('mbkm.id', 'DESC')
            ->get()->result_object();
    }

    public function getKaprodiFull($kode_dosen) {
        return $this->db->select('*')->from('kaprodi')->where('kode_dosen', $kode_dosen)->get()->row_array();
    }

    public function getNamaProdi($kode_program_studi) {
        return $this->db->select('nama_program_studi')
            ->from('program_studi')
            ->where('kode_program_studi', $kode_program_studi)
            ->get()->row_object();
    }

    public function getCekMahasiswaMbkm($nim, $ta) {
        return $this->db->select('mahasiswa.*,program_studi.*,mbkm.id as id_mbkm,mbkm.kode_ta as ta_now')
            ->from('mahasiswa')
            ->join('program_studi', 'program_studi.kode_program_studi = mahasiswa.program_studi_kode')
            ->join('mbkm', 'mahasiswa.nim = mbkm.nim')
            ->where('mahasiswa.nim ', $nim)
            ->where('mbkm.kode_ta', $ta)
            ->get()->row_object();
    }

    public function getMahasiswaByProdi($kode_program_studi, $nim) {
        return $this->db->select('mahasiswa.*,program_studi.*')
            ->from('mahasiswa')
            ->join('program_studi', 'program_studi.kode_program_studi = mahasiswa.program_studi_kode')
            ->where('program_studi_kode', $kode_program_studi)
            ->where('mahasiswa.nim ', $nim)
            ->get()->result_object();
    }

    public function cekMbkm($nim, $ta) {
        return $this->db->select('*')->from('mbkm')->where('mbkm.nim', $nim)->where('mbkm.kode_ta', $ta)->get()->row_object();
    }

    public function insertMbkm($data) {
        return $this->db->insert('mbkm', $data);
    }

    public function deleteMbkm($id) {
        return $this->db->where('id', $id)->delete('mbkm');
    }

    // ========== VALIDASIKHUSUS ==========

    public function getAllFakultas() {
        return $this->db->select("*")->from('fakultas')->get()->result();
    }

    public function getFakultasById($kode_fakultas) {
        return $this->db->select("*")->from('fakultas')->where('kode_fakultas', $kode_fakultas)->get()->row_array();
    }

    public function getKelasForValidasi($kode_prodi, $kode_tahun_akademik) {
        return $this->db->select('datecreate,ps.singkatan_program_studi,status_nilai, validasi_nilai, validasi_dekan, nama_kelas, nama_matakuliah,kelas.kelas_id, mengajar_id, GROUP_CONCAT(nama_dosen) as nama_dosen, mak.kode_matakuliah')
            ->from('kelas')
            ->join('nama_kelas as nk', 'nk.nama_kelas_id=kelas.nama_kelas_id')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->join('mengajar as meng', 'meng.kelas_id=kelas.kelas_id', 'left')
            ->join('dosen', 'meng.kode_dosen=dosen.kode_dosen', 'left')
            ->join('program_studi as ps', 'ps.kode_program_studi=kelas.kode_program_studi')
            ->where_in('kelas.kode_program_studi', $kode_prodi)
            ->where('kelas.kode_tahun_akademik', $kode_tahun_akademik)
            ->group_by('kelas.kelas_id')
            ->order_by('kelas.datecreate', 'ASC')
            ->get()->result();
    }

    // ========== KONSULTASI PERWALIAN ==========

    public function getKonsultasiMahasiswa($kode_dosen, $kode_tahun_akademik) {
        return $this->db->select('krs.kode_krs,p.kode_dosen_perwakilan, m.telepon, m.nama_mahasiswa, m.nim, kode_perwalian, d.nama_dosen, d.kode_dosen, kp.status_cetak, kp.kode_konsultasi_perwalian')
            ->from('perwalian as p')
            ->join('dosen as d', 'p.kode_dosen=d.kode_dosen')
            ->join('mahasiswa as m', 'p.nim=m.nim')
            ->join('konsultasi_perwalian as kp', 'kp.nim=p.nim')
            ->join('krs', "m.nim=krs.nim and krs.kode_tahun_akademik=".$this->db->escape($kode_tahun_akademik), 'left')
            ->where('kp.kode_tahun_akademik', $kode_tahun_akademik)
            ->where("(p.kode_dosen_perwakilan='" . $kode_dosen . "'or p.kode_dosen='" . $kode_dosen . "')")
            ->where('kp.status_cetak', 'N')
            ->group_by('p.nim')
            ->get()->result();
    }

    public function getKonsultasiMahasiswaAktif($kode_dosen, $kode_tahun_akademik) {
        return $this->db->select('krs.kode_krs,p.kode_dosen_perwakilan, m.telepon, m.nama_mahasiswa, m.nim, kode_perwalian, d.nama_dosen, d.kode_dosen, kp.status_cetak, kp.kode_konsultasi_perwalian')
            ->from('perwalian as p')
            ->join('dosen as d', 'p.kode_dosen=d.kode_dosen')
            ->join('mahasiswa as m', 'p.nim=m.nim')
            ->join('konsultasi_perwalian as kp', 'kp.nim=p.nim')
            ->join('krs', "m.nim=krs.nim and krs.kode_tahun_akademik=".$this->db->escape($kode_tahun_akademik), 'left')
            ->where('kp.kode_tahun_akademik', $kode_tahun_akademik)
            ->where("(p.kode_dosen_perwakilan='" . $kode_dosen . "'or p.kode_dosen='" . $kode_dosen . "')")
            ->where('kp.status_cetak', 'A')
            ->group_by('p.nim')
            ->get()->result();
    }

    public function getPerwalianTidakAktif($kode_dosen, $kode_tahun_akademik) {
        return $this->db->select('*,krs.kode_krs,p.kode_dosen_perwakilan, m.telepon, m.nama_mahasiswa, m.nim, kode_perwalian, d.nama_dosen, d.kode_dosen, kp.status_cetak, kp.kode_konsultasi_perwalian')
            ->from('perwalian as p')
            ->join('status_perkuliahan as sp', 'sp.nim=p.nim')
            ->join('dosen as d', 'p.kode_dosen=d.kode_dosen')
            ->join('mahasiswa as m', 'p.nim=m.nim')
            ->join('konsultasi_perwalian as kp', 'kp.nim=p.nim')
            ->join('krs', "m.nim=krs.nim and krs.kode_tahun_akademik=".$this->db->escape($kode_tahun_akademik), 'left')
            ->where('kp.kode_tahun_akademik', $kode_tahun_akademik)
            ->where("(p.kode_dosen_perwakilan='" . $kode_dosen . "'or p.kode_dosen='" . $kode_dosen . "')")
            ->where('kp.status_cetak', 'N')
            ->group_by('p.nim')
            ->get()->result();
    }

    public function getStatusPendaftaranMahasiswa($nim) {
        return $this->db->select('status_pendaftaran')
            ->from('mahasiswa')
            ->where('nim', $nim)
            ->get()->row_object();
    }

    public function getKrsSebelumnya($nim, $semester) {
        return $this->db->select('max(krs.semester) as semester, max(krs.kode_tahun_akademik) as tahun_akademik')
            ->from('krs')
            ->join('krs_detail as kd', 'kd.kode_krs = krs.kode_krs')
            ->where('kd.status != ', 'K')
            ->where('nim', $nim)
            ->where('krs.semester <', $semester)
            ->group_by('nim')
            ->get()->row_object();
    }

    public function getPerwalianExport($kode_dosen) {
        return $this->db->select('mah.nim,nama_mahasiswa, telepon')
            ->from('perwalian as per')
            ->join('mahasiswa as mah', 'mah.nim=per.nim')
            ->where('per.kode_dosen', $kode_dosen)
            ->where('mah.status', 'A')
            ->order_by('mah.nim', 'DESC')
            ->get()->result();
    }

    public function getDetailPerwalian($nim) {
        return $this->db->select('nama_dosen,mah.nim, nama_mahasiswa, mah.email, mah.telepon')
            ->from('perwalian as per')
            ->join('dosen', 'per.kode_dosen=dosen.kode_dosen')
            ->join('mahasiswa as mah', 'mah.nim=per.nim')
            ->where('per.nim', $nim)
            ->get()->row_object();
    }

    public function getKonsultasiPerwalianByNim($nim) {
        return $this->db->select('*')
            ->from('konsultasi_perwalian')
            ->where('konsultasi_perwalian.nim', $nim)
            ->order_by('kode_tahun_akademik', 'desc')
            ->get()->row_object();
    }

    public function insertKonsultasiPerwalianDetail($obj) {
        $this->db->insert('konsultasi_perwalian_detail', $obj);
    }

    public function getCekKrsAktif($nim, $kode_tahun_akademik) {
        return $this->db->get_where('krs', array('nim' => $nim, 'kode_tahun_akademik' => $kode_tahun_akademik))->row();
    }
}
