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
class Ets_MarketPlaceRegistrationModuleFrontController extends ModuleFrontController
{
    public $_success = '';
    public $_errors = array();
    public $_warning = '';
    public function __construct()
	{
		parent::__construct();
        $this->display_column_right=false;
        $this->display_column_left =false;
	}
    public function postProcess()
    {
        parent::postProcess();
        if($this->module->_getSeller())
            Tools::redirect($this->context->link->getPageLink('my-account'));
        if(!$this->context->customer->logged || !Configuration::get('ETS_MP_ENABLED') || !Configuration::get('ETS_MP_REQUIRE_REGISTRATION'))
            Tools::redirect($this->context->link->getPageLink('my-account'));
        if(!$this->module->checkGroupCustomer())
            Tools::redirect($this->context->link->getPageLink('my-account'));
        if(Tools::isSubmit('submitDeclinceManageShop'))
        {
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'ets_mp_seller_manager` SET active=0 WHERE email="'.pSQL($this->context->customer->email).'"');
            $this->_warning = $this->module->l('You have declined the shop management invitation. How about registering for your own shop?','registration');
        }
        if(Tools::isSubmit('submitApproveManageShop'))
        {
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'ets_mp_seller_manager` SET active=1 WHERE email="'.pSQL($this->context->customer->email).'"');
        }
        if(($registration=Ets_mp_registration::_getRegistration()))
        {
            if($registration->active==-1)
            {
                $message= Configuration::get('ETS_MP_MESSAGE_SUBMITTED',$this->context->language->id)?:$this->module->l('Your application has been submitted successfully. Our team are reviewing the application, and we will get back to you as soon as possible','registration');
                $this->_success = $this->module->_replaceTag($message);
            }
            elseif($registration->active==1)
                Tools::redirect($this->context->link->getModuleLink($this->module->name,'create'));
            elseif($registration->active==0)
            {
                $message = Configuration::get('ETS_MP_MESSAGE_APPLICATION_DECLINED',$this->context->language->id)?:$this->module->l('Sorry! Your application is declined.
Reason: [application_declined_reason]
');
                $this->_warning = str_replace(array('[application_declined_reason]',"\n"),array($registration->reason,'<'.'b'.'r/'.'>'),$message);
            }
                
        }
        else
        {
           if(Configuration::get('ETS_MP_REGISTRATION_FIELDS'))
           {
               if(Tools::isSubmit('submitSeller'))
               {
                    $seller_fields = array(
                        'seller_name' => $this->module->l('Seller name','registration'),
                        'seller_email' => $this->module->l('Seller email','registration'),
                        'shop_name' => $this->module->l('Shop name','registration'),
                        'shop_description' => $this->module->l('Shop description','registration'),
                        'shop_address' => $this->module->l('Shop address','registration'),
                        'vat_number' => $this->module->l('VAT number','registration'),
                        'shop_phone' => $this->module->l('Phone number','registration'),
                        'shop_logo' => $this->module->l('Shop logo','registration'),
                        'shop_banner' => $this->module->l('Shop banner','registration'),
                        'banner_url' => $this->module->l('Banner URL','registration'),
                        'link_facebook' => $this->module->l('Facebook link','registration'),
                        'link_instagram' => $this->module->l('Instagram link','registration'),
                        'link_google' => $this->module->l('Google link','registration'),
                        'link_twitter' => $this->module->l('Twitter link','registration'),
                        'latitude' => $this->module->l('Latitude','registration'),
                        'longitude' => $this->module->l('Longitude','registration'),
                        'message_to_administrator' => $this->module->l('Introduction','registration'),
                    );
                    $fields = explode(',',Configuration::get('ETS_MP_REGISTRATION_FIELDS'));
                    $fields_validate = explode(',',Configuration::get('ETS_MP_REGISTRATION_FIELDS_VALIDATE'));
                    if($fields)
                    {
                        foreach($fields as $field)
                        {
                            if($field!='0')
                            {
                                if(in_array($field,$fields_validate))
                                {
                                    if(($field!='shop_logo' && $field!='shop_banner' && !Tools::getValue($field)) || (($field=='shop_logo' || $field=='shop_banner') && !$_FILES[$field]['name']))
                                        $this->_errors[] = sprintf($this->module->l('%s is required','registration'),$seller_fields[$field]);
                                }
                                if(in_array($field,array('link_facebook','link_google','link_instagram','link_twitter','banner_url')))
                                {
                                    if(Tools::getValue($field) && !Ets_marketplace::isLink(Tools::getValue($field)))
                                        $this->_errors[] = sprintf($this->module->l('%s is not valid','registration'),$seller_fields[$field]);
                                }
                                elseif(in_array($field,array('latitude','longitude')))
                                {
                                    if(Tools::getValue($field) && !Validate::isCoordinate(Tools::getValue($field)))
                                        $this->_errors[] =  sprintf($this->module->l('%s is not valid','registration'),$seller_fields[$field]);
                                }
                                elseif($field!='shop_logo' && $field!='shop_banner' && $field!='shop_phone' && $field!='shop_name' && Tools::getValue($field) && !Validate::isCleanHtml(Tools::getValue($field)))
                                    $this->_errors[] = sprintf($this->module->l('%s is not valid','registration'),$seller_fields[$field]);
                                elseif($field=='seller_email' && Tools::getValue($field) && (Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'ets_mp_registration` WHERE seller_email="'.pSQL(Tools::getValue($field)).'"') || Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'ets_mp_seller` WHERE seller_email="'.pSQL(Tools::getValue($field)).'"')))
                                {
                                    $this->_errors[] = $this->module->l('The email is already used, please choose another one','registration');
                                }
                                elseif($field=='shop_phone' && Tools::getValue($field) && !Validate::isPhoneNumber(Tools::getValue($field)))
                                    $this->_errors[] = $this->module->l('Phone number is not valid','registration');
                                elseif($field=='shop_name' && Tools::getValue($field) && !Validate::isGenericName(Tools::getValue($field)))
                                    $this->_errors[] = $this->module->l('Shop name is not valid','registration');
                                elseif($field=='vat_number' && Tools::getValue($field) && !Validate::isGenericName(Tools::getValue($field)))
                                    $this->_errors[] = $this->module->l('VAT number is not valid','registration');
                            }
                            
                        }
                    }
                    if(!$this->_errors)
                    {
                        $seller = new Ets_mp_registration();
                        $seller->id_customer = $this->context->customer->id;
                        $seller->id_shop = $this->context->shop->id;
                        $seller->date_add = date('Y-m-d H:i:s');
                        $seller->date_upd = date('Y-m-d H:i:s');
                        $seller->active=-1;
                        foreach($fields as $field)
                        {
                            if($field!='shop_logo' && $field!='shop_banner')
                                $seller->{$field} = Tools::getValue($field);
                            else
                                $seller->{$field} = $this->module->uploadFile($field,$this->_errors);
                            
                        }
                        if(!$this->_errors)
                        {
                            if($seller->add())
                            {
                                $message = Configuration::get('ETS_MP_MESSAGE_SUBMITTED',$this->context->language->id)?:$this->module->l('Your application has been submitted successfully. Our team are reviewing the application, and we will get back to you as soon as possible','registration');
                                $this->_success = $this->module->_replaceTag($message);
                                if(Configuration::get('ETS_MP_EMAIL_ADMIN_APPLICATION_REQUEST'))
                                {
                                    $this->context->smarty->assign(
                                        array(
                                            'seller_fields' => $seller_fields,
                                            'submit_fields' => $fields,
                                            'seller_email' => $this->context->customer->email,
                                            'submit_values' => Tools::getAllValues(),
                                        )
                                    );
                                    $datas = array(
                                        '{seller_name}' => $this->context->customer->firstname.' '.$this->context->customer->lastname,
                                        '{seller_application_content}' => $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/seller_application_content.tpl'),
                                        '{seller_application_content_txt}' => $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/seller_application_content_txt.tpl'),
                                    );
                                    $subjects = array(
                                        'translation' => $this->module->l('New application','registration'),
                                        'origin'=>'New application',
                                        'specific'=>'registration'
                                    );
                                    Ets_marketplace::sendMail('to_admin_new_seller_application',$datas,'',$subjects);
                                    
                                }
                            }
                            else
                            {
                                $this->_errors[] = $this->module->l('Registration failed','registration');
                                if($seller->shop_logo && file_exists(_PS_IMG_DIR_.'mp_seller/'.$seller->shop_logo))
                                    @unlink(_PS_IMG_DIR_.'mp_seller/'.$seller->shop_logo);
                                if($seller->shop_banner && file_exists(_PS_IMG_DIR_.'mp_seller/'.$seller->shop_banner))
                                    @unlink(_PS_IMG_DIR_.'mp_seller/'.$seller->shop_banner);
                            }
                        }
                        else
                        {
                            if($seller->shop_logo && file_exists(_PS_IMG_DIR_.'mp_seller/'.$seller->shop_logo))
                                @unlink(_PS_IMG_DIR_.'mp_seller/'.$seller->shop_logo);
                            if($seller->shop_banner && file_exists(_PS_IMG_DIR_.'mp_seller/'.$seller->shop_banner))
                                @unlink(_PS_IMG_DIR_.'mp_seller/'.$seller->shop_banner);
                            
                        }
                        
                    }
               } 
            }
            elseif(!Db::getInstance()->getValue('SELECT id_customer FROM `'._DB_PREFIX_.'ets_mp_seller_manager` WHERE email="'.pSQL($this->context->customer->email).'" AND active !=0'))
            {
                $seller = new Ets_mp_registration();
                $seller->id_customer = $this->context->customer->id;
                $seller->id_shop = $this->context->shop->id;
                $seller->date_add = date('Y-m-d H:i:s');
                $seller->date_upd = date('Y-m-d H:i:s');
                $seller->active=-1;
                if($seller->add())
                {
                    $message= Configuration::get('ETS_MP_MESSAGE_SUBMITTED',$this->context->language->id)?:$this->module->l('Your application has been submitted successfully. Our team are reviewing the application, and we will get back to you as soon as possible','registration');
                    $this->_success = $this->module->_replaceTag($message);
                    if(Configuration::get('ETS_MP_EMAIL_ADMIN_APPLICATION_REQUEST'))
                    {
                        $datas = array(
                            '{seller_name}' => $this->context->customer->firstname.' '.$this->context->customer->lastname,
                            '{seller_email}' => $this->context->customer->email,
                            '{shop_description}' => Tools::getValue('shop_description'),
                            '{shop_address}' => Tools::getValue('shop_address'),
                            '{shop_phone}' => Tools::getValue('shop_phone'),
                            '{message}' => Tools::getValue('message_to_administrator'),
                        );
                        $subjects = array(
                            'translation' => $this->module->l('New application','registration'),
                            'origin'=> 'New application',
                            'specific'=>'registration'
                        );
                        Ets_marketplace::sendMail('to_admin_new_seller_application',$datas,'',$subjects);
                        
                    }
                }
                else
                    $this->_errors[] = $this->module->l('Registration failed','registration');
            } 
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
            $this->setTemplate('module:'.$this->module->name.'/views/templates/front/registration.tpl');      
        else        
            $this->setTemplate('registration_16.tpl'); 
    }
    public function _initContent()
    {
        if($id_group = (int)Configuration::get('ETS_MP_SELLER_GROUP_DEFAULT'))
        {
            $group = new Ets_mp_seller_group($id_group);
            if(!$group->use_fee_global && $group->fee_type)
            {
                $fee_type = $group->fee_type;
                $fee_amount = $group->fee_amount;
            }
            else
            {
                $fee_type = Configuration::get('ETS_MP_SELLER_FEE_TYPE');
                $fee_amount = Configuration::get('ETS_MP_SELLER_FEE_AMOUNT');
            }
        }
        else
        {
            $fee_type = Configuration::get('ETS_MP_SELLER_FEE_TYPE');
            $fee_amount = Configuration::get('ETS_MP_SELLER_FEE_AMOUNT');
        }
        if($fee_type=='no_fee')
            $fee_amount = 0;
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
                'register_customer' => $this->context->customer,
                'ETS_MP_REGISTRATION_FIELDS' => explode(',',Configuration::get('ETS_MP_REGISTRATION_FIELDS')),
                'ETS_MP_REGISTRATION_FIELDS_VALIDATE' => explode(',',Configuration::get('ETS_MP_REGISTRATION_FIELDS_VALIDATE')),
                'ETS_MP_MESSAGE_INVITE' =>Tools::nl2br(str_replace('[fee_amount]',Tools::displayPrice($this->module->getFeeIncludeTax($fee_amount),new Currency(Configuration::get('PS_CURRENCY_DEFAULT'))).' ('.$this->module->l('Tax incl','registration').')',Configuration::get('ETS_MP_MESSAGE_INVITE',$this->context->language->id))),
                'seller' => Ets_mp_registration::_getRegistration(),
                'manager_shop' => isset($manager_shop) ? $manager_shop : false,
                'number_phone' => Db::getInstance()->getValue('SELECT ifnull(phone,phone_mobile) FROM `'._DB_PREFIX_.'address` WHERE id_customer='.(int)$this->context->customer->id.' AND (phone!="" OR phone_mobile!="") ORDER BY id_address DESC'),
                'vat_number' => Db::getInstance()->getValue('SELECT vat_number FROM `'._DB_PREFIX_.'address` WHERE id_customer='.(int)$this->context->customer->id.' AND vat_number!="" ORDER BY id_address DESC'),
            )
        );
        return ($this->_warning ? $this->module->displayWarning($this->_warning):'').($this->_errors ? $this->module->displayError($this->_errors):'').($this->_success ? $this->module->displayConfirmation($this->_success):'').$this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/registration.tpl');
    }
}