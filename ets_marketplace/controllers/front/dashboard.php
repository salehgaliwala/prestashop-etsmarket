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
class Ets_MarketPlaceDashboardModuleFrontController extends ModuleFrontController
{
    public $seller;
    public function __construct()
	{
		parent::__construct();
        $this->display_column_right=false;
        $this->display_column_left =false;
        if($this->module->is17)
        {
            $smarty = $this->context->smarty;
            smartyRegisterFunction($smarty, 'function', 'displayAddressDetail', array('AddressFormat', 'generateAddressSmarty'));
            smartyRegisterFunction($smarty, 'function', 'displayPrice', array('Tools', 'displayPriceSmarty'));
        }
	}
    public function postProcess()
    {
        parent::postProcess();
        if(!$this->context->customer->logged || !($this->seller = $this->module->_getSeller(true)) )
            Tools::redirect($this->context->link->getModuleLink($this->module->name,'myseller'));
        if(!$this->module->_checkPermissionPage($this->seller))
            die($this->module->l('You do not have permission','dashboard'));
        if(Tools::isSubmit('actionSubmitCommissionsChart') && $data_type = Tools::getValue('actionSubmitCommissionsChart'))
        {
            $chart_labels = array();
            $seller_commission_datas= array();
            $turn_over_datas = array();
            $labelStringx= $this->module->l('Date','dashboard');
            $no_data_char_commission = true;
            $total_number_of_product_sold =0;
            $total_turn_over = 0;
            $total_earning_commission=0;
            if($data_type=='this_year')
            {
                $months= array(1,2,3,4,5,6,7,8,9,10,11,12);
                foreach($months as $month)
                {
                    $chart_labels[]= $month;
                    $turn_over = (float)$this->_getTotalTurnOver(' AND month(o.date_upd) = "'.pSQL($month).'" AND YEAR(o.date_upd) ="'.pSQL(date('Y')).'"');
                    $turn_over_datas[] = $turn_over;
                    $seller_commission=(float)$this->_getTotalSellerCommission(' AND month(date_upd) = "'.pSQL($month).'" AND YEAR(date_upd) ="'.pSQL(date('Y')).'"');
                    $seller_commission_datas[] = $seller_commission;
                    if($seller_commission!=0 || $turn_over!=0)
                        $no_data_char_commission = false;
                }
                $labelStringx = $this->module->l('Month','dashboard');
                $total_number_of_product_sold = $this->_getTotalNumberOfProductSold(' AND YEAR(o.date_upd)="'.pSQL(date('Y')).'"');
                $total_turn_over = $this->_getTotalTurnOver(' AND YEAR(o.date_upd)="'.pSQL(date('Y')).'"');
                $total_earning_commission = $this->_getTotalSellerCommission(' AND YEAR(date_upd) ="'.pSQL(date('Y')).'"');
            }
            if($data_type=='_year')
            {
                $months= array(1,2,3,4,5,6,7,8,9,10,11,12);
                foreach($months as $month)
                {
                    $chart_labels[]= $month;
                    $turn_over = (float)$this->_getTotalTurnOver(' AND month(o.date_upd) = "'.pSQL($month).'" AND YEAR(o.date_upd) ="'.pSQL(date('Y')-1).'"');
                    $turn_over_datas[] = $turn_over;
                    $seller_commission=(float)$this->_getTotalSellerCommission(' AND month(date_upd) = "'.pSQL($month).'" AND YEAR(date_upd) ="'.pSQL(date('Y')-1).'"');
                    $seller_commission_datas[] = $seller_commission;
                    if($seller_commission!=0 || $turn_over!=0)
                        $no_data_char_commission = false;
                }
                $labelStringx = $this->module->l('Month','dashboard');
                $total_number_of_product_sold = $this->_getTotalNumberOfProductSold(' AND YEAR(o.date_upd)="'.pSQL(date('Y')-1).'"');
                $total_turn_over = $this->_getTotalTurnOver(' AND YEAR(o.date_upd)="'.pSQL(date('Y')-1).'"');
                $total_earning_commission = $this->_getTotalSellerCommission(' AND YEAR(date_upd) ="'.pSQL(date('Y')-1).'"');
            }
            if($data_type=='this_month')
            {
                $days = (int)date('t', mktime(0, 0, 0, (int)date('m'), 1, (int)date('Y')));
                if($days)
                {
                    for($day=1; $day<=$days;$day++)
                    {
                        $chart_labels[] = $day;
                        $turn_over = (float)$this->_getTotalTurnOver(' AND day(o.date_upd) = "'.pSQL($day).'" AND MONTH(o.date_upd)="'.pSQL(date('m')).'" AND YEAR(o.date_upd) ="'.pSQL(date('Y')).'"');
                        $seller_commission=(float)$this->_getTotalSellerCommission(' AND day(date_upd) = "'.pSQL($day).'" AND MONTH(date_upd)="'.pSQL(date('m')).'" AND YEAR(date_upd) ="'.pSQL(date('Y')).'"');
                        $turn_over_datas[] = $turn_over;
                        $seller_commission_datas[] = $seller_commission;
                        if($turn_over||$seller_commission)
                            $no_data_char_commission=false;
                    }
                }
                $labelStringx = $this->module->l('Day','dashboard');
                $total_number_of_product_sold = $this->_getTotalNumberOfProductSold(' AND MONTH(o.date_upd)="'.pSQL(date('m')).'" AND YEAR(o.date_upd)="'.pSQL(date('Y')).'"');
                $total_turn_over = $this->_getTotalTurnOver(' AND MONTH(o.date_upd)="'.pSQL(date('m')).'" AND YEAR(o.date_upd)="'.pSQL(date('Y')).'"');
                $total_earning_commission = $this->_getTotalSellerCommission(' AND MONTH(date_upd) ="'.pSQL(date('m')).'" AND YEAR(date_upd) ="'.pSQL(date('Y')).'"');
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
                        $turn_over = (float)$this->_getTotalTurnOver(' AND day(o.date_upd) = "'.pSQL($day).'" AND MONTH(o.date_upd)="'.pSQL($month).'" AND YEAR(o.date_upd) ="'.pSQL($year).'"');
                        $seller_commission=(float)$this->_getTotalSellerCommission(' AND day(date_upd) = "'.pSQL($day).'" AND MONTH(date_upd)="'.pSQL($month).'" AND YEAR(date_upd) ="'.pSQL($year).'"');
                        $turn_over_datas[] = $turn_over;
                        $seller_commission_datas[] = $seller_commission;
                        if($turn_over||$seller_commission)
                            $no_data_char_commission=false;
                    }
                }
                $labelStringx = $this->module->l('Day','dashboard');
                $total_number_of_product_sold = $this->_getTotalNumberOfProductSold(' AND MONTH(o.date_upd)="'.pSQL($month).'" AND YEAR(o.date_upd)="'.pSQL($year).'"');
                $total_turn_over = $this->_getTotalTurnOver(' AND MONTH(o.date_upd)="'.pSQL($month).'" AND YEAR(o.date_upd)="'.pSQL($year).'"');
                $total_earning_commission = $this->_getTotalSellerCommission(' AND MONTH(date_upd) ="'.pSQL($month).'" AND YEAR(date_upd) ="'.pSQL($year).'"');
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
                            INNER JOIN `'._DB_PREFIX_.'ets_mp_seller_order` seller_order ON (seller_order.id_order=o.id_order AND seller_order.id_customer="'.(int)$this->seller->id_customer.'")
                            WHERE o.id_shop="'.(int)$this->context->shop->id.'" AND o.current_state IN ('.implode(',',array_map('intval',$status)).')';
                        $min_order= Db::getInstance()->getValue($sql);
                        $sql = 'SELECT MAX(o.date_upd) FROM `'._DB_PREFIX_.'orders` o
                            INNER JOIN `'._DB_PREFIX_.'currency` c ON (o.id_currency=c.id_currency)
                            INNER JOIN `'._DB_PREFIX_.'ets_mp_seller_order` seller_order ON (seller_order.id_order=o.id_order AND seller_order.id_customer="'.(int)$this->seller->id_customer.'")
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
                                $turn_over = (float)$this->_getTotalTurnOver(' AND o.date_upd<="'.pSQL($end_date).'" AND o.date_upd>="'.pSQL($start_date).'" AND YEAR(o.date_upd) ="'.pSQL($year).'"');
                                $seller_commission=(float)$this->_getTotalSellerCommission(' AND date_upd<="'.pSQL($end_date).'" AND date_upd>="'.pSQL($start_date).'" AND YEAR(date_upd) ="'.pSQL($year).'"');
                                $turn_over_datas[] = $turn_over;
                                $seller_commission_datas[] = $seller_commission;
                                if($turn_over!=0 || $seller_commission!=0)
                                    $no_data_char_commission=false;
                            }
                        }
                        $labelStringx = $this->module->l('Year','dashboard');
                        
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
                                    $no_data_char_commission=false;
                            }
                        }
                        $labelStringx = $this->module->l('Month','dashboard');
                        
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
                                if($turn_over_datas!=0 || $seller_commission!=0)
                                    $no_data_char_commission=false;
                            }
                        }
                        $labelStringx = $this->module->l('Day','dashboard');
                    }
                    $total_number_of_product_sold = $this->_getTotalNumberOfProductSold(' AND o.date_upd >="'.pSQL($start_date).'" AND o.date_upd <="'.pSQL($end_date).'"');
                    $total_turn_over = $this->_getTotalTurnOver(' AND o.date_upd >="'.pSQL($start_date).'" AND o.date_upd <="'.pSQL($end_date).'"');
                    $total_earning_commission = $this->_getTotalSellerCommission(' AND date_upd <="'.pSQL($end_date).'" AND date_upd >="'.pSQL($start_date).'"');
                }
                
            }
            //if($no_data_char_commission)
//            {
//                die(
//                    Tools::jsonEncode(
//                        array(
//                            'no_data' => $no_data_char_commission,
//                        )
//                    )
//                );
//            }
            unset($no_data_char_commission);
            $commissions_line_datasets = array($turn_over_datas,$seller_commission_datas);
            die(
                Tools::jsonEncode(
                    array(
                        'label_datas' => $chart_labels,
                        'commissions_line_datasets' => $commissions_line_datasets,
                        'labelStringx' => $labelStringx,
                        'total_earning_commission' => Tools::displayPrice((float)$total_earning_commission),
                        'total_turn_over' => Tools::displayPrice((float)$total_turn_over),
                        'total_number_of_product_sold'=>(int)$total_number_of_product_sold,
                    )
                )
            );
        }
    }
    public function initContent()
	{
		parent::initContent();
        $day_before_expired = (int)Configuration::get('ETS_MP_MESSAGE_EXPIRE_BEFORE_DAY');
        $date_expired = date('Y-m-d H:i:s',strtotime("+ $day_before_expired days"));
        if($this->seller && $this->seller->date_to!='' && $this->seller->date_to!='0000-00-00 00:00:00' && strtotime($this->seller->date_to)< strtotime($date_expired))
        {
            $going_to_be_expired = true;
        }
        else
            $going_to_be_expired = false;
        $this->context->smarty->assign(
            array(
                'html_content' => $this->_initContentDemo(),
                'path' => $this->module->getBreadCrumb(),
                'breadcrumb' => $this->module->is17 ? $this->module->getBreadCrumb() : false,
                'seller' => $this->seller,
                'going_to_be_expired'=>$going_to_be_expired,
            )
        );
        if($this->module->is17)
            $this->setTemplate('module:'.$this->module->name.'/views/templates/front/dashboard.tpl');      
        else        
            $this->setTemplate('dashboard_16.tpl'); 
    }
    public function _initContent()
    {
        $no_data_char_commission= true;
        $turn_over_datas= array();
        $seller_commission_datas = array();
        $chart_labels = array();
        $days = (int)date('t', mktime(0, 0, 0, (int)date('m'), 1, (int)date('Y')));
        if($days)
        {
            for($day=1; $day<=$days;$day++)
            {
                $chart_labels[] = $day;
                $turn_over = (float)$this->_getTotalTurnOver(' AND day(o.date_upd) = "'.pSQL($day).'" AND MONTH(o.date_upd)="'.pSQL(date('m')).'" AND YEAR(o.date_upd) ="'.pSQL(date('Y')).'"');
                $seller_commission=(float)$this->_getTotalSellerCommission(' AND day(date_upd) = "'.pSQL($day).'" AND MONTH(date_upd)="'.pSQL(date('m')).'" AND YEAR(date_upd) ="'.pSQL(date('Y')).'"');
                $turn_over_datas[] = $turn_over;
                $seller_commission_datas[] = $seller_commission;
                if($turn_over||$seller_commission)
                    $no_data_char_commission=false;
            }
        }
        $commissions_line_datasets = array(
            array(
                'label'=> $this->module->l('Turnover','dashboard'),
                'data' =>$turn_over_datas,
                'backgroundColor'=>'rgba(163,225,212,0.3)',
                'borderColor'=>'rgba(163,225,212,1)',
                'borderWidth'=>1,
                'pointRadius' => 2,
                'lineTension'=> 0
            ),
            array(
                'label'=> $this->module->l('Earning commission','dashboard'),
                'data' =>$seller_commission_datas,
                'backgroundColor'=>'rgba(253,193,7,0.3)',
                'borderColor'=>'rgba(253,193,7,1)',
                'borderWidth'=>1,
                'pointRadius' => 2,
                'lineTension'=> 0
            ),
        );
        $this->context->smarty->assign(
            array(
                'ets_mp_url_search_product' => $this->context->link->getModuleLink($this->module->name,'ajax',array('ajaxSearchProduct'=>1,'disableCombination'=>1,'active'=>true)),
                'no_data_char_commission' => $no_data_char_commission,
                'current_currency' => $this->context->currency,
                'commissions_line_datasets' => $commissions_line_datasets,
                'chart_labels' => $chart_labels,
                'total_turn_over' => $this->_getTotalTurnOver(' AND MONTH(o.date_upd)="'.pSQL(date('m')).'" AND YEAR(o.date_upd)="'.pSQL(date('Y')).'"'),
                'total_earning_commission' => $this->_getTotalSellerCommission(' AND YEAR(date_upd) ="'.pSQL(date('Y')).'" AND MONTH(date_upd) ="'.pSQL(date('m')).'"'),
                'total_commission_balance' => Tools::convertPrice($this->seller->getTotalCommission(1)-$this->seller->getToTalUseCommission(1)),
                'total_withdrawls' => Tools::convertPrice($this->seller->getToTalUseCommission(1,false,false,true)),
                'total_commission_used' => Tools::convertPrice($this->seller->getToTalUseCommission(1)),
                'best_selling_products' => $this->_getBestSellingProducts(),
                'total_number_of_product_sold' => $this->_getTotalNumberOfProductSold(' AND YEAR(o.date_upd)="'.pSQL(date('Y')).'" AND MONTH(o.date_upd)="'.pSQL(date('m')).'"'),
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/dashboard/front/dashboard.tpl');
    }
    public function _initContentDemo()
    {
        $no_data_char_commission= true;
        $months= array(1,2,3,4,5,6,7,8,9,10,11,12);
        $turn_over_datas= array();
        $seller_commission_datas = array();
        $chart_labels = array();
        foreach($months as $month)
        {
            $chart_labels[]= $month;
            $turn_over = (float)$this->_getTotalTurnOver(' AND month(o.date_upd) = "'.pSQL($month).'" AND YEAR(o.date_upd) ="'.pSQL(date('Y')).'"');
            $turn_over_datas[] = $turn_over;
            $seller_commission=(float)$this->_getTotalSellerCommission(' AND month(date_upd) = "'.pSQL($month).'" AND YEAR(date_upd) ="'.pSQL(date('Y')).'"');
            $seller_commission_datas[] = $seller_commission;
            if($seller_commission!=0 || $turn_over!=0)
                $no_data_char_commission = false;
        }
        $commissions_line_datasets = array(
            array(
                'label'=> $this->module->l('Turnover','dashboard'),
                'data' =>$turn_over_datas,
                'backgroundColor'=>'rgba(163,225,212,0.3)',
                'borderColor'=>'rgba(163,225,212,1)',
                'borderWidth'=>1,
                'pointRadius' => 2,
                'lineTension'=> 0
            ),
            array(
                'label'=> $this->module->l('Earning commission','dashboard'),
                'data' =>$seller_commission_datas,
                'backgroundColor'=>'rgba(253,193,7,0.3)',
                'borderColor'=>'rgba(253,193,7,1)',
                'borderWidth'=>1,
                'pointRadius' => 2,
                'lineTension'=> 0
            ),
        );
        $this->context->smarty->assign(
            array(
                'ets_mp_url_search_product' => $this->context->link->getModuleLink($this->module->name,'ajax',array('ajaxSearchProduct'=>1,'disableCombination'=>1,'active'=>true)),
                'no_data_char_commission' => $no_data_char_commission,
                'current_currency' => $this->context->currency,
                'commissions_line_datasets' => $commissions_line_datasets,
                'chart_labels' => $chart_labels,
                'total_turn_over' => $this->_getTotalTurnOver(' AND YEAR(o.date_upd)="'.pSQL(date('Y')).'"'),
                'total_earning_commission' => $this->_getTotalSellerCommission(' AND YEAR(date_upd) ="'.pSQL(date('Y')).'"'),
                'total_commission_balance' => Tools::convertPrice($this->seller->getTotalCommission(1)-$this->seller->getToTalUseCommission(1)),
                'total_withdrawls' => Tools::convertPrice($this->seller->getToTalUseCommission(1,false,false,true)),
                'total_commission_used' => Tools::convertPrice($this->seller->getToTalUseCommission(1)),
                'best_selling_products' => $this->_getBestSellingProducts(),
                'total_number_of_product_sold' => $this->_getTotalNumberOfProductSold(' AND YEAR(o.date_upd)="'.pSQL(date('Y')).'"'),
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/dashboard/front/dashboard.tpl');
    }
    public function _getTotalNumberOfProductSold($filter=false)
    {
        if(Configuration::get('ETS_MP_COMMISSION_APPROVED_WHEN'))
        {
            $status = explode(',',Configuration::get('ETS_MP_COMMISSION_APPROVED_WHEN'));
            $sql ='SELECT sum(product_quantity) FROM `'._DB_PREFIX_.'order_detail` od
            INNER JOIN `'._DB_PREFIX_.'orders` o ON (od.id_order=o.id_order)
            INNER JOIN `'._DB_PREFIX_.'ets_mp_seller_product` seller_product ON (seller_product.id_product=od.product_id)
            WHERE seller_product.id_customer="'.(int)$this->seller->id_customer.'" '.(Tools::getValue('id_product_chart') ? ' AND seller_product.id_product="'.(int)Tools::getValue('id_product_chart').'"':'').' AND o.current_state IN ('.implode(',',array_map('intval',$status)).')'.($filter ? $filter:'');
            return Db::getInstance()->getValue($sql);
        }
        else
            return 0;
    }
    public function _getTotalTurnOver($filter=false)
    {
        if(Configuration::get('ETS_MP_COMMISSION_APPROVED_WHEN'))
        {
            $status = explode(',',Configuration::get('ETS_MP_COMMISSION_APPROVED_WHEN'));
            $sql = 'SELECT SUM(od.total_price_tax_incl/c.conversion_rate) FROM `'._DB_PREFIX_.'orders` o
                INNER JOIN `'._DB_PREFIX_.'order_detail` od ON(od.id_order=o.id_order)
                INNER JOIN `'._DB_PREFIX_.'currency` c ON (o.id_currency=c.id_currency)
                INNER JOIN `'._DB_PREFIX_.'ets_mp_seller_order` seller_order ON (seller_order.id_order=o.id_order AND seller_order.id_customer="'.(int)$this->seller->id_customer.'")
                WHERE o.id_shop="'.(int)$this->context->shop->id.'" AND o.current_state IN ('.implode(',',array_map('intval',$status)).')'.($filter ? $filter:'').(Tools::getValue('id_product_chart') ? ' AND od.product_id="'.(int)Tools::getValue('id_product_chart').'"':'').'';
            $turn_over = Db::getInstance()->getValue($sql);
            return Tools::convertPrice($turn_over);
        }
        return 0;
    }
    public function _getTotalSellerCommission($filter=false)
    {
        $sql = 'SELECT sum(commission) FROM `'._DB_PREFIX_.'ets_mp_seller_commission` WHERE status=1 AND id_customer="'.(int)$this->seller->id_customer.'" AND id_shop="'.(int)$this->context->shop->id.'"'.($filter ? $filter:'').(Tools::getValue('id_product_chart') ? ' AND id_product="'.(int)Tools::getValue('id_product_chart').'"':'');
        $commission= Db::getInstance()->getValue($sql);
        return Tools::convertPrice($commission);
    }
    public function _getBestSellingProducts()
    {
        if(Configuration::get('ETS_MP_COMMISSION_APPROVED_WHEN'))
            $status = explode(',',Configuration::get('ETS_MP_COMMISSION_APPROVED_WHEN'));
        else
            return '';
        $nb_days_new_product = Configuration::get('PS_NB_DAYS_NEW_PRODUCT');
        $id_lang = (int)Context::getContext()->language->id;
        if (!Validate::isUnsignedInt($nb_days_new_product))
            $nb_days_new_product = 20;
        $prev_version = version_compare(_PS_VERSION_, '1.6.1.0', '<');
        $sql = 'SELECT DISTINCT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) AS quantity' . ($prev_version? ' ,IFNULL(product_attribute_shop.id_product_attribute, 0)':' ,MAX(product_attribute_shop.id_product_attribute)') . ' id_product_attribute, pl.`description`, pl.`description_short`, pl.`available_now`,
			product_sale.quantity quantity_sale, pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, i.`id_image`,
			il.`legend` as legend, m.`name` AS manufacturer_name,cl.name as default_category,CONCAT(customer.firstname," ",customer.lastname) as seller_name,seller.id_customer,seller.id_seller, seller_lang.shop_name,product_shop.`date_add`,
			DATEDIFF(product_shop.`date_add`, DATE_SUB("' . date('Y-m-d') . ' 00:00:00",
			INTERVAL ' . (int)$nb_days_new_product . ' DAY)) > 0 AS new, product_shop.price AS orderprice,commission.commission';
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
                INNER JOIN `'._DB_PREFIX_.'ets_mp_seller_product` sp ON (sp.id_product=p.id_product AND sp.id_customer="'.(int)$this->seller->id_customer.'")
                LEFT JOIN (SELECT id_product,id_customer,SUM(commission) as commission FROM `'._DB_PREFIX_.'ets_mp_seller_commission` WHERE id_customer="'.(int)$this->seller->id_customer.'" GROUP BY id_product,id_customer) commission ON (commission.id_product = p.id_product)  
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
        $products = Product::getProductsProperties($this->context->language->id,$products);
        if($products)
        {
            if($this->module->is17)
                $type_image= ImageType::getFormattedName('home');
            else
                $type_image= ImageType::getFormatedName('home');
            foreach($products as &$product)
            {
                $product['price'] = Tools::displayPrice($product['price']);
                if($product['id_image'])
                {
                    
                    $product['image'] = '<'.'a hr'.'ef="'.$this->context->link->getProductLink($product['id_product']).'"><i'.'mg src="'.$this->context->link->getImageLink($product['link_rewrite'],$product['id_image'],$type_image).'" style="width:80px;"><'.'/'.'a'.'>';
                }
                else
                    $product['image']='';
                $product['name'] = '<'.'a  hr'.'ef="'.$this->context->link->getProductLink($product['id_product']).'">'.$product['name'].'<'.'/'.'a'.'>';
                $product['commission'] = Tools::displayPrice($product['commission'],new Currency(Configuration::get('PS_CURRENCY_DEFAULT')));
                
            }
        }
        $this->context->smarty->assign(
            array(
                'products' => $products,
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/dashboard/front/best_selling_products.tpl');
    }
 }