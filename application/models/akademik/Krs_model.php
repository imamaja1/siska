<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Krs_model extends CI_Model
{

    private $table = "krs";

    function get_kode_krs($nim, $kode_tahun_akademik)
    {
        $query = $this->db->select('*')
                ->from('krs')
                ->join('krs_detail as krd', 'krs.kode_krs=krd.kode_krs')
                ->where('nim', $nim)
                ->where('kode_tahun_akademik', $kode_tahun_akademik)
                ->where_not_in('status', 'K')
                ->where_not_in('semester', 'K')
                ->get()->row_object();

        if (!empty($query)) {
            return $query->kode_krs;
        } else {
            return 0;
        }
    }

    function get_krs($nim, $kode_tahun_akademik)
    {
        $query = $this->db->select('*')
                ->from('krs')
                ->join('krs_detail as krd', 'krs.kode_krs=krd.kode_krs')
                ->where('nim', $nim)
                ->where('kode_tahun_akademik', $kode_tahun_akademik)
                ->where_not_in('status', 'K')
                ->where_not_in('semester', 'K')
                ->get()->row_object();

        if (!empty($query)) {
            return $query;
        } else {
            return false;
        }
    }

    function khs($kode_krs)
    {
        $krs_detail = $this->db->query("SELECT *, krs.semester FROM khs_detail, krs, krs_detail, matakuliah, mahasiswa WHERE krs.kode_krs=krs_detail.kode_krs and krs_detail.kode_krs_detail=khs_detail.kode_krs_detail and krs_detail.id_matakuliah=matakuliah.id_matakuliah and krs.nim=mahasiswa.nim and krs_detail.kode_krs=?", array($kode_krs))->result();

        if (count($krs_detail) > 0) {
            return $krs_detail;
        } else {
            return false;
        }
    }

    function kurikulum_penilaian($angkatan, $kode_program_studi)
    {
        $penilaian = $this->db->query("SELECT * FROM (SELECT distinct kode_sistem_penilaian_detail, mid(angkatan1,-2) as angkatan, nama_kurikulum.kode_nama_kurikulum, nilai_minimum, nilai_maksimum, grade, bobot_nilai, kategori, keterangan, nama_kurikulum, kode_program_studi FROM nama_kurikulum, kurikulum, sistem_penilaian, sistem_penilaian_detail WHERE nama_kurikulum.kode_nama_kurikulum=kurikulum.kode_nama_kurikulum and nama_kurikulum.kode_nama_kurikulum=sistem_penilaian.kode_nama_kurikulum and sistem_penilaian.kode_sistem_penilaian=sistem_penilaian_detail.kode_sistem_penilaian) as mhs WHERE angkatan=? and kode_program_studi=?", array($angkatan, $kode_program_studi))->result_array();

        return $penilaian;
    }

    function autocomplate($keyword)
    {

        return $this->db->select('nim')->like('nim', $keyword, 'after')->group_by('nim')->order_by('nim ASC')->limit(6)->get('krs')->result();
    }

    function get_mahasiswa_by_angkatan_jurusan_semester($tahun_akademik, $angkatan, $jurusan, $semester, $limit, $offset)
    {

        return $this->db->select('m.nim, m.nama_mahasiswa, k.kode_krs, k.semester')
                ->from('mahasiswa as m')
                ->join('krs as k', 'm.nim=k.nim')
                ->join('konsultasi_perwalian as ko', 'm.nim=ko.nim and ko.kode_tahun_akademik=k.kode_tahun_akademik')
//                tambahan cek pembayaran sks
                //->join('status_perkuliahan as sp', 'k.nim=sp.nim')
//                end
                //->where('sp.kode_tahun_akademik', $tahun_akademik)
                //->where_not_in('pembayaran_sks', ['0'])
                ->where(array('mid(m.nim,1,2)' => $angkatan, 'm.program_studi_kode' => $jurusan, 'k.semester' => $semester, 'ko.status_cetak' => 'A'))
                ->group_by('m.nim')
                ->limit($limit, $offset)
                ->get()->result();
    }

    function count_mahasiswa_by_angkatan_jurusan_semester($tahun_akademik, $angkatan, $jurusan, $semester)
    {

        return $this->db->select('m.nim')
                ->from('mahasiswa as m')
                ->join('krs as k', 'm.nim=k.nim')
                ->join('konsultasi_perwalian as ko', 'm.nim=ko.nim and ko.kode_tahun_akademik=k.kode_tahun_akademik')
                //                tambahan cek pembayaran sks
                //->join('status_perkuliahan as sp', 'k.nim=sp.nim')
//                end
                //->where('sp.kode_tahun_akademik', $tahun_akademik)
                //->where_not_in('pembayaran_sks', ['0'])
                ->where(array('mid(m.nim,1,2)' => $angkatan, 'm.program_studi_kode' => $jurusan, 'k.semester' => $semester, 'ko.status_cetak' => 'A'))
                ->group_by('m.nim')->get()->result();

    }

    function get_mahasiswa_by_nim($tahun_akademik, $kata_kunci, $semester)
    {
        return $this->db->select('m.nim, m.nama_mahasiswa, k.kode_krs, ko.status_cetak')
                ->from('mahasiswa as m')
                ->join('krs as k', 'm.nim=k.nim')
                ->join('konsultasi_perwalian as ko', 'm.nim=ko.nim and ko.kode_tahun_akademik=k.kode_tahun_akademik')
                //                tambahan cek pembayaran sks
                ->join('status_perkuliahan as sp','k.nim=sp.nim')
               // ->where('sp.kode_tahun_akademik', $tahun_akademik)
                ->where_not_in('pembayaran_sks',['0'])
//                end
                ->where(array('m.nim' => $kata_kunci, 'k.semester' => $semester))
                ->where('ko.status_cetak', 'A')
//                        ->where('ko.kode_tahun_akademik', $tahun_akademik)
                ->group_by('m.nim')->get()->result();
    }

    function get_current_krs($keyword)
    {
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        return $this->db->select('*,k.nim')
                ->from('mahasiswa as m')
                ->join('krs as k', 'm.nim=k.nim')
                ->join('konsultasi_perwalian as ko', 'm.nim=ko.nim')
                //                tambahan cek pembayaran sks
                ->join('status_perkuliahan as sp','k.nim=sp.nim')
                ->where('sp.kode_tahun_akademik', $kode_tahun_akademik)
                ->where_not_in('pembayaran_sks',['0'])
//                end
                ->group_start()
                    ->like('m.nim', $keyword)
                    ->or_like('m.nama_mahasiswa', $keyword)
                ->group_end()
                ->where('ko.status_cetak', 'A')
                ->where_not_in('k.semester', array('K'))
                ->where('m.status', 'A')
                ->where('ko.kode_tahun_akademik', $kode_tahun_akademik)
                ->where('k.kode_tahun_akademik', $kode_tahun_akademik)
//            ->group_by('m.nim')
                ->get()->result();
    }

    function get_mahasiswa_by_nama($tahun_akademik, $kata_kunci, $semseter, $limit, $offset)
    {
        return $this->db->select('m.nim, m.nama_mahasiswa, k.kode_krs, ko.status_cetak')
                ->from('mahasiswa as m')
                ->join('krs as k', 'm.nim=k.nim')
                ->join('konsultasi_perwalian as ko', 'm.nim=ko.nim and ko.kode_tahun_akademik=k.kode_tahun_akademik')
                //                tambahan cek pembayaran sks
                ->join('status_perkuliahan as sp','k.nim=sp.nim')
                ->where('sp.kode_tahun_akademik', $tahun_akademik)
                ->where_not_in('pembayaran_sks',['0'])
//                end
                ->like('nama_mahasiswa', $kata_kunci)
                ->where('k.semester', $semseter)
                ->where('ko.status_cetak', 'A')
//                        ->where('ko.kode_tahun_akademik', $tahun_akademik)
                ->limit($limit, $offset)
                ->group_by('m.nim')->get()->result();
    }
    
    function count_mahasiswa_by_nama($tahun_akademik, $kata_kunci, $semseter)
    {
        return $this->db->select('m.nim')
                ->from('mahasiswa as m')
                ->join('krs as k', 'm.nim=k.nim')
                ->join('konsultasi_perwalian as ko', 'm.nim=ko.nim and ko.kode_tahun_akademik=k.kode_tahun_akademik')
                //                tambahan cek pembayaran sks
                ->join('status_perkuliahan as sp','k.nim=sp.nim')
                ->where('sp.kode_tahun_akademik', $tahun_akademik)
                ->where_not_in('pembayaran_sks',['0'])
//                end
                ->like('nama_mahasiswa', $kata_kunci)
                ->where('k.semester', $semseter)
                ->where('ko.status_cetak', 'A')
//                        ->where('ko.kode_tahun_akademik', $tahun_akademik)
                ->group_by('m.nim')->get()->result();
    }

    function get_krs_mahasiswa_by_nim($nim, $semester)
    {
        return $this->db->select('t.tahun_akademik, d.nik, d.nama_dosen, d.signature, k.nim, k.semester, m.nama_mahasiswa,m.foto, m.alamat, m.kota, m.propinsi, m.telepon, m.alamat_orangtua, m.kota_orangtua, m.propinsi_orangtua, m.telepon_orangtua')
                ->from('krs as k')
                ->join('mahasiswa as m', 'k.nim=m.nim')
                ->join('perwalian as p', 'p.nim=k.nim')
                ->join('dosen as d', 'p.kode_dosen=d.kode_dosen')
                ->join('tahun_akademik as t', 'k.kode_tahun_akademik=t.kode_tahun_akademik')
                ->where(array('k.nim' => $nim, 'k.semester' => $semester))
                ->group_by('k.nim')->get()->row();
    }

    function get_krs_matakuliah_by_nim_semester($nim, $semester)
    {

        $strSQL = "SELECT distinct(matakuliah.kode_matakuliah), matakuliah.nama_matakuliah, substring(matakuliah.kode_matakuliah,5,1) as sks,
                    matakuliah.sks_teori, matakuliah.sks_praktek, matakuliah.sks_praktikum, krs_detail.status
                    FROM krs_detail INNER JOIN matakuliah ON krs_detail.id_matakuliah=matakuliah.id_matakuliah INNER JOIN krs ON krs.kode_krs=krs_detail.kode_krs WHERE krs.nim=? AND krs.semester=? AND krs.semester!='K' AND krs_detail.status!='K'
                    ORDER BY substring(matakuliah.kode_matakuliah,6,1) ASC, substring(matakuliah.kode_matakuliah,-2,2) ASC";
        return $this->db->query($strSQL, array($nim, $semester))->result();
    }

    function get_rekapitulasi_mahasiswa_per_matakuliah($id_matakuliah, $kode_tahun_akademik)
    {

        return $this->db->select('krs.nim, mahasiswa.nama_mahasiswa')
                ->from('krs')
                ->join('krs_detail', 'krs_detail.kode_krs=krs.kode_krs')
                ->join('mahasiswa', 'krs.nim=mahasiswa.nim')
                ->where(array('krs.kode_tahun_akademik' => $kode_tahun_akademik, 'krs_detail.id_matakuliah' => $id_matakuliah))
                ->where_in('krs_detail.status', array('B', 'U'))
                ->where_not_in('krs.semester', 'K')
          		->group_by('krs.nim')
                ->order_by('krs.nim', 'ASC')
                ->get()->result();
    }

    function get_rekapitulasi_matakuliah_per_tahun_akademik($kode_program_studi, $kode_tahun_akademik)
    {
        $query1 = $this->db->select('matakuliah.id_matakuliah,matakuliah.kode_matakuliah, matakuliah.sks_praktikum, matakuliah.nama_matakuliah, count(DISTINCT(krs.nim)) as jum')
                ->from('krs')
                ->join('mahasiswa as mah', 'mah.nim=krs.nim')
                ->join('krs_detail', 'krs_detail.kode_krs=krs.kode_krs')
                ->join('matakuliah', 'krs_detail.id_matakuliah=matakuliah.id_matakuliah')
                ->where(array('mah.program_studi_kode' => $kode_program_studi, 'krs.kode_tahun_akademik' => $kode_tahun_akademik))
                ->where_in('krs_detail.status', array('B', 'U'))
                ->where_not_in('krs.semester', 'K')
                ->group_by('matakuliah.id_matakuliah')
                ->get_compiled_select();

        $query3 = $this->db->query("SELECT id_matakuliah, kode_matakuliah, sks_praktikum, nama_matakuliah, jum as jml FROM (" . $query1 . ") as mah")->result();
        return $query3;
    }

    function get_kodemk_krs($nim)
    {
        $tahun = $this->db->select('kode_tahun_akademik')
                ->from('tahun_akademik')
                ->where('status', 'A')
                ->get()->row_object();
        $query = $this->db->select('id_matakuliah')
                ->from('krs')
                ->join('krs_detail as krd', 'krs.kode_krs=krd.kode_krs')
                ->where('nim', $nim)
                ->where('status', 'B')
                ->where_not_in('kode_tahun_akademik', $tahun->kode_tahun_akademik)
                ->get()->result_array();
        $data = array();
        foreach ($query as $row) {
            $data[] = $row['id_matakuliah'];
        }
        return $data;
    }

    function get_kodemk_krs_sudah_ambil($nim)
    {
        $tahun = $this->db->select('kode_tahun_akademik')
                ->from('tahun_akademik')
                ->where('status', 'A')
                ->get()->row_object();
        // $sub = $this->db->select('id_matakuliah')
        //         ->from('krs')
        //         ->join('krs_detail as krd', 'krs.kode_krs=krd.kode_krs')
        //         ->where('nim', $nim)
        //         ->where('status', 'B')
        //         ->where_not_in('kode_tahun_akademik', $tahun->kode_tahun_akademik)
        //         ->get_compiled_select();
        // $sub1 = $this->db->select('id_matakuliah')
        //         ->from('krs')
        //         ->join('krs_detail as krd', 'krs.kode_krs=krd.kode_krs')
        //         ->where('nim', $nim)
        //         ->where('status', 'B')
        //         ->where('semester', 'K')
        //         ->get_compiled_select();
        // $query = $this->db->query("$sub UNION $sub1")->result_array();
       $query = $this->db->select('id_matakuliah')
           ->from('krs')
           ->join('krs_detail as krd', 'krs.kode_krs=krd.kode_krs')
           ->where('nim', $nim)
        //    ->where('status', 'B')
           ->where_not_in('kode_tahun_akademik', $tahun->kode_tahun_akademik)
           ->get()->result_array();
        $data = array();
        foreach ($query as $row) {
            $data[] = $row['id_matakuliah'];
        }
        return $data;
    }

    function get_kodemk_krs_konversi($nim)
    {
        $tahun = $this->db->select('kode_tahun_akademik')
                ->from('tahun_akademik')
                ->where('status', 'A')
                ->get()->row_object();
        $query = $this->db->select('id_matakuliah')
                ->from('krs')
                ->join('krs_detail as krd', 'krs.kode_krs=krd.kode_krs')
                ->where('nim', $nim)
                ->where('status', 'B')
                ->where('semester', 'K')
//                        ->where_not_in('kode_tahun_akademik', $tahun->kode_tahun_akademik)
                ->get()->result_array();
        $data = array();
        foreach ($query as $row) {
            $data[] = $row['id_matakuliah'];
        }
        return $data;
    }

    public function get_kodemk_diambil($kode_kurikulum)
    {
        $query = $this->db->query("SELECT id_matakuliah_ambil FROM matakuliah_prasyarat WHERE kode_nama_kurikulum=?", array($kode_kurikulum))->result();
        $data = array();
        foreach ($query as $row) {
            $data[] = $row->id_matakuliah_ambil;
        }
        return $data;
    }

    public function get_kodemk_prasyarat($id_matakuliah, $kode_nama_kurikulum)
    {
//        $query = $this->db->get_where('matakuliah_prasyarat', array('matakuliah_yg_diambil' => $kode_matakuliah, 'kode_nama_kurikulum'=>$kode_nama_kurikulum))->row_object();
        $query = $this->db->get_where('matakuliah_prasyarat', array('id_matakuliah_ambil' => $id_matakuliah, 'kode_nama_kurikulum' => $kode_nama_kurikulum))->row_object();
        return $query->id_matakuliah_syarat;
    }

    public function simpan_krs($data)
    {
        $this->db->insert($this->table, $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    public function cek_krs_exis($nim, $kode_tahun_akademik)
    {

        $query = $this->db->select('*')
                ->from('krs')
                ->join('krs_detail as krd', 'krs.kode_krs=krd.kode_krs')
                ->where('nim', $nim)
                ->where('kode_tahun_akademik', $kode_tahun_akademik)
                ->where_not_in('status', 'K')
                ->where_not_in('semester', 'K')
                ->get()->row_object();

        return $query;
    }

    public function get_jumlah_maksimum_sks_by_nim_and_semester($nim, $semester)
    {
        return $this->db->select('k_detail.kode_krs_detail, m.kode_matakuliah, m.nama_matakuliah, (m.sks_teori + m.sks_praktek + m.sks_praktikum) as jumlah_sks, ((khs_detail.nilai_harian * 0.2) + (khs_detail.nilai_uts * 0.3) + (khs_detail.nilai_uas * 0.5 )) as na')
                ->from('krs as k')
                ->join('krs_detail as k_detail', 'k.kode_krs=k_detail.kode_krs')
                ->join('matakuliah as m', 'm.id_matakuliah=k_detail.id_matakuliah')
                ->join('khs_detail as khs_detail', 'khs_detail.kode_krs_detail=k_detail.kode_krs_detail')
                ->where('k.nim', $nim)
                ->where('k.semester', $semester)
                ->where_not_in('k_detail.status', 'K')
                ->where_not_in('m.kode_matakuliah', 'TSBB370068', 'MDBB340015', 'TDBB350020', 'TDPB650021', 'MDPB650016', 'TSKB670084')->get();
    }

    function get_krs_matakuliah_semester_genap($kode_nama_kurikulum, $semester_genap)
    {
        $sql = "SELECT matakuliah.kode_matakuliah, matakuliah.nama_matakuliah, matakuliah.sks_teori, matakuliah.sks_praktek, matakuliah.sks_praktikum, ";
        $sql .= " substr(matakuliah.kode_matakuliah,5,1) as jumlah_sks, ";
        $sql .= " substr(matakuliah.kode_matakuliah,6,1) as semester FROM kurikulum";
        $sql .= " INNER JOIN matakuliah ON kurikulum.id_matakuliah=matakuliah.id_matakuliah";
        $sql .= " WHERE kurikulum.kode_nama_kurikulum=? AND SUBSTRING(matakuliah.kode_matakuliah,6,1)=?";
        $sql .= " ORDER BY SUBSTR(matakuliah.kode_matakuliah,6,1), RIGHT(matakuliah.kode_matakuliah,3)";
        return $this->db->query($sql, array($kode_nama_kurikulum, $semester_genap))->result();
    }

    function get_krs_matakuliah_semester_ganjil($kode_nama_kurikulum, $semester_ganjil)
    {
        $sql = "SELECT matakuliah.kode_matakuliah, matakuliah.nama_matakuliah, matakuliah.sks_teori, matakuliah.sks_praktek, matakuliah.sks_praktikum, ";
        $sql .= " substr(matakuliah.kode_matakuliah,5,1) as jumlah_sks, ";
        $sql .= " substr(matakuliah.kode_matakuliah,6,1) as semester FROM kurikulum";
        $sql .= " INNER JOIN matakuliah ON kurikulum.id_matakuliah=matakuliah.id_matakuliah";
        $sql .= " WHERE kurikulum.kode_nama_kurikulum=? AND SUBSTRING(matakuliah.kode_matakuliah,6,1)=?";
        $sql .= " ORDER BY SUBSTR(matakuliah.kode_matakuliah,6,1), RIGHT(matakuliah.kode_matakuliah,3)";
        return $this->db->query($sql, array($kode_nama_kurikulum, $semester_ganjil))->result();
    }

    function get_singkatan_program_studi_by_kode_jurusan_jenjang($kode_jenjang, $kode_jurusan)
    {
        return $this->db->select('p.singkatan_program_studi')
                ->from('program_studi as p')
                ->join('jurusan as ju', 'ju.id_jurusan=p.id_jurusan')
                ->join('jenjang as je', 'je.id_jenjang=p.id_jenjang')
                ->where(array('ju.kode_jurusan' => $kode_jurusan, 'je.kode_jenjang' => $kode_jenjang))
                ->get()->row();
    }

    function hapus($kode_krs)
    {
        log_aktivitas_nilai('delete', 'kode_krs', $kode_krs, null, 'krs', null, null, $kode_krs);
        return $this->db->where('kode_krs', $kode_krs)->delete($this->table);
    }

    function get_all_krs_detail_by_nim($nim)
    {
        return $this->db->select('krs.kode_tahun_akademik, ta.tahun_akademik as nama_ta, krs.semester, krs.kode_krs, krs_detail.kode_krs_detail, krs_detail.status, matakuliah.kode_matakuliah, matakuliah.nama_matakuliah, matakuliah.sks_teori, matakuliah.sks_praktek, matakuliah.sks_praktikum, khs_detail.nilai_akhir')
                ->from('krs')
                ->join('krs_detail', 'krs_detail.kode_krs = krs.kode_krs')
                ->join('matakuliah', 'matakuliah.id_matakuliah = krs_detail.id_matakuliah')
                ->join('tahun_akademik as ta', 'ta.kode_tahun_akademik = krs.kode_tahun_akademik', 'left')
                ->join('khs_detail', 'khs_detail.kode_krs_detail = krs_detail.kode_krs_detail', 'left')
                ->where('krs.nim', $nim)
                ->order_by('krs.kode_tahun_akademik', 'DESC')
                ->get()->result();
    }

    function cek_konversi_matakuliah($nim)
    {
        $query = $this->db->select('*')
                ->from('krs')
                ->where('nim', $nim)
                ->get()->result_object();
        return $query;
    }

    function get_kode_krs_konversi($nim, $kode_tahun_akademik)
    {
        $query = $this->db->select('*')
                ->from('krs')
                ->join('krs_detail as krd', 'krs.kode_krs=krd.kode_krs')
                ->where('nim', $nim)
                ->where('kode_tahun_akademik', $kode_tahun_akademik)
                ->where_not_in('status', 'K')
                ->where('semester', 'K')
                ->get()->row_object();
        if (!empty($query)) {
            return $query->kode_krs;
        } else {
            return 0;
        }
    }

    function get_krs_konversi($nim)
    {
        $query = $this->db->select('*')
                ->from('krs')
                ->join('krs_detail as krd', 'krs.kode_krs=krd.kode_krs')
                ->where('nim', $nim)
                ->where_not_in('status', 'K')
                ->where('semester', 'K')
                ->get()->row_object();
        if (!empty($query)) {
            return $query->kode_krs;
        } else {
            return 0;
        }
    }

    function get_mahasiswa_by_mk()
    {
        $matakuliah = $this->db->get('matakuliah')->result();
        foreach ($matakuliah as $mk) {
            $query[] = $this->db->select('mah.nim, mah.nama_mahasiswa, mak.kode_matakuliah, mak.nama_matakuliah ')
                    ->from('krs')
                    ->join('mahasiswa as mah', 'mah.nim=krs.nim')
                    ->join('krs_detail as kd', 'kd.kode_krs=krs.kode_krs')
                    ->join('matakuliah as mak', 'mak.id_matakuliah=kd.id_matakuliah')
                    ->where('mak.id_matakuliah', $mk->id_matakuliah)
                    ->get()->result_object();
        }
        return $query;

    }

    function get_jml_makul_transfer($nim)
    {
        $tahun_akademik = $this->db->where('status', 'A')->get('tahun_akademik')->row_object();
        $semester = array('1', '2', '3', '4');
        $query = $this->db->select('mak.id_matakuliah')
                ->from('krs')
                ->join('krs_detail as kd', 'kd.kode_krs=krs.kode_krs')
                ->join('khs_detail as khd', 'khd.kode_krs_detail=kd.kode_krs_detail')
                ->join('matakuliah as mak', 'mak.id_matakuliah=kd.id_matakuliah')
                ->where('krs.nim', $nim)
                ->where_not_in('krs.kode_tahun_akademik', $tahun_akademik->kode_tahun_akademik)
                ->where_in('substr(mak.kode_matakuliah,6,1)', $semester)
                ->group_by('mak.id_matakuliah')
                ->get()->result();
        return count($query);
    }

}
