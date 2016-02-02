# Customized_Modules
Optoma Customized Modules :: Contactinfo
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

Welcome to Optoma's New World ~~

