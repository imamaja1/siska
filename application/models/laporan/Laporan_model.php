<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array(
            'akademik/Krs_model',
            'akademik/Khs_model',
            'jurusan/m_tahun_akademik',
        ));
    }

    public function aktif_perkuliahan($kode_tahun_akademik, $angkatan, $kode_program_studi)
    {

            $query = $this->db->select('krs.nim, mah.nama_mahasiswa,sum(SUBSTR(mak.kode_matakuliah,5,1)) as jumlah_sks')
                ->from('krs')
                ->join('krs_detail as krd', 'krs.kode_krs=krd.kode_krs')
                ->join('mahasiswa as mah', 'krs.nim=mah.nim')
                ->join('matakuliah as mak', 'krd.id_matakuliah=mak.id_matakuliah')
                ->join('konsultasi_perwalian as kp', 'kp.nim=krs.nim and kp.kode_tahun_akademik=krs.kode_tahun_akademik')
                ->where('mah.program_studi_kode', $kode_program_studi)
                ->where('substr(krs.nim,1,2)', $angkatan)
                ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
                ->where('kp.status_cetak', 'A')
                ->group_by('krs.nim')
                ->get()->result_object();

        return $query;
    }
      
  	public function aktif_perkuliahan_kprodi($kode_tahun_akademik, $angkatan, $kode_program_studi)
    {
        $query = $this->db->select('krs.nim, mah.nama_mahasiswa, mah.telepon, kp.status_cetak, d.nama_dosen')
            ->from('krs')
            ->join('krs_detail as krd', 'krs.kode_krs=krd.kode_krs')
            ->join('mahasiswa as mah', 'krs.nim=mah.nim')
            ->join('matakuliah as mak', 'krd.id_matakuliah=mak.id_matakuliah')
            ->join('konsultasi_perwalian as kp', 'kp.nim=krs.nim and kp.kode_tahun_akademik=krs.kode_tahun_akademik')
          	->join('perwalian as p', 'mah.nim=p.nim')
            ->join('dosen as d', 'p.kode_dosen=d.kode_dosen')
            ->where('mah.program_studi_kode', $kode_program_studi)
            ->where('substr(krs.nim,1,2)', $angkatan)
            ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
//            ->where('kp.status_cetak', 'A')
            ->group_by('krs.nim')
            ->get();

        return $query;
    }

    public function tidak_aktif_perangkatan_perprodi($angkatan, $kode_program_studi, $nim_aktif)
    {
        return $query = $this->db->select('mah.nim, nama_mahasiswa, telepon, d.nama_dosen')
            ->from('mahasiswa as mah')
            ->join('perwalian as p', 'mah.nim=p.nim')
            ->join('dosen as d', 'p.kode_dosen=d.kode_dosen')
            ->where('mah.program_studi_kode', $kode_program_studi)
            ->where('substr(mah.nim,1,2)', $angkatan)
            ->where_not_in('mah.nim', $nim_aktif)
            ->get();
    }


    public function rekap_ipk_count($kode_tahun_akademik, $angkatan, $kode_program_studi)
    {
        $mahasiswa = $this->db->select('mah.nim, nama_mahasiswa, status_pendaftaran')
            ->from('mahasiswa as mah')
            ->where('mah.program_studi_kode', $kode_program_studi)
            ->where('substr(mah.nim,1,2)', $angkatan)
          
            //cek kuliah aktif mahasiswa pada semester berlangsung
            ->join('status_perkuliahan', 'status_perkuliahan.nim=mah.nim')
            ->where('status_perkuliahan.status_perkuliahan', 'A')
            ->where('status_perkuliahan.kode_tahun_akademik', $kode_tahun_akademik)

            ->join('krs', 'krs.nim=mah.nim')
            ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
          	//end cek kuliah aktif
          
          // agar tidak double di nim
          	->group_by('krs.nim')
          
            ->get()->result_object();

        return count($mahasiswa);
    }

    //    public function rekap_ipk($kode_tahun_akademik, $angkatan, $kode_program_studi, $limit, $offset)
    public function rekap_ipk($kode_tahun_akademik, $angkatan, $kode_program_studi)
    {
        $mahasiswa = $this->db->select('mah.nim, nama_mahasiswa, status_pendaftaran')
            ->from('mahasiswa as mah')
            //                keoerluan data lulusan
//                ->where('mah.status','N')
//                end keperluan
          
            //cek kuliah aktif mahasiswa pada semester berlangsung
            ->join('status_perkuliahan', 'status_perkuliahan.nim=mah.nim')
            ->where('status_perkuliahan.status_perkuliahan', 'A')
            ->where('status_perkuliahan.kode_tahun_akademik', $kode_tahun_akademik)

            ->join('krs', 'krs.nim=mah.nim')
            ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
          	//end cek kuliah aktif
          
            ->where('mah.program_studi_kode', $kode_program_studi)
            ->where('substr(mah.nim,1,2)', $angkatan)
          // agar tidak double nim untuk mhs transfer lanjut
            ->group_by('krs.nim')
//            ->limit($limit)
//            ->offset($offset)
            ->get()->result_object();

        $i = 0;
        foreach ($mahasiswa as $mah) {
            $kode_nama_kurikulum = kode_nama_kurikulum($mah->nim);
//            $ipk = $this->ipk($mah->nim,$kode_nama_kurikulum);
            $ipk = $this->ipok($mah->nim, $kode_nama_kurikulum, $kode_tahun_akademik);
            $semester = $this->semester_saat_ini($mah->nim, $kode_tahun_akademik);
            $ip = $this->ip($mah->nim, $semester, $mah->status_pendaftaran);
            $data[$i]['nama_mahasiswa'] = $mah->nama_mahasiswa;
            $data[$i]['nim'] = $mah->nim;
            $data[$i]['ipk'] = $ipk['ipk'];
            $data[$i]['total_sks'] = $ipk['total_sks'];
            $data[$i]['ip'] = $ip['ip'];
            $data[$i]['sks'] = $ip['sks'];
            $i++;
        }

        return $data;
    }

    public function rekap_all_ipk($kode_tahun_akademik, $angkatan, $kode_program_studi)
    {
        $mahasiswa = $this->db->select('mah.nim, nama_mahasiswa, status_pendaftaran')
            ->from('mahasiswa as mah')
            //                keoerluan data lulusan
//                ->where('mah.status','N')
//                end keperluan
          
            //cek kuliah aktif mahasiswa pada semester berlangsung
            ->join('status_perkuliahan', 'status_perkuliahan.nim=mah.nim')
            ->where('status_perkuliahan.status_perkuliahan', 'A')
            ->where('status_perkuliahan.kode_tahun_akademik', $kode_tahun_akademik)

            ->join('krs', 'krs.nim=mah.nim')
            ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
          	//end cek kuliah aktif
          
            ->where('mah.program_studi_kode', $kode_program_studi)
            ->where('substr(mah.nim,1,2)', $angkatan)
                    // agar tidak double nim untuk mhs transfer lanjut
            ->group_by('krs.nim')
            ->get()->result_object();

        $i = 0;
        foreach ($mahasiswa as $mah) {
            $kode_nama_kurikulum = kode_nama_kurikulum($mah->nim);
//            $ipk = $this->ipk($mah->nim,$kode_nama_kurikulum);
            $ipk = $this->ipok($mah->nim, $kode_nama_kurikulum, $kode_tahun_akademik);
            $semester = $this->semester_saat_ini($mah->nim, $kode_tahun_akademik);
            $ip = $this->ip($mah->nim, $semester, $mah->status_pendaftaran);
            $data[$i]['nama_mahasiswa'] = $mah->nama_mahasiswa;
            $data[$i]['nim'] = $mah->nim;
            $data[$i]['ipk'] = $ipk['ipk'];
            $data[$i]['total_sks'] = $ipk['total_sks'];
            $data[$i]['ip'] = $ip['ip'];
            $data[$i]['sks'] = $ip['sks'];
            $i++;
        }

        return $data;
    }

    public function ipk_rata_count($kode_tahun_akademik, $angkatan, $kode_program_studi)
    {
        $mk_skripsi = get_kode_matakuliah_skripsi();
        $mk_kkp = get_kode_matakuliah_kkp();
        $normal = $this->db->select('mah.status_pendaftaran,mah.nim, nama_mahasiswa, jenis_kelamin, (sum(sks_teori) + sum(sks_praktek) + sum(sks_praktikum)) as total_sks, sum(sks_praktikum) as praktikum, "" as skripsi, "" as kkp')
                ->from('krs')
                ->join('krs_detail as kd','kd.kode_krs=krs.kode_krs')
                ->join('mahasiswa as mah','krs.nim=mah.nim')
                ->join('matakuliah as mak','mak.id_matakuliah=kd.id_matakuliah')
                ->join('status_perkuliahan as sp',"krs.nim=sp.nim AND sp.kode_tahun_akademik=".$this->db->escape($kode_tahun_akademik))
                ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
                ->where('mid(mah.nim,1,2)', $angkatan)
//                ->where('sp.pengumpulan_krs', '1')
                ->where('mah.program_studi_kode', $kode_program_studi)
                ->where_not_in('kd.status', ['K'])
                ->group_by('mah.nim')
                ->get_compiled_select();

        $skripsi =  $this->db->select('mah.status_pendaftaran,mah.nim, nama_mahasiswa, jenis_kelamin, "" as total_sks, "" as praktikum, (sum(sks_teori) + sum(sks_praktek)) as skripsi, "" as kkp')
                ->from('krs')
                ->join('krs_detail as kd','kd.kode_krs=krs.kode_krs')
                ->join('mahasiswa as mah','krs.nim=mah.nim')
                ->join('matakuliah as mak','mak.id_matakuliah=kd.id_matakuliah')
                ->join('status_perkuliahan as sp',"krs.nim=sp.nim AND sp.kode_tahun_akademik=".$this->db->escape($kode_tahun_akademik))
                ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
                ->where('mid(mah.nim,1,2)', $angkatan)
//                ->where('sp.pengumpulan_krs', '1')
                ->where('mah.program_studi_kode', $kode_program_studi)
                ->where_not_in('kd.status', ['K'])
                ->where_in('mak.kode_matakuliah', $mk_skripsi)
                ->group_by('mah.nim')
                ->get_compiled_select();
        $kkp =  $this->db->select('mah.status_pendaftaran,mah.nim, nama_mahasiswa, jenis_kelamin, "" as total_sks, "" as praktikum, "" as skripsi, (sum(sks_teori) + sum(sks_praktek)) as kkp')
                ->from('krs')
                ->join('krs_detail as kd','kd.kode_krs=krs.kode_krs')
                ->join('mahasiswa as mah','krs.nim=mah.nim')
                ->join('matakuliah as mak','mak.id_matakuliah=kd.id_matakuliah')
                ->join('status_perkuliahan as sp',"krs.nim=sp.nim AND sp.kode_tahun_akademik=".$this->db->escape($kode_tahun_akademik))
                ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
                ->where('mid(mah.nim,1,2)', $angkatan)
//                ->where('sp.pengumpulan_krs', '1')
                ->where('mah.program_studi_kode', $kode_program_studi)
                ->where_not_in('kd.status', ['K'])
                ->where_in('mak.kode_matakuliah', $mk_kkp)
                ->group_by('mah.nim')
                ->get_compiled_select();

        $sql = $this->db->query("SELECT nim, ANY_VALUE(status_pendaftaran) as status_pendaftaran, ANY_VALUE(nama_mahasiswa) as nama_mahasiswa, ANY_VALUE(jenis_kelamin) as jenis_kelamin, SUM(total_sks) as total_sks, SUM(praktikum) as praktikum, SUM(skripsi) as skripsi, SUM(kkp) as kkp FROM ($normal UNION $skripsi UNION $kkp) as m GROUP BY nim")->result();

        return count($sql);
    }

    public function ipk_rata($kode_tahun_akademik, $angkatan, $kode_program_studi, $limit, $offset)
    {
        $mk_skripsi = get_kode_matakuliah_skripsi();
        $mk_kkp = get_kode_matakuliah_kkp();
        $normal = $this->db->select('mah.status_pendaftaran,mah.nim, nama_mahasiswa, jenis_kelamin, (sum(sks_teori) + sum(sks_praktek) + sum(sks_praktikum)) as total_sks, sum(sks_praktikum) as praktikum, "" as skripsi, "" as kkp')
                ->from('krs')
                ->join('krs_detail as kd','kd.kode_krs=krs.kode_krs')
                ->join('mahasiswa as mah','krs.nim=mah.nim')
                ->join('matakuliah as mak','mak.id_matakuliah=kd.id_matakuliah')
                ->join('status_perkuliahan as sp',"krs.nim=sp.nim AND sp.kode_tahun_akademik=".$this->db->escape($kode_tahun_akademik))
                ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
                ->where('mid(mah.nim,1,2)', $angkatan)
//                ->where('sp.pengumpulan_krs', '1')
                ->where('mah.program_studi_kode', $kode_program_studi)
                ->where_not_in('kd.status', ['K'])
                ->group_by('mah.nim')
                ->get_compiled_select();

        $skripsi =  $this->db->select('mah.status_pendaftaran,mah.nim, nama_mahasiswa, jenis_kelamin, "" as total_sks, "" as praktikum, (sum(sks_teori) + sum(sks_praktek)) as skripsi, "" as kkp')
                ->from('krs')
                ->join('krs_detail as kd','kd.kode_krs=krs.kode_krs')
                ->join('mahasiswa as mah','krs.nim=mah.nim')
                ->join('matakuliah as mak','mak.id_matakuliah=kd.id_matakuliah')
                ->join('status_perkuliahan as sp',"krs.nim=sp.nim AND sp.kode_tahun_akademik=".$this->db->escape($kode_tahun_akademik))
                ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
                ->where('mid(mah.nim,1,2)', $angkatan)
//                ->where('sp.pengumpulan_krs', '1')
                ->where('mah.program_studi_kode', $kode_program_studi)
                ->where_not_in('kd.status', ['K'])
                ->where_in('mak.kode_matakuliah', $mk_skripsi)
                ->group_by('mah.nim')
                ->get_compiled_select();
        $kkp =  $this->db->select('mah.status_pendaftaran,mah.nim, nama_mahasiswa, jenis_kelamin, "" as total_sks, "" as praktikum, "" as skripsi, (sum(sks_teori) + sum(sks_praktek)) as kkp')
                ->from('krs')
                ->join('krs_detail as kd','kd.kode_krs=krs.kode_krs')
                ->join('mahasiswa as mah','krs.nim=mah.nim')
                ->join('matakuliah as mak','mak.id_matakuliah=kd.id_matakuliah')
                ->join('status_perkuliahan as sp',"krs.nim=sp.nim AND sp.kode_tahun_akademik=".$this->db->escape($kode_tahun_akademik))
                ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
                ->where('mid(mah.nim,1,2)', $angkatan)
//                ->where('sp.pengumpulan_krs', '1')
                ->where('mah.program_studi_kode', $kode_program_studi)
                ->where_not_in('kd.status', ['K'])
                ->where_in('mak.kode_matakuliah', $mk_kkp)
                ->group_by('mah.nim')
                ->get_compiled_select();

        $sql = $this->db->query("SELECT nim, ANY_VALUE(status_pendaftaran) as status_pendaftaran, ANY_VALUE(nama_mahasiswa) as nama_mahasiswa, ANY_VALUE(jenis_kelamin) as jenis_kelamin, SUM(praktikum) as praktikum, SUM(skripsi) as skripsi, SUM(kkp) as kkp FROM ($normal UNION $skripsi UNION $kkp) as m GROUP BY nim limit ".(int)$limit." offset ".(int)$offset)->result();
        $i = 0;
        foreach ($sql as $mah) {
            $kode_nama_kurikulum = kode_nama_kurikulum($mah->nim);
//            $ipk = $this->ipk($mah->nim,$kode_nama_kurikulum);
            $ipk = $this->ipok($mah->nim, $kode_nama_kurikulum, $kode_tahun_akademik);
            $semester = $this->semester_saat_ini($mah->nim, $kode_tahun_akademik);
            $ip = $this->ip($mah->nim, $semester, $mah->status_pendaftaran);
            $data[$i]['nama_mahasiswa'] = $mah->nama_mahasiswa;
            $data[$i]['nim'] = $mah->nim;
            $data[$i]['jenis_kelamin'] = $mah->jenis_kelamin;
            $data[$i]['praktikum'] = $mah->praktikum;
            $data[$i]['skripsi'] = $mah->skripsi;
            $data[$i]['kkp'] = $mah->kkp;
            $data[$i]['ipk'] = $ipk['ipk'];
            $data[$i]['total_sks'] = $ipk['total_sks'];
            $data[$i]['ip'] = $ip['ip'];
            $i++;
        }

        return $data;
    }

    public function all_ipk_rata($kode_tahun_akademik, $angkatan, $kode_program_studi)
    {
        $mk_skripsi = get_kode_matakuliah_skripsi();
        $mk_kkp = get_kode_matakuliah_kkp();
        $normal = $this->db->select('mah.status_pendaftaran,mah.nim, nama_mahasiswa, jenis_kelamin, (sum(sks_teori) + sum(sks_praktek) + sum(sks_praktikum)) as total_sks, sum(sks_praktikum) as praktikum, "" as skripsi, "" as kkp')
                ->from('krs')
                ->join('krs_detail as kd','kd.kode_krs=krs.kode_krs')
                ->join('mahasiswa as mah','krs.nim=mah.nim')
                ->join('matakuliah as mak','mak.id_matakuliah=kd.id_matakuliah')
                ->join('status_perkuliahan as sp',"krs.nim=sp.nim AND sp.kode_tahun_akademik=".$this->db->escape($kode_tahun_akademik))
                ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
                ->where('mid(mah.nim,1,2)', $angkatan)
//                ->where('sp.pengumpulan_krs', '1')
                ->where('mah.program_studi_kode', $kode_program_studi)
                ->where_not_in('kd.status', ['K'])
                ->group_by('mah.nim')
                ->get_compiled_select();

        $skripsi =  $this->db->select('mah.status_pendaftaran,mah.nim, nama_mahasiswa, jenis_kelamin, "" as total_sks, "" as praktikum, (sum(sks_teori) + sum(sks_praktek)) as skripsi, "" as kkp')
                ->from('krs')
                ->join('krs_detail as kd','kd.kode_krs=krs.kode_krs')
                ->join('mahasiswa as mah','krs.nim=mah.nim')
                ->join('matakuliah as mak','mak.id_matakuliah=kd.id_matakuliah')
                ->join('status_perkuliahan as sp',"krs.nim=sp.nim AND sp.kode_tahun_akademik=".$this->db->escape($kode_tahun_akademik))
                ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
                ->where('mid(mah.nim,1,2)', $angkatan)
//                ->where('sp.pengumpulan_krs', '1')
                ->where('mah.program_studi_kode', $kode_program_studi)
                ->where_not_in('kd.status', ['K'])
                ->where_in('mak.kode_matakuliah', $mk_skripsi)
                ->group_by('mah.nim')
                ->get_compiled_select();
        $kkp =  $this->db->select('mah.status_pendaftaran,mah.nim, nama_mahasiswa, jenis_kelamin, "" as total_sks, "" as praktikum, "" as skripsi, (sum(sks_teori) + sum(sks_praktek)) as kkp')
                ->from('krs')
                ->join('krs_detail as kd','kd.kode_krs=krs.kode_krs')
                ->join('mahasiswa as mah','krs.nim=mah.nim')
                ->join('matakuliah as mak','mak.id_matakuliah=kd.id_matakuliah')
                ->join('status_perkuliahan as sp',"krs.nim=sp.nim AND sp.kode_tahun_akademik=".$this->db->escape($kode_tahun_akademik))
                ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
                ->where('mid(mah.nim,1,2)', $angkatan)
//                ->where('sp.pengumpulan_krs', '1')
                ->where('mah.program_studi_kode', $kode_program_studi)
                ->where_not_in('kd.status', ['K'])
                ->where_in('mak.kode_matakuliah', $mk_kkp)
                ->group_by('mah.nim')
                ->get_compiled_select();

        $sql = $this->db->query("SELECT nim, ANY_VALUE(status_pendaftaran) as status_pendaftaran, ANY_VALUE(nama_mahasiswa) as nama_mahasiswa, ANY_VALUE(jenis_kelamin) as jenis_kelamin, SUM(praktikum) as praktikum, SUM(skripsi) as skripsi, SUM(kkp) as kkp FROM ($normal UNION $skripsi UNION $kkp) as m GROUP BY nim")->result();
        $i = 0;
        foreach ($sql as $mah) {
            $kode_nama_kurikulum = kode_nama_kurikulum($mah->nim);
//            $ipk = $this->ipk($mah->nim,$kode_nama_kurikulum);
            $ipk = $this->ipok($mah->nim, $kode_nama_kurikulum, $kode_tahun_akademik);
            $semester = $this->semester_saat_ini($mah->nim, $kode_tahun_akademik);
            $ip = $this->ip($mah->nim, $semester, $mah->status_pendaftaran);
            $data[$i]['nama_mahasiswa'] = $mah->nama_mahasiswa;
            $data[$i]['nim'] = $mah->nim;
            $data[$i]['jenis_kelamin'] = $mah->jenis_kelamin;
            $data[$i]['praktikum'] = $mah->praktikum;
            $data[$i]['skripsi'] = $mah->skripsi;
            $data[$i]['kkp'] = $mah->kkp;
            $data[$i]['ipk'] = $ipk['ipk'];
            $data[$i]['total_sks'] = $ipk['total_sks'];
            $data[$i]['ip'] = $ip['ip'];
            $i++;
        }

        return $data;
    }

//    FUNGSI PENDUKUNG
    public function ipok($nim, $kode_nama_kurikulum, $kode_tahun_akademik)
    {
        $sub = $this->db->select('semester,nim,status,kd.id_matakuliah,nilai_harian,nilai_uts,nilai_akhir,nilai_uas, (sks_teori+sks_praktek+sks_praktikum) as sks, mak.kode_matakuliah')
            ->from('krs')
            ->join('krs_detail as kd', 'krs.kode_krs=kd.kode_krs')
            ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kd.id_matakuliah')
            ->where('nim', $nim)
            ->where('krs.kode_tahun_akademik <=', $kode_tahun_akademik)
            ->where('nilai_akhir IS NOT NULL')
            ->order_by('nilai_akhir', 'DESC')->get_compiled_select();
        $data = $this->db->select('id_matakuliah, ANY_VALUE(semester) as semester, ANY_VALUE(nilai_akhir) as nilai_akhir, ANY_VALUE(sks) as sks')
            ->from("($sub) as sub", false)
            ->group_by('id_matakuliah')
            ->get()->result();
        $sksn = 0;
        $total_sks = 0;
        foreach ($data as $row) {
//            $sistem_penilaian = $this->sistem_penilaian($nim,$row->semester);
            $data_penilaian = data_penilaian($nim, $row->semester);
//            if (stup_grade($kode_nama_kurikulum, $row->semester)) {
//                $data_penilaian = stup_grade($kode_nama_kurikulum, $row->semester);
//            } else {
//                $data_penilaian = sistem_penilaian($nim);
//            }
            $total_sks = $total_sks + $row->sks;
            foreach ($data_penilaian as $item) {
                if ($row->nilai_akhir >= $item['nilai_minimum'] && $item['nilai_maksimum'] >= $row->nilai_akhir) {
                    $sksn = $sksn + ($item['bobot_nilai'] * $row->sks);
                } else {
                    $sksn = $sksn + 0;
                }
            }
        }
//        $ipk = $sksn/$total_sks;
//        return number_format($ipk,2);
        if ($total_sks == 0) {
            $res['total_sks'] = $total_sks;
            $res['ipk'] = 0;
        } else {
            $res['total_sks'] = $total_sks;
            $res['ipk'] = number_format($sksn / $total_sks, 2);
        }

        return $res;
    }

    public function ipk($nim, $kode_nama_kurikulum)
    {
        $j = 1;
        $n = 0;
        $t_sks = 0;
        $t_sksn = 0;
        for ($j = 1; $j <= 8; $j++) {
            $data_kurikulum = $this->db->select('*')
                ->from('kurikulum as kur')
                ->join('matakuliah as mak', 'kur.id_matakuliah=mak.id_matakuliah')
                ->where('kode_nama_kurikulum', $kode_nama_kurikulum)
                ->where('semester', $j)
                ->order_by('substr(mak.kode_matakuliah,-4,4) ASC')
                ->get()->result();
            if (count($data_kurikulum) <= 0) {
                break;
            }
            $kode_krs = $this->db->get_where('krs', array('nim' => $nim))->result();
            $data[$n]['semester'] = $j;
            $i = 0;
            foreach ($data_kurikulum as $row) {
                $cek = $this->db->select('*,count(kd.kode_krs_detail) as jumlah')
                    ->from('krs as k')
                    ->join('krs_detail as kd', 'k.kode_krs=kd.kode_krs')
                    ->join('khs_detail as hd', 'kd.kode_krs_detail=hd.kode_krs_detail')
                    ->join('matakuliah as m', 'kd.id_matakuliah=m.id_matakuliah')
                    ->where('hd.nilai_akhir IS NOT NULL')
                    ->where('m.kode_matakuliah', $row->kode_matakuliah)
                    ->where('k.nim', $nim)
                    ->get()->row_object();
                $data[$n]['data_nilai'][$i]['jumlah_data'] = $cek->jumlah;
                $data[$n]['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
                $data[$n]['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;

                $sks = $row->sks_teori + $row->sks_praktek + $row->sks_praktikum;
                if ($cek->jumlah == 1) {
                    if (stup_grade($kode_nama_kurikulum, $cek->semester)) {
                        $data_penilaian = stup_grade($kode_nama_kurikulum, $cek->semester);
                    } else {
                        $data_penilaian = sistem_penilaian($nim);
                    }
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
                    $lebih = $this->db->select('*')
                        ->from('krs')
                        ->join('krs_detail as kd', 'krs.kode_krs=kd.kode_krs')
                        ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
                        ->join('matakuliah as mak', 'kd.id_matakuliah=mak.id_matakuliah')
                        ->where('nim', $nim)
                        ->where('mak.kode_matakuliah', $row->kode_matakuliah)
                        ->order_by('nilai_akhir', 'DESC')
                        ->limit(1)
                        ->get()->row_object();
                    if (stup_grade($kode_nama_kurikulum, $lebih->semester)) {
                        $data_penilaian = stup_grade($kode_nama_kurikulum, $lebih->semester);
                    } else {
                        $data_penilaian = sistem_penilaian($nim);
                    }
                    $data[$n]['data_nilai'][$i]['sks'] = $sks;
                    $nilai_akhir = $lebih->nilai_akhir * 1;
                    foreach ($data_penilaian as $key) {
                        if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
                            $data[$n]['data_nilai'][$i]['grade'] = $key['grade'];
                            $data[$n]['data_nilai'][$i]['sksn'] = $key['bobot_nilai'] * $sks;
                            $data[$n]['data_nilai'][$i]['sks'] = $sks;
                        }
                    }
                } else {
                    $data[$n]['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
                    $data[$n]['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;

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
        if ($t_sks == 0) {
            $res['total_sks'] = $t_sks;
            $res['ipk'] = 0;
        } else {
            $res['total_sks'] = $t_sks;
            $res['ipk'] = number_format($t_sksn / $t_sks, 2);
        }

        return $res;
    }

    public function ip($nim, $semester, $status_pendaftaran)
    {
        $kode_nama_kurikulum = kode_nama_kurikulum($nim);

        $data_penilaian = data_penilaian($nim, $semester);
//        if (stup_grade($kode_nama_kurikulum, $semester)) {
//            $data_penilaian = stup_grade($kode_nama_kurikulum, $semester);
//        } else {
//            $data_penilaian = sistem_penilaian($nim);
//        }

        if ($semester !== 1) {
            if ($semester >= 2 && $status_pendaftaran !== 'B') {
//                $tahun_akademik = $this->m_tahun_akademik->get_aktif() - 1;
                $tahun_akademik = $this->m_tahun_akademik->get_kode_tahun_akademik_by_semester($semester, $nim);

//                $kode_krs = $this->Krs_model->get_kode_krs_konversi($nim, $tahun_akademik);
//                $kode_kr = $this->Krs_model->get_kode_krs($nim, $tahun_akademik);
//                if ($kode_kr == 0) {
//                    $kode_krs = $this->Krs_model->get_krs_konversi($nim);
//                } else {
                    $kode_krs = $this->Krs_model->get_kode_krs($nim, $tahun_akademik);
//                }
                //Generate
//                $data_penilaian = $this->Khs_model->kurikulum_penilaian($angkatan, $kode_program_studi);
                $data_krs = $this->Khs_model->khs($kode_krs);

                $khs['sksn'] = 0;
                $khs['total_sks'] = 0;
                $khs['total_bobot'] = 0;
                $sksn = 0;
                $sks = 0;
                $i = 0;
                foreach ($data_krs as $row) {
                    $khs['nim'] = $row->nim;
                    $khs['nama_mahasiswa'] = $row->nama_mahasiswa;
                    $khs['tahun_akademik'] = $this->m_tahun_akademik->get_byid($row->kode_tahun_akademik);
                    $khs['semester'] = $row->semester;
                    $khs['kurikulum'] = $data_penilaian[0]['nama_kurikulum'];
                    $khs['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
                    $khs['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
                    $khs['data_nilai'][$i]['sks'] = $row->sks;
//                    $nilai_akhir = ($row->nilai_harian * 20 / 100) + ($row->nilai_uts * 30 / 100) + ($row->nilai_uas * 50 / 100);
                    $nilai_akhir = $row->nilai_akhir * 1;
                    foreach ($data_penilaian as $key) {
                        if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
                            $khs['data_nilai'][$i]['grade'] = $key['grade'];
                            $khs['data_nilai'][$i]['sksn'] = $key['bobot_nilai'] * $row->sks;
                        }
                    }
                    $sksn = $sksn + $khs['data_nilai'][$i]['sksn'];
                    $sks = $sks + $khs['data_nilai'][$i]['sks'];
                    $khs['sksn'] = $khs['sksn'] + $khs['data_nilai'][$i]['sks'];

                    $i++;
                }
                if ($sks == 0) {
                    $data['ip'] = 0;
                    $data['sks'] = $sks;
                } else {
                    $data['ip'] = number_format($sksn / $sks, 2);
                    $data['sks'] = $sks;
                }

                return $data;
            }else {
                $tahun_akademik = $this->m_tahun_akademik->get_kode_tahun_akademik_by_semester($semester, $nim);
//                if ($tahun_akademik)

//                for ($x = 0; $x <= 3; $x++) {
//                    if($kode_krs > 0){
//                        break;
//                    }else{
//                        $tahun_akademik = $tahun_akademik - 1;
//                    }
//                }

                if ($tahun_akademik) {
                    $kode_krs = $this->Krs_model->get_kode_krs($nim, $tahun_akademik);

                    $data_krs = $this->Khs_model->khs($kode_krs);

                    $khs['sksn'] = 0;
                    $khs['total_sks'] = 0;
                    $khs['total_bobot'] = 0;
                    $sksn = 0;
                    $sks = 0;
                    $i = 0;
                    foreach ($data_krs as $row) {
                        $khs['nim'] = $row->nim;
                        $khs['nama_mahasiswa'] = $row->nama_mahasiswa;
                        $khs['tahun_akademik'] = $this->m_tahun_akademik->get_byid($row->kode_tahun_akademik);
                        $khs['semester'] = $row->semester;
                        $khs['kurikulum'] = $data_penilaian[0]['nama_kurikulum'];
                        $khs['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
                        $khs['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
                        $khs['data_nilai'][$i]['sks'] = $row->sks;

                        $nilai_akhir = $row->nilai_akhir * 1;
                        foreach ($data_penilaian as $key) {
                            if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
                                $khs['data_nilai'][$i]['grade'] = $key['grade'];
                                $khs['data_nilai'][$i]['sksn'] = $key['bobot_nilai'] * $row->sks;
                            }
                        }
                        $sksn = $sksn + $khs['data_nilai'][$i]['sksn'];
                        $sks = $sks + $khs['data_nilai'][$i]['sks'];
                        $khs['sksn'] = $khs['sksn'] + $khs['data_nilai'][$i]['sks'];

                        $i++;
                    }
                    if ($sks == 0) {
                        $data['ip'] = 0;
                        $data['sks'] = $sks;
                    } else {
                        $data['ip'] = number_format($sksn / $sks, 2);
                        $data['sks'] = $sks;
                    }
                } else {
                    $data['ip'] = 0;
                    $data['sks'] = 0;
                }

                return $data;
            }
        } elseif ($semester == 1 && $status_pendaftaran !== 'B') {
            $tahun_akademik = $this->m_tahun_akademik->get_aktif();
            $kode_krs = $this->Krs_model->get_kode_krs_konversi($nim, $tahun_akademik);

            $data_krs = $this->Khs_model->khs($kode_krs);

            $khs['sksn'] = 0;
            $khs['total_sks'] = 0;
            $khs['total_bobot'] = 0;
            $sksn = 0;
            $sks = 0;
            $i = 0;
            foreach ($data_krs as $row) {
                $khs['nim'] = $row->nim;
                $khs['nama_mahasiswa'] = $row->nama_mahasiswa;
                $khs['tahun_akademik'] = $this->m_tahun_akademik->get_byid($row->kode_tahun_akademik);
                $khs['semester'] = $row->semester;
                $khs['kurikulum'] = $data_penilaian[0]['nama_kurikulum'];
                $khs['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
                $khs['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
                $khs['data_nilai'][$i]['sks'] = $row->sks;
                $nilai_akhir = $row->nilai_akhir * 1;
                foreach ($data_penilaian as $key) {
                    if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
                        $khs['data_nilai'][$i]['grade'] = $key['grade'];
                        $khs['data_nilai'][$i]['sksn'] = $key['bobot_nilai'] * $row->sks;
                    }
                }
                $sksn = $sksn + $khs['data_nilai'][$i]['sksn'];
                $sks = $sks + $khs['data_nilai'][$i]['sks'];
                $khs['sksn'] = $khs['sksn'] + $khs['data_nilai'][$i]['sks'];
                $i++;
            }
            if ($sks == 0) {
                $data['ip'] = 0;
                $data['sks'] = $sks;
            } else {
                $data['ip'] = number_format($sksn / $sks, 2);
                $data['sks'] = $sks;
            }

            return $data;

        }
    }

    public function semester_saat_ini($nim, $kode_tahun_akademik)
    {
//        $nim = $this->session->userdata('nim');
        $tahun_angkatan = substr($nim, 0, 2);
        $tahun = $this->db->select(array('semester', 'substring(tahun_akademik,3,2) as tahun_akademik', 'tahun_akademik as ta', 'kode_tahun_akademik'))
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->get('tahun_akademik')
            ->row_object();;
        $sem = $tahun->semester;
        $tahun_akademik = $tahun->tahun_akademik;

        if ($sem == 0) {
            # code...
            $semester = ($tahun_akademik - $tahun_angkatan) * 2 + 2;
        } else {
            $semester = ($tahun_akademik - $tahun_angkatan) * 2 + 1;
        }

        return $semester;
    }
  public function ipk_rata_new($kode_tahun_akademik, $tahun_angkatan, $kode_program_studi, $limit, $offset)
    {
        $mk_skripsi = get_kode_matakuliah_skripsi();
        $mk_kkp = get_kode_matakuliah_kkp();
        $data_new = $this->db->select('mah.nama_mahasiswa,mah.nim,mah.jenis_kelamin')->from('krs')
                        ->join('mahasiswa as mah','krs.nim=mah.nim')
                        ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
                        ->where('mid(mah.nim,1,2)', $tahun_angkatan)
                        ->where('mah.program_studi_kode', $kode_program_studi)
                        ->where('mah.status !=', 'K')
                        ->order_by('krs.nim')
          				->group_by('krs.nim')
                        ->limit($limit, $offset)
                        ->get()->result_object();
        foreach ($data_new as $key => $value) {
            $tmp = $this->db->select('mak.sks_teori,mak.sks_praktek,mak.sks_praktikum,mak.id_matakuliah, max(khd.nilai_akhir) as nilai_akhir,mak.kode_matakuliah')
                ->from('krs')
                ->join('krs_detail as kd', 'krs.kode_krs=kd.kode_krs')
                ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
                ->join('matakuliah as mak', 'kd.id_matakuliah=mak.id_matakuliah')
                ->where('krs.kode_tahun_akademik <= ', $kode_tahun_akademik)
                ->where('nim', $value->nim)
                // ->where('nilai_akhir >= spd.nilai_minimum AND nilai_akhir <= spd.nilai_maksimum')
                ->order_by('mak.id_matakuliah')
                ->group_by('mak.id_matakuliah')
                ->get()->result_object();
            $total_sks = 0;
            $praktikum = 0;
            $kkp = 0;
            $skripsi = 0;
            $bobot = 0;
            $data_penilaian = data_penilaian($value->nim);
            foreach ($tmp as $no => $val) {
                $total_sks += $val->sks_teori+$val->sks_praktek+$val->sks_praktikum;
                $praktikum += $val->sks_praktikum;
                $kkp += array_search($val->kode_matakuliah, $mk_kkp) ? 1:0;
                $skripsi += array_search($val->kode_matakuliah, $mk_skripsi) ? 1:0;
                foreach ($data_penilaian as $nil) {
                    if (($nil['nilai_minimum'] <= $val->nilai_akhir) && ($val->nilai_akhir <= $nil['nilai_maksimum'])) {
                        $bobot += $nil['bobot_nilai'] * ($val->sks_teori+$val->sks_praktek+$val->sks_praktikum);
                    }else{
                        $bobot += 0* ($val->sks_teori+$val->sks_praktek+$val->sks_praktikum);
                    }
                }
            }        
            $semester = $this->semester_saat_ini($value->nim, $kode_tahun_akademik);
            $ip = $this->ip($value->nim, $semester, $value->status_pendaftaran);
            $data_new[$key]->praktikum = $praktikum;
            $data_new[$key]->skripsi = $skripsi;
            $data_new[$key]->kkp =  $kkp;
            $data_new[$key]->ipk = round($bobot/$total_sks, 2);;
            $data_new[$key]->total_sks = $total_sks;
            $data_new[$key]->ip = $ip['ip'];
        }     
        return $data_new;
    }
    public function  all_ipk_rata_new($kode_tahun_akademik, $tahun_angkatan, $kode_program_studi)
    {
        $mk_skripsi = get_kode_matakuliah_skripsi();
        $mk_kkp = get_kode_matakuliah_kkp();
        $data_new = $this->db->select('mah.nama_mahasiswa,mah.nim,mah.jenis_kelamin')->from('krs')
                        ->join('mahasiswa as mah','krs.nim=mah.nim')
                        ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
                        ->where('mid(mah.nim,1,2)', $tahun_angkatan)
                        ->where('mah.program_studi_kode', $kode_program_studi)
                        ->where('mah.status !=', 'K')
                        ->order_by('krs.nim')
          				->group_by('krs.nim')
                        ->get()->result_object();
        foreach ($data_new as $key => $value) {
            $tmp = $this->db->select('mak.sks_teori,mak.sks_praktek,mak.sks_praktikum,mak.id_matakuliah, max(khd.nilai_akhir) as nilai_akhir,mak.kode_matakuliah')
                ->from('krs')
                ->join('krs_detail as kd', 'krs.kode_krs=kd.kode_krs')
                ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
                ->join('matakuliah as mak', 'kd.id_matakuliah=mak.id_matakuliah')
                ->where('krs.kode_tahun_akademik <= ', $kode_tahun_akademik)
                ->where('nim', $value->nim)
                ->order_by('mak.id_matakuliah')
                ->group_by('mak.id_matakuliah')
                ->get()->result_object();
            $total_sks = 0;
            $praktikum = 0;
            $kkp = 0;
            $skripsi = 0;
            $bobot = 0;
            $data_penilaian = data_penilaian($value->nim);
            foreach ($tmp as $no => $val) {
                $total_sks += $val->sks_teori+$val->sks_praktek+$val->sks_praktikum;
                $praktikum += $val->sks_praktikum;
                $kkp += array_search($val->kode_matakuliah, $mk_kkp) ? 1:0;
                $skripsi += array_search($val->kode_matakuliah, $mk_skripsi) ? 1:0;
                foreach ($data_penilaian as $nil) {
                    if (($nil['nilai_minimum'] <= $val->nilai_akhir) && ($val->nilai_akhir <= $nil['nilai_maksimum'])) {
                        $bobot += $nil['bobot_nilai'] * ($val->sks_teori+$val->sks_praktek+$val->sks_praktikum);
                    }else{
                        $bobot += 0* ($val->sks_teori+$val->sks_praktek+$val->sks_praktikum);
                    }
                }
            }        
            $semester = $this->semester_saat_ini($value->nim, $kode_tahun_akademik);
            $ip = $this->ip($value->nim, $semester, $value->status_pendaftaran);
            $data_new[$key]->praktikum = $praktikum;
            $data_new[$key]->skripsi = $skripsi;
            $data_new[$key]->kkp =  $kkp;
            $data_new[$key]->ipk = round($bobot/$total_sks, 2);
            $data_new[$key]->total_sks = $total_sks;
            $data_new[$key]->ip = $ip['ip'];
        }     
        return $data_new;
    }
  	public function rekap_ipk_new($kode_tahun_akademik, $tahun_angkatan, $kode_program_studi, $limit, $offset){
        $data_new = $this->db->select('mah.nama_mahasiswa,mah.nim,mah.jenis_kelamin')->from('krs')
                        ->join('mahasiswa as mah','krs.nim=mah.nim')
                        ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
                        ->where('mid(mah.nim,1,2)', $tahun_angkatan)
                        ->where('mah.program_studi_kode', $kode_program_studi)
                        ->where('mah.status !=', 'K')
                        ->order_by('krs.nim')
                        ->group_by('krs.nim')
                        ->limit($limit, $offset)
                        ->get()->result_object();

        foreach ($data_new as $key => $value) {
            $tmp = $this->db->select('mak.sks_teori,mak.sks_praktek,mak.sks_praktikum,mak.id_matakuliah, max(khd.nilai_akhir) as nilai_akhir,mak.kode_matakuliah')
                ->from('krs')
                ->join('krs_detail as kd', 'krs.kode_krs=kd.kode_krs')
                ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
                ->join('matakuliah as mak', 'kd.id_matakuliah=mak.id_matakuliah')
                ->where('krs.kode_tahun_akademik <= ', $kode_tahun_akademik)
                ->where('nim', $value->nim)
                // ->where('nilai_akhir >= spd.nilai_minimum AND nilai_akhir <= spd.nilai_maksimum')
                ->order_by('mak.id_matakuliah')
                ->group_by('mak.id_matakuliah')
                ->get()->result_object();
            $total_sks = 0;
            $praktikum = 0;
            $kkp = 0;
            $skripsi = 0;
            $bobot = 0;
            $data_penilaian = data_penilaian($value->nim);
            foreach ($tmp as $no => $val) {
                $total_sks += $val->sks_teori+$val->sks_praktek+$val->sks_praktikum;
                $praktikum += $val->sks_praktikum;
                $kkp += array_search($val->kode_matakuliah, $mk_kkp) ? 1:0;
                $skripsi += array_search($val->kode_matakuliah, $mk_skripsi) ? 1:0;
                foreach ($data_penilaian as $nil) {
                    if (($nil['nilai_minimum'] <= $val->nilai_akhir) && ($val->nilai_akhir <= $nil['nilai_maksimum'])) {
                        $bobot += $nil['bobot_nilai'] * ($val->sks_teori+$val->sks_praktek+$val->sks_praktikum);
                    }else{
                        $bobot += 0* ($val->sks_teori+$val->sks_praktek+$val->sks_praktikum);
                    }
                }
            }        
            $semester = $this->semester_saat_ini($value->nim, $kode_tahun_akademik);
            $ip = $this->ip($value->nim, $semester, $value->status_pendaftaran);
            $data_new[$key]->praktikum = $praktikum;
            $data_new[$key]->skripsi = $skripsi;
            $data_new[$key]->kkp =  $kkp;
            $data_new[$key]->ipk = round($bobot/$total_sks, 2);;
            $data_new[$key]->total_sks = $total_sks;
            $data_new[$key]->ip = $ip['ip'];
            $data_new[$key]->sks = $ip['sks'];
        }     
        return $data_new;
    }
  	public function cetak_rekap_ipk_new($kode_tahun_akademik, $tahun_angkatan, $kode_program_studi, $limit, $offset){
        $data_new = $this->db->select('mah.nama_mahasiswa,mah.nim,mah.jenis_kelamin')->from('krs')
                        ->join('mahasiswa as mah','krs.nim=mah.nim')
                        ->where('krs.kode_tahun_akademik', $kode_tahun_akademik)
                        ->where('mid(mah.nim,1,2)', $tahun_angkatan)
                        ->where('mah.program_studi_kode', $kode_program_studi)
                        ->where('mah.status !=', 'K')
                        ->order_by('krs.nim')
                        ->group_by('krs.nim')
                        //->limit($limit, $offset)
                        ->get()->result_object();

        foreach ($data_new as $key => $value) {
            $tmp = $this->db->select('mak.sks_teori,mak.sks_praktek,mak.sks_praktikum,mak.id_matakuliah, max(khd.nilai_akhir) as nilai_akhir,mak.kode_matakuliah')
                ->from('krs')
                ->join('krs_detail as kd', 'krs.kode_krs=kd.kode_krs')
                ->join('khs_detail as khd', 'kd.kode_krs_detail=khd.kode_krs_detail')
                ->join('matakuliah as mak', 'kd.id_matakuliah=mak.id_matakuliah')
                ->where('krs.kode_tahun_akademik <= ', $kode_tahun_akademik)
                ->where('nim', $value->nim)
                // ->where('nilai_akhir >= spd.nilai_minimum AND nilai_akhir <= spd.nilai_maksimum')
                ->order_by('mak.id_matakuliah')
                ->group_by('mak.id_matakuliah')
                ->get()->result_object();
            $total_sks = 0;
            $praktikum = 0;
            $kkp = 0;
            $skripsi = 0;
            $bobot = 0;
            $data_penilaian = data_penilaian($value->nim);
            foreach ($tmp as $no => $val) {
                $total_sks += $val->sks_teori+$val->sks_praktek+$val->sks_praktikum;
                $praktikum += $val->sks_praktikum;
                $kkp += array_search($val->kode_matakuliah, $mk_kkp) ? 1:0;
                $skripsi += array_search($val->kode_matakuliah, $mk_skripsi) ? 1:0;
                foreach ($data_penilaian as $nil) {
                    if (($nil['nilai_minimum'] <= $val->nilai_akhir) && ($val->nilai_akhir <= $nil['nilai_maksimum'])) {
                        $bobot += $nil['bobot_nilai'] * ($val->sks_teori+$val->sks_praktek+$val->sks_praktikum);
                    }else{
                        $bobot += 0* ($val->sks_teori+$val->sks_praktek+$val->sks_praktikum);
                    }
                }
            }        
            $semester = $this->semester_saat_ini($value->nim, $kode_tahun_akademik);
            $ip = $this->ip($value->nim, $semester, $value->status_pendaftaran);
            $data_new[$key]->praktikum = $praktikum;
            $data_new[$key]->skripsi = $skripsi;
            $data_new[$key]->kkp =  $kkp;
            $data_new[$key]->ipk = round($bobot/$total_sks, 2);;
            $data_new[$key]->total_sks = $total_sks;
            $data_new[$key]->ip = $ip['ip'];
            $data_new[$key]->sks = $ip['sks'];
        }     
        return $data_new;
    }
}