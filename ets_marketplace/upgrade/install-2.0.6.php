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
function upgrade_module_2_0_6($object)
{
    $object->registerHook('displayShoppingCartFooter');
    $object->_installTabs();
    if(!class_exists('Ets_mp_defines'))
        require_once(dirname(__FILE__) . '/../classes/Ets_mp_defines.php');
    $commission_usage_settings = Ets_mp_defines::getInstance()->getFieldConfig('commission_usage_settings');
    if($commission_usage_settings)
    {
        foreach($commission_usage_settings as $setting)
        {
            if(isset($setting['default']))
                Configuration::updateValue($setting['name'],$setting['default']);
        }
    }
    $sqls = array();
    if(!$object->checkCreatedColumn('ets_mp_seller_lang','shop_banner'))
    {
        $sqls[] = 'ALTER TABLE `'._DB_PREFIX_.'ets_mp_seller_lang` ADD `shop_banner` VARCHAR(222) NULL';
    }
    if(!$object->checkCreatedColumn('ets_mp_seller','link_facebook'))
    {
        $sqls[] = 'ALTER TABLE `'._DB_PREFIX_.'ets_mp_seller` ADD `link_facebook` VARCHAR(222) NULL';
    }
    if(!$object->checkCreatedColumn('ets_mp_seller','link_instagram'))
    {
        $sqls[] = 'ALTER TABLE `'._DB_PREFIX_.'ets_mp_seller` ADD `link_instagram` VARCHAR(222) NULL';
    }
    if(!$object->checkCreatedColumn('ets_mp_seller','link_google'))
    {
        $sqls[] = 'ALTER TABLE `'._DB_PREFIX_.'ets_mp_seller` ADD `link_google` VARCHAR(222) NULL';
    }
    if(!$object->checkCreatedColumn('ets_mp_seller','link_twitter'))
    {
        $sqls[] = 'ALTER TABLE `'._DB_PREFIX_.'ets_mp_seller` ADD `link_twitter` VARCHAR(222) NULL';
    }
    if(!$object->checkCreatedColumn('ets_mp_registration','shop_banner'))
    {
        $sqls[] = 'ALTER TABLE `'._DB_PREFIX_.'ets_mp_registration` ADD `shop_banner` VARCHAR(222) NULL';
    }
    if(!$object->checkCreatedColumn('ets_mp_registration','link_facebook'))
    {
        $sqls[] = 'ALTER TABLE `'._DB_PREFIX_.'ets_mp_registration` ADD `link_facebook` VARCHAR(222) NULL';
    }
    if(!$object->checkCreatedColumn('ets_mp_registration','link_instagram'))
    {
        $sqls[] = 'ALTER TABLE `'._DB_PREFIX_.'ets_mp_registration` ADD `link_instagram` VARCHAR(222) NULL';
    }
    if(!$object->checkCreatedColumn('ets_mp_registration','link_google'))
    {
        $sqls[] = 'ALTER TABLE `'._DB_PREFIX_.'ets_mp_registration` ADD `link_google` VARCHAR(222) NULL';
    }
    if(!$object->checkCreatedColumn('ets_mp_registration','link_twitter'))
    {
        $sqls[] = 'ALTER TABLE `'._DB_PREFIX_.'ets_mp_registration` ADD `link_twitter` VARCHAR(222) NULL';
    }
    if($sqls)
    {
        foreach($sqls as $sql)
            Db::getInstance()->execute($sql);
    }
    return true;
}