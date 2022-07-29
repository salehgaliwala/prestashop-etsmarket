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
 * @author ETS-Soft <contact@etssoft.net>
 * @copyright  2007-2020 ETS-Soft
 * @license    Valid for 1 website (or project) for each purchase of license
 *  International Registered Trademark & Property of ETS-Soft
 */

class Order extends OrderCore
{
    public function __construct($id = null, $id_lang = null)
    {
        parent::__construct($id, $id_lang);
        if(Tools::getValue('controller')=='orderconfirmation' && ($id_cart = Tools::getValue('id_cart')) && $id_cart==$this->id_cart)
        {
            $id_order_current = Order::getIdByCartId((int) ($id_cart));
            if($id_order_current==$this->id)
            {
                $orders = Db::getInstance()->executeS('SELECT id_order FROM `'._DB_PREFIX_.'orders` WHERE id_order!="'.(int)$this->id.'" AND id_cart="'.(int)$this->id_cart.'"');
                if($orders)
                {
                    
                    foreach($orders as $order)
                    {
                        $class_order = new Order($order['id_order']);
                        $this->total_paid += $class_order->total_paid;
                        $this->total_paid_real += $class_order->total_paid_real;
                        $this->total_paid_tax_incl += $class_order->total_paid_tax_incl;
                        $this->total_paid_tax_excl += $class_order->total_paid_tax_excl;
                        $this->total_discounts_tax_incl += $class_order->total_discounts_tax_incl;
                        $this->total_discounts_tax_excl += $class_order->total_discounts_tax_excl;
                        $this->total_shipping_tax_incl += $class_order->total_shipping_tax_incl;
                        $this->total_shipping_tax_excl += $class_order->total_shipping_tax_excl;
                        $this->total_wrapping_tax_incl += $class_order->total_wrapping_tax_incl;
                        $this->total_wrapping_tax_excl += $class_order->total_wrapping_tax_excl;
                        $this->total_products_wt += $class_order->total_products_wt;
                        $this->total_products += $class_order->total_products;
                    }
                }
            }
        }
    }
    public function getProductsDetail()
    {
        if(Tools::getValue('controller')=='orderconfirmation' && ($id_cart = Tools::getValue('id_cart')) && $id_cart==$this->id_cart)
        {
            $id_order_current = Order::getIdByCartId((int) ($id_cart));
            if($id_order_current==$this->id)
            {
               return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
               SELECT od.*,ps.id_product
               FROM `' . _DB_PREFIX_ . 'order_detail` od
               INNER JOIN `'._DB_PREFIX_.'orders` o ON (od.id_order=o.id_order)
               LEFT JOIN `' . _DB_PREFIX_ . 'product` p ON (p.id_product = od.product_id)
               LEFT JOIN `' . _DB_PREFIX_ . 'product_shop` ps ON (ps.id_product = p.id_product AND ps.id_shop = od.id_shop)
               WHERE o.`id_cart` = ' . (int) $this->id_cart);
            } 
        }
        return parent::getProductsDetail();
    }
    public static function getIdByCartId($id_cart)
    {
        $sql = 'SELECT `id_order` 
            FROM `' . _DB_PREFIX_ . 'orders`
            WHERE `id_cart` = ' . (int) $id_cart .
            Shop::addSqlRestriction();

        $result = Db::getInstance()->getValue($sql);

        return !empty($result) ? (int) $result : false;
    }
}