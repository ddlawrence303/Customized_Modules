<?php
namespace Optomamodules\Libsinfo\Controller\Index;

use Optomamodules\Libsinfo\Controller\Index\ConfigLib as ConfigLib;
use Optomamodules\Libsinfo\Controller\Index\LogLib as LogLib;
use Optomamodules\Libsinfo\Controller\Index\CommonLib as CommonLib;
use Optomamodules\Libsinfo\Controller\Index\DebugLib as DebugLib;

class HttpLib extends \Optomamodules\Libsinfo\Controller\Index
{
	public function SendRequest($url, $method = 'GET', $data = array(), $headers = array('Content-type: application/x-www-form-urlencoded'))
	{
		$context = stream_context_create(array(
			'http' => array(
				'method' => $method,
				'header' => $headers,
				'content' => http_build_query($data)
			)
		));
	}

	public function __construct() {}
	public function execute() {}

	public static function getProtocal()
	{
		return json_encode($_SERVER);
	}	

	public static function getCookieFilePath()
	{
		//return define('COOKIE_FILE_PATH') ? COOKIE_FILE_PATH : '/tmp/cookie.txt'
		return  '/tmp/cookie.txt';
	}

	public static function get($url, $curl_options = array())
	{	
		$cookie_file_path = self::getCookieFilePath();
		$curl = new cURL(true, $cookie_file_path);
		$max_check_times = 3; //checking
		$check_times = 0;
		do {
			$content = $curl->get($url, $curl_options);
			if (strlen($content) > 0) {
				break;
			}
			$check_times ++;
		} while ($check_times < 3); 
		return $content;
	}	
	
	public static function post($url, $data, $curl_options = array())
	{
		$cookie_file_path = self::getCookieFilePath();
		$curl = new cURL(true, $cookie_file_path);
		if (is_array($data)) {
			$data = http_build_query($data);
		}
		return $curl->post($url, $data, $curl_options);
	}
	
	public static function getHttpResponseCode($url, $ignore_redirect = false)
	{	
		if ('//' == substr($url, 0, 2)) {
			$url = 'http' . $url;
		}
		$headers = get_headers($url, 1);
		$i = 0;
		do {
			$code = substr($headers[$i], 9, 3);
		} while (!$ignore_redirect and isset($headers[++ $i]));
		return $code;
	}

	//get proxy infos
	public static function getCnProxy()
	{
		return false;
	}
	

	public static function getWCurlRequest_httplib( $param = array())
	{
		return false;
	}

	public static function getWCRequest_http($arg = array())
	{
		return false;
	}
	
	public static function getLampHttpRequest()
	{
	
		return false;
	}

	// it is for testing
	public static function getLampCurlRequest()
	{
		try {
		//easier style
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://shop.optomausa.com/lamp.php?search=0&searchword=EP718");
		//curl_setopt($ch, CURLOPT_URL, "http://shop.optomausa.com/lamp.php");

		$headers = array();
		$headers[] = 'Accept: text/html, image/jpeg, image/jpg, image/png, text/xml';
		$headers[] = 'Connection: Keep-Alive';
		$headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
		$agent = $_SERVER['HTTP_USER_AGENT'];
		$model = 'EP718';			
		$data = array('search' => 0 , 'searchword' => $model);
		
		curl_setopt($ch , CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch , CURLOPT_USERAGENT, $agent);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15000);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query( $data ));
	
		curl_setopt($ch, CURLOPT_COOKIEFILE, $_SERVER['HTTP_COOKIE']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
		}catch(\Exception $e){
			var_dump($e->getMessage());exit;
		}
	}	 
}

//http://www.php.net/manual/en/book.curl.php

class cURL{
	private $headers = array();
	private $user_agent = null;
	private $compression;
	private $cookie_file;
	private $proxy = null;
	private $cookies;

	function __construct($cookies = true, $cookie = '/tmp/cookie.txt', $compression = 'gzip' , $proxy = '' )
	{
		$this->headers[] = 'Accept: text/html, image/jpeg, image/jpg, image/png, text/xml';
		$this->headers[] = 'Connection: Keep-Alive';
		$this->headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
		$this->agent = $_SERVER['HTTP_USER_AGENT'];

		$this->proxy = $proxy;
		$this->cookies = $_SERVER['HTTP_COOKIE'];
		if ($cookies == true) {
			$this->cookie($_SERVER['HTTP_COOKIE']);		
		}			
	}
	
	function cURL($cookies = true, $cookie = '/tmp/cookie.txt', $compression = 'gzip', $proxy = '')
	{
		$this->headers[] = 'Accept: text/html';
		//$this->headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg';
		$this->headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg, text/html, text/json';
		$this->headers[] = 'Connection: Keep-Alive';
		$this->headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
		$this->proxy = $proxy;
		$this->cookies = $cookies;
		if ($this->cookies == true) {
			$this->cookie($cookie);
		}
	}	

	function cookie($cookie_file)
	{
		if (false) { //pending
		if (file_exists($cookie_file)) {
			$this->cookie_file = $cookie_file;
		} else {
			fopen($cookie_file, 'w') or $this->error(
				'the cookie file could not be opend! Make sure this dir has correct permissions!!');
			$this->cookie_file = $cookie_file;
		}
		}
		$this->cookies = $_SERVER['HTTP_COOKIE'];
		return $this->cookies;	
	}

	function get($url, $curl_options = array())
	{
		$process = curl_init($url);
		curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($process, CURLOPT_HEADER, 0);
		curl_setopt($process, CURLOPT_USERAGENT, $this->user_agent);
	  	if ($this->cookies = true) {
			curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
		} 	
		if ($this->cookies = true) {
			curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
		}

		curl_setopt($process, CURLOPT_ENCODING, $this->compression);
		curl_setopt($process, CURLOPT_TIMEOUT, 15);
		if ($this->proxy) {
			curl_setopt($process, CURLOPT_PROXY, $this->proxy);
		}
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);

		if (is_array($curl_options)) {
			foreach ($curl_options as $key => $value) {
				curl_setopt($process, $key, $value);
			}
		}
		$return =  curl_exec($process);
		curl_close($process);
		return $return;
	}

	function post($url, $data, $curl_options = array())
	{
		$process = curl_init($url);
		
		if (false) {
		$headers = array();
		$headers[] = 'Accept: text/html, image/jpeg, image/jpg, image/png, text/xml';
		$headers[] = 'Connection: Keep-Alive';
		$headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8';
		$agent = $_SERVER['HTTP_USER_AGENT'];
		}

		curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
		//curl_setopt($process, CURLOPT_HTTPHEADER, $this->headers);
		curl_setopt($process, CURLOPT_HEADER, 1);
		//curl_setopt($process, CURLOPT_USERAGENT, $this->agent);
		curl_setopt($process, CURLOPT_USERAGENT, $this->agent);
		/*if ($this->cookies == true) { // it should be testing
			curl_setopt($process, CURLOPT_COOKIEFILE, $this->cookie_file);
		}
		if ($this->cookies == true) {
			curl_setopt($process, CURLOPT_COOKIEJAR, $this->cookie_file);
		}*/
		if ($this->cookies == true) {
			curl_setopt($process, CURLOPT_COOKIEFILE, $_SERVER['HTTP_COOKIE']);
		} 
		if ($this->cookies == true) {
			curl_setopt($process, CURLOPT_COOKIEJAR, $_SERVER['HTTP_COOKIE']);
		}
	
		curl_setopt($process, CURLOPT_ENCODING, $this->compression);
		curl_setopt($process, CURLOPT_TIMEOUT, 150000);
		
		if ($this->proxy) {
			curl_setopt($process, CURLOPT_PROXY, $this->proxy);
		}
		curl_setopt($process, CURLOPT_POSTFIELDS, $data);
		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($process, CURLOPT_POST, 1);
		if (is_array($curl_options)) {
			foreach ($curl_options as $key => $value) {
				curl_setopt($process, $key, $value);
			}
		}
		$return = curl_exec($process);
		//$infos = curl_getinfo($return);
		curl_close($process);
		return $return;		
	}
}

?>
