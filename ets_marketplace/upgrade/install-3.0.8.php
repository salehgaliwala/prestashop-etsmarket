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
function upgrade_module_3_0_8($object)
{
    $sqls = array();
    if(!$object->checkCreatedColumn('ets_mp_seller','user_supplier'))
    {
        $sqls[] = 'ALTER TABLE `'._DB_PREFIX_.'ets_mp_seller` ADD `user_supplier` INT(11) NULL AFTER `payment_verify`';
        $sqls[] = 'UPDATE `'._DB_PREFIX_.'ets_mp_seller` SET user_supplier=3';
    }
    if($sqls)
    {
        foreach($sqls as $sql)
            Db::getInstance()->execute($sql);
    }
    Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'ets_mp_supplier_seller` ( 
        `id_supplier` INT(11) NOT NULL , 
        `id_customer` INT(11) NOT NULL , 
        PRIMARY KEY (`id_supplier`, `id_customer`)) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8');
    return true;
}