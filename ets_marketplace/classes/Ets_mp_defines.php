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

if (!defined('_PS_VERSION_'))
	exit;
class Ets_mp_defines
{ 
    protected static $instance;
    public	function __construct()
	{
        $this->context= Context::getContext();
	}
    public function l($string)
    {
        return Translate::getModuleTranslation('ets_marketplace', $string, pathinfo(__FILE__, PATHINFO_FILENAME));
    }
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Ets_mp_defines();
        }
        return self::$instance;
    }
    public function _installDb(){
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_registration` (
        `id_registration` INT(11) NOT NULL AUTO_INCREMENT , 
        `id_customer` INT(11) NOT NULL , 
        `id_shop` INT(11) NOT NULL , 
        `shop_name` TEXT NOT NULL , 
        `shop_description` TEXT NOT NULL , 
        `shop_address` TEXT NOT NULL , 
        `shop_phone` VARCHAR(222) NOT NULL , 
        `vat_number` VARCHAR(222) NULL ,
        `shop_logo` VARCHAR(222) NOT NULL , 
        `shop_banner` VARCHAR(222) NOT NULL , 
        `banner_url` TEXT , 
        `link_facebook` VARCHAR(1000) NULL , 
        `link_instagram` VARCHAR(1000) NULL , 
        `link_google` VARCHAR(1000) NULL , 
        `link_twitter` VARCHAR(1000) NULL , 
        `message_to_administrator` TEXT NOT NULL ,
        `reason` text null, 
        `comment` text null,
        `date_add` DATETIME ,
        `date_upd` DATETIME ,
        `active` INT(1) NOT NULL , 
        `latitude` decimal(13,8),
        `longitude` decimal(13,8),
        PRIMARY KEY (`id_registration`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_seller` (
        `id_seller` INT(11) NOT NULL AUTO_INCREMENT , 
        `id_customer` INT(11) NOT NULL , 
        `id_shop` INT(11) NOT NULL ,
        `id_group` INT(11) NOT NULL ,  
        `id_billing` INT(11) NOT NULL ,
        `shop_phone` VARCHAR(222) NULL , 
        `vat_number` VARCHAR(222) NULL ,
        `shop_logo` VARCHAR(222) NULL , 
        `link_facebook` VARCHAR(1000) NULL , 
        `link_instagram` VARCHAR(1000) NULL , 
        `link_google` VARCHAR(1000) NULL , 
        `link_twitter` VARCHAR(1000) NULL ,
        `message_to_administrator` TEXT NULL , 
        `reason` text null, 
        `code_chat` TEXT NULL, 
        `commission_rate` FLOAT(10,2) NULL,
        `auto_enabled_product` varchar(22) NULL,
        `payment_verify` INT(1) NULL,
        `user_shipping` INT(1) NULL,
        `user_brand` INT(11) NULL,
        `user_supplier` INT(11) NULL,
        `user_attribute` INT(1) NULL,
        `user_feature` INT(11) NULL,
        `date_add` DATETIME ,
        `date_upd` DATETIME ,
        `date_from` DATE NULL,
        `date_to` DATE NULL, 
        `active` INT(1) NULL , 
        `mail_expired` INT(1) NULL ,
        `mail_going_to_be_expired` INT(1) NULL ,
        `mail_payed` INT(1) NULL ,
        `mail_wait_pay` INT(1) NULL,
        `latitude` decimal(13,8),
        `longitude` decimal(13,8),
        `vacation_mode` INT(1),
        `vacation_type` VARCHAR(100),
        PRIMARY KEY (`id_seller`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_seller_lang` ( 
        `id_seller` INT(11) NOT NULL , 
        `id_lang` INT NOT NULL , 
        `shop_name` VARCHAR(222) NULL , 
        `shop_banner` VARCHAR(222) NULL ,
        `banner_url` TEXT , 
        `shop_description` TEXT NULL , 
        `shop_address` TEXT NULL , 
        `vacation_notifications` TEXT NULL , 
        PRIMARY KEY (`id_seller`, `id_lang`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_seller_product` ( 
        `id_seller_product` INT(11) NOT NULL AUTO_INCREMENT ,
        `id_customer` INT(11) NOT NULL , 
        `id_product` INT(11) NOT NULL ,
        `approved` INT(11) NOT NULL , 
        `active` INT(11) NOT NULL , 
        `is_admin` INT(1) NOT NULL , 
        PRIMARY KEY (`id_seller_product`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_cart_rule_seller` ( 
        `id_cart_rule` INT(11) NOT NULL , 
        `id_customer` INT(11) NOT NULL, 
        PRIMARY KEY (`id_cart_rule`, `id_customer`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_seller_order`( 
        `id_customer` INT(11) NOT NULL , 
        `id_order` INT(11) NOT NULL ,
         PRIMARY KEY (`id_customer`, `id_order`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_seller_customer_message`( 
        `id_customer` INT(11) NOT NULL , 
        `id_manager` INT(11) NOT NULL , 
        `id_customer_message` INT(11) NOT NULL ,
         PRIMARY KEY (`id_customer`, `id_customer_message`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_seller_commission` ( 
        `id_seller_commission` INT(11) NOT NULL AUTO_INCREMENT , 
        `id_customer` INT(11) NOT NULL , 
        `reference` VARCHAR(22),
        `id_order` INT(11) NULL , 
        `id_product` INT(11) NULL , 
        `id_shop` INT(11) NULL,
        `id_product_attribute` INT(11) NULL,
        `product_name` VARCHAR(222) NULL, 
        `price` FLOAT(10,2) NULL , 
        `price_tax_incl` FLOAT(10,2) NULL , 
        `quantity` INT(11) NULL , 
        `total_price` FLOAT(10,2) NULL,
        `total_price_tax_incl` FLOAT(10,2) NULL,
        `use_tax` INT(11) NULL , 
        `status` INT(11) NULL,
        `commission` FLOAT(10,2) NULL, 
        `expired_date` DATETIME NULL,
        `note` text NULL,
        `date_add` DATETIME NULL,
        `date_upd` DATETIME NULL, 
        PRIMARY KEY (`id_seller_commission`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_commission_usage` ( 
        `id_ets_mp_commission_usage` INT(11) NOT NULL AUTO_INCREMENT , 
        `amount` FLOAT(10,2) NOT NULL , 
        `reference` VARCHAR(22),
        `id_customer` INT(11) NOT NULL , 
        `id_shop` INT(11) NOT NULL , 
        `id_voucher` INT(11) NULL , 
        `id_withdraw` INT(11) NULL , 
        `id_order` INT(11) NULL , 
        `id_currency` INT(11) NULL , 
        `status` INT(11) NULL , 
        `note` TEXT NULL , 
        `date_add` DATETIME NULL , 
        `deleted` INT(1) NULL , 
        PRIMARY KEY (`id_ets_mp_commission_usage`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_payment_method` ( 
        `id_ets_mp_payment_method` INT(11) NOT NULL AUTO_INCREMENT ,
        `id_shop` INT(11) NULL , 
        `fee_type` VARCHAR(222) NULL , 
        `fee_fixed` FLOAT(10,2) NULL , 
        `fee_percent` FLOAT(10,2) NULL , 
        `estimated_processing_time` INT(11) NULL , 
        `logo` VARCHAR(222) NULL ,
        `enable` INT(1) NULL , 
        `deleted` INT(1) NULL , 
        `sort` INT(3) NULL ,
        PRIMARY KEY (`id_ets_mp_payment_method`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_payment_method_lang` ( 
        `id_ets_mp_payment_method` INT(11) NOT NULL , 
        `id_lang` INT(11) NOT NULL , 
        `title` VARCHAR(222) CHARACTER SET utf8 COLLATE utf8_bin NULL, 
        `description` TEXT CHARACTER SET utf8 COLLATE utf8_bin NULL , 
        `note` TEXT CHARACTER SET utf8 COLLATE utf8_bin NULL , 
        PRIMARY KEY (`id_ets_mp_payment_method`, `id_lang`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_payment_method_field` ( 
        `id_ets_mp_payment_method_field` INT(11) NOT NULL AUTO_INCREMENT , 
        `id_ets_mp_payment_method` INT(11) NOT NULL , 
        `type` VARCHAR(222) NOT NULL , 
        `sort` INT(11) NOT NULL , 
        `required` TINYINT(1) NOT NULL , 
        `enable` TINYINT(1) NOT NULL , 
        `deleted` INT(1) NOT NULL , PRIMARY KEY (`id_ets_mp_payment_method_field`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_payment_method_field_lang` ( 
        `id_ets_mp_payment_method_field` INT(11) NOT NULL , 
        `id_lang` INT(11) NOT NULL ,
        `title` VARCHAR(222) NOT NULL , 
        `description` TEXT NOT NULL , 
        PRIMARY KEY (`id_ets_mp_payment_method_field`, `id_lang`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_attribute_group_seller` ( 
        `id_attribute_group` INT(11) NOT NULL , 
        `id_customer` INT(11) NOT NULL , 
        PRIMARY KEY (`id_attribute_group`, `id_customer`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_attribute_seller` ( 
        `id_attribute` INT(11) NOT NULL , 
        `id_customer` INT(11) NOT NULL , 
        PRIMARY KEY (`id_attribute`, `id_customer`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_feature_seller` ( 
        `id_feature` INT(11) NOT NULL , 
        `id_customer` INT(11) NOT NULL , 
        PRIMARY KEY (`id_feature`, `id_customer`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_manufacturer_seller` ( 
        `id_manufacturer` INT(11) NOT NULL , 
        `id_customer` INT(11) NOT NULL , 
        PRIMARY KEY (`id_manufacturer`, `id_customer`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_supplier_seller` ( 
        `id_supplier` INT(11) NOT NULL , 
        `id_customer` INT(11) NOT NULL , 
        PRIMARY KEY (`id_supplier`, `id_customer`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_cart_rule_seller` ( 
        `id_cart_rule` INT(11) NOT NULL , 
        `id_customer` INT(11) NOT NULL , 
        PRIMARY KEY (`id_cart_rule`, `id_customer`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_withdrawal` (
        `id_ets_mp_withdrawal` INT(11) NOT NULL AUTO_INCREMENT , 
        `id_ets_mp_payment_method` INT(11) NOT NULL , 
        `status` TINYINT(1) NOT NULL , 
        `fee` FLOAT(10,2) NOT NULL , 
        `fee_type` VARCHAR(222) NOT NULL , 
        `date_add` datetime NOT NULL ,
        `processing_date` datetime NULL ,
        PRIMARY KEY (`id_ets_mp_withdrawal`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_withdrawal_field` ( 
        `id_ets_mp_withdrawal_field` INT(11) NOT NULL AUTO_INCREMENT , 
        `id_ets_mp_withdrawal` INT(11) NOT NULL , 
        `id_ets_mp_payment_method_field` INT(11) NOT NULL , 
        `value` TEXT NOT NULL , 
        PRIMARY KEY (`id_ets_mp_withdrawal_field`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_seller_billing` ( 
        `id_ets_mp_seller_billing` INT(11) NOT NULL AUTO_INCREMENT ,
        `id_customer` INT(11),
        `id_shop` INT(11),
        `seller_confirm` INT(11),
        `amount` FLOAT(10,2) NULL ,
        `amount_tax` FLOAT(10,2) NULL ,
        `fee_type` VARCHAR(222),
        `reference` VARCHAR(22),
        `date_from` DATE NULL ,
        `date_to` DATE  NULL , 
        `active` INT(1) NULL ,
        `used` INT(1) NULL ,
        `date_add` datetime null,
        `date_upd` datetime null,  
        `note` text,
        `id_employee` int(11),  
         PRIMARY KEY (`id_ets_mp_seller_billing`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_seller_customer_follow`( 
        `id_seller` INT(11) NOT NULL , 
        `id_customer` INT(11) NOT NULL ,
         PRIMARY KEY (`id_seller`, `id_customer`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_seller_contact` ( 
        `id_contact` INT(11) NOT NULL AUTO_INCREMENT , 
        `id_customer` INT NULL , 
        `id_seller` INT NULL , 
        `id_product` INT NULL , 
        `id_order` int(11) NULL,
        `name` VARCHAR(222) NULL,
        `email` VARCHAR(222) NULL,
        `phone` VARCHAR(222) NULL,
        PRIMARY KEY (`id_contact`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_seller_contact_message` ( 
        `id_message` INT(11) NOT NULL AUTO_INCREMENT , 
        `id_contact` INT(11) NULL , 
        `id_customer` INT(11) NULL , 
        `id_seller` INT(11) NULL , 
        `id_manager` INT(11),
        `id_employee` INT(11) NULL , 
        `title` TEXT NULL , 
        `message` TEXT NULL , 
        `read` INT(1) NULL,
        `customer_read` INT(1) NULL,
        `attachment`  VARCHAR(222) NULL,
        `attachment_name`  VARCHAR(222) NULL,
        `date_add` DATETIME NULL , 
        PRIMARY KEY (`id_message`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_carrier_seller` ( 
        `id_carrier_reference` INT(11) NOT NULL , 
        `id_customer` INT(11) NOT NULL , 
        PRIMARY KEY (`id_carrier_reference`, `id_customer`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_seller_manager` ( 
        `id_ets_mp_seller_manager` INT(11) NOT NULL AUTO_INCREMENT , 
        `id_user` INT(11),
        `id_customer` INT(11),
        `email` VARCHAR(222) NOT NULL , 
        `permission` VARCHAR(1000) NOT NULL , 
        `active` INT(1),
        `delete_product` INT(1),
        PRIMARY KEY (`id_ets_mp_seller_manager`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_seller_report` ( 
        `id_ets_mp_seller_report` INT(11) NOT NULL AUTO_INCREMENT , 
        `id_customer` INT(11),
        `id_seller` INT(11),
        `id_product` INT(11),
        `title` VARCHAR(1000) NULL , 
        `content` text NULL , 
        `date_add` DATETIME NULL , 
        `date_upd` DATETIME NULL , 
        PRIMARY KEY (`id_ets_mp_seller_report`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_seller_group` ( 
            `id_ets_mp_seller_group` INT(11) NOT NULL AUTO_INCREMENT, 
            `id_shop` INT(11) NOT NULL , 
            `use_fee_global` INT(11) NOT NULL , 
            `use_commission_global` INT(11) NOT NULL , 
            `fee_type` VARCHAR(222) NOT NULL , 
            `badge_image` VARCHAR(222) NOT NULL , 
            `fee_amount` FLOAT(10,2) NOT NULL , 
            `fee_tax` INT(11) NOT NULL , 
            `commission_rate` FLOAT(10,2) NOT NULL ,
            `auto_upgrade` FLOAT(10,2) NOT NULL ,  
            `date_add` DATETIME NOT NULL , 
            `date_upd` DATETIME NOT NULL,
        PRIMARY KEY (`id_ets_mp_seller_group`) ) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
        Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_seller_group_lang` ( 
            `id_ets_mp_seller_group` INT(11) NOT NULL , 
            `id_lang` INT(11) NOT NULL , 
            `name` TEXT NOT NULL , 
            `level_name` TEXT,
            `description` TEXT NOT NULL , 
        PRIMARY KEY (`id_ets_mp_seller_group`, `id_lang`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
        return true;
    }
    public function getFieldConfig($type)
    {
        if($type=='configTabs')
        {
            return array(
                'conditions' => $this->l('Conditions'),
                'application' => $this->l('Application'),
                'memberships' => $this->l('Membership'),
                'seller_settings' => $this->l('Shop'),
                'map_settings' => $this->l('Map'),
                'commission_status' => $this->l('Order'),
                'email_settings' => $this->l('Email'),
                'message' => $this->l('Messages'),
                'contact_form' => $this->l('Contact form'),
                'home_page' => $this->l('Home page'),
            );
        }
        if($type=='sidebars')
        {
            return array(
                'dashboard' => array(
                    'title'=>$this->l('Dashboard'),
                    'controller' => 'AdminMarketPlaceDashboard',
                ),
                'orders' => array(
                    'title' => $this->l('Orders'),
                    'controller' => 'AdminMarketPlaceOrders',
                ),
                'products' => array(
                    'title'=> $this->l('Products'),
                    'controller'=>'AdminMarketPlaceProducts',
                ),
                'ratings' => array(
                    'title'=> $this->l('Ratings'),
                    'controller'=>'AdminMarketPlaceRatings',
                ),
                'commission' => array(
                    'title' => $this->l('Commissions'),
                    'controller'=> 'AdminMarketPlaceCommissions',
                ),
                'billing' => array(
                    'title' => $this->l('Membership'),
                    'controller'=> 'AdminMarketPlaceBillings', 
                ),
                'withdraw' => array(
                    'title' => $this->l('Withdrawals'),
                    'controller'=>'AdminMarketPlaceWithdrawals'
                ),
                'sellers_registration' => array(
                    'title' => $this->l('Applications'),
                    'controller'=>'AdminMarketPlaceRegistrations',
                ),
                'sellers' => array(
                    'title'=>$this->l('Shops'),
                    'controller' =>'AdminMarketPlaceSellers',
                    'subs' => array(
                        'AdminMarketPlaceSellers' => array(
                            'title' => $this->l('Shops'),
                            'controller'=> 'AdminMarketPlaceSellers',
                            'icon' => 'sellers',
                        ),
                        'AdminMarketPlaceShopGroups' => array(
                            'title' => $this->l('Shop groups'),
                            'controller'=> 'AdminMarketPlaceShopGroups',
                            'icon' => 'group',
                        ),
                        'AdminMarketPlaceReport' => array(
                            'title' => $this->l('Reports'),
                            'controller' => 'AdminMarketPlaceReport',
                            'icon' => 'report',
                        ),
                        
                    ),
                ),
                'settings' => array(
                    'title' => $this->l('Settings'),
                    'controller' => 'AdminMarketPlaceSettings',
                    'subs' => array(  
                        'AdminMarketPlaceSettingsGeneral' => array(
                            'title' => $this->l('General'),
                            'controller'=>'AdminMarketPlaceSettingsGeneral',
                            'icon' => 'settings'                            
                        ),              
                        'AdminMarketPlaceCommissionsUsage' => array(
                            'title' => $this->l('Commissions'),
                            'controller'=> 'AdminMarketPlaceCommissionsUsage',
                            'icon' => 'commissions-usage', 
                        ),
                        'AdminMarketPlaceCronJob' => array(
                            'title' => $this->l('Cronjob'),
                            'controller' => 'AdminMarketPlaceCronJob',
                            'icon' => 'cronjob',
                        ),
                    ),                    
                ),
            );
        }
        if($type =='commission_usage_settings')
        {
            return  array(
                array(
                    'type' =>'switch',
                    'label' => $this->l('Allow sellers to pay for their order using commission balance'),
                    'name' => 'ETS_MP_ALLOW_BALANCE_TO_PAY',
                    'default'=>1,
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'type' =>'text',
                    'name' => 'ETS_MP_MIN_BALANCE_REQUIRED_FOR_ORDER',
                    'label' => $this->l('Minimum commission balance required to be usable to pay for order'),
                    'validate' => 'isUnsignedFloat',
                    'suffix' => $this->context->currency->iso_code,
                    'form_group_class' => 'usage_order',
                    'desc' => $this->l('Commission balance need to exceed this value to allow sellers to use it for checkout process. Leave blank to allow sellers to use commission balance without this limit'),
                ),
                array(
                    'type' => 'text',
                    'name' => 'ETS_MP_MAX_BALANCE_REQUIRED_FOR_ORDER',
                    'validate' => 'isUnsignedFloat',
                    'label' => $this->l('Maximum commission balance can be used to pay for each order'),
                    'desc' => $this->l('The maximum amount of commission that can be used to pay for each order when sellers checkout. Leave blank to allow sellers to pay for their orders using any amount of commission they have in their account'),
                    'col' => 5,
                    'suffix' => $this->context->currency->iso_code,
                    'form_group_class' => 'usage_order',
                ),
                array(
                    'type' => 'switch',
                    'name'=>'ETS_MP_ALLOW_CONVERT_TO_VOUCHER',
                    'col' => 3,
                    'label' => $this->l('Allow sellers to convert commission balance into voucher'),
                    'desc' => $this->l('They can use this voucher when checking out their order'),
                    'default' => 1,
                    'form_group_class' => 'line_top',
                    'divider_before' => true,
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'type' => 'text',
                    'name' => 'ETS_MP_MIN_BALANCE_REQUIRED_FOR_VOUCHER',
                    'validate' => 'isUnsignedFloat',
                    'label' => $this->l('Minimum commission balance required to be usable to convert into voucher'),
                    'desc' => $this->l('Commission balance need to exceed this value to allow sellers to convert into voucher. Leave blank to allow sellers to use commission balance without this limit'),
                    'col' => 5,
                    'suffix' => $this->context->currency->iso_code,
                    'form_group_class' => 'usage_voucher',
                ),
                array(
                    'type' => 'text',
                    'name' => 'ETS_MP_MAX_BALANCE_REQUIRED_FOR_VOUCHER',
                    'validate' => 'isUnsignedFloat',
                    'label' => $this->l('Maximum commission balance that can be converted into voucher (each time)'),
                    'desc' => $this->l('The maximum amount of commission balance that sellers can convert into voucher code (each time they do that). Leave blank to allow sellers to convert any amount of commission balance into voucher code.'),
                    'col' => 5,
                    'suffix' => $this->context->currency->iso_code,
                    'form_group_class' => 'usage_voucher',
                ),
                array(
                    'type' => 'switch',
                    'col' => 3,
                    'name' => 'ETS_MP_ALLOW_VOUCHER_IN_CART',
                    'label' => $this->l('Display "Convert voucher" message in shopping cart'),
                    'default' => 1,
                    'form_group_class' => 'line_top',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                    'form_group_class' => 'usage_voucher',
                ),
                array(
                    'type'=>'text',
                    'name' => 'ETS_MP_DEFAULT_VOUCHER_NAME',
                    'lang'=>true,
                    'label' => $this->l('Discount name'),
                    'default'=> $this->l('Converted from commission balance'),
                    'form_group_class' => 'usage_voucher',
                ),
                array(
                    'type' => 'switch',
                    'col' => 3,
                    'name' => 'ETS_MP_ALLOW_WITHDRAW',
                    'label' => $this->l('Allow seller to withdraw commission'),
                    'default' => 1,
                    'divider_before' => true,
                    'form_group_class' => 'line_top',
                    'desc' => $this->l('Enable this feature to allow sellers to withdraw their commission balance to their bank account, PayPal account, Amazon gift card, etc. Create withdrawal methods you want in "Withdrawal methods" tab'),
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'type' => 'text',
                    'validate' => 'isUnsignedFloat',
                    'name' => 'ETS_MP_BALANCE_REQUIRED_FOR_WITHDRAW',
                    'label' => $this->l('Minimum commission balance required to be usable to withdraw'),
                    'desc' => $this->l('Seller balance need to exceed this value to allow sellers to withdraw. Leave blank to allow seller to use commission balance without this limit'),
                    'col' => 5,
                    'suffix' => $this->context->currency->iso_code,
                    'form_group_class' => 'usage_withdraw',
                ),
                array(
                    'type' => 'text',
                    'validate' => 'isUnsignedFloat',
                    'name' => 'ETS_MP_MAX_WITHDRAW',
                    'label' => $this->l('Maximum amount can withdraw each request'),
                    'col' => 5,
                    'suffix' => $this->context->currency->iso_code,
                    'desc' => $this->l('Maximum amount of commission balance that seller can withdraw (each time). Leave blank to allow seller to withdraw any amount of commission balance they have in their account.'),
                    'form_group_class' => 'usage_withdraw',
                ),
                array(
                    'type' => 'switch',
                    'col' => 3,
                    'name' => 'ETS_MP_WITHDRAW_INVOICE_REQUIRED',
                    'label' => $this->l('Require invoice?'),
                    'default' => 0,
                    'desc' => $this->l('Ask seller to submit an invoice when they withdraw their commission balance.'),
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                     'form_group_class' => 'usage_withdraw',
                ),
                array(
                    'type' => 'switch',
                    'name' => 'ETS_MP_WITHDRAW_ONE_ONLY',
                    'col' => 3,
                    'label' => $this->l('Require seller to wait until the last pending withdrawal request to be processed to submit a new one?'),
                    'default' => 0,
                    'desc' => $this->l('Enable this option will limit seller to be able to submit a new withdrawal request if the last one still being processed'),
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                     'form_group_class' => 'usage_withdraw',
                ),
           );
        }
        if($type =='settings')
        {
            $id_root_category = Db::getInstance()->getValue('SELECT id_category FROM `'._DB_PREFIX_.'category` WHERE is_root_category=1');
            $sub_categories_default=array();
            $categories = Db::getInstance()->executeS('SELECT id_category FROM `'._DB_PREFIX_.'category` WHERE id_parent='.(int)$id_root_category);
            if($categories)
            {
                foreach($categories as $category)
                    $sub_categories_default[]= $category['id_category'].',';
            }
            $groups = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'group` g
                INNER JOIN `'._DB_PREFIX_.'group_shop` gs ON (g.id_group=gs.id_group)
                LEFT JOIN `'._DB_PREFIX_.'group_lang` gl ON (g.id_group = gl.id_group AND gl.id_lang ="'.(int)$this->context->language->id.'")
                WHERE gs.id_shop = "'.(int)$this->context->shop->id.'" AND g.id_group!="'.(int)Configuration::get('PS_GUEST_GROUP').'" AND g.id_group!="'.(int)Configuration::get('PS_UNIDENTIFIED_GROUP').'"
                GROUP BY g.id_group');
            $default_groups = '';
            if($groups)
            {
                foreach($groups as $group)
                    $default_groups .= $group['id_group'].',';
            }
            $status = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'order_state` os
                LEFT JOIN `'._DB_PREFIX_.'order_state_lang` osl ON (os.id_order_state =osl.id_order_state AND osl.id_lang="'.(int)$this->context->language->id.'")
                GROUP BY os.id_order_state
            ');
            $disabled_categories =array();
            $roots = Db::getInstance()->executeS('SELECT id_category FROM `'._DB_PREFIX_.'category` WHERE is_root_category=1');
            if($roots)
            {
                foreach($roots as $root)
                    $disabled_categories[$root['id_category']] = $root['id_category'];
            }
            $ets_marketplace = Module::getInstanceByName('ets_marketplace');
            $context = $this->context;
            return array(
                array(
                    'type' =>'switch',
                    'label' => $this->l('Enable marketplace'),
                    'name' => 'ETS_MP_ENABLED',
                    'default'=>1,
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                    'tab'=>'conditions',
                    'desc'=>$this->l('Enable marketplace and allow customers to become sellers of your website. They can upload their products to sell on your website and get commission')
                ),
                array(
                    'type'=>'checkbox',
                    'name' => 'ETS_MP_SELLER_GROUPS',
                    'label' => $this->l('Applicable customer group'),
                    'desc' => $this->l('Select customer groups who can join marketplace and become a seller'),
                    'values' => array(
                        'query'=> $groups,
                        'id' => 'id_group',
                        'name' => 'name',
                    ),
                    'tab'=>'conditions',
                    'default' => trim($default_groups,','),
                    'required' => true,
                ),
                array(
                    'type' =>'switch',
                    'label' => $this->l('Require customers to submit an application'),
                    'name' => 'ETS_MP_REQUIRE_REGISTRATION',
                    'default'=>1,
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                    'tab'=>'application',
                    'desc'=>$this->l('You (admin) will need to manually approve marketplace joining requests')
                ),
                array(
                    'type' => 'checkbox',
                    'name' => 'ETS_MP_REGISTRATION_FIELDS',
                    'tab'=>'application',
                    'label'=> $this->l('Select fields to get information from sellers'),
                    'default' => 'seller_name,seller_email,shop_phone,message_to_administrator',
                    'values' => array(
                        'query'=> array(
                            array(
                                'id'=> 'seller_name',
                                'name' => $this->l('Seller name'),
                            ),
                            array(
                                'id'=> 'seller_email',
                                'name' => $this->l('Email'),
                            ),
                            array(
                                'id'=> 'shop_phone',
                                'name' => $this->l('Phone number'),
                            ),
                            array(
                                'id'=> 'message_to_administrator',
                                'name' => $this->l('Introduction'),
                            ),
                            array(
                                'id'=> 'shop_name',
                                'name' => $this->l('Shop name'),
                            ),
                            array(
                                'id'=> 'shop_description',
                                'name' => $this->l('Shop description'),
                            ),
                            array(
                                'id'=> 'shop_address',
                                'name' => $this->l('Shop address'),
                            ),
                            array(
                                'id'=> 'latitude',
                                'name' => $this->l('Latitude'),
                            ),
                            array(
                                'id'=> 'longitude',
                                'name' => $this->l('Longitude'),
                            ),
                            array(
                                'id'=> 'vat_number',
                                'name' => $this->l('VAT number'),
                            ),
                            array(
                                'id'=> 'shop_logo',
                                'name' => $this->l('Shop logo'),
                            ),
                            array(
                                'id'=> 'shop_banner',
                                'name' => $this->l('Shop banner'),
                            ),
                            array(
                                'id'=> 'banner_url',
                                'name' => $this->l('Banner URL'),
                            ),
                            array(
                                'id'=> 'link_facebook',
                                'name' => $this->l('Facebook link'),
                            ),
                            array(
                                'id'=> 'link_instagram',
                                'name' => $this->l('Instagram link'),
                            ),
                            array(
                                'id'=> 'link_google',
                                'name' => $this->l('Google link'),
                            ),
                            array(
                                'id'=> 'link_twitter',
                                'name' => $this->l('Twitter link'),
                            ),

                        ),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'form_group_class' => 'ets_mp_registration_fields'
                ),
                array(
                    'name'=> 'ETS_MP_TERM_LINK',
                    'type'=> 'text',
                    'label' => $this->l('"Terms and condition of use" link'),
                    'desc' => $this->l('Seller is required to read and accept the "Terms and conditions of use" to submit their application. Leave blank to ignore this requirement.'),
                    'tab'=>'application',
                    'lang'=> true,
                    'form_group_class' => 'ets_mp_registration_fields'
                ),
                array(
                    'type' => 'checkbox',
                    'name' => 'ETS_MP_CONTACT_FIELDS',
                    'tab'=>'contact_form',
                    'label'=> $this->l('Select fields to display on contact shop form'),
                    'default' => 'product_link,title,reference,message,attachment',
                    'values' => array(
                        'query'=> array(
                            array(
                                'id'=> 'product_link',
                                'name' => $this->l('Product link'),
                            ),
                            array(
                                'id'=> 'name',
                                'name' => $this->l('Name'),
                            ),
                            array(
                                'id'=> 'email',
                                'name' => $this->l('Email'),
                            ),
                            array(
                                'id'=> 'phone',
                                'name' => $this->l('Phone'),
                            ),
                            array(
                                'id'=> 'title',
                                'name' => $this->l('Title'),
                            ),
                            array(
                                'id'=> 'reference',
                                'name' => $this->l('Order reference'),
                            ),
                            array(
                                'id'=> 'message',
                                'name' => $this->l('Message'),
                            ),
                            array(
                                'id'=> 'attachment',
                                'name' => $this->l('Attached file'),
                            ),

                        ),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                ),
                array(
                    'name' => $this->l('ETS_MP_SELLER_GROUP_DEFAULT'),
                    'type'=>'select',
                    'label' => $this->l('Default seller group'),
                    'options' => array(
            			 'query' => array_merge(array(array('id_group'=>'0','name'=>'--')),Ets_mp_seller_group::_getSellerGroups()),                             
                         'id' => 'id_group',
            			 'name' => 'name'  
                    ),    
                    'default' => 0,
                    'tab'=>'memberships', 
                ),
                array(
                    'name'=> 'ETS_MP_SELLER_FEE_TYPE',
                    'type' => 'radio',
                    'label' => $this->l('Registration fee to become a seller'),
                    'values' => array(
                        array(
                            'id' => 'ETS_MP_SELLER_FEE_TYPE_no_fee',
                            'value'=>'no_fee',
                            'label' => $this->l('No fee'),
                        ),
                        array(
                            'id' => 'ETS_MP_SELLER_FEE_TYPE_pay_once',
                            'value'=>'pay_once',
                            'label' => $this->l('Pay once')
                        ),
                        array(
                            'id' => 'ETS_MP_SELLER_FEE_TYPE_monthly_fee',
                            'value'=>'monthly_fee',
                            'label' => $this->l('Monthly fee')
                        ),
                        array(
                            'id' => 'ETS_MP_SELLER_FEE_TYPE_quarterly_fee',
                            'value'=>'quarterly_fee',
                            'label' => $this->l('Quarterly fee')
                        ),
                        array(
                            'id' => 'ETS_MP_SELLER_FEE_TYPE_yearly_fee',
                            'value'=>'yearly_fee',
                            'label' => $this->l('Yearly fee')
                        ),
                    ),
                    'tab'=>'memberships',
                    'default' => 'no_fee',
                ),
                array(
                    'name' => 'ETS_MP_SELLER_FEE_AMOUNT',
                    'label' => $this->l('Fee amount'),
                    'tab'=>'memberships',
                    'type'=>'text',
                    'required' => true,
                    'validate' => 'isPrice', 
                    'form_group_class' => 'ets_mp_fee',
                    'suffix' => $this->context->currency->sign,
                ),
                array(
                    'name' => 'ETS_MP_SELLER_FEE_TAX',
                    'label' => $this->l('Fee tax'),
                    'type' => 'select',
                    'options' => array(
            			 'query' => TaxRulesGroup::getTaxRulesGroupsForOptions(),                             
                         'id' => 'id_tax_rules_group',
            			 'name' => 'name'  
                    ),    
                    'default' => 0,
                    'tab'=>'memberships', 
                    'form_group_class'=>'ets_mp_fee'                          
                ),
                array(
                    'type' => 'switch',
                    'name' => 'ETS_MP_ENABLED_IF_NO_FEE',
                    'label' => $this->l('Auto approve seller shop when "No fee" is selected'),
                    'default'=> 1,
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                    'tab'=>'memberships',
                    'form_group_class' => 'ets_mp_no_fee',
                ),
                array(
                    'name' => 'ETS_MP_SELLER_FEE_EXPLANATION',
                    'type'=>'textarea',
                    'label'=> $this->l('Fee explanation'),
                    'tab'=>'memberships',
                    'lang'=> true,
                    'desc' => $this->l('Display fee explanation to sellers. Available tag: [highlight][fee_amount][end_highlight]. Leave this field blank if you do not want to display the fee explanation'),
                ),
                array(
                    'name' => 'ETS_MP_MESSAGE_EXPIRE_BEFORE_DAY',
                    'type' => 'text',
                    'label'=> $this->l('Display message when seller account is going to be expired before'),
                    'default'=>7,
                    'validate' => 'isunsignedInt',
                    'tab'=>'memberships',
                    'suffix'=> $this->l('day(s)'),
                    'desc' => $this->l('On X days before seller account is expired, a notification will be display to seller. X is the time value you set above.'),
                ),
                array(
                    'name' =>'ETS_MP_SELLER_PAYMENT_INFORMATION',
                    'validate'=>'isCleanHtml',
                    'label'=> $this->l('Payment information of the marketplace manager'),
                    'default' => $this->l('PayPal account: example@paypal_email'),
                    'type' => 'textarea',
                    'lang'=> true,
                    'tab'=>'memberships',
                    'desc' => $this->l('Enter your PayPal account or bank account here to get registration fee. Available tag:[highlight][shop_id][end_highlight],[highlight][shop_name][end_highlight],[highlight][seller_name][end_highlight],[highlight][seller_email][end_highlight]'),
                    'required' => true,
                ),
                array(
                    'type' => 'switch',
                    'name' => 'ETS_MP_APPROVE_AUTO_BY_BILLING',
                    'label' => $this->l('Approve seller account automatically when billing invoice is set as Paid'),
                    'default'=> 1,
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                    'tab'=>'memberships',
                ),
                array(
                    'type' =>'switch',
                    'label' => $this->l('Seller products need to be approved by Admin'),
                    'name' => 'ETS_MP_SELLER_PRODUCT_APPROVE_REQUIRED',
                    'default'=>0,
                    'tab'=>'seller_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                    'desc' => $this->l('Uploaded product is invisible until it is approved by Admin'),
                ),
                array(
                    'name' => 'ETS_MP_APPLICABLE_CATEGORIES',
                    'label' => $this->l('Applicable product categories'),
                    'desc' => $this->l('Select product categories which sellers can submit their products to sell on marketplace'),
                    'type' => 'radio',
                    'default' => 'all_product_categories',
                    'values' => array(
                        array(
                            'id' => 'ETS_MP_APPLICABLE_CATEGORIES_all_product_categories',
                            'value'=>'all_product_categories',
                            'label' => $this->l('All product categories')
                        ),
                        array(
                            'id' => 'ETS_MP_APPLICABLE_CATEGORIES_specific_product_categories',
                            'label' => $this->l('Specific product categories'),
                            'value' => 'specific_product_categories'
                        )
                    ),
                    'tab'=>'seller_settings',
                    'required' => true,
                ),
                array(
                    'name'=>'ETS_MP_SELLER_CATEGORIES',
                    'label' => $this->l('Categories'),
                    'type' => 'tre_categories',
                    'default' => $sub_categories_default,
                    'use_checkbox'=>true,
                    'form_group_class' => 'seller_categories',
                    'tab'=>'seller_settings',
                    'required2'=>true,
                    'tree'=> $ets_marketplace->displayProductCategoryTre($ets_marketplace->getCategoriesTree(),Tools::getValue('ETS_MP_SELLER_CATEGORIES',explode(',',Configuration::get('ETS_MP_SELLER_CATEGORIES'))),'ETS_MP_SELLER_CATEGORIES',$disabled_categories,0,true),
                ),
                array(
                    'name' => 'ETS_MP_COMMISSION_RATE',
                    'type'=> 'text',
                    'label' => $this->l('Global shop commission rate'),
                    'suffix' => '%',
                    'default'=> '90',
                    'desc' => $this->l('You can customize commission rate for each seller shop or shop group. Click').' <'.'a hr'.'ef="'.(isset($context->employee) && $context->employee->id ? $this->context->link->getAdminLink('AdminMarketPlaceShopGroups') :'#').'" tar'.'get="_bla'.'nk">'.$this->l('here').'<'.'/'.'a'.'> '.$this->l('to config'),
                    'required' => true,
                    'tab'=>'seller_settings',
                    'validate' => 'isPrice',
                ),
                array(
                    'type' =>'switch',
                    'label' => $this->l('Exclude taxes before calculating commission'),
                    'name' => 'ETS_MP_COMMISSION_EXCLUDE_TAX',
                    'default'=>1,
                    'tab'=>'seller_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'type' =>'switch',
                    'label' => $this->l('Recalculate commission when admin add/edit order'),
                    'name' => 'ETS_MP_RECALCULATE_COMMISSION',
                    'default'=>1,
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                    'tab'=>'seller_settings',
                ),
                array(
                    'type' =>'switch',
                    'label' => $this->l('Allow sellers to generate their voucher code?'),
                    'name' => 'ETS_MP_SELLER_CAN_CREATE_VOUCHER',
                    'default'=>1,
                    'tab'=>'seller_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                    'desc' => $this->l('Sellers can generate their voucher code to send to their customers'),
                ),
                array(
                    'type' =>'switch',
                    'label' => $this->l('Allow seller to add new product'),
                    'name' => 'ETS_MP_ALLOW_SELLER_CREATE_PRODUCT',
                    'default'=>1,
                    'tab'=>'seller_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'type' =>'switch',
                    'label' => $this->l('Allow seller to edit product'),
                    'name' => 'ETS_MP_ALLOW_SELLER_EDIT_PRODUCT',
                    'default'=>1,
                    'tab'=>'seller_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'type' =>'switch',
                    'label' => $this->l('Allow seller to delete product'),
                    'name' => 'ETS_MP_ALLOW_SELLER_DELETE_PRODUCT',
                    'default'=>1,
                    'tab'=>'seller_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'type' =>'switch',
                    'label' => $this->l('Allow seller to create their own carriers (shipping methods)'),
                    'name' => 'ETS_MP_SELLER_CREATE_SHIPPING',
                    'default'=>0,
                    'tab'=>'seller_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'type' =>'switch',
                    'label' => $this->l('Allow seller to use store\'s global carriers (shipping methods)'),
                    'name' => 'ETS_MP_SELLER_USER_GLOBAL_SHIPPING',
                    'default'=>1,
                    'tab'=>'seller_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                    'form_group_class' => 'create_shipping',
                ),
                array(
                    'type' =>'switch',
                    'label' => $this->l('Allow seller to create their own brands (manufacturers)'),
                    'name' => 'ETS_MP_SELLER_CREATE_BRAND',
                    'default'=>1,
                    'tab'=>'seller_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'type' =>'switch',
                    'label' => $this->l('Allow seller to use store\'s global brands (manufacturers)'),
                    'name' => 'ETS_MP_SELLER_USER_GLOBAL_BRAND',
                    'default'=>1,
                    'tab'=>'seller_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                    'form_group_class' => 'create_brand',
                ),
                array(
                    'type' =>'switch',
                    'label' => $this->l('Allow seller to create their own suppliers'),
                    'name' => 'ETS_MP_SELLER_CREATE_SUPPLIER',
                    'default'=>1,
                    'tab'=>'seller_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'type' =>'switch',
                    'label' => $this->l('Allow seller to use store\'s global suppliers'),
                    'name' => 'ETS_MP_SELLER_USER_GLOBAL_SUPPLIER',
                    'default'=>1,
                    'tab'=>'seller_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                    'form_group_class' => 'create_supplier',
                ),
                array(
                    'name' => 'ETS_MP_SELLER_PRODUCT_TYPE_SUBMIT',
                    'type'=> 'checkbox',
                    'label' => $this->l('Available product types for seller'),
                    'desc' => $this->l('Select product types which sellers can upload to their shop'),
                    'tab'=>'seller_settings',
                    'required' => true,
                    'default' => 'standard_product,pack_product,virtual_product',
                    'values' => array(
                        'query' => array(
                            array(
                                'id' => 'standard_product',
                                'name' => $this->l('Standard product'),
                            ),
                            array(
                                'id'=> 'pack_product',
                                'name'=>$this->l('Pack of product'),
                            ),
                            array(
                                'id'=> 'virtual_product',
                                'name'=>$this->l('Virtual product'),
                            ),
                        ),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                ),
                array(
                    'type' =>'switch',
                    'label' => $this->l('Allow seller to create product with combinations'),
                    'name' => 'ETS_MP_SELLER_CREATE_PRODUCT_ATTRIBUTE',
                    'default'=>1,
                    'tab'=>'seller_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'type' =>'switch',
                    'label' => $this->l('Allow seller to create their own product attributes'),
                    'name' => 'ETS_MP_SELLER_CREATE_ATTRIBUTE',
                    'default'=>1,
                    'tab'=>'seller_settings',
                    'form_group_class' => 'create_attribute',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'type' =>'switch',
                    'label' => $this->l('Allow seller to use store\'s global attributes'),
                    'name' => 'ETS_MP_SELLER_USER_GLOBAL_ATTRIBUTE',
                    'default'=>1,
                    'tab'=>'seller_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                    'form_group_class' => 'create_attribute',
                ),
                array(
                    'type' =>'switch',
                    'label' => $this->l('Allow seller to create their own product features'),
                    'name' => 'ETS_MP_SELLER_CREATE_FEATURE',
                    'default'=>1,
                    'tab'=>'seller_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'type' =>'switch',
                    'label' => $this->l('Allow seller to use store\'s global features'),
                    'name' => 'ETS_MP_SELLER_USER_GLOBAL_FEATURE',
                    'default'=>1,
                    'tab'=>'seller_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                    'form_group_class' => 'create_feature',
                ),
                array(
                    'type' =>'switch',
                    'label' => $this->l('Display reviews that are waiting to be approved'),
                    'name' => 'ETS_MP_SELLER_DISPLAY_REVIEWS_WAITING',
                    'default'=>1,
                    'tab'=>'seller_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                    'form_group_class' => 'create_feature',
                ),
                array(
                    'type' =>'switch',
                    'label' => $this->l('Allow seller to approve reviews from customers'),
                    'name' => 'ETS_MP_SELLER_APPROVE_REVIEW',
                    'default'=>0,
                    'tab'=>'seller_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                    'form_group_class' => '',
                ),
                array(
                    'type' =>'switch',
                    'label' => $this->l('Allow seller to delete review'),
                    'name' => 'ETS_MP_SELLER_DELETE_REVIEW',
                    'default'=>0,
                    'tab'=>'seller_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                    'form_group_class' => '',
                ),
                array(
                    'type' =>'switch',
                    'label' => $this->l('Return shipping fee to seller'),
                    'name' => 'ETS_MP_RETURN_SHIPPING',
                    'default'=>1,
                    'tab'=>'seller_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                    'form_group_class' => '',
                ),
                array(
                    'name' => 'ETS_MP_SELLER_ALLOWED_INFORMATION_SUBMISSION',
                    'label' => $this->l('Allow seller to submit these product information'),
                    'type'=> 'checkbox',
                    'tab'=>'seller_settings',
                    'required' => true,
                    'values' => array(
                        'query' => array(
                            array(
                                'id'=> 'product_reference',
                                'name' => $this->l('Product reference'),
                            ),
                            array(
                                'id'=> 'short_description',
                                'name' => $this->l('Short description'),
                            ),
                            array(
                                'id' => 'specific_price',
                                'name' => $this->l('Specific price'),
                            ),
                            array(
                                'id' => 'tax',
                                'name' => $this->l('Tax'),
                            ),
                            array(
                                'id'=>'out_of_stock_behavior',
                                'name' => $this->l('Out of stock behavior')
                            ),
                            array(
                                'id'=>'seo',
                                'name' => $this->l('SEO'),
                            )
                        ),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'default'=> 'product_reference,short_description,specific_price,tax,out_of_stock_behavior,brand,seo',
                    'desc' => $this->l('Above information are not required. You can disable them to simplify the submission of Seller product form'),
                ),
                array(
                    'name' =>'ETS_MP_SELLER_MESSAGE_DISPLAYED',
                    'type' => 'switch',
                    'label' => $this->l('Display seller messages'),
                    'default' =>1,
                    'desc' => $this->l('Display seller messages on the order details'),
                    'tab'=>'seller_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'name' =>'ETS_MP_DISPLAY_CUSTOMER_EMAIL',
                    'type' => 'switch',
                    'label' => $this->l('Display customer email on front office'),
                    'default' =>0,
                    'tab'=>'seller_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'name' =>'ETS_MP_SELLER_ALLOWED_EMBED_CHAT',
                    'type' => 'switch',
                    'label' => $this->l('Allow seller to embed live chat code'),
                    'default' =>1,
                    'desc' => $this->l('Allow seller to embed a live chat code to start chatting with their customers, for example: Zendesk, Zopim chat'),
                    'tab'=>'seller_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'name' =>'ETS_MP_SELLER_ALLOWED_IMPORT_EXPORT_PRODUCTS',
                    'type' => 'switch',
                    'label' => $this->l('Allow seller to import / export their products'),
                    'default' =>1,
                    'desc' => $this->l('Allow seller to import / export products via CSV file'),
                    'tab'=>'seller_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'name' =>'ETS_MP_SHOP_ALIAS',
                    'type' =>'text',
                    'lang'=>true,
                    'default' => 'shops',
                    'label' => $this->l('Shop URL alias'),
                    'tab'=>'seller_settings',
                    'required' => true,
                ),
                array(
                    'name' =>'ETS_MP_URL_SUBFIX',
                    'type' => 'switch',
                    'label' => $this->l('Use URL suffix'),
                    'default' => !Configuration::get('PS_ROUTE_product_rule') || Tools::strpos(Configuration::get('PS_ROUTE_product_rule'),'.html') ? 0:1,
                    'desc' => $this->l('Enable to add ".html" to the end of URLs'),
                    'tab'=>'seller_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'name' =>'ETS_MP_SHOP_META_TITLE',
                    'type' =>'text',
                    'lang'=>true,
                    'label' => $this->l('Shop meta title'),
                    'tab'=>'seller_settings',
                    'validate'=>'isCleanHtml',
                ),
                array(
                    'name' =>'ETS_MP_SHOP_META_DESCRIPTION',
                    'type' =>'textarea',
                    'lang'=>true,
                    'label' => $this->l('Shop meta description'),
                    'tab'=>'seller_settings',
                    'validate'=>'isCleanHtml',
                ),
                array(
                    'type' => 'switch',
                    'name' => 'ETS_MP_DISPLAY_FOLLOWED_SHOP',
                    'label' => $this->l('Display followed shops'),
                    'default'=> 1,
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                    'tab'=>'home_page',
                ),
                array(
                    'name' =>'ETS_MP_DISPLAY_NUMBER_SHOP',
                    'type' =>'text',
                    'label' => $this->l('Number of shops to display'),
                    'tab'=>'home_page',
                    'validate'=>'isUnsignedInt',
                    'default' =>12,
                    'col'=>2,
                    'required'=>true,
                    'form_group_class'=>'shop_home'
                ),
                array(
                    'type' => 'switch',
                    'name' => 'ETS_MP_DISPLAY_PRODUCT_FOLLOWED_SHOP',
                    'label' => $this->l('Display products from followed shops'),
                    'default'=> 1,
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                    'tab'=>'home_page',
                ),
                array(
                    'name' =>'ETS_MP_DISPLAY_NUMBER_PRODUCT_FOLLOWED_SHOP',
                    'type' =>'text',
                    'label' => $this->l('Number of followed products to display'),
                    'tab'=>'home_page',
                    'validate'=>'isUnsignedInt',
                    'default'=>12,
                    'col'=>2,
                    'required'=>true,
                    'form_group_class'=>'shop_product_home'
                ),
                array(
                    'type' => 'switch',
                    'name' => 'ETS_MP_DISPLAY_PRODUCT_TRENDING_SHOP',
                    'label' => $this->l('Display trending products'),
                    'default'=> 1,
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                    'tab'=>'home_page',
                ),
                array(
                    'name' =>'ETS_MP_TRENDING_PERIOD_SHOP',
                    'type' =>'text',
                    'label' => $this->l('Trending period'),
                    'tab'=>'home_page',
                    'validate'=>'isUnsignedInt',
                    'default'=>30,
                    'col'=>2,
                    'required'=>true,
                    'suffix' => $this->l('Day(s)'),
                    'form_group_class'=>'trending_product_home'
                ),
                array(
                    'name' =>'ETS_MP_DISPLAY_NUMBER_PRODUCT_TRENDING_SHOP',
                    'type' =>'text',
                    'label' => $this->l('Number of trending products to display'),
                    'tab'=>'home_page',
                    'validate'=>'isUnsignedInt',
                    'default'=>12,
                    'col'=>2,
                    'required'=>true,
                    'form_group_class'=>'trending_product_home'
                ),
                array(
                    'type' => 'switch',
                    'name' => 'ETS_MP_DISPLAY_PRODUCT_SOLD',
                    'label' => $this->l('Display number of products sold'),
                    'default'=> 1,
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                    'tab'=>'seller_settings',
                ),
                array(
                    'type' => 'switch',
                    'name' => 'ETS_MP_VACATION_MODE_FOR_SELLER',
                    'label' => $this->l('Enable vacation mode for seller'),
                    'desc' => $this->l('Allow sellers to temporarily close their shop. All products in vacation mode will be disabled and cannot be enabled until seller shop is back to online.'),
                    'default'=> 0,
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                    'tab'=>'seller_settings',
                ),
                array(
                    'type' => 'switch',
                    'name' => 'ETS_MP_ENABLE_CAPTCHA',
                    'label' => $this->l('Enable captcha'),
                    'default'=> 0,
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                    'tab'=>'seller_settings',
                ),
                array(
                    'type' => 'switch',
                    'name' => 'ETS_MP_ENABLE_MAP',
                    'label' => $this->l('Enable basic shop map'),
                    'default'=> 0,
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                    'desc' => $this->l('Basic map with shop locations. No API key is required.'),
                    'tab'=>'map_settings',
                ),
                array(
                    'type' => 'switch',
                    'name' => 'ETS_MP_SEARCH_ADDRESS_BY_GOOGLE',
                    'label' => $this->l('Enable advanced shop map'),
                    'default'=> 0,
                    'form_group_class' => 'map',
                    'desc' => $this->l('Enable address suggestions, auto-fill coordinates and shop address searching. Google API key is required'),
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                    'tab'=>'map_settings',
                ),
                array(
                    'type' => 'text',
                    'name'=>'ETS_MP_GOOGLE_MAP_API',
                    'label' => $this->l('API key'),
                    'tab'=>'map_settings',
                    'col'=>4,
                    'required' => true,
                    'form_group_class' => 'map search',
                    'desc' => sprintf($this->l('Please add a new project in your %s, create an API key then enable 3 following APIs for the project:'),'<'.'a hr'.'ef="https://console.cloud.google.com/" tar'.'get="_bl'.'ank">Google Console<'.'/'.'a'.'>').
                    '<'.'b'.'r'.'>Maps JavaScript API: <'.'a hr'.'ef="https://developers.google.com/maps/documentation/javascript/get-api-key" tar'.'get="_bl'.'ank">https://developers.google.com/maps/documentation/javascript/get-api-key<'.'/'.'a'.'>'
                    .'<'.'b'.'r'.'>Places API: <'.'a hr'.'ef="https://developers.google.com/places/web-service/get-api-key" tar'.'get="_bl'.'ank">https://developers.google.com/places/web-service/get-api-key<'.'/'.'a'.'>'
                    .'<'.'b'.'r'.'>Geocoding API: <'.'a hr'.'ef="https://developers.google.com/maps/documentation/geocoding/get-api-key" tar'.'get="_bl'.'ank">https://developers.google.com/maps/documentation/geocoding/get-api-key<'.'/'.'a'.'>',
                ),
                array(
                    'type'=>'file',
                    'name' => 'ETS_MP_GOOGLE_MAP_LOGO',
                    'label' => $this->l('Map marker'),
                    'tab'=>'map_settings',
                    'imageType' => 'map',
                    'display_img' => Configuration::get('ETS_MP_GOOGLE_MAP_LOGO') ? __PS_BASE_URI__.'modules/ets_marketplace/views/img/'.Configuration::get('ETS_MP_GOOGLE_MAP_LOGO'):__PS_BASE_URI__.'modules/ets_marketplace/views/img/logo_map.png',
                    'desc' => sprintf($this->l('Accepted formats: jpg, png, gif, jpeg. Limit: %sMb'),Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE')),
                    'form_group_class' => 'map badge_image',
                    'img_del_link' => Configuration::get('ETS_MP_GOOGLE_MAP_LOGO') ? $this->context->link->getAdminLink('AdminMarketPlaceSettingsGeneral').'&delImage=ETS_MP_GOOGLE_MAP_LOGO&current_tab=map_settings':'',
                ),
                array(
                    'type' => 'checkbox',
                    'name' => 'ETS_MP_ENABLE_CAPTCHA_FOR',
                    'label' => $this->l('Enable captcha for'),
                    'default'=> 'shop_contact,shop_report',
                    'form_group_class' => 'captcha',
                    'required2' => true,
                    'values' => array(
                        'query' => array(
                            array(
                                'id'=>'shop_contact',
                                'name' => $this->l('Contact form of seller shop')
                            ),
                            array(
                                'id'=>'shop_report',
                                'name' => $this->l('Report')
                            )
                        ),
                        'id' => 'id',
                        'name' => 'name',
                    ),
                    'tab'=>'seller_settings',
                ),
                array(
                    'type' => 'select',
                    'name' => 'ETS_MP_ENABLE_CAPTCHA_TYPE',
                    'label' => $this->l('Captcha type'),
                    'default'=> 'shop_contact,shop_report',
                    'form_group_class' => 'captcha',
                    'options' => array(
            			 'query' => array(
                            array(
                                'id'=>'google_v2',
                                'name' => $this->l('Google reCAPTCHA v2')
                            ),
                            array(
                                'id'=>'google_v3',
                                'name' => $this->l('Google reCAPTCHA v3')
                            )
                         ),                             
                         'id' => 'id',
            			 'name' => 'name'  
                    ),    
                    'tab'=>'seller_settings',
                ),
                array(
                    'type'=>'text',
                    'name'=>'ETS_MP_ENABLE_CAPTCHA_SITE_KEY2',
                    'label' => $this->l('Site key'),
                    'form_group_class' => 'captcha v2',
                    'required2' => true,
                    'tab'=>'seller_settings',
                ),
                array(
                    'type'=>'text',
                    'name'=>'ETS_MP_ENABLE_CAPTCHA_SECRET_KEY2',
                    'label' => $this->l('Secret key'),
                    'form_group_class' => 'captcha v2',
                    'required2' => true,
                    'tab'=>'seller_settings',
                ),
                array(
                    'type'=>'text',
                    'name'=>'ETS_MP_ENABLE_CAPTCHA_SITE_KEY3',
                    'label' => $this->l('Site key'),
                    'form_group_class' => 'captcha v3',
                    'required2' => true,
                    'tab'=>'seller_settings',
                ),
                array(
                    'type'=>'text',
                    'name'=>'ETS_MP_ENABLE_CAPTCHA_SECRET_KEY3',
                    'label' => $this->l('Secret key'),
                    'form_group_class' => 'captcha v3',
                    'required2' => true,
                    'tab'=>'seller_settings',
                ),
                array(
                    'type' => 'switch',
                    'name' => 'ETS_MP_NO_CAPTCHA_IS_LOGIN',
                    'label' => $this->l('Do not require registered user'),
                    'default'=> 1,
                    'form_group_class' => 'captcha',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                    'tab'=>'seller_settings',
                ),
                array(
                    'name' => 'ETS_MP_COMMISSION_PENDING_WHEN',
                    'label' => $this->l('Commission will be created with "Pending" status if order status is'),
                    'type'=> 'checkbox',
                    'tab'=>'commission_status',
                    'form_group_class' => 'commission_status',
                    'values' => array(
                        'query' => OrderState::getOrderStates($this->context->language->id),
                        'id' => 'id_order_state',
                        'name' => 'name',
                    ),
                    'default' => $this->_getOrderStateDefault('pending'),
                ),
                array(
                    'name' => 'ETS_MP_COMMISSION_APPROVED_WHEN',
                    'label' => $this->l('Commission will be created with "Approved" status if order status is'),
                    'type'=> 'checkbox',
                    'tab'=>'commission_status',
                    'form_group_class' => 'commission_status',
                    'values' => array(
                        'query' => OrderState::getOrderStates($this->context->language->id),
                        'id' => 'id_order_state',
                        'name' => 'name',
                    ),
                    'default' => $this->_getOrderStateDefault('approved'),
                   
                ),
                array(
                    'name'=>'ETS_MP_VALIATE_COMMISSION_IN_DAYS',
                    'type'=>'text',
                    'label' => $this->l('Only validate commission if order has been changed to statuses above for'),
                    'suffix' => $this->l('Days'),
                    'desc' => $this->l('Commission status will remain "Pending" status until the required days exceeded. Leave blank to validate commission immediately when order status is changed to one of the statuses above'),
                    'tab'=>'commission_status',
                    'form_group_class' => 'commission_status',
                ),
                array(
                    'name' => 'ETS_MP_COMMISSION_CANCELED_WHEN',
                    'label' => $this->l('Cancel commission if order status is'),
                    'type'=> 'checkbox',
                    'tab'=>'commission_status',
                    'form_group_class' => 'commission_status',
                    'values' => array(
                        'query' => OrderState::getOrderStates($this->context->language->id),
                        'id' => 'id_order_state',
                        'name' => 'name',
                    ),
                    'default' => $this->_getOrderStateDefault('cancel'),
                ),
                array(
                    'type' =>'switch',
                    'label' => $this->l('Allow seller to update order status'),
                    'name' => 'ETS_MP_SELLER_CAN_CHANGE_ORDER_STATUS',
                    'default'=>1,
                    'tab'=>'commission_status',
                    'form_group_class' => 'commission_status',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'type' =>'checkbox',
                    'label' => $this->l('Select order status which sellers can update'),
                    'name' => 'ETS_MP_SELLER_ALLOWED_STATUSES',
                    'default'=>'1,3,10,13',
                    'desc' => $this->l('Sellers can change the status of any order which contain their products'),
                    'tab'=>'commission_status',
                    'required' => true,
                    'values' => array(
                        'query'=> $status,
                        'id' => 'id_order_state',
                        'name' => 'name',
                    ),
                    'form_group_class' => 'ets_mp_allowed_statuses'
                ),
                array(
                    'name'=>'ETS_MP_EMAIL_ADMIN_NOTIFICATION',
                    'type' => 'text',
                    'tab'=> 'email_settings',
                    'label' => $this->l('Email addresses to receive notifications'),
                    'validate' => 'isEmail',
                    'desc' => $this->l('Notifcation messages ("New commission has been created","Commission has been validated","New withdrawal request", etc.) will be sent to these emails. Enter email addresses separated by a comma (",") if you want to send notification messages to more than 1 email'),
                    'default' => Configuration::get('PS_SHOP_EMAIL'),
                ),
                array(
                    'name' =>'ETS_MP_EMAIL_ADMIN_APPLICATION_REQUEST',
                    'type' => 'switch',
                    'label' => $this->l('When sellers submits an application to join marketplace'),
                    'default' =>1,
                    'tab'=> 'email_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'name' =>'ETS_MP_EMAIL_ADMIN_SHOP_CREATED',
                    'type' => 'switch',
                    'label' => $this->l('When seller creates shop'),
                    'default' =>1,
                    'tab'=> 'email_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'name' =>'ETS_MP_EMAIL_ADMIN_NEW_PRODUCT_UPLOADED',
                    'type' => 'switch',
                    'label' => $this->l('When new product is uploaded by seller'),
                    'default' =>1,
                    'tab'=> 'email_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),  
                 array(
                    'name' =>'ETS_MP_EMAIL_ADMIN_COMMISSION_CREATED',
                    'type' => 'switch',
                    'label' => $this->l('When a commission is created for seller'),
                    'default' =>1,
                    'tab'=> 'email_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'name' =>'ETS_MP_EMAIL_ADMIN_COMMISSION_VALIDATED_OR_CANCELED',
                    'type' => 'switch',
                    'label' => $this->l('When a commission is validated or canceled'),
                    'default' =>1,
                    'tab'=> 'email_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ), 
                array(
                    'name' =>'ETS_MP_EMAIL_ADMIN_WITHDRAWAL_CREATED',
                    'type' => 'switch',
                    'label' => $this->l('When a withdrawal is created'),
                    'default' =>1,
                    'tab'=> 'email_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'name' =>'ETS_MP_EMAIL_ADMIN_CONFIRMED_PAYMENT',
                    'type' => 'switch',
                    'label' => $this->l('When seller confirmed payment'),
                    'default' =>1,
                    'tab'=> 'email_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'name' =>'ETS_MP_EMAIL_ADMIN_REPORT',
                    'type' => 'switch',
                    'label' => $this->l('When a shop or product is reported as abused'),
                    'default' =>1,
                    'tab'=> 'email_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'name' =>'ETS_MP_EMAIL_SELLER_APPLICATION_APPROVED_OR_DECLINED',
                    'type' => 'switch',
                    'label' => $this->l('When seller application is approved or declined'),
                    'default' =>1,
                    'tab'=> 'email_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'name' =>'ETS_MP_EMAIL_SELLER_BILLING_CREATED',
                    'type' => 'switch',
                    'label' => $this->l('When billing is created for seller'),
                    'default' =>1,
                    'tab'=> 'email_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'name' =>'ETS_MP_EMAIL_SELLER_SHOP_ACTIVED_OR_DECLINED',
                    'type' => 'switch',
                    'label' => $this->l('When seller shop is activate or declined'),
                    'default' =>1,
                    'tab'=> 'email_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'name' =>'ETS_MP_EMAIL_SELLER_GOING_TOBE_EXPIRED',
                    'type' => 'switch',
                    'label' => $this->l('When their seller account is going to be expired'),
                    'default' =>1,
                    'tab'=> 'email_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                    'desc' => $this->l('Send email when seller account is going to be expired before').' '.Configuration::get('ETS_MP_MESSAGE_EXPIRE_BEFORE_DAY'). ' '.$this->l('day(s)'),
                ),
                array(
                    'name' =>'ETS_MP_EMAIL_SELLER_EXPIRED',
                    'type' => 'switch',
                    'label' => $this->l('When their seller account is expired'),
                    'default' =>1,
                    'tab'=> 'email_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'name' =>'ETS_MP_EMAIL_SELLER_DISABLED',
                    'type' => 'switch',
                    'label' => $this->l('When seller account is disabled'),
                    'default' =>1,
                    'tab'=> 'email_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'name' =>'ETS_MP_EMAIL_SELLER_PRODUCT_APPROVED_OR_DECLINED',
                    'type' => 'switch',
                    'label' => $this->l('When seller product is approved or declined'),
                    'default' =>1,
                    'tab'=> 'email_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),  
                array(
                    'name' =>'ETS_MP_EMAIL_SELLER_PRODUCT_PURCHASED',
                    'type' => 'switch',
                    'label' => $this->l('When seller products are purchased'),
                    'default' =>1,
                    'tab'=> 'email_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),        
                array(
                    'name' =>'ETS_MP_EMAIL_SELLER_COMMISSION_CREATED',
                    'type' => 'switch',
                    'label' => $this->l('When commission is created for seller'),
                    'default' =>1,
                    'tab'=> 'email_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'name' =>'ETS_MP_EMAIL_SELLER_COMMISSION_VALIDATED_OR_CANCELED',
                    'type' => 'switch',
                    'label' => $this->l('When commission is validated or canceled'),
                    'default' =>1,
                    'tab'=> 'email_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'name' =>'ETS_MP_EMAIL_SELLER_WITHDRAWAL_APPROVED',
                    'type' => 'switch',
                    'label' => $this->l('When seller withdrawal is approved or declined'),
                    'default' =>1,
                    'tab'=> 'email_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                
                array(
                    'name' =>'ETS_MP_EMAIL_NEW_CONTACT',
                    'type' => 'switch',
                    'label' => $this->l('Send notification email to seller/customer when customer/seller contacts'),
                    'default' =>1,
                    'tab'=> 'email_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'name' =>'ETS_MP_EMAIL_SELLER_REPORT',
                    'type' => 'switch',
                    'label' => $this->l('When a shop or product is reported as abused'),
                    'default' =>1,
                    'tab'=> 'email_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'name' =>'ETS_MP_EMAIL_SELLER_UPGRADED_GROUP',
                    'type' => 'switch',
                    'label' => $this->l('When shop group is upgraded'),
                    'default' =>1,
                    'tab'=> 'email_settings',
                    'values' => array(
        				array(
        					'id' => 'active_on',
        					'value' => 1,
        					'label' => $this->l('Yes')
        				),
        				array(
        					'id' => 'active_off',
        					'value' => 0,
        					'label' => $this->l('No')
        				)
        			),
                ),
                array(
                    'name' => 'ETS_MP_MESSAGE_INVITE',
                    'type' => 'textarea',
                    'validate'=>'isCleanHtml',
                    'lang'=> true,
                    'tab' => 'message',
                    'label' => $this->l('Invite customers to join marketplace'),
                    'desc' => $this->l('Display on “My account/Seller account" area to introduce/invite customers to join marketplace. Available tags: [highlight][fee_amount][end_highlight]'),
                    'default' => $this->l('Your seller profile has not been enabled yet. In order to join our marketplace to sell your products and get commission, please submit an application with required information.'),
                ),
                array(
                    'name' => 'ETS_MP_MESSAGE_SUBMITTED',
                    'type' => 'textarea',
                    'validate'=>'isCleanHtml',
                    'lang'=> true,
                    'tab' => 'message',
                    'label' => $this->l('Message when application is submitted'),
                    'default' => $this->l('Your application has been submitted successfully. Our team are reviewing the application, and we will get back to you as soon as possible.'),
                ),
                array(
                    'name' => 'ETS_MP_MESSAGE_APPLICATION_ACCEPTED',
                    'type' => 'textarea',
                    'lang'=> true,
                    'tab' => 'message',
                    'validate'=>'isCleanHtml',
                    'label' => $this->l('Message when application is accepted'),
                    'default' => $this->l('Congratulations! Your application is accepted. You can create your shop now.'),
                ),
                array(
                    'name' => 'ETS_MP_MESSAGE_APPLICATION_DECLINED',
                    'type' => 'textarea',
                    'lang'=> true,
                    'tab' => 'message',
                    'validate'=>'isCleanHtml',
                    'label' => $this->l('Message when application is declined'),
                    'desc' => $this->l('Available tags: [highlight][application_declined_reason][end_highlight]'),
                    'default' => $this->l('Sorry! Your application is declined.
                    Reason: [application_declined_reason]'),
                ),
                array(
                    'name' => 'ETS_MP_MESSAGE_CREATED_SHOP_NO_FEE',
                    'type' => 'textarea',
                    'lang'=> true,
                    'tab' => 'message',
                    'validate'=>'isCleanHtml',
                    'label' => $this->l('Message when seller create the shop (No fee)'),
                    'default' => $this->l('Thanks for creating your shop. Our team are reviewing it. We will get back to you soon'),
                ),
                array(
                    'name' => 'ETS_MP_MESSAGE_CREATED_SHOP_FEE_REQUIRED',
                    'type' => 'textarea',
                    'validate'=>'isCleanHtml',
                    'lang'=> true,
                    'tab' => 'message',
                    'label' => $this->l('Message when seller create the shop (Fee require)'),
                    'desc' => $this->l('Available tags: [highlight][fee_amount][end_highlight], [highlight][payment_information_manager][end_highlight], [highlight][manager_email][end_highlight], [highlight][manager_phone][end_highlight]'),
                    'default' => $this->l('Thanks for creating your shop. Please send the fee [fee_amount] right now to activate your shop and click on the button "I have just sent the fee" after making payment. [payment_information_manager]'),
                ),
                array(
                    'name' => 'ETS_MP_MESSAGE_CONFIRMED_PAYMENT',
                    'type' => 'textarea',
                    'validate'=>'isCleanHtml',
                    'lang'=> true,
                    'tab' => 'message',
                    'label' => $this->l('Message when seller confirmed the payment'),
                    'default' => $this->l('Thanks for confirming that you have just sent the fee, we will check it and get back to you as soon as possible'),
                ),
                array(
                    'name' => 'ETS_MP_MESSAGE_SHOP_ACTIVED',
                    'type' => 'textarea',
                    'validate'=>'isCleanHtml',
                    'lang'=> true,
                    'tab' => 'message',
                    'label' => $this->l('Message when created shop is accepted'),
                    'default' => $this->l('Congratulations! Your shop is now activate. You can upload products and start selling them'),
                ),
                array(
                    'name' => 'ETS_MP_MESSAGE_SHOP_DECLINED',
                    'type' => 'textarea',
                    'validate'=>'isCleanHtml',
                    'lang'=> true,
                    'tab' => 'message',
                    'label' => $this->l('Message when created shop is declined'),
                    'desc' => $this->l('Available tags: [highlight][shop_declined_reason][end_highlight]'),
                    'default' => $this->l('Sorry! Your created shop is declined.
Reason: [shop_declined_reason]'),
                ),
                array(
                    'name' => 'ETS_MP_MESSAGE_SELLER_GOING_TOBE_EXPIRED',
                    'type' => 'textarea',
                    'validate'=>'isCleanHtml',
                    'label' => $this->l('Message when seller account is going to be expired'),
                    'lang'=> true,
                    'tab' => 'message',
                    'desc' => $this->l('Display this message until when seller renews the account or until the expired date is reached. Available tags: [highlight][remaining_day][end_highlight]'),
                    'default' => $this->l('Your seller account will be expired after [remaining_day] day. Please send the fee to renew the account'),
                ),
                array(
                    'name' => 'ETS_MP_MESSAGE_SELLER_IS_EXPIRED',
                    'type' => 'textarea',
                    'validate'=>'isCleanHtml',
                    'label' => $this->l('Message when seller account is expired'),
                    'lang'=> true,
                    'tab' => 'message',
                    'default' => $this->l('Your seller account is expired. If you want to continue using the seller account, please contact us via our email: [store_email]'),
                    'desc' => $this->l('Available tags: [highlight][store_email][end_highlight]'),
                ),
                array(
                    'name' => 'ETS_MP_MESSAGE_SELLER_IS_DISABLED',
                    'type' => 'textarea',
                    'validate'=>'isCleanHtml',
                    'label' => $this->l('Message when seller account is disabled'),
                    'lang'=> true,
                    'tab' => 'message',
                    'default' => $this->l('Your seller account is disabled. If you want to continue using the seller account, please contact us via our email: [store_email]'),
                    'desc' => $this->l('Available tags: [highlight][store_email][end_highlight]'),
                ),
            );
        }
    }
    public function _getOrderStateDefault($type='pending')
    {
        $orderStates = OrderState::getOrderStates($this->context->language->id);
        $defaults= array();
        if($type=='pending')
            $templates = array('cheque','bankwire','cashondelivery','outofstock','preparation');
        elseif($type=='approved')
            $templates = array('payment','payment','');
        elseif($type=='cancel')
            $templates = array('order_canceled','refund','payment_error');
        if($orderStates)
        {
            foreach($orderStates as $orderState)
            {
                if(in_array($orderState['template'],$templates))
                    $defaults[] = $orderState['id_order_state'];
            }
        }
        if($defaults)
            return implode(',',$defaults);
        else
            return '';
    }
    public function processDuplicate($id_product=0,&$errors,$seller=null)
    {
        if(!$id_product)
            $id_product = Tools::getValue('id_product');
        if (($product = new Product((int) $id_product)) &&  Validate::isLoadedObject($product) && !$errors) {
            $id_product_old = $product->id;
            if (empty($product->price)) {
                $product_price = new Product($id_product_old, false, null, $this->context->shop->id);
                $product->price = $product_price->price;
            }
            unset(
                $product->id,
                $product->id_product
            );
            $languages = Language::getLanguages(false);
            foreach($languages as $language)
            {
                $product->name[$language['id_lang']] = $this->l('Copy of').' '.$product->name[$language['id_lang']];
            }    
            $product->indexed = 0;
            if($seller)
            {
                if($seller->auto_enabled_product=='no')
                {
                    $product->active=0;
                    $approved=0;
                }
                elseif(Configuration::get('ETS_MP_SELLER_PRODUCT_APPROVE_REQUIRED'))
                {
                    $product->active=0;
                    $approved=0;
                }
            }
            if ($product->add()
            && ($seller ? Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'ets_mp_seller_product`(id_customer,id_product,approved,active) VALUES("'.(int)$seller->id_customer.'","'.(int)$product->id.'","'.(isset($approved) ? (int)$approved :($product->active ? 1 :0)).'","'.(int)$product->active.'")') : true)
            && Category::duplicateProductCategories($id_product_old, $product->id)
            && Product::duplicateSuppliers($id_product_old, $product->id)
            && ($combination_images = Product::duplicateAttributes($id_product_old, $product->id)) !== false
            && GroupReduction::duplicateReduction($id_product_old, $product->id)
            && Product::duplicateAccessories($id_product_old, $product->id)
            && Product::duplicateFeatures($id_product_old, $product->id)
            && Product::duplicateSpecificPrices($id_product_old, $product->id)
            && Pack::duplicate($id_product_old, $product->id)
            && Product::duplicateCustomizationFields($id_product_old, $product->id)
            && Product::duplicateTags($id_product_old, $product->id)
            && Product::duplicateDownload($id_product_old, $product->id)) {
                if ($product->hasAttributes()) {
                    Product::updateDefaultAttribute($product->id);
                }
                if (!Tools::getValue('noimage') && !Image::duplicateProductImages($id_product_old, $product->id, $combination_images)) {
                    $errors[] = $this->l('An error occurred while copying the image.');
                } else {
                    Hook::exec('actionProductAdd', array('id_product_old' => $id_product_old, 'id_product' => (int) $product->id, 'product' => $product));
                    if (in_array($product->visibility, array('both', 'search')) && Configuration::get('PS_SEARCH_INDEXATION')) {
                        Search::indexation(false, $product->id);
                    }
                    $this->context->cookie->success_message = $this->l('Product successfully duplicated.');
                    return $product->id;
                }
            } else {
                $errors[] = $this->l('An error occurred while creating an object.');
            }
        }elseif(!$errors)
            $errors[] = $this->l('Product is not valid');
    }
}