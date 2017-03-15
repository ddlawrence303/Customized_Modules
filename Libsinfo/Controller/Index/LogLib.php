<?php

namespace Optomamodules\Libsinfo\Controller\Index;

class LogLib extends \Optomamodules\Libsinfo\Controller\Index
{
	public static $warning_sign = '(!) ';
	public static $warning_sign_regexp = '#\(!\)\s\s#';

	//testing
	const LOGS_PATH = '';
	
	public function __construct(){}
	public function execute(){}
	public static function getLogPath()
	{
		if (false) {
		if (!defined(LOGS_PATH)) {
			define(LOGS_PATH, $_SERVER['DOCUMENT_ROOT'] . '/logs');
		}
		return LOGS_PATH;
		}
		return $_SERVER['DOCUMENT_ROOT'] . '/logs';
	}

	public static function logException($exception)
	{
		$log = sprintf("[%s] %s: \"%s\" in file: %s(%s)\n%s\n",
			date('Y-m-d H:i:s'),
			get_class($exception),
			$exception->getMessage(),
			$exception->getFile(),
			$exception->getLine(),
			$exception->getTraceAsString()
		);
		return $log;
		//self::log('exception', $log, true);
	}

	public static function logDangerous($message)
	{
		self::log('dangerous', $message, true);
	}

	public static function logBark($message)
	{
		self::log('watchdog_bark', $message, true);
	}
	
	public static function log($name, $message, $send_warning_notification = false)
	{
		$file_dir = self::getLogPath() . '/' . $name;
		if (!file_exists($file_dir)) {
			mkdir($file_dir);
			chmod($file_dir, 0777);
			//setting hipchat
		}
		$file_path = $file_dir . '/' . date('Y-m-d') . '.log';
		if (!file_exists($file_path)) {
			touch($file_path);
			chmod($file_path, 0666);
		}	
		$message = sprintf('[%s] %s%s', date('Y-m-d H:i:s'), $send_warning_notification ? self::$warning_sign : '' , $message);
		file_put_contents($file_path, $message . "\n", FILE_APPEND);
		if ($send_warning_notification) {
			//MessageLib::sendEmail();
			//todo
		}
	}	

	public static function test($message)
	{
		self::log('test', $message);
	}
}

?>
