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
class Ets_mp_seller extends ObjectModel
{
    protected static $instance;
    public $id_customer;
    public $id_shop;
    public $id_billing;
	public $shop_name;
    public $id_group;
    public $shop_description;
	public $shop_address;
    public $shop_phone;
    public $shop_logo;
    public $shop_banner;
    public $banner_url;
    public $link_facebook;
    public $link_instagram;
    public $link_google;
    public $link_twitter;
    public $message_to_administrator;
    public $reason;
    public $active;
    public $mail_expired;
    public $mail_going_to_be_expired;
    public $mail_payed;
    public $mail_wait_pay;
    public $payment_verify;
    public $user_shipping;
    public $user_brand;
    public $user_supplier;
    public $user_attribute;
    public $user_feature;
    public $commission_rate;
    public $auto_enabled_product;
    public $code_chat;
    public $date_from;
    public $date_to;
    public $date_add;
    public $date_upd;
    public $vat_number;
    public $latitude;
    public $longitude;
    public $vacation_mode;
    public $vacation_type;
    public $vacation_notifications;

    /* _ARM_ Adding licence */
    public $licence;
    public $shop_zip;
    public $shop_city;

    public static $definition = array(
		'table' => 'ets_mp_seller',
		'primary' => 'id_seller',
		'multilang' => true,
		'fields' => array(
			'id_customer' => array('type' => self::TYPE_INT),
            'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'id_billing' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'id_group' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'shop_phone'  => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'), 
            'vat_number' => array('type'=> self::TYPE_STRING),
            'shop_logo'  => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'), 
            'shop_banner'  => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml','lang'=>true), 
            'banner_url'  => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml','lang'=>true), 
            'link_facebook'  => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'), 
            'link_instagram'  => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'), 
            'link_google'  => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'), 
            'link_twitter'  => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
            'message_to_administrator' => array('type' =>   self::TYPE_STRING,'validate' => 'isCleanHtml'),
            'reason' => array('type' =>   self::TYPE_STRING,'validate' => 'isCleanHtml'),  
            'commission_rate' => array('type'=> self::TYPE_STRING), 
            'auto_enabled_product' => array('type'=>self::TYPE_STRING), 
            'code_chat' => array('type'=> self::TYPE_STRING), 
            'active' => array('type'=> self::TYPE_INT),
            'mail_expired' => array('type'=> self::TYPE_INT),
            'mail_wait_pay' => array('type'=> self::TYPE_INT),
            'mail_going_to_be_expired' => array('type'=> self::TYPE_INT),
            'mail_payed' => array('type'=> self::TYPE_INT),
            'payment_verify' => array('type' => self::TYPE_INT),
            'user_shipping' => array('type' => self::TYPE_INT),
            'user_brand' => array('type' => self::TYPE_INT),
            'user_supplier' => array('type'=>self::TYPE_INT),
            'user_attribute' => array('type' => self::TYPE_INT),
            'user_feature' => array('type' => self::TYPE_INT),
            'latitude' => array('type'=>self::TYPE_FLOAT),
            'longitude' => array('type'=>self::TYPE_FLOAT),
            'vacation_mode' => array('type'=>self::TYPE_INT),
            'vacation_type' => array('type'=>self::TYPE_STRING),
            'date_from' =>	array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
            'date_to' =>	array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),  
            'date_add' =>	array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
            'date_upd' =>	array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),  
            'shop_name' =>	array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml','lang'=>true),            
            'shop_description' =>	array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml','lang'=>true),
            'shop_address'  => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml','lang'=>true),   
            'vacation_notifications' => array('type'=>self::TYPE_STRING,'lang'=>true),

            'shop_zip' => ['type' => self::TYPE_STRING, 'validate' => 'isPostCode'],
            'shop_city' => ['type' => self::TYPE_STRING, 'validate' => 'isCityName']
        )
	);
    public	function __construct($id_item = null, $id_lang = null, $id_shop = null)
	{
		parent::__construct($id_item, $id_lang, $id_shop);
        $this->context= Context::getContext();
        $customer = new Customer($this->id_customer);
        $this->seller_email = $customer->email;
        $this->seller_name = $customer->firstname.' '.$customer->lastname;
        $this->id_language = $customer->id_lang;
        if(!$this->user_shipping)
        {
            $this->user_shipping = 3;
        }
        if(!$this->user_brand)
            $this->user_brand = 3;
        if(!$this->user_supplier)
            $this->user_supplier = 3;
        if(!$this->user_attribute)
            $this->user_attribute =3;
        if(!$this->user_feature)
            $this->user_feature = 3;
        if($this->id_billing)
        {
            $billing = new Ets_mp_billing($this->id_billing);
            if($billing->active == 0 && $billing->seller_confirm==0)
                $this->payment_verify=-1;
            else
                $this->payment_verify=0;
        }

        /* _ARM_ Adding licence */
        $this->licence = (! empty($customer->licence) ? $customer->licence : null);
	}
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new Ets_mp_seller();
        }
        return self::$instance;
    }
    static public function _getSellers($filter='',$sort='',$start=0,$limit=10,$total=false)
    {
        if(Module::isEnabled('ets_productcomments'))
        {
            $sql_avg = 'SELECT  seller_product2.id_customer, AVG(pc.grade) as avg_rate,SUM(pc.grade) as total_grade,COUNT(pc.id_ets_pc_product_comment) as count_grade FROM `'._DB_PREFIX_.'ets_pc_product_comment` pc
            INNER JOIN `'._DB_PREFIX_.'product` p ON (pc.id_product=p.id_product AND p.active=1)
            INNER JOIN `'._DB_PREFIX_.'ets_mp_seller_product` seller_product2 ON (seller_product2.id_product=pc.id_product)
            WHERE 1'.(Configuration::get('ETS_PC_MODERATE') ? ' AND pc.validate=1':'').' AND pc.grade!=0  GROUP BY seller_product2.id_customer';
        }
        elseif(Module::isEnabled('productcomments'))
        {
            $sql_avg = 'SELECT seller_product2.id_customer, AVG(pc.grade) as avg_rate,SUM(pc.grade) as total_grade,COUNT(pc.id_product_comment) as count_grade FROM `'._DB_PREFIX_.'product_comment` pc
            INNER JOIN `'._DB_PREFIX_.'product` p ON (pc.id_product=p.id_product AND p.active=1)
            INNER JOIN `'._DB_PREFIX_.'ets_mp_seller_product` seller_product2 ON (seller_product2.id_product=pc.id_product)
            WHERE 1 '.(Configuration::get('PRODUCT_COMMENTS_MODERATE') ? ' AND pc.validate=1':'').' AND pc.grade!=0 GROUP BY seller_product2.id_customer';
        }
        if($total)
        {
            $sql = 'SELECT COUNT(*) FROM `'._DB_PREFIX_.'ets_mp_seller` s
                LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_lang` sl ON (s.id_seller = sl.id_seller AND sl.id_lang="'.(int)Context::getContext()->language->id.'")
                LEFT JOIN `'._DB_PREFIX_.'customer` customer ON (s.id_customer=customer.id_customer)
                LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_billing` b ON (b.id_ets_mp_seller_billing = s.id_billing)
                LEFT JOIN (
                    SELECT sp.id_customer,count(sp.id_product) as total_product FROM `'._DB_PREFIX_.'ets_mp_seller_product` sp
                    INNER JOIN `'._DB_PREFIX_.'product` p ON (sp.id_product= p.id_product AND p.active=1) GROUP BY sp.id_customer
                ) seller_product ON (seller_product.id_customer=s.id_customer)
                LEFT JOIN (
                    SELECT r.id_seller,COUNT(r.id_customer) as total_reported FROM `'._DB_PREFIX_.'ets_mp_seller_report` r WHERE id_product=0 GROUP BY r.id_seller 
                ) seller_report ON (seller_report.id_seller=s.id_seller)
                LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_group_lang` seller_group_lang ON (seller_group_lang.id_ets_mp_seller_group = s.id_group AND seller_group_lang.id_lang="'.(int)Context::getContext()->language->id.'")
                '.(isset($sql_avg) ? ' LEFT JOIN ('.$sql_avg.') seller_rate ON (seller_rate.id_customer = s.id_customer)':'').'
            WHERE s.id_shop="'.(int)Context::getContext()->shop->id.'" '.$filter;
            return Db::getInstance()->getValue($sql);
        }
        else
        {
            /* _ARM_ Adding licence */
            $sql = 'SELECT s.*,b.active as payment_status,b.seller_confirm,b.reference,seller_product.total_product,seller_report.total_reported'.(isset($sql_avg) ? ',seller_rate.avg_rate,seller_rate.total_grade,seller_rate.count_grade':'').',CONCAT(customer.firstname," ", customer.lastname) as seller_name,customer.email as seller_email, sl.shop_name,sl.shop_address,sl.shop_description,seller_group_lang.name as group_name, customer.licence FROM `'._DB_PREFIX_.'ets_mp_seller` s
                LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_lang` sl ON (s.id_seller = sl.id_seller AND sl.id_lang ="'.(int)Context::getContext()->language->id.'")
                LEFT JOIN `'._DB_PREFIX_.'customer` customer ON (s.id_customer=customer.id_customer)
                LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_billing` b ON (b.id_ets_mp_seller_billing = s.id_billing)
                LEFT JOIN (
                    SELECT sp.id_customer,count(sp.id_product) as total_product FROM `'._DB_PREFIX_.'ets_mp_seller_product` sp
                    INNER JOIN `'._DB_PREFIX_.'product` p ON (sp.id_product= p.id_product AND p.active=1) GROUP BY sp.id_customer
                ) seller_product ON (seller_product.id_customer=s.id_customer)
                LEFT JOIN (
                    SELECT r.id_seller,COUNT(r.id_customer) as total_reported FROM `'._DB_PREFIX_.'ets_mp_seller_report` r WHERE id_product=0 GROUP BY r.id_seller 
                ) seller_report ON (seller_report.id_seller=s.id_seller)
                LEFT JOIN (
                    SELECT sp.id_customer,SUM(ps.quantity) as total_sale FROM '._DB_PREFIX_.'product_sale ps
                    INNER JOIN '._DB_PREFIX_.'ets_mp_seller_product sp ON (ps.id_product=sp.id_product)
                    GROUP BY sp.id_customer
                ) seller_sale ON (seller_sale.id_customer=s.id_customer)
                LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_group_lang` seller_group_lang ON (seller_group_lang.id_ets_mp_seller_group = s.id_group AND seller_group_lang.id_lang="'.(int)Context::getContext()->language->id.'")
                '.(isset($sql_avg) ? ' LEFT JOIN ('.$sql_avg.') seller_rate ON (seller_rate.id_customer = s.id_customer)':'').'
            WHERE s.id_shop="'.(int)Context::getContext()->shop->id.'" '.$filter. ''
            .($sort ? ' ORDER BY '.$sort: ' ORDER BY s.id_seller DESC')
            .' LIMIT '.(int)$start.','.(int)$limit.'';
            return Db::getInstance()->executeS($sql);
        }
    }
    public function getNewProducts($filter='',$page = 0, $per_page = 12, $order_sort = 'p.id_product desc',$total=false,$listIds=false)
    {
        if($order_sort)
        {
            $order_sort = explode(' ',trim($order_sort));
            $order_by = $order_sort[0];
            if(isset($order_sort[1]))
                $order_way = $order_sort[1];
            else
                $order_way = null;
        }
        else
        {
            $order_way = null;
            $order_by = null;
        }
        
        if($total)
            return $this->getListNewProducts($filter,(int) $this->context->language->id, $page, (int)$per_page,$total);
        elseif($listIds)
            return $this->getListNewProducts($filter,(int) $this->context->language->id, $page, (int)$per_page,$total,$order_by,$order_way,null,$listIds);
		$newProducts = $this->getListNewProducts($filter,(int) $this->context->language->id, $page, (int)$per_page,$total,$order_by,$order_way,null,$listIds);
        if(version_compare(_PS_VERSION_, '1.7', '>='))
            return Ets_marketplace::productsForTemplate($newProducts);
		return $newProducts;
    }
    public function getListNewProducts($filter='',$id_lang, $page_number = 0, $nb_products = 10, $count = false, $order_by = null, $order_way = null, Context $context = null,$listIds=false)
    {
        $now = date('Y-m-d') . ' 00:00:00';
        if (!$context) {
            $context = Context::getContext();
        }
        $front = true;
        if (!in_array($context->controller->controller_type, array('front', 'modulefront'))) {
            $front = false;
        }
        if ($page_number < 1) {
            $page_number = 1;
        }
        if ($nb_products < 1) {
            $nb_products = 10;
        }
        if (empty($order_by) || $order_by == 'position') {
            $order_by = 'date_add';
        }
        if (empty($order_way)) {
            $order_way = 'DESC';
        }
        if ($order_by == 'id_product' || $order_by == 'price' || $order_by == 'date_add' || $order_by == 'date_upd') {
            $order_by_prefix = 'product_shop';
        } elseif ($order_by == 'name') {
            $order_by_prefix = 'pl';
        }
        if (!Validate::isOrderBy($order_by) || !Validate::isOrderWay($order_way)) {
            die($order_by.' '.$order_way);
        }

        $sql_groups = '';
        if (Group::isFeatureActive()) {
            $groups = FrontController::getCurrentCustomerGroups();
            $sql_groups = ' AND EXISTS(SELECT 1 FROM `' . _DB_PREFIX_ . 'category_product` cp
                JOIN `' . _DB_PREFIX_ . 'category_group` cg ON (cp.id_category = cg.id_category AND cg.`id_group` ' . (count($groups) ? 'IN (' . implode(',', $groups) . ')' : '= ' . (int) Configuration::get('PS_UNIDENTIFIED_GROUP')) . ')
                WHERE cp.`id_product` = p.`id_product`)';
        }
        if (strpos($order_by, '.') > 0) {
            $order_by = explode('.', $order_by);
            $order_by_prefix = $order_by[0];
            $order_by = $order_by[1];
        }
        $nb_days_new_product = (int) Configuration::get('PS_NB_DAYS_NEW_PRODUCT') ?:20;
        if ($count) {
            $sql = 'SELECT COUNT(DISTINCT p.`id_product`) AS nb
                    FROM `' . _DB_PREFIX_ . 'product` p
                    ' . Shop::addSqlAssociation('product', 'p') . '
                    INNER JOIN `'._DB_PREFIX_.'ets_mp_seller_product` seller_product ON (seller_product.id_product=p.id_product AND seller_product.id_customer="'.(int)$this->id_customer.'")
                    LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.id_product=p.id_product)
                    WHERE product_shop.`active` = 1
                    AND product_shop.`date_add` > "' . date('Y-m-d', strtotime('-' . $nb_days_new_product . ' DAY')) . '"
                    ' . ($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '') . $filter.'
                    ' . $sql_groups;
            return (int) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($sql);
        }
        elseif($listIds)
        {
            $sql = 'SELECT p.`id_product`
                    FROM `' . _DB_PREFIX_ . 'product` p
                    ' . Shop::addSqlAssociation('product', 'p') . '
                    INNER JOIN `'._DB_PREFIX_.'ets_mp_seller_product` seller_product ON (seller_product.id_product=p.id_product AND seller_product.id_customer="'.(int)$this->id_customer.'")
                    LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.id_product=p.id_product)
                    WHERE product_shop.`active` = 1
                    AND product_shop.`date_add` > "' . date('Y-m-d', strtotime('-' . $nb_days_new_product . ' DAY')) . '"
                    ' . ($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '') . $filter.'
                    ' . $sql_groups;
            $products = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
            $ids= array();
            if($products)
            {
                foreach($products as $product)
                    $ids[] = $product['id_product'];
            }
            return $ids;
        }
        $sql = new DbQuery();
        $sql->select(
            'p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`description`, pl.`description_short`, pl.`link_rewrite`, pl.`meta_description`,
            pl.`meta_keywords`, pl.`meta_title`, pl.`name`, pl.`available_now`, pl.`available_later`, image_shop.`id_image` id_image, il.`legend`, m.`name` AS manufacturer_name,
            (DATEDIFF(product_shop.`date_add`,
                DATE_SUB(
                    "' . $now . '",
                    INTERVAL ' . $nb_days_new_product . ' DAY
                )
            ) > 0) as new'
        );

        $sql->from('product', 'p');
        $sql->join(Shop::addSqlAssociation('product', 'p'));
        $sql->innerjoin('ets_mp_seller_product','seller_product','seller_product.id_product=p.id_product');
        $sql->leftJoin('category_product','cp','cp.id_product=p.id_product');
        $sql->leftJoin('product_sale','sale','sale.id_product=p.id_product');
        $sql->leftJoin(
            'product_lang',
            'pl',
            '
            p.`id_product` = pl.`id_product`
            AND pl.`id_lang` = ' . (int) $id_lang . Shop::addSqlRestrictionOnLang('pl')
        );
        $sql->leftJoin('image_shop', 'image_shop', 'image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop=' . (int) $context->shop->id);
        $sql->leftJoin('image_lang', 'il', 'image_shop.`id_image` = il.`id_image` AND il.`id_lang` = ' . (int) $id_lang);
        $sql->leftJoin('manufacturer', 'm', 'm.`id_manufacturer` = p.`id_manufacturer`');

        $sql->where('product_shop.`active` = 1');
        $sql->where('seller_product.id_customer='.(int)$this->id_customer);
        if($id_category = Tools::getValue('id_ets_css_sub_category'))
            $sql->where('cp.`id_category` = '.(int)$id_category);
        if ($front) {
            $sql->where('product_shop.`visibility` IN ("both", "catalog")');
        }
        $sql->where('product_shop.`date_add` > "' . date('Y-m-d', strtotime('-' . $nb_days_new_product . ' DAY')) . '"');
        if (Group::isFeatureActive()) {
            $groups = FrontController::getCurrentCustomerGroups();
            $sql->where('EXISTS(SELECT 1 FROM `' . _DB_PREFIX_ . 'category_product` cp
                JOIN `' . _DB_PREFIX_ . 'category_group` cg ON (cp.id_category = cg.id_category AND cg.`id_group` ' . (count($groups) ? 'IN (' . implode(',', $groups) . ')' : '=' . (int) Configuration::get('PS_UNIDENTIFIED_GROUP')) . ')
                WHERE cp.`id_product` = p.`id_product`)');
        }
        if($filter)
            $sql->where(ltrim(trim($filter),'AND'));
        if($order_by=='rand')
        {
            $order_way='';
            $order_by = 'RAND()';
        }
        $sql->orderBy((isset($order_by_prefix) ? pSQL($order_by_prefix) . '.' : '') . pSQL($order_by) . ' ' . pSQL($order_way));
        $sql->limit($nb_products, (int) (($page_number - 1) * $nb_products));
        if (Combination::isFeatureActive()) {
            $sql->select('product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity, IFNULL(product_attribute_shop.id_product_attribute,0) id_product_attribute');
            $sql->leftJoin('product_attribute_shop', 'product_attribute_shop', 'p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop=' . (int) $context->shop->id);
        }
        $sql->join(Product::sqlStock('p', 0));
        $sql->groupBy('p.id_product');
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        if (!$result) {
            return false;
        }

        if ($order_by == 'price') {
            Tools::orderbyPrice($result, $order_way);
        }
        $products_ids = array();
        foreach ($result as $row) {
            $products_ids[] = $row['id_product'];
        }
        // Thus you can avoid one query per product, because there will be only one query for all the products of the cart
        Product::cacheFrontFeatures($products_ids, $id_lang);
        return Product::getProductsProperties((int) $id_lang, $result);
    }
    public function getBestSellerProducts($filter='',$page = 0, $per_page = 12, $order_by = 'p.id_product desc',$total=false,$listIds= false)
    {
        if($total)
            return $this->getBestSalesLight($filter,(int)$this->context->language->id, $page, (int)$per_page,$order_by,true);
		if (!($result = $this->getBestSalesLight($filter,(int)$this->context->language->id, $page, (int)$per_page,$order_by,false,$listIds)))
			return  array();
        if($listIds)
            return $result;
        if(version_compare(_PS_VERSION_, '1.7', '>='))
            return Ets_marketplace::productsForTemplate($result);                    
		return $result;
    }
    public function getBestSalesLight($filter='',$idLang, $pageNumber = 0, $nbProducts = 10,$order_by='ps.quantity DESC',$total=false,$listIds= false)
    {
        $context = Context::getContext();
        if ($pageNumber <= 0) {
            $pageNumber = 1;
        }
        if ($nbProducts < 1) {
            $nbProducts = 10;
        }
        if($total)
            $sql = 'SELECT COUNT(DISTINCT p.id_product)';
        else
        $sql = '
		SELECT
			p.id_product, IFNULL(product_attribute_shop.id_product_attribute,0) id_product_attribute, pl.`link_rewrite`, pl.`name`, pl.`description_short`, product_shop.`id_category_default`,
			image_shop.`id_image` id_image, il.`legend`,
			ps.`quantity` AS sales, p.`ean13`, p.`upc`, cl.`link_rewrite` AS category, p.show_price, p.available_for_order, IFNULL(stock.quantity, 0) as quantity, p.customizable,
			IFNULL(pa.minimal_quantity, p.minimal_quantity) as minimal_quantity, stock.out_of_stock,
			product_shop.`date_add` > "' . date('Y-m-d', strtotime('-' . (Configuration::get('PS_NB_DAYS_NEW_PRODUCT') ? (int) Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20) . ' DAY')) . '" as new,
			product_shop.`on_sale`, product_attribute_shop.minimal_quantity AS product_attribute_minimal_quantity';
   
		$sql .=' FROM `' . _DB_PREFIX_ . 'product_sale` ps
        
		LEFT JOIN `' . _DB_PREFIX_ . 'product` p ON ps.`id_product` = p.`id_product`
        LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.id_product=p.id_product)
		' . Shop::addSqlAssociation('product', 'p') . '
        '.(
                Tools::getValue('id_ets_css_sub_category')?' LEFT JOIN `'._DB_PREFIX_.'category_product` cp2 ON (cp2.id_product=p.id_product)':''
        ).'
        INNER JOIN `'._DB_PREFIX_.'ets_mp_seller_product` seller_product ON (seller_product.id_product=p.id_product AND seller_product.id_customer="'.(int)$this->id_customer.'")
		LEFT JOIN `'._DB_PREFIX_.'product_sale` sale ON (sale.id_product = p.id_product)
        LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_shop` product_attribute_shop
			ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop=' . (int) $context->shop->id . ')
		LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute` pa ON (product_attribute_shop.id_product_attribute=pa.id_product_attribute)
		LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl
			ON p.`id_product` = pl.`id_product`
			AND pl.`id_lang` = ' . (int) $idLang . Shop::addSqlRestrictionOnLang('pl') . '
		LEFT JOIN `' . _DB_PREFIX_ . 'image_shop` image_shop
			ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop=' . (int) $context->shop->id . ')
		LEFT JOIN `' . _DB_PREFIX_ . 'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = ' . (int) $idLang . ')
		LEFT JOIN `' . _DB_PREFIX_ . 'category_lang` cl
			ON cl.`id_category` = product_shop.`id_category_default`
			AND cl.`id_lang` = ' . (int) $idLang . Shop::addSqlRestrictionOnLang('cl') . Product::sqlStock('p', 0);

        $sql .= '
		WHERE product_shop.`active` = 1
        '.(Tools::getValue('id_ets_css_sub_category') ? ' AND cp2.id_category="'.(int)Tools::getValue('id_ets_css_sub_category').'"':'').'
		AND p.`visibility` != \'none\'';

        if (Group::isFeatureActive()) {
            $groups = FrontController::getCurrentCustomerGroups();
            $sql .= ' AND EXISTS(SELECT 1 FROM `' . _DB_PREFIX_ . 'category_product` cp
				JOIN `' . _DB_PREFIX_ . 'category_group` cg ON (cp.id_category = cg.id_category AND cg.`id_group` ' . (count($groups) ? 'IN (' . implode(',', $groups) . ')' : '=' . (int) Configuration::get('PS_UNIDENTIFIED_GROUP')) . ')
				WHERE cp.`id_product` = p.`id_product`)';
        }
        if($filter)
            $sql .= $filter;
        if($total)
        {
            
            return Db::getInstance()->getValue($sql);
        }
        elseif($listIds)
        {
            $products = Db::getInstance()->executeS($sql);
            $ids = array();
            foreach($products as $product)
            {
                $ids[] = $product['id_product'];
            }
            return $ids;
        }
        $sql .= ' GROUP BY p.id_product
		ORDER BY '.($order_by ? $order_by :'ps.quantity DESC').'
        
		LIMIT ' . (int) (($pageNumber-1) * $nbProducts) . ', ' . (int) $nbProducts;
        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
            return false;
        }
        if(version_compare(_PS_VERSION_, '1.7', '<'))
            $result = Product::getProductsProperties($idLang, $result);
        return $result;
    }
    public function getSpecialProducts($filter='',$page = 0, $per_page = 12, $order_sort = 'p.id_product desc',$total=false,$listIds= false)
    {
        if($total)
            return $this->getPricesDrop($filter,(int)Context::getContext()->language->id,$page,(int)$per_page,$total);
        if($order_sort)
        {
            $order_sort = explode(' ',trim($order_sort));
            $order_by = $order_sort[0];
            if(isset($order_sort[1]))
                $order_way = $order_sort[1];
            else
                $order_way = null;
        }
        else
        {
            $order_way = null;
            $order_by = null;
        }
        if($order_by=='rand')
        {
            $order_way = null;
            $order_by = null;
        }
        $products = $this->getPricesDrop($filter,
            (int)Context::getContext()->language->id,
            $page,
            (int)$per_page,$total,$order_by,$order_way,false,false,null,$listIds
        );
        if($listIds)
            return $products;
        if(version_compare(_PS_VERSION_, '1.7', '>='))
        {
            return Ets_marketplace::productsForTemplate($products);
        }
        else
            return $products;
    }
    public function getPricesDrop(
        $filter='',
        $id_lang,
        $page_number = 0,
        $nb_products = 10,
        $count = false,
        $order_by = null,
        $order_way = null,
        $beginning = false,
        $ending = false,
        Context $context = null,
        $listIds= false
    ) {
        if (!Validate::isBool($count)) {
            die(Tools::displayError());
        }

        if (!$context) {
            $context = Context::getContext();
        }
        if ($page_number < 1) {
            $page_number = 1;
        }
        if ($nb_products < 1) {
            $nb_products = 10;
        }
        if (empty($order_by) || $order_by == 'position') {
            $order_by = 'price';
        }
        if (empty($order_way)) {
            $order_way = 'DESC';
        }
        if ($order_by == 'id_product' || $order_by == 'price' || $order_by == 'date_add' || $order_by == 'date_upd') {
            $order_by_prefix = 'product_shop';
        } elseif ($order_by == 'name') {
            $order_by_prefix = 'pl';
        }
        if (!Validate::isOrderBy($order_by) || !Validate::isOrderWay($order_way)) {
            die(Tools::displayError());
        }
        $current_date = date('Y-m-d H:i:00');
        $ids_product =$this->_getProductIdByDate((!$beginning ? $current_date : $beginning), (!$ending ? $current_date : $ending), $context);

        $tab_id_product = array();
        foreach ($ids_product as $product) {
            if (is_array($product)) {
                $tab_id_product[] = (int) $product['id_product'];
            } else {
                $tab_id_product[] = (int) $product;
            }
        }

        $front = true;
        if (!in_array($context->controller->controller_type, array('front', 'modulefront'))) {
            $front = false;
        }

        $sql_groups = '';
        if (Group::isFeatureActive()) {
            $groups = FrontController::getCurrentCustomerGroups();
            $sql_groups = ' AND EXISTS(SELECT 1 FROM `' . _DB_PREFIX_ . 'category_product` cp
                JOIN `' . _DB_PREFIX_ . 'category_group` cg ON (cp.id_category = cg.id_category AND cg.`id_group` ' . (count($groups) ? 'IN (' . implode(',', $groups) . ')' : '=' . (int) Configuration::get('PS_UNIDENTIFIED_GROUP')) . ')
                WHERE cp.`id_product` = p.`id_product`)';
        }

        if ($count) {
            return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
            SELECT COUNT(DISTINCT p.`id_product`)
            FROM `' . _DB_PREFIX_ . 'product` p
            ' . Shop::addSqlAssociation('product', 'p') . '
            INNER JOIN `'._DB_PREFIX_.'ets_mp_seller_product` seller_product ON (seller_product.id_product=p.id_product AND seller_product.id_customer="'.(int)$this->id_customer.'")
            LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.id_product=p.id_product)
            WHERE product_shop.`active` = 1
            AND product_shop.`show_price` = 1
            ' . ($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '') . '
            ' . ((!$beginning && !$ending) ? 'AND p.`id_product` IN(' . ((is_array($tab_id_product) && count($tab_id_product)) ? implode(', ', $tab_id_product) : 0) . ')' : '') .$filter. '
            ' . $sql_groups);
        }
        elseif($listIds)
        {
            $products =  Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
            SELECT p.`id_product`
            FROM `' . _DB_PREFIX_ . 'product` p
            ' . Shop::addSqlAssociation('product', 'p') . '
            INNER JOIN `'._DB_PREFIX_.'ets_mp_seller_product` seller_product ON (seller_product.id_product=p.id_product AND seller_product.id_customer="'.(int)$this->id_customer.'")
            LEFT JOIN `'._DB_PREFIX_.'product_sale` sale ON (sale.id_product = p.id_product)
            LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.id_product=p.id_product)
            WHERE product_shop.`active` = 1
            AND product_shop.`show_price` = 1
            ' . ($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '') . '
            ' . ((!$beginning && !$ending) ? 'AND p.`id_product` IN(' . ((is_array($tab_id_product) && count($tab_id_product)) ? implode(', ', $tab_id_product) : 0) . ')' : '') .$filter. '
            ' . $sql_groups);
            $ids = array();
            if($products)
            {
                foreach($products as $product)
                {
                    $ids[] = $product['id_product'];
                }
            }
            return $ids;
        }
        if (strpos($order_by, '.') > 0) {
            $order_by = explode('.', $order_by);
            $order_by = pSQL($order_by[0]) . '.`' . pSQL($order_by[1]) . '`';
        }

        $sql = '
        SELECT
            p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) as quantity, pl.`description`, pl.`description_short`, pl.`available_now`, pl.`available_later`,
            IFNULL(product_attribute_shop.id_product_attribute, 0) id_product_attribute,
            pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`,
            pl.`name`, image_shop.`id_image` id_image, il.`legend`, m.`name` AS manufacturer_name,
            DATEDIFF(
                p.`date_add`,
                DATE_SUB(
                    "' . date('Y-m-d') . ' 00:00:00",
                    INTERVAL ' . (Validate::isUnsignedInt(Configuration::get('PS_NB_DAYS_NEW_PRODUCT')) ? Configuration::get('PS_NB_DAYS_NEW_PRODUCT') : 20) . ' DAY
                )
            ) > 0 AS new
        FROM `' . _DB_PREFIX_ . 'product` p
        ' . Shop::addSqlAssociation('product', 'p') . '
        INNER JOIN `'._DB_PREFIX_.'ets_mp_seller_product` seller_product ON (seller_product.id_product=p.id_product AND seller_product.id_customer="'.(int)$this->id_customer.'")
        LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.id_product=p.id_product)
        LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_shop` product_attribute_shop
            ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop=' . (int) $context->shop->id . ')
        ' . Product::sqlStock('p', 0, false, $context->shop) . '
        LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (
            p.`id_product` = pl.`id_product`
            AND pl.`id_lang` = ' . (int) $id_lang . Shop::addSqlRestrictionOnLang('pl') . '
        )
        LEFT JOIN `' . _DB_PREFIX_ . 'image_shop` image_shop
            ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop=' . (int) $context->shop->id . ')
        LEFT JOIN `' . _DB_PREFIX_ . 'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = ' . (int) $id_lang . ')
        LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m ON (m.`id_manufacturer` = p.`id_manufacturer`)
        WHERE product_shop.`active` = 1
        AND product_shop.`show_price` = 1
        '.(Tools::getValue('id_ets_css_sub_category') ? ' AND cp.id_category="'.(int)Tools::getValue('id_ets_css_sub_category').'"':'').'
        ' . ($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '') . '
        ' . ((!$beginning && !$ending) ? ' AND p.`id_product` IN (' . ((is_array($tab_id_product) && count($tab_id_product)) ? implode(', ', $tab_id_product) : 0) . ')' : '') .$filter. '
        ' . $sql_groups . '
        GROUP BY p.id_product
        ORDER BY ' . (isset($order_by_prefix) ? pSQL($order_by_prefix) . '.' : '') . pSQL($order_by) . ' ' . pSQL($order_way) . '
        LIMIT ' . (int) (($page_number - 1) * $nb_products) . ', ' . (int) $nb_products;
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql);
        if (!$result) {
            return false;
        }

        if ($order_by == 'price') {
            Tools::orderbyPrice($result, $order_way);
        }

        return Product::getProductsProperties($id_lang, $result);
    }
    public function _getProductIdByDate($beginning, $ending, Context $context = null, $with_combination = false)
    {
        if (!$context) {
            $context = Context::getContext();
        }

        $id_address = $context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')};
        $ids = Address::getCountryAndState($id_address);
        $id_country = $ids['id_country'] ? (int) $ids['id_country'] : (int) Configuration::get('PS_COUNTRY_DEFAULT');

        return SpecificPrice::getProductIdByDate(
            $context->shop->id,
            $context->currency->id,
            $id_country,
            $context->customer->id_default_group,
            $beginning,
            $ending,
            0,
            $with_combination
        );
    }
    public function getProducts($filter='',$page = 0, $per_page = 12, $order_by = 'p.id_product desc',$total=false,$active=false,$listIds=false,$full=true)
    {
        $page = (int)$page;
        if ($page <= 0)
            $page = 1;
        $per_page = (int)$per_page;
        if ($per_page <= 0)
            $per_page = 12;
        $front = true;
        $nb_days_new_product = Configuration::get('PS_NB_DAYS_NEW_PRODUCT');
        $id_lang = (int)Context::getContext()->language->id;
        if (!Validate::isUnsignedInt($nb_days_new_product)) {
            $nb_days_new_product = 20;
        }
        $prev_version = version_compare(_PS_VERSION_, '1.6.1.0', '<');
        if(!$total)
            if($listIds)
                $sql ='SELECT DISTINCT p.id_product ';
            else
                $sql ='SELECT DISTINCT p.*, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) AS stock_quantity' . ($prev_version? ' ,IFNULL(product_attribute_shop.id_product_attribute, 0)':' ,MAX(product_attribute_shop.id_product_attribute)') . ' id_product_attribute, pl.`description`, pl.`description_short`, pl.`available_now`,
    					pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, i.`id_image`,
    					il.`legend` as legend, m.`name` AS manufacturer_name,cl.name as default_category,
    					DATEDIFF(product_shop.`date_add`, DATE_SUB("' . date('Y-m-d') . ' 00:00:00",
    					INTERVAL ' . (int)$nb_days_new_product . ' DAY)) > 0 AS new, product_shop.price AS orderprice,sp.approved';
        else
            $sql ='SELECT COUNT(DISTINCT p.id_product) ';
        $sql .= ' FROM `'._DB_PREFIX_.'product` p
                '.Shop::addSqlAssociation('product', 'p').
                (!$prev_version?
                    'LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (pa.id_product = p.id_product)'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.default_on=1').'':
                    'LEFT JOIN `'._DB_PREFIX_.'product_attribute_shop` product_attribute_shop ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop='.(int)$this->context->shop->id.')'
                )
                .Product::sqlStock('p', 0, false, Context::getContext()->shop).'
                LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_product` sp ON (sp.id_product=p.id_product)
                LEFT JOIN `'._DB_PREFIX_.'product_sale` sale ON (sale.id_product = p.id_product)
                LEFT JOIN `'._DB_PREFIX_.'category` c ON (c.id_category=p.id_category_default)
                LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.id_product=p.id_product)
                LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.id_category = cl.id_category AND cl.id_lang="'.(int)$id_lang.'")
                LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = ' . (int)$id_lang . Shop::addSqlRestrictionOnLang('pl') . ')'.
                ' LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.id_product=p.id_product AND i.cover=1)
                LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')	
                LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
                WHERE sp.id_customer="'.(int)$this->id_customer.'" '.($active ? ' AND product_shop.active=1':'').' AND product_shop.`id_shop` = ' . (int)Context::getContext()->shop->id.($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : ''); 
        if($total)
        {
            $sql .= $filter ? $filter :'';
            return Db::getInstance()->getValue($sql);
        }
        elseif($listIds)
        {
            $products = Db::getInstance()->executeS($sql);
            $ids = array();
            if($products)
            {
                foreach($products as $product)
                    $ids[] = $product['id_product'];
            }
            return $ids;
        }
        else
        {
            $sql .= $filter ? $filter :'';
            $sql .= ' GROUP BY p.id_product'.($order_by ? ' ORDER BY ' . pSQL($order_by): '').' LIMIT ' . (int)($page-1)*$per_page . ',' . (int)$per_page;
        }
        $products = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql, true, true);
        if (!$products) {
            return array();
        }
        if (trim($order_by) == 'product_shop.price asc') {
            Tools::orderbyPrice($products, 'asc');
        } elseif (trim($order_by) == 'product_shop.price desc') {
            Tools::orderbyPrice($products, 'desc');
        }
        if($full)
        {
            $products = Product::getProductsProperties($id_lang, $products);        
            if(version_compare(_PS_VERSION_, '1.7', '>=')) {
                $products = Ets_marketplace::productsForTemplate($products);
            }
        }
        return $products;
    }
    public function getProductOther($product)
    {
        $front = true;
        $nb_days_new_product = Configuration::get('PS_NB_DAYS_NEW_PRODUCT');
        $id_lang = (int)Context::getContext()->language->id;
        if (!Validate::isUnsignedInt($nb_days_new_product)) {
            $nb_days_new_product = 20;
        }
        $prev_version = version_compare(_PS_VERSION_, '1.6.1.0', '<');
        $sql ='SELECT DISTINCT p.*, IF(p.id_category_default="'.(int)$product->id_category_default.'",1,0) as category, product_shop.*, stock.out_of_stock, IFNULL(stock.quantity, 0) AS stock_quantity' . ($prev_version? ' ,IFNULL(product_attribute_shop.id_product_attribute, 0)':' ,MAX(product_attribute_shop.id_product_attribute)') . ' id_product_attribute, pl.`description`, pl.`description_short`, pl.`available_now`,
				pl.`available_later`, pl.`link_rewrite`, pl.`meta_description`, pl.`meta_keywords`, pl.`meta_title`, pl.`name`, i.`id_image`,
				il.`legend` as legend, m.`name` AS manufacturer_name,cl.name as default_category,
				DATEDIFF(product_shop.`date_add`, DATE_SUB("' . date('Y-m-d') . ' 00:00:00",
				INTERVAL ' . (int)$nb_days_new_product . ' DAY)) > 0 AS new, product_shop.price AS orderprice,sp.approved';
        $sql .= ' FROM `'._DB_PREFIX_.'product` p
        '.Shop::addSqlAssociation('product', 'p').
        (!$prev_version?
            'LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (pa.id_product = p.id_product)'.Shop::addSqlAssociation('product_attribute', 'pa', false, 'product_attribute_shop.default_on=1').'':
            'LEFT JOIN `'._DB_PREFIX_.'product_attribute_shop` product_attribute_shop ON (p.`id_product` = product_attribute_shop.`id_product` AND product_attribute_shop.`default_on` = 1 AND product_attribute_shop.id_shop='.(int)$this->context->shop->id.')'
        )
        .Product::sqlStock('p', 0, false, Context::getContext()->shop).'
        LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller_product` sp ON (sp.id_product=p.id_product)
        LEFT JOIN `'._DB_PREFIX_.'category` c ON (c.id_category=p.id_category_default)
        LEFT JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.id_product=p.id_product)
        LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON (c.id_category = cl.id_category AND cl.id_lang="'.(int)$id_lang.'")
        LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (p.`id_product` = pl.`id_product` AND pl.`id_lang` = ' . (int)$id_lang . Shop::addSqlRestrictionOnLang('pl') . ')'.
        ' LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.id_product=p.id_product AND i.cover=1)
        LEFT JOIN `'._DB_PREFIX_.'image_lang` il ON (i.`id_image` = il.`id_image` AND il.`id_lang` = '.(int)$id_lang.')	
        LEFT JOIN `' . _DB_PREFIX_ . 'manufacturer` m ON m.`id_manufacturer` = p.`id_manufacturer`
        WHERE sp.id_customer="'.(int)$this->id_customer.'" AND product_shop.active=1 AND product_shop.`id_shop` = ' . (int)Context::getContext()->shop->id.($front ? ' AND product_shop.`visibility` IN ("both", "catalog")' : '');
        $sql .= ' AND p.id_product!="'.(int)$product->id.'"';
        $sql .= ' GROUP BY p.id_product ORDER BY category desc'; 
        $products = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql, true, true);
        $products = Product::getProductsProperties($id_lang, $products);        
        if(version_compare(_PS_VERSION_, '1.7', '>=')) {
            $products = Ets_marketplace::productsForTemplate($products);
        }
        return $products;
    }
    public function getDiscounts($filter='',$start=0,$limit=12,$order_by='',$total=false)
    {
        if($total)
            $sql = 'SELECT COUNT(DISTINCT cr.id_cart_rule)';
        else
            $sql ='SELECT cr.*,crl.name,if(cr.reduction_percent,cr.reduction_percent,cr.reduction_amount) as discount';
        $sql .=' FROM `'._DB_PREFIX_.'cart_rule` cr
        LEFT JOIN `'._DB_PREFIX_.'cart_rule_shop` crs ON (cr.id_cart_rule=crs.id_cart_rule)
        LEFT JOIN `'._DB_PREFIX_.'cart_rule_lang` crl ON (cr.id_cart_rule=crl.id_cart_rule AND crl.id_lang="'.(int)$this->context->language->id.'")
        LEFT JOIN `'._DB_PREFIX_.'ets_mp_cart_rule_seller` cart_rule_seller ON (cart_rule_seller.id_cart_rule=cr.id_cart_rule)
        WHERE  cart_rule_seller.id_customer="'.(int)$this->id_customer.'" AND (crs.id_cart_rule is null OR crs.id_shop="'.(int)$this->context->shop->id.'")  '.($filter ? $filter:'').($order_by ? ' ORDER BY '.($order_by):'');
        if(!$total)
            $sql .= ' LIMIT '.(int)$start.','.(int)$limit;
        if($total)  
            return Db::getInstance()->getValue($sql);
        else
            return Db::getInstance()->executeS($sql);
    }
    public function getOrders($filter='',$start=0,$limit=12,$order_by='',$total=false)
    {
        if($total)
            $sql = 'SELECT COUNT(DISTINCT o.id_order)';
        else
            $sql ='SELECT o.*,c.commission';
        $sql .=' FROM `'._DB_PREFIX_.'orders` o 
        INNER JOIN `'._DB_PREFIX_.'ets_mp_seller_order` so ON (o.id_order=so.id_order) 
        LEFT JOIN (SELECT id_order,id_customer,SUM(commission) as commission FROM `'._DB_PREFIX_.'ets_mp_seller_commission` WHERE id_customer="'.(int)$this->id_customer.'" GROUP BY id_order,id_customer) c ON (c.id_order = o.id_order)    
        WHERE so.id_customer="'.(int)$this->id_customer.'"'.($filter ? $filter:'')
        .($order_by ? ' ORDER By '.$order_by:'');
        if(!$total)
            $sql .= ' LIMIT '.(int)$start.','.(int)$limit;
        if($total)
            return Db::getInstance()->getValue($sql);
        else
        {
           return Db::getInstance()->executeS($sql);
        }
    }
    public function getCommissions($filter='',$having="",$start=0,$limit=12,$order_by='',$total=false)
    {
        if($total)
            $sql = 'SELECT COUNT(sc.id)';
        else
            $sql ='SELECT sc.*,CONCAT(customer.firstname," ",customer.lastname) as seller_name,seller_lang.shop_name,p.id_product as product_id';
        $sql .= ' FROM (
        SELECT id_seller_commission as id, "commission" as type,reference,product_name,price,price_tax_incl,quantity,commission,status,note,date_add,id_shop,id_customer,id_order,id_product,id_product_attribute,"" as id_withdraw,"" as id_voucher FROM `'._DB_PREFIX_.'ets_mp_seller_commission` c
        UNION ALL
        SELECT id_ets_mp_commission_usage as id,"usage" as type,reference,"" as product_name,"" as price,"" as price_tax_incl,"" as quantity,amount as commission,status,note,date_add,id_shop,id_customer,id_order,"" as id_product,"" as id_product_attribute,id_withdraw,id_voucher FROM `'._DB_PREFIX_.'ets_mp_commission_usage` u
        )as sc
        INNER JOIN `'._DB_PREFIX_.'ets_mp_seller` seller ON (sc.id_customer= seller.id_customer)
        INNER JOIN `'._DB_PREFIX_.'ets_mp_seller_lang` seller_lang ON (seller.id_seller= seller_lang.id_seller AND seller_lang.id_lang="'.(int)$this->context->language->id.'")
        LEFT JOIN `'._DB_PREFIX_.'orders` o ON (sc.id_order=o.id_order)
        LEFT JOIN `'._DB_PREFIX_.'customer` customer ON (customer.id_customer=o.id_customer)
        LEFT JOIN `'._DB_PREFIX_.'product` p ON (p.id_product=sc.id_product AND p.active=1)
        WHERE seller.id_seller="'.(int)$this->id.'" AND sc.id_shop="'.(int)$this->context->shop->id.'"'.($filter ? $filter:'');
        if(!$total)
        {
            $sql .=($order_by ? ' ORDER By '.$order_by :'');
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
    public function getManufacturers($filter='',$having="",$start=0,$limit=12,$order_by='',$total=false)
    {
        if(!Configuration::get('ETS_MP_SELLER_CREATE_BRAND') && !Configuration::get('ETS_MP_SELLER_USER_GLOBAL_BRAND'))
            return $total ? 0 : array();
        if(!Configuration::get('ETS_MP_SELLER_CREATE_BRAND'))
            $filter .= ' AND man_seller.id_customer IS NULL';
        elseif(!Configuration::get('ETS_MP_SELLER_USER_GLOBAL_BRAND'))
            $filter .= ' AND man_seller.id_customer='.(int)$this->id_customer;
        elseif($this->user_brand==1)
            $filter .= ' AND man_seller.id_customer IS NULL';
        elseif($this->user_brand ==2)
            $filter .= ' AND man_seller.id_customer='.(int)$this->id_customer;
        else
            $filter .= ' AND (man_seller.id_customer is null OR man_seller.id_customer="'.(int)$this->id_customer.'")';
        if($total)
            $sql ='SELECT COUNT(DISTINCT m.id_manufacturer)';
        else
            $sql ='SELECT m.*,m.id_manufacturer as id,COUNT(p.id_product) as products,count(a.id_address) addresss,seller.id_seller,man_seller.id_customer as id_seller_customer';
        $sql .=' FROM `'._DB_PREFIX_.'manufacturer` m
            INNER JOIN `'._DB_PREFIX_.'manufacturer_shop` ms ON (m.id_manufacturer=ms.id_manufacturer AND ms.id_shop="'.(int)$this->context->shop->id.'")
            LEFT JOIN `'._DB_PREFIX_.'manufacturer_lang` ml ON (m.id_manufacturer=ml.id_manufacturer AND ml.id_lang ="'.(int)$this->context->language->id.'")    
            LEFT JOIN `'._DB_PREFIX_.'ets_mp_manufacturer_seller` man_seller ON (man_seller.id_manufacturer= m.id_manufacturer)    
            LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller` seller ON (seller.id_customer=man_seller.id_customer)
            LEFT JOIN `'._DB_PREFIX_.'product` p ON (p.id_manufacturer = m. id_manufacturer)
            LEFT JOIN `'._DB_PREFIX_.'address` a ON (a.id_manufacturer=m.id_manufacturer)
            WHERE 1 '.($filter ? $filter:'');
        if($total)
            return Db::getInstance()->getValue($sql);
        {
            $sql .= ' GROUP BY m.id_manufacturer'.($order_by ? ' ORDER BY '.$order_by :'');
            if($having)
                $sql .=' HAVING 1'.$having;
            if($limit)
            $sql .=' LIMIT '.(int)$start.','.(int)$limit;
            return Db::getInstance()->executeS($sql);
        }
    }
    public function getSuppliers($filter='',$having="",$start=0,$limit=12,$order_by='',$total=false)
    {
        if(!Configuration::get('ETS_MP_SELLER_CREATE_SUPPLIER') && !Configuration::get('ETS_MP_SELLER_USER_GLOBAL_SUPPLIER'))
            return $total ? 0 : array();
        if(!Configuration::get('ETS_MP_SELLER_CREATE_SUPPLIER'))
            $filter .= ' AND sup_seller.id_customer IS NULL';
        elseif(!Configuration::get('ETS_MP_SELLER_USER_GLOBAL_SUPPLIER'))
            $filter .= ' AND sup_seller.id_customer ='.(int)$this->id_customer;
        elseif($this->user_supplier==1)
            $filter .= ' AND sup_seller.id_customer IS NULL';
        elseif($this->user_supplier ==2)
            $filter .= ' AND sup_seller.id_customer ='.(int)$this->id_customer;
        else
            $filter .= ' AND (sup_seller.id_customer is null OR sup_seller.id_customer="'.(int)$this->id_customer.'")';
        if($total)
            $sql ='SELECT COUNT(DISTINCT s.id_supplier)';
        else
            $sql ='SELECT s.*,s.id_supplier as id,COUNT(ps.id_product) as products,seller.id_seller,sup_seller.id_customer as id_seller_customer';
        $sql .=' FROM `'._DB_PREFIX_.'supplier` s
            INNER JOIN `'._DB_PREFIX_.'supplier_shop` ss ON (s.id_supplier=ss.id_supplier AND ss.id_shop="'.(int)$this->context->shop->id.'")
            LEFT JOIN `'._DB_PREFIX_.'supplier_lang` sl ON (s.id_supplier=sl.id_supplier AND sl.id_lang ="'.(int)$this->context->language->id.'")    
            LEFT JOIN `'._DB_PREFIX_.'ets_mp_supplier_seller` sup_seller ON (sup_seller.id_supplier= s.id_supplier)    
            LEFT JOIN `'._DB_PREFIX_.'ets_mp_seller` seller ON (seller.id_customer=sup_seller.id_customer)
            LEFT JOIN `'._DB_PREFIX_.'product_supplier` ps ON (ps.id_supplier = s.id_supplier)
            WHERE 1 '.($filter ? $filter:'');
        if($total)
            return Db::getInstance()->getValue($sql);
        {
            $sql .= ' GROUP BY s.id_supplier'.($order_by ? ' ORDER BY '.$order_by :'');
            if($having)
                $sql .=' HAVING 1'.$having;
            if($limit)
            $sql .=' LIMIT '.(int)$start.','.(int)$limit;
            return Db::getInstance()->executeS($sql);
        }
    }
    public function getProductComments($filter='',$having="",$start=0,$limit=12,$order_by='',$total=false)
    {
        $filter .=' AND sp.id_customer="'.(int)$this->id_customer.'"';
        return Ets_mp_seller::getListProductComments($filter,$having,$start,$limit,$order_by,$total);
    }
    public static function getListProductComments($filter='',$having="",$start=0,$limit=12,$order_by='',$total=false)
    {
        if(Module::isEnabled('productcomments') || Module::isEnabled('ets_productcomments'))
        {
            if(Module::isEnabled('ets_productcomments'))
            {
                if($total)
                    $sql ='SELECT COUNT(DISTINCT pc.id_ets_pc_product_comment)';
                else
                    $sql ='SELECT pc.id_ets_pc_product_comment as id_comment,pc.id_product,IF(pcl.title,pcl.title,pcol.title) as title,IF(pcl.content,pcl.content,pcol.content) as content,IF(c.id_customer,CONCAT(c.firstname," ",c.lastname),pc.customer_name) as customer,pc.grade,pc.validate,pc.date_add,pl.name,seller_lang.shop_name,seller.id_seller';

                $sql .=' FROM '._DB_PREFIX_.'ets_pc_product_comment pc';
            }
            else
            {
                if($total)
                    $sql ='SELECT COUNT(DISTINCT pc.id_product_comment)';
                else
                    $sql ='SELECT pc.id_product_comment as id_comment,pc.id_product,pc.title,pc.content,IF(c.id_customer,CONCAT(c.firstname," ",c.lastname),pc.customer_name) as customer,pc.grade,pc.validate,pc.date_add,pl.name,seller_lang.shop_name,seller.id_seller';
                $sql .=' FROM '._DB_PREFIX_.'product_comment pc';
            }
            $sql .=' INNER JOIN '._DB_PREFIX_.'product p ON (pc.id_product=p.id_product)
            INNER JOIN '._DB_PREFIX_.'ets_mp_seller_product sp ON (sp.id_product=p.id_product)
                INNER JOIN '._DB_PREFIX_.'ets_mp_seller seller ON (seller.id_customer=sp.id_customer)
                LEFT JOIN '._DB_PREFIX_.'ets_mp_seller_lang seller_lang ON(seller_lang.id_seller=seller.id_seller AND seller_lang.id_lang="'.(int)Context::getContext()->language->id.'")
                LEFT JOIN '._DB_PREFIX_.'product_lang pl ON (pl.id_product= p.id_product AND pl.id_lang ="'.(int)Context::getContext()->language->id.'")';
            if(Module::isEnabled('ets_productcomments'))
            {
                $sql .= ' LEFT JOIN '._DB_PREFIX_.'ets_pc_product_comment_lang pcl ON (pc.id_ets_pc_product_comment = pcl.id_ets_pc_product_comment AND pcl.id_lang="'.(int)Context::getContext()->language->id.'")' ;
                $sql .= ' LEFT JOIN '._DB_PREFIX_.'ets_pc_product_comment_lang pcol ON (pc.id_ets_pc_product_comment = pcol.id_ets_pc_product_comment)' ;
            }    
            $sql .= ' LEFT JOIN '._DB_PREFIX_.'customer c ON (c.id_customer=pc.id_customer)
                WHERE pc.deleted=0 '.($filter ? $filter:'');
            if($total)
                return Db::getInstance()->getValue($sql);
            {
                $sql .= ' GROUP BY id_comment '.($order_by ? ' ORDER BY '.$order_by :'');
                if($having)
                    $sql .=' HAVING 1'.$having;
                if($limit)
                $sql .=' LIMIT '.(int)$start.','.(int)$limit;
                return Db::getInstance()->executeS($sql);
            }
        }

        return $total ? 0 : array();
    }
    public function getStockAvailables($start=0,$limit=12,$order_by='',$total=false)
    {
        if($total)
            $sql = 'SELECT COUNT(DISTINCT stock.id_stock_available)';
        else
            $sql ='SELECT stock.id_stock_available,stock.quantity,p.id_product,pa.id_product_attribute,pl.name,p.reference,p.active,su.name as supplier_name';
        $sql .= ' FROM '._DB_PREFIX_.'stock_available stock
        INNER JOIN '._DB_PREFIX_.'product p ON(stock.id_product=p.id_product)
        INNER JOIN '._DB_PREFIX_.'ets_mp_seller_product sp ON (sp.id_product=p.id_product)
        LEFT JOIN '._DB_PREFIX_.'product_lang pl ON (p.id_product= pl.id_product AND pl.id_lang="'.(int)$this->context->language->id.'")
        LEFT JOIN '._DB_PREFIX_.'product_attribute pa ON (pa.id_product_attribute= stock.id_product_attribute)
        LEFT JOIN '._DB_PREFIX_.'product_attribute pa2 ON (pa2.id_product= stock.id_product)
        LEFT JOIN '._DB_PREFIX_.'supplier su ON (su.id_supplier=p.id_supplier)
        WHERE sp.id_customer="'.(int)$this->id_customer.'" AND (pa2.id_product_attribute is null OR stock.id_product_attribute!=0)'; 
        if($total)
            return Db::getInstance()->getValue($sql);
        else
        {
            $sql .= ' GROUP BY stock.id_stock_available'.($order_by ? ' ORDER BY '.$order_by :'');
            if($limit)
                $sql .=' LIMIT '.(int)$start.','.(int)$limit;
            return Db::getInstance()->executeS($sql);
        }   
    }
    public function getFeatures($filter='',$start=0,$limit=12,$order_by='',$total=false)
    {
        if(!Configuration::get('ETS_MP_SELLER_CREATE_FEATURE') && !Configuration::get('ETS_MP_SELLER_USER_GLOBAL_FEATURE'))
        {
            return $total ? 0 : array();
        } 
        if(!Configuration::get('ETS_MP_SELLER_CREATE_FEATURE'))
            $filter .= ' AND feature_seller.id_customer is null';
        elseif(!Configuration::get('ETS_MP_SELLER_USER_GLOBAL_FEATURE'))
            $filter .= ' AND feature_seller.id_customer ='.(int)$this->id_customer;
        elseif($this->user_feature==1)
             $filter .= ' AND feature_seller.id_customer is null';
        elseif($this->user_feature==2)
              $filter .= ' AND feature_seller.id_customer ='.(int)$this->id_customer;
        else
            $filter .=' AND (feature_seller.id_customer="'.(int)$this->id_customer.'" OR feature_seller.id_customer is null)';  
        if($total)
            $sql = 'SELECT COUNT(DISTINCT f.id_feature)';
        else
            $sql ='SELECT f.*,fl.name,COUNT(DISTINCT fv.id_feature_value) as total_featuresvalue,feature_seller.id_customer';
        $sql .= ' FROM `'._DB_PREFIX_.'feature` f
        INNER JOIN `'._DB_PREFIX_.'feature_shop` fs ON (f.id_feature = fs.id_feature)
        LEFT JOIN `'._DB_PREFIX_.'ets_mp_feature_seller` feature_seller ON (feature_seller.id_feature = f.id_feature)
        LEFT JOIN `'._DB_PREFIX_.'feature_lang` fl ON (f.id_feature = fl.id_feature AND fl.id_lang="'.(int)$this->context->language->id.'")
        LEFT JOIN `'._DB_PREFIX_.'feature_value` fv ON (fv.id_feature = f.id_feature)
        WHERE fs.id_shop="'.(int)$this->context->shop->id.'"'.($filter ? $filter:'');
        if(!$total)
        {
            $sql .=' GROUP BY f.id_feature'.($order_by ? ' ORDER By '.$order_by :'');
            if($limit)
                $sql .= ' LIMIT '.(int)$start.','.(int)$limit;
        }
        if($total)
            return Db::getInstance()->getValue($sql);
        else
            return Db::getInstance()->executeS($sql);
    }
    public function getFeatureValues($filter='',$start=0,$limit=12,$order_by='',$total=false)
    {
        if(!Configuration::get('ETS_MP_SELLER_CREATE_FEATURE') && !Configuration::get('ETS_MP_SELLER_USER_GLOBAL_FEATURE'))
        {
            return $total ? 0 : array();
        } 
        if(!Configuration::get('ETS_MP_SELLER_CREATE_FEATURE'))
            $filter .= ' AND feature_seller.id_customer is null';
        elseif(!Configuration::get('ETS_MP_SELLER_USER_GLOBAL_FEATURE'))
            $filter .= ' AND feature_seller.id_customer is not null';
        elseif($this->user_feature==1)
             $filter .= ' AND feature_seller.id_customer is null';
        elseif($this->user_feature==2)
            $filter .= ' AND feature_seller.id_customer is not null';
        else
            $filter .=' AND (feature_seller.id_customer="'.(int)$this->id_customer.'" OR feature_seller.id_customer is null)';  
        if($total)
            $sql = 'SELECT COUNT(DISTINCT fv.id_feature_value)';
        else
            $sql ='SELECT fv.*,fvl.value,feature_seller.id_customer';
        $sql .=' FROM `'._DB_PREFIX_.'feature_value` fv 
        INNER JOIN `'._DB_PREFIX_.'feature` f ON (fv.id_feature = f.id_feature)
        INNER JOIN `'._DB_PREFIX_.'feature_shop` fs ON (f.id_feature AND fs.id_shop)
        LEFT JOIN `'._DB_PREFIX_.'ets_mp_feature_seller` feature_seller ON (feature_seller.id_feature = f.id_feature AND feature_seller.id_customer="'.(int)$this->id_customer.'")
        LEFT JOIN `'._DB_PREFIX_.'feature_value_lang` fvl ON (fv.id_feature_value= fvl.id_feature_value)
        WHERE fv.custom=0 AND fs.id_shop="'.(int)$this->context->shop->id.'"'.($filter ? $filter:'');

        if(!$total)
        {
            $sql .=' GROUP BY fv.id_feature_value'.($order_by ? ' ORDER By '.$order_by :'');
            if($limit)
                $sql .= ' LIMIT '.(int)$start.','.(int)$limit;
        }
        if($total)
            return Db::getInstance()->getValue($sql);
        else
            return Db::getInstance()->executeS($sql);
    }
    public function getAttributeGroups($filter='',$start=0,$limit=12,$order_by='',$total=false)
    {
        if(!Configuration::get('ETS_MP_SELLER_CREATE_PRODUCT_ATTRIBUTE') || (!Configuration::get('ETS_MP_SELLER_CREATE_ATTRIBUTE') && !Configuration::get('ETS_MP_SELLER_USER_GLOBAL_ATTRIBUTE') ))
            return $total ? 0 : array();
        elseif(!Configuration::get('ETS_MP_SELLER_CREATE_ATTRIBUTE'))
            $filter .=' AND agse.id_customer is null';
        elseif(!Configuration::get('ETS_MP_SELLER_USER_GLOBAL_ATTRIBUTE'))
            $filter.=' AND agse.id_customer ='.$this->id_customer;
        elseif($this->user_attribute==1)
            $filter .=' AND agse.id_customer is null';
        elseif($this->user_attribute==2)
            $filter .=' AND agse.id_customer="'.(int)$this->id_customer.'"';
        else
            $filter .= ' AND (agse.id_customer is null OR agse.id_customer="'.(int)$this->id_customer.'")';
        if($total)
            $sql = 'SELECT COUNT(DISTINCT ag.id_attribute_group)';
        else
            $sql ='SELECT ag.*,agl.name,COUNT(a.id_attribute) as total_attribute,agse.id_customer';
        $sql .=' FROM `'._DB_PREFIX_.'attribute_group` ag 
        INNER JOIN `'._DB_PREFIX_.'attribute_group_shop` ags ON (ags.id_attribute_group= ag.id_attribute_group)
        LEFT JOIN `'._DB_PREFIX_.'ets_mp_attribute_group_seller` agse ON (agse.id_attribute_group=ag.id_attribute_group)
        LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl ON (agl.id_attribute_group = ag.id_attribute_group AND agl.id_lang="'.(int)$this->context->language->id.'")
        LEFT JOIN `'._DB_PREFIX_.'attribute` a ON (a.id_attribute_group = ag.id_attribute_group)
        WHERE ags.id_shop="'.(int)$this->context->shop->id.'"'.($filter ? $filter:'');
        if(!$total)
        {
            $sql .=' GROUP BY ag.id_attribute_group'.($order_by ? ' ORDER By '.$order_by :'');
            if($limit)
                $sql .= ' LIMIT '.(int)$start.','.(int)$limit;
        }
        if($total)
            return Db::getInstance()->getValue($sql);
        else
            return Db::getInstance()->executeS($sql);
    }
    public function getAttributes($filter='',$start=0,$limit=12,$order_by='',$total=false)
    {
        if($total)
        {
            $sql = 'SELECT COUNT(DISTINCT a.id_attribute)';
        }
        else
            $sql ='SELECT a.*,al.name';
        $sql .= ' FROM `'._DB_PREFIX_.'attribute` a
        INNER JOIN `'._DB_PREFIX_.'attribute_shop` attribute_shop ON (a.id_attribute = attribute_shop.id_attribute)
        LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al ON (a.id_attribute = al.id_attribute AND al.id_lang="'.(int)$this->context->language->id.'")
        LEFT JOIN `'._DB_PREFIX_.'attribute_group` ag ON (a.id_attribute_group = ag.id_attribute_group)
        WHERE attribute_shop.id_shop="'.(int)$this->context->shop->id.'"'.($filter ? $filter:'');
        if(!$total)
        {
            $sql .=' GROUP BY a.id_attribute'.($order_by ? ' ORDER By '.$order_by :'');
            $sql .= ' LIMIT '.(int)$start.','.(int)$limit;
        }
        if($total)
            return Db::getInstance()->getValue($sql);
        else
            return Db::getInstance()->executeS($sql);
    }
    public function getTotalCommission($status=false)
    {
        $sql = 'SELECT SUM(commission) FROM `'._DB_PREFIX_.'ets_mp_seller_commission` WHERE id_customer="'.(int)$this->id_customer.'"'.($status!==false ? ' AND status="'.(int)$status.'"':'');
        //die($sql);
        return (float)Db::getInstance()->getValue($sql);
    }
    public function getToTalUseCommission($status=false,$pay_for_order=false,$voucher=false,$withdraw=false)
    {
        $sql = 'SELECT SUM(amount) FROM `'._DB_PREFIX_.'ets_mp_commission_usage` WHERE id_customer="'.(int)$this->id_customer.'"'.($status!==false ? ' AND status="'.(int)$status.'"':'').($pay_for_order ? ' AND id_order!=0':'').($voucher ? ' AND id_voucher!=0':'').($withdraw ? ' AND id_withdraw!=0':'');
        return (float)Db::getInstance()->getValue($sql);
    }
    public function _renderSellers($filter='',$title='')
    {
        if(!$title)
            $title = $this->l('Shops');
        $module = Module::getInstanceByName('ets_marketplace');
        $context = Context::getContext();
        if(Tools::isSubmit('viewseller') && $id_seller = (int)Tools::getValue('id_seller'))
        {
            return $this->_renderInfoSeller($id_seller);
        }
        if(Tools::isSubmit('editets_seller') && $id_seller = (int)Tools::getValue('id_seller'))
        {
            return $this->_renderFormSeller();
        }
        if(Tools::getValue('del')=='yes' && $id_seller = Tools::getValue('id_seller'))
        {
            $seller = new Ets_mp_seller($id_seller);
            if($seller->delete())
            {
                $this->context->cookie->success_message = $this->l('Deleted successfully');
                Tools::redirectAdmin($this->context->link->getAdminLink(Tools::getValue('controller')).'&list=true');
            }
        }
        $fields_list = array(
            'id_seller' => array(
                'title' => $this->l('ID'),
                'width' => 40,
                'type' => 'text',
                'sort' => true,
                'filter' => true,
            ),
            'shop_name' => array(
                'title' => $this->l('Shop name'),
                'type' => 'text',
                'sort' => true,
                'filter' => true,
                'strip_tag' => false,
            ),
            'seller_name' => array(
                'title' => $this->l('Seller name'),
                'type' => 'text',
                'sort' => true,
                'filter' => true,
                'strip_tag' => false,
            ),
            'seller_email' => array(
                'title' => $this->l('Seller email'),
                'type' => 'text',
                'sort' => true,
                'filter' => true
            ),
            'reference' => array(
                'title' => $this->l('Invoice ref'),
                'type' => 'text',
                'sort' => true,
                'filter' => true
            ),
            'payment_status' => array(
                'title' => $this->l('Payment status'),
                'type'=> 'select',
                'sort' => true,
                'filter' => true,
                'strip_tag' => false,
                'filter_list' => array(
                    'id_option' => 'active',
                    'value' => 'title',
                    'list' => array(
                        array(
                            'active' =>-1,
                            'title' => $this->l('Canceled')
                        ),
                        array(
                            'active' =>0,
                            'title' => $this->l('Pending')
                        ),
                        array(
                            'active' => 1,
                            'title' => $this->l('Paid')
                        ),
                    )
                )
            ),
            'date_from' => array(
                'title' => $this->l('Available from'),
                'type' => 'date',
                'sort' => true,
                'filter' => true
            ),
            'date_to' => array(
                'title' => $this->l('Available to'),
                'type' => 'date',
                'sort' => true,
                'filter' => true
            ),
            'total_reported' => array(
                'title' => $this->l('Reported'),
                'type' => 'int',
                'sort' => true,
                'class'=>'text-center',
                'filter' => true
            ),
            /* _ARM_ Adding licence */
            'licence' => [
                'title' => $this->l('Licence de La Poste'),
                'type' => 'text',
                'sort' => true,
                'filter' => true
            ],

            'group_name'=> array(
                'title' => $this->l('Shop group'),
                'type'=>'text',
                'sort' => true,
                'filter'=> true, // seller_group_lang
            ),
            'active' => array(
                'title' => $this->l('Shop status'),
                'type' => 'select',
                'sort' => true,
                'filter' => true,
                'strip_tag' => false,
                'filter_list' => array(
                    'id_option' => 'active',
                    'value' => 'title',
                    'list' => array(
                        array(
                            'active' => -3,
                            'title' => $this->l('Declined payment')
                        ),
                        array(
                            'active' => -2,
                            'title' => $this->l('Expired')
                        ),
                        array(
                            'active' =>-1,
                            'title' => $this->l('Pending')
                        ),
                        array(
                            'active' => 1,
                            'title' => $this->l('Active')
                        ),
                        array(
                            'active' => 0,
                            'title' => $this->l('Disabled')
                        ),
                    )
                )
            ),
        );
        //Filter
        $show_resset = false;
        if(Tools::getValue('id_seller') && !Tools::isSubmit('del'))
        {
            $filter .= ' AND s.id_seller="'.(int)Tools::getValue('id_seller').'"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('seller_name')))
        {
            $filter .=' AND CONCAT(customer.firstname," ", customer.lastname) LIKE "%'.pSQL(trim(Tools::getValue('seller_name'))).'%"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('seller_email')))
        {
            $filter .=' AND customer.email LIKE "%'.pSQL(trim(Tools::getValue('seller_email'))).'%"';
            $show_resset = true;
        }
        /* _ARM_ Adding licence */
        if(trim(Tools::getValue('licence')))
        {
            $filter .=' AND customer.licence LIKE "%'.pSQL(trim(Tools::getValue('licence'))).'%"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('shop_name')))
        {
            $filter .= ' AND sl.shop_name LIKE "%'.pSQL(trim(Tools::getValue('shop_name'))).'%"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('shop_description')))
        {
            $filter .= ' AND sl.shop_description = "%'.trim(Tools::getValue('shop_description')).'%"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('active'))!=='')
        {
            $filter .= ' AND s.active="'.(int)Tools::getValue('active').'"';
            $show_resset=true;
        }
        if(trim(Tools::getValue('payment_status'))!=='')
        {
            $filter .= ' AND b.active="'.(int)Tools::getValue('payment_status').'"';
            $show_resset=true;      
        } 
        if(trim(Tools::getValue('reference'))!=='')
        {
            $filter .= ' AND b.reference like "%'.pSQL(Tools::getValue('reference')).'%"';
            $show_resset=true;      
        }
        if(trim(Tools::getValue('date_from_min')))
        {
            $filter .=' AND s.date_from >= "'.pSQL(Tools::getValue('date_from_min')).' 00:00:00"';
            $show_resset=true;
        } 
        if(trim(Tools::getValue('date_from_max')))
        {
            $filter .=' AND s.date_from <= "'.pSQL(Tools::getValue('date_from_max')).' 23:59:59"';
            $show_resset=true;
        }
        if(trim(Tools::getValue('date_to_min')))
        {
            $filter .= ' AND s.date_to >="'.pSQL(Tools::getValue('date_to_min')).' 00:00:00"';
            $show_resset=true;
        }          
        if(trim(Tools::getValue('date_to_max')))
        {
            $filter .=' AND s.date_to <="'.pSQL(Tools::getValue('date_to_max')).' 23:59:59"';
            $show_resset = true;
        }
        if(trim(Tools::getValue('total_reported_min')))
        {
            $filter .=' AND seller_report.total_reported >= '.(int)Tools::getValue('total_reported_min');
            $show_resset=true;
        } 
        if(trim(Tools::getValue('total_reported_max')))
        {
            $filter .=' AND seller_report.total_reported <= '.(int)Tools::getValue('total_reported_max');
            $show_resset=true;
        }
        if(trim(Tools::getValue('group_name')))
        {
            $filter .= ' AND seller_group_lang.name like "'.pSQL(Tools::getValue('group_name')).'"';
            $show_resset=true;
        }
        //Sort
        $sort = "";
        if(Tools::getValue('sort'))
        {
            switch (Tools::getValue('sort')) {
                case 'id_seller':
                    $sort .=' s.id_seller';
                    break;
                case 'customer_name':
                    $sort .=' customer_name';
                    break;
                case 'seller_name':
                    $sort .= ' seller_name';
                    break;
                case 'seller_email':
                    $sort .= ' seller_email';
                    break;
                /* _ARM_ Adding licence */
                case 'licence':
                    $sort .= ' licence';
                    break;
                case 'reference':
                    $sort .= 'b.reference';
                    break;
                case 'payment_status':
                    $sort .= 'b.active';
                    break;
                case 'shop_name':
                    $sort .= 'sl.shop_name';
                    break;
                case 'shop_description':
                    $sort .= 'sl.shop_description';
                    break;
                case 'active':
                    $sort .='s.active';
                    break;
                case 'date_from':
                    $sort .='s.date_from';
                    break;
                case 'date_to':
                    $sort .='s.date_to';
                    break;
                case 'total_reported':
                    $sort .='seller_report.total_reported';
                    break;
                case 'group_name':
                    $sort .='seller_group_lang.name';
                    break;
            }
            if($sort && ($sort_type=Tools::getValue('sort_type')) && in_array($sort_type,array('acs','desc')))
                $sort .= ' '.$sort_type;  
        }
        //Paggination
        $page = (int)Tools::getValue('page') && (int)Tools::getValue('page') > 0 ? (int)Tools::getValue('page') : 1;
        $totalRecords = (int) Ets_mp_seller::_getSellers($filter,$sort,0,0,true);
        $paggination = new Ets_mp_paggination_class();            
        $paggination->total = $totalRecords;
        $paggination->url = $context->link->getAdminLink('AdminMarketPlaceSellers').'&page=_page_'.$module->getFilterParams($fields_list,'ets_seller');
        $paggination->limit =  20;
        $totalPages = ceil($totalRecords / $paggination->limit);
        if($page > $totalPages)
            $page = $totalPages;
        $paggination->page = $page;
        $start = $paggination->limit * ($page - 1);
        if($start < 0)
            $start = 0;
        $sellers= Ets_mp_seller::_getSellers($filter,$sort,$start,$paggination->limit,false);
        if($sellers)
        {
            foreach($sellers as &$seller)
            {
                $seller['status_val'] = $seller['active'];
                $seller['child_view_url'] = $context->link->getAdminLink('AdminMarketPlaceSellers').'&viewseller=1&id_seller='.(int)$seller['id_seller'];
                if($seller['active']==-1)
                    $seller['active'] = '<'.'span'.' class="ets_mp_status pending">'.$this->l('Pending').'<'.'/'.'span'.'>'; 
                elseif($seller['active']==0)
                    $seller['active'] = '<'.'span'.' class="ets_mp_status disabled">'.$this->l('Disabled').'<'.'/'.'span'.'>';
                elseif($seller['active']==1)
                    $seller['active'] = '<'.'span'.' class="ets_mp_status actived">'.$this->l('Active').'<'.'/'.'span'.'>';
                elseif($seller['active']==-2)
                    $seller['active'] = '<'.'span'.' class="ets_mp_status expired">'.$this->l('Expired').'<'.'/'.'span'.'>';
                elseif($seller['active']==-3)
                    $seller['active'] = '<'.'span'.' class="ets_mp_status declined">'.$this->l('Declined payment').'<'.'/'.'span'.'>';
                if($seller['id_billing'])
                {
                    if($seller['payment_status']==-1)
                        $seller['payment_status'] = '<'.'span'.' class="ets_mp_status canceled">'.$this->l('Canceled').'<'.'/'.'span'.'>'; 
                    elseif($seller['payment_status']==0)
                        $seller['payment_status'] = '<'.'span'.' class="ets_mp_status pending">'.$this->l('Pending').($seller['seller_confirm'] ? ' ('.$this->l('Seller confirmed').')':'').'<'.'/'.'span'.'>';
                    elseif($seller['payment_status']==1)
                        $seller['payment_status'] = '<'.'span'.' class="ets_mp_status purchased">'.$this->l('Paid').'<'.'/'.'span'.'>';
                }
                else
                    $seller['payment_status'] ='--';
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
                $seller['shop_name'] = '<'.'a hr'.'ef="'.$module->getShopLink(array('id_seller'=>$seller['id_seller'])).'" tar'.'get="_bl'.'ank" >'.$seller['shop_name'].'<'.'/'.'a'.'>';
                
            }
        }
        $paggination->text =  $this->l('Showing {start} to {end} of {total} ({pages} Pages)');
        $paggination->style_links = $this->l('links');
        $paggination->style_results = $this->l('results');
        $listData = array(
            'name' => 'ets_seller',
            'actions' => array('view','edit','delete'),
            'icon' => 'icon-sellers',
            'currentIndex' => $context->link->getAdminLink('AdminMarketPlaceSellers'),
            'identifier' => 'id_seller',
            'show_toolbar' => true,
            'show_action' => true,
            'title' => $title,
            'fields_list' => $fields_list,
            'field_values' => $sellers,
            'paggination' => $paggination->render(),
            'filter_params' => $module->getFilterParams($fields_list,'ets_seller'),
            'show_reset' =>$show_resset,
            'totalRecords' => $totalRecords,
            'sort'=> Tools::getValue('sort','id_seller'),
            'sort_type' => Tools::getValue('sort_type','desc'),
        );            
        return  $module->renderList($listData);
    }
    public function _renderInfoSeller($id_seller)
    {
        if(!Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'ets_mp_seller` WHERE id_seller='.(int)$id_seller))
            Tools::redirect($this->context->link->getAdminLink('AdminMarketPlaceSellers'));
        $module = Module::getInstanceByName('ets_marketplace');
        $errors= array();
        if(Tools::isSubmit('saveStatusSeller') && $id_seller= Tools::getValue('id_seller'))
        {
            $seller = new Ets_mp_seller($id_seller);
            if(Tools::getValue('date_from') && !Validate::isDate(Tools::getValue('date_from')))
                $errors[] = $this->l('"From" date is not valid');
            if(Tools::getValue('date_to') && !Validate::isDate(Tools::getValue('date_to')))
                $errors[] = $this->l('"To" date is not valid');
            if(Tools::getValue('date_from') && Tools::getValue('date_to') && Validate::isDate(Tools::getValue('date_from') && Validate::isDate(Tools::getValue('date_to') && strtotime(Tools::getValue('date_from') >= strtotime(Tools::getValue('date_to'))))))
                $errors[] = $this->l('"From" date must be smaller than "To" date');
            if(!$errors)
            {
                $seller->date_from = Tools::getValue('date_from');
                $seller->date_to = Tools::getValue('date_to');
                $active_old = $seller->active;
                if(!Tools::getValue('active_seller'))
                {
                    if($seller->active==-1)
                        $seller->active=-3;
                    else
                        $seller->active=0;
                    //$seller->payment_verify=-1;
                }
                else
                {
                    if((!$seller->date_from || strtotime($seller->date_from) <= strtotime(date('Y-m-d'))) && (!$seller->date_to || strtotime($seller->date_to) >= strtotime(date('Y-m-d'))))
                        $seller->active =1;
                    else
                    {
                        $seller->active =-2;
                        if($seller->getFeeType()!='no_fee')
                            $seller->payment_verify=-1;
                        else
                            $seller->payment_verify=0;
                    }
                }
                $seller->reason = Tools::getValue('reason');
                if($seller->update(true))
                {
                    if($seller->active!=$active_old && $seller->active==-2)
                    {
                        $fee_type= $seller->getFeeType();
                        if($fee_type!='no_fee')
                        {
                            $billing = new Ets_mp_billing();
                            $billing->id_seller = $seller->id;
                            $billing->amount = (float)$seller->getFeeAmount();
                            $billing->amount_tax = $module->getFeeIncludeTax($billing->amount,$seller);
                            $billing->active = 0;
                            $billing->date_from = $seller->date_to;
                            if($fee_type=='monthly_fee')
                                $billing->date_to = date("Y-m-d H:i:s", strtotime($seller->date_to."+1 month"));
                            elseif($fee_type=='quarterly_fee')
                                $billing->date_to = date("Y-m-d H:i:s", strtotime($seller->date_to."+3 month"));
                            elseif($fee_type=='yearly_fee')
                                $billing->date_to = date("Y-m-d H:i:s", strtotime($seller->date_to."+1 year"));
                            else
                                $billing->date_to ='';
                            $billing->fee_type = $fee_type;
                            if($billing->add(true,true))
                            {
                                if($fee_type!='no_fee')
                                {
                                    $seller->id_billing= $billing->id;
                                    $seller->update();
                                }
                                
                            }
                            
                        }
                    }
                    if(Tools::isSubmit('ajax'))
                    {
                        die(
                            Tools::jsonEncode(
                                array(
                                    'success' => $this->l('Updated seller successfully'),
                                    'seller_active'=>$seller->active,
                                )
                            )
                        );
                    }
                    else
                    {
                        $this->context->cookie->success_message = $this->l('Updated seller successfully');
                    }
                }
                else
                    $errors[] = $this->l('Update failed');
                
            }
            if($errors)
            {
                if(Tools::isSubmit('ajax'))
                {
                    die(
                        Tools::jsonEncode(
                            array(
                                'errors' => $module->displayError($errors),
                            )
                        )
                    );
                }
                
            }
        }
        $seller = new Ets_mp_seller($id_seller,$this->context->language->id);
        $customer_seller = new Customer($seller->id_customer);
        
        if(Tools::isSubmit('add_commission_by_admin') || Tools::isSubmit('deduct_commission_by_admin'))
        {
            $amount = Tools::getValue('amount', false);
            $action = Tools::getValue('action', false);
            $reason = Tools::getValue('reason', false);
            if (!$amount) {
                $errors[] = $this->l('Amount is required');
            } elseif (!Validate::isPrice($amount)) {
                if($action=='deduct')
                    $errors[]= $this->l('The commission is not enough to deduct');
                else
                    $errors[] = $this->l('Amount must be a decimal');
            }
            if(!$errors)
            {
                if($action=='deduct')
                {
                    $totalCommistionCanUse = $seller->getTotalCommission(1)-$seller->getToTalUseCommission(1);
                    if($totalCommistionCanUse < $amount)
                        $errors[] = $this->l('Remaining commission is not enough to deduct.');
                    else
                    {
                        $commission_usage = new Ets_mp_commission_usage();
                        $commission_usage->amount= $amount;
                        $commission_usage->status=1;
                        $commission_usage->id_customer= $seller->id_customer;
                        $commission_usage->date_add = date('Y-m-d H:i:s');
                        $commission_usage->deleted =0;
                        $commission_usage->note = $reason;
                        $commission_usage->id_currency = $this->context->currency->id;
                        $commission_usage->id_shop = $this->context->shop->id;
                        if($commission_usage->add(true,true))
                            $this->context->cookie->success_message = $this->l('Deducted successfully');
                    }
                }
                else
                {
                    $commisstion = new Ets_mp_commission();
                    $commisstion->commission= $amount;
                    $commisstion->id_shop=$this->context->shop->id;
                    $commisstion->id_customer = $seller->id_customer;
                    $commisstion->status=1;
                    $commisstion->note = $reason;
                    $commisstion->add();
                    $this->context->cookie->success_message = $this->l('Added successfully');
                }
            }
        }
        if(version_compare(_PS_VERSION_, '1.7.6', '>='))
        {
            $sfContainer = call_user_func(array('\PrestaShop\PrestaShop\Adapter\SymfonyContainer','getInstance'));
        	if (null !== $sfContainer) {
        		$sfRouter = $sfContainer->get('router');
        		$link_customer= $sfRouter->generate(
        			'admin_customers_view',
        			array('customerId' => $customer_seller->id)
        		);
        	}
            else
               $link_customer  =$this->context->link->getAdminLink('AdminCustomers').'&id_customer='.(int)$customer_seller->id.'&viewcustomer';
        }
        else
            $link_customer  =$this->context->link->getAdminLink('AdminCustomers').'&id_customer='.(int)$customer_seller->id.'&viewcustomer';
        if($seller->latitude!=0 && $seller->longitude!=0)
        {
            $default_country = new Country((int)Tools::getCountry());
            if(($map_key = Configuration::get('ETS_MP_GOOGLE_MAP_API')))
                $key ='key='.$map_key.'&';
            else
                $key='';
            $link_map_google = 'http'.((Configuration::get('PS_SSL_ENABLED') && Configuration::get('PS_SSL_ENABLED_EVERYWHERE')) ? 's' : '').'://maps.googleapis.com/maps/api/js?'.$key.'region='.Tools::substr($default_country->iso_code, 0, 2);
            $this->context->smarty->assign(
                array(
                    'link_map_google' =>$link_map_google,
                )
            );
        }
        $this->context->smarty->assign(
            array(
                'seller' => $seller,
                'seller_billing' => $seller->id_billing ? new Ets_mp_billing($seller->id_billing):false,
                'customer'=> $customer_seller,
                'link_customer' => $link_customer,
                'link'=> $this->context->link,
                'currency' => $this->context->currency,
                'amount' => $errors ?  Tools::getValue('amount', false): '',
                'action' => $errors ?  Tools::getValue('action', false) :'',
                'reason' => $errors ? Tools::getValue('reason', false): '',
                'history_billings' => Ets_mp_billing::getInstance()->_renderBilling($seller->id_customer),
                'history_commissions' => Ets_mp_commission::getInstance()->_renderCommission($seller->id_customer),
                'base_link' => $module->getBaseLink(),
            )
        );
        $html = '';
        if($errors)
            $html .= $module->displayError($errors);
        return $html.$this->context->smarty->fetch(_PS_MODULE_DIR_.'ets_marketplace/views/templates/hook/seller_info.tpl');
    }
    public function _renderFormSeller()
    {
        $html = '';
        $module = Module::getInstanceByName('ets_marketplace');
        if(Tools::isSubmit('saveSeller') && $id_seller = Tools::getValue('id_seller'))
        {
            $errors = array();
            $valueFieldPost= array();
            $this->submitSaveSeller($id_seller,$errors,true,$valueFieldPost);
            if($errors)
                $html .= $module->displayError($errors);                                                  
        }
        if($id_seller = Tools::getValue('id_seller'))
            $seller = new Ets_mp_seller($id_seller);
        else
            $seller = new Ets_mp_seller();
        if($seller->active==-1)
        {
           $status =array(
    			 'query' => array(
                    array(
                        'id_option'=> -1,
                        'name'=>$this->l('Pending'),
                    ),
                    array(
                        'id_option'=>1,
                        'name'=>$this->l('Active'),
                    ),
                    array(
                        'id_option'=>0,
                        'name'=>$this->l('Disabled'),
                    ),
                    array(
                        'id_option'=> -3,
                        'name'=> $this->l('Declined'),
                    ),
                 ),                             
                 'id' => 'id_option',
    			 'name' => 'name'  
            ); 
        }
        else
            $status =array(
    			 'query' => array(
                    array(
                        'id_option'=>'1',
                        'name'=>$this->l('Activate'),
                    ),
                    array(
                        'id_option'=>0,
                        'name'=>$this->l('Disabled'),
                    ),
                 ),                             
                 'id' => 'id_option',
    			 'name' => 'name'  
            );
        $fields_form = array(
			'form' => array(
				'legend' => array(
					'title' => $this->l('Edit shop'),
                    'icon' =>'icon-sellers',				
				),
				'input' => array(					
					array(
						'type' => 'text',
						'label' => $this->l('Seller name'),
						'name' => 'seller_name', 
                        'required' => true,
                        'disabled' => true,   					                     
					), 
                    array(
						'type' => 'text',
						'label' => $this->l('Seller email'),
						'name' => 'seller_email',   
                        'disabled' => true,
                        'required' => true,					                    
					), 
                    array(
                        'type' =>'text',
                        'name' =>'shop_phone',
                        'label' => $this->l('Shop phone'),
                        'required' => false,
                    ),
                    array(
                        'type' =>'text',
                        'name' =>'shop_name',
                        'label' => $this->l('Shop name'),
                        'lang' => true,
                        'required' => false,
                    ),
                    array(
						'type' => 'textarea',
						'label' => $this->l('Shop description'),
						'name' => 'shop_description',                            
                        'lang' => true	,
                        'required' => false,					
					),  
                    array(
						'type' => 'text',
						'label' => $this->l('Shop address'),
						'name' => 'shop_address',                            
                        'lang' => true	,
                        'required' => true,					
                    ),
                    /** _ARM_ SBA Concept */
                    [
                        'type' => 'text',
						'label' => $this->l('Shop zip'),
						'name' => 'shop_zip',
                        'required' => true,
                    ],
                    [
                        'type' => 'text',
						'label' => $this->l('Shop city'),
						'name' => 'shop_city',
                        'required' => true,
                    ],
                    array(
						'type' => 'text',
						'label' => $this->l('Latitude'),
						'name' => 'latitude',                            			
					), 
                    array(
						'type' => 'text',
						'label' => $this->l('Longitude'),
						'name' => 'longitude',                            			
					),
                    array(
						'type' => 'text',
						'label' => $this->l('VAT number'),
						'name' => 'vat_number',                            			
					),     
                    array(
						'type' => 'textarea',
						'label' => $this->l('Live Chat embed code'),
						'name' => 'code_chat',  
                        'desc' => $this->l('Enter here embed code of live chat service such as intercom.com, zendesk.com, etc. '),
					),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Commission rate'),
                        'name' => 'commission_rate',
                        'suffix' =>'%',
                        'col'=>3,
                        'desc' => $this->l('If you leave this field blank, the default value').' '.Tools::ps_round($seller->getCommissionRate(),2).$this->l('% will be applied')
                    ),
                    array(
                        'type'=> 'select',
                        'label' => $this->l('Auto approve products submitted by this seller'),
                        'name' => 'auto_enabled_product',
                        'options' => array(
                            'query' => array(
                                array(
                                    'id_option'=>'default',
                                    'name'=>$this->l('Default (base on general config)'),
                                ),
                                array(
                                    'id_option'=>'yes',
                                    'name'=>$this->l('Auto approve'),
                                ),
                                array(
                                    'id_option'=>'no',
                                    'name'=>$this->l('Manually approve by admin'),
                                ),
                             ),                             
                             'id' => 'id_option',
                			 'name' => 'name'
                        ),
                    ),
                    array(
                        'type'=> 'select',
                        'label' => $this->l('Shop group'),
                        'name' => 'id_group',
                        'options' => array(
                            'query' => array_merge(array(array('id_group'=>0,'name'=>'--')),Ets_mp_seller_group::_getSellerGroups()),                             
                             'id' => 'id_group',
                			 'name' => 'name'
                        ),
                    ),
                    array(
                        'type' => 'file',
                        'label' => $this->l('Logo'),
                        'name' => 'shop_logo',
                        // 'required' => true,
                        'image' =>$seller->shop_logo ? '<i'.'mg src="'.$module->getBaseLink().'/img/mp_seller/'.$seller->shop_logo.'" style="width: 160px;"':false,
                        //'delete_url'=>$this->context->link->getAdminLink('AdminMarketPlaceSellers').'&editets_seller=1&id_seller='.$seller->id.'&deletelogo=1',
                        'desc' => sprintf($this->l('Recommended size: 250x250 px. Accepted formats: jpg, png, gif. Limit %sMb'),Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE')),
                    ), 
                    array(
						'type' => 'file_lang',
						'label' => $this->l('Shop banner'),
						'name' => 'shop_banner',
                        'imageType' => 'banner',
                        'desc' => sprintf($this->l('Recommended size: 1170x170 px. Accepted formats: jpg, png, gif. Limit %sMb'),Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE')),
                    ),
                    array(
                        'type' =>'text',
                        'label' => $this->l('Banner URL'),
                        'name' => 'banner_url',
                        'lang'=>true,
                    ),  
                    array(
                        'type' =>'text',
                        'label' => $this->l('Facebook link'),
                        'name' => 'link_facebook',
                    ),  
                    array(
                        'type' =>'text',
                        'label' => $this->l('Google link'),
                        'name' => 'link_google',
                    ),
                    array(
                        'type' =>'text',
                        'label' => $this->l('Instagram link'),
                        'name' => 'link_instagram',
                    ),
                    array(
                        'type' =>'text',
                        'label' => $this->l('Twitter link'),
                        'name' => 'link_twitter',
                    ), 
                    /* _ARM_ Adding licence */
                    array(
                        'type' =>'text',
                        'label' => $this->l('Licence de La Poste'),
                        'name' => 'licence',
                    ),

                    array(
						'type' => 'select',
						'label' => $this->l('Status'),
						'name' => 'active',
                        //'form_group_class' => 'ets_mp_status_seller',
						'options' => $status,					
					),
                    array(
                        'type' => 'date',
                        'label' => $this->l('Available from'),
                        'name' => 'date_from',
                        'form_group_class' => 'seller_date',
                    ),
                    array(
                        'type' => 'date',
                        'label' => $this->l('Available to'),
                        'name' => 'date_to',
                        'form_group_class' => 'seller_date',
                    ),
                    array(
                        'type' =>'textarea',
                        'label' => $this->l('Reason'),
                        'name' => 'reason',
                        'form_group_class'=>'seller_reason',
                    )
                ),
                'submit' => array(
					'title' => $this->l('Save'),
				),
                'buttons' => array(
                    array(
                        'href' => $this->context->link->getAdminLink('AdminMarketPlaceSellers', true),
                        'icon'=>'process-icon-cancel',
                        'title' => $this->l('Cancel'),
                    )
                ),
            ),
		);
        $helper = new HelperForm();
		$helper->show_toolbar = false;
		$helper->table = $this->table;
		$lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->default_form_language = $lang->id;
		$helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
		$this->fields_form = array();
		$helper->module = $module;
		$helper->identifier = $this->identifier;
		$helper->submit_action = 'saveSeller';
		$helper->currentIndex = $this->context->link->getAdminLink('AdminMarketPlaceSellers', false).'&editets_seller=1&id_seller='.(int)Tools::getValue('id_seller');
		$helper->token = Tools::getAdminTokenLite('AdminMarketPlaceSellers');
		$language = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
		$helper->tpl_vars = array(
			'base_url' => $this->context->shop->getBaseURL(),
			'language' => array(
				'id_lang' => $language->id,
				'iso_code' => $language->iso_code
			),
            
            'PS_ALLOW_ACCENTED_CHARS_URL', (int)Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL'),
			'fields_value' => $this->getSellerFieldsValues(),
			'languages' => $this->context->controller->getLanguages(),
			'id_language' => $this->context->language->id,
			'image_baseurl' => _PS_IMG_.'mp_seller/',
            'link' => $this->context->link,
            'cancel_url' => $this->context->link->getAdminLink('AdminMarketPlaceSellers', true),
            'banner_del_link' => $this->context->link->getAdminLink('AdminMarketPlaceSellers').'&editets_seller=1&id_seller='.Tools::getValue('id_seller').'&deletebanner=1',
		);            
        $fields_form['form']['input'][] = array('type' => 'hidden', 'name' => 'id_seller');
		//$helper->override_folder = '/../'; 
        return $html.$helper->generateForm(array($fields_form));	
    }
    public function getSellerFieldsValues()
    {
        $fields = array();
        $id_seller = Tools::getValue('id_seller');
        $languages = Language::getLanguages(false);
        $seller = new Ets_mp_seller($id_seller);
        $fields['seller_name'] =Tools::getValue('seller_name',$seller->seller_name);
        $fields['seller_email'] = Tools::getValue('seller_email',$seller->seller_email);
        $fields['vat_number'] = Tools::getValue('seller_email',$seller->vat_number);
        $fields['shop_phone'] = Tools::getValue('shop_phone',$seller->shop_phone);
        $fields['active'] = Tools::getValue('active',$seller->active);
        $fields['id_seller'] = $id_seller;
        $fields['reason'] = Tools::getValue('reason',$seller->reason);
        $fields['commission_rate'] = Tools::getValue('commission_rate',$seller->commission_rate);
        $fields['code_chat'] = Tools::getValue('code_chat',$seller->code_chat);
        $fields['date_from'] = Tools::getValue('date_from',$seller->date_from);
        $fields['date_to'] = Tools::getValue('date_to',$seller->date_to);
        $fields['auto_enabled_product'] = Tools::getValue('auto_enabled_product',$seller->auto_enabled_product);
        $fields['link_facebook'] = Tools::getValue('link_facebook',$seller->link_facebook);
        $fields['link_google'] = Tools::getValue('link_google',$seller->link_google);
        $fields['link_instagram'] = Tools::getValue('link_instagram',$seller->link_instagram);
        $fields['link_twitter'] = Tools::getValue('link_twitter',$seller->link_twitter);
        $fields['latitude'] = Tools::getValue('latitude',$seller->latitude)!=0 ? Tools::getValue('latitude',$seller->latitude) :'';
        $fields['longitude'] = Tools::getValue('longitude',$seller->longitude) !=0? Tools::getValue('longitude',$seller->longitude) :'';
        $fields['id_group'] = Tools::getValue('id_group',$seller->id_group);
        /* _ARM_ Adding licence */
        $fields['licence'] = Tools::getValue('licence',$seller->licence);
        $fields['shop_zip'] = Tools::getValue('shop_zip',$seller->shop_zip);
        $fields['shop_city'] = Tools::getValue('shop_city',$seller->shop_city);

        if($languages)
        {
            foreach($languages as $language)
            {
                $fields['shop_name'][$language['id_lang']] = Tools::getValue('shop_name_'.$language['id_lang'],$seller->shop_name[$language['id_lang']]);
                $fields['shop_description'][$language['id_lang']] = Tools::getValue('shop_description_'.$language['id_lang'],$seller->shop_description[$language['id_lang']]);
                $fields['shop_address'][$language['id_lang']] = Tools::getValue('shop_address_'.$language['id_lang'],$seller->shop_address[$language['id_lang']]);
                $fields['shop_banner'][$language['id_lang']] = $seller->shop_banner[$language['id_lang']];
                $fields['banner_url'][$language['id_lang']] = Tools::getValue('banner_url_'.$language['id_lang'],$seller->banner_url[$language['id_lang']]);
            }
        }
        return $fields;
    }
    public function update($null_values = true)
    {
        $seller_old = (int)Db::getInstance()->getValue('SELECT active,vacation_mode,vacation_type,id_group FROM `'._DB_PREFIX_.'ets_mp_seller` WHERE id_seller='.(int)$this->id);
        $return = parent::update($null_values);
        /* _ARM_ Adding licence */
        if (! empty(Tools::getValue('licence', null))) {
            $customer = new Customer($this->id_customer);
            $customer->licence = Tools::getValue('licence');
            $customer->save();
        }
        if($return && $seller_old['active'] !=$this->active)
        {
            $this->rebuildLayeredCache($this->active);
            $data= array(
                '{seller_name}' => $this->seller_name,
                '{reason}' => $this->reason,
                '{store_email}' => Configuration::get('ETS_MP_EMAIL_ADMIN_NOTIFICATION')?:Configuration::get('PS_SHOP_EMAIL'),
            );
            if($this->active==1 && Configuration::get('ETS_MP_EMAIL_SELLER_SHOP_ACTIVED_OR_DECLINED'))
            {
                $subjects = array(
                    'translation' => $this->l('Your shop has been activated'),
                    'origin'=> 'Your shop has been activated',
                    'specific'=>'seller'
                );
                Ets_marketplace::sendMail('to_seller_shop_actived',$data,$this->seller_email,$subjects ,$this->seller_name);
            }
            elseif($this->active==0 && Configuration::get('ETS_MP_EMAIL_SELLER_DISABLED'))
            {
                $subjects = array(
                    'translation' => $this->l('Your shop has been disabled'),
                    'origin'=> 'Your shop has been disabled',
                    'specific'=>'seller'
                );
                Ets_marketplace::sendMail('to_seller_account_disabled',$data,$this->seller_email,$subjects,$this->seller_name);
            }
            elseif($this->active==-2 && Configuration::get('ETS_MP_EMAIL_SELLER_EXPIRED'))
            {
                $subjects = array(
                    'translation' => $this->l('Your shop is expired'),
                    'origin'=> 'Your shop is expired',
                    'specific'=>'seller'
                );
                Ets_marketplace::sendMail('to_seller_account_expired',$data,$this->seller_email,$subjects,$this->seller_name);
            }    
            elseif($this->active==-3 && Configuration::get('ETS_MP_EMAIL_SELLER_SHOP_ACTIVED_OR_DECLINED'))
            {
                $subjects = array(
                    'translation' => $this->l('Your shop has been declined'),
                    'origin'=> 'Your shop has been declined',
                    'specific'=>'seller'
                );
                Ets_marketplace::sendMail('to_seller_shop_declined',$data,$this->seller_email,$subjects,$this->seller_name);
            }    
        }
        if($this->id_group!= $seller_old['id_group'])
        {
            if($this->active==1 && Configuration::get('ETS_MP_EMAIL_SELLER_UPGRADED_GROUP'))
            {
                $fee_type = $this->getFeeType();
                $fee_type_text = $this->l('No fee');
                switch ($fee_type) {
                  case 'no_fee':
                    $fee_type_text = $this->l('No fee');
                    break;
                  case 'pay_once':
                    $fee_type_text = $this->l('Pay once');
                    break;
                  case 'monthly_fee':
                    $fee_type_text = $this->l('Monthly fee');
                    break;
                  case 'quarterly_fee':
                    $fee_type_text = $this->l('Quarterly fee');
                    break;
                  case 'yearly_fee':
                    $fee_type_text = $this->l('Yearly fee');
                    break;
                }
                $data= array(
                    '{seller_name}' => $this->seller_name,
                    '{fee_type}' => $fee_type_text,
                    '{fee_amount}' => $this->getFeeAmount(),
                    '{commission_rate}' => $this->commission_rate ?: $this->getCommissionRate(),
                );
                $subjects = array(
                    'translation' => $this->l('Your shop is upgraded'),
                    'origin'=> 'Your shop is upgraded',
                    'specific'=>'seller'
                );
                Ets_marketplace::sendMail('to_seller_upgraded_group',$data,$this->seller_email,$subjects ,$this->seller_name);
            }
        }
        if($this->vacation_mode!=$seller_old['vacation_mode'] || $this->vacation_type!= $seller_old['vacation_type'])
        {
            if($this->vacation_mode && $this->vacation_type=='disable_product')
                $this->rebuildLayeredCache(0);
            else
                $this->rebuildLayeredCache(1);
        }
        return $return;
    }
    public function delete()
    {
        $result = parent::delete();
        if($result)
        {
            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'ets_mp_seller_manager` WHERE id_customer='.(int)$this->id_customer);
            $this->rebuildLayeredCache(false);
            if($this->shop_logo && !Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'ets_mp_registration` WHERE shop_logo="'.pSQL($this->shop_logo).'"'))
            {
                if(file_exists(_PS_IMG_DIR_.'mp_seller/'.$this->shop_logo))
                    @unlink(_PS_IMG_DIR_.'mp_seller/'.$this->shop_logo);
            }
        }
        return $result;
    }
    public function l($string)
    {
        return Translate::getModuleTranslation('ets_marketplace', $string, pathinfo(__FILE__, PATHINFO_FILENAME));
    }
    public function rebuildLayeredCache($active)
    {
        $products = Db::getInstance()->executeS('SELECT id_product FROM `'._DB_PREFIX_.'ets_mp_seller_product` WHERE id_customer='.(int)$this->id_customer.' AND active=1');
        $productsIds = array();
        foreach($products as $product)
        {
            $productsIds[] = $product['id_product'];
        }
        if($productsIds)
        {
            if($active)
            {
                Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'product` SET active=1 WHERE active=0 AND id_product IN (' . implode(',', array_map('intval', $productsIds)) . ')');
                Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'product_shop` SET active=1 WHERE active=0 AND id_product IN (' . implode(',', array_map('intval', $productsIds)) . ')');
            }
            else
            {
                Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'product` SET active=0 WHERE active=1 AND id_product IN (' . implode(',', array_map('intval', $productsIds)) . ')');
                Db::getInstance()->execute('UPDATE `'._DB_PREFIX_.'product_shop` SET active=0 WHERE active=1 AND id_product IN (' . implode(',', array_map('intval', $productsIds)) . ')');
            }
            if(Module::isEnabled('ps_facetedsearch'))
                $search = Module::getInstanceByName('ps_facetedsearch'); 
            elseif(Module::isEnabled('blocklayered'))
                $search = Module::getInstanceByName('blocklayered');
            if(isset($search) && $search)
            {
                $search->rebuildLayeredCache($productsIds);
            }
            if((int)Configuration::get('ETS_SPEED_ENABLE_PAGE_CACHE') && Module::isInstalled('ets_superspeed') && Module::isEnabled('ets_superspeed') && class_exists('Ets_ss_class_cache'))
            {
                $cacheObjSuperSpeed = new Ets_ss_class_cache();
                if(method_exists($cacheObjSuperSpeed,'deleteCache'))
                    $cacheObjSuperSpeed->deleteCache();
            }
            if((int)Configuration::get('ETS_SPEED_ENABLE_PAGE_CACHE') && Module::isInstalled('ets_pagecache') && Module::isEnabled('ets_pagecache') && class_exists('Ets_pagecache_class_cache'))
            {
                $cacheObjPageCache = new Ets_pagecache_class_cache();
                if(method_exists($cacheObjPageCache,'deleteCache'))
                    $cacheObjPageCache->deleteCache();
            }
        }
    }
    static public function _getSellerByIdCustomer($id_customer,$id_lang=null,$active=false)
    {
        $id_seller= (int)Db::getInstance()->getValue('SELECT id_seller FROM `'._DB_PREFIX_.'ets_mp_seller` WHERE id_customer='.(int)$id_customer.($active ? ' AND active=1':''));
        if($id_seller)
            return new Ets_mp_seller($id_seller,$id_lang);
        else
            return false;
    }
    public function getLink()
    {
        $module = Module::getInstanceByName('ets_marketplace');
        return $module->getShopLink(array('id_seller'=>$this->id));
    }
    public function getAVGReviewProduct()
    {
        if(Module::isInstalled('ets_productcomments') && Module::isEnabled('ets_productcomments'))
        {
            $sql = 'SELECT AVG(pc.grade) as avg_grade,COUNT(pc.grade) as count_grade FROM `'._DB_PREFIX_.'ets_pc_product_comment` pc
            INNER JOIN `'._DB_PREFIX_.'product` p ON (pc.id_product=p.id_product AND p.active=1)
            INNER JOIN `'._DB_PREFIX_.'ets_mp_seller_product` seller_product ON (seller_product.id_product=pc.id_product)
            WHERE seller_product.id_customer="'.(int)$this->id_customer.'"'.(Configuration::get('ETS_PC_MODERATE') ? ' AND pc.validate=1':'').' AND pc.grade!=0';
            return Db::getInstance()->getRow($sql);
        }
        if(Module::isInstalled('productcomments') && Module::isEnabled('productcomments'))
        {
            $sql = 'SELECT AVG(pc.grade) as avg_grade,COUNT(pc.grade) as count_grade FROM `'._DB_PREFIX_.'product_comment` pc
            INNER JOIN `'._DB_PREFIX_.'product` p ON (pc.id_product=p.id_product AND p.active=1)
            INNER JOIN `'._DB_PREFIX_.'ets_mp_seller_product` seller_product ON (seller_product.id_product=pc.id_product)
            WHERE seller_product.id_customer="'.(int)$this->id_customer.'"'.(Configuration::get('PRODUCT_COMMENTS_MODERATE') ? ' AND pc.validate=1':'').' AND pc.grade!=0';
            return Db::getInstance()->getRow($sql);
        }
        /** _ARM_ Add SBA Comments */
        if (Module::isEnabled('sba_comments')) {
            include_once _PS_MODULE_DIR_.'sba_comments/classes/Comment.php';
            $average = Comment::getSellerNote((int) $this->id_customer);
            $count = Comment::getComments((int) $this->id_customer, true, true);
            return [
                'avg_grade' => $average,
                'count_grade' => $count
            ];
        }
        return false;
    }
    public function getCarriers($filter='',$start=0,$limit=12,$order_by='',$total=false)
    {
        if(!Configuration::get('ETS_MP_SELLER_CREATE_SHIPPING') && !Configuration::get('ETS_MP_SELLER_USER_GLOBAL_SHIPPING'))
            return $total ? 0: array();
        if(!Configuration::get('ETS_MP_SELLER_CREATE_SHIPPING'))
            $filter .= ' AND cs.id_carrier_reference is NULL';
        elseif(!Configuration::get('ETS_MP_SELLER_USER_GLOBAL_SHIPPING'))
           $filter.= ' AND cs.id_customer="'.(int)$this->id_customer.'"';
        elseif($this->user_shipping==1)
            $filter .= ' AND cs.id_carrier_reference is NULL';
        elseif($this->user_shipping==3)
            $filter .= ' AND (cs.id_customer="'.(int)$this->id_customer.'" OR cs.id_carrier_reference is NULL)';
        else
           $filter = ' AND cs.id_customer="'.(int)$this->id_customer.'"'; 
        if($total)
            $sql = 'SELECT COUNT(DISTINCT c.id_carrier)';
        else
            $sql ='SELECT *';

        /* _ARM_ Add condition on carrier sellerId = seller customerId */
       // $sql .=' FROM `'._DB_PREFIX_.'carrier` c 
        //LEFT JOIN `'._DB_PREFIX_.'ets_mp_carrier_seller` cs ON (c.id_reference= cs.id_carrier_reference)
        //LEFT JOIN `'._DB_PREFIX_.'carrier_lang` cl ON (c.id_carrier=cl.id_carrier AND cl.id_lang="'.(int)$this->context->language->id.'")     
        //WHERE c.deleted=0 AND c.`sellerId` = '.(int) $this->id_customer.' '.($filter ? $filter:'').' GROUP BY c.id_carrier'
        //.($order_by ? ' ORDER By '.$order_by:'');

        $sql .=' FROM `'._DB_PREFIX_.'carrier` c 
        LEFT JOIN `'._DB_PREFIX_.'ets_mp_carrier_seller` cs ON (c.id_reference= cs.id_carrier_reference)
        LEFT JOIN `'._DB_PREFIX_.'carrier_lang` cl ON (c.id_carrier=cl.id_carrier AND cl.id_lang="'.(int)$this->context->language->id.'")     
        WHERE c.deleted=0 AND c.`sellerId` = 0 '.($filter ? $filter:'').' GROUP BY c.id_carrier'
        .($order_by ? ' ORDER By '.$order_by:'');
        if(!$total && $limit)
            $sql .= ' LIMIT '.(int)$start.','.(int)$limit;
        if($total)
            return Db::getInstance()->getValue($sql);
        else
        {
           return Db::getInstance()->executeS($sql);
        }
    }
    public function getUserManagers($filter='',$start=0,$limit=12,$order_by='',$total=false)
    {
        if($total)
            $sql = 'SELECT COUNT(DISTINCT m.id_ets_mp_seller_manager)';
        else
            $sql = 'SELECT m.*,CONCAT(c.firstname," ",c.lastname) as name'; 
        $sql .=' FROM `'._DB_PREFIX_.'ets_mp_seller_manager` m 
        INNER JOIN `'._DB_PREFIX_.'customer` c ON (c.id_customer=m.id_user)
        WHERE m.id_customer="'.(int)$this->id_customer.'" '.($filter ? $filter:'').($order_by ? ' ORDER By '.$order_by:'');
        if($total)
            return Db::getInstance()->getValue($sql);
        else
        {
            $sql .= ' LIMIT '.(int)$start.','.(int)$limit;
            return Db::getInstance()->executeS($sql);
        }
    }
    public function _getTotalNumberOfProductSold($id_product=0)
    {
        if(Configuration::get('ETS_MP_COMMISSION_APPROVED_WHEN'))
        {
            $status = explode(',',Configuration::get('ETS_MP_COMMISSION_APPROVED_WHEN'));
            $sql ='SELECT sum(product_quantity) FROM `'._DB_PREFIX_.'order_detail` od
            INNER JOIN `'._DB_PREFIX_.'orders` o ON (od.id_order=o.id_order)
            INNER JOIN `'._DB_PREFIX_.'ets_mp_seller_product` seller_product ON (seller_product.id_product=od.product_id)
            WHERE seller_product.id_customer="'.(int)$this->id_customer.'" AND o.current_state IN ('.implode(',',array_map('intval',$status)).')'.($id_product ? ' AND od.product_id="'.(int)$id_product.'"':'');
            return (int)Db::getInstance()->getValue($sql);
        }
        else
            return 0;
    }
    public function getFeeType()
    {
        if($this->id_group)
        {
            $group = new Ets_mp_seller_group($this->id_group);
            if(!$group->use_fee_global && $group->fee_type)
                return $group->fee_type;
        }
        return Configuration::get('ETS_MP_SELLER_FEE_TYPE');
    }
    public function getFeeAmount()
    {
        if($this->id_group)
        {
            $group = new Ets_mp_seller_group($this->id_group);
            if(!$group->use_fee_global && $group->fee_amount)
                return $group->fee_amount;
        }
        return Configuration::get('ETS_MP_SELLER_FEE_AMOUNT');
    }
    public function getCommissionRate()
    {
        if($this->id_group)
        {
            $group = new Ets_mp_seller_group($this->id_group);
            if(!$group->use_commission_global && $group->commission_rate)
                return $group->commission_rate;
        }
        return Configuration::get('ETS_MP_COMMISSION_RATE');
    }
    public function getFeeTax()
    {
        if($this->id_group)
        {
            $group = new Ets_mp_seller_group($this->id_group);
            if(!$group->use_commission_global && $group->id)
                return $group->fee_tax;
        }
        return Configuration::get('ETS_MP_SELLER_FEE_TAX');
    }
    public function checkHasOrder($id_order)
    {
        return Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'ets_mp_seller_order` WHERE id_order='.(int)$id_order.' AND id_customer='.(int)$this->id_customer);
    }
    public function checkHasProduct($id_product)
    {
        return Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'ets_mp_seller_product` WHERE id_product="'.(int)$id_product.'" AND id_customer='.(int)$this->id_customer);
    }
    public function getListCarriersUser($id_carrier=0,$id_reference =0)
    {
        $carriers = $this->getCarriers(' AND c.active=1 '.($id_carrier !=0 ? ' AND c.id_carrier="'.(int)$id_carrier.'"':'').($id_reference !=0 ? ' AND c.id_reference="'.(int)$id_reference.'"':''),false,false);
        if($id_carrier || $id_reference)
            return $carriers ? $carriers[0] :array();
        else
            return $carriers;
    }
    public function checkHasManufacturer($id_manufacturer,$user= true)
    {
        if($user)
            return $this->getManufacturers(' AND m.active=1 AND m.id_manufacturer='.(int)$id_manufacturer);
        else
            return Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'ets_mp_manufacturer_seller` WHERE id_customer="'.(int)$this->id_customer.'" AND id_manufacturer='.(int)$id_manufacturer);
        
    }
    public function checkHasSupplier($id_supplier,$user= true)
    {
        if($user)
            return $this->getSuppliers(' AND s.active=1 AND s.id_supplier='.(int)$id_supplier);
        else
            return Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'ets_mp_supplier_seller` WHERE id_customer="'.(int)$this->id_customer.'" AND id_supplier='.(int)$id_supplier);
        
    }
    public function checkHasFeature($id_feature,$user = true)
    {
        if($user)
        {
            return $this->getFeatures(' AND f.id_feature="'.(int)$id_feature.'"') ? true :false;
        }
        else
            return Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'ets_mp_feature_seller` WHERE id_feature="'.(int)$id_feature.'" AND id_customer="'.(int)$this->id_customer.'"');
    }
    public function checkHasAttributeGroup($id_attribute_group,$user= true)
    {
        if($user)
            return $this->getAttributeGroups(' AND ag.id_attribute_group = "'.(int)$id_attribute_group.'"') ? true :false;
        else
        {
            $sql = 'SELECT * FROM `'._DB_PREFIX_.'attribute_group` ag
            INNER JOIN `'._DB_PREFIX_.'attribute_group_shop` ags ON (ag.id_attribute_group=ags.id_attribute_group AND ags.id_shop="'.(int)$this->context->shop->id.'")
            INNER JOIN `'._DB_PREFIX_.'ets_mp_attribute_group_seller` agse ON (agse.id_attribute_group = ag.id_attribute_group)
            WHERE ag.id_attribute_group="'.(int)$id_attribute_group.'" AND agse.id_customer="'.(int)$this->id_customer.'"';
            return Db::getInstance()->getRow($sql);
        }
        
    }
    public function checkHasCartRule($id_cart_rule)
    {
        return Db::getInstance()->getRow('SELECT * FROM `'._DB_PREFIX_.'ets_mp_cart_rule_seller` WHERE id_cart_rule="'.(int)$id_cart_rule.'" AND id_customer="'.(int)$this->id_customer.'"');
    }
    public function confirmedPayment()
    {
        $billing = new Ets_mp_billing($this->id_billing);
        $billing->seller_confirm =1;
        if(Validate::isLoadedObject($billing) &&  $billing->update(true))
        {
            if(Configuration::get('ETS_MP_EMAIL_ADMIN_CONFIRMED_PAYMENT'))
            {
                $data= array(
                    '{seller_name}' => $this->seller_name,
                    '{billing_number}' => $billing->getBillingNumberInvoice(),
                    '{shop_seller}' => $this->shop_name[$this->context->language->id],
                    '{amount}' => Tools::displayPrice($billing->amount,new Currency(Configuration::get('PS_CURRENCY_DEFAULT'))),
                    '{confirmed_date}' => date('Y-m-d H:i:s'),
                );
                $subjects = array(
                    'translation' => $this->l('A seller has confirmed payment'),
                    'origin'=> 'A seller has confirmed payment',
                    'specific'=>'seller',
                );
                Ets_marketplace::sendMail('to_admin_seller_confirmed_payment',$data,'',$subjects);
                
            }
            die(
                Tools::jsonEncode(
                    array(
                        'success' => ($message = Configuration::get('ETS_MP_MESSAGE_CONFIRMED_PAYMENT',$this->context->language->id)) ? $message : $this->l('Thanks for confirming that you have just sent the fee, we will check it and get back to you as soon as possible'),
                    )
                )
            );
        }
    }
    public static function getMaps($id_seller=0,$total = false)
    {
        $module = Module::getInstanceByName('ets_marketplace');
        if(Tools::getValue('all')==1 || $total)
            $sql = 'SELECT * FROM '._DB_PREFIX_.'ets_mp_seller s
            LEFT JOIN '._DB_PREFIX_.'ets_mp_seller_lang sl ON (s.id_seller = sl.id_seller AND sl.id_lang="'.(int)Context::getContext()->language->id.'")
            LEFT JOIN '._DB_PREFIX_.'ets_mp_seller_product sp ON (s.id_customer=sp.id_customer)
            LEFT JOIN '._DB_PREFIX_.'product p ON (p.id_product = sp.id_product)
            WHERE s.latitude!=0 AND s.longitude!=0 AND s.latitude is not null AND s.longitude is not null'.($id_seller ? ' AND s.id_seller="'.(int)$id_seller.'"':' AND sp.id_product is NOT NULL AND p.active=1 GROUP BY s.id_seller');
        else
        {
            $distance = (int)Tools::getValue('radius', 100);
            $multiplicator = 6371;
            $sql = 'SELECT *,('.(int)$multiplicator.'
				* acos(
					cos(radians('.(float)Tools::getValue('latitude').'))
					* cos(radians(s.latitude))
					* cos(radians(s.longitude) - radians('.(float)Tools::getValue('longitude').'))
					+ sin(radians('.(float)Tools::getValue('latitude').'))
					* sin(radians(s.latitude))
				)
			) as distance FROM '._DB_PREFIX_.'ets_mp_seller s
            LEFT JOIN '._DB_PREFIX_.'ets_mp_seller_lang sl ON (s.id_seller = sl.id_seller AND sl.id_lang="'.(int)Context::getContext()->language->id.'")
            LEFT JOIN '._DB_PREFIX_.'ets_mp_seller_product sp ON (s.id_customer=sp.id_customer)
            LEFT JOIN '._DB_PREFIX_.'product p ON (p.id_product = sp.id_product)
            WHERE s.latitude!=0 AND s.longitude!=0 AND s.latitude is not null AND s.longitude is not null'.($id_seller ? ' AND s.id_seller="'.(int)$id_seller.'"':' AND sp.id_product is NOT NULL AND p.active=1').
            ' GROUP BY s.id_seller HAVING distance < '.(int)$distance.'
			ORDER BY distance ASC
			LIMIT 0,20';
        }
        $maps = Db::getInstance()->executeS($sql);
        if($total)
        {
            return  count($maps);
        }
        if($maps)
        {
            $parnode = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><markers></markers>');
            foreach ($maps as $map) {
                if($map['latitude'] && $map['longitude'])
                {
                    $other = $map['shop_phone'] ? $module->l('Shop phone number:','seller').' '.$map['shop_phone']:'';
                    $newnode = $parnode->addChild('marker');
                    $newnode->addAttribute('name', $map['shop_name']);
                    $newnode->addAttribute('addressNoHtml', strip_tags(str_replace('<br />', ' ', $map['shop_address'])));
                    $newnode->addAttribute('address', $map['shop_address']);
                    $newnode->addAttribute('other', $other);
                    $newnode->addAttribute('phone', $map['shop_phone']);
                    $newnode->addAttribute('id_store', (int)$map['id_seller']);
                    $newnode->addAttribute('has_store_picture', file_exists(_PS_IMG_DIR_.'/mp_seller/'.$map['shop_logo']) ? Context::getContext()->link->getMediaLink(__PS_BASE_URI__.'img/mp_seller/'.$map['shop_logo']):false );
                    $newnode->addAttribute('lat', (float)$map['latitude']);
                    $newnode->addAttribute('lng', (float)$map['longitude']);
                    $newnode->addAttribute('link_shop',$module->getShopLink(array('id_seller'=>$map['id_seller'])));
                    if (isset($map['distance'])) {
                        $newnode->addAttribute('distance', (int)$map['distance']);
                    }
                }
            }
            header('Content-type: text/xml');
            die($parnode->asXML());
        }
    }
    public function submitSaveSeller($id_seller,&$errors,$admin=false,&$valueFieldPost)
    {
        $id_lang_default = (int)Configuration::get('PS_LANG_DEFAULT');
        $languages = Language::getLanguages(false);
        $module = Module::getInstanceByName('ets_marketplace');
        $seller_fields = array(
            'seller_name' => $this->l('Seller name'),
            'seller_email' => $this->l('Seller email'),
            'shop_name' => $this->l('Shop name'),
            'shop_description' => $this->l('Shop description'),
            'shop_address' => $this->l('Shop address'),
            'vat_number' => $this->l('VAT number'),
            'shop_phone' => $this->l('Shop phone number'),
            'shop_logo' => $this->l('Shop logo'),
            'shop_banner' => $this->l('Shop banner'),
            'banner_url' => $this->l('Banner URL'),
            'link_facebook' => $this->l('Facebook link'),
            'link_google' => $this->l('Google link'),
            'link_instagram' => $this->l('Instagram link'),
            'link_twitter' => $this->l('Twitter link'),
            'latitude' => $this->l('Latitude'),
            'longitude' => $this->l('Longitude'),
            
            'shop_zip' => $this->l('Shop zip'),
            'shop_city' => $this->l('Shop city')
        );
        if(!Tools::getValue('shop_name_'.$id_lang_default))
            $errors[] = $this->l('Shop name is required');
        /*if(!Tools::getValue('shop_description_'.$id_lang_default))
            $errors[] = $this->l('Shop description is required');*/
        if(!Tools::getValue('shop_address_'.$id_lang_default))
            $errors[] = $this->l('Shop address is required');

        if(!Tools::getValue('shop_zip'))
            $errors[] = $this->l('Shop zip is required');
        if(!Tools::getValue('shop_city'))
            $errors[] = $this->l('Shop city is required');

        if(!Tools::getValue('shop_phone'))
            $errors[] = $this->l('Shop phone number is required');
        if(Tools::getValue('longitude') || Tools::getValue('latitude'))
        {
            if(!Tools::getValue('longitude'))
                $errors[] = $this->l('Longitude is required');
            if(!Tools::getValue('latitude')) 
                $errors[] = $this->l('Latitude is required');
        }
        if($admin)
        {
            if(Tools::getValue('commission_rate'))
            {
                if(!Validate::isPrice(Tools::getValue('commission_rate')))
                    $errors[] = $this->l('Commission rate is invalid');
                elseif(Tools::getValue('commission_rate')<=0 || Tools::getValue('commission_rate')>=100)
                    $errors[] = $this->l('Commission rate must be between 0% and 100%');
            }
                
            if(Tools::getValue('vat_number') && !Validate::isGenericName(Tools::getValue('vat_number')))
                $errors[] = $this->l('VAT number is invalid');
            if(Tools::getValue('date_from') && !Validate::isDate(Tools::getValue('date_from')))
                $errors[] = $this->l('Available from is not valid');
            if(Tools::getValue('date_to') && !Validate::isDate(Tools::getValue('date_to')))
                $errors[] = $this->l('Available to is not valid');
            if(Tools::getValue('date_to') && Tools::getValue('date_from') && Validate::isDate(Tools::getValue('date_to')) && Validate::isDate(Tools::getValue('date_from')) && strtotime(Tools::getValue('date_from')) >= strtotime(Tools::getValue('date_to')))
                $errors[] = $this->l('"From" date must be smaller than "To" date');
        }
        elseif(Tools::isSubmit('vacation_type') && Tools::getValue('vacation_type'))
        {
            if(($vacation_type =  Tools::getValue('vacation_type')) && !in_array($vacation_type,array('show_notifications','disable_product')))
                $errors[] = $this->l('Vacation mode is not valid');
            $valueFieldPost['vacation_type'] = Tools::getValue('vacation_type');
            if($vacation_type== 'show_notifications' && !Tools::getValue('vacation_notifications_'.$id_lang_default))
                $errors[] = $this->l('Notification is required');
            foreach($languages as $language)
            {
                if(Tools::getValue('vacation_notifications_'.$language['id_lang']) && !Validate::isCleanHtml(Tools::getValue('vacation_notifications_'.$language['id_lang'])))
                        $errors[] =sprintf($this->l('Notification is not valid in %s'),$language['iso_code']);
                    $valueFieldPost['vacation_notifications'][$language['id_lang']] = Tools::getValue('vacation_notifications_'.$language['id_lang']);
            }
        }
        foreach($seller_fields as $key=> $seller_field)
        {
            if(in_array($key,array('shop_name','shop_description','shop_address','banner_url')))
            {
                foreach($languages as $language)
                {
                    if(Tools::getValue($key.'_'.$language['id_lang']) && ($key =='banner_url' ? !Ets_marketplace::isLink(Tools::getValue($key.'_'.$language['id_lang'])) : !Validate::isCleanHtml(Tools::getValue($key.'_'.$language['id_lang'])) ) )
                        $errors[] =sprintf($this->l('%s is not valid in %s'),$seller_field,$language['iso_code']);
                    $valueFieldPost[$key][$language['id_lang']] = Tools::getValue($key.'_'.$language['id_lang']);
                }
            }
            else
            {
                if(in_array($key,array('link_facebook','link_google','link_instagram','link_twitter')))
                {
                    if(Tools::getValue($key) && !Ets_marketplace::isLink(Tools::getValue($key)))
                        $errors[] = sprintf($this->l('%s is not valid'),$seller_field);
                }
                elseif(in_array($key,array('longitude','latitude')))
                {
                    if(Tools::getValue($key) && !Validate::isCoordinate(Tools::getValue($key)))
                        $errors[] = sprintf($this->l('%s is not valid'),$seller_field);
                }
                elseif($key!='shop_logo' && $key!='shop_banner' && $key!='seller_email' && $key!='vat_number' && Tools::getValue($key) && !Validate::isCleanHtml(Tools::getValue($key)))
                    $errors[] = sprintf($this->l('%s is not valid'),$seller_field);
                elseif( $key=='seller_email' && Tools::getValue($key) && !Validate::isEmail(Tools::getValue($key)))
                    $errors[] = sprintf($this->l('%s is not valid'),$seller_field);
                elseif($key=='vat_number' && Tools::getValue($key) && !Validate::isGenericName(Tools::getValue($key)))
                        $errors[] = $this->l('VAT number is not valid');
                $valueFieldPost[$key] = Tools::getValue($key);
            }
        }
        if(!$errors)
        {
            if($id_seller)
            {
                $seller = new Ets_mp_seller($id_seller);
            }
            else
            {
                $seller = new Ets_mp_seller();
                $seller->date_add = date('Y-m-d H:i:s');
                $seller->id_customer = $this->context->customer->id;
                $seller->id_shop = $this->context->shop->id;
                $seller->date_add = date('Y-m-d H:i:s');
                $seller->id_group = (int)Configuration::get('ETS_MP_SELLER_GROUP_DEFAULT');
                $seller->auto_enabled_product='default';
                if($seller->getFeeType()!='no_fee')
                {
                    $seller->active = -1;
                    $seller->payment_verify=-1;
                }
                else
                {
                    $seller->payment_verify =0;
                    if(Configuration::get('ETS_MP_ENABLED_IF_NO_FEE'))
                        $seller->active =1;
                    else
                        $seller->active =-1;
                }
                $seller->shop_logo = Db::getInstance()->getValue('SELECT shop_logo FROM `'._DB_PREFIX_.'ets_mp_registration` WHERE id_customer='.(int)$this->context->customer->id);
                foreach($languages as $language)
                    $seller->shop_banner[$language['id_lang']] = Db::getInstance()->getValue('SELECT shop_banner FROM `'._DB_PREFIX_.'ets_mp_registration` WHERE id_customer='.(int)$this->context->customer->id);
            }
            $seller->date_upd = date('Y-m-d H:i:s');
            foreach(array_keys($seller_fields) as $field)
            {
                if(in_array($field,array('shop_name','shop_description','shop_address','banner_url')))
                {
                    foreach($languages as $language)
                    {
                        $seller->{$field}[$language['id_lang']] = Tools::getValue($field.'_'.$language['id_lang']) ? Tools::getValue($field.'_'.$language['id_lang']) :Tools::getValue($field.'_'.$id_lang_default);
                    }
                }
                else
                {
                    if($field!='shop_logo' && $field!='shop_banner')
                    {
                        if(Tools::isSubmit($field))
                            $seller->{$field} = Tools::getValue($field);
                    }
                    else
                    { 
                        if($field=='shop_logo')
                        {
                            if(isset($_FILES['shop_logo']['name'])&& $_FILES['shop_logo']['name'])
                            {
                                $logo = $module->uploadFile('shop_logo',$errors);
                                if($logo)
                                {
                                    $logo_old = $seller->shop_logo;
                                    $seller->shop_logo = $logo;
                                }
                            }
                            /*elseif(!$seller->shop_logo)
                                $errors[] = $this->l('Shop logo is required');*/
                        }
                        if($field=='shop_banner')
                        {
                            $shop_banner_news = array();
                            $shop_banner_olds = array();
                            foreach($languages as $language)
                            {
                                if(isset($_FILES['shop_banner_'.$language['id_lang']]['name'])&& $_FILES['shop_banner_'.$language['id_lang']]['name'])
                                {
                                    $shop_banner_news[$language['id_lang']] = $module->uploadFile('shop_banner_'.$language['id_lang'],$errors);
                                    if($shop_banner_news[$language['id_lang']])
                                    {
                                        $shop_banner_olds[$language['id_lang']] = $seller->shop_banner[$language['id_lang']];
                                        $seller->shop_banner[$language['id_lang']] = $shop_banner_news[$language['id_lang']];
                                    }
                                }
                            }
                            foreach($languages as $language)
                            {
                                if(!$seller->shop_banner[$language['id_lang']])
                                    $seller->shop_banner[$language['id_lang']] = $seller->shop_banner[$id_lang_default];
                            }
                        }
                        
                    }
                }
            }
            if($admin)
            {
                $seller->date_from = Tools::getValue('date_from');
                $seller->date_to = Tools::getValue('date_to');
                $seller->code_chat = Tools::getValue('code_chat');
                $active_old = $seller->active;
                $seller->id_group = Tools::getValue('id_group');
                $seller->commission_rate = trim(Tools::getValue('commission_rate')) ? (float)Tools::getValue('commission_rate'):null;
                if(Tools::getValue('active')==0 || Tools::getValue('active')==-3)
                {
                    $seller->active = Tools::getValue('active');
                    $seller->reason = Tools::getValue('reason');
                } 
                elseif(Tools::getValue('active')==-1)
                     $seller->active = Tools::getValue('active');  
                else{
                    if((!$seller->date_from || strtotime($seller->date_from) <= strtotime(date('Y-m-d'))) && (!$seller->date_to || strtotime($seller->date_to) >= strtotime(date('Y-m-d'))))
                    {
                        $seller->active =1;
                        $seller->mail_expired=0;
                        $seller->mail_going_to_be_expired=0;
                        //$seller->payment_verify=0;
                    }
                    else
                    {
                        $seller->active =-2;
                        $seller->payment_verify=-1;
                    }
                }
            }elseif(Tools::isSubmit('vacation_type') && Tools::isSubmit('vacation_mode'))
            {
                $seller->vacation_mode = (int)Tools::getValue('vacation_mode');
                $seller->vacation_type = Tools::getValue('vacation_type');
                foreach($languages as $language)
                {
                    $seller->vacation_notifications[$language['id_lang']] = Tools::getValue('vacation_notifications_'.$language['id_lang']) ? : Tools::getValue('vacation_notifications_'.$id_lang_default);
                }
            }
            if(!$errors)
            {
                if($seller->id)
                {
                    if($seller->update(true))
                    {
                        if($admin)
                        {
                            if($seller->active!=$active_old && $seller->active==-2)
                            {
                                $fee_type = $seller->getFeeType();
                                if($fee_type!='no_fee')
                                {
                                    $billing = new Ets_mp_billing();
                                    $billing->id_customer = $seller->id_customer;
                                    $billing->amount = (float)$seller->getFeeAmount();
                                    $billing->amount_tax = $module->getFeeIncludeTax($billing->amount,$seller);
                                    $billing->active = 0;
                                    $billing->date_from = $seller->date_to;
                                    if($fee_type=='monthly_fee')
                                        $billing->date_to = date("Y-m-d H:i:s", strtotime($seller->date_to."+1 month"));
                                    elseif($fee_type=='quarterly_fee')
                                        $billing->date_to = date("Y-m-d H:i:s", strtotime($seller->date_to."+3 month"));
                                    elseif($fee_type=='yearly_fee')
                                        $billing->date_to = date("Y-m-d H:i:s", strtotime($seller->date_to."+1 year"));
                                    else
                                        $billing->date_to ='';
                                    $billing->fee_type = $fee_type;
                                    if($billing->add(true,true))
                                    {
                                        $seller->id_billing = $billing->id;
                                        $seller->update();
                                    }
                                }
                            }
                        }
                        if(isset($logo_old) && $logo_old);
                            @unlink(_PS_IMG_DIR_.'mp_seller/'.$logo_old);
                        if(isset($shop_banner_olds) && $shop_banner_olds)
                        {
                            foreach($shop_banner_olds as $shop_banner_old)
                            {
                                if(!in_array($shop_banner_old,$seller->shop_banner) && file_exists(_PS_IMG_DIR_.'mp_seller/'.$shop_banner_old))
                                    @unlink(_PS_IMG_DIR_.'mp_seller/'.$shop_banner_old);
                            }
                        }
                        $this->context->cookie->success_message = $this->l('Updated shop successfully');
                    }
                    else
                    {
                        $this->_errors[] = $this->l('An error occurred while saving the shop');
                        if(isset($logo) && $logo && file_exists(_PS_IMG_DIR_.'mp_seller/'.$logo))
                            @unlink(_PS_IMG_DIR_.'mp_seller/'.$logo);
                        if(isset($shop_banner_news) && $shop_banner_news)
                        {
                            foreach($shop_banner_news as $shop_banner)
                            {
                                if(file_exists(_PS_IMG_DIR_.'mp_seller/'.$shop_banner))
                                    @unlink(_PS_IMG_DIR_.'mp_seller/'.$shop_banner);
                            }
                        }
                    }
                }
                else
                {
                    if($seller->add(true,true))
                    {
                        if(isset($logo_old) && $logo_old);
                            @unlink(_PS_IMG_DIR_.'mp_seller/'.$logo_old);
                        if(isset($shop_banner_olds) && $shop_banner_olds)
                        {
                            foreach($shop_banner_olds as $shop_banner_old)
                            {
                                if(!in_array($shop_banner_old,$seller->shop_banner) && file_exists(_PS_IMG_DIR_.'mp_seller/'.$shop_banner_old))
                                    @unlink(_PS_IMG_DIR_.'mp_seller/'.$shop_banner_old);
                            }
                        }
                        $fee_type = $seller->getFeeType();
                        if($fee_type!='no_fee')
                        {
                            $billing = new Ets_mp_billing();
                            $billing->id_customer = $seller->id_customer;
                            $billing->amount = (float)$seller->getFeeAmount();
                            $billing->amount_tax = $module->getFeeIncludeTax($billing->amount,$seller);
                            $billing->active = 0;
                            $billing->date_from = date('Y-m-d');
                            if($fee_type=='monthly_fee')
                                $billing->date_to = date("Y-m-d", strtotime(date('Y-m-d')."+1 month"));
                            elseif($fee_type=='quarterly_fee')
                                $billing->date_to = date("Y-m-d", strtotime(date('Y-m-d')."+3 month"));
                            elseif($fee_type=='yearly_fee')
                                $billing->date_to = date("Y-m-d", strtotime(date('Y-m-d')."+1 year"));
                            else
                                $billing->date_to ='';
                            $billing->fee_type = $fee_type;
                            if($billing->add(true,true))
                            {
                                $seller->id_billing = $billing->id;
                                $seller->update();
                            }
                            $message = Configuration::get('ETS_MP_MESSAGE_CREATED_SHOP_FEE_REQUIRED',$this->context->language->id)?: $this->l('Thanks for creating your shop. Please send the fee [fee_amount] right now to activate your shop and click on the button "I have just sent the fee" after making payment. [payment_information_manager]');
                            $str_search = array(
                                '[fee_amount]',
                                '[manager_email]',
                                '[payment_information_manager]',
                                '[manager_phone]',
                            );
                            $str_replace =array(
                                Tools::displayPrice($billing->amount_tax,new Currency(Configuration::get('PS_CURRENCY_DEFAULT'))).' ('.$this->l('Tax incl').')',
                                Configuration::get('ETS_MP_EMAIL _ADMIN_NOTIFICATION')?:Configuration::get('PS_SHOP_EMAIL'),
                                $module->_replaceTag(Configuration::get('ETS_MP_SELLER_PAYMENT_INFORMATION',$this->context->language->id)),
                                Configuration::get('PS_SHOP_PHONE'),
                            );
                            $message = str_replace($str_search,$str_replace,$message);
                        }
                        else
                        {
                            if($seller->active==1)
                                $message = Configuration::get('ETS_MP_ MESSAGE_SHOP_ACTIVED',$this->context->language->id)?:$this->l('Congratulations! Your shop has been activated. You can upload products and start selling them','create');
                            else
                                $message = Configuration::get('ETS_MP_MESSAGE_CREATED_SHOP_NO_FEE',$this->context->language->id)?:$this->l('Thanks for creating your shop. Our team are reviewing it. We will get back to you soon','create');
                        
                        } 
                        $this->context->cookie->success_message =str_replace("\n",'<br/>',$message);
                        if(Configuration::get('ETS_MP_EMAIL_ADMIN_SHOP_CREATED'))
                        {
                            $data = array(
                                '{seller_name}'=>  $this->context->customer->firstname.' '.$this->context->customer->lastname,
                                '{shop_seller_name}' => $seller->shop_name[$this->context->language->id],
                                '{shop_description}' => $seller->shop_description[$this->context->language->id],
                                '{shop_address}' => $seller->shop_address[$this->context->language->id],
                                '{shop_phone}' => $seller->shop_phone,
                            );
                            $subjects = array(
                                'translation' => $this->l('New shop has been created'),
                                'origin'=>'New shop has been created',
                                'specific'=>'create'
                            );
                            Ets_marketplace::sendMail('to_admin_shop_created',$data,'',$subjects);
                            
                        }
                    }
                    else
                    {
                        $errors[] = $this->l('An error occurred while creating the shop');
                        if(isset($logo) && $logo && file_exists(_PS_IMG_DIR_.'mp_seller/'.$logo))
                            @unlink(_PS_IMG_DIR_.'mp_seller/'.$logo);
                        if(isset($shop_banner_news) && $shop_banner_news)
                        {
                            foreach($shop_banner_news as $shop_banner)
                            {
                                if(file_exists(_PS_IMG_DIR_.'mp_seller/'.$shop_banner))
                                    @unlink(_PS_IMG_DIR_.'mp_seller/'.$shop_banner);
                            }
                        }
                    }
                }    
            }
            else
            {
                if(isset($logo) && $logo && file_exists(_PS_IMG_DIR_.'mp_seller/'.$logo))
                    @unlink(_PS_IMG_DIR_.'mp_seller/'.$logo);
                if(isset($shop_banner_news) && $shop_banner_news)
                {
                    foreach($shop_banner_news as $shop_banner)
                    {
                        if(file_exists(_PS_IMG_DIR_.'mp_seller/'.$shop_banner))
                            @unlink(_PS_IMG_DIR_.'mp_seller/'.$shop_banner);
                    }
                }
            }
        }
    }
}