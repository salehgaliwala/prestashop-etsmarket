{*
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
* needs, please contact us for extra customization service at an affordable price
*
*  @author ETS-Soft <etssoft.jsc@gmail.com>
*  @copyright  2007-2020 ETS-Soft
*  @license    Valid for 1 website (or project) for each purchase of license
*  International Registered Trademark & Property of ETS-Soft
*}
<div class="panel">
    <div class="ets_mp-voucer-message">
        {if isset($cart_rule) && $cart_rule->id}
            <div class="module_confirmation conf confirm alert alert-success">
                {l s='You have successfully converted ' mod='ets_marketplace'} {Tools::displayPrice($cart_rule->reduction_amount,$currency_default)|escape:'html':'UTF-8'} {l s=' into voucher code:' mod='ets_marketplace'} {$cart_rule->code|escape:'html':'UTF-8'}
                <a href="javascript:void(0)" class="btn btn-success ets_mp-apply-voucher b-radius-3 text-uppercase" data-voucher-code="{$cart_rule->id|intval}">{l s='Apply Voucher code to my cart' mod='ets_marketplace'}</a>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
        {/if}
    </div>
    <div class="ets_mp-form ets_mp-voucher-form mb-40">
        {if $total_commission_can_user >0}
            <p>{l s='You have ' mod='ets_marketplace'} <strong>{Tools::displayPrice($total_commission_can_user,$currency_default)|escape:'html':'UTF-8'}</strong> {l s=' in your balance. It can be converted into voucher code. Fill in required fields below to convert your commission balance into voucher code. Voucher code can be used to checkout your shopping cart.' mod='ets_marketplace'}</p>
            <form action="" method="post">
                <div class="form-group{if isset($error_amount) && $error_amount} has-error{/if}">
                    <label class="fw-b mb-5" for="ets_mp_VOUCHER_AMOUNT">{l s='Amount to convert:' mod='ets_marketplace'}</label>
                    <div class="input-group mb-5">
                        <input class="form-control" name="ets_mp_VOUCHER_AMOUNT" placeholder="0.00" aria-label="0.00" value="{if isset($ets_mp_VOUCHER_AMOUNT)}{$ets_mp_VOUCHER_AMOUNT|escape:'html':'UTF-8'}{/if}" aria-describedby="ets_mp_VOUCHER_AMOUNT" type="text" />
                        <div class="input-group-append">
                        <span class="input-group-text" id="ets_mp_VOUCHER_AMOUNT">{$currency_default->sign|escape:'html':'UTF-8'}</span>
                        </div>
                    </div>
                    {if isset($error_amount) && $error_amount}
                        <span class="help-block">{$error_amount|escape:'html':'UTF-8'}</span>
                    {/if}
                    <p class="ets_mp-note mb-20">
                        {l s='Note: Voucher availability:' mod='ets_marketplace'} <strong> {l s='30 days' mod='ets_marketplace'}</strong>.{if $MIN_VOUCHER} {l s='Min amount to convert' mod='ets_marketplace'} {$MIN_VOUCHER|escape:'html':'UTF-8'}.{/if}{if $MAX_VOUCHER} {l s='Max amount to convert' mod='ets_marketplace'} {$MAX_VOUCHER|escape:'html':'UTF-8'}.{/if}
                    </p>
                </div>
                <input name="ets_mp-submit-voucher" value="1" type="hidden" />
                <button class="btn btn-info text-uppercase b-radius-3 fs-14" type="submit">{l s='Convert now' mod='ets_marketplace'}</button>
            </form>
        {else}
            <div class="alert alert-warning">{l s='Voucher is not available. You are required to have positive balance in order to submit your convert request.' mod='ets_marketplace'}</div>
        {/if}
    </div>
    <div class="ets_mp-voucher-history">
        <h4 class="text-uppercase fs-14 mb-15">{l s='Your voucher codes' mod='ets_marketplace'}</h4>
        <div class="table-responsive">
        <table class="table ets_mp-table-flat">
            <thead>
                <tr>
                    <th>{l s='Code' mod='ets_marketplace'}</th>
                    <th>{l s='Description' mod='ets_marketplace'}</th>
                    <th class="text-center">{l s='Quantity' mod='ets_marketplace'}</th>
                    <th>{l s='Value' mod='ets_marketplace'}</th>
                    <th class="text-center">{l s='Minimum' mod='ets_marketplace'}</th>
                    <th class="text-center">{l s='Cumulative' mod='ets_marketplace'}</th>
                    <th class="text-center">{l s='Expiration date' mod='ets_marketplace'}</th>
                    <th class="text-center">{l s='Status' mod='ets_marketplace'}</th>
                </tr>
            </thead>
            <tbody>
                {if $cart_rules}
                    {foreach from=$cart_rules item='cart_rule'}
                        <tr>
                            <td>{$cart_rule.code|escape:'html':'UTF-8'}</td>
                            <td>{$cart_rule.name|escape:'html':'UTF-8'}</td>
                            <td class="text-center">{$cart_rule.quantity|intval}</td>
                            <td>{Tools::displayPrice($cart_rule.reduction_amount,$currency_default)|escape:'html':'UTF-8'}</td>
                            <td class="text-center">{$cart_rule.voucher_minimal|escape:'html':'UTF-8'}</td>
                            <td class="text-center">{if $cart_rule.cart_rule_restriction}{l s='No' mod='ets_marketplace'}{else}{l s='Yes' mod='ets_marketplace'}{/if}</td>
                            <td class="text-center">{$cart_rule.voucher_date|escape:'html':'UTF-8'}</td>
                            <td class="text-center">
                                {if $cart_rule.status == 1}
                                    <i class="fa fa-lock i-mr-2 text-warning"></i>{l s='Used' mod='ets_marketplace'}
                                {elseif $cart_rule.status == -1}
                                    <i class="fa fa-check text-danger"></i>{l s='Expired' mod='ets_marketplace'}
                                {else}
                                    <i class="fa fa-check text-success"></i>{l s='Available' mod='ets_marketplace'}
                                {/if}
                            </td>
                        </tr>
                    {/foreach}
                {else}
                    <tr><td class="text-center" colspan="8">{l s='No data' mod='ets_marketplace'}</td></tr>
                {/if}
        </tbody>
        </table>
        <div class="paggination" style="text-align: center;">
            {$paggination nofilter}
        </div>
        
        </div>
    </div>
</div>