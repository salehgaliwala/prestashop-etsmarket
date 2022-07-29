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
function upgrade_module_3_0_5($object)
{
    $sqls = array();
    if(!$object->checkCreatedColumn('ets_mp_seller_group','auto_upgrade'))
    {
        $sqls[] = 'ALTER TABLE `'._DB_PREFIX_.'ets_mp_seller_group` ADD `auto_upgrade` FLOAT(10,2)';
    }
    if(!$object->checkCreatedColumn('ets_mp_seller_lang','banner_url'))
    {
        $sqls[] = 'ALTER TABLE `'._DB_PREFIX_.'ets_mp_seller_lang` ADD `banner_url` text';
    }
    if(!$object->checkCreatedColumn('ets_mp_registration','banner_url'))
    {
        $sqls[] = 'ALTER TABLE `'._DB_PREFIX_.'ets_mp_registration` ADD `banner_url` text';
    }
    if($sqls)
    {
        foreach($sqls as $sql)
            Db::getInstance()->execute($sql);
    }
    $object->_installFieldConfigDefault();
    return true;
}