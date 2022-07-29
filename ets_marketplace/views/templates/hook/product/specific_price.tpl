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
<div class="card card-block">
    <h4>
        <b>{l s='Specific price conditions' mod='ets_marketplace'}</b>
    </h4>
    <fieldset class="specific_price">
        <label>{l s='For' mod='ets_marketplace'}</label>
        <div class="row form-group">
            <div class="specific_price_id_currency col-lg-3">
                <select name="specific_price_id_currency" class="form-control">
                    <option value="0">{l s='All currencies' mod='ets_marketplace'}</option>
                    {if $currencies}
                        {foreach from=$currencies item='currency'}
                            <option value="{$currency.id_currency|intval}"{if $specific_price->id_currency==$currency.id_currency} selected="selected"{/if} >{$currency.name|escape:'html':'UTF-8'}</option>
                        {/foreach}
                    {/if}
                </select>
            </div>
            <div class="specific_price_id_country col-lg-3">
                <select name="specific_price_id_country" class="form-control">
                    <option value="0">{l s='All countries' mod='ets_marketplace'}</option>
                    {if $countries}
                        {foreach from = $countries item='country'}
                            <option value="{$country.id_country|intval}"{if $specific_price->id_country==$country.id_country} selected="selected"{/if}>{$country.name|escape:'html':'UTF-8'}</option>
                        {/foreach}
                    {/if}
                </select>
            </div>
            <div class="specific_price_id_group col-lg-3">
                <select name="specific_price_id_group" class="form-control">
                    <option value="0">{l s='All group' mod='ets_marketplace'}</option>
                    {if $groups}
                        {foreach from=$groups item='group'}
                            <option value="{$group.id_group|intval}" {if $specific_price->id_group==$group.id_group} selected="selected"{/if}>{$group.name|escape:'html':'UTF-8'}</option>
                        {/foreach}
                    {/if}
                </select>
            </div>
            <div class="specific_price_id_customer col-lg-3">
                <input id="specific_price_id_customer_hide" value="{$specific_price->id_customer|intval}" type="hidden" name="specific_price_id_customer" />
                <input type="text" placeholder="{l s='All customers' mod='ets_marketplace'}" value="{if $specific_price->id_customer}{$specific_price->id_customer|intval}{/if}" id="specific_price_id_customer" />
                {if $specific_price->id_customer}
                    <div class="customer_selected">{$specific_price_customer->firstname|escape:'html':'UTF-8'}&nbsp;{$specific_price_customer->lastname|escape:'html':'UTF-8'}&nbsp;<span class="delete_customer_search">delete</span></div>
                {/if}
            </div>
        </div>
        <div class="row">
            <div id="specific-price-combination-selector" class="col-lg-6 ">
                <fieldset class="form-group">
                    <label>{l s='Combinations' mod='ets_marketplace'}</label>
                    <select name="specific_price_id_product_attribute">
                        <option value="0">{l s='Apply to all combinations' mod='ets_marketplace'}</option>
                        {if $productAttributes}
                            {foreach from = $productAttributes item = 'productAttribute'}
                                <option value="{$productAttribute.id_product_attribute|intval}"{if $specific_price->id_product_attribute==$productAttribute.id_product_attribute} selected="selected"{/if} >{$productAttribute.name_attribute|escape:'html':'UTF-8'}</option>
                            {/foreach}
                        {/if}
                    </select>
                </fieldset>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <fieldset class="form-group">
                    <label>{l s='Available from' mod='ets_marketplace'}</label>
                    <div class="input-group datepicker">
                        <input id="" class="form-control" name="specific_price_from" placeholder="YYYY-MM-DD" value="{$specific_price->from|escape:'html':'UTF-8'}" type="text" />
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="icon icon-date"></i>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="col-lg-3">
                <fieldset class="form-group">
                    <label>{l s='Available to' mod='ets_marketplace'}</label>
                    <div class="input-group datepicker">
                        <input id="" class="form-control" name="specific_price_to" placeholder="YYYY-MM-DD" value="{$specific_price->to|escape:'html':'UTF-8'}" type="text" />
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <i class="icon icon-date"></i>
                            </div>
                        </div>
                    </div>
                </fieldset>
            </div>
            <div class="col-md-2">
                <fieldset class="form-group">
                    <label>{l s='Starting at' mod='ets_marketplace'}</label>
                    <div class="input-group">
                        <input id="specific_price_from_quantity" class="form-control" name="specific_price_from_quantity" value="{if $specific_price->from_quantity}{$specific_price->from_quantity|intval}{else}1{/if}" type="text" />
                        <div class="input-group-append">
                            <span class="input-group-text">{l s='Unit(s)' mod='ets_marketplace'}</span>
                        </div>
                    </div>
                </fieldset>
            </div>
        </div>
        <br />
        <h4>
            <b>{l s='Impact on price' mod='ets_marketplace'}</b>
        </h4>
        <div class="row">
            <div class="col-lg-3">
                <fieldset class="form-group">
                    <label>{l s='Product price (tax excl.)' mod='ets_marketplace'}</label>
                    <div class="input-group money-type">
                        <div class="input-group-prepend">
                            <span class="input-group-text">{$default_currency->sign|escape:'html':'UTF-8'} </span>
                        </div>
                        <input type="text" name="specific_price_product_price" {if !$specific_price->price || $specific_price->price==-1}disabled="disabled"{/if} value="{if $specific_price->price && $specific_price->price!=-1}{$specific_price->price|floatval}{/if}" />
                    </div>
                </fieldset>
            </div>
            <div class="col-md-3">
                <fieldset class="form-group specific_price_leave_bprice">
                    <label>&nbsp;</label>
                    <div class="checkbox">
                        <label>
                            <input id="specific_price_leave_bprice" name="specific_price_leave_bprice" value="1"{if !$specific_price->price || $specific_price->price==-1} checked="checked"{/if} type="checkbox" />
                            {l s='Leave initial price' mod='ets_marketplace'}
                        </label>
                    </div>
                </fieldset>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3">
                <fieldset class="form-group">
                    <label class="required">{l s='Apply a discount of' mod='ets_marketplace'}</label>
                    <div class="input-group money-type">
                          <div class="input-group-prepend">
                                <span class="input-group-text">{if $specific_price->reduction_type=='amount' || !$specific_price->reduction_type}{$default_currency->sign|escape:'html':'UTF-8'}{else}%{/if} </span>
                          </div>
                          <input id="specific_price_sp_reduction" class="form-control" name="specific_price_sp_reduction" value="{if $specific_price->reduction_type=='amount'}{$specific_price->reduction|escape:'html':'UTF-8'}{else}{$specific_price->reduction*100|escape:'html':'UTF-8'}{/if}" type="text" />
                    </div>
                </fieldset>
            </div>
            <div class="col-lg-3">
                <fieldset class="form-group">
                    <label class="xs-hide">&nbsp;</label>
                    <select id="specific_price_sp_reduction_type" name="specific_price_sp_reduction_type" class="custom-select">
                        <option value="amount"{if $specific_price->reduction_type=='amount'} selected="selected"{/if}>{$default_currency->sign|escape:'html':'UTF-8'}</option>
                        <option value="percentage"{if $specific_price->reduction_type=='percentage'} selected="selected"{/if} >%</option>
                    </select>
                </fieldset>
            </div>
            <div class="col-lg-3">
                <fieldset class="form-group">
                    <label class="xs-hide">&nbsp;</label>
                    <select id="specific_price_sp_reduction_tax" name="specific_price_sp_reduction_tax" class="custom-select" style="">
                        <option value="0"{if $specific_price->reduction_tax==0} selected="selected"{/if}>{l s='Tax excluded' mod='ets_marketplace'}</option>
                        <option value="1"{if $specific_price->reduction_tax==1} selected="selected"{/if}>{l s='Tax included' mod='ets_marketplace'}</option>
                    </select>
                </fieldset>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12 text-sm-right">
                <button type="button" id="specific_price_cancel" name="specific_price_cancel" class="btn-outline-secondary js-cancel btn">{l s='Cancel' mod='ets_marketplace'}</button>
                <input type="hidden" value="1" name="submitSavePecificPrice" />
                <input type="hidden" name="id_specific_price" id="id_specific_price" value="{$specific_price->id|intval}" />
                <button type="button" id="specific_price_save" name="specific_price_save" class="btn-outline-primary js-save btn">{l s='Apply' mod='ets_marketplace'}</button>
            </div>
            <div class="clearfix"></div>
        </div>
    </fieldset>
</div>