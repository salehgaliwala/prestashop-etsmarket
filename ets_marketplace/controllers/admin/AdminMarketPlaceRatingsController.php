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
class AdminMarketPlaceRatingsController extends ModuleAdminController
{
    public function __construct()
    {
       parent::__construct();
       $this->context= Context::getContext();
       $this->bootstrap = true;
    }
    public function renderList()
    {
        $this->module->getContent();
        $this->context->smarty->assign(
            array(
                'ets_mp_body_html'=> $this->_renderRatings(),
            )
        );
        $html ='';
        if($this->context->cookie->success_message)
        {
            $html .= $this->module->displayConfirmation($this->context->cookie->success_message);
            $this->context->cookie->success_message ='';
        }
        if($this->module->_errors)
            $html .=$this->module->displayError($this->module->_errors);
        return $html.$this->module->display(_PS_MODULE_DIR_.$this->module->name.DIRECTORY_SEPARATOR.$this->module->name.'.php', 'admin.tpl');
    }
    public function postProcess()
    {
        parent::postProcess();
        if(Tools::getValue('approve')=='yes' && ($id_comment = Tools::getValue('id_comment')))
        {
                if(Module::isInstalled('productcomments'))
                {
                    $productComment = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'product_comment WHERE id_product_comment="'.(int)$id_comment.'"');
                    if(!$productComment)
                        $this->module->_errors[] = $this->l('Review is not valid');
                    else
                    {
                        Db::getInstance()->execute('update '._DB_PREFIX_.'product_comment SET validate=1 WHERE id_product_comment="'.(int)$id_comment.'"');
                        $this->context->cookie->success_message = $this->l('Approved successfully');
                        Tools::redirectAdmin($this->context->link->getAdminLink('AdminMarketPlaceRatings'));
                    }
                        
                }
                elseif(Module::isInstalled('ets_productcomments'))
                {
                    $productComment = new EtsPcProductComment($id_comment);
                    if(!Validate::isLoadedObject($productComment))
                        $this->module->_errors[] = $this->l('Review is not valid');
                    else
                    {
                        $productComment->validate = 1;
                        if($productComment->update())
                        {
                            $this->context->cookie->success_message = $this->l('Approved successfully');
                            Tools::redirectAdmin($this->context->link->getAdminLink('AdminMarketPlaceRatings'));
                        }
                        else
                            $this->module->_errors[] = $this->l('An error occurred while saving the review');
                    }
                }
        }
        if(Tools::getValue('del')=='yes' && ($id_comment = Tools::getValue('id_comment')))
        {
                if(Module::isInstalled('productcomments'))
                {
                    $productComment = Db::getInstance()->getRow('SELECT * FROM '._DB_PREFIX_.'product_comment WHERE id_product_comment="'.(int)$id_comment.'"');
                    if(!$productComment)
                        $this->module->_errors[] = $this->l('Review is not valid');
                    else
                    {
                        Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'product_comment WHERE id_product_comment="'.(int)$id_comment.'"');
                        $this->context->cookie->success_message = $this->l('Deleted successfully');
                        Tools::redirectAdmin($this->context->link->getAdminLink('AdminMarketPlaceRatings'));
                    }
                }
                elseif(Module::isInstalled('ets_productcomments'))
                {
                    $productComment = new EtsPcProductComment($id_comment);
                    if(!Validate::isLoadedObject($productComment))
                        $this->module->_errors[] = $this->l('Review is not valid');
                    else
                    {
                        if($productComment->delete())
                        {
                            $this->context->cookie->success_message = $this->l('Deleted successfully');
                            Tools::redirectAdmin($this->context->link->getAdminLink('AdminMarketPlaceRatings'));
                        }
                        else
                            $this->module->_errors[] = $this->l('An error occurred while deleting the review');
                    }
                }
        }
    }
    public function _renderRatings()
    {
        if(!Module::isInstalled('productcomments') && !Module::isInstalled('ets_productcomments'))
            return $this->module->displayWarning($this->l('You have to install the Product Comments module of PrestaShop or Product reviews, ratings, Q&A module of ETS-Soft'));
        $fields_list = array(
            'id_comment' => array(
                'title' => $this->l('ID'),
                'width' => 40,
                'type' => 'text',
                'sort' => true,
                'filter' => true,
            ),
            'review'=> array(
                'title' => $this->l('Review'),
                'type'=>'text',
                'strip_tag' => false,
                'sort'=>false,
                'filter'=> false,
            ),
            'grade' => array(
                'title' => $this->l('Ratings'),
                'type' => 'text',
                'sort' => true,
                'filter' => true,
                'strip_tag' => false,
            ),
            'name' => array(
                'title' => $this->l('Product'),
                'type' => 'text',
                'sort' => true,
                'filter' => true,
                'strip_tag'=>false
            ),
            'shop_name' => array(
                'title' => $this->l('Shop'),
                'type' => 'text',
                'sort' => true,
                'filter' => true,
                'strip_tag'=>false
            ),
            'validate' => array(
                'title' => $this->l('Status'),
                'type' => 'active',
                'sort' => true,
                'filter' => true,
                'strip_tag' => false,
                'filter_list' => array(
                    'id_option' => 'active',
                    'value' => 'title',
                    'list' => array(
                        0 => array(
                            'active' => 1,
                            'title' => $this->l('Enabled')
                        ),
                        1 => array(
                            'active' => 0,
                            'title' => $this->l('Disabled')
                        )
                    )
                )
            ),
            'date_add' => array(
                'title' => $this->l('Time of publication'),
                'type' => 'date',
                'sort' => true,
                'filter' => true,
                'class' => 'text-center'
            ),
        );
        //Filter
        $validate = true;
        if(Module::isInstalled('productcomments'))
        {
            if(!Configuration::get('PRODUCT_COMMENTS_MODERATE'))
                $validate = false;
        }elseif(Module::isInstalled('ets_productcomments'))
        {
            if(!Configuration::get('ETS_PC_MODERATE'))
                $validate = false;
        }
        if(!$validate)
            unset($fields_list['validate']);
        $show_resset = false;
        $filter = "";
        $having="";
        if(Tools::getValue('id_comment') && !Tools::getValue('del') && !Tools::getValue('approve'))
        {
            if(Module::isInstalled('ets_productcomments'))
                $filter .= ' AND pc.id_ets_pc_product_comment="'.(int)Tools::getValue('id_comment').'"';
            else
                $filter .= ' AND pc.id_product_comment="'.(int)Tools::getValue('id_comment').'"';
            $show_resset = true;
        }
        if(Tools::getValue('name'))
        {
            $filter .=' AND pl.name LIKE "%'.pSQL(Tools::getValue('name')).'%"';
            $show_resset = true;
        }
        if(Tools::getValue('shop_name'))
        {
            $filter .=' AND seller_lang.shop_name LIKE "%'.pSQL(Tools::getValue('shop_name')).'%"';
            $show_resset = true;
        }
        if(Tools::getValue('grade'))
        {
            $filter .= ' AND pc.grade ="'.(int)Tools::getValue('grade').'"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('validate'))!='')
        {
            $filter .= ' AND pc.validate="'.(int)Tools::getValue('validate').'"';
            $show_resset=true;
        }
        if($date_add_min = Tools::getValue('date_add_min'))
        {
            $filter .= ' AND pc.date_add >= "'.pSQL($date_add_min).'"';
            $show_resset = true;
        }
        if($date_add_max = Tools::getValue('date_add_max'))
        {
            $filter .= ' AND pc.date_add <= "'.pSQL($date_add_max).'"';
            $show_resset = true;
        }
        if($validate && !Configuration::get('ETS_MP_SELLER_DISPLAY_REVIEWS_WAITING'))
            $filter .=' AND pc.validate=1';
        //Sort
        $sort = "";
        if(Tools::getValue('sort','id_comment'))
        {
            switch (Tools::getValue('sort','id_comment')) {
                case 'id_comment':
                    $sort .='id_comment';
                    break;
                case 'name':
                    $sort .='pl.name';
                    break;
                case 'shop_name':
                    $sort .='seller_lang.shop_name';
                    break;
                case 'grade':
                    $sort .='pc.grade';
                    break;
                case 'validate':
                    $sort .='pc.validate';
                    break;
                case 'date_add':
                    $sort .='pc.date_add';
                    break;
            }
            if($sort && ($sort_type=Tools::getValue('sort_type','desc')) && in_array($sort_type,array('asc','desc')))
                $sort .= ' '.trim($sort_type);  
        }
        //Paggination
        $page = (int)Tools::getValue('page') && (int)Tools::getValue('page') > 0 ? (int)Tools::getValue('page') : 1;
        $totalRecords = (int)Ets_mp_seller::getListProductComments($filter,$having,0,0,'',true);
        $paggination = new Ets_mp_paggination_class();            
        $paggination->total = $totalRecords;
        $paggination->url = $this->context->link->getAdminLink('AdminMarketPlaceRatings').'&page=_page_'.$this->module->getFilterParams($fields_list,'mp_ratings');
        $paggination->limit =  20;
        $totalPages = ceil($totalRecords / $paggination->limit);
        if($page > $totalPages)
            $page = $totalPages;
        $paggination->page = $page;
        $start = $paggination->limit * ($page - 1);
        if($start < 0)
            $start = 0;
        $productComments = Ets_mp_seller::getListProductComments($filter, $having,$start,$paggination->limit,$sort,false);
        if($productComments)
        {
            foreach($productComments as &$productComment)
            {
                if(Tools::strlen($productComment['content'])>135)
                {
                    $productComment['content'] = Tools::substr($productComment['content'],0,135).'...';
                }
                $productComment['review'] = '<'.'b'.'>'.$productComment['title'].'<'.'/'.'b'.'><'.'/br'.'>'.$productComment['content'];
                $productComment['grade'] = $productComment['grade'].'/5';
                $productComment['child_view_url'] = $this->context->link->getAdminLink('AdminModules').'&configure='.(Module::isInstalled('productcomments') ? 'productcomments':'ets_productcomments');
                $productComment['name'] ='<'.'a hr'.'ef="'.$this->context->link->getProductLink($productComment['id_product']).'" tar'.'get="_bla'.'nk">'.$productComment['name'].'<'.'/'.'a'.'>';;
                $productComment['shop_name'] ='<'.'a hr'.'ef="'.$this->module->getShopLink(array('id_seller'=>$productComment['id_seller'])).'" tar'.'get="_bla'.'nk">'.$productComment['shop_name'].'<'.'/'.'a'.'>';;
                $productComment['action_edit'] = false;
                $productComment['action_delete'] = true;
                if($validate && !$productComment['validate'])
                    $productComment['action_approve'] = true;
            }
        }
        $paggination->text =  $this->l('Showing {start} to {end} of {total} ({pages} Pages)','ratings');
        $paggination->style_links = 'links';
        $paggination->style_results = 'results';
        $listData = array(
            'name' => 'mp_ratings',
            'actions' => array('view','approve_review','delete'),
            'currentIndex' => $this->context->link->getAdminLink('AdminMarketPlaceRatings'),
            'identifier' => 'id_comment',
            'show_toolbar' => true,
            'show_action' => true,
            'title' => $this->l('Ratings'),
            'fields_list' => $fields_list,
            'field_values' => $productComments,
            'paggination' => $paggination->render(),
            'filter_params' => $this->module->getFilterParams($fields_list,'mp_ratings'),
            'show_reset' =>$show_resset,
            'totalRecords' => $totalRecords,
            'sort'=> Tools::getValue('sort','id_comment'),
            'show_add_new'=>  false,
            'sort_type' => Tools::getValue('sort_type','desc'),
        );           
        return $this->module->renderList($listData);
    }
}