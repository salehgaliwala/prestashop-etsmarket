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
function upgrade_module_3_0_1($object)
{
    $object->_uninstallTabs();
    $object->_installTabs();
    Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_seller_group` ( 
    `id_ets_mp_seller_group` INT(11) NOT NULL AUTO_INCREMENT, 
    `id_shop` INT(11) NOT NULL , 
    `use_fee_global` INT(11) NOT NULL , 
    `use_commission_global` INT(11) NOT NULL , 
    `fee_type` VARCHAR(222) NOT NULL ,
    `fee_amount` FLOAT(10,2) NOT NULL , 
    `fee_tax` INT(11) NOT NULL , 
    `commission_rate` FLOAT(10,2) NOT NULL , 
    `date_add` DATETIME NOT NULL , 
    `date_upd` DATETIME NOT NULL,
    PRIMARY KEY (`id_ets_mp_seller_group`) ) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
    Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_seller_group_lang` ( 
    `id_ets_mp_seller_group` INT(11) NOT NULL , 
    `id_lang` INT(11) NOT NULL , 
    `name` TEXT NOT NULL , 
    `level_name` TEXT NOT NULL , 
    `description` TEXT NOT NULL , 
    PRIMARY KEY (`id_ets_mp_seller_group`, `id_lang`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
    if(!$object->checkCreatedColumn('ets_mp_seller','id_group'))
    {
        Db::getInstance()->execute('ALTER TABLE `'._DB_PREFIX_.'ets_mp_seller` ADD `id_group` INT(11) NULL');
    }
    return true;
}