<?php
namespace Optomamodules\Reviewtool\Block;

use Magento\Catalog\Model\Product;
use Magento\Review\Model\ResourceModel\Ratin\Collection as RatingCollection; //??
use Magento\Customer\Model\Context;

use \Optomamodules\Libsinfo\Controller\Index\DebugLib as DebugLib;
use \Optomamodules\Libsinfo\Controller\Index\LogLib as LogLib;

class Form extends \Magento\Framework\View\Element\Template
{

	protected $_reviewData = null;

	protected $productRepository;

	protected $_ratingFactory;

	protected $urlEncoder;

	protected $messageManager;

	protected $httpContext;

	protected $customerUrl;

	protected $jsLayout;

	public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Review\Helper\Data $reviewData,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Review\Model\RatingFactory $ratingFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\Http\Context $httpContext,
        \Magento\Customer\Model\Url $customerUrl,
        array $data = []
    ) {
        $this->urlEncoder = $urlEncoder;
        $this->_reviewData = $reviewData;
        $this->productRepository = $productRepository;
        $this->_ratingFactory = $ratingFactory;
        $this->messageManager = $messageManager;
        $this->httpContext = $httpContext;
        $this->customerUrl = $customerUrl;
        parent::__construct($context, $data);
        $this->jsLayout = isset($data['jsLayout']) ? $data['jsLayout'] : [];
    }


	protected function _construct()
    {
        parent::_construct();

        $this->setAllowWriteReviewFlag(
            $this->httpContext->getValue(Context::CONTEXT_AUTH)
            || $this->_reviewData->getIsGuestAllowToWrite()
        );
        if (!$this->getAllowWriteReviewFlag()) {
            $queryParam = $this->urlEncoder->encode(
                $this->getUrl('*/*/*', ['_current' => true]) . '#review-form'
            );
            $this->setLoginLink(
                $this->getUrl(
                    'customer/account/login/',
                    [Url::REFERER_QUERY_PARAM_NAME => $queryParam]
                )
            );
        }

        $this->setTemplate('form.phtml');
    }


	public function getJsLayout()
	{
		return \Zend_Json::encode($this->jsLayout);
	}


	public function getProductInfo()
	{
		//get the product instance
		return $this->productRepository->getById(
			$this->getProductId(),
			false,
			$this->_storeManager->getStore()->getId()
		);
	}

	//get reviewinfo product poast action
	public function getAction()
	{
		return $this->getUrl(
			'reviewinfo/product/post',
			[
				'_secure' => $this->getRequest()->isSecure(),
				'id' => $this->getProductId(),
			]
		);
	}


	public function getRatings()
    {
        return $this->_ratingFactory->create()->getResourceCollection()->addEntityFilter(
            'product'
        )->setPositionOrder()->addRatingPerStoreName(
            $this->_storeManager->getStore()->getId()
        )->setStoreFilter(
            $this->_storeManager->getStore()->getId()
        )->setActiveFilter(
            true
        )->load()->addOptionToItems();
    }


	public function getRegisterUrl()
	{
		//DebugLib::lolo(__FUNCTION__, 'FUNCTION');
		return null;
	}


	protected function getProductId()
	{
		return $this->getRequest()->getParam('id', false);
	}

}


