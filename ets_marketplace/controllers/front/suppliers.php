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
class Ets_MarketPlaceSuppliersModuleFrontController extends ModuleFrontController
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
        if(!Configuration::get('ETS_MP_SELLER_CREATE_SUPPLIER') && !Configuration::get('ETS_MP_SELLER_USER_GLOBAL_SUPPLIER'))
            Tools::redirect($this->context->link->getModuleLink($this->module->name,'myseller'));
        if(!$this->context->customer->logged || !($this->seller = $this->module->_getSeller(true)) )
            Tools::redirect($this->context->link->getModuleLink($this->module->name,'myseller'));
        if(!$this->module->_checkPermissionPage($this->seller))
            die($this->module->l('You do not have permission to access this page','suppliers'));
        if(Tools::isSubmit('change_enabled') && $id_supplier = Tools::getValue('id_supplier'))
        {
            $errors = '';
            $supplier = new Supplier($id_supplier);
            if(!Validate::isLoadedObject($supplier) || !$this->seller->checkHasSupplier($id_supplier,false))
                $errors = $this->module->l('Supplier is not valid','suppliers');
            else
            {
                $supplier->active = (int)Tools::getValue('change_enabled');
                if($supplier->update())
                {
                    if(Tools::getValue('change_enabled'))
                    {
                        die(
                            Tools::jsonEncode(
                                array(
                                    'href' =>$this->context->link->getModuleLink($this->module->name,'suppliers',array('id_supplier'=>$id_supplier,'change_enabled'=>0,'field'=>'active')),
                                    'title' => $this->module->l('Click to disable','suppliers'),
                                    'success' => $this->module->l('Updated successfully','suppliers'),
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
                                    'href' => $this->context->link->getModuleLink($this->module->name,'suppliers',array('id_supplier'=>$id_supplier,'change_enabled'=>1,'field'=>'active')),
                                    'title' => $this->module->l('Click to enable','suppliers'),
                                    'success' => $this->module->l('Updated successfully','suppliers'),
                                    'enabled' => 0,
                                )
                            )  
                        );
                    }
                }else
                    $errors = $this->module->l('An error occurred while saving the supplier','suppliers');
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
        if(Tools::getValue('del')=='yes' && $id_supplier =Tools::getValue('id_supplier'))
        {
            $supplier = new Supplier($id_supplier);
            if(!Validate::isLoadedObject($supplier) || !$this->seller->checkHasSupplier($id_supplier,false))
                $this->_errors[] = $this->module->l('Supplier is not valid','suppliers');
            elseif($supplier->delete())
            {
                Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'ets_mp_supplier_seller` WHERE id_supplier='.(int)$supplier->id);
                $this->context->cookie->success_message = $this->module->l('Deleted successfully','suppliers');
                Tools::redirect($this->context->link->getModuleLink($this->module->name,'suppliers',array('list'=>1)));
            }
            else
                $this->_errors[] = $this->module->l('An error occurred while deleting the supplier','suppliers');
            
        }
        if(Tools::isSubmit('deletelogo') && ($id_supplier = Tools::getValue('id_supplier')))
        {
            $supplier = new Supplier($id_supplier);
            if(!Validate::isLoadedObject($supplier) || !$this->seller->checkHasSupplier($id_supplier,false))
                $this->_errors[] = $this->module->l('Suppliers are not valid','suppliers');
            else
            {
                if(file_exists(_PS_SUPP_IMG_DIR_ . $id_supplier . '.jpg')) {
                    @unlink(_PS_SUPP_IMG_DIR_ . $id_supplier . '.jpg');
                }
                $images_types = ImageType::getImagesTypes('manufacturers');
                foreach ($images_types as $image_type) {
                    if(file_exists( _PS_SUPP_IMG_DIR_ . $id_supplier . '-' . Tools::stripslashes($image_type['name']) . '.jpg'))
                        @unlink( _PS_SUPP_IMG_DIR_ . $id_supplier . '-' . Tools::stripslashes($image_type['name']) . '.jpg');
                    if(file_exists(_PS_SUPP_IMG_DIR_ . $id_supplier . '-' . Tools::stripslashes($image_type['name']) . '2x.jpg'))
                        @unlink(_PS_SUPP_IMG_DIR_ . $id_supplier . '-' . Tools::stripslashes($image_type['name']) . '2x.jpg');
                }
                $this->context->cookie->success_message = $this->module->l('Deleted logo successfully','suppliers');
                Tools::redirect($this->context->link->getModuleLink($this->module->name,'suppliers',array('list'=>1)));
            }
        }
        if(Tools::isSubmit('changeUserSuppliers'))
        {
            Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'ets_mp_seller` SET user_supplier="'.(int)Tools::getValue('user_supplier').'" WHERE id_customer="'.(int)$this->seller->id_customer.'"');
            die(
                Tools::jsonEncode(
                    array(
                        'success' => $this->module->l('Updated successfully','suppliers'),
                    )
                )
            );
        }
        if(Tools::isSubmit('submitSaveSupplier'))
        {
            $this->_submitSaveSupplier();
        }
    }
    public function initContent()
	{
		parent::initContent();
        if($this->context->cookie->success_message)
        {
            $this->_success = $this->context->cookie->success_message;
            $this->context->cookie->success_message ='';
        }    
        $this->context->controller->addJqueryPlugin('tagify');
        if(Tools::isSubmit('addnew') || Tools::isSubmit('editmp_supplier')){
             $display_form =true;
        }
        else
            $display_form = Configuration::get('ETS_MP_SELLER_USER_GLOBAL_SUPPLIER') ? false :true;
        $this->context->smarty->assign(
            array(
                'path' => $this->module->getBreadCrumb(),
                'breadcrumb' => $this->module->is17 ? $this->module->getBreadCrumb() : false, 
                'display_form' => $display_form,
                'html_content' => $this->_initContent(),
                'ets_seller' => $this->seller,
                '_errors' => $this->_errors ? $this->module->displayError($this->_errors):'',
                '_success' => $this->_success ? $this->module->displayConfirmation($this->_success):'',
            )
        );
        if($this->module->is17)
            $this->setTemplate('module:'.$this->module->name.'/views/templates/front/suppliers.tpl');      
        else        
            $this->setTemplate('suppliers_16.tpl'); 
    }
    public function renderSupplierForm()
    {
        $languages = Language::getLanguages(false);
        $valueFieldPost= array();
        if($id_supplier = Tools::getValue('id_supplier'))
            $supplier = new Supplier($id_supplier);
        else
            $supplier= new Supplier();
        if($languages)
        {
            foreach($languages as $language)
            {
                $valueFieldPost['description'][$language['id_lang']] = Tools::getValue('description_'.$language['id_lang'],$supplier->description[$language['id_lang']]);
                $valueFieldPost['meta_title'][$language['id_lang']] = Tools::getValue('meta_title_'.$language['id_lang'],$supplier->meta_title[$language['id_lang']]);
                $valueFieldPost['meta_description'][$language['id_lang']] = Tools::getValue('meta_description_'.$language['id_lang'],$supplier->meta_description[$language['id_lang']]);
                $valueFieldPost['meta_keywords'][$language['id_lang']] = Tools::getValue('meta_keywords_'.$language['id_lang'],$supplier->meta_keywords[$language['id_lang']]);
            }
        }
        $valueFieldPost['name'] = Tools::getValue('name',$supplier->name);
        $valueFieldPost['active'] =Tools::getValue('active',$supplier->active);
        if($id_supplier && ($id_address = Db::getInstance()->getValue('SELECT id_address FROM '._DB_PREFIX_.'address WHERE id_supplier="'.(int)$id_supplier.'"')))
        {
            $address = new Address($id_address);
        }
        else
            $address = new Address();
        $valueFieldPost['phone'] = Tools::getValue('phone',$address->phone);
        $valueFieldPost['phone_mobile'] = Tools::getValue('phone_mobile',$address->phone_mobile);
        $valueFieldPost['address1'] = Tools::getValue('address1',$address->address1);
        $valueFieldPost['address2'] = Tools::getValue('address2',$address->address2);
        $valueFieldPost['id_country'] =Tools::getValue('id_country',$address->id_country);
        $valueFieldPost['id_state'] = Tools::getValue('id_state',$address->id_state) ;
        $valueFieldPost['postcode'] = Tools::getValue('postcode',$address->postcode);
        $valueFieldPost['city'] =Tools::getValue('city',$address->city);
        if(version_compare(_PS_VERSION_, '1.7', '>='))
            $type_image= ImageType::getFormattedName('small');
        else
            $type_image= ImageType::getFormatedName('small');
        if($supplier->id && file_exists(_PS_SUPP_IMG_DIR_.(int)$supplier->id.'.jpg'))
        {
            if($this->module->is17)
                $valueFieldPost['logo'] = $this->context->link->getSupplierImageLink($supplier->id,$type_image);
            else
                $valueFieldPost['logo'] = $this->module->getBaseLink().'/img/su/'.(int)$supplier->id.'.jpg';
        }
        $countries = Db::getInstance()->executeS('SELECT c.id_country as id,cl.name as name FROM `'._DB_PREFIX_.'country` c,`'._DB_PREFIX_.'country_lang` cl,`'._DB_PREFIX_.'country_shop` cs WHERE c.active=1 AND c.id_country=cl.id_country AND c.id_country=cs.id_country AND cl.id_lang="'.(int)$this->context->language->id.'" AND cs.id_shop="'.(int)$this->context->shop->id.'" ORDER BY cl.name asc');
        $states = Db::getInstance()->executeS('SELECT id_state as id,name,id_country as parent FROM `'._DB_PREFIX_.'state` WHERE active=1 order by name asc');
        $fields=array(
            array(
                'type'=>'text',
                'name'=>'name',
                'label' => $this->module->l('Name','suppliers'),
                'required' => true,
                'desc' => $this->module->l('Invalid characters: <>;=#{} ','suppliers'),
            ),
            array(
                'type'=>'textarea',
                'name'=>'description',
                'label' => $this->module->l('Description','suppliers'),
                'lang' => true,
                'autoload_rte'=>true,
            ),
            array(
                'type'=>'text',
                'name'=>'phone',
                'label' => $this->module->l('Phone','suppliers'),
                'desc' => $this->module->l('Phone number for this supplier ','suppliers'),
            ),
            array(
                'type'=>'text',
                'name'=>'phone_mobile',
                'label' => $this->module->l('Mobile phone','suppliers'),
                'desc' => $this->module->l('Mobile phone number for this supplier','suppliers'),
            ),
            array(
                'type'=>'text',
                'name'=>'address1',
                'label' => $this->module->l('Address','suppliers'),
                'required' => true,
            ),
            array(
                'type'=>'text',
                'name'=>'address2',
                'label' => $this->module->l('Address(2)','suppliers'),
            ),
            array(
                'type'=>'text',
                'name'=>'postcode',
                'label' => $this->module->l('Zip/postal code','suppliers'),
            ),
            array(
                'type'=>'text',
                'name'=>'city',
                'label' => $this->module->l('City','suppliers'),
                'required' => true,
            ),
            array(
                'type'=>'select',
                'label'=>$this->module->l('Country','suppliers'),
                'name'=>'id_country',
                'required' => true, 
                'form_group_class' => 'js-manufacturer-address-country', 
                'values'=>$countries,
            ),
            array(
                'type'=>'select',
                'label'=>$this->module->l('State','suppliers'),
                'name'=>'id_state',
                'required' => true, 
                'form_group_class' => 'js-manufacturer-address-state', 
                'values'=>$states,
            ),
            array(
                'type' => 'file',
                'name' =>'logo',
                'link_del' => $this->context->link->getModuleLink($this->module->name,'suppliers',array('id_supplier'=>$supplier->id,'deletelogo'=>1)),
                'label' => $this->module->l('Logo','suppliers'),
                'desc' => sprintf($this->module->l('Accepted formats: jpg, jpeg, gif, png. Limit: %dMB','suppliers'),Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE')),
            ),
            array(
                'type'=>'text',
                'name'=>'meta_title',
                'label' => $this->module->l('Meta title','suppliers'),
                'lang' => true,
                'desc' => $this->module->l('Invalid characters: <>;=#{} ','suppliers'),
            ),
            array(
                'type'=>'textarea',
                'name'=>'meta_description',
                'label' => $this->module->l('Meta description','suppliers'),
                'lang' => true,
                'desc' => $this->module->l('Invalid characters: <>;=#{} ','suppliers'),
            ),
            array(
                'type'=>'tags',
                'name'=>'meta_keywords',
                'label' => $this->module->l('Meta keywords','suppliers'),
                'lang' => true,
                'desc' => $this->module->l('To add tags, click in the field, write something, and then press the "Enter" key. Invalid characters: <>;=#{} ','suppliers'),
            ),
            array(
                'type' =>'switch',
                'name' => 'active',
                'label'=> $this->module->l('Enabled','suppliers'),
            )
        );  
        $this->context->smarty->assign(
            array(
                'fields' => $fields,
                'languages' => $languages,
                'valueFieldPost' => $valueFieldPost,
                'id_lang_default' => Configuration::get('PS_LANG_DEFAULT'),
            )
        );
        $html_form= $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/form.tpl');
        $this->context->smarty->assign(
            array(
                'url_path' => $this->module->getBaseLink().'/modules/'.$this->module->name.'/',
                'html_form' => $html_form,
                'id_supplier' => Tools::getValue('id_supplier'),
                'link_cancel' => $this->context->link->getModuleLink($this->module->name,'suppliers',array('list'=>1))
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/supplier/supplier_form.tpl');
    }
    public function _initContent()
    {
        if(Tools::isSubmit('addnew') || Tools::isSubmit('editmp_supplier')){
            if(!Configuration::get('ETS_MP_SELLER_CREATE_SUPPLIER'))
                return $this->module->displayWarning($this->module->l('You do not have permission to create new supplier','suppliers'));
            return  $this->renderSupplierForm();
        }
        else
        {
            $fields_list = array(
                'id_supplier' => array(
                    'title' => $this->module->l('ID','suppliers'),
                    'width' => 40,
                    'type' => 'text',
                    'sort' => true,
                    'filter' => true,
                ),
                'logo'=> array(
                    'title' => $this->module->l('Logo','suppliers'),
                    'type'=>'text',
                    'strip_tag' => false,
                    'sort'=>false,
                    'filter'=> false,
                ),
                'name' => array(
                    'title' => $this->module->l('Name','suppliers'),
                    'type' => 'text',
                    'sort' => true,
                    'filter' => true,
                    'strip_tag' => false,
                ),
                'products' => array(
                    'title' => $this->module->l('Products','suppliers'),
                    'type' => 'text',
                    'sort' => true,
                ),
                'active' => array(
                    'title' => $this->module->l('Enabled','suppliers'),
                    'type' => 'active',
                    'sort' => true,
                    'filter' => true,
                    'strip_tag' => false,
                    'filter_list' => array(
                        'id_option' => 'active',
                        'value' => 'title',
                        'list' => array(
                            0 => array(
                                'active' => 1,
                                'title' => $this->module->l('Yes','suppliers')
                            ),
                            1 => array(
                                'active' => 0,
                                'title' => $this->module->l('No','suppliers')
                            )
                        )
                    )
                ),
            );
            //Filter
            $show_resset = false;
            $filter = "";
            $having="";
            if(Tools::getValue('id_supplier') && !Tools::getValue('del'))
            {
                $filter .= ' AND s.id_supplier="'.(int)Tools::getValue('id_supplier').'"';
                $show_resset = true;
            }
            if(Tools::getValue('name'))
            {
                $filter .=' AND s.name LIKE "%'.pSQL(Tools::getValue('name')).'%"';
                $show_resset = true;
            }
            if(trim(Tools::getValue('active'))!='')
            {
                $filter .= ' AND s.active="'.(int)Tools::getValue('active').'"';
                $show_resset=true;
            }
            //Sort
            $sort = "";
            if(Tools::getValue('sort','id_supplier'))
            {
                switch (Tools::getValue('sort','id_supplier')) {
                    case 'id_supplier':
                        $sort .='s.id_supplier';
                        break;
                    case 'name':
                        $sort .='s.name';
                        break;
                    case 'active':
                        $sort .='s.active';
                        break;
                }
                if($sort && ($sort_type=Tools::getValue('sort_type','desc')) && in_array($sort_type,array('asc','desc')))
                    $sort .= ' '.trim($sort_type);  
            }
            //Paggination
            $page = (int)Tools::getValue('page') && (int)Tools::getValue('page') > 0 ? (int)Tools::getValue('page') : 1;
            $totalRecords = (int) $this->seller->getSuppliers($filter,$having,0,0,'',true);
            $paggination = new Ets_mp_paggination_class();            
            $paggination->total = $totalRecords;
            $paggination->url =$this->context->link->getModuleLink($this->module->name,'suppliers',array('list'=>true, 'page'=>'_page_')).$this->module->getFilterParams($fields_list,'mp_supplier');
            $paggination->limit =  10;
            $totalPages = ceil($totalRecords / $paggination->limit);
            if($page > $totalPages)
                $page = $totalPages;
            $paggination->page = $page;
            $start = $paggination->limit * ($page - 1);
            if($start < 0)
                $start = 0;
            $suppliers = $this->seller->getSuppliers($filter, $having,$start,$paggination->limit,$sort,false);
            if($suppliers)
            {
                if(version_compare(_PS_VERSION_, '1.7', '>='))
                    $type_image= ImageType::getFormattedName('small');
                else
                    $type_image= ImageType::getFormatedName('small');
                foreach($suppliers as &$supplier)
                {
                    if(file_exists(_PS_SUPP_IMG_DIR_.$supplier['id_supplier'].'.jpg'))
                    {
                        if($this->module->is17)
                            $supplier['logo'] = '<'.'i'.'mg src="'.$this->context->link->getSupplierImageLink($supplier['id_supplier'],$type_image).'?time='.time().'"'.'>';
                        else
                            $supplier['logo'] = '<'.'i'.'mg src="'.$this->module->getBaseLink().'/img/su/'.$supplier['id_supplier'].'.jpg?time='.time().'"'.' style="width:98px">';
                    }   
                    if($supplier['id_seller'])
                    {
                        $supplier['action_edit']=true;
                        $supplier['child_view_url'] = $this->context->link->getSupplierLink($supplier['id_supplier']);
                    }
                    else
                    {
                        $supplier['action_edit'] = false;
                        $supplier['child_view_url'] = $this->context->link->getSupplierLink($supplier['id_supplier']);
                    }
                    $supplier['name'] ='<'.'a hr'.'ef="'.$this->context->link->getSupplierLink($supplier['id_supplier']).'">'.$supplier['name'].'<'.'/'.'a'.'>';
                }
            }
            $paggination->text =  $this->module->l('Showing {start} to {end} of {total} ({pages} Pages)','suppliers');
            $paggination->style_links = 'links';
            $paggination->style_results = 'results';
            $listData = array(
                'name' => 'mp_supplier',
                'actions' => array('view','edit', 'delete'),
                'currentIndex' => $this->context->link->getModuleLink($this->module->name,'suppliers',array('list'=>1)),
                'identifier' => 'id_supplier',
                'show_toolbar' => true,
                'show_action' => true,
                'title' => $this->module->l('Suppliers','suppliers'),
                'fields_list' => $fields_list,
                'field_values' => $suppliers,
                'paggination' => $paggination->render(),
                'filter_params' => $this->module->getFilterParams($fields_list,'mp_supplier'),
                'show_reset' =>$show_resset,
                'totalRecords' => $totalRecords,
                'sort'=> Tools::getValue('sort','id_supplier'),
                'show_add_new'=>  Configuration::get('ETS_MP_SELLER_CREATE_SUPPLIER') && $this->seller->user_supplier!=1 ? true:false,
                'link_new' => $this->context->link->getModuleLink($this->module->name,'suppliers',array('addnew'=>1)),
                'sort_type' => Tools::getValue('sort_type','desc'),
            );           
            return $this->module->renderList($listData);
        }
    }
    public function _submitSaveSupplier()
    {
        $languages = Language::getLanguages(false);
        $id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
        if($id_supplier = (int)Tools::getValue('id_supplier'))
        {
            $supplier = new Supplier($id_supplier);
            if(!Validate::isLoadedObject($supplier) || !$this->seller->checkHasSupplier($id_supplier,false))
                $this->_errors[] = $this->module->l('Supplier is not valid','suppliers');
        }
        else
            $supplier = new Supplier();
        if(!Tools::getValue('name'))
            $this->_errors[] = $this->module->l('Name is required','suppliers');
        elseif(Tools::getValue('name') && !Validate::isCatalogName(Tools::getValue('name')))
            $this->_errors[] = $this->module->l('Name is not valid','suppliers');
        else
            $supplier->name = Tools::getValue('name');
        foreach($languages as $language)
        {
            if(Tools::getValue('description_'.$language['id_lang']) && !Validate::isCleanHtml(Tools::getValue('description_'.$language['id_lang'])))
                $this->_errors[] = $this->module->l('Description is not valid in','suppliers').' '.$language['iso_code'];
            elseif(Tools::getValue('description_'.$language['id_lang']))
                $supplier->description[$language['id_lang']] = Tools::getValue('description_'.$language['id_lang']);
            else
                $supplier->description[$language['id_lang']] = Tools::getValue('description_'.$id_lang_default);
            if(($meta_title= Tools::getValue('meta_title_'.$language['id_lang'])) && !Validate::isGenericName($meta_title) )
                $this->_errors[] = $this->module->l('Meta title is not valid in','suppliers').' '.$language['iso_code'];
            elseif($meta_title)
                $supplier->meta_title[$language['id_lang']] = $meta_title;
            else
                $supplier->meta_title[$language['id_lang']] = Tools::getValue('meta_title_'.$id_lang_default);
            if(($meta_description= Tools::getValue('meta_description_'.$language['id_lang'])) && !Validate::isGenericName($meta_description))
                $this->_errors[] = $this->module->l('Meta description is not valid in','suppliers').' '.$language['iso_code'];
            elseif($meta_description)
                $supplier->meta_description[$language['id_lang']] = $meta_description;
            else
                $supplier->meta_description[$language['id_lang']] = Tools::getValue('meta_description_'.$id_lang_default);
            if(($meta_keywords= Tools::getValue('meta_keywords_'.$language['id_lang'])) && !Validate::isGenericName($meta_keywords))
                $this->_errors[] = $this->module->l('Meta keywords is not valid in','suppliers').' '.$language['iso_code'];
            elseif($meta_keywords)
                $supplier->meta_keywords[$language['id_lang']] = $meta_keywords;
            else
                $supplier->meta_keywords[$language['id_lang']] = Tools::getValue('meta_keywords_'.$id_lang_default);
        }
        $supplier->active = (int)Tools::getValue('active');
        if(isset($_FILES['logo']) && isset($_FILES['logo']['name']) && $_FILES['logo']['name'] && isset($_FILES['logo']['size']) && $_FILES['logo']['size'])
        {
            /** _ARM_ SBA Concept */
            $_FILES['logo']['name'] = preg_replace('/[^a-zA-Z0-9_.-]/', '-', $_FILES['logo']['name']);

            $this->module->validateFile($_FILES['logo']['name'],$_FILES['logo']['size'],$this->_errors,array('jpg', 'gif', 'jpeg', 'png'));
        }
        if(!($address1 = Tools::getValue('address1')))
            $this->_errors[] = $this->module->l('Address is required','suppliers');
        elseif(!Validate::isAddress($address1))
            $this->_errors[] = $this->module->l('Address is not valid','suppliers');
        if(($address2 = Tools::getValue('address2')) && !Validate::isAddress($address2))
            $this->_errors[] = $this->module->l('Address(2) is not valid','suppliers');
        if(($phone = Tools::getValue('phone')) && !Validate::isPhoneNumber($phone))
            $this->_errors[] = $this->module->l('Phone is not valid','suppliers');
        if(($phone_mobile = Tools::getValue('phone_mobile')) && !Validate::isPhoneNumber($phone_mobile))
            $this->_errors[] = $this->module->l('Mobile phone is not valid','suppliers');
        if(!($id_country = Tools::getValue('id_country')))
            $this->_errors[] = $this->module->l('Country is required','suppliers');
        elseif(($country = new Country($id_country)) && (!Validate::isLoadedObject($country) || !$country->active))
            $this->_errors[] = $this->module->l('Country is not valid','suppliers');
        if(($id_state = Tools::getValue('id_state')) && ($state = new State($id_state)) && $state->id_country == $id_country && (!Validate::isLoadedObject($state) || !$state->active) )
            $this->_errors[] = $this->module->l('State is not valid','suppliers');
        if(!($city = Tools::getValue('city')))
            $this->_errors[] = $this->module->l('City is required','suppliers');
        elseif(!Validate::isCityName($city))
            $this->_errors[] = $this->module->l('City is not valid','suppliers');
        if(($postcode = Tools::getValue('postcode')) && !Validate::isPostCode($postcode))
            $this->_errors[] = $this->module->l('Zip/postal code is not valid','suppliers');
        elseif($postcode && !$country->checkZipCode($postcode))
            $this->_errors[] = sprintf($this->module->l('Zip/postal code is not valid - should look like "%s"','suppliers'),$country->zip_code_format);
        if(!$this->_errors)
        {
            if($supplier->id)
            {
                if($supplier->update())
                {
                    $this->submitAddressSupplier($supplier);
                    $this->supplierImageUpload($supplier->id);
                    if(!$this->_errors)
                    {
                        $this->context->cookie->success_message = $this->module->l('Updated successfully','suppliers');
                        Tools::redirect($this->context->link->getModuleLink($this->module->name,'suppliers',array('list'=>1)));
                    }
                }
                else
                    $this->_errors[] = $this->module->l('An error occurred while saving the supplier','suppliers');
            }
            elseif($supplier->add())
            {
                $this->submitAddressSupplier($supplier);
                $this->supplierImageUpload($supplier->id);
                if(!$this->_errors)
                {
                    Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'ets_mp_supplier_seller`(id_customer,id_supplier) VALUES("'.(int)$this->seller->id_customer.'","'.(int)$supplier->id.'")');
                    $this->context->cookie->success_message = $this->module->l('Added successfully','suppliers');
                    Tools::redirect($this->context->link->getModuleLink($this->module->name,'suppliers',array('list'=>1)));
                }
            } 
            else
                $this->_errors[] = $this->module->l('An error occurred while saving the supplier','suppliers');   
        } 
    }
    public function submitAddressSupplier($supplier)
    {
        if($id_address = Db::getInstance()->getValue('SELECT id_address FROM '._DB_PREFIX_.'address WHERE id_supplier="'.(int)$supplier->id.'"'))
            $address = new Address($id_address);
        else
            $address = new Address();
        $address->alias = $supplier->name;
        $address->firstname = 'supplier';
        $address->lastname = 'supplier';
        $address->id_supplier = $supplier->id;
        $address->phone = Tools::getValue('phone');
        $address->phone_mobile = Tools::getValue('phone_mobile');
        $address->address1 = Tools::getValue('address1');
        $address->address2 = Tools::getValue('address2');
        $address->id_country = Tools::getValue('id_country');
        $address->city = Tools::getValue('city');
        $address->postcode = Tools::getValue('postcode');
        if(($id_state = Tools::getValue('id_state')) && ($state = new State($id_state)) && $state->id_country == $address->id_country )
            $address->id_state = $id_state;
        else
            $address->id_state =null;
        
        if($address->id)
        {
            if(!$address->update())
                $this->_errors[] = $this->module->l('An error occurred while saving the supplier address','suppliers');
        }
        else
        {
            if(!$address->add())
                $this->_errors[] = $this->module->l('An error occurred while creating the supplier address','suppliers');
        }
    }
    public function supplierImageUpload($id_supplier)
    {
        if(isset($_FILES['logo']) && isset($_FILES['logo']['name']) && $_FILES['logo']['name'])
        {
            if(file_exists(_PS_SUPP_IMG_DIR_.$id_supplier.'.jpg'))
            {
                @unlink(_PS_SUPP_IMG_DIR_.$id_supplier.'.jpg');
            }
            $temp_name = tempnam(_PS_TMP_IMG_DIR_, 'PS');    				
			if(move_uploaded_file($_FILES['logo']['tmp_name'], $temp_name))
            {
                $type = Tools::strtolower(Tools::substr(strrchr($_FILES['logo']['name'], '.'), 1));
                if(ImageManager::resize($temp_name, _PS_SUPP_IMG_DIR_.$id_supplier.'.jpg', null, null, $type))
                {
                    $res=true;
                    $generate_hight_dpi_images= (bool) Configuration::get('PS_HIGHT_DPI');
                    if(file_exists(_PS_SUPP_IMG_DIR_ . $id_supplier . '.jpg')) {
                        $images_types = ImageType::getImagesTypes('suppliers');
                        foreach ($images_types as $image_type) {
                            $res &= ImageManager::resize(
                                _PS_SUPP_IMG_DIR_ . $id_supplier . '.jpg',
                                _PS_SUPP_IMG_DIR_ . $id_supplier . '-' . Tools::stripslashes($image_type['name']) . '.jpg',
                                (int) $image_type['width'],
                                (int) $image_type['height']
                            );
            
                            if ($generate_hight_dpi_images) {
                                $res &= ImageManager::resize(
                                    _PS_SUPP_IMG_DIR_ . $id_supplier . '.jpg',
                                    _PS_SUPP_IMG_DIR_ . $id_supplier . '-' . Tools::stripslashes($image_type['name']) . '2x.jpg',
                                    (int) $image_type['width'] * 2,
                                    (int) $image_type['height'] * 2
                                );
                            }
                        }
                        $current_logo_file = _PS_TMP_IMG_DIR_ . 'supplier_mini_' . $id_supplier . '_' . $this->context->shop->id . '.jpg';
                        if ($res && file_exists($current_logo_file)) {
                            unlink($current_logo_file);
                        }
                    }
                    return $res;
                }
                else
                  $this->_errors[] = $this->module->l('An error occurred while uploading the supplier logo','suppliers');  
                
            }
            else
                $this->_errors[] = $this->module->l('An error occurred while uploading the supplier logo','suppliers');
        }
    }
 }