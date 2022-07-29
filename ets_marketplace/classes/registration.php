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
class Ets_mp_registration extends ObjectModel
{
    public $id_customer;
    public $id_shop;
	public $shop_name;
    public $shop_description;
	public $shop_address;
    public $shop_phone;
    public $vat_number;
    public $shop_logo;
    public $shop_banner;
    public $banner_url;
    public $link_facebook;
    public $link_instagram;
    public $link_google;
    public $link_twitter;
    public $latitude;
    public $longitude;
    public $message_to_administrator;
    public $reason;
    public $comment;
    public $active;
    public $date_add;
    public $date_upd;
    public static $definition = array(
		'table' => 'ets_mp_registration',
		'primary' => 'id_registration',
		'multilang' => false,
		'fields' => array(
			'id_customer' => array('type' => self::TYPE_INT),
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'), 
            'shop_name' =>	array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),            
            'shop_description' =>	array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
            'shop_address'  => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'), 
            'shop_phone'  => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'), 
            'vat_number'  => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'), 
            'shop_logo'  => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'), 
            'shop_banner'  => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'), 
            'banner_url'  => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'), 
            'link_facebook'  => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'), 
            'link_instagram'  => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'), 
            'link_google'  => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'), 
            'link_twitter'  => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
            'message_to_administrator' => array('type' =>   self::TYPE_STRING,'validate' => 'isCleanHtml'),   
            'latitude' => array('type'=>self::TYPE_FLOAT),
            'longitude' => array('type'=>self::TYPE_FLOAT),
            'active' => array('type'=> self::TYPE_INT),
            'reason' => array('type'=>self::TYPE_STRING),
            'comment' => array('type'=>self::TYPE_STRING),
            'date_add' =>	array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
            'date_upd' =>	array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),    
        )
	);
    public	function __construct($id_item = null, $id_lang = null, $id_shop = null)
	{
		parent::__construct($id_item, $id_lang, $id_shop);
        $this->context = Context::getContext();
        $customer = new Customer($this->id_customer);
        $this->seller_email = $customer->email;
        $this->seller_name = $customer->firstname.' '.$customer->lastname;
        $this->id_language = $customer->id;
	}
    static public function _getRegistration()
    {
        $context = Context::getContext();
        if($id_registration = Db::getInstance()->getValue('SELECT id_registration FROM `'._DB_PREFIX_.'ets_mp_registration` WHERE id_customer="'.(int)$context->customer->id.'" AND id_shop="'.(int)$context->shop->id.'"'))
        {
            return new Ets_mp_registration($id_registration);
        }
        return false;
    }
    static public function _getRegistrations($filter='',$sort='',$start=0,$limit=10,$total=false)
    {
        if($total)
        {
            $sql = 'SELECT COUNT(*) FROM `'._DB_PREFIX_.'ets_mp_registration` r
                LEFT JOIN `'._DB_PREFIX_.'customer` customer ON (r.id_customer=customer.id_customer)
            WHERE r.id_shop="'.(int)Context::getContext()->shop->id.'" '.$filter;
            return Db::getInstance()->getValue($sql); 
        }
        $sql = 'SELECT r.*,CONCAT(customer.firstname," ", customer.lastname) as seller_name,customer.email as seller_email FROM `'._DB_PREFIX_.'ets_mp_registration` r
                LEFT JOIN `'._DB_PREFIX_.'customer` customer ON (r.id_customer=customer.id_customer)
        WHERE r.id_shop="'.(int)Context::getContext()->shop->id.'" '.$filter. ''
        .($sort ? ' ORDER BY '.$sort: ' ORDER BY r.id_registration DESC')
        .' LIMIT '.(int)$start.','.(int)$limit.'';
        return Db::getInstance()->executeS($sql);
    }
    public function _renderSellersRegistration()
    {
        $module = Module::getInstanceByName('ets_marketplace');
        if(Tools::getValue('del')=='yes' && $id_registration = (int)Tools::getValue('id_registration'))
        {
            $registration = new Ets_mp_registration($id_registration);
            if($registration->delete())
            {
                $this->context->cookie->success_message = $this->l('Deleted successfully');
                Tools::redirectAdmin($this->context->link->getAdminLink(Tools::getValue('controller')).'&list=true');
            }
        }
        if(!Tools::isSubmit('post_filter') &&  Tools::isSubmit('saveStatusRegistration') && $id_registration = (int)Tools::getValue('id_registration'))
        {
            $registration = new Ets_mp_registration($id_registration);
            $seller = Db::getInstance()->getValue('SELECT id_seller FROM `'._DB_PREFIX_.'ets_mp_seller` WHERE id_customer='.(int)$registration->id_customer);
            $active_old = $registration->active;
            if($seller)
            {
                die(
                    Tools::jsonEncode(
                        array(
                            'errors' => $this->l('Seller created'),
                        )
                    )
                );
            }
            $registration->active = Tools::getValue('active_registration');
            if((!$reason=Tools::getValue('reason')) || Validate::isCleanHtml($reason))
                $registration->reason= $reason;
            if((!$comment=Tools::getValue('comment')) || Validate::isCleanHtml($comment))
                $registration->comment= $comment;
            if($registration->update())
            {
                if($registration->active!=$active_old)
                {
                    if(Configuration::get('ETS_MP_EMAIL_SELLER_APPLICATION_APPROVED_OR_DECLINED'))
                    {
                        $data =array(
                            '{seller_name}' => $registration->seller_name,
                            '{application_declined_reason}' => $reason,
                        );
                        if($registration->active==1)
                        {
                            $subjects = array(
                                'translation' => $this->l('Application has been approved'),
                                'origin'=> 'Application has been approved',
                                'specific'=>'registration'
                            );
                            Ets_marketplace::sendMail('to_seller_application_approved',$data,$registration->seller_email,$subjects,$registration->seller_name);
                        }
                        else
                        {
                            $subjects = array(
                                'translation' => $this->l('Application has been declined'),
                                'origin'=> 'Application has been declined',
                                'specific'=>'registration'
                            );
                            Ets_marketplace::sendMail('to_seller_application_declined',$data,$registration->seller_email,$subjects,$registration->seller_name);
                        }
                    }
                }
                if(Tools::isSubmit('ajax'))
                {
                    die(
                        Tools::jsonEncode(
                            array(
                                'success' => $this->l('Updated status successfully'),
                                'status' => $registration->active ? '<span class="ets_mp_status approved">'.$this->l('Approved').'</span>' : '<span class="ets_mp_status declined">'.$this->l('Declined').'</span>',
                                'id_seller' => $registration->id,
                                'seller' => $seller ? true: false,
                            )
                        )
                    );
                }
                $this->context->cookie->success_message = $this->l('Updated status successfully');
            }
        }
        if(Tools::isSubmit('viewets_registration') && $id_registration = Tools::getValue('id_registration'))
        {
           return $this->_renderFormSellersRegistration(); 
        }
        $fields_list = array(
            'id_registration' => array(
                'title' => $this->l('ID'),
                'width' => 40,
                'type' => 'text',
                'sort' => true,
                'filter' => true,
            ),
            'seller_name' => array(
                'title' => $this->l('Customer name'),
                'type' => 'text',
                'sort' => true,
                'filter' => true,
                'strip_tag'=>false,
            ),
            'seller_email' => array(
                'title' => $this->l('Customer email'),
                'type' => 'text',
                'sort' => true,
                'filter' => true
            ),
            'message_to_administrator' => array(
                'title' => $this->l('Introduction'),
                'type'=>'text',
            ),
            'active' => array(
                'title' => $this->l('Status'),
                'type' => 'select',
                'sort' => true,
                'filter' => true,
                'strip_tag' => false,
                'filter_list' => array(
                    'id_option' => 'active',
                    'value' => 'title',
                    'list' => array(
                        0 => array(
                            'active' => 1,
                            'title' => $this->l('Approved')
                        ),
                        1 => array(
                            'active' => 0,
                            'title' => $this->l('Declined')
                        ),
                        2 => array(
                            'active' => -1,
                            'title' => $this->l('Pending'),
                        )
                    )
                )
            ),
        );
        //Filter
        $show_resset = false;
        $filter = "";
        if(Tools::getValue('id_registration') && !Tools::isSubmit('saveStatusRegistration') && !Tools::isSubmit('del'))
        {
            $filter .= ' AND r.id_registration="'.(int)Tools::getValue('id_registration').'"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('seller_name')))
        {
            $filter .=' AND CONCAT(customer.firstname," ",customer.lastname) LIKE "%'.pSQL(trim(Tools::getValue('seller_name'))).'%"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('seller_email')))
        {
            $filter .=' AND customer.email LIKE "%'.pSQL(trim(Tools::getValue('seller_email'))).'%"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('shop_name')))
        {
            $filter .= ' AND r.shop_name LIKE "%'.pSQL(trim(Tools::getValue('shop_name'))).'%"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('shop_description')))
        {
            $filter .= ' AND r.shop_description = "%'.trim(Tools::getValue('shop_description')).'%"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('active'))!='')
        {
            $filter .= ' AND r.active="'.(int)Tools::getValue('active').'"';
            $show_resset=true;
        }
        //Sort
        $sort = "";
        if(Tools::getValue('sort'))
        {
            switch (Tools::getValue('sort')) {
                case 'id_registration':
                    $sort .=' r.id_registration';
                    break;
                case 'seller_name':
                    $sort .= ' seller_name';
                    break;
                case 'seller_email':
                    $sort .= ' seller_email';
                    break;
                case 'shop_name':
                    $sort .= 'r.shop_name';
                    break;
                case 'shop_description':
                    $sort .= 'r.shop_description';
                    break;
                case 'active':
                    $sort .='r.active';
                    break;
            }
            if($sort && ($sort_type=Tools::getValue('sort_type')) && in_array($sort_type,array('acs','desc')))
                $sort .= ' '.$sort_type;  
        }
        //Paggination
        $page = (int)Tools::getValue('page') && (int)Tools::getValue('page') > 0 ? (int)Tools::getValue('page') : 1;
        $totalRecords = (int) Ets_mp_registration::_getRegistrations($filter,$sort,0,0,true);;
        $paggination = new Ets_mp_paggination_class();            
        $paggination->total = $totalRecords;
        $paggination->url = $this->context->link->getAdminLink(Tools::getValue('controller')).'&page=_page_'.$module->getFilterParams($fields_list,'ets_registration');
        $paggination->limit =  20;
        $totalPages = ceil($totalRecords / $paggination->limit);
        if($page > $totalPages)
            $page = $totalPages;
        $paggination->page = $page;
        $start = $paggination->limit * ($page - 1);
        if($start < 0)
            $start = 0;
        $sellers_registration = Ets_mp_registration::_getRegistrations($filter,$sort,$start,$paggination->limit,false);
        if($sellers_registration)
        {
            foreach($sellers_registration as &$seller)
            {
                $seller['child_view_url'] = $this->context->link->getAdminLink(Tools::getValue('controller')).'&viewets_registration=1&id_registration='.$seller['id_registration'];
                $seller['status']= $seller['active'];
                if($seller['active']==-1)
                    $seller['active'] = '<'.'span'.' class="ets_mp_status pending">'.$this->l('Pending').'<'.'/'.'span'.'>'; 
                elseif($seller['active']==0)
                    $seller['active'] = '<'.'span'.' class="ets_mp_status declined">'.$this->l('Declined').'<'.'/'.'span'.'>';
                elseif($seller['active']==1)
                {
                    $seller['active'] = '<'.'span'.' class="ets_mp_status approved">'.$this->l('Approved').'<'.'/'.'span'.'>';
                }
                if(version_compare(_PS_VERSION_, '1.7.6', '>='))
                {
                    $sfContainer = call_user_func(array('\PrestaShop\PrestaShop\Adapter\SymfonyContainer','getInstance'));
                	if (null !== $sfContainer) {
                		$sfRouter = $sfContainer->get('router');
                		$link_customer= $sfRouter->generate(
                			'admin_customers_view',
                			array('customerId' => $seller['id_customer'])
                		);
                        $seller['seller_name'] = '<'.'a hr'.'ef="'.$link_customer.'">'.$seller['seller_name'].'<'.'/'.'a'.'>';
                	}
                }
                else
                    $seller['seller_name'] = '<'.'a hr'.'ef="'.$this->context->link->getAdminLink('AdminCustomers').'&id_customer='.(int)$seller['id_customer'].'&viewcustomer">'.$seller['seller_name'].'<'.'/'.'a'.'>';
                $seller['has_seller'] = Db::getInstance()->getValue('SELECT id_seller FROM `'._DB_PREFIX_.'ets_mp_seller` WHERE id_customer='.(int)$seller['id_customer']);
            }
        }
        $paggination->text =  $this->l('Showing {start} to {end} of {total} ({pages} Pages)');
        $paggination->style_links = $this->l('links');
        $paggination->style_results = $this->l('results');
        $listData = array(
            'name' => 'ets_registration',
            'actions' => array('approve_registration','decline_registration'),
            'icon' => 'icon-sellers_registration',
            'currentIndex' => $this->context->link->getAdminLink(Tools::getValue('controller')),
            'identifier' => 'id_registration',
            'show_toolbar' => true,
            'show_action' => true,
            'title' => $this->l('Applications'),
            'fields_list' => $fields_list,
            'field_values' => $sellers_registration,
            'paggination' => $paggination->render(),
            'filter_params' => $module->getFilterParams($fields_list,'ets_registration'),
            'show_reset' =>$show_resset,
            'totalRecords' => $totalRecords,
            'sort'=> Tools::getValue('sort','id_registration'),
            'sort_type' => Tools::getValue('sort_type','desc'),
        );            
        return  $module->renderList($listData);
    }
    public function _renderFormSellersRegistration()
    {
        $registration = new Ets_mp_registration(Tools::getValue('id_registration'));
        $seller= Db::getInstance()->getValue('SELECT id_seller FROM `'._DB_PREFIX_.'ets_mp_seller` WHERE id_customer='.(int)$registration->id_customer);
        if(version_compare(_PS_VERSION_, '1.7.6', '>='))
        {
            $sfContainer = call_user_func(array('\PrestaShop\PrestaShop\Adapter\SymfonyContainer','getInstance'));
        	if (null !== $sfContainer) {
        		$sfRouter = $sfContainer->get('router');
        		$link_customer= $sfRouter->generate(
        			'admin_customers_view',
        			array('customerId' => $registration->id_customer)
        		);
        	}
        }
        else
            $link_customer= $this->context->link->getAdminLink('AdminCustomers').'&id_customer='.(int)$registration->id_customer.'&viewcustomer">';
        $this->context->smarty->assign(
            array(
                'registration' => $registration,
                'customer'=> new Customer($registration->id_customer),
                'link'=> $this->context->link,
                'has_seller' => $seller ? true :false,
                'link_customer' =>$link_customer,
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.'ets_marketplace/views/templates/hook/shop/registration_detail.tpl');
    }
    public function l($string)
    {
        return Translate::getModuleTranslation('ets_marketplace', $string, pathinfo(__FILE__, PATHINFO_FILENAME));
    }
    public function delete()
    {
        $result = parent::delete();
        if($result)
        {
            if($this->shop_logo && !Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'ets_mp_seller` WHERE shop_logo="'.pSQL($this->shop_logo).'"'))
            {
                if(file_exists(_PS_IMG_DIR_.'mp_seller/'.$this->shop_logo))
                    @unlink(_PS_IMG_DIR_.'mp_seller/'.$this->shop_logo);
            }
        }
        return $result;
    }
}