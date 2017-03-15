<?php
namespace Optomamodules\Libsinfo\Controller\Index;

use Optomamodules\Libsinfo\Controller\Index\ConfigLib as ConfigLib;
use Optomamodules\Libsinfo\Controller\Index\LogLib as LogLib;
use Optomamodules\Libsinfo\Controller\Index\DebugLib as DebugLib;

class Post extends \Magento\Contact\Controller\Index
//class Post extends \Optomamodules\Libsinfo\Controller\Index
{
	/**
	  * exec post user question
	  * @return void
	  * @throws \Exception
	  */
	//public function __construct(){}
	public function execute()
	{	
		try {
		DebugLib::lolo($this->getRequest()->getPostValue, 'distance post data');
		$post = $this->getRequest()->getPostValue();
		if (!$post) {
			$this->_redirect('*/*/');
			return;
		}
		
		return array(
			'code' => 'OK',
			'msg' => json_encode($post)
		);
			
		} catch (\Exception $e) {
			//setting log
			LogLib::lob(str_replace( "\\", "_", get_class($this)), $e->getMessage());
			$this->messageManager->addError(
				__('Hi there, this is Optoma, so sorry for that we just cant\'t process your request right now, please wait a minutes and we will contact you very soon~')
			);
			$this->_redirect('libsinfo/index');//todo 
			return;
		}
	}
}
