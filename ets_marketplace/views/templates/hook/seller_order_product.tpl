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
<div class="ets_mp_extra_product_order" style="display:none;">
    <div class="panel seller_info">
        <div class="panel-header">
            <h3 class="panel-title"><i class="fa fa-address-card"></i>&nbsp;{l s='Seller info' mod='ets_marketplace'}
            </h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-horizontal label_bold">
                        <div class="row">
            				<label class="control-label col-lg-4 col-sm-6">{l s='Full name' mod='ets_marketplace'}</label>
            				<div class="col-lg-8 col-sm-6">
            					<p class="form-control-static"> <a href="{$link->getAdminLink('AdminMarketPlaceSellers')|escape:'html':'UTF-8'}&viewseller=1&id_seller={$seller->id|intval}" title="{l s='View seller' mod='ets_marketplace'}" target="_blank">{if $seller->seller_name}{$seller->seller_name|escape:'html':'UTF-8'}{else}{$customer->firstname|escape:'html':'UTF-8'}&nbsp;{$customer->lastname|escape:'html':'UTF-8'}{/if}</a></p>
            				</div>
            			</div>
                        <div class="row">
                            <label class="control-label col-lg-4 col-sm-6">{l s='Email' mod='ets_marketplace'}</label>
                            <div class="col-lg-8 col-sm-6">
                                <p class="form-control-static">{if $seller->seller_email}{$seller->seller_email|escape:'html':'UTF-8'}{else}{$customer->email|escape:'html':'UTF-8'}{/if}</p>
                            </div>
                        </div>
                        <div class="row">
            				<label class="control-label col-lg-4 col-sm-6">{l s='Registration date' mod='ets_marketplace'}</label>
            				<div class="col-lg-8 col-sm-6">
            					<p class="form-control-static">{dateFormat date=$seller->date_add full=1}</p>
            				</div>
            			</div>
                        <div class="row info_status">
                            <label class="control-label col-lg-4 col-sm-6">{l s='Status' mod='ets_marketplace'}</label>
                            <div class="col-lg-8 col-sm-6">
                                {if $seller->active==-2}
                                    <span class="ets_mp_status expired">{l s='Expired' mod='ets_marketplace'}</span>
                                {elseif $seller->active==-1}
                                    <span class="ets_mp_status pending">{l s='Pending' mod='ets_marketplace'}</span>
                                {elseif $seller->active==0}
                                    <span class="ets_mp_status disabled">{l s='Disabled' mod='ets_marketplace'}</span>
                                {elseif $seller->active==1}
                                    <span class="ets_mp_status approved">{l s='Approved' mod='ets_marketplace'}</span>
                                {/if}
                            </div>
                        </div>
                        {if $seller->payment_verify}
                            <div class="row info_status">
                                <label class="control-label col-lg-4 col-sm-6">{l s='Payment verify' mod='ets_marketplace'}</label>
                                <div class="col-lg-8 col-sm-6">
                                    {if $seller->payment_verify==-1}
                                        <span class="ets_mp_status awaiting_payment">{l s='Awaiting payment' mod='ets_marketplace'}</span>
                                    {/if}
                                    {if $seller->payment_verify==1}
                                        <span class="ets_mp_status confirmed_payment">{l s='Seller confirmed payment' mod='ets_marketplace'}</span>
                                    {/if}
                                </div>
                            </div>
                        {/if}
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-horizontal label_bold">
                        <div class="row">
                            <label class="control-label col-lg-6">
                                {l s='Total commission balance' mod='ets_marketplace'}
                                <i class="fa fa-question-circle">
                                    <span class="ets_tooltip" data-tooltip="top">{l s='The remaining amount of commission after converting into voucher, withdrawing, paying for orders or being deducted by marketplace admin' mod='ets_marketplace'}</span>
                                </i>
                            </label>
                            <div class="col-lg-6">
                                {assign var='total_commission_balance' value=$seller->getTotalCommission(1)-$seller->getToTalUseCommission(1)}
                                <p class="form-control-static" id="total_commission">{displayPrice price=$total_commission_balance}</p>
                            </div>
                        </div>
                        <div class="row">
                            <label class="control-label col-lg-6">
                                {l s='Total commission' mod='ets_marketplace'}
                                <i class="fa fa-question-circle">
                                        <span class="ets_tooltip" data-tooltip="top">{l s='Total commission money this seller has earned' mod='ets_marketplace'}</span>
                                    </i>
                            </label>
                            <div class="col-lg-6">
                                <p class="form-control-static" id="total_commission">{displayPrice price=$seller->getTotalCommission(1)}</p>
                            </div> 
                        </div>
                        <div class="row">
            				<label class="control-label col-lg-6">{l s='Withdrawn' mod='ets_marketplace'}
            					<i class="fa fa-question-circle">
                                    <span class="ets_tooltip" data-tooltip="top">{l s='Total amount of commission money this seller has withdrawn' mod='ets_marketplace'}</span>
                                </i>
                            </label>
            				<div class="col-lg-6">
            					<p class="form-control-static" id="total_withdrawn">{displayPrice price=$seller->getToTalUseCommission(1,false,false,true)}</p>
            				</div>
            			</div>
                        <div class="row">
            				<label class="control-label col-lg-6">{l s='Paid for orders' mod='ets_marketplace'}
            					<i class="fa fa-question-circle">
                                    <span class="ets_tooltip" data-tooltip="top">{l s='Total amount of commission money this seller has used to pay for orders' mod='ets_marketplace'}</span>
                                </i>
                            </label>
            				<div class="col-lg-6">
            					<p class="form-control-static" id="total_paid_for_orders">{displayPrice price=$seller->getToTalUseCommission(1,true)}</p>
            				</div>
            			</div>
                        <div class="row">
            				<label class="control-label col-lg-6">{l s='Converted to voucher' mod='ets_marketplace'}
            					<i class="fa fa-question-circle">
                                    <span class="ets_tooltip" data-tooltip="top">{l s='Total amount of commission money this seller has used to convert into vouchers' mod='ets_marketplace'}</span>
                                </i>
                            </label>
            				<div class="col-lg-6">
            					<p class="form-control-static" id="total_convert_to_voucher">{displayPrice price=$seller->getToTalUseCommission(1,false,true)}</p>
            				</div>
            			</div>
                        <div class="row">
            				<label class="control-label col-lg-6">{l s='Total used' mod='ets_marketplace'}
            					<i class="fa fa-question-circle">
                                    <span class="ets_tooltip" data-tooltip="top">{l s='Total amount of commission money this seller has withdrawn, paid for orders, converted into vouchers and commission money deducted by marketplace admin' mod='ets_marketplace'}</span>
                                </i>
                            </label>
            				<div class="col-lg-6">
            					<p class="form-control-static" id="total_commission_used">{displayPrice price=$seller->getToTalUseCommission(1)}</p>
            				</div>
            			</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>