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
 * needs please, contact us for extra customization service at an affordable price
 *
 * @author ETS-Soft <etssoft.jsc@gmail.com>
 * @copyright  2007-2020 ETS-Soft
 * @license    Valid for 1 website (or project) for each purchase of license
 *  International Registered Trademark & Property of ETS-Soft
 */

class Ets_marketplaceValidationModuleFrontController extends ModuleFrontController
{
    public function postProcess()
    {
        $cart = $this->context->cart;
        if (!Configuration::get('ETS_MP_ENABLED') || !Configuration::get('ETS_MP_ALLOW_BALANCE_TO_PAY') || $cart->id_customer == 0 || $cart->id_address_delivery == 0 || $cart->id_address_invoice == 0 || !$this->module->active)
            Tools::redirect('index.php?controller=order&step=1');
        $authorized = false;
        foreach (Module::getPaymentModules() as $module)
            if ($module['name'] == 'ets_marketplace') {
                $authorized = true;
                break;
            }
        if (!$authorized)
            die($this->module->l('This payment method is not available.', 'validation'));
        $customer = new Customer($cart->id_customer);
        if (!Validate::isLoadedObject($customer))
            Tools::redirect('index.php?controller=order&step=1');
        $currency = $this->context->currency;
        if(($seller= $this->module->_getSeller(true)) && $seller->id_customer == $this->context->customer->id)
        {
            $commission_total_balance = $seller->getTotalCommission(1) - $seller->getToTalUseCommission(1);
            $min_order_pay = (float)Configuration::get('ETS_MP_MIN_BALANCE_REQUIRED_FOR_ORDER');
            $max_order_pay = (float)Configuration::get('ETS_MP_MAX_BALANCE_REQUIRED_FOR_ORDER');
            if($commission_total_balance >0 && (!$min_order_pay || $min_order_pay <= $commission_total_balance) && (!$max_order_pay || $max_order_pay >=$commission_total_balance))
            {
                $cart_total = $cart->getOrderTotal(true, Cart::BOTH);
                $cart_total = Tools::convertPrice($cart_total, null, false);
                if($cart_total <= $commission_total_balance)
                {
                    $mailVars = array(
                        '{reward_owner}' => '',
                        '{reward_amount}' => ''
                    );
                    if ($this->module->validateOrder($cart->id, 2, (float)$cart->getOrderTotal(true, Cart::BOTH), $this->module->l('Pay by commission','validation'), NULL, $mailVars, (int)$currency->id, false, $customer->secure_key)) {
                        Tools::redirect('index.php?controller=order-confirmation&id_cart=' . $cart->id . '&id_module=' . $this->module->id . '&id_order=' . $this->module->currentOrder . '&key=' . $customer->secure_key);
                    }
                }
            }
        }
        Tools::redirect('index.php?controller=order&step=1');

    }
}