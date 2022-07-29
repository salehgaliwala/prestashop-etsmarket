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
class AdminMarketPlaceOrdersController extends ModuleAdminController
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
        if(Tools::isSubmit('del') && $id_order = Tools::getValue('id_order'))
        {
            $order = new Order($id_order);
            if($order->delete())
            {
                $this->context->cookie->success_message = $this->l('Deleted order successfully');
                Tools::redirectAdmin($this->context->link->getAdminLink('AdminMarketPlaceOrders'));
            }
        }
    }
    public function renderList()
    {
        $this->module->getContent();
        $this->context->smarty->assign(
            array(
                'ets_mp_body_html'=> $this->_renderOrders(),
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
    public function _renderOrders()
    {
        $orderStates = Db::getInstance()->executeS(
        'SELECT os.*,osl.name FROM `'._DB_PREFIX_.'order_state` os
        LEFT JOIN `'._DB_PREFIX_.'order_state_lang` osl ON (os.id_order_state = osl.id_order_state AND osl.id_lang="'.(int)$this->context->language->id.'")'
        );
        $fields_list = array(
            'id_order' => array(
                'title' => $this->l('ID'),
                'width' => 40,
                'type' => 'text',
                'sort' => true,
                'filter' => true,
                'class'=>'text-center'
            ),
            'reference'=>array(
                'title' => $this->l('Order reference'),
                'type'=> 'text',
                'sort' => true,
                'filter' => true,
            ),
            'customer_name' => array(
                'title' => $this->l('Customer'),
                'type'=> 'text',
                'sort' => true,
                'filter' => true,
                'strip_tag'=>false,
            ),
            'total_paid_tax_incl' => array(
                'title' => $this->l('Total price (tax incl)'),
                'type' => 'int',
                'sort' => true,
                'filter' => true,
                'class'=>'text-center'
            ),
            'seller_name' => array(
                'title' => $this->l('Seller name'),
                'type' => 'text',
                'sort' => true,
                'filter' => true,
                'strip_tag'=>false,
            ),
            'shop_name' => array(
                'title' => $this->l('Shop name'),
                'type' => 'text',
                'sort' => true,
                'filter' => true,
                'strip_tag'=>false
            ),
            'total_commission' => array(
                'title' => $this->l('Seller commissions'),
                'type' => 'text',
                'sort' => true,
                //'filter' => true,
            ),
            'admin_earned' => array(
                'title' => $this->l('Admin earned'),
                'type' => 'text',
                'sort' => true,
                //'filter' => true,
            ),
            'current_state' => array(
                'title' => $this->l('Status'),
                'type' => 'select',
                'sort' => true,
                'filter' => true,
                'strip_tag' => false,
                'filter_list' => array(
                    'list' => $orderStates,
                    'id_option' => 'id_order_state',
                    'value' => 'name',
                ),
            ),
            'date_add' => array(
                'title' => $this->l('Date'),
                'type' => 'date',
                'sort' => true,
                'filter' => true
            ),
        );
        //Filter
        $show_resset = false;
        $filter = "";
        $having = "";
        if(Tools::getValue('id_order') && !Tools::isSubmit('del'))
        {
            $filter .= ' AND o.id_order="'.(int)Tools::getValue('id_order').'"';
            $show_resset = true;
        }
        if(Tools::getValue('seller_name'))
        {
            $filter .= ' AND CONCAT(customer.firstname," ",customer.lastname) like "%'.pSQL(Tools::getValue('seller_name')).'%"';
            $show_resset =true;
        }
        if(Tools::getValue('customer_name'))
        {
            $filter .= ' AND CONCAT(c.firstname," ",c.lastname) like "%'.pSQL(Tools::getValue('customer_name')).'%"';
            $show_resset = true;   
        }
        if(Tools::getValue('shop_name'))
        {
            $filter .= ' AND sl.shop_name like "%'.pSQL(Tools::getValue('shop_name')).'%"';
            $show_resset = true;
        }
        if(Tools::getValue('total_commission'))
        {
            $having .=' AND total_commission ="'.(float)Tools::getValue('total_commission').'"';
            $show_resset = true;
        }
        if(Tools::getValue('admin_earned'))
        {
            $having .= ' AND admin_earned ="'.(float)Tools::getValue('admin_earned').'"';
            $show_resset = true;
        }
        if(Tools::getValue('reference'))
        {
            $filter .=' AND o.reference LIKE "%'.pSQL(Tools::getValue('reference')).'%"';
            $show_resset = true;
        }
        if(Tools::getValue('payment'))
        {
            $filter .=' AND o.payment LIKE "%'.pSQL(Tools::getValue('payment')).'%"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('date_add_min')))
        {
            $filter .=' AND o.date_add >= "'.pSQL(Tools::getValue('date_add_min')).' 00:00:00"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('date_add_max')))
        {
            $filter .= ' AND o.date_add <="'.pSQL(Tools::getValue('date_add_max')).' 23:59:59"';
            $show_resset=true;
        }
        if(trim(Tools::getValue('total_paid_tax_incl_min')))
        {
            $filter .=' AND o.total_paid_tax_incl >= "'.(float)Tools::getValue('total_paid_tax_incl_min').'"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('total_paid_tax_incl_max')))
        {
            $filter .=' AND o.total_paid_tax_incl <= "'.(float)Tools::getValue('total_paid_tax_incl_max').'"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('current_state')))
        {
            $filter .=' AND o.current_state = "'.(int)Tools::getValue('current_state').'"';
            $show_resset = true;
        }
        //Sort
        $sort = "";
        if(Tools::getValue('sort','id_order'))
        {
            switch (Tools::getValue('sort','id_order')) {
                case 'id_order':
                    $sort .='o.id_order';
                    break;
                case 'seller_name':
                    $sort .='seller_name';
                    break;
                case 'reference':
                    $sort .='o.reference';
                    break;
                case 'customer_name':
                    $sort .='customer_name';
                    break;
                case 'shop_name':
                    $sort .='shop_name';
                    break;
                case 'date_add':
                    $sort .= 'o.date_add';
                    break;
                case 'total_paid_tax_incl':
                    $sort .= 'o.total_paid_tax_incl';
                    break;
                case 'total_commission':
                    $sort .='total_commission';
                    break;
                case 'admin_earned':
                    $sort .='admin_earned';
                    break;
                case 'payment':
                    $sort .= 'o.payment';
                    break;
                case 'current_state':
                    $sort .= 'o.current_state';
                    break;
            }
            if($sort && ($sort_type=Tools::getValue('sort_type','desc')) && in_array($sort_type,array('asc','desc')))
                $sort .= ' '.trim($sort_type);  
        }
        //Paggination
        $page = (int)Tools::getValue('page') && (int)Tools::getValue('page') > 0 ? (int)Tools::getValue('page') : 1;
        $totalRecords = (int) $this->module->getOrders($filter,$having,0,0,'',true);
        $paggination = new Ets_mp_paggination_class();            
        $paggination->total = $totalRecords;
        $paggination->url = $this->context->link->getAdminLink('AdminMarketPlaceOrders').'&page=_page_'.$this->module->getFilterParams($fields_list,'ms_orders');
        $paggination->limit =  20;
        $totalPages = ceil($totalRecords / $paggination->limit);
        if($page > $totalPages)
            $page = $totalPages;
        $paggination->page = $page;
        $start = $paggination->limit * ($page - 1);
        if($start < 0)
            $start = 0;
        $orders = $this->module->getOrders($filter,$having, $start,$paggination->limit,$sort,false);
        if($orders)
        {
            foreach($orders as &$order)
            {
                $order['total_paid_tax_incl'] = Tools::displayPrice(Tools::convertPrice($order['total_paid_tax_incl'],$order['id_currency'],false), new Currency(Configuration::get('PS_CURRENCY_DEFAULT')));
                $order['total_commission'] = Tools::displayPrice($order['total_commission'], new Currency(Configuration::get('PS_CURRENCY_DEFAULT')));
                $order['admin_earned'] = Tools::displayPrice($order['admin_earned'], new Currency(Configuration::get('PS_CURRENCY_DEFAULT')));
                $order['current_state'] = $this->module->displayOrderState($order['current_state']);  
                $order['child_view_url'] = $this->context->link->getAdminLink('AdminOrders').'&id_order='.$order['id_order'].'&vieworder';   
                if($order['id_order_seller'])
                {
                    if(!$order['id_seller'])
                    {
                        $order['shop_name']='<'.'sp'.'an cl'.'ass="deleted_shop row_deleted">'.$this->l('Shop deleted').'</sp'.'an'.'>';
                    }
                    else
                    {
                        $order['shop_name'] = '<'.'a hr'.'ef="'.$this->module->getShopLink(array('id_seller'=>$order['id_seller'])).'" tar'.'get="_bl'.'ank">'.$order['shop_name'].'<'.'/'.'a'.'>'; 
                    }
                    if($order['id_customer_seller'])
                    {
                        $order['seller_name'] = '<'.'a hr'.'ef="'.$this->module->getLinkCustomerAdmin($order['id_customer_seller']).'">'.$order['seller_name'].'<'.'/'.'a'.'>';
                    }
                    else
                        $order['seller_name'] = '<'.'sp'.'an class="row_deleted">'.$this->l('Seller deleted').'<'.'/'.'span'.'>';
                }
                else
                {
                    $order['seller_name'] ='--';
                    $order['shop_name'] ='--';
                }
                $order['customer_name'] = '<'.'a hr'.'ef="'.$this->module->getLinkCustomerAdmin($order['id_customer']).'">'.$order['customer_name'].'<'.'/'.'a'.'>'; 
                               
            }
        }
        $paggination->text =  $this->l('Showing {start} to {end} of {total} ({pages} Pages)');
        $paggination->style_links = $this->l('links');
        $paggination->style_results = $this->l('results');
        $listData = array(
            'name' => 'ms_orders',
            'actions' => array('view'),
            'icon' => 'icon-orders',
            'currentIndex' => $this->context->link->getAdminLink('AdminMarketPlaceOrders'),
            'identifier' => 'id_order',
            'show_toolbar' => true,
            'show_action' => true,
            'title' => $this->l('Orders'),
            'fields_list' => $fields_list,
            'field_values' => $orders,
            'paggination' => $paggination->render(),
            'filter_params' => $this->module->getFilterParams($fields_list,'ms_orders'),
            'show_reset' =>$show_resset,
            'totalRecords' => $totalRecords,
            'sort'=> Tools::getValue('sort','id_order'),
            'show_add_new'=> false,
            'sort_type' => Tools::getValue('sort_type','desc'),
        );            
        return $this->module->renderList($listData);
    }
}