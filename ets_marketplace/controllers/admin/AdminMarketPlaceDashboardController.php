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
class AdminMarketPlaceDashboardController extends ModuleAdminController
{
    public function __construct()
    {
       parent::__construct();
       $this->context= Context::getContext();
       $this->bootstrap = true;
    }
    public function initContent()
    {
        parent::initContent();
        if(Tools::isSubmit('actionSubmitCommissionsChart') && $data_type= Tools::getValue('actionSubmitCommissionsChart'))
        {
            $chart_labels = array();
            $earnings_datas= array();
            $seller_fee_datas = array();
            $seller_revenve_datas= array();
            $labelStringx= $this->l('Date');
            $no_data = true;
            if($data_type=='this_year')
            {
                $months= array(1,2,3,4,5,6,7,8,9,10,11,12);
                foreach($months as $month)
                {
                    $chart_labels[] = $month;
                    $total_fee = (float)$this->_getTotalSellerFee(' AND month(b.date_upd) = "'.pSQL($month).'" AND YEAR(b.date_upd) ="'.pSQL(date('Y')).'"');
                    $total_revenve = (float)$this->_getTotalSellerRevenve(' AND month(sc.date_upd) = "'.pSQL($month).'" AND YEAR(sc.date_upd) ="'.pSQL(date('Y')).'"');
                    $earnings_datas[]= $total_fee+$total_revenve;
                    $seller_fee_datas[] = $total_fee;
                    $seller_revenve_datas[] = $total_revenve;
                    if($total_fee!=0 || $total_revenve!=0)
                        $no_data=false;
                }
                $labelStringx = $this->l('Month');
            }
            if($data_type=='this_month')
            {
                $days = (int)date('t', mktime(0, 0, 0, (int)date('m'), 1, (int)date('Y')));
                if($days)
                {
                    for($day=1; $day<=$days;$day++)
                    {
                        $chart_labels[] = $day;
                        $total_fee = (float)$this->_getTotalSellerFee(' AND day(b.date_upd) = "'.pSQL($day).'" AND MONTH(b.date_upd)="'.pSQL(date('m')).'" AND YEAR(b.date_upd) ="'.pSQL(date('Y')).'"');
                        $total_revenve = (float)$this->_getTotalSellerRevenve(' AND day(sc.date_upd) = "'.pSQL($day).'" AND MONTH(sc.date_upd)="'.pSQL(date('m')).'" AND YEAR(sc.date_upd) ="'.pSQL(date('Y')).'"');
                        $earnings_datas[]= $total_fee+$total_revenve;
                        $seller_fee_datas[] = $total_fee;
                        $seller_revenve_datas[] = $total_revenve;
                        if($total_fee!=0 || $total_revenve!=0)
                            $no_data=false;
                    }
                }
                $labelStringx = $this->l('Day');
            }
            if($data_type=='_month')
            {
                $month = date('m',strtotime("-1 months"));
                $year = date('Y',strtotime("-1 months"));
                $days = (int)date('t', mktime(0, 0, 0, (int)$month, 1, (int)$year));
                if($days)
                {
                    for($day=1; $day<=$days;$day++)
                    {
                        $chart_labels[] = $day;
                        $total_fee = (float)$this->_getTotalSellerFee(' AND day(b.date_upd) = "'.pSQL($day).'" AND MONTH(b.date_upd)="'.pSQL($month).'" AND YEAR(b.date_upd) ="'.pSQL($year).'"');
                        $total_revenve = (float)$this->_getTotalSellerRevenve(' AND day(sc.date_upd) = "'.pSQL($day).'" AND MONTH(sc.date_upd)="'.pSQL($month).'" AND YEAR(sc.date_upd) ="'.pSQL($year).'"');
                        $earnings_datas[]= $total_fee+$total_revenve;
                        $seller_fee_datas[] = $total_fee;
                        $seller_revenve_datas[] = $total_revenve;
                        if($total_fee!=0 || $total_revenve!=0)
                            $no_data=false;
                    }
                }
                $labelStringx = $this->l('Day');
            }
            if($data_type=='time_range' || $data_type=='all_time')
            {
                if($data_type=='time_range')
                {
                    $start_date = Tools::getValue('date_from').' 00:00:00';
                    $end_date = Tools::getValue('date_to').' 23:59:59';
                }
                else
                {
                    $sql  = 'SELECT MIN(s.date_upd) FROM `'._DB_PREFIX_.'ets_mp_seller_billing` b
                    INNER JOIN `'._DB_PREFIX_.'ets_mp_seller` s ON (b.id_customer=s.id_customer)
                    WHERE s.id_shop="'.(int)$this->context->shop->id.'" AND b.active=1';
                    $min_billing=  Db::getInstance()->getInstance()->getValue($sql);
                    $sql  = 'SELECT MAX(s.date_upd) FROM `'._DB_PREFIX_.'ets_mp_seller_billing` b
                    INNER JOIN `'._DB_PREFIX_.'ets_mp_seller` s ON (b.id_customer=s.id_customer)
                    WHERE s.id_shop="'.(int)$this->context->shop->id.'" AND b.active=1';
                    $max_billing=  Db::getInstance()->getInstance()->getValue($sql);
                    $sql = $sql = 'SELECT MIN(sc.date_upd) FROM `'._DB_PREFIX_.'ets_mp_seller_commission` sc
                    WHERE sc.id_product!=0 AND sc.id_shop="'.(int)$this->context->shop->id.'" AND sc.status=1';
                    $min_commission=  Db::getInstance()->getValue($sql);
                    $sql = $sql = 'SELECT MAX(sc.date_upd) FROM `'._DB_PREFIX_.'ets_mp_seller_commission` sc
                    WHERE sc.id_product!=0 AND sc.id_shop="'.(int)$this->context->shop->id.'" AND sc.status=1';
                    $max_commission=  Db::getInstance()->getValue($sql);
                    $start_date = $min_billing;
                    $end_date = $max_billing;
                    if((!$start_date || strtotime($start_date) > strtotime($min_commission)) && $min_commission )
                        $start_date = $min_commission;
                    if((!$end_date || strtotime($end_date) < strtotime($max_commission)) && $max_commission)
                        $end_date = $max_commission;
                    if($start_date && $end_date)
                    {
                        if(date('Y-m-d',strtotime($start_date))==date('Y-m-d',strtotime($end_date)))
                        {
                            $start_date = date('Y-m-d 00:00:00',strtotime($start_date)-86400);
                            $end_date = date('Y-m-d 23:59:59',strtotime($end_date)+86400);
                        }
                        else{
                            $start_date = date('Y-m-d 00:00:00',strtotime($start_date));
                            $end_date = date('Y-m-d 23:59:59',strtotime($end_date));
                        }
                    }
                }
                if(isset($start_date) && isset($end_date) && $start_date && $end_date)
                {
                    if (date('Y', strtotime($start_date)) != date('Y', strtotime($end_date)))
                    {
                        $years = $this->module->getYearRanger($start_date,$end_date,'Y');
                        if($years)
                        {
                            foreach($years as $year)
                            {
                                $chart_labels[] = $year;
                                $total_fee = (float)$this->_getTotalSellerFee(' AND b.date_upd<="'.pSQL($end_date).'" AND b.date_upd>="'.pSQL($start_date).'" AND YEAR(b.date_upd) ="'.pSQL($year).'"');
                                $total_revenve = (float)$this->_getTotalSellerRevenve(' AND sc.date_upd<="'.pSQL($end_date).'" AND sc.date_upd>="'.pSQL($start_date).'" AND YEAR(sc.date_upd) ="'.pSQL($year).'"');
                                $earnings_datas[]= $total_fee+$total_revenve;
                                $seller_fee_datas[] = $total_fee;
                                $seller_revenve_datas[] = $total_revenve;
                                if($total_fee!=0 || $total_revenve!=0)
                                    $no_data=false;
                            }
                        }
                        $labelStringx = $this->l('Year');
                        
                    }
                    elseif((int)date('m', strtotime($start_date)) != (int)date('m', strtotime($end_date))) 
                    {
                        $months = $this->module->getDateRanger($start_date,$end_date,'m',false,'month');
                        if($months)
                        {
                            $year = date('Y', strtotime($start_date));
                            foreach($months as $month)
                            {
                                $chart_labels[] = $month;
                                $total_fee = (float)$this->_getTotalSellerFee(' AND b.date_upd<="'.pSQL($end_date).'" AND b.date_upd>="'.pSQL($start_date).'" AND MONTH(b.date_upd)="'.pSQL($month).'" AND YEAR(b.date_upd) ="'.pSQL($year).'"');
                                $total_revenve = (float)$this->_getTotalSellerRevenve(' AND sc.date_upd<="'.pSQL($end_date).'" AND sc.date_upd >="'.pSQL($start_date).'" AND MONTH(sc.date_upd)="'.pSQL($month).'" AND YEAR(sc.date_upd) ="'.pSQL($year).'"');
                                $earnings_datas[]= $total_fee+$total_revenve;
                                $seller_fee_datas[] = $total_fee;
                                $seller_revenve_datas[] = $total_revenve; 
                                if($total_fee!=0 || $total_revenve!=0)
                                    $no_data=false;
                            }
                        }
                        $labelStringx = $this->l('Month');
                        
                    }
                    else
                    {
                        $days = $this->module->getDateRanger($start_date,$end_date,'d');
                        if($days)
                        {
                            $year = date('Y', strtotime($start_date));
                            $month = date('m', strtotime($start_date));
                            foreach($days as $day)
                            {
                                $chart_labels[] = $day;
                                $total_fee = (float)$this->_getTotalSellerFee(' AND DAY(b.date_upd)="'.pSQL($day).'" AND MONTH(b.date_upd)="'.pSQL($month).'" AND YEAR(b.date_upd) ="'.pSQL($year).'"');
                                $total_revenve = (float)$this->_getTotalSellerRevenve(' AND DAY(sc.date_upd)="'.pSQL($day).'" AND MONTH(sc.date_upd)="'.pSQL($month).'" AND YEAR(sc.date_upd) ="'.pSQL($year).'"');
                                $earnings_datas[]= $total_fee+$total_revenve;
                                $seller_fee_datas[] = $total_fee;
                                $seller_revenve_datas[] = $total_revenve;
                                if($total_fee!=0 || $total_revenve!=0)
                                    $no_data=false;
                            }
                        }
                        $labelStringx = $this->l('Day');
                    }
                }
                
            }
            if($no_data)
            {
                die(
                    Tools::jsonEncode(
                        array(
                            'no_data' => $no_data,
                        )
                    )
                );
            }
            $commissions_line_datasets = array($earnings_datas,$seller_fee_datas,$seller_revenve_datas);
            die(
                Tools::jsonEncode(
                    array(
                        'label_datas' => $chart_labels,
                        'commissions_line_datasets' => $commissions_line_datasets,
                        'labelStringx' => $labelStringx,
                    )
                )
            );
        }
        if(Tools::isSubmit('actionSubmitTurnOVerChart') && $data_type= Tools::getValue('actionSubmitTurnOVerChart'))
        {
            $chart_labels = array();
            $seller_commission_datas = array();
            $turn_over_datas = array();
            $labelStringx= $this->l('Date');
            $no_data= true;
            if($data_type=='this_year')
            {
                $months= array(1,2,3,4,5,6,7,8,9,10,11,12);
                foreach($months as $month)
                {
                    $chart_labels[] = $month;
                    $turn_over = (float)$this->_getTotalTurnOver(' AND month(o.date_upd) = "'.pSQL($month).'" AND YEAR(o.date_upd) ="'.pSQL(date('Y')).'"');
                    $turn_over_datas[] = $turn_over;
                    $seller_commission=(float)$this->_getTotalSellerCommission(' AND month(date_upd) = "'.pSQL($month).'" AND YEAR(date_upd) ="'.pSQL(date('Y')).'"');
                    $seller_commission_datas[]= $seller_commission;
                    if($turn_over!=0 || $seller_commission!=0)
                        $no_data=false;
                }
                $labelStringx = $this->l('Month');
            }
            if($data_type=='this_month')
            {
                $days = (int)date('t', mktime(0, 0, 0, (int)date('m'), 1, (int)date('Y')));
                if($days)
                {
                    for($day=1; $day<=$days;$day++)
                    {
                        $chart_labels[] = $day;
                        $turn_over = (float)$this->_getTotalTurnOver(' AND day(o.date_upd) = "'.pSQL($day).'" AND MONTH(o.date_upd)="'.pSQL(date('m')).'" AND YEAR(o.date_upd) ="'.pSQL(date('Y')).'"');;
                        $seller_commission=(float)$this->_getTotalSellerCommission(' AND day(date_upd) = "'.pSQL($day).'" AND MONTH(date_upd)="'.pSQL(date('m')).'" AND YEAR(date_upd) ="'.pSQL(date('Y')).'"');
                        $turn_over_datas[] = $turn_over;
                        $seller_commission_datas[] = $seller_commission;
                        if($turn_over!=0 || $seller_commission!=0)
                            $no_data = false;
                    }
                }
                $labelStringx = $this->l('Day');
            }
            if($data_type=='_month')
            {
                $month = date('m',strtotime("-1 months"));
                $year = date('Y',strtotime("-1 months"));
                $days = (int)date('t', mktime(0, 0, 0, (int)$month, 1, (int)$year));
                if($days)
                {
                    for($day=1; $day<=$days;$day++)
                    {
                        $chart_labels[] = $day;
                        $turn_over = (float)$this->_getTotalTurnOver(' AND day(o.date_upd) = "'.pSQL($day).'" AND MONTH(o.date_upd)="'.pSQL($month).'" AND YEAR(o.date_upd) ="'.pSQL($year).'"');;
                        $seller_commission=(float)$this->_getTotalSellerCommission(' AND day(date_upd) = "'.pSQL($day).'" AND MONTH(date_upd)="'.pSQL($month).'" AND YEAR(date_upd) ="'.pSQL($year).'"');
                        $turn_over_datas[] = $turn_over;
                        $seller_commission_datas[] = $seller_commission;
                        if($turn_over!=0 || $seller_commission!=0)
                            $no_data = false;
                    }
                }
                $labelStringx = $this->l('Day');
            }
            if($data_type=='time_range' || $data_type=='all_time')
            {
                if($data_type=='time_range')
                {
                    $start_date = Tools::getValue('date_from').' 00:00:00';
                    $end_date = Tools::getValue('date_to').' 23:59:59';
                }
                else
                {
                    if(Configuration::get('ETS_MP_COMMISSION_APPROVED_WHEN'))
                    {
                        $status = explode(',',Configuration::get('ETS_MP_COMMISSION_APPROVED_WHEN'));
                        $sql = 'SELECT MIN(o.date_upd) FROM `'._DB_PREFIX_.'orders` o
                            INNER JOIN `'._DB_PREFIX_.'currency` c ON (o.id_currency=c.id_currency)
                            INNER JOIN `'._DB_PREFIX_.'ets_mp_seller_order` seller_order ON (seller_order.id_order=o.id_order)
                            WHERE o.id_shop="'.(int)$this->context->shop->id.'" AND o.current_state IN ('.implode(',',array_map('intval',$status)).')';
                        $min_order= Db::getInstance()->getValue($sql);
                        $sql = 'SELECT MAX(o.date_upd) FROM `'._DB_PREFIX_.'orders` o
                            INNER JOIN `'._DB_PREFIX_.'currency` c ON (o.id_currency=c.id_currency)
                            INNER JOIN `'._DB_PREFIX_.'ets_mp_seller_order` seller_order ON (seller_order.id_order=o.id_order)
                            WHERE o.id_shop="'.(int)$this->context->shop->id.'" AND o.current_state IN ('.implode(',',array_map('intval',$status)).')';
                        $max_order = Db::getInstance()->getValue($sql);
                        
                    }
                    else
                    {
                        $min_order='';
                        $max_order='';
                    }
                    $sql = 'SELECT MIN(date_upd) FROM `'._DB_PREFIX_.'ets_mp_seller_commission` WHERE id_shop="'.(int)$this->context->shop->id.'" AND status=1';
                    $min_commission = Db::getInstance()->getValue($sql);
                    $sql = 'SELECT MAX(date_upd) FROM `'._DB_PREFIX_.'ets_mp_seller_commission` WHERE id_shop="'.(int)$this->context->shop->id.'" AND status=1';
                    $max_commission = Db::getInstance()->getValue($sql);
                    $start_date = $min_order;
                    $end_date = $max_order;
                    if((!$start_date || strtotime($start_date) > strtotime($min_commission)) && $min_commission)
                        $start_date = $min_commission;
                    if((!$end_date || strtotime($end_date) < strtotime($max_commission)) && $max_commission)
                        $end_date = $max_commission;
                    if($start_date && $end_date)
                    {
                        if(date('Y-m-d',strtotime($start_date))==date('Y-m-d',strtotime($end_date)))
                        {
                            $start_date = date('Y-m-d 00:00:00',strtotime($start_date)-86400);
                            $end_date = date('Y-m-d 23:59:59',strtotime($end_date)+86400);
                        }
                        else{
                            $start_date = date('Y-m-d 00:00:00',strtotime($start_date));
                            $end_date = date('Y-m-d 23:59:59',strtotime($end_date));
                        }
                    }
                }
                if(isset($start_date) && isset($end_date) && $start_date && $end_date)
                {
                    if (date('Y', strtotime($start_date)) != date('Y', strtotime($end_date)))
                    {
                        $years = $this->module->getYearRanger($start_date,$end_date,'Y');
                        if($years)
                        {
                            foreach($years as $year)
                            {
                                $chart_labels[] = $year;
                                $turn_over = (float)$this->_getTotalTurnOver(' AND o.date_upd<="'.pSQL($end_date).'" AND o.date_upd>="'.pSQL($start_date).'" AND YEAR(o.date_upd) ="'.pSQL($year).'"');
                                $seller_commission=(float)$this->_getTotalSellerCommission(' AND date_upd<="'.pSQL($end_date).'" AND date_upd>="'.pSQL($start_date).'" AND YEAR(date_upd) ="'.pSQL($year).'"');
                                $turn_over_datas[]= $turn_over;
                                $seller_commission_datas[] = $seller_commission;
                                if($turn_over!=0 || $seller_commission!=0)
                                    $no_data=false;
                            }
                        }
                        $labelStringx = $this->l('Year');
                        
                    }
                    elseif((int)date('m', strtotime($start_date)) != (int)date('m', strtotime($end_date))) 
                    {
                        $months = $this->module->getDateRanger($start_date,$end_date,'m',false,'month');
                        if($months)
                        {
                            $year = date('Y', strtotime($start_date));
                            foreach($months as $month)
                            {
                                $chart_labels[] = $month;
                                $turn_over = (float)$this->_getTotalTurnOver(' AND o.date_upd<="'.pSQL($end_date).'" AND o.date_upd>="'.pSQL($start_date).'" AND MONTH(o.date_upd)="'.pSQL($month).'" AND YEAR(o.date_upd) ="'.pSQL($year).'"');
                                $seller_commission=(float)$this->_getTotalSellerCommission(' AND date_upd<="'.pSQL($end_date).'" AND date_upd >="'.pSQL($start_date).'" AND MONTH(date_upd)="'.pSQL($month).'" AND YEAR(date_upd) ="'.pSQL($year).'"');
                                $turn_over_datas[] = $turn_over;
                                $seller_commission_datas[] = $seller_commission;
                                if($turn_over!=0 || $seller_commission!=0)
                                    $no_data = false; 
                            }
                        }
                        $labelStringx = $this->l('Month');
                        
                    }
                    else
                    {
                        $days = $this->module->getDateRanger($start_date,$end_date,'d');
                        if($days)
                        {
                            $year = date('Y', strtotime($start_date));
                            $month = date('m', strtotime($start_date));
                            foreach($days as $day)
                            {
                                $chart_labels[] = $day;
                                $turn_over = (float)$this->_getTotalTurnOver(' AND DAY(o.date_upd)="'.pSQL($day).'" AND MONTH(o.date_upd)="'.pSQL($month).'" AND YEAR(o.date_upd) ="'.pSQL($year).'"');
                                $seller_commission=(float)$this->_getTotalSellerCommission(' AND DAY(date_upd)="'.pSQL($day).'" AND MONTH(date_upd)="'.pSQL($month).'" AND YEAR(date_upd) ="'.pSQL($year).'"');
                                $turn_over_datas[] = $turn_over;
                                $seller_commission_datas[] = $seller_commission;
                                if($turn_over!=0 || $seller_commission!=0)
                                    $no_data=false;
                            }
                        }
                        $labelStringx = $this->l('Day');
                    }
                }
                
            }
            if($no_data)
            {
                die(
                    Tools::jsonEncode(
                       array(
                            'no_data' => true,
                       )
                    )
                );
            }
            $turn_over_bar_datasets= array($seller_commission_datas,$turn_over_datas);
            die(
                Tools::jsonEncode(
                    array(
                        'label_datas' => $chart_labels,
                        'turn_over_bar_datasets' => $turn_over_bar_datasets,
                        'labelStringx' => $labelStringx,
                    )
                )
            );
        }
    }
    public function renderList()
    {
        $this->module->getContent();
        $this->context->smarty->assign(
            array(
                'ets_mp_body_html'=> $this->_renderDashboardDemo(),
            )
        );
        return $this->module->display(_PS_MODULE_DIR_.$this->module->name.DIRECTORY_SEPARATOR.$this->module->name.'.php', 'admin.tpl');
    }
    public function _renderDashboard()
    {
        $day_before_expired = (int)Configuration::get('ETS_MP_MESSAGE_EXPIRE_BEFORE_DAY');
        $going_tobe_expired_sellers = Db::getInstance()->executeS('SELECT seller.*,CONCAT(customer.firstname," ",customer.lastname) as seller_name,customer.email as seller_email, seller_lang.shop_name 
        FROM `'._DB_PREFIX_.'ets_mp_seller` seller
        LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_lang` seller_lang ON(seller.id_seller= seller_lang.id_seller AND seller_lang.id_lang="'.(int)$this->context->language->id.'")
        LEFT JOIN `'._DB_PREFIX_.'customer` customer ON (customer.id_customer=seller.id_customer)
        WHERE seller.active=1 AND seller.date_to is not NULL AND seller.date_to <="'.($day_before_expired ? pSQL(date('Y-m-d H:i:s',strtotime("+ $day_before_expired days"))): pSQL(date('Y-m-d H:i:s'))).'"');
        $chart_labels = array();
        $earnings_datas= array();
        $seller_fee_datas = array();
        $seller_revenve_datas= array();
        $seller_commission_datas = array();
        $turn_over_datas = array();
        $no_data_char_commision = true;
        $no_data_char_turn_over= true;
        $days = (int)date('t', mktime(0, 0, 0, (int)date('m'), 1, (int)date('Y')));
        if($days)
        {
            for($day=1; $day<=$days;$day++)
            {
                $chart_labels[] = $day;
                $total_fee = (float)$this->_getTotalSellerFee(' AND day(b.date_upd) = "'.pSQL($day).'" AND MONTH(b.date_upd)="'.pSQL(date('m')).'" AND YEAR(b.date_upd) ="'.pSQL(date('Y')).'"');
                $total_revenve = (float)$this->_getTotalSellerRevenve(' AND day(sc.date_upd) = "'.pSQL($day).'" AND MONTH(sc.date_upd)="'.pSQL(date('m')).'" AND YEAR(sc.date_upd) ="'.pSQL(date('Y')).'"');
                $earnings_datas[]= $total_fee+$total_revenve;
                $seller_fee_datas[] = $total_fee;
                $seller_revenve_datas[] = $total_revenve;
                if($total_fee!=0 || $total_revenve!=0)
                    $no_data_char_commision=false;
                $turn_over = (float)$this->_getTotalTurnOver(' AND day(o.date_upd) = "'.pSQL($day).'" AND MONTH(o.date_upd)="'.pSQL(date('m')).'" AND YEAR(o.date_upd) ="'.pSQL(date('Y')).'"');;
                $seller_commission=(float)$this->_getTotalSellerCommission(' AND day(date_upd) = "'.pSQL($day).'" AND MONTH(date_upd)="'.pSQL(date('m')).'" AND YEAR(date_upd) ="'.pSQL(date('Y')).'"');
                $turn_over_datas[] = $turn_over;
                $seller_commission_datas[] = $seller_commission;
                if($turn_over!=0 || $seller_commission!=0)
                    $no_data_char_turn_over = false;
            }
        }
        $commissions_line_datasets = array(
            array(
                'label'=> $this->l('Earning'),
                'data' =>$earnings_datas,
                'backgroundColor'=>'rgba(163,225,212,0.3)',
                'borderColor'=>'rgba(163,225,212,1)',
                'borderWidth'=>1,
                'pointRadius' => 2,
                'lineTension'=> 0
            ),
            array(
                'label'=> $this->l('Membership fee'),
                'data' =>$seller_fee_datas,
                'backgroundColor'=>'rgba(253,193,7,0.3)',
                'borderColor'=>'rgba(253,193,7,1)',
                'borderWidth'=>1,
                'pointRadius' => 2,
                'lineTension'=> 0
            ),
            array(
                'label'=> $this->l('Revenue'),
                'data' =>$seller_revenve_datas,
                'backgroundColor'=>'rgba(139,195,72,0.3)',
                'borderColor'=>'rgba(139,195,72,1)',
                'borderWidth'=>1,
                'pointRadius' => 2,
                'lineTension'=> 0
            )
        );
        $turn_over_bar_datasets = array(
            array(
                'label'=> $this->l('Seller commissions'),
                'data' =>$seller_commission_datas,
                'backgroundColor'=>'rgba(163,225,212,0.3)',
                'borderColor'=>'rgba(163,225,212,1)',
                'borderWidth'=>1,
                'pointRadius' => 2,
                'lineTension'=> 0
            ),
            array(
                'label'=> $this->l('Turnover'),
                'data' =>$turn_over_datas,
                'backgroundColor'=>'rgba(253,193,7,0.3)',
                'borderColor'=>'rgba(253,193,7,1)',
                'borderWidth'=>1,
                'pointRadius' => 2,
                'lineTension'=> 0
            ),
        );
        $this->context->smarty->assign(
            array(
                'module' => $this->module,
                'last_withdraws'=> Ets_mp_withdraw::_getWithdrawals(false,' w.id_ets_mp_withdrawal DESC',0,5,false),
                'last_payment_billings' => Ets_mp_billing::getInstance()->getSellerBillings(false,false,0,10,'b.id_ets_mp_seller_billing DESC'),
                'going_tobe_expired_sellers' => $going_tobe_expired_sellers,
                'latest_orders' => $this->_getLatestOrders(),
                'latest_seller_commissions'=> $this->_getLatestSellerCommissions(),
                'latest_products' => $this->_getLatestProducts(),
                'best_selling_products' => $this->_getBestSellingProducts(),
                'top_sellers' => $this->_getTopSellers(),
                'top_seller_commissions' => $this->_getTopSellerCommissions(),
                'totalTurnOver' => $this->_getTotalTurnOver(),
                'totalSellerProduct' => $this->module->getSellerProducts(' AND sp.id_product is NOT NULL AND p.active=1',0,0,0,true),
                'totalSellerRevenve'=> $this->_getTotalSellerRevenve(),
                'totalSellerFee' => $this->_getTotalSellerFee(),
                'commissions_line_datasets' => $commissions_line_datasets,
                'chart_labels' => $chart_labels,
                'turn_over_bar_datasets' => $turn_over_bar_datasets,
                'totalSellerCommission' => $this->_getTotalSellerCommission(),
                'default_currency' => $this->context->currency,
                'no_data_char_commission' => $no_data_char_commision,
                'no_data_char_turn_over' => $no_data_char_turn_over,
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/dashboard/admin_dashboard.tpl');
    }
    public function _renderDashboardDemo()
    {
        $day_before_expired = (int)Configuration::get('ETS_MP_MESSAGE_EXPIRE_BEFORE_DAY');
        $going_tobe_expired_sellers = Db::getInstance()->executeS('SELECT seller.*,CONCAT(customer.firstname," ",customer.lastname) as seller_name,customer.email as seller_email, seller_lang.shop_name 
        FROM '._DB_PREFIX_.'ets_mp_seller seller
        LEFT JOIN '._DB_PREFIX_.'ets_mp_seller_lang seller_lang ON(seller.id_seller= seller_lang.id_seller AND seller_lang.id_lang="'.(int)$this->context->language->id.'")
        LEFT JOIN '._DB_PREFIX_.'customer customer ON (customer.id_customer=seller.id_customer)
        WHERE seller.active=1 AND seller.date_to is not NULL AND seller.date_to <="'.($day_before_expired ? pSQL(date('Y-m-d H:i:s',strtotime("+ $day_before_expired days"))): pSQL(date('Y-m-d H:i:s'))).'"');
        $chart_labels = array();
        $earnings_datas= array();
        $seller_fee_datas = array();
        $seller_revenve_datas= array();
        $seller_commission_datas = array();
        $turn_over_datas = array();
        $months= array(1,2,3,4,5,6,7,8,9,10,11,12);
        $no_data_char_commision = true;
        $no_data_char_turn_over= true;
        foreach($months as $month)
        {
            $chart_labels[] = $month;
            $total_fee = (float)$this->_getTotalSellerFee(' AND month(b.date_upd) = "'.pSQL($month).'" AND YEAR(b.date_upd) ="'.pSQL(date('Y')).'"');
            $total_revenve = (float)$this->_getTotalSellerRevenve(' AND month(sc.date_upd) = "'.pSQL($month).'" AND YEAR(sc.date_upd) ="'.pSQL(date('Y')).'"');
            $earnings_datas[]= $total_fee+$total_revenve;
            $seller_fee_datas[] = $total_fee;
            $seller_revenve_datas[] = $total_revenve;
            $turn_over = (float)$this->_getTotalTurnOver(' AND month(o.date_upd) = "'.pSQL($month).'" AND YEAR(o.date_upd) ="'.pSQL(date('Y')).'"');
            $turn_over_datas[] = $turn_over;
            $seller_commission=(float)$this->_getTotalSellerCommission(' AND month(date_upd) = "'.pSQL($month).'" AND YEAR(date_upd) ="'.pSQL(date('Y')).'"');
            $seller_commission_datas[] = $seller_commission;
            if($total_fee!=0 || $total_revenve!=0 )
                $no_data_char_commision=false;
            if($seller_commission!=0 || $turn_over!=0)
                $no_data_char_turn_over = false;
        }
        $commissions_line_datasets = array(
            array(
                'label'=> $this->l('Earning'),
                'data' =>$earnings_datas,
                'backgroundColor'=>'rgba(163,225,212,0.3)',
                'borderColor'=>'rgba(163,225,212,1)',
                'borderWidth'=>1,
                'pointRadius' => 2,
                'lineTension'=> 0
            ),
            array(
                'label'=> $this->l('Seller fee'),
                'data' =>$seller_fee_datas,
                'backgroundColor'=>'rgba(253,193,7,0.3)',
                'borderColor'=>'rgba(253,193,7,1)',
                'borderWidth'=>1,
                'pointRadius' => 2,
                'lineTension'=> 0
            ),
            array(
                'label'=> $this->l('Revenue'),
                'data' =>$seller_revenve_datas,
                'backgroundColor'=>'rgba(139,195,72,0.3)',
                'borderColor'=>'rgba(139,195,72,1)',
                'borderWidth'=>1,
                'pointRadius' => 2,
                'lineTension'=> 0
            )
        );
        $turn_over_bar_datasets = array(
            array(
                'label'=> $this->l('Seller commissions'),
                'data' =>$seller_commission_datas,
                'backgroundColor'=>'rgba(163,225,212,0.3)',
                'borderColor'=>'rgba(163,225,212,1)',
                'borderWidth'=>1,
                'pointRadius' => 2,
                'lineTension'=> 0
            ),
            array(
                'label'=> $this->l('Turnover'),
                'data' =>$turn_over_datas,
                'backgroundColor'=>'rgba(253,193,7,0.3)',
                'borderColor'=>'rgba(253,193,7,1)',
                'borderWidth'=>1,
                'pointRadius' => 2,
                'lineTension'=> 0
            ),
        );
        $this->context->smarty->assign(
            array(
                'module' => $this->module,
                'last_withdraws'=> Ets_mp_withdraw::_getWithdrawals(false,' w.id_ets_mp_withdrawal DESC',0,5,false),
                'last_payment_billings' => (new Ets_mp_billing())->getSellerBillings(false,false,0,10,'b.id_ets_mp_seller_billing DESC'),
                'going_tobe_expired_sellers' => $going_tobe_expired_sellers,
                'latest_orders' => $this->_getLatestOrders(),
                'latest_seller_commissions'=> $this->_getLatestSellerCommissions(),
                'latest_products' => $this->_getLatestProducts(),
                'best_selling_products' => $this->_getBestSellingProducts(),
                'top_sellers' => $this->_getTopSellers(),
                'top_seller_commissions' => $this->_getTopSellerCommissions(),
                'totalTurnOver' => $this->_getTotalTurnOver(),
                'totalSellerProduct' => $this->module->getSellerProducts(' AND sp.id_product is NOT NULL AND p.active=1',0,0,0,true),
                'totalSellerRevenve'=> $this->_getTotalSellerRevenve(),
                'totalSellerFee' => $this->_getTotalSellerFee(),
                'commissions_line_datasets' => $commissions_line_datasets,
                'chart_labels' => $chart_labels,
                'turn_over_bar_datasets' => $turn_over_bar_datasets,
                'totalSellerCommission' => $this->_getTotalSellerCommission(),
                'default_currency' => $this->context->currency,
                'no_data_char_commission' => $no_data_char_commision,
                'no_data_char_turn_over' => $no_data_char_turn_over,
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/dashboard/admin_dashboard.tpl');
    }
    public function _getLatestOrders()
    {
        $latest_orders= $this->module->getOrders(' AND so.id_order is NOT NULL',false,0,10,'o.id_order DESC');
        if($latest_orders)
        {
            foreach ($latest_orders as &$order)
            {
                $order['current_state'] = $this->module->displayOrderState($order['current_state']);
                $order['customer_name'] = '<'.'a hr'.'ef="'.$this->module->getLinkCustomerAdmin($order['id_customer']).'">'.$order['customer_name'].'<'.'/'.'a'.'>';
            }
        }
        $this->context->smarty->assign(
            array(
                'latest_orders' => $latest_orders,
                'module'=> $this->module,
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/dashboard/latest_orders.tpl');
    }
    public function _getLatestSellerCommissions()
    {
        $sql ='SELECT sc.*,customer.id_customer as id_customer_seller,CONCAT(customer.firstname," ",customer.lastname) as seller_name,seller.id_seller,seller_lang.shop_name';
        $sql .= ' FROM `'._DB_PREFIX_.'ets_mp_seller_commission` sc
        LEFT JOIN `'._DB_PREFIX_.'customer` customer ON (customer.id_customer=sc.id_customer)
        LEFT  JOIN `'._DB_PREFIX_.'ets_mp_seller` seller ON (customer.id_customer= seller.id_customer)
        LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_lang` seller_lang ON (seller.id_seller= seller_lang.id_seller AND seller_lang.id_lang="'.(int)$this->context->language->id.'")
        WHERE sc.id_shop="'.(int)$this->context->shop->id.'" AND sc.status=1 ORDER BY sc.id_seller_commission DESC LIMIT 0,10';
        $latest_seller_commissions = Db::getInstance()->executeS($sql);
        if($latest_seller_commissions)
        {
            foreach($latest_seller_commissions as &$commission)
            {
                if($commission['id_product'])
                {
                    $commission['product_name'] = '<a href="'.$this->context->link->getAdminLink('AdminProducts',true,array('id_product'=>$commission['id_product'])).'">'.$commission['product_name'].'</a>';
                }
            }
        }
        $this->context->smarty->assign(
            array(
                'latest_seller_commissions' => $latest_seller_commissions,
                'module'=>$this->module,
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/dashboard/latest_seller_commissions.tpl');
    }
    public function _getLatestProducts()
    {
        $products = $this->module->getSellerProducts(' AND sp.id_product is NOT NULL',1,10);
        if($products)
        {
            if($this->module->is17)
                $type_image= ImageType::getFormattedName('home');
            else
                $type_image= ImageType::getFormatedName('home');
            foreach($products as &$product)
            {
                $product['price'] = Tools::displayPrice($product['price']);
                $product['link'] = $this->context->link->getAdminLink('AdminProducts',true,array('id_product'=>$product['id_product']));
                if($product['id_image'])
                {
                    
                    $product['image'] = '<'.'a hr'.'ef="'.$product['link'].'"><i'.'mg src="'.$this->context->link->getImageLink($product['link_rewrite'],$product['id_image'],$type_image).'" style="width:80px;"><'.'/'.'a'.'>';
                }
                else
                    $product['image']='';
                $product['name'] = '<'.'a  hr'.'ef="'.$product['link'].'">'.$product['name'].'<'.'/'.'a'.'>';
            }
        }
        $this->context->smarty->assign(
            array(
                'lastest_products' => $products,
                'module'=>$this->module,
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/dashboard/lastest_products.tpl');
    }
    public function _getBestSellingProducts()
    {
        if(Configuration::get('ETS_MP_COMMISSION_APPROVED_WHEN'))
        {
            $status = explode(',',Configuration::get('ETS_MP_COMMISSION_APPROVED_WHEN'));
            $nb_days_new_product = Configuration::get('PS_NB_DAYS_NEW_PRODUCT');
            $id_lang = (int)Context::getContext()->language->id;
            if (!Validate::isUnsignedInt($nb_days_new_product))
                $nb_days_new_product = 20;
            $prev_version = version_compare(_PS_VERSION_, '1.6.1.0', '<');
            $sql = 'SELECT DISTINCT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) AS quantity' . ($prev_version? ' ,IFNULL(product_attribute_shop.id_product_attribute, 0)':' ,MAX(product_attribute_shop.id_product_attribute)') . ' id_product_attribute, pl.`description`, pl.`description_short`, pl.`available_now`,
    			product_sale.quantity quantity_sale, pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, i.`id_image`,
    			il.`legend` as legend, m.`name` AS manufacturer_name,cl.name as default_category,CONCAT(customer.firstname," ",customer.lastname) as seller_name,customer.id_customer as id_customer_seller,seller.id_seller, seller_lang.shop_name,product_shop.`date_add`,
    			DATEDIFF(product_shop.`date_add`, DATE_SUB("' . date('Y-m-d') . ' 00:00:00",
    			INTERVAL ' . (int)$nb_days_new_product . ' DAY)) > 0 AS new, product_shop.price AS orderprice';
            $sql .= ' FROM `'._DB_PREFIX_.'product` p
                    '.Shop::addSqlAssociation('product', 'p').
                    (!$prev_version?
                        'LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (pa.id_product = p.id_product)'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.default_on=1').'':
                        'LEFT JOIN `'._DB_PREFIX_.'product_attribute_shop` product_attribute_shop ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop='.(int)$this->context->shop->id.')'
                    )
                    .Product::sqlStock('p', 0, false, Context::getContext()->shop).'
                    INNER JOIN (
                        SELECT od.product_id,sum(product_quantity) as quantity FROM `'._DB_PREFIX_.'order_detail` od
                        INNER JOIN `'._DB_PREFIX_.'orders` o ON (od.id_order=o.id_order)
                        WHERE o.current_state IN ('.implode(',',array_map('intval',$status)).')
                        GROUP BY od.product_id
                    ) as product_sale ON (product_sale.product_id=p.id_product)
                    INNER JOIN `'._DB_PREFIX_.'ets_mp_seller_product` sp ON (sp.id_product=p.id_product)
                    LEFT JOIN `'._DB_PREFIX_.'customer` customer ON (customer.id_customer=sp.id_customer)
                    LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller` seller ON (customer.id_customer=seller.id_customer)
                    LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_lang` seller_lang ON (seller_lang.id_seller=seller.id_seller AND seller_lang.id_lang="'.(int)$this->context->language->id.'")
                    LEFT JOIN `'._DB_PREFIX_.'category` c ON (c.id_category=p.id_category_default)
                    LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.id_category = cl.id_category AND cl.id_lang="'.(int)$id_lang.'")
                    LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = ' . (int)$id_lang . Shop::addSqlRestrictionOnLang('pl') . ')'.
                    ' LEFT JOIN `' . _DB_PREFIX_ . 'image` i ON (i.`id_product` = p.`id_product` AND i.cover=1)
                    LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')	
                    LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
                    WHERE product_shop.`id_shop` = ' . (int)Context::getContext()->shop->id ;
            $sql .= ' GROUP BY p.id_product ORDER BY product_sale.quantity DESC LIMIT 0,10';
            $products = Db::getInstance()->executeS($sql);
            if($products)
            {
                if($this->module->is17)
                    $type_image= ImageType::getFormattedName('home');
                else
                    $type_image= ImageType::getFormatedName('home');
                foreach($products as &$product)
                {
                    $product['link'] = $this->context->link->getAdminLink('AdminProducts',true,array('id_product'=>$product['id_product']));
                    $product['price'] = Tools::displayPrice($product['price']);
                    if(!$product['id_image'])
                        $product['id_image'] = Db::getInstance()->getValue('SELECT id_image FROM `'._DB_PREFIX_.'image` WHERE id_product='.(int)$product['id_product']);
                    if($product['id_image'])
                    {
                        
                        $product['image'] = '<'.'a hr'.'ef="'.$product['link'].'"><i'.'mg src="'.$this->context->link->getImageLink($product['link_rewrite'],$product['id_image'],$type_image).'" style="width:80px;"><'.'/'.'a'.'>';
                    }
                    else
                        $product['image']='';
                    $product['name'] = '<'.'a  hr'.'ef="'.$product['link'].'">'.$product['name'].'<'.'/'.'a'.'>';
                    
                }
            }
            $this->context->smarty->assign(
                array(
                    'products' => $products,
                    'module' => $this->module,
                )
            );
        }
        else
        {
            $this->context->smarty->assign(
                array(
                    'products' => array(),
                )
            );
        }
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/dashboard/best_selling_products.tpl');
    }
    public function _getTopSellers()
    {
        if(Configuration::get('ETS_MP_COMMISSION_APPROVED_WHEN'))
            $status = explode(',',Configuration::get('ETS_MP_COMMISSION_APPROVED_WHEN'));
        else
            $status = array();
        if($status)
        {
            $sql = 'SELECT s.*,CONCAT(customer.firstname," ", customer.lastname) as seller_name,customer.email as seller_email,sl.shop_name,sl.shop_address,sl.shop_description,top_order_seller.total_order FROM `'._DB_PREFIX_.'ets_mp_seller` s
                INNER JOIN (
                    SELECT seller_order.id_customer as id_customer,SUM(od.product_quantity) as total_order
                    FROM `'._DB_PREFIX_.'ets_mp_seller_order` seller_order
                    INNER JOIN `'._DB_PREFIX_.'orders` o ON(o.id_order=seller_order.id_order)
                    INNER JOIN `'._DB_PREFIX_.'order_detail` od ON (o.id_order=od.id_order)
                    WHERE o.id_shop="'.(int)$this->context->shop->id.'" AND o.current_state IN ('.implode(',',array_map('intval',$status)).') GROUP BY seller_order.id_customer
                ) as top_order_seller ON (top_order_seller.id_customer=s.id_customer)
                LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_lang` sl ON (s.id_seller = sl.id_seller AND sl.id_lang ="'.(int)Context::getContext()->language->id.'")
                LEFT JOIN `'._DB_PREFIX_.'customer` customer ON (s.id_customer=customer.id_customer)
                WHERE s.id_shop="'.(int)Context::getContext()->shop->id.'" ORDER BY top_order_seller.total_order DESC LIMIT 0,10';
            $sellers = Db::getInstance()->executeS($sql);
        }
        else
            $sellers = array();
        $this->context->smarty->assign(
            array(
                'sellers'=> $sellers,
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_. $this->module->name.'/views/templates/hook/dashboard/top_sellers.tpl');
    }
    public function _getTopSellerCommissions()
    {
        $sql = 'SELECT s.*,CONCAT(customer.firstname," ", customer.lastname) as seller_name,customer.email as seller_email,sl.shop_name,sl.shop_address,sl.shop_description,seller_commission.total_commission FROM `'._DB_PREFIX_.'ets_mp_seller` s
            INNER JOIN (
                SELECT id_customer,SUM(commission) as total_commission FROM `'._DB_PREFIX_.'ets_mp_seller_commission` WHERE status=1 GROUP BY id_customer
            ) as seller_commission ON (seller_commission.id_customer=s.id_customer)
            LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_lang` sl ON (s.id_seller = sl.id_seller AND sl.id_lang ="'.(int)Context::getContext()->language->id.'")
            LEFT JOIN `'._DB_PREFIX_.'customer` customer ON (customer.id_customer=s.id_customer)
            WHERE s.id_shop="'.(int)Context::getContext()->shop->id.'" ORDER BY seller_commission.total_commission DESC LIMIT 0,10';
        $sellers = Db::getInstance()->executeS($sql);
        $this->context->smarty->assign(
            array(
                'sellers' => $sellers,
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/dashboard/top_seller_commissions.tpl');
    }
    public function _getTotalTurnOver($filter=false)
    {
        if(Configuration::get('ETS_MP_COMMISSION_APPROVED_WHEN'))
        {
            $status = explode(',',Configuration::get('ETS_MP_COMMISSION_APPROVED_WHEN'));
            $sql = 'SELECT SUM(o.total_paid_tax_incl/c.conversion_rate) FROM `'._DB_PREFIX_.'orders` o
                INNER JOIN `'._DB_PREFIX_.'currency` c ON (o.id_currency=c.id_currency)
                INNER JOIN `'._DB_PREFIX_.'ets_mp_seller_order` seller_order ON (seller_order.id_order=o.id_order)
                WHERE o.id_shop="'.(int)$this->context->shop->id.'" AND o.current_state IN ('.implode(',',array_map('intval',$status)).')'.($filter ? $filter:'').'
            ';
            return (float)Db::getInstance()->getValue($sql);
        }
        return 0;
    }
    public function _getTotalSellerRevenve($filter=false)
    {
        $sql = 'SELECT if(sc.use_tax,SUM(sc.total_price_tax_incl-sc.commission),SUM(sc.total_price-sc.commission)) FROM `'._DB_PREFIX_.'ets_mp_seller_commission` sc
        WHERE sc.id_product!=0 AND sc.id_shop="'.(int)$this->context->shop->id.'" AND sc.status=1'.($filter ? $filter:'');
        return Db::getInstance()->getValue($sql);
    }
    public function _getTotalSellerFee($filter=false)
    {
        $sql  = 'SELECT SUM(b.amount) FROM `'._DB_PREFIX_.'ets_mp_seller_billing` b
        LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller` s ON (b.id_customer=s.id_customer)
        WHERE b.id_shop="'.(int)$this->context->shop->id.'" AND b.active=1'.($filter ? $filter:'');
        return Db::getInstance()->getInstance()->getValue($sql);
    }
    public function _getTotalSellerCommission($filter=false)
    {
        $sql = 'SELECT sum(commission) FROM `'._DB_PREFIX_.'ets_mp_seller_commission` WHERE status=1 AND id_shop="'.(int)$this->context->shop->id.'"'.($filter ? $filter:'');
        return Db::getInstance()->getValue($sql);
    }
    
}