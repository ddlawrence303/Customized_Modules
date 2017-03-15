<?php
// Contactinfo Block
namespace Optomamodules\Reviewtool\Block;

use Magento\Framework\View\Element\Template as Template;

/* main contact form block */
class ReviewForm extends Template
{

	/* 
	@param template\Context $context
	@param array $data
	*/
	public function __construct(Template\Context $context, array $data = [])
	{
		parent::__construct($context, $data);
		$this->_isScopePrivate = true;
	}

	/* returns action url for contact form */
	public function getFormAction($arg = '')
	{
		$target = '';
		switch ($arg) {
			case 'sales_support':
		
			case 'parts_and_accessories':

			case 'customer_service':

			case 'product_review':
				$target = '/optomamodules_reviewtool/index/post';
				//$target = '/reviewinfo/index/post';
			break;

			default:
				$target = $this->getUrl('reviewtool/index/post', ['_secure' => true]);			
			break;
		}	
		return $target;
		//return $this->getUrl('contactinfo/index/post', ['_secure' => true]);
	}	
}
