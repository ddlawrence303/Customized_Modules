<?php
 /**
   * [Subject] Optoma Customized Modules :: Reviewtool
   *
   * [Author] Lawrence Lin (ddlawrence303@gmail.com)
   *
   * [Create Time] 2016.02.02
   */

/* setting customized namespace */
namespace Optomamodules\Reviewtool\Controller; 

use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\ScopeInterface;

abstract class Index extends \Magento\Framework\App\Action\Action
{
	
	/* recipient email config path */
//	const XML_PATH_EMAIL_RECIPIENT = 'contact/email/recipient_email';
	
	/* sender email config path */
//	const XML_PATH_EMAIL_SENDER = 'contact/email/sender_email_identity';

	/* email template config path */
//	const XML_PATH_EMAIL_TEMPLATE = 'contact/email/email_template';

	/*enable config path */
//	const XML_PATH_ENABLED = 'contact/contact/enabled';

	/* @var \Magento\FFramework\Mail\Template\TransportBuilder */
	protected $_transportBuilder;

	/* @var \Magento\Framework\Translate\Inline\StateInterface */
	protected $inlineTranslation;

	/* @var \Magento\Framework\App\Config\ScopeConfigInterface */
	protected $scopeConfig;

	/* @var \Magento\Store\Model\StoreManagerInterface */
	protected $storeManagel_email_templater;


	protected $customerSession;
	/**
	  * @param \Magento\Framework\App\Action\Context $context
	  * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
	  * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
	  * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
	  * @param \Magento\Store\Model\StoreManagerInterface $storeManager
	  */	
	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
		\Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
		\Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
		\Magento\Store\Model\StoreManagerInterface $storeManager
	//	\Magento\Customer\Model\Session $customerSession	
	) {
		parent::__construct($context);
		$this->_transportBuilder = $transportBuilder;
		$this->inlineTranslation = $inlineTranslation;
		$this->scopeConfig = $scopeConfig;
		$this->storeManager = $storeManager;
	//	$this->customerSession = $customerSession;
	}

	/**
	  *@param RequestInterface $request
	  *@return \Magento\Framework\App\ResponseInterface
	  *@throws \Magento\Framework\Exception\NotFoundException
	  */
	public function dispatch(\Magento\Framework\App\RequestInterface $request)
	{ 
		if (!$this->scopeConfig->isSetFlat(self::XML_PATH_ENABLED, ScopeInterface::SCOPE_STORE)) {
			throw new NotFoundException(__('Page not found.'));
		}
		return parent::dispatch($request);
	}	
}

