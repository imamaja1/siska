<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Krs_kpat_model extends CI_Model {

	private $table = 'krs_detail';

	public function __construct()
	{
		parent::__construct();
		
	}

	public function filter($kode_tahun_akademik, $angkatan, $kode_program_studi)
	{

            if ($angkatan) {
			$query = $this->db->select('krs.kode_krs, kode_tahun_akademik, nama_mahasiswa, krs.nim')
                ->from('krs')
                ->join('krs_detail as kd','krs.kode_krs=kd.kode_krs')
                ->join('mahasiswa as mah','krs.nim=mah.nim')
                ->where('kd.status','K')
                ->where('mah.program_studi_kode', $kode_program_studi)
                ->where('substr(krs.nim,1,2)', $angkatan)
                ->where('kode_tahun_akademik', $kode_tahun_akademik)
                ->group_by('krs.nim')
                ->get()->result_object();
		}else{
			$query = $this->db->select('krs.kode_krs, kode_tahun_akademik, nama_mahasiswa, krs.nim')
                ->from('krs')
                ->join('krs_detail as kd','krs.kode_krs=kd.kode_krs')
                ->join('mahasiswa as mah','krs.nim=mah.nim')
                ->where('kd.status','K')
                ->where('mah.program_studi_kode', $kode_program_studi)
                // ->where('substr(krs.nim,1,2)', $angkatan)
                ->where('kode_tahun_akademik', $kode_tahun_akademik)
                ->group_by('krs.nim')
                ->get()->result_object();
		}


		return $query;
	}

	public function lihat_krs($kode_krs)
	{
		$query['data_mahasiswa'] = $this->db->query("SELECT krs.semester, mahasiswa.nim, tahun_akademik.kode_tahun_akademik, kode_program_studi, nama_mahasiswa, krs.kode_krs, tahun_akademik.tahun_akademik FROM krs, krs_detail, mahasiswa, matakuliah, tahun_akademik WHERE krs.kode_krs=krs_detail.kode_krs and krs.nim=mahasiswa.nim and krs_detail.id_matakuliah=matakuliah.id_matakuliah and krs.kode_tahun_akademik=tahun_akademik.kode_tahun_akademik and krs.kode_krs=?", array($kode_krs))->row_object();

		$query['data_matakuliah'] =  $this->db->query("SELECT * FROM krs, krs_detail,matakuliah WHERE krs.kode_krs=krs_detail.kode_krs and krs_detail.id_matakuliah=matakuliah.id_matakuliah and krs.kode_krs=? and krs_detail.status='K'", array($kode_krs))->result();

		return $query;
	}

	public function simpan_kpat($data, $id)
	{
		return $this->db->where('kode_krs_detail', $id)->update('krs_detail', $data);
	}

	public function autocomplate($keyword)
	{
		return $this->db->query("SELECT nim FROM krs WHERE nim like ? group by nim ORDER BY nim LIMIT 6", array($keyword . '%'))->result();
	}

	public function get_krs_sebelumnya($nim, $kode_tahun_akademik)
	{
		$data_krs = $this->get_kode_krs1($nim, $kode_tahun_akademik);
		$data_penilaian = sistem_penilaian($nim);
		$j=0;
		foreach ($data_krs['data'] as $key => $value) {
			$i=0;
			foreach ($value as $row) {
//				$khs[$j]['nim'] = $row->nim;
				$khs[$j]['nim'] = $data_krs['nim'];
				$khs[$j]['kurikulum'] = $data_penilaian[0]['nama_kurikulum'];
				$khs[$j]['semester'] = $row->semester;
				//$khs['nama_mahasiswa'] = $row->nama_mahasiswa;
				$khs[$j]['data_nilai'][$i]['kode_matakuliah'] = $row->kode_matakuliah; 
				$khs[$j]['data_nilai'][$i]['nama_matakuliah'] = $row->nama_matakuliah; 
				$khs[$j]['data_nilai'][$i]['id_matakuliah'] = $row->id_matakuliah;
//				$khs[$j]['data_nilai'][$i]['kode_krs_detail'] = $row->kode_krs_detail;
				$khs[$j]['data_nilai'][$i]['sks'] = $row->sks_teori+$row->sks_praktek+$row->sks_praktikum; 
				$khs[$j]['data_nilai'][$i]['sks_teori'] = $row->sks_teori; 
				$khs[$j]['data_nilai'][$i]['sks_praktek'] = $row->sks_praktek; 
				$khs[$j]['data_nilai'][$i]['sks_praktikum'] = $row->sks_praktikum; 
				$khs[$j]['data_nilai'][$i]['ket'] = $row->ket;
//				$nilai_akhir = ($row->nilai_harian*20/100)+($row->nilai_uts*30/100)+($row->nilai_uas*50/100);
                if ($row->nilai_akhir == null)
                {
                    $nilai_akhir = 0;
                }else{
                    $nilai_akhir = $row->nilai_akhir;
                }
				$khs[$j]['data_nilai'][$i]['nilai_akhir'] = $nilai_akhir;
					foreach ($data_penilaian as $key) {
						if (($key['nilai_minimum'] <= $nilai_akhir) && ($nilai_akhir <= $key['nilai_maksimum'])) {
							$khs[$j]['data_nilai'][$i]['grade'] = $key['grade']; 
							$khs[$j]['data_nilai'][$i]['sksn'] = $key['bobot_nilai']*($row->sks_teori+$row->sks_praktek+$row->sks_praktikum); 
						}
					}
				$i++;
				$data['semester'] = $row->semester;
				$data['tahun_akademik'] = $this->m_tahun_akademik->get_byid($data_krs['kode_tahun_akademik']);
			}
		$j++;
			
		}
		$data['data'] = $khs;
		return $data;
	}

	//Fungsi Pendukung
	public function get_kode_krs($nim, $kode_tahun_akademik)
	{
		$query = $this->db->where('nim', $nim)
            ->where_not_in('kode_tahun_akademik', $kode_tahun_akademik)
            ->where_not_in('semester', 'K')
            ->get('krs')->result();

		$data = array();
		foreach ($query as $row) {
			$data[] = $this->get_kode_krs_detail($row->kode_krs);
		}

		return $data;

	}

    public function get_kode_krs1($nim, $kode_tahun_akademik)
    {
        $kode_nama_kurikulum = kode_nama_kurikulum($nim);
//        $query = $this->db->where('nim', $nim)
//            ->where_not_in('kode_tahun_akademik', $kode_tahun_akademik)
////            ->where_not_in('semester', 'K')
//            ->get('krs')->result();

//        foreach ($query as $row) {
//            $data[] = $this->get_kode_krs_detail($row->kode_krs);
//        }

        $skripsi_kkp = get_kode_matakuliah_kkp_skripsi();
        $not_in = $this->db->select('makul.id_matakuliah as id_matakuliah, nama_matakuliah')
            ->from('krs')
            ->join('krs_detail as krd', 'krd.kode_krs=krs.kode_krs')
            ->join('matakuliah as makul', 'makul.id_matakuliah=krd.id_matakuliah')
            ->join('khs_detail as khd', 'krd.kode_krs_detail=khd.kode_krs_detail')
            ->where('krs.nim', $nim)
            ->where_not_in('makul.kode_matakuliah',$skripsi_kkp)->get()->result();
//        echo "<pre>";
//        print_r($not_in);
//        die();
        foreach ($not_in as $row){
            $not[] = $row->id_matakuliah;
        }
        $sub = $this->db->select('sks_praktek, sks_teori, sks_praktikum, makul.id_matakuliah, nama_matakuliah, mid(makul.kode_matakuliah,6,1) as semester, makul.kode_matakuliah, FORMAT(khd.nilai_akhir,2) as nilai_akhir, "1" as ket')
            ->from('krs')
            ->join('krs_detail as krd', 'krd.kode_krs=krs.kode_krs')
            ->join('matakuliah as makul', 'makul.id_matakuliah=krd.id_matakuliah')
            ->join('khs_detail as khd', 'krd.kode_krs_detail=khd.kode_krs_detail')
            ->where('krs.nim', $nim)
            ->where_not_in('makul.kode_matakuliah',$skripsi_kkp)
            ->group_by('makul.id_matakuliah')
            ->get_compiled_select();
        $sub1 = $this->db->select('sks_praktek, sks_teori, sks_praktikum, mak.id_matakuliah, nama_matakuliah, kur.semester, mak.kode_matakuliah, "0", "0" as ket')
            ->from('kurikulum as kur')
            ->join('matakuliah as mak','kur.id_matakuliah=mak.id_matakuliah')
            ->where('kur.kode_nama_kurikulum',$kode_nama_kurikulum)
            ->where_not_in('mak.kode_matakuliah',$skripsi_kkp)
            ->where_not_in('kur.id_matakuliah',$not)
            ->get_compiled_select();
        for($i=0; $i < 8; $i++ ){
            $sem = $i+1;
            $tes = $this->db->query("SELECT * FROM ($sub UNION $sub1) as u WHERE u.semester=? order by mid(kode_matakuliah,-3,3) ASC", array($sem))->result();
            if (count($tes) > 0){
                $data['data'][$i] = $tes;
            }
        }
        $data['nim'] = $nim;
        $data['kode_tahun_akademik'] = $kode_tahun_akademik;
//        echo "<pre>";
//        print_r($data);
//        die();

        return $data;

    }

	public function get_kode_krs_detail($kode_krs)
	{
//		$query = $this->db->query("SELECT *, krs.semester FROM krs, krs_detail, khs_detail, matakuliah WHERE krs_detail.kode_krs=krs.kode_krs and krs_detail.kode_krs_detail=khs_detail.kode_krs_detail and krs_detail.kode_matakuliah=matakuliah.kode_matakuliah and krs_detail.kode_krs=".$kode_krs."")->result();
		$skripsi_kkp = get_kode_matakuliah_kkp_skripsi();
        $query = $this->db->select('krs.nim, krs.semester, krd.id_matakuliah, makul.kode_matakuliah, makul.nama_matakuliah, makul.sks_teori, makul.sks_praktek, makul.sks_praktikum, khd.kode_krs_detail, khd.nilai_harian, khd.nilai_uts, khd.nilai_uas, khd.nilai_akhir')
            ->from('krs')
            ->join('krs_detail as krd', 'krd.kode_krs=krs.kode_krs')
            ->join('matakuliah as makul', 'makul.id_matakuliah=krd.id_matakuliah')
            ->join('khs_detail as khd', 'krd.kode_krs_detail=khd.kode_krs_detail')
            ->where('krs.kode_krs', $kode_krs)
            ->where_not_in('makul.kode_matakuliah',$skripsi_kkp)
            ->group_by('makul.kode_matakuliah')
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
 ?>