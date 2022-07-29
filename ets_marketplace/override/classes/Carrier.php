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
 
class Carrier extends CarrierCore
{
    public static function getAvailableCarrierList(Product $product, $id_warehouse, $id_address_delivery = null, $id_shop = null, $cart = null, &$error = array())
    {
        if($product->getType()== Product::PTYPE_VIRTUAL)
            return parent::getAvailableCarrierList($product, $id_warehouse, $id_address_delivery, $id_shop, $cart, $error);
        static $ps_country_default = null;

        if ($ps_country_default === null) {
            $ps_country_default = Configuration::get('PS_COUNTRY_DEFAULT');
        }

        if (null === $id_shop) {
            $id_shop = Context::getContext()->shop->id;
        }
        if (null === $cart) {
            $cart = Context::getContext()->cart;
        }

        if (null === $error || !is_array($error)) {
            $error = array();
        }

        $id_address = (int) ((null !== $id_address_delivery && $id_address_delivery != 0) ? $id_address_delivery : $cart->id_address_delivery);
        if ($id_address) {
            $id_zone = Address::getZoneById($id_address);

            // Check the country of the address is activated
            if (!Address::isCountryActiveById($id_address)) {
                return array();
            }
        } else {
            $country = new Country($ps_country_default);
            $id_zone = $country->id_zone;
        }

        // Does the product is linked with carriers?
        $cache_id = 'Carrier::getAvailableCarrierList_' . (int) $product->id . '-' . (int) $id_shop;
        if (!Cache::isStored($cache_id)) {
            $query = new DbQuery();
            $query->select('id_carrier');
            $query->from('product_carrier', 'pc');
            $query->innerJoin(
                'carrier',
                'c',
                'c.id_reference = pc.id_carrier_reference AND c.deleted = 0 AND c.active = 1'
            );
            $query->where('pc.id_product = ' . (int) $product->id);
            $query->where('pc.id_shop = ' . (int) $id_shop);

            $carriers_for_product = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
            Cache::store($cache_id, $carriers_for_product);
        } else {
            $carriers_for_product = Cache::retrieve($cache_id);
        }
        // by ets_marketplace
        $sql = 'SELECT id_carrier FROM `'._DB_PREFIX_.'carrier` c
        LEFT JOIN `'._DB_PREFIX_.'ets_mp_carrier_seller` cs ON (cs.id_carrier_reference=c.id_reference)
        WHERE c.deleted=0 AND c.active=1';
        if($id_customer = (int)Db::getInstance()->getValue('SELECT id_customer FROM `'._DB_PREFIX_.'ets_mp_seller_product` WHERE id_product="'.(int)$product->id.'"'))
        {
            if(!Configuration::get('ETS_MP_SELLER_CREATE_SHIPPING') && !Configuration::get('ETS_MP_SELLER_USER_GLOBAL_SHIPPING'))
                return array();
            if(!Configuration::get('ETS_MP_SELLER_CREATE_SHIPPING'))
                $carriers_for_seller = Db::getInstance()->executeS($sql.' AND cs.id_carrier_reference is NULL');
            elseif(($seller = Ets_mp_seller::_getSellerByIdCustomer($id_customer)) && Validate::isLoadedObject($seller))
            {
                if(!Configuration::get('ETS_MP_SELLER_USER_GLOBAL_SHIPPING'))
                    $carriers_for_seller = Db::getInstance()->executeS($sql.' AND cs.id_customer="'.(int)$seller->id_customer.'"');
                elseif($seller->user_shipping==1)
                    $carriers_for_seller = Db::getInstance()->executeS($sql.' AND cs.id_carrier_reference is NULL');
                elseif($seller->user_shipping==3)
                    $carriers_for_seller = Db::getInstance()->executeS($sql.' AND (cs.id_customer="'.(int)$seller->id_customer.'" OR cs.id_carrier_reference is NULL)');
                else
                   $carriers_for_seller = Db::getInstance()->executeS($sql.' AND cs.id_customer="'.(int)$seller->id_customer.'"'); 
            }
            if(!$carriers_for_seller)
                return array();
        }
        else
        {
            $carriers_for_seller = Db::getInstance()->executeS($sql.' AND cs.id_carrier_reference is NULL');
            $sql = 'SELECT id_carrier FROM `'._DB_PREFIX_.'carrier` c
            INNER JOIN `'._DB_PREFIX_.'ets_mp_carrier_seller` cs ON (cs.id_carrier_reference=c.id_reference)
            WHERE c.deleted=0 AND c.active=1';
            if(!$carriers_for_seller && Db::getInstance()->getValue($sql))
                return array();
        }
        if($carriers_for_seller)
        {
            $products = $cart->getProducts(false, false, null, true);
            foreach($carriers_for_seller as $index=> &$carrier_for_seller)
            {
                if(($price = $cart->getPackageShippingCost($carrier_for_seller['id_carrier'],true,null,self::getProductsBySellerInCart($products,$product->id),$id_zone))===false)   
                   unset($carriers_for_seller[$index]);
                else
                    $carrier_for_seller['price_shipping'] = $price;
                    
            }
            unset($carrier_for_seller);
            if(!$carriers_for_seller)
                return array();
        }
        if($carriers_for_product)
        {
            $array1 = array();
            if($carriers_for_seller)
            {
                foreach($carriers_for_seller as $carrier_for_seller)
                    $array1[] = $carrier_for_seller['id_carrier'];
            }
            $array2 = array();
            if($carriers_for_product)
            {
                foreach($carriers_for_product as $carrier_for_product)
                    $array2[] = $carrier_for_product['id_carrier'];
            }
            $array_intersect = array_intersect($array1,$array2); 
            if($array_intersect)
            {
                foreach($array_intersect as $id_carrier)
                {
                    $carriers_for_product[] = array(
                        'id_carrier' =>$id_carrier
                    );
                }
            }
            else
                $carriers_for_product=array();
        }  
        else
            $carriers_for_product = $carriers_for_seller;
        // end by ets_marketplace
        $carrier_list = array();
        if (!empty($carriers_for_product)) {
            //the product is linked with carriers
            foreach ($carriers_for_product as $carrier) { //check if the linked carriers are available in current zone
                if (Carrier::checkCarrierZone($carrier['id_carrier'], $id_zone)) {
                    $carrier_list[$carrier['id_carrier']] = $carrier['id_carrier'];
                }
            }
            if (empty($carrier_list)) {
                return array();
            }//no linked carrier are available for this zone
        }

        // The product is not directly linked with a carrier
        // Get all the carriers linked to a warehouse
        if ($id_warehouse) {
            $warehouse = new Warehouse($id_warehouse);
            $warehouse_carrier_list = $warehouse->getCarriers();
        }

        $available_carrier_list = array();
        $cache_id = 'Carrier::getAvailableCarrierList_getCarriersForOrder_' . (int) $id_zone . '-' . (int) $cart->id;
        if (!Cache::isStored($cache_id)) {
            $customer = new Customer($cart->id_customer);
            $carrier_error = array();
            $carriers = Carrier::getCarriersForOrder($id_zone, $customer->getGroups(), $cart, $carrier_error);
            Cache::store($cache_id, array($carriers, $carrier_error));
        } else {
            list($carriers, $carrier_error) = Cache::retrieve($cache_id);
        }

        $error = array_merge($error, $carrier_error);

        foreach ($carriers as $carrier) {
            $available_carrier_list[$carrier['id_carrier']] = $carrier['id_carrier'];
        }

        if ($carrier_list) {
            $carrier_list = array_intersect($available_carrier_list, $carrier_list);
        } else {
            $carrier_list = $available_carrier_list;
        }

        if (isset($warehouse_carrier_list)) {
            $carrier_list = array_intersect($carrier_list, $warehouse_carrier_list);
        }

        $cart_quantity = 0;
        $cart_weight = 0;

        foreach ($cart->getProducts(false, false) as $cart_product) {
            if ($cart_product['id_product'] == $product->id) {
                $cart_quantity += $cart_product['cart_quantity'];
            }
            if (isset($cart_product['weight_attribute']) && $cart_product['weight_attribute'] > 0) {
                $cart_weight += ($cart_product['weight_attribute'] * $cart_product['cart_quantity']);
            } else {
                $cart_weight += ($cart_product['weight'] * $cart_product['cart_quantity']);
            }
        }

        if ($product->width > 0 || $product->height > 0 || $product->depth > 0 || $product->weight > 0 || $cart_weight > 0) {
            foreach ($carrier_list as $key => $id_carrier) {
                $carrier = new Carrier($id_carrier);

                // Get the sizes of the carrier and the product and sort them to check if the carrier can take the product.
                $carrier_sizes = array((int) $carrier->max_width, (int) $carrier->max_height, (int) $carrier->max_depth);
                $product_sizes = array((int) $product->width, (int) $product->height, (int) $product->depth);
                rsort($carrier_sizes, SORT_NUMERIC);
                rsort($product_sizes, SORT_NUMERIC);

                if (($carrier_sizes[0] > 0 && $carrier_sizes[0] < $product_sizes[0])
                    || ($carrier_sizes[1] > 0 && $carrier_sizes[1] < $product_sizes[1])
                    || ($carrier_sizes[2] > 0 && $carrier_sizes[2] < $product_sizes[2])) {
                    $error[$carrier->id] = Carrier::SHIPPING_SIZE_EXCEPTION;
                    unset($carrier_list[$key]);
                }

                if ($carrier->max_weight > 0 && ($carrier->max_weight < $product->weight * $cart_quantity || $carrier->max_weight < $cart_weight)) {
                    $error[$carrier->id] = Carrier::SHIPPING_WEIGHT_EXCEPTION;
                    unset($carrier_list[$key]);
                }
            }
        }

        return $carrier_list;
    }
    public static function getProductsBySellerInCart($products,$id_product)
    {
        $array = array();
        $id_customer = Db::getInstance()->getValue('SELECT id_customer FROM `'._DB_PREFIX_.'ets_mp_seller_product` WHERE id_product="'.(int)$id_product.'"');
        if($products)
        {
            foreach($products as $product)
            {
                $id_customer2 = Db::getInstance()->getValue('SELECT id_customer FROM `'._DB_PREFIX_.'ets_mp_seller_product` WHERE id_product="'.(int)$product['id_product'].'"');
                if($id_customer==$id_customer2)
                    $array[]= $product;
            }
        }
        return $array;
    }
}