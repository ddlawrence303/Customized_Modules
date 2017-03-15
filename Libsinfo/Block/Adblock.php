<?php
// Contactinfo Block
namespace Optomamodules\Libsinfo\Block;

use Magento\Framework\View\Element\Template as Template;
use \Optomamodules\Libsinfo\Controller\Index\ImageLib as ImageLib;
use \Optomamodules\Libsinfo\Controller\Index\ConfigLib as ConfigLib;
use \Optomamodules\Libsinfo\Controller\Index\LogLib as LogLib;
use \Optomamodules\Libsinfo\Controller\Index\DBConnLib as DBConnLib;
use \Optomamodules\Libsinfo\Controller\Index\SQLLib as SQLLib;
use \Optomamodules\Libsinfo\Controller\Index\DebugLib as DebugLib;


/* main contact form block */
class Adblock extends Template
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
				//$target = '';break;
		
			case 'parts_and_accessories':
				//$target = '';break;

			case 'customer_service':
				//$target = '';break;

			case 'product_review':
//				$target = '/reviewinfo/index/post';
			break;

			default:
				$target = $this->getUrl('contactinfo/index/post', ['_secure' => true]);			
			break;
		}	
		return $target;
		//return $this->getUrl('contactinfo/index/post', ['_secure' => true]);
	}


	public function getParseData($datas = null)
	{
		$tmp = $datas->toArray();

		$result = array();
		foreach ($tmp['items'] as $key => $value) {
			if (2 == intval($value['status'])) {
			$result[ 'review_id_' . $value['review_id'] ] = $value;
			//$result[ $key ] = $value;
			}
		}
		unset($tmp);
		return (null == $result) ?: $result;
	}
	
	public function getReviewList()
	{  
		
		try {
		$temp = null;
		//connection
		$_mysql = DBConnLib::getConnection('mysql', array('mysql_user' => 'admin', 'database' => 'opam_site_new'));	
		$review_sql = SQLLib::getSqlstatement('owner_review', 'get');	

		$owner_review_result = $_mysql->query(SQLLib::getSqlstatement('owner_review', 'get'));
		$owner_review_list = array();

while ($row = mysqli_fetch_assoc($owner_review_result)) {
        $owner_review_list[ $row['id'] ] = $row;

        $product_url = ConfigLib::getUrn() . '/' . ($row['ReviewProduct']);
        $owner_review_list[ $row['id']]['product_url'] = $product_url;
        //slice
        $str_limit = 600;
        if ($str_limit < strlen($row['ReviewText'])) {
                //substr
                $str = substr( $row['ReviewText'], 0, $str_limit) . '......';
                $owner_review_list[ $row['id']]['ReviewText'] = $str;
        } else {
                $owner_review_list[ $row['id']]['ReviewText'] =  $row['ReviewText'];
        }
}

		$temp = $owner_review_list; //setting data
		return (null == $temp) ?: $temp;
		} catch (\Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
}
