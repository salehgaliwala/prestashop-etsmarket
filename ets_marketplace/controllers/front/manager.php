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
class Ets_MarketPlaceManagerModuleFrontController extends ModuleFrontController
{
    public $seller;
    public $_success;
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
            Tools::redirect($this->context->link->getModuleLink($this->module->name,'myseller'));
        if($this->context->cookie->success)
        {
            $this->_success = $this->context->cookie->success;
            $this->context->cookie->success= '';
        }    
        if(Tools::isSubmit('searchCustomerByEmail') && $email= Tools::getValue('email'))
        {
            $customer_name = Db::getInstance()->getValue('SELECT concat(firstname," ",lastname) FROM `'._DB_PREFIX_.'customer` WHERE email="'.pSQL($email).'" AND active=1 AND deleted=0 AND is_guest=0');
            die(
                Tools::jsonEncode(
                    array(
                        'customer_name' => $this->module->l('Customer name:','manager').' '.$customer_name,
                    )
                )
            );
        }
    } 
    public function initContent()
	{
		parent::initContent();
        if(Tools::getValue('submitSaveManagerShop'))
        {
            if(!Tools::getValue('email'))
                $this->module->_errors[] = $this->module->l('Email is required','manager');
            elseif(!Validate::isEmail(Tools::getValue('email')))
                $this->module->_errors[] = $this->module->l('Email is not valid','manager');
            elseif(!Db::getInstance()->getValue('SELECT id_customer FROM `'._DB_PREFIX_.'customer` WHERE email="'.pSQL(Tools::getValue('email')).'" AND active=1 AND deleted=0 AND is_guest=0'))
                $this->module->_errors[] = $this->module->l('There is no existing account corresponding to the entered email','manager');
            elseif($this->_checkSellerByEmail(Tools::getValue('email')))
                $this->module->_errors[] = $this->module->l('There is already an existing seller account with the entered email','manager');
            elseif(Db::getInstance()->getValue('SELECT email FROM `'._DB_PREFIX_.'ets_mp_seller_manager` WHERE email="'.pSQl(Tools::getValue('email')).'" AND id_ets_mp_seller_manager!="'.(int)Tools::getValue('id_ets_mp_seller_manager').'"'))
                $this->module->_errors[] = $this->module->l('This user has been assigned with shop manager role','manager');
            if(!Tools::getValue('permission'))
                $this->module->_errors[]= $this->module->l('Permission is required','manager');
            if(Tools::getValue('id_ets_mp_seller_manager') && !Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'ets_mp_seller_manager` WHERE id_customer="'.(int)$this->context->customer->id.'" AND id_ets_mp_seller_manager='.(int)Tools::getValue('id_ets_mp_seller_manager')))
                $this->module->_errors[]= $this->module->l('You do not have permission','manager');
            if(!$this->module->_errors)
            {
                if($id_manager = Tools::getValue('id_ets_mp_seller_manager'))
                    $manager = new Ets_mp_manager($id_manager);
                else
                    $manager = new Ets_mp_manager();
                
                $manager->id_user = Db::getInstance()->getValue('SELECT id_customer FROM `'._DB_PREFIX_.'customer` WHERE email="'.pSQL(Tools::getValue('email')).'"');
                $user = new Customer($manager->id_user);
                $manager->permission = implode(',',Tools::getValue('permission'));
                if(!$manager->id)
                {
                    $manager->active = -1;
                    $manager->id_customer = $this->seller->id_customer;
                    $manager->email = Tools::getValue('email');
                }
                $manager->delete_product = Tools::getValue('delete_product');
                $template_vars =array(
                        '{customer_name}' => $user->firstname.' '.$user->lastname,
                        '{seller_name}' => $this->seller->seller_name,
                        '{permission}' => $this->displayPermission($manager->permission),
                        '{link_account}' => $this->context->link->getPageLink('my-account'),
                );
                if($manager->id)
                {
                    if($manager->update())
                    {
                        $success = $this->module->l('Updated manager successfully','manager');
                    }
                    else
                    {
                        die(Tools::jsonEncode(
                            array(
                                'errors' => $this->module->l('An error occurred while saving the manager','manager'),
                            )
                        ));
                    }
                    
                }
                else
                {
                    if($manager->add())
                    {
                        $subjects = array(
                            'translation' => $this->module->l('Invitation to become store manager','manager'),
                            'origin'=> 'Invitation to become store manager',
                            'specific'=>'manager'
                        );
                        Ets_marketplace::sendMail('shop_manager',$template_vars,$manager->email,$subjects,$user->firstname.' '.$user->lastname);
                        $success = $this->module->l('Added manager successfully','manager');
                    }
                    else
                    {
                        die(Tools::jsonEncode(
                            array(
                                'errors' =>  $this->module->l('An error occurred while creating the manager','manager'),
                            )
                        ));
                    }
                }
                if(isset($success) && $success)
                {
                    if($manager->active==-1)
                        $active = '<'.'span'.' class="ets_mp_status pending">'.$this->module->l('Pending','manager').'<'.'/'.'span'.'>'; 
                    elseif($manager->active==0)
                        $active = '<'.'span'.' class="ets_mp_status declined">'.$this->module->l('Declined','manager').'<'.'/'.'span'.'>';
                    elseif($manager->active==1)
                        $active = '<'.'span'.' class="ets_mp_status approved">'.$this->module->l('Approved','manager').'<'.'/'.'span'.'>';
                    die(Tools::jsonEncode(
                        array(
                            'success' => $success,
                            'id_manager' => $manager->id,
                            'name' => $user->firstname.' '.$user->lastname,
                            'email' => $manager->email,
                            'permission' => $this->displayPermission($manager->permission), 
                            'active' => $active,
                            'link_edit' => $this->context->link->getModuleLink($this->module->name,'manager',array('editmp_manager'=>1,'id_ets_mp_seller_manager'=>$manager->id)),
                            'link_delete' => $this->context->link->getModuleLink($this->module->name,'manager',array('del'=>'yes','id_ets_mp_seller_manager'=>$manager->id)),
                        )
                    ));
                }
            }
            else
            {
                die(
                    Tools::jsonEncode(
                        array(
                            'errors' => $this->module->displayError($this->module->_errors),
                        )
                    )  
                );
            }
        }
        if(Tools::getValue('del')=='yes' && $id_manager = Tools::getValue('id_ets_mp_seller_manager'))
        {
            $manager = new Ets_mp_manager($id_manager);
            if($manager->id_customer == $this->context->customer->id)
            {
                
                if($manager->delete())
                {
                    $this->context->cookie->success = $this->module->l('Deleted successfully');
                    Tools::redirect($this->context->link->getModuleLink($this->module->name,'manager'));
                }
                else
                    $this->module->_errors[] = $this->module->l('An error occurred while deleting the manager','manager');
            }
            else
                die($this->module->l('You do not have permission','manager'));
        }
        $this->context->smarty->assign(
            array(
                'path' => $this->module->getBreadCrumb(),
                'breadcrumb' => $this->module->is17 ? $this->module->getBreadCrumb() : false,
                'html_content' => $this->_initContent(),
            )
        );
        if($this->module->is17)
            $this->setTemplate('module:'.$this->module->name.'/views/templates/front/manager.tpl');      
        else        
            $this->setTemplate('manager_16.tpl');
    }
    public function displayPermission($permission)
    {
        if(Configuration::get('ETS_MP_SELLER_PRODUCT_TYPE_SUBMIT'))
            $product_types = explode(',',Configuration::get('ETS_MP_SELLER_PRODUCT_TYPE_SUBMIT'));
        else
            $product_types = array();
        $permissions = array(
            'dashboard'=>array(
                'name'=> $this->module->l('Dashboard','manager') ,
                'id'=>'dashboard'
            ) ,
            'orders'=>array(
                'name'=> $this->module->l('Orders','manager'),
                'id'=>'orders'
            ),
            'products'=>array(
                'name'=> $this->module->l('Products','manager'),
                'id'=>'products'
            ),
            'stock'=>array(
                'name'=> $this->module->l('Stock','manager'),
                'id'=>'stock'
            ),
            'messages'=>array(
                'name'=> $this->module->l('Messages','manager'),
                'id'=>'messages'
            ),
            'commissions'=>array(
                'name'=> $this->module->l('Commissions','manager'),
                'id'=>'commissions'
            ),
            'billing'=>array(
                'name'=> $this->module->l('Membership','manager') ,
                'id'=>'billing'
            ),
            'attributes'=> array(
                'name'=> in_array('standard_product',$product_types) && $this->module->_use_attribute && $this->module->_use_feature ?  $this->module->l('Attributes and features','manager') : ($this->module->_use_feature ? $this->module->l('Features','manager') : $this->module->l('Attributes','manager')),
                'id'=>'attributes'
            ),
            'brands'=>array(
                'name'=> $this->module->l('Brands','manager') ,
                'id'=>'brands'
            ),
            'suppliers'=>array(
                'name'=> $this->module->l('Suppliers','manager') ,
                'id'=>'suppliers'
            ),
            'ratings'=>array(
                'name'=> $this->module->l('Ratings','manager') ,
                'id'=>'ratings'
            ),
            'profile'=> array(
                'name'=> $this->module->l('Profile','manager') ,
                'id'=>'profile'
            ),
            'carrier' => array(
                'name'=> $this->module->l('Carriers','manager') ,
                'id'=>'carrier'
            ),
            'discount' => array(
                'name'=> $this->module->l('Discounts','manager') ,
                'id'=>'discount'
            ),
        );
        if(!Configuration::get('ETS_MP_SELLER_CAN_CREATE_VOUCHER'))
            unset($permissions['discount']);
        if(!(in_array('standard_product',$product_types) && $this->module->_use_attribute) && !$this->module->_use_feature)
            unset($permissions['attributes']);
        if(!Configuration::get('ETS_MP_SELLER_CREATE_BRAND') && !Configuration::get('ETS_MP_SELLER_USER_GLOBAL_BRAND'))
            unset($permissions['brands']);
        if(!Configuration::get('ETS_MP_SELLER_CREATE_SUPPLIER') && !Configuration::get('ETS_MP_SELLER_USER_GLOBAL_SUPPLIER'))
            unset($permissions['suppliers']);
        if(!Configuration::get('ETS_MP_SELLER_CREATE_SHIPPING') && !Configuration::get('ETS_MP_SELLER_USER_GLOBAL_SHIPPING'))
            unset($permissions['carrier']);
        if(!Configuration::get('ETS_MP_ALLOW_CONVERT_TO_VOUCHER'))
            unset($permissions['voucher']);
        if(!Configuration::get('ETS_MP_ALLOW_WITHDRAW'))
            unset($permissions['withdraw']);
        if(!Module::isEnabled('productcomments') && !Module::isEnabled('ets_productcomments'))
            unset($permissions['ratings']);
        $this->context->smarty->assign(
            array(
                'user_permissions' => explode(',',$permission),
                'permissions' => $permissions
            )
        );  
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/shop/manager_permission.tpl');
    }
    public function _initContent()
    {
        if(Tools::isSubmit('addnew') || (Tools::isSubmit('editmp_manager') && Tools::getValue('id_ets_mp_seller_manager')))
        {
            if(!Tools::isSubmit('ajax'))
                return $this->_renderManagerForm();
            else
            {
                die(
                    Tools::jsonEncode(
                        array(
                            'form_html'=> $this->_renderManagerForm(),
                        )
                    )
                );
            }
        }
        $fields_list = array(
            'id_ets_mp_seller_manager' => array(
                'title' => $this->module->l('ID','manager'),
                'width' => 40,
                'type' => 'text',
                'sort' => true,
                'filter' => true,
            ),
            'name'=>array(
                'title' => $this->module->l('Name','manager'),
                'type'=> 'text',
                'sort' => true,
                'filter' => true,
            ),
            'email'=>array(
                'title' => $this->module->l('Email','manager'),
                'type'=> 'text',
                'sort' => true,
                'filter' => true,
            ),
            'permission'=>array(
                'title' => $this->module->l('Permissions','manager'),
                'type'=> 'text',
                'strip_tag' => false,
            ),
            'active' => array(
                'title' => $this->module->l('Status','manager'),
                'type' => 'select',
                'sort' => true,
                'filter' => true,
                'strip_tag' => false,
                'filter_list' => array(
                    'id_option' => 'active',
                    'value' => 'title',
                    'list' => array(
                        0 => array(
                            'active' => -1,
                            'title' => $this->module->l('Pending','manager')
                        ),
                        1 => array(
                            'active' => 0,
                            'title' => $this->module->l('Declined','manager')
                        ),
                        2 => array(
                            'active' => 1,
                            'title' => $this->module->l('Accepted','manager')
                        )
                    )
                )
            ),
        );
        $show_resset = false;
        $filter = "";
        if(trim(Tools::getValue('id_ets_mp_seller_manager')) && !Tools::isSubmit('del'))
        {
            $show_resset = true;
            $filter .=' AND m.id_ets_mp_seller_manager="'.(int)Tools::getValue('id_ets_mp_seller_manager').'"';            
        }
        if(trim(Tools::getValue('name')))
        {
            $filter .=' AND CONTCAT(c.firtname," ",c.lastname) like "%'.pSQL(Tools::getValue('name')).'%"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('email')))
        {
            $filter .=' AND m.email like "%'.pSQL(Tools::getValue('email')).'%"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('active'))!='')
        {
            $show_resset = true;
            $filter .=' AND m.active="'.(int)Tools::getValue('active').'"';            
        }
        $sort = "";
        if(Tools::getValue('sort'))
        {
            switch (Tools::getValue('sort')) {
                case 'id_ets_mp_seller_manager':
                    $sort .='m.id_ets_mp_seller_manager';
                    break;
                case 'name':
                    $sort .='name';
                    break;
                case 'active':
                    $sort .='m.active';
                    break;
                case 'email':
                    $sort .='m.email';
                    break;
            }
            if($sort && ($sort_type=Tools::getValue('sort_type')) && in_array($sort_type,array('asc','desc')))
                $sort .= ' '.trim($sort_type);
        }
        $page = (int)Tools::getValue('page') && (int)Tools::getValue('page') > 0 ? (int)Tools::getValue('page') : 1;
        $totalRecords = (int)$this->seller->getUserManagers($filter,0,0,'',true);
        $paggination = new Ets_mp_paggination_class();            
        $paggination->total = $totalRecords;
        $paggination->url = $this->context->link->getModuleLink($this->module->name,'manager',array('list'=>1,'page'=>'_page_')).$this->module->getFilterParams($fields_list,'mp_manager');
        $paggination->limit =  10;
        $totalPages = ceil($totalRecords / $paggination->limit);
        if($page > $totalPages)
            $page = $totalPages;
        $paggination->page = $page;
        $start = $paggination->limit * ($page - 1);
        if($start < 0)
            $start = 0;
        $userManagers = $this->seller->getUserManagers($filter,$start,$paggination->limit,$sort,false);
        if($userManagers)
        {
            foreach($userManagers as &$userManager)
            {
                $userManager['permission'] = $this->displayPermission($userManager['permission']);
                if($userManager['active']==-1)
                {
                    $userManager['active'] = '<'.'span'.' class="ets_mp_status pending">'.$this->module->l('Pending','manager').'<'.'/'.'span'.'>'; 
                }
                elseif($userManager['active']==0)
                    $userManager['active'] = '<'.'span'.' class="ets_mp_status declined">'.$this->module->l('Declined','manager').'<'.'/'.'span'.'>';
                elseif($userManager['active']==1)
                    $userManager['active'] = '<'.'span'.' class="ets_mp_status approved">'.$this->module->l('Accepted','manager').'<'.'/'.'span'.'>';
            }
        }
        $paggination->text =  $this->module->l('Showing {start} to {end} of {total} ({pages} Pages)','manager');
        $paggination->style_links = 'links';
        $paggination->style_results = 'results';
        $listData = array(
            'name' => 'mp_manager',
            'actions' => array('view','delete'),
            'currentIndex' => $this->context->link->getModuleLink($this->module->name,'manager',array('list'=>1)),
            'identifier' => 'id_ets_mp_seller_manager',
            'show_toolbar' => true,
            'show_action' =>true,
            'title' => $this->module->l('Shop managers','manager'),
            'fields_list' => $fields_list,
            'field_values' => $userManagers,
            'paggination' => $paggination->render(),
            'filter_params' => $this->module->getFilterParams($fields_list,'mp_manager'),
            'show_reset' =>$show_resset,
            'totalRecords' => $totalRecords,
            'sort'=> Tools::getValue('sort','id_ets_mp_seller_manager'),
            'show_add_new'=> true,
            'link_new' => $this->context->link->getModuleLink($this->module->name,'manager',array('addnew'=>1,'ajax'=>1)),
            'sort_type' => Tools::getValue('sort_type','desc'),
        );            
        return ($this->_success ? $this->module->displayConfirmation($this->_success):'').($this->module->_errors ? $this->module->displayError($this->module->_errors):'').$this->module->renderList($listData);
    }
    public function _renderManagerForm()
    {
        if($id_manager = (int) Tools::getValue('id_ets_mp_seller_manager'))
            $manager = new Ets_mp_manager($id_manager);
        else
            $manager = new Ets_mp_manager();
        $valueFieldPost= array();
        $valueFieldPost['email'] = Tools::getValue('email',$manager->email);
        $valueFieldPost['permission'] = Tools::getValue('permission',$manager->permission ? explode(',',$manager->permission):array());
        $valueFieldPost['delete_product'] = Tools::getValue('delete_product',$manager->delete_product);
        if(Configuration::get('ETS_MP_SELLER_PRODUCT_TYPE_SUBMIT'))
            $product_types = explode(',',Configuration::get('ETS_MP_SELLER_PRODUCT_TYPE_SUBMIT'));
        else
            $product_types = array();
        $permissions = array(
            'dashboard'=> array(
                'name'=> $this->module->l('Dashboard','manager') ,
                'id'=>'dashboard'
            ) ,
            'orders'=> array(
                'name'=> $this->module->l('Orders','manager'),
                'id'=>'orders'
            ),
            'products'=> array(
                'name'=> $this->module->l('Products','manager'),
                'id'=>'products'
            ),
            'stock'=>array(
                'name'=> $this->module->l('Stock','manager'),
                'id'=>'stock'
            ),
            'messages' => array(
                'name'=> $this->module->l('Messages','manager'),
                'id'=>'messages'
            ),
            'commissions'=> array(
                'name'=> $this->module->l('Commissions','manager'),
                'id'=>'commissions'
            ),
            'attributes'=> array(
                'name'=> in_array('standard_product',$product_types) && $this->module->_use_attribute && $this->module->_use_feature ?  $this->module->l('Attributes and features','manager') : ($this->module->_use_feature ? $this->module->l('Features','manager') : $this->module->l('Attributes','manager')),
                'id'=>'attributes'
            ),
            'discount' => array(
                'name'=> $this->module->l('Discounts','manager') ,
                'id'=>'discount'
            ),
            'carrier'=> array(
                'name'=> $this->module->l('Carriers','manager') ,
                'id'=>'carrier'
            ),
            'brands'=> array(
                'name'=> $this->module->l('Brands','manager') ,
                'id'=>'brands'
            ),
            'suppliers'=> array(
                'name'=> $this->module->l('Suppliers','manager') ,
                'id'=>'suppliers'
            ),
            'ratings' => array(
                'name' => $this->module->l('Ratings','manager'),
                'id' => 'ratings',
            ),
            'billing' => array(
                'name'=> $this->module->l('Membership','manager') ,
                'id'=>'billing'
            ),
            'profile'=> array(
                'name'=> $this->module->l('Profile','manager') ,
                'id'=>'profile'
            ),
        );
        if(!Configuration::get('ETS_MP_SELLER_CAN_CREATE_VOUCHER'))
            unset($permissions['discount']);
        if(!Configuration::get('ETS_MP_SELLER_CREATE_BRAND') && !Configuration::get('ETS_MP_SELLER_USER_GLOBAL_BRAND'))
            unset($permissions['brands']);
        if(!Configuration::get('ETS_MP_SELLER_CREATE_SUPPLIER') && !Configuration::get('ETS_MP_SELLER_USER_GLOBAL_SUPPLIER'))
            unset($permissions['suppliers']);
        if(!Configuration::get('ETS_MP_SELLER_CREATE_SHIPPING') && !Configuration::get('ETS_MP_SELLER_USER_GLOBAL_SHIPPING'))
            unset($permissions['carrier']);
        if(!Configuration::get('ETS_MP_ALLOW_CONVERT_TO_VOUCHER'))
            unset($permissions['voucher']);
        if(!Configuration::get('ETS_MP_ALLOW_WITHDRAW'))
            unset($permissions['withdraw']);
        if(!(in_array('standard_product',$product_types) && $this->module->_use_attribute) && !$this->module->_use_feature)
            unset($permissions['attributes']);
        if(!Module::isEnabled('productcomments') && !Module::isEnabled('ets_productcomments'))
            unset($permissions['ratings']);
        $fields = array(
            array(
                'type' => 'text',
                'name' =>'email',
                'label' => $this->module->l('Email','manager'),
                'required' => true,
                'readonly'=> $manager->id ? true : false,
                'autocomplete' => false,
            ),
            array(
                'type' =>'checkbox',
                'name' => 'permission',
                'label' => $this->module->l('Permissions','manager'),
                'values' => $permissions,
                'required' => true,
            ),
            array(
                'type'=>'switch',
                'name' => 'delete_product',
                'label' => $this->module->l('Do you want to allow this account to delete product?','manager'),
                'form_group_class' => 'delete_product'
            )
        );
        $this->context->smarty->assign(
            array(
                'fields' => $fields,
                'languages' => Language::getLanguages(false),
                'valueFieldPost' => $valueFieldPost,
                'id_lang_default' => Configuration::get('PS_LANG_DEFAULT'),
            )
        );
        $html_form= $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/form.tpl');
        $this->context->smarty->assign(
            array(
                'html_form' => $html_form,
                'id_ets_mp_seller_manager' => (int)Tools::getValue('id_ets_mp_seller_manager'),
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/manager_form.tpl');
    }
    public function _checkSellerByEmail($email)
    {
        $sql1 = 'SELECT r.id_customer FROM `'._DB_PREFIX_.'ets_mp_registration` r
        INNER JOIN `'._DB_PREFIX_.'customer` c ON (c.id_customer=r.id_customer)
        WHERE c.email="'.pSQL($email).'"';
        $sql2 = 'SELECT s.id_customer FROM `'._DB_PREFIX_.'ets_mp_seller` s
        INNER JOIN `'._DB_PREFIX_.'customer` c ON (c.id_customer=s.id_customer)
        WHERE c.email="'.pSQL($email).'"';
        return Db::getInstance()->getValue($sql1) || Db::getInstance()->getValue($sql2);
    }
 }