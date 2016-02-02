<?php
 /**
   * [Subject] Optoma Customized Modules :: Contactinfo
   *
   * [Usage]
   * 1. exec cli : php bin/magento module:enable <Vendor_Name>_<Module_Name>, such as  Optomamodules_Contactinfo, and check module status
   * 2. override the Post action in phtml file <Vendor_Name>_<Optomo_Theme>/templates/<modify_file>.phtml,
        which will change the post target position, such as {$root_url}/<Customized modules name>/<index entry point>/post(contactinfo/index/post) to get params
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

/* setting customized namespace */
namespace Optomamodules\Contactinfo\Controller;

use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\ScopeInterface;

abstract class Index extends \Magento\Framework\App\Action\Action
{

        /* recipient email config path */
        const XML_PATH_EMAIL_RECIPIENT = 'contact/email/recipient_email';

        /* sender email config path */
        const XML_PATH_EMAIL_SENDER = 'contact/email/sender_email_identity';

        /* email template config path */
        const XML_PATH_EMAIL_TEMPLATE = 'contact/email/email_template';

        /*enable config path */
        const XML_PATH_ENABLED = 'contact/contact/enabled';

        /* @var \Magento\FFramework\Mail\Template\TransportBuilder */
        protected $_transportBuilder;

        /* @var \Magento\Framework\Translate\Inline\StateInterface */
        protected $inlineTranslation;

         const XML_PATH_ENABLED = 'contact/contact/enabled';

        /* @var \Magento\FFramework\Mail\Template\TransportBuilder */
        protected $_transportBuilder;

        /* @var \Magento\Framework\Translate\Inline\StateInterface */
        protected $inlineTranslation;

        /* @var \Magento\Framework\App\Config\ScopeConfigInterface */
        protected $scopeConfig;

        /* @var \Magento\Store\Model\StoreManagerInterface */
        protected $storeManagel_email_templater;

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
        ) {
                parent::__construct($context);
                $this->_transportBuilder = $transportBuilder;
                $this->inlineTranslation = $inlineTranslation;
                $this->scopeConfig = $scopeConfig;
                $this->storeManager = $storeManager;
        }

        /**
          *@param RequestInterface $request
          *@return \Magento\Framework\App\ResponseInterface
          *@throws \Magento\Framework\Exception\NotFoundException
          */
        public function despatch(RequestInterface $request)
        {
                if (!$this->scopeConfig->isSetFlat(self::XML_PATH_ENABLED, ScopeInterface::SCOPE_STORE)) {
                        throw new NotFoundException(__('Page not found.'));
                }
                return parent::dispatch($request);
        }
}

