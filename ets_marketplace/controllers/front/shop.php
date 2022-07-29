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
class Ets_MarketPlaceShopModuleFrontController extends ModuleFrontController
{
    public function __construct()
	{
		parent::__construct();
        $this->display_column_right=false;
        $this->display_column_left =false;
	}
    public function postProcess()
    {
        parent::postProcess();
        if(!Configuration::get('ETS_MP_ENABLED'))
            Tools::redirect($this->context->link->getPageLink('my-account'));
        if(Tools::isSubmit('submitunfollow') && $id_seller = Tools::getValue('id_seller'))
        {
            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'ets_mp_seller_customer_follow` WHERE id_seller="'.(int)$id_seller.'" AND id_customer="'.(int)$this->context->customer->id.'"');
            $total_follow = Db::getInstance()->getValue('SELECT COUNT(id_customer) FROM `'._DB_PREFIX_.'ets_mp_seller_customer_follow` WHERE id_seller='.(int)$id_seller);
            die(
                Tools::jsonEncode(
                    array(
                        'success' => $this->module->l('Unfollow successfully','shop'),
                        'follow'=>false,
                        'total_follow' => $total_follow > 1 ? '<i class="fa fa-thumbs-o-up"></i> '.$this->module->l('Followers','shop').': <span>'.$total_follow.'</span>' : '<i class="fa fa-thumbs-o-up"></i> '.$this->module->l('Follower','shop').': <span>'.$total_follow.'</span>',
                    )
                )
            );
        }
        if(Tools::isSubmit('submitfollow') && $id_seller=Tools::getValue('id_seller'))
        {
            if(!Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'ets_mp_seller_customer_follow` WHERE id_seller="'.(int)$id_seller.'" AND id_customer="'.(int)$this->context->customer->id.'"'))
                Db::getInstance()->execute('INSERT INTO `'._DB_PREFIX_.'ets_mp_seller_customer_follow`(id_seller,id_customer) VALUES("'.(int)$id_seller.'","'.(int)$this->context->customer->id.'")');
            $total_follow = Db::getInstance()->getValue('SELECT COUNT(id_customer) FROM `'._DB_PREFIX_.'ets_mp_seller_customer_follow` WHERE id_seller='.(int)$id_seller);
            die(
                Tools::jsonEncode(
                    array(
                        'success' => $this->module->l('Follow successfully','shop'),
                        'follow'=>true,
                        'total_follow' => $total_follow ? ($total_follow > 1 ? '<i class="fa fa-thumbs-o-up"></i> '.$this->module->l('Followers','shop').': <span>'.$total_follow.'</span>': '<i class="fa fa-thumbs-o-up"></i> '.$this->module->l('Follower','shop').': <span>'.$total_follow.'</span>'):false,
                    )
                )
            );
        }
        if(Tools::isSubmit('getmaps') && $id_seller= Tools::getValue('id_seller'))
        {
            Ets_mp_seller::getMaps($id_seller);
        }
    }
    public function initContent()
	{
		parent::initContent();
        if(Configuration::get('PS_REWRITING_SETTINGS') && isset($_SERVER['REQUEST_URI']) && Tools::strpos($_SERVER['REQUEST_URI'],'module/ets_marketplace'))
        {
            if($id_seller= (int)Tools::getValue('id_seller'))
                Tools::redirect($this->module->getShopLink(array('id_seller' =>$id_seller)));
            else    
                Tools::redirect($this->module->getShopLink());
        }
        $this->module->setMetas();
        $this->context->smarty->assign(
            array(
                'html_content' =>$this->_initContent(),
                'path' => $this->module->getBreadCrumb(),
                'breadcrumb' => $this->module->is17 ? $this->module->getBreadCrumb() : false, 
            )
        );
        if($this->module->is17)
            $this->setTemplate('module:'.$this->module->name.'/views/templates/front/shop.tpl');      
        else        
            $this->setTemplate('shop_16.tpl'); 
    }
    public function _initContent()
    {
        if($id_seller= (int)Tools::getValue('id_seller'))
        {
            $seller = new Ets_mp_seller($id_seller,$this->context->language->id);
            if($seller->active==1)
            {
                $filter='';
                if(Tools::getValue('current_tab','all')=='all')
                {
                   if(trim(Tools::getValue('product_name')))
                        $filter .=' AND (pl.name LIKE "%'.trim(Tools::getValue('product_name')).'%" OR p.reference LIKE "'.pSQL(Tools::getValue('product_name')).'" OR p.id_product="'.(int)Tools::getValue('product_name').'")'; 
                }
                if(trim(Tools::getValue('idCategories'),','))
                {
                    $idCategories = explode(',',trim(Tools::getValue('idCategories'),','));
                    $filter .=' AND cp.id_category IN ('.implode(',',array_map('intval',$idCategories)).')';
                }
                $page = (int)Tools::getValue('page') && (int)Tools::getValue('page') > 0 ? (int)Tools::getValue('page') : 1;
                $totalRecords = (int)$this->getProducts($filter,0,0,'',true);
                $paggination = new Ets_mp_paggination_class();            
                $paggination->total = $totalRecords;
                $paggination->url = $this->module->getShopLink(array('id_seller'=>$seller->id,'current_tab'=>Tools::getValue('current_tab','all'),'page'=>'_page_'));
                $paggination->limit =  12;
                $totalPages = ceil($totalRecords / $paggination->limit);
                if($page > $totalPages)
                    $page = $totalPages;
                $paggination->page = $page;
                $paggination->text =  $this->module->l('Showing {start} to {end} of {total} ({pages} Pages)','shop');
                if(Tools::getValue('order_by'))
                {
                    switch (Tools::getValue('order_by')) {
                        case 'position.asc':
                            $order_by= 'cp.position asc';
                            break;
                        case 'name.asc':
                            $order_by = ' pl.name asc';
                            break;
                        case 'name.desc':
                            $order_by =' pl.name desc';
                            break;
                        case 'price.desc':
                            $order_by =' product_shop.price desc';
                            break;
                        case 'price.asc':
                            $order_by =' product_shop.price asc';
                            break;
                        case 'new_product':
                            $order_by =' product_shop.date_add desc';
                            break;
                        case 'best_sale':
                            $order_by =' sale.quantity desc';
                            break;
                        default:
                            $order_by= 'cp.position asc';
                    } 
                }
                else
                    $order_by= 'cp.position asc';
                $reviews = $seller->getAVGReviewProduct();
                $total_reviews = isset($reviews['avg_grade']) ? $reviews['avg_grade']:0;
                $count_reviews = isset($reviews['count_grade']) ? $reviews['count_grade']:0;
                
                $total_messages = $this->module->_getOrderMessages('',null,null,null,true,$seller->id);
                if($total_messages)
                {
                    $total_messages_reply = Db::getInstance()->getValue('SELECT COUNT(DISTINCT cm.id_customer_thread) FROM `'._DB_PREFIX_.'customer_message` cm INNER JOIN `'._DB_PREFIX_.'ets_mp_seller_customer_message` scm ON (scm.id_customer_message=cm.id_customer_message AND scm.id_customer="'.(int)$seller->id_customer.'")') + Db::getInstance()->getValue('SELECT COUNT(DISTINCT id_contact) FROM `'._DB_PREFIX_.'ets_mp_seller_contact_message` WHERE id_seller='.(int)$seller->id);
                    $response_rate = Tools::ps_round($total_messages_reply*100/$total_messages,2);
                }
                if(Configuration::get('ETS_MP_DISPLAY_PRODUCT_SOLD'))
                {
                    $total_product_sold = $seller->_getTotalNumberOfProductSold();
                }
                if(Configuration::get('ETS_MP_ENABLE_CAPTCHA') && Configuration::get('ETS_MP_ENABLE_CAPTCHA_FOR') && $this->context->customer->logged)
                {
                    $captcha_for = explode(',',Configuration::get('ETS_MP_ENABLE_CAPTCHA_FOR'));
                    if(in_array('shop_report',$captcha_for) &&  !Configuration::get('ETS_MP_NO_CAPTCHA_IS_LOGIN'))
                        $is_captcha = true;
                }
                if($seller->id_group && Db::getInstance()->getValue('SELECT id_ets_mp_seller_group FROM `'._DB_PREFIX_.'ets_mp_seller_group` WHERE id_ets_mp_seller_group='.(int)$seller->id_group))
                {
                    $this->context->smarty->assign(
                        array(
                            'seller_group' => new Ets_mp_seller_group($seller->id_group,$this->context->language->id),
                            
                        )
                    );
                }
                $this->context->smarty->assign(
                    array(
                        'seller'=>$seller,
                        'totalProducts' => $totalRecords,
                        'total_all_products' => $seller->getProducts(false,0,0,false,true,true),
                        'total_new_products' => $seller->getNewProducts(false,0,0,false,true),
                        'total_best_seller_products' => $seller->getBestSellerProducts(false,false,false,false,true),
                        'total_special_products' => $seller->getSpecialProducts(false,false,false,false,true),
                        'total_reviews' => Tools::ps_round($total_reviews,1),
                        'total_follow' => Db::getInstance()->getValue('SELECT COUNT(id_customer) FROM `'._DB_PREFIX_.'ets_mp_seller_customer_follow` WHERE id_seller='.(int)$seller->id),
                        'total_reviews_int' => (int)$total_reviews,
                        'count_reviews' => $count_reviews,
                        'total_products' => $seller->getProducts(false,false,false,false,true,true,false),
                        'total_product_sold' => isset($total_product_sold) ? $total_product_sold: false,
                        'link_base' => $this->module->getBaseLink(),
                        'products' => $this->getProducts($filter,$page,$paggination->limit,$order_by),
                        'current_page' => $page,
                        'link_ajax_sort_product_list'=> $this->module->getShopLink(array('id_seller'=>$seller->id)),
                        'paggination' => $paggination->render(),
                        'ajax' => Tools::isSubmit('ajax'),
                        'response_rate' => isset($response_rate) ? $response_rate :false,
                        'idCategories' => Tools::getValue('idCategories'),
                        'current_tab' => Tools::getValue('current_tab','all'),
                        'product_name' => Tools::getValue('product_name'),
                        'seller_follow' => $this->checkIsFollow($seller->id),
                        'link_all' => $this->module->getShopLink(array('id_seller'=>$seller->id,'current_tab'=>'all')),
                        'link_new_product' => $this->module->getShopLink(array('id_seller'=>$seller->id,'current_tab'=>'new_product')),
                        'link_best_seller' => $this->module->getShopLink(array('id_seller'=>$seller->id,'current_tab'=>'best_seller')),
                        'link_special' => $this->module->getShopLink(array('id_seller'=>$seller->id,'current_tab'=>'special')),
                        'customer_logged' => $this->context->customer->logged,
                        'reported' => $this->context->customer->logged ? Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'ets_mp_seller_report` WHERE id_customer="'.(int)$this->context->customer->id.'" AND id_seller="'.(int)$seller->id.'" AND id_product=0'):false,
                        'is_captcha' => isset($is_captcha) ? $is_captcha:false,
                        'ETS_MP_ENABLE_CAPTCHA_TYPE' => Configuration::get('ETS_MP_ENABLE_CAPTCHA_TYPE'),
                        'ETS_MP_ENABLE_CAPTCHA_SITE_KEY2' => Configuration::get('ETS_MP_ENABLE_CAPTCHA_SITE_KEY2'),
                        'ETS_MP_ENABLE_CAPTCHA_SECRET_KEY2' => Configuration::get('ETS_MP_ENABLE_CAPTCHA_SECRET_KEY2'),
                        'ETS_MP_ENABLE_CAPTCHA_SITE_KEY3' => Configuration::get('ETS_MP_ENABLE_CAPTCHA_SITE_KEY3'),
                        'ETS_MP_ENABLE_CAPTCHA_SECRET_KEY3' => Configuration::get('ETS_MP_ENABLE_CAPTCHA_SECRET_KEY3'),
                        'base_link' => $this->module->getBaseLink(),
                        'report_customer' => $this->context->customer,
                        'is_product_comment' => $this->module->is17 && Module::isInstalled('productcomments') ? true :false,
                        'product_comment_grade_url' => $this->context->link->getModuleLink('productcomments', 'CommentGrade'),
                    )
                );
                $productIds = $this->getProducts($filter,$page,$paggination->limit,$order_by,false,true);
                if(Tools::isSubmit('ajax'))
                {
                    die(Tools::jsonEncode(
                        array(
                            'product_list'=> $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/shop/product_list.tpl'),
                        )
                    ));
                }
                else
                {
                    $this->context->smarty->assign(
                        array(
                            'product_list'=> $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/shop/product_list.tpl'),
                            'list_categories' => $this->getBlockCategories($productIds),
                            
                        )
                    );  
                    return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/shop/shop.tpl');
                }  
            }
            else
            {
                return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/shop/no_shop.tpl');
            }
        }
        $page = (int)Tools::getValue('page') && (int)Tools::getValue('page') > 0 ? (int)Tools::getValue('page') : 1;
        $totalRecords = (int)Ets_mp_seller::_getSellers('AND s.active=1','',0,0,true);
        $paggination = new Ets_mp_paggination_class();            
        $paggination->total = $totalRecords;
        $paggination->url = $this->module->getShopLink(array('page'=>'_page_'));
        $paggination->limit =  8;
        $totalPages = ceil($totalRecords / $paggination->limit);
        if($page > $totalPages)
            $page = $totalPages;
        $paggination->page = $page;
        $start = $paggination->limit * ($page - 1);
        if($start < 0)
            $start = 0;
        $paggination->text =  $this->module->l('Showing {start} to {end} of {total} ({pages} Pages)','shop');
        $paggination->style_links = $this->module->l('links','shop');
        $paggination->style_results = $this->module->l('results','shop');
        if(Tools::getValue('order_by','sale.desc'))
        {
            switch (Tools::getValue('order_by','sale.desc')) {
                case 'sale.desc':
                    $order_by= 'seller_sale.total_sale DESC';
                    break;
                case 'name.asc':
                    $order_by= 'sl.shop_name asc';
                    break;
                case 'name.desc':
                    $order_by = ' sl.shop_name desc';
                    break;
                case 'quantity.desc':
                    $order_by =' seller_product.total_product desc';
                    break;
                case 'rate.desc':
                    $order_by =' seller_rate.total_grade desc';
                    break;
                case 'date_add.asc':
                    $order_by =' s.date_add asc';
                    break;
                case 'date_add.desc':
                    $order_by =' s.date_add desc';
                    break;
                default:
                    $order_by= 'sl.shop_name asc';
            } 
        }
        $sellers = Ets_mp_seller::_getSellers('AND s.active=1 AND seller_product.total_product >0',$order_by,$start,$paggination->limit);
        if($sellers)
        {
            foreach($sellers as &$seller)
            {
                if($seller['shop_logo'])
                    $seller['shop_logo'] = $seller['shop_logo'];
                $seller['link_view'] = $this->module->getShopLink(array('id_seller'=>$seller['id_seller']));
            }
        }
        if($sellers)
        {
            foreach($sellers as &$seller)
            {
                $seller['link'] = $this->module->getShopLink(array('id_seller'=>$seller['id_seller']));
                $seller['avg_rate_int'] = isset($seller['avg_rate']) ? (int)$seller['avg_rate'] : 0;
                $seller['avg_rate'] = isset($seller['avg_rate']) ? Tools::ps_round($seller['avg_rate'],2):0;
            }
        }
        $this->context->smarty->assign(
            array(
                'sellers' => $sellers,
                'link_base' => $this->module->getBaseLink(),
                'paggination' => $paggination->render(),
            )
        );
        if(Tools::isSubmit('ajax'))
            die(
                Tools::jsonEncode(
                    array(
                        'shop_list'=> $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/shop/shop_list.tpl'),
                    )
                )
            );
        else
        $this->context->smarty->assign(
            array(
                'shop_list'=> $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/shop/shop_list.tpl'),
            )
        );
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.$this->module->name.'/views/templates/hook/shop/shops.tpl');
    }
    private function getCategories($category,$productIds)
    {
        if(!$productIds)
            return false;
        $range = '';
        $maxdepth = Configuration::get('BLOCK_CATEG_MAX_DEPTH');
        if (Validate::isLoadedObject($category)) {
            if ($maxdepth > 0) {
                $maxdepth += $category->level_depth;
            }
            $range = 'AND nleft >= '.(int)$category->nleft.' AND nright <= '.(int)$category->nright;
        }
        $resultIds = array();
        $resultParents = array();
        $sql ='
			SELECT c.id_parent, c.id_category, cl.name, cl.description, cl.link_rewrite
			FROM `'._DB_PREFIX_.'category` c
			INNER JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.`id_category` = cl.`id_category` AND cl.`id_lang` = '.(int)$this->context->language->id.Shop::addSqlRestrictionOnLang('cl').')
			INNER JOIN `'._DB_PREFIX_.'category_shop` cs ON (cs.`id_category` = c.`id_category` AND cs.`id_shop` = '.(int)$this->context->shop->id.')
			WHERE (c.`active` = 1 OR c.`id_category` = '.(int)Configuration::get('PS_HOME_CATEGORY').')
			AND c.`id_category` != '.(int)Configuration::get('PS_ROOT_CATEGORY').' AND c.id_category!= "'.(int)$category->id.'"
			'.((int)$maxdepth != 0 ? ' AND `level_depth` <= '.(int)$maxdepth : '').'
			'.$range.'
			AND c.id_category IN (
				SELECT id_category
				FROM `'._DB_PREFIX_.'category_group`
				WHERE `id_group` IN ('.pSQL(implode(', ', Customer::getGroupsStatic((int)$this->context->customer->id))).')
			)
            AND c.id_category IN (
                SELECT parent.id_category
                FROM `'._DB_PREFIX_.'category` AS node, `'._DB_PREFIX_.'category` AS parent
                WHERE
                	node.nleft BETWEEN parent.nleft AND parent.nright
                	AND node.id_category IN (SELECT id_category
                FROM `'._DB_PREFIX_.'category_product` cp WHERE id_product IN ('.implode(',',array_map('intval',$productIds)).'))
                ORDER BY parent.nleft
            )
			ORDER BY `level_depth` ASC, '.(Configuration::get('BLOCK_CATEG_SORT') ? 'cl.`name`' : 'cs.`position`').' '.(Configuration::get('BLOCK_CATEG_SORT_WAY') ? 'DESC' : 'ASC');
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        if($result)
        {
           foreach ($result as &$row) {
                $resultParents[$row['id_parent']][] = &$row;
                $resultIds[$row['id_category']] = &$row;
            }
    
            return $this->getTree($resultParents, $resultIds, $maxdepth, ($category ? $category->id : null),$productIds); 
        }
        return false;
    }

    public function getTree($resultParents, $resultIds, $maxDepth, $id_category = null,$productIds, $currentDepth = 0)
    {
        if (is_null($id_category)) {
            $id_category = $this->context->shop->getCategory();
        }

        $children = array();

        if (isset($resultParents[$id_category]) && count($resultParents[$id_category]) && ($maxDepth == 0 || $currentDepth < $maxDepth)) {
            foreach ($resultParents[$id_category] as $subcat) {
                $children[] = $this->getTree($resultParents, $resultIds, $maxDepth, $subcat['id_category'],$productIds, $currentDepth + 1);
            }
        }

        if (isset($resultIds[$id_category])) {
            $link = $this->context->link->getCategoryLink($id_category, $resultIds[$id_category]['link_rewrite']);
            $name = $resultIds[$id_category]['name'];
            $desc = $resultIds[$id_category]['description'];
        } else {
            $link = $name = $desc = '';
        }
        $total_product = (int)Db::getInstance()->getValue('SELECT COUNT(id_product) FROM `'._DB_PREFIX_.'category_product` WHERE id_category="'.(int)$id_category.'" AND id_product in ('.implode(',',array_map('intval',$productIds)).')');
        return array(
            'id' => $id_category,
            'link' => $link,
            'name' => $name,
            'desc'=> $desc,
            'total_product' => $total_product,
            'children' => $children
        );
    }
    public function getBlockCategories($productIds)
    {
        $category = new Category((int)Configuration::get('PS_HOME_CATEGORY'), $this->context->language->id);
        $categories = $this->getCategories($category,$productIds);
        $this->context->smarty->assign(
            array(
                'categories' => $categories,
                'currentCategory' => $category->id,
                'current_tab' => Tools::getValue('current_tab','all'),
            )
        );
        if($categories)
            return $this->context->smarty->fetch(_PS_MODULE_DIR_. $this->module->name.'/views/templates/hook/shop/categories.tpl');
        return false;
    }
    public function getProducts($filter='',$page = 0, $per_page = 12, $order_by = 'p.id_product desc',$total=false,$listIds = false)
    {
        if($id_seller= (int)Tools::getValue('id_seller'))
        {
            $seller = new Ets_mp_seller($id_seller,$this->context->language->id);
            switch (Tools::getValue('current_tab','all')) {
                case 'all':
                    return $seller->getProducts($filter,$page,$per_page,$order_by,$total,true,$listIds);
                case 'new_product':
                    return $seller->getNewProducts($filter,$page,$per_page,$order_by,$total,$listIds);
                case 'best_seller':
                    return $seller->getBestSellerProducts($filter,$page,$per_page,$order_by,$total,$listIds);
                case 'special':
                    return $seller->getSpecialProducts($filter,$page,$per_page,$order_by,$total,$listIds);
                default:
                    return $seller->getProducts($filter,$page,$per_page,$order_by,$total,$listIds);
            } 
        }
        return array();
    }
    public function checkIsFollow($id_seller)
    {
        if($this->context->customer->logged)
        {
            if(Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'ets_mp_seller_customer_follow` WHERE id_seller="'.(int)$id_seller.'" AND id_customer="'.(int)$this->context->customer->id.'"'))
                return 1;
            else
                return 0;
        }
        return -1;
    }
}