<?php
namespace Optomamodules\Reviewtool\Controller\Review;

use \Optomamodules\Libsinfo\Controller\Index\DebugLib as DebugLib;
use \Optomamodules\Libsinfo\Controller\Index\ConfigLib as ConfigLib;
use \Optomamodules\Libsinfo\Controller\Index\LogLib as LogLib;

class Save extends \Optomamodules\Reviewtool\Controller\Review
{
    protected $transportBuilder;
    protected $inlineTranslation;
    protected $scopeConfig;
    protected $storeManager;
    protected $formKeyValidator;
    protected $dateTime;
    protected $reviewFactory;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Customer\Model\Session $customerSession,
	\Magento\Framework\View\Result\PageFactory $resultPageFactory,		
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Data\Form\FormKey\Validator $formKeyValidator,
        \Magento\Framework\Stdlib\DateTime $dateTime,
        \Optomamodules\Reviewtool\Model\ReviewFactory $reviewFactory
    )
    {
        $this->transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->formKeyValidator = $formKeyValidator;
        $this->dateTime = $dateTime;
        $this->reviewFactory = $reviewFactory;
        //$this->ticketFactory = $ticketFactory;
        parent::__construct($context, $customerSession, $resultPageFactory);
    }

    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        if (!$this->formKeyValidator->validate($this->getRequest())) {
            return $resultRedirect->setRefererUrl();
        }

//	DebugLib::lolo($this->getRequest()->getPostValue());
	 	
//	DebugLib::lolo($this->getRequest()->getPostValue(), 'Post Value Get');


        $title = $this->getRequest()->getParam('review_title');
        $review_product = $this->getRequest()->getParam('review_product');
        $review_rating = $this->getRequest()->getParam('review_rating');


//	DebugLib::lolo($title, 'title');
//	DebugLib::lolo($review_product, 'prodcut');
//	DebugLib::lolo($review_rating , 'rating');
	
	if (true) {

        try {
            /* Save review */
            $review = $this->reviewFactory->create();
	    //DebugLib::lolo($review->debug(), 'Review');//exit;


	    //testing data
	    $tmp = array(
		'reviewer_id' => rand(4000, 5000),
		'review_title' => $title,
		'review_text' => 'Product testing',
		'review_rating' => $review_rating,
		'review_archived' => 1,
		'review_culture' => 'en',
		'reviewDate' => date('Y-m-d H:i:s'),
		'moderated' => 1,
		'review_product' => $review_product,
		'review_serial' => 'Q8ZF430AAAAAC0965',
		'purchase_place' => 'optoma',
		'severity' => 5,
		'created_at' => date('Y-m-d H:i:s'),
		'status' => 'low'
		);
	
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
	
//	    DebugLib::lolo($review->toArray(), 'Review');
		$review->save();

	
//	    DebugLib::lolo($review->debug(), 'Review');


//		exit;
	    if (false) {	
            $review->setCustomerId($this->customerSession->getCustomerId());
            $review->setTitle($title);
            $review->setSeverity($severity);
            $review->setCreatedAt($this->dateTime->formatDate(true));
            $review->setStatus(\Optomamodules\Reviewtool\Model\Ticket::STATUS_OPENED);
            $review->save();
	    }

            $customer = $this->customerSession->getCustomerData();

	   if (false) {//testing to user 
            /* Send email to store owner */
            $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
            $transport = $this->transportBuilder
                ->setTemplateIdentifier($this->scopeConfig->getValue('optomamodules_reviewtool/email_template/store_owner', $storeScope))
                ->setTemplateOptions(
                    [
                        'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                        'store' => $this->storeManager->getStore()->getId(),
                    ]
                )
                ->setTemplateVars(['ticket' => $ticket])
                ->setFrom([
                    'name' => $customer->getFirstname() . ' ' . $customer->getLastname(),
                    'email' => $customer->getEmail()
                ])
                ->addTo($this->scopeConfig->getValue('trans_email/ident_general/email', $storeScope))
                ->getTransport();

            $transport->sendMessage();
            $this->inlineTranslation->resume();
		}
	
		//sending message 
            $this->messageManager->addSuccess(__('Review information successfully created.'));
			$this->_redirect('./'. $post['ReviewProduct']);
        } catch (Exception $e) {
            $this->messageManager->addError(__('Error occurred during Review creation.'));
        }
	}
        return $resultRedirect->setRefererUrl();
    }
}

