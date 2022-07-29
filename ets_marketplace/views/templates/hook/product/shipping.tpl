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

<div class="row">
    <div class="col-md-12">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 pb-1" style="display:none">
                    <h2>{l s='Package dimension' mod='ets_marketplace'}</h2>
                    <p class="subtitle" style="margin-bottom: 5px;">{l s='Charge additional shipping costs based on packet dimensions covered here.' mod='ets_marketplace'}</p>
                    {* _ARM_ Hide product weight *}
                    <div class="row hidden-xl-down">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label class="form-control-label">{l s='Width' mod='ets_marketplace'}</label>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text">cm</span>
                                    </div>
                                    <input id="width" class="form-control" name="width" value="{if $product_class->width!=0}{$product_class->width|floatval}{/if}" type="text" />

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label class="form-control-label">{l s='Height' mod='ets_marketplace'}</label>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text">cm</span>
                                    </div>
                                    <input id="height" class="form-control" name="height" value="{if $product_class->height!=0}{$product_class->height|floatval}{/if}" type="text" />

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label class="form-control-label">{l s='Depth' mod='ets_marketplace'}</label>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text">cm</span>
                                    </div>
                                    <input id="depth" class="form-control" name="depth" value="{if $product_class->depth!=0}{$product_class->depth|floatval}{/if}" type="text" />

                                </div>
                            </div>
                        </div>
                        {*<div class="col-lg-3">
                            <div class="form-group">
                                <label class="form-control-label">{l s='Weight' mod='ets_marketplace'}</label>
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <span class="input-group-text">kg</span>
                                    </div>
                                    <input id="weight" class="form-control" name="weight" value="{if $product_class->weight!=0}{$product_class->weight|floatval}{/if}" type="text" />
                                </div>
                            </div>
                        </div>*}
                    </div>
                    {* _ARM_ Add special weight form *}
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="form-control-label">{l s='Weight' mod='ets_marketplace'}</label>
                                <small class="form-text">
                                    {l s='Sélectionner' mod='ets_marketplace'}
                                </small>
                                {assign var="allWeights" value=Weight::getAllSellerWeight(Context::getContext()->customer->id)}
                                {if $allWeights && $allWeights|count > 0}<select id="select-weight" name="weight">{/if}
                                {foreach from=$allWeights item=$weight}
                                    <option data-max="{$weight->max}" value="{$weight->max - 0.01}" {if $product_class->weight < $weight->max && $product_class->weight >= $weight->min}selected{/if}>
                                        {l s='From %d to %d kg (%d CHF)' sprintf=[$weight->min, $weight->max, $weight->price] mod='ets_marketplace'}
                                    </option>
                                    
                                    {*<div class="input-group">
                                        <input type="radio" required id="weight-option-{$weight->max}" name="weight" value="{$weight->max - 0.01}"{if $product_class->weight < $weight->max && $product_class->weight >= $weight->min} checked="checked"{/if}>&nbsp;
                                        <label for="weight-option-{$weight->max}">{l s='From %d to %d kg (%d CHF)' sprintf=[$weight->min, $weight->max, $weight->price] mod='ets_marketplace'}</label>
                                    </div>*}
                                {foreachelse}
                                    <input id="weight" class="form-control" name="weight" value="{if $product_class->weight!=0}{$product_class->weight|floatval}{/if}" type="hidden" />
                                {/foreach}
                                {if $allWeights && $allWeights|count > 0}</select>{/if}
                            </div>
                        </div>
                    </div>
                </div>
                {if $is17}
                    <div class="col-md-12">
                        <div class="form-group" style="margin-bottom: 5px;">
                            <h2>
                                {l s='Delivery Time' mod='ets_marketplace'}
                                <span class="help-box">
                                    <span>
                                        {l s='Display delivery time for a product is advised for merchants selling in Europe to comply with the local laws.' mod='ets_marketplace'}
                                    </span>
                                </span>
                            </h2>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="radio">
                                        <label for="additional_delivery_times_0">
                                            <input id="additional_delivery_times_0" name="additional_delivery_times" value="0" type="radio"{if $product_class->additional_delivery_times==0 && $product_class->id} checked="checked"{/if} />
                                            {l s='None' mod='ets_marketplace'}
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label for="additional_delivery_times_1">
                                            <input id="additional_delivery_times_1" name="additional_delivery_times" value="1" type="radio"{if $product_class->additional_delivery_times==1} checked="checked"{/if} />
                                            {l s='Default delivery time' mod='ets_marketplace'}
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label for="additional_delivery_times_2">
                                            <input id="additional_delivery_times_2" name="additional_delivery_times" value="2" type="radio"{if $product_class->additional_delivery_times==2 || !$product_class->id} checked="checked"{/if} />
                                            {l s='Specify delivery time to this product' mod='ets_marketplace'}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 pb-1">
                        <div class="row">
                            <div class="col-lg-5">
                                <div class="form-group">
                                    <label class="px-0 control-label">
                                        {l s='Delivery time of in-stock products:' mod='ets_marketplace'}
                                    </label>
                                    {if $languages && count($languages)>1}
                                        <div class="form-group">
                                            {foreach from=$languages item='language'}
                                                <div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang!=$id_lang_default} style="display:none;"{/if}>
                                                    <div class="col-lg-10">
                                                        {if isset($valueFieldPost)}
                                                            {assign var='value_text' value=$valueFieldPost['delivery_in_stock'][$language.id_lang]}
                                                        {/if}
                                                        <input placeholder="{l s='Delivered within 3-4 days' mod='ets_marketplace'}" class="form-control" name="delivery_in_stock_{$language.id_lang|intval}" value="{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{/if}"  type="text" />
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="toggle_form">
                                                        <button class="btn btn-default dropdown-toggle" type="button" tabindex="-1" data-toggle="dropdown">
                                                        {$language.iso_code|escape:'html':'UTF-8'}
                                                        <i class="icon-caret-down"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            {foreach from=$languages item='lang'}
                                                                <li>
                                                                    <a class="hideOtherLanguage" href="#" tabindex="-1" data-id-lang="{$lang.id_lang|intval}">{$lang.name|escape:'html':'UTF-8'}</a>
                                                                </li>
                                                            {/foreach}
                                                        </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            {/foreach}
                                        </div>
                                    {else}
                                        {if isset($valueFieldPost)}
                                            {assign var='value_text' value=$valueFieldPost['delivery_in_stock'][$id_lang_default]}
                                        {/if}
                                        <input placeholder="{l s='Delivered within 3-4 days' mod='ets_marketplace'}" class="form-control" name="delivery_in_stock_{$id_lang_default|intval}" value="{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{/if}"  type="text" />
                                    {/if}
                                    <span class="help-block">{l s='Leave empty to disable.' mod='ets_marketplace'}
                                </div>
                            </div>
                            <div class="col-lg-7">
                                <div class="form-group">
                                    <label class="px-0 control-label">
                                        {l s='Delivery time of out-of-stock products with allowed orders:' mod='ets_marketplace'}
                                    </label>
                                    {if $languages && count($languages)>1}
                                        <div class="form-group">
                                            {foreach from=$languages item='language'}
                                                <div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang!=$id_lang_default} style="display:none;"{/if}>
                                                    <div class="col-lg-10">
                                                        {if isset($valueFieldPost)}
                                                            {assign var='value_text' value=$valueFieldPost['delivery_out_stock'][$language.id_lang]}
                                                        {/if}
                                                        <input placeholder="{l s='Delivered within 5-6 days' mod='ets_marketplace'}" class="form-control" name="delivery_out_stock_{$language.id_lang|intval}" value="{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{/if}"  type="text" />
                                                    </div>
                                                    <div class="col-lg-2">
                                                        <div class="toggle_form">
                                                        <button class="btn btn-default dropdown-toggle" type="button" tabindex="-1" data-toggle="dropdown">
                                                        {$language.iso_code|escape:'html':'UTF-8'}
                                                        <i class="icon-caret-down"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            {foreach from=$languages item='lang'}
                                                                <li>
                                                                    <a class="hideOtherLanguage" href="#" tabindex="-1" data-id-lang="{$lang.id_lang|intval}">{$lang.name|escape:'html':'UTF-8'}</a>
                                                                </li>
                                                            {/foreach}
                                                        </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            {/foreach}
                                        </div>
                                    {else}
                                        {if isset($valueFieldPost)}
                                            {assign var='value_text' value=$valueFieldPost['delivery_out_stock'][$id_lang_default]}
                                        {/if}
                                        <input placeholder="{l s='Delivered within 5-6 days' mod='ets_marketplace'}" class="form-control" name="delivery_out_stock_{$id_lang_default|intval}" value="{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{/if}"  type="text" />
                                    {/if}
                                    <span class="help-block">{l s='Leave empty to disable.' mod='ets_marketplace'}
                                </div>
                            </div>
                        </div>
                    </div>
                {/if}
                <div class="col-md-12 pb-1">
                    <div class="form-group">
                        <h2>
                            {l s='Shipping fees' mod='ets_marketplace'}
                            <span class="help-box">
                                <span>{l s='If a carrier has a tax, it will be added to the shipping fees. Does not apply to free shipping.' mod='ets_marketplace'}</span>
                            </span>
                        </h2>
                        <label class="form-control-label">{l s='Does this product incur additional shipping costs?' mod='ets_marketplace'}</label>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="input-group money-type">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{$default_currency->sign|escape:'html':'UTF-8'} </span>
                                    </div>
                                    <input id="additional_shipping_cost" class="form-control" name="additional_shipping_cost" value="{if $product_class->additional_shipping_cost!=0}{$product_class->additional_shipping_cost|floatval}{/if}" type="text" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--<div class="col-md-12">
                    <div class="form-group">
                        <h2 class="">{l s='Available carriers' mod='ets_marketplace'}</h2>
                        <small class="form-text">
                            {l s='Choisie ton ou tes modes de livraison' mod='ets_marketplace'}
                        </small>
                        <div id="selectedCarriers">
                            {if $carriers}
                                {foreach $carriers item='carrier'}
                                    <div class="checkbox">
                                        <label class="">
                                            <div class="ets_input_group">
                                                {* _ARM_ Auto select seller carriers *}
                                                <input id="selectedCarriers_{$carrier.id_reference|intval}" name="selectedCarriers[]" value="{$carrier.id_reference|intval}" type="checkbox" {if in_array($carrier.id_reference,$selected_carriers) || empty($selected_carriers)} checked="checked"{/if}/>
                                                <div class="ets_input_check"></div>
                                            </div>
                                            {$carrier.name|escape:'html':'UTF-8'}{if $carrier.delay} ({$carrier.delay|escape:'html':'UTF-8'}){/if}
                                        </label>
                                    </div>
                                {/foreach}
                            {/if}
                            
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="alert alert-warning" role="alert">
                        <p class="alert-text"> {l s='If no carrier is selected then all the carriers will be available for customers orders.' mod='ets_marketplace'} </p>
                    </div>
                </div>-->
            </div>
        </div>
    </div>
</div>
<div class="row form-group">
<label class="col-lg-3 form-control-label">Modes de livraison</label>
<div class="col-lg-9 form-group ets-mp-input-groups">
<p><small>Choisis ton ou tes modes de livraison</small></p>
<div class="input-group row">
    {if $carriers}
      {foreach $carriers item='carrier'}
            
                <div class="col-lg-4">
                    <div class="checkbox">
                        <input id="selectedCarriers_{$carrier.id_reference|intval}" name="selectedCarriers[]" value="{$carrier.id_reference|intval}" type="checkbox" {if in_array($carrier.id_reference,$selected_carriers) || empty($selected_carriers)} checked="checked"{/if}/><label> {$carrier.name|escape:'html':'UTF-8'}{if $carrier.delay} ({$carrier.delay|escape:'html':'UTF-8'}){/if}</label>
                    </div>
                </div>
      {/foreach}  
     {/if}          
</div>
</div>
</div>
<div class="row form-group" id="dynamicShipping"  {if in_array($carrier.id_reference,$selected_carriers) || empty($selected_carriers)} style="dislay:inlin-block" {/if} >
<label class="col-lg-3 form-control-label">Prix envoi du colis</label>
<div class="col-lg-9 form-group ets-mp-input-groups">
<p><small>Choisis le prix auquel tu veux envoyer ton colis par La Poste.</small></p>
<div class="input-group">
    <input autocomplete="off" type="text" placeholder="CHF 0,00" name="shipping_cost" id="shipping_cost" value="{$valueFieldPost['shipping_cost']}">
    <div class="input-group-append">
        <span class="input-group-text">CHF</span>
    </div>
                                                                                                                             </div>

</div>
</div>

