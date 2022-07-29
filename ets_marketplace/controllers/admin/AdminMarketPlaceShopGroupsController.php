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
class AdminMarketPlaceShopGroupsController extends ModuleAdminController
{
    public function __construct()
    {
       parent::__construct();
       $this->context= Context::getContext();
       $this->bootstrap = true;
    }
    public function postProcess()
    {
        parent::postProcess();
        if(Tools::getValue('del')=='yes' && $id_group = Tools::getValue('id_group'))
        {
            $group = new Ets_mp_seller_group($id_group);
            if($group->delete())
            {
                $this->context->cookie->success_message = $this->l('Deleted successfully');
                Tools::redirectAdmin($this->context->link->getAdminLink(Tools::getValue('controller')).'&list=true');
            }
        }
        if(Tools::getValue('delbadimage') && $id_group = Tools::getValue('id_group'))
        {
            $group = new Ets_mp_seller_group($id_group);
            $badge_image_old = $group->badge_image;
            $group->badge_image = '';
            if($group->update())
            {
                if($badge_image_old && file_exists(_PS_IMG_DIR_.'mp_group/'.$badge_image_old))
                    @unlink(_PS_IMG_DIR_.'mp_group/'.$badge_image_old);
                $this->context->cookie->success_message = $this->l('Badge image deleted successfully');
                    Tools::redirectAdmin($this->context->link->getAdminLink('AdminMarketPlaceShopGroups').'&editets_group=1&id_group='.(int)$group->id);
            }
        }
        if(Tools::isSubmit('saveShopGroup'))
        {
            $id_lang_default = Configuration::get('PS_LANG_DEFAULT');
            $languages = Language::getLanguages(false);
            $errors = array();
            if(!Tools::getValue('name_'.$id_lang_default))
                $errors[] = $this->l('Name is required');
            else
            {
                foreach($languages as $language)
                {
                    if(Tools::getValue('name_'.$language['id_lang']) && !Validate::isCleanHtml(Tools::getValue('name_'.$language['id_lang'])))
                        $errors[] = $this->l('Name is not valid in').' '.$language['iso_code'];
                }
            }
            foreach($languages as $language)
            {
                if(Tools::getValue('description_'.$language['id_lang']) && !Validate::isCleanHtml(Tools::getValue('description_'.$language['id_lang'])))
                    $errors[] = $this->l('Description is not valid in').' '.$language['iso_code'];
                if(Tools::getValue('level_name_'.$language['id_lang']) && !Validate::isCleanHtml(Tools::getValue('level_name_'.$language['id_lang'])))
                    $errors[] = $this->l('Badge name is not valid in').' '.$language['iso_code'];
            }
            if(!Tools::getValue('use_fee_global') && !Tools::getValue('fee_type'))
                $errors[] = $this->l('Fee type is required');
            if(!Tools::getValue('use_fee_global') && Tools::getValue('fee_type')!='no_fee')
            {
                if(!Tools::getValue('fee_amount'))
                    $errors[] = $this->l('Fee amount is required');
                elseif(!Validate::isPrice(Tools::getValue('fee_amount')) || Tools::getValue('fee_amount')<=0)
                    $errors[] = $this->l('Fee amount is not valid');
            }
            if(!Tools::getValue('use_commission_global'))
            {
                if(trim(Tools::getValue('commission_rate'))=='')
                    $errors[] = $this->l('Commission rate is required');
                elseif(!Validate::isPrice(Tools::getValue('commission_rate')))
                    $errors[] = $this->l('Commission rate is not valid');
                elseif(Tools::getValue('commission_rate')<=0 || Tools::getValue('commission_rate')>=100)
                    $errors[] = $this->l('Commission rate must be between 0% and 100%');
            }
            if(($auto_upgrade = Tools::getValue('auto_upgrade'))!=='')
            {
                if(!Validate::isPrice($auto_upgrade) || $auto_upgrade <=0)
                    $errors[] = $this->l('"Auto upgrade when turnover reached" field is not valid');
                elseif(Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'ets_mp_seller_group WHERE auto_upgrade="'.(float)$auto_upgrade.'" AND id_ets_mp_seller_group != "'.(int)Tools::getValue('id_group').'"'))
                    $errors[] = $this->l('"Auto upgrade when turnover reached" field value is exists');
            }
            $badge_image = '';
            if(isset($_FILES['badge_image']['name']) && $_FILES['badge_image']['name'] && isset($_FILES['badge_image']['tmp_name']) && $_FILES['badge_image']['tmp_name'])
            {
                if(!Validate::isFileName($_FILES['badge_image']['name']))
                {
                    $errors[] = $this->l('Level badge image file name is not valid');
                }
                else
                {
                    $type = Tools::strtolower(Tools::substr(strrchr($_FILES['badge_image']['name'], '.'), 1));
                    $imagesize = @getimagesize($_FILES['badge_image']['tmp_name']);
                    if(empty($imagesize) || !in_array($type,array('jpg', 'gif', 'jpeg', 'png')))
                        $errors[] = $this->l('Level badge image is not valid');
                    else
                    {
                        $max_file_size = Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE')*1024*1024;
                        if( $_FILES['badge_image']['size'] > $max_file_size)
                            $errors[] = sprintf($this->l('Image is too large (%s Mb). Maximum allowed: %s Mb'),Tools::ps_round((float)$_FILES['badge_image']['size']/1048576,2), Tools::ps_round(Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE'),2));
                    }
                                       
                }
                if(!$errors)
                {
                    if(!is_dir(_PS_IMG_DIR_.'mp_group/'))
                    {
                        @mkdir(_PS_IMG_DIR_.'mp_group/',0777,true);
                        @copy(dirname(__FILE__).'/index.php', _PS_IMG_DIR_.'mp_group/index.php');
                    }
                    $_FILES['badge_image']['name'] = Tools::strtolower(Tools::passwdGen(12,'NO_NUMERIC')).'.'.$type;
                    if(file_exists(_PS_IMG_DIR_.'mp_group/'.$_FILES['badge_image']['name']))
                        $errors[] = $this->l('Level badge image already exists. Try to rename the file then reupload');
                    else
                    {
                        $temp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');
                        if (!$temp_name || !move_uploaded_file($_FILES['badge_image']['tmp_name'], $temp_name))
        					$errors[] = $this->l('Cannot upload the file');
        				elseif (!ImageManager::resize($temp_name, _PS_IMG_DIR_.'mp_group/'.$_FILES['badge_image']['name'], null,null, $type))
        					$errors[] = $this->l('An error occurred during the image upload process.');
                        else
                            $badge_image = $_FILES['badge_image']['name'];
                    }
                }
            }
            if(!$errors)
            {
                $badge_image_old = '';
                if($id_group = Tools::getValue('id_group'))
                {
                    $group = new Ets_mp_seller_group($id_group);
                    if($badge_image)
                    {
                        $badge_image_old = $group->badge_image;
                        $group->badge_image = $badge_image;
                    }
                }    
                else
                {
                    $group = new Ets_mp_seller_group();
                    $group->id_shop = $this->context->shop->id;
                    $group->badge_image = $badge_image;
                }
                $group->use_fee_global = (int)Tools::getValue('use_fee_global');
                if(!$group->use_fee_global)
                {
                    $group->fee_type = Tools::getValue('fee_type');
                    if($group->fee_type!='no_fee')
                    {
                        $group->fee_amount = (float)Tools::getValue('fee_amount');
                        $group->fee_tax = (int)Tools::getValue('fee_tax');
                    }
                }
                $group->use_commission_global = (int)Tools::getValue('use_commission_global');
                if(!$group->use_commission_global)
                    $group->commission_rate = (float)Tools::getValue('commission_rate');
                $group->auto_upgrade = (float)$auto_upgrade;
                foreach($languages as $language)
                {
                    $group->name[$language['id_lang']] = Tools::getValue('name_'.$language['id_lang']) ? : Tools::getValue('name_'.$id_lang_default);
                    $group->description[$language['id_lang']] = Tools::getValue('description_'.$language['id_lang']);
                    $group->level_name[$language['id_lang']] = Tools::getValue('level_name_'.$language['id_lang']) ? : Tools::getValue('level_name_'.$id_lang_default);
                }
                if($group->id)
                {
                    if($group->update())
                    {
                        if($badge_image_old && file_exists(_PS_IMG_DIR_.'mp_group/'.$badge_image_old))
                            @unlink(_PS_IMG_DIR_.'mp_group/'.$badge_image_old);
                        $this->context->cookie->success_message = $this->l('Updated successfully');
                        Tools::redirectAdmin($this->context->link->getAdminLink('AdminMarketPlaceShopGroups').'&editets_group=1&id_group='.(int)$group->id);
                    }
                    else
                    {
                        if($badge_image && file_exists(_PS_IMG_DIR_.'mp_group/'.$badge_image))
                            @unlink(_PS_IMG_DIR_.'mp_group/'.$badge_image);
                        $this->module->_errors[] = $this->l('Update failed');
                    }
                }
                else
                {
                    if($group->add())
                    {
                        $this->context->cookie->success_message = $this->l('Added successfully');
                        Tools::redirectAdmin($this->context->link->getAdminLink('AdminMarketPlaceShopGroups').'&editets_group=1&id_group='.(int)$group->id);
                    }
                    else
                    {
                        if($badge_image && file_exists(_PS_IMG_DIR_.'mp_group/'.$badge_image))
                            @unlink(_PS_IMG_DIR_.'mp_group/'.$badge_image);
                        $this->module->_errors[] = $this->l('Add failed');
                    }    
                }
            }
            else
                $this->module->_errors = $errors;
        }
    }
    public function renderList()
    {
        $this->module->getContent();
        $this->context->smarty->assign(
            array(
                'ets_mp_body_html'=> $this->_getContent(),
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
    public function _getContent()
    {
        if(Tools::isSubmit('addGroup') ||  (Tools::isSubmit('editets_group') && Tools::getValue('id_group')))
        {
            return $this->_renderFormGroup();
        }
        return $this->_rederListGroups();
    }
    public function _rederListGroups()
    {
        $fields_list = array(
            'id_group' => array(
                'title' => $this->l('ID'),
                'width' => 40,
                'type' => 'text',
                'sort' => true,
                'filter' => true,
            ),
            'name' => array(
                'title' => $this->l('Name'),
                'type' => 'text',
                'sort' => true,
                'filter' => true,
                'strip_tag' => false,
            ),
            'description' => array(
                'title' => $this->l('Description'),
                'type' => 'text',
                'sort' => true,
                'filter' => true,
                'strip_tag' => false,
            ),
            'fee_amount' => array(
                'title' => $this->l('Fee'),
                'type' => 'text',
                'sort' => true,
            ),
            'commission_rate' => array(
                'title' => $this->l('Commission rate'),
                'type' => 'text',
                'sort' => true,
                'form_group_class' => 'text-center',
            ),
            'auto_upgrade' => array(
                'title' => $this->l('Auto upgrade'),
                'type' => 'text',
                'sort' => true,
                'form_group_class' => 'text-center',
            ),
        );
        //Filter
        $filter='';
        $show_resset = false;
        if(Tools::getValue('id_group') && !Tools::isSubmit('del'))
        {
            $filter .= ' AND g.id_ets_mp_seller_group="'.(int)Tools::getValue('id_group').'"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('name')))
        {
            $filter .=' AND gl.name LIKE "%'.pSQL(trim(Tools::getValue('name'))).'%"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('description')))
        {
            $filter .=' AND gl.description LIKE "%'.pSQL(trim(Tools::getValue('description'))).'%"';
            $show_resset = true;
        }
        //Sort
        $sort = "";
        if(Tools::getValue('sort'))
        {
            switch (Tools::getValue('sort')) {
                case 'id_group':
                    $sort .=' id_group';
                    break;
                case 'name':
                    $sort .=' gl.name';
                    break;
                case 'description':
                    $sort .= ' gl.description';
                    break;
                case 'fee_amount':
                    $sort .= ' fee_amount';
                    break;
                case 'commission_rate':
                    $sort .= 'commission_rate';
                    break;
                case 'auto_upgrade':
                    $sort .= 'auto_upgrade';
                    break;
            }
            if($sort && ($sort_type=Tools::getValue('sort_type')) && in_array($sort_type,array('acs','desc')))
                $sort .= ' '.$sort_type;  
        }
        //Paggination
        $page = (int)Tools::getValue('page') && (int)Tools::getValue('page') > 0 ? (int)Tools::getValue('page') : 1;
        $totalRecords = (int) Ets_mp_seller_group::_getSellerGroups($filter,$sort,0,0,true);
        $paggination = new Ets_mp_paggination_class();            
        $paggination->total = $totalRecords;
        $paggination->url = $this->context->link->getAdminLink(Tools::getValue('AdminMarketPlaceShopGroups')).'&page=_page_'.$this->module->getFilterParams($fields_list,'ets_group');
        $paggination->limit =  20;
        $totalPages = ceil($totalRecords / $paggination->limit);
        if($page > $totalPages)
            $page = $totalPages;
        $paggination->page = $page;
        $start = $paggination->limit * ($page - 1);
        if($start < 0)
            $start = 0;
        $groups= Ets_mp_seller_group::_getSellerGroups($filter,$sort,$start,$paggination->limit,false);
        if($groups)
        {
            foreach($groups as &$group)
            {
                if($group['fee_type']=='no_fee')
                    $group['fee_type'] = $this->l('No fee');
                elseif($group['fee_type']=='pay_once')
                    $group['fee_type'] = $this->l('Pay once');
                elseif($group['fee_type']=='monthly_fee')
                    $group['fee_type'] = $this->l('Monthly fee');
                elseif($group['fee_type']=='quarterly_fee')
                    $group['fee_type'] = $this->l('Quarterly fee');
                elseif($group['fee_type']=='yearly_fee')
                    $group['fee_type'] = $this->l('Yearly fee');
                $group['fee_amount'] = $group['fee_amount'] ? Tools::displayPrice($group['fee_amount']).' ('.$group['fee_type'].')': $this->l('No fee');
                $group['commission_rate'] = Tools::ps_round($group['commission_rate'],2).'%'; 
                $group['auto_upgrade'] = $group['auto_upgrade']!=0 ? Tools::displayPrice($group['auto_upgrade']):'--';
            }
        }
        $paggination->text =  $this->l('Showing {start} to {end} of {total} ({pages} Pages)');
        $paggination->style_links = $this->l('links');
        $paggination->style_results = $this->l('results');
        $listData = array(
            'name' => 'ets_group',
            'actions' => array('view','delete'),
            'icon' => 'icon-sellers',
            'currentIndex' => $this->context->link->getAdminLink('AdminMarketPlaceShopGroups'),
            'identifier' => 'id_group',
            'show_toolbar' => true,
            'show_action' => true,
            'title' => $this->l('Shop groups'),
            'fields_list' => $fields_list,
            'field_values' => $groups,
            'paggination' => $paggination->render(),
            'filter_params' => $this->module->getFilterParams($fields_list,'ets_group'),
            'show_reset' =>$show_resset,
            'totalRecords' => $totalRecords,
            'sort'=> Tools::getValue('sort','id_group'),
            'sort_type' => Tools::getValue('sort_type','desc'),
            'show_add_new' => true,
            'link_new' => $this->context->link->getAdminLink('AdminMarketPlaceShopGroups').'&addGroup'
        );            
        return  $this->module->renderList($listData);
    }
    public function _renderFormGroup()
    {
        if($id_group = Tools::getValue('id_group'))
            $group = new Ets_mp_seller_group($id_group);
        else
            $group = new Ets_mp_seller_group();
        $fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $group->id ? $this->l('Edit shop group') : $this->l('Add shop group'),
                    'icon' =>'icon-group',				
				),
				'input' => array(					
					array(
						'type' => 'text',
						'label' => $this->l('Name'),
						'name' => 'name', 
                        'lang' => true,
                        'required' => true, 					                     
					),
                    array(
						'type' => 'text',
						'label' => $this->l('Badge name'),
						'name' => 'level_name', 
                        'lang' => true,					                     
					), 
                    array(
						'type' => 'file',
						'label' => $this->l('Badge image'),
						'name' => 'badge_image',
                        'desc' => sprintf($this->l('Accepted formats: jpg, png, gif, jpeg. Limit %sMb. Recommended size: 20x20px'),Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE')) , 	
                        'imageType' => 'badge',	
                        'form_group_class' =>'badge_image',
                        'display_img' => $group->badge_image ? __PS_BASE_URI__.'img/mp_group/'.$group->badge_image:false,
                        'img_del_link' => $group->badge_image ? $this->context->link->getAdminLink('AdminMarketPlaceShopGroups').'&delbadimage=1&editets_group=1&id_group='.$group->id:'',		                     
					), 
                    array(
						'type' => 'textarea',
						'label' => $this->l('Description'),
						'name' => 'description',   
                        'lang' => true,				                    
					), 
                    array(
                        'type'=> 'switch',
                        'name'=> 'use_fee_global',
                        'label' => $this->l('Use global fee'),
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
                        'name' =>'fee_type',
                        'label' => $this->l('Fee type'),
                        'type' => 'radio',
                        'required' => true,
                        'form_group_class' => 'global_fee',
                        'values' => array(
                            array(
                                'id' => 'fee_type_no_fee',
                                'value'=>'no_fee',
                                'label' => $this->l('No fee'),
                            ),
                            array(
                                'id' => 'fee_type_pay_once',
                                'value'=>'pay_once',
                                'label' => $this->l('Pay once')
                            ),
                            array(
                                'id' => 'fee_type_monthly_fee',
                                'value'=>'monthly_fee',
                                'label' => $this->l('Monthly fee')
                            ),
                            array(
                                'id' => 'fee_type_quarterly_fee',
                                'value'=>'quarterly_fee',
                                'label' => $this->l('Quarterly fee')
                            ),
                            array(
                                'id' => 'fee_type_yearly_fee',
                                'value'=>'yearly_fee',
                                'label' => $this->l('Yearly fee')
                            ),
                        ),
                    ),
                    array(
						'type' => 'text',
						'label' => $this->l('Fee amount'),
						'name' => 'fee_amount',                            
                        'required' => true,	
                        'suffix' => $this->context->currency->sign,	
                        'form_group_class' => 'ets_mp_fee global_fee',	
                        'col'=>3,		
					),  
                    array(
						'type' => 'select',
						'label' => $this->l('Fee tax'),
						'name' => 'fee_tax',                            
                        'options' => array(
                			 'query' => TaxRulesGroup::getTaxRulesGroupsForOptions(),                             
                             'id' => 'id_tax_rules_group',
                			 'name' => 'name'  
                        ),    
                        'form_group_class'=>'ets_mp_fee global_fee'				
					),   
                    array(
                        'type'=> 'switch',
                        'name'=> 'use_commission_global',
                        'label' => $this->l('Use global commission'),
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
                        'label' => $this->l('Commission rate'),
                        'name' => 'commission_rate',
                        'suffix' =>'%',
                        'col'=>3,
                        'required' => true,
                        'form_group_class' =>'global_commission text-center',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Auto upgrade when turnover reached'),
                        'desc' => $this->l('Upgrade seller to this level when their turnover reaches the threshold amount'),
                        'suffix' => (new Currency(Configuration::get('PS_CURRENCY_DEFAULT')))->iso_code,
                        'col'=>3,
                        'name' => 'auto_upgrade',
                    ),
                ),
                'submit' => array(
					'title' => $this->l('Save'),
				),
                'buttons' => array(
                    array(
                        'href' => $this->context->link->getAdminLink('AdminMarketPlaceShopGroups', true),
                        'icon'=>'process-icon-cancel',
                        'title' => $this->l('Cancel'),
                    )
                ),
            ),
		);
        $helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = 'ets_mp_seller_group';
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();
		$helper->module = $this->module;
		$helper->identifier = 'id_group';
		$helper->submit_action = 'saveShopGroup';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminMarketPlaceShopGroups', false).(Tools::isSubmit('addGroup') ?'&addGroup':'&editets_group=1&id_group='.Tools::getValue('id_group'));
		$helper->token = Tools::getAdminTokenLite('AdminMarketPlaceShopGroups');
		$language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->tpl_vars = array(
			'base_url' => $this->context->shop->getBaseURL(),
			'language' => array(
				'id_lang' => $language->id,
				'iso_code' => $language->iso_code
			),
            
            'PS_ALLOW_ACCENTED_CHARS_URL', (int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL'),
			'fields_value' => $this->getShopGroupFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id,
			'image_baseurl' => '',
            'link' => $this->context->link,
            'cancel_url' => $this->context->link->getAdminLink('AdminMarketPlaceShopGroups', true),
		);            
        $fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_group');
		//$helper->override_folder = '/../'; 
        return $helper->generateForm(array($fields_form));
    }
    public function getShopGroupFieldsValues()
    {
        $fields = array();
        $id_group = Tools::getValue('id_group');
        $languages = Language::getLanguages(false);
        $group = new Ets_mp_seller_group($id_group);
        $fields['id_group'] = $id_group;
        $fields['use_fee_global'] = Tools::getValue('use_fee_global',$group->use_fee_global);
        $fields['use_commission_global'] = Tools::getValue('use_commission_global',$group->use_commission_global);
        $fields['fee_type'] = Tools::getValue('fee_type',$group->fee_type);
        $fields['fee_amount'] = Tools::getValue('fee_amount',$group->fee_amount)?:'';
        $fields['fee_tax'] = Tools::getValue('fee_tax',$group->fee_tax);
        $fields['commission_rate'] =Tools::getValue('commission_rate',Tools::ps_round($group->commission_rate,2)) ?:'';
        $fields['auto_upgrade'] = Tools::isSubmit('auto_upgrade') ? Tools::getValue('auto_upgrade') : ($group->auto_upgrade!= 0 ? $group->auto_upgrade :'');
        if($languages)
        {
            foreach($languages as $language)
            {
                $fields['name'][$language['id_lang']] = Tools::getValue('name_'.$language['id_lang'],$group->name[$language['id_lang']]);
                $fields['level_name'][$language['id_lang']] = Tools::getValue('level_name_'.$language['id_lang'],$group->level_name[$language['id_lang']]);
                $fields['description'][$language['id_lang']] = Tools::getValue('description_'.$language['id_lang'],$group->description[$language['id_lang']]);
            }
        }
        return $fields;
    }
}