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
<script type="text/javascript">
    var confirm_withdraw ='{l s='Please confirm that you want to withdraw' mod='ets_marketplace' js=1}'; 
</script>
<div class="row">
    <div class="ets_mp_content_left col-lg-3">
        {hook h='displayMPLeftContent'}
    </div>
    <div class="ets_mp_content_left col-lg-9">
        <div class="panel">
        <div class="row">
            <div class="col-md-12">
                <h3 class="fs-16 text-uppercase mb-15">{l s='Submit withdrawal request' mod='ets_marketplace'}</h3>
                <div class="payment-info">
                    <p class="mb-0">
                        {l s='Withdrawal method:' mod='ets_marketplace'}
                        <strong>{$paymentMethod->title|escape:'html':'UTF-8'}</strong>
                    </p>
                    <p class="mb-0">
                        {l s='Fee:' mod='ets_marketplace'}
                        <strong>{if $paymentMethod->fee_type!='NO_FEE'}{$fee_payment|escape:'html':'UTF-8'}{else}{l s='Free' mod='ets_marketplace'}{/if}</strong>
                    </p>
                    {if $paymentMethod->estimated_processing_time}
                        <p class="mb-0">{l s='Estimated processing time:' mod='ets_marketplace'} {$paymentMethod->estimated_processing_time|intval} {if $paymentMethod->estimated_processing_time >1}{l s='days' mod='ets_marketplace'}{else}{l s='day' mod='ets_marketplace'}{/if}</p>
                    {/if}
                    <p class="mb-0">
                        {l s='Balance available for withdrawal:' mod='ets_marketplace'}
                        <strong>{Tools::displayPrice($total_commission,$currency_default)|escape:'html':'UTF-8'}</strong>
                    </p>
                    {if $paymentMethod->description}
                        <p>{l s='Description:' mod='ets_marketplace'} {$paymentMethod->description|nl2br nofilter}</p>
                    {/if}
                    <p class="mb-15">{l s='Please fill in the fields below with required information then submit your withdrawal request.' mod='ets_marketplace'}</p>
                </div>
            </div>
        </div>
        {if $paymentMethod->enable!=1 || $paymentMethod->deleted==1 || !$payment_fields}
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-info alert-error">
                        <p class="mb-0">{l s='We\'re sorry! This payment method is not available, please select other method' mod='ets_marketplace'}</p>
                    </div>
                </div>
            </div>
        {else}
            {if $total_commission <=0}
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info alert-warning">
                            <p class="mb-0">{l s='Withdrawal is not available. You are required to have positive balance in order to submit your withdrawal request.' mod='ets_marketplace'}</p>

                        </div>
                    </div>
                </div>
            {elseif $ETS_MP_BALANCE_REQUIRED_FOR_WITHDRAW>0 && $total_commission < $ETS_MP_BALANCE_REQUIRED_FOR_WITHDRAW}
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info alert-warning">
                            <p class="mb-0">{l s='Withdrawal is not available. You are required to have at least' mod='ets_marketplace'} {$ETS_MP_BALANCE_REQUIRED_FOR_WITHDRAW|escape:'html':'UTF-8'}{l s='in your "Available balance for withdrawal" in order to be able to submit your withdrawal request' mod='ets_marketplace'}</p>
                        </div>
                    </div>
                </div>
            {elseif $withdraw_one_only}
                <div class="row">
                    <div class="col-md-12">
                        <div class="alert alert-info alert-warning">
                            <p class="mb-0">{l s=' Your last withdrawal request is pending to be processed. Please wait for the last request to be processed before submitting new one' mod='ets_marketplace'}</p>
                        </div>
                    </div>
                </div>
            {else}
                {if $paymentMethod->fee_type=='FIXED' && $total_commission<=$paymentMethod->fee_fixed}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="alert alert-info alert-warning">
                                <p class="mb-0">{l s='Withdrawal is not available. You are required to have at least' mod='ets_marketplace'} {$fee_payment|escape:'html':'UTF-8'} {l s='in your "Available balance for withdrawal" in order to be able to submit your withdrawal request' mod='ets_marketplace'}</p>
                            </div>
                        </div>
                    </div>
                {else}
                    <div class="row">
                        <div class="col-md-12">
                            <form class="ets_mp-withdraw-form" novalidate="" method="post" action="" autocomplete="off" enctype="multipart/form-data">
                                <div class="ets_mp-box-content-withdraw">
                                    <div class="form-panel">
                                        <div class="form-panel-header">
                                            <h4 class="form-panel-title">{l s='Amount to withdraw' mod='ets_marketplace'}</h4>
                                        </div>
                                        <div class="form-panel-body mb-5">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <div class="form-group">
                                                        <div class="input-group display-flex mb-5">
                                                            <input name="amount_withdraw" class="form-control" id="amount_withdraw" value="{if $amount_withdraw!=''}{$amount_withdraw|intval}{/if}" placeholder="0.00" type="text" />
                                                            <div class="input-group-append">
                                                                <span class="input-group-text">{$currency_default->sign|escape:'html':'UTF-8'}</span>
                                                            </div>
                                                        </div>
                                                    <span class="help-block"></span>
                                                    <p class="ets_ws-note mb-20 desc">
                                                        {if $MAX_WITHDRAW || $MIN_WITHDRAW}
                                                            {l s='Note:' mod='ets_marketplace'}{if $MIN_WITHDRAW} {l s='Min amount' mod='ets_marketplace'} {$MIN_WITHDRAW|escape:'html':'UTF-8'}.{/if}{if $MAX_WITHDRAW} {l s='Max amount' mod='ets_marketplace'} {$MAX_WITHDRAW|escape:'html':'UTF-8'}.{/if}
                                                        {/if}
                                                    </p>
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="ets_mp-withdraw-boxes">
                                                        <h3>
                                                            <small>{l s='You will receive:' mod='ets_marketplace'}</small>
                                                            <span class="price">{Tools::displayPrice(0,$currency_default)|escape:'html':'UTF-8'}</span>
                                                        </h3>
                                                        <p class="ets_mp-note desc">
                                                            {l s='Note: Withdrawal fee has been calculated.' mod='ets_marketplace'}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-panel">
                                        <div class="form-panel-header">
                                            <h4 class="form-panel-title">{l s='Additional information' mod='ets_marketplace'}</h4>
                                        </div>
                                        <div class="form-panel-body">
                                            <div class="row">
                                                <div class="col-md-8 col-sm-full">
                                                    <div class="form-payment-fields">
                                                        {if $payment_fields}
                                                            {foreach from=$payment_fields item='payment_field'}
                                                                <div class="row">
                                                                    <div class="form-group{if isset($payment_field.error) && $payment_field.error} has-error{/if}">
                                                                        <label class="col-md-4 mt-5 pr-10{if $payment_field.required} required{/if}" for="payment_field_{$payment_field.id_ets_mp_payment_method_field|intval}">{$payment_field.title|escape:'html':'UTF-8'}
                        
                                                                        </label>
                                                                        <div class="col-md-7 p-0">
                                                                            {if $payment_field.type=='textarea'}
                                                                                <textarea id="payment_field_{$payment_field.id_ets_mp_payment_method_field|intval}" name="payment_field_{$payment_field.id_ets_mp_payment_method_field|intval}" required="" class="form-control">{if isset($payment_field.value) && $payment_field.value}{$payment_field.value|escape:'html':'UTF-8'}{/if}</textarea>
                                                                            {elseif $payment_field.type=='file'}
                                                                                <div class="custom-file">
                                                                                    <input id="payment_field_{$payment_field.id_ets_mp_payment_method_field|intval}" name="payment_field_{$payment_field.id_ets_mp_payment_method_field|intval}" type="file" class="form-control custom-file-input" />
                                                                                    <label class="custom-file-label" for="payment_field_{$payment_field.id_ets_mp_payment_method_field|intval}" data-browser="{l s='Browse' mod='ets_marketplace'}">
                                                                                       {l s='Upload your invoice' mod='ets_marketplace'}
                                                                                    </label>
                                                                                </div>
                                                                            {else}                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                   
                                                                                <input id="payment_field_{$payment_field.id_ets_mp_payment_method_field|intval}" name="payment_field_{$payment_field.id_ets_mp_payment_method_field|intval}" required="" class="form-control" value="{if isset($payment_field.value) && $payment_field.value}{$payment_field.value|escape:'html':'UTF-8'}{/if}" type="text" />
                                                                            {/if}
                                                                            {if isset($payment_field.error) && $payment_field.error}
                                                                                <span class="error-block" style="color:red">{$payment_field.error|escape:'html':'UTF-8'}</span>
                                                                            {/if}
                                                                            {if isset($payment_field.description) && $payment_field.description}
                                                                                <span class="help-block">{$payment_field.description|nl2br nofilter}</span>
                                                                            {/if}                                                                                                                                                                                                                                                
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            {/foreach}
                                                        {/if}
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <p class="note mb-35">
                                                                {l s='*Note: Please enter the required information above exactly to receive your funds. Wrong information may result in losing the money that you\'re withdrawing' mod='ets_marketplace'}
                                                            </p>
                                                            <div class="form-buttons">
                                                                <input value="1" name="ets_mp_withdraw_submit" type="hidden" />
                                                                <button type="submit" class="btn btn-primary ets_mp-button ets_mp-submit-request" disabled="">{l s='Withdraw Funds' mod='ets_marketplace'}</button>
                                                                <a href="{$link->getModuleLink('ets_marketplace','withdraw')|escape:'html':'UTF-8'}" class="ets_mp-button ets_mp-button-default ets_mp-button-cancel btn btn-default btn-secondary">{l s='Cancel' mod='ets_marketplace'}</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                {/if}
            {/if}
        </div>
        {/if}
    </div>
</div>