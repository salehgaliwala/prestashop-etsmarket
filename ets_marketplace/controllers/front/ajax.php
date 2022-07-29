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
class Ets_MarketPlaceAjaxModuleFrontController extends ModuleFrontController
{
    public function __construct()
	{
		parent::__construct();
	}
    public function postProcess()
    {
        parent::postProcess();
        if(!$this->context->customer->logged || (!$this->module->_getSeller() && Configuration::get('ETS_MP_REQUIRE_REGISTRATION')))
        {
            die(
                Tools::jsonEncode(
                    array(
                        'errors' => $this->module->l('Not login','ajax'),
                    )
                )
            );
        }
        if(Tools::isSubmit('ajaxSearchProduct'))
        {
            $this->displayAjaxProductsList();
        }
        if(Tools::isSubmit('ajaxSearchCustomer'))
        {
            $this->displayAjaxCustomersList();
        }
    }
    public function displayAjaxCustomersList()
    {
        $query = trim(Tools::getValue('q', false));
        if(empty($query))
            die();
        $sql  ='SELECT * FROM `'._DB_PREFIX_.'customer` WHERE id_shop="'.(int)$this->context->shop->id.'" AND (id_customer="'.(int)$query.'" OR email LIKE "%'.pSQL($query).'%" OR CONCAT(firstname," ",lastname) LIKE "%'.pSQL($query).'%")';
        $customers = Db::getInstance()->executeS($sql);
        if($customers)
        {
            foreach($customers as $customer)
            {
                echo $customer['id_customer'].'|'.$customer['firstname'].' '.$customer['lastname'].'|'.$customer['email']."\n";
            }
        }
        die();
    }
    public function displayAjaxProductsList()
    {
        $query = trim(Tools::getValue('q', false));
        if(version_compare(_PS_VERSION_, '1.7', '>='))
            $type_image= ImageType::getFormattedName('home');
        else
            $type_image= ImageType::getFormatedName('home');
        if (empty($query)) {
            die();
        }
        if ($pos = Tools::strpos($query, ' (ref:')) {
            $query = Tools::substr($query, 0, $pos);
        }
        $excludeIds = Tools::getValue('excludeIds', false);
        if ($excludeIds && $excludeIds != 'NaN') {
            $excludeIds = implode(',', array_map('intval', explode(',', $excludeIds)));
        } else {
            $excludeIds = '';
        }
        $disableCombination = Tools::getValue('disableCombination', false);
        $excludeVirtuals = (bool) Tools::getValue('excludeVirtuals', true);
        $exclude_packs = (bool) Tools::getValue('exclude_packs', true);
        $context = Context::getContext();
        $sql = 'SELECT p.`id_product`, pl.`link_rewrite`, p.`reference`, pl.`name`, image_shop.`id_image` id_image, il.`legend`, p.`cache_default_attribute`
                FROM `' . _DB_PREFIX_ . 'product` p
                ' . Shop::addSqlAssociation('product', 'p') . '
                INNER JOIN `'._DB_PREFIX_.'ets_mp_seller_product` seller_product ON (seller_product.id_product=p.id_product AND seller_product.id_customer="'.(int)$this->module->_getSeller()->id_customer.'")
                LEFT JOIN `' . _DB_PREFIX_ . 'product_lang` pl ON (pl.id_product = p.id_product AND pl.id_lang = ' . (int) $context->language->id . Shop::addSqlRestrictionOnLang('pl') . ')
                LEFT JOIN `' . _DB_PREFIX_ . 'image_shop` image_shop
                    ON (image_shop.`id_product` = p.`id_product` AND image_shop.cover=1 AND image_shop.id_shop=' . (int) $context->shop->id . ')
                LEFT JOIN `' . _DB_PREFIX_ . 'image_lang` il ON (image_shop.`id_image` = il.`id_image` AND il.`id_lang` = ' . (int) $context->language->id . ')
                WHERE (p.id_product="'.(int)$query.'" OR pl.name LIKE \'%' . pSQL($query) . '%\' OR p.reference LIKE \'%' . pSQL($query) . '%\')' .
                (!empty($excludeIds) ? ' AND p.id_product NOT IN (' . $excludeIds . ') ' : ' ') .
                (Tools::getValue('active') ? ' AND p.active=1':'').
                ($excludeVirtuals ? ' AND NOT EXISTS (SELECT 1 FROM `' . _DB_PREFIX_ . 'product_download` pd WHERE (pd.id_product = p.id_product))' : '') .
                ($exclude_packs ? ' AND (p.cache_is_pack IS NULL OR p.cache_is_pack = 0)' : '') .
                ' GROUP BY p.id_product';
        $items = Db::getInstance()->executeS($sql);
        if ($items && ($disableCombination || $excludeIds)) {
            $results = [];
            foreach ($items as $item) {
                if(!$item['id_image'])
                    $item['id_image'] = Db::getInstance()->getValue('SELECT id_image FROM `'._DB_PREFIX_.'image` WHERE id_product='.(int)$item['id_product']);
                echo $item['id_product'].'|0|'.trim(str_replace('|','',$item['name'])).'|'.$item['reference'].'|'.($item['id_image'] ? str_replace('http://', Tools::getShopProtocol(), $context->link->getImageLink($item['link_rewrite'], $item['id_image'], $type_image)):'')."\n";
            }
        }
        elseif ($items) {
            // packs
            $results = array();
            foreach ($items as $item) {
                // check if product have combination
                if(!$item['id_image'])
                    $item['id_image'] = Db::getInstance()->getValue('SELECT id_image FROM `'._DB_PREFIX_.'image` WHERE id_product='.(int)$item['id_product']);
                if (Combination::isFeatureActive() && $item['cache_default_attribute']) {
                    $sql = 'SELECT pa.`id_product_attribute`, pa.`reference`, pai.`id_image`, al.`name` AS attribute_name,
                                a.`id_attribute`
                            FROM `' . _DB_PREFIX_ . 'product_attribute` pa
                            ' . Shop::addSqlAssociation('product_attribute', 'pa') . '
                            LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_combination` pac ON pac.`id_product_attribute` = pa.`id_product_attribute`
                            LEFT JOIN `' . _DB_PREFIX_ . 'attribute` a ON a.`id_attribute` = pac.`id_attribute`
                            LEFT JOIN `' . _DB_PREFIX_ . 'attribute_lang` al ON (a.`id_attribute` = al.`id_attribute` AND al.`id_lang` = ' . (int) $context->language->id . ')
                            LEFT JOIN `' . _DB_PREFIX_ . 'product_attribute_image` pai ON pai.`id_product_attribute` = pa.`id_product_attribute`
                            WHERE pa.`id_product` = ' . (int) $item['id_product'] . '
                            GROUP BY pa.`id_product_attribute`
                            ORDER BY pa.`id_product_attribute`';

                    $combinations = Db::getInstance()->executeS($sql);
                    if (!empty($combinations)) {
                        foreach ($combinations as $combination) {
                            $results[$combination['id_product_attribute']]['id'] = $item['id_product'];
                            $results[$combination['id_product_attribute']]['id_product_attribute'] = $combination['id_product_attribute'];
                            $results[$combination['id_product_attribute']]['name'] = $item['name'].' '.$this->module->getProductAttributeName($combination['id_product_attribute']);
                            if (!empty($combination['reference'])) {
                                $results[$combination['id_product_attribute']]['ref'] = $combination['reference'];
                            } else {
                                $results[$combination['id_product_attribute']]['ref'] = !empty($item['reference']) ? $item['reference'] : '';
                            }
                            if (empty($results[$combination['id_product_attribute']]['image'])) {
                                if(!$combination['id_image'])
                                    $combination['id_image'] = $item['id_image'];
                                $results[$combination['id_product_attribute']]['image'] = str_replace('http://', Tools::getShopProtocol(), $context->link->getImageLink($item['link_rewrite'], $combination['id_image'], $type_image));
                            }
                            echo $item['id_product'].'|'.(int)$combination['id_product_attribute'].'|'.trim(str_replace('|','',$results[$combination['id_product_attribute']]['name'])).'|'.$results[$combination['id_product_attribute']]['ref'].'|'.$results[$combination['id_product_attribute']]['image']."\n";
                        }
                    } else {
                        echo $item['id_product'].'|0|'.trim(str_replace('|','',$item['name'])).'|'.$item['reference'].'|'.str_replace('http://', Tools::getShopProtocol(), $context->link->getImageLink($item['link_rewrite'], $item['id_image'], $type_image))."\n";
                    }
                } else {
                    echo $item['id_product'].'|0|'.trim(str_replace('|','',$item['name'])).'|'.$item['reference'].'|'.str_replace('http://', Tools::getShopProtocol(), $context->link->getImageLink($item['link_rewrite'], $item['id_image'], $type_image))."\n";
                }
            }
        }
        die();
    }
 }