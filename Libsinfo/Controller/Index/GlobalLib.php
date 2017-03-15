<?php

// this is for testing
namespace Optomamodules\Libsinfo\Controller\Index;

use Optomamodules\Libsinfo\Controller\Index\HttpLib as HttpLib;
use Optomamodules\Libsinfo\Controller\Index\ConfigLib as ConfigLib;
use Optomamodules\Libsinfo\Controller\Index\SecurityLib as SecurityLib;

class GlobalLib extends \Optomamodules\Libsinfo\Controller\Index
{
	//setting and checking
	private static $_country_datas = array(
		//HQ
		'tw' => array(
			'code' => 'tw',
			'online' => false,
			'text' => array(),
			'currency' => 'NT$',
			'in_service' => false,
			'ga_id' => ''
		),
		// Apac		
		'roa' => array(
			'code' => 'apac',
			'online' => false,
			'text' => array(),
			'currency' => '$',
			'in_service' => false,
			'ga_id' => ''
		),	
		//OPAM
		'usa' => array(
			'code' => 'usa',
			'online' => false,
			'text' => array(),
			'currency' => '$',
			'in_service' => false,
			'ga_id' => ''
		),

		//EMEA
		'uk' => array(
			//for Nick
		),	
	);

	public static function getOnlineCountryDatas()
	{
		$online_country_datas = self::$_country_datas;
		foreach ($online_country_datas as $key => $value) {
			if (!$value['online']) {
				unset($online_country_datas[$key]);
			}
		}
		return $online_country_datas;		
	}

	public static function getCountryDataByCode($code)
	{
		return self::$_country_datas[$code] ?: self::$_country_datas['tw'];
	}
	
	public function __construct(){}
	public function execute() {}
}


