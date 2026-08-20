<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Kuisioner_model extends CI_Model
{
    function get_matakuliah_kuisioner($nim, $kode_tahun_akademik=null)
    {
        $query = $this->db->select('*, kelas.kelas_id,km.kelas_mahasiswa_id, mak.kode_matakuliah')
        ->from('kelas')
        ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
        ->join('kelas_mahasiswa as km', 'km.kelas_id=kelas.kelas_id')
        ->join('krs_detail as kd', 'km.kode_krs_detail=kd.kode_krs_detail')
        ->join('krs', 'krs.kode_krs=kd.kode_krs')
        ->join('kuisioner as kuis', 'km.kelas_mahasiswa_id=kuis.kelas_mahasiswa_id', 'LEFT')
        ->where('kuisioner_id', null)
        ->where('krs.nim',$nim)
        // TODO::cek ini untuk quisioner
        ->where('kelas.kode_tahun_akademik',$kode_tahun_akademik)
        ->get()->result();

        return $query;
    }

    function get_soal($kelas_mahasiswa_id)
    {
        $matakuliah = $this->db->select('*')
        ->from('kelas')
        ->join('matakuliah as mak','kelas.id_matakuliah=mak.id_matakuliah')
        ->join('kelas_mahasiswa as km','kelas.kelas_id=km.kelas_id')
        ->where('km.kelas_mahasiswa_id', $kelas_mahasiswa_id)
        ->get()->row_object();
        if (!$matakuliah) {
            return array();
        }
        if ($matakuliah->sks_praktikum == 0)
        {
            $query = $this->db->select('*')
            ->from('soal_kuisioner')
            ->join('kategori_kuisioner as kk', 'kk.kategori_id=soal_kuisioner.kategori_id')
            ->where('jenis', 'T')
            ->get()->result();
        }else{
            $query = $this->db->select('*')
                ->from('soal_kuisioner')
                ->join('kategori_kuisioner as kk', 'kk.kategori_id=soal_kuisioner.kategori_id')
                ->where('jenis', 'P')
                ->or_where('jenis','T')
                ->get()->result();
        }
        return $query;
    }

    function get_soal_layanan()
    {
        $bagian = $this->db->select('bag.id_bagian,nama_bagian, count(bag.id_bagian) as rowspan')
            ->from('soal_kuisioner_pelayanan as skp')
            ->join('soal_kuisioner as sk','sk.soal_kuisioner_id=skp.soal_kuisioner_id')
            ->join('bagian as bag','bag.id_bagian=skp.id_bagian')
            ->order_by('bag.id_bagian ASC')
            ->group_by('bag.id_bagian')
            ->get()->result_object();
        $data = array();
        $i=0;
        foreach ($bagian as $row) {
            $data[$i]['nama_bagian'] = $row->nama_bagian;
            $data[$i]['rowspan'] = $row->rowspan;
            $data[$i]['data'] = $this->db->select('*')
                ->from('soal_kuisioner_pelayanan as skp')
                ->join('soal_kuisioner as sk','sk.soal_kuisioner_id=skp.soal_kuisioner_id')
                ->join('bagian as bag','bag.id_bagian=skp.id_bagian')
                ->where('bag.id_bagian',$row->id_bagian)
                ->get()->result_object();
            $i++;
        }

        return $data;
    }

    function get_matakuliah($kelas_mahasiswa_id)
    {
        return $this->db->select('*')
        ->from('kelas')
        ->join('matakuliah as mak','kelas.id_matakuliah=mak.id_matakuliah')
        ->join('kelas_mahasiswa as km','km.kelas_id=kelas.kelas_id')
        ->where('kelas_mahasiswa_id', $kelas_mahasiswa_id)
        ->get()->row_object();
    }

    function get_dosen($kelas_id)
    {
        return $this->db->select('*')
        ->from('kelas')
//        ->join('mengajar', 'kelas.kode_matakuliah=mengajar.kode_matakuliah and kelas.nama_kelas_id=mengajar.nama_kelas_id')
        ->join('mengajar', 'kelas.kelas_id=mengajar.kelas_id')
        ->join('dosen', 'mengajar.kode_dosen=dosen.kode_dosen')
        ->where('kelas.kelas_id', $kelas_id)
        ->get()->result();
    }

    function get_dosen_mengajar($kelas_mahasiswa_id)
    {
        return $this->db->select('*')
            ->from('kelas')
            ->join('kelas_mahasiswa as km', 'kelas.kelas_id=km.kelas_id')
            ->join('mengajar', 'kelas.kelas_id=mengajar.kelas_id')
            ->join('dosen', 'mengajar.kode_dosen=dosen.kode_dosen')
            ->where('km.kelas_mahasiswa_id', $kelas_mahasiswa_id)
            ->get()->result();
    }

    function simpan($data)
    {
        return $this->db->insert('kuisioner', $data);
    }

    function simpan_layanan($data)
    {
        return $this->db->insert('kuisioner_layanan', $data);
    }

    function layanan_axis($nim)
    {
        $kode_tahun_akademik = tahun_akademik()->kode_tahun_akademik;
        $cek = $this->db->get_where('kuisioner_layanan', array('kode_tahun_akademik'=>$kode_tahun_akademik,'nim'=> $nim))->result_object();
        if (count($cek) > 0)
        {
            return true;
        }else{
            return false;
        }
    }

    function cek_kuisioner_belum_diisi($nim, $kode_tahun_akademik)
    {
        $query = $this->db->select('km.kelas_mahasiswa_id')
            ->from('kelas')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kelas.id_matakuliah')
            ->join('kelas_mahasiswa as km', 'km.kelas_id=kelas.kelas_id')
            ->join('krs_detail as kd', 'km.kode_krs_detail=kd.kode_krs_detail')
            ->join('krs', 'krs.kode_krs=kd.kode_krs')
            ->join('kuisioner as kuis', 'km.kelas_mahasiswa_id=kuis.kelas_mahasiswa_id', 'LEFT')
            ->where('kuisioner_id', null)
            ->where('krs.nim', $nim)
            ->where('kelas.kode_tahun_akademik', $kode_tahun_akademik)
            ->get()->num_rows();

        return $query;
    }

    function get_setting()
    {
        $query = $this->db->where('setting_id', '1')->get('setting_kuisioner')->row_object();
        return $query && isset($query->aktif_kuisioner) ? $query->aktif_kuisioner : '';
    }

    function update_setting($data)
    {
        return $this->db->where('setting_id','1')->update('setting_kuisioner', $data);
    }

    function get_hasil_kuisioner($kode_tahun_akademik, $id_matakuliah, $kelas_id)
    {
        $matakuliah = $this->db->select('*')
        ->from('kelas')
        ->join('matakuliah as mak','kelas.id_matakuliah=mak.id_matakuliah')
        ->where('kelas.id_matakuliah', $id_matakuliah)
        ->get()->row_object();
        if ($matakuliah->sks_praktikum == 0)
        {
            $data['soal_kuisioner'] = $this->db->select('kategori, count(soal_kuisioner.kategori_id) as colspan')
            ->from('soal_kuisioner')
            ->join('kategori_kuisioner as kk', 'kk.kategori_id=soal_kuisioner.kategori_id')
            ->where('jenis', 'T')
            ->group_by('soal_kuisioner.kategori_id')
            ->get()->result();

            $data['jumlah_soal'] = $this->db->select('*')
                ->from('soal_kuisioner')
                ->join('kategori_kuisioner as kk', 'kk.kategori_id=soal_kuisioner.kategori_id')
                ->where('jenis', 'T')
                ->get()->result();
        }else{
//            Soal kuisioner Praktikum
            $data['soal_kuisioner']['P'] = $this->db->select('kategori, count(soal_kuisioner.kategori_id) as colspan')
                ->from('soal_kuisioner')
                ->join('kategori_kuisioner as kk', 'kk.kategori_id=soal_kuisioner.kategori_id')
                ->where('jenis', 'P')
                ->group_by('soal_kuisioner.kategori_id')
                ->get()->result();

            $data['jumlah_soal']['P'] = $this->db->select('*')
                ->from('soal_kuisioner')
                ->join('kategori_kuisioner as kk', 'kk.kategori_id=soal_kuisioner.kategori_id')
                ->where('jenis', 'P')
                ->get()->result();
//            Soal kuisioner Teori
            $data['soal_kuisioner']['T'] = $this->db->select('kategori, count(soal_kuisioner.kategori_id) as colspan')
                ->from('soal_kuisioner')
                ->join('kategori_kuisioner as kk', 'kk.kategori_id=soal_kuisioner.kategori_id')
                ->where('jenis', 'T')
                ->group_by('soal_kuisioner.kategori_id')
                ->get()->result();

            $data['jumlah_soal']['T'] = $this->db->select('*')
                ->from('soal_kuisioner')
                ->join('kategori_kuisioner as kk', 'kk.kategori_id=soal_kuisioner.kategori_id')
                ->where('jenis', 'T')
                ->get()->result();

        }

        $kelas_mahasiswa_id =  $this->db->select('*')
        ->from('kuisioner as kuis')
        ->join('kelas_mahasiswa as km', 'kuis.kelas_mahasiswa_id=km.kelas_mahasiswa_id')
        ->join('kelas', 'km.kelas_id=kelas.kelas_id')
        ->where('kelas.kode_tahun_akademik', $kode_tahun_akademik)
        ->where('kelas.id_matakuliah', $id_matakuliah)
        ->where('kelas.kelas_id', $kelas_id)
        ->get()->result();

        if ($matakuliah->sks_praktikum == 0)
        {
            foreach ($kelas_mahasiswa_id as $row)
            {
                $data['hasil'][$row->kelas_mahasiswa_id] = $this->db->select('*')
                    ->from('kuisioner as kuis')
                    ->join('kelas_mahasiswa as km', 'kuis.kelas_mahasiswa_id=km.kelas_mahasiswa_id')
                    ->join('kelas', 'kelas.kelas_id=km.kelas_id')
                    ->join('soal_kuisioner as sk', 'sk.soal_kuisioner_id=kuis.soal_kuisioner_id')
                    ->join('kategori_kuisioner as kk', 'kk.kategori_id=sk.kategori_id')
                    ->where('km.kelas_mahasiswa_id', $row->kelas_mahasiswa_id)
                    ->where('jenis', 'T')
                    ->get()->result();
            }
        }else{
            foreach ($kelas_mahasiswa_id as $row)
            {
                $data['hasil']['T'][$row->kelas_mahasiswa_id] = $this->db->select('*')
                    ->from('kuisioner as kuis')
                    ->join('kelas_mahasiswa as km', 'kuis.kelas_mahasiswa_id=km.kelas_mahasiswa_id')
                    ->join('kelas', 'kelas.kelas_id=km.kelas_id')
                    ->join('soal_kuisioner as sk', 'sk.soal_kuisioner_id=kuis.soal_kuisioner_id')
                    ->join('kategori_kuisioner as kk', 'kk.kategori_id=sk.kategori_id')
                    ->where('km.kelas_mahasiswa_id', $row->kelas_mahasiswa_id)
                    ->where('jenis', 'T')
                    ->get()->result();

                $data['hasil']['P'][$row->kelas_mahasiswa_id] = $this->db->select('*')
                    ->from('kuisioner as kuis')
                    ->join('kelas_mahasiswa as km', 'kuis.kelas_mahasiswa_id=km.kelas_mahasiswa_id')
                    ->join('kelas', 'kelas.kelas_id=km.kelas_id')
                    ->join('soal_kuisioner as sk', 'sk.soal_kuisioner_id=kuis.soal_kuisioner_id')
                    ->join('kategori_kuisioner as kk', 'kk.kategori_id=sk.kategori_id')
                    ->where('km.kelas_mahasiswa_id', $row->kelas_mahasiswa_id)
                    ->where('jenis', 'P')
                    ->get()->result();
            }
        }

        return $data;
    }

    function get_matakuliah_dan_dosen($kode_tahun_akademik, $id_matakuliah, $kelas_id)
    {
        $data['nama_matakuliah'] = $this->db->select('*')
        ->from('matakuliah')
        ->where('id_matakuliah', $id_matakuliah)
        ->get()->row_object();

        $data['dosen'] = $this->db->select('*')
        ->from('mengajar')
        ->join('kelas', 'kelas.kelas_id=mengajar.kelas_id')
        ->join('dosen', 'dosen.kode_dosen=mengajar.kode_dosen')
//        ->where('kode_tahun_akademik', $kode_tahun_akademik)
//        ->where('kode_matakuliah', $kode_matakuliah)
        ->where('kelas.kelas_id', $kelas_id)
        ->get()->result();

        return $data;

    }
  
   	function get_kelas_dosen($kelas_id)
    {
        return $this->db->select('dosen.nama_dosen')
            ->from('mengajar')
            ->join('kelas', 'kelas.kelas_id=mengajar.kelas_id')
            ->join('dosen', 'dosen.kode_dosen=mengajar.kode_dosen')
            ->where('kelas.kelas_id', $kelas_id)
            ->get()->result();
    }
}