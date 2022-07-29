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
class Ets_mp_report extends ObjectModel
{
    protected static $instance;
    public $id_customer;
    public $id_seller;
    public $id_product;
    public $title;
    public $content;
    public $date_add;
    public $date_upd;
    public static $definition = array(
		'table' => 'ets_mp_seller_report',
		'primary' => 'id_ets_mp_seller_report',
		'multilang' => false,
		'fields' => array(
            'id_customer' => array('type' => self::TYPE_INT),
            'id_seller'  => array('type' => self::TYPE_INT),
            'id_product' => array('type'=> self::TYPE_INT),
            'title' => array('type'=> self::TYPE_STRING),
            'content' => array('type'=> self::TYPE_STRING),
            'date_add' => array('type'=>self::TYPE_DATE),
            'date_upd' => array('type'=>self::TYPE_DATE),
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
            self::$instance = new Ets_mp_report();
        }
        return self::$instance;
    }
    public function _renderReports()
    {
        $module = Module::getInstanceByName('ets_marketplace');
        if(Tools::isSubmit('viewreport') && $id_report = Tools::getValue('id_report'))
        {
            return $this->_renderViewReport($id_report);
        }
        $fields_list = array(
            'id_ets_mp_seller_report' => array(
                'title' => $this->l('ID'),
                'width' => 40,
                'type' => 'text',
                'sort' => true,
                'filter' => true,
            ),
            'reporter_name' => array(
                'title'=> $this->l('Reporter'),
                'type'=> 'text',
                'sort'=>true,
                'filter'=>true,
            ),
            'email' => array(
                'title' => $this->l('Email'),
                'type'=> 'text',
                'sort'=>true,
                'filter'=>true,
            ),
            'shop_name' => array(
                'title'=> $this->l('Shop'),
                'type'=> 'text',
                'sort'=>true,
                'filter'=>true,
                'strip_tag' => false,
            ),
            'product_name' => array(
                'title'=> $this->l('Product'),
                'type'=> 'text',
                'sort'=>true,
                'filter'=>true,
                'strip_tag' => false,
            ),
            'title' => array(
                'title' => $this->l('Title'),
                'type'=> 'text',
                'sort'=>true,
                'filter'=>true,
            ),
            'content' => array(
                'title'=> $this->l('Report content'),
                'type'=> 'text',
                'sort'=>true,
                'filter'=>true,
                'strip_tag' => false,
            )
        );
        $show_resset = false;
        $filter ='';
        if(Tools::getValue('id_ets_mp_seller_report') && !Tools::isSubmit('del'))
        {
            $filter .= ' AND r.id_ets_mp_seller_report="'.(int)Tools::getValue('id_ets_mp_seller_report').'"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('reporter_name'))!='')
        {
            $filter .= ' AND CONCAT(reporter.firstname," ",reporter.lastname) LIKE "%'.pSQL(Tools::getValue('reporter_name')).'%"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('shop_name'))!='')
        {
            $filter .= ' AND sl.shop_name LIKE "%'.pSQL(Tools::getValue('shop_name')).'%"';
            $show_resset=true;
        }
        if(trim(Tools::getValue('product_name'))!='')
        {
            $filter .= ' AND pl.name LIKE "%'.pSQL(Tools::getValue('product_name')).'%"';
            $show_resset=true;
        }
        if(trim(Tools::getValue('content'))!='')
        {
            $filter .= ' AND r.content LIKE "%'.pSQL(Tools::getValue('content')).'%"';
            $show_resset=true;
        }
        if(trim(Tools::getValue('email'))!='')
        {
            $filter .= ' AND reporter.email LIKE "%'.pSQL(Tools::getValue('email')).'%"';
            $show_resset=true;
        }
        if(trim(Tools::getValue('title'))!='')
        {
            $filter .= ' AND r.title LIKE "%'.pSQL(Tools::getValue('title')).'%"';
            $show_resset=true;
        }
        $sort = "";
        if(Tools::getValue('sort'))
        {
            switch (Tools::getValue('sort')) {
                case 'id_ets_mp_seller_report':
                    $sort .=' r.id_ets_mp_seller_report';
                    break;
                case 'reporter_name':
                    $sort .=' reporter_name';
                    break;
                case 'product_name':
                    $sort .= ' pl.name';
                    break;
                case 'content':
                    $sort .= ' r.content';
                    break;
                case 'shop_name':
                    $sort .= 'sl.shop_name';
                    break;
                case 'email':
                    $sort .= 'reporter.email';
                    break;
                case 'title':
                    $sort .= 'r.title';
                    break;
            }
            if($sort && ($sort_type=Tools::getValue('sort_type')) && in_array($sort_type,array('acs','desc')))
                $sort .= ' '.$sort_type;  
        }
        $page = (int)Tools::getValue('page') && (int)Tools::getValue('page') > 0 ? (int)Tools::getValue('page') : 1;
        $totalRecords = (int) Ets_mp_report::_getReports($filter,$sort,0,0,true);
        $paggination = new Ets_mp_paggination_class();            
        $paggination->total = $totalRecords;
        $paggination->url = $this->context->link->getAdminLink(Tools::getValue('AdminMarketPlaceReport')).'&page=_page_'.$module->getFilterParams($fields_list,'ets_report');
        $paggination->limit =  20;
        $totalPages = ceil($totalRecords / $paggination->limit);
        if($page > $totalPages)
            $page = $totalPages;
        $paggination->page = $page;
        $start = $paggination->limit * ($page - 1);
        if($start < 0)
            $start = 0;
        $reports = Ets_mp_report::_getReports($filter,$sort,$start,$paggination->limit,false);
        if($reports)
        {
            foreach($reports as &$report)
            {
                $report['child_view_url'] = $this->context->link->getAdminLink('AdminMarketPlaceReport').'&viewreport=1&id_report='.(int)$report['id_ets_mp_seller_report'];
                if($report['id_product'])
                    $report['product_name'] = '<'.'a hr'.'ef="'.$this->context->link->getProductLink($report['id_product']).'" ta'.'rget="_bla'.'nk">'.$report['product_name'].'<'.'/'.'a'.'>';
                $report['shop_name'] = '<'.'a hr'.'ef="'.$module->getShopLink(array('id_seller'=>$report['id_seller'])).'" tar'.'get="_bla'.'nk">'.$report['shop_name'].'<'.'/'.'a'.'>';
                $report['content'] = Tools::nl2br($report['content']);
            }
        }
        $paggination->text =  $this->l('Showing {start} to {end} of {total} ({pages} Pages)');
        $paggination->style_links = 'links';
        $paggination->style_results = 'results';
        $listData = array(
            'name' => 'ets_report',
            'actions' => array('delete'),
            'icon' => 'icon-report',
            'currentIndex' => $this->context->link->getAdminLink('AdminMarketPlaceReport'),
            'identifier' => 'id_ets_mp_seller_report',
            'show_toolbar' => true,
            'show_action' => true,
            'title' => $this->l('Reports'),
            'fields_list' => $fields_list,
            'field_values' => $reports,
            'paggination' => $paggination->render(),
            'filter_params' => $module->getFilterParams($fields_list,'ets_report'),
            'show_reset' =>$show_resset,
            'totalRecords' => $totalRecords,
            'sort'=> Tools::getValue('sort','id_ets_mp_seller_report'),
            'sort_type' => Tools::getValue('sort_type','desc'),
        );            
        return  $module->renderList($listData);
    }
    public function _renderViewReport($id_report)
    {
        $sql = 'SELECT r.*,pl.name as product_name, CONCAT(reporter.firstname," ",reporter.lastname) as reporter_name,sl.shop_name,reporter.email FROM `'._DB_PREFIX_.'ets_mp_seller_report` r
            INNER JOIN `'._DB_PREFIX_.'ets_mp_seller` s ON (s.id_seller = r.id_seller)
            LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_lang` sl ON (s.id_seller=sl.id_seller AND sl.id_lang="'.(int)$this->context->language->id.'")
            LEFT JOIN `'._DB_PREFIX_.'customer` reporter ON (reporter.id_customer=r.id_customer)
            LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (pl.id_product= r.id_product AND pl.id_lang="'.(int)$this->context->language->id.'")
            WHERE r.id_ets_mp_seller_report='.(int)$id_report;
        $report = Db::getInstance()->getRow($sql);
        $this->context->smarty->assign(
            array(
                'report' => $report,
                'module_marketplace' => Module::getInstanceByName('ets_marketplace'),
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.'ets_marketplace/views/templates/hook/view_report.tpl');
    }
    static public function _getReports($filter='',$sort='',$start=0,$limit=10,$total=false)
    {
        $context = Context::getContext();
        if($total)
        {
            $sql = 'SELECT COUNT(distinct id_ets_mp_seller_report) FROM `'._DB_PREFIX_.'ets_mp_seller_report` r
            INNER JOIN `'._DB_PREFIX_.'ets_mp_seller` s ON (s.id_seller = r.id_seller)
            LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_lang` sl ON (s.id_seller=sl.id_seller AND sl.id_lang="'.(int)$context->language->id.'")
            LEFT JOIN `'._DB_PREFIX_.'customer` reporter ON (reporter.id_customer=r.id_customer)
            LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (pl.id_product= r.id_product AND pl.id_lang="'.(int)$context->language->id.'")
            WHERE s.id_shop = "'.(int)$context->shop->id.'"'.$filter;
            return Db::getInstance()->getValue($sql);
        }
        else
        {
            $sql = 'SELECT r.*,pl.name as product_name, CONCAT(reporter.firstname," ",reporter.lastname) as reporter_name,reporter.email,sl.shop_name FROM `'._DB_PREFIX_.'ets_mp_seller_report` r
            INNER JOIN `'._DB_PREFIX_.'ets_mp_seller` s ON (s.id_seller = r.id_seller)
            LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_lang` sl ON (s.id_seller=sl.id_seller AND sl.id_lang="'.(int)$context->language->id.'")
            LEFT JOIN `'._DB_PREFIX_.'customer` reporter ON (reporter.id_customer=r.id_customer)
            LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (pl.id_product= r.id_product AND pl.id_lang="'.(int)$context->language->id.'")
            WHERE s.id_shop = "'.(int)$context->shop->id.'"'.$filter
            .' GROUP BY r.id_ets_mp_seller_report'
            .($sort ? ' ORDER BY '.$sort: ' ORDER BY r.id_ets_mp_seller_report DESC')
            .' LIMIT '.(int)$start.','.(int)$limit.'';
            return Db::getInstance()->executeS($sql);
        }
        
    }
    public static function submitReportShop($module)
    {
        $errors = array();
        if(!$report_title = Tools::getValue('report_title'))
            $errors[] = $module->l('Title is required','report');
        elseif(!Validate::isCleanHtml($report_title))
            $errors[] = $module->l('Title is not valid','report');
        elseif(Tools::strlen($report_title) >100)
            $errors[] = $module->l('Title can not be longer than 100 characters','report');
        if(!$report_content = Tools::getValue('report_content'))
            $errors[] = $module->l('Content is required','report');
        elseif($report_content && !Validate::isCleanHtml($report_content))
            $errors[] = $module->l('Content is not valid','report');
        elseif(Tools::strlen($report_content) >300)
            $errors[] = $module->l('Content can not be longer than 300 characters','report');
        if(!$id_seller_report = (int)Tools::getValue('id_seller_report'))
        {
            $errors[] = $module->l('Shop report is null','report');
        }
        elseif(($seller = new Ets_mp_seller($id_seller_report)) &&  !Validate::isLoadedObject($seller))
        {
            $errors[] = $module->l('Shop report is not valid','report');
        }
        elseif(($id_product = Tools::getValue('id_product_report')) && ($product = new Product($id_product)) && (!Validate::isLoadedObject($product) || !$seller->checkHasProduct($id_product)))
            $errors[] = $module->l('Product report is not valid','report');
        if(Configuration::get('ETS_MP_ENABLE_CAPTCHA') && Tools::isSubmit('g-recaptcha-response'))
        {
            if(!Tools::getValue('g-recaptcha-response'))
            {
                $errors[] = $module->l('reCAPTCHA is invalid');
            }
            else
            {
                $recaptcha = Tools::getValue('g-recaptcha-response') ? Tools::getValue('g-recaptcha-response') : false;
                if ($recaptcha) {
                    $response = json_decode(Tools::file_get_contents($module->link_capcha), true);
                    if ($response['success'] == false) {
                        $errors[] = $module->l('reCAPTCHA is invalid');
                    }
                }
            }
            
        }
        if($errors)
        {
            die(
                Tools::jsonEncode(
                    array(
                        'errors' => $module->displayError($errors),
                    )
                )
            );
        }
        else
        {
            $report = new Ets_mp_report();
            $report->id_seller = (int)$id_seller_report;
            $report->id_customer = Context::getContext()->customer->id;
            $report->id_product = (int)$id_product;
            $report->title = $report_title;
            $report->content = $report_content;
            if($report->add())
            {
                if(Configuration::get('ETS_MP_EMAIL_SELLER_REPORT'))
                {
                    $report_seller = new Ets_mp_seller($report->id_seller);
                    $template_vars = array(
                        '{seller_name}' => $report_seller->seller_name,
                        '{reporter}' => Context::getContext()->customer->firstname.' '.Context::getContext()->customer->lastname,
                        '{shop_seller}' => $report_seller->shop_name[$report_seller->id_language],
                        '{product_name}' => $report->id_product ? (new Product($report->id_product,false,$report_seller->id_language))->name: '',
                        '{link_report}' => $report->id_product ? Context::getContext()->link->getProductLink($report->id_product) : $report_seller->getLink(),
                        '{title}' => $report->title,
                        '{content}' => Tools::nl2br($report->content),
                    );
                    $subjects = array(
                        'translation' =>$module->l('Seller shop was reported as abused','report') ,
                        'origin'=>'Seller shop was reported as abused',
                        'specific'=>'report'
                    );  
                    Ets_marketplace::sendMail($report->id_product ? 'to_seller_when_report_product' : 'to_seller_when_report_shop',$template_vars,$report_seller->seller_email,$subjects); 
                }
                if(Configuration::get('ETS_MP_EMAIL_ADMIN_REPORT'))
                {
                    $report_seller = new Ets_mp_seller($report->id_seller,Context::getContext()->language->id);
                    $template_vars = array(
                        '{seller_name}' => $report_seller->seller_name,
                        '{reporter}' => Context::getContext()->customer->firstname.' '.Context::getContext()->customer->lastname,
                        '{shop_seller}' => $report_seller->shop_name,
                        '{product_name}' => $report->id_product ? (new Product($report->id_product,false,Context::getContext()->language->id))->name: '',
                        '{link_report}' => $report->id_product ? Context::getContext()->link->getProductLink($report->id_product) : $report_seller->getLink(),
                        '{title}' => $report->title,
                        '{content}' => Tools::nl2br($report->content),
                    );
                    $subjects = array(
                        'translation' =>$module->l('Seller shop was reported as abused','report') ,
                        'origin'=>'Seller shop was reported as abused',
                        'specific'=>'report'
                    );  
                    Ets_marketplace::sendMail($report->id_product ? 'to_admin_when_report_product' : 'to_admin_when_report_shop',$template_vars,'',$subjects); 
                    
                }
                die(
                    Tools::jsonEncode(
                        array(
                            'success' => $module->l('Reported successfully','report'),
                        )
                    )
                );
            }
            else
            {
                die(
                    Tools::jsonEncode(
                        array(
                            'errors' => $module->displayError($module->l('Report failed.','report')),
                        )
                    )
                );
            }    
        }
    }
    public function l($string)
    {
        return Translate::getModuleTranslation('ets_marketplace', $string, pathinfo(__FILE__, PATHINFO_FILENAME));
    }
 }