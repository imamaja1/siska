<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HelperService extends MY_Service
{
    public function getKodeProdi($nim)
    {
        $mah = $this->db->select('program_studi_kode')->from('mahasiswa')->where('nim', $nim)->get()->row_object();
        if (!$mah) return null;
        $prodi = $this->db->select('*, fk.kode_fakultas')
            ->from('program_studi as ps')
            ->join('fakultas fk', 'ps.kode_fakultas=fk.kode_fakultas')
            ->where('ps.kode_program_studi', $mah->program_studi_kode)
            ->get()->row_object();
        return $prodi;
    }

    public function availableKompetensi($nim)
    {
        $prodi = $this->getKodeProdi($nim);
        return ($prodi && $prodi->kompetensi == 'Y');
    }


    public function availableExtensi($nim)
    {
        $angkatan = substr($nim, 0, 2);
        $old_kompetensi = substr($nim, 5, 1);
        $new_kompetensi = substr($nim, 6, 1);

        if ($angkatan < 19) {
            return ($old_kompetensi == '5');
        } else {
            return ($new_kompetensi == '1');
        }
    }

    public function kodeNamaKurikulum($nim)
    {
        $ex = substr($nim, 6, 1);
        $gelombang = substr($nim, 5, 1);
        $angkatan = substr($nim, 0, 2);
        $prodi = $this->getKodeProdi($nim);
        if (!$prodi) return null;

        if ($angkatan < 19) {
            if ($gelombang == '5') {
                $query = $this->db->select('*,nk.kode_nama_kurikulum')
                    ->from('nama_kurikulum as nk')
                    ->join('kurikulum_angkatan ka', 'nk.kode_nama_kurikulum=ka.kode_nama_kurikulum')
                    ->where('substr(angkatan,3,2)', $angkatan)
                    ->where('kode_program_studi', $prodi->kode_program_studi)
                    ->where('ekstensi', 'Y')
                    ->get()->row_object();
            } else {
                $query = $this->db->select('*,nk.kode_nama_kurikulum')
                    ->from('nama_kurikulum as nk')
                    ->join('kurikulum_angkatan ka', 'nk.kode_nama_kurikulum=ka.kode_nama_kurikulum')
                    ->where('substr(angkatan,3,2)', $angkatan)
                    ->where('kode_program_studi', $prodi->kode_program_studi)
                    ->where('ekstensi', 'N')
                    ->get()->row_object();
            }
        } elseif ($angkatan < 24) {
            if ($ex == '1') {
                $query = $this->db->select('*,nk.kode_nama_kurikulum')
                    ->from('nama_kurikulum as nk')
                    ->join('kurikulum_angkatan ka', 'nk.kode_nama_kurikulum=ka.kode_nama_kurikulum')
                    ->where('substr(angkatan,3,2)', $angkatan)
                    ->where('kode_program_studi', $prodi->kode_program_studi)
                    ->where('ekstensi', 'Y')
                    ->get()->row_object();
            } else {
                $query = $this->db->select('*,nk.kode_nama_kurikulum')
                    ->from('nama_kurikulum as nk')
                    ->join('kurikulum_angkatan ka', 'nk.kode_nama_kurikulum=ka.kode_nama_kurikulum')
                    ->where('substr(angkatan,3,2)', $angkatan)
                    ->where('kode_program_studi', $prodi->kode_program_studi)
                    ->where('ekstensi', 'N')
                    ->get()->row_object();
            }
        } else {
            $query = $this->db->select('*,nk.kode_nama_kurikulum')
                ->from('nama_kurikulum as nk')
                ->join('kurikulum_angkatan ka', 'nk.kode_nama_kurikulum=ka.kode_nama_kurikulum')
                ->where('substr(angkatan,3,2)', $angkatan)
                ->where('kode_program_studi', $prodi->kode_program_studi)
                ->get()->row_object();
        }
        return $query ? $query->kode_nama_kurikulum : null;
    }

    public function namaKurikulum($nim)
    {
        $gelombang = substr($nim, 5, 1);
        $ex = substr($nim, 6, 1);
        $angkatan = substr($nim, 0, 2);
        $prodi = $this->getKodeProdi($nim);
        if (!$prodi) return null;

        if ($angkatan < 19) {
            if ($gelombang == '5') {
                $query = $this->db->select('*,nk.kode_nama_kurikulum')
                    ->from('nama_kurikulum as nk')
                    ->join('kurikulum_angkatan ka', 'nk.kode_nama_kurikulum=ka.kode_nama_kurikulum')
                    ->where('substr(angkatan,3,2)', $angkatan)
                    ->where('kode_program_studi', $prodi->kode_program_studi)
                    ->where('ekstensi', 'Y')
                    ->get()->row_object();
            } else {
                $query = $this->db->select('*,nk.kode_nama_kurikulum')
                    ->from('nama_kurikulum as nk')
                    ->join('kurikulum_angkatan ka', 'nk.kode_nama_kurikulum=ka.kode_nama_kurikulum')
                    ->where('substr(angkatan,3,2)', $angkatan)
                    ->where('kode_program_studi', $prodi->kode_program_studi)
                    ->where('ekstensi', 'N')
                    ->get()->row_object();
            }
        } else {
            if ($ex == '1') {
                $query = $this->db->select('*,nk.kode_nama_kurikulum')
                    ->from('nama_kurikulum as nk')
                    ->join('kurikulum_angkatan ka', 'nk.kode_nama_kurikulum=ka.kode_nama_kurikulum')
                    ->where('substr(angkatan,3,2)', $angkatan)
                    ->where('kode_program_studi', $prodi->kode_program_studi)
                    ->where('ekstensi', 'Y')
                    ->get()->row_object();
            } else {
                $query = $this->db->select('*,nk.kode_nama_kurikulum')
                    ->from('nama_kurikulum as nk')
                    ->join('kurikulum_angkatan ka', 'nk.kode_nama_kurikulum=ka.kode_nama_kurikulum')
                    ->where('substr(angkatan,3,2)', $angkatan)
                    ->where('kode_program_studi', $prodi->kode_program_studi)
                    ->where('ekstensi', 'N')
                    ->get()->row_object();
            }
            if (!$query) {
                $query = $this->db->select('*,nk.kode_nama_kurikulum')
                    ->from('nama_kurikulum as nk')
                    ->join('kurikulum_angkatan ka', 'nk.kode_nama_kurikulum=ka.kode_nama_kurikulum')
                    ->where('substr(angkatan,3,2)', $angkatan)
                    ->where('kode_program_studi', $prodi->kode_program_studi)
                    ->where('ekstensi', 'N')
                    ->get()->row_object();
            }
        }
        return $query;
    }

    public function tahunAkademik()
    {
        return $this->db->select('*')->from('tahun_akademik')->where('status', 'A')->get()->row_object();
    }

    public function semester()
    {
        $ta = $this->tahunAkademik();
        return $ta ? $ta->semester : null;
    }

    public function dataPenilaian($nim, $semester = null)
    {
        $tahun = substr($nim, 0, 2);
        $tahun_perubahan = $this->db->select('*')
            ->from('krs')
            ->where('nim', $nim)
            ->where('kode_tahun_akademik >', 25)
            ->get()->num_rows();
        if ($tahun >= 22 || $tahun_perubahan > 0) {
            $tmp = $this->db->select('*')
                ->from('sistem_penilaian_detail')
                ->where('kode_sistem_penilaian', 1)
                ->get()->result_array();
        } else {
            $tmp = $this->db->select('*')
                ->from('stup_grade')
                ->where('kode_nama_kurikulum', 14)
                ->get()->result_array();
        }
        return $tmp;
    }

    public function sistemPenilaian($nim)
    {
        $kode_nama_kurikulum = $this->kodeNamaKurikulum($nim);
        if (!$kode_nama_kurikulum) return [];
        $penilaian = $this->db->select('*')
            ->from('sistem_penilaian as sp')
            ->join('nama_kurikulum as nk', 'nk.kode_nama_kurikulum=sp.kode_nama_kurikulum')
            ->join('sistem_penilaian_detail as spd', 'sp.kode_sistem_penilaian=spd.kode_sistem_penilaian')
            ->where('nk.kode_nama_kurikulum', $kode_nama_kurikulum)
            ->get()->result_array();
        return $penilaian;
    }

    public function stupGrade($kode_kurikulum_angkatan, $semester = null)
    {
        if ($semester === null) return false;
        $stup = $this->db->select('*')
            ->from('nama_kurikulum as nk')
            ->join('stup_grade as sg', 'nk.kode_nama_kurikulum=sg.kode_nama_kurikulum')
            ->join('kurikulum_angkatan as ka', 'ka.kode_nama_kurikulum=nk.kode_nama_kurikulum')
            ->where('kode_kurikulum_angkatan', $kode_kurikulum_angkatan)
            ->where('ka.semester_stup_grade <= ', $semester)
            ->get()->result_array();
        return count($stup) > 0 ? $stup : false;
    }

    public function rbacCek($con, $id)
    {
        $this->load->model('rbac_model');
        $data = $this->rbac_model->get_rbac($con, $id);
        return count($data) > 0;
    }

    public function rbacList($id)
    {
        $this->load->model('rbac_model');
        return $this->rbac_model->get_rbac_role($id);
    }

    public function getKodeMatakuliahKkp()
    {
        $query = $this->db->select('mak.kode_matakuliah')
            ->from('kurikulum as kur')
            ->join('matakuliah as mak', 'kur.id_matakuliah=mak.id_matakuliah')
            ->where('(nama_matakuliah like "%kuliah kerja profesi%" or nama_matakuliah like "%magang%" or nama_matakuliah like "%KKP%" or nama_matakuliah like "%KKN%" or nama_matakuliah like "%Seminar Proposal%" or nama_matakuliah like "%KERJA PRAKTIK%" or nama_matakuliah like "%Thesis%")')
            ->group_by('mak.kode_matakuliah')
            ->get()->result_object();
        $data = [];
        foreach ($query as $row) {
            $data[] = $row->kode_matakuliah;
        }
        return $data;
    }

    public function getKodeMatakuliahKkpSkripsi()
    {
        $query = $this->db->select('mak.kode_matakuliah')
            ->from('kurikulum as kur')
            ->join('matakuliah as mak', 'kur.id_matakuliah=mak.id_matakuliah')
            ->where('(nama_matakuliah like "%kuliah kerja profesi%" or nama_matakuliah like "%Proposal%" or nama_matakuliah like "%magang%" or nama_matakuliah like "%skripsi%" or nama_matakuliah like "%tugas akhir%" or nama_matakuliah like "%KKP%" or nama_matakuliah like "%proyek akhir%" or nama_matakuliah like "%Thesis%")')
            ->group_by('mak.kode_matakuliah')
            ->get()->result_object();
        $data = [];
        foreach ($query as $row) {
            $data[] = $row->kode_matakuliah;
        }
        return $data;
    }

    public function getKodeMatakuliahSkripsi()
    {
        $query = $this->db->select('mak.kode_matakuliah,mak.nama_matakuliah')
            ->from('kurikulum as kur')
            ->join('matakuliah as mak', 'kur.id_matakuliah=mak.id_matakuliah')
            ->where('(nama_matakuliah like "%skripsi%" or nama_matakuliah like "%Proposal%" or nama_matakuliah like "%tugas akhir%" or nama_matakuliah like "%tugas akir%" or nama_matakuliah like "%proyek akhir%" or nama_matakuliah like "%Final Assignment%" or nama_matakuliah like "%Skripsi%" or nama_matakuliah like "%Thesis%")')
            ->group_by('mak.kode_matakuliah')
            ->get()->result_object();
        $data = [];
        foreach ($query as $row) {
            $data[] = $row->kode_matakuliah;
        }
        return $data;
    }

    public function getKodeNamaKurikulumByProdiAngkatan($kode_program_studi, $angkatan)
    {
        $query = $this->db->select('nk.kode_nama_kurikulum')
            ->from('nama_kurikulum as nk')
            ->join('kurikulum_angkatan as ka', 'nk.kode_nama_kurikulum=ka.kode_nama_kurikulum')
            ->where('mid(angkatan,3,2)', $angkatan)
            ->where('kode_program_studi', $kode_program_studi)
            ->where('ekstensi', 'N')
            ->get()->row_object();
        return $query ? $query->kode_nama_kurikulum : null;
    }

    public function getMakulKkpByKodeNamaKurikulum($kode_nama_kurikulum)
    {
        return $this->db->select('mak.id_matakuliah, nama_matakuliah, mak.kode_matakuliah')
            ->from('kurikulum as kur')
            ->join('matakuliah as mak', 'kur.id_matakuliah=mak.id_matakuliah')
            ->where('kur.kode_nama_kurikulum', $kode_nama_kurikulum)
            ->where('(nama_matakuliah like "%kuliah kerja profesi%" or nama_matakuliah like "%magang%" or nama_matakuliah like "%KKP%")')
            ->get()->row_object();
    }

    public function getMatakuliah($id_matakuliah)
    {
        return $this->db->select('*')->from('matakuliah')->where('id_matakuliah', $id_matakuliah)->get()->row_object();
    }

    public function isKaprodi($kode_dosen)
    {
        $cek = $this->db->select('kode_program_studi')
            ->from('kaprodi')
            ->where('kode_dosen', $kode_dosen)
            ->get()->row_object();
        return !empty($cek);
    }

    public function isDekan($kode_dosen)
    {
        $cek = $this->db->select('kode_fakultas')
            ->from('fakultas')
            ->where('dekan', $kode_dosen)
            ->get()->row_object();
        return !empty($cek);
    }

    public function signaturNik($nik)
    {
        $cek = $this->db->select('signature')
            ->from('dosen')
            ->where('nik', $nik)
            ->get()->row_object();
        return $cek ? $cek->signature : null;
    }

    public function bodoKop($nim)
    {
        $prodi = $this->getKodeProdi($nim);
        $res = [];

        if (substr($nim, 2, 4) == '0108' && $prodi->kode_fakultas == '08' || substr($nim, 2, 4) == '0402' && $prodi->kode_fakultas == '08') {
            $res["kop"] = "PAS.png";
            $res["nama_fakultas"] = "Program Pascasarjana";
            $res["dekan"] = "Dr. Neny Sulistianingsih, M.Kom";
            $res["nik"] = "15.7.211";
        } else {
            switch ($prodi->kode_fakultas) {
                case '01':
                    $res = ["kop" => "FT.png", "nama_fakultas" => "Fakultas Teknik", "dekan" => "Dr. Helna Wardhana, S.Kom, M.Kom", "nik" => "98.7.99"]; break;
                case '02':
                    $res = ["kop" => "FIB.png", "nama_fakultas" => "Fakultas Humaniora, Hukum dan Pariwisata", "dekan" => "Dr. Titik Ceriyani Miswaty, M.Pd", "nik" => "15.6.218"]; break;
                case '03':
                    $res = ["kop" => "FK.png", "nama_fakultas" => "Fakultas Kesehatan", "dekan" => "Baiq Fitria Rahmiati, S.Gz, M.Si", "nik" => "18.6.303"]; break;
                case '04':
                    $res = ["kop" => "FEB.png", "nama_fakultas" => "Fakultas Ekonomi dan Bisnis", "dekan" => "Rini Anggriani, S.E., M.M", "nik" => "19.6.353"]; break;
                case '05':
                    $res = ["kop" => "FSD.png", "nama_fakultas" => "Fakultas Seni dan Desain", "dekan" => "Christofer Satria, S.Sn., M.Sn.", "nik" => "17.6.296"]; break;
                case '06':
                    $res = ["kop" => "FH.png", "nama_fakultas" => "Fakultas Hukum", "dekan" => "Dr. Titik Ceriyani Miswaty, M.Pd", "nik" => "15.6.218"]; break;
                case '07':
                    $res = ["kop" => "FP.png", "nama_fakultas" => "Fakultas Pendidikan", "dekan" => "Dr. Titik Ceriyani Miswaty, M.Pd", "nik" => "15.6.218"]; break;
                case '08':
                    $res = ["kop" => "FT.png", "nama_fakultas" => "Pascasarjana", "dekan" => "Dr. Neny Sulistianingsih, M.Kom", "nik" => "15.7.211"]; break;
                case '09':
                    $res = ["kop" => "FKD.png", "nama_fakultas" => "Kedokteran", "dekan" => "dr. Karina Anindita, M.Biomed, Sp.PD., FINASIM", "nik" => "24.6.718"]; break;
            }
        }
        return $res;
    }

    public function pembayaranMahasiswa($nim)
    {
        $kode_tahun_akademik = $this->tahunAkademik();
        if (!$kode_tahun_akademik) return null;
        return $this->db->select('*')
            ->from('status_perkuliahan')
            ->where('nim', $nim)
            ->where('kode_tahun_akademik', $kode_tahun_akademik->kode_tahun_akademik)
            ->get()->row_object();
    }

    public function block($nim)
    {
        $block = $this->db->where('nim', $nim)->get('block')->row_object();
        return $block ? true : false;
    }

    public function cekKomentarRevisi($kelas_id)
    {
        $komentar = $this->db->where('kelas_id', $kelas_id)->where('isian', 'R')->get('kelas_validasi')->row_object();
        return $komentar ? true : false;
    }

    public function mkHidden()
    {
        return $this->db->select('id_matakuliah')
            ->from('matakuliah')
            ->like('nama_matakuliah', 'Data Science')
            ->get()->result_array();
    }

    public function prodiAktifKrs($kode_program_studi)
    {
        $ta = $this->tahunAkademik();
        if (!$ta) return null;
        if ($kode_program_studi == '2') {
            return $ta->kode_tahun_akademik + 1;
        }
        return $ta->kode_tahun_akademik;
    }
}
