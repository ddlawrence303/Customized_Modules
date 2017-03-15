<?php
namespace Optomamodules\Libsinfo\Controller\Index;

use Optomamodules\Libsinfo\Controller\Index\HttpLib as HttpLib;
use Optomamodules\Libsinfo\Controller\Index\ConfigLib as ConfigLib;
use Optomamodules\Libsinfo\Controller\Index\SecurityLib as SecurityLib;

class CommonLib extends \Optomamodules\Libsinfo\Controller\Index
{
	public static function isBlackEmail($email)
	{
		if (DEBUG_ENV) {
			return false;
		}

		$black_email_domains = array(); //get list from black list
		foreach ($black_email_domains as $black_email_domain) {
			if (stristr($email, $black_email_domain)) {
				return true;
			}
		}
		return false;
	}

	public static function setOptomaCookie($name, $value, $expire_at = null)
	{
		if (!defined('COOKIE_DOMAIN')) {
			define('COOKIE_DOMAIN', 'www.optoma.com');
			LogLib::log('warning', 'COOKIE_DOMAIN UNSET: ' . $_SERVER['REQUEST_URI']);
		}
		if (is_null($expire_at)) {
			//custum
			$expire_at = time() + 86400 * 7;
		}
		//setting
		setcookie($name, $value, $expire_at, '/', COOKIE_DOMAIN);
	}

	public static function getShortUrl($url = '')
	{
		$key = sprintf('%u', crc32($url));
		$short_url = sprintf('http://%s/redirect/%s', $_SERVER['HTTP_HOST'], $key);
		//setting cache
		return $short_url;
	}

	public static function getClientIp()
	{
		return false;
	}

	public static function getGAV($key)
	{
		//todo
		return false;
	}

	public static function setGAV($key)
	{
		//todo
		return false;
	}

	public static function deleteGAV($key)
	{
		//todo
		return false;
	}

	public static function getCountryCode()
	{
		//todo
		return false;
	}	
	
	public function __construct(){}
	public function execute() {}
}

