{*
* 2007-2018 ETS-Soft
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

<div id="ets-mp-features-content">
    {if $product_features}
        {foreach $product_features item='product_feature'}
            <div class="form-group etm-mp-product-feature">
                <div class="row">
                <div class="col-lg-4 form-group">
                    <label>{l s='Feature' mod='ets_marketplace'}</label>
                    <div>
                        <select name="id_features[]" class="id_features">
                            <option value="0">{l s='Choose a feature' mod='ets_marketplace'}</option>
                            {if $features}
                                {foreach from=$features item='feature'}
                                    <option class="id_feature" {if $product_feature.id_feature==$feature.id_feature} selected="selected"{/if} value="{$feature.id_feature|intval}">{$feature.name|escape:'html':'UTF-8'}</option>
                                {/foreach}
                            {/if}
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 form-group">
                    <label>{l s='Pre-defined value' mod='ets_marketplace'}</label>
                    <div>
                        <select class="id_feature_values" name="id_feature_values[]" {if !$product_feature.feature_values}disabled="disabled"{/if}>
                            <option value="0">{l s='Choose a value' mod='ets_marketplace'}</option>
                            {if $features_values}
                                {foreach from=$features_values item='feature_value'}
                                    <option class="id_feature_value" data-id-feature="{$feature_value.id_feature|intval}" value="{$feature_value.id_feature_value|intval}"{if $product_feature.id_feature_value==$feature_value.id_feature_value} selected="selected"{/if}{if $feature_value.id_feature!=$product_feature.id_feature} style="display:none;"{/if} >{$feature_value.value|escape:'html':'UTF-8'}</option>
                                {/foreach}
                            {/if}
                        </select>
                    </div>
                </div>
                <div class="col-lg-4 form-group">
                    <label>{l s='OR Customized value' mod='ets_marketplace'}</label>
                    <div>
                        <input type="text" name="feature_value_custom[]" value="{if $product_feature.feature_value.custom==1}{$product_feature.feature_value.value|escape:'html':'UTF-8'}{/if}"/>
                    </div>
                </div>
                    <a class="btn tooltip-link ets-mp-delete" title="{l s='Delete' mod='ets_marketplace'}">
                        <i class="fa fa-trash"></i>
                    </a>
                </div>
            </div>
        {/foreach}
    {/if}
</div>
{if $features}
    <div class="row">
        <div class="col-md-4">
            <button id="ets_mp_add_feature_button" class="btn btn-outline-primary sensitive add" type="button">
            <i class="icon-new"></i>
            {l s='Add a feature' mod='ets_marketplace'}
            </button>
        </div>
        <div id="ets-mp-feature-add-content" style="display:none;">
            <div class="form-group etm-mp-product-feature">
                <div class="row">
                    <div class="col-lg-4 form-group">
                        <label>{l s='Feature' mod='ets_marketplace'}</label>
                        <div>
                            <select name="id_features[]" class="id_features">
                                <option value="0">{l s='Choose a feature' mod='ets_marketplace'}</option>
                                {if $features}
                                    {foreach from=$features item='feature'}
                                        <option class="id_feature" value="{$feature.id_feature|intval}">{$feature.name|escape:'html':'UTF-8'}</option>
                                    {/foreach}
                                {/if}
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label>{l s='Pre-defined value' mod='ets_marketplace'}</label>
                        <div>
                            <select class="id_feature_values" name="id_feature_values[]">
                                <option value="0">{l s='Choose a value' mod='ets_marketplace'}</option>
                                {if $features_values}
                                    {foreach from=$features_values item='feature_value'}
                                        <option class="id_feature_value" data-id-feature="{$feature_value.id_feature|intval}" value="{$feature_value.id_feature_value|intval}" style="display:none;">{$feature_value.value|escape:'html':'UTF-8'}</option>
                                    {/foreach}
                                {/if}
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 form-group">
                        <label class="">{l s='OR Customized value' mod='ets_marketplace'}</label>
                        <div>
                            <input type="text" name="feature_value_custom[]" value=""/>
                        </div>
                    </div>
                    <a class="btn tooltip-link ets-mp-delete" title="{l s='Delete' mod='ets_marketplace'}">
                        <i class="fa fa-trash"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
{/if}
