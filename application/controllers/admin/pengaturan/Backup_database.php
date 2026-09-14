<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Backup_database extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library(array('upload', 'form_validation'));

        $class = $this->router->fetch_class();
        if (!$this->session->userdata('nama_login')) {
            redirect('login/admin');
        } else {
            $id_user = $this->session->userdata('id');
            $cek = rbac_cek($class, $id_user);
            if (!$cek) {
                redirect(site_url('denied'));
            }
        }
    }

    private function _get_db_credentials()
    {
        include(APPPATH . 'config/database.php');
        return array(
            'hostname' => $db['default']['hostname'],
            'username' => $db['default']['username'],
            'password' => $db['default']['password'],
            'database' => $db['default']['database'],
            'dbdriver' => $db['default']['dbdriver'],
        );
    }

    private function _db_connect()
    {
        $cred = $this->_get_db_credentials();
        $conn = @new mysqli($cred['hostname'], $cred['username'], $cred['password'], $cred['database']);
        if ($conn->connect_error) {
            return false;
        }
        $conn->set_charset('utf8');
        return $conn;
    }

    public function index()
    {
        $data = array(
            'content'   => 'admin/pengaturan/backup_database/V_index',
            'judul'     => 'Pengaturan',
            'sub_judul' => 'Export / Import Database',
            'title_h1'  => '<li>Pengaturan</li>',
            'title_h2'  => '<li>Export / Import Database</li>',
        );

        $this->load->view('admin/template/V_main', $data);
    }

    public function export()
    {
        set_time_limit(0);
        ini_set('memory_limit', '512M');

        $conn = $this->_db_connect();
        if (!$conn) {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>Gagal terhubung ke database.</h6></div>');
            redirect(site_url('admin/pengaturan/backup_database'));
        }

        $filename = 'siska_backup_' . date('Ymd_His') . '.sql';
        $tempfile = tempnam(sys_get_temp_dir(), 'siska_bkp_');

        $fp = fopen($tempfile, 'w');
        fwrite($fp, "-- SISKA UBG Database Backup\n");
        fwrite($fp, "-- Tanggal: " . date('Y-m-d H:i:s') . "\n");
        fwrite($fp, "-- DB: " . $this->_get_db_credentials()['database'] . "\n\n");
        fwrite($fp, "SET FOREIGN_KEY_CHECKS=0;\n\n");

        $tables_res = $conn->query('SHOW TABLES');
        $tables = array();
        while ($row = $tables_res->fetch_row()) {
            $tables[] = $row[0];
        }
        $tables_res->free();

        foreach ($tables as $table) {
            $table = $conn->real_escape_string($table);

            $create = $conn->query('SHOW CREATE TABLE `' . $table . '`');
            $create_row = $create->fetch_row();
            $create->free();

            fwrite($fp, 'DROP TABLE IF EXISTS `' . $table . '`;' . "\n");
            fwrite($fp, $create_row[1] . ";\n\n");

            $data_res = $conn->query('SELECT * FROM `' . $table . '`', MYSQLI_USE_RESULT);
            if (!$data_res) {
                fclose($fp);
                @unlink($tempfile);
                $conn->close();
                $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>Gagal membaca tabel ' . $table . '.</h6></div>');
                redirect(site_url('admin/pengaturan/backup_database'));
            }

            $cols = array();
            $fields = $data_res->fetch_fields();
            foreach ($fields as $f) {
                $cols[] = $f->name;
            }

            $insert_prefix = 'INSERT INTO `' . $table . '` (`' . implode('`,`', array_map(array($conn, 'real_escape_string'), $cols)) . '`) VALUES ';
            $batch = array();
            $batch_size = 200;

            while ($data_row = $data_res->fetch_assoc()) {
                $values = array();
                foreach ($cols as $col) {
                    $val = $data_row[$col];
                    if ($val === null) {
                        $values[] = 'NULL';
                    } else {
                        $values[] = "'" . $conn->real_escape_string($val) . "'";
                    }
                }
                $batch[] = '(' . implode(',', $values) . ')';
                if (count($batch) >= $batch_size) {
                    fwrite($fp, $insert_prefix . implode(",\n", $batch) . ";\n");
                    $batch = array();
                }
            }

            if (!empty($batch)) {
                fwrite($fp, $insert_prefix . implode(",\n", $batch) . ";\n");
            }

            $data_res->free();
            fwrite($fp, "\n");
        }

        fwrite($fp, "SET FOREIGN_KEY_CHECKS=1;\n");
        fclose($fp);
        $conn->close();

        if (!file_exists($tempfile) || filesize($tempfile) == 0) {
            @unlink($tempfile);
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>Gagal membuat backup database.</h6></div>');
            redirect(site_url('admin/pengaturan/backup_database'));
        }

        $this->_stream_download($tempfile, $filename);
        @unlink($tempfile);
    }

    public function import()
    {
        set_time_limit(0);
        ini_set('memory_limit', '512M');
        $this->form_validation->set_rules('file_backup', 'File Backup', 'callback_validate_sql_file');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>' . strip_tags(validation_errors()) . '</h6></div>');
            redirect(site_url('admin/pengaturan/backup_database'));
        }

        $config = array(
            'upload_path'   => sys_get_temp_dir() . '/',
            'allowed_types' => 'sql',
            'max_size'      => 51200,
            'overwrite'     => true,
            'file_name'     => 'siska_import_' . date('Ymd_His') . '.sql',
        );

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('file_backup')) {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>' . strip_tags($this->upload->display_errors()) . '</h6></div>');
            redirect(site_url('admin/pengaturan/backup_database'));
        }

        $upload_data = $this->upload->data();
        $file_path = $upload_data['full_path'];

        $conn = $this->_db_connect();
        if (!$conn) {
            @unlink($file_path);
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>Gagal terhubung ke database.</h6></div>');
            redirect(site_url('admin/pengaturan/backup_database'));
        }

        $result = $this->_execute_sql_file($conn, $file_path);
        @unlink($file_path);
        $conn->close();

        if ($result['status'] === true) {
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-success"><h6>Import database berhasil (' . $result['statements'] . ' statement dieksekusi).</h6></div>');
        } else {
            log_message('error', 'Import database gagal: ' . $result['error']);
            $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h6>Import database gagal: ' . $result['error'] . '</h6></div>');
        }

        redirect(site_url('admin/pengaturan/backup_database'));
    }

    private function _execute_sql_file($conn, $file_path)
    {
        $fp = fopen($file_path, 'r');
        if (!$fp) {
            return array('status' => false, 'error' => 'Tidak dapat membaca file.');
        }

        $buffer = '';
        $in_single = false;
        $in_double = false;
        $in_backtick = false;
        $in_line_comment = false;
        $statements = 0;
        $error = '';

        while (($chunk = fread($fp, 65536)) !== false && $chunk !== '') {
            $len = strlen($chunk);
            for ($i = 0; $i < $len; $i++) {
                $ch = $chunk[$i];

                if ($in_line_comment) {
                    if ($ch === "\n") {
                        $in_line_comment = false;
                    }
                    continue;
                }

                if ($in_single) {
                    $buffer .= $ch;
                    if ($ch === '\\' && $i + 1 < $len) {
                        $buffer .= $chunk[++$i];
                    } elseif ($ch === "'") {
                        $in_single = false;
                    }
                    continue;
                }

                if ($in_double) {
                    $buffer .= $ch;
                    if ($ch === '\\' && $i + 1 < $len) {
                        $buffer .= $chunk[++$i];
                    } elseif ($ch === '"') {
                        $in_double = false;
                    }
                    continue;
                }

                if ($in_backtick) {
                    $buffer .= $ch;
                    if ($ch === '`') {
                        $in_backtick = false;
                    }
                    continue;
                }

                if ($ch === '-') {
                    if ($i + 1 < $len && $chunk[$i + 1] === '-') {
                        $i++;
                        $in_line_comment = true;
                        continue;
                    }
                    $buffer .= $ch;
                    continue;
                }

                if ($ch === "'") {
                    $in_single = true;
                    $buffer .= $ch;
                    continue;
                }

                if ($ch === '"') {
                    $in_double = true;
                    $buffer .= $ch;
                    continue;
                }

                if ($ch === '`') {
                    $in_backtick = true;
                    $buffer .= $ch;
                    continue;
                }

                if ($ch === ';') {
                    $statement = trim($buffer);
                    $buffer = '';
                    if ($statement !== '') {
                        if (!$conn->query($statement)) {
                            $error = $conn->error . ' (pada statement ke-' . ($statements + 1) . ')';
                            fclose($fp);
                            return array('status' => false, 'error' => $error, 'statements' => $statements);
                        }
                        $statements++;
                    }
                    continue;
                }

                $buffer .= $ch;
            }
        }

        fclose($fp);

        $statement = trim($buffer);
        if ($statement !== '') {
            if (!$conn->query($statement)) {
                return array('status' => false, 'error' => $conn->error, 'statements' => $statements);
            }
            $statements++;
        }

        return array('status' => true, 'error' => '', 'statements' => $statements);
    }

    private function _stream_download($filepath, $filename)
    {
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filepath));
        header('Cache-Control: private, max-age=0, must-revalidate');
        header('Pragma: public');

        $fp = fopen($filepath, 'rb');
        if ($fp) {
            while (!feof($fp)) {
                echo fread($fp, 8192);
                flush();
            }
            fclose($fp);
        }
        exit;
    }

    public function validate_sql_file($str)
    {
        if (empty($_FILES['file_backup']['name'])) {
            $this->form_validation->set_message('validate_sql_file', 'File backup wajib diisi.');
            return false;
        }

        $ext = strtolower(pathinfo($_FILES['file_backup']['name'], PATHINFO_EXTENSION));
        if ($ext !== 'sql') {
            $this->form_validation->set_message('validate_sql_file', 'File harus berekstensi .sql.');
            return false;
        }

        return true;
    }
}