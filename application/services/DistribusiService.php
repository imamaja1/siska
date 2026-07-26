<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DistribusiService extends MY_Service {

    public function __construct() {
        parent::__construct();
    }

    public function getDistribusiDosen($kode_tahun_akademik) {
        return $this->db->select('*')
            ->from('kelas')
            ->join('mengajar','mengajar.kelas_id=kelas.kelas_id')
            ->join('dosen','mengajar.kode_dosen=dosen.kode_dosen')
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->group_by('mengajar.kode_dosen')
            ->get()->result();
    }

    public function getBebanQuery($kode_tahun_akademik, $kode_dosen) {
        return $this->db->select('GROUP_CONCAT(nama_kelas) as nama_kelas,singkatan_program_studi,dos.nama_dosen,semester,nama_matakuliah,sks_teori, sks_praktek, sks_praktikum, (sks_teori+sks_praktek+sks_praktikum) as sks, count(kelas.kelas_id) as jml_kelas')
            ->from('kelas')
            ->join('mengajar as mng','kelas.kelas_id=mng.kelas_id')
            ->join('matakuliah as mak','mak.id_matakuliah=kelas.id_matakuliah')
            ->join('dosen as dos','mng.kode_dosen=dos.kode_dosen')
            ->join('program_studi as ps','ps.kode_program_studi=mak.kode_program_studi')
            ->join('nama_kelas as nk','nk.nama_kelas_id=kelas.nama_kelas_id')
            ->where('kode_tahun_akademik', $kode_tahun_akademik)
            ->where('mng.kode_dosen', $kode_dosen)
            ->group_by('kelas.id_matakuliah')
            ->get_compiled_select();
    }

    public function getBebanData($sub_query) {
        return $this->db->select('*, (sks*jml_kelas) as beban')
            ->from("(" . $sub_query . ") as mah")
            ->get()->result();
    }

    public function getBebanRowspan($sub_query) {
        return $this->db->select('*, (sks*jml_kelas) as beban')
            ->from("(" . $sub_query . ") as mah")
            ->count_all_results();
    }

    public function getBebanCompiled($sub_query) {
        return $this->db->select('*, (sks*jml_kelas) as beban')
            ->from("(" . $sub_query . ") as mah")
            ->get_compiled_select();
    }

    public function getTotalBeban($beban_query) {
        return $this->db->select('*,sum(beban) as total_beban')
            ->from("(" . $beban_query . ") as cut")
            ->get()->row();
    }

    public function buildDistribusiData($kode_tahun_akademik) {
        $dosen = $this->getDistribusiDosen($kode_tahun_akademik);
        if (!count($dosen)) {
            return false;
        }
        $i = 0;
        $hasil = array();
        foreach ($dosen as $row) {
            $beban = $this->getBebanQuery($kode_tahun_akademik, $row->kode_dosen);
            $hasil[$i]['nama_dosen'] = $row->nama_dosen;
            $hasil[$i]['data'] = $this->getBebanData($beban);
            $hasil[$i]['rowspan'] = $this->getBebanRowspan($beban);
            $beb = $this->getBebanCompiled($beban);
            $total_beban = $this->getTotalBeban($beb);
            $hasil[$i]['total_beban'] = $total_beban->total_beban;
            $i++;
        }
        return $hasil;
    }
}
