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
class Ets_mp_billing extends ObjectModel
{
    protected static $instance;
    public $id_customer;
    public $reference;
    public $seller_confirm;
    public $amount;
    public $amount_tax;
    public $fee_type;
    public $id_shop;
    public $active;
    public $used;
    public $date_from;
    public $date_to;
    public $date_add;
    public $date_upd;
    public $id_employee;
    public $note;    
    public static $definition = array(
		'table' => 'ets_mp_seller_billing',
		'primary' => 'id_ets_mp_seller_billing',
		'multilang' => false,
		'fields' => array(
			'id_customer' => array('type' => self::TYPE_INT),
            'id_shop' => array('type' => self::TYPE_INT),
            'seller_confirm' => array('type'=>self::TYPE_INT),
            'amount' => array('type'=> self::TYPE_FLOAT),
            'amount_tax' => array('type'=> self::TYPE_FLOAT),
            'fee_type' => array('type'=>self::TYPE_STRING),
            'reference' => array('type'=>self::TYPE_STRING),
            'active' => array('type'=> self::TYPE_INT),
            'used' =>array('type'=> self::TYPE_INT),
            'note' =>	array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
            'id_employee' => array('type'=>self::TYPE_INT),
            'date_from' =>	array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
            'date_to' =>	array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
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
            self::$instance = new Ets_mp_billing();
        }
        return self::$instance;
    }
    public function _renderFromBilling()
    {
        $module = Module::getInstanceByName('ets_marketplace');
        $fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Add membership'),
                    'icon' =>'icon-billing',				
				),
				'input' => array(
                    array(
                        'type'=>'hidden',
                        'name' => 'id_seller',
                    ),					
					array(
						'type' => 'text',
						'label' => $this->l('Seller name'),
						'name' => 'seller_name', 
                        'required' => true,  
                        'suffix' => '<'.'i cl'.'ass="fa fa-search"'.'><'.'/'.'i'.'>', 	
                        'col'=>3,
                        'form_group_class' => 'form_search_seller',				                     
					), 
                    array(
						'type' => 'text',
						'label' => $this->l('Amount'),
						'name' => 'amount',
                        'suffix' => $this->context->currency->iso_code,
                        'col'=>3, 
                        'required' => true, 					                    
					),         
                    array(
						'type' => 'select',
						'label' => $this->l('Status'),
						'name' => 'active',
						'options' => array(
                			 'query' => array(
                                array(
                                    'id_option'=>0,
                                    'name'=>$this->l('Pending'),
                                ),
                                array(
                                    'id_option'=>1,
                                    'name'=>$this->l('Paid'),
                                ),
                             ),                             
                             'id' => 'id_option',
                			 'name' => 'name'  
                        ),					
					),
                    array(
                        'type' => 'date',
                        'label' => $this->l('Available from'),
                        'name' => 'date_from',
                    ),
                    array(
                        'type' => 'date',
                        'label' => $this->l('Available to'),
                        'name' => 'date_to',
                    ),
                    array(
                        'type' =>'textarea',
                        'label' => $this->l('Description'),
                        'name' => 'note',
                        'col'=>6
                    ),
                ),
                'submit' => array(
					'title' => $this->l('Save'),
				),
                'buttons' => array(
                    array(
                        'href' =>Tools::isSubmit('viewseller') ? $this->context->link->getAdminLink('AdminMarketPlaceSellers'): $this->context->link->getAdminLink('AdminMarketPlaceBillings', true),
                        'icon'=>'process-icon-cancel',
                        'title' => $this->l('Cancel'),
                    )
                ),
            ),
		);
        $helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = 'ets_mp_seller_billing';
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();
		$helper->module = $module;
		$helper->identifier = 'id_ets_mp_seller_billing';
		$helper->submit_action = 'saveBilling';
		$helper->currentIndex = Tools::isSubmit('viewseller') ? $this->context->link->getAdminLink('AdminMarketPlaceSellers',false).'&addnewbillng=1': $this->context->link->getAdminLink('AdminMarketPlaceBillings', false).'&addnewbillng=1';
		$helper->token = Tools::isSubmit('viewseller') ? Tools::getAdminTokenLite('AdminMarketPlaceSellers'): Tools::getAdminTokenLite('AdminMarketPlaceBillings');
		$language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->tpl_vars = array(
			'base_url' => $this->context->shop->getBaseURL(),
			'language' => array(
				'id_lang' => $language->id,
				'iso_code' => $language->iso_code
			),
            
            'PS_ALLOW_ACCENTED_CHARS_URL', (int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL'),
			'fields_value' => $this->getBillingFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id,
			'image_baseurl' => '',
            'link' => $this->context->link,
            'cancel_url' => $this->context->link->getAdminLink('AdminMarketPlaceSellers', true),
		);            
        return $helper->generateForm(array($fields_form));
    }
    public function getBillingFieldsValues()
    {
        if($id_seller= Tools::getValue('id_seller'))
            $seller = new Ets_mp_seller($id_seller);
        else
            $seller = new Ets_mp_seller();
        $fields = array(
            'id_seller' => Tools::getValue('id_seller',$seller->id),
            'seller_name' => $seller->seller_name,
            'amount'=> Tools::getValue('amount'),
            'active' => Tools::getValue('active'),
            'date_from'=> Tools::getValue('date_from'),
            'date_to' => Tools::getValue('date_to'),
            'note' => Tools::getValue('note'),
        );
        return $fields;
    }
    public function _renderBilling($id_customer=0)
    {
        $module = Module::getInstanceByName('ets_marketplace');
        if((Tools::isSubmit('cancelms_billings') || Tools::isSubmit('purchasems_billings') || Tools::getValue('del')=='yes') && $id_ets_mp_seller_billing =(int)Tools::getValue('id_ets_mp_seller_billing'))
        {
            $billing_class = new Ets_mp_billing($id_ets_mp_seller_billing);
            if(Tools::getValue('del')=='yes')
            {
                if($billing_class->delete())
                    $this->context->cookie->success_message = $this->l('Deleted successfully');
                else
                    $module->_errors[] = $this->l('An error occurred while deleting the memberships');
            }
            if(Tools::isSubmit('cancelms_billings'))
            {
                $billing_class->active=-1;
                if($billing_class->update(true))
                    $this->context->cookie->success_message = $this->l('Canceled successfully');
                else
                    $module->_errors[] = $this->l('An error occurred while saving the memberships');
            }
            if(Tools::isSubmit('purchasems_billings'))
            {
                $billing_class->active=1;
                $used = $billing_class->used;
                $billing_class->used=1;
                if($billing_class->update(true))
                {
                    $this->context->cookie->success_message = $this->l('Set as paid successfully');
                    $seller = Ets_mp_seller::_getSellerByIdCustomer($billing_class->id_customer);
                    if(!$used)
                    {
                       $seller->payment_verify =0;
                       $seller->update(); 
                    }
                    if(!$used && Configuration::get('ETS_MP_APPROVE_AUTO_BY_BILLING'))
                    { 
                        if($seller->active!=0)
                        {
                            if($seller->date_to || $seller->active==-1)
                            {
                                $seller->date_from = $seller->date_to && strtotime($seller->date_to) < strtotime(date('Y-m-d H:i:s')) ? $seller->date_to : date('Y-m-d H:i:s');
                                if($seller->active==-1 || ($seller->date_to && strtotime($seller->date_to) < strtotime(date('Y-m-d H:i:s'))))
                                {
                                    $date_add = date('Y-m-d H:i:s');
                                }
                                else
                                {
                                    $date_add = $seller->date_to;
                                }
                                if($billing_class->fee_type=='monthly_fee')
                                    $seller->date_to = date("Y-m-d H:i:s", strtotime($date_add."+1 month"));
                                elseif($billing_class->fee_type=='quarterly_fee')
                                    $seller->date_to = date("Y-m-d H:i:s", strtotime($date_add."+3 month"));
                                elseif($billing_class->fee_type=='yearly_fee')
                                    $seller->date_to = date("Y-m-d H:i:s", strtotime($date_add."+1 year"));
                                else
                                    $seller->date_to =null;
                                if((!$seller->date_from || strtotime($seller->date_from) <= strtotime(date('Y-m-d H:i:s'))) && (!$seller->date_to || strtotime($seller->date_to) >= strtotime(date('Y-m-d H:i:s'))))
                                    $seller->active=1;
                            }
                        }
                        $seller->update(true);
                    }
                    
                }
                else
                    $module->_errors[] = $this->l('An error occurred while saving the memberships');
            }
            //Tools::redirectAdmin($this->context->link->getAdminLink(Tools::getValue('controller')).(Tools::isSubmit('viewseller')? '&viewseller=1&id_seller='.(int)Tools::getValue('id_seller'):''));
        }
        $fields_list = array(
            'id_ets_mp_seller_billing' => array(
                'title' => $this->l('ID'),
                'width' => 40,
                'type' => 'text',
                'sort' => true,
                'filter' => true,
            ),
            'reference' => array(
                'title' => $this->l('Reference'),
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
                'strip_tag' => false,                
            ),
            'shop_name' => array(
                'title' => $this->l('Shop name'),
                'type' => 'text',
                'sort' => true,
                'filter' => true,
                'strip_tag' => false,
            ),
            'amount' => array(
                'title' => $this->l('Amount'),
                'type' => 'int',
                'sort' => true,
                'filter' => true,
            ),
            'active' => array(
                'title' => $this->l('Status'),
                'type' => 'select',
                'sort' => true,
                'filter' => true,
                'strip_tag'=> false,
                'filter_list' => array(
                    'list' => array(
                        array(
                            'id_option'=>-1,
                            'value' => $this->l('Canceled'),
                        ),
                        array(
                            'id_option'=>0,
                            'value' => $this->l('Pending'),
                        ),
                        array(
                            'id_option'=>1,
                            'value' => $this->l('Paid'),
                        )
                    ),
                    'id_option' => 'id_option',
                    'value' => 'value',
                ),
            ),
            'note' => array(
                'title' => $this->l('Description'),
                'type'=>'text',
                'sort'=>false,
                'filter'=>false,
                'strip_tag'=>false,
            ),
            'by_admin' => array(
                'title' => $this->l('Invoice type'),
                'type'=>'select',
                'sort'=>true,
                'filter'=>true,
                'filter_list' => array(
                    'list' => array(
                        array(
                            'id_option'=>0,
                            'value' => $this->l('Auto'),
                        ),
                        array(
                            'id_option'=>1,
                            'value' => $this->l('Manually'),
                        )
                    ),
                    'id_option' => 'id_option',
                    'value' => 'value',
                ),
            ),
            'date_add' => array(
                'title' => $this->l('Date of invoice'),
                'type' => 'date',
                'sort' => true,
                'filter' => true
            ),
            'date_due' => array(
                'title' => $this->l('Due date'),
                'type' => 'date',
                'sort' => true,
                'filter' => true
            ),
            'pdf' => array(
                'title' => $this->l('PDF','billing'),
                'type' => 'text',
                'sort' => false,
                'filter' => false,
                'strip_tag' => false,
            ),
        );
        //Filter
        $show_resset = false;
        $filter = "";
        $having = "";
        if($id_customer)
        {
            $filter .=' AND b.id_customer='.(int)$id_customer;
            unset($fields_list['seller_name']);
            unset($fields_list['shop_name']);
        }
        if(Tools::isSubmit('ets_mp_submit_ms_billings'))
        {
            if(Tools::getValue('id_ets_mp_seller_billing'))
            {
                $filter .=' AND b.id_ets_mp_seller_billing ="'.(int)Tools::getValue('id_ets_mp_seller_billing').'"';
                $show_resset=true;
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
            if(trim(Tools::getValue('amount_min')))
            {
                $filter .= ' AND b.amount >= "'.(float)Tools::getValue('amount_min').'"';
                $show_resset = true;
            }
            if(trim(Tools::getValue('amount_max')))
            {
                $filter .= ' AND b.amount <="'.(float)Tools::getValue('amount_max').'"';
                $show_resset = true;
            }
            if(trim(Tools::getValue('active'))!=='')
            {
                $filter .= ' AND b.active="'.(int)Tools::getValue('active').'"';
                $show_resset = true;
            }
            if(trim(Tools::getValue('date_add_min')))
            {
                $filter .= ' AND b.date_add >="'.pSQL(Tools::getValue('date_add_min')).' 00:00:00"';
                $show_resset = true;
            }
            if(trim(Tools::getValue('date_add_max')))
            {
                $filter .= ' AND b.date_add <="'.pSQL(Tools::getValue('date_add_max')).' 23:59:59"';
                $show_resset = true;
            }
            if(trim(Tools::getValue('date_due_min')))
            {
                $having .= ' AND date_due!="" AND date_due >="'.pSQL(Tools::getValue('date_due_min')).' 00:00:00"';
                $show_resset = true;
            }
            if(trim(Tools::getValue('date_due_max')))
            {
                $having .= ' AND date_due!="" AND date_due <="'.pSQL(Tools::getValue('date_due_max')).' 23:59:59"';
                $show_resset = true;
            }
            if(trim(Tools::getValue('note')))
            {
                $filter .= ' AND b.note LIKE "%'.trim(Tools::getValue('note')).'%"';
                $show_resset=true;
            }
            if(trim(Tools::getValue('by_admin'))!='')
            {
                $show_resset=true;
                if(Tools::getValue('by_admin'))
                    $filter .=' AND b.id_employee!=0';
                else
                    $filter .=' AND b.id_employee=0';
            }
            if(trim(Tools::getValue('reference'))!='')
            {
                $filter .=' AND b.reference LIKE "'.pSQL(Tools::getValue('reference')).'%"';
                $show_resset = true;
            }
        }
        //Sort
        $sort = "";
        if(Tools::getValue('sort','id_ets_mp_seller_billing'))
        {
            switch (Tools::getValue('sort','id_ets_mp_seller_billing')) {
                case 'id_ets_mp_seller_billing':
                    $sort .='b.id_ets_mp_seller_billing';
                    break;
                case 'seller_name':
                    $sort .='seller_name';
                    break;
                case 'shop_name':
                    $sort .='seller_lang.shop_name';
                    break;
                case 'amount':
                    $sort .='b.amount';
                    break;
                case 'active':
                    $sort .='b.active';
                    break;
                case 'date_add':
                    $sort .='b.date_add';
                    break;
                case 'date_due':
                    $sort .='date_due';
                    break;
                case 'by_admin':
                    $sort .='b.id_employee';
                    break;
                case 'note':
                    $sort .='b.note';
                    break;
                case 'reference':
                    $sort .='b.reference';
                    break;
            }
            if($sort && ($sort_type=Tools::getValue('sort_type','desc')) && in_array($sort_type,array('acs','desc')))
                $sort .= ' '.trim($sort_type);  
        }
        //Paggination
        $page = (int)Tools::getValue('page') && (int)Tools::getValue('page') > 0 ? (int)Tools::getValue('page') : 1;
        $totalRecords = (int) $this->getSellerBillings($filter,$having,0,0,'',true);
        $paggination = new Ets_mp_paggination_class();            
        $paggination->total = $totalRecords;
        $paggination->url = $this->context->link->getAdminLink(Tools::getValue('controller')).'&page=_page_'.(Tools::isSubmit('viewseller') && Tools::getValue('id_seller') ? '&viewseller=1&id_seller='.(int)Tools::getValue('id_seller'):'').$module->getFilterParams($fields_list,'ms_billings');
        $paggination->limit =  20;
        $totalPages = ceil($totalRecords / $paggination->limit);
        if($page > $totalPages)
            $page = $totalPages;
        $paggination->page = $page;
        $start = $paggination->limit * ($page - 1);
        if($start < 0)
            $start = 0;
        $billings = $this->getSellerBillings($filter,$having, $start,$paggination->limit,$sort,false);
        if($billings)
        {
            foreach($billings as &$billing)
            {
                $billing['amount'] = Tools::displayPrice($billing['amount'],new Currency(Configuration::get('PS_CURRENCY_DEFAULT')));
                if($billing['active']==0)
                {
                    $billing['active'] = '<'.'sp'.'an cla'.'ss="ets_mp_status pending">'.$this->l('Pending').'<'.'/'.'sp'.'an'.'>';
                    //$billing['active'] = '<'.'i cl'.'as'.'s="ic'.'on-clo'.'ck-o"'.' title="'.$this->l('Pending').'"><'.'/i'.'>';
                }
                elseif($billing['active']==1)
                {
                    $billing['active'] = '<'.'sp'.'an cl'.'ass="ets_mp_status purchased">'.$this->l('Paid').'</sp'.'an'.'>';
                    //$billing['active'] ='<'.'i cl'.'ass="fa fa-check"'.' title="'.$this->l('Purchased').'">'.'<'.'/'.'i'.'>';
                }
                elseif($billing['active']==-1)
                {
                    $billing['active'] = '<'.'sp'.'an cl'.'ass="ets_mp_status deducted">'.$this->l('Canceled').'</sp'.'an'.'>';
                    //$billing['active'] ='<'.'i cl'.'ass="fa fa-check"'.' title="'.$this->l('Purchased').'">'.'<'.'/'.'i'.'>';
                }
                if($billing['id_seller'])
                {
                    $billing['shop_name'] = '<'.'a hr'.'ef="'.$module->getShopLink(array('id_seller'=>$billing['id_seller'])).'">'.$billing['shop_name'].'<'.'/'.'a'.'>';
                }
                else
                {
                    $billing['shop_name']='<'.'sp'.'an cl'.'ass="deleted_shop row_deleted">'.$this->l('Shop deleted').'</sp'.'an'.'>';
                } 
                if($billing['id_customer_seller'])
                {
                    $billing['seller_name'] = '<'.'a hr'.'ef="'.$module->getLinkCustomerAdmin($billing['id_customer_seller']).'">'.$billing['seller_name'].'<'.'/'.'a'.'>';
                }
                else
                    $billing['seller_name'] = '<'.'sp'.'an class="row_deleted">'.$this->l('Seller deleted').'<'.'/'.'span'.'>'; 
                if($billing['id_employee'])
                    $billing['by_admin'] = $this->l('Manually');
                else
                    $billing['by_admin'] = $this->l('Auto');  
                $billing['billing_number'] ='#BL';
                while(Tools::strlen($billing['billing_number'].$billing['id_ets_mp_seller_billing'])<8)
                    $billing['billing_number'] .='0';
                $billing['billing_number'] .= $billing['id_ets_mp_seller_billing'];
                if(!$billing['id_employee'])
                {
                    if($billing['fee_type']=='pay_once')
                        $billing['note'] = $this->l('Pay once');
                    if($billing['fee_type']=='monthly_fee')
                        $billing['note'] = $this->l('Monthly fee:').'<b'.'r'.'/'.'>'.$this->l('From').' '.Tools::displayDate($billing['date_from']).' '.$this->l('To'). ' '.Tools::displayDate($billing['date_to']);
                    if($billing['fee_type']=='quarterly_fee')
                        $billing['note'] = $this->l('Quarterly fee:').'<b'.'r'.'/'.'>'.$this->l('From').' '.Tools::displayDate($billing['date_from']).' '.$this->l('To'). ' '.Tools::displayDate($billing['date_to']);
                    if($billing['fee_type']=='yearly_fee')
                        $billing['note'] = $this->l('Yearly fee:').'<b'.'r'.'/'.'>'.$this->l('From').' '.Tools::displayDate($billing['date_from']).' '.$this->l('To'). ' '.Tools::displayDate($billing['date_to']);  
                }
                else
                    $billing['note'] .= (trim($billing['note']) ? '<b'.'r'.'/'.'> ':'').($billing['date_from'] && $billing['date_from']!='0000-00-00' ? $this->l('From').' '.Tools::displayDate($billing['date_from']).' ' :'' ). ($billing['date_to'] && $billing['date_to']!='0000-00-00' ? $this->l('To').' '.Tools::displayDate($billing['date_to']) :'' );
                $billing['pdf'] ='<'.'a class="ets_mp_downloadpdf" hr'.'ef="'.$this->context->link->getAdminLink('AdminMarketPlaceBillings').'&id_ets_mp_seller_billing='.(int)$billing['id_ets_mp_seller_billing'].'&dowloadpdf=yes"'.' title="'.$billing['reference'].'"><i class="icon-pdf icon icon-pdf fa fa-file-pdf-o"></i><'.'/'.'a'.'>';
                if(!$billing['date_due'])
                    $billing['date_due'] ='--';
            }
        }
        $paggination->text =  $this->l('Showing {start} to {end} of {total} ({pages} Pages)');
        $listData = array(
            'name' => 'ms_billings',
            'icon' => 'fa fa-bank',
            'actions' => array('purchased','delete'),
            'currentIndex' => $this->context->link->getAdminLink(Tools::getValue('controller')).(Tools::isSubmit('viewseller') && Tools::getValue('id_seller') ? '&viewseller=1&id_seller='.(int)Tools::getValue('id_seller'):''),
            'identifier' => 'id_ets_mp_seller_billing',
            'show_toolbar' => true,
            'show_action' => true,
            'title' => $this->l('Membership'),
            'fields_list' => $fields_list,
            'field_values' => $billings,
            'paggination' => $paggination->render(),
            'filter_params' => $module->getFilterParams($fields_list,'ms_billings'),
            'show_reset' =>$show_resset,
            'totalRecords' => $totalRecords,
            'sort'=> Tools::getValue('sort','id_ets_mp_seller_billing'),
            'show_add_new'=> true,
            'link_new' => $this->context->link->getAdminLink('AdminMarketPlaceBillings').'&addnewbillng=1', // $this->context->link->getAdminLink(Tools::getValue('controller')).'&addnewbillng=1'.(Tools::isSubmit('viewseller') && Tools::getValue('id_seller') ? '&viewseller=1&id_seller='.(int)Tools::getValue('id_seller'):''),
            'sort_type' => Tools::getValue('sort_type','desc'),
        ); 
        return $module->renderList($listData);
    }
    public function l($string)
    {
        return Translate::getModuleTranslation('ets_marketplace', $string, pathinfo(__FILE__, PATHINFO_FILENAME));
    }
    public function getSellerBillings($filter='',$having="",$start=0,$limit=12,$order_by='',$total=false)
    {
        if($total)
            $sql = 'SELECT COUNT(DISTINCT b.id_ets_mp_seller_billing)';
        else
            $sql ='SELECT b.*,if(b.id_ets_mp_seller_billing = seller.id_billing AND b.active=0,seller.date_to,"") as date_due, CONCAT(customer.firstname," ",customer.lastname) as seller_name,customer.id_customer as id_customer_seller,seller.id_customer,seller.id_seller, seller_lang.shop_name,b.active as status';
        $sql .= ' FROM `'._DB_PREFIX_.'ets_mp_seller_billing` b
        LEFT JOIN `'._DB_PREFIX_.'customer` customer ON (customer.id_customer=b.id_customer)
        LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller` seller ON (customer.id_customer= seller.id_customer)
        LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_lang` seller_lang ON (seller.id_seller= seller_lang.id_seller AND seller_lang.id_lang="'.(int)$this->context->language->id.'")
        WHERE b.id_shop="'.(int)$this->context->shop->id.'"'.($filter ? $filter:'');
        if(!$total)
        {
            $sql .=' GROUP BY b.id_ets_mp_seller_billing ';
            if($having)
                $sql .= ' HAVING 1 '.$having;
            $sql .= ($order_by ? ' ORDER By '.$order_by :'');
            $sql .= ' LIMIT '.(int)$start.','.(int)$limit;
        }
        if($total)
            return Db::getInstance()->getValue($sql);
        else
        {
            return Db::getInstance()->executeS($sql);
        }
    }
    public function add($auto_date=true,$null_values=false)
    {
        do {
            $reference = Order::generateReference();
        } while (Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'ets_mp_seller_billing` WHERE reference="'.pSQL($reference).'"'));
        $this->reference= $reference;
        $this->id_shop = $this->context->shop->id;
        $res = parent::add($auto_date,$null_values);
        if($res && Configuration::get('ETS_MP_EMAIL_SELLER_BILLING_CREATED'))
        {
            $seller = Ets_mp_seller::_getSellerByIdCustomer($this->id_customer,$this->context->language->id);
            $payment_information = Configuration::get('ETS_MP_SELLER_PAYMENT_INFORMATION',$this->context->language->id);
            $str_search = array('[shop_id]','[shop_name]','[seller_name]','[seller_email]');
            $str_replace = array($seller->id,$seller->shop_name,$seller->seller_email,$seller->seller_email);
            $data = array(
                '{seller_name}' => $seller->seller_name,
                '{payment_information}' => str_replace($str_search,$str_replace,$payment_information),
            );
            $pdf = new PDF($this,'BillingPdf', Context::getContext()->smarty);
            $file_attachment = array();
            $file_attachment['content'] =$pdf->render(false);
            $file_attachment['name'] = $this->getBillingNumberInvoice(). '.pdf';
            $file_attachment['mime'] = 'application/pdf';
            $subjects = array(
                'translation' => $this->l('New billing created'),
                'origin'=> 'New billing created',
                'specific'=>'billing',
            );
            Ets_marketplace::sendMail('to_seller_billing_created',$data,$seller->seller_email,$subjects,$seller->seller_name,$file_attachment);
        }
        return $res;
    }
    public function getBillingNumberInvoice(){
        return $this->reference;
    }
}