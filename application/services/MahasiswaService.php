<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MahasiswaService extends MY_Service {

    public function __construct() {
        parent::__construct();
        $this->load->model(array(
            'akademik/mahasiswa_model',
            'jurusan/m_tahun_akademik',
            'jurusan/program_studi/nama_jurusan_model',
            'akademik/Krs_model',
        ));
        $this->load->library('ion_auth');
    }

    public function getTahunAkademikLengkap() {
        return $this->db->select('kode_tahun_akademik, tahun_akademik, semester')
            ->order_by('kode_tahun_akademik', 'DESC')
            ->get('tahun_akademik')
            ->result();
    }

    public function getProgramStudiLengkap() {
        return $this->nama_jurusan_model->get();
    }

    public function getProvinsiLengkap() {
        return $this->mahasiswa_model->get_provinsi();
    }
    
    public function getMahasiswaByNim($nim) {
        return $this->mahasiswa_model->get_mahasiswa_by_nim($nim);
    }

    public function simpanMahasiswa($post_data) {
        $data = array(
            'nik' => $post_data['nik'],
            'status' => $post_data['status'],
            'status_pendaftaran' => $post_data['status_pendaftaran'],
            'sandi' => md5($post_data['password']),
            'nim' => $post_data['nim'],
            'npm' => $post_data['npm'],
            'nomor_pendaftaran' => $post_data['no_pendaftaran'],
            'nomor_pendaftaran_ulang' => $post_data['no_pendaftaran_ulang'],
            'nama_mahasiswa' => $post_data['nama_mahasiswa'],
            'tempat_lahir' => $post_data['tempat_lahir'],
            'tanggal_lahir' => $post_data['tanggal_lahir'],
            'alamat' => $post_data['alamat_lengkap'],
            'kota' => $post_data['kota'],
            'propinsi' => $post_data['propinsi'],
            'telepon' => $post_data['telepon'],
            'jenis_kelamin' => $post_data['jenis_kelamin'],
            'agama' => $post_data['agama'],
            'golongan_darah' => $post_data['golongan_darah'],
            'kewarganegaraan' => $post_data['kewarganegaraan'],
            'nama_instansi' => $post_data['nama_instansi'],
            'email' => $post_data['nim'] . "@universitasbumigora.ac.id",
            'nama_ayah' => $post_data['nama_ayah'],
            'agama_ayah' => $post_data['agama_ayah'],
            'pekerjaan_ayah' => $post_data['pekerjaan_ayah'],
            'nama_ibu' => $post_data['nama_ibu'],
            'agama_ibu' => $post_data['agama_ibu'],
            'pekerjaan_ibu' => $post_data['pekerjaan_ibu'],
            'alamat_orangtua' => $post_data['alamat_orangtua'],
            'kota_orangtua' => $post_data['kota_orangtua'],
            'propinsi_orangtua' => $post_data['propinsi_orangtua'],
            'telepon_orangtua' => $post_data['telepon_orangtua'],
            'program_studi_kode' => $post_data['program_studi_kode']
        );

        if ($this->mahasiswa_model->add($data)) {
            $username = $post_data['nim'];
            $password = $post_data['password'];
            $email = $post_data['nim'] . "@universitasbumigora.ac.id";
            $additional_data = array(
                'first_name' => $username,
                'key_ref' => $username,
                'role' => 2
            );
            $group = array('3');
            $this->ion_auth->register($username, $password, $email, $additional_data, $group);

            return array('status' => true, 'msg' => 'Tambah Data Mahasiswa Berhasil.');
        } else {
            return array('status' => false, 'msg' => 'Tambah Data Mahasiswa Gagal.');
        }
    }

    public function ubahMahasiswa($nim, $post_data) {
        $data = array(
            'nik' => $post_data['nik'],
            'status' => $post_data['status'],
            'status_pendaftaran' => $post_data['status_pendaftaran'],
            'sandi' => md5($post_data['password']),
            'npm' => $post_data['npm'],
            'nomor_pendaftaran' => $post_data['no_pendaftaran'],
            'nomor_pendaftaran_ulang' => $post_data['no_pendaftaran_ulang'],
            'nama_mahasiswa' => $post_data['nama_mahasiswa'],
            'tempat_lahir' => $post_data['tempat_lahir'],
            'tanggal_lahir' => $post_data['tanggal_lahir'],
            'alamat' => $post_data['alamat_lengkap'],
            'kota' => $post_data['kota'],
            'propinsi' => $post_data['propinsi'],
            'telepon' => $post_data['telepon'],
            'jenis_kelamin' => $post_data['jenis_kelamin'],
            'agama' => $post_data['agama'],
            'golongan_darah' => $post_data['golongan_darah'],
            'kewarganegaraan' => $post_data['kewarganegaraan'],
            'nama_instansi' => $post_data['nama_instansi'],
            'nama_ayah' => $post_data['nama_ayah'],
            'agama_ayah' => $post_data['agama_ayah'],
            'pekerjaan_ayah' => $post_data['pekerjaan_ayah'],
            'nama_ibu' => $post_data['nama_ibu'],
            'agama_ibu' => $post_data['agama_ibu'],
            'pekerjaan_ibu' => $post_data['pekerjaan_ibu'],
            'alamat_orangtua' => $post_data['alamat_orangtua'],
            'kota_orangtua' => $post_data['kota_orangtua'],
            'propinsi_orangtua' => $post_data['propinsi_orangtua'],
            'telepon_orangtua' => $post_data['telepon_orangtua'],
            'program_studi_kode' => $post_data['program_studi_kode']
        );

        if ($this->mahasiswa_model->update($nim, $data)) {
            $user_id = $this->getUserIdByKeyRef($nim);
            
            $additional_data = array(
                'first_name' => $nim,
                'key_ref' => $nim,
                'role' => 2
            );
            $password = $post_data['password'];
            if (!empty($password)) {
                $additional_data['password'] = $password;
            }
            $this->ion_auth->update($user_id, $additional_data);

            return array('status' => true, 'msg' => 'Ubah Data Mahasiswa Berhasil.');
        } else {
            return array('status' => false, 'msg' => 'Ubah Data Mahasiswa Gagal.');
        }
    }

    public function generateSandi($nim) {
        $mhs = $this->mahasiswa_model->get_mahasiswa_by_nim($nim);
        if ($mhs) {
            $tgl = date('dmY', strtotime($mhs->tanggal_lahir));
            $sandi = substr(md5($tgl), 0, 8);
            
            $data = array('sandi' => $sandi);
            $this->mahasiswa_model->update($nim, $data);
            
            $user_id = $this->getUserIdByKeyRef($nim);
            if ($user_id) {
                $this->ion_auth->update($user_id, array('password' => $sandi));
            }
            return array('status' => true, 'sandi' => $sandi, 'nim' => $nim);
        }
        return array('status' => false, 'msg' => 'Data tidak ditemukan');
    }

    public function getValidasiKrsMahasiswa($ta, $prodi) {
        if (!$ta) {
            $ta = $this->m_tahun_akademik->get_aktif();
        }
        if (!$prodi) {
            $prodi = 1;
        }
        if (!$ta) {
            return array('data_mhs' => [], 'ta' => null, 'prodi' => $prodi);
        }
        
        $data_mhs = $this->db->select('count(m.nim),m.nama_mahasiswa,m.nim,kp.status_cetak,sp.pembayaran_sks,dosen.nama_dosen')
            ->from('krs')
            ->join('mahasiswa as m', 'krs.nim=m.nim')
            ->join('konsultasi_perwalian as kp',"kp.nim=m.nim and kp.kode_tahun_akademik=".$this->db->escape($ta))
            ->join('status_perkuliahan as sp',"sp.nim=krs.nim and sp.kode_tahun_akademik=".$this->db->escape($ta))
            ->join('perwalian','perwalian.nim = krs.nim')
            ->join('dosen','perwalian.kode_dosen = dosen.kode_dosen')
            ->where('m.program_studi_kode',$prodi)
            ->where('krs.kode_tahun_akademik',$ta)
            ->where('sp.status_perkuliahan','A')
            ->group_by('m.nim')
            ->get()->result_object();

        return array('data_mhs' => $data_mhs, 'ta' => $ta, 'prodi' => $prodi);
    }

    public function getNamaProdi($prodi) {
        return $this->nama_jurusan_model->get_nama($prodi);
    }

    public function uploadImage($nim, $file_name, $upload_path) {
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'jpg|png|jpeg';
        $config['file_name'] = $file_name;
        $config['overwrite'] = true;
        $config['max_size'] = 2048;
        
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        
        if (!$this->upload->do_upload('foto')) {
            return array('status' => false, 'msg' => strip_tags($this->upload->display_errors()));
        } else {
            $image_data = $this->upload->data();
            $this->cropTo3x4($image_data);
            $mahasiswa = $this->mahasiswa_model->get($nim);
            $old_foto = $mahasiswa && isset($mahasiswa->foto) ? $mahasiswa->foto : null;
            $data = array('foto' => $image_data['file_name']);
            if ($this->mahasiswa_model->update($nim, $data)) {
                $placeholders = array('L.png', 'P.png', 'default.png');
                if ($old_foto && !in_array($old_foto, $placeholders) && $old_foto !== $image_data['file_name']) {
                    $old_path = FCPATH . 'assets/foto/' . $old_foto;
                    if (file_exists($old_path)) {
                        @unlink($old_path);
                    }
                }
                return array('status' => true, 'msg' => 'Upload Success', 'foto' => $image_data['file_name']);
            }
            return array('status' => false, 'msg' => 'Upload failed at DB update');
        }
    }

    private function cropTo3x4($image_data) {
        $target_w = 450;
        $target_h = 600;
        $src_w = $image_data['image_width'];
        $src_h = $image_data['image_height'];
        $path = $image_data['full_path'];
        $target_ratio = $target_w / $target_h;
        $src_ratio = $src_w / $src_h;

        if (abs($src_ratio - $target_ratio) < 0.01) {
            $resize_w = $target_w;
            $resize_h = $target_h;
        } elseif ($src_ratio > $target_ratio) {
            $resize_w = (int)round($src_w * ($target_h / $src_h));
            $resize_h = $target_h;
        } else {
            $resize_w = $target_w;
            $resize_h = (int)round($src_h * ($target_w / $src_w));
        }

        $this->load->library('image_lib');
        $resize_cfg = array(
            'image_library' => 'gd2',
            'source_image'  => $path,
            'maintain_ratio' => TRUE,
            'width'         => $resize_w,
            'height'        => $resize_h,
            'master_dim'    => $src_ratio > $target_ratio ? 'height' : 'width',
        );
        $this->image_lib->initialize($resize_cfg);
        $this->image_lib->resize();
        $this->image_lib->clear();

        $crop_x = (int)max(0, round(($resize_w - $target_w) / 2));
        $crop_y = 0;

        $crop_cfg = array(
            'image_library' => 'gd2',
            'source_image'  => $path,
            'maintain_ratio' => FALSE,
            'width'         => $target_w,
            'height'        => $target_h,
            'x_axis'        => $crop_x,
            'y_axis'        => $crop_y,
        );
        $this->image_lib->initialize($crop_cfg);
        $this->image_lib->crop();
        $this->image_lib->clear();
    }

    public function getMahasiswaByAngkatanJurusanPaginated($nama_angkatan, $kode_program_studi, $limit, $offset) {
        $gelombang = 5;
        if ($kode_program_studi == "Ekstensi") {
            $program_studi = "S1 Ekstensi";
            $query = $this->mahasiswa_model->get_mahasiswa_ekstensi_pagination($nama_angkatan, $gelombang, $limit, $offset);
            $data_count = count($this->mahasiswa_model->get_mahasiswa_ekstensi_count($nama_angkatan, $gelombang));
        } else {
            $query_jurusan = $this->nama_jurusan_model->get_all_byid($kode_program_studi);
            $program_studi = $query_jurusan ? $query_jurusan->singkatan_program_studi : '';
            $query = $this->mahasiswa_model->get_pagination_search($nama_angkatan, $kode_program_studi, $limit, $offset);
            $data_count = count($this->mahasiswa_model->get_count_search($nama_angkatan, $kode_program_studi));
        }
        return array('data' => $query, 'count' => $data_count, 'program_studi' => $program_studi);
    }

    public function searchMahasiswa($berdasarkan, $kata_kunci) {
        if ($berdasarkan == 'nim') {
            $data = $this->mahasiswa_model->search_by_nim($kata_kunci);
            return array('data' => $data, 'count' => count($data));
        }
        return false;
    }

    public function searchMahasiswaByNamePaginated($kata_kunci, $limit, $offset) {
        $data = $this->mahasiswa_model->search_by_nama($kata_kunci, $limit, $offset);
        $count = count($this->mahasiswa_model->count_search_by_nama($kata_kunci));
        return array('data' => $data, 'count' => $count);
    }

    public function getMahasiswaCetak($angkatan, $kode_program_studi) {
        $query = $this->mahasiswa_model->get_mahasiswa_by_angkatan_jurusan($angkatan, $kode_program_studi);
        $singkatan_jurusan = $this->nama_jurusan_model->get_all_byid($kode_program_studi);
        $singkatan = $singkatan_jurusan ? $singkatan_jurusan->singkatan_program_studi : '';
        
        return array('data' => $query, 'singkatan' => $singkatan);
    }

    // ==================== TRANSACTION HELPERS ====================

    public function transStart() {
        $this->db->trans_start();
    }

    public function transComplete() {
        $this->db->trans_complete();
    }

    public function transStatus() {
        return $this->db->trans_status();
    }

    public function transRollback() {
        $this->db->trans_rollback();
    }

    public function transCommit() {
        $this->db->trans_commit();
    }

    // ==================== KRS HELPERS ====================

    public function getKrsMhsHistory($nim, $ta) {
        return $this->db->select('*')->from('krs')
            ->join('krs_detail', 'krs_detail.kode_krs = krs.kode_krs', 'left')
            ->where('krs_detail.status != ', 'k')
            ->where('semester !=', 'k')
            ->where("nim", $nim)
            ->where('krs.kode_tahun_akademik <', $ta)
            ->group_by('krs.semester')
            ->get()->result_object();
    }

    public function getKrsMhsHistorySimple($nim, $ta) {
        return $this->db->select('*')->from('krs')
            ->where("nim", $nim)
            ->where("kode_tahun_akademik < ", $ta)
            ->get()->result_object();
    }

    public function getKrsMhsHistoryWithoutTa($nim, $ta) {
        return $this->db->select('*')->from('krs')
            ->join('krs_detail', 'krs_detail.kode_krs = krs.kode_krs', 'left')
            ->where('krs_detail.status != ', 'k')
            ->where('semester !=', 'k')
            ->where("nim", $nim)
            ->where('krs.kode_tahun_akademik <', $ta)
            ->group_by('krs.Semester')
            ->get()->result_object();
    }

    public function getKonsultasiPerwalianAktif($nim, $kode_tahun_akademik) {
        return $this->db->get_where('konsultasi_perwalian', array('nim' => $nim, 'kode_tahun_akademik' => $kode_tahun_akademik, 'status_cetak' => 'A'))->row_object();
    }

    public function getBayarSks($nim, $kode_tahun_akademik) {
        return $this->db->where('nim', $nim)
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->where_not_in('pembayaran_sks', '0')
            ->get('status_perkuliahan')->row_object();
    }

    public function getCekKrsBiasa($nim, $kode_tahun_akademik) {
        return $this->db->select('*')
            ->from('krs')
            ->where_not_in('semester', 'K')
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->where('nim', $nim)
            ->get()->result();
    }

    public function getCekKrsExis($nim, $kode_tahun_akademik) {
        return $this->db->select('*')
            ->from('krs')
            ->join('krs_detail as kd', 'kd.kode_krs=krs.kode_krs')
            ->where(array('nim' => $nim, 'kode_tahun_akademik' => $kode_tahun_akademik))
            ->where_not_in('semester', 'K')
            ->where_not_in('kd.status', ['K'])
            ->get()->row_object();
    }

    public function getMatakuliahPrasyaratSelect($kode_nama_kurikulum, $id_matakuliah) {
        return $this->db->select('*')
            ->from('matakuliah_prasyarat')
            ->where('kode_nama_kurikulum', $kode_nama_kurikulum)
            ->where('id_matakuliah_syarat', $id_matakuliah)
            ->where('jenis_prasyarat', 'LA')
            ->get()->row_object();
    }

    public function getMatakuliahPrasyaratById($id_matakuliah, $kode_nama_kurikulum) {
        return $this->db->get_where('matakuliah_prasyarat', array('id_matakuliah_ambil' => $id_matakuliah, 'kode_nama_kurikulum' => $kode_nama_kurikulum))->result();
    }

    public function getMakKrsDetail($nim, $id_matakuliah) {
        return $this->db->select('*,count(kd.kode_krs_detail) as jumlah')
            ->from('krs as k')
            ->join('krs_detail as kd', 'k.kode_krs=kd.kode_krs')
            ->join('khs_detail as hd', 'kd.kode_krs_detail=hd.kode_krs_detail')
            ->join('matakuliah as m', 'kd.id_matakuliah=m.id_matakuliah')
            ->where('kd.id_matakuliah', $id_matakuliah)
            ->where('k.nim', $nim)
            ->get()->row_object();
    }

    public function getLebihKrsDetail($nim, $id_matakuliah) {
        return $this->db->select('*')
            ->from('krs')
            ->join('krs_detail as krd', 'krs.kode_krs=krd.kode_krs')
            ->join('khs_detail as khd', 'krd.kode_krs_detail=khd.kode_krs_detail')
            ->join('matakuliah as mak', 'krd.id_matakuliah=mak.id_matakuliah')
            ->where('krd.id_matakuliah', $id_matakuliah)
            ->where('krs.nim', $nim)
            ->order_by('khd.nilai_akhir DESC')
            ->limit(1)
            ->get()->row_object();
    }

    public function getKhsLamaMaksimumSks($nim, $ta) {
        return $this->db->select('krs.semester, krs.kode_tahun_akademik, krs.kode_krs')
            ->from('krs')
            ->join('krs_detail as kd', 'krs.kode_krs = kd.kode_krs')
            ->where('kd.status !=', 'k')
            ->where('krs.semester !=', 'k')
            ->where('nim', $nim)
            ->where('krs.kode_tahun_akademik <', $ta)
            ->order_by('krs.kode_tahun_akademik', 'desc')
            ->order_by('krs.semester', 'desc')
            ->limit(1)
            ->get()->row_array();
    }

    public function getKompetensiByKode($kode_kompetensi) {
        return $this->db->select('*')
            ->from('kompetensi')
            ->where('kode_kompetensi', $kode_kompetensi)
            ->group_by('kode_kompetensi')
            ->get()->row_object();
    }

    public function getSemesterFromKurikulum($kode_nama_kurikulum, $id_matakuliah) {
        return $this->db->select('semester')
            ->from('kurikulum')
            ->where('kode_nama_kurikulum', $kode_nama_kurikulum)
            ->where('id_matakuliah', $id_matakuliah)
            ->get()->row_object();
    }

    public function getStatusPerkuliahan($nim, $kode_tahun_akademik) {
        return $this->db->where('nim', $nim)
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->get('status_perkuliahan')
            ->row_object();
    }

    public function getMahasiswaRowByNim($nim) {
        return $this->db->select('nim,nama_mahasiswa')
            ->from('mahasiswa')
            ->where('nim', $nim)
            ->get()->row_object();
    }

    public function getUserIdByKeyRef($nim) {
        $user = $this->db->get_where('users', array('key_ref' => $nim))->row_object();
        return $user ? $user->id : null;
    }

    public function getNikTeleponEmail($nim) {
        return $this->db->select('nik,telepon,email')->from('mahasiswa')->where('nim', $nim)->get()->row();
    }

    public function getStatusPendaftaranByNim($nim) {
        return $this->db->select('status_pendaftaran')
            ->from('mahasiswa')
            ->where('nim', $nim)
            ->get()->row_object();
    }

    // ==================== KHS HELPERS ====================

    public function getBlockByNim($nim) {
        return $this->db->select('*')
            ->from('block')
            ->where('nim', $nim)->get()->result_object();
    }

    public function getKrsListForKhs($nim, $kode_tahun_akademik) {
        return $this->db->select('*')
            ->from('krs')
            ->join('krs_detail as krd', 'krs.kode_krs=krd.kode_krs')
            ->where('nim', $nim)
            ->where_not_in('kode_tahun_akademik', $kode_tahun_akademik)
            ->where_not_in('semester', 'K')
            ->where_not_in('krd.status', 'K')
            ->group_by('kode_tahun_akademik')
            ->get()->result_object();
    }

    public function getSemesterByKodeKrs($kode_krs) {
        return $this->db->where('kode_krs', $kode_krs)->get('krs')->row_object();
    }

    public function getNamaMahasiswa($nim) {
        $mhs = $this->db->get_where('mahasiswa', array('nim' => $nim))->row_object();
        return $mhs ? $mhs->nama_mahasiswa : null;
    }

    public function getDosenByNik($nik) {
        return $this->db->select('*')
            ->from('dosen')
            ->where('nik', $nik)
            ->get()->row_object();
    }

    // ==================== PETIKAN NILAI HELPERS ====================

    public function getTahunAkademikById($kode_tahun_akademik) {
        return $this->db->select('*, tahun_akademik as ta')
            ->from('tahun_akademik')
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->get()->row_object();
    }

    public function getLastSemesterKrs($nim) {
        return $this->db->select('semester')
            ->from('krs')
            ->where('nim', $nim)
            ->order_by('kode_krs', 'desc')
            ->limit('1')
            ->get()->row();
    }

    public function getSignatureDosen($nik) {
        $dosen = $this->db->select('signature')
            ->from('dosen')
            ->where('nik', $nik)
            ->get()->row_object();
        return $dosen ? $dosen->signature : null;
    }

    // ==================== GANTI SANDI HELPERS ====================

    public function updateMahasiswaPassword($nim, $data) {
        return $this->db->where('nim', $nim)->update('mahasiswa', $data);
    }

    public function updateUsersPassword($username, $data) {
        $this->db->where('username', $username)->update('users', $data);
    }

    // ==================== KOMPETENSI HELPERS ====================

    public function getMatakuliahKonsentrasi($kode_nama_kurikulum, $kode_kompetensi) {
        return $this->db->select('mak.nama_matakuliah, mak.kode_matakuliah, kur.semester')
            ->from('kurikulum as kur')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kur.id_matakuliah')
            ->join('matakuliah_kompetensi as mk', 'mk.id_matakuliah=mak.id_matakuliah')
            ->where('kur.kode_nama_kurikulum', $kode_nama_kurikulum)
            ->where('mk.kode_kompetensi', $kode_kompetensi)
            ->get()->result();
    }

    // ==================== KURIKULUM HELPERS ====================

    public function getKompetensiByProdi($kode_prodi) {
        return $this->db->select('makom.id_matakuliah as id_matakuliah, kom.nama_kompetensi as nama')
            ->from('kompetensi as kom')
            ->join('matakuliah_kompetensi as makom', 'makom.kode_kompetensi=kom.kode_kompetensi')
            ->where('kom.kode_program_studi', $kode_prodi)
            ->get()->result_array();
    }

    public function isMahasiswaBaru($nim) {
        $tahun = $this->m_tahun_akademik->get_semester();
        $angkatan_nim = substr($nim, 0, 2);
        $sem = $tahun->semester;
        $tahun_akademik = $tahun->tahun_akademik;

        if ($sem == 0) {
            $semester = ($tahun_akademik - $angkatan_nim) * 2 + 2;
        } else {
            $semester = ($tahun_akademik - $angkatan_nim) * 2 + 1;
        }

        return ($angkatan_nim == $tahun_akademik && $semester == 1);
    }
}
