<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once FCPATH . 'vendor/autoload.php';

class M_pdf extends \Mpdf\Mpdf {

    public $param;

    public function __construct($param = null)
    {
        $this->param = $param;
        parent::__construct($this->buildConfig($param));
    }

    public function reinitialize($param)
    {
        $this->param = $param;
        $config = $this->buildConfig($param);
        parent::__construct($config);
    }

    private function buildConfig($param)
    {
        if ($param === null) {
            return [];
        }

        if (is_array($param)) {
            $param['tempDir'] = FCPATH . 'application/cache/mpdf';
            return $param;
        }

        $params = explode(',', str_replace(["'", '"', ' '], '', $param));

        $config = [];

        if (isset($params[0]) && $params[0] !== '') {
            $config['mode'] = $params[0];
        }
        if (isset($params[1]) && $params[1] !== '') {
            $config['format'] = $params[1];
        }
        if (isset($params[4]) && $params[4] !== '') {
            $config['margin_left'] = (int)$params[4];
        }
        if (isset($params[5]) && $params[5] !== '') {
            $config['margin_right'] = (int)$params[5];
        }
        if (isset($params[6]) && $params[6] !== '') {
            $config['margin_top'] = (int)$params[6];
        }
        if (isset($params[7]) && $params[7] !== '') {
            $config['margin_bottom'] = (int)$params[7];
        }
        if (isset($params[8]) && $params[8] !== '') {
            $config['margin_header'] = (int)$params[8];
        }
        if (isset($params[9]) && $params[9] !== '') {
            $config['margin_footer'] = (int)$params[9];
        }

        $config['tempDir'] = FCPATH . 'application/cache/mpdf';

        return $config;
    }
}
