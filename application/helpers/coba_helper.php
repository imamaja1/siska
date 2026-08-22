<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

if (!function_exists('_helper_service')) {
    function _helper_service() {
        $CI = get_instance();
        if (!isset($CI->helperservice)) {
            $CI->load->service('HelperService');
        }
        return $CI->helperservice;
    }
}

function kirim_ke_telegram($telegram_id, $message_text){
    $url = "" . $telegram_id;
    $url = $url . "&text=" . urlencode($message_text);
    $ch = curl_init();
    $optArray = array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true
    );
    curl_setopt_array($ch, $optArray);
    $result = curl_exec($ch);
    curl_close($ch);
}

function trunc($angka, $desimal = 0) {
    $faktor = pow(10, $desimal);
    return floor($angka * $faktor) / $faktor;
}

function tgl_indo($tanggal)
{
    $tanggal = date('Y-m-d', strtotime($tanggal));
    $bulan = array(
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $pecahkan = explode('-', $tanggal);
    return $pecahkan[2] . ' ' . $bulan[(int)$pecahkan[1]] . ' ' . $pecahkan[0];
}

if (!function_exists('mk_hidden')) {
    function mk_hidden() { return _helper_service()->mkHidden(); }
}

if (!function_exists('tes')) {
    function tes($var = "") { return "Ini adalah hasil dari helper tes : " . $var; }
}

if (!function_exists('rbac_cek')) {
    function rbac_cek($con, $id) { return _helper_service()->rbacCek($con, $id); }
}

if (!function_exists('rbac_list')) {
    function rbac_list($id) { return _helper_service()->rbacList($id); }
}

if (!function_exists('stup_grade')) {
    function stup_grade($kode_kurikulum_angkatan, $semester = null) { return _helper_service()->stupGrade($kode_kurikulum_angkatan, $semester); }
}

if (!function_exists('tahun_akademik')) {
    function tahun_akademik() { return _helper_service()->tahunAkademik(); }
}

if (!function_exists('semester')) {
    function semester() { return _helper_service()->semester(); }
}

function get_kode_prodi($nim) { return _helper_service()->getKodeProdi($nim); }

function available_kompetensi($nim) { return _helper_service()->availableKompetensi($nim); }

function available_extensi($nim) { return _helper_service()->availableExtensi($nim); }

function get_kode_nama_kurikulum_by_prodi_angkatan($kode_program_studi, $angkatan) { return _helper_service()->getKodeNamaKurikulumByProdiAngkatan($kode_program_studi, $angkatan); }

function get_makul_kkp_by_kode_nama_kurikulum($kode_nama_kurikulum) { return _helper_service()->getMakulKkpByKodeNamaKurikulum($kode_nama_kurikulum); }

function get_kode_matakuliah_kkp() { return _helper_service()->getKodeMatakuliahKkp(); }
function get_kode_matakuliah_kkp_skripsi() { return _helper_service()->getKodeMatakuliahKkpSkripsi(); }
function get_kode_matakuliah_skripsi() { return _helper_service()->getKodeMatakuliahSkripsi(); }

if (!function_exists('kode_nama_kurikulum')) {
    function kode_nama_kurikulum($nim) { return _helper_service()->kodeNamaKurikulum($nim); }
}

function nama_kurikulum($nim) { return _helper_service()->namaKurikulum($nim); }

if (!function_exists('nama_kurikulum_nama')) {
    function nama_kurikulum_nama($nim) { return _helper_service()->namaKurikulumNama($nim); }
}

if (!function_exists('sistem_penilaian')) {
    function sistem_penilaian($nim) { return _helper_service()->sistemPenilaian($nim); }
}

function data_penilaian($nim, $semester = null) { return _helper_service()->dataPenilaian($nim, $semester); }

function get_matakuliah($id_matakuliah) { return _helper_service()->getMatakuliah($id_matakuliah); }

function day_left($tgl_end)
{
    $startTimeStamp = strtotime(date('Y-m-d'));
    $endTimeStamp = strtotime($tgl_end);
    $timeDiff = abs($endTimeStamp - $startTimeStamp);
    $numberDays = $timeDiff / 86400;
    $numberDays = intval($numberDays);
    if ($startTimeStamp > $endTimeStamp) {
        return "<span class='badge bg-red'>Waktu Habis</span>";
    } else {
        if ($numberDays == 0) {
            return "<span class='badge bg-orange'>Hari Ini</span>";
        } else {
            return "<span class='badge bg-green'>" . $numberDays . " Hari Lagi</span>";
        }
    }
}

function scanDirectories($rootDir, $allData = array())
{
    $invisibleFileNames = array(".", "..", ".htaccess", ".htpasswd", ".html");
    $dirContent = scandir($rootDir);
    foreach ($dirContent as $key => $content) {
        $path = $rootDir . '/' . $content;
        if (!in_array($content, $invisibleFileNames)) {
            if (is_file($path) && is_readable($path)) {
                $allData[] = $path;
            } elseif (is_dir($path) && is_readable($path)) {
                $allData = scanDirectories($path, $allData);
            }
        }
    }
    return $allData;
}

function isKaprodi($kode_dosen) { return _helper_service()->isKaprodi($kode_dosen); }

function isDekan($kode_dosen) { return _helper_service()->isDekan($kode_dosen); }

function signatur_nik($nik) { return _helper_service()->signaturNik($nik); }

function get_mac_addres()
{
    $MAC = exec('getmac');
    $MAC = strtok($MAC, ' ');
    return $MAC;
}

function bodo_kop($nim) { return _helper_service()->bodoKop($nim); }

function pembayaran_mahasiswa($nim) { return _helper_service()->pembayaranMahasiswa($nim); }

function prodi_aktif_krs($kode_program_studi) { return _helper_service()->prodiAktifKrs($kode_program_studi); }

function block($nim) { return _helper_service()->block($nim); }

if (!function_exists('nilai_validasi')) {
    function nilai_validasi($status, $kelas_id = null)
    {
        if ($status == 'F' || $status === null || $status === '' || $status === '0') {
            return '<span class="label label-danger">Belum</span>';
        } else if ($status == 'R') {
            return '<span class="label label-warning">Revisi</span>';
        }
        return '<span class="label label-success">Sudah</span>';
    }

    function nilai_validasi_telat($status)
    {
        if ($status == 'T') {
            return '<span class="label label-success">Sudah</span>';
        } else if ($status == 'R') {
            return '<span class="label label-warning">Revisi Telat</span>';
        }
        return '<span class="label label-default">Telat</span>';
    }

    function nilai_validasi_dosen_uas($status, $kelas_id = null, $time = null)
    {
        if ($status == 'T') return '<span class="label label-success">Sudah</span>';
        $CI = get_instance();
        $data = $CI->db->select('updated_at')->where('kelas_id', $kelas_id)
            ->where('isian', 'T')
            ->where('validasi_prodi !=', 'T')
            ->where('validasi_dekan !=', 'T')
            ->order_by('updated_at', 'desc')
            ->get('kelas_validasi')->row_object();
        if ($data && strtotime($data->updated_at) <= strtotime('+2 days', strtotime($time))) {
            if ($status == 'F' || $status === null || $status === '') return '<span class="label label-default">Telat</span>';
            else if ($status == 'R') return '<span class="label label-warning">Revisi Telat</span>';
            return '<span class="label label-success">Sudah</span>';
        } else {
            if ($status == 'F' || $status === null || $status === '') return '<span class="label label-default">Telat</span>';
            else if ($status == 'R') return '<span class="label label-warning">Revisi Telat</span>';
            return '<span class="label label-success">Sudah</span>';
        }
    }

    function nilai_validasi_prodi_uas($status, $kelas_id = null, $time = null)
    {
        if ($status == 'T') return '<span class="label label-success">Sudah</span>';
        $CI = get_instance();
        $data = $CI->db->select('updated_at')->where('kelas_id', $kelas_id)
            ->where('isian', 'T')
            ->where('validasi_prodi', 'T')
            ->where('validasi_dekan !=', 'T')
            ->order_by('updated_at', 'desc')
            ->get('kelas_validasi')->row_object();
        if ($data && strtotime($data->updated_at) <= strtotime('+2 days', strtotime($time))) {
            return '<span class="label label-success">Sudah</span>';
        } else {
            if ($status == 'F' || $status === null || $status === '') return '<span class="label label-default">Telat</span>';
            else if ($status == 'R') return '<span class="label label-warning">Revisi Telat</span>';
            return '<span class="label label-success">Telat</span>';
        }
    }

    function nilai_validasi_dekan_uas($status, $kelas_id = null, $time = null)
    {
        if ($status == 'T') return '<span class="label label-success">Sudah</span>';
        $CI = get_instance();
        $data = $CI->db->select('updated_at')->where('kelas_id', $kelas_id)
            ->where('isian', 'T')
            ->where('validasi_prodi', 'T')
            ->where('validasi_dekan', 'T')
            ->order_by('updated_at', 'desc')
            ->get('kelas_validasi')->row_object();
        if ($data && strtotime($data->updated_at) <= strtotime('+2 days', strtotime($time))) {
            return '<span class="label label-success">Sudah</span>';
        } else {
            if ($status == 'F' || $status === null || $status === '') return '<span class="label label-default">Telat</span>';
            else if ($status == 'R') return '<span class="label label-warning">Revisi Telat</span>';
            return '<span class="label label-success">Telat</span>';
        }
    }
}

if (!function_exists('cek_komentar_revisi')) {
    function cek_komentar_revisi($kelas_id) { return _helper_service()->cekKomentarRevisi($kelas_id); }
}

if (!function_exists('isian_nilai')) {
    function isian_nilai($status)
    {
        if ($status == 'F') return '<span class="label label-danger">Belum</span>';
        return '<span class="label label-success">Sudah</span>';
    }
}

if (!function_exists('validasi_nilai')) {
    function validasi_nilai($status)
    {
        if ($status == 'F') return '<span class="label label-danger">Belum</span>';
        return '<span class="label label-success">Sudah</span>';
    }
}

if (!function_exists('e')) {
    function e($str)
    {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('log_aktivitas_nilai')) {
    function log_aktivitas_nilai($aksi, $kolom = null, $nilai_lama = null, $nilai_baru = null, $sumber = 'perubahan', $kode_khs_detail = null, $kode_krs_detail = null, $kode_krs = null)
    {
        $CI = get_instance();
        $nim = null;
        $id_matakuliah = null;
        $kode_tahun_akademik = null;

        if ($kode_krs_detail) {
            $row = $CI->db->select('krs.nim, kd.id_matakuliah, krs.kode_tahun_akademik')
                ->from('krs_detail as kd')
                ->join('krs', 'krs.kode_krs=kd.kode_krs')
                ->where('kd.kode_krs_detail', $kode_krs_detail)
                ->get()->row();
            if ($row) {
                $nim = $row->nim;
                $id_matakuliah = $row->id_matakuliah;
                $kode_tahun_akademik = $row->kode_tahun_akademik;
            }
            if (!$kode_khs_detail) {
                $khs = $CI->db->where('kode_krs_detail', $kode_krs_detail)->get('khs_detail')->row();
                if ($khs) {
                    $kode_khs_detail = $khs->kode_khs_detail;
                }
            }
        } elseif ($kode_khs_detail) {
            $row = $CI->db->select('krs.nim, kd.id_matakuliah, krs.kode_tahun_akademik, khd.kode_krs_detail')
                ->from('khs_detail as khd')
                ->join('krs_detail as kd', 'kd.kode_krs_detail=khd.kode_krs_detail')
                ->join('krs', 'krs.kode_krs=kd.kode_krs')
                ->where('khd.kode_khs_detail', $kode_khs_detail)
                ->get()->row();
            if ($row) {
                $nim = $row->nim;
                $id_matakuliah = $row->id_matakuliah;
                $kode_tahun_akademik = $row->kode_tahun_akademik;
                if (!$kode_krs_detail) {
                    $kode_krs_detail = $row->kode_krs_detail;
                }
            }
        } elseif ($kode_krs) {
            $row = $CI->db->select('nim, kode_tahun_akademik')
                ->where('kode_krs', $kode_krs)
                ->get('krs')->row();
            if ($row) {
                $nim = $row->nim;
                $kode_tahun_akademik = $row->kode_tahun_akademik;
            }
        }

        if (is_array($kolom)) {
            $kolom = implode(',', $kolom);
        }
        if (is_array($nilai_lama)) {
            $nilai_lama = json_encode($nilai_lama);
        }
        if (is_array($nilai_baru)) {
            $nilai_baru = json_encode($nilai_baru);
        }

        return $CI->db->insert('log_aktivitas_nilai', array(
            'kode_khs_detail' => $kode_khs_detail,
            'kode_krs_detail' => $kode_krs_detail,
            'nim' => $nim,
            'id_matakuliah' => $id_matakuliah,
            'kode_tahun_akademik' => $kode_tahun_akademik,
            'aksi' => $aksi,
            'kolom' => $kolom,
            'nilai_lama' => $nilai_lama,
            'nilai_baru' => $nilai_baru,
            'sumber' => $sumber,
            'id_user' => $CI->session->userdata('id'),
            'nama_login' => $CI->session->userdata('nama_login'),
        ));
    }
}
