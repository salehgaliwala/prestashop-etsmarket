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

if (!defined('_PS_VERSION_')) {
    exit();
}
//$withdraw_status=array(
//    array(
//        'id' => '-1',
//        'name' => $this->l('Declined')
//    ),
//    array(
//        'id' => '0',
//        'name' => $this->l('Pending')
//    ),
//    array(
//        'id' => '1',
//        'name' => $this->l('Approved')
//    )
//);
class Ets_mp_withdraw extends ObjectModel
{
    public $id_ets_mp_payment_method;
    public $status;
    public $fee;
    public $fee_type;
    public $date_add;
    public $processing_date;
    public static $definition = array(
        'table' => 'ets_mp_withdrawal',
        'primary' => 'id_ets_mp_withdrawal',
        'fields' => array(
            'id_ets_mp_payment_method' => array(
                'type' => self::TYPE_INT,
            ),
            'status' => array(
                'type' => self::TYPE_INT,
            ),
            'fee' => array(
                'type' => self::TYPE_FLOAT,
            ),
            'fee_type' => array(
                'type' => self::TYPE_STRING,
            ),
            'date_add' => array(
                'type' => self::TYPE_DATE
            ),
            'processing_date'=>array(
                'type' => self::TYPE_DATE
            ),
        )
    );
    public	function __construct($id_item = null, $id_lang = null, $id_shop = null)
	{
		parent::__construct($id_item, $id_lang, $id_shop);
        $this->context= Context::getContext();
	}
    public static function _getWithdrawals($filter='',$sort='',$start=0,$limit=10,$total=false)
    {
        $context = Context::getContext();
        if($total)
        {
            $sql = 'SELECT COUNT(DISTINCT w.id_ets_mp_withdrawal) FROM ';
        }
        else
        {
            $sql = 'SELECT w.id_ets_mp_withdrawal,w.status,CONCAT(customer.firstname," ",customer.lastname) as seller_name,customer.id_customer as id_customer_seller,seller.id_seller,pml.title,cu.amount,cu.note,cu.id_customer as seller_id FROM ';
        }
        $sql .= _DB_PREFIX_.'ets_mp_withdrawal w
        INNER JOIN `'._DB_PREFIX_.'ets_mp_commission_usage` cu ON (w.id_ets_mp_withdrawal = cu.id_withdraw)
        lEFT JOIN `'._DB_PREFIX_.'customer` customer ON (customer.id_customer=cu.id_customer)
        LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller` seller ON (seller.id_customer=customer.id_customer)
        LEFT JOIN `'._DB_PREFIX_.'ets_mp_payment_method_lang` pml ON (w.id_ets_mp_payment_method=pml.id_ets_mp_payment_method AND pml.id_lang="'.(int)$context->language->id.'")
        LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_lang` seller_lang ON (seller.id_seller=seller_lang.id_seller AND seller_lang.id_lang="'.(int)$context->language->id.'")
        WHERE cu.id_shop="'.(int)$context->shop->id.'"'.($filter);
        if(!$total)
        {
            $sql .=' GROUP BY w.id_ets_mp_withdrawal '.($sort ? ' ORDER By '.$sort :'');
            $sql .= ' LIMIT '.(int)$start.','.(int)$limit;
            return Db::getInstance()->executeS($sql);
        }
        else
            return Db::getInstance()->getValue($sql);
    }
    public function _renderWithdraw($id_seller=false)
    {
        $module = Module::getInstanceByName('ets_marketplace');
        if((Tools::isSubmit('approveets_withdraw') || Tools::isSubmit('returnets_withdraw')|| Tools::isSubmit('deductets_withdraw') || Tools::getValue('del')=='yes') && $id_ets_mp_withdrawal= (int)Tools::getValue('id_ets_mp_withdrawal'))
        {
            $submit= true;
            $withdraw_class = new Ets_mp_withdraw($id_ets_mp_withdrawal);
            $id_ets_mp_commission_usage = Db::getInstance()->getValue('SELECT id_ets_mp_commission_usage FROM '._DB_PREFIX_.'ets_mp_commission_usage WHERE id_withdraw='.(int)$id_ets_mp_withdrawal);
            $commissison_usage = new Ets_mp_commission_usage($id_ets_mp_commission_usage);
            if(Tools::getValue('del')=='yes')
            {
                if($withdraw_class->delete())
                {
                    $this->context->cookie->success_message = $this->l('Deleted successfully');
                    $commissison_usage->delete();
                }
                
            }
            else
            {
                if(Tools::isSubmit('approveets_withdraw'))
                {
                    $withdraw_class->status= 1;
                    $this->context->cookie->success_message = $this->l('Approved successfully');
                }
                elseif(Tools::isSubmit('returnets_withdraw'))
                {
                    $withdraw_class->status=-1;
                    $commissison_usage->status = 0;
                    $commissison_usage->note .= ($commissison_usage->note ? ' - ':'').$this->l('Returned commission');
                    $this->context->cookie->success_message = $this->l('Returned successfully');
                }
                elseif(Tools::isSubmit('deductets_withdraw'))
                {
                    $withdraw_class->status=-1;
                    $commissison_usage->status = 1;
                    $commissison_usage->note .= ($commissison_usage->note ? ' - ':'').$this->l('Deducted commission');
                    $this->context->cookie->success_message = $this->l('Deducted successfully');
                }
                if($withdraw_class->update())
                {
                    if($commissison_usage->id)
                    {
                        $commissison_usage->update();
                    }
                }
                
            }
            if(!Tools::isSubmit('viewwithdraw'))
                Tools::redirectAdmin($this->context->link->getAdminLink(Tools::getValue('controller')));
        }
        if(Tools::isSubmit('submitSaveNoteWithdrawal') && $id_ets_mp_commission_usage =(int)Tools::getValue('id_ets_mp_commission_usage'))
        {

            $commission_usage = new Ets_mp_commission_usage($id_ets_mp_commission_usage);
            $commission_usage->note = Tools::getValue('note');
            if($commission_usage->update())
                $this->context->cookie->success_message= $this->l('Updated note successfully');
            
        }
        if(Tools::isSubmit('viewwithdraw') && $id_ets_mp_withdrawal = Tools::getValue('id_ets_mp_withdrawal'))
        {   
            $withdraw_detail = Db::getInstance()->getRow('SELECT w.id_ets_mp_withdrawal,seller.id_seller,w.fee,cu.id_ets_mp_commission_usage,cu.note, cu.amount,(cu.amount-w.fee) as pay_amount, pml.title as payment_name,w.fee_type,w.status,w.date_add FROM `'._DB_PREFIX_.'ets_mp_withdrawal` w
            INNER JOIN `'._DB_PREFIX_.'ets_mp_commission_usage` cu ON (cu.id_withdraw =w.id_ets_mp_withdrawal)
            LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller` seller ON (seller.id_customer=cu.id_customer)
            LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_lang` seller_lang ON (seller_lang.id_seller=seller.id_seller AND seller_lang.id_lang="'.(int)$this->context->language->id.'")
            LEFT JOIN `'._DB_PREFIX_.'ets_mp_payment_method_lang` pml ON (pml.id_ets_mp_payment_method = w.id_ets_mp_payment_method)
            WHERE cu.id_shop = "'.(int)$this->context->shop->id.'" AND w.id_ets_mp_withdrawal="'.(int)$id_ets_mp_withdrawal.'"'); 
            $withdraw_fields = Db::getInstance()->executeS('SELECT wf.value,pmfl.title,wf.id_ets_mp_payment_method_field FROM `'._DB_PREFIX_.'ets_mp_withdrawal_field` wf
            LEFT JOIN `'._DB_PREFIX_.'ets_mp_payment_method_field_lang` pmfl ON (wf.id_ets_mp_payment_method_field=pmfl.id_ets_mp_payment_method_field AND pmfl.id_lang="'.(int)$this->context->language->id.'")
            WHERE wf.id_ets_mp_withdrawal='.(int)$id_ets_mp_withdrawal);
            if($withdraw_detail)
            {
                $this->context->smarty->assign(
                    array(
                        'withdraw_detail' => $withdraw_detail,
                        'link' => $this->context->link,
                        'seller' => new Ets_mp_seller($withdraw_detail['id_seller'],$this->context->language->id),
                        'withdraw_fields' => $withdraw_fields
                    )
                );
                return  $this->context->smarty->fetch(_PS_MODULE_DIR_.'ets_marketplace/views/templates/hook/detail_withdraw.tpl');
            }
            else
                Tools::redirectAdmin($this->context->link->getAdminLink(Tools::getValue('controller')));
        }
        $withdraw_status=array(
            array(
                'id' => '-1',
                'name' => $this->l('Declined')
            ),
            array(
                'id' => '0',
                'name' => $this->l('Pending')
            ),
            array(
                'id' => '1',
                'name' => $this->l('Approved')
            )
        );
        $fields_list = array(
            'id_ets_mp_withdrawal' => array(
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
                'strip_tag' =>false,
            ),
            'title' => array(
                'title' => $this->l('Payment name'),
                'type' => 'text',
                'sort' => true,
                'filter' => true
            ),
            'amount' => array(
                'title' => $this->l('Amount'),
                'type' => 'int',
                'sort' => true,
                'filter' => true
            ),
            'status' => array(
                'title' => $this->l('Status'),
                'type' => 'select',
                'sort' => true,
                'filter' => true,
                'strip_tag' => false,
                'filter_list' => array(
                    'list' => $withdraw_status,
                    'id_option' => 'id',
                    'value' => 'name',
                ),
            ),
            'note' => array(
                'title'=> $this->l('Description'),
                'type' => 'text',
                'sort' => true,
                'filter' => true
            ),
        );
        //Filter
        $show_resset = false;
        $filter = "";
        if(Tools::getValue('id_ets_mp_withdrawal') && !$submit)
        {
            $filter .= ' AND w.id_ets_mp_withdrawal="'.(int)Tools::getValue('id_ets_mp_withdrawal').'"';
            $show_resset = true;
        }
        if(Tools::getValue('seller_name'))
        {
            $filter .=' AND CONCAT(customer.firstname," ",customer.lastname) LIKE "%'.pSQL(Tools::getValue('seller_name')).'%"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('title')))
        {
            $filter .= ' AND pml.title LIKE "%'.pSQL(trim(Tools::getValue('title'))).'%"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('amount_min')))
        {
            $filter .= ' AND cu.amount >= "'.(float)Tools::getValue('amount_min').'"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('amount_max')))
        {
            $filter .= ' AND cu.amount <= "'.(float)Tools::getValue('amount_max').'"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('status'))!=='')
        {
            $filter .= ' AND w.status = "'.(int)Tools::getValue('status').'"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('note')))
        {
            $filter .=' AND cu.note LIKE "%'.pSQL(trim(Tools::getValue('note'))).'%"';
            $show_resset = true;
        }
        
        //Sort
        $sort = "";
        if(Tools::getValue('sort','id_ets_mp_withdrawal'))
        {
            switch (Tools::getValue('sort','id_ets_mp_withdrawal')) {
                case 'id_ets_mp_withdrawal':
                    $sort .=' w.id_ets_mp_withdrawal';
                    break;
                case 'seller_name':
                    $sort .= ' seller_name';
                    break;
                case 'title':
                    $sort .= ' pml.title';
                    break;
                case 'amount':
                    $sort .= ' cu.amount';
                    break;
                case 'status':
                    $sort .= ' w.status';
                    break;
                case 'note':
                    $sort.= ' cu.note';
                    break;
            }
            if($sort && ($sort_type=Tools::getValue('sort_type','desc')) && in_array($sort_type,array('acs','desc')))
                $sort .= ' '.$sort_type;  
        }
        //Paggination
        $page = (int)Tools::getValue('page') && (int)Tools::getValue('page') > 0 ? (int)Tools::getValue('page') : 1;
        $totalRecords = (int) self::_getWithdrawals($filter,$sort,0,0,true);
        $paggination = new Ets_mp_paggination_class();            
        $paggination->total = $totalRecords;
        $paggination->url = $this->context->link->getAdminLink(Tools::getValue('controller')).'&page=_page_'.$module->getFilterParams($fields_list,'ets_withdraw');
        $paggination->limit =  20;
        $totalPages = ceil($totalRecords / $paggination->limit);
        if($page > $totalPages)
            $page = $totalPages;
        $paggination->page = $page;
        $start = $paggination->limit * ($page - 1);
        if($start < 0)
            $start = 0;
        $withdraws= self::_getWithdrawals($filter,$sort,$start,$paggination->limit,false);
        if($withdraws)
        {
            foreach($withdraws as &$withdraw)
            {
                $withdraw['child_view_url'] = $this->context->link->getAdminLink(Tools::getValue('controller')).'&viewwithdraw&id_ets_mp_withdrawal='.(int)$withdraw['id_ets_mp_withdrawal'];
                $withdraw['amount'] = Tools::displayPrice($withdraw['amount'],new Currency(Configuration::get('PS_CURRENCY_DEFAULT')));
                if($withdraw['status']==0)
                    $withdraw['change_status'] = true;
                else
                    $withdraw['change_status'] = false;
                if($withdraw['status']==-1)
                    $withdraw['status'] = '<'.'span'.' class="ets_mp_status declined">'.$this->l('Declined').'<'.'/'.'span'.'>'; 
                elseif($withdraw['status']==0)
                    $withdraw['status'] = '<'.'span'.' class="ets_mp_status pending">'.$this->l('Pending').'<'.'/'.'span'.'>';
                elseif($withdraw['status']==1)
                    $withdraw['status'] = '<'.'span'.' class="ets_mp_status approved">'.$this->l('Approved').'<'.'/'.'span'.'>';
                if($withdraw['id_customer_seller'])
                {
                    $withdraw['seller_name'] = '<'.'a hr'.'ef="'.$module->getLinkCustomerAdmin($withdraw['id_customer_seller']).'">'.$withdraw['seller_name'].'<'.'/'.'a'.'>';
                }
                else
                    $withdraw['seller_name'] = '<'.'sp'.'an class="row_deleted">'.$this->l('Seller deleted').'<'.'/'.'span'.'>';
            }
        }
        $paggination->text =  $this->l('Showing {start} to {end} of {total} ({pages} Pages)');
        $paggination->style_links = $this->l('links');
        $paggination->style_results = $this->l('results');
        $listData = array(
            'name' => 'ets_withdraw',
            'actions' => array('view','approve','declined','delete'),
            'icon' => 'icon-withdraw',
            'currentIndex' => $this->context->link->getAdminLink(Tools::getValue('controller')),
            'identifier' => 'id_ets_mp_withdrawal',
            'show_toolbar' => true,
            'show_action' => true,
            'title' => $this->l('Withdrawals'),
            'fields_list' => $fields_list,
            'field_values' => $withdraws,
            'paggination' => $paggination->render(),
            'filter_params' => $module->getFilterParams($fields_list,'ets_withdraw'),
            'show_reset' =>$show_resset,
            'totalRecords' => $totalRecords,
            'sort'=> Tools::getValue('sort','id_ets_mp_withdrawal'),
            'sort_type' => Tools::getValue('sort_type','desc'),
        ); 
        unset($id_seller);         
        return  $module->renderList($listData);
    }
    public function add($auto_date=true,$null_values=false)
    {
        $res = parent::add($auto_date,$null_values);
        
        return $res;
    }
    public function update($null_values=false)
    {
        $status_old = Db::getInstance()->getValue('SELECT status FROM `'._DB_PREFIX_.'ets_mp_withdrawal` WHERE id_ets_mp_withdrawal='.(int)$this->id);
        $res = parent::update($null_values);
        if($status_old != $this->status && $res && Configuration::get('ETS_MP_EMAIL_SELLER_WITHDRAWAL_APPROVED'))
        {
            $withdrawal = $this->getWithdrawalDetail();
            if($withdrawal)
            {
                $data = array(
                    '{seller_name}' => $withdrawal['seller_name']?:$withdrawal['name'],
                    '{withdrawal_ID}' => $this->id,
                    '{amount}' => Tools::displayPrice($withdrawal['amount'],new Currency(Configuration::get('PS_CURRENCY_DEFAULT'))),
                    '{payment_method}'=>$withdrawal['payment_method'],
                    '{approved_date}' => date('Y-m-d H:i:s'),
                    '{declined_date}' => date('Y-m-d H:i:s'),
                    '{reason}' => ''
                );
                $email = $withdrawal['seller_email'] ?:$withdrawal['email'];
                if($this->status==1)
                {
                    $subjects = array(
                        'translation' => $this->l('Your withdrawal has been approved'),
                        'origin'=> 'Your withdrawal has been approved',
                        'specific'=>'ets_mp_withdraw'
                    );
                    Ets_marketplace::sendMail('to_seller_withdrawal_approved',$data,$email,$subjects,$withdrawal['seller_name']?:$withdrawal['name']);
                }
                else
                {
                    $subjects = array(
                        'translation' => $this->l('Your withdrawal has been declined'),
                        'origin'=> 'Your withdrawal has been declined',
                        'specific'=>'ets_mp_withdraw',
                    );
                    Ets_marketplace::sendMail('to_seller_withdrawal_declined',$data,$email,$subjects,$withdrawal['seller_name']?:$withdrawal['name']);
                }    
            }
        }
        return $res;
    }
    public function l($string)
    {
        return Translate::getModuleTranslation('ets_marketplace', $string, pathinfo(__FILE__, PATHINFO_FILENAME));
    }
    public function getWithdrawalDetail()
    {
        $sql = 'SELECT cu.id_withdraw,cu.amount,s.id_seller,CONCAT(c.firstname," ",c.lastname) as seller_name,s.id_customer,c.email as seller_email,CONCAT(c.firstname," ",c.lastname) as name, w.date_add,w.fee,w.fee_type,w.status,pml.title as payment_method,pm.estimated_processing_time 
        FROM `'._DB_PREFIX_.'ets_mp_withdrawal` w
        INNER JOIN `'._DB_PREFIX_.'ets_mp_commission_usage` cu ON (cu.id_withdraw= w.id_ets_mp_withdrawal)
        INNER JOIN `'._DB_PREFIX_.'ets_mp_seller` s ON (cu.id_customer=s.id_customer)
        INNER JOIN `'._DB_PREFIX_.'customer` c ON (c.id_customer=s.id_customer)
        INNER JOIN `'._DB_PREFIX_.'ets_mp_payment_method` pm ON (pm.id_ets_mp_payment_method=w.id_ets_mp_payment_method)
        LEFT JOIN `'._DB_PREFIX_.'ets_mp_payment_method_lang` pml ON (pml.id_ets_mp_payment_method=pm.id_ets_mp_payment_method AND pml.id_lang="'.(int)$this->context->language->id.'")
        WHERE w.id_ets_mp_withdrawal = "'.(int)$this->id.'"';
        $withDrawal = Db::getInstance()->getRow($sql);
        return $withDrawal;
    }
 }