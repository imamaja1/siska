<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProgramStudiService extends MY_Service {

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'jurusan/program_studi/jenjang_model',
            'jurusan/program_studi/kode_jurusan_model',
            'jurusan/program_studi/nama_jurusan_model',
            'jurusan/institusi_model',
            'jurusan/program_studi/kompetensi_model',
            'jurusan/program_studi/ketua_jurusan_model',
            'jurusan/m_dosen'
        ));
    }

    // --- NAMA JURUSAN LOGIC ---

    public function getNamaJurusanLengkap() {
        return $this->db->select('*')
                ->from('program_studi as ps')
                ->join('fakultas as fk', 'ps.kode_fakultas=fk.kode_fakultas')
                ->get()->result_object();
    }

    public function getJenjang() {
        return $this->jenjang_model->get();
    }

    public function getFakultas() {
        return $this->db->get('fakultas')->result_object();
    }

    public function getNamaJurusanByKode($kode_program_studi) {
        return $this->db->get_where('program_studi', array('kode_program_studi' => $kode_program_studi))->row_object();
    }

    public function simpanNamaJurusan($post_data) {
        $data = array(
            'nama_program_studi' => $post_data['nama_jurusan'],
            'singkatan_program_studi' => $post_data['singkatan'],
            'kode_fakultas' => $post_data['kode_fakultas'],
            'kode_prodi_univ' => $post_data['kode_prodi_univ'],
            'id_jenjang' => $post_data['jenjang'],
            'kompetensi' => $post_data['kompetensi']
        );
        
        if ($this->nama_jurusan_model->add($data)) {
            return true;
        }
        return false;
    }

    public function ubahNamaJurusan($post_data) {
        $param = $post_data['param'];
        $data = array(
            'nama_program_studi' => $post_data['nama_jurusan'],
            'kode_fakultas' => $post_data['kode_fakultas'],
            'singkatan_program_studi' => $post_data['singkatan'],
            'kode_prodi_univ' => $post_data['kode_prodi_univ'],
            'id_jenjang' => $post_data['jenjang'],
            'kompetensi' => $post_data['kompetensi']
        );

        if ($this->nama_jurusan_model->ubah($data, $param)) {
            return true;
        }
        return false;
    }

    public function hapusNamaJurusan($id) {
        return $this->nama_jurusan_model->hapus($id);
    }

    // --- KODE JURUSAN LOGIC ---

    public function getKodeJurusanLengkap() {
        return $this->kode_jurusan_model->get();
    }

    public function getInstitusi() {
        return $this->institusi_model->get();
    }

    public function simpanKodeJurusan($post_data) {
        $kode_jurusan = $post_data['kode_jurusan'];
        $cek_kode_jurusan = $this->kode_jurusan_model->cek_kode_jurusan($kode_jurusan);

        if ($cek_kode_jurusan->num_rows() > 0) {
            return array('status' => false, 'msg' => 'Data dengan Kode Jurusan ' . $kode_jurusan . ' sudah ada.');
        } else {
            $data = array(
                'kode_jurusan' => $kode_jurusan,
                'nama_jurusan' => $post_data['nama_jurusan'],
                'kode_institusi' => $post_data['nama_institusi']
            );
            
            if ($this->kode_jurusan_model->add($data)) {
                return array('status' => true, 'msg' => 'Simpan data jurusan berhasil');
            }
            return array('status' => false, 'msg' => 'Simpan data jurusan gagal');
        }
    }

    public function ubahKodeJurusan($post_data) {
        $param = $post_data['param'];
        $data_jurusan = array(
            'kode_jurusan' => $post_data['kode_jurusan'],
            'nama_jurusan' => $post_data['nama_jurusan'],
            'kode_institusi' => $post_data['nama_institusi'],
        );

        if ($this->kode_jurusan_model->ubah($data_jurusan, $param)) {
            return true;
        }
        return false;
    }

    // --- KOMPETENSI LOGIC ---

    public function getKompetensiLengkap() {
        return $this->kompetensi_model->get_kompetensi();
    }

    public function simpanKompetensi($post_data) {
        $data = array(
            'nama_kompetensi' => $post_data['nama_kompetensi'],
            'singkatan_kompetensi' => $post_data['singkatan_kompetensi'],
            'kode_program_studi' => $post_data['kode_nama_jurusan'],
        );

        if ($this->kompetensi_model->simpan($data)) {
            return true;
        }
        return false;
    }

    public function ubahKompetensi($post_data) {
        $param = $post_data['param'];
        $data = array(
            'nama_kompetensi' => $post_data['nama_kompetensi'],
            'singkatan_kompetensi' => $post_data['singkatan_kompetensi'],
            'kode_program_studi' => $post_data['kode_nama_jurusan'],
        );

        if ($this->kompetensi_model->ubah($data, $param)) {
            return true;
        }
        return false;
    }

    public function hapusKompetensi($id) {
        return $this->kompetensi_model->hapus($id);
    }

    // --- JENJANG LOGIC ---

    public function getJenjangLengkap() {
        return $this->jenjang_model->get();
    }

    public function simpanJenjang($post_data) {
        $kode_jenjang = $post_data['kode_jenjang'];
        $cek = $this->jenjang_model->cek_kode_jenjang($kode_jenjang);

        if ($cek->num_rows() > 0) {
            return array('status' => false, 'msg' => 'Data dengan kode jenjang ' . $kode_jenjang . ' sudah ada.');
        } else {
            $data = array(
                'kode_jenjang' => $kode_jenjang,
                'nama_jenjang' => $post_data['nama_jenjang'],
                'kode_institusi' => $post_data['nama_institusi']
            );
            
            if ($this->jenjang_model->add($data)) {
                return array('status' => true, 'msg' => 'Tambah Data Jenjang Berhasil');
            }
            return array('status' => false, 'msg' => 'Tambah Data Jenjang Gagal');
        }
    }

    public function ubahJenjang($post_data) {
        $id_jenjang = $post_data['id_jenjang-edit'];
        $data = array(
            'kode_jenjang' => $post_data['kode_jenjang-edit'],
            'nama_jenjang' => $post_data['nama_jenjang-edit'],
            'kode_institusi' => $post_data['nama_institusi-edit']
        );

        if ($this->jenjang_model->edit($id_jenjang, $data)) {
            return true;
        }
        return false;
    }

    public function hapusJenjang($id_jenjang) {
        return $this->jenjang_model->del($id_jenjang);
    }

    // --- KETUA JURUSAN LOGIC ---

    public function getKetuaJurusanLengkap() {
        return $this->ketua_jurusan_model->get();
    }

    public function getDosen() {
        return $this->m_dosen->get();
    }

    public function simpanKetuaJurusan($post_data) {
        $kode_dosen = $post_data['kode_dosen'];
        
        $config['upload_path'] = './assets/signature_kaprodi/';
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = 1024;
        $config['file_name'] = $kode_dosen;
        $config['overwrite'] = true;

        $this->load->library('upload', $config);

        if ($this->upload->do_upload('tanda_tangan')) {
            $data = array(
                'kode_program_studi' => $post_data['kode_nama_jurusan'],
                'kode_dosen' => $kode_dosen,
                'tanda_tangan' => $this->upload->data('file_name')
            );
            
            if ($this->ketua_jurusan_model->add($data)) {
                return array('status' => true, 'msg' => 'Data Kaprodi Berhasil disimpan');
            }
            return array('status' => false, 'msg' => 'Data Kaprodi gagal disimpan');
        } else {
            return array('status' => false, 'msg' => 'Cek kembali ukuran file dan type file: ' . $this->upload->display_errors('',''));
        }
    }

    public function getKetuaJurusanById($kode_kaprodi) {
        return $this->db->get_where('kaprodi', array('kode_kaprodi' => $kode_kaprodi))->row_object();
    }

    public function ubahKetuaJurusan($post_data) {
        $param = $post_data['param'];
        $data = array(
            'kode_program_studi' => $post_data['kode_nama_jurusan'],
            'kode_dosen' => $post_data['kode_dosen'],
        );

        if ($this->ketua_jurusan_model->ubah($data, $param)) {
            return true;
        }
        return false;
    }

    public function hapusKetuaJurusan($id) {
        $tanda_tangan = $this->ketua_jurusan_model->get_kode_dosen($id);
        
        if ($this->ketua_jurusan_model->hapus($id)) {
            if (!empty($tanda_tangan) && isset($tanda_tangan[0]->tanda_tangan)) {
                $file_path = FCPATH . 'assets/signature_kaprodi/' . $tanda_tangan[0]->tanda_tangan;
                if (file_exists($file_path)) {
                    unlink($file_path);
                }
            }
            return true;
        }
        return false;
    }

    public function uploadImageKetuaJurusan($kode_kaprodi) {
        $kaprodi = $this->getKetuaJurusanById($kode_kaprodi);

        if (!$kaprodi) {
            return array('status' => false, 'msg' => 'Data kaprodi tidak ditemukan');
        }

        $upload_path = FCPATH . 'assets/signature_kaprodi/';
        if (!is_dir($upload_path)) {
            mkdir($upload_path, 0755, true);
        }

        $config['upload_path']   = $upload_path;
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size']      = 1024;
        $config['file_name']     = $kaprodi->kode_dosen;
        $config['overwrite']     = true;

        $this->load->library('upload');
        $this->upload->initialize($config);

        if (!$this->upload->do_upload('foto')) {
            $error = $this->upload->display_errors('','');
            return array('status' => false, 'msg' => $error);
        }

        $new_file = $this->upload->data('file_name');

        $this->db->where('kode_kaprodi', $kode_kaprodi)
                 ->update('kaprodi', array('tanda_tangan' => $new_file));

        if (!empty($kaprodi->tanda_tangan) && $kaprodi->tanda_tangan !== $new_file) {
            $old_file = $upload_path . $kaprodi->tanda_tangan;
            if (is_file($old_file)) {
                @unlink($old_file);
            }
        }

        return array('status' => true, 'msg' => 'Upload gambar berhasil');
    }

    // --- KONSENTRASI / MATAKULIAH KOMPETENSI LOGIC ---

    public function getKonsentrasiLengkap() {
        return $this->db->select('*')
            ->from('kompetensi')
            ->join('program_studi as ps','ps.kode_program_studi=kompetensi.kode_program_studi')
            ->get()->result();
    }

    public function getMatakuliahKonsentrasi($kode_konsentrasi) {
        $konsentrasi = $this->db->get_where('kompetensi',array('kode_kompetensi'=>$kode_konsentrasi))->row_object();
        $matakuliah = $this->db->get_where('matakuliah', array('kode_program_studi'=>$konsentrasi->kode_program_studi))->result();
        $lists = $this->db->select('*')
            ->from('matakuliah_kompetensi as mk')
            ->join('matakuliah as makul','makul.id_matakuliah=mk.id_matakuliah')
            ->where('mk.kode_kompetensi', $kode_konsentrasi)
            ->get()->result();

        return array(
            'konsentrasi' => $konsentrasi,
            'matakuliah' => $matakuliah,
            'lists' => $lists
        );
    }

    public function simpanMatakuliahKonsentrasi($post_data) {
        return $this->db->insert('matakuliah_kompetensi', $post_data);
    }

    public function hapusMatakuliahKonsentrasi($id) {
        return $this->db->where('id', $id)->delete('matakuliah_kompetensi');
    }

}
