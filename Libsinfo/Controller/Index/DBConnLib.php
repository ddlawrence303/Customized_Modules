<?php
namespace Optomamodules\Libsinfo\Controller\Index;

use Optomamodules\Libsinfo\Controller\Index\HttpLib as HttpLib;
use Optomamodules\Libsinfo\Controller\Index\ConfigLib as ConfigLib;
use Optomamodules\Libsinfo\Controller\Index\LogLib as LogLib;
use Optomamodules\Libsinfo\Controller\Index\ContentLib as ContentLib;

class DBConnLib extends \Optomamodules\Libsinfo\Controller\Index 
{
	//setting const
	//public static $hostname_web_manage2 = "localhost";
	//public static $database_web_manage2 = "ppoptoma";
	//public static $username_web_manage2 = "ppoptoma";
	//public static $password_web_manage2 = "22373279";	
	public static $hostname = null;
	public static  $password = null;
	public static $username = null;

	public $db = null;
	public $dbname = null;
	
	private static function getMysqlConn($args = array())
	{
		$_db = null;
		try {	
		if (false) {
		if ('magento' == $args['database']) {// magento or original database
			self::$username = ConfigLib::getUserName( 'magento');
			self::$password = ConfigLib::getPassword( 'magento');
			self::$dbname = ConfigLib::getDBname('magento');
		} else if ('opto_prod' == $args['database']) {
			self::$username = ConfigLib::getUserName('opto_prod');
			self::$password = ConfigLib::getPassword('opto_prod');
			self::$dbname = ConfigLib::getDBname('opto_prod');
		}
		}
	
		//DebugLib::lolo( ConfigLib::getDBname($args['database']), 'DBConnLib Checking');echo 'MMMM';exit;
		$res = mysqli_connect( ConfigLib::getHost() . ':3306', ConfigLib::getUserName($args['mysql_user']), ConfigLib::getPassword($args['mysql_user']) , ConfigLib::getDBname($args['database']) );
		//$res = mysqli_connect( ConfigLib::getHost() . ':3306', ConfigLib::getUserName($args['mysql_user']), ConfigLib::getPassword($args['mysql_user']) , ConfigLib::getDBname($args['database']) );
		//$res = mysqli_connect('192.168.43.128:3306', 'magento2', 'magento2', 'magento2');	
		
		return (null == $res) ?: $res;	

		} catch (\Exception $e) {
			//setting log msg
			var_dump($e->getMessage());echo 'Database ERROR happen';exit;
		}
	}

	public static function releaseConnection($conn = null)
	{
		if (!is_null($conn)) {
			mysqli_close($conn);
			unset($conn);
		}
		return;
	}
	
	public static function getConnection($label = null, $args = array())
	{
		$db = null;
		if ('mysql' == trim($label)) {
			$db = self::getMysqlConn($args);
		} else if ('oracle' == trim($label)) {
			$db = self::getOracleConn($args); 
		}
		return $db;
	}

	private static function getOracleConn()
	{
		return null;
	}

	//public static function 
	public static function ConnOpam()
	{
		try {
			$_mysql = mysql_pconnect(self::$hostname_web_manage2, self::$username_web_manage2, self::$password_web_manage2);
		} catch (\Exception $e) {
			var_dump($e->getMessage);
			//LogLib::log(); //loggin msg
		}
		return $_mysql;	
	}

	//pdo connection
	public static function getPDO_DBH()
	{
		return false;
	}

	public static function lolo($arg = null)
	{
		return (true == empty($arg)) ?: json_encode($arg); 
	}
	
	public function __construct(){}
	public function execute() {}
}

