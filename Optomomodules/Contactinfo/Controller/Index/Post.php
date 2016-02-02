<?php
  /**
    * [Subject] Optoma Customized Modules :: Contactinfo
    *
    * [Usage]
    * 1. exec cli : php bin/magento module:enable <Vendor_Name>_<Module_Name>, such as  Optomamodules_Contactinfo, and check module status
    * 2. override the Post action in phtml file <Vendor_Name>_<Optomo_Theme>/templates/<modify_file>.phtml,which will change the post target position, such as {$root_url}/<Customized modules name>/<index entry point>/post(contactinfo/index/post) to get params
    * 3. Setting service email and contact admin users' email in Admin Interface(Magento), which is for sure about the {$sender}, {$email} and related information.
    *
    * [User Guide Reference]
  * http://devdocs.magento.com/guides/v2.0/frontend-dev-guide/templates/template-email.html#customize-email-templates
  *
   * [Testing] Contact Template Content need to be prepared
    * This module is just for staging testing, and you can give me some feedback if any questions during your testing.
    *
   * [Author] Lawrence Lin (ddlawrence303@gmail.com)
    *
    * [Create Time] 2016.02.02
   */

namespace Optomamodules\Contactinfo\Controller\Index;

class Post extends \Magento\Contact\Controller\Index
 {
         /**
          * exec post user question
          * @return void
         * @throws \Exception
           */
         public function execute()
       {
                 $post = $this->getRequest()->getPostValue();
                 if (!$post) {
                        $this->_redirect('*/*/');
                        return;
                  }
                $this->inlineTranslation->suspend();
                 try {
                        $postObject = new \Magento\Framework\DataObject();
                        $postObject->setData($post);

                       $error = false;

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

                         /* this column, hideit, is not so sure for using during this process, so I close it temporarily....
                         if (!\Zend_Validate::is(trim($post['hideit']), 'NotEmpty')) {
                                 $error = true;
                         }*/

                        if ($error) {
                                throw new \Exception(); //todo
                         }

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
                        $this->messageManager->addSuccess(
                                 __('Hi there, this is Optoma, and thanks for your contacting with us about your questions by nice information, and we will notify you very     soon, see you next time~')
                        );

                        /* redirect to new page :: pending */
                        $this->_redirect('contact/index');
                        return;
                } catch (\Exception $e) {
                         /* Error Log should be noted here */
                         $this->inlineTranslation->resume();
                         $this->messageManager->addError(
                                __('Hi there, this is Optoma, so sorry for that we just cant\'t process your request right now, please wait a minutes and we will contact y    ou very soon~')
                        );
                        $this->_redirect('contact/index');//todo
                        return;
                }
         }
 }
