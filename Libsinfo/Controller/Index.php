<?php
 /**
   * [Subject] Optoma Customized Modules :: Libsinfo
   *
   * [Author] Lawrence Lin (ddlawrence303@gmail.com)
   *
   * [Create Time] 2016.02.02
   */

/* setting customized namespace */
namespace Optomamodules\Libsinfo\Controller; 

use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\ScopeInterface;

abstract class Index extends \Magento\Framework\App\Action\Action
{
		//@var \Magento\Framework\View\Result\Page
	protected $resultPageFactory;

	protected $_transportBuilder;
	protected $inlineTranslation;
	protected $scopeConfig;
	protected $storeManager;	

	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		//\Magento\Framework\View\Result\PageFactory $resultPageFactory
		\Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
		\Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
		 \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
                \Magento\Store\Model\StoreManagerInterface $storeManager	
	)
	{
		 parent::__construct($context);
                $this->_transportBuilder = $transportBuilder;
                $this->inlineTranslation = $inlineTranslation;
                $this->scopeConfig = $scopeConfig;
                $this->storeManager = $storeManager;
		
		//$this->resultPageFactory = $resultPageFactory;
		//parent::__construct($context);
	}

	/**
	  *@param RequestInterface $request
	  *@return \Magento\Framework\App\ResponseInterface
	  *@throws \Magento\Framework\Exception\NotFoundException
	  */
	public function despatch(\Magento\Framework\App\RequestInterface $request)
	{
		if (!$this->scopeConfig->isSetFlat(self::XML_PATH_ENABLED, ScopeInterface::SCOPE_STORE)) {
			throw new NotFoundException(__('Page not found.'));
		}
		return parent::dispatch($request);
	}	
}

