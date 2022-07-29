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
class Ets_MarketPlaceProfileModuleFrontController extends ModuleFrontController
{
    public $_success;
    public $_errors = array();
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
        if(!$this->context->customer->logged || !($this->seller = $this->module->_getSeller()))
            Tools::redirect($this->context->link->getPageLink('my-account'));
        if(!$this->module->_checkPermissionPage($this->seller))
            die($this->module->l('You do not have permission','profile'));
        if(Tools::isSubmit('deletebanner') && $id_lang= Tools::getValue('banner_lang'))
        {
            $shop_banner_old = $this->seller->shop_banner[$id_lang];
            $this->seller->shop_banner[$id_lang] = '';
            if($this->seller->update())
            {
                if($shop_banner_old && !in_array($shop_banner_old,$this->seller->shop_banner))
                        @unlink(_PS_IMG_DIR_.'mp_seller/'.$shop_banner_old);
                $this->context->cookie->success_message = $this->module->l('Deleted shop banner successfully','profile');
            }
            else
                $this->_errors[] = $this->module->l('Deleted shop banner failed','profile');
        }
        $languages = Language::getLanguages(false);
        $valueFieldPost= array();
        foreach($languages as $language)
        {
            $valueFieldPost['shop_name'][$language['id_lang']] = Tools::getValue('shop_name_'.$language['id_lang'],(isset($this->seller->shop_name[$language['id_lang']]) ? $this->seller->shop_name[$language['id_lang']]:''));
            $valueFieldPost['shop_description'][$language['id_lang']] = Tools::getValue('shop_description_'.$language['id_lang'],isset($this->seller->shop_description[$language['id_lang']]) ? $this->seller->shop_description[$language['id_lang']]:'');
            $valueFieldPost['shop_address'][$language['id_lang']] = Tools::getValue('shop_address_'.$language['id_lang'],isset($this->seller->shop_address[$language['id_lang']])?$this->seller->shop_address[$language['id_lang']]:'');
            $valueFieldPost['vacation_notifications'][$language['id_lang']] = Tools::getValue('vacation_notifications_'.$language['id_lang'],isset($this->seller->vacation_notifications[$language['id_lang']])?$this->seller->vacation_notifications[$language['id_lang']]:'');
            $valueFieldPost['banner_url'][$language['id_lang']] = Tools::getValue('banner_url_'.$language['id_lang'],isset($this->seller->banner_url[$language['id_lang']]) ? $this->seller->banner_url[$language['id_lang']]:''); 
        }
        if(Tools::isSubmit('submitSaveSeller'))
        {
            Ets_mp_seller::getInstance()->submitSaveSeller($this->seller->id,$this->_errors,false,$valueFieldPost);
        }
        $this->context->smarty->assign(
            array(
                'valueFieldPost' => $valueFieldPost,
            )
        );
        if($this->context->cookie->success_message)
        {
            $this->_success = $this->context->cookie->success_message;
            $this->context->cookie->success_message='';
        }    
    }
    public function initContent()
	{
		parent::initContent();
        $this->context->smarty->assign(
            array(
                'path' => $this->module->getBreadCrumb(),
                'breadcrumb' => $this->module->is17 ? $this->module->getBreadCrumb() : false,
                'html_content' => $this->_initContent(),
            )
        );
        if($this->module->is17)
            $this->setTemplate('module:'.$this->module->name.'/views/templates/front/profile.tpl');      
        else        
            $this->setTemplate('profile_16.tpl'); 
    }
    public function _initContent()
    {
        $this->context->smarty->assign(
            array(
                '_errors' => $this->_errors ? $this->module->displayError($this->_errors):'',
                '_success' => $this->_success ? $this->module->displayConfirmation($this->_success):'',
                'seller' => $this->seller,
                'profile_customer' => new Customer($this->seller->id_customer),
                'link_base' => $this->module->getBaseLink(),
                'shop_name' => $this->context->shop->name,
                'languages' => Language::getLanguages(true),
                'number_phone' => Db::getInstance()->getValue('SELECT ifnull(phone,phone_mobile) FROM `'._DB_PREFIX_.'address` WHERE id_customer='.(int)$this->seller->id_customer.' AND (phone!="" OR phone_mobile!="") ORDER BY id_address DESC'),
                'vat_number' => Db::getInstance()->getValue('SELECT vat_number FROM `'._DB_PREFIX_.'address` WHERE id_customer='.(int)$this->seller->id_customer.' AND vat_number!="" ORDER BY id_address DESC'),
                'id_lang_default' => Configuration::get('PS_LANG_DEFAULT'),
                'ETS_MP_VACATION_MODE_FOR_SELLER' => Configuration::get('ETS_MP_VACATION_MODE_FOR_SELLER'),
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/shop/profile.tpl');
    }
}