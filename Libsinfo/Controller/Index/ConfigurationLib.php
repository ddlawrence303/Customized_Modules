<?php
namespace Optomamodules\Libsinfo\Controller\Index;

use \Optomamodules\Libsinfo\Controller\Index\HttpLib as HttpLib;
use \Optomamodules\Libsinfo\Controller\Index\ConfigLib as ConfigLib;
use \Optomamodules\Libsinfo\Controller\Index\LogLib as LogLib;

class ConfigurationLib extends \Optomamodules\Libsinfo\Controller\Index
{
	//setting configuration infos
	private /* array[string]string. */ $configs = null;

	private static $exports = array(
		'__config',
		'images_host',
		'js_host',
		'css_host',
		'www_host',
		'cig_host',
		'mobile_host'		
	);

 	
	//mysql export :: it's the reference for DBConnLib 
	private static $exportsMySQL = array(
		'host_dbm1', //pending to check
		'host_dbm2',
		'host_dbm3',
		'host_admin',
		'host_opam',
		'host_opam_ec'
	);

	protected static /* configuration. */ $instance = null;

	public /* void */ function __construct(){}

	public function execute() {}
	public /* void */ function __clone(){
		throw new Exception('ConfigurationLib is going wrong!');
	}

	/*private  function guessSite()
	{
		if (array_key_exists('host_role', $this->configs['__config'])) {
			$this->configs['site'] = $this
		}		
	} */


	public /* string */ function get(/*string*/ $name)
	{
		$this->key_exists($name);
		return $this->configs[$name];	
	}

	public /* string */ function __get(/*string*/ $name) { return $this->get($name);} 
	
	static /* configuration object */ function instance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new Configuration();
			self::$instance->loadConfigs();
		}	
		return self::$instance;
	}
}


