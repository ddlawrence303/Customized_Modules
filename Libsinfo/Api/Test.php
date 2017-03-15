<?php
 /**
   * [Subject] Optoma Customized Modules :: Libsinfo
   *
   * [Usage]
   * 1. exec cli : php bin/magento module:enable <Vendor_Name>_<Module_Name>, such as  Optomamodules_Libsinfo, and check module status
   * 2. override the Post action in phtml file <Vendor_Name>_<Optomo_Theme>/templates/<modify_file>.phtml,
        which will change the post target position, such as {$root_url}/<Customized modules name>/<index entry point>/post(libsinfo/index/post) to get params
   *
   * [Testing] Contact Template Content need to be prepared
   * This module is just for staging testing, and you can give me some feedback if any questions during your testing.
   *
   * [Author] Lawrence Lin (ddlawrence303@gmail.com)
   *
   * [Create Time] 2016.02.02
   */

namespace Optomamodules\Libsinfo\Api;

//class Index extends \Optomamodules\Libsinfo\Controller\Index
//testing
//class Libs extends \Magento\Contact\Controller\Index
class Test 
{
	public static function lolo()
	{
		$res = json_encode($_POST);
		return $res ;
	
	}

	public static function getGG()
	{
		$res = json_encode($_GET);
		return $res;
	}	

	public static function test()
	{
		return 'I love you';
	}
	
	/* exec entry point and redner content */
	public function execute()
	{
		//$this->_view->loadLayout();
		//$this->_view->renderLayout();
		echo 'my name is lala'; return;
        }
}
