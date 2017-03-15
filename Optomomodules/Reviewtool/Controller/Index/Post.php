<?php
 /**
   * [Subject] Optoma Customized Modules :: Reviewtool
   *
   * [Author] Lawrence Lin (ddlawrence303@gmail.com)
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
use \Optomamodules\Reviewtool\Model\ReviewFactory;
use \Magento\Framework\Mail\Template\TransportBuilder;

class Post extends \Magento\Contact\Controller\Index
{

/*	protected $customerSession;
	protected $formKeyValidator;
	protected $dateTime;
	protected $reviewFactory;		
	protected $transportBuilder;
	protected $inlineTranslation;
	protected $scopeConfig;
	protected $storeManager;
	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
		\Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
		\Magento\Framework\Stdlib\DateTime $dateTime,
	){
		$this->transportBuilder = $transportBuilder;
		$this->inlineTranslation = $inlineTranslation;
		$this->scopeConfig = $scopeConfig;
		$this->storeManager = $storeManager;
		$this->formKeyValidator = $formKeyValidator;
		$this->dateTime = $dateTime;
		$this->reviewFactory = $reviewFactory;
		parent::__construct($context);
	} */


	/**
	  * exec post user question
	  * @return void
	  * @throws \Exception
	  */
	public function execute()
	{ 

		$post = $this->getRequest()->getPostValue();
		if (!$post) {
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
	
 			/** 
                          * setting custome param 
			  * add new elements : product_name & product_sku information	
			  */
			if (array_key_exists('product_name', $post) && array_key_exists('product_sku', $post)) {
				if (!\Zend_Validate::is(trim($post['product_name']), 'NotEmpty')) {
					$error = true;
				}

			 	if (!\Zend_Validate::is(trim($post['product_sku']), 'NotEmpty')) {
					$error = true;
				}	
			}
			
			}
		
			//testing data
//		DebugLib::lolo($this->getRequest()->getPostValue(), 'Post Value GET');		
//			DebugLib::lolo($this->getRequest()->getParam('name'), 'name');				
//		DebugLib::lolo($this->getRequest()->getParam('email'), 'email');				

	    $tmp = array(
		'reviewer_id' => rand(8000, 9000),
		'review_title' => 'HHHHHHHTESTing',
		'review_text' => 'Product testing lalaalalla',
		'review_rating' => 4,
		'review_archived' => 1,
		'review_culture' => 'en',
		'reviewDate' => date('Y-m-d H:i:s'),
		'moderated' => 1,
		'review_product' => 's316',
		'review_serial' => 'Q8ZF430AAAAAC0965',
		'purchase_place' => 'optoma testing',
		'severity' => 5,
		'created_at' => date('Y-m-d H:i:s'),
		'status' => 'low'
		);

		//object create
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$review = $objectManager->create('Optomamodules\Reviewtool\Model\Review');
//		DebugLib::lolo($review->debug(), 'REVIEW MODEL'); 

	    $review->setData('reviewer_id', $tmp['reviewer_id']);
	    $review->setData('review_title', $tmp['review_title']);
	    $review->setData('review_text', $tmp['review_text']);
	    $review->setData('review_rating', $tmp['review_rating']);
	    $review->setData('review_archived', $tmp['review_archived']);
	    $review->setData('review_culture', $tmp['review_culture']);
	    $review->setData('reviewDate', $tmp['reviewDate']);
	    $review->setData('moderated', $tmp['moderated']);
	    $review->setData('review_product', $tmp['review_product']);
	    $review->setData('review_serial', $tmp['review_serial']);
	    $review->setData('purchase_place', $tmp['purchase_place']);
	    $review->setData('severity', $tmp['severity']);
	    $review->setData('created_at', $tmp['created_at']);
	    $review->setData('status', $tmp['status']);
	
//	DebugLib::lolo($review->toArray(), 'REview infos');
		$review->save();

			if ($error) {
				throw new \Exception(); //todo
			}

			if (false) {
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
			}



			$this->messageManager->addSuccess(
				__('Hi there, this is Optoma, and thanks for your contacting with us about your questions by nice information, and we will notify you very soon, see you next time~')
			);
		 
			//LogLib::log(str_replace( "\\" , "_" , get_class($this)), json_encode($post));
                        /* redirect to new page :: pending */	
			
			//$this->getResponse()->setRedirect('contactinfo/sales_support');
			//$this->_redirect('contactinfo/index');
			//$this->_redirect('contact/index');
			$this->_redirect('*/*/');	
			return;
		} catch (\Exception $e) {
			//setting log
			LogLib::log(str_replace( "\\", "_", get_class($this)), $e->getMessage());
			$this->inlineTranslation->resume();
			$this->messageManager->addError(
				__('Hi there, this is Optoma, so sorry for that we just cant\'t process your request right now, please wait a minutes and we will contact you very soon~')
			);
			$this->_redirect('contact/index');//todo 
			return;
		}
	}
}
