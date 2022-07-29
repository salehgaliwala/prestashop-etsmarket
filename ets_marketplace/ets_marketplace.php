<?php
/**
 * 2007-2020 ETS-Soft
 *
 * NOTICE OF LICENSE
 *
 * This file is not open source! Each license that you purchased is only available for 1 wesite only.
 * If you want to use this file on more websites (or projects), you need to purchase additional licenses. 
 * You are not allowed to redistribute, resell, lease, license, sub-license or offer our resources to any third party.
 * 
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please contact us for extra customization service at an affordable price
 *
 *  @author ETS-Soft <etssoft.jsc@gmail.com>
 *  @copyright  2007-2020 ETS-Soft
 *  @license    Valid for 1 website (or project) for each purchase of license
 *  International Registered Trademark & Property of ETS-Soft
 */

 /* _ARM_ Add Weight class */
require_once _PS_MODULE_DIR_.'sba_batch/classes/Weight.php';

if(!defined('_PS_VERSION_'))
	exit;
require_once(dirname(__FILE__) . '/classes/Ets_mp_paggination_class.php');
require_once(dirname(__FILE__) . '/classes/seller.php');
require_once(dirname(__FILE__) . '/classes/billing.php');
require_once(dirname(__FILE__) . '/classes/registration.php');
require_once(dirname(__FILE__) . '/classes/commission.php');
require_once(dirname(__FILE__) . '/classes/Ets_mp_paymentmethod.php');
require_once(dirname(__FILE__) . '/classes/Ets_mp_paymentmethodfield.php');
require_once(dirname(__FILE__) . '/classes/Commission_Usage.php');
require_once(dirname(__FILE__) . '/classes/Ets_mp_withdraw.php');
require_once(dirname(__FILE__) . '/classes/Ets_mp_withdraw_field.php');
require_once(dirname(__FILE__) . '/classes/HTMLTemplateBillingPdf.php');
require_once(dirname(__FILE__) . '/classes/manager.php');
require_once(dirname(__FILE__) . '/classes/report.php');
require_once(dirname(__FILE__) . '/classes/group.php');
require_once(dirname(__FILE__) . '/classes/contact.php');
require_once(dirname(__FILE__) . '/classes/contact_message.php');
require_once(dirname(__FILE__) . '/classes/Ets_mp_email.php');
if(!class_exists('Ets_mp_defines'))
    require_once(dirname(__FILE__) . '/classes/Ets_mp_defines.php');
if (!defined('_PS_ETS_MARKETPLACE_UPLOAD_DIR_')) {
    define('_PS_ETS_MARKETPLACE_UPLOAD_DIR_', _PS_UPLOAD_DIR_.'ets_marketplace/');
}
if (!defined('_PS_ETS_MARKETPLACE_UPLOAD_')) {
    define('_PS_ETS_MARKETPLACE_UPLOAD_', __PS_BASE_URI__.'upload/ets_marketplace/');
}
class Ets_marketplace extends PaymentModule
{ 
    public $is17 = false;
    public $is15 = false;
    public $_errors = array();
    public $_path_module;
    public $_use_feature;
    public $_use_attribute;
    public $_hooks = array(
        'displayBackOfficeHeader',
        'displayHome',
        'displayCustomerAccount',
        'displayMyAccountBlock',
        'actionValidateOrder',
        'displayHeader',
        'displayFooter',
        'displayMPLeftContent',
        'actionOrderStatusUpdate',
        'paymentOptions',
        'payment',
        'paymentReturn',
        'displayCartExtraProductActions',
        'displayProductPriceBlock',
        'actionProductUpdate',
        'actionProductDelete',
        'displayETSMPFooterYourAccount',
        'displayProductAdditionalInfo',
        'displayRightColumnProduct',
        'displayFooterProduct',
        'actionObjectLanguageAddAfter',
        'actionObjectCustomerDeleteAfter',
        'moduleRoutes',
        'displayShoppingCartFooter',
        'actionObjectOrderDetailUpdateAfter',
        'actionObjectOrderDetailAddAfter',
        'actionObjectOrderDetailDeleteAfter',
        'displayProductListReviews',
        'displayPDFInvoice',
        'displayAdminProductsSeller',
    );
    public $file_types = array('jpg', 'gif', 'jpeg', 'png','doc','docs','docx','pdf','zip','txt');
    public function __construct()
	{
        $this->name = 'ets_marketplace';
		$this->tab = 'front_office_features';
		$this->version = '3.1.4';
		$this->author = 'ETS-Soft';
		$this->need_instance = 0;
		$this->secure_key = Tools::encrypt($this->name);
		$this->bootstrap = true;
        if(version_compare(_PS_VERSION_, '1.7', '>='))
            $this->is17 = true; 
        if (version_compare(_PS_VERSION_, '1.6', '<'))
            $this->is15 = true;
        $this->ps_versions_compliancy = array('min' => '1.6.0.0', 'max' => _PS_VERSION_);
		parent::__construct();
        $this->displayName =$this->l('Marketplace Builder');
        $this->description = $this->l('Turn PrestaShop into marketplace with simple set up steps, #1 PrestaShop Marketplace module (multi vendor) that allows sellers to list their products for sale and pay a percentage fee amount for each sale or a membership fee');
        $this->_path_module = $this->_path;
        if(Configuration::get('ETS_MP_SELLER_USER_GLOBAL_FEATURE') || Configuration::get('ETS_MP_SELLER_CREATE_FEATURE'))
            $this->_use_feature = true;
        else
            $this->_use_feature = false;
        if(Configuration::get('ETS_MP_SELLER_CREATE_PRODUCT_ATTRIBUTE') && ( Configuration::get('ETS_MP_SELLER_CREATE_ATTRIBUTE') || Configuration::get('ETS_MP_SELLER_USER_GLOBAL_ATTRIBUTE')))
            $this->_use_attribute = true;
        else
            $this->_use_attribute = false;
        $recaptcha = Tools::getValue('g-recaptcha-response') ? Tools::getValue('g-recaptcha-response') : '';
        $secret = Configuration::get('ETS_MP_ENABLE_CAPTCHA_TYPE')=='google_v2' ? Configuration::get('ETS_MP_ENABLE_CAPTCHA_SECRET_KEY2') : Configuration::get('ETS_MP_ENABLE_CAPTCHA_SECRET_KEY3');
        $this->link_capcha="https://www.google.com/recaptcha/api/siteverify?secret=" . $secret . "&response=" . $recaptcha . "&remoteip=" . Tools::getRemoteAddr();
    }
    public function createIndexDataBase()
    {
        $sqls = array();
        if(Db::getInstance()->executeS("SHOW INDEX FROM "._DB_PREFIX_."ets_mp_commission_usage WHERE KEY_NAME = 'index_commission_usage'"))
            $sqls[] = 'ALTER TABLE '._DB_PREFIX_.'ets_mp_commission_usage DROP INDEX index_commission_usage';
        $sqls[] = 'ALTER TABLE '._DB_PREFIX_.'ets_mp_commission_usage ADD UNIQUE `index_commission_usage` (`id_ets_mp_commission_usage`,`id_customer`,`id_shop`,`id_voucher`,`id_withdraw`,`id_order`,`id_currency`,`status`,`deleted`)';
        if(Db::getInstance()->executeS("SHOW INDEX FROM "._DB_PREFIX_."ets_mp_payment_method WHERE KEY_NAME = 'index_payment_method'"))
            $sqls[] = 'ALTER TABLE '._DB_PREFIX_.'ets_mp_payment_method DROP INDEX index_payment_method';
        $sqls[] = 'ALTER TABLE '._DB_PREFIX_.'ets_mp_payment_method ADD UNIQUE `index_payment_method` (`id_ets_mp_payment_method`,`id_shop`,`enable`,`deleted`,`sort`)';
        if(Db::getInstance()->executeS("SHOW INDEX FROM "._DB_PREFIX_."ets_mp_payment_method_field WHERE KEY_NAME = 'index_payment_method_field'"))
            $sqls[] = 'ALTER TABLE '._DB_PREFIX_.'ets_mp_payment_method_field DROP INDEX index_payment_method_field';
        $sqls[] = 'ALTER TABLE '._DB_PREFIX_.'ets_mp_payment_method_field ADD UNIQUE `index_payment_method_field` (`id_ets_mp_payment_method_field`,`id_ets_mp_payment_method`,`sort`,`required`,`enable`,`deleted`)';
        if(Db::getInstance()->executeS("SHOW INDEX FROM "._DB_PREFIX_."ets_mp_registration WHERE KEY_NAME = 'index_registration'"))
            $sqls[] = 'ALTER TABLE '._DB_PREFIX_.'ets_mp_registration DROP INDEX index_registration';
        $sqls[] = 'ALTER TABLE '._DB_PREFIX_.'ets_mp_registration ADD UNIQUE `index_registration` (`id_registration`,`id_customer`,`id_shop`,`active`)';
        if(Db::getInstance()->executeS("SHOW INDEX FROM "._DB_PREFIX_."ets_mp_seller WHERE KEY_NAME = 'index_seller'"))
            $sqls[] = 'ALTER TABLE '._DB_PREFIX_.'ets_mp_seller DROP INDEX index_seller';
        $sqls[] = 'ALTER TABLE '._DB_PREFIX_.'ets_mp_seller ADD UNIQUE `index_seller` (`id_seller`,`id_customer`,`id_shop`,`active`,`payment_verify`,`user_shipping`,`mail_expired`,`mail_going_to_be_expired`,`mail_payed`,`mail_wait_pay`)';
        if(Db::getInstance()->executeS("SHOW INDEX FROM "._DB_PREFIX_."ets_mp_seller_billing WHERE KEY_NAME = 'index_seller_billing'"))
            $sqls[] = 'ALTER TABLE '._DB_PREFIX_.'ets_mp_seller_billing DROP INDEX index_seller_billing';
        $sqls[] = 'ALTER TABLE '._DB_PREFIX_.'ets_mp_seller_billing ADD UNIQUE `index_seller_billing` (`id_ets_mp_seller_billing`,`id_customer`,`id_shop`,`active`,`used`,`id_employee`)';
        $sqls[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ .'ets_mp_seller_carrier`';
        if(Db::getInstance()->executeS("SHOW INDEX FROM "._DB_PREFIX_."ets_mp_seller_commission WHERE KEY_NAME = 'index_seller_commission'"))
            $sqls[] = 'ALTER TABLE '._DB_PREFIX_.'ets_mp_seller_commission DROP INDEX index_seller_commission';
        $sqls[] = 'ALTER TABLE '._DB_PREFIX_.'ets_mp_seller_commission ADD UNIQUE `index_seller_commission` (`id_seller_commission`,`id_customer`,`id_order`,`id_product`,`id_shop`,`id_product_attribute`,`status`)';
        if(Db::getInstance()->executeS("SHOW INDEX FROM "._DB_PREFIX_."ets_mp_seller_contact WHERE KEY_NAME = 'index_seller_contact'"))
            $sqls[] = 'ALTER TABLE '._DB_PREFIX_.'ets_mp_seller_contact DROP INDEX index_seller_contact';
        $sqls[] = 'ALTER TABLE '._DB_PREFIX_.'ets_mp_seller_contact ADD UNIQUE `index_seller_contact` (`id_contact`,`id_customer`,`id_seller`,`id_product`,`id_order`)';
        if(Db::getInstance()->executeS("SHOW INDEX FROM "._DB_PREFIX_."ets_mp_seller_contact_message WHERE KEY_NAME = 'index_seller_contact_message'"))
            $sqls[] = 'ALTER TABLE '._DB_PREFIX_.'ets_mp_seller_contact_message DROP INDEX  index_seller_contact_message';
        $sqls[] = 'ALTER TABLE '._DB_PREFIX_.'ets_mp_seller_contact_message ADD UNIQUE `index_seller_contact_message` (`id_message`,`id_contact`,`id_customer`,`id_seller`,`id_employee`,`read`,`customer_read`)';
        if(Db::getInstance()->executeS("SHOW INDEX FROM "._DB_PREFIX_."ets_mp_seller_product WHERE KEY_NAME = 'index_seller_product'"))
            $sqls[] = 'ALTER TABLE '._DB_PREFIX_.'ets_mp_seller_product DROP INDEX index_seller_product';
        $sqls[] = 'ALTER TABLE '._DB_PREFIX_.'ets_mp_seller_product ADD UNIQUE `index_seller_product` (`id_customer`,`id_product`,`approved`,`active`)';
        if(Db::getInstance()->executeS("SHOW INDEX FROM "._DB_PREFIX_."ets_mp_withdrawal WHERE KEY_NAME = 'index_withdrawal'"))
            $sqls[] = 'ALTER TABLE '._DB_PREFIX_.'ets_mp_withdrawal DROP INDEX index_withdrawal';
        $sqls[] = 'ALTER TABLE '._DB_PREFIX_.'ets_mp_withdrawal ADD UNIQUE `index_withdrawal` (`id_ets_mp_withdrawal`,`id_ets_mp_payment_method`,`status`)';
        if(Db::getInstance()->executeS("SHOW INDEX FROM "._DB_PREFIX_."ets_mp_withdrawal_field WHERE KEY_NAME = 'index_withdrawal_field'"))
            $sqls[] = 'ALTER TABLE '._DB_PREFIX_.'ets_mp_withdrawal_field DROP INDEX index_withdrawal_field';
        $sqls[] = 'ALTER TABLE '._DB_PREFIX_.'ets_mp_withdrawal_field ADD UNIQUE `index_withdrawal_field` (`id_ets_mp_withdrawal_field`,`id_ets_mp_withdrawal`,`id_ets_mp_payment_method_field`)';
        foreach($sqls as $sql)
            Db::getInstance()->execute($sql);
        return true;
    }
    public function _registerHooks()
    {
        if($this->_hooks)
        {
            foreach($this->_hooks as $hook)
            {
                $this->registerHook($hook);
            }
        }
        return true;
    }
    public function install()
	{
	    return parent::install()
        && $this->_installDb() 
        && $this->_registerHooks()
        && $this->_installDbDefault() 
        && $this->_installTabs() 
        && $this->createTemplateMail()
        && $this->installLinkDefault() && $this->createIndexDataBase()&&$this->_installOverried();
    }
    public function createTemplateMail()
    {
        $languages= Language::getLanguages(false);
        foreach($languages as $language)
        {
            $this->copy_directory(dirname(__FILE__).'/mails/en', dirname(__FILE__).'/mails/'.$language['iso_code']);
        }
        return true;
    }
    public function copy_directory($src, $dst)
    {
        $dir = opendir($src);
        if(!file_exists($dst))
            @mkdir($dst);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($src . '/' . $file)) {
                    $this->copy_directory($src . '/' . $file, $dst . '/' . $file);
                } elseif(!file_exists($dst . '/' . $file)) {
                    copy($src . '/' . $file, $dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }
    public function delete_template_overried($directory)
    {
        $dir = opendir($directory);
        while (false !== ($file = readdir($dir))) {
            if (($file != '.') && ($file != '..')) {
                if (is_dir($directory . '/' . $file)) {
                    $this->delete_template_overried($directory . '/' . $file);
                } else {
                    if (file_exists($directory . '/' . $file) && $file != 'index.php' && ($content = Tools::file_get_contents($directory . '/' . $file)) && Tools::strpos($content, 'overried by chung_ets_marketplace') !== false) {
                        @unlink($directory . '/' . $file);
                        if (file_exists($directory . '/backup_' . $file))
                            copy($directory . '/backup_' . $file, $directory . '/' . $file);
                    }

                }
            }
        }
        closedir($dir);
    }
    public function _installOverried()
    {
        $this->copy_directory(dirname(__FILE__) . '/views/templates/admin/_configure/templates', _PS_OVERRIDE_DIR_ . 'controllers/admin/templates');
        return true;
    }
    public function _unInstallOverried()
    {
        $this->delete_template_overried(_PS_OVERRIDE_DIR_ . 'controllers/admin/templates');
        return true;
    }
    public function _installTabs()
    {
        $languages = Language::getLanguages(false);
        if(!Tab::getIdFromClassName('AdminMarketPlace'))
        {
            $tab = new Tab();
            $tab->class_name = 'AdminMarketPlace';
            $tab->module = $this->name;
            $tab->id_parent = 0;            
            foreach($languages as $lang){
                $tab->name[$lang['id_lang']] = $this->getTextLang('Market place',$lang) ? : $this->l('Market place');
            }
            $tab->save();
        }
        $tabId = Tab::getIdFromClassName('AdminMarketPlace');
        if($tabId)
        {
            $subTabs = array(
                array(
                    'class_name' =>'AdminMarketPlaceDashboard',
                    'tab_name' => $this->l('Dashboard'),
                    'tabname' => 'Dashboard',
                    'icon'=>'icon icon-dashboard',
                ),
                array(
                    'class_name' => 'AdminMarketPlaceOrders',
                    'tab_name' => $this->l('Orders'),
                    'tabname' => 'Orders',
                    'icon'=>'icon icon-orders',
                ),
                array(
                    'class_name' => 'AdminMarketPlaceProducts',
                    'tab_name' => $this->l('Products'),
                    'tabname' => 'Products',
                    'icon'=>'icon icon-products',
                ),
                array(
                    'class_name' => 'AdminMarketPlaceRatings',
                    'tab_name' => $this->l('Ratings'),
                    'tabname' => 'Ratings',
                    'icon'=>'icon icon-ratings',
                ),
                array(
                    'class_name' => 'AdminMarketPlaceCommissions',
                    'tab_name' => $this->l('Commissions'),
                    'tabname' => 'Commissions',
                    'icon'=>'icon icon-commission',
                ),
                array(
                    'class_name' => 'AdminMarketPlaceBillings',
                    'tab_name' => $this->l('Membership'),
                    'tabname' => 'Membership',
                    'icon'=>'icon icon-billing', 
                ),
                array(
                    'class_name' => 'AdminMarketPlaceWithdrawals',
                    'tab_name' => $this->l('Withdrawals'),
                    'tabname' => 'Withdrawals',
                    'icon'=>'icon icon-withdraw',
                ),
                array(
                    'class_name' => 'AdminMarketPlaceRegistrations',
                    'tab_name' => $this->l('Applications'),
                    'tabname' => 'Applications',
                    'icon'=>'icon icon-sellers_registration',
                ),
                array(
                    'class_name' => 'AdminMarketPlaceShopSellers',
                    'tab_name' => $this->l('Shops'),
                    'tabname' => 'Shops',
                    'icon'=>'icon icon-sellers',
                    'subs' => array(
                        'AdminMarketPlaceSellers' => array(
                            'tab_name' => $this->l('Shops'),
                            'tabname' => 'Shops',
                            'class_name'=> 'AdminMarketPlaceSellers',
                            'icon' => 'icon icon-sellers', 
                        ),
                        'AdminMarketPlaceShopGroups' => array(
                            'tab_name' => $this->l('Shop groups'),
                            'tabname' => 'Shop groups',
                            'class_name'=> 'AdminMarketPlaceShopGroups',
                            'icon' => 'icon icon-group',
                        ),
                        'AdminMarketPlaceReport' => array(
                            'tab_name' => $this->l('Reports'),
                            'tabname' => 'Reports',
                            'class_name' => 'AdminMarketPlaceReport',
                            'icon' => 'icon icon-report',
                        )
                        
                    ),
                ),
                array(
                    'class_name' => 'AdminMarketPlaceSettings',
                    'tab_name' => $this->l('Settings'),
                    'tabname' => 'Settings',
                    'icon'=>'icon icon-settings',
                    'subs' => array(
                         array(
                            'class_name' => 'AdminMarketPlaceSettingsGeneral',
                            'tab_name' => $this->l('General'),
                            'tabname' => 'General',
                            'icon'=>'icon icon-settings',
                        ),   
                        array(
                            'class_name' => 'AdminMarketPlaceCommissionsUsage',
                            'tab_name' => $this->l('Commissions'),
                            'tabname' => 'Commissions',
                            'icon'=>'icon icon-commissions-usage',
                        ),
                        array(
                            'class_name' => 'AdminMarketPlaceCronJob',
                            'tab_name' => $this->l('Cronjob'),
                            'tabname' => 'Cronjob',
                            'icon'=>'icon icon-Cronjob',
                        )
                    )
                ),
            );
            foreach($subTabs as $tabArg)
            {
                if(!Tab::getIdFromClassName($tabArg['class_name']))
                {
                    $tab = new Tab();
                    $tab->class_name = $tabArg['class_name'];
                    $tab->module = $this->name;
                    $tab->id_parent = $tabId; 
                    $tab->icon=$tabArg['icon'];           
                    foreach($languages as $lang){
                        $tab->name[$lang['id_lang']] = $this->getTextLang($tabArg['tabname'],$lang)?: $tabArg['tab_name'];
                    }
                    $tab->save();
                    if(isset($tabArg['subs']) && $tabArg['subs'])
                    {
                        foreach($tabArg['subs'] as $sub)
                        {
                            $subtab = new Tab();
                            $subtab->class_name = $sub['class_name'];
                            $subtab->module = $this->name;
                            $subtab->id_parent = $tab->id; 
                            $subtab->icon=$sub['icon'];           
                            foreach($languages as $lang){
                                $subtab->name[$lang['id_lang']] = $this->getTextLang($sub['tabname'],$lang)?: $sub['tab_name'];
                            }
                            $subtab->save();
                        }
                    }
                }elseif($tab_id = Tab::getIdFromClassName($tabArg['class_name']) && isset($tabArg['subs']) && $tabArg['subs'])
                {
                    foreach($tabArg['subs'] as $sub)
                    {
                        if(!Tab::getIdFromClassName($sub['class_name']))
                        {
                            $subtab = new Tab();
                            $subtab->class_name = $sub['class_name'];
                            $subtab->module = $this->name;
                            $subtab->id_parent = $tab_id; 
                            $subtab->icon=$sub['icon'];           
                            foreach($languages as $lang){
                                $subtab->name[$lang['id_lang']] = $this->getTextLang($sub['tabname'],$lang)?:$sub['tab_name'];
                            }
                            $subtab->save();
                        }
                        
                    }
                }
            }                
        }            
        return true;
    }
    public function setMetas()
    {
        $meta = array();
        if(trim(Tools::getValue('module'))== $this->name && Tools::getValue('controller')=='shop')
        {
            if($id_seller=(int)Tools::getValue('id_seller'))
            {
                $seller = new Ets_mp_seller($id_seller,$this->context->language->id);
                $meta['meta_title'] = $seller->shop_name ?: $seller->seller_name;
                $meta['description'] = Tools::strlen(strip_tags($seller->shop_description)) <=256 ? strip_tags($seller->shop_description) : Tools::substr(strip_tags($seller->shop_description),0,Tools::strpos(strip_tags($seller->shop_description)," ",255));
            }
            else
            {
                $meta['meta_title'] = Configuration::get('ETS_MP_SHOP_META_TITLE',$this->context->language->id) ? : $this->l('Shops');
                $meta_description = Configuration::get('ETS_MP_SHOP_META_DESCRIPTION',$this->context->language->id);
                $meta['description'] = Tools::strlen(strip_tags($meta_description)) <=256 ? strip_tags($meta_description) : Tools::substr(strip_tags($meta_description),0,Tools::strpos(strip_tags($meta_description)," ",255));
            }
            if($this->is17)
            {
                $body_classes = array(
                    'lang-'.$this->context->language->iso_code => true,
                    'lang-rtl' => (bool) $this->context->language->is_rtl,
                    'country-'.$this->context->country->iso_code => true,                              
                );
                $page = array(
                    'title' => '',
                    'canonical' => '',
                    'meta' => array(
                        'title' => isset($meta['meta_title'])? $meta['meta_title'] :'',
                        'description' => isset($meta['description']) ? $meta['description'] :'',
                        'keywords' => isset($meta['keywords']) ? $meta['keywords'] :'',
                        'robots' => 'index',
                    ),
                    'page_name' => '',
                    'body_classes' => $body_classes,
                    'admin_notifications' => array(),
                ); 
                $this->context->smarty->assign(array('page' => $page)); 
            }    
            else
            {
                $this->context->smarty->assign($meta);
            }   
        }        
    }
    public function installLinkDefault()
    {
        $metas= array(
            array(
                'controller' => 'dashboard',
                'title' => $this->l('Dashboard'),
                'tabname' => 'Dashboard',
                'url_rewrite' => 'seller-dashboard',
                'url_rewrite_lang' =>$this->l('seller-dashboard'),
            ),
            array(
                'controller' => 'orders',
                'title' => $this->l('Orders'),
                'tabname' => 'Orders',
                'url_rewrite' => 'seller-orders',
                'url_rewrite_lang' =>$this->l('seller-orders'),
            ),
            array(
                'controller' => 'products',
                'title' => $this->l('Products'),
                'tabname' => 'Products',
                'url_rewrite' => 'seller-products',
                'url_rewrite_lang' =>$this->l('seller-products'),
            ),
            array(
                'controller' => 'commissions',
                'title' => $this->l('Commissions'),
                'tabname' => 'Commissions',
                'url_rewrite' => 'seller-commissions',
                'url_rewrite_lang' =>$this->l('seller-commissions'),
            ),
            array(
                'controller' => 'billing',
                'title' => $this->l('Membership'),
                'tabname' => 'Membership',
                'url_rewrite' => 'seller-membership-invoices',
                'url_rewrite_lang' =>$this->l('seller-membership-invoices'),
            ),
            array(
                'controller' => 'withdraw',
                'title' => $this->l('Withdrawals'),
                'tabname' => 'Withdrawals',
                'url_rewrite' => 'seller-withdrawals',
                'url_rewrite_lang' =>$this->l('seller-withdrawals'),
            ),
            array(
                'controller' => 'voucher',
                'title' => $this->l('My vouchers'),
                'tabname' => 'My vouchers',
                'url_rewrite' => 'seller-vouchers',
                'url_rewrite_lang' =>$this->l('seller-vouchers'),
            ),
            array(
                'controller' => 'attributes',
                'title' => $this->l('Attributes'),
                'tabname' => 'Attributes',
                'url_rewrite' => 'seller-attributes',
                'url_rewrite_lang' =>$this->l('seller-attributes'),
            ),
            array(
                'controller' => 'features',
                'title' => $this->l('Features'),
                'tabname' => 'Features',
                'url_rewrite' => 'seller-features',
                'url_rewrite_lang' =>$this->l('seller-features'),
            ),
            array(
                'controller' => 'discount',
                'title' => $this->l('Discounts'),
                'tabname' => 'Discounts',
                'url_rewrite' => 'seller-discounts',
                'url_rewrite_lang' =>$this->l('seller-discounts'),
            ),
            array(
                'controller' => 'messages',
                'title' => $this->l('Messages'),
                'tabname' => 'Messages',
                'url_rewrite' => 'seller-messages',
                'url_rewrite_lang' =>$this->l('seller-messages'),
            ),
            array(
                'controller' => 'profile',
                'title' => $this->l('Profile'),
                'tabname' => 'Profile',
                'url_rewrite' => 'seller-profile',
                'url_rewrite_lang' =>$this->l('seller-profile'),
            ),
            array(
                'controller' => 'create',
                'title' => $this->l('Create'),
                'tabname' => 'Create',
                'url_rewrite' => 'seller-create-shop',
                'url_rewrite_lang' =>$this->l('seller-create-shop'),
            ),
            array(
                'controller' => 'registration',
                'title' => $this->l('Application'),
                'tabname' => 'Application',
                'url_rewrite' => 'seller-application',
                'url_rewrite_lang' =>$this->l('seller-application'),
            ),
            array(
                'controller' => 'myseller',
                'title' => $this->l('Seller account'),
                'tabname' => 'Seller account',
                'url_rewrite' => 'seller-account',
                'url_rewrite_lang' =>$this->l('seller-account'),
            ),
            array(
                'controller' => 'brands',
                'title' => $this->l('Brands'),
                'tabname' => 'Brands',
                'url_rewrite' => 'seller-brands',
                'url_rewrite_lang' =>$this->l('seller-brands'),
            ),
            array(
                'controller' => 'suppliers',
                'title' => $this->l('Suppliers'),
                'tabname' => 'Suppliers',
                'url_rewrite' => 'seller-suppliers',
                'url_rewrite_lang' =>$this->l('seller-suppliers'),
            ),
            array(
                'controller' => 'import',
                'title' => $this->l('Import products'),
                'tabname' => 'Import products',
                'url_rewrite' => 'seller-import-products',
                'url_rewrite_lang' =>$this->l('seller-import-products'),
            ),
            array(
                'controller' => 'contactseller',
                'title' => $this->l('Contact shop'),
                'tabname' => 'Contact shop',
                'url_rewrite' => 'seller-contact',
                'url_rewrite_lang' =>$this->l('seller-contact'),
            ),
            array(
                'controller' => 'carrier',
                'title' => $this->l('Carriers'),
                'tabname' => 'Carrier',
                'url_rewrite' => 'seller-carrier',
                'url_rewrite_lang' =>$this->l('seller-carrier'),
            ),
            array(
                'controller' => 'manager',
                'title' => $this->l('Shop managers'),
                'tabname' => 'Shop managers',
                'url_rewrite' => 'seller-manager',
                'url_rewrite_lang' =>$this->l('seller-manager'),
            ),
            array(
                'controller' => 'map',
                'title' => $this->l('Store locations'),
                'tabname' => 'Store locations',
                'url_rewrite' => 'store-locations',
                'url_rewrite_lang' =>$this->l('store-locations'),
            ),
            array(
                'controller' => 'ratings',
                'title' => $this->l('Ratings'),
                'tabname' => 'Ratings',
                'url_rewrite' => 'seller-product-ratings',
                'url_rewrite_lang' =>$this->l('seller-product-ratings'),
            ),
            array(
                'controller' => 'stock',
                'title' => $this->l('Stock'),
                'tabname' => 'stock',
                'url_rewrite' => 'seller-product-stock',
                'url_rewrite_lang' =>$this->l('seller-product-stock'),
            )
        );
        $languages = Language::getLanguages(false);
        foreach($metas as $meta)
        {
            if(!Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'meta_lang` WHERE url_rewrite ="'.pSQL($meta['url_rewrite']).'"') && !Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'meta` WHERE page ="module-'.pSQL($this->name).'-'.pSQL($meta['controller']).'"'))
            {
                $meta_class = new Meta();
                $meta_class->page = 'module-'.$this->name.'-'.$meta['controller'];
                $meta_class->configurable=1;
                foreach($languages as $language)
                {
                    $meta_class->title[$language['id_lang']] = $this->getTextLang($meta['tabname'],$language) ?: $meta['title'];
                    $meta_class->url_rewrite[$language['id_lang']] = ($link_rewrite = $this->getTextLang($meta['url_rewrite_lang'],$language)) ? Tools::link_rewrite($link_rewrite) :  $meta['url_rewrite'];
                }
                $meta_class->add();
            }
        }
        return true;
    }
    public function unInstallLinkDefault()
    {
        $metas= array(
            array(
                'controller' => 'dashboard',
                'title' => $this->l('Dashboard'),
                'url_rewrite' => 'seller-dashboard'
            ),
            array(
                'controller' => 'orders',
                'title' => $this->l('Orders'),
                'url_rewrite' => 'seller-orders'
            ),
            array(
                'controller' => 'products',
                'title' => $this->l('Products'),
                'url_rewrite' => 'seller-products'
            ),
            array(
                'controller' => 'commissions',
                'title' => $this->l('Commissions'),
                'url_rewrite' => 'seller-commissions'
            ),
            array(
                'controller' => 'billing',
                'title' => $this->l('Membership'),
                'url_rewrite' => 'seller-membership-invoices'
            ),
            array(
                'controller' => 'withdraw',
                'title' => $this->l('Withdrawals'),
                'url_rewrite' => 'seller-withdrawals'
            ),
            array(
                'controller' => 'voucher',
                'title' => $this->l('My vouchers'),
                'url_rewrite' => 'seller-vouchers'
            ),
            array(
                'controller' => 'attributes',
                'title' => $this->l('Attributes and Features'),
                'url_rewrite' => 'seller-attributes'
            ),
            array(
                'controller' => 'features',
                'title' => $this->l('Attributes and Features'),
                'url_rewrite' => 'seller-features'
            ),
            array(
                'controller' => 'discount',
                'title' => $this->l('Discounts'),
                'url_rewrite' => 'seller-discounts'
            ),
            array(
                'controller' => 'messages',
                'title' => $this->l('Messages'),
                'url_rewrite' => 'seller-messages'
            ),
            array(
                'controller' => 'profile',
                'title' => $this->l('Profile'),
                'url_rewrite' => 'seller-profile'
            ),
            array(
                'controller' => 'create',
                'title' => $this->l('Create'),
                'url_rewrite' => 'seller-create-shop'
            ),
            array(
                'controller' => 'registration',
                'title' => $this->l('Application'),
                'url_rewrite' => 'seller-application'
            ),
            array(
                'controller' => 'myseller',
                'title' => $this->l('Seller account'),
                'url_rewrite' => 'seller-account'
            ),
            array(
                'controller' => 'brands',
                'title' => $this->l('Brands'),
                'url_rewrite' => 'seller-brands'
            ),
            array(
                'controller' => 'import',
                'title' => $this->l('Import products'),
                'url_rewrite' => 'seller-import-products'
            ),
            array(
                'controller' => 'contactseller',
                'title' => $this->l('Seller contact'),
                'url_rewrite' => 'seller-contact'
            ),
            array(
                'controller' => 'carrier',
                'title' => $this->l('Carriers'),
                'url_rewrite' => 'seller-carrier'
            ),
            array(
                'controller' => 'manager',
                'title' => $this->l('Shop managers'),
                'url_rewrite' => 'seller-manager'
            ),
            array(
                'controller' => 'map',
                'title' => $this->l('Store locations'),
                'url_rewrite' => 'store-locations'
            )
        );
        foreach($metas as $meta)
        {
            if($id_meta = (int)Db::getInstance()->getValue('SELECT id_meta FROM `'._DB_PREFIX_.'meta` WHERE page ="module-'.pSQL($this->name).'-'.pSQL($meta['controller']).'"'))
            {
                $meta_class = new Meta($id_meta);
                $meta_class->delete();
            }
        }
        return true;
    }
    public function _installDb(){
        if(!class_exists('Ets_mp_defines'))
            require_once(dirname(__FILE__) . '/classes/Ets_mp_defines.php');
        $files = glob(dirname(__FILE__).'/views/import/*'); 
        if($files)
        {
           foreach($files as $file){ 
                if(is_file($file) && $file!=dirname(__FILE__).'/views/import/index.php')
                    @unlink($file); 
            } 
        }
        return Ets_mp_defines::getInstance()->_installDb();
    }
    public function _installFieldConfigDefault()
    {
        $languages = Language::getLanguages(false);
        if($settings = Ets_mp_defines::getInstance()->getFieldConfig('settings'))
        {
            foreach($settings as $setting)
            {
                if(!Configuration::hasKey($setting['name']))
                {
                    if($setting['type']=='categories' && isset($setting['default']) && $setting['default'])
                        Configuration::updateValue($setting['name'],implode(',',$setting['default']));
                    elseif(isset($setting['default']))
                    {
                        if(isset($setting['lang']) && $setting['lang'])
                        {
                            $values = array();
                            foreach($languages as $language)
                            {
                                $values[$language['id_lang']] = $setting['default'];
                            }
                            Configuration::updateValue($setting['name'],$values,true);
                        }
                        else
                            Configuration::updateValue($setting['name'],$setting['default'],true);
                    }
                }
            }
        }
        Configuration::updateValue('ETS_MP_REGISTRATION_FIELDS_VALIDATE','shop_phone,message_to_administrator');
        Configuration::updateValue('ETS_MP_CONTACT_FIELDS_VALIDATE','title,message');
        $commission_usage_settings = Ets_mp_defines::getInstance()->getFieldConfig('commission_usage_settings');
        if($commission_usage_settings)
        {
            foreach($commission_usage_settings as $setting)
            {
                if(isset($setting['default']) && !Configuration::hasKey($setting['name']))
                    Configuration::updateValue($setting['name'],$setting['default']);
            }
        }
        return true;
    }
    public function _installDbDefault(){
        $languages = Language::getLanguages(false);
        $this->_installFieldConfigDefault();
        $pm_params = array();
        $pmf_params = array(
            array(
                'type' => 'text',
                'required' => 1,
                'enable' => 1,
                'sort' => 1,
            ),
            array(
                'type' => 'text',
                'required' => 1,
                'enable' => 1,
                'sort' => 2,
            ),
            array(
                'type' => 'text',
                'required' => 1,
                'enable' => 1,
                'sort' => 3,
            ),
            array(
                'type' => 'text',
                'required' => 1,
                'enable' => 1,
                'sort' => 4,
            ),
        );
        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $pm_params['title'][$lang['id_lang']] = $this->getTextLang('PayPal',$lang) ?:  $this->l('PayPal');
            $pm_params['desc'][$lang['id_lang']] = $this->getTextLang('The fastest method to withdraw funds, directly to your local bank account!',$lang) ?: $this->l('The fastest method to withdraw funds, directly to your local bank account!');
            $pm_params['note'][$lang['id_lang']] = null;
            foreach ($pmf_params as &$p) {
                if($p['sort'] == 1){
                    $p['title'][$lang['id_lang']] = $this->getTextLang('First name',$lang) ?: $this->l('First name');
                    $p['desc'][$lang['id_lang']] = $this->getTextLang('Type your first name',$lang) ?: $this->l('Type your first name');
                }
                elseif($p['sort'] == 2){
                    $p['title'][$lang['id_lang']] = $this->getTextLang('Last name',$lang) ?: $this->l('Last name');
                    $p['desc'][$lang['id_lang']] =  $this->getTextLang('Type your last name',$lang) ? : $this->l('Type your last name');
                }
                elseif($p['sort'] == 3){
                    $p['title'][$lang['id_lang']] = $this->getTextLang('PayPal email',$lang)?: $this->l('PayPal email');
                    $p['desc'][$lang['id_lang']] = $this->getTextLang('Type your PayPal email to receive money',$lang) ?: $this->l('Type your PayPal email to receive money');
                }
                elseif($p['sort'] == 4){
                    $p['title'][$lang['id_lang']] = $this->getTextLang('Phone',$lang) ?: $this->l('Phone');
                    $p['desc'][$lang['id_lang']] =  $this->getTextLang('Type your phone number',$lang) ?: $this->l('Type your phone number');
                }
            }
        }
        $pm_params['fee_fixed'] = 1;
        $pm_params['fee_type'] = 'NO_FEE';
        $pm_params['fee_percent'] = null;
        $pm_params['estimate_processing_time'] = 30;
        $pm = new Ets_mp_paymentmethod();
        $pm->title = $pm_params['title'];
        $pm->description = $pm_params['desc'];
        $pm->fee_fixed = $pm_params['fee_fixed'];
        $pm->fee_type = $pm_params['fee_type'];
        $pm->enable=1;
        $pm->id_shop= $this->context->shop->id;
        $pm->sort=1;
        $pm->estimated_processing_time = $pm_params['estimate_processing_time'];
        $pm->logo = 'paypal.png';
        $id_pm = $pm->add();
        if($id_pm){
            if(!is_dir(_PS_IMG_DIR_.'mp_payment/'))
            {
                @mkdir(_PS_IMG_DIR_.'mp_payment/',0777,true);
                @copy(dirname(__FILE__).'/index.php', _PS_IMG_DIR_.'mp_payment/index.php');
            }
            Tools::copy(_PS_MODULE_DIR_.$this->name.'/views/img/paypal.png',_PS_IMG_DIR_.'mp_payment/paypal.png');
            foreach ($pmf_params as $pmf_param) {
                $pmf = new Ets_mp_paymentmethodfield();
                $pmf->id_ets_mp_payment_method = $id_pm;
                $pmf->title = $pmf_param['title'];
                $pmf->description = $pmf_param['desc'];
                $pmf->type = $pmf_param['type'];
                $pmf->required = $pmf_param['required'];
                $pmf->sort = $pmf_param['sort'];
                $pmf->enable=1;
                $pmf->add();
            }
        }
        return true;
    }
    public function _unRegisterHooks()
    {
        if($this->_hooks)
        {
            foreach($this->_hooks as $hook)
                $this->unregisterHook($hook);
        }
        return true;
    }
    public function uninstall()
	{
        return parent::uninstall()
        && $this->_unRegisterHooks()
        && $this->_uninstallDbDefault() && $this->_uninstallDb()&& $this->_uninstallTabs() && $this->unInstallLinkDefault()&& $this->_unInstallOverried();
    }
    public function _uninstallTabs()
    {
        $tabs = array('AdminMarketPlaceDashboard','AdminMarketPlaceOrders','AdminMarketPlaceProducts','AdminMarketPlaceCommissions','AdminMarketPlaceCommissionsUsage','AdminMarketPlaceBillings','AdminMarketPlaceWithdrawals','AdminMarketPlaceRegistrations','AdminMarketPlaceShopSellers','AdminMarketPlaceSellers','AdminMarketPlaceSettings','AdminMarketPlaceRatings');
        if($tabs)
        {
            foreach($tabs as $classname)
            {
                if($tabId = Tab::getIdFromClassName($classname))
                {
                    if($classname=='AdminMarketPlaceSettings')
                    {
                        $subs = array('AdminMarketPlaceSettingsGeneral','AdminMarketPlacePayments','AdminMarketPlaceCronJob');
                        foreach($subs as $sub)
                        {
                            if($idTab = Tab::getIdFromClassName($sub))
                            {
                                $tab = new Tab($idTab);
                                if($tab)
                                    $tab->delete();
                            }
                        }
                    }
                    if($classname=='AdminMarketPlaceShopSellers')
                    {
                        $subs = array('AdminMarketPlaceSellers','AdminMarketPlaceReport','AdminMarketPlaceShopGroups');
                        foreach($subs as $sub)
                        {
                            if($idTab = Tab::getIdFromClassName($sub))
                            {
                                $tab = new Tab($idTab);
                                if($tab)
                                    $tab->delete();
                            }
                        }
                    }
                    $tab = new Tab($tabId);
                    if($tab)
                        $tab->delete();
                }               
            }
            if($tabId = Tab::getIdFromClassName('AdminMarketPlace'))
            {
                $tab = new Tab($tabId);
                if($tab)
                    $tab->delete();
            }
        }
        return true;
    }
    public function _uninstallDbDefault()
    {
        if($settings = Ets_mp_defines::getInstance()->getFieldConfig('settings'))
        {
            foreach($settings as $setting)
            {
                Configuration::deleteByName($setting['name']);
            }
        }
        Configuration::deleteByName('ETS_MP_REGISTRATION_FIELDS_VALIDATE');
        Configuration::deleteByName('ETS_MP_CONTACT_FIELDS_VALIDATE');
        $commission_usage_settings = Ets_mp_defines::getInstance()->getFieldConfig('commission_usage_settings');
        if($commission_usage_settings)
        {
            foreach($commission_usage_settings as $setting)
            {
                Configuration::deleteByName($setting['name']);
            }
        }
        return true;
    }
    public function _uninstallDb()
    {
        $tables = array(
            'ets_mp_registration',
            'ets_mp_seller',
            'ets_mp_seller_lang',
            'ets_mp_seller_product',
            'ets_mp_cart_rule_seller',
            'ets_mp_seller_order',
            'ets_mp_seller_commission',
            'ets_mp_commission_usage',
            'ets_mp_payment_method',
            'ets_mp_payment_method_lang',
            'ets_mp_payment_method_field',
            'ets_mp_payment_method_field_lang',
            'ets_mp_attribute_group_seller',
            'ets_mp_attribute_seller',
            'ets_mp_withdrawal',
            'ets_mp_withdrawal_field',
            'ets_mp_seller_billing',
            'ets_mp_manufacturer_seller',
            'ets_mp_seller_customer_message',
            'ets_mp_seller_customer_follow',
            'ets_mp_feature_seller',
            'ets_mp_seller_contact',
            'ets_mp_seller_contact_message',
            'ets_mp_carrier_seller',
            'ets_mp_seller_manager',
            'ets_mp_seller_report',
            'ets_mp_seller_group',
            'ets_mp_seller_group_lang'
        );
        if($tables)
        {
            foreach($tables as $table)
               Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . pSQL($table).'`'); 
        }
        $files = glob(_PS_IMG_DIR_.'mp_seller/*'); 
        if($files)
        {
           foreach($files as $file){ 
                    @unlink($file); 
            } 
        }
        $files = glob(_PS_ETS_MARKETPLACE_UPLOAD_DIR_.'mp_attachment/*'); 
        if($files)
        {
           foreach($files as $file){ 
                @unlink($file); 
           } 
        }
        $files = glob(_PS_IMG_DIR_.'mp_payment/*'); 
        if($files)
        {
           foreach($files as $file){ 
                @unlink($file); 
           } 
        }
        $files = glob(dirname(__FILE__).'/views/import/*'); 
        if($files)
        {
           foreach($files as $file){ 
                if(is_file($file) && $file!=dirname(__FILE__).'/views/import/index.php')
                    @unlink($file); 
            } 
        } 
        $files = glob(_PS_IMG_DIR_.'mp_group/*'); 
        if($files)
        {
           foreach($files as $file){ 
                    @unlink($file); 
            } 
        }
        if(file_exists(dirname(__FILE__).'/cronjob_log.txt'))
            @unlink(dirname(__FILE__).'/cronjob_log.txt');   
        return true;
    }
    public function getSellerInfoById($id_seller)
    {
        $seller = new Ets_mp_seller($id_seller,$this->context->language->id);
        $this->context->smarty->assign(
            array(
                'seller' => $seller,
                'link'=> $this->context->link,
            )
        );
        return $this->display(__FILE__,'seller_order_product.tpl');
    }
    public function getRequestContainer()
    {
        $sfContainer = call_user_func(array('\PrestaShop\PrestaShop\Adapter\SymfonyContainer','getInstance'));

        if (null !== $sfContainer && null !== $sfContainer->get('request_stack')->getCurrentRequest()) {
            $request = $sfContainer->get('request_stack')->getCurrentRequest();
            return $request;
        }
        return null;
    }
    public function hookDisplayBackOfficeHeader($params)
    {
        $tabs = array('AdminMarketPlaceDashboard','AdminMarketPlaceOrders','AdminMarketPlaceProducts','AdminMarketPlaceCommissions','AdminMarketPlaceCommissionsUsage','AdminMarketPlaceBillings','AdminMarketPlaceWithdrawals','AdminMarketPlaceRegistrations','AdminMarketPlaceSellers','AdminMarketPlacePayments','AdminMarketPlaceCronJob','AdminMarketPlaceSettings','AdminMarketPlaceSettingsGeneral','AdminMarketPlaceReport','AdminMarketPlaceShopGroups','AdminMarketPlaceRatings');
        $html ='';
        if(Tools::getValue('controller')=='AdminMarketPlaceDashboard')
        {
            $this->context->controller->addJquery();
            $this->context->controller->addJS($this->_path.'views/js/Chart.min.js');
            $this->context->controller->addCSS($this->_path.'views/css/daterangepicker.css'); 
        }
        if(Tools::getValue('controller')=='AdminMarketPlaceProducts')
        {
            $this->context->controller->addJquery();
            $this->context->controller->addJS($this->_path.'views/js/product_bulk.js');
        }
        if((Tools::getValue('controller')=='AdminModules' && Tools::getValue('configure')==$this->name) || in_array(Tools::getValue('controller'),$tabs) )
        {
            $this->context->controller->addCSS($this->_path.'views/css/admin.css');
        }
        if(Tools::getValue('controller')=='AdminOrders' || Tools::getValue('controller')=='AdminProducts')
        {
            if(Tools::getValue('controller')=='AdminOrders' && $id_order=Tools::getValue('id_order'))
            {
                $sql ='SELECT seller.id_seller FROM `'._DB_PREFIX_.'ets_mp_seller_order` o
                INNER JOIN `'._DB_PREFIX_.'customer` customer ON (o.id_customer = customer.id_customer)
                INNER JOIN `'._DB_PREFIX_.'ets_mp_seller` seller ON (seller.id_customer=customer.id_customer)
                WHERE o.id_order='.(int)$id_order;
                $id_seller = (int)Db::getInstance()->getValue($sql);
            }
            elseif(Tools::getValue('controller')=='AdminProducts')
            {
                if($this->is17)
                {
                    $request = $this->getRequestContainer();
                    if($request)
                        $id_product= $request->get('id');
                    else
                        $id_product = Tools::getValue('id_product');
                }
                else
                    $id_product= Tools::getValue('id_product');
                if($id_product)
                {
                    $sql ='SELECT seller.id_seller FROM `'._DB_PREFIX_.'ets_mp_seller_product` p
                    INNER JOIN `'._DB_PREFIX_.'customer` customer ON (customer.id_customer=p.id_customer)
                    INNER JOIN `'._DB_PREFIX_.'ets_mp_seller` seller ON (customer.id_customer=seller.id_customer)
                    WHERE p.id_product='.(int)$id_product;
                    $id_seller = (int)Db::getInstance()->getValue($sql);
                }    
            }
            if(isset($id_seller) && $id_seller)
            {
                $html .= $this->getSellerInfoById($id_seller);
            }
        }
        $this->context->controller->addCSS($this->_path.'views/css/admin_all.css');
        if(!$this->is17){
            $this->context->controller->addCSS($this->_path.'views/css/admin_16.css');
        }
        $this->context->smarty->assign(
            array(
                'total_registrations' => Db::getInstance()->getValue('
                    SELECT COUNT(*) FROM `'._DB_PREFIX_.'ets_mp_registration` r
                    LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller` s ON (r.id_customer=s.id_customer) 
                    WHERE s.id_seller is null AND r.active=-1 AND r.id_shop="'.(int)$this->context->shop->id.'"'),
                'total_seller_wait_approve' => Db::getInstance()->getValue('SELECT COUNT(*) FROM `'._DB_PREFIX_.'ets_mp_seller` WHERE payment_verify!=0 AND active!=1'),
            )
        );
        $this->context->smarty->assign(
            array(
                 'ets_mp_module_dir' => $this->_path,
            )
        );
        $html .=$this->display(__FILE__,'admin_header.tpl');
        return $html;
    }
    public function hookActionProductDelete($params)
    {
        if(isset($params['id_product']) && $params['id_product'])
        {
            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'ets_mp_seller_product` WHERE id_product='.(int)$params['id_product']);
            Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'cart_rule WHERE reduction_product="'.(int)$params['id_product'].'"');
        }
    }
    public function hookActionProductUpdate($params)
    {
        $id_product = $params['id_product'];
        if($product_seller = Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'ets_mp_seller_product` WHERE id_product='.(int)$id_product))
        {
            if(isset($this->context->employee) && isset($this->context->employee->id) && $this->context->employee->id)
            {
               $admin= true; 
            }
            else
                $admin = false;
            $product = new Product($id_product);
            if($product->active)
            {
                Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'ets_mp_seller_product` SET active=1 '.($admin ? ',approved=1':'').' WHERE id_product='.(int)$id_product);
            }
            else
            {
                Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'ets_mp_seller_product` SET active=0'.($admin ? ',approved=0':'').' WHERE active=1 AND id_product='.(int)$id_product);
            }
            if($admin && $product_seller['id_customer'] && Configuration::get('ETS_MP_EMAIL_SELLER_PRODUCT_APPROVED_OR_DECLINED') && $product_seller['approved']!=$product->active)
            {
                $seller = Ets_mp_seller::_getSellerByIdCustomer($product_seller['id_customer']);
                $data = array(
                    '{seller_name}' => $seller->seller_name,
                    '{product_link}' => $this->context->link->getProductLink($product),
                    '{product_name}' => $product->name[$this->context->language->id],
                    '{product_ID}' => $product->id,
                );
                if($product->active)
                {
                    $subjects = array(
                        'translation' => $this->l('Your product is approved'),
                        'origin'=> 'Your product is approved',
                        'specific'=>false
                    );
                    Ets_marketplace::sendMail('to_seller_product_approved',$data,$seller->seller_email,$subjects,$seller->seller_name);
                }
                else
                {
                    $subjects = array(
                        'translation' => $this->l('Your product is declined'),
                        'origin'=> 'Your product is declined',
                        'specific'=>false
                    );
                    Ets_marketplace::sendMail('to_seller_product_declined',$data,$seller->seller_email,$subjects,$seller->seller_name);
                }
            }
        }
        
    }
    public function hookDisplayAdminProductsSeller($params)
    {
        if(isset($params['id_product']) && ($id_product = $params['id_product']))
        {
            $sql = 'SELECT id_customer FROM '._DB_PREFIX_.'ets_mp_seller_product WHERE id_product= "'.(int)$id_product.'" AND is_admin!=1';
            if(!(int)Db::getInstance()->getValue($sql))
            {
                if(($id_customer = (int)Db::getInstance()->getValue('SELECT id_customer FROM '._DB_PREFIX_.'ets_mp_seller_product WHERE id_product= "'.(int)$id_product.'" AND is_admin=1')))
                {
                    $seller = Ets_mp_seller::_getSellerByIdCustomer($id_customer,$this->context->language->id);
                }
                else
                    $seller = false;
                $this->context->smarty->assign(
                    array(
                        'seller_product' => $seller,
                        'id_product' => $id_product,
                        'is_ps16' => $this->is17 ? false:true,
                        'link_search_seller' => $this->getBaseLink().'/modules/'.$this->name.'/search_seller.php?token='.Tools::getAdminTokenLite('AdminModules'),
                    )
                );
                return $this->display(__FILE__,'form_add_seller_to_product.tpl');
            }
        }
    }
    public function getContent()
	{
	   $this->context->controller->addJqueryUI('ui.sortable');
       $this->context->controller->addJqueryPlugin('autocomplete');
       $control = Tools::getValue('control');
       $html = '';
       if(Tools::isSubmit('delImage') && $fieldDel = Tools::getValue('delImage'))
       {
            if(($image = Configuration::get($fieldDel)) && file_exists(dirname(__FILE__).'/views/img/'.$image))
            {
                @unlink(dirname(__FILE__).'/views/img/'.$image);
                Configuration::deleteByName($fieldDel);
                $this->context->cookie->success_message = $this->l('Deleted successfully');
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminMarketPlaceSettingsGeneral').'&current_tab=map_settings');
            }
       }
       if(Tools::isSubmit('saveConfig'))
       {
            if($this->_checkFormBeforeSubmit())
            {
                $html .= $this->displayConfirmation($this->l('Save successfully'));
                $this->_saveFromSettings();
            }
       }
	   $this->context->smarty->assign(array(
            'ets_mp_sidebar' => $this->renderSidebar($control),
            'control' => $control,
            'ets_mp_module_dir' => $this->_path,
        ));
        if($control)
        {
            if($this->context->cookie->success_message)
            {
                $html .= $this->displayConfirmation($this->context->cookie->success_message);
                $this->context->cookie->success_message ='';
            }
            $this->context->smarty->assign(
                array(
                    'ets_mp_body_html'=> $this->renderAdminBodyHtml($control),
                )
            );
            if($this->_errors)
                $html .= $this->displayError($this->_errors);
            $html .=$this->display(__FILE__,'admin.tpl');
            return $html;  
        }
        elseif(Tools::getValue('controller')=='AdminModules')
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminMarketPlaceDashboard'));
    }
    public function renderSidebar($control)
    {
        $this->context->smarty->assign(
            array(
                'sidebars' => Ets_mp_defines::getInstance()->getFieldConfig('sidebars'),
                'control' => $control,
                'link'=>$this->context->link,
                'controller'=>Tools::getValue('controller'),
                'mp_link_module' => $this->context->link->getAdminLink('AdminModules').'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name,
            )
        );
        return $this->display(__FILE__,'sidebar.tpl');
    }
    public function renderAdminBodyHtml($control)
    {
        switch ($control) {
            case 'dashboard':
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminMarketPlaceDashboardController'));
            case 'commission':
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminMarketPlaceCommissionsController'));
            case 'commission_usage':
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminMarketPlaceCommissionUsageController'));
            case 'billing':
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminMarketPlaceBillingsController'));
            case 'withdraw':
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminMarketPlaceWithdrawalsController'));
            case 'sellers_registration':
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminMarketPlaceRegistrationsController'));
            case 'sellers':
            {
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminMarketPlaceSellersController')); 
            }
            case 'payments':
            {
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminMarketPlacePaymentsController')); 
            }
            case 'cronjob':
                return $this->_renderCronjob();
            case 'products':
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminMarketPlaceProductsController'));
            case 'orders':
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminMarketPlaceOrdersController'));
            default:
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminMarketPlaceDashboardController'));
        } 
    }
    public function _renderCronjob()
    {
        if(Tools::isSubmit('etsmpSubmitUpdateToken'))
        {
            if(Tools::getValue('ETS_MP_CRONJOB_TOKEN'))
            {
                Configuration::updateGlobalValue('ETS_MP_CRONJOB_TOKEN',Tools::getValue('ETS_MP_CRONJOB_TOKEN'));
                die(
                    Tools::jsonEncode(
                        array(
                            'success' => $this->l('Updated successfully'),
                        )
                    )
                );
            }
            else
            {
                die(
                    Tools::jsonEncode(
                        array(
                            'errors' => $this->l('Token is required'),
                        )
                    )
                );
            }   
        }
        if(!Configuration::getGlobalValue('ETS_MP_CRONJOB_TOKEN'))
            Configuration::updateGlobalValue('ETS_MP_CRONJOB_TOKEN',Tools::passwdGen(12));
        $this->context->smarty->assign(
            array(
                'dir_cronjob' => dirname(__FILE__).'/cronjob.php',
                'link_conjob' => $this->getBaseLink().'/modules/'.$this->name.'/cronjob.php',
                'ETS_MP_CRONJOB_TOKEN' => Tools::getValue('ETS_MP_CRONJOB_TOKEN',Configuration::getGlobalValue('ETS_MP_CRONJOB_TOKEN')),
            )
        );
        return $this->display(__FILE__,'cronjob.tpl');
    }
    public function _renderSettings()
    {
        $languages = Language::getLanguages(false);
        $fields_form = array(
    		'form' => array(
    			'legend' => array(
    				'title' => $this->l('General'),
    				'icon' => 'icon-settings'
    			),
    			'input' => array(),
                'submit' => array(
    				'title' => $this->l('Save'),
    			)
            ),
    	);
        $configs = Ets_mp_defines::getInstance()->getFieldConfig('settings');
        $fields = array();
        foreach($configs as $config)
        {
            $fields_form['form']['input'][] = $config;
            $fields[$config['name']] = Tools::getValue($config['name'],Configuration::get($config['name']));
            if($config['type']!='checkbox' && $config['type']!='categories' && $config['type']!='tre_categories')
            {
                if(isset($config['lang']) && $config['lang'])
                {
                    foreach($languages as $language)
                    {
                        $fields[$config['name']][$language['id_lang']] = Tools::getValue($config['name'].'_'.$language['id_lang'],Configuration::get($config['name'],$language['id_lang']));
                    }
                    
                }
                else
                    $fields[$config['name']] = Tools::getValue($config['name'],Configuration::get($config['name']));
            }
            else
                $fields[$config['name']] = Tools::isSubmit('saveConfig') ?  Tools::getValue($config['name']) : explode(',',Configuration::get($config['name']));
            $fields['ETS_MP_REGISTRATION_FIELDS_VALIDATE'] = Tools::isSubmit('saveConfig') ? Tools::getValue('ETS_MP_REGISTRATION_FIELDS_VALIDATE') : explode(',',Configuration::get('ETS_MP_REGISTRATION_FIELDS_VALIDATE'));
            $fields['ETS_MP_CONTACT_FIELDS_VALIDATE'] = Tools::isSubmit('saveConfig') ? Tools::getValue('ETS_MP_CONTACT_FIELDS_VALIDATE') : explode(',',Configuration::get('ETS_MP_CONTACT_FIELDS_VALIDATE'));
        }
        $fields_form['form']['input'][]= array(
            'name' =>'current_tab',
            'type' => 'hidden',
        );
        $fields['current_tab'] =Tools::getValue('current_tab','conditions');
        $helper = new HelperForm();
    	$helper->show_toolbar = false;
    	$helper->table = $this->table;
    	$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
    	$helper->default_form_language = $lang->id;
    	$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
    	$this->fields_form = array();
    	$helper->module = $this;
    	$helper->identifier = $this->identifier;
    	$helper->submit_action = 'saveConfig';
    	$helper->currentIndex = $this->context->link->getAdminLink('AdminMarketPlaceSettingsGeneral', false);
    	$helper->token = Tools::getAdminTokenLite('AdminMarketPlaceSettingsGeneral');
    	$language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));            
        $helper->tpl_vars = array(
    		'base_url' => $this->context->shop->getBaseURL(),
    		'language' => array(
    			'id_lang' => $language->id,
    			'iso_code' => $language->iso_code
    		),
    		'fields_value' => $fields,
    		'languages' => $this->context->controller->getLanguages(),
            'configTabs' => Ets_mp_defines::getInstance()->getFieldConfig('configTabs'),
    		'id_language' => $this->context->language->id,
            'isConfigForm' => true,
            'link_base' => $this->getBaseLink(),
            'current_tab' => Tools::getValue('current_tab','conditions'),
            'image_baseurl' => $this->_path.'views/img/',
        );
        return $helper->generateForm(array($fields_form));	
    }
    public function _checkFormBeforeSubmit()
    {
        $languages = Language::getLanguages(false);
        $id_lang_default = Configuration::get('PS_LANG_DEFAULT');
        if(!Tools::getValue('ETS_MP_SELLER_GROUPS'))
            $this->_errors[] = $this->l('Applicable customer group is required');
        if(Tools::getValue('ETS_MP_SELLER_FEE_TYPE')!='no_fee')
        {
            if(!(float)Tools::getValue('ETS_MP_SELLER_FEE_AMOUNT'))
                $this->_errors[] = $this->l('Fee amount is required');
        }
        if(!Tools::getValue('ETS_MP_SELLER_PAYMENT_INFORMATION_'.$id_lang_default))
            $this->_errors[] = $this->l('Payment information of the marketplace manager is required');
        if(trim(Tools::getValue('ETS_MP_COMMISSION_RATE'))=='')
            $this->_errors[] = $this->l('Global shop commission rate is required');
        elseif( Validate::isFloat(Tools::getValue('ETS_MP_COMMISSION_RATE')) && (Tools::getValue('ETS_MP_COMMISSION_RATE') >100 || Tools::getValue('ETS_MP_COMMISSION_RATE')<=0))
            $this->_errors[] = $this->l('Global shop commission rate must be between 0% and 100%');
        if(!Tools::getValue('ETS_MP_SELLER_ALLOWED_INFORMATION_SUBMISSION'))
            $this->_errors[] = $this->l('Allow seller to submit these information is required');
        if(Tools::getValue('ETS_MP_SELLER_CAN_CHANGE_ORDER_STATUS') && !Tools::getValue('ETS_MP_SELLER_ALLOWED_STATUSES'))
            $this->_errors[] = $this->l('Select order status which seller can update is required');
        if(!Tools::getValue('ETS_MP_SELLER_PRODUCT_TYPE_SUBMIT'))
            $this->_errors[] = $this->l('The type of product is required.');
        if(Tools::getValue('ETS_MP_ENABLE_CAPTCHA'))
        {
            if(!Tools::getValue('ETS_MP_ENABLE_CAPTCHA_FOR'))
                $this->_errors[] = $this->l('Enable captcha for is required');
            if(Tools::getValue('ETS_MP_ENABLE_CAPTCHA_TYPE')=='google_v2')
            {
                if(!Tools::getValue('ETS_MP_ENABLE_CAPTCHA_SITE_KEY2'))
                    $this->_errors[] = $this->l('Site key is required');
                if(!Tools::getValue('ETS_MP_ENABLE_CAPTCHA_SECRET_KEY2'))
                    $this->_errors[] = $this->l('Secret key is required');
            }
            if(Tools::getValue('ETS_MP_ENABLE_CAPTCHA_TYPE')=='google_v3')
            {
                if(!Tools::getValue('ETS_MP_ENABLE_CAPTCHA_SITE_KEY3'))
                    $this->_errors[] = $this->l('Site key is required');
                if(!Tools::getValue('ETS_MP_ENABLE_CAPTCHA_SECRET_KEY3'))
                    $this->_errors[] = $this->l('Secret key is required');
            }
        }
        if(Tools::getValue('ETS_MP_ENABLE_MAP') && Tools::getValue('ETS_MP_SEARCH_ADDRESS_BY_GOOGLE') && !Tools::getValue('ETS_MP_GOOGLE_MAP_API'))
        {
            $this->_errors[] = $this->l('Google map api is required');
        }
        if($settings = Ets_mp_defines::getInstance()->getFieldConfig('settings'))
        {
            foreach($settings as $config)
            {
                $name = $config['name'];
                if(isset($config['lang']) && $config['lang'])
                { 
                    if((isset($config['validate']) && $config['validate'] && method_exists('Validate',$config['validate'])))
                    {
                        $validate = $config['validate'];
                        foreach($languages as $lang)
                        {
                            if(trim(Tools::getValue($name.'_'.$lang['id_lang'])) && !Validate::$validate(trim(Tools::getValue($name.'_'.$lang['id_lang']))))
                                $this->_errors[] =  $config['label'].' '.$this->l('is not valid in ').$lang['iso_code'];
                        }
                        unset($validate);
                    }
                }
                else
                {
                    if((isset($config['validate']) && $config['validate'] && method_exists('Validate',$config['validate'])))
                    {
                        $validate = $config['validate'];
                        if(trim(Tools::getValue($name)) && !Validate::$validate(trim(Tools::getValue($name))))
                             $this->_errors[] = $config['label'].' '. $this->l('is not valid');
                        unset($validate);
                    } 
                }
                    
            }
        }
        if(Tools::getValue('ETS_MP_APPLICABLE_CATEGORIES')=='specific_product_categories' && !Tools::getValue('ETS_MP_SELLER_CATEGORIES'))
            $this->_errors[] = $this->l('Categories are required');
        if(Tools::getValue('ETS_MP_ENABLE_MAP') && isset($_FILES['ETS_MP_GOOGLE_MAP_LOGO']['name']) && $_FILES['ETS_MP_GOOGLE_MAP_LOGO']['name'])
        {
            $this->validateFile($_FILES['ETS_MP_GOOGLE_MAP_LOGO']['name'],$_FILES['ETS_MP_GOOGLE_MAP_LOGO']['size'],$this->_errors,array('jpeg','jpg','png','gif'));
        }
        if(Tools::getValue('ETS_MP_DISPLAY_FOLLOWED_SHOP') && !Tools::getValue('ETS_MP_DISPLAY_NUMBER_SHOP'))
            $this->_errors[] = $this->l('Number of shops to display is required');
        if(Tools::getValue('ETS_MP_DISPLAY_PRODUCT_FOLLOWED_SHOP') && !Tools::getValue('ETS_MP_DISPLAY_NUMBER_PRODUCT_FOLLOWED_SHOP'))
            $this->_errors[] = $this->l('Number of followed products to display on homepage is required');
        if(Tools::getValue('ETS_MP_DISPLAY_PRODUCT_TRENDING_SHOP'))
        {
            if(!Tools::getValue('ETS_MP_TRENDING_PERIOD_SHOP'))
                $this->_errors[] = $this->l('Trending period is required');
            if(!Tools::getValue('ETS_MP_DISPLAY_NUMBER_PRODUCT_TRENDING_SHOP'))
                $this->_errors[] = $this->l('Number of trending products to display is required');
        }
        if($this->_errors)
            return false;
        else
            return true;
    }
    public function _saveFromSettings()
    {
        $languages = Language::getLanguages(false);
        $id_language_default = Configuration::get('PS_LANG_DEFAULT');
        if($settings = Ets_mp_defines::getInstance()->getFieldConfig('settings'))
        {
            foreach($settings as $config)
            {
                
                if($config['type']=='checkbox' || $config['type']=='categories'|| $config['type']=='tre_categories')
                {
                    Configuration::deleteByName($config['name']);
                    Configuration::updateValue($config['name'],Tools::getValue($config['name']) ? implode(',',Tools::getValue($config['name'])) :'' );
                }
                else
                {
                    if(isset($config['lang']) && $config['lang'])
                    {
                        Configuration::deleteByName($config['name']);
                        $values = array();
                        foreach($languages as $language)
                        {
                            $values[$language['id_lang']] = Tools::getValue($config['name'].'_'.$language['id_lang']) ? Tools::getValue($config['name'].'_'.$language['id_lang']) :Tools::getValue($config['name'].'_'.$id_language_default);
                        }
                        
                        Configuration::updateValue($config['name'],$values,true);
                    }
                    elseif($config['type']=='file')
                    {
                        if(isset($_FILES[$config['name']]['name']) && isset($_FILES[$config['name']]['name']) && isset($_FILES[$config['name']]['tmp_name']) && $_FILES[$config['name']]['tmp_name'])
                        {
                            $type = Tools::strtolower(Tools::substr(strrchr($_FILES[$config['name']]['name'], '.'), 1));
                            $file_name = Tools::passwdGen(12).'.'.$type;
                            $temp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');
                            if (!$temp_name || !move_uploaded_file($_FILES[$config['name']]['tmp_name'], $temp_name))
            					$this->_errors[] = $this->l('Cannot upload the file');
            				elseif (!ImageManager::resize($temp_name,dirname(__FILE__).'/views/img/'.$file_name, 30,30, $type))
            					$this->_errors[] = $this->l('An error occurred during the image upload process.');
                            else
                            {
                                $file_old = Configuration::get($config['name']);
                                Configuration::updateValue($config['name'],$file_name);
                                if($file_old && file_exists(dirname(__FILE__).'/views/img/'.$file_old))
                                    @unlink(dirname(__FILE__).'/views/img/'.$file_old);
                            }
                        }
                    }    
                    else
                    {
                        Configuration::deleteByName($config['name']);
                        Configuration::updateValue($config['name'],Tools::getValue($config['name']),true);
                    }
                }
                
            }
            Configuration::updateValue('ETS_MP_REGISTRATION_FIELDS_VALIDATE',implode(',',Tools::getValue('ETS_MP_REGISTRATION_FIELDS_VALIDATE',array())));
            Configuration::updateValue('ETS_MP_CONTACT_FIELDS_VALIDATE',implode(',',Tools::getValue('ETS_MP_CONTACT_FIELDS_VALIDATE',array())));
            if(!$this->_errors)
                $this->context->cookie->success_message = $this->l('Updated successfully');
        }
    }
    public function _checkPermissionPage($seller=false,$controller='')
    {
        if(!$seller) 
            $seller = $this->_getSeller(true);
        if(!$controller)
            $controller = Tools::getValue('controller');
        if($seller->id_customer == $this->context->customer->id)
        {
            return true;
        }
        else
        {
            $permissions = Db::getInstance()->getValue('SELECT permission FROM `'._DB_PREFIX_.'ets_mp_seller_manager` WHERE email ="'.pSQL($this->context->customer->email).'" AND active=1');
            if($permissions)
            {
                $permissions = explode(',',$permissions);
                if(in_array($controller,$permissions) || (in_array('all',$permissions) && !in_array($controller,array('manager','shop','voucher','withdraw'))))
                    return true;
            }
        }
        return false;
    }
    public function _getSeller($active=false)
    {
        if($id_seller = Db::getInstance()->getValue('SELECT id_seller FROM `'._DB_PREFIX_.'ets_mp_seller` WHERE id_customer="'.(int)$this->context->customer->id.'" AND id_shop="'.(int)$this->context->shop->id.'"'.($active ? ' AND active=1':'')))
        {
            return new Ets_mp_seller($id_seller);
        }
        elseif($id_customer = Db::getInstance()->getValue('SELECT id_customer FROM `'._DB_PREFIX_.'ets_mp_seller_manager` WHERE email ="'.pSQL($this->context->customer->email).'" AND active=1'))
        {
            return Ets_mp_seller::_getSellerByIdCustomer($id_customer,null,$active);
        }
        return false;
    }
    public function renderList($listData)
    { 
        if(isset($listData['fields_list']) && $listData['fields_list'])
        {
            foreach($listData['fields_list'] as $key => &$val)
            {
                if(isset($val['filter']) && $val['filter'] && ($val['type']=='int' || $val['type']=='date'))
                {
                    if(Tools::isSubmit('ets_mp_submit_'.$listData['name']))
                    {
                        $val['active']['max'] =  trim(Tools::getValue($key.'_max'));   
                        $val['active']['min'] =  trim(Tools::getValue($key.'_min')); 
                    }
                    else
                    {
                        $val['active']['max']='';
                        $val['active']['min']='';
                    }  
                }  
                elseif(!Tools::getValue('del') && Tools::isSubmit('ets_mp_submit_'.$listData['name']))               
                    $val['active'] = trim(Tools::getValue($key));
                else
                    $val['active']='';
            }
        }    
        $this->smarty->assign($listData);
        return $this->display(__FILE__, 'list_helper.tpl');
    }
    public function getFilterParams($field_list,$table='')
    {
        $params = '';        
        if($field_list)
        {
            if(Tools::isSubmit('ets_mp_submit_'.$table))
                $params .='&ets_mp_submit_'.$table.='=1';
            foreach($field_list as $key => $val)
            {
                if(Tools::getValue($key)!='')
                {
                    $params .= '&'.$key.'='.urlencode(Tools::getValue($key));
                }
                if(Tools::getValue($key.'_max')!='')
                {
                    $params .= '&'.$key.'_max='.urlencode(Tools::getValue($key.'_max'));
                }
                if(Tools::getValue($key.'_min')!='')
                {
                    $params .= '&'.$key.'_min='.urlencode(Tools::getValue($key.'_min'));
                } 
            }
            unset($val);
        }
        return $params;
    }
    public function validateFile($file_name,$file_size,&$errors,$file_types=array(),$max_file_size= false)
    {
        if($file_name)
        {
            if(!Validate::isFileName($file_name))
            {
                $errors[] = sprintf($this->l('The file name "%s" is invalid'),$file_name);
            }
            else
            {
                $type = Tools::strtolower(Tools::substr(strrchr($file_name, '.'), 1));
                if(!$file_types)
                    $file_types = $this->file_types;
                if(!in_array($type,$file_types))
                    $errors[] = sprintf($this->l('The file name "%s" is not in the correct format, accepted formats: %s'),$file_name,'.'.trim(implode(', .',$file_types),', .'));
                $max_file_size = $max_file_size ? : Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE')*1024*1024;
                if($file_size > $max_file_size)
                    $errors[] = sprintf($this->l('The file name "%s" is too large. Limit: %s'),$file_name,Tools::ps_round($max_file_size/1048576,2).'Mb');
            }
        }
        
    }
    public function uploadFile($name,&$errors)
    {
       
        if(!is_dir(_PS_IMG_DIR_.'mp_seller/'))
        {
            @mkdir(_PS_IMG_DIR_.'mp_seller/',0777,true);
            @copy(dirname(__FILE__).'/index.php', _PS_IMG_DIR_.'mp_seller/index.php');
        }
        if(isset($_FILES[$name]['tmp_name']) && isset($_FILES[$name]['name']) && $_FILES[$name]['name'])
        {
            if(!Validate::isFileName($_FILES[$name]['name']))
                $errors[] = '"'.$_FILES[$name]['name'].'" '.$this->l('file name is not valid');
            else
            {
                $type = Tools::strtolower(Tools::substr(strrchr($_FILES[$name]['name'], '.'), 1));
                $_FILES[$name]['name'] = Tools::strtolower(Tools::passwdGen(12,'NO_NUMERIC')).'.'.$type;
    			$imagesize = @getimagesize($_FILES[$name]['tmp_name']);
    			if (isset($_FILES[$name]) &&				
    				!empty($_FILES[$name]['tmp_name']) &&
    				!empty($imagesize) &&
    				in_array($type, array('jpg', 'gif', 'jpeg', 'png'))
    			)
    			{
    				$temp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');    
                    $max_file_size = Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE')*1024*1024;				
    				if ($_FILES[$name]['size'] > $max_file_size)
    					$errors[] = sprintf($this->l('Image is too large (%s Mb). Maximum allowed: %s Mb'),Tools::ps_round((float)$_FILES[$name]['size']/1048576,2), Tools::ps_round(Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE'),2));
    				elseif (!$temp_name || !move_uploaded_file($_FILES[$name]['tmp_name'], $temp_name))
    					$errors[] = $this->l('Cannot upload the file');
    				elseif (!ImageManager::resize($temp_name, _PS_IMG_DIR_.'mp_seller/'.$_FILES[$name]['name'], $name=='shop_logo' ? 250 :null, $name=='shop_logo' ? 250 :null, $type))
    					$errors[] = $this->l('An error occurred during the image upload process.');
    				if (isset($temp_name))
    					@unlink($temp_name);
                    if(!$errors)
                        return $_FILES[$name]['name'];		
    			}
                else
                {
                    if($name=='shop_logo')
                        $errors[] = $this->l('Logo is not valid');
                    else
                        $errors[] = $this->l('Banner is not valid');
                }
            }
                
        }
        return '';
    }
    public function getBreadCrumb()
    {
        $nodes = array();
        $nodes[] = array(
            'title' => $this->l('Home'),
            'url' => $this->context->link->getPageLink('index', true),
        );
        if(Tools::getValue('controller')=='map')
        {
            $nodes[] = array(
                'title' => $this->l('Store locations'),
                'url' => $this->context->link->getModuleLink($this->name,'map'),
                'last' => true,
            );
        }
        elseif(Tools::getValue('controller')!='shop')
        {
            $nodes[] = array(
                'title' => $this->l('My account'),
                'url' => $this->context->link->getPageLink('my-account'),
            );
            if(Tools::getValue('controller')=='contactseller')
                $nodes[] = array(
                    'title' => $this->l('Contact shop'),
                    'url' => $this->context->link->getModuleLink($this->name,'contactseller'),
                    'last' => Tools::getValue('controller')=='contactseller' ? true : false,
                );
            else
                $nodes[] = array(
                    'title' => $this->l('My seller account'),
                    'url' => $this->context->link->getModuleLink($this->name,'myseller'),
                    'last' => Tools::getValue('controller')=='myseller' ? true : false,
                );
        }
        else
        {
            $nodes[] = array(
                'title' => $this->l('Shops'),
                'url' => $this->getShopLink(),
                'last' => Tools::getValue('id_seller') ? false:true,
            );
            if($id_seller= Tools::getValue('id_seller'))
            {
                $seller = new Ets_mp_seller($id_seller,$this->context->language->id);
                $nodes[] = array(
                    'title' => $seller->shop_name,
                    'url' => $this->getShopLink(array('id_seller'=>$id_seller)),
                    'last' => true,
                );
            }
        }
        if(Tools::getValue('controller')=='dashboard')
        {
            $nodes[] = array(
                'title' => $this->l('Dashboard'),
                'url' => $this->context->link->getModuleLink($this->name,'dashboard'),
                'last' => true,
            );
        }
        if(Tools::getValue('controller')=='orders')
        {
            $nodes[] = array(
                'title' => $this->l('Orders'),
                'url' => $this->context->link->getModuleLink($this->name,'orders'),
                'last' => true,
            );
        }
        if(Tools::getValue('controller')=='carrier')
        {
            $nodes[] = array(
                'title' => $this->l('Carriers'),
                'url' => $this->context->link->getModuleLink($this->name,'carrier'),
                'last' => Tools::getValue('id_carrier') ? false :true,
            );
            if(Tools::getValue('id_carrier'))
            {
                $carrier = new Carrier(Tools::getValue('id_carrier'));
                $nodes[] = array(
                    'title' => $carrier->name ? : $this->context->shop->name,
                    'url' => $this->context->link->getModuleLink($this->name,'carrier',array('editmp_carrier'=>1,'id_carrier'=>Tools::getValue('id_carrier'))),
                    'last' => true,
                );
            }
        }
        if(Tools::getValue('controller')=='products')
        {
            $nodes[] = array(
                'title' => $this->l('Products'),
                'url' => $this->context->link->getModuleLink($this->name,'products',array('list'=>1)),
                'last' => true,
            );
        }
        if(Tools::getValue('controller')=='ratings')
        {
            $nodes[] = array(
                'title' => $this->l('Ratings'),
                'url' => $this->context->link->getModuleLink($this->name,'ratings',array('list'=>1)),
                'last' => true,
            );
        }
        if(Tools::getValue('controller')=='commissions')
        {
            $nodes[] = array(
                'title' => $this->l('Commissions'),
                'url' => $this->context->link->getModuleLink($this->name,'commissions'),
                'last' => true,
            );
        }
        if(Tools::getValue('controller')=='billing')
        {
            $nodes[] = array(
                'title' => $this->l('Membership'),
                'url' => $this->context->link->getModuleLink($this->name,'billing'),
                'last' => true,
            );
        }
        if(Tools::getValue('controller')=='withdraw')
        {
            $nodes[] = array(
                'title' => $this->l('Withdrawals'),
                'url' => $this->context->link->getModuleLink($this->name,'withdraw'),
                'last' => true,
            );
        }
        if(Tools::getValue('controller')=='voucher')
        {
            $nodes[] = array(
                'title' => $this->l('My vouchers'),
                'url' => $this->context->link->getModuleLink($this->name,'voucher'),
                'last' => true,
            );
        }
        if(Tools::getValue('controller')=='attributes')
        {
            $nodes[] = array(
                'title' => $this->l('Attributes'),
                'url' => $this->context->link->getModuleLink($this->name,'attributes'),
                'last' => Tools::getValue('id_attribute_group') ? true:false,
            );
            if($id_attribute_group = Tools::getValue('id_attribute_group'))
            {
                $attributeGroup = new AttributeGroup($id_attribute_group,$this->context->language->id);
                $nodes[] = array(
                    'title' => $attributeGroup->name,
                    'url' =>Tools::isSubmit('viewGroup') ? $this->context->link->getModuleLink($this->name,'attributes',array('viewGroup'=>1,'id_attribute_group'=>$id_attribute_group)) : $this->context->link->getModuleLink($this->name,'attributes',array('editmp_attribute_group'=>1,'id_attribute_group'=>$id_attribute_group)),
                    'last' => true,
                );
            }
        }
        if(Tools::getValue('controller')=='features')
        {
            $nodes[] = array(
                'title' => $this->l('Features'),
                'url' => $this->context->link->getModuleLink($this->name,'features'),
                'last' => Tools::getValue('id_feature') ? true:false,
            );
            if($id_feature = Tools::getValue('id_feature'))
            {
                $feature = new Feature($id_feature,$this->context->language->id);
                $nodes[] = array(
                    'title' => $feature->name,
                    'url' =>Tools::isSubmit('viewFeature') ? $this->context->link->getModuleLink($this->name,'features',array('viewFeature'=>1,'id_feature'=>$id_feature)) : $this->context->link->getModuleLink($this->name,'features',array('editmp_feature'=>1,'id_feature'=>$id_feature)),
                    'last' => true,
                );
            }
        }
        if(Tools::getValue('controller')=='discount')
        {
            $nodes[] = array(
                'title' => $this->l('Discounts'),
                'url' => $this->context->link->getModuleLink($this->name,'discount'),
                'last' => Tools::getValue('id_cart_rule')? true:false,
            );
            if($id_cart_rule= Tools::getValue('id_cart_rule'))
            {
                $cartRule = new CartRule($id_cart_rule,$this->context->language->id);
                $nodes[] = array(
                    'title' => $cartRule->name,
                    'url' => $this->context->link->getModuleLink($this->name,'discount',array('editmp_discount'=>1,'id_cart_rule'=>$cartRule->id)),
                    'last' => true,
                );
            }
        }
        if(Tools::getValue('controller')=='messages')
        {
            $nodes[] = array(
                'title' => $this->l('Messages'),
                'url' => $this->context->link->getModuleLink($this->name,'messages'),
                'last' => true,
            );
        }
        if(Tools::getValue('controller')=='profile')
        {
            $nodes[] = array(
                'title' => $this->l('Profile'),
                'url' => $this->context->link->getModuleLink($this->name,'profile'),
                'last' => true,
            );
        }
        if(Tools::getValue('controller')=='brands')
        {
            $nodes[] = array(
                'title' => $this->l('Brands'),
                'url' => $this->context->link->getModuleLink($this->name,'brands',array('list'=>1)),
                'last' => Tools::getValue('id_manufacturer') ? true:false,
            );
            if($id_manufacturer=Tools::getValue('id_manufacturer'))
            {
                $manufacturer = new Manufacturer($id_manufacturer);
                $nodes[] = array(
                    'title' => $manufacturer->name,
                    'url' => $this->context->link->getModuleLink($this->name,'brands',array('view'=>1,'id_manufacturer'=>$manufacturer->id)),
                    'last' => true,
                );
            }
        }
        if(Tools::getValue('controller')=='suppliers')
        {
            $nodes[] = array(
                'title' => $this->l('Suppliers'),
                'url' => $this->context->link->getModuleLink($this->name,'suppliers',array('list'=>1)),
                'last' =>  true,
            );
        }
        if(Tools::getValue('controller')=='import')
        {
            $nodes[] = array(
                'title' => $this->l('Products'),
                'url' => $this->context->link->getModuleLink($this->name,'products',array('list'=>1)),
            );
            $nodes[] = array(
                'title' => $this->l('Import products'),
                'url' => $this->context->link->getModuleLink($this->name,'import'),
                'last' => true,
            );
        }
        if(Tools::getValue('controller')=='manager')
        {
            $nodes[] = array(
                'title' => $this->l('Shop managers'),
                'url' => $this->context->link->getModuleLink($this->name,'manager',array('list'=>1)),
            );
        }
        if(Tools::getValue('controller')=='stock')
        {
            $nodes[] = array(
                'title' => $this->l('Stock'),
                'url' => $this->context->link->getModuleLink($this->name,'stock',array('list'=>1)),
            );
        }
        if($this->is17)
            return array('links' => $nodes,'count' => count($nodes));
        return $this->displayBreadcrumb($nodes);
    }
    public function displayBreadcrumb($nodes)
    {
        $this->smarty->assign(array('nodes' => $nodes));
        return  $this->display(__FILE__, 'nodes.tpl');
    }
    public static function productsForTemplate($products, Context $context = null)
    {
        if (!$products || !is_array($products))
            return array();
        if (!$context)
            $context = Context::getContext();
        $assembler = new ProductAssembler($context);
        $presenterFactory = new ProductPresenterFactory($context);
        $presentationSettings = $presenterFactory->getPresentationSettings();
        $presenter = new PrestaShop\PrestaShop\Core\Product\ProductListingPresenter(
            new PrestaShop\PrestaShop\Adapter\Image\ImageRetriever(
                $context->link
            ),
            $context->link,
            new PrestaShop\PrestaShop\Adapter\Product\PriceFormatter(),
            new PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever(),
            $context->getTranslator()
        );

        $products_for_template = array();

        foreach ($products as $rawProduct) {
            $products_for_template[] = $presenter->present(
                $presentationSettings,
                $assembler->assembleProduct($rawProduct),
                $context->language
            );
        }
        return $products_for_template;
    }
    public function hookActionOrderStatusUpdate($params)
    {
        $newOrderStatus = $params['newOrderStatus'];
        $id_order = $params['id_order'];
        if($commissions = Db::getInstance()->executeS('SELECT id_seller_commission FROM `'._DB_PREFIX_.'ets_mp_seller_commission` WHERE id_order="'.(int)$id_order.'"'))
        {
            if(Configuration::get('ETS_MP_COMMISSION_PENDING_WHEN') && ($status_pedding = explode(',',Configuration::get('ETS_MP_COMMISSION_PENDING_WHEN'))) && in_array($newOrderStatus->id,$status_pedding))
            {
                $status=-1;
            }
            elseif(Configuration::get('ETS_MP_COMMISSION_APPROVED_WHEN') && ($status_approved = explode(',',Configuration::get('ETS_MP_COMMISSION_APPROVED_WHEN'))) && in_array($newOrderStatus->id,$status_approved))
            {
                
                if(!$days = (int)Configuration::get('ETS_MP_VALIATE_COMMISSION_IN_DAYS'))
                    $status=1;
                else
                {
                    $status=-1;
                    $expired_date = date('Y-m-d H:i:s',strtotime("+ $days days"));
                }    
                
            }
            elseif(Configuration::get('ETS_MP_COMMISSION_CANCELED_WHEN') && ($status_canceled = explode(',',Configuration::get('ETS_MP_COMMISSION_CANCELED_WHEN'))) && in_array($newOrderStatus->id,$status_canceled))
            {
                $status=0;
            }
            else
                $status=-1;   
            foreach($commissions as $commission)
            {
                $ets_commission = new Ets_mp_commission($commission['id_seller_commission']);
                $ets_commission->status = $status;
                if(isset($expired_date))
                    $ets_commission->expired_date = $expired_date;
                $ets_commission->update();
            }
        }
    }
    public function hookDisplayShoppingCartFooter($params)
    {
        if(Configuration::get('ETS_MP_ALLOW_VOUCHER_IN_CART'))
        {
            if(($seller= $this->_getSeller(true)) && $seller->id_customer == $this->context->customer->id)
            {
                $currency_default = new Currency(Configuration::get('PS_CURRENCY_DEFAULT'));
                $commission_total_balance = $seller->getTotalCommission(1) - $seller->getToTalUseCommission(1);
                if($commission_total_balance >0 && (!Configuration::get('ETS_MP_MIN_BALANCE_REQUIRED_FOR_VOUCHER') || $commission_total_balance > Configuration::get('ETS_MP_MIN_BALANCE_REQUIRED_FOR_VOUCHER')) && (!Configuration::get('ETS_MP_MAX_BALANCE_REQUIRED_FOR_VOUCHER') || $commission_total_balance < Configuration::get('ETS_MP_MAX_BALANCE_REQUIRED_FOR_VOUCHER') ) )
                {
                    $this->context->smarty->assign(
                        array(
                            'commission_total_balance' => Tools::displayPrice($commission_total_balance,$currency_default),
                        )
                    );
                    return $this->display(__FILE__, 'cart-message.tpl');
                }
                
            }
        }
    }
    public function hookActionObjectOrderDetailDeleteAfter($params)
    {
        $context = Context::getContext();
        if(Configuration::get('ETS_MP_RECALCULATE_COMMISSION') && isset($context->employee->id) && $context->employee->id)
        {
            $orderDetail = $params['object'];
            $product = Db::getInstance()->getRow('SELECT product_name, sum(total_price_tax_excl) as total_price_tax_excl,sum(total_price_tax_incl) as total_price_tax_incl, sum(unit_price_tax_excl) as unit_price_tax_excl,sum(unit_price_tax_incl) as unit_price_tax_incl, sum(product_quantity) as product_quantity,product_id,product_attribute_id FROM `'._DB_PREFIX_.'order_detail` WHERE id_order="'.(int)$orderDetail->id_order.'" AND product_id="'.(int)$orderDetail->product_id.'" AND product_attribute_id="'.(int)$orderDetail->product_attribute_id.'"');
            if(!$product)
                Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'ets_mp_seller_commission` WHERE id_order="'.(int)$orderDetail->id_order.'" AND id_product="'.(int)$orderDetail->product_id.'" AND id_product_attribute="'.(int)$orderDetail->product_attribute_id.'"');
            else
                $this->changeCommissionWhenUpdateOrder($orderDetail);
        }
        
    }
    public function hookActionObjectOrderDetailAddAfter($params)
    {
        $context = Context::getContext();
        if(Configuration::get('ETS_MP_RECALCULATE_COMMISSION') && isset($context->employee->id) && $context->employee->id)
        {
            $this->changeCommissionWhenUpdateOrder($params['object']);
        }
    }
    public function hookActionObjectOrderDetailUpdateAfter($params)
    {
        $context = Context::getContext();
        if(Configuration::get('ETS_MP_RECALCULATE_COMMISSION') && isset($context->employee->id) && $context->employee->id)
        {
            $this->changeCommissionWhenUpdateOrder($params['object']);
        }
    }
    public function changeCommissionWhenUpdateOrder($orderDetail)
    {
        if(!Configuration::get('ETS_MP_ENABLED'))
            return true;
        $order = new Order($orderDetail->id_order);
        $product = Db::getInstance()->getRow('SELECT product_name, sum(total_price_tax_excl) as total_price_tax_excl,sum(total_price_tax_incl) as total_price_tax_incl, sum(unit_price_tax_excl) as unit_price_tax_excl,sum(unit_price_tax_incl) as unit_price_tax_incl, sum(product_quantity) as product_quantity,product_id,product_attribute_id FROM `'._DB_PREFIX_.'order_detail` WHERE id_order="'.(int)$orderDetail->id_order.'" AND product_id="'.(int)$orderDetail->product_id.'" AND product_attribute_id="'.(int)$orderDetail->product_attribute_id.'"');
        if(($id_customer = (int)Db::getInstance()->getValue('SELECT id_customer FROM `'._DB_PREFIX_.'ets_mp_seller_product` WHERE id_product='.(int)$product['product_id'])))
        {
            if(!Db::getInstance()->getValue('SELECT id_customer FROM `'._DB_PREFIX_.'ets_mp_seller_order` WHERE id_order="'.(int)$order->id.'" AND id_customer="'.(int)$id_customer.'"'))
            {
                Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'ets_mp_seller_order`(id_order,id_customer) VALUES("'.(int)$order->id.'","'.(int)$id_customer.'")');
            }
            if($id_commission = (int)Db::getInstance()->getValue('SELECT id_seller_commission FROM `'._DB_PREFIX_.'ets_mp_seller_commission` WHERE id_order="'.(int)$orderDetail->id_order.'" AND id_product="'.(int)$product['product_id'].'" AND id_product_attribute="'.(int)$product['product_attribute_id'].'"'))
                $commission = new Ets_mp_commission($id_commission); 
            else
                $commission = new Ets_mp_commission(); 
            $commission->id_product = (int)$product['product_id'];
            $commission->id_customer= $id_customer;
            $commission->id_order = (int)$order->id;
            $commission->id_product_attribute = (int)$product['product_attribute_id'];
            $commission->product_name = $product['product_name'];
            $commission->quantity = (int)$product['product_quantity'];
            $commission->price = (float)Tools::ps_round(Tools::convertPrice($product['unit_price_tax_excl'],null,false),6);  
            $commission->price_tax_incl = (float)Tools::ps_round(Tools::convertPrice($product['unit_price_tax_incl'],null,false),6);
            $commission->total_price = (float)Tools::ps_round(Tools::convertPrice($product['total_price_tax_excl'],null,false),6);
            $commission->total_price_tax_incl=(float)Tools::ps_round(Tools::convertPrice($product['total_price_tax_incl'],null,false),6);
            $commission->id_shop = $order->id_shop;
            $commission->date_add = date('Y-m-d H:i:s'); 
            $commission->date_upd = date('Y-m-d H:i:s'); 
            $seller = Ets_mp_seller::_getSellerByIdCustomer($id_customer);
            $commistion_rate = $seller->commission_rate!=0 ? (float)$seller->commission_rate: (float)$seller->getCommissionRate();
            
            if(Configuration::get('ETS_MP_COMMISSION_EXCLUDE_TAX'))
            {
                $commission->commission = (float)Tools::ps_round(Tools::convertPrice($product['total_price_tax_excl'],null,false) * $commistion_rate/100,6);
                $commission->use_tax=0;
            }
            else
            {
                $commission->commission = (float)Tools::ps_round(Tools::convertPrice($product['total_price_tax_incl'],null,false) * $commistion_rate/100,6);
                $commission->use_tax=1;
            }
            if(!$commission->id)
            {
                if(Configuration::get('ETS_MP_COMMISSION_PENDING_WHEN') && ($status_pedding = explode(',',Configuration::get('ETS_MP_COMMISSION_PENDING_WHEN'))) && in_array($order->current_state,$status_pedding))
                {
                    $commission->status=-1;
                }
                elseif(Configuration::get('ETS_MP_COMMISSION_APPROVED_WHEN') && ($status_approved = explode(',',Configuration::get('ETS_MP_COMMISSION_APPROVED_WHEN'))) && in_array($order->current_state,$status_approved))
                {
                    if(!$days = (int)Configuration::get('ETS_MP_VALIATE_COMMISSION_IN_DAYS'))
                        $commission->status =1;
                    else
                    {
                        $commission->status=-1;
                        $commission->expired_date = date('Y-m-d H:i:s',strtotime("+ $days days"));
                    }    
                }
                elseif(Configuration::get('ETS_MP_COMMISSION_CANCELED_WHEN') && ($status_canceled = explode(',',Configuration::get('ETS_MP_COMMISSION_CANCELED_WHEN'))) && in_array($order->current_state,$status_canceled))
                {
                    $commission->status =0;
                }
                else
                    $commission->status=-1;
                $commission->add();
            }
            else
                $commission->update();
        }
    }
    public function getEmailProductPurchasedTemplateContent($template, $products)
    {
        $content ='';
        if($products)
        {
            foreach($products as $product)
            {
                $product_link = $this->context->link->getProductLink($product['product_id']);
                if($template=='txt')
                    $content .= $product['product_name'].': '.$product_link."\n";
                else
                    $content .= '<'.'p'.'>'.'<'.'a hr'.'ef="'.$product_link.'" tar'.'get="_bl'.'ank">'.$product['product_name'].'<'.'/'.'a'.'>'.'<'.'/'.'p'.'>';
            }
        }
        return $content;
    }
    public function hookActionValidateOrder($params)
    {
        if (!Configuration::get('ETS_MP_ENABLED') ||  !(isset($params['cart'])) || !(isset($params['order'])) || !$params['cart'] || !($order = $params['order']))
            return;
        if($order->module == $this->name && ($seller_pay = $this->_getSeller()) && $seller_pay->id_customer == $this->context->customer->id)
        {
            $commission_usage = new Ets_mp_commission_usage();
            $commission_usage->amount = Tools::convertPrice($order->total_paid,null,false);
            $commission_usage->id_shop = $this->context->shop->id;
            $commission_usage->id_customer = $seller_pay->id_customer;
            $commission_usage->id_order = $order->id;
            $commission_usage->status = 1;
            $commission_usage->id_currency = $this->context->currency->id;
            $commission_usage->date_add = date('Y-m-d H:i:s');
            $commission_usage->note = $this->l('Paid for order #').$order->id;
            $commission_usage->add();
        }
        $products = $order->getProductsDetail();
        $sellers= array();
        if($products)
        {
            foreach($products as $product)
            {
                if(($id_customer = (int)Db::getInstance()->getValue('SELECT id_customer FROM `'._DB_PREFIX_.'ets_mp_seller_product` WHERE id_product='.(int)$product['product_id'])))
                {
                    $seller = Ets_mp_seller::_getSellerByIdCustomer($id_customer);
                    if($seller)
                    {
                        if(!in_array($id_customer,$sellers))
                        {
                            Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'ets_mp_seller_order`(id_order,id_customer) VALUES("'.(int)$order->id.'","'.(int)$id_customer.'")');
                            $sellers[] = $id_customer;
                            if(Configuration::get('ETS_MP_EMAIL_SELLER_PRODUCT_PURCHASED'))
                            {
                                $data = array(
                                    '{seller_name}' => $seller->seller_name,
                                    '{seller_shop}' => $seller->shop_name[$this->context->language->id],
                                    '{product_name}' => $product['product_name'],
                                    '{customer_name}' => $this->context->customer->firstname.' '.$this->context->customer->lastname,
                                    '{order_reference}' => $order->reference,
                                    '{product_detail_txt}' => $this->getEmailProductPurchasedTemplateContent('txt',$products),
                                    '{product_detail_tpl}' => $this->getEmailProductPurchasedTemplateContent('tpl',$products),
                                    '{total_payment}' => Tools::displayPrice(Tools::convertPrice($order->total_products_wt,null,false),new Currency(Configuration::get('PS_CURRENCY_DEFAULT'))),
                                    '{purchased_date}' => $order->date_add,
                                    '{order_status}' => $params['orderStatus']->name,
                                );
                                $subjects = array(
                                    'translation' => $this->l('Your product has been purchased'),
                                    'origin'=> 'Your product has been purchased',
                                    'specific'=>false
                                );
                                Ets_marketplace::sendMail('to_seller_product_purchased',$data,$seller->seller_email,$subjects,$seller->seller_name);
                            }
                        }
                        $commission = new Ets_mp_commission(); 
                        $commission->id_product = (int)$product['product_id'];
                        $commission->id_customer= $id_customer;
                        $commission->id_order = (int)$order->id;
                        $commission->id_product_attribute = (int)$product['product_attribute_id'];
                        $commission->product_name = $product['product_name'];
                        $commission->quantity = (int)$product['product_quantity'];
                        $commission->price = (float)Tools::ps_round(Tools::convertPrice($product['unit_price_tax_excl'],null,false),6);  
                        $commission->price_tax_incl = (float)Tools::ps_round(Tools::convertPrice($product['unit_price_tax_incl'],null,false),6);
                        $commission->total_price = (float)Tools::ps_round(Tools::convertPrice($product['total_price_tax_excl'],null,false),6);
                        $commission->total_price_tax_incl=(float)Tools::ps_round(Tools::convertPrice($product['total_price_tax_incl'],null,false),6);
                        $commission->id_shop = $order->id_shop;
                        $commission->date_add = date('Y-m-d H:i:s'); 
                        $commission->date_upd = date('Y-m-d H:i:s'); 
                        $commistion_rate = $seller->commission_rate!=0 ? (float)$seller->commission_rate: (float)$seller->getCommissionRate();
                        if(Configuration::get('ETS_MP_COMMISSION_EXCLUDE_TAX'))
                        {
                            $commission->commission = (float)Tools::ps_round(Tools::convertPrice($product['total_price_tax_excl'],null,false) * $commistion_rate/100,6);
                            $commission->use_tax=0;
                        }
                        else
                        {
                            $commission->commission = (float)Tools::ps_round(Tools::convertPrice($product['total_price_tax_incl'],null,false) * $commistion_rate/100,6);
                            $commission->use_tax=1;
                        }
                        if(Configuration::get('ETS_MP_COMMISSION_PENDING_WHEN') && ($status_pedding = explode(',',Configuration::get('ETS_MP_COMMISSION_PENDING_WHEN'))) && in_array($params['orderStatus']->id,$status_pedding))
                        {
                            $commission->status=-1;
                        }
                        elseif(Configuration::get('ETS_MP_COMMISSION_APPROVED_WHEN') && ($status_approved = explode(',',Configuration::get('ETS_MP_COMMISSION_APPROVED_WHEN'))) && in_array($params['orderStatus']->id,$status_approved))
                        {
                            if(!$days = (int)Configuration::get('ETS_MP_VALIATE_COMMISSION_IN_DAYS'))
                                $commission->status =1;
                            else
                            {
                                $commission->status=-1;
                                $commission->expired_date = date('Y-m-d H:i:s',strtotime("+ $days days"));
                            }    
                        }
                        elseif(Configuration::get('ETS_MP_COMMISSION_CANCELED_WHEN') && ($status_canceled = explode(',',Configuration::get('ETS_MP_COMMISSION_CANCELED_WHEN'))) && in_array($params['orderStatus']->id,$status_canceled))
                        {
                            $commission->status =0;
                        }
                        else
                            $commission->status=-1;
                        $commission->add();
                    }
                }
            }
        }
        if(Configuration::get('ETS_MP_RETURN_SHIPPING'))
        {
            $id_customer_seller = Db::getInstance()->getValue('SELECT cs.id_customer FROM '._DB_PREFIX_.'ets_mp_carrier_seller cs
            INNER JOIN '._DB_PREFIX_.'carrier c ON (cs.id_carrier_reference = c.id_reference)
            WHERE c.id_carrier = "'.(int)$order->id_carrier.'"');
            if($id_customer_seller && ($seller = Ets_mp_seller::_getSellerByIdCustomer($id_customer_seller)))
            {
                $commission = new Ets_mp_commission(); 
                $commission->id_product = 0;
                $commission->id_customer= $id_customer_seller;
                $commission->id_order = (int)$order->id;
                $commission->id_product_attribute = 0;
                $commission->product_name = $this->l('Return shipping fee from order #').$order->id;
                $commission->quantity = 1;
                $commission->price = (float)Tools::ps_round(Tools::convertPrice($order->total_shipping_tax_excl,null,false),6);  
                $commission->price_tax_incl = (float)Tools::ps_round(Tools::convertPrice($order->total_shipping_tax_incl,null,false),6);
                $commission->total_price = (float)Tools::ps_round(Tools::convertPrice($order->total_shipping_tax_excl,null,false),6);
                $commission->total_price_tax_incl=(float)Tools::ps_round(Tools::convertPrice($order->total_shipping_tax_incl,null,false),6);
                $commission->id_shop = $order->id_shop;
                $commission->date_add = date('Y-m-d H:i:s'); 
                $commission->date_upd = date('Y-m-d H:i:s'); 
                $commistion_rate = $seller->commission_rate!=0 ? (float)$seller->commission_rate: (float)$seller->getCommissionRate();
                if(Configuration::get('ETS_MP_COMMISSION_EXCLUDE_TAX'))
                {
                    $commission->commission = (float)Tools::ps_round(Tools::convertPrice($order->total_shipping_tax_excl,null,false),6);
                    $commission->use_tax=0;
                }
                else
                {
                    $commission->commission = (float)Tools::ps_round(Tools::convertPrice($order->total_shipping_tax_incl,null,false),6);
                    $commission->use_tax=1;
                }
                if(Configuration::get('ETS_MP_COMMISSION_PENDING_WHEN') && ($status_pedding = explode(',',Configuration::get('ETS_MP_COMMISSION_PENDING_WHEN'))) && in_array($params['orderStatus']->id,$status_pedding))
                {
                    $commission->status=-1;
                }
                elseif(Configuration::get('ETS_MP_COMMISSION_APPROVED_WHEN') && ($status_approved = explode(',',Configuration::get('ETS_MP_COMMISSION_APPROVED_WHEN'))) && in_array($params['orderStatus']->id,$status_approved))
                {
                    if(!$days = (int)Configuration::get('ETS_MP_VALIATE_COMMISSION_IN_DAYS'))
                        $commission->status =1;
                    else
                    {
                        $commission->status=-1;
                        $commission->expired_date = date('Y-m-d H:i:s',strtotime("+ $days days"));
                    }    
                }
                elseif(Configuration::get('ETS_MP_COMMISSION_CANCELED_WHEN') && ($status_canceled = explode(',',Configuration::get('ETS_MP_COMMISSION_CANCELED_WHEN'))) && in_array($params['orderStatus']->id,$status_canceled))
                {
                    $commission->status =0;
                }
                else
                    $commission->status=-1;
                $commission->add();
            }
        }
    }
    public function hookModuleRoutes()
    {
        $subfix = (int)Configuration::get('ETS_MP_URL_SUBFIX') ? '.html' : '';
        $shopAlias = Configuration::get('ETS_MP_SHOP_ALIAS',$this->context->language->id)?:'shops';
        if(!$shopAlias)
            return array();
        Configuration::deleteByName('PS_ROUTE_etsmpshops');
        Configuration::deleteByName('PS_ROUTE_etsmpshopsseller');
        $routes = array(
            'etsmpshops' => array(
                'controller' => 'shop',
                'rule' => $shopAlias,
                'keywords' => array(),
                'params' => array(
                    'fc' => 'module',
                    'module' => $this->name,
                ),
            ),
            'etsmpshopsseller' => array(
                'controller' => 'shop',
                'rule' => $shopAlias.'/{id_seller}-{url_alias}'.$subfix,
                'keywords' => array(
                    'url_alias'       =>   array('regexp' => '[_a-zA-Z0-9-]+','param' => 'url_alias'),
                    'id_seller' =>    array('regexp' => '[0-9]+', 'param' => 'id_seller'),
                ),
                'params' => array(
                    'fc' => 'module',
                    'module' => $this->name,
                ),
            ),
        );
        return $routes;
    }
    public function getLangLinkFriendly($id_lang = null, Context $context = null, $id_shop = null)
	{
		if (!$context)
			$context = Context::getContext();

		if ((!Configuration::get('PS_REWRITING_SETTINGS') && in_array($id_shop, array($context->shop->id,  null))) || !Language::isMultiLanguageActivated($id_shop) || !(int)Configuration::get('PS_REWRITING_SETTINGS', null, null, $id_shop))
			return '';

		if (!$id_lang)
			$id_lang = $context->language->id;

		return Language::getIsoById($id_lang).'/';
	}
	
	public function getBaseLinkFriendly($id_shop = null, $ssl = null)
	{
		static $force_ssl = null;
		
		if ($ssl === null)
		{
			if ($force_ssl === null)
				$force_ssl = (Configuration::get('PS_SSL_ENABLED') && Configuration::get('PS_SSL_ENABLED_EVERYWHERE'));
			$ssl = $force_ssl;
		}

		if (Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') && $id_shop !== null)
			$shop = new Shop($id_shop);
		else
			$shop = Context::getContext()->shop;

		$base = ($ssl ? 'https://'.$shop->domain_ssl : 'http://'.$shop->domain);

		return $base.$shop->getBaseURI();
	}
    public function getShopLink($params = array())
    {
        $context = Context::getContext();      
        $id_lang =  $context->language->id;
        $subfix = (int)Configuration::get('ETS_MP_URL_SUBFIX') ? '.html' : '';
        $alias = Configuration::get('ETS_MP_SHOP_ALIAS',$this->context->language->id) ?:'shops';
        $friendly = Configuration::get('PS_REWRITING_SETTINGS');        
        if($friendly && $alias)
        {    
            $url = $this->getBaseLinkFriendly(null, null).$this->getLangLinkFriendly($id_lang, null, null).$alias; 
            if(isset($params['id_seller']) && $params['id_seller'])
            {
                
                $seller = new Ets_mp_seller($params['id_seller'],$id_lang);

                $url .= '/'.$seller->id.'-'.Tools::link_rewrite($seller->shop_name).$subfix;
                unset($params['id_seller']);
            }
            if($params)
            {
                $extra='';
                foreach($params as $key=> $param)
                    $extra .='&'.$key.'='.$param;
                $url .= '?'.ltrim($extra,'&');
            }
            return $url;       
        }
        else
            return $this->context->link->getModuleLink($this->name,'shop',$params);
    }
    public function hookDisplayHeader()
    {
        //http://api.ipstack.com/116.104.138.251?access_key=8c2b6fa4488371c6c82474c15ea80b32
        if(Tools::isSubmit('submitReportShopSeller'))
        {
            Ets_mp_report::submitReportShop($this);
        }
        if(Tools::isSubmit('i_have_just_sent_the_fee') && ($seller= $this->_getSeller()))
        {
 
            $seller->confirmedPayment();
        }
        if(Tools::getValue('module')==$this->name || Tools::getValue('controller')=='myaccount')
        {
            $this->context->smarty->assign(
                array(
                    'is17' => $this->is17,
                )
            );
            $this->context->controller->addJqueryPlugin('growl');
            $this->context->controller->addJqueryUI('ui.tooltip');
            $this->context->controller->addJqueryUI('ui.effect');
            $this->context->controller->addJqueryUI('ui.datepicker');
            $this->context->controller->addCSS($this->_path.'views/css/front.css'); 
            if(Tools::getValue('controller')=='carrier')
            {
                if(Tools::isSubmit('addnew') || (Tools::isSubmit('editmp_carrier') && Tools::getValue('id_carrier')))
                {
                    $this->context->controller->addJqueryPlugin('smartWizard');
                    $this->context->controller->addJqueryPlugin('typewatch');
                    if($this->is17)
                    {
                        $this->context->controller->registerJavascript('modules-ets_marketplace-carrier','modules/'.$this->name.'/views/js/carrier.js', ['position' => 'bottom', 'priority' => 160]);
                    }
                    else
                       $this->context->controller->addJS($this->_path.'views/js/carrier.js'); 
                }
            }    
            if(!$this->is17)
                $this->context->controller->addCSS($this->_path.'views/css/front16.css');
            $this->context->controller->addCSS($this->_path.'views/css/autosearch.css');
            if(Tools::getValue('controller')=='products')
            {
                $this->context->controller->addJqueryPlugin('fancybox');
                $this->context->controller->addJqueryUI('ui.sortable');
            }
            if(Tools::getValue('controller')=='dashboard')
            {
                $this->context->controller->addCSS($this->_path.'views/css/daterangepicker.css');
                if($this->is17)
                {
                    $this->context->controller->registerJavascript('modules-ets_marketplace-chart','modules/'.$this->name.'/views/js/Chart.min.js', ['position' => 'bottom', 'priority' => 153]);
                    $this->context->controller->registerJavascript('modules-ets_marketplace-moment','modules/'.$this->name.'/views/js/moment.min.js', ['position' => 'bottom', 'priority' => 154]);
                    $this->context->controller->registerJavascript('modules-ets_marketplace-date','modules/'.$this->name.'/views/js/daterangepicker.js', ['position' => 'bottom', 'priority' => 154]);
                    $this->context->controller->registerJavascript('modules-ets_marketplace-dashboard','modules/'.$this->name.'/views/js/front_dashboard.js', ['position' => 'bottom', 'priority' => 155]);
                }
                else
                {
                    $this->context->controller->addJS($this->_path.'views/js/Chart.min.js');
                    $this->context->controller->addJS($this->_path.'views/js/moment.min.js');
                    $this->context->controller->addJS($this->_path.'views/js/daterangepicker.js');
                    $this->context->controller->addJS($this->_path.'views/js/front_dashboard.js');
                }
                
            }
            if(!$this->is17 && Tools::getValue('controller')=='shop')
            {
                $this->context->controller->addCSS(_THEME_CSS_DIR_.'product_list.css', 'all');
            }
            if(Tools::getValue('controller')=='contactseller')
            {
                if($this->is17)
                {
                    $this->context->controller->registerJavascript('modules-ets_marketplace-contact','modules/'.$this->name.'/views/js/contact.js', ['position' => 'bottom', 'priority' => 153]);
                }
                else
                    $this->context->controller->addJS($this->_path.'views/js/contact.js');
            }

            if($this->is17)
            {
                $this->context->controller->registerJavascript('modules-ets_marketplace-auto','modules/'.$this->name.'/views/js/autosearch.js', ['position' => 'bottom', 'priority' => 150]);
                $this->context->controller->registerJavascript('modules-ets_marketplace','modules/'.$this->name.'/views/js/front.js', ['position' => 'bottom', 'priority' => 151]);
                $this->context->controller->registerJavascript('modules-ets_marketplace-multi-upload','modules/'.$this->name.'/views/js/multi_upload.js', ['position' => 'bottom', 'priority' => 151]); 
                $this->context->controller->registerJavascript('js-jquery-plugins-timepicker','js/jquery/plugins/timepicker/jquery-ui-timepicker-addon.js', ['position' => 'bottom', 'priority' => 151]);
            }    
            else
            {
                $this->context->controller->addJS($this->_path.'views/js/autosearch.js');
                $this->context->controller->addJS($this->_path.'views/js/front.js');
                $this->context->controller->addJS($this->_path.'views/js/front16.js');
                $this->context->controller->addJS($this->_path.'views/js/multi_upload.js');
                $this->context->controller->addJS(_PS_JS_DIR_ . 'jquery/plugins/timepicker/jquery-ui-timepicker-addon.js');
            }
            $this->context->controller->addCSS(_PS_JS_DIR_ . 'jquery/plugins/timepicker/jquery-ui-timepicker-addon.css');
            if(Tools::getValue('controller')=='products')
            {
                if($this->is17)
                    $this->context->controller->registerJavascript('modules-ets_marketplace-product-bulk','modules/'.$this->name.'/views/js/product_bulk.js', ['position' => 'bottom', 'priority' => 160]);
                else
                    $this->context->controller->addJS($this->_path.'views/js/product_bulk.js');
            }
            if(Tools::getValue('controller')=='stock')
            {
                if($this->is17)
                    $this->context->controller->registerJavascript('modules-ets_marketplace-product-stock','modules/'.$this->name.'/views/js/stock.js', ['position' => 'bottom', 'priority' => 160]);
                else
                    $this->context->controller->addJS($this->_path.'views/js/stock.js');
            }  
        }
        $this->context->controller->addCSS($this->_path.'views/css/home.css');
        if(Tools::getValue('controller')=='order')
            $this->context->controller->addCSS($this->_path.'views/css/payment.css');
        if(Tools::getValue('controller')=='index' || Tools::getValue('controller')=='product' )
        {

            if(!$this->is17)
                $this->context->controller->addCSS(_PS_THEME_DIR_.$this->context->shop->theme_name.'/css/product_list.css','all');
            if($this->is17)
            {
                $this->context->controller->registerJavascript('modules-ets_marketplace-stick','modules/'.$this->name.'/views/js/slick.min.js', ['position' => 'bottom', 'priority' => 150]);
                $this->context->controller->registerJavascript('modules-ets_marketplace-follow','modules/'.$this->name.'/views/js/product_follow.js', ['position' => 'bottom', 'priority' => 150]);
            } 
            else
            {
                $this->context->controller->addJS($this->_path.'views/js/slick.min.js');
                $this->context->controller->addJS($this->_path.'views/js/product_follow.js');
            }
            $this->context->controller->addCSS($this->_path.'views/css/slick.css');
        }
        $this->context->controller->addJqueryPlugin('growl');
        if($this->is17)
            $this->context->controller->registerJavascript('modules-ets_marketplace-product-detail','modules/'.$this->name.'/views/js/report.js', ['position' => 'bottom', 'priority' => 154]);
        else
            $this->context->controller->addJS($this->_path.'views/js/report.js');
        $this->context->controller->addCSS($this->_path.'views/css/report.css');
        if(Tools::getValue('controller')=='cart' || Tools::getValue('controller')=='order' || Tools::getValue('controller')=='onepagecheckout')
        {
            if($this->is17)
                $this->context->controller->registerJavascript('modules-ets_marketplace-cart','modules/'.$this->name.'/views/js/cart.js', ['position' => 'bottom', 'priority' => 153]);
            else
                $this->context->controller->addJS($this->_path.'views/js/cart.js');
            
        }
        $this->context->smarty->assign(
            array(
                'colorImageFolder' => $this->getBaseLink().'/img/admin/',
            )
        );
        if($settings = Ets_mp_defines::getInstance()->getFieldConfig('settings'))
        {
            foreach($settings as $setting)
            {
                if(isset($setting['lang']))
                {
                    $text = Configuration::get($setting['name'],$this->context->language->id) ? : (isset($setting['default']) ? $setting['default']:'');
                    $this->context->smarty->assign(
                        array(
                            $setting['name'] => $this->_replaceTag($text),
                        )
                    );
                }
                else
                {
                    if($setting['type']=='switch')
                    {
                        $this->context->smarty->assign(
                            array(
                                $setting['name'] => (int)Configuration::get($setting['name']),
                            )
                        );
                    }
                    else
                    $this->context->smarty->assign(
                        array(
                            $setting['name'] => Configuration::get($setting['name']) ? : (isset($setting['default']) ? $setting['default']:'' ),
                        )
                    );
                }    
            }
        }
        if(Configuration::get('ETS_MP_ENABLE_MAP') && Tools::getValue('module')==$this->name && (Tools::getValue('controller')=='shop' || Tools::getValue('controller')=='map'))
        {
            $default_country = new Country((int)Tools::getCountry());
            if(Configuration::get('ETS_MP_SEARCH_ADDRESS_BY_GOOGLE') && ($map_key = Configuration::get('ETS_MP_GOOGLE_MAP_API')))
                $key ='key='.$map_key.'&';
            else
                $key='';
            $link_map_google = 'http'.((Configuration::get('PS_SSL_ENABLED') && Configuration::get('PS_SSL_ENABLED_EVERYWHERE')) ? 's' : '').'://maps.googleapis.com/maps/api/js?'.$key.'region='.Tools::substr($default_country->iso_code, 0, 2);
            if($this->is17)
            {
                if(Tools::getValue('controller')=='shop')
                    $this->context->controller->registerJavascript('modules-ets_marketplace-google-map',$link_map_google, ['position' => 'bottom', 'priority' => 150,'server' =>'remote','inline' => true]);
                $this->context->controller->registerJavascript('modules-ets_marketplace-map','modules/'.$this->name.'/views/js/map.js', ['position' => 'bottom', 'priority' => 153]);
            }
            else
            {
                if(Tools::getValue('controller')=='shop')
                {
                    $this->context->controller->addJS($link_map_google);
                    $this->context->controller->addJS($this->_path.'views/js/map.js');
                }              
            }
            $this->context->controller->addCSS($this->_path.'views/css/map.css');
        }
        return $this->display(__FILE__,'header.tpl');
    }
    public function hookDisplayHome()
    {
        if(!Configuration::get('ETS_MP_ENABLED'))
            return '';
        $html= '';
        if(Configuration::get('ETS_MP_DISPLAY_PRODUCT_TRENDING_SHOP') && ($nbProducts = (int)Configuration::get('ETS_MP_DISPLAY_NUMBER_PRODUCT_TRENDING_SHOP')) && ($days = (int)Configuration::get('ETS_MP_TRENDING_PERIOD_SHOP') ))
        {
            $trending_products = $this->getTrendingProducts($nbProducts,$days);
            if(!$trending_products)
                $trending_products = $this->getTrendingProducts($nbProducts);
            $this->context->smarty->assign(
                array(
                    'products' => $trending_products,
                    'position'=>''
                )
            );
            if($this->is17)
                $html .= $this->display(__FILE__,'trending_product.tpl');
            else
                $html .= $this->display(__FILE__,'trending_product16.tpl');
        }
        if($this->context->customer->logged)
        {
            if($sellers = Db::getInstance()->executeS('SELECT seller.id_customer FROM 
            `'._DB_PREFIX_.'ets_mp_seller_customer_follow` scl
            INNER JOIN `'._DB_PREFIX_.'ets_mp_seller` seller ON (seller.id_seller=scl.id_seller AND seller.active=1)
            WHERE scl.id_customer="'.(int)$this->context->customer->id.'"'))
            {
                
                $id_sellers = array();
                foreach($sellers as $seller)
                {
                    $id_sellers[] = $seller['id_customer'];
                }
                if(Configuration::get('ETS_MP_DISPLAY_PRODUCT_FOLLOWED_SHOP') && ($number_product = (int)Configuration::get('ETS_MP_DISPLAY_NUMBER_PRODUCT_FOLLOWED_SHOP')))
                {
                    $front = true;
                    $nb_days_new_product = Configuration::get('PS_NB_DAYS_NEW_PRODUCT');
                    $id_lang = (int)Context::getContext()->language->id;
                    if (!Validate::isUnsignedInt($nb_days_new_product)) {
                        $nb_days_new_product = 20;
                    }
                    $prev_version = version_compare(_PS_VERSION_, '1.6.1.0', '<');
                    $sql ='SELECT DISTINCT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) AS stock_quantity' . ($prev_version? ' ,IFNULL(product_attribute_shop.id_product_attribute, 0)':' ,MAX(product_attribute_shop.id_product_attribute)') . ' id_product_attribute, pl.`description`, pl.`description_short`, pl.`available_now`,
        					pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, i.`id_image`,
        					il.`legend` as legend, m.`name` AS manufacturer_name,cl.name as default_category,
        					DATEDIFF(product_shop.`date_add`, DATE_SUB("' . date('Y-m-d') . ' 00:00:00",
        					INTERVAL ' . (int)$nb_days_new_product . ' DAY)) > 0 AS new, product_shop.price AS orderprice,sp.approved';
                    $sql .= ' FROM `'._DB_PREFIX_.'product` p
                            '.Shop::addSqlAssociation('product', 'p').
                            (!$prev_version?
                                'LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (pa.id_product = p.id_product)'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.default_on=1').'':
                                'LEFT JOIN `'._DB_PREFIX_.'product_attribute_shop` product_attribute_shop ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop='.(int)$this->context->shop->id.')'
                            )
                            .Product::sqlStock('p', 0, false, Context::getContext()->shop).'
                            LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_product` sp ON (sp.id_product=p.id_product)
                            LEFT JOIN `'._DB_PREFIX_.'category` c ON (c.id_category=p.id_category_default)
                            LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.id_product=p.id_product)
                            LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.id_category = cl.id_category AND cl.id_lang="'.(int)$id_lang.'")
                            LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = ' . (int)$id_lang . Shop::addSqlRestrictionOnLang('pl') . ')'.'
                            LEFT JOIN `' . _DB_PREFIX_ . 'image` i ON (i.`id_product` = p.`id_product` AND i.cover=1)
                            LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')	
                            LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
                            WHERE product_shop.active=1 AND sp.id_customer IN ('.implode(',',array_map('intval',$id_sellers)).') AND product_shop.`id_shop` = ' . (int)Context::getContext()->shop->id.($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : ''); 
                    $sql .= ' GROUP BY p.id_product ORDER BY RAND() LIMIT 0,'.(int)$number_product;
                    $products = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql, true, true);
                    if($products)
                    {
                        $products = Product::getProductsProperties($id_lang, $products);        
                        if(version_compare(_PS_VERSION_, '1.7', '>=')) {
                            $products = Ets_marketplace::productsForTemplate($products);
                        }
                        $this->context->smarty->assign(
                            array(
                                'products' => $products,
                                'position'=>''
                            )
                        );
                        if($this->is17)
                            $html .= $this->display(__FILE__,'product_seller_follow.tpl');
                        else
                            $html .= $this->display(__FILE__,'product_seller_follow16.tpl');
                    }
                }
                if(Configuration::get('ETS_MP_DISPLAY_FOLLOWED_SHOP') && ($number_shop = (int)Configuration::get('ETS_MP_DISPLAY_NUMBER_SHOP')))
                {
                    $sql = 'SELECT s.*,CONCAT(c.firstname," ", c.lastname) as customer_name,sl.shop_name,sl.shop_address,sl.shop_description,top_order_seller.total_order,seller_product.total_product 
                    FROM `'._DB_PREFIX_.'ets_mp_seller` s
                        LEFT JOIN (
                            SELECT seller.id_seller as id_seller,COUNT(DISTINCT seller_order.id_order) as total_order
                            FROM `'._DB_PREFIX_.'ets_mp_seller_order` seller_order
                            INNER JOIN `'._DB_PREFIX_.'orders` o ON(o.id_order=seller_order.id_order)
                            INNER JOIN `'._DB_PREFIX_.'ets_mp_seller` seller ON (seller_order.id_customer=seller.id_customer)
                            WHERE o.id_shop="'.(int)$this->context->shop->id.'" GROUP BY seller.id_seller
                        ) as top_order_seller ON (top_order_seller.id_seller=s.id_seller)
                        LEFT JOIN (
                            SELECT seller.id_seller,count(sp.id_product) as total_product FROM `'._DB_PREFIX_.'ets_mp_seller_product` sp
                            INNER JOIN `'._DB_PREFIX_.'product` p ON (sp.id_product= p.id_product AND p.active=1)
                            INNER JOIN `'._DB_PREFIX_.'ets_mp_seller` seller ON(seller.id_customer=sp.id_customer)
                            GROUP BY seller.id_seller
                        ) as seller_product ON (seller_product.id_seller=s.id_seller)
                        LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_lang` sl ON (s.id_seller = sl.id_seller AND sl.id_lang ="'.(int)Context::getContext()->language->id.'")
                        LEFT JOIN `'._DB_PREFIX_.'customer` c ON (s.id_customer=c.id_customer)
                        WHERE s.id_shop="'.(int)Context::getContext()->shop->id.'" AND s.active=1 AND s.id_customer IN ('.implode(',',array_map('intval',$id_sellers)).') ORDER BY top_order_seller.total_order DESC LIMIT 0,'.(int)$number_shop;
                    $sellers = Db::getInstance()->executeS($sql);
                    if($sellers)
                    {
                        foreach($sellers as &$seller)
                        {
                            $seller['link'] = $this->getShopLink(array('id_seller'=>$seller['id_seller']));
                        }
                    }
                    $this->context->smarty->assign(
                        array(
                            'sellers'=> $sellers,
                            'link_base' => $this->getBaseLink(),
                        )
                    );
                    $html .= $this->display(__FILE__,'seller_follow.tpl');
                }
            }
        }
        // top _shop
        $sellers = Ets_mp_seller::_getSellers(' AND seller_sale.total_sale >0','seller_sale.total_sale DESC',0,12);
        if($sellers)
        {
            foreach($sellers as &$seller)
            {
                $seller['link'] = $this->getShopLink(array('id_seller'=>$seller['id_seller']));
            }
        }
        $this->context->smarty->assign(
            array(
                'sellers'=> $sellers,
                'link_base' => $this->getBaseLink(),
            )
        );
        $html .= $this->display(__FILE__,'home_top_seller.tpl');
        return $html;
    }
    public function getTrendingProducts($nbProducts,$day=0)
    {
        if($day)
            $date = strtotime("-$day day", strtotime(date('Y-m-d')));
        $active = true;
        $front = true;
        $nb_days_new_product = Configuration::get('PS_NB_DAYS_NEW_PRODUCT');
        $id_lang = (int)$this->context->language->id;
        if (!Validate::isUnsignedInt($nb_days_new_product)) {
            $nb_days_new_product = 20;
        }
        $prev_version = version_compare(_PS_VERSION_, '1.6.1.0', '<');
        $order_by = ' total_sale DESC';
        $sql = 'SELECT DISTINCT p.*,count(DISTINCT od.id_order) as total_sale ,product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) AS quantity' . ($prev_version? ' ,IFNULL(product_attribute_shop.id_product_attribute, 0)':' ,MAX(product_attribute_shop.id_product_attribute)') . ' id_product_attribute, pl.`description`, pl.`description_short`, pl.`available_now`,
    					pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, IFNULL(image_shop.`id_image`, i.`id_image`) id_image,
    					il.`legend` as legend, m.`name` AS manufacturer_name, cl.`name` AS category_default,
    					DATEDIFF(product_shop.`date_add`, DATE_SUB("' . date('Y-m-d') . ' 00:00:00",
    					INTERVAL ' . (int)$nb_days_new_product . ' DAY)) > 0 AS new, product_shop.price AS orderprice
                FROM `' . _DB_PREFIX_ . 'category_product` cp
                INNER JOIN '._DB_PREFIX_.'order_detail od ON (od.product_id = cp.id_product)
                INNER JOIN '._DB_PREFIX_.'orders o ON (od.id_order=o.id_order)
                LEFT JOIN `'._DB_PREFIX_.'product` p ON p.`id_product` = cp.`id_product`
                '.Shop::addSqlAssociation('product', 'p').
                ' LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl ON (product_shop.`id_category_default` = cl.`id_category` AND cl.`id_lang` = ' . (int)$id_lang . Shop::addSqlRestrictionOnLang('cl') . ')'.
                (!$prev_version?
                    'LEFT JOIN '._DB_PREFIX_.'product_attribute pa ON (pa.id_product = p.id_product)'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.default_on=1').'':
                    'LEFT JOIN `'._DB_PREFIX_.'product_attribute_shop` product_attribute_shop ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop='.(int)$this->context->shop->id.')'
                ).
                (
                    Tools::getValue('id_ets_css_sub_category')?' LEFT JOIN '._DB_PREFIX_.'category_product cp2 ON (cp2.id_product=p.id_product)':''
                )
                .Product::sqlStock('p', 0, false, $this->context->shop).'
                LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = ' . (int)$id_lang . Shop::addSqlRestrictionOnLang('pl') . ')'.
                (!$prev_version?
                    'LEFT JOIN `' . _DB_PREFIX_ . 'image` i ON (i.`id_product` = p.`id_product`)'. Shop::addSqlAssociation('image', 'i', false, 'image_shop.cover = 1') :
                    'LEFT JOIN `' . _DB_PREFIX_ . 'image_shop` image_shop ON (image_shop.`id_product` = p.`id_product` AND image_shop.id_shop=' . (int)$this->context->shop->id . ')'
                ).'
                LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')	
                LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
                WHERE '.($day ? 'o.date_add >="'.pSQL(date('Y-m-d', $date)).'"':'1').' AND  product_shop.`id_shop` = ' . (int)$this->context->shop->id
                .($active ? ' AND product_shop.`active` = 1' : '')
                .(Tools::getValue('id_ets_css_sub_category') ? ' AND cp2.id_category="'.(int)Tools::getValue('id_ets_css_sub_category').'"':'')
                . ($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '')
                .($this->context->cart->id ? ' AND product_shop.id_product NOT IN (SELECT id_product FROM '._DB_PREFIX_.'cart_product WHERE id_cart="'.(int)$this->context->cart->id.'")':'')
                . ' GROUP BY p.id_product'
                . ( ($order_by) ? ($order_by != 'rand' ? ' ORDER BY ' . pSQL($order_by) : ' ORDER BY RAND()') : '') . '
                LIMIT  0,' . (int)$nbProducts;
        $products = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        if (!$products) {
            return array();
        }
        if($this->is17)
            return Ets_marketplace::productsForTemplate($products);
        else
            return Product::getProductsProperties($id_lang, $products);
    }
    public function hookDisplayETSMPFooterYourAccount()
    {
        $this->context->smarty->assign(
            array(
                'is17' => $this->is17,
                'seller_account' => Tools::getValue('controller')!='myseller' && Tools::getValue('controller')!='contactseller' && Tools::getValue('controller')!='registration' ?  $this->context->link->getModuleLink($this->name,'myseller'):'', 
            )
        );
        return $this->display(__FILE__,'footer_my_account.tpl');
    }
    public function hookDisplayFooter()
    {
        if(!Configuration::get('ETS_MP_ENABLED'))
            return '';
        if(Tools::getValue('controller')=='map' && !$this->is17 && Configuration::get('ETS_MP_ENABLE_MAP'))
        {
            $default_country = new Country((int)Tools::getCountry());
            if(($map_key = Configuration::get('ETS_MP_GOOGLE_MAP_API')))
                $key ='key='.$map_key.'&';
            else
                $key='';
            $link_map_google = 'http'.((Configuration::get('PS_SSL_ENABLED') && Configuration::get('PS_SSL_ENABLED_EVERYWHERE')) ? 's' : '').'://maps.googleapis.com/maps/api/js?'.$key.'region='.Tools::substr($default_country->iso_code, 0, 2);
            $this->context->smarty->assign(
                array(
                    'link_map_google' => $link_map_google,
                    'link_map_js' =>$this->_path.'views/js/map.js',
                    'ETS_MP_GOOGLE_MAP_API' => $map_key,
                )
            );
            return $this->display(__FILE__,'footer_map_js.tpl');
        }
        if(Configuration::get('ETS_MP_SELLER_ALLOWED_EMBED_CHAT'))
        {
            if(Tools::getValue('controller')=='product' && $id_product = Tools::getValue('id_product'))
            {
                if($id_customer = Db::getInstance()->getValue('SELECT id_customer FROM `'._DB_PREFIX_.'ets_mp_seller_product` WHERE id_product='.(int)$id_product))
                {
                    $seller = Ets_mp_seller::_getSellerByIdCustomer($id_customer,$this->context->language->id);
                }
            }
            if(Tools::getValue('fc') =='module' && Tools::getValue('module')== $this->name && Tools::getValue('controller')=='shop' && $id_seller = (int)Tools::getValue('id_seller'))
                $seller = new Ets_mp_seller($id_seller);
            if(isset($seller) && $seller &&  $seller->active==1 && $seller->code_chat)
            {
                $this->context->smarty->assign(
                    array(
                        'code_chat' => $seller->code_chat,
                    )
                );
                return $this->display(__FILE__,'footer.tpl');
            }
        }
        if(($products =$this->context->cart->getProducts()) && (Tools::getValue('controller')=='cart' || Tools::getValue('controller')=='onepagecheckout' ) )
        {
            $sellers = array();
            foreach($products as $product)
            {
                if(!isset($sellers[$product['id_product']]))
                {
                    $sql = 'SELECT p.id_customer FROM `'._DB_PREFIX_.'ets_mp_seller_product` p
                    INNER JOIN `'._DB_PREFIX_.'ets_mp_seller` seller ON (p.id_customer=seller.id_customer)
                    WHERE p.id_product='.(int)$product['id_product'].' AND seller.active!=0';
                    if($id_customer = (int)Db::getInstance()->getValue($sql))
                    {
                        $seller= Ets_mp_seller::_getSellerByIdCustomer($id_customer,$this->context->language->id);
                        $this->context->smarty->assign(
                            array(
                                'link_shop_seller' => $this->getShopLink(array('id_seller'=>$seller->id)),
                                'shop_name' => $seller->shop_name,
                                'link_contact_form' => $this->context->link->getModuleLink($this->name,'contactseller',array('id_product'=>$product['id_product'])),
                            )
                        );
                        $sellers[$product['id_product']] = $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->name.'/views/templates/hook/product/cart_detail.tpl');
                        
                    }
                }
            }
            if($sellers)
            {
                $this->context->smarty->assign(
                    array(
                        'sellers' => $sellers,
                    )
                );
                return $this->display(__FILE__,'sellers_cart.tpl');
            }
        }
    }
    public function checkGroupCustomer()
    {
        if($this->context->customer->logged)
        {
            if(Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'ets_mp_seller_manager` WHERE email="'.pSQL($this->context->customer->email).'" AND active!=0'))
                return true;
            $results = Db::getInstance()->executeS('
            SELECT cg.`id_group`
            FROM `' . _DB_PREFIX_ . 'customer_group` cg
            INNER JOIN `'._DB_PREFIX_.'group` g ON (g.id_group=cg.id_group)
            INNER JOIN `'._DB_PREFIX_.'group_shop` gs ON (g.id_group=gs.id_group AND gs.id_shop="'.(int)$this->context->shop->id.'")
            WHERE cg.`id_customer` = ' . (int) $this->context->customer->id);
            $group_seller = explode(',',Configuration::get('ETS_MP_SELLER_GROUPS'));
            if($results)
            {
                foreach($results as $result)
                {
                    if(in_array($result['id_group'],$group_seller))
                        return true;
                }
            }
        }
        return false;
    }
    public function hookDisplayPDFInvoice($params)
    {
        if(($object = $params['object']) && isset($object->id_order) && $object->id_order)
        {
            if($id_customer = Db::getInstance()->getValue('SELECT id_customer FROM `'._DB_PREFIX_.'ets_mp_seller_order` WHERE id_order='.(int)$object->id_order))
            {
                if($seller = Ets_mp_seller::_getSellerByIdCustomer($id_customer,$this->context->language->id))
                {
                    $this->context->smarty->assign(
                        array(
                            'order_seller' => $seller,
                        )
                    );
                    return $this->display(__FILE__,'seller_order_invoice.tpl');                
                }
            }
        }
    }
    public function hookDisplayMyAccountBlock()
    {
        if(!Configuration::get('ETS_MP_ENABLED'))
            return '';
        $seller = $this->_getSeller();
        if(!$seller && !$this->checkGroupCustomer() && $this->context->customer->logged)
            return '';
        $this->smarty->assign(
            array(
                'is17' => $this->is17,
                'seller' => $seller,
                'registration' => Ets_mp_registration::_getRegistration(),
                'link' => $this->context->link,
                'require_registration' => (int)Configuration::get('ETS_MP_REQUIRE_REGISTRATION'),
            )
        );
        return $this->display(__FILE__,'my_account.tpl');
    }
    public function hookDisplayCustomerAccount()
    {
        if(!Configuration::get('ETS_MP_ENABLED'))
            return '';
        $seller = $this->_getSeller();
        if(!$seller && !$this->checkGroupCustomer())
            return '';
        $this->smarty->assign(
            array(
                'is17' => $this->is17,
                'seller' => $seller,
                'registration' => Ets_mp_registration::_getRegistration(),
                'link' => $this->context->link,
                'require_registration' => (int)Configuration::get('ETS_MP_REQUIRE_REGISTRATION'),
            )
        );
        return $this->display(__FILE__,'customer_account.tpl');
    }
    public function hookPaymentOptions($params)
    {
        if(!Configuration::get('ETS_MP_ENABLED') || !Configuration::get('ETS_MP_ALLOW_BALANCE_TO_PAY'))
            return '';
        if(($seller= $this->_getSeller(true)) && $seller->id_customer == $this->context->customer->id)
        {
            $commission_total_balance = $seller->getTotalCommission(1) - $seller->getToTalUseCommission(1);
            $min_order_pay = (float)Configuration::get('ETS_MP_MIN_BALANCE_REQUIRED_FOR_ORDER');
            $max_order_pay = (float)Configuration::get('ETS_MP_MAX_BALANCE_REQUIRED_FOR_ORDER');
            $cart = $params['cart'];
            $cart_total = $cart->getOrderTotal(true, Cart::BOTH);
            $cart_total = Tools::convertPrice($cart_total, null, false);
            if($commission_total_balance >0 && $cart_total >0 && $cart_total <= $commission_total_balance && (!$min_order_pay || $min_order_pay <= $cart_total) && (!$max_order_pay || $max_order_pay >=$cart_total))
            {
                $this->context->smarty->assign(
                    array(
                        'commission_total_balance' => Tools::displayPrice(Tools::convertPrice($commission_total_balance)),
                    )
                );
                $newOption = new PrestaShop\PrestaShop\Core\Payment\PaymentOption();
                $newOption->setModuleName($this->name)
                    ->setCallToActionText($this->l('Pay by Commission'))
                    ->setAction($this->context->link->getModuleLink($this->name, 'validation', array(), true))
                    ->setAdditionalInformation($this->fetch('module:ets_marketplace/views/templates/hook/payment_info.tpl'));
                $payment_options = array(
                    $newOption,
                );
                return $payment_options;

            }
        }
    }
    public function hookPayment($params)
	{
		if (!$this->active && $this->is17)
			return;
        if(!Configuration::get('ETS_MP_ENABLED') || !Configuration::get('ETS_MP_ALLOW_BALANCE_TO_PAY'))
            return '';
		if(($seller= $this->_getSeller(true)) && $seller->id_customer == $this->context->customer->id)
        {
            $commission_total_balance = $seller->getTotalCommission(1) - $seller->getToTalUseCommission(1);
            $min_order_pay = (float)Configuration::get('ETS_MP_MIN_BALANCE_REQUIRED_FOR_ORDER');
            $max_order_pay = (float)Configuration::get('ETS_MP_MAX_BALANCE_REQUIRED_FOR_ORDER');
            $cart = $params['cart'];
            $cart_total = $cart->getOrderTotal(true, Cart::BOTH);
            $cart_total = Tools::convertPrice($cart_total, null, false);
            if($commission_total_balance >0 && $cart_total >0 && $cart_total <= $commission_total_balance && (!$min_order_pay || $min_order_pay <= $cart_total) && (!$max_order_pay || $max_order_pay >=$cart_total))
            {
                
                $this->context->smarty->assign(
                    array(
                        'commission_total_balance' => Tools::displayPrice(Tools::convertPrice($commission_total_balance)),
                    )
                );
                return $this->display(__FILE__, 'payment.tpl');
            }
        }
		
	}
    public function hookPaymentReturn($params)
	{
		if (!$this->active || $this->is17)
			return;
		return $this->display(__FILE__, 'payment_return.tpl');
	}
    public function hookDisplayMPLeftContent()
    {
        if(!Configuration::get('ETS_MP_ENABLED'))
            Tools::redirect($this->context->link->getPageLink('my-account'));
        if(Configuration::get('ETS_MP_SELLER_PRODUCT_TYPE_SUBMIT'))
            $product_types = explode(',',Configuration::get('ETS_MP_SELLER_PRODUCT_TYPE_SUBMIT'));
        else
            $product_types = array();
        $tabs = array(
            'dashboard' => array(
                'page' => 'dashboard',
                'name' => $this->l('Dashboard')
            ),
            'orders' => array(
                'page' => 'orders',
                'name' => $this->l('Orders'),
                'link' => $this->context->link->getModuleLink($this->name,'orders',array('list'=>1))
            ),
            'products' => array(
                'page' => 'products',
                'name' => $this->l('Products'),
                'link' => $this->context->link->getModuleLink($this->name,'products',array('list'=>true)),
            ),
            'stock' => array(
                'page' => 'stock',
                'name' => $this->l('Stock'),
                'link' => $this->context->link->getModuleLink($this->name,'stock',array('list'=>true)),
            ),
            'ratings' => array(
                'page' => 'ratings',
                'name' => $this->l('Ratings'),
            ),
            'messages'=>array(
                'page' => 'messages',
                'name' => $this->l('Messages')
            ),
            'commissions' => array(
                'page' => 'commissions',
                'name' => $this->l('Commissions'),
                'link' => $this->context->link->getModuleLink($this->name,'commissions',array('list'=>true)),
            ),
            'attributes'=>array(
                'page' => 'attributes',
                'name' => in_array('standard_product',$product_types) && $this->_use_attribute && $this->_use_feature ?  $this->l('Attributes and features') : ($this->_use_feature ? $this->l('Features') : $this->l('Attributes') ),
                'link' => in_array('standard_product',$product_types) && $this->_use_attribute ? $this->context->link->getModuleLink($this->name,'attributes') : $this->context->link->getModuleLink($this->name,'features'),
            ),
            'discount'=>array(
                'page' => 'discount',
                'name' => $this->l('Discounts'),
                'link' => $this->context->link->getModuleLink($this->name,'discount',array('list'=>true)),
            ),
            'carrier'=>array(
                'page' => 'carrier',
                'name' => $this->l('Carriers'),
                'link' => $this->context->link->getModuleLink($this->name,'carrier',array('list'=>true)),
            ),
            'brands' =>array(
                'page'=> 'brands',
                'name' => $this->l('Brands','myseller'),
                'link' => $this->context->link->getModuleLink($this->name,'brands',array('list'=>true))
            ),
            'suppliers' =>array(
                'page'=> 'suppliers',
                'name' => $this->l('Suppliers'),
                'link' => $this->context->link->getModuleLink($this->name,'suppliers',array('list'=>true))
            ),
            'billing' =>array(
                'page' => 'billing',
                'name' => $this->l('Membership'),
                'link' => $this->context->link->getModuleLink($this->name,'billing',array('list'=>true)),
            ),
            'withdraw'=>array(
                'page' => 'withdraw',
                'name' => $this->l('Withdrawals'),
                'link' => $this->context->link->getModuleLink($this->name,'withdraw'),
            ),
            'voucher'=>array(
                'page' => 'voucher',
                'name' => $this->l('My vouchers'),
                'link' => $this->context->link->getModuleLink($this->name,'voucher'),
            ),
            'profile' => array(
                'page' => 'profile',
                'name' => $this->l('Profile')
            ),
            'manager' => array(
                'page' =>'manager',
                'name'=> $this->l('Shop managers')
            ),
        );
        if(!Configuration::get('ETS_MP_SELLER_CAN_CREATE_VOUCHER'))
            unset($tabs['discount']);
        if(!Configuration::get('ETS_MP_SELLER_ALLOWED_IMPORT_EXPORT_PRODUCTS'))
            unset($tabs['import']);
        if(!(in_array('standard_product',$product_types) && $this->_use_attribute) && !$this->_use_feature)
            unset($tabs['attributes']);
        if(!Configuration::get('ETS_MP_SELLER_CREATE_BRAND') && !Configuration::get('ETS_MP_SELLER_USER_GLOBAL_BRAND'))
            unset($tabs['brands']);
        if(!Configuration::get('ETS_MP_SELLER_CREATE_SUPPLIER') && !Configuration::get('ETS_MP_SELLER_USER_GLOBAL_SUPPLIER'))
        {
            unset($tabs['suppliers']);
        }
        if(!Configuration::get('ETS_MP_SELLER_CREATE_SHIPPING') && !Configuration::get('ETS_MP_SELLER_USER_GLOBAL_SHIPPING'))
            unset($tabs['carrier']);
        if(!Configuration::get('ETS_MP_ALLOW_CONVERT_TO_VOUCHER'))
            unset($tabs['voucher']);
        if(!Configuration::get('ETS_MP_ALLOW_WITHDRAW'))
            unset($tabs['withdraw']);
        if(!Module::isEnabled('productcomments') && !Module::isEnabled('ets_productcomments'))
            unset($tabs['ratings']);
        if($seller = $this->_getSeller())
        {
            $tabs['shop'] = array(
                'page' => 'shop',
                'name' => $this->l('My shop','myseller'),
                'link'=> $this->getShopLink(array('id_seller'=>$seller->id)),
                'new_tab' => true,
            );
            $this->context->smarty->assign(
                array(
                    'total_message' => $this->_getOrderMessages(' AND (`read`!=1 OR `read` is NULL) AND id_employee=0 AND id_seller=0',false,false,false,true),
                )
            );
            $day_before_expired = (int)Configuration::get('ETS_MP_MESSAGE_EXPIRE_BEFORE_DAY');
            $date_expired = date('Y-m-d H:i:s',strtotime("+ $day_before_expired days"));
            if($seller && $seller->date_to!='' && $seller->date_to!='0000-00-00 00:00:00' && strtotime($seller->date_to)< strtotime($date_expired))
            {
                $going_to_be_expired = true;
            }
            else
                $going_to_be_expired = false;
            $this->context->smarty->assign(
                array(
                    'going_to_be_expired' =>$going_to_be_expired,
                    'seller' => $seller,
                    'isManager' => $this->context->customer->id!= $seller->id_customer,
                    'seller_billing' => $seller->id_billing ? (new Ets_mp_billing($seller->id_billing)) : false, 
                )
            );
            
        }
        if($tabs)
        {
            foreach($tabs as $key=> $tab)
            {
                if(!$this->_checkPermissionPage($seller,$tab['page']))
                    unset($tabs[$key]);
            }
        }
        $this->smarty->assign(
            array(
                'tabs' => $tabs,
                'controller'=> Tools::getValue('controller')!='features' ? Tools::getValue('controller') : 'attributes',
            )
        );
        return $this->display(__FILE__,'left_content.tpl');
    }
    public function getCategoriesTree($id_root=0)
    {
        if(!$id_root)
        {
            $id_root = Db::getInstance()->getValue('SELECT c.id_category FROM `'._DB_PREFIX_.'category` c
            INNER JOIN `'._DB_PREFIX_.'category_shop` cs ON (c.id_category = cs.id_category AND cs.id_shop="'.(int)$this->context->shop->id.'")
            WHERE c.active=1 AND is_root_category=1');
        }
        $sql ='SELECT * FROM `'._DB_PREFIX_.'category` c
        INNER JOIN `'._DB_PREFIX_.'category_shop` cs ON (c.id_category = cs.id_category AND cs.id_shop="'.(int)$this->context->shop->id.'")
        LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.id_category=cl.id_category AND cl.id_lang ="'.(int)$this->context->language->id.'" AND cl.id_shop="'.(int)$this->context->shop->id.'")
        WHERE c.id_category = "'.(int)$id_root.'" AND c.active=1 GROUP BY c.id_category';
        $tree=array();
        if($category = Db::getInstance()->getRow($sql))
        {
            $cat = array(
                'name' => $category['name'],
                'id_category' => $category['id_category']
            );
            $temp = array();
            $Childrens = $this->getChildrenCategories($category['id_category']);
            if($Childrens)
            {
                foreach($Childrens as $children)
                {
                    $arg = $this->getCategoriesTree($children['id_category']);
                    if($arg && isset($arg['0']))
                    {
                        $temp[] = $arg[0];
                    }
                }
            }
            $cat['children'] = $temp;
            $tree[] = $cat;
        }
        return $tree;
    }
    public function getChildrenCategories($id_parent)
    {
        $sql = 'SELECT c.id_category,cl.name FROM `'._DB_PREFIX_.'category` c
        INNER JOIN `'._DB_PREFIX_.'category_shop` cs ON (c.id_category = cs.id_category AND cs.id_shop="'.(int)$this->context->shop->id.'")
        LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.id_category = cl.id_category AND cl.id_lang="'.(int)$this->context->language->id.'" AND cl.id_shop="'.(int)$this->context->shop->id.'")
        WHERE c.id_parent="'.(int)$id_parent.'" AND c.active=1
        ';
        return Db::getInstance()->executeS($sql);
    }
    public function displayProductCategoryTre($blockCategTree,$selected_categories=array(),$name='',$disabled_categories=array(),$id_category_default=0,$backend=false,$displayInput=true)
    {
        $this->context->smarty->assign(
            array(
                'blockCategTree'=> $blockCategTree,
                'branche_tpl_path_input'=> _PS_MODULE_DIR_.$this->name.'/views/templates/hook/category-tree.tpl',
                'selected_categories'=>$selected_categories,
                'disabled_categories' => $disabled_categories,
                'id_category_default' => $id_category_default,
                'name'=>$name ? $name :'id_categories',
                'backend' => $backend,
                'displayInput' => $displayInput,
            )
        );
        return $this->display(__FILE__, 'categories.tpl');
    }
    public function displayProductFeatures($id_product)
    {
        $seller = $this->_getSeller();
        if($id_product)
        {
            $sql = 'SELECT * FROM `'._DB_PREFIX_.'feature_product` WHERE id_product='.(int)$id_product;
            $product_features = Db::getInstance()->executeS($sql);
            if($product_features)
            {
                foreach($product_features as &$product_feature)
                {
                    $sql = 'SELECT * FROM `'._DB_PREFIX_.'feature_value` fv
                        LEFT JOIN `'._DB_PREFIX_.'feature_value_lang` fvl ON (fv.id_feature_value = fvl.id_feature_value AND fvl.id_lang="'.(int)$this->context->language->id.'")
                    WHERE fv.id_feature = "'.(int)$product_feature['id_feature'].'" AND fv.custom=0';
                    $product_feature['feature_values'] = Db::getInstance()->executeS($sql);
                    $sql = 'SELECT * FROM `'._DB_PREFIX_.'feature_value` fv
                        LEFT JOIN `'._DB_PREFIX_.'feature_value_lang` fvl ON (fv.id_feature_value = fvl.id_feature_value AND fvl.id_lang="'.(int)$this->context->language->id.'")
                    WHERE fv.id_feature = "'.(int)$product_feature['id_feature'].'" AND fv.id_feature_value="'.(int)$product_feature['id_feature_value'].'"';
                    $product_feature['feature_value'] = Db::getInstance()->getRow($sql);
                }
            }
        }
        else
            $product_features = array();
        $features= $seller->getFeatures('',false,false);
        $features_values = $seller->getFeatureValues('',false,false);
        $this->context->smarty->assign(
            array(
                'product_features' => $product_features,
                'features' =>$features , // sf.id_seller is null OR
                'features_values' => $features_values,
            )
        );
        if($features || $product_features)
            return $this->display(__FILE__,'product/features.tpl');
        else
            return false;
        
    }
    public function getBaseLink()
    {
        $url =(Configuration::get('PS_SSL_ENABLED_EVERYWHERE')?'https://':'http://').$this->context->shop->domain.$this->context->shop->getBaseURI();
        return trim($url,'/');
    }
    public function getProductAttributeName($id_product_attribute,$small=false)
    {
        $sql = 'SELECT a.id_attribute,al.name,agl.name as group_name FROM `'._DB_PREFIX_.'attribute` a
            INNER JOIN `'._DB_PREFIX_.'attribute_shop` attribute_shop ON (a.id_attribute= attribute_shop.id_attribute AND attribute_shop.id_shop="'.(int)$this->context->shop->id.'")
            INNER JOIN `'._DB_PREFIX_.'product_attribute_combination` pac ON (a.id_attribute=pac.id_attribute)
            LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al ON (a.id_attribute=al.id_attribute AND al.id_lang="'.(int)$this->context->language->id.'")
            LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl ON (a.id_attribute_group= agl.id_attribute_group AND agl.id_lang="'.(int)$this->context->language->id.'")
            WHERE pac.id_product_attribute ="'.(int)$id_product_attribute.'"
        ';
        $attributes = Db::getInstance()->executeS($sql);
        $name_attribute ='';
        if($attributes)
        {
            foreach($attributes as $attribute)
            {
                if($small)
                   $name_attribute .= $attribute['name'].' - '; 
                else
                    $name_attribute .= $attribute['group_name'].' - '.$attribute['name'].', ';
            }
        }
        return $small ? trim($name_attribute,' - '): trim($name_attribute,', ');
    }
    public function getFeeIncludeTax($fee,$seller=null)
    {
        if(!$seller)
        {
            $id_customer = $this->context->customer->id;
            $seller = new Ets_mp_seller();
        }
        else
            $id_customer = $seller->id_customer;
        if($id_tax_group = (int)$seller->getFeeTax())
        {
            if($id_address = Db::getInstance()->getValue('SELECT MIN(id_address) FROM `'._DB_PREFIX_.'address` WHERE id_customer="'.(int)$id_customer.'" AND active=1 AND `deleted`=0'))
                $address = new Address($id_address);
            else
                $address = new Address();
            $address = Address::initialize($address->id,true);
            $tax_manager = TaxManagerFactory::getManager($address, $id_tax_group);
            $product_tax_calculator = $tax_manager->getTaxCalculator();
            $feeTax = $product_tax_calculator->addTaxes($fee);
            return $feeTax;
        }
        return $fee;
    }
    public function getTaxValue($id_tax_group)
    {
        if($id_tax_group)
        {
            $price = 10;
            $context = $this->context;
            if (is_object($context->cart) && $context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')} != null) {
                $id_address = $context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')};
                $address = new Address($id_address);
            } else {
                $address = new Address();
            }
            $address = Address::initialize($address->id,true);
            $tax_manager = TaxManagerFactory::getManager($address, $id_tax_group);
            $product_tax_calculator = $tax_manager->getTaxCalculator();
            $priceTax = $product_tax_calculator->addTaxes($price);
            if($priceTax >  $price)
                return ($priceTax-$price)/$price;
            else
                return 0;
        }
        return 0;
    }
    public function displayOrderState($id_order_state)
    {
        if($id_order_state)
        {
            $orderState = Db::getInstance()->getRow(
            'SELECT os.*,osl.name FROM `'._DB_PREFIX_.'order_state` os
            LEFT JOIN `'._DB_PREFIX_.'order_state_lang` osl ON (os.id_order_state = osl.id_order_state AND osl.id_lang="'.(int)$this->context->language->id.'")
            WHERE os.id_order_state = "'.(int)$id_order_state.'"');
            $this->context->smarty->assign(
                array(
                    'orderState' => $orderState,
                )
            );
            if($orderState)
                return $this->display(__FILE__,'order_state.tpl');
        }
        return '--';
    }
    public function getOrders($filter='',$having="",$start=0,$limit=12,$order_by='',$total=false)
    {
        if($total)
            $sql = 'SELECT COUNT(DISTINCT o.id_order)';
        else
            $sql ='SELECT o.*,so.id_order as id_order_seller,CONCAT(customer.firstname, " ", customer.lastname) as seller_name,customer.id_customer as id_customer_seller, s.id_seller,CONCAT(c.firstname, " ", c.lastname) as customer_name,sl.*,sum(sc.commission) as total_commission,(sum(sc.total_price)-sum(sc.commission)) as admin_earned';
        $sql .=' FROM `'._DB_PREFIX_.'orders` o 
        LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_order` so ON (o.id_order=so.id_order)
        LEFT JOIN `'._DB_PREFIX_.'customer` customer ON(so.id_customer=customer.id_customer)
        LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller` s ON (s.id_customer=customer.id_customer AND s.id_shop="'.(int)$this->context->shop->id.'")
        LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_lang` sl ON (s.id_seller=sl.id_seller AND sl.id_lang="'.(int)$this->context->language->id.'")
        LEFT JOIN `'._DB_PREFIX_.'customer` c ON (c.id_customer=o.id_customer)
        LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_commission` sc ON (sc.id_order=o.id_order)
        WHERE 1 '.($filter ? $filter:'');
        if(!$total)
        {
            $sql .=' GROUP BY o.id_order '.($order_by ? ' ORDER By '.$order_by :'');
            if($having)
                $sql .= ' HAVING 1 '.$having;
            $sql .= ' LIMIT '.(int)$start.','.(int)$limit;
        }
        if($total)
            return Db::getInstance()->getValue($sql);
        else
        {
           return Db::getInstance()->executeS($sql);
        }
    }
    public function getSellerProducts($filter='',$page = 0, $per_page = 12, $order_by = 'p.id_product desc',$total=false)
    {
        $page = (int)$page;
        if ($page <= 0)
            $page = 1;
        $per_page = (int)$per_page;
        if ($per_page <= 0)
            $per_page = 12;
        $front = true;
        $nb_days_new_product = Configuration::get('PS_NB_DAYS_NEW_PRODUCT');
        $id_lang = (int)Context::getContext()->language->id;
        if (!Validate::isUnsignedInt($nb_days_new_product)) {
            $nb_days_new_product = 20;
        }
        $prev_version = version_compare(_PS_VERSION_, '1.6.1.0', '<');
        if(!$total)
            $sql ='SELECT DISTINCT p.*,seller_report.total_reported,sp.approved, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) AS quantity' . ($prev_version? ' ,IFNULL(product_attribute_shop.id_product_attribute, 0)':' ,MAX(product_attribute_shop.id_product_attribute)') . ' id_product_attribute, pl.`description`, pl.`description_short`, pl.`available_now`,
    					pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, i.`id_image`,
    					il.`legend` as legend, m.`name` AS manufacturer_name,cl.name as default_category,CONCAT(customer.firstname," ",customer.lastname) as seller_name,customer.id_customer as id_customer_seller,seller.id_seller,sp.id_product as id_seller_product, seller_lang.shop_name,product_shop.`date_add`,
    					DATEDIFF(product_shop.`date_add`, DATE_SUB("' . date('Y-m-d') . ' 00:00:00",
    					INTERVAL ' . (int)$nb_days_new_product . ' DAY)) > 0 AS new, product_shop.price AS orderprice';
        else
            $sql ='SELECT COUNT(DISTINCT p.id_product) ';
        $sql .= ' FROM `'._DB_PREFIX_.'product` p
                '.Shop::addSqlAssociation('product', 'p').
                (!$prev_version?
                    'LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (pa.id_product = p.id_product)'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.default_on=1').'':
                    'LEFT JOIN `'._DB_PREFIX_.'product_attribute_shop` product_attribute_shop ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop='.(int)$this->context->shop->id.')'
                )
                .Product::sqlStock('p', 0, false, Context::getContext()->shop).'
                LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_product` sp ON (sp.id_product=p.id_product)
                LEFT JOIN `'._DB_PREFIX_.'customer` customer ON (sp.id_customer=customer.id_customer)
                LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller` seller ON (customer.id_customer=seller.id_customer)
                LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_lang` seller_lang ON (seller_lang.id_seller=seller.id_seller AND seller_lang.id_lang="'.(int)$this->context->language->id.'")
                LEFT JOIN `'._DB_PREFIX_.'category` c ON (c.id_category=p.id_category_default)
                LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.id_category = cl.id_category AND cl.id_lang="'.(int)$id_lang.'")
                LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = ' . (int)$id_lang . Shop::addSqlRestrictionOnLang('pl') . ')'.
                'LEFT JOIN `' . _DB_PREFIX_ . 'image` i ON (i.`id_product` = p.`id_product` AND i.cover=1)
                LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')	
                LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
                LEFT JOIN (
                    SELECT r.id_product,COUNT(r.id_customer) as total_reported FROM `'._DB_PREFIX_.'ets_mp_seller_report` r WHERE id_product!=0 GROUP BY r.id_product 
                ) seller_report ON (seller_report.id_product = sp.id_product)
                WHERE product_shop.`id_shop` = ' . (int)Context::getContext()->shop->id .($this->is17 ? ' AND p.state=1':''). ($filter ? $filter :'').'
                '
                . ($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : ''); 
        if($total)
            return Db::getInstance()->getValue($sql);
        else
            $sql .= ' GROUP BY p.id_product'.($order_by ? ' ORDER BY ' . pSQL($order_by): '').' LIMIT ' . (int)($page-1)*$per_page . ',' . (int)$per_page;
        $products = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql, true, true);
        if (!$products) {
            return array();
        }
        if ($order_by == 'product_shop.price asc') {
            Tools::orderbyPrice($products, 'asc');
        } elseif ($order_by == 'product_shop.price desc') {
            Tools::orderbyPrice($products, 'desc');
        }   
        return $products;
    }
    public function checkListProductSeller($productList)
    {
        if($productList)
        {
            foreach($productList as $product)
            {
                if(Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'ets_mp_seller_product` WHERE id_product="'.(int)$product['id_product'].'"'))
                    return true;
            }
        }
        return false;
    }
    public function getListProductSeller($productList)
    {
        $sellerProducts = array();
        if($productList)
        {
            foreach($productList as $product)
            {
                $id_customer = Db::getInstance()->getValue('SELECT id_customer FROM `'._DB_PREFIX_.'ets_mp_seller_product` WHERE id_product="'.(int)$product['id_product'].'"');
                if(!isset($sellerProducts[$id_customer]))
                    $sellerProducts[$id_customer] = array();
                $sellerProducts[$id_customer][]=$product;
            }
        }
        return $sellerProducts;
    }
    public function getSellerCommissions($filter='',$having="",$start=0,$limit=12,$order_by='',$total=false)
    {
        if($total)
            $sql = 'SELECT COUNT(sc.id)';
        else
            $sql ='SELECT sc.*,CONCAT(customer.firstname," ",customer.lastname) as seller_name,customer.id_customer as id_customer_seller ,seller.id_seller as seller_id,seller_lang.shop_name,p.id_product as product_id';
        $sql .= ' FROM (
        SELECT id_seller_commission as id, "commission" as type,reference,product_name,price,price_tax_incl,quantity,commission,if(c.use_tax,c.total_price_tax_incl-c.commission,c.total_price-c.commission) as admin_earning,status,note,date_add,id_shop,id_customer,id_order,id_product,id_product_attribute,"" as id_withdraw,"" as id_voucher FROM `'._DB_PREFIX_.'ets_mp_seller_commission` c
        UNION ALL
        SELECT id_ets_mp_commission_usage as id,"usage" as type,reference,"" as product_name,"" as price,"" as price_tax_incl,"" as quantity,amount as commission,"" as admin_earning,status,note,date_add,id_shop,id_customer,id_order,"" as id_product,"" as id_product_attribute,id_withdraw,id_voucher FROM `'._DB_PREFIX_.'ets_mp_commission_usage` u
        )as sc
        LEFT JOIN `'._DB_PREFIX_.'customer` customer ON (customer.id_customer=sc.id_customer)
        LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller` seller ON (customer.id_customer= seller.id_customer)
        LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_lang` seller_lang ON (seller.id_seller= seller_lang.id_seller AND seller_lang.id_lang="'.(int)$this->context->language->id.'")
        LEFT JOIN `'._DB_PREFIX_.'product` p ON (p.id_product=sc.id_product)
        WHERE sc.id_shop="'.(int)$this->context->shop->id.'"'.($filter ? $filter:'');
        if(!$total)
        {
            $sql .=($order_by ? ' ORDER By '.$order_by :'');
            if($having)
                $sql .= ' HAVING 1 '.$having;
            $sql .= ' LIMIT '.(int)$start.','.(int)$limit;
        }
        if($total)
            return Db::getInstance()->getValue($sql);
        else
        {
            return Db::getInstance()->executeS($sql);
        }
    }
    public function generatePromoCode($prefix = null)
    {
        if ($prefix) {
            $code = $prefix . Tools::passwdGen(5);
            if (CartRule::getCartsRuleByCode($code, $this->context->language->id)) {
                $code = self::generatePromoCode($prefix);
            }
        } else {
            $code = Tools::passwdGen(8);
            if (CartRule::getCartsRuleByCode($code, $this->context->language->id)) {
                $code = self::generatePromoCode(null);
            }
        }
        return Tools::strtoupper($code);
    }
    public static function sendMail($template,$template_vars,$emails='',$title='',$name=null,$file_attachment=null,$id_lang=null){
        if(!$emails)
        {
            $isAdmin= true;
            $emails= Configuration::get('ETS_MP_EMAIL_ADMIN_NOTIFICATION')?:Configuration::get('PS_SHOP_EMAIL');
            if($emails && Tools::strpos($emails,',')!==false)
                $emails= explode(',',$emails);
        }
        else
            $isAdmin= false;
        if(is_array($emails))
        {
            foreach($emails as $key=>$email)
            {
                if(!Validate::isEmail($email))
                    unset($emails[$key]);
            }
        }elseif(!Validate::isEmail($emails))
            return '';
        if($emails)
        {
            if($isAdmin)
                $to = array('employee'=>$emails);
            else
                $to = array('customer' => $emails);
            return Ets_mp_email::Send(
    			$id_lang,
    			$template,
    			$title,
    			$template_vars,
    			$to,
    			$name,
    			null,
    			null,
    			$file_attachment,
    			null,
    			$template=='order_merchant_comment' ? _PS_MAIL_DIR_ : dirname(__FILE__).'/mails/',
    			null,
    			Context::getContext()->shop->id
    		);
        }
        return false;
    }
    public function _replaceTag($text){
        $search = array(
                '[fee_amount]',
                '[payment_information_manager]',
                '[seller_email]',
                '[shop_phone]',
                '[remaining_day]',
                '[disabled_day]',
                '[shop_id]',
                '[shop_name]',
                '[seller_name]',
                '[shop_declined_reason]',
                '[store_email]',
                '[manager_email]',
                '[manager_phone]'
        );
        if($seller= $this->_getSeller())
        {
            $replace = array(
                Tools::displayPrice($this->getFeeIncludeTax((float)$seller->getFeeAmount(),$seller),new Currency(Configuration::get('PS_CURRENCY_DEFAULT'))).' ('.$this->l('Tax incl').')',
                Configuration::get('ETS_MP_SELLER_PAYMENT_INFORMATION',$this->context->language->id),
                $seller->seller_email,
                $seller->shop_phone,
                Ceil((strtotime($seller->date_to)-strtotime(date('Y-m-d H:i:s')))/86400),
                date('Y-m-d'),
                $seller->id,
                $seller->shop_name[$this->context->language->id],
                $seller->seller_name,
                $seller->reason,
                Configuration::get('ETS_MP_EMAIL_ADMIN_NOTIFICATION')?:Configuration::get('PS_SHOP_EMAIL'),
                Configuration::get('ETS_MP_EMAIL_ADMIN_NOTIFICATION')?:Configuration::get('PS_SHOP_EMAIL'),
                Configuration::get('PS_SHOP_PHONE'),
            );
            return Tools::nl2br(str_replace($search,$replace,$text));
        }elseif($seller=Ets_mp_registration::_getRegistration())
        {
           $replace = array(
                Tools::displayPrice($this->getFeeIncludeTax((float)Configuration::get('ETS_MP_SELLER_FEE_AMOUNT')),new Currency(Configuration::get('PS_CURRENCY_DEFAULT'))).' ('.$this->l('Tax incl').')',
                Configuration::get('ETS_MP_SELLER_PAYMENT_INFORMATION',$this->context->language->id),
                $seller->seller_email,
                $seller->shop_phone,
                date('Y-m-d'),
                date('Y-m-d'),
                $seller->id,
                $seller->shop_name,
                $seller->seller_name,
                $seller->reason,
                Configuration::get('ETS_MP_EMAIL_ADMIN_NOTIFICATION')?:Configuration::get('PS_SHOP_EMAIL'),
                Configuration::get('ETS_MP_EMAIL_ADMIN_NOTIFICATION')?:Configuration::get('PS_SHOP_EMAIL'),
                Configuration::get('PS_SHOP_PHONE'),
            );
            return Tools::nl2br(str_replace($search,$replace,$text)); 
        }
        return Tools::nl2br($text) ;
    }
    public function _postPDFProcess()
    {
        $seller = $this->_getSeller();
        if (!$this->context->customer->isLogged() && !Tools::getValue('secure_key')) {
            Tools::redirect('index.php?controller=authentication&back=pdf-invoice');
        }
        if (!(int) Configuration::get('PS_INVOICE')) {
            die($this->l('Membership is disabled in this shop.'));
        }
        $id_order = (int) Tools::getValue('id_order');
        if (Validate::isUnsignedId($id_order)) {
            $order = new Order((int) $id_order);
        }
        if (!isset($order) || !Validate::isLoadedObject($order)) {
            die($this->l('The invoice was not found.'));
        }
        if ((isset($this->context->customer->id) && $order->id_customer != $this->context->customer->id) || (Tools::isSubmit('secure_key') && $order->secure_key != Tools::getValue('secure_key'))) {
            if(!$seller || !Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'ets_mp_seller_order` WHERE id_order='.(int)$order->id.' AND id_seller='.(int)$seller->id))
                die($this->l('The invoice was not found.'));
        }
        if (!OrderState::invoiceAvailable($order->getCurrentState()) && !$order->invoice_number) {
            die($this->l('No invoice is available.'));
        }
        return $order;
    }
    public function getCustomerMessagesOrder($id_customer, $id_order,$limit=2){
        $sql = 'SELECT cm.*, c.`firstname` AS cfirstname, c.`lastname` AS clastname,
                e.`firstname` AS efirstname, e.`lastname` AS elastname
			FROM `' . _DB_PREFIX_ . 'customer_thread` ct
			LEFT JOIN `' . _DB_PREFIX_ . 'customer_message` cm
				ON ct.id_customer_thread = cm.id_customer_thread
            LEFT JOIN `' . _DB_PREFIX_ . 'customer` c 
                ON ct.`id_customer` = c.`id_customer`
            LEFT OUTER JOIN `' . _DB_PREFIX_ . 'employee` e 
                ON e.`id_employee` = cm.`id_employee`
			WHERE ct.id_customer = ' . (int) $id_customer .
                ' AND ct.`id_order` = ' . (int) $id_order . '
            GROUP BY cm.id_customer_message
		 	ORDER BY cm.date_add DESC'.($limit ? ' LIMIT '.(int)$limit :'');
        $messages = Db::getInstance()->executeS($sql);
        if($messages)
        {
            foreach($messages as &$message)
            {
                $sql = 'SELECT *,CONCAT(customer.firstname," ",customer.lastname) as seller_name,CONCAT(manager.firstname," ",manager.lastname) as manager_name FROM `'._DB_PREFIX_.'ets_mp_seller_customer_message` scm
                INNER JOIN `'._DB_PREFIX_.'customer` customer ON (customer.id_customer=scm.id_customer)
                INNER JOIN `'._DB_PREFIX_.'ets_mp_seller` s ON (s.id_customer=customer.id_customer)
                LEFT JOIN `'._DB_PREFIX_.'customer` manager ON (manager.id_customer= scm.id_manager)
                WHERE scm.id_customer_message = "'.(int)$message['id_customer_message'].'"';
                if($seller = Db::getInstance()->getRow($sql))
                {
                    if($seller['manager_name'])
                    {
                        $message['efirstname'] = $seller['manager_name'];
                        $message['elastname']='(Seller manager)';
                    }
                    else
                    {
                        $message['efirstname'] = $seller['seller_name'];
                        $message['elastname']='(Seller)';
                    }
                    
                }
            }
        }
        return $messages;
    }
    public function _runCronJob()
    {
        $commissions_expired = Db::getInstance()->executeS('SELECT id_seller_commission FROM `'._DB_PREFIX_.'ets_mp_seller_commission` WHERE status=-1 AND expired_date!="0000-00-00 00:00:00" AND expired_date <="'.pSQL(date('Y-m-d H:i:s')).'"');
        $ok=false;
        if($commissions_expired)
        {
            foreach($commissions_expired as $commission)
            {
                $ets_commission = new Ets_mp_commission($commission['id_seller_commission']);
                $ets_commission->status=1;
                $ets_commission->expired_date='0000-00-00 00:00:00';
                $ets_commission->update();
            }
            $ok= true;
            if(Configuration::getGlobalValue('ETS_MP_SAVE_CRONJOB_LOG'))
                file_put_contents(dirname(__FILE__).'/cronjob_log.txt',Tools::displayDate(date('Y-m-d H:i:s'),Configuration::get('PS_LANG_DEFAULT'),true).': '.$this->l('Approved').' '.Count($commissions_expired).' '.$this->l('commission')."\n",FILE_APPEND);
        }
        $day_before_expired = (int)Configuration::get('ETS_MP_MESSAGE_EXPIRE_BEFORE_DAY');
        $sellers_going_to_be_expired = Db::getInstance()->executeS('SELECT id_seller FROM `'._DB_PREFIX_.'ets_mp_seller` WHERE mail_going_to_be_expired=0 AND date_to!="" AND date_to <="'.($day_before_expired ? pSQL(date('Y-m-d H:i:s',strtotime("+ $day_before_expired days"))): pSQL(date('Y-m-d H:i:s'))).'"');
        if($sellers_going_to_be_expired)
        {
            foreach($sellers_going_to_be_expired as $seller)
            {
                $seller_class = new Ets_mp_seller($seller['id_seller'],$this->context->language->id);
                $seller_class->mail_going_to_be_expired=1;
                $seller_class->mail_payed=0;
                $seller_class->mail_wait_pay=0;
                if($seller_class->getFeeType()!='no_fee')
                {
                    $seller_class->payment_verify=-1;
                }
                else
                    $seller_class->payment_verify=0;
                if($seller_class->update(true))
                {
                    if(Configuration::get('ETS_MP_EMAIL_SELLER_GOING_TOBE_EXPIRED'))
                    {
                        $payment_information = Configuration::get('ETS_MP_SELLER_PAYMENT_INFORMATION',$this->context->language->id);
                        $str_search = array('[shop_id]','[shop_name]','[seller_name]','[seller_email]');
                        $str_replace = array($seller_class->id,$seller_class->shop_name,$seller_class->seller_email,$seller_class->seller_email);
                        $data= array(
                            '{seller_name}' => $seller_class->seller_name,
                            '{reason}' => $seller_class->reason,
                            '{date_expired}' => $seller_class->date_to,
                            '{fee_amount}' => (float)$seller_class->getFeeAmount().'('.(new Currency(Configuration::get('PS_CURRENCY_DEFAULT')))->iso_code.')',
                            '{payment_information}' => str_replace($str_search,$str_replace,$payment_information),
                            '{store_email}' => Configuration::get('ETS_MP_EMAIL_ADMIN_NOTIFICATION')?:Configuration::get('PS_SHOP_EMAIL'),
                        );
                        $subjects = array(
                            'translation' => $this->l('Your account is going to be expired'),
                            'origin'=> 'Your account is going to be expired',
                            'specific'=>false
                        );
                        Ets_marketplace::sendMail('to_seller_account_going_to_be_expired',$data,$seller_class->seller_email,$subjects,$seller_class->seller_name);
                    }
                    $fee_type= $seller_class->getFeeType();
                    if($fee_type!='no_fee')
                    {
                        $billing = new Ets_mp_billing();
                        $billing->id_customer = $seller_class->id_customer;
                        $billing->amount = (float)$seller_class->getFeeAmount();
                        $billing->amount_tax = (float)$this->getFeeIncludeTax($billing->amount,$seller_class);
                        $billing->active = 0;
                        $billing->date_from = $seller_class->date_to;
                        if($fee_type=='monthly_fee')
                            $billing->date_to = date("Y-m-d", strtotime($seller_class->date_to."+1 month"));
                        elseif($fee_type=='quarterly_fee')
                            $billing->date_to = date("Y-m-d", strtotime($seller_class->date_to."+3 month"));
                        elseif($fee_type=='yearly_fee')
                            $billing->date_to = date("Y-m-d", strtotime($seller_class->date_to."+1 year"));
                        else
                            $billing->date_to ='';
                        $billing->fee_type = $fee_type;
                        if($billing->add(true,true))
                        {
                            $seller_class->id_billing = $billing->id;
                            $seller_class->update();
                        }
                    }
                    
                }
            }
            $ok= true;
            if(Configuration::getGlobalValue('ETS_MP_SAVE_CRONJOB_LOG'))
                file_put_contents(dirname(__FILE__).'/cronjob_log.txt',Tools::displayDate(date('Y-m-d H:i:s'),Configuration::get('PS_LANG_DEFAULT'),true).': '.$this->l('Sent').' '.Count($sellers_going_to_be_expired).' '.$this->l('email is going to be expired')."\n",FILE_APPEND);
            unset($seller);
        }
        $sellers_expired = Db::getInstance()->executeS('SELECT id_seller FROM `'._DB_PREFIX_.'ets_mp_seller` WHERE date_to!="" AND mail_expired=0 AND date_to < "'.pSQL(date('Y-m-d H:i:s')).'"');
        if($sellers_expired)
        {
            foreach($sellers_expired as $seller)
            {
                $seller_class = new Ets_mp_seller($seller['id_seller']);
                $seller_class->mail_expired=1;
                $seller_class->active = -2;
                $seller_class->mail_payed=0;
                $seller_class->mail_wait_pay=0;
                $seller_class->payment_verify=-1;
                $seller_class->update(true);
            }
            $ok= true;
            if(Configuration::getGlobalValue('ETS_MP_SAVE_CRONJOB_LOG'))
                file_put_contents(dirname(__FILE__).'/cronjob_log.txt',Tools::displayDate(date('Y-m-d H:i:s'),Configuration::get('PS_LANG_DEFAULT'),true).': '.$this->l('Expired').' '.Count($sellers_expired).' '.$this->l('seller')."\n",FILE_APPEND);
            unset($seller);
        }
        $sellers_wait_approve = Db::getInstance()->executeS('SELECT id_seller FROM `'._DB_PREFIX_.'ets_mp_seller` WHERE active=-2 AND (date_to ="" OR date_to >= "'.pSQL(date('Y-m-d')).'") AND (date_from ="" OR date_from <= "'.pSQL(date('Y-m-d')).'")');
        if($sellers_wait_approve)
        {
            foreach($sellers_wait_approve as $seller)
            {
                $seller_class = new Ets_mp_seller($seller['id_seller']);
                $seller_class->active = 1;
                $seller_class->update(true);
            }
            $ok= true;
            if(Configuration::getGlobalValue('ETS_MP_SAVE_CRONJOB_LOG'))
                file_put_contents(dirname(__FILE__).'/cronjob_log.txt',Tools::displayDate(date('Y-m-d H:i:s'),Configuration::get('PS_LANG_DEFAULT'),true).': '.$this->l('Approved').' '.Count($sellers_expired).' '.$this->l('seller')."\n",FILE_APPEND);
            unset($seller);
        }
        $sellers_wait_pay = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'ets_mp_seller` s
        LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_lang` sl ON (s.id_seller=sl.id_seller AND sl.id_lang="'.(int)Configuration::get('PS_LANG_DEFAULT').'")
        INNER JOIN `'._DB_PREFIX_.'ets_mp_seller_billing` sb ON (s.id_customer= sb.id_customer);
        WHERE s.mail_wait_pay!=1 AND (s.active=-1 OR s.active=-2) AND sb.active=0 AND (sb.date_to ="" OR sb.date_to >= "'.pSQL(date('Y-m-d')).'") AND (sb.date_from ="" OR sb.date_from <= "'.pSQL(date('Y-m-d')).'")');
        if($sellers_wait_pay)
        {
            foreach($sellers_wait_pay as &$seller)
            {
                $seller_class = new Ets_mp_seller($seller['id_seller']);
                $seller_class->mail_wait_pay = 1;
                $seller_class->update(true);
                $seller['seller_name'] = $seller_class->seller_name;
                $seller['seller_email'] = $seller_class->seller_email;
            }
        }
        // send mail
        if($sellers_wait_pay)
        {
            $header = array(
                $this->l('ID'),
                $this->l('Invoice ID'),
                $this->l('Seller name'),
                $this->l('Seller mail'),
                $this->l('Amount'),
            );
            $data= array();
            foreach($sellers_wait_pay as $seller)
            {
                $data[]=array(
                    'id_seller' =>$seller['id_seller'],
                    'id_billing' => $seller['id_ets_mp_seller_billing'],
                    'seller_name' => $seller['seller_name'],
                    'seller_email' => $seller['seller_email'],
                    'amount' => $seller['amount'],
                );
            }
            $filename ='list_seller';
            $file_attachment = array();
            $file_attachment['content'] = $this->exportCSV($filename,$header,$data,false);
            $file_attachment['name'] = $filename . date('d_m_Y') . '.csv';
            $file_attachment['mime'] = 'application/csv';
        }
        if(($order_status = Configuration::get('ETS_MP_COMMISSION_APPROVED_WHEN')))
        {
            $sql = 'SELECT so.id_customer,SUM(o.total_paid_tax_incl*c.conversion_rate) as total_order FROM `'._DB_PREFIX_.'orders` o
            INNER JOIN `'._DB_PREFIX_.'ets_mp_seller_order` so ON (o.id_order = so.id_order)
            INNER JOIN `'._DB_PREFIX_.'currency` c ON(o.id_currency = c.id_currency)
            WHERE o.current_state IN ('.implode(',',array_map('intval',explode(',',$order_status))).')
            GROUP BY so.id_customer';
            $order_sellers = Db::getInstance()->executeS($sql);
            
            if($order_sellers)
            {
                $total = 0;
                foreach($order_sellers as $order_seller)
                {
                    $groups = Db::getInstance()->executeS('SELECT id_ets_mp_seller_group FROM `'._DB_PREFIX_.'ets_mp_seller_group` WHERE auto_upgrade <='.(float)$order_seller['total_order'].' ORDER BY auto_upgrade DESC');                    
                    if($groups && isset($groups[0]) && ($id_group = $groups[0]['id_ets_mp_seller_group']))
                    {                        
                        $seller = Ets_mp_seller::_getSellerByIdCustomer($order_seller['id_customer']);
                        if($seller->id_group!=$id_group)
                        {
                            $seller->id_group = $id_group;
                            if($seller->update() && Configuration::getGlobalValue('ETS_MP_SAVE_CRONJOB_LOG'))
                            {
                                $ok = true;
                                $total++;
                            }
                        }
                    }
                }
                if($total)
                    file_put_contents(dirname(__FILE__).'/cronjob_log.txt',Tools::displayDate(date('Y-m-d H:i:s'),Configuration::get('PS_LANG_DEFAULT'),true).': '.$total .' '. $this->l(' shop(s) has been upgraded')."\n",FILE_APPEND);
            }
        }
        if(!$ok && Configuration::getGlobalValue('ETS_MP_SAVE_CRONJOB_LOG'))
            file_put_contents(dirname(__FILE__).'/cronjob_log.txt',Tools::displayDate(date('Y-m-d H:i:s'),Configuration::get('PS_LANG_DEFAULT'),true).': '.$this->l('Cronjob run but nothing to do')."\n",FILE_APPEND);
        file_put_contents(dirname(__FILE__).'/cronjob_time.txt',date('Y-m-d H:i:s'));
        if(Tools::isSubmit('ajax'))
            die(
                Tools::jsonEncode(
                    array(
                        'success' => $this->l('Cronjob done'),
                        'cronjob_log' => file_exists(dirname(__FILE__).'/cronjob_log.txt') ?  Tools::file_get_contents(dirname(__FILE__).'/cronjob_log.txt'):'',
                    )
                )
            );
    }
    public function exportCSV($file_name,$header=array(),$data= array(),$display=false)
    {
        $filename = $file_name . date('d_m_Y') . ".csv";
        if ($display) {
            header("Content-Disposition: attachment; filename=\"$filename\"");
            header("Content-type: application/x-msdownload");
        }
        $flag = false;
        $csv = '';
        if ($data) {
            foreach ($data as $row) {
                if (!$flag) {
                    $csv .= join("\t", $header)."\r\n";
                    $flag = true;
                }
                if($row)
                {
                    foreach($row as &$val)
                        $val = str_replace(array("\r\n","\r","\n"),"",$val);
                }
                $csv .= join("\t", array_values($row))."\r\n";
            }
        } else {
            $csv .= join("\t", $header)."\r\n";
        }
        $csv = chr(255).chr(254).mb_convert_encoding($csv, "UTF-16LE", "UTF-8");
        if ($display) {
            echo $csv;
            exit();
        } else {
            return $csv;
        }
    }
    public function getDateRanger($start, $end, $format = 'Y-m-d', $list_data_by_date = false, $type = 'date')
    {

        $array = array();
        $interval = new DateInterval('P1D');
        if ($type == 'month') {
            $interval = DateInterval::createFromDateString('1 month');
        }

        $period = new DatePeriod(
            new DateTime($start),
            $interval,
            new DateTime($end));

        foreach ($period as $date) {
            if ($list_data_by_date) {
                $array[$date->format($format)] = 0;
            } else {
                $array[] = $date->format($format);
            }
        }
        return $array;
    }

    public function getYearRanger($start, $end, $format = 'Y', $list_data_by_date = false)
    {

        $array = array();

        $getRangeYear = range(gmdate('Y', strtotime($start)), gmdate('Y', strtotime($end)));
        foreach ($getRangeYear as $year) {
            if ($list_data_by_date) {
                $array[date($format, strtotime($year . '-01-01 00:00:00'))] = 0;
            } else {
                $array[] = date($format, strtotime($year . '-01-01 00:00:00'));
            }
        }
        return $array;
    }
    public function hookDisplayFooterProduct($params)
    {
        if(!Configuration::get('ETS_MP_ENABLED'))
            return '';
        if(isset($params['product']) && $product= $params['product'])
        {
            if($id_customer = (int)Db::getInstance()->getValue('SELECT id_customer FROM `'._DB_PREFIX_.'ets_mp_seller_product` WHERE id_product='.(int)$product->id))
            {
                $seller= Ets_mp_seller::_getSellerByIdCustomer($id_customer,$this->context->language->id);
                $products= $seller->getProductOther($product);
                if($products)
                {
                    $this->context->smarty->assign(
                        array(
                            'seller' => $seller,
                            'products' => $products,
                            'position' => '',
                            'link_seller' => $this->getShopLink(array('id_seller'=>$seller->id))
                        )
                    );
                    if($this->is17)
                        return $this->display(__FILE__,'product/products_other.tpl');
                    else
                        return $this->display(__FILE__,'product/products_other16.tpl');
                }
                
            }
        }
        return '';
    }
    public function displaySellerInProductPage($id_product)
    {
        $sql = 'SELECT p.id_customer FROM `'._DB_PREFIX_.'ets_mp_seller_product` p
        INNER JOIN `'._DB_PREFIX_.'ets_mp_seller` seller ON (p.id_customer=seller.id_customer)
        WHERE p.id_product='.(int)$id_product.' AND seller.active!=0';
        if($id_customer = (int)Db::getInstance()->getValue($sql))
        {
            $seller= Ets_mp_seller::_getSellerByIdCustomer($id_customer,$this->context->language->id);
            $reviews = $seller->getAVGReviewProduct();
            $total_reviews = isset($reviews['avg_grade']) ? $reviews['avg_grade']:0;
            $count_reviews = isset($reviews['count_grade']) ? $reviews['count_grade']:0;
            $total_messages = $this->_getOrderMessages('',null,null,null,true,$seller->id);
            if($total_messages)
            {
                $total_messages_reply = Db::getInstance()->getValue('SELECT COUNT(DISTINCT cm.id_customer_thread) FROM `'._DB_PREFIX_.'customer_message` cm INNER JOIN `'._DB_PREFIX_.'ets_mp_seller_customer_message` scm ON (scm.id_customer_message=cm.id_customer_message AND scm.id_customer="'.(int)$seller->id_customer.'")') + Db::getInstance()->getValue('SELECT COUNT(DISTINCT id_contact) FROM `'._DB_PREFIX_.'ets_mp_seller_contact_message` WHERE id_seller='.(int)$seller->id);
                $response_rate = Tools::ps_round($total_messages_reply*100/$total_messages,2);
            }
            if(Configuration::get('ETS_MP_ENABLE_CAPTCHA') && Configuration::get('ETS_MP_ENABLE_CAPTCHA_FOR') && $this->context->customer->logged)
            {
                $captcha_for = explode(',',Configuration::get('ETS_MP_ENABLE_CAPTCHA_FOR'));
                if(in_array('shop_report',$captcha_for) &&  !Configuration::get('ETS_MP_NO_CAPTCHA_IS_LOGIN'))
                    $is_captcha = true;
            }
            $this->context->smarty->assign(
                array(
                    'link_shop_seller' => $this->getShopLink(array('id_seller'=>$seller->id)),
                    'shop_name' => $seller->shop_name,
                    'logo_seller' => $seller->shop_logo,
                    'total_reviews' => Tools::ps_round($total_reviews,1),
                    'total_reviews_int' => (int)$total_reviews,
                    'count_reviews' => $count_reviews,
                    'total_product_sold' => Configuration::get('ETS_MP_DISPLAY_PRODUCT_SOLD') ? $seller->_getTotalNumberOfProductSold($id_product):false,
                    'total_products' => $seller->getProducts(false,false,false,false,true,true,false),
                    'link_contact_form' => $this->context->link->getModuleLink($this->name,'contactseller',array('id_product'=>$id_product)),
                    'total_follow' => Db::getInstance()->getValue('SELECT COUNT(id_customer) FROM `'._DB_PREFIX_.'ets_mp_seller_customer_follow` WHERE id_seller='.(int)$seller->id),
                    'response_rate' => isset($response_rate) ? $response_rate :false,
                    'seller_date_add' => $seller->date_add,
                    'customer_logged' => $this->context->customer->logged,
                    'report_customer' => $this->context->customer,
                    'link_proudct' => $this->context->link->getProductLink($id_product),
                    'id_product_report' => $id_product,
                    'id_seller_report' => $seller->id,
                    'quick_view' => Tools::getValue('action')=='quickview' ? true : false,
                    'reported' => $this->context->customer->logged ? Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'ets_mp_seller_report` WHERE id_customer="'.(int)$this->context->customer->id.'" AND id_seller="'.(int)$seller->id.'" AND id_product="'.(int)$id_product.'"'):false,
                    'is_captcha' => isset($is_captcha) ? $is_captcha:false,
                    'ETS_MP_ENABLE_CAPTCHA_TYPE' => Configuration::get('ETS_MP_ENABLE_CAPTCHA_TYPE'),
                    'ETS_MP_ENABLE_CAPTCHA_SITE_KEY2' => Configuration::get('ETS_MP_ENABLE_CAPTCHA_SITE_KEY2'),
                    'ETS_MP_ENABLE_CAPTCHA_SECRET_KEY2' => Configuration::get('ETS_MP_ENABLE_CAPTCHA_SECRET_KEY2'),
                    'ETS_MP_ENABLE_CAPTCHA_SITE_KEY3' => Configuration::get('ETS_MP_ENABLE_CAPTCHA_SITE_KEY3'),
                    'ETS_MP_ENABLE_CAPTCHA_SECRET_KEY3' => Configuration::get('ETS_MP_ENABLE_CAPTCHA_SECRET_KEY3'),
                    'vacation_notifications' => $seller->vacation_mode ? $seller->vacation_notifications:'',
                )
            );
            return $this->display(__FILE__,'product/product_detail.tpl');
        }
    }
    public function hookDisplayProductAdditionalInfo($params)
    {
        if(!Configuration::get('ETS_MP_ENABLED'))
            return '';
        if(isset($params['product']) && $product= $params['product'])
        {
            return $this->displaySellerInProductPage($product->id);
        }
    }
    public function hookDisplayRightColumnProduct()
    {
        if(!Configuration::get('ETS_MP_ENABLED'))
            return '';
        if($id_product=Tools::getValue('id_product'))
        {
            return $this->displaySellerInProductPage($id_product);
        }
    }
    public function hookDisplayCartExtraProductActions($params)
    {
        if(!Configuration::get('ETS_MP_ENABLED') || Tools::getValue('controller')!='order')
            return '';
        if(isset($params['product']) && $product= $params['product'])
        {
            $sql = 'SELECT p.id_customer FROM `'._DB_PREFIX_.'ets_mp_seller_product` p
            INNER JOIN `'._DB_PREFIX_.'ets_mp_seller` seller ON (p.id_customer=seller.id_customer)
            WHERE p.id_product='.(int)$product['id_product'].' AND seller.active!=0';
            if($id_customer = (int)Db::getInstance()->getValue($sql))
            {
                $seller= Ets_mp_seller::_getSellerByIdCustomer($id_customer,$this->context->language->id);
                $this->context->smarty->assign(
                    array(
                        'link_shop_seller' => $this->getShopLink(array('id_seller'=>$seller->id)),
                        'shop_name' => $seller->shop_name,
                        'link_contact_form' => $this->context->link->getModuleLink($this->name,'contactseller',array('id_product'=>$product['id_product'])),
                    )
                );
                return $this->display(__FILE__,'product/cart_detail.tpl');
            }
        }
    }
    public function hookDisplayProductPriceBlock($params)
    {
        return $this->hookDisplayCartExtraProductActions($params);
    }
    
    private function duplicateRowsFromDefaultShopLang($tableName, $shopId,$identifier)
    {
        $shopDefaultLangId = Configuration::get('PS_LANG_DEFAULT');
        $fields = array();
        $shop_field_exists = $primary_key_exists = false;
        $columns = Db::getInstance()->executeS('SHOW COLUMNS FROM `' . $tableName . '`');
        foreach ($columns as $column) {
            $fields[] = '`' . $column['Field'] . '`';
            if ($column['Field'] == 'id_shop') {
                $shop_field_exists = true;
            }
            if ($column['Field'] == $identifier) {
                $primary_key_exists = true;
            }
        }
        $fields = implode(',', $fields);

        if (!$primary_key_exists) {
            return true;
        }

        $sql = 'INSERT IGNORE INTO `' . $tableName . '` (' . $fields . ') (SELECT ';

        // For each column, copy data from default language
        reset($columns);
        $selectQueries = array();
        foreach ($columns as $column) {
            if ($identifier != $column['Field'] && $column['Field'] != 'id_lang') {
                $selectQueries[] = '(
							SELECT `' . bqSQL($column['Field']) . '`
							FROM `' . bqSQL($tableName) . '` tl
							WHERE tl.`id_lang` = ' . (int) $shopDefaultLangId . '
							' . ($shop_field_exists ? ' AND tl.`id_shop` = ' . (int) $shopId : '') . '
							AND tl.`' . bqSQL($identifier) . '` = `' . bqSQL(str_replace('_lang', '', $tableName)) . '`.`' . bqSQL($identifier) . '`
						)';
            } else {
                $selectQueries[] = '`' . bqSQL($column['Field']) . '`';
            }
        }
        $sql .= implode(',', $selectQueries);
        $sql .= ' FROM `' . _DB_PREFIX_ . 'lang` CROSS JOIN `' . bqSQL(str_replace('_lang', '', $tableName)) . '` ';

        // prevent insert with where initial data exists
        $sql .= ' WHERE `' . bqSQL($identifier) . '` IN (SELECT `' . bqSQL($identifier) . '` FROM `' . bqSQL($tableName) . '`) )';
        return Db::getInstance()->execute($sql);
    }
    public function hookActionObjectLanguageAddAfter()
    {
       $this->duplicateRowsFromDefaultShopLang(_DB_PREFIX_.'ets_mp_seller_lang',$this->context->shop->id,'id_seller');
       $this->createTemplateMail();
    }
    public function hookActionObjectCustomerDeleteAfter($params)
    {
        if($params['object']->id)
        {
            $seller = Ets_mp_seller::_getSellerByIdCustomer($params['object']->id);
            if($seller)
                $seller->delete();
            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'ets_mp_registration` WHERE id_customer='.(int)$params['object']->id);
        }
    }
    public function getLinkCustomerAdmin($id_customer)
    {
        if(version_compare(_PS_VERSION_, '1.7.6', '>='))
        {
            $sfContainer = call_user_func(array('\PrestaShop\PrestaShop\Adapter\SymfonyContainer','getInstance'));
        	if (null !== $sfContainer) {
        		$sfRouter = $sfContainer->get('router');
        		$link_customer= $sfRouter->generate(
        			'admin_customers_view',
        			array('customerId' => $id_customer)
        		);
        	}
        }
        else
            $link_customer = $this->context->link->getAdminLink('AdminCustomers').'&id_customer='.(int)$id_customer.'&viewcustomer';
        return $link_customer;
    }
    public function updateContext(Customer $customer)
	{
	    if ($this->is17)
	        return false;
        $this->context->cookie->id_compare = isset($this->context->cookie->id_compare) ? $this->context->cookie->id_compare: CompareProduct::getIdCompareByIdCustomer($customer->id);
        $this->context->cookie->id_customer = (int)($customer->id);
        $this->context->cookie->customer_lastname = $customer->lastname;
        $this->context->cookie->customer_firstname = $customer->firstname;
        $this->context->cookie->logged = 1;
        $customer->logged = 1;
        $this->context->cookie->is_guest = $customer->isGuest();
        $this->context->cookie->passwd = $customer->passwd;
        $this->context->cookie->email = $customer->email;
        // Add customer to the context
        $this->context->customer = $customer;
        if (Configuration::get('PS_CART_FOLLOWING') && (empty($this->context->cookie->id_cart) || Cart::getNbProducts($this->context->cookie->id_cart) == 0) && $id_cart = (int)Cart::lastNoneOrderedCart($this->context->customer->id)) {
            $this->context->cart = new Cart($id_cart);
        } else {
            $this->context->cart->id_carrier = 0;
            $this->context->cart->setDeliveryOption(null);
            $this->context->cart->id_address_delivery = (int)Address::getFirstCustomerAddressId((int)($customer->id));
            $this->context->cart->id_address_invoice = (int)Address::getFirstCustomerAddressId((int)($customer->id));
        }
        $this->context->cart->id_customer = (int)$customer->id;
        $this->context->cart->secure_key = $customer->secure_key;
        $this->context->cart->save();
        $this->context->cookie->id_cart = (int)$this->context->cart->id;
        $this->context->cookie->write();
        $this->context->cart->autosetProductAddress();
        Hook::exec('actionAuthentication', array('customer' => $this->context->customer));
        // Login information have changed, so we check if the cart rules still apply
        CartRule::autoRemoveFromCart($this->context);
        CartRule::autoAddToCart($this->context);
	}
    public function _getOrderMessages($filter='',$start=0,$limit=12,$order_by='',$total=false,$id_seller=0)
    {
        if(!$id_seller)
            $seller = $this->_getSeller();
        else
            $seller = new Ets_mp_seller($id_seller);
        if($seller)
        {
            $sql1 ='SELECT o.id_order,"" as id_contact,"" as title_contact,o.reference,cm.read,cm.message,cm.id_employee,"0" as id_seller,cm.date_add,CONCAT(manager.firstname," ",manager.lastname) as manager_name,CONCAT(customer.firstname," ",customer.lastname) as seller_name,CONCAT(c.firstname," ",c.lastname) as customer_name,CONCAT(e.firstname," ",e.lastname) as employee_name,
            cm_min.id_employee as id_employee_min,CONCAT(manager_min.firstname," ",manager_min.lastname) as manager_min_name,CONCAT(customer_min.firstname," ",customer_min.lastname) as seller_min_name,CONCAT(e_min.firstname," ",e_min.lastname) as employee_min_name
            FROM `'._DB_PREFIX_.'customer_thread` ct
            INNER JOIN `'._DB_PREFIX_.'customer_message` cm ON (cm.id_customer_thread=ct.id_customer_thread)
            INNER JOIN (SELECT id_customer_thread,max(id_customer_message) as id_customer_message_max FROM `'._DB_PREFIX_.'customer_message` group by id_customer_thread) last_message ON (last_message.id_customer_message_max=cm.id_customer_message AND last_message.id_customer_thread=ct.id_customer_thread)
            INNER JOIN `'._DB_PREFIX_.'orders` o ON (ct.id_order=o.id_order)
            INNER JOIN `'._DB_PREFIX_.'ets_mp_seller_order` so ON (o.id_order=so.id_order)  
            LEFT JOIN `'._DB_PREFIX_.'customer` c ON (c.id_customer=ct.id_customer)
            LEFT JOIN `'._DB_PREFIX_.'employee` e ON (e.id_employee=cm.id_employee)
            LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_customer_message` scm ON (scm.id_customer_message=cm.id_customer_message)
            LEFT JOIN `'._DB_PREFIX_.'customer` manager ON (manager.id_customer=scm.id_manager)
            LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller` seller ON (seller.id_customer=scm.id_customer)
            LEFT JOIN `'._DB_PREFIX_.'customer` customer ON (customer.id_customer=seller.id_customer)
            LEFT JOIN (
                SELECT id_customer_thread,MIN(id_customer_message) as id_customer_message_min FROM `'._DB_PREFIX_.'customer_message` group by id_customer_thread
            ) first_message ON (first_message.id_customer_thread=ct.id_customer_thread)
            LEFT JOIN `'._DB_PREFIX_.'customer_message` cm_min ON (first_message.id_customer_message_min=cm_min.id_customer_message)
            LEFT JOIN `'._DB_PREFIX_.'employee` e_min ON (e_min.id_employee=cm_min.id_employee)
            LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_customer_message` scm_min ON (scm_min.id_customer_message=cm_min.id_customer_message)
            LEFT JOIN `'._DB_PREFIX_.'customer` manager_min ON (manager_min.id_customer=scm_min.id_manager)
            LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller` seller_min ON (seller_min.id_customer=scm_min.id_customer)
            LEFT JOIN `'._DB_PREFIX_.'customer` customer_min ON (customer_min.id_customer=seller_min.id_customer)   
            WHERE so.id_customer="'.(int)$seller->id_customer.'" GROUP BY o.id_order';
            $sql2 = 'SELECT contact.id_order,contact.id_contact,cm.title as title_contact,o.reference,cm.read,cm.message,cm.id_employee,cm.id_seller,cm.date_add,CONCAT(manager.firstname," ",manager.lastname) as manager_name,CONCAT(customer.firstname," ",customer.lastname) as seller_name,if(contact.id_customer!=0,CONCAT(c.firstname," ",c.lastname),contact.name) as customer_name, CONCAT(e.firstname," ",e.lastname) as employee_name,
            "" as id_employee_min,"" as manager_min_name,"" as seller_min_name,"" as employee_min_name
            FROM `'._DB_PREFIX_.'ets_mp_seller_contact` contact
            INNER JOIN `'._DB_PREFIX_.'ets_mp_seller_contact_message` cm ON (contact.id_contact=cm.id_contact)
            INNER JOIN (SELECT id_contact,max(id_message) as id_message_max FROM `'._DB_PREFIX_.'ets_mp_seller_contact_message` GROUP BY id_contact) cmmax ON (cmmax.id_message_max = cm.id_message AND cmmax.id_contact= contact.id_contact)
            LEFT JOIN `'._DB_PREFIX_.'customer` c ON (c.id_customer= contact.id_customer)
            LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller` seller ON (seller.id_seller=cm.id_seller)
            LEFT JOIN `'._DB_PREFIX_.'customer` customer ON (customer.id_customer=seller.id_customer)
            LEFT JOIN `'._DB_PREFIX_.'customer` manager ON (manager.id_customer=cm.id_manager)
            LEFT JOIN `'._DB_PREFIX_.'employee` e ON (e.id_employee=cm.id_employee)
            LEFT JOIN `'._DB_PREFIX_.'orders` o ON (o.id_order=contact.id_order)
            WHERE contact.id_seller="'.(int)$seller->id.'"';
            $sql = "SELECT * FROM (($sql1) UNION ALL ($sql2)) as tb WHERE 1".($filter ? $filter :'');
            if($total)
                return count(Db::getInstance()->executeS($sql));
            else
            {
                $sql .= ($order_by ? ' ORDER By '.$order_by:'');
                $sql .= ' LIMIT '.(int)$start.','.(int)$limit;
                return Db::getInstance()->executeS($sql);
            }
        }
    }
    public function checkMultiSellerProductList($products)
    {
        $sellers = array();
        if($products)
        {
            foreach($products as $product)
            {
                $id_customer = Db::getInstance()->getValue('SELECT id_customer FROM `'._DB_PREFIX_.'ets_mp_seller_product` WHERE id_product="'.(int)$product['id_product'].'"');
                if(!isset($sellers[$id_customer]))
                    $sellers[$id_customer] = array();
                $sellers[$id_customer][]= $product;
            }
        }
        if(count($sellers)>=2)
            return $sellers;
        else
            return false;
    }
   
    public function checkCreatedColumn($table,$column)
    {
        $fieldsCustomers = Db::getInstance()->ExecuteS('DESCRIBE '._DB_PREFIX_.pSQL($table));
        $check_add=false;
        foreach($fieldsCustomers as $field)
        {
            if($field['Field']==$column)
            {
                $check_add=true;
                break;
            }    
        }
        return $check_add;
    }
    public static function isLink($inputLink)
    {
        if(Tools::strpos($inputLink,'http')===0)
        {
            $link_validation = '/(http|https)\:\/\/[a-zA-Z0-9\.\/\?\:@\-_=#]+\.([a-zA-Z0-9\&\.\/\?\:@\-_=#])*/';
            if(preg_match("$link_validation", $inputLink)){
                return  true;
            }
        }
        return false;
    }
    public function hookDisplayProductListReviews($params)
    {
        if(Tools::getValue('controller')!='shop')
        {
            if(isset($params['product']))
            {
                $product = $params['product'];
                $sql = 'SELECT p.id_customer FROM `'._DB_PREFIX_.'ets_mp_seller_product` p
                INNER JOIN `'._DB_PREFIX_.'ets_mp_seller` seller ON (p.id_customer=seller.id_customer)
                WHERE p.id_product='.(int)$product['id_product'].' AND seller.active!=0';
                if($id_customer = (int)Db::getInstance()->getValue($sql))
                {
                    $seller= Ets_mp_seller::_getSellerByIdCustomer($id_customer,$this->context->language->id);
                    $this->context->smarty->assign(
                        array(
                            'link_shop_seller' => $this->getShopLink(array('id_seller'=>$seller->id)),
                            'shop_name' => $seller->shop_name,
                            'logo_seller' => $seller->shop_logo,
                            'link_contact_form' => $this->context->link->getModuleLink($this->name,'contactseller',array('id_product'=>$product['id_product'])),
                        )
                    );
                    return $this->display(__FILE__,'product/product_list_detail.tpl');
                }
            }
        }
        
    }
    public function getTextLang($text, $lang,$file_name='')
    {
        if(is_array($lang))
            $iso_code = $lang['iso_code'];
        elseif(is_object($lang))
            $iso_code = $lang->iso_code;
        else
        {
            $language = new Language($lang);
            $iso_code = $language->iso_code;
        }
		$modulePath = rtrim(_PS_MODULE_DIR_, '/').'/'.$this->name;
        $fileTransDir = $modulePath.'/translations/'.$iso_code.'.'.'php';
        if(!@file_exists($fileTransDir)){
            return $text;
        }
        $fileContent = Tools::file_get_contents($fileTransDir);
        $text_tras = preg_replace("/\\\*'/", "\'", $text);
        $strMd5 = md5($text_tras);
        $keyMd5 = '<{' . $this->name . '}prestashop>' . ($file_name ? : $this->name) . '_' . $strMd5;
        preg_match('/(\$_MODULE\[\'' . preg_quote($keyMd5) . '\'\]\s*=\s*\')(.*)(\';)/', $fileContent, $matches);
        if($matches && isset($matches[2])){
           return  $matches[2];
        }
        return $text;
    }
}