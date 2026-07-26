<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base Service Class
 * 
 * Provides all services with access to the CodeIgniter super-object
 * so that they can use $this->load, $this->db, $this->session, etc.
 */
class MY_Service {

    public function __construct() {
        log_message('info', 'Service Class Initialized');
    }

    public function __get($key) {
        $CI =& get_instance();
        if (isset($CI->$key)) {
            return $CI->$key;
        }
        $alt = ucfirst($key);
        if (isset($CI->$alt)) {
            return $CI->$alt;
        }
        return $CI->$key;
    }
}
