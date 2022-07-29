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
function upgrade_module_3_0_2($object)
{
    $object->uninstallOverrides();
    $object->installOverrides();
    $object->registerHook('displayMyAccountBlock');
    $object->registerHook('displayPDFInvoice');
    $sqls = array();
    if(!$object->checkCreatedColumn('ets_mp_seller_group','badge_image'))
    {
        $sqls[] = 'ALTER TABLE `'._DB_PREFIX_.'ets_mp_seller_group` ADD `badge_image` VARCHAR(222)';
    }
    if(!$object->checkCreatedColumn('ets_mp_seller_group_lang','level_name'))
    {
        $sqls[] = 'ALTER TABLE `'._DB_PREFIX_.'ets_mp_seller_group_lang` ADD `level_name` TEXT';
    }
    if(!$object->checkCreatedColumn('ets_mp_seller_contact_message','attachment_name'))
    {
        $sqls[] = 'ALTER TABLE `'._DB_PREFIX_.'ets_mp_seller_contact_message` ADD `attachment_name` VARCHAR(222)';
    }
    if(!$object->checkCreatedColumn('ets_mp_seller','vat_number'))
    {
        $sqls[] = 'ALTER TABLE `'._DB_PREFIX_.'ets_mp_seller` ADD `vat_number` VARCHAR(222)';
    }
    if(!$object->checkCreatedColumn('ets_mp_registration','vat_number'))
    {
        $sqls[] = 'ALTER TABLE `'._DB_PREFIX_.'ets_mp_registration` ADD `vat_number` VARCHAR(222)';
    }
    $sqls[] = 'ALTER TABLE `'._DB_PREFIX_.'ets_mp_payment_method_lang` CHANGE `title` `title` VARCHAR(222) CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL, CHANGE `description` `description` TEXT CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL, CHANGE `note` `note` TEXT CHARACTER SET utf8 COLLATE utf8_bin NULL DEFAULT NULL';
    if(!$object->checkCreatedColumn('ets_mp_seller_contact_message','id_manager'))
    {
        $sqls[] = 'ALTER TABLE `'._DB_PREFIX_.'ets_mp_seller_contact_message` ADD `id_manager` INT(11)';
    }
    if(!$object->checkCreatedColumn('ets_mp_seller_customer_message','id_manager'))
    {
        $sqls[] = 'ALTER TABLE `'._DB_PREFIX_.'ets_mp_seller_customer_message` ADD `id_manager` INT(11) NOT NULL AFTER `id_customer`';
    }
    if($sqls)
    {
        foreach($sqls as $sql)
            Db::getInstance()->execute($sql);
    }
    return true;
}