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
//$commistion_status=array(
//    array(
//        'id' => '-1',
//        'name' => $this->l('Pending')
//    ),
//    array(
//        'id' => '0',
//        'name' => $this->l('Cancel')
//    ),
//    array(
//        'id' => '1',
//        'name' => $this->l('Approve')
//    )
//);
class Ets_mp_commission extends ObjectModel
{
    protected static $instance;
    public $id_customer;
    public $reference;
    public $id_order;
    public $id_product;
    public $id_product_attribute;
	public $id_shop;
    public $product_name;
	public $price;
    public $price_tax_incl;
    public $quantity;
    public $total_price;
    public $total_price_tax_incl;
    public $use_tax;
    public $status;
    public $commission;
    public $note;
    public $expired_date;
    public $date_add;
    public $date_upd;
    public static $definition = array(
		'table' => 'ets_mp_seller_commission',
		'primary' => 'id_seller_commission',
		'multilang' => false,
		'fields' => array(
			'id_customer' => array('type' => self::TYPE_INT),
            'reference' => array('type' => self::TYPE_STRING),
            'id_order' => array('type' => self::TYPE_STRING),
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'id_product' => array('type' => self::TYPE_STRING,),
            'id_product_attribute' =>	array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),  
            'product_name'  => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'), 
            'price'  => array('type' => self::TYPE_STRING, 'validate' => 'isPrice'), 
            'price_tax_incl' => array('type' =>   self::TYPE_FLOAT,'validate' => 'isPrice'),   
            'quantity' => array('type'=> self::TYPE_STRING),
            'total_price' => array('type' => self::TYPE_FLOAT,'validate' =>'isPrice'),
            'total_price_tax_incl' => array('type' => self::TYPE_FLOAT,'validate' =>'isPrice'),
            'status' => array('type'=>self::TYPE_INT),
            'commission' => array('type'=>self::TYPE_FLOAT,'validate' => 'isPrice'),
            'expired_date' => array('type'=>self::TYPE_STRING,'validate'=>'isCleanHtml'),
            'note' => array('type'=>self::TYPE_STRING,'validate'=>'isCleanHtml'),
            'use_tax' => array('type'=>self::TYPE_INT),
            'date_add' =>	array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
            'date_upd' =>	array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
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
            self::$instance = new Ets_mp_commission();
        }
        return self::$instance;
    }
    public function _renderCommission($id_customer=false)
    {
        $module = Module::getInstanceByName('ets_marketplace');
        if((Tools::isSubmit('cancelms_commissions') || Tools::isSubmit('approvems_commissions') || (Tools::getValue('del')=='yes' && Tools::getValue('type')=='commission') ) && $id_seller_commission =(int)Tools::getValue('id_commission'))
        {
                $commission = new Ets_mp_commission($id_seller_commission);
                if(Tools::getValue('del')=='yes')
                {
                    if($commission->delete())
                    {
                        $this->context->cookie->success_message = $this->l('Deleted successfully');
                    }
                }
                if(Tools::isSubmit('cancelms_commissions'))
                {
                    $commission->status=0;
                    if($commission->update())
                    {
                        $this->context->cookie->success_message = $this->l('Canceled successfully');
                        $this->context->cookie->write();
                    }
                }
                if(Tools::isSubmit('approvems_commissions'))
                {
                    $commission->status=1;
                    if($commission->update())
                    {
                        $this->context->cookie->success_message = $this->l('Approved successfully');
                        $this->context->cookie->write();
                        
                    }
                }
                Tools::redirectAdmin($this->context->link->getAdminLink(Tools::getValue('controller')).(Tools::isSubmit('viewseller')? '&viewseller=1&id_seller='.(int)Tools::getValue('id_seller'):''));
        }
        if((Tools::isSubmit('returnms_commissions') || Tools::isSubmit('deductms_commissions') || (Tools::getValue('del')=='yes' && Tools::getValue('type')=='usage') ) && $id_ets_mp_commission_usage =(int)Tools::getValue('id_commission'))
        {
            $commission_ugage = new Ets_mp_commission_usage($id_ets_mp_commission_usage);
            if(Tools::getValue('del')=='yes')
            {
                if($commission_ugage->delete())
                    $this->context->cookie->success_message = $this->l('Deleted successfully');
            }
            if(Tools::isSubmit('returnms_commissions'))
            {
                $commission_ugage->status=0;
                if($commission_ugage->update())
                    $this->context->cookie->success_message = $this->l('Returned successfully');
            }
            if(Tools::isSubmit('deductms_commissions'))
            {
                $commission_ugage->status=1;
                if($commission_ugage->update())
                    $this->context->cookie->success_message = $this->l('Deducted successfully');
            }
            Tools::redirectAdmin($this->context->link->getAdminLink(Tools::getValue('controller')).(Tools::isSubmit('viewseller')? '&viewseller=1&id_seller='.(int)Tools::getValue('id_seller'):''));
        }
        $commistion_status=array(
            array(
                'id' => '-1', //-1
                'name' => $this->l('Pending')
            ),
            array(
                'id' => '0', // 0
                'name' => $this->l('Canceled')
            ),
            array(
                'id' => '1', // 1
                'name' => $this->l('Approved')
            ),
            array(
                'id' =>'refunded' ,// 0,
                'name' => $this->l('Refunded')
            ),
            array(
                'id' =>'deducted' , // 1,
                'name' => $this->l('Deducted')
            ),
        );
        $fields_list = array(
            'id' => array(
                'title' => $this->l('ID'),
                'width' => 40,
                'type' => 'text',
                'sort' => true,
                'filter' => true,
            ),
            'seller_name' => array(
                'title' => $this->l('Seller name'),
                'type' => 'text',
                'sort' => true,
                'filter' => true,
                'strip_tag'=>false,
            ),
            'id_order'=>array(
                'title' => $this->l('Order ID'),
                'type'=> 'text',
                'sort' => true,
                'filter' => true,
            ),
            'product_name' => array(
                'title' => $this->l('Product name'),
                'type'=> 'text',
                'sort' => true,
                'filter' => true,
                'strip_tag'=>false,
            ),
            'price' => array(
                'title' => $this->l('Product price'),
                'type'=> 'int',
                'sort' => true,
                'filter' => true,
            ),
            'quantity' => array(
                'title' => $this->l('Product quantity'),
                'type'=> 'int',
                'sort' => true,
                'filter' => true,
            ),
            'commission' => array(
                'title' => $this->l('Commissions'),
                'type' => 'int',
                'sort' => true,
                'filter' => true,
                'strip_tag' => false,
            ),
            'admin_earning' => array(
                'title' => $this->l('Admin earning'),
                'type' => 'int',
                'sort' => true,
                'filter' => true,
                'strip_tag' => false,
            ),            
            'status' => array(
                'title' => $this->l('Status'), 
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
        if($id_customer)
        {
            $filter .=' AND sc.id_customer='.(int)$id_customer;
            unset($fields_list['seller_name']);
        }
        if(Tools::isSubmit('ets_mp_submit_ms_commissions'))
        {
            if(Tools::getValue('id'))
            {
                $filter .=' AND sc.id="'.(int)Tools::getValue('id').'"';
                $show_resset=true;
            }
            if(Tools::getValue('id_order'))
            {
                $filter .= ' AND sc.id_order="'.(int)Tools::getValue('id_order').'"';
                $show_resset = true;
            }
            if(Tools::getValue('seller_name'))
            {
                $filter .= ' AND CONCAT(customer.firstname," ",customer.lastname) like "%'.pSQL(Tools::getValue('seller_name')).'%"';
                $show_resset =true;
            }
            if(Tools::getValue('shop_name'))
            {
                $filter .= ' AND seller_lang.shop_name like "%'.pSQL(Tools::getValue('shop_name')).'%"';
                $show_resset = true;
            }
            if(trim(Tools::getValue('product_name')))
            {
                $filter .= ' AND sc.product_name like "'.pSQL(Tools::getValue('product_name')).'"';
                $show_resset = true;
            }
            if(trim(Tools::getValue('price_min')))
            {
                $filter .=' AND sc.price >= "'.(float)Tools::getValue('price_min').'"';
                $show_resset = true;
            }
            if(trim(Tools::getValue('price_max')))
            {
                $filter .=' AND sc.price <= "'.(float)Tools::getValue('price_max').'"';
                $show_resset = true;
            }
            if(trim(Tools::getValue('quantity_min')))
            {
                $filter .=' AND sc.quantity <="'.(int)Tools::getValue('quantity_min').'"';
                $show_resset=true;
            }
            if(trim(Tools::getValue('quantity_max')))
            {
                $filter .=' AND sc.quantity >="'.(int)Tools::getValue('quantity_max').'"';
                $show_resset=true;
            }
            if(trim(Tools::getValue('commission_min')))
            {
                $filter .=' AND sc.commission >= "'.(float)Tools::getValue('commission_min').'"';
                $show_resset=true;
            }
            if(trim(Tools::getValue('commission_max')))
            {
                $filter .=' AND sc.commission <="'.(float)Tools::getValue('commission_max').'"';
                $show_resset=true;
            }
            if(trim(Tools::getValue('admin_earning_min')))
            {
                $having .=' AND admin_earning_min >= "'.(float)Tools::getValue('admin_earning_min').'"';
                $show_resset=true;
            }
            if(trim(Tools::getValue('admin_earning_max')))
            {
                $having .=' AND admin_earning <="'.(float)Tools::getValue('admin_earning_max').'"';
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
                $filter .=' AND sc.date_add >="'.pSQL(Tools::getValue('date_add_min')).' 00:00:00"';
                $show_resset = true;
            }
            if(trim(Tools::getValue('date_add_max')))
            {
                $filter .=' AND sc.date_add <="'.pSQL(Tools::getValue('date_add_max')).' 23:59:59"';
                $show_resset = true;
            }
        }
        //Sort
        $sort = "";
        if(Tools::getValue('sort','date_add'))
        {
            switch (Tools::getValue('sort','date_add')) {
                case 'id':
                    $sort .='sc.id';
                    break;
                case 'id_order':
                    $sort .='sc.id_order';
                    break;
                case 'seller_name':
                    $sort .='seller_name';
                    break;
                case 'shop_name':
                    $sort .='seller_lang.shop_name';
                    break;
                case 'product_name':
                    $sort .='sc.product_name';
                    break;
                case 'price':
                    $sort .= 'sc.price';
                    break;
                case 'quantity':
                    $sort .= 'sc.quantity';
                    break;
                case 'commission':
                    $sort .='sc.commission';
                    break;
                case 'admin_earning':
                    $sort .='admin_earning';
                    break;
                case 'date_add':
                    $sort .= 'sc.date_add';
                    break;
                case 'status':
                    $sort .=' sc.status';
                    break;
            }
            if($sort && ($sort_type=Tools::getValue('sort_type','desc')) && in_array($sort_type,array('asc','desc')))
                $sort .= ' '.trim($sort_type);  
        }
        //Paggination
        $page = (int)Tools::getValue('page') && (int)Tools::getValue('page') > 0 ? (int)Tools::getValue('page') : 1;
        $totalRecords = (int) $module->getSellerCommissions($filter,$having,0,0,'',true);
        $paggination = new Ets_mp_paggination_class();            
        $paggination->total = $totalRecords;
        $paggination->url = $this->context->link->getAdminLink(Tools::getValue('controller')).'&page=_page_'.(Tools::isSubmit('viewseller') && Tools::getValue('id_seller') ? '&viewseller=1&id_seller='.(int)Tools::getValue('id_seller'):'').$module->getFilterParams($fields_list,'ms_commissions');
        $paggination->limit =  20;
        $totalPages = ceil($totalRecords / $paggination->limit);
        if($page > $totalPages)
            $page = $totalPages;
        $paggination->page = $page;
        $start = $paggination->limit * ($page - 1);
        if($start < 0)
            $start = 0;
        $commissions = $module->getSellerCommissions($filter,$having, $start,$paggination->limit,$sort,false);
        if($commissions)
        {
            foreach($commissions as &$commission)
            {
                $commission['price'] = $commission['price_tax_incl']!=0 ? Tools::displayPrice($commission['price_tax_incl']):'';  
                  
                $commission['commission'] = Tools::displayPrice($commission['commission']);  
                if($commission['id_product'] && $commission['admin_earning'])
                    $commission['admin_earning'] = Tools::displayPrice($commission['admin_earning']);
                else
                    $commission['admin_earning'] ='';
                if($commission['type']=='usage')
                    $commission['commission'] = '<span class="ets_mp_commision_usage">-'.$commission['commission'].'</span>';
                if($commission['note'])
                    $commission['commission'] .= '<'.'b'.'r /'.'>'.'<'.'i'.'>'.$commission['note'].'<'.'/'.'i'.'>';
                $commission['status_val'] = $commission['status'];
                $commission['id_commission'] = $commission['id'];
                if($commission['type']=='usage')
                {
                    $commission['id'] = 'U-'.$commission['id'];
                    if($commission['status']==0)
                        $commission['status'] = '<'.'span'.' class="ets_mp_status refunded">'.$this->l('Refunded','commissions').'<'.'/'.'span'.'>';
                    elseif($commission['status']==1)
                        $commission['status'] = '<'.'span'.' class="ets_mp_status deducted">'.$this->l('Deducted','commissions').'<'.'/'.'span'.'>';
                }
                else
                {
                    $commission['id'] = 'C-'.$commission['id'];
                    if($commission['status']==-1)
                        $commission['status'] = '<'.'span'.' class="ets_mp_status pending">'.$this->l('Pending','commissions').'<'.'/'.'span'.'>'; 
                    elseif($commission['status']==0)
                        $commission['status'] = '<'.'span'.' class="ets_mp_status canceled">'.$this->l('Canceled','commissions').'<'.'/'.'span'.'>';
                    elseif($commission['status']==1)
                        $commission['status'] = '<'.'span'.' class="ets_mp_status approved">'.$this->l('Approved','commissions').'<'.'/'.'span'.'>';
                }
                if($commission['id_product'])
                {
                    $commission['product_name'] = ($commission['product_id'] ? '<'.'a h'.'ref="'.$this->context->link->getAdminLink('AdminProducts',true,array('id_product'=>$commission['id_product'])).'">':'').$commission['product_name'].($commission['product_id'] ? '<'.'/a'.'>':'');
                }
                if($commission['id_customer_seller'])
                {
                    $commission['seller_name'] = '<'.'a hr'.'ef="'.$module->getLinkCustomerAdmin($commission['id_customer_seller']).'">'.$commission['seller_name'].'<'.'/'.'a'.'>'; 
                }
                else
                    $commission['seller_name'] = '<'.'sp'.'an class="row_deleted">'.$this->l('Seller deleted').'<'.'/'.'span'.'>';
                                   
            }
        }
        $paggination->text =  $this->l('Showing {start} to {end} of {total} ({pages} Pages)');
        $paggination->style_links = $this->l('links');
        $paggination->style_results = $this->l('results');
        $listData = array(
            'name' => 'ms_commissions',
            'actions' => array('approved','delete'),
            'icon' => 'fa fa-dollar',
            'currentIndex' => $this->context->link->getAdminLink(Tools::getValue('controller')).(Tools::isSubmit('viewseller') && Tools::getValue('id_seller') ? '&viewseller=1&id_seller='.(int)Tools::getValue('id_seller'):''),
            'identifier' => 'id_commission',
            'show_toolbar' => true,
            'show_action' => true,
            'title' => $this->l('Commissions'),
            'fields_list' => $fields_list,
            'field_values' => $commissions,
            'paggination' => $paggination->render(),
            'filter_params' => $module->getFilterParams($fields_list,'ms_commissions'),
            'show_reset' =>$show_resset,
            'totalRecords' => $totalRecords,
            'sort'=> Tools::getValue('sort','date_add'),
            'show_add_new'=> false,
            'sort_type' => Tools::getValue('sort_type','desc'),
        );           
        return $module->renderList($listData);
    }
    public function l($string)
    {
        return Translate::getModuleTranslation('ets_marketplace', $string, pathinfo(__FILE__, PATHINFO_FILENAME));
    }
    public function update($null_value=true)
    {
        $status_old = (int)Db::getInstance()->getValue('SELECT status FROM `'._DB_PREFIX_.'ets_mp_seller_commission` WHERE id_seller_commission='.(int)$this->id);
        $result = parent::update($null_value);
        if($result && $this->status!=$status_old)
        {
            if(Configuration::get('ETS_MP_EMAIL_SELLER_COMMISSION_VALIDATED_OR_CANCELED') && Configuration::get('ETS_MP_EMAIL_ADMIN_COMMISSION_VALIDATED_OR_CANCELED'))
            {
                $seller = Ets_mp_seller::_getSellerByIdCustomer($this->id_customer);
                $data = array(
                    '{seller_name}' => $seller->seller_name,
                    '{commission_ID}' => $this->id,
                    '{amount}' => Tools::displayPrice($this->commission,new Currency(Configuration::get('PS_CURRENCY_DEFAULT'))),
                    '{reason}' => $this->note,
                    '{created_date}'=>$this->date_add,
                    '{approved_date}' => date('Y-m-d H:i:s'),
                    '{canceled_date}' => date('Y-m-d H:i:s'),
                );
                if(Configuration::get('ETS_MP_EMAIL_SELLER_COMMISSION_VALIDATED_OR_CANCELED'))
                {
                    if($this->status==1)
                    {
                        $subjects = array(
                            'translation' => $this->l('Your commission has been validated'),
                            'origin'=> 'Your commission has been validated',
                            'specific'=>'commission'
                        );
                        Ets_marketplace::sendMail('to_seller_commission_validated',$data,$seller->seller_email,$subjects,$seller->seller_name);
                    }
                    elseif($this->status==0)
                    {
                        $subjects = array(
                            'translation' => $this->l('Your commission has been canceled'),
                            'origin'=> 'Your commission has been canceled',
                            'specific'=>'commission'
                        );
                        Ets_marketplace::sendMail('to_seller_commission_canceled',$data,$seller->seller_email,$subjects,$seller->seller_name);
                    }
                }
                if(Configuration::get('ETS_MP_EMAIL_ADMIN_COMMISSION_VALIDATED_OR_CANCELED'))
                {
                    if($this->status==1)
                    {
                        $subjects = array(
                            'translation' => $this->l('A commission has been validated'),
                            'origin'=> 'A commission has been validated',
                            'specific'=>'commission'
                        );
                        Ets_marketplace::sendMail('to_admin_commission_validated',$data,'',$subjects);
                    }
                    elseif($this->status==0)
                    {
                        $subjects = array(
                            'translation' => $this->l('A commission has been canceled'),
                            'origin'=> 'A commission has been canceled',
                            'specific'=>'commission',
                        );
                        Ets_marketplace::sendMail('to_admin_commission_canceled',$data,'',$subjects);
                    }
                    
                }
            }
            
        }
        return $result;
    }
    public function add($auto_date=true,$null_value=true)
    {
        do {
            $reference = Ets_mp_commission::generateReference();
        } while (Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'ets_mp_seller_commission` WHERE reference="'.pSQL($reference).'"'));
        $this->reference = $reference;
        $res = parent::add($auto_date,$null_value);
        if($res && (Configuration::get('ETS_MP_EMAIL_ADMIN_COMMISSION_CREATED')||Configuration::get('ETS_MP_EMAIL_ADMIN_COMMISSION_CREATED')))
        {
            if($this->status==-1)
                $status = '<'.'span'.' class="ets_mp_status pending">'.$this->l('Pending').'<'.'/'.'span'.'>'; 
            elseif($this->status==0)
                $status = '<'.'span'.' class="ets_mp_status canceled">'.$this->l('Canceled').'<'.'/'.'span'.'>';
            elseif($this->status==1)
                $status = '<'.'span'.' class="ets_mp_status approved">'.$this->l('Approved').'<'.'/'.'span'.'>';
            $seller = Ets_mp_seller::_getSellerByIdCustomer($this->id_customer);
            if($this->id_order)
                $order = new Order($this->id_order);
            $data=array(
                '{seller_name}' => $seller->seller_name,
                '{commission_ID}' => $this->id,
                '{amount}' => Tools::displayPrice($this->commission,new Currency(Configuration::get('PS_CURRENCY_DEFAULT'))),
                '{product_name}' => $this->product_name,
                '{order_reference}' => isset($order) ? $order->reference:'',
                '{created_date}'=>$this->date_add,
                '{status}' => $status,
            );
            if(Configuration::get('ETS_MP_EMAIL_SELLER_COMMISSION_CREATED'))
            {  
                $subjects = array(
                    'translation' => $this->l('New seller commission has been created'),
                    'origin'=> 'New seller commission has been created',
                    'specific'=>'commission'
                );
                Ets_marketplace::sendMail('to_seller_commission_created',$data,$seller->seller_email,$subjects,$seller->seller_name);
            }
            if(Configuration::get('ETS_MP_EMAIL_ADMIN_COMMISSION_CREATED'))
            {
                $subjects = array(
                    'translation' => $this->l('New commission has been created'),
                    'origin'=>'New commission has been created',
                    'specific'=>'commission'
                );
                Ets_marketplace::sendMail('to_admin_commission_created_for_seller',$data,'',$subjects);
                
            }
        }
        return $res;
    }
    public static function generateReference()
    {
        return Tools::strtoupper(Tools::passwdGen(9, 'NO_NUMERIC'));
    }
}