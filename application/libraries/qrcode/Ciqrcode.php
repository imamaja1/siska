<?php
/**
 * PHP QR Code porting for Codeigniter
 *
 * @package        	CodeIgniter
 * @subpackage    	Libraries
 * @category    	Libraries
 * @porting author	dwi.setiyadi@gmail.com
 * @original author	http://phpqrcode.sourceforge.net/
 *
 * @version		2.0
 */

use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Output\QROutputInterface;

class Ciqrcode
{
	var $cacheable = true;
	var $cachedir = 'application/cache/';
	var $errorlog = 'application/logs/';
	var $quality = true;
	var $size = 1024;

	function __construct($config = array()) {
		require_once FCPATH . 'vendor/autoload.php';
		$this->initialize($config);
	}

	public function initialize($config = array()) {
		$this->cacheable = (isset($config['cacheable'])) ? $config['cacheable'] : $this->cacheable;
		$this->cachedir = (isset($config['cachedir'])) ? $config['cachedir'] : FCPATH.$this->cachedir;
		$this->errorlog = (isset($config['errorlog'])) ? $config['errorlog'] : FCPATH.$this->errorlog;
		$this->quality = (isset($config['quality'])) ? $config['quality'] : $this->quality;
		$this->size = (isset($config['size'])) ? $config['size'] : $this->size;
	}

	public function generate($params = array()) {
		$params['data'] = (isset($params['data'])) ? $params['data'] : 'QR Code Library';

		$level = 'L';
		if (isset($params['level']) && in_array($params['level'], array('L','M','Q','H'))) $level = $params['level'];

		$size = 4;
		if (isset($params['size'])) $size = min(max((int)$params['size'], 1), 10);

		$eccMap = array(
			'L' => QRCode::ECC_L,
			'M' => QRCode::ECC_M,
			'Q' => QRCode::ECC_Q,
			'H' => QRCode::ECC_H,
		);

		$options = new QROptions(array(
			'outputType'   => QROutputInterface::GDIMAGE_PNG,
			'eccLevel'     => $eccMap[$level],
			'scale'        => $size,
			'outputBase64' => false,
		));

		if (isset($params['savename'])) {
			(new QRCode($options))->render($params['data'], $params['savename']);
			return $params['savename'];
		}

		$png = (new QRCode($options))->render($params['data']);
		header('Content-Type: image/png');
		echo $png;
	}
}

/* end of file */
