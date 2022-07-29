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

if (!defined('_PS_VERSION_')) {
    exit();
}

class Ets_mp_paymentmethod extends ObjectModel
{
    protected static $instance;
    /**
     * @var int
     */
    public $id_shop;
    /**
     * @var string
     */
    public $fee_type;
    /**
     * @var int
     */
    public $fee_fixed;
    /**
     * @var int
     */
    public $fee_percent;
    /**
     * @var int
     */
    public $enable;
    public $deleted;
    public $sort;
    public $estimated_processing_time;
    public $logo;
    public $title;
    public $description;
    public $note;
    public static $definition = array(
        'table' => 'ets_mp_payment_method',
        'primary' => 'id_ets_mp_payment_method',
        'multilang' => true,
        'fields' => array(
            'id_shop' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt'
            ),
            'title' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isString'
            ),
            'description' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isString'
            ),
            'note' => array(
                'type' => self::TYPE_STRING,
                'lang' => true,
                'validate' => 'isString'
            ),
            'fee_type' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isString'
            ),
            'fee_fixed' => array(
                'type' => self::TYPE_FLOAT,
                'validate' => 'isFloat'
            ),
            'fee_percent' => array(
                'type' => self::TYPE_FLOAT,
                'validate' => 'isFloat'
            ),
            'estimated_processing_time' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isUnsignedInt'
            ),
            'logo' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isString'
            ),
            'enable' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt'
            ),
            'sort' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt'
            ),
            'deleted' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt'
            ),
        )
    );
    public	function __construct($id_item = null, $id_lang = null, $id_shop = null)
	{
		parent::__construct($id_item, $id_lang, $id_shop);
        $this->context= Context::getContext();
	}
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Ets_mp_paymentmethod();
        }
        return self::$instance;
    }
    public function getListFields()
    {
        $sql ='SELECT * FROM `'._DB_PREFIX_.'ets_mp_payment_method_field` pmf
        LEFT JOIN `'._DB_PREFIX_.'ets_mp_payment_method_field_lang` pmfl ON (pmf.id_ets_mp_payment_method_field=pmfl.id_ets_mp_payment_method_field AND pmfl.id_lang="'.(int)$this->context->language->id.'")
        WHERE pmf.id_ets_mp_payment_method="'.(int)$this->id.'" AND pmf.deleted=0 AND pmf.enable=1 ORDER BY pmf.sort ASC';
        return Db::getInstance()->executeS($sql);
    }
    public static function getPaymentMethod($id){
        $sql = "SELECT * FROM `"._DB_PREFIX_."ets_mp_payment_method` WHERE id_ets_mp_payment_method = $id";
        $payment_method = Db::getInstance()->getRow($sql);
        $payment_method['fee_percent'] = (float)$payment_method['fee_percent'];
        $payment_method['fee_fixed'] = (float)$payment_method['fee_fixed'];
        if($payment_method){
            $sqlLang = "SELECT * FROM `"._DB_PREFIX_."ets_mp_payment_method_lang` WHERE id_ets_mp_payment_method = $id";
            $payment_method_langs = Db::getInstance()->executeS($sqlLang);
            $payment_method['langs'] = array();
            foreach ($payment_method_langs as $pml) {
                $payment_method['langs'][$pml['id_lang']] = array(
                    'id' => $pml['id_ets_mp_payment_method'],
                    'title' => $pml['title'],
                    'description' => $pml['description'],
                    'note' => $pml['note'],
                    'id_lang' => $pml['id_lang'],
                );
            } 
        }
        return $payment_method;
    }
    public static function getListPaymentMethodField($id_pm, $id_lang = null){
        $languages= Language::getLanguages(false);
        if(Tools::isSubmit('submit_payment_method'))
        {
            $results = array();
            if ($pmf = Tools::getValue('payment_method_field', array())) {
                foreach($pmf as $item)
                {
                    $result = array();
                    if(isset($item['id']) && $item['id'])
                        $result['id'] = $item['id'];
                    else
                    {
                        $result['id'] = 0;
                    }    
                    $result['type'] = $item['type'];
                    $result['required'] = $item['required'];
                    $result['enable'] = $item['enable'];
                    foreach($languages as $language)
                    {
                        $result['title'][$language['id_lang']] = trim($item['title'][$language['id_lang']]) ;
                        $result['description'][$language['id_lang']] = trim($item['description'][$language['id_lang']]); 
                    }
                    $results[] = $result;
                }
            }
            return $results;
        }
        elseif($id_pm)
        {
            $filter_where = '';
            if($id_lang){
                $filter_where .= "AND pmfl.id_lang = $id_lang";
            }
            $sql = "SELECT pmf.*, pmfl.title, pmfl.description, pmfl.id_lang FROM (
                SELECT * FROM `"._DB_PREFIX_."ets_mp_payment_method_field` WHERE id_ets_mp_payment_method = $id_pm AND `deleted` = 0
            ) pmf
            JOIN `"._DB_PREFIX_."ets_mp_payment_method_field_lang` pmfl ON pmf.id_ets_mp_payment_method_field = pmfl.id_ets_mp_payment_method_field
            WHERE 1 $filter_where
            ORDER BY pmf.sort ASC";
            $payment_method_fields = Db::getInstance()->executeS($sql);
            if(!$id_lang && $payment_method_fields){
                $results = array();
                foreach ($payment_method_fields as $field) {
                    $results[$field['id_ets_mp_payment_method_field']]['id'] = $field['id_ets_mp_payment_method_field'];
                    $results[$field['id_ets_mp_payment_method_field']]['type'] = $field['type'];
                    $results[$field['id_ets_mp_payment_method_field']]['enable'] = $field['enable'];
                    $results[$field['id_ets_mp_payment_method_field']]['description'][$field['id_lang']] = $field['description'];
                    $results[$field['id_ets_mp_payment_method_field']]['required'] = $field['required'];
                    $results[$field['id_ets_mp_payment_method_field']]['title'][$field['id_lang']] = $field['title'];
                }
                return $results;
            }
            return $payment_method_fields;
        }
    }
    public static function getListPaymentMethods()
    {
        $context = Context::getContext();
        $id_shop = (int)$context->shop->id;
        $sql = 'SELECT * FROM `'._DB_PREFIX_.'ets_mp_payment_method` pm
        LEFT JOIN `'._DB_PREFIX_.'ets_mp_payment_method_lang` pml ON (pm.id_ets_mp_payment_method =pml.id_ets_mp_payment_method AND pml.id_lang="'.(int)$context->language->id.'")
        WHERE pm.id_shop="'.(int)$id_shop.'" AND pm.deleted=0 ORDER BY pm.sort ASC';
        $results = Db::getInstance()->executeS($sql);
        return $results;
    }
    public function _renderPayments(){ 
        $context = Context::getContext();
        $languages = Language::getLanguages(false);
        $id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
        $currency = Currency::getDefaultCurrency();
        $module = Module::getInstanceByName('ets_marketplace');
        $link_pm = $context->link->getAdminLink('AdminMarketPlaceCommissionsUsage').'&tabActive=payment_settings';
        $errors = array();
        if(Tools::isSubmit('delete_logo') && $id_payment_method = (int)Tools::getValue('payment_method'))
        {
            $paymentMethod = new Ets_mp_paymentmethod($id_payment_method);
            if(file_exists(_PS_IMG_DIR_.'mp_payment/'.$paymentMethod->logo))
                @unlink(_PS_IMG_DIR_.'mp_payment/'.$paymentMethod->logo);
            $paymentMethod->logo ='';
            $paymentMethod->update();
            Tools::redirectAdmin($link_pm . '&payment_method=' . $paymentMethod->id . '&edit_pm=1&conf=7');
        }
        if(Tools::getValue('action')=='updatePaymentMethodOrdering' && $paymentMethods= Tools::getValue('paymentmethod'))
        {
            foreach($paymentMethods as $position=>$id_ets_mp_payment_method)
            {
                $sort= $position+1;
                Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'ets_mp_payment_method` SET sort="'.(int)$sort.'" WHERE id_ets_mp_payment_method='.(int)$id_ets_mp_payment_method);
            }
            die(
                Tools::jsonEncode(
                    array(
                        'success' => $this->l('Updated successfully','Ets_mp_paymentmethod')
                    )
                )
            );
        }
        if(Tools::getValue('action')=='updatePaymentMethodFieldOrdering' && $paymentmethodfields= Tools::getValue('paymentmethodfield'))
        {
            foreach($paymentmethodfields as $position=>$id_ets_mp_payment_method_field)
            {
                $sort= $position+1;
                Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'ets_mp_payment_method_field` SET sort="'.(int)$sort.'" WHERE id_ets_mp_payment_method_field='.(int)$id_ets_mp_payment_method_field);
            }
            die(
                Tools::jsonEncode(
                    array(
                        'success' => $this->l('Updated successfully','Ets_mp_paymentmethod')
                    )
                )
            );
        }
        $valuefields = array();
        if($id_ets_mp_payment_method= (int)Tools::getValue('payment_method'))
            $paymentMethod = new Ets_mp_paymentmethod($id_ets_mp_payment_method);
        else
        {
            $paymentMethod = new Ets_mp_paymentmethod();
            $paymentMethod->id_shop= $context->shop->id;
            $paymentMethod->sort = 1+ Db::getInstance()->getValue('SELECT count(*) FROM `'._DB_PREFIX_.'ets_mp_payment_method` WHERE id_shop="'.(int)$context->shop->id.'" AND deleted=0');
        }
        foreach($languages as $language)
        {
            $valuefields['title'][$language['id_lang']] = Tools::getValue('payment_method_name_'.$language['id_lang'],$paymentMethod->title[$language['id_lang']]);
            $valuefields['description'][$language['id_lang']] = Tools::getValue('payment_method_desc_'.$language['id_lang'],$paymentMethod->description[$language['id_lang']]);
            $valuefields['note'][$language['id_lang']] = Tools::getValue('payment_method_note_'.$language['id_lang'],$paymentMethod->note[$language['id_lang']]);
        }
        $valuefields['fee_type'] = Tools::getValue('payment_method_fee_type',$paymentMethod->fee_type);
        $valuefields['fee_fixed'] = Tools::getValue('payment_method_fee_fixed',$paymentMethod->fee_fixed);
        $valuefields['fee_percent'] = Tools::getValue('payment_method_fee_percent',$paymentMethod->fee_percent);
        $valuefields['estimated_processing_time'] = Tools::getValue('payment_method_estimated',$paymentMethod->estimated_processing_time);
        $valuefields['enable'] = Tools::getValue('payment_method_enabled',$paymentMethod->enable);
        $valuefields['payment_method'] = Tools::getValue('payment_method');
        $valuefields['logo'] = $paymentMethod->logo;
        $pmf = self::getListPaymentMethodField($id_ets_mp_payment_method);
        $context->smarty->assign(
            array(
                'valuefields' => $valuefields,
                'payment_method_fields' => $pmf,
                'link_base' => $module->getBaseLink()
            )
        );
        if (Tools::isSubmit('submit_payment_method', false)) {
            if(!Tools::getValue('payment_method_name_'.$id_lang_default))
                $errors[] = $this->l('Title of withdrawal method is required.','Ets_mp_paymentmethod');
            foreach($languages as $language)
            {
                if(Tools::getValue('payment_method_name_'.$language['id_lang']) && !Validate::isCleanHtml(Tools::getValue('payment_method_name_'.$language['id_lang'])))
                    $errors[] = $this->l('Title of payment method is not valid in','Ets_mp_paymentmethod').' '.$language['iso_code'];
                if(Tools::getValue('payment_method_desc_'.$language['id_lang']) && !Validate::isCleanHtml(Tools::getValue('payment_method_desc_'.$language['id_lang'])))
                    $errors[] = $this->l('Description of payment method is not valid in','Ets_mp_paymentmethod').' '.$language['iso_code'];
                if(Tools::getValue('payment_method_note_'.$language['id_lang']) && !Validate::isCleanHtml(Tools::getValue('payment_method_note_'.$language['id_lang'])))
                    $errors[] = $this->l('Description of payment method is not valid in','Ets_mp_paymentmethod').' '.$language['iso_code'];
            }
            if (($pm_fee_type = Tools::getValue('payment_method_fee_type')) != 'NO_FEE')
            {
                if ($pm_fee_type == 'FIXED') {
                    if (!($pm_fee_fixed = Tools::getValue('payment_method_fee_fixed'))) {
                        $errors[] = $this->l('Fee (fixed amount) is required','Ets_mp_paymentmethod');
                    } elseif (!Validate::isFloat($pm_fee_fixed)) {
                        $errors[] = $this->l('Fee (fixed amount) must be a decimal number.','Ets_mp_paymentmethod');
                    }
                } elseif ($pm_fee_type == 'PERCENT') {
                    if (!($pm_fee_percent = Tools::getValue('payment_method_fee_percent'))) {
                        $errors[] = $this->l('Fee (percentage) is required','Ets_mp_paymentmethod');
                    } elseif (!Validate::isFloat($pm_fee_percent)) {
                        $errors[] = $this->l('Fee (percentage) must be a decimal number.','Ets_mp_paymentmethod');
                    }
                }
            }
            if ($pm_estimated = Tools::getValue('payment_method_estimated', false)) {
                if (!Validate::isUnsignedInt($pm_estimated)) {
                    $errors[] = $this->l('Estimated processing time must be an integer','Ets_mp_paymentmethod');
                }
            }
            if ($pmf = Tools::getValue('payment_method_field', array())) {
                foreach ($pmf as $item) {
                    if (isset($item['title']) && is_array($item['title']) && $item['title']) {
                        if(!isset($item['title'][$id_lang_default]) || !$item['title'][$id_lang_default])
                            $errors[] = $this->l('Title of withdrawal method field is required');
                        foreach ($item['title'] as $title) {
                            if($title){
                                if (!Validate::isString('$title')) {
                                    $errors[] = $this->l('Title of withdrawal method field must be a string','Ets_mp_paymentmethod');
                                }
                            }
                        }
                    }
                }
            }
            if(isset($_FILES['logo']) && isset($_FILES['logo']['name']) && $_FILES['logo']['name'] && !$errors)
            {
                if(!Validate::isFileName($_FILES['logo']['name']))
                    $errors[] = '"'.$_FILES['logo']['name'].'" '.$this->l('file name is not valid');
                else
                {
                    $type = Tools::strtolower(Tools::substr(strrchr($_FILES['logo']['name'], '.'), 1));
                    if(!is_dir(_PS_IMG_DIR_.'mp_payment/'))
                    {
                        @mkdir(_PS_IMG_DIR_.'mp_payment/',0777,true);
                        @copy(dirname(__FILE__).'/index.php', _PS_IMG_DIR_.'mp_payment/index.php');
                    }    
                    $target_file = _PS_IMG_DIR_.'mp_payment/';
                    $file_name = Tools::strtolower(Tools::passwdGen(12,'NO_NUMERIC')).'.'.$type;  
                    $target_file .=$file_name; 
                    if(!in_array($type, array('jpg', 'gif', 'jpeg', 'png')))
                    {
                        $errors[] = $this->l('Logo is not valid');
                    }
                    else
                    {
                        $max_sizefile = Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE');
                        if($_FILES['logo']['size'] > $max_sizefile*1024*1024)
                            $errors[] =sprintf($this->l('Image is too large (%s Mb). Maximum allowed: %s Mb'),Tools::ps_round((float)$_FILES['logo']['size']/1048576,2), Tools::ps_round(Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE'),2));
                    }
                }
                if(!$errors)
                {
                    if (move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) {
                        if($paymentMethod->logo)
                            $logo_old = $paymentMethod->logo;
                        $paymentMethod->logo = $file_name;
                    } else {
                        $errors[] = $this->l('Sorry, there was an error while uploading your logo.');
                    }
                }
            }
            if (!$errors) {  
                $paymentMethod->fee_type = Tools::getValue('payment_method_fee_type');
                $paymentMethod->fee_fixed = (float)Tools::getValue('payment_method_fee_fixed');
                $paymentMethod->fee_percent =(float)Tools::getValue('payment_method_fee_percent');
                $paymentMethod->estimated_processing_time =Tools::getValue('payment_method_estimated');
                $paymentMethod->enable = (int)Tools::getValue('payment_method_enabled');
                foreach($languages as $language)
                {
                    $paymentMethod->title[$language['id_lang']] = trim(Tools::getValue('payment_method_name_'.$language['id_lang'])) ? trim(Tools::getValue('payment_method_name_'.$language['id_lang'])) : trim(Tools::getValue('payment_method_name_'.$id_lang_default));
                    $paymentMethod->description[$language['id_lang']] = trim(Tools::getValue('payment_method_desc_'.$language['id_lang'])) ?  trim(Tools::getValue('payment_method_desc_'.$language['id_lang'])): trim(Tools::getValue('payment_method_desc_'.$id_lang_default));
                    $paymentMethod->note[$language['id_lang']] = trim(Tools::getValue('payment_method_note_'.$language['id_lang'])) ?  trim(Tools::getValue('payment_method_note_'.$language['id_lang'])) : trim(Tools::getValue('payment_method_note_'.$id_lang_default));
                }
                $ok=0;
                if($paymentMethod->id)
                {
                   if($paymentMethod->update(true))
                   {
                        if(isset($logo_old) && $logo_old)
                            @unlink(_PS_IMG_DIR_.'mp_payment/'.$logo_old);
                        $ok=1;
                   }
                   else
                        $errors[] = $this->l('Update failed','Ets_mp_paymentmethod'); 
                }
                elseif ($paymentMethod->add(true,true)) {
                    $ok=2;
                }
                if($ok)
                {
                    $paymentMethod->deleteAllField();
                    if ($pmf = Tools::getValue('payment_method_field', array())) {
                        $sort=1;
                        foreach($pmf as $item)
                        {
                            if(isset($item['id']) && $item['id'])
                                $paymentField = new Ets_mp_paymentmethodfield($item['id']);
                            else
                            {
                                $paymentField = new Ets_mp_paymentmethodfield();
                                $paymentField->id_ets_mp_payment_method = $paymentMethod->id;
                            }    
                            $paymentField->type = $item['type'];
                            $paymentField->required = $item['required'];
                            $paymentField->enable = $item['enable'];
                            $paymentField->deleted=0;
                            $paymentField->sort=$sort;
                            $sort++;
                            foreach($languages as $language)
                            {
                                $paymentField->title[$language['id_lang']] = trim($item['title'][$language['id_lang']]) ? trim($item['title'][$language['id_lang']]) : trim($item['title'][$id_lang_default]);
                                $paymentField->description[$language['id_lang']] = trim($item['description'][$language['id_lang']]) ? trim($item['description'][$language['id_lang']]) : trim($item['description'][$id_lang_default]); 
                            }
                            if($paymentField->id)
                                $paymentField->update();
                            else
                                $paymentField->add();
                        }
                    }
                    if($ok==1)
                    {
                        $this->context->cookie->success_message = $this->l('Updated successfully');
                        Tools::redirectAdmin($link_pm . '&payment_method=' . $paymentMethod->id . '&edit_pm=1');
                    }
                    if($ok==2)
                    {
                        $this->context->cookie->success_message = $this->l('Added successfully');
                        Tools::redirectAdmin($link_pm . '&payment_method=' . $paymentMethod->id . '&edit_pm=1');
                    }
                }
                else    
                    $errors[] = $this->l('Add failed','Ets_mp_paymentmethod'); 
            }
        }
        if ((int)Tools::getValue('create_pm', false) || (Tools::isSubmit('edit_pm') && (int)Tools::getValue('payment_method', false)) ) {
            $context->smarty->assign(array(
                'languages' => $languages,
                'default_lang' => (int)Configuration::get('PS_LANG_DEFAULT'),
                'currency' => $currency,
                'link_pm' => $link_pm,
                'errors' => $errors ? $module->displayError($errors):false,
            ));
            return $context->smarty->fetch(_PS_MODULE_DIR_.'ets_marketplace/views/templates/hook/payment/form_payment_method.tpl');
        }
        elseif(Tools::isSubmit('delete_pm') && $id_ets_mp_payment_method=(int)Tools::getValue('payment_method'))
        {
            $paymentMethod = new Ets_mp_paymentmethod($id_ets_mp_payment_method);
            $paymentMethod->deleted=1;
            $paymentMethod->update();
            Tools::redirectAdmin($link_pm . '&conf=1'); 
        }
        $payment_methods = self::getListPaymentMethods();
        $default_currency = Currency::getDefaultCurrency()->iso_code;
        $context->smarty->assign(array(
            'payment_methods' => $payment_methods,
            'default_currency' => $default_currency,
            'link_pm' => $link_pm
        ));
        return  $context->smarty->fetch(_PS_MODULE_DIR_.'ets_marketplace/views/templates/hook/payment/payments.tpl');
    }
    public function deleteAllField()
    {
        Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'ets_mp_payment_method_field` SET deleted=1 WHERE id_ets_mp_payment_method='.(int)$this->id);
    }
    public function l($string)
    {
        return Translate::getModuleTranslation('ets_marketplace', $string, pathinfo(__FILE__, PATHINFO_FILENAME));
    }
}
