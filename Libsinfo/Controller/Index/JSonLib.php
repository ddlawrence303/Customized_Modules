<?php
namespace Optomamodules\Libsinfo\Controller\Index;

use Optomamodules\Libsinfo\Controller\Index\HttpLib as HttpLib;
use Optomamodules\Libsinfo\Controller\Index\ConfigLib as ConfigLib;
use Optomamodules\Libsinfo\Controller\Index\SecurityLib as SecurityLib;
use Optomamodules\Libsinfo\Controller\Index\DebugLib as DebugLib;

class JSonLib extends \Optomamodules\Libsinfo\Controller\Index
{

	public static function getParam($arg = array())
	{
		return json_encode($arg);
	}

	public static function getConfigPath()
	{
		return false;	
	}

	public static function getConfig($name, $extension = 'json')
	{
	
		return false;	
	}

	public static function getUserName($label = null)
	{
	
		return false;	
	} 

	public static function getPassword($pass = null)
	{
		return false;	
	}

	public static function getJsonData($datas = null)
	{
		$tmp = array();
		$result = array();
		if (assert(!is_null($datas))) {
			$tmp = json_decode($datas, true);
			foreach ($tmp as $key => $value) {
				$result[ $key ] = $value;	
			}
		}
		unset($tmp);
		return (null == empty($result)) ?: $result;		
	}


	public function __construct() {}
	public function execute(){
		//do CRUD
	}
}



