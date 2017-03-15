<?php
namespace Optomamodules\Libsinfo\Controller\Index;

use Optomamodules\Libsinfo\Controller\Index\ConfigLib as ConfigLib;
use Optomamodules\Libsinfo\Controller\Index\LogLib as LogLib;
use Optomamodules\Libsinfo\Controller\Index\HttpLib as HttpLib;

class SecurityLib extends \Optomamodules\Libsinfo\Controller\Index
{
	
	private static $security = false;
	
	public function get()
	{
		$url = $_GET['url'];
		$contents = HttpLib::get($url);//parse the url informations
		
		preg_match('/\.([^.]+)$/', $url, $matches);
		$ext = strtolower($matches[1]);
		if (in_array($ext, ImageLib::$image_types)) {
			header("Content-type: image/{$ext}");
		}
		echo $contents;
		return;
	}
	
	public function __construct(){}

	public function execute(){}

	public function protect()
	{
		return false;
	}

	public static function getSecurity()
	{
		$http = null; //var_dump( SecurityLib::$security);
		if (!SecurityLib::$security) {
			$http = 'http://';
		} else {
			$http = 'https://';
		}
		return $http;
	}
}
