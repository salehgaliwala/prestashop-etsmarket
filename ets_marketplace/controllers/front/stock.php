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
class Ets_MarketPlaceStockModuleFrontController extends ModuleFrontController
{
    public $_errors= array();
    public $_success ='';
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
            die($this->module->l('You do not have permission to access this page','suppliers'));
        if(Tools::isSubmit('applyNewQuantity'))
        {
            if($mp_stocks_quantity = Tools::getValue('mp_stocks_quantity'))
            {
                $data= false;
                foreach($mp_stocks_quantity as $id_stock_available =>$quantity)
                {
                    if($quantity)
                    {
                        $data = true;
                        $stock_available = new StockAvailable($id_stock_available);
                        if(!Validate::isLoadedObject($stock_available) || !$this->seller->checkHasProduct($stock_available->id_product) || !Validate::isInt($stock_available->quantity + $quantity))
                        {
                            die(
                                json_encode(
                                    array(
                                        'errors' => $this->module->l('Data submit is not valid','stock'),
                                    )
                                )
                            );
                        }
                    }
                    
                }
                if($data)
                {
                    foreach($mp_stocks_quantity as $id_stock_available =>$quantity)
                    {
                        if($quantity)
                        {
                            $stock_available = new StockAvailable($id_stock_available);
                            $stock_available->quantity = $stock_available->quantity +$quantity;
                            $stock_available->update();
                        }
                        
                    }
                    die(
                        json_encode(
                            array(
                                'success' => $this->module->l('Updated quantity successfully','stock'),
                            )
                        )
                    );
                }
                
            }
            die(
                json_encode(
                    array(
                        'errors' => $this->module->l('Data submitted is blank','stock'),
                    )
                )
            );
        }
        if(Tools::isSubmit('editStockAvailable') && ($id_stock_available = (int)Tools::getValue('id_stock_available')) && ($quantity = (int)Tools::getValue('quantity')))
        {
            $stock_available = new StockAvailable($id_stock_available);
            $stock_available->quantity +=$quantity; 
            if(!Validate::isInt($stock_available->quantity))
            {
                die(
                    json_encode(
                        array(
                            'errors' => $this->module->l('Quantity is not valid','stock'),
                        )
                    )
                );
            }
            elseif(!Validate::isLoadedObject($stock_available) || !$this->seller->checkHasProduct($stock_available->id_product))
            {
                die(
                    json_encode(
                        array(
                            'errors' => $this->module->l('Data submit is not valid','stock'),
                        )
                    )
                );
            }
            elseif($stock_available->update())
            {
                die(
                    json_encode(
                        array(
                            'success' => $this->module->l('Updated quantity successfully','stock'),
                            'quantity' => $stock_available->quantity
                        )
                    )
                );
            }
            else
            {
                die(
                    json_encode(
                        array(
                            'errors' => $this->module->l('An error occurred while saving data','stock'),
                        )
                    )
                );
            }
        }
    }
    public function initContent()
	{
		parent::initContent();
        if($this->context->cookie->success_message)
        {
            $this->_success = $this->context->cookie->success_message;
            $this->context->cookie->success_message ='';
        }    
        $this->context->smarty->assign(
            array(
                'path' => $this->module->getBreadCrumb(),
                'breadcrumb' => $this->module->is17 ? $this->module->getBreadCrumb() : false, 
                'html_content' => $this->_initContent(),
                '_errors' => $this->_errors ? $this->module->displayError($this->_errors):'',
                '_success' => $this->_success ? $this->module->displayConfirmation($this->_success):'',
            )
        );
        if($this->module->is17)
            $this->setTemplate('module:'.$this->module->name.'/views/templates/front/stock.tpl');      
        else        
            $this->setTemplate('stock_16.tpl'); 
    }
    public function _initContent()
    {
        $fields_list = array(
            'checkbox' => array(
                'title' => '',
                'width' => 40,
                'type' => 'checkbox',
            ),
            'name' => array(
                'title' => $this->module->l('product','stock'),
                'width' => 40,
                'type' => 'text',
                'sort' => true,
                'filter' => false,
                'strip_tag'=>false,
            ),
            'reference'=> array(
                'title' => $this->module->l('Reference','stock'),
                'type'=>'text',
                'strip_tag' => false,
                'sort'=>false,
                'filter'=> false,
            ),
            'supplier_name' => array(
                'title' => $this->module->l('Supplier','stock'),
                'type' => 'text',
                'sort' => true,
                'filter' => false,
                'strip_tag' => false,
            ),
            'active' => array(
                'title' => $this->module->l('Status','stock'),
                'type' => 'active',
                'sort' => true,
                'filter' => false,
                'strip_tag' => false,
                'filter_list' => array(
                    'id_option' => 'active',
                    'value' => 'title',
                    'list' => array(
                        0 => array(
                            'active' => 1,
                            'title' => $this->module->l('Enabled','stock')
                        ),
                        1 => array(
                            'active' => 0,
                            'title' => $this->module->l('Disabled','stock')
                        )
                    )
                )
            ),
            'quantity' => array(
                'title' => $this->module->l('Available','stock'),
                'type' => 'text',
                'sort' => true,
                'filter' => false,
                'strip_tag' => false,
            ),
            'input' => array(
                'title' => $this->module->l('Edit quantity','stock'),
                'type' =>'input_number',
                'field'=>'quantity'
            ),
        );
        //Sort
        $sort = "";
        if(Tools::getValue('sort','id_stock_available'))
        {
            switch (Tools::getValue('sort','id_stock_available')) {
                case 'id_stock_available':
                    $sort .='stock.id_stock_available';
                    break;
                case 'name':
                    $sort .='pl.name';
                    break;
                case 'reference':
                    $sort .='p.reference';
                    break;
                case 'supplier_name':
                    $sort .='su.name';
                    break;
                case 'quantity':
                    $sort .='stock.quantity';
                    break;
            }
            if($sort && ($sort_type=Tools::getValue('sort_type','desc')) && in_array($sort_type,array('asc','desc')))
                $sort .= ' '.trim($sort_type);  
        }
        //Paggination
        $page = (int)Tools::getValue('page') && (int)Tools::getValue('page') > 0 ? (int)Tools::getValue('page') : 1;
        $totalRecords = (int) $this->seller->getStockAvailables(0,0,'',true);
        $paggination = new Ets_mp_paggination_class();            
        $paggination->total = $totalRecords;
        $paggination->url =$this->context->link->getModuleLink($this->module->name,'stock',array('list'=>true, 'page'=>'_page_')).$this->module->getFilterParams($fields_list,'mp_stocks');
        $paggination->limit =  10;
        $totalPages = ceil($totalRecords / $paggination->limit);
        if($page > $totalPages)
            $page = $totalPages;
        $paggination->page = $page;
        $start = $paggination->limit * ($page - 1);
        if($start < 0)
            $start = 0;
        $stockAvailables = $this->seller->getStockAvailables($start,$paggination->limit,$sort,false);
        if($stockAvailables)
        {
            foreach($stockAvailables as &$stockAvailable)
            {
                $stockAvailable['action_edit'] = false;
                if($stockAvailable['id_product_attribute'])
                    $stockAvailable['name'] = $stockAvailable['name'].'<'.'br'.'/'.'>'.'<'.'i'.'>'.$this->module->getProductAttributeName($stockAvailable['id_product_attribute'],true).'<'.'/'.'i'.'>';
            }
        }
        $paggination->text =  $this->module->l('Showing {start} to {end} of {total} ({pages} Pages)','stock');
        $paggination->style_links = 'links';
        $paggination->style_results = 'results';
        $listData = array(
            'name' => 'mp_stocks',
            'actions' => array(),
            'currentIndex' => $this->context->link->getModuleLink($this->module->name,'stock',array('list'=>1)),
            'identifier' => 'id_stock_available',
            'show_toolbar' => false,
            'show_action' => false,
            'title' => $this->module->l('Stock','stock'),
            'fields_list' => $fields_list,
            'field_values' => $stockAvailables,
            'paggination' => $paggination->render(),
            'filter_params' => $this->module->getFilterParams($fields_list,'mp_stocks'),
            'show_reset' =>false,
            'totalRecords' => $totalRecords,
            'sort'=> Tools::getValue('sort','id_stock_available'),
            'show_add_new'=>  false,
            'sort_type' => Tools::getValue('sort_type','desc'),
            'bulk_action_html' => $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/bulk_stock.tpl')
        );           
        return $this->module->renderList($listData);
    }
}