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
 
if (!defined('_PS_ADMIN_DIR_')) {
    define('_PS_ADMIN_DIR_', getcwd());
}
include(_PS_ADMIN_DIR_.'/../../config/config.inc.php');
include(dirname(__FILE__).'/ajax.init.php');
$context = Context::getContext();
$ets_marketplace = Module::getInstanceByName('ets_marketplace');
if($context->employee->id && Tools::getValue('token')==Tools::getAdminTokenLite('AdminModules'))
{
    if(Tools::isSubmit('getSellerProductByAdmin') && $query = Tools::getValue('q'))
    {
        $sql  = 'SELECT * FROM '._DB_PREFIX_.'ets_mp_seller s
        INNER JOIN '._DB_PREFIX_.'customer c ON (s.id_customer=c.id_customer)
        LEFT JOIN '._DB_PREFIX_.'ets_mp_seller_lang sl ON (s.id_seller = sl.id_seller AND sl.id_lang="'.(int)$context->language->id.'")
        WHERE s.id_customer = "'.(int)$query.'" OR s.id_seller ="'.(int)$query.'" OR sl.shop_name LIKE "%'.pSQL($query).'%" OR c.email LIKE "%'.pSQL($query).'%" OR CONCAT(c.firstname," ",c.lastname) LIKE "%'.pSQL($query).'%" AND s.active=1';
        $sellers = Db::getInstance()->executeS($sql);
        die(
            Tools::jsonEncode(
                array(
                    'sellers' => $sellers,
                )
            )
        );
    }
    if(Tools::isSubmit('submitAddSellerProduct') && ($id_product = (int)Tools::getValue('id_product')) && ($id_customer =(int)Tools::getValue('id_customer')))
    {
        $sql = 'SELECT id_customer FROM '._DB_PREFIX_.'ets_mp_seller_product WHERE id_product="'.(int)$id_product.'" AND is_admin!=1';
        if(($product = new Product($id_product)) && (!Validate::isLoadedObject($product) ||Db::getInstance()->getValue($sql)))
        {
            die(
                Tools::jsonEncode(
                    array(
                        'errors' =>$ets_marketplace->l('Product is not valid','search_seller'),
                    )
                )
            );
        }
        else
        {
            if(Db::getInstance()->getValue('SELECT id_customer FROM '._DB_PREFIX_.'ets_mp_seller_product WHERE id_product="'.(int)$id_product.'"'))
            {
                Db::getInstance()->execute('UPDATE '._DB_PREFIX_.'ets_mp_seller_product SET id_customer ="'.(int)$id_customer.'" WHERE id_product="'.(int)$id_product.'"');
            }
            else
                Db::getInstance()->execute('INSERT INTO '._DB_PREFIX_.'ets_mp_seller_product(id_customer,id_product,active,approved,is_admin) VALUES("'.(int)$id_customer.'","'.(int)$id_product.'","'.(int)$product->active.'","'.(int)$product->active.'",1)');
            die(
                Tools::jsonEncode(
                    array(
                        'success' =>$ets_marketplace->l('Updated successfully','search_seller'),
                    )
                )
            );
        }
    }
    if(Tools::isSubmit('submitDeleteSellerProduct') && ($id_product = (int)Tools::getValue('id_product')))
    {
        $sql = 'SELECT id_customer FROM '._DB_PREFIX_.'ets_mp_seller_product WHERE id_product="'.(int)$id_product.'" AND is_admin=1';
        if(($product = new Product($id_product)) && (!Validate::isLoadedObject($product) || !Db::getInstance()->getValue($sql)))
        {
            die(
                Tools::jsonEncode(
                    array(
                        'errors' =>$ets_marketplace->l('Product is not valid','search_seller'),
                    )
                )
            );
        }
        else
        {
            Db::getInstance()->execute('DELETE FROM '._DB_PREFIX_.'ets_mp_seller_product WHERE id_product='.(int)$id_product);
            die(
                Tools::jsonEncode(
                    array(
                        'success' =>$ets_marketplace->l('Deleted successfully','search_seller'),
                    )
                )
            );
        }
    }
}
die(
    Tools::jsonEncode(
        array(
            'sellers' => false,
        )
    )
);