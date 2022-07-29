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
class Ets_MarketPlaceCreateModuleFrontController extends ModuleFrontController
{
    public $_success;
    public $_errors = array();
    public $_warning;
    public function __construct()
	{
		parent::__construct();
        $this->display_column_right=false;
        $this->display_column_left =false;
	}
    public function postProcess()
    {
        parent::postProcess();
        if(Tools::isSubmit('i_have_just_sent_the_fee') && ($seller= $this->module->_getSeller()))
        {
            $seller->confirmedPayment();
        }
        if(!$this->context->customer->logged || (!Ets_mp_registration::_getRegistration() && Configuration::get('ETS_MP_REQUIRE_REGISTRATION')))
            Tools::redirect($this->context->link->getPageLink('my-account'));
        if($this->module->_getSeller())
            Tools::redirect($this->context->link->getModuleLink($this->module->name,'myseller'));
        if(Tools::isSubmit('submitDeclinceManageShop'))
        {
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'ets_mp_seller_manager` SET active=0 WHERE email="'.pSQL($this->context->customer->email).'"');
            $this->_warning = $this->module->l('You have declined a shop management invitation. How about registering for your own shop?','registration');
        }
        if(Tools::isSubmit('submitApproveManageShop'))
        {
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'ets_mp_seller_manager` SET active=1 WHERE email="'.pSQL($this->context->customer->email).'"');
        }
        if(Tools::isSubmit('submitSaveSeller'))
        {
            $valueFieldPost = array();
            Ets_mp_seller::getInstance()->submitSaveSeller(0,$this->_errors,false,$valueFieldPost);
            if($this->context->cookie->success_message)
            {
                $this->_success = $this->context->cookie->success_message;
                $this->context->cookie->success_message='';
            }
            $this->context->smarty->assign(
                array(
                    'valueFieldPost' => $valueFieldPost,
                )
            );
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
            $this->setTemplate('module:'.$this->module->name.'/views/templates/front/create.tpl');      
        else        
            $this->setTemplate('create_16.tpl'); 
    }
    public function _initContent()
    {
        if($id_seller_customer = Db::getInstance()->getValue('SELECT id_customer FROM `'._DB_PREFIX_.'ets_mp_seller_manager` WHERE email="'.pSQL($this->context->customer->email).'" AND active !=0'))
        {
            $manager_shop = Db::getInstance()->getRow('SELECT c.firstname,c.lastname,sl.shop_name FROM `'._DB_PREFIX_.'customer` c
            INNER JOIN `'._DB_PREFIX_.'ets_mp_seller` s ON(c.id_customer= s.id_customer)
            LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_lang` sl ON (sl.id_seller = s.id_seller AND sl.id_lang="'.(int)$this->context->language->id.'")
            WHERE s.id_customer="'.(int)$id_seller_customer.'"');
            if($manager_shop)
                $manager_shop['active'] = Db::getInstance()->getValue('SELECT active FROM `'._DB_PREFIX_.'ets_mp_seller_manager` WHERE email="'.pSQL($this->context->customer->email).'"');
        }
        $this->context->smarty->assign(
            array(
                'seller' => Ets_mp_registration::_getRegistration(),
                '_errors' => $this->_errors ? $this->module->displayError($this->_errors):'',
                '_success' => $this->_success ? $this->module->displayConfirmation($this->_success):'',
                'shop_seller' => $this->module->_getSeller(),
                'create_customer' => $this->context->customer,
                'path' => $this->module->getBreadCrumb(),
                'link_base' => $this->module->getBaseLink(),
                'shop_name' => $this->context->shop->name,
                'languages' => Language::getLanguages(true),
                'id_lang_default' => Configuration::get('PS_LANG_DEFAULT'),
                'manager_shop' => isset($manager_shop) ? $manager_shop :false,
                'breadcrumb' => $this->module->is17 ? $this->module->getBreadCrumb() : false, 
                'number_phone' => Db::getInstance()->getValue('SELECT ifnull(phone,phone_mobile) FROM `'._DB_PREFIX_.'address` WHERE id_customer='.(int)$this->context->customer->id.' AND (phone!="" OR phone_mobile!="") ORDER BY id_address DESC'),
                'vat_number' => Db::getInstance()->getValue('SELECT vat_number FROM `'._DB_PREFIX_.'address` WHERE id_customer='.(int)$this->context->customer->id.' AND vat_number!="" ORDER BY id_address DESC'),
            )
        );
        return ($this->_warning ? $this->module->displayWarning($this->_warning):'').$this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/create.tpl');
    }
}