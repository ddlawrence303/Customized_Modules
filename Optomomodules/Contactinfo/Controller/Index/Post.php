<?php
  2  /**
  3    * [Subject] Optoma Customized Modules :: Contactinfo
  4    *
  5    * [Usage]
  6    * 1. exec cli : php bin/magento module:enable <Vendor_Name>_<Module_Name>, such as  Optomamodules_Contactinfo, and check module status
  7    * 2. override the Post action in phtml file <Vendor_Name>_<Optomo_Theme>/templates/<modify_file>.phtml,
  8         which will change the post target position, such as {$root_url}/<Customized modules name>/<index entry point>/post(contactinfo/index/post) to get params
  9    * 3. Setting service email and contact admin users' email in Admin Interface(Magento), which is for sure about the {$sender}, {$email} and related information.
 10    *
 11    * [User Guide Reference]
 12    * http://devdocs.magento.com/guides/v2.0/frontend-dev-guide/templates/template-email.html#customize-email-templates
 13    *
 14    * [Testing] Contact Template Content need to be prepared
 15    * This module is just for staging testing, and you can give me some feedback if any questions during your testing.
 16    *
 17    * [Author] Lawrence Lin (ddlawrence303@gmail.com)
 18    *
 19    * [Create Time] 2016.02.02
 20    */
 21
 22 namespace Optomamodules\Contactinfo\Controller\Index;
 23
 24 class Post extends \Magento\Contact\Controller\Index
 25 {
 26         /**
 27           * exec post user question
 28           * @return void
 29           * @throws \Exception
 30           */
 31         public function execute()
 32         {
 33                 $post = $this->getRequest()->getPostValue();
 34                 if (!$post) {
 35                         $this->_redirect('*/*/');
 36                         return;
 37                 }
 38                 $this->inlineTranslation->suspend();
 39                 try {
 40                         $postObject = new \Magento\Framework\DataObject();
 41                         $postObject->setData($post);
 42
 43                         $error = false;
 44
 45                         /* validate-checking */
 46                         if (!\Zend_Validate::is(trim($post['name']), 'NotEmpty')) {
 47                                 $error = true;
 48                         }
                          
                            if (!\Zend_Validate::is(trim($post['comment']), 'NotEmpty')) {
 51                                 $error = true;
 52                         }
 53
 54                         if (!\Zend_Validate::is(trim($post['email']), 'EmailAddress')) {
 55                                 $error = true;
 56                         }
 57
 58                         /**
 59                           * setting custome param
 60                           * add new elements : product_name & product_sku information
 61                           */
 62                         if (array_key_exists('product_name', $post) && array_key_exists('product_sku', $post)) {
 63                                 if (!\Zend_Validate::is(trim($post['product_name']), 'NotEmpty')) {
 64                                         $error = true;
 65                                 }
 66
 67                                 if (!\Zend_Validate::is(trim($post['product_sku']), 'NotEmpty')) {
 68                                         $error = true;
 69                                 }
 70                         }
 71
 72                         /* this column, hideit, is not so sure for using during this process, so I close it temporarily....
 73                         if (!\Zend_Validate::is(trim($post['hideit']), 'NotEmpty')) {
 74                                 $error = true;
 75                         }*/
 76
 77                         if ($error) {
 78                                 throw new \Exception(); //todo
 79                         }
 80
 81                         /* Transport email to user */
 82                         $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
 83                         $transport = $this->_transportBuilder
 84                                 ->setTemplateIdentifier($this->scopeConfig->getValue(self::XML_PATH_EMAIL_TEMPLATE, $storeScope))
 85                                 ->setTemplateOptions(
 86                                         [
 87                                                 'area' => \Magento\Backend\App\Area\FrontNameResolver::AREA_CODE,
 88                                                 'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
 89                                         ]
 90                                 )
 91                                 ->setTemplateVars(['data' => $postObject])
 92                                 ->setFrom($this->scopeConfig->getValue(self::XML_PATH_EMAIL_SENDER, $storeScope))
 93                                 ->addTo($this->scopeConfig->getValue(self::XML_PATH_EMAIL_RECIPIENT, $storeScope))
 94                                 ->setReplyTo($post['email'])
 95                                 ->getTransport();
 96
                            $transport->sendMessage();
 98                         $this->inlineTranslation->resume();
 99                         $this->messageManager->addSuccess(
100                                 __('Hi there, this is Optoma, and thanks for your contacting with us about your questions by nice information, and we will notify you very     soon, see you next time~')
101                         );
102
103                         /* redirect to new page :: pending */
104                         $this->_redirect('contact/index');
105                         return;
106                 } catch (\Exception $e) {
107                         /* Error Log should be noted here */
108                         $this->inlineTranslation->resume();
109                         $this->messageManager->addError(
110                                 __('Hi there, this is Optoma, so sorry for that we just cant\'t process your request right now, please wait a minutes and we will contact y    ou very soon~')
111                         );
112                         $this->_redirect('contact/index');//todo
113                         return;
114                 }
115         }
116 }
