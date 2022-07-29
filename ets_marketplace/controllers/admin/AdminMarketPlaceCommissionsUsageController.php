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
class AdminMarketPlaceCommissionsUsageController extends ModuleAdminController
{
    public function __construct()
    {
       parent::__construct();
       $this->context= Context::getContext();
       $this->bootstrap = true;
       
    }
    public function initContent()
    {
        parent::initContent();
        if($this->ajax)
        {
            $this->renderList();
        }
    }
    public function renderList()
    {
        $this->module->getContent();
        if(Tools::isSubmit('saveCommissionUsageSettings'))
        {
            if($this->_checkFormBeforeSubmit())
            {
                $this->_saveFromSettings();
            }
        }
        $this->context->smarty->assign(
            array(
                'ets_mp_body_html'=> Tools::getValue('tabActive','commission_usage')=='commission_usage' ? $this->_renderCommissionUsageSettings() : Ets_mp_paymentmethod::getInstance()->_renderPayments(),
                'tabActive' => Tools::getValue('tabActive','commission_usage')
            )
        );
        $html ='';
        if($this->context->cookie->success_message)
        {
            $html .= $this->module->displayConfirmation($this->context->cookie->success_message);
            $this->context->cookie->success_message ='';
        }
        if($this->module->_errors)
            $html .= $this->module->displayError($this->module->_errors);
        return $html.$this->module->display(_PS_MODULE_DIR_.$this->module->name.DIRECTORY_SEPARATOR.$this->module->name.'.php', 'admin.tpl');
    }
    public function _checkFormBeforeSubmit()
    {
        $languages = Language::getLanguages(false);
        $configs = Ets_mp_defines::getInstance()->getFieldConfig('commission_usage_settings');
        if($configs)
        {
            foreach($configs as $config)
            {
                $name = $config['name'];
                if(isset($config['required']) && $config['required'] && !Tools::getValue($name))
                    $this->module->_errors[] = $config['label'].' '. $this->l('is required');
                if(isset($config['lang']) && $config['lang'])
                { 
                    if((isset($config['validate']) && $config['validate'] && method_exists('Validate',$config['validate'])))
                    {
                        $validate = $config['validate'];
                        foreach($languages as $lang)
                        {
                            if(trim(Tools::getValue($name.'_'.$lang['id_lang'])) && !Validate::$validate(trim(Tools::getValue($name.'_'.$lang['id_lang']))))
                                $this->module->_errors[] =  $config['label'].' '.$this->l('is not valid in ').$lang['iso_code'];
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
                             $this->module->_errors[] = $config['label'].' '. $this->l('is not valid');
                        unset($validate);
                    } 
                }
                    
            }
        }
        if(!$this->module->_errors)
            return true;
    }
    public function _saveFromSettings()
    {
        $languages = Language::getLanguages(false);
        $id_language_default = Configuration::get('PS_LANG_DEFAULT');
        $configs = Ets_mp_defines::getInstance()->getFieldConfig('commission_usage_settings');
        if($configs)
        {
            foreach($configs as $config)
            {
                Configuration::deleteByName($config['name']);
                if($config['type']=='checkbox' || $config['type']=='categories'|| $config['type']=='tre_categories'|| $config['type']=='list_product')
                {
                    Configuration::updateValue($config['name'],Tools::getValue($config['name']) ? implode(',',Tools::getValue($config['name'])) :'' );
                }
                elseif($config['type']!='custom_html')
                {
                    if(isset($config['lang']) && $config['lang'])
                    {
                        $values = array();
                        foreach($languages as $language)
                        {
                            $values[$language['id_lang']] = Tools::getValue($config['name'].'_'.$language['id_lang']) ? Tools::getValue($config['name'].'_'.$language['id_lang']) :Tools::getValue($config['name'].'_'.$id_language_default);
                        }
                        Configuration::updateValue($config['name'],$values,true);
                    }
                    else
                        Configuration::updateValue($config['name'],Tools::getValue($config['name']),true);
                }
                
            }
        }
        $this->context->cookie->success_message = $this->l('Updated successfully');
    }
    public function _renderCommissionUsageSettings()
    {
        $languages = Language::getLanguages(false);
        $fields_form = array(
    		'form' => array(
    			'legend' => array(
    				'title' => $this->l('Commission'),
    				'icon' => 'icon-settings'
    			),
    			'input' => array(),
                'submit' => array(
    				'title' => $this->l('Save'),
    			)
            ),
    	);
        $configs = Ets_mp_defines::getInstance()->getFieldConfig('commission_usage_settings');
        $fields = array();
        foreach($configs as $config)
        {
            $fields_form['form']['input'][] = $config;
            //$fields[$config['name']] = Tools::getValue($config['name'],Configuration::get($config['name']));
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
            elseif($config['type']!='custom_html')
                $fields[$config['name']] = Tools::isSubmit('AdminMarketPlaceCommissionsUsage') ?  Tools::getValue($config['name']) : (Configuration::get($config['name']) ? explode(',',Configuration::get($config['name'])):array());
        }
        $helper = new HelperForm();
    	$helper->show_toolbar = false;
    	$helper->table = 'commission_usage';
    	$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
    	$helper->default_form_language = $lang->id;
    	$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
    	$this->fields_form = array();
    	$helper->module = $this->module;
    	$helper->identifier = $this->identifier;
    	$helper->submit_action = 'saveCommissionUsageSettings';
    	$helper->currentIndex = $this->context->link->getAdminLink('AdminMarketPlaceCommissionsUsage', false);
    	$helper->token = Tools::getAdminTokenLite('AdminMarketPlaceCommissionsUsage');
    	$language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));            
        $helper->tpl_vars = array(
    		'base_url' => $this->context->shop->getBaseURL(),
    		'language' => array(
    			'id_lang' => $language->id,
    			'iso_code' => $language->iso_code
    		),
    		'fields_value' => $fields,
    		'languages' => $this->context->controller->getLanguages(),
    		'id_language' => $this->context->language->id,
            'isConfigForm' => true,
        );
        return $helper->generateForm(array($fields_form));
    }
}