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

ob_start();
$timerStart = microtime(true);
try {
    $context = Context::getContext();
    if (Tools::isSubmit('logout')) {
        $context->employee->logout();
    }

    if (!isset($context->employee) || !$context->employee->isLoggedBack()) {
        Tools::redirectAdmin('index.php?controller=AdminLogin&redirect='.$_SERVER['REQUEST_URI']);
    }

    $iso = $context->language->iso_code;
    if (file_exists(_PS_TRANSLATIONS_DIR_.$iso.'/errors.php')) {
        include(_PS_TRANSLATIONS_DIR_.$iso.'/errors.php');
    }
    if (file_exists(_PS_TRANSLATIONS_DIR_.$iso.'/fields.php')) {
        include(_PS_TRANSLATIONS_DIR_.$iso.'/fields.php');
    }
    if (file_exists(_PS_TRANSLATIONS_DIR_.$iso.'/admin.php')) {
        include(_PS_TRANSLATIONS_DIR_.$iso.'/admin.php');
    }

    /* Server Params */
    $protocol_link = (Configuration::get('PS_SSL_ENABLED')) ? 'https://' : 'http://';
    $protocol_content = Configuration::get('PS_SSL_ENABLED') ? 'https://' : 'http://';
    $link = new Link($protocol_link, $protocol_content);
    $context->link = $link;
    if (!defined('_PS_BASE_URL_')) {
        define('_PS_BASE_URL_', Tools::getShopDomain(true));
    }
    if (!defined('_PS_BASE_URL_SSL_')) {
        define('_PS_BASE_URL_SSL_', Tools::getShopDomainSsl(true));
    }
    // Change shop context ?
    if (Shop::isFeatureActive() && Tools::getValue('setShopContext') !== false) {
        $context->cookie->shopContext = Tools::getValue('setShopContext');
        $url = parse_url($_SERVER['REQUEST_URI']);
        $query = (isset($url['query'])) ? $url['query'] : '';
        parse_str($query, $parseQuery);
        unset($parseQuery['setShopContext']);
        Tools::redirectAdmin($url['path'] . '?' . http_build_query($parseQuery, '', '&'));
    }

    $context->currency = new Currency(Configuration::get('PS_CURRENCY_DEFAULT'));
    if ($context->employee->isLoggedBack()) {
        $shop_id = '';
        
        Shop::setContext(Shop::CONTEXT_ALL);
        if ($context->cookie->shopContext) {
            $split = explode('-', $context->cookie->shopContext);
            if (count($split) == 2) {
                if ($split[0] == 'g') {
                    if ($context->employee->hasAuthOnShopGroup($split[1])) {
                        Shop::setContext(Shop::CONTEXT_GROUP, $split[1]);
                    } else {
                        $shop_id = $context->employee->getDefaultShopID();
                        Shop::setContext(Shop::CONTEXT_SHOP, $shop_id);
                    }
                } elseif ($context->employee->hasAuthOnShop($split[1])) {
                    $shop_id = $split[1];
                    Shop::setContext(Shop::CONTEXT_SHOP, $shop_id);
                } else {
                    $shop_id = $context->employee->getDefaultShopID();
                    Shop::setContext(Shop::CONTEXT_SHOP, $shop_id);
                }
            }
        }

        // Replace existing shop if necessary
        if (!$shop_id) {
            $context->shop = new Shop(Configuration::get('PS_SHOP_DEFAULT'));
        } elseif ($context->shop->id != $shop_id) {
            $context->shop = new Shop($shop_id);
        }
    }
} catch (PrestaShopException $e) {
    $e->displayMessage();
}
