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
function upgrade_module_2_0_7($object)
{
    $object->registerHook('actionObjectOrderDetailUpdateAfter');
    $object->registerHook('actionObjectOrderDetailAddAfter');
    $object->registerHook('actionObjectOrderDetailDeleteAfter');
    $object->registerHook('displayProductListReviews');
    $object->installLinkDefault();
    $sqls = array();
    $sqls[]= 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_seller_manager` ( 
        `id_ets_mp_seller_manager` INT(11) NOT NULL AUTO_INCREMENT , 
        `id_user` INT(11),
        `id_customer` INT(11),
        `name` VARCHAR(1000) NOT NULL , 
        `email` VARCHAR(222) NOT NULL , 
        `permission` VARCHAR(1000) NOT NULL , 
        `active` INT(1),
        `delete_product` INT(1),
        PRIMARY KEY (`id_ets_mp_seller_manager`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8';
    if(!$object->checkCreatedColumn('ets_mp_seller','user_brand'))
    {
        $sqls[] = 'ALTER TABLE `'._DB_PREFIX_.'ets_mp_seller` ADD `user_brand` INT(11) NULL AFTER `payment_verify`';
        $sqls[] = 'UPDATE `'._DB_PREFIX_.'ets_mp_seller` SET user_brand=3';
    }
    if(!$object->checkCreatedColumn('ets_mp_seller','user_attribute'))
    {
        $sqls[] = 'ALTER TABLE `'._DB_PREFIX_.'ets_mp_seller` ADD `user_attribute` INT(11) NULL AFTER `payment_verify`';
        $sqls[] = 'UPDATE `'._DB_PREFIX_.'ets_mp_seller` SET user_attribute=3';
    }
    if(!$object->checkCreatedColumn('ets_mp_seller','user_feature'))
    {
        $sqls[] = 'ALTER TABLE `'._DB_PREFIX_.'ets_mp_seller` ADD `user_feature` INT(11) NULL AFTER `payment_verify`';
        $sqls[] = 'UPDATE `'._DB_PREFIX_.'ets_mp_seller` SET user_feature=3';
    }
    if(!$object->checkCreatedColumn('ets_mp_seller_billing','amount_tax'))
    {
        $sqls[] = 'ALTER TABLE `'._DB_PREFIX_.'ets_mp_seller_billing` ADD `amount_tax` INT(11) NULL AFTER `amount`';
        $sqls[] = 'UPDATE `'._DB_PREFIX_.'ets_mp_seller_billing` SET amount_tax = amount';
    }
    if(!$object->checkCreatedColumn('ets_mp_seller_commission','reference'))
    {
        $sqls[] = 'ALTER TABLE `'._DB_PREFIX_.'ets_mp_seller_commission` ADD `reference` VARCHAR(22)';
    }
    if(!$object->checkCreatedColumn('ets_mp_commission_usage','reference'))
    {
        $sqls[] = 'ALTER TABLE `'._DB_PREFIX_.'ets_mp_commission_usage` ADD `reference` VARCHAR(22)';
    }
    if(!$object->checkCreatedColumn('ets_mp_seller','id_billing'))
    {
        $sqls[] = 'ALTER TABLE `'._DB_PREFIX_.'ets_mp_seller` ADD `id_billing` INT(11) NULL AFTER `payment_verify`';
    }
    if(!$object->checkCreatedColumn('ets_mp_seller_billing','seller_confirm'))
    {
        $sqls[] = 'ALTER TABLE `'._DB_PREFIX_.'ets_mp_seller_billing` ADD `seller_confirm` INT(11) NULL';
    }
    $sqls[]= 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_seller_report` ( 
        `id_ets_mp_seller_report` INT(11) NOT NULL AUTO_INCREMENT , 
        `id_customer` INT(11),
        `id_seller` INT(11),
        `id_product` INT(11),
        `title` VARCHAR(1000) NULL , 
        `content` text NULL , 
        `date_add` DATETIME NULL , 
        `date_upd` DATETIME NULL ,
        PRIMARY KEY (`id_ets_mp_seller_report`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8';
    if($sqls)
    {
        foreach($sqls as $sql)
            Db::getInstance()->execute($sql);
    }
    Configuration::updateValue('ETS_MP_SELLER_USER_GLOBAL_SHIPPING',1);
    Configuration::updateValue('ETS_MP_SELLER_CREATE_BRAND',1);
    Configuration::updateValue('ETS_MP_SELLER_USER_GLOBAL_BRAND',1);
    Configuration::updateValue('ETS_MP_SELLER_PRODUCT_TYPE_SUBMIT','standard_product,pack_product,virtual_product');
    $object->uninstallOverrides();
    $object->installOverrides();
    return true;
}