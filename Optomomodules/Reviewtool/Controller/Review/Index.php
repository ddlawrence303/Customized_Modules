<?php
namespace Optomamodules\Reviewtool\Controller\Review;

class Index extends \Optomamodules\Reviewtool\Controller\Review
{
    public function execute()
    {
        $resultPage = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
        return $resultPage;
    }
}
