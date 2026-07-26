<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Loader extends CI_Loader {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Load Service
     *
     * This function is used to load a "Service" class.
     *
     * @param   string|array  $service    Service name or array of services
     * @param   mixed         $params     Optional parameters to pass to the service class constructor
     * @param   string        $object_name Optional object name to assign the service to
     * @return  object
     */
    public function service($service, $params = NULL, $object_name = NULL) {
        if (is_array($service)) {
            foreach ($service as $s) {
                $this->service($s, $params);
            }
            return $this;
        }

        if ($service == '') {
            return FALSE;
        }

        $path = '';
        // Is the service in a sub-folder?
        if (($last_slash = strrpos($service, '/')) !== FALSE) {
            $path = substr($service, 0, ++$last_slash);
            $service = substr($service, $last_slash);
        }

        $class = ucfirst($service);

        $name = empty($object_name) ? strtolower($service) : $object_name;
        
        $CI =& get_instance();
        
        // Prevent loading the service multiple times
        if (isset($CI->$name)) {
            return $this;
        }

        if (!file_exists(APPPATH . 'services/' . $path . $class . '.php')) {
            show_error('Unable to load the requested service: ' . $path . $class . '.php');
        }
        
        // Ensure the base service class is loaded first
        if (!class_exists('MY_Service', false) && file_exists(APPPATH . 'services/MY_Service.php')) {
            require_once(APPPATH . 'services/MY_Service.php');
        }

        require_once(APPPATH . 'services/' . $path . $class . '.php');

        if ($params !== NULL) {
            $CI->$name = new $class($params);
        } else {
            $CI->$name = new $class();
        }

        return $this;
    }
}
