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
* needs please, contact us for extra customization service at an affordable price
*
*  @author ETS-Soft <etssoft.jsc@gmail.com>
*  @copyright  2007-2020 ETS-Soft
*  @license    Valid for 1 website (or project) for each purchase of license
*  International Registered Trademark & Property of ETS-Soft
*/

if (!defined('_PS_VERSION_')) {
    exit();
}

class Ets_mp_commission_usage extends ObjectModel
{
    /**
     * @var float
     */
    public $amount;
    public $reference;
    /**
     * @var int
     */
    public $id_shop;
    /**
     * @var int
     */
    public $id_voucher;
    /**
     * @var int
     */
    public $id_order;
    /**
     * @var int
     */
    public $id_withdraw;
    /**
     * @var int
     */
    public $id_currency;
    /**
     * @var int
     */
    public $id_customer;
    public $status;
    /**
     * @var string
     */
    public $note;
    /**
     * @var int
     */
    public $deleted;
    public $date_add;
    public static $definition = array(
        'table' => 'ets_mp_commission_usage',
        'primary' => 'id_ets_mp_commission_usage',
        'fields' => array(
            'reference' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isString'
            ),
            'amount' => array(
                'type' => self::TYPE_FLOAT,
                'validate' => 'isFloat'
            ),
            'id_customer' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt'
            ),
            'id_shop' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt'
            ),
            'id_voucher' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isUnsignedInt'
            ),
            'id_withdraw' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isUnsignedInt'
            ),
            'id_order' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isUnsignedInt'
            ),
            'id_currency' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt'
            ),
            'status' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            ),
            'note' => array(
                'type' => self::TYPE_STRING,
                'validate' => 'isString'
            ),
            'date_add' => array(
                'type' => self::TYPE_DATE,
                'validate' => 'isDateFormat',
                'allow_null' => true
            ),
            'deleted' => array(
                'type' => self::TYPE_INT,
                'validate' => 'isInt'
            )
        )
    );
    public	function __construct($id_item = null, $id_lang = null, $id_shop = null)
	{
		parent::__construct($id_item, $id_lang, $id_shop);
        $this->context= Context::getContext();
	}
    public function add($auto_date=true,$null_value=true)
    {
        do {
            $reference = Ets_mp_commission_usage::generateReference();
        } while (Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'ets_mp_commission_usage` WHERE reference="'.pSQL($reference).'"'));
        $this->reference = $reference;
        return parent::add($auto_date,$null_value);
    }
    public function update($null_value=true)
    {
        return parent::update($null_value);
    }
    public function _renderCommissionUsage($id_seller=false)
    {
        $module = Module::getInstanceByName('ets_marketplace');
        if((Tools::isSubmit('returnms_commissions_usage') || Tools::isSubmit('deductms_commissions_usage') || Tools::getValue('del')=='yes') && $id_ets_mp_commission_usage =(int)Tools::getValue('id_ets_mp_commission_usage'))
        {
            $commission_ugage = new Ets_mp_commission_usage($id_ets_mp_commission_usage);
            if(Tools::getValue('del')=='yes')
            {
                if($commission_ugage->delete())
                    $this->context->cookie->success_message = $this->l('Deleted successfully');
            }
            if(Tools::isSubmit('returnms_commissions_usage'))
            {
                $commission_ugage->status=0;
                if($commission_ugage->update())
                    $this->context->cookie->success_message = $this->l('Returned successfully');
            }
            if(Tools::isSubmit('deductms_commissions_usage'))
            {
                $commission_ugage->status=1;
                if($commission_ugage->update())
                    $this->context->cookie->success_message = $this->l('Deducted successfully');
            }
            Tools::redirectAdmin($this->context->link->getAdminLink(Tools::getValue('controller')).(Tools::isSubmit('viewseller')? '&viewseller=1&id_seller='.(int)Tools::getValue('id_seller'):''));
        }
        $commistion_usage_status=array(
            array(
                'id' => '0',
                'name' => $this->l('Refunded')
            ),
            array(
                'id' => '1',
                'name' => $this->l('Deducted')
            ),
        );
        $fields_list = array(
            'id_ets_mp_commission_usage' => array(
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
                'strip_tag' => false,
            ),
            'amount' => array(
                'title' => $this->l('Amount'),
                'type' => 'int',
                'sort' => true,
                'filter' => true,
                'strip_tag'=> false,
            ),
            'status' => array(
                'title' => $this->l('Status'),
                'type' => 'select',
                'sort' => true,
                'filter' => true,
                'strip_tag' => false,
                'filter_list' => array(
                    'list' => $commistion_usage_status,
                    'id_option' => 'id',
                    'value' => 'name',
                ),
            ),
            'date_add' => array(
                'title' => $this->l('Date'),
                'type' => 'date',
                'sort' => true,
                'filter' => true
            )
        );
        //Filter
        $show_resset = false;
        $filter = "";
        $having = "";
        if($id_seller)
        {
            $filter .=' AND uc.id_seller='.(int)$id_seller;
            unset($fields_list['seller_name']);
        }
        if(Tools::isSubmit('ets_mp_submit_ms_commissions_usage'))
        {
            if(Tools::getValue('id_ets_mp_commission_usage'))
            {
                $filter .=' AND uc.id_ets_mp_commission_usage="'.(int)Tools::getValue('id_ets_mp_commission_usage').'"';
                $show_resset=true;
            }
            if(Tools::getValue('seller_name'))
            {
                $filter .= ' AND seller.seller_name like "%'.pSQL(Tools::getValue('seller_name')).'%"';
                $show_resset =true;
            }
            if(trim(Tools::getValue('amount_min')))
            {
                $filter .=' AND uc.amount >= "'.(float)Tools::getValue('amount_min').'"';
                $show_resset = true;
            }
            if(trim(Tools::getValue('amount_max')))
            {
                $filter .=' AND uc.amount <= "'.(float)Tools::getValue('amount_max').'"';
                $show_resset = true;
            }
            if(trim(Tools::getValue('status'))!=='')
            {
                $filter .=' AND uc.status = "'.(int)Tools::getValue('status').'"';
                $show_resset = true;
            }
            if(trim(Tools::getValue('date_add_min')))
            {
                $filter .=' AND uc.date_add <="'.pSQL(Tools::getValue('date_add_min')).'"';
                $show_resset = true;
            }
            if(trim(Tools::getValue('date_add_max')))
            {
                $filter .=' AND uc.date_add >="'.pSQL(Tools::getValue('date_add_max')).'"';
                $show_resset = true;
            }
            if(trim(Tools::getValue('note')))
            {
                $filter .=' AND uc.note LIKE "%'.pSQL(Tools::getValue('note')).'%"';
                $show_resset = true;
            }
        }
        //Sort
        $sort = "";
        if(Tools::getValue('sort','id_ets_mp_commission_usage'))
        {
            switch (Tools::getValue('sort','id_ets_mp_commission_usage')) {
                case 'id_ets_mp_commission_usage':
                    $sort .='uc.id_ets_mp_commission_usage';
                    break;
                case 'seller_name':
                    $sort .='seller.seller_name';
                    break;
                case 'amount':
                    $sort .= 'uc.amount';
                    break;
                case 'date_add':
                    $sort .= 'uc.date_add';
                    break;
                case 'status':
                    $sort .=' uc.status';
                    break;
                case 'note':
                    $sort .=' uc.note';
                    break;
            }
            if($sort && ($sort_type=Tools::getValue('sort_type','desc')) && in_array($sort_type,array('acs','desc')))
                $sort .= ' '.trim($sort_type);  
        }
        //Paggination
        $page = (int)Tools::getValue('page') && (int)Tools::getValue('page') > 0 ? (int)Tools::getValue('page') : 1;
        $totalRecords = (int) $this->getSellerCommissionsUsage($filter,$having,0,0,'',true);
        $paggination = new Ets_mp_paggination_class();            
        $paggination->total = $totalRecords;
        $paggination->url = $this->context->link->getAdminLink(Tools::getValue('controller')).'&page=_page_'.(Tools::isSubmit('viewseller') && Tools::getValue('id_seller') ? '&viewseller=1&id_seller='.(int)Tools::getValue('id_seller'):'').$module->getFilterParams($fields_list,'ms_commissions_usage');
        $paggination->limit =  20;
        $totalPages = ceil($totalRecords / $paggination->limit);
        if($page > $totalPages)
            $page = $totalPages;
        $paggination->page = $page;
        $start = $paggination->limit * ($page - 1);
        if($start < 0)
            $start = 0;
        $commission_ugages = $this->getSellerCommissionsUsage($filter,$having, $start,$paggination->limit,$sort,false);
        if($commission_ugages)
        {
            foreach($commission_ugages as &$commission_ugage)
            {
                $commission_ugage['amount'] = Tools::displayPrice($commission_ugage['amount']);   
                if($commission_ugage['note'])
                    $commission_ugage['amount'] .= '<'.'b'.'r /'.'>'.'<'.'i'.'>'.$commission_ugage['note'].'<'.'/'.'i'.'>';
                $commission_ugage['status_val'] = $commission_ugage['status'];
                if($commission_ugage['status']==0)
                    $commission_ugage['status'] = '<'.'span'.' class="ets_mp_status refunded">'.$this->l('Refunded').'<'.'/'.'span'.'>';
                elseif($commission_ugage['status']==1)
                    $commission_ugage['status'] = '<'.'span'.' class="ets_mp_status deducted">'.$this->l('Deducted').'<'.'/'.'span'.'>';
                if($commission_ugage['id_seller'])
                {
                    $commission_ugage['seller_name'] = '<'.'a hr'.'ef="'.$this->context->link->getAdminLink('AdminMarketPlaceSellers').'&viewseller=1&id_seller='.(int)$commission_ugage['id_seller'].'">'.$commission_ugage['seller_name'].'<'.'/'.'a'.'>';
                }                
            }
        }
        $paggination->text =  $this->l('Showing {start} to {end} of {total} ({pages} Pages)');
        $paggination->style_links = $this->l('links');
        $paggination->style_results = $this->l('results');
        $listData = array(
            'name' => 'ms_commissions_usage',
            'actions' => array('approved','delete'),
            'icon' => 'fa fa-percent',
            'currentIndex' => $this->context->link->getAdminLink(Tools::getValue('controller')).(Tools::isSubmit('viewseller') && Tools::getValue('id_seller') ? '&viewseller=1&id_seller='.(int)Tools::getValue('id_seller'):''),
            'identifier' => 'id_ets_mp_commission_usage',
            'show_toolbar' => true,
            'show_action' => true,
            'title' => $this->l('Commission'),
            'fields_list' => $fields_list,
            'field_values' => $commission_ugages,
            'paggination' => $paggination->render(),
            'filter_params' => $module->getFilterParams($fields_list,'ms_commissions_usage'),
            'show_reset' =>$show_resset,
            'totalRecords' => $totalRecords,
            'sort'=> Tools::getValue('sort','id_ets_mp_commission_usage'),
            'show_add_new'=> false,
            'sort_type' => Tools::getValue('sort_type','desc'),
        );        
        return $module->renderList($listData);
    }
    public function getSellerCommissionsUsage($filter='',$having="",$start=0,$limit=12,$order_by='',$total=false)
    {
        if($total)
            $sql = 'SELECT COUNT(DISTINCT uc.id_ets_mp_commission_usage)';
        else
            $sql ='SELECT uc.*,seller.id_seller,CONCAT(customer.firstname," ",customer.lastname) as seller_name,seller_lang.shop_name';
        $sql .= ' FROM `'._DB_PREFIX_.'ets_mp_commission_usage` uc
        INNER JOIN `'._DB_PREFIX_.'ets_mp_seller` seller ON (uc.id_seller= seller.id_seller)
        INNER JOIN `'._DB_PREFIX_.'ets_mp_seller_lang` seller_lang ON (seller.id_seller= seller_lang.id_seller AND seller_lang.id_lang="'.(int)$this->context->language->id.'")
        LEFT JOIN `'._DB_PREFIX_.'customer` customer ON (customer.id_customer=seller.id_customer)
        WHERE uc.id_shop="'.(int)$this->context->shop->id.'"'.($filter ? $filter:'');
        if(!$total)
        {
            $sql .=' GROUP BY uc.id_ets_mp_commission_usage '.($order_by ? ' ORDER By '.$order_by :'');
            if($having)
                $sql .= ' HAVING 1 '.$having;
            $sql .= ' LIMIT '.(int)$start.','.(int)$limit;
        }
        if($total)
            return Db::getInstance()->getValue($sql);
        else
        {
            return Db::getInstance()->executeS($sql);
        }
    }
    public function l($string)
    {
        return Translate::getModuleTranslation('ets_marketplace', $string, pathinfo(__FILE__, PATHINFO_FILENAME));
    }
    public static function generateReference()
    {
        return Tools::strtoupper(Tools::passwdGen(9, 'NO_NUMERIC'));
    }
}