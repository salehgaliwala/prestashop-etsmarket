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
class Ets_MarketPlaceCarrierModuleFrontController extends ModuleFrontController
{
    public $_errors= array();
    public $_success ='';
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
        if(!$this->context->customer->logged || !($this->seller = $this->module->_getSeller(true)) )
            Tools::redirect($this->context->link->getModuleLink($this->module->name,'myseller'));
        if(!$this->module->_checkPermissionPage($this->seller))
            die($this->module->l('You do not have permission to access this page','carrier'));
        if(!Configuration::get('ETS_MP_SELLER_CREATE_SHIPPING') && !Configuration::get('ETS_MP_SELLER_USER_GLOBAL_SHIPPING'))
            Tools::redirect($this->context->link->getModuleLink($this->module->name,'myseller'));
        if((Tools::isSubmit('editmp_carrier') || Tools::getValue('del')=='yes' ) && ($id_carrier = Tools::getValue('id_carrier')) && !$this->checkUserCarrier($id_carrier) )
            die($this->module->l('You do not have permission to edit this carrier','carrier'));
        if(Tools::getValue('action')=='validate_step')
            $this->ajaxProcessValidateStep();
        if(Tools::getValue('action')=='changeRanges')
            $this->ajaxProcessChangeRanges();
        if(Tools::getValue('action')=='finish_step')
            $this->ajaxProcessFinishStep();
        if(Tools::isSubmit('changeUserShipping'))
        {
           
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'ets_mp_seller` SET user_shipping="'.(int)Tools::getValue('user_shipping').'" WHERE id_customer="'.(int)$this->seller->id_customer.'"');
            die(
                Tools::jsonEncode(
                    array(
                        'success' => $this->module->l('Updated successfully','carrier'),
                    )
                )
            );
            
        }
        if(Tools::isSubmit('change_enabled') && $id_carrier = Tools::getValue('id_carrier'))
        {
            $carrier = new Carrier($id_carrier);
            $errors = '';
            if(!Validate::isLoadedObject($carrier) || !$this->checkUserCarrier($id_carrier))
            {
                $errors = $this->module->l('Carrier is not valid','carrier');
            }
            elseif(!($field = Tools::getValue('field')))
                $errors = $this->module->l('Field is required','carrier');
            elseif(!isset($carrier->{$field}))
                $errors = $this->module->l('Field is not valid','carrier');
            else
                $carrier->{$field} = (int)Tools::getValue('change_enabled');
            if(!$errors)
            {
                if($carrier->update())
                {
                    if(Tools::getValue('change_enabled'))
                    {
                        die(
                            Tools::jsonEncode(
                                array(
                                    'href' =>$this->context->link->getModuleLink($this->module->name,'carrier',array('id_carrier'=>$id_carrier,'change_enabled'=>0,'field'=>Tools::getValue('field'))),
                                    'title' => $this->module->l('Click to disable','carrier'),
                                    'success' => $this->module->l('Updated successfully','carrier'),
                                    'enabled' => 1,
                                )
                            )  
                        );
                    }
                    else
                    {
                        die(
                            Tools::jsonEncode(
                                array(
                                    'href' => $this->context->link->getModuleLink($this->module->name,'carrier',array('id_carrier'=>$id_carrier,'change_enabled'=>1,'field'=>Tools::getValue('field'))),
                                    'title' => $this->module->l('Click to enable','carrier'),
                                    'success' => $this->module->l('Updated successfully','carrier'),
                                    'enabled' => 0,
                                )
                            )  
                        );
                    }
                }
                else
                    $errors = $this->module->l('An error occurred while saving the carrier','carrier');
            }
            if($errors)
            {
                die(
                    Tools::jsonEncode(
                        array(
                            'errors' => $errors
                        )
                    )
                );
            }
        }
        if(Tools::getValue('del')=='yes' && $id_carrier= Tools::getValue('id_carrier'))
        {
            $carrier = new Carrier($id_carrier);
            if(!Validate::isLoadedObject($carrier) || !$this->checkUserCarrier($id_carrier))
                $this->_errors[] = $this->module->l('Carrier is not valid');
            else
            {
                $carrier->deleted=1;
                if($carrier->update())
                {
                    $this->context->cookie->_success = $this->module->l('Deleted carrier successfully','carrier');
                }
                else
                    $this->_errors = $this->module->l('An error occurred while deleting the carrier','carrier');
            }
            
        }
    }
    public function initContent()
	{
		parent::initContent();
        if(isset($this->context->cookie->_success) && $this->context->cookie->_success )
        {
            $this->_success = $this->context->cookie->_success;
            $this->context->cookie->_success='';
        }    
        $this->context->smarty->assign(
            array(
                'path' => $this->module->getBreadCrumb(),
                'breadcrumb' => $this->module->is17 ? $this->module->getBreadCrumb() : false, 
                'html_content' => $this->_initContent(),
                '_errors' => $this->_errors ? $this->module->displayError($this->_errors):'',
                '_success' => $this->_success ? $this->module->displayConfirmation($this->_success):'',
            )
        );
        if($this->module->is17)
            $this->setTemplate('module:'.$this->module->name.'/views/templates/front/carrier.tpl');      
        else        
            $this->setTemplate('carrier_16.tpl'); 
    }
    public function _initContent()
    {
        $carrier_content = '';
        if(Tools::isSubmit('addnew') || (Tools::isSubmit('editmp_carrier') && Tools::getValue('id_carrier')))
        {
            $display_form = true;
            if(!Configuration::get('ETS_MP_SELLER_CREATE_SHIPPING') && Tools::isSubmit('addnew'))
                $carrier_content = $this->module->displayWarning($this->module->l('You do not have permission to create new carrier','carrier'));
            else
                $carrier_content =  $this->_renderCarrierForm();
        }
        else
        {
            $display_form = false;
            $carrier_content = $this->_renderCarriersList();
        }
        $this->context->smarty->assign(
            array(
                'carrier_content' => $carrier_content,
                'ets_seller' => $this->seller,
                'display_form' => $display_form
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/carrier.tpl');
    }
    public function _renderCarriersList()
    {
        $fields_list = array(
            'id_carrier' => array(
                'title' => $this->module->l('ID','carrier'),
                'width' => 40,
                'type' => 'text',
                'sort' => true,
                'filter' => true,
            ),
            'name'=>array(
                'title' => $this->module->l('Name','carrier'),
                'type'=> 'text',
                'sort' => true,
                'filter' => true,
            ),
            'logo'=>array(
                'title' => $this->module->l('Logo','carrier'),
                'type'=> 'text',
                'strip_tag' => false,
            ),
            'delay'=>array(
                'title' => $this->module->l('Delay','carrier'),
                'type' => 'text',
                'sort'=>true,
                'filter' => true,
            ),
            'active' => array(
                    'title' => $this->module->l('Enabled','carrier'),
                    'type' => 'active',
                    'sort' => true,
                    'filter' => true,
                    'strip_tag' => false,
                    'filter_list' => array(
                        'id_option' => 'active',
                        'value' => 'title',
                        'list' => array(
                            0 => array(
                                'active' => 0,
                                'title' => $this->module->l('No','carrier')
                            ),
                            1 => array(
                                'active' => 1,
                                'title' => $this->module->l('Yes','carrier')
                            ),
                        )
                    )
                ),
                'is_free' => array(
                    'title' => $this->module->l('Free shipping','carrier'),
                    'type' => 'active',
                    'sort' => true,
                    'filter' => true,
                    'strip_tag' => false,
                    'filter_list' => array(
                        'id_option' => 'active',
                        'value' => 'title',
                        'list' => array(
                            0 => array(
                                'active' => 0,
                                'title' => $this->module->l('No','carrier')
                            ),
                            1 => array(
                                'active' => 1,
                                'title' => $this->module->l('Yes','carrier')
                            ),
                        )
                    )
                )
        );
        $show_resset = false;
        $filter = "";
        if(trim(Tools::getValue('id_carrier')) && !Tools::isSubmit('del'))
        {
            $show_resset = true;
            $filter .=' AND c.id_carrier="'.(int)Tools::getValue('id_carrier').'"';            
        }
        if(trim(Tools::getValue('name')))
        {
            $filter .=' AND c.name like "%'.pSQL(Tools::getValue('name')).'%"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('delay')))
        {
            $filter .=' AND cl.delay="%'.pSQL(Tools::getValue('delay')).'%"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('active'))!='')
        {
            $show_resset = true;
            $filter .=' AND c.active="'.(int)Tools::getValue('active').'"';            
        }
        if(trim(Tools::getValue('is_free'))!='')
        {
            $show_resset = true;
            $filter .=' AND c.is_free="'.(int)Tools::getValue('is_free').'"';            
        }
        $sort = "";
        if(Tools::getValue('sort'))
        {
            switch (Tools::getValue('sort')) {
                case 'id_carrier':
                    $sort .='c.id_carrier';
                    break;
                case 'name':
                    $sort .='c.name';
                    break;
                case 'active':
                    $sort .='c.active';
                    break;
                case 'delay':
                    $sort .='cl.delay';
                    break;
                case 'is_free':
                    $sort .='c.is_free';
                    break;
                case 'position':
                    $sort .='c.position';
                    break;
            }
            if($sort && ($sort_type=Tools::getValue('sort_type')) && in_array($sort_type,array('asc','desc')))
                $sort .= ' '.trim($sort_type);
        }
        $page = (int)Tools::getValue('page') && (int)Tools::getValue('page') > 0 ? (int)Tools::getValue('page') : 1;
        $totalRecords = (int)$this->seller->getCarriers($filter,0,0,'',true);
        $paggination = new Ets_mp_paggination_class();            
        $paggination->total = $totalRecords;
        $paggination->url = $this->context->link->getModuleLink($this->module->name,'attributes',array('list_group'=>1,'page'=>'_page_')).$this->module->getFilterParams($fields_list,'mp_carrier');
        $paggination->limit =  10;
        $totalPages = ceil($totalRecords / $paggination->limit);
        if($page > $totalPages)
            $page = $totalPages;
        $paggination->page = $page;
        $start = $paggination->limit * ($page - 1);
        if($start < 0)
            $start = 0;
        $carriers = $this->seller->getCarriers($filter,$start,$paggination->limit,$sort,false);
        if($carriers)
        {
            foreach($carriers as &$carrier)
            {
                if(!$carrier['name'])
                    $carrier['name'] = $this->context->shop->name;
                if(file_exists(_PS_SHIP_IMG_DIR_.$carrier['id_carrier'].'.jpg'))
                    $carrier['logo'] = '<img src ="'.$this->context->link->getMediaLink(_PS_IMG_.'s/'.$carrier['id_carrier'].'').'.jpg" style="width:50px">';
                if(!$carrier['id_customer'])
                    $carrier['action_edit'] = false;
            }
        }
        $paggination->text =  $this->module->l('Showing {start} to {end} of {total} ({pages} Pages)','carrier');
        $paggination->style_links = 'links';
        $paggination->style_results = 'results';
        $listData = array(
            'name' => 'mp_carrier',
            'actions' => array('view','delete'),
            'currentIndex' => $this->context->link->getModuleLink($this->module->name,'carrier',array('list'=>1)),
            'identifier' => 'id_carrier',
            'show_toolbar' => true,
            'show_action' =>true,
            'title' => $this->module->l('Carriers','carrier'),
            'fields_list' => $fields_list,
            'field_values' => $carriers,
            'paggination' => $paggination->render(),
            'filter_params' => $this->module->getFilterParams($fields_list,'mp_carrier'),
            'show_reset' =>$show_resset,
            'totalRecords' => $totalRecords,
            'sort'=> Tools::getValue('sort','id_carrier'),
            'show_add_new'=> Configuration::get('ETS_MP_SELLER_CREATE_SHIPPING') && $this->seller->user_shipping!=1 ? true:false,
            'link_new' => $this->context->link->getModuleLink($this->module->name,'carrier',array('addnew'=>1)),
            'sort_type' => Tools::getValue('sort_type','desc'),
        );            
        return $this->module->renderList($listData);
    }
    public function _renderCarrierForm()
    {
        if($id_carrier= Tools::getValue('id_carrier'))
            $carrier = new Carrier($id_carrier);
        else
            $carrier = new Carrier();
        $currency = new Currency(Configuration::get('PS_CURRENCY_DEFAULT'));
        $sql_groups ='SELECT * FROM `'._DB_PREFIX_.'group` g
        INNER JOIN `'._DB_PREFIX_.'group_shop` gs ON (g.id_group=gs.id_group AND gs.id_shop="'.(int)$this->context->shop->id.'")
        LEFT JOIN `'._DB_PREFIX_.'group_lang` gl ON (g.id_group =gl.id_group AND gl.id_lang="'.(int)$this->context->language->id.'")';
        $customer_groups = Db::getInstance()->executeS($sql_groups);
        if($customer_groups)
        {
            foreach($customer_groups as &$group)
            {
                $group['checked'] = (int)Db::getInstance()->getValue('SELECT id_carrier FROM `'._DB_PREFIX_.'carrier_group` WHERE id_carrier="'.(int)$carrier->id.'" AND id_group="'.(int)$group['id_group'].'"');
            }
            unset($group);
        }
        $this->context->smarty->assign(
            array(
                'languages'=>Language::getLanguages(true),
                'carrier' =>$carrier,
                'delay' => $carrier->delay,
                'currency' => $currency,
                'carrier_ranges_html' => $this->dispayListRangeCarrier(),
                'customer_groups' => $customer_groups,
                'tax_rule_groups' => Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'tax_rules_group` WHERE active=1 AND deleted=0'),
                'id_lang_default' => Configuration::get('PS_LANG_DEFAULT'),
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/form_carrier.tpl');
    }
    public function ajaxProcessValidateStep()
    {
        $this->validateForm(true);
    }
    protected function copyFromPost(&$object, $table)
    {
        /* Classical fields */
        foreach (Tools::getAllValues() as $key => $value) {
            if (array_key_exists($key, $object) && $key != 'id_' . $table) {
                $object->{$key} = $value;
            }
        }

        /* Multilingual fields */
        $class_vars = get_class_vars(get_class($object));
        $fields = array();
        if (isset($class_vars['definition']['fields'])) {
            $fields = $class_vars['definition']['fields'];
        }

        foreach ($fields as $field => $params) {
            if (array_key_exists('lang', $params) && $params['lang']) {
                foreach (Language::getIDs(false) as $id_lang) {
                    if (Tools::isSubmit($field . '_' . (int) $id_lang)) {
                        $object->{$field}[(int) $id_lang] = Tools::getValue($field . '_' . (int) $id_lang);
                    }
                }
            }
        }
    }
    public function duplicateLogo($new_id, $old_id)
    {
        $old_logo = _PS_SHIP_IMG_DIR_ . '/' . (int) $old_id . '.jpg';
        if (file_exists($old_logo)) {
            copy($old_logo, _PS_SHIP_IMG_DIR_ . '/' . (int) $new_id . '.jpg');
        }

        $old_tmp_logo = _PS_TMP_IMG_DIR_ . '/carrier_mini_' . (int) $old_id . '.jpg';
        if (file_exists($old_tmp_logo)) {
            if (!isset($_FILES['logo'])) {
                copy($old_tmp_logo, _PS_TMP_IMG_DIR_ . '/carrier_mini_' . $new_id . '.jpg');
            }
            unlink($old_tmp_logo);
        }
    }
    protected function changeGroups($id_carrier)
    {
        $carrier = new Carrier((int) $id_carrier);
        if (!Validate::isLoadedObject($carrier)) {
            return false;
        }
        return $carrier->setGroups(Tools::getValue('groupBox'));
    }
    public function processRanges($id_carrier)
    {
        $carrier = new Carrier((int) $id_carrier);
        if (!Validate::isLoadedObject($carrier)) {
            return false;
        }
        $range_inf = Tools::getValue('range_inf');
        $range_sup = Tools::getValue('range_sup');
        $range_type = Tools::getValue('shipping_method');
        $fees = Tools::getValue('fees');
        $carrier->deleteDeliveryPrice($carrier->getRangeTable());
        if ($range_type != Carrier::SHIPPING_METHOD_FREE) {
            foreach ($range_inf as $key => $delimiter1) {
                if (!isset($range_sup[$key])) {
                    continue;
                }
                $range = $carrier->getRangeObject((int) $range_type);
                $range->id_carrier = (int) $carrier->id;
                $range->delimiter1 = (float) $delimiter1;
                $range->delimiter2 = (float) $range_sup[$key];
                $range->save();
                if (!Validate::isLoadedObject($range)) {
                    return false;
                }
                $price_list = array();
                if (is_array($fees) && count($fees)) {
                    foreach ($fees as $id_zone => $fee) {
                        $price_list[] = array(
                            'id_range_price' => ($range_type == Carrier::SHIPPING_METHOD_PRICE ? (int) $range->id : null),
                            'id_range_weight' => ($range_type == Carrier::SHIPPING_METHOD_WEIGHT ? (int) $range->id : null),
                            'id_carrier' => (int) $carrier->id,
                            'id_zone' => (int) $id_zone,
                            'price' => isset($fee[$key]) ? (float) str_replace(',', '.', $fee[$key]) : 0,
                        );
                    }
                }

                if (count($price_list) && !$carrier->addDeliveryPrice($price_list, true)) {
                    return false;
                }
            }
        }
        return true;
    }
    public function postImage($id_carrier,&$errors)
    {
        $name = 'logo';
        if(isset($_FILES[$name]['tmp_name']) && isset($_FILES[$name]['name']) && $_FILES[$name]['name'])
        {
            if(Validate::isFileName($_FILES[$name]['name']))
            {
                $type = Tools::strtolower(Tools::substr(strrchr($_FILES[$name]['name'], '.'), 1));
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
    					$errors[] = sprintf($this->module->l('Image is too large (%s Mb). Maximum allowed: %s Mb','carrier'),Tools::ps_round((float)$_FILES[$name]['size']/1048576,2), Tools::ps_round(Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE'),2));
    				elseif (!$temp_name || !move_uploaded_file($_FILES[$name]['tmp_name'], $temp_name))
    					$errors[] = $this->module->l('Cannot upload the logo','carrier');
    				elseif (!ImageManager::resize($temp_name, _PS_SHIP_IMG_DIR_.$id_carrier.'.jpg', 250, 250, $type))
    					$errors[] = $this->module->l('An error occurred during the logo upload process.','carrier');
    				if (isset($temp_name))
    					@unlink($temp_name);
                    if(!$errors)
                        return true;		
    			}
                else
                    $errors[] = $this->module->l('Logo is not valid','carrier');
            }
            else
                $errors[] = '"'.$_FILES[$name]['name'].'" '. $this->module->l('file name is not valid','carrier');
        }
        return true;
    }
    public function changeZones($id)
    {
        $return = true;
        $carrier = new Carrier($id);
        if (!Validate::isLoadedObject($carrier)) {
            die($this->module->l('The object cannot be loaded.','carrier'));
        }
        $zones = Zone::getZones(false);
        foreach ($zones as $zone) {
            if (count($carrier->getZone($zone['id_zone']))) {
                if (!Tools::isSubmit('zone_' . $zone['id_zone']) || !Tools::getValue('zone_' . $zone['id_zone'])) {
                    $return &= $carrier->deleteZone((int) $zone['id_zone']);
                }
            } elseif (Tools::getValue('zone_' . $zone['id_zone'])) {
                $return &= $carrier->addZone((int) $zone['id_zone']);
            }
        }

        return $return;
    }
    public function updateAssoShop($id_carrier)
    {
        if(!Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'carrier_shop` WHERE id_carrier="'.(int)$id_carrier.'" AND id_shop="'.(int)$this->context->shop->id.'"'))
            return Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'carrier_shop`(id_carrier,id_shop) VALUES("'.(int)$id_carrier.'","'.(int)$this->context->shop->id.'")');
        else
            return true;
    }
    public function dispayListRangeCarrier()
    {
        if($id_carrier = (int)Tools::getValue('id_carrier'))
            $carrier = new Carrier($id_carrier);
        else
            $carrier = new Carrier();
        if ((!(int) $shipping_method = Tools::getValue('shipping_method',$carrier->shipping_method? :2)) || !in_array($shipping_method, array(Carrier::SHIPPING_METHOD_PRICE, Carrier::SHIPPING_METHOD_WEIGHT))) {
            return;
        }
        if($shipping_method==Carrier::SHIPPING_METHOD_PRICE)
            $range_prices = Db::getInstance()->executeS('SELECT id_range_price as id_range,delimiter1,delimiter2 FROM `'._DB_PREFIX_.'range_price` WHERE id_carrier="'.(int)$carrier->id.'"');
        else   
            $range_weights = Db::getInstance()->executeS('SELECT id_range_weight as id_range,delimiter1,delimiter2 FROM `'._DB_PREFIX_.'range_weight` WHERE id_carrier="'.(int)$carrier->id.'"');
        $zones = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'zone` where active=1');
        $deliveries = array();
        if($zones)
        {
            foreach($zones as &$zone)
            {
                $zone['checked'] = Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'carrier_zone` WHERE id_carrier="'.(int)$carrier->id.'" AND id_zone="'.(int)$zone['id_zone'].'"');
                if(isset($range_prices) && $range_prices)
                {
                    foreach($range_prices as $range_price)
                    {
                        $deliveries[$zone['id_zone']][$range_price['id_range']] = Db::getInstance()->getValue('SELECT price FROM `'._DB_PREFIX_.'delivery` WHERE id_zone="'.(int)$zone['id_zone'].'" AND id_carrier="'.(int)$carrier->id.'" AND id_range_price="'.(int)$range_price['id_range'].'"');
                    }
                }
                elseif(isset($range_weights) && $range_weights)
                {
                    foreach($range_weights as $range_weight)
                    {
                        $deliveries[$zone['id_zone']][$range_weight['id_range']] = Db::getInstance()->getValue('SELECT price FROM `'._DB_PREFIX_.'delivery` WHERE id_zone="'.(int)$zone['id_zone'].'" AND id_carrier="'.(int)$carrier->id.'" AND id_range_weight="'.(int)$range_weight['id_range'].'"');
                    }
                }
            }
        }
        $currency = new Currency(Configuration::get('PS_CURRENCY_DEFAULT'));
        $this->context->smarty->assign(
            array(
                'zones' => $zones,
                'currency'=> $currency,
                'ranges' => isset($range_prices) ? $range_prices : $range_weights,
                'deliveries' => $deliveries,
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/carrier_range.tpl');
    }
    public function ajaxProcessChangeRanges()
    {
        die($this->dispayListRangeCarrier());
    }
    public function ajaxProcessFinishStep()
    {
        $return = array('has_error' => false);
        $this->validateForm(false);
        if ($id_carrier = Tools::getValue('id_carrier')) {
            $current_carrier = new Carrier((int) $id_carrier);

            // if update we duplicate current Carrier
            /** @var Carrier $new_carrier */
            $new_carrier = $current_carrier->duplicateObject();

            if (Validate::isLoadedObject($new_carrier)) {
                // Set flag deteled to true for historization
                $current_carrier->deleted = true;
                $current_carrier->update();

                // Fill the new carrier object
                $this->copyFromPost($new_carrier, 'carrier');
                $new_carrier->position = $current_carrier->position;
                $new_carrier->update();
                $this->updateAssoShop($new_carrier->id);
                $this->duplicateLogo((int) $new_carrier->id, (int) $current_carrier->id);
                $this->changeGroups((int) $new_carrier->id);

                //Copy default carrier
                if (Configuration::get('PS_CARRIER_DEFAULT') == $current_carrier->id) {
                    Configuration::updateValue('PS_CARRIER_DEFAULT', (int) $new_carrier->id);
                }
                Hook::exec('actionCarrierUpdate', array(
                    'id_carrier' => (int) $current_carrier->id,
                    'carrier' => $new_carrier,
                ));
                $this->changeZones($new_carrier->id);
                $new_carrier->setTaxRulesGroup((int) Tools::getValue('id_tax_rules_group'));
                $carrier = $new_carrier;
                $this->context->cookie->_success = $this->module->l('Updated carrier successfully','carrier');
            }
        } else {
            $carrier = new Carrier();
            $this->copyFromPost($carrier, 'carrier');
            if (!$carrier->add()) {
                $return['has_error'] = true;
                $return['errors'][] = $this->module->l('An error occurred while saving this carrier.','carrier');
            }
            else
                $this->context->cookie->_success = $this->module->l('Add carrier successfully','carrier');
        }
        if($carrier->id)
        {
            $id_reference = (int)Db::getInstance()->getValue('SELECT id_reference FROM `'._DB_PREFIX_.'carrier` WHERE id_carrier="'.(int)$carrier->id.'"');
            if(!Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'ets_mp_carrier_seller` WHERE id_customer="'.(int)$this->seller->id_customer.'" AND id_carrier_reference="'.(int)$id_reference.'"'))
                Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'ets_mp_carrier_seller`(id_customer,id_carrier_reference) VALUES("'.(int)$this->seller->id_customer.'","'.(int)$id_reference.'")');
        }
        
        if ($carrier->is_free) {
            //if carrier is free delete shipping cost
            $carrier->deleteDeliveryPrice('range_weight');
            $carrier->deleteDeliveryPrice('range_price');
        }

        if (Validate::isLoadedObject($carrier)) {
            if (!$this->changeGroups((int) $carrier->id)) {
                $return['has_error'] = true;
                $return['errors'][] = $this->module->l('An error occurred while saving carrier groups.','carrier');
            }

            if (!$this->changeZones((int) $carrier->id)) {
                $return['has_error'] = true;
                $return['errors'][] = $this->module->l('An error occurred while saving carrier zones.','carrier');
            }

            if (!$carrier->is_free) {
                if (!$this->processRanges((int) $carrier->id)) {
                    $return['has_error'] = true;
                    $return['errors'][] = $this->module->l('An error occurred while saving carrier ranges.','carrier');
                }
            }

            if (Shop::isFeatureActive() && !$this->updateAssoShop((int) $carrier->id)) {
                $return['has_error'] = true;
                $return['errors'][] = $this->module->l('An error occurred while saving associations of shops.','carrier');
            }

            if (!$carrier->setTaxRulesGroup((int) Tools::getValue('id_tax_rules_group'))) {
                $return['has_error'] = true;
                $return['errors'][] = $this->module->l('An error occurred while saving the tax rules group.','carrier');
            }
            if(!$this->postImage($carrier->id,$return['errors']))
                $return['has_error'] = true;
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'delivery` SET `id_shop_group` = NULL,id_shop=NULL WHERE `id_carrier` = "'.(int)$carrier->id.'"');
            $return['id_carrier'] = $carrier->id;
        }
        die(json_encode($return));
    }
    protected function validateForm($die = true)
    {
        $return = array('has_error' => false);
        if($id_carrier = (int)Tools::getValue('id_carrier'))
        {
            if(($carrier = new Carrier($id_carrier)) && (!Validate::isLoadedObject($carrier) || !$this->checkUserCarrier($id_carrier)))
                $this->_errors[] = $this->module->l('Carrier is not valid','carrier');
        }
        if(isset($_FILES['logo']['name']) && $_FILES['logo']['name'] && isset($_FILES['logo']['size']) && $_FILES['logo']['size'])
            $this->module->validateFile($_FILES['logo']['name'],$_FILES['logo']['size'],$this->_errors,array('jpg','gif','png'));
        $this->validateRules();
        if (count($this->_errors)) {
            $return['has_error'] = true;
            $return['errors'] = $this->_errors;
        }
        
        if (count($this->_errors) || $die) {
            die(json_encode($return));
        }
    }
    public function validateRules()
    {
        $class_name='Carrier';
        $object = new Carrier();
        $definition = $this->getValidationRules();
        $default_language = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $languages = Language::getLanguages(true);
        foreach ($definition['fields'] as $field => $def) {
            $skip = array();
            if (in_array($field, array('passwd', 'no-picture'))) {
                $skip = array('required');
            }
            if (isset($def['lang']) && $def['lang']) {
                if (isset($def['required']) && $def['required']) {
                    $value = Tools::getValue($field . '_' . $default_language->id);
                    // !isset => not exist || "" == $value can be === 0 (before, empty $value === 0 returned true)
                    if (!isset($value) || '' == $value) {
                        $this->_errors[$field . '_' . $default_language->id] = $this->module->l('The field','carrier').' '.$object->displayFieldName($field, $class_name).' '.$this->module->l('is required at least in','carrier').' '.$default_language->name;
                    }
                }
                foreach ($languages as $language) {
                    $value = Tools::getValue($field . '_' . $language['id_lang']);
                    if (!empty($value)) {
                        if (($error = $object->validateField($field, $value, $language['id_lang'], $skip, true)) !== true) {
                            $this->_errors[$field . '_' . $language['id_lang']] = $error;
                        }
                    }
                }
            } elseif (($error = $object->validateField($field, Tools::getValue($field), null, $skip, true)) !== true) {
                if($field=='url')
                    $this->_errors[$field] = $this->module->l('Tracking URL is invalid','carrier');
                else
                    $this->_errors[$field] = $error;
            }
        }
        
    }
    public function getValidationRules()
    {
        $step_number = (int) Tools::getValue('step_number');
        if (!$step_number) {
            return;
        }

        if ($step_number == 4 && !Shop::isFeatureActive() || $step_number == 5 && Shop::isFeatureActive()) {
            return array('fields' => array());
        }

        $step_fields = array(
            1 => array('name', 'delay', 'grade', 'url'),
            2 => array('is_free', 'id_tax_rules_group', 'shipping_handling', 'shipping_method', 'range_behavior'),
            3 => array('range_behavior', 'max_height', 'max_width', 'max_depth', 'max_weight'),
            4 => array(),
        );
        if (Shop::isFeatureActive()) {
            $tmp = $step_fields;
            $step_fields = array_slice($tmp, 0, 1, true) + array(2 => array('shop'));
            $step_fields[3] = $tmp[2];
            $step_fields[4] = $tmp[3];
        }

        $definition = ObjectModel::getDefinition('Carrier');
        foreach ($definition['fields'] as $field => $def) {
            if (is_array($step_fields[$step_number]) && !in_array($field, $step_fields[$step_number])) {
                unset($definition['fields'][$field]);
            }
            unset($def);
        }

        return $definition;
    }
    public function checkUserCarrier($id_carrier)
    {
        $carrier = new Carrier($id_carrier);
        return Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'ets_mp_carrier_seller` WHERE id_customer="'.(int)$this->seller->id_customer.'" AND id_carrier_reference="'.(int)$carrier->id_reference.'"');
    }
}