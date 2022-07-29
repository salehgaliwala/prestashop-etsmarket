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
class Ets_MarketPlaceCommissionsModuleFrontController extends ModuleFrontController
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
        if(!$this->context->customer->logged || !($this->seller = $this->module->_getSeller(true)) )
            Tools::redirect($this->context->link->getModuleLink($this->module->name,'myseller'));
        if(!$this->module->_checkPermissionPage($this->seller))
            die($this->module->l('You do not have permission','commissions'));
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
            $this->setTemplate('module:'.$this->module->name.'/views/templates/front/commissions.tpl');      
        else        
            $this->setTemplate('commissions_16.tpl'); 
    }
    public function _initContent()
    {
        $commistion_status=array(
            array(
                'id' => '-1',
                'name' => $this->module->l('Pending','commissions'),
            ),
            array(
                'id' => '0',
                'name' => $this->module->l('Canceled','commissions')
            ),
            array(
                'id' => '1',
                'name' => $this->module->l('Approved','commissions')
            ),
            array(
                'id' =>'refunded' ,// 0,
                'name' => $this->module->l('Refunded','commissions')
            ),
            array(
                'id' =>'deducted' , // 1,
                'name' => $this->module->l('Deducted','commissions')
            ),
        );
        $fields_list = array(
            'reference' => array(
                'title' => $this->module->l('Reference','commissions'),
                'width' => 40,
                'type' => 'text',
                'sort' => true,
                'filter' => true,
            ),
            'commission' => array(
                'title' => $this->module->l('Commissions','commissions'),
                'type' => 'int',
                'sort' => true,
                'filter' => true,
                'strip_tag' => false,
            ),
            'product_name' => array(
                'title' => $this->module->l('Product','commissions'),
                'type'=> 'text',
                'sort' => true,
                'filter' => true,
                'strip_tag'=> false,
            ),
            'price' => array(
                'title' => $this->module->l('Price','commissions'),
                'type'=> 'int',
                'sort' => true,
                'filter' => true,
            ),
            /*'quantity' => array(
                'title' => $this->module->l('Quantity','commissions'),
                'type'=> 'int',
                'sort' => true,
                'filter' => true,
            ),*/
            'status' => array(
                'title' => $this->module->l('Status','commissions'),
                'type' => 'select',
                'sort' => true,
                'filter' => true,
                'strip_tag' => false,
                'filter_list' => array(
                    'list' => $commistion_status,
                    'id_option' => 'id',
                    'value' => 'name',
                ),
            ),
            'date_add' => array(
                'title' => $this->module->l('Date','commissions'),
                'type' => 'date',
                'sort' => true,
                'filter' => true,
                'class' => 'text-center'
            ),
        );
        //Filter
        $show_resset = false;
        $filter = "";
        $having = "";
        if(Tools::getValue('reference'))
        {
            $filter .= ' AND sc.reference like "%'.pSQL(Tools::getValue('reference')).'%"';
            $show_resset = true;
        }
        if(Tools::getValue('customer_name'))
        {
            $filter .= ' AND CONCAT(c.firstname," ",c.lastname) like "%'.pSQL(Tools::getValue('customer_name')).'%"';
            $show_resset = true;   
        }
        if(Tools::getValue('id_order'))
        {
            $filter .=' AND sc.id_order ="'.(float)Tools::getValue('id_order').'"';
            $show_resset = true;
        }
        if(Tools::getValue('product_name'))
        {
            $filter .= ' AND sc.product_name LIKE "%'.pSQL(Tools::getValue('product_name')).'%"';
            $show_resset = true;
        }
        if(Tools::getValue('price_min'))
        {
            $filter .=' AND sc.price_tax_incl >='.(float)Tools::getValue('price_min').'';
            $show_resset = true;
        }
        if(Tools::getValue('price_max'))
        {
            $filter .=' AND sc.price_tax_incl <='.(float)Tools::getValue('price_max').'';
            $show_resset = true;
        }
        if(trim(Tools::getValue('quantity_min')))
        {
            $filter .=' AND sc.quantity >= '.(int)Tools::getValue('quantity_min').'';
            $show_resset = true;
        }
        if(trim(Tools::getValue('quantity_max')))
        {
            $filter .=' AND sc.quantity <= '.(int)Tools::getValue('quantity_max').'';
            $show_resset = true;
        }
        if(trim(Tools::getValue('commission_min')))
        {
            $filter .= ' AND sc.commission >='.(float)Tools::getValue('commission_min').'';
            $show_resset=true;
        }
        if(trim(Tools::getValue('commission_max')))
        {
            $filter .= ' AND sc.commission <='.(float)Tools::getValue('commission_max').'';
            $show_resset=true;
        }
        if(trim(Tools::getValue('status'))!=='')
        {
            $status= Tools::getValue('status');
            if($status =='refunded' || $status=='deducted')
            {
                $filter .= ' AND sc.type="usage" AND sc.status="'.($status =='refunded' ? 0 :1).'"';
            }
            else
            {
                $filter .=' AND sc.type="commission" AND sc.status = "'.(int)Tools::getValue('status').'"';
            }
            $show_resset = true;
        }
        if(trim(Tools::getValue('date_add_min')))
        {
            $filter .= ' AND sc.date_add >="'.pSQL(Tools::getValue('date_add_min')).' 00:00:00"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('date_add_max')))
        {
            $filter .=  ' AND sc.date_add <="'.pSQL(Tools::getValue('date_add_max')).' 23:59:59"';
            $show_resset = true;
        }
        //Sort
        $sort = "";
        if(Tools::getValue('sort','date_add'))
        {
            switch (Tools::getValue('sort','date_add')) {
                case 'reference':
                    $sort .='sc.reference';
                    break;
                case 'id_order':
                    $sort .='sc.id_order';
                    break;
                case 'customer_name':
                    $sort .='customer_name';
                    break;
                case 'product_name':
                    $sort .='sc.product_name';
                    break;
                case 'price':
                    $sort .='sc.price';
                    break;
                case 'quantity':
                    $sort .=' sc.quantity';
                    break;
                case 'commission':
                    $sort .=' sc.commission';
                    break;
                case 'status':
                    $sort .=' sc.status';
                    break;
                case 'date_add':
                    $sort .= 'sc.date_add';
                    break;
                
            }
            if($sort && ($sort_type = Tools::getValue('sort_type','desc')) && in_array($sort_type,array('asc','desc')))
                $sort .= ' '.trim($sort_type);  
        }
        //Paggination
        $page = (int)Tools::getValue('page') && (int)Tools::getValue('page') > 0 ? (int)Tools::getValue('page') : 1;
        $totalRecords = (int) $this->seller->getCommissions($filter,$having,0,0,'',true);
        $paggination = new Ets_mp_paggination_class();            
        $paggination->total = $totalRecords;
        $paggination->url = $this->context->link->getModuleLink($this->module->name,'commissions',array('list'=>true,'page'=>'_page_')).$this->module->getFilterParams($fields_list,'mp_commissions');
        $paggination->limit =  10;
        $totalPages = ceil($totalRecords / $paggination->limit);
        if($page > $totalPages)
            $page = $totalPages;
        $paggination->page = $page;
        $start = $paggination->limit * ($page - 1);
        if($start < 0)
            $start = 0;
        $commissions = $this->seller->getCommissions($filter,$having, $start,$paggination->limit,$sort,false);
        if($commissions)
        {
            foreach($commissions as &$commission)
            {
                if($commission['price'])
                    $commission['price'] = Tools::displayPrice($commission['price_tax_incl'],new Currency(Configuration::get('PS_CURRENCY_DEFAULT')));  
                else
                    $commission['price']='';
                $commission['commission'] = Tools::displayPrice($commission['commission'],new Currency(Configuration::get('PS_CURRENCY_DEFAULT')));  
                if($commission['type']=='usage')
                    $commission['commission'] = '<span class="ets_mp_commision_usage">-'.$commission['commission'].'</span>';
                if($commission['note'])
                    $commission['commission'] .= '<'.'b'.'r /'.'>'.'<'.'i'.'>'.$commission['note'].'<'.'/'.'i'.'>';
                if($commission['type']=='usage')
                {
                    $commission['id'] = 'U-'.$commission['id'];
                    if($commission['status']==0)
                        $commission['status'] = '<'.'span'.' class="ets_mp_status refunded">'.$this->module->l('Refunded','commissions').'<'.'/'.'span'.'>';
                    elseif($commission['status']==1)
                        $commission['status'] = '<'.'span'.' class="ets_mp_status deducted">'.$this->module->l('Deducted','commissions').'<'.'/'.'span'.'>';
                }
                else
                {
                    $commission['id'] = 'C-'.$commission['id'];
                    if($commission['status']==-1)
                        $commission['status'] = '<'.'span'.' class="ets_mp_status pending">'.$this->module->l('Pending','commissions').'<'.'/'.'span'.'>'; 
                    elseif($commission['status']==0)
                        $commission['status'] = '<'.'span'.' class="ets_mp_status canceled">'.$this->module->l('Canceled','commissions').'<'.'/'.'span'.'>';
                    elseif($commission['status']==1)
                        $commission['status'] = '<'.'span'.' class="ets_mp_status approved">'.$this->module->l('Approved','commissions').'<'.'/'.'span'.'>';
                }
                if($commission['id_product'] >0)
                    $commission['product_name'] = ($commission['product_id'] ? '<'.'a hr'.'ef="'.$this->context->link->getProductLink($commission['id_product'],null,null,null,null,null,$commission['id_product_attribute']).'" target="_blank">':'').$commission['product_name'].($commission['product_id'] ? '<'.'/'.'a'.'>':'');
                if(!$commission['quantity'])
                    $commission['quantity']='';               
            }
        }
        $paggination->text =  $this->module->l('Showing {start} to {end} of {total} ({pages} Pages)','commissions');
        $paggination->style_links = $this->module->l('links','commissions');
        $paggination->style_results = $this->module->l('results','commissions');
        $listData = array(
            'name' => 'mp_commissions',
            'actions' => array(),
            'currentIndex' => $this->context->link->getModuleLink($this->module->name,'commissions',array('list'=>true)),
            'identifier' => 'id',
            'show_toolbar' => true,
            'show_action' => true,
            'title' => $this->module->l('Commissions','commissions'),
            'fields_list' => $fields_list,
            'field_values' => $commissions,
            'paggination' => $paggination->render(),
            'filter_params' => $this->module->getFilterParams($fields_list,'mp_commissions'),
            'show_reset' =>$show_resset,
            'totalRecords' => $totalRecords,
            'sort'=> Tools::getValue('sort','date_add'),
            'show_add_new'=> false,
            'sort_type' => Tools::getValue('sort_type','desc'),
        );            
        return $this->module->renderList($listData);
    }
    
 }