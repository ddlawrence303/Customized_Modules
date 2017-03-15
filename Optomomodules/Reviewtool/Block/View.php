<?php
namespace Optomamodules\Reviewtool\Block;

use \Optomamodules\Libsinfo\Controller\Index\DebugLib as DebugLib;
use \Optomamodules\Libsinfo\Controller\Index\LogLib as LogLib;

class View extends \Magento\Catalog\Block\Product\AbstractProduct
{

	protected $_template = 'view.phtml';

	protected $_voteFactory;
	
	protected $_ratingFactory;

	protected $_reviewFactory;


	public function __construct(
          \Magento\Catalog\Block\Product\Context $context,
          \Magento\Review\Model\Rating\Option\VoteFactory $voteFactory,
          \Magento\Review\Model\RatingFactory $ratingFactory,
          \Magento\Review\Model\ReviewFactory $reviewFactory,
          array $data = []
      ) {
          $this->_voteFactory = $voteFactory;
          $this->_reviewFactory = $reviewFactory;
          $this->_ratingFactory = $ratingFactory;
 
          parent::__construct(
              $context,
              $data
          );
      }
 		

	public function getProductData()
	{
		return $this->_coreRegistry->registry('current_product');
	}

	public function getReviewData()
	{
		return $this->_coreRegistry->registry('current_review');
	}


	public function getBackUrl()
	{
		return $this->getUrl('*/*/list', ['id' => $this->geProductData()->getId()]);
	}

	//get the rating collection
	
	public function getRating()
	{
		if (!$this->getRatingCollection()) {
			$ratingCollection = $this->_voteFactory->create()->getResourceCollection->setReviewFilter(
				$this->getReviewId()
			)->setStoreFilter(
				$this->_storeManager->getStore()->getId()
			)->addRatingInfo(
				$this->_storeManager->getStore()->getId()
			)->load();
			$this->setRatingCollection($ratingCollection->getSize() ? $ratingCollection : false );
		}
		return $this->getRatingCollection();
	}

	public function getRatingSummary()
     {
         if (!$this->getRatingSummaryCache()) {
             $this->setRatingSummaryCache(
                 $this->_ratingFactory->create()->getEntitySummary($this->getProductData()->getId())
             );
         }
         return $this->getRatingSummaryCache();
     }

	public function getTotalReviews()
	{
		//connectino to DB ??
		
		 if (!$this->getTotalReviewsCache()) {
             $this->setTotalReviewsCache(
                 $this->_reviewFactory->create()->getTotalReviews(
                     $this->getProductData()->getId(),
                     false,
                     $this->_storeManager->getStore()->getId()
                 )
             );
         }
         return $this->getTotalReviewsCache();
	}

	 public function getReviewsSummaryHtml(
         \Magento\Catalog\Model\Product $product,
         $templateType = false,
         $displayIfNoReviews = false
     ) {
         if (!$product->getRatingSummary()) {
             $this->_reviewFactory->create()->getEntitySummary($product, $this->_storeManager->getStore()->getId());
         }
         return parent::getReviewsSummaryHtml($product, $templateType, $displayIfNoReviews);
     }

}
