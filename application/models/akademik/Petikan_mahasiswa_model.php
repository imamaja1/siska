<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Petikan_mahasiswa_model extends CI_Model{

    public function petikan_nilai($nim, $kode_nama_kurikulum)
    {
        $j=1;
        $n=0;
        $t_sks = 0;
        $t_sksn = 0;

        for ($j=1; $j <= 8; $j++) {
            $data_kurikulum = $this->db->query("SELECT * FROM kurikulum, matakuliah WHERE kode_nama_kurikulum=? and kurikulum.kode_matakuliah=matakuliah.kode_matakuliah and semester=?", array($kode_nama_kurikulum, $j))->result();
            if (count($data_kurikulum) <= 0)
            {
                break;
            }
            $kode_krs = $this->db->get_where('krs', array('nim'=>$nim))->result();
            $data_penilaian = $this->sistem_penilaian($nim);
            $data[$n]['semester'] = $j;
            $i=0;
            foreach ($data_kurikulum as $row)
            {
//                $cek = $this->db->query(" SELECT *, count(krs_detail.kode_krs_detail) as jumlah FROM krs, krs_detail, khs_detail, matakuliah WHERE krs.kode_krs=krs_detail.kode_krs and krs_detail.kode_krs_detail=khs_detail.kode_krs_detail and krs_detail.kode_matakuliah='{$row->kode_matakuliah}' and krs.nim='{$nim}' and krs_detail.kode_matakuliah=matakuliah.kode_matakuliah")->row_object();
                $cek = $this->db->select('*,count(kd.kode_krs_detail) as jumlah')
                    ->from('krs as k')
                    ->join('krs_detail as kd', 'k.kode_krs=kd.kode_krs')
                    ->join('khs_detail as hd', 'kd.kode_krs_detail=hd.kode_krs_detail')
                    ->join('matakuliah as m', 'kd.kode_matakuliah=m.kode_matakuliah')
//                    ->where('hd.nilai_harian IS NOT NULL')
//                    ->where('hd.nilai_uts IS NOT NULL')
//                    ->where('hd.nilai_uas IS NOT NULL')
                    ->where('hd.nilai_akhir IS NOT NULL')
                    ->where('kd.kode_matakuliah', $row->kode_matakuliah)
                    ->where('k.nim', $nim)
                    ->get()->row_object();
//                $data[$n]['data_nilai'][$i]['jumlah_data'] = $cek->jumlah;
                $data[$n]['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah;
                $data[$n]['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah;
                $sks = $row->sks_teori+$row->sks_praktek+$row->sks_praktikum;
                if ($cek->jumlah == 1)
                {
                    $data[$n]['data_nilai'][$i]['sks'] = $sks;
//                    $nilai_akhir = ($cek->nilai_harian*20/100)+($cek->nilai_uts*30/100)+($cek->nilai_uas*50/100);
                    $nilai_akhir = $cek->nilai_akhir * 1;
                    foreach ($data_penilaian as $key) {
                        if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
                            $data[$n]['data_nilai'][$i]['grade'] = $key['grade'];
                            $data[$n]['data_nilai'][$i]['sksn'] = $key['bobot_nilai']*$sks;
                            $data[$n]['data_nilai'][$i]['sks'] = $sks;
                        }
                    }
                }elseif ($cek->jumlah > 1)
                {
//                    $lebih = $this->db->query(" SELECT * FROM krs, krs_detail, khs_detail, matakuliah WHERE krs.kode_krs=krs_detail.kode_krs and krs_detail.kode_krs_detail=khs_detail.kode_krs_detail and krs_detail.kode_matakuliah='{$row->kode_matakuliah}' and krs.nim='{$nim}' and krs_detail.kode_matakuliah=matakuliah.kode_matakuliah")->result();
                    $lebih = $this->db->select('*')
                        ->from('krs')
                        ->join('krs_detail as kd','krs.kode_krs=kd.kode_krs')
                        ->join('khs_detail as khd','kd.kode_krs_detail=khd.kode_krs_detail')
                        ->join('matakuliah as mak','kd.kode_matakuliah=mak.kode_matakuliah')
                        ->where('nim', $nim)
                        ->where('kd.kode_matakuliah', $row->kode_matakuliah)
                        ->order_by('nilai_akhir','DESC')
                        ->limit(1)
                        ->get()->row_object();

                        if (stup_grade($kode_nama_kurikulum, $lebih->semester))
                        {
                            $data_penilaian = stup_grade($kode_nama_kurikulum, $lebih->semester);
                        }else{
                            $data_penilaian = $this->sistem_penilaian($nim);
                        }

//                    foreach ($lebih as $item) {
                        $data[$n]['data_nilai'][$i]['sks'] = $sks;
//                        $nilai_akhir = ($item->nilai_harian*20/100)+($item->nilai_uts*30/100)+($item->nilai_uas*50/100);
                        $nilai_akhir = $lebih->nilai_akhir * 1;
//                        $k=0;
                        foreach ($data_penilaian as $key) {
                            if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
//                                $nilai['grade'][$k] = $key['grade'];
//                                $nilai['sksn'][$k] = $key['bobot_nilai']*$sks;
                                $data[$n]['data_nilai'][$i]['grade'] = $key['grade'];
                                $data[$n]['data_nilai'][$i]['sksn'] = $key['bobot_nilai']*$sks;
                                $data[$n]['data_nilai'][$i]['sks'] = $sks;
                            }
//                            $k++;
                        }
//                        $data[$n]['data_nilai'][$i]['grade'] = min($nilai['grade']);
//                        $data[$n]['data_nilai'][$i]['sksn'] = max($nilai['sksn']);
//                        $data[$n]['data_nilai'][$i]['sks'] = $sks;
//                    }
                }else{
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
        return $data;
    }

    public function mk_krs($nim)
    {
        $query = $this->db->where('nim', $nim)->get('krs')->result();

        $data = array();
        foreach ($query as $row) {
            $data[] = $this->get_kode_krs_detail($row->kode_krs)->kode_matakuliah;
        }

        return $data;

    }

    //Fungsi Pendukung
    public function get_kode_krs($nim)
    {
        $query = $this->db->where('nim', $nim)->get('krs')->result();

        $data = array();
        foreach ($query as $row) {
            $data[] = $this->get_kode_krs_detail($row->kode_krs);
        }

        return $data;

    }

    public function get_kode_krs_detail($kode_krs)
    {
//        $query = $this->db->query("SELECT *, krs.semester FROM krs, krs_detail, khs_detail, matakuliah WHERE krs_detail.kode_krs=krs.kode_krs and krs_detail.kode_krs_detail=khs_detail.kode_krs_detail and krs_detail.kode_matakuliah=matakuliah.kode_matakuliah and krs_detail.kode_krs=".$kode_krs."")->result();
        $query = $this->db->select('*, k.semester')
            ->form('krs as k')
            ->join('krs_detail as kd', 'k.kode_krs=kd.kode_krs')
            ->join('khs_detail as hd', 'kd.kode_krs_detail=hd.kode_krs_detail')
            ->join('matakuliah as m', 'kd.kode_matakuliah=m.kode_matakuliah')
            ->where('kd.kode_krs', $kode_krs)
            ->where_not_in('kd.status', 'K')
            ->get()->result();

        return $query;
    }

    public function sistem_penilaian($nim)
    {
        $angkatan = substr($nim, 0,2);
        $kode_jurusan = substr($nim, 2,2);
        $kode_jenjang = substr($nim, 4,1);

        $kode_program_studi = $this->get_kode_prodi($kode_jurusan, $kode_jenjang);

        $penilaian = $this->db->query("SELECT * FROM (SELECT distinct kode_sistem_penilaian_detail, mid(angkatan,-2) as angkatan, nama_kurikulum.kode_nama_kurikulum, nilai_minimum, nilai_maksimum, grade, bobot_nilai, kategori, keterangan, nama_kurikulum, kode_program_studi FROM nama_kurikulum, kurikulum, sistem_penilaian, sistem_penilaian_detail WHERE nama_kurikulum.kode_nama_kurikulum=kurikulum.kode_nama_kurikulum and nama_kurikulum.kode_nama_kurikulum=sistem_penilaian.kode_nama_kurikulum and sistem_penilaian.kode_sistem_penilaian=sistem_penilaian_detail.kode_sistem_penilaian) as mhs WHERE angkatan=? and kode_program_studi=?", array($angkatan, $kode_program_studi))->result_array();

        return $penilaian;
    }

    public function get_kode_prodi($kode_jurusan, $kode_jenjang)
    {
        $query = $this->db->query("SELECT kode_program_studi FROM program_studi, jurusan, jenjang WHERE program_studi.id_jenjang=jenjang.id_jenjang and program_studi.id_jurusan=jurusan.id_jurusan and kode_jenjang=? and kode_jurusan=?", array($kode_jenjang, $kode_jurusan))->row_object();
        return $query->kode_program_studi;
    }

}