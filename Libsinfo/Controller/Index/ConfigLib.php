<?php
namespace Optomamodules\Libsinfo\Controller\Index;

use Optomamodules\Libsinfo\Controller\Index\HttpLib as HttpLib;
use Optomamodules\Libsinfo\Controller\Index\ConfigLib as ConfigLib;
use Optomamodules\Libsinfo\Controller\Index\SecurityLib as SecurityLib;

class ConfigLib extends \Optomamodules\Libsinfo\Controller\Index
{
	public static $_default_config_path = '/include/Optoma_config'; //it should be modified as json file

	public static function getConfigPath()
	{
		if (defined('CONFIG_PATH')) {
			return CONFIG_PATH;
		}
		$default_path = self::$_default_config_path;

		if (is_dir($default_path)) {
			return $default_path;
		}
		throw new LogicException('ConfigLib: config path not found'); 
	}

	public function execute()
	{

	}

	public static function getConfig($name, $extension = 'json')
	{
		$config_path = self::getConfigPath();
		$file_path = sprintf('%s/%s.%s', $config_path, $name, $extension);
		$text = file_get_contents($file_path);
		if ('json' == $extention) {
			return json_decode($text, true); 
		} else {
			return $text;
		}
	}

	public static function getUrn()
	{
		//checking ip infos
		if (SecurityLib::getSecurity()) {
			return SecurityLib::getSecurity() . ($_SERVER['HTTP_HOST']);
		}
	}

	public static function getHost()
	{
		if (SecurityLib::getSecurity()) {
			return $_SERVER['HTTP_HOST'];
		}
	}

	/* get mysql user name */
	public static function getUserName($label = null)
	{
		//todo :: should be modified
		$string = null; //connection string
		switch	($label) {
			case 'root':
				$string = 'root';
			break;
			case 'admin':
				$string = 'admin';
			break;
			case 'opto_prod': /* original database for official site*/
			case 'magento': /* magento database */
			default:
				$string =  'magento2'; //user name
			break;	
		}
		return ('' == trim($string)) ?: $string;	
	} 

	//testing
	public static function getPassword($pass = null)
	{
		// it shuld be modified
		$string = null;
		switch ($pass) {
			case 'root':
				$string = 'root';
			break;
			case 'admin':
				$string = 'admin';
			break;
			case 'opto_prod': /* original database for opam*/
			case 'magento': /* magento database */
			default:
				$string = 'magento2';
			break;		
		}
		return ('' == trim($string)) ?: $string;
	}

	public static function getDBname($dbname = null)
	{
		$string = null;
		switch ($dbname) {
			case 'nick':
				$string = 'nick_db'; // integration :: original OPAM
			break;	
			case 'opam_ec':
				$string = 'opam_ec'; //integration :: original OPAM EC
			break;
			case 'opam_site': // integration :: original OPAM
			case 'opam':
			case 'opam_site_new':
				$string = 'opam_site_new'; //integration :: opam site new DB
			break;

			case 'opam_site_renew':
				$string = 'opam_site_renew';
			break;			

			case 'space_simulator':
				$string = 'space_simulator';//integration :: space simulator distance simulator
			break;
			case 'mysql':
				//$string = 'mysql'; // it should be checked
			break;
			case 'opto_prod': /* original database for website */
				$string = 'OptoProd';
			break;
			case 'magento': /* default */
			default:
				$string = 'magento2';
			break;
		}
		return ('' == trim($string)) ?: $string; 		
	}	
}

?>
