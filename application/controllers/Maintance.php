<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Maintance extends CI_Controller {

    function index() {
        $this->load->view('View_maintance');
    }
    
    function notfound()
    {
        $this->output->set_status_header('404');
        $this->load->view('View_notfound');
    }

}
