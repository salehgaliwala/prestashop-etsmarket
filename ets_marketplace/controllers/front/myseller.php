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
class Ets_MarketPlaceMySellerModuleFrontController extends ModuleFrontController
{
    public $seller;
    public function __construct()
	{
		parent::__construct();
        $this->display_column_right=false;
        $this->display_column_left =false;
	}
    public function postProcess()
    {
        parent::postProcess();
        $this->seller = $this->module->_getSeller();
        if(!$this->context->customer->logged ||  !Configuration::get('ETS_MP_ENABLED') || (!($registration = Ets_mp_registration::_getRegistration()) && Configuration::get('ETS_MP_REQUIRE_REGISTRATION') && !$this->seller))
            Tools::redirect($this->context->link->getPageLink('my-account'));
        if(!$this->seller && $registration && Configuration::get('ETS_MP_REQUIRE_REGISTRATION') && $registration->active!=1)
        {
            Tools::redirect($this->context->link->getModuleLink($this->module->name,'registration'));
        }
        elseif(!$this->seller)
           Tools::redirect($this->context->link->getModuleLink($this->module->name,'create'));
    }
    public function initContent()
	{
		parent::initContent();
        $this->context->smarty->assign(
            array(
                'html_content' => $this->_initContent(),
            )
        );
        if($this->module->is17)
            $this->setTemplate('module:'.$this->module->name.'/views/templates/front/myseller.tpl');      
        else        
            $this->setTemplate('myseller_16.tpl'); 
    }
    public function _initContent()
    {
        if(Configuration::get('ETS_MP_SELLER_PRODUCT_TYPE_SUBMIT'))
            $product_types = explode(',',Configuration::get('ETS_MP_SELLER_PRODUCT_TYPE_SUBMIT'));
        else
            $product_types = array();
        $seller_pages = array(
            'dashboard'=>array(
                'page' => 'dashboard',
                'name' => $this->module->l('Dashboard','myseller'),
                ),
            'orders'=>array(
                'page' => 'orders',
                'name' => $this->module->l('Orders','myseller'),
            ),
            'products'=>array(
                'page' => 'products',
                'name' => $this->module->l('Products','myseller'),
                'link' => $this->context->link->getModuleLink($this->module->name,'products',array('list'=>true)),
            ),
            'stock'=>array(
                'page' => 'stock',
                'name' => $this->module->l('Stock','myseller'),
                'link' => $this->context->link->getModuleLink($this->module->name,'stock',array('list'=>true)),
            ),
            'ratings' => array(
                'page' =>'ratings',
                'name'=> $this->module->l('Ratings','myseller')
            ),
            'messages' => array(
                'page' => 'messages',
                'icon' => 'fa fa-comments',
                'name' => $this->module->l('Messages','myseller'),
            ),
            'commissions'=>array(
                'page' => 'commissions',
                'name' => $this->module->l('Commissions','myseller'),
                'link' => $this->context->link->getModuleLink($this->module->name,'commissions',array('list'=>true)),
            ),
            'attributes'=>array(
                'page' => 'attributes',
                'name' => in_array('standard_product',$product_types) && $this->module->_use_attribute && $this->module->_use_feature ?  $this->module->l('Attributes and features','myseller') : ($this->module->_use_feature ? $this->module->l('Features','myseller') : $this->module->l('Attributes','myseller') ),
                'link' => in_array('standard_product',$product_types) && $this->module->_use_attribute ? $this->context->link->getModuleLink($this->module->name,'attributes') : $this->context->link->getModuleLink($this->module->name,'features'),
            ),
            'carrier'=>array(
                'page' => 'carrier',
                'name' => $this->module->l('Carriers','myseller'),
                'link' => $this->context->link->getModuleLink($this->module->name,'carrier',array('list'=>true)),
            ),
            'brands' =>array(
                'page'=> 'brands',
                'name' => $this->module->l('Brands','myseller'),
                'link' => $this->context->link->getModuleLink($this->module->name,'brands',array('list'=>true))
            ),
            'suppliers' =>array(
                'page'=> 'suppliers',
                'name' => $this->module->l('Suppliers','myseller'),
                'link' => $this->context->link->getModuleLink($this->module->name,'suppliers',array('list'=>true))
            ),
            'billing' =>array(
                'page' => 'billing',
                'name' => $this->module->l('Membership','myseller'),
                'link' => $this->context->link->getModuleLink($this->module->name,'billing',array('list'=>true)),
            ),
            'withdraw'=>array(
                'page' => 'withdraw',
                'name' => $this->module->l('Withdrawals','myseller'),
                'link' => $this->context->link->getModuleLink($this->module->name,'withdraw'),
            ),
            'voucher'=> array(
                'page' => 'voucher',
                'name' => $this->module->l('My vouchers','myseller')
            ),
            'discount' => array(
                'page' => 'discount',
                'name' => $this->module->l('Discounts','myseller'),
                'link' => $this->context->link->getModuleLink($this->module->name,'discount',array('list'=>true))
            ),
            'profile'=> array(
                'page' => 'profile',
                'name' => $this->module->l('Seller profile','myseller')
            ),
            'manager' => array(
                'page' =>'manager',
                'name'=> $this->module->l('Shop Managers','myseller')
            ),
            'shop'=> array(
                'page' => 'shop',
                'name' => $this->module->l('My shop','myseller'),
                'link'=> $this->module->getShopLink(array('id_seller'=>$this->seller->id)),
                'new_tab'=> true,
            )
        );
        if(!Configuration::get('ETS_MP_SELLER_CAN_CREATE_VOUCHER'))
            unset($seller_pages['discount']);
        if(!Configuration::get('ETS_MP_SELLER_ALLOWED_IMPORT_EXPORT_PRODUCTS'))
            unset($seller_pages['import']);
        if(!(in_array('standard_product',$product_types) && $this->module->_use_attribute) && !$this->module->_use_feature)
            unset($seller_pages['attributes']);
        if(!Configuration::get('ETS_MP_SELLER_CREATE_BRAND') && !Configuration::get('ETS_MP_SELLER_USER_GLOBAL_BRAND'))
            unset($seller_pages['brands']);
        if(!Configuration::get('ETS_MP_SELLER_CREATE_SUPPLIER') && !Configuration::get('ETS_MP_SELLER_USER_GLOBAL_SUPPLIER'))
        {
            unset($seller_pages['suppliers']);
        }
        if(!Configuration::get('ETS_MP_SELLER_CREATE_SHIPPING') && !Configuration::get('ETS_MP_SELLER_USER_GLOBAL_SHIPPING'))
            unset($seller_pages['carrier']);
        if(!Configuration::get('ETS_MP_ALLOW_CONVERT_TO_VOUCHER'))
            unset($seller_pages['voucher']);
        if(!Configuration::get('ETS_MP_ALLOW_WITHDRAW'))
            unset($seller_pages['withdraw']);
        if(!Module::isEnabled('productcomments') && !Module::isEnabled('ets_productcomments'))
            unset($seller_pages['ratings']);
        $day_before_expired = (int)Configuration::get('ETS_MP_MESSAGE_EXPIRE_BEFORE_DAY');
        $date_expired = date('Y-m-d H:i:s',strtotime("+ $day_before_expired days"));
        $seller = $this->module->_getSeller();
        if($seller && $seller->date_to!='' && $seller->date_to!='0000-00-00 00:00:00' && strtotime($seller->date_to)< strtotime($date_expired))
        {
            $going_to_be_expired = true;
        }
        else
            $going_to_be_expired = false;
        if($seller_pages)
        {
            foreach($seller_pages as $key=> $page)
            {
                if(!$this->module->_checkPermissionPage($seller,$page['page']))
                    unset($seller_pages[$key]);
            }
        }
        $this->context->smarty->assign(
            array(
                'seller' =>$seller ,
                'seller_billing' => $seller->id_billing ? (new Ets_mp_billing($seller->id_billing)) : false,
                'going_to_be_expired' => $going_to_be_expired,
                'registration' => Ets_mp_registration::_getRegistration(),
                'seller_customer' => $this->context->customer,
                'path' => $this->module->getBreadCrumb(),
                'breadcrumb' => $this->module->is17 ? $this->module->getBreadCrumb() : false, 
                'is17' => $this->module->is17,
                'isManager' => $this->context->customer->id!= $seller->id_customer,
                'seller_pages' => $seller_pages,
                'require_registration' => (int)Configuration::get('ETS_MP_REQUIRE_REGISTRATION'),
                'ETS_MP_REGISTRATION_FIELDS' => explode(',',Configuration::get('ETS_MP_REGISTRATION_FIELDS')),
                'ETS_MP_REGISTRATION_FIELDS_VALIDATE' => explode(',',Configuration::get('ETS_MP_REGISTRATION_FIELDS_VALIDATE')),
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/myseller.tpl');
    }
}