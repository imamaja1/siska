<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Lupa_password extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('lupa_password_model', 'm_reset');
    }

    public function coba()
    {
        echo $token = substr(sha1(rand()), 0, 30);
    }

    public function index()
    {
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email', array('required' => 'Field Email harus diisi', 'valid_email' => 'Format email tidak valid'));
        $this->form_validation->set_rules('status', 'Status', 'required', array('required' => 'Field Status harus dipilih'));

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Halaman Reset Password';
            $this->load->view('auth/v_lupa_password', $data);
        } else {
            $email = $this->input->post('email');
            $clean = $this->security->xss_clean($email);
            $status = $this->input->post('status');
            $userInfo = $this->m_reset->getUserInfoByEmail($clean, $status);
            if (!$userInfo) {
                $this->session->set_flashdata('email_salah', '<div class="alert animated fadeInUp alert-danger">
                        <h6>Email yang anda inputkan tidak terdaftar.</h6>
                    </div>');
                redirect(site_url('Lupa_password'), 'refresh');
            }
            $token = $this->m_reset->insertToken($clean, $status);
            $qstring = $this->base64url_encode($token);
            $url = site_url() . '/Lupa_password/reset_password/token/' . $qstring;
            $link = '<a href="' . $url . '">' . $url . '</a>';

            $message = '';
            $message .= '<strong>Salam Hangat, </strong><br>Anda menerima email ini karena ada permintaan untuk memperbaharui  
                 password anda.<br>';
            $message .= '<strong>Silakan klik link ini : </strong> ' . $link;
            $message .= '<br>Jika terdapat permasalahan, mohon menghubungi bagian SDC / PUSTIK untuk mendapatkan keterangan lebih lanjut.';
            $message .= '<br>Terima Kasih.';

            // echo $message; //send this through mail
            $this->load->library('email');
            $config = array();
            $config['charset'] = 'utf-8';
            $config['useragent'] = 'Codeigniter';
            $config['protocol'] = "smtp";
            $config['mailtype'] = "html";
            $config['smtp_host'] = $this->config->item('smtp_host');
            $config['smtp_port'] = $this->config->item('smtp_port');
            $config['smtp_timeout'] = $this->config->item('smtp_timeout');
            $config['smtp_user'] = $this->config->item('smtp_user');
            $config['smtp_pass'] = $this->config->item('smtp_pass');
            $config['crlf'] = "\r\n";
            $config['newline'] = "\r\n";
            // $config['wordwrap'] = TRUE;
            //memanggil library email dan set konfigurasi untuk pengiriman email

            $this->email->initialize($config);
            //konfigurasi pengiriman
            $this->email->from($config['smtp_user'], 'Software Development Center');
            $this->email->to($email);
            $this->email->subject("Reset Password Siska");

            // $message = "<p>Anda melakukan permintaan reset password</p>";
            // $message .= "<a href='" . site_url('welcome/reset_password/' . $reset_key) . "'>klik reset password</a>";
            $this->email->message($message);

            if ($this->email->send()) {
                $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-success"><h5>Berhasil.</h5>
                        <h6>Link untuk reset password sudah dikirim ke email : <b>' . $this->input->post('email') . '</b><br>
                        Silahkan buka alamat email tersebut dan ikuti petunjuk selanjutnya.
                        </h6></div>');
            } else {
                $this->session->set_flashdata('pesan', '<div class="alert animated fadeInUp alert-danger"><h5>Error !</h5><h6>Sistem gagal mengirimkan email, mohon menghubungi bagian SDC untuk melakukan reset password.</h6></div>');
            }
            redirect(site_url('Lupa_password'), 'refresh');
            exit;
        }
    }

    public function reset_password()
    {
        $token = $this->base64url_decode($this->uri->segment(4));
        $cleanToken = $this->security->xss_clean($token);

        $user_info = $this->m_reset->isTokenValid($cleanToken); //either false or array();
//        var_dump($user_info);
//        die();
        if (!$user_info) {
            $this->session->set_flashdata('pesan', '<div class="alert animated shake alert-danger"><h5>Error !</h5>
                        <h6>Link reset password sudah digunakan atau kadaluarsa.</h6>
                    </div>');
            redirect(site_url('Lupa_password'), 'refresh');
        }

        $data = array(
            'title' => 'Halaman Reset Password',
            'nama' => $user_info,
            'token' => $this->base64url_encode($token)
        );

        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
        $this->form_validation->set_rules('passconf', 'Password Confirmation', 'required|matches[password]');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('auth/v_reset_password', $data);
        } else {
            $post = $this->input->post(NULL, TRUE);
            $cleanPost = $this->security->xss_clean($post);
            $hashed = md5($cleanPost['password']);
            $cleanPost['password'] = $hashed;
            $cleanPost['email'] = $user_info;
            unset($cleanPost['passconf']);
            if (!$this->m_reset->updatePassword($cleanPost['password'], $cleanToken)) {
                $this->session->set_flashdata(['pesan' => '<h5>Error !</h5><h6>Sistem gagal menyimpan perubahan password, mohon menghubungi bagian SDC untuk melakukan reset password anda.</h6>', 'tipe' => 'danger']);
            } else {
                $this->session->set_flashdata(['pesan' => '<h5>Berhasil.</h5><h6>Password baru telah tersimpan. Silahkan login.</h6>', 'tipe' => 'success']);
            }
            $this->m_reset->removeToken($cleanToken); //remove used token
            redirect(site_url('login'), 'refresh');
        }
    }

    public function base64url_encode($data)
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    public function base64url_decode($data)
    {
        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }
}
