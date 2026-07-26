<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Nilai_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    var $table = 'krs';

    function get_all_matakuliah_by_tahun_akademik_and_jurusan($kode_tahun_akademik, $kode_jurusan_jenjang) {
        $this->load->service('NilaiService');
        return $this->nilaiservice->getAllMatakuliahByTahunAkademikAndJurusan($kode_tahun_akademik, $kode_jurusan_jenjang);
    }

}
