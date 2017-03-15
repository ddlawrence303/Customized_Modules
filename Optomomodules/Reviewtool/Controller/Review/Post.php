<?php
 /**
   * [Subject] Optoma Customized Modules :: Contactinfo
   *
   * [Usage]
   * 1. exec cli : php bin/magento module:enable <Vendor_Name>_<Module_Name>, such as  Optomamodules_Contactinfo, and check module status
   * 2. override the Post action in phtml file <Vendor_Name>_<Optomo_Theme>/templates/<modify_file>.phtml,
        which will change the post target position, such as {$root_url}/<Customized modules name>/<index entry point>/post(contactinfo/index/post) to get params
   * 3. Setting service email and contact admin users' email in Admin Interface(Magento), which is for sure about the {$sender}, {$email} and related information.
   *
   * [Create Time] 2016.02.02
   */

namespace Optomamodules\Reviewtool\Controller\Index;

use \Optomamodules\Libsinfo\Controller\Index\ConfigLib as ConfigLib;
use \Optomamodules\Libsinfo\Controller\Index\LogLib as LogLib;
use \Optomamodules\Libsinfo\Controller\Index\DebugLib as DebugLib;
use \Optomamodules\Libsinfo\Controller\Index\DBConnLib as DBConnLib;
use \Optomamodules\Libsinfo\Controller\Index\SQLLib as SQLLib;
use \Optomamodules\Libsinfo\Controller\Index\JSonLib as JSonLib;

class Post extends \Magento\Contact\Controller\Index
{
	/**
	  * exec post user question
	  * @return void
	  * @throws \Exception
	  */
	public function execute()
	{ 
		
		//DebugLib::lolo($this->getRequest()->getPostValue(), 'Post data is heree');
		//CRUD action 
		//var_dump($this->getRequest()->getPostValue()); echo 'REview UUUU testing OOOOOO';exit;
		$post = $this->getRequest()->getPostValue();
		if (!$post) {
		//if (true) {
			//$this->_redirectReferer();
			//$this->_redirect('projection/distance-calculator');
			$this->_redirect('*/*/');
			return;
		}
		$this->inlineTranslation->suspend();
		try {
			$postObject = new \Magento\Framework\DataObject();
			$postObject->setData($post);
		
			$error = false;

		
			if (false) {
			/* validate-checking */
			if (!\Zend_Validate::is(trim($post['name']), 'NotEmpty')) {
				$error = true;
			}
			
			if (!\Zend_Validate::is(trim($post['comment']), 'NotEmpty')) {
				$error = true;
			}

			if (!\Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
				$error = true;
			}
			}
			

			if ($error) {
				throw new \Exception(); //todo
			}


			
			//DML mysql DB start =================================================
			try {
			

		

	if (false) {
	$_mysql = DBConnLib::getConnection('mysql', array('mysql_user' => 'admin', 'database' => 'opam_site_new'));
			//DebugLib::lolo($_mysql, ' Mysql Connection Condiftion ');
			$sql_list = SQLLib::getSqlstatement('owner_review', 'insert');
			
			//DebugLib::lolo($sql_list, ' Review sql ');

			// temp tables
	/*		id : int
ReviewerId  9999
ReviewTitle
ReviewText
Rating (type in)
Archived  0
ReviewCulture  (mage code ??)   en 
ReviewDate (date(Ymd H:i:s))
Moderated  0
ReviewProduct  post data!! (block->getname)
ReviewSerial  :: post data
PurchasePlace */ 

$temp = array(
'ReviewerId' => rand(8000, 9000),
'ReviewTitle' => 'Review Testing JJJJJJJJJ',
'ReviewText' => 'I am reviewing hahahaahha',
'Rating' => 5,
'Archived' => 0,
'ReviewCulture' => 'en',
'ReviewData' => date('Y-m-d H:i:s'),
'Moderated' => 0,
'ReviewProduct' => $post['gname'], // testing
'ReviewSerial' => $post['serial_number'], 
'PurchasePlace' => $post['purchase_from'],
'email' => 'lawrence.lin@optoma.com'
);

$post = $temp; //setting default data

			//DebugLib::lolo($post, 'POST DATA INFOS');

			$review_sql = sprintf($sql_list, 
				rand(9000, 10000),// should be get the data
				$post['ReviewerId'], 
				$post['ReviewTitle'],
				$post['ReviewText'],
				$post['Rating'],
				$post['Archived'],
				$post['ReviewCulture'],
				$post['ReviewData'],
				$post['Moderated'],
				$post['ReviewProduct'],
				$post['ReviewSerial'],
				$post['PurchasePlace']
			);

			//DebugLib::lolo($review_sql, ' Review SQL statement');
		
			$result = $_mysql->query($review_sql); //exec

	}			
			//DebugLib::lolo($result, ' Review SQL result'); 

			} catch (\Exception $e) {
			//	DebugLib::lolo($e->getMessage(), 'Mysql DB ERror');
				echo 'Mysql DB Error ';exit;
			}
				

			//echo '================= DB DML processing ==================';exit;
			//DML mysql DB start =================================================

			/* Transport email to user */
			$storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
			$transport = $this->_transportBuilder
				->setTemplateIdentifier($this->scopeConfig->getValue(self::XML_PATH_EMAIL_TEMPLATE, $storeScope))
				->setTemplateOptions(
					[
						'area' => \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE,
						'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
					]
				)
				->setTemplateVars(['data' => $postObject])
				->setFrom($this->scopeConfig->getValue(self::XML_PATH_EMAIL_SENDER, $storeScope))
				->addTo($this->scopeConfig->getValue(self::XML_PATH_EMAIL_RECIPIENT, $storeScope))
				->setReplyTo($post['email'])
				->getTransport();
			
			$transport->sendMessage();
			$this->inlineTranslation->resume();


			$this->messageManager->addSuccess(
				__('Hi there, this is Optoma, and thanks for your contacting with us about your questions by nice information, and we will notify you very soon, see you next time~')
			);
		 
			//LogLib::log(str_replace( "\\" , "_" , get_class($this)), json_encode($post));
                        /* redirect to new page :: pending */	
			
			//$this->getResponse()->setRedirect('contactinfo/sales_support');
			//DebugLib::lolo($url->toString(), 'response url');echo 'GGGG';exit;
	
			//return;
			// this is for controller
			//$this->_redirect('contactinfo/index');
			//$this->_redirect('catalog/product');
			$this->_redirect('./'. $post['ReviewProduct']);
			return;
		} catch (\Exception $e) {
			var_dump($e->getMessage());
			//setting log
			LogLib::log(str_replace( "\\", "_", get_class($this)), $e->getMessage());
			$this->inlineTranslation->resume();
			$this->messageManager->addError(
				__('Hi there, this is Optoma, so sorry for that we just cant\'t process your request right now, please wait a minutes and we will contact you very soon~')
			);
			//$this->_redirect('catalog/product');//todo 
			$this->_redirect('./'. $post['ReviewProduct']);
			return;
		}
	}
}
