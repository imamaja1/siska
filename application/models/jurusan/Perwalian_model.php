<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perwalian_model extends CI_Model
{

    private $tabel = 'perwalian';

    function cek_status_konsultasi_krs($kode_konsultasi_perwalian)
    {
        return $this->db->get_where('konsultasi_perwalian', array('kode_konsultasi_perwalian' => $kode_konsultasi_perwalian));
    }

    function get()
    {
        return $this->db->select('m.nama_mahasiswa, m.nim, kode_perwalian, d.nama_dosen')
            ->from('perwalian as p')
            ->join('dosen as d', 'p.kode_dosen=d.kode_dosen')
            ->join('mahasiswa as m', 'p.nim=m.nim')
            ->get()->result();
    }

    public function get_homebase()
    {
        $query = $this->db->select('count(kode_dosen) as jumlah_dosen, homebase')
            ->from('dosen')
            ->group_by('homebase')
            ->where('homebase IS NOT NULL')
            ->where('status_dosen', 'T')
            ->get()->result_object();
        return $query;
    }

    public function get_dosen_by_homebase($homebase)
    {
        $query = $this->db->select('*')
            ->from('dosen')
            ->where('status_dosen', 'T')
            ->where('status_login', 'A')
            ->where('homebase', $homebase)
            ->get()->result();
        return $query;
    }

    public function get_dosen_aktif_by_homebase($homebase)
    {
        $query = $this->db->select('*')
            ->from('dosen')
            ->where('status_dosen', 'T')
            ->where('status_login', 'A')
            ->where('homebase', $homebase)
            ->order_by('kode_dosen', 'ASC')
            ->get()->result();
        return $query;
    }

    public function get_mahasiswa_by_homebase($homebase, $ta, $limit, $offset)
    {
        $query = $this->db->select('nim')
            ->from('mahasiswa')
            ->where('program_studi_kode', $homebase)
            ->where('mid(nim,1,2)', $ta)
            ->limit($limit, $offset)
            ->order_by('nim ASC')
            ->get()->result_object();

        return $query;
    }

    function get_by_dosen($kode_dosen)
    {

        return $this->db->select('m.nama_mahasiswa, m.nim, kode_perwalian, d.nama_dosen, d.kode_dosen')
            ->from('perwalian as p')
            ->join('dosen as d', 'p.kode_dosen=d.kode_dosen')
            ->join('mahasiswa as m', 'p.nim=m.nim')
            ->where(array('p.kode_dosen' => $kode_dosen))
            ->get()->result();
    }

    public function get_konsultasi_perwalian_by_dosen($kode_dosen, $kode_tahun_akademik)
    {
        return $this->db->select('p.kode_dosen_perwakilan, m.nama_mahasiswa, m.nim, kode_perwalian, d.nama_dosen, d.kode_dosen, kp.status_cetak, kp.kode_konsultasi_perwalian')
            ->from('perwalian as p')
            ->join('dosen as d', 'p.kode_dosen=d.kode_dosen')
            ->join('mahasiswa as m', 'p.nim=m.nim')
            ->join('konsultasi_perwalian as kp', 'kp.nim=p.nim')
            ->where(array('p.kode_dosen' => $kode_dosen, 'kp.kode_tahun_akademik' => $kode_tahun_akademik))
            ->group_by('p.nim')
            ->get()->result();
    }

    public function get_konsultasi_perwalian_by_dosen_angkatan_jurusan($kode_dosen, $kode_tahun_akademik, $angkatan, $jurusan)
    {
        return $this->db->select('p.kode_dosen_perwakilan, m.nama_mahasiswa, m.nim, kode_perwalian, d.nama_dosen, d.kode_dosen, kp.status_cetak, kp.kode_konsultasi_perwalian')
            ->from('perwalian as p')
            ->join('dosen as d', 'p.kode_dosen=d.kode_dosen')
            ->join('mahasiswa as m', 'p.nim=m.nim')
            ->join('konsultasi_perwalian as kp', 'kp.nim=p.nim')
            ->where(array('kp.kode_tahun_akademik' => $kode_tahun_akademik, 'mid(p.nim,1,2)' => $angkatan, 'mid(p.nim,3,3)' => $jurusan))
            ->where("(p.kode_dosen_perwakilan=".$this->db->escape($kode_dosen)." or p.kode_dosen=".$this->db->escape($kode_dosen).")")
            ->group_by('p.nim')
            ->get()->result();
    }

    public function get_konsultasi_perwalian_umum($nim, $kode_tahun_akademik)
    {
        return $this->db->select('p.kode_dosen_perwakilan, m.nama_mahasiswa, m.nim, kode_perwalian, d.nama_dosen, d.kode_dosen, kp.status_cetak, kp.kode_konsultasi_perwalian')
            ->from('perwalian as p')
            ->join('dosen as d', 'p.kode_dosen=d.kode_dosen')
            ->join('mahasiswa as m', 'p.nim=m.nim')
            ->join('konsultasi_perwalian as kp', 'kp.nim=p.nim')
            ->where(array('p.nim' => $nim, 'kp.kode_tahun_akademik' => $kode_tahun_akademik))
            ->group_by('p.nim')
            ->get()->result();
    }

    public function get_konsultasi_perwalian_by_nim($kode_dosen, $kode_tahun_akademik, $kata_kunci)
    {
        return $this->db->select('*')
            ->from('perwalian as p')
            ->join('dosen as d', 'p.kode_dosen=d.kode_dosen')
            ->join('mahasiswa as m', 'p.nim=m.nim')
            ->join('konsultasi_perwalian as kp', 'kp.nim=p.nim')
            ->where(array('kp.kode_tahun_akademik' => $kode_tahun_akademik, 'p.nim' => $kata_kunci))
            ->where("(p.kode_dosen_perwakilan=".$this->db->escape($kode_dosen)." or p.kode_dosen=".$this->db->escape($kode_dosen).")")
            ->group_by('p.nim')
            ->get()->result();
    }

// ------------------------------------------------------------------------------------------------------------------------------------------
    public function get_konsultasi_perwalian_by_umum($kode_tahun_akademik, $nim)
    {
        return $this->db->select('*')
            ->from('perwalian as p')
            ->join('mahasiswa as m', 'p.nim=m.nim')
            ->join('konsultasi_perwalian as kp', 'kp.nim=p.nim')
            ->join('konsultasi_perwalian as kpd', 'kpd.kode_konsultasi_perwalian=kp.kode_konsultasi_perwalian')
            ->where(array('kpd.kode_tahun_akademik' => $kode_tahun_akademik, 'kp.nim' => $nim))
            ->get()->result();
    }

// ------------------------------------------------------------------------------------------------------------------------------------------
    public function get_konsultasi_perwalian_by_kode_konsultasi_perwalian($kode_konsultasi_perwalian)
    {
        return $this->db->select('*')
            ->from('perwalian as p')
            ->join('dosen as d', 'p.kode_dosen=d.kode_dosen')
            ->join('mahasiswa as m', 'p.nim=m.nim')
            ->join('konsultasi_perwalian as kp', 'kp.nim=p.nim')
            ->where('kp.kode_konsultasi_perwalian', $kode_konsultasi_perwalian)
            ->group_by('p.nim')
            ->get()->result();
    }

    function get_konsultasi_perwalian_by_kode_konsultasi_perwalian_v2($kode_konsultasi_perwalian)
    {
        return $this->db->select('*')
            ->from('perwalian as p')
            ->join('dosen as d', 'p.kode_dosen=d.kode_dosen')
            ->join('mahasiswa as m', 'p.nim=m.nim')
            ->join('konsultasi_perwalian as kp', 'kp.nim=p.nim')
            ->where('kp.kode_konsultasi_perwalian', $kode_konsultasi_perwalian)->get();
    }

    function aktif($kode_konsultasi_perwalian)
    {
        $this->db->trans_start();
        $this->db->set('status_cetak', 'A')
            ->where('kode_konsultasi_perwalian', $kode_konsultasi_perwalian)
            ->update('konsultasi_perwalian');
        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function nonaktif($kode_konsultasi_perwalian)
    {
        $this->db->trans_start();
        $this->db->set('status_cetak', 'N')
            ->where('kode_konsultasi_perwalian', $kode_konsultasi_perwalian)
            ->update('konsultasi_perwalian');
        $this->db->trans_complete();

        if ($this->db->trans_status() === TRUE) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function get_perwalian_by_nim($nim)
    {
        $query = $this->db->select('*')
            ->from('perwalian as p')
            ->join('mahasiswa as m', 'p.nim=m.nim')
            ->join('dosen as d', 'p.kode_dosen=d.kode_dosen')
            ->where('p.nim', $nim)
            ->get()->row_object();
        return $query;
    }


    function filter($angkatan, $kode_dosen, $kode_program_studi)
    {

        return $this->db->select('m.nama_mahasiswa, m.nim, kode_perwalian, d.nama_dosen, p.kode_dosen_perwakilan')
            ->from('perwalian as p')
            ->join('dosen as d', 'p.kode_dosen=d.kode_dosen')
            ->join('mahasiswa as m', 'p.nim=m.nim')
            ->where(array('p.kode_dosen' => $kode_dosen, 'mid(p.nim,1,2)' => $angkatan, 'm.program_studi_kode' => $kode_program_studi))
            ->get()->result();

    }

    function get_perwalian_by_nim_dan_kode_dosen($nim, $kode_dosen, $kode_tahun_akademik)
    {
        return $this->db->select('p.nim, m.nama_mahasiswa, s.status_perkuliahan')
            ->from('perwalian as p')
            ->join('mahasiswa as m', 'p.nim=m.nim')
            ->join('status_perkuliahan as s', 'p.nim=s.nim', 'LEFT')
            ->where(array('p.kode_dosen' => $kode_dosen, 's.kode_tahun_akademik' => $kode_tahun_akademik, 'p.nim' => $nim))
            ->get()->result();
    }

    function get_perwalian_by_nama_dan_kode_dosen($nama_mahasiswa, $kode_dosen, $kode_tahun_akademik)
    {
        $sql = " SELECT perwalian.nim, mahasiswa.nama_mahasiswa, status_perkuliahan.status_perkuliahan FROM perwalian ";
        $sql .= " INNER JOIN mahasiswa ON perwalian.nim=mahasiswa.nim ";
        $sql .= " LEFT JOIN status_perkuliahan ON perwalian.nim=status_perkuliahan.nim";
        $sql .= " WHERE perwalian.kode_dosen=? AND status_perkuliahan.kode_tahun_akademik=?";
        $sql .= " AND mahasiswa.nama_mahasiswa LIKE '%" . $this->db->escape_like_str($nama_mahasiswa) . "%'";
        $sql .= " ORDER BY RIGHT(perwalian.nim,4) ";
        return $this->db->query($sql, array($kode_dosen, $kode_tahun_akademik))->result();
    }

    function get_all_status_perkuliahan_perwalian_by_kode_tahun_akademik_angkatan_jurusan_and_dosen($kode_tahun_akademik, $kode_angkatan_and_jurusan, $kode_dosen)
    {
        $sql = " SELECT perwalian.nim, mahasiswa.nama_mahasiswa, dosen.nama_dosen, status_perkuliahan.status_perkuliahan  FROM perwalian ";
        $sql .= " INNER JOIN mahasiswa ON perwalian.nim=mahasiswa.nim ";
        $sql .= " INNER JOIN dosen ON perwalian.kode_dosen=dosen.kode_dosen ";
        $sql .= " LEFT JOIN status_perkuliahan ON perwalian.nim=status_perkuliahan.nim AND status_perkuliahan.kode_tahun_akademik=?";
        $sql .= " WHERE substr(perwalian.nim,1,5)=? ";
        $sql .= " AND perwalian.kode_dosen=? ";
        $sql .= " ORDER BY RIGHT(perwalian.nim,4) ";

        return $this->db->query($sql, array($kode_tahun_akademik, $kode_angkatan_and_jurusan, $kode_dosen))->result();
    }

    public function simpan($data)
    {
        return $this->db->insert($this->tabel, $data);
    }

    function ubah($data, $id)
    {
        return $this->db->where('kode_perwalian', $id)->update($this->tabel, $data);
    }

    function hapus($id)
    {
        return $this->db->where('kode_perwalian', $id)->delete($this->tabel);
    }

    public function get_angkatan_list()
    {
        return $this->db->distinct()->select('mid(nim,1,2) as angkatan')
            ->from('mahasiswa')
            ->order_by('angkatan', 'DESC')
            ->get()->result_object();
    }

    public function get_nim_perwalian($kode_program_studi, $angkatan)
    {
        return $this->db->select('p.nim')
            ->from($this->tabel . ' as p')
            ->join('mahasiswa as m', 'm.nim=p.nim')
            ->where('m.program_studi_kode', $kode_program_studi)
            ->where('mid(p.nim,1,2)', $angkatan)
            ->get()->result_array();
    }

    public function get_data_perwalian($kode_program_studi, $angkatan)
    {
        return $this->db->select('p.kode_perwalian, p.nim, m.nama_mahasiswa, d.nama_dosen, p.kode_tahun_akademik, ta.tahun_akademik, ta.semester')
            ->from($this->tabel . ' as p')
            ->join('mahasiswa as m', 'm.nim=p.nim')
            ->join('dosen as d', 'd.kode_dosen=p.kode_dosen')
            ->join('tahun_akademik as ta', 'ta.kode_tahun_akademik=p.kode_tahun_akademik', 'left')
            ->where('m.program_studi_kode', $kode_program_studi)
            ->where('mid(p.nim,1,2)', $angkatan)
            ->order_by('p.nim', 'ASC')
            ->get()->result();
    }

    public function hapus_perwalian($kode_program_studi, $angkatan)
    {
        $nims = array_column($this->get_nim_perwalian($kode_program_studi, $angkatan), 'nim');

        if (empty($nims)) {
            return array(
                'status' => true,
                'hapus_perwalian' => 0,
                'hapus_konsultasi' => 0,
            );
        }

        $this->db->trans_start();

        $this->db->where_in('nim', $nims)
            ->delete('konsultasi_perwalian');
        $hapus_konsultasi = $this->db->affected_rows();

        $this->db->where_in('nim', $nims)
            ->delete($this->tabel);
        $hapus_perwalian = $this->db->affected_rows();

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return array('status' => false);
        }

        return array(
            'status' => true,
            'hapus_perwalian' => $hapus_perwalian,
            'hapus_konsultasi' => $hapus_konsultasi,
        );
    }

    function autocomplate($keyword)
    {
        return $this->db->select('*')
            ->from('perwalian')
            ->like('nim',$keyword,'both')
            ->group_by('nim')
            ->order_by('nim')
            ->limit(6)
            ->get()->result_object();
    }

    function cek_mahasiswa_baru($ta)
    {
        return $this->db->get_where($this->tabel, array('mid(nim,1,2)' => $ta))->result();
    }

    public function simpan_konsultasi_perwalian($data)
    {
        return $this->db->insert('konsultasi_perwalian', $data);
    }

    public function status_cetak($data, $param)
    {
        return $this->db->where('kode_konsultasi_perwalian', $param)->update('konsultasi_perwalian', $data);
    }

    function get_nama_dosen_perwalian_by_kode_dosen_perwakilan($kode_dosen_perwakilan)
    {
        return $this->db->distinct('perwalian.kode_dosen, dosen.nama_dosen')
            ->from('perwalian')
            ->join('dosen', 'perwalian.kode_dosen=dosen.kode_dosen')
            ->where('perwalian.kode_dosen_perwakilan', $kode_dosen_perwakilan)->get();
    }

    function get_perwalian_by_kode_dosen_dan_kode_dosen_perwakilan($kode_tahun_akademik, $kode_dosen_perwakilan, $kode_dosen)
    {
        $sql = "SELECT perwalian.nim, mahasiswa.nama_mahasiswa, status_perkuliahan.status_perkuliahan FROM perwalian";
        $sql .= " INNER JOIN mahasiswa ON perwalian.nim=mahasiswa.nim";
        $sql .= " LEFT JOIN status_perkuliahan ON perwalian.nim=status_perkuliahan.nim";
        $sql .= " AND status_perkuliahan.kode_tahun_akademik=?";
        $sql .= " WHERE perwalian.kode_dosen=? AND perwalian.kode_dosen_perwakilan=?";
        $sql .= " ORDER BY substr(perwalian.nim,1,5), right(perwalian.nim,-4)";
        return $this->db->query($sql, array($kode_tahun_akademik, $kode_dosen_perwakilan, $kode_dosen));
    }

    function get_mahasiswa_belum_ada_dosen_wali($tahun_angkatan, $homebase)
    {

        $mahasiswa_punya_wali = $this->db->select('perwalian.nim')
            ->from('perwalian')
            ->join('mahasiswa as mah','mah.nim=perwalian.nim')
            ->where('mah.program_studi_kode', $homebase)
            ->where('mid(perwalian.nim,1,2)', $tahun_angkatan)
            ->get()->result_object();
        if (count($mahasiswa_punya_wali) > 0)
        {
            foreach ($mahasiswa_punya_wali as $row) {
                $nim_mahasiwa_punya_wali[] = $row->nim;
            }
        }else{
            $nim_mahasiwa_punya_wali = [0];
        }

        $mahasiswa_belum_punya_wali = $this->db->select('nim,mahasiswa.nama_mahasiswa')
            ->from('mahasiswa')
            ->where('program_studi_kode', $homebase)
            ->where('mid(nim,1,2)', $tahun_angkatan)
            ->where_not_in('nim', $nim_mahasiwa_punya_wali)
            ->get()->result_object();

        if (count($mahasiswa_belum_punya_wali) > 0) {
            return $mahasiswa_belum_punya_wali;
        } else {
            return false;
        }
    }

    public function get_mahasiswa_sudah_ada_dosen_wali($tahun_angkatan, $homebase)
    {
        return $this->db->select('p.nim, m.nama_mahasiswa, p.kode_dosen, d.nama_dosen')
            ->from('perwalian as p')
            ->join('mahasiswa as m', 'm.nim=p.nim')
            ->join('dosen as d', 'd.kode_dosen=p.kode_dosen', 'left')
            ->where('m.program_studi_kode', $homebase)
            ->where('mid(p.nim,1,2)', $tahun_angkatan)
            ->order_by('p.nim', 'ASC')
            ->get()->result_object();
    }

    public function cek_perwalian_exists($nim)
    {
        return (bool) $this->db->where('nim', $nim)->count_all_results($this->tabel);
    }

    public function ubah_dosen_wali($nim, $kode_dosen)
    {
        return $this->db->where('nim', $nim)->update($this->tabel, array('kode_dosen' => $kode_dosen));
    }

    public function get_duplikat_perwalian()
    {
        return $this->db->select('p.nim, m.nama_mahasiswa, p.kode_tahun_akademik, COUNT(*) as jumlah_record, MIN(p.kode_perwalian) as kode_perwalian_lama, MAX(p.kode_perwalian) as kode_perwalian_terbaru, ta.tahun_akademik, ta.semester')
            ->from('perwalian as p')
            ->join('mahasiswa as m', 'm.nim=p.nim', 'left')
            ->join('tahun_akademik as ta', 'ta.kode_tahun_akademik=p.kode_tahun_akademik', 'left')
            ->group_by('p.nim, p.kode_tahun_akademik')
            ->having('COUNT(*) >', 1)
            ->order_by('p.nim', 'ASC')
            ->get()->result();
    }

    public function get_detail_duplikat()
    {
        return $this->db->select('p.kode_perwalian, p.nim, p.kode_dosen, d.nama_dosen, p.kode_tahun_akademik, p.date_created')
            ->from('perwalian as p')
            ->join('dosen as d', 'd.kode_dosen=p.kode_dosen', 'left')
            ->where_in('p.nim', "SELECT nim FROM perwalian GROUP BY nim, kode_tahun_akademik HAVING COUNT(*) > 1", false)
            ->order_by('p.nim', 'ASC')
            ->order_by('p.kode_perwalian', 'ASC')
            ->get()->result();
    }

    public function resolusi_duplikat($nim, $kode_tahun_akademik, $kode_perwalian_terbaru)
    {
        $this->db->trans_start();
        $this->db->where('nim', $nim);
        if ($kode_tahun_akademik !== null) {
            $this->db->where('kode_tahun_akademik', $kode_tahun_akademik);
        }
        $this->db->where('kode_perwalian !=', $kode_perwalian_terbaru);
        $this->db->delete('perwalian');
        $affected = $this->db->affected_rows();
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        }
        return $affected;
    }

    public function get_konsultasi_kode_dosen_null($kode_tahun_akademik = null, $angkatan = null)
    {
        $sql = "SELECT kp.kode_konsultasi_perwalian, kp.nim, kp.kode_tahun_akademik, COALESCE(m.nama_mahasiswa, '(NO MAHASISWA)') AS nama_mahasiswa, pw.kode_dosen, d.nama_dosen, ta.tahun_akademik, ta.semester
                FROM konsultasi_perwalian kp
                JOIN (SELECT p.nim, p.kode_dosen FROM perwalian p
                      JOIN (SELECT nim, MAX(kode_perwalian) AS maxid FROM perwalian GROUP BY nim) mx
                        ON mx.nim = p.nim AND mx.maxid = p.kode_perwalian) pw
                  ON pw.nim = kp.nim
                LEFT JOIN mahasiswa m ON m.nim = kp.nim
                LEFT JOIN dosen d ON d.kode_dosen = pw.kode_dosen
                LEFT JOIN tahun_akademik ta ON ta.kode_tahun_akademik = kp.kode_tahun_akademik
                WHERE (kp.kode_dosen IS NULL OR kp.kode_dosen != pw.kode_dosen)";

        $params = array();
        if ($kode_tahun_akademik !== null) {
            $sql .= " AND kp.kode_tahun_akademik = ?";
            $params[] = $kode_tahun_akademik;
        }
        if ($angkatan !== null) {
            $sql .= " AND mid(kp.nim,1,2) = ?";
            $params[] = $angkatan;
        }
        $sql .= " ORDER BY kp.kode_tahun_akademik DESC, kp.nim ASC";

        return $this->db->query($sql, $params)->result();
    }

    public function get_perwalian_tanpa_konsultasi($kode_tahun_akademik = null, $angkatan = null)
    {
        $sql = "SELECT p.nim, COALESCE(m.nama_mahasiswa, '(NO MAHASISWA)') AS nama_mahasiswa, p.kode_dosen, d.nama_dosen
                FROM perwalian p
                LEFT JOIN mahasiswa m ON m.nim = p.nim
                LEFT JOIN dosen d ON d.kode_dosen = p.kode_dosen
                WHERE NOT EXISTS (
                    SELECT 1 FROM konsultasi_perwalian kp
                    WHERE kp.nim = p.nim";
        $params = array();
        if ($kode_tahun_akademik !== null) {
            $sql .= " AND kp.kode_tahun_akademik = ?";
            $params[] = $kode_tahun_akademik;
        }
        $sql .= ")";
        if ($angkatan !== null) {
            $sql .= " AND mid(p.nim,1,2) = ?";
            $params[] = $angkatan;
        }
        $sql .= " ORDER BY p.nim ASC";

        return $this->db->query($sql, $params)->result();
    }

    public function buat_konsultasi_perwalian_hilang($kode_tahun_akademik, $angkatan = null)
    {
        $sql = "INSERT INTO konsultasi_perwalian (kode_tahun_akademik, nim, kode_dosen, status_cetak)
                SELECT ?, p.nim, p.kode_dosen, 'N'
                FROM perwalian p
                WHERE NOT EXISTS (
                    SELECT 1 FROM konsultasi_perwalian kp
                    WHERE kp.nim = p.nim AND kp.kode_tahun_akademik = ?)";
        $params = array($kode_tahun_akademik, $kode_tahun_akademik);
        if ($angkatan !== null) {
            $sql .= " AND mid(p.nim,1,2) = ?";
            $params[] = $angkatan;
        }

        $this->db->trans_start();
        $this->db->query($sql, $params);
        $affected = $this->db->affected_rows();
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        }
        return $affected;
    }

    public function sync_konsultasi_kode_dosen($kode_tahun_akademik = null, $kode_konsultasi_perwalian = null, $angkatan = null)
    {
        $sql = "UPDATE konsultasi_perwalian kp
                JOIN (SELECT p.nim, p.kode_dosen FROM perwalian p
                      JOIN (SELECT nim, MAX(kode_perwalian) AS maxid FROM perwalian GROUP BY nim) m
                        ON m.nim = p.nim AND m.maxid = p.kode_perwalian) pw
                  ON pw.nim = kp.nim
                SET kp.kode_dosen = pw.kode_dosen
                WHERE (kp.kode_dosen IS NULL OR kp.kode_dosen != pw.kode_dosen)";

        $params = array();
        if ($kode_tahun_akademik !== null) {
            $sql .= " AND kp.kode_tahun_akademik = ?";
            $params[] = $kode_tahun_akademik;
        }
        if ($angkatan !== null) {
            $sql .= " AND mid(kp.nim,1,2) = ?";
            $params[] = $angkatan;
        }
        if ($kode_konsultasi_perwalian !== null) {
            $sql .= " AND kp.kode_konsultasi_perwalian = ?";
            $params[] = $kode_konsultasi_perwalian;
        }

        $this->db->trans_start();
        $this->db->query($sql, $params);
        $affected = $this->db->affected_rows();
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        }
        return $affected;
    }

    function cek_status_cetak($nim, $kode_tahun_akademik)
    {
        $query = $this->db->select('*')
            ->from('konsultasi_perwalian')
            ->where('nim', $nim)
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->where('status_cetak', 'A')
            ->get()->result();
        return $query;
    }

    function ubah_kp($kode_konsultasi_perwalian, $data = [])
    {
        return $this->db->update('konsultasi_perwalian', $data, array('kode_konsultasi_perwalian' => $kode_konsultasi_perwalian));
    }

    public function dosen_wali()
    {
        return $this->db->select('*, count(kode_perwalian) as jml')
            ->from('perwalian as per')
            ->join('dosen','per.kode_dosen=dosen.kode_dosen')
            ->join('mahasiswa as mah','mah.nim=per.nim')
            ->join('program_studi as ps','dosen.homebase=ps.kode_program_studi')
            ->where('mah.status','A')
            ->group_by('per.kode_dosen')
            ->get()->result();
    }

    public function rekap_dosen_wali(){
                return $this->db->select('dosen.nama_dosen, mah.nim, nama_mahasiswa, ps.nama_program_studi as jurusan, program_studi.nama_program_studi as homebase')
                ->from('perwalian as per')
                ->join('dosen','per.kode_dosen=dosen.kode_dosen')
                ->join('mahasiswa as mah','mah.nim=per.nim')      
                ->join('program_studi as ps', 'mah.program_studi_kode=ps.kode_program_studi')
          		->join('program_studi', 'program_studi.kode_program_studi=dosen.homebase')
                ->where('mah.status','A')
                ->order_by('dosen.kode_dosen')
                ->order_by('mah.nim','DESC')
                ->get()->result();
    }
  
    public function rekap_dosen_wali_perdosen($kode_dosen)
    {
        return $this->db->select('dosen.nama_dosen, mah.nim, nama_mahasiswa, ps.nama_program_studi as jurusan, program_studi.nama_program_studi as homebase')
            ->from('perwalian as per')
            ->join('dosen', 'per.kode_dosen=dosen.kode_dosen')
            ->join('mahasiswa as mah', 'mah.nim=per.nim')
            ->join('program_studi as ps', 'mah.program_studi_kode=ps.kode_program_studi')
            ->join('program_studi', 'program_studi.kode_program_studi=dosen.homebase')
            ->where('mah.status', 'A')
            ->where('dosen.kode_dosen', $kode_dosen)
            ->order_by('dosen.kode_dosen')
            ->order_by('mah.nim', 'DESC')
            ->get()->result();
    }

}
