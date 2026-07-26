<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Status_perkuliahan_model extends CI_Model {

    private $table = "status_perkuliahan";

    public function __construct() {
        parent::__construct();
    }

    public function ubah($data, $id) {
        # code...
        return $this->db->where('kode_status_perkuliahan', $id)->update($this->table, $data);
    }

    public function filter($kode_tahun_akademik, $angkatan, $kode_prodi) {

            $query = $this->db->select('kode_status_perkuliahan, status_perkuliahan, nama_mahasiswa, mah.nim, kode_tahun_akademik')
                ->from('status_perkuliahan as sp')
                ->join('mahasiswa as mah','sp.nim=mah.nim')
                ->where('mah.program_studi_kode', $kode_prodi)
                ->where('substring(sp.nim,1,2)', $angkatan)
                ->where('kode_tahun_akademik', $kode_tahun_akademik)
                ->get()->result_object();
       return $query;
    }

    public function filter1($angkatan, $kode_program_studi, $kode_tahun_akademik){

            $cek = $this->db->select('sp.nim')
                ->from('status_perkuliahan as sp')
                ->join('mahasiswa as mah','mah.nim=sp.nim')
                ->where('kode_tahun_akademik', $kode_tahun_akademik)
                ->where('mah.program_studi_kode', $kode_program_studi)
                ->where('substring(mah.nim,1,2)', $angkatan)
                ->get()->result_object();

            foreach ($cek as $row)
            {
                $nim[] = $row->nim;
            }
            if (isset($nim) ) :
                $query = $this->db->select('nama_mahasiswa, nim, npm')
                    ->from('mahasiswa as m')
                    ->where('substring(m.nim,1,2)', $angkatan)
                    ->where('m.program_studi_kode', $kode_program_studi)
                    ->where_not_in('m.nim',$nim)
                    ->get()->result();
            else:
                $query = $this->db->select('nama_mahasiswa, nim, npm')
                    ->from('mahasiswa as m')
                    ->where('substring(m.nim,1,2)', $angkatan)
                    ->where('m.program_studi_kode', $kode_program_studi)
                    ->get()->result();
            endif;

            return $query;
    }

    public function simpan($data) {
        return $this->db->insert($this->table, $data);
    }

    public function cek_exis($nim, $kode_tahun_akademik) {
        return $this->db->get_where($this->table, array('nim' => $nim, 'kode_tahun_akademik' => $kode_tahun_akademik))->num_rows();
    }

    public function autocomplate($keyword) {
//        return $this->db->query("SELECT * FROM mahasiswa WHERE nim like '" . $keyword . "%' ORDER BY nim LIMIT 6")->result();
        $tahun_akademik = $this->db->select('kode_tahun_akademik')
                ->from('tahun_akademik')
                ->where('status', 'A')
                ->get()->row_object();
        if (is_null(get_cookie('kode_tahun_akademik'))){
            $kode_tahun_akademik = $tahun_akademik->kode_tahun_akademik;
        }else{
            $kode_tahun_akademik = get_cookie('kode_tahun_akademik');
        }

//        $cek = $this->db->select('nim')
//            ->where('kode_tahun_akademik', $kode_tahun_akademik)
//            ->get($this->table)->result_object();
//        foreach ($cek as $row)
//        {
//            $nim[] = $row->nim;
//        }

//        if (isset($nim) ) :
            $query = $this->db->select('*')
                ->from('mahasiswa')
                ->like('nim', $keyword, 'after')
//                ->where_not_in('nim', $nim)
                ->where_not_in('nim', "select `nim` from status_perkuliahan where kode_tahun_akademik='$kode_tahun_akademik'", false)
                ->order_by('nim ASC')
                ->limit(6)
                ->get()->result();
//        else:
//            $query = $this->db->select('*')
//                ->from('mahasiswa')
//                ->like('nim', $keyword, 'after')
//                ->order_by('nim ASC')
//                ->limit(6)
//                ->get()->result();
//        endif;
        return $query;
    }

    public function cek_status_aktif_mahasiswa($nim, $kode_tahun_akademik)
    {
        $query = $this->db->get_where($this->table, array('nim'=>$nim, 'kode_tahun_akademik'=>$kode_tahun_akademik, 'status_perkuliahan'=>'A'))->result();
        return $query;
    }

}

/* End of file Status_perkuliahan_model.php */
/* Location: ./application/models/Status_perkuliahan_model.php */
?>