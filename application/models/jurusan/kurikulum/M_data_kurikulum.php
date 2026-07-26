<?php
defined('BASEPATH') or exit('No direct script access allowed');

class m_data_kurikulum extends CI_Model
{

    public $table = "kurikulum";

    public function __construct()
    {
        parent::__construct();
    }

    public function get()
    {
        return $this->db->query("SELECT * from kurikulum, nama_kurikulum, matakuliah WHERE kurikulum.kode_nama_kurikulum=nama_kurikulum.kode_nama_kurikulum and kurikulum.id_matakuliah=matakuliah.id_matakuliah")->result_object();
    }

    public function get_byid($kode_nama_kurikulum)
    {
        $query = $this->db->query("SELECT * from kurikulum, nama_kurikulum, matakuliah WHERE kurikulum.kode_nama_kurikulum=nama_kurikulum.kode_nama_kurikulum and kurikulum.id_matakuliah=matakuliah.id_matakuliah and kurikulum.kode_nama_kurikulum=?", array($kode_nama_kurikulum))->result_object();
        return $query;
    }

    public function get_data_kurikulum($kode_nama_kurikulum)
    {
        $i = 1;
        for ($i = 1; $i <= 8; $i++) {
            $data[$i]['semester'] = $i;
            $data[$i]['data'] = $this->db->select('*')
                ->from('kurikulum as kur')
                ->join('nama_kurikulum as nakur', 'kur.kode_nama_kurikulum=nakur.kode_nama_kurikulum')
                ->join('matakuliah as mak', 'mak.id_matakuliah=kur.id_matakuliah')
                ->where('nakur.kode_nama_kurikulum', $kode_nama_kurikulum)
                ->where('semester', $i)
                ->order_by('substr(mak.kode_matakuliah,-2,2) ASC')
                ->get()->result();
            //			$data[$i]['data']= $this->db->query("SELECT * from kurikulum, nama_kurikulum, matakuliah WHERE kurikulum.kode_nama_kurikulum=nama_kurikulum.kode_nama_kurikulum and kurikulum.kode_matakuliah=matakuliah.kode_matakuliah and kurikulum.kode_nama_kurikulum={$kode_nama_kurikulum} and semester={$i}")->result_object();
        }

        return $data;
    }

    //    public function get_krs_matakuliah_wajib($kode_nama_kurikulum, $semester, $sem=null)
    public function get_krs_matakuliah_wajib($kode_nama_kurikulum, $semester, $sem = null, $status_pendaftaran = null)
    {

        $cek_paket = $this->db->select('*,ps.kode_program_studi')
            ->from('nama_kurikulum as nk')
            ->join('kurikulum_angkatan as ka', 'ka.kode_nama_kurikulum=nk.kode_nama_kurikulum')
            ->join('program_studi as ps', 'nk.kode_program_studi=ps.kode_program_studi')
            ->where('nk.kode_nama_kurikulum', $kode_nama_kurikulum)
            ->get()->row_object();
        $i = 1;
        //        $kompetensi = $this->db->select("GROUP_CONCAT(matakuliah_pilihan) as matakuliah_pilihan")
//                ->from('kompetensi')
//                ->where('kode_program_studi', $cek_paket->kode_program_studi)
//                ->group_by('kode_program_studi')
//                ->get()->row_object();
        $kompetensi = $this->db->select('makom.id_matakuliah as id_matakuliah')
            ->from('kompetensi as kom')
            ->join('matakuliah_kompetensi as makom', 'makom.kode_kompetensi=kom.kode_kompetensi')
            ->where('kom.kode_program_studi', $cek_paket->kode_program_studi)
            ->get()->result_array();

        if (count($kompetensi) > 0) {
            $mk_pilihan = array_column($kompetensi, 'id_matakuliah');
        } else {
            $mk_pilihan = array('aa', 'aa');
        }

        if ($cek_paket->paket == 'Y' && $status_pendaftaran == 'B' && $sem < 7) {
            $data[0]['semester'] = $sem;
            $data[0]['data'] = $this->db->select('*')
                ->from('kurikulum as k')
                ->join('nama_kurikulum as nk', 'k.kode_nama_kurikulum=nk.kode_nama_kurikulum')
                ->join('matakuliah as mk', 'k.id_matakuliah=mk.id_matakuliah')
                ->where(array('k.kode_nama_kurikulum' => $kode_nama_kurikulum, 'semester' => $sem))
                //                    ->where_not_in('substr(mk.kode_matakuliah,7,2)', $mk_pilihan)
                ->where_not_in('mk.id_matakuliah', $mk_pilihan)
                ->get()->result_array();
        } else {
            $mak_skripsi = get_kode_matakuliah_skripsi();
            $mak_kkp = get_kode_matakuliah_kkp();
            $mak_skripsi_smt = $this->db->select('*')
                ->from('kurikulum as k')
                ->join('nama_kurikulum as nk', 'k.kode_nama_kurikulum=nk.kode_nama_kurikulum')
                ->join('matakuliah as mak', 'k.id_matakuliah=mak.id_matakuliah')
                ->where('k.kode_nama_kurikulum', $kode_nama_kurikulum)
                ->where_in('mak.kode_matakuliah', $mak_skripsi)
                ->limit(1)
                ->get()->row_object();
            $mak_kkp_smt = $this->db->select('*')
                ->from('kurikulum as k')
                ->join('nama_kurikulum as nk', 'k.kode_nama_kurikulum=nk.kode_nama_kurikulum')
                ->join('matakuliah as mak', 'k.id_matakuliah=mak.id_matakuliah')
                ->where('k.kode_nama_kurikulum', $kode_nama_kurikulum)
                ->where_in('mak.kode_matakuliah', $mak_kkp)
                ->limit(1)
                ->get()->row_object();
            
            if ($cek_paket->id_jenjang == '1') {
                $lop = 8;
            } else {
                $lop = 6;
            }
            for ($i = 1; $i <= $lop; $i++) {
                if ($i % 2 == $semester) {
                    $data[$i]['semester'] = $i;
                    if ($mak_skripsi_smt && $i < $mak_skripsi_smt->semester) {
                        // sementara
                        if ($cek_paket->kode_program_studi == '4' && $i == 7) {
                            $query1 = $this->db->select('*')
                                ->from('kurikulum as k')
                                ->join('nama_kurikulum as nk', 'k.kode_nama_kurikulum=nk.kode_nama_kurikulum')
                                ->join('matakuliah as mk', 'k.id_matakuliah=mk.id_matakuliah')
                                ->where(array('k.kode_nama_kurikulum' => $kode_nama_kurikulum, 'semester' => $i))
                                //                                    ->where_not_in('substr(mk.kode_matakuliah,7,2)', $mk_pilihan)
                                ->where_not_in('mk.id_matakuliah', $mk_pilihan)
                                ->where_not_in('mk.kode_matakuliah', $mak_kkp)
                                ->where_not_in('mk.kode_matakuliah', $mak_skripsi)->get_compiled_select();

                            $query2 = $this->db->select('*')
                                ->from('kurikulum as k')
                                ->join('nama_kurikulum as nk', 'k.kode_nama_kurikulum=nk.kode_nama_kurikulum')
                                ->join('matakuliah as mk', 'k.id_matakuliah=mk.id_matakuliah')
                                ->where('k.kode_nama_kurikulum', $kode_nama_kurikulum)
                                ->where_in('mk.kode_matakuliah', $mak_skripsi)
                                ->get_compiled_select();

                            $data[$i]['data'] = $this->db->query($query1 . ' UNION ALL ' . $query2)->result_array();
                        } else {
                            if ($i == 7 && $cek_paket->kode_program_studi == '12') {
                                $query1 = $this->db->select('*')
                                    ->from('kurikulum as k')
                                    ->join('nama_kurikulum as nk', 'k.kode_nama_kurikulum=nk.kode_nama_kurikulum')
                                    ->join('matakuliah as mk', 'k.id_matakuliah=mk.id_matakuliah')
                                    ->where(array('k.kode_nama_kurikulum' => $kode_nama_kurikulum, 'semester' => $i))
                                    ->where_not_in('mk.id_matakuliah', $mk_pilihan)
                                    ->where_not_in('mk.kode_matakuliah', $mak_skripsi)
                                    ->where_not_in('mk.kode_matakuliah', $mak_kkp)
                                    ->get_compiled_select();

                                    $query2 = $this->db->select('*')
                                        ->from('kurikulum as k')
                                        ->join('nama_kurikulum as nk', 'k.kode_nama_kurikulum=nk.kode_nama_kurikulum')
                                        ->join('matakuliah as mk', 'k.id_matakuliah=mk.id_matakuliah')
                                        ->where(array('k.kode_nama_kurikulum' => $kode_nama_kurikulum))
                                        ->where_in('mk.kode_matakuliah', $mak_skripsi)
                                        ->get_compiled_select();

                                    $data[$i]['data'] = $this->db->query($query1 . ' UNION ALL ' . $query2)->result_array();
                                
                            }else{
                                //							end.sementara
                                $data[$i]['data'] = $this->db->select('*')
                                    ->from('kurikulum as k')
                                    ->join('nama_kurikulum as nk', 'k.kode_nama_kurikulum=nk.kode_nama_kurikulum')
                                    ->join('matakuliah as mk', 'k.id_matakuliah=mk.id_matakuliah')
                                    ->where(array('k.kode_nama_kurikulum' => $kode_nama_kurikulum, 'semester' => $i))
                                    ->where_not_in('mk.id_matakuliah', $mk_pilihan)
                                    ->where_not_in('mk.kode_matakuliah', $mak_skripsi)
                                    ->where_not_in('mk.kode_matakuliah', $mak_kkp)
                                    ->get()->result_array();
                            }   
                        }
                    } else {
                        $query1 = $this->db->select('*')
                            ->from('kurikulum as k')
                            ->join('nama_kurikulum as nk', 'k.kode_nama_kurikulum=nk.kode_nama_kurikulum')
                            ->join('matakuliah as mk', 'k.id_matakuliah=mk.id_matakuliah')
                            ->where(array('k.kode_nama_kurikulum' => $kode_nama_kurikulum, 'semester' => $i))
                            //                                ->where_not_in('substr(mk.kode_matakuliah,7,2)', $mk_pilihan)
                            ->where_not_in('mk.id_matakuliah', $mk_pilihan)
                            ->where_not_in('mk.kode_matakuliah', $mak_kkp)
                            ->where_not_in('mk.kode_matakuliah', $mak_skripsi)->get_compiled_select();

                        $query2 = $this->db->select('*')
                            ->from('kurikulum as k')
                            ->join('nama_kurikulum as nk', 'k.kode_nama_kurikulum=nk.kode_nama_kurikulum')
                            ->join('matakuliah as mk', 'k.id_matakuliah=mk.id_matakuliah')
                            ->where('k.kode_nama_kurikulum', $kode_nama_kurikulum)
                            ->where_in('mk.kode_matakuliah', $mak_skripsi)
                            ->get_compiled_select();

                        $data[$i]['data'] = $this->db->query($query1 . ' UNION ALL ' . $query2)->result_array();
                    }
                }
            }
            for ($i = 1; $i <= $lop; $i++) {
                if ($i % 2 == $semester) {
                    $data[$i]['semester'] = $i;
                    if ($mak_kkp_smt && $i >= $mak_kkp_smt->semester) {
                        $tmp = $this->db->select('*')
                                ->from('kurikulum as k')
                                ->join('nama_kurikulum as nk', 'k.kode_nama_kurikulum=nk.kode_nama_kurikulum')
                                ->join('matakuliah as mk', 'k.id_matakuliah=mk.id_matakuliah')
                                ->where('k.kode_nama_kurikulum', $kode_nama_kurikulum)
                                ->where_in('mk.kode_matakuliah', $mak_kkp)
                                ->get()->row_array();
                        $max = count($data[$i]['data']);
                        $data[$i]['data'][$max] = $tmp;
                        // echo json_encode($max);
                        break;
                    }
                }
            }
          	if($cek_paket->kode_program_studi == "18"){
                for ($i = 1; $i <= $lop; $i++) {
                    $datax[$i]['semester'] = $i;
                    $tmp = $this->db->select('*')
                            ->from('kurikulum as k')
                            ->join('nama_kurikulum as nk', 'k.kode_nama_kurikulum=nk.kode_nama_kurikulum')
                            ->join('matakuliah as mk', 'k.id_matakuliah=mk.id_matakuliah')
                            ->where('k.kode_nama_kurikulum', $kode_nama_kurikulum)
                            ->where("semester",$i)
                            ->get()->result_array();
                    $datax[$i]['data'] = $tmp;
                }
                return $datax;
            }
        }
        return $data;
    }

    public function get_data_kurikulum_krs_pilihan($kode_nama_kurikulum, $semester, $kode_kompetensi = null, $kode_mk_pilihan = null)
    {
      	$mk_hidden = array_column(mk_hidden(), 'id_matakuliah');
        if ($kode_kompetensi == null) {

            //        $cek_ekstensi = $this->db->select('*')
//                ->from('nama_kurikulum as nk')
//                ->join('kurikulum_angkatan as ka', 'ka.kode_nama_kurikulum=nk.kode_nama_kurikulum')
//                ->where('nk.kode_nama_kurikulum', $kode_nama_kurikulum)
//                ->get()->row_object();
            $cek_ekstensi = $this->db->select('*')
                ->from('nama_kurikulum as nk')
                ->join('kurikulum_angkatan as ka', 'ka.kode_nama_kurikulum=nk.kode_nama_kurikulum')
                ->where('nk.kode_nama_kurikulum', $kode_nama_kurikulum)
                ->where('ka.ekstensi', 'Y')
                ->get()->row_object();
            //        echo "<pre>";
//        print_r($cek_ekstensi);
//        die();
//        LAMA SEKALI
//        $kompetensi = $this->db->select("GROUP_CONCAT(matakuliah_pilihan) as matakuliah_pilihan")
//			->from('kompetensi')
//			->where('kode_program_studi', $cek_ekstensi->kode_program_studi)
//			->group_by('kode_program_studi')
//			->get()->row_object();
//        END LAMA SEKALI

            //        $kompetensi = $this->db->select('makom.id_matakuliah as id_matakuliah')
//                ->from('kompetensi as kom')
//                ->join('matakuliah_kompetensi as makom', 'makom.kode_kompetensi=kom.kode_kompetensi')
//                ->where('kom.kode_program_studi', $cek_ekstensi->kode_program_studi)
//                ->get()->result_array();

            //        if ($cek_ekstensi->ekstensi == 'Y') {
            if ($cek_ekstensi) {
                $kompetensi = $this->db->select('makom.id_matakuliah as id_matakuliah')
                    ->from('kompetensi as kom')
                    ->join('matakuliah_kompetensi as makom', 'makom.kode_kompetensi=kom.kode_kompetensi')
                    ->where('kom.kode_program_studi', $cek_ekstensi->kode_program_studi)
                  	->where_not_in('makom.id_matakuliah',$mk_hidden)
                    ->get()->result_array();

                if (count($kompetensi) > 0) {
                    //                $mk_pilihan = explode(',',$kompetensi->matakuliah_pilihan);
//                $mk_pilihan = array_unique($mk_pilihan);
                    $mk_pilihan = array_column($kompetensi, 'id_matakuliah');

                } else {
                    $mk_pilihan = array('aa', 'aa');
                }
            } else {
                //            $mk_pilihan = explode(",",$kode_mk_pilihan);
                $matakuliah = $this->db->select('id_matakuliah')
                    ->from('matakuliah_kompetensi as makom')
                    ->where('kode_kompetensi', $kode_kompetensi)
                    ->get()->result_array();
                if (count($matakuliah) > 0) {
                    $mk_pilihan = array_column($matakuliah, 'id_matakuliah');
                } else {
                    $mk_pilihan = array('aa', 'aa');
                }
            }
        } else {
            $matakuliah = $this->db->select('id_matakuliah')
                ->from('matakuliah_kompetensi as makom')
                ->where('kode_kompetensi', $kode_kompetensi)
              	->where_not_in('makom.id_matakuliah',$mk_hidden)
                ->get()->result_array();
            if (count($matakuliah) > 0) {
                $mk_pilihan = array_column($matakuliah, 'id_matakuliah');
            } else {
                $mk_pilihan = array('aa', 'aa');
            }
        }

        $i = 1;
        for ($i = 1; $i <= 8; $i++) {
            if ($i % 2 == $semester) {
                $data[$i]['semester'] = $i;
                //                if ($kode_kompetensi == 2)
//				{
//                    $query1 = $this->db->select('*')
//                        ->from('kurikulum as k')
//                        ->join('nama_kurikulum as nk', 'k.kode_nama_kurikulum=nk.kode_nama_kurikulum')
//                        ->join('matakuliah as mk', 'k.id_matakuliah=mk.id_matakuliah')
//                        ->where(array('k.kode_nama_kurikulum'=> $kode_nama_kurikulum, 'semester'=>$i))
//                        ->where_in('substr(mk.kode_matakuliah,7,2)',$mk_pilihan)
//                        ->where('substr(mk.kode_matakuliah,6,1) > 4')
//                        ->get()->result_array();
//                    $query2 = $this->db->select('*')
//                        ->from('kurikulum as k')
//                        ->join('nama_kurikulum as nk', 'k.kode_nama_kurikulum=nk.kode_nama_kurikulum')
//                        ->join('matakuliah as mk', 'k.id_matakuliah=mk.id_matakuliah')
//                        ->where(array('k.kode_nama_kurikulum'=> $kode_nama_kurikulum, 'semester'=>$i))
//                        ->where_in('mk.kode_matakuliah',array('TSKB271476','TSKB171477'))
//                        ->where('substr(mk.kode_matakuliah,6,1) > 4')
//                        ->get()->result_array();
//                    $data[$i]['data']  = array_merge($query1,$query2);
//				}else{
//                    $query1 = $this->db->select('*')
//                        ->from('kurikulum as k')
//                        ->join('nama_kurikulum as nk', 'k.kode_nama_kurikulum=nk.kode_nama_kurikulum')
//                        ->join('matakuliah as mk', 'k.id_matakuliah=mk.id_matakuliah')
//                        ->where(array('k.kode_nama_kurikulum'=> $kode_nama_kurikulum, 'semester'=>$i))
//                        ->where_in('substr(mk.kode_matakuliah,7,2)',$mk_pilihan)
//                        ->where('substr(mk.kode_matakuliah,6,1) > 4')
//                        ->get()->result_array();
//                    	$data[$i]['data']  = $query1;
//                }

                //                $data[$i]['data']= $this->db->select('*')
//                        ->from('kurikulum as k')
//                        ->join('nama_kurikulum as nk', 'k.kode_nama_kurikulum=nk.kode_nama_kurikulum')
//                        ->join('matakuliah as mk', 'k.id_matakuliah=mk.id_matakuliah')
//                        ->where(array('k.kode_nama_kurikulum'=> $kode_nama_kurikulum, 'semester'=>$i))
//                        ->where_in('substr(mk.kode_matakuliah,7,2)',$mk_pilihan)
//                        ->where('substr(mk.kode_matakuliah,6,1) > 4')
//                        ->get()->result_array();

                //                New Model matakuliah pilihan
                $data[$i]['data'] = $this->db->select('*')
                    ->from('kurikulum as k')
                    ->join('nama_kurikulum as nk', 'k.kode_nama_kurikulum=nk.kode_nama_kurikulum')
                    ->join('matakuliah as mk', 'k.id_matakuliah=mk.id_matakuliah')
                    //						->join('matakuliah_kompetensi as makom','makom.id_matakuliah=mk.id_matakuliah')
//						->where(array('k.kode_nama_kurikulum'=> $kode_nama_kurikulum, 'semester'=>$i,'makom.kode_kompetensi'=>$kode_kompetensi))
                    ->where(array('k.kode_nama_kurikulum' => $kode_nama_kurikulum, 'semester' => $i))
                    ->where_in('mk.id_matakuliah', $mk_pilihan)
                  	->where_not_in('mk.id_matakuliah',$mk_hidden)
                    ->get()->result_array();
                //				end new model matakuliah pilihan
            }
        }

        return $data;
    }

    public function get_kode_nama_kurikulum($nim)
    {
        //		$kode_jurusan = substr($nim, 2,2);
//		$kode_jenjang = substr($nim, 4,1);
//		$angkatan = substr($nim, 0,2);
//		$prodi = $this->db->query("SELECT kode_program_studi, kode_jurusan, kode_jenjang from (select kode_program_studi, kode_jurusan, kode_jenjang from program_studi, jurusan, jenjang where program_studi.id_jurusan=jurusan.id_jurusan and program_studi.id_jenjang=jenjang.id_jenjang) as mahas where kode_jurusan='{$kode_jurusan}' and kode_jenjang='{$kode_jenjang}'")->row_object();
//
//		$query = $this->db->query(" SELECT * from (SELECT nama_kurikulum.kode_nama_kurikulum, mid(angkatan,3,2) as angkatan, kode_program_studi from nama_kurikulum) as mah where angkatan='{$angkatan}' and kode_program_studi='{$prodi->kode_program_studi}' ")->row_object();

        //		$query = $this->get_nama_kurikulum($nim);
        $query = nama_kurikulum($nim);
        return $query->kode_nama_kurikulum;
    }

    public function get_nama_kurikulum($nim)
    {
        //        $kode_jurusan = substr($nim, 2,2);
//        $kode_jenjang = substr($nim, 4,1);
//        $gelombang = substr($nim, 5,1);
//        $angkatan = substr($nim, 0,2);
////        $prodi = $this->db->query("SELECT kode_program_studi, kode_jurusan, kode_jenjang from (select kode_program_studi, kode_jurusan, kode_jenjang from program_studi, jurusan, jenjang where program_studi.id_jurusan=jurusan.id_jurusan and program_studi.id_jenjang=jenjang.id_jenjang) as mahas where kode_jurusan='{$kode_jurusan}' and kode_jenjang='{$kode_jenjang}'")->row_object();
//		$prodi = get_kode_prodi($nim);
//        if ($gelombang == '5')
//		{
//        	$query = $this->db->query(" SELECT * from (SELECT ekstensi, paket, nama_kurikulum.kode_nama_kurikulum, mid(angkatan,3,2) as angkatan, kode_program_studi from nama_kurikulum) as mah where angkatan='{$angkatan}' and kode_program_studi='{$prodi->kode_program_studi}' and ekstensi='Y' ")->row_object();
//		}else{
//            $query = $this->db->query(" SELECT * from (SELECT ekstensi, paket, nama_kurikulum.kode_nama_kurikulum, mid(angkatan,3,2) as angkatan, kode_program_studi from nama_kurikulum) as mah where angkatan='{$angkatan}' and kode_program_studi='{$prodi->kode_program_studi}' and ekstensi='N' ")->row_object();
//        }
        $query = nama_kurikulum($nim);

        return $query;
    }

    public function get_matakuliah_bynim($nim)
    {
        $query = $this->get_nama_kurikulum($nim);
        //        $kode_jurusan = substr($nim, 2,2);
//        $kode_jenjang = substr($nim, 4,1);
//        $angkatan = substr($nim, 0,2);
//        $prodi = $this->db->query("SELECT kode_program_studi, kode_jurusan, kode_jenjang from (select kode_program_studi, kode_jurusan, kode_jenjang from program_studi, jurusan, jenjang where program_studi.id_jurusan=jurusan.id_jurusan and program_studi.id_jenjang=jenjang.id_jenjang) as mahas where kode_jurusan='{$kode_jurusan}' and kode_jenjang='{$kode_jenjang}'")->row_object();
//
//        $query = $this->db->query(" SELECT * from (SELECT nama_kurikulum.kode_nama_kurikulum, mid(angkatan,3,2) as angkatan, kode_program_studi from nama_kurikulum) as mah where angkatan='{$angkatan}' and kode_program_studi='{$prodi->kode_program_studi}' ")->row_object();

        $kode_nama_kurikulum = $query->kode_nama_kurikulum;

        return $this->db->query("SELECT * from kurikulum, matakuliah WHERE kurikulum.id_matakuliah=matakuliah.id_matakuliah and kurikulum.kode_nama_kurikulum=? order by matakuliah.kode_matakuliah ASC ", array($kode_nama_kurikulum))->result();
    }

    // menampilkan matakuliah sesuai semester ganjil atau genap
    public function get_matakuliah_by_nim_semester($nim, $semester)
    {
        $query = $this->get_nama_kurikulum($nim);
        $kode_nama_kurikulum = $query->kode_nama_kurikulum;
        if ($semester == 1) {
            $semester = [1, 3, 5, 7];
        } else {
            $semester = [2, 4, 6, 8];
        }
        $sql = "SELECT * from kurikulum, matakuliah
                            WHERE kurikulum.id_matakuliah=matakuliah.id_matakuliah 
                              and kurikulum.kode_nama_kurikulum= ? 
                              and kurikulum.semester in ?  
                            order by matakuliah.kode_matakuliah ASC";
        return $this->db->query($sql, array($kode_nama_kurikulum, $semester))->result();
    }

    public function simpan($data)
    {
        # code...
        return $this->db->insert($this->table, $data);
    }

    public function ubah($data, $id)
    {
        # code...
        return $this->db->where('kode_kurikulum', $id)->update($this->table, $data);
    }

    public function hapus($id)
    {
        # code...
        return $this->db->where('kode_kurikulum', $id)->delete($this->table);
    }

    public function get_matakuliah_awal($kode_nama_kurikulum)
    {
        $query = $this->db->select('m.id_matakuliah,m.kode_matakuliah, nama_matakuliah, sks_teori, sks_praktek, sks_praktikum')
            ->from('kurikulum as kur')
            ->join('matakuliah as m', 'm.id_matakuliah=kur.id_matakuliah')
            ->where('kur.kode_nama_kurikulum', $kode_nama_kurikulum)
            ->where('kur.semester', '1')
            ->get()->result_object();

        return $query;
    }

    public function get_jml_makul_wajib($kode_nama_kurikulum)
    {
        $semester = array('1', '2', '3', '4');
        $query = $this->db->select('*')
            ->from('nama_kurikulum nk')
            ->join('kurikulum as kur', 'kur.kode_nama_kurikulum=nk.kode_nama_kurikulum')
            ->join('matakuliah as mak', 'mak.id_matakuliah=kur.id_matakuliah')
            ->where('kur.kode_nama_kurikulum', $kode_nama_kurikulum)
            ->where_in('kur.semester', $semester)
            ->get()->result();

        return count($query);
    }

}

?>