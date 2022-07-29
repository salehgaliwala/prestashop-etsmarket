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

class Cart extends CartCore
{
    public function getPackageShippingCost($id_carrier = null, $use_tax = true, Country $default_country = null, $product_list = null, $id_zone = null)
    {
        if (null === $product_list) {
            $products = $this->getProducts(false, false, null, true);
        } else {
            $products = $product_list;
        }
        $marketplace = Module::getInstanceByName('ets_marketplace');
        if($sellers = $marketplace->checkMultiSellerProductList($products))
        {
            $shipping_cost = 0;
            foreach($sellers as $seller)
            {
                $is_virtual= true;
                if($seller)
                {
                    foreach($seller as $p)
                    {
                        if(!$p['is_virtual'])
                        {
                            $is_virtual = false;
                        }
                    }
                }
                if(($price=parent::getPackageShippingCost($id_carrier,$use_tax,$default_country,$seller,$id_zone))===false && !$is_virtual)
                    return false;
                $shipping_cost += $is_virtual ? 0 : ( $price? :0);
            }
            return $shipping_cost;
        }
        return parent::getPackageShippingCost($id_carrier,$use_tax,$default_country,$products,$id_zone);
    }
    public function getPackageList($flush = false)
    {
        $final_package_list = parent::getPackageList($flush);
        if($final_package_list)
        {
            foreach($final_package_list as $final_packages)
            {
                foreach($final_packages as $final_package)
                {
                    if(!$final_package['carrier_list'] || ($final_package['carrier_list'] && isset($final_package['carrier_list'][0]) && $final_package['carrier_list'][0]===0))
                    {  
                        return array();
                    }
                }
                
            }
        }
        return $final_package_list;
    }
    public function getDeliveryOptionList(Country $default_country = null, $flush = false)
    {
        $delivery_option_list = parent::getDeliveryOptionList($default_country,$flush);
        if($delivery_option_list)
        {
            if(version_compare(_PS_VERSION_, '1.7', '>='))
            {
                foreach($delivery_option_list as &$option_list)
                {
                    foreach($option_list as $key => &$option)
                    {
                       if($option['carrier_list']) 
                       {
                            foreach($option['carrier_list'] as &$carrier)
                            {
                                $list_carriers = explode(',',trim($key,','));
                                if(count($list_carriers)>=2)
                                {
                                    $name = '';
                                    $delay = array();
                                    $languages = Language::getLanguages(true);
                                    foreach($list_carriers as $carrier_id)
                                    {
                                        $objCarrier = new Carrier($carrier_id);
                                        $name .= $objCarrier->name.' ('.Tools::displayPrice($this->getPackageShippingCost($carrier_id,false,null,$carrier['product_list'])).'), ';
                                        foreach($languages as $language)
                                        {
                                            if(!isset($delay[$language['id_lang']]))
                                                $delay[$language['id_lang']] = '';
                                            if($objCarrier->delay[$language['id_lang']])
                                                $delay[$language['id_lang']] .= $objCarrier->name.': '.$objCarrier->delay[$language['id_lang']].', ';
                                        }
                                        
                                    }
                                    foreach($languages as $language)
                                    {
                                        $delay[$language['id_lang']] = trim($delay[$language['id_lang']],', ');
                                    }
                                    $carrier['instance']->name = trim($name,', ');
                                    $carrier['instance']->delay = $delay;
                                }
                                
                            }
                       }
                    }
                }
            }
            else
            {
                foreach($delivery_option_list as &$option_list)
                {
                    foreach($option_list as $key => &$option)
                    {
                       if($option['carrier_list']) 
                       {
                            foreach($option['carrier_list'] as &$carrier)
                            {
                                if($carrier['product_list'])
                                {
                                    foreach($carrier['product_list'] as &$product)
                                    {
                                        $carrier_list = array();
                                        if($product['carrier_list'])
                                        {
                                            foreach($product['carrier_list'] as $id)
                                                $carrier_list[] = $id;
                                        }
                                        $product['carrier_list'] = $carrier_list;
                                    }
                                }
                            }
                       }
                    }
                }
            }
            
        }
        return $delivery_option_list;
    }
}