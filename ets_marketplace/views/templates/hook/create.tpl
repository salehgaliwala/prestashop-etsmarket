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
{if isset($ETS_MP_GOOGLE_MAP_API) && $ETS_MP_GOOGLE_MAP_API && isset($ETS_MP_SEARCH_ADDRESS_BY_GOOGLE) && $ETS_MP_SEARCH_ADDRESS_BY_GOOGLE}
    <script>
        {literal}
        var address_autocomplete;
        function ets_mp_initAutocomplete() {
          address_autocomplete = new google.maps.places.Autocomplete(
              document.getElementById('search_shop_address'), {types: ['geocode']});
          address_autocomplete.setFields(['address_component']);
          address_autocomplete.addListener('place_changed', ets_mp_fillInAddress);
        }
        function ets_mp_fillInAddress() {
            var address = document.getElementById('search_shop_address').value;
        	var geocoder = new google.maps.Geocoder();
        	geocoder.geocode({address: address}, function(results, status) {
                if (status === google.maps.GeocoderStatus.OK)
      			{
      			   var center = results[0].geometry.location;
                   document.getElementById('latitude').value = Math.round(center.lat()*1000000)/1000000;
                   document.getElementById('longitude').value = Math.round(center.lng()*1000000)/1000000;
      			}
        	});
        }
        {/literal}
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={$ETS_MP_GOOGLE_MAP_API|escape:'html':'UTF-8'}&libraries=places&callback=ets_mp_initAutocomplete" async defer></script>
{/if}
{if $manager_shop}
    {if $manager_shop.active==-1}
        <form id="seller-register-form" action="" method="post" enctype="multipart/form-data">
            <div class="alert alert-info">
                {l s='You have a shop management invitation' mod='ets_marketplace'} {$manager_shop.shop_name|escape:'html':'UTF-8'} {l s='from ' mod='ets_marketplace'} {$manager_shop.firstname|escape:'html':'UTF-8'} {$manager_shop.lastname|escape:'html':'UTF-8'}
            </div>
            <div class="ets_button_group" style="display: block;margin-bottom: 30px">
                <button type="submit" id="submitDeclinceManageShop" class="btn btn-primary" name="submitDeclinceManageShop">{l s='Decline' mod='ets_marketplace'}</button>
                <button type="submit" id="submitApproveManageShop" class="btn btn-primary" name="submitApproveManageShop">{l s='Approve' mod='ets_marketplace'}</button>
            </div>
        </form>
    {else}
        <div class="alert alert-info">  
            {l s='You accepted a shop management invitation' mod='ets_marketplace'} {$manager_shop.shop_name|escape:'html':'UTF-8'}. <a href="{$link->getModuleLink('ets_marketplace','myseller')|escape:'html':'UTF-8'}">{l s='Click here' mod='ets_marketplace'}</a> {l s='to manage shop' mod='ets_marketplace'}
        </div>
    {/if}
{else}
    <div class="ets_mp_content_left ets_mp_createpage{if !$_errors} no_close{/if}">
        <div class="panel">
        {if !$_success}
            {if $ETS_MP_REQUIRE_REGISTRATION}
                <div class="alert alert-info">
                    {l s='Congratulations! Your application has been approved. You can now create your shop by completing the form below.' mod='ets_marketplace'}
                </div>
            {else}
                <div class="alert alert-info">
                    {l s='Complete the form below to create your shop' mod='ets_marketplace'}
                </div>
            {/if}
        {/if}
        {if $_errors}
            {$_errors nofilter}
        {/if}
        {if $_success}
            {$_success nofilter}
        {/if} 
        {if !$shop_seller || ($shop_seller && ($shop_seller->active==1 || $shop_seller->getFeeType()!='no_fee'))}
        <section>
            <form id="seller-form" action="{$link->getModuleLink('ets_marketplace','create')|escape:'html':'UTF-8'}" method="post" enctype="multipart/form-data">
                <section>
                    <div class="ets_mp_step_content">
                    {if $shop_seller}
                        {if $shop_seller->active==1}
                            <div class="step step_3 active">
                                <a class="btn btn-primary" href="{$link->getModuleLink('ets_marketplace','products',['addnew'=>1])|escape:'html':'UTF-8'}"><i class="icon icon-new"></i> {l s='Create your first product' mod='ets_marketplace'}</a>
                            </div>
                        {elseif $shop_seller->getFeeType()!='no_fee'}
                            {if $ETS_MP_SELLER_FEE_EXPLANATION}
                                <div class="fee_explanation">
                                    <b>{l s='Fee explanation' mod='ets_marketplace'}:</b> {$ETS_MP_SELLER_FEE_EXPLANATION nofilter}
                                </div>
                            {/if}
                            <div class="step step_3 active">
                                <button type="button" class="btn btn-primary i_have_just_sent_the_fee">{l s='I have just sent the fee' mod='ets_marketplace'}</button>
                            </div>
                        {/if}
                    {else}
                        <div class="step step_1 active">
                            <div class="form-group row" style="display: none;">
                                <label class="col-md-3 form-control-label required"> {l s='Seller name' mod='ets_marketplace'} </label>
                                <div class="col-md-9">
                                    <input class="form-control" name="seller_name" value="{$create_customer->firstname|escape:'html':'UTF-8'} {$create_customer->lastname|escape:'html':'UTF-8'}" type="text" disabled="disabled" />
                                </div>
                            </div>
                            <div class="form-group row" style="display: none;">
                                <label class="col-md-3 form-control-label required"> {l s='Seller email' mod='ets_marketplace'} </label>
                                <div class="col-md-9">
                                    <input class="form-control" name="seller_email" value="{$create_customer->email|escape:'html':'UTF-8'}" type="text" disabled="disabled" />
                                </div>
                            </div>
                            <div class="form-group row" style="display: none;">
                                <label class="col-md-3 form-control-label required"> {l s='Shop name' mod='ets_marketplace'} </label>
                                <div class="col-md-9">
                                    {if $languages && count($languages)>1}
                                        <div class="form-group">
                                            {foreach from=$languages item='language'}
                                                <div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang!=$id_lang_default} style="display:none;"{/if}>
                                                    <div class="col-lg-10">
                                                        {if isset($valueFieldPost)}
                                                            {assign var='value_text' value=$valueFieldPost['shop_name'][$language.id_lang]}
                                                        {/if}
                                                        <input class="form-control" name="shop_name_{$language.id_lang|intval}" value="{$create_customer->firstname|escape:'html':'UTF-8'} {$create_customer->lastname|escape:'html':'UTF-8'}"  type="text" />
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
                                            {assign var='value_text' value=$valueFieldPost['shop_name'][$id_lang_default]}
                                        {/if}
                                        <input class="form-control" name="shop_name_{$id_lang_default|intval}" value="{$create_customer->firstname|escape:'html':'UTF-8'} {$create_customer->lastname|escape:'html':'UTF-8'}"  type="text" />
                                    {/if}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label"> {l s='Shop description' mod='ets_marketplace'} </label>
                                <div class="col-md-9">
                                    {if $languages && count($languages)>1}
                                        <div class="form-group">
                                            {foreach from=$languages item='language'}
                                                <div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang!=$id_lang_default} style="display:none;"{/if}>
                                                    <div class="col-lg-10">
                                                        {if isset($valueFieldPost)}
                                                            {assign var='value_text' value=$valueFieldPost['shop_description'][$language.id_lang]}
                                                        {/if}
                                                        <textarea class="form-control" name="shop_description_{$language.id_lang|intval}">{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{else}{if $seller}{$seller->shop_description|escape:'html':'UTF-8'}{/if}{/if}</textarea>
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
                                            {assign var='value_text' value=$valueFieldPost['shop_description'][$id_lang_default]}
                                        {/if}
                                        <textarea class="form-control" name="shop_description_{$id_lang_default|intval}">{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{else}{if $seller}{$seller->shop_description|escape:'html':'UTF-8'}{/if}{/if}</textarea>
                                    {/if}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label required"> {l s='Shop address' mod='ets_marketplace'} </label>
                                <div class="col-md-9">
                                    {if $languages && count($languages)>1}
                                        <div class="form-group">
                                            {foreach from=$languages item='language'}
                                                <div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang!=$id_lang_default} style="display:none;"{/if}>
                                                    <div class="col-lg-10">
                                                        {if isset($valueFieldPost)}
                                                            {assign var='value_text' value=$valueFieldPost['shop_address'][$language.id_lang]}
                                                        {/if}
                                                        <input type="text" class="form-control" {if $language.id_lang==$id_lang_default} id="search_shop_address"{/if} name="shop_address_{$language.id_lang|intval}" value="{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{else}{if $seller}{$seller->shop_address|escape:'html':'UTF-8'}{/if}{/if}" />
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
                                            {assign var='value_text' value=$valueFieldPost['shop_address'][$id_lang_default]}
                                        {/if}
                                        <input type="text" class="form-control" id="search_shop_address" name="shop_address_{$id_lang_default|intval}" value="{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{else}{if $seller}{$seller->shop_address|escape:'html':'UTF-8'}{/if}{/if}" />
                                    {/if}
                                    
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label required"> {l s='Shop zip' mod='ets_marketplace'} </label>
                                <div class="col-md-9">
                                    <input id="search_shop_zip" type="text" class="form-control shop_address" name="shop_zip" value="{if isset($seller->shop_zip)}{$seller->shop_zip|escape:'html':'UTF-8'}{/if}" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label required"> {l s='Shop city' mod='ets_marketplace'} </label>
                                <div class="col-md-9">
                                    <input id="search_shop_city" type="text" class="form-control shop_address" name="shop_city" value="{if isset($seller->shop_city)}{$seller->shop_city|escape:'html':'UTF-8'}{/if}" />
                                </div>
                            </div>
                            {if isset($ETS_MP_ENABLE_MAP) && $ETS_MP_ENABLE_MAP}
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label"> {l s='Latitude' mod='ets_marketplace'} </label>
                                    <div class="col-md-9">
                                        <input class="form-control" id="latitude" name="latitude" value="{if isset($smarty.post.latitude)}{$smarty.post.latitude|escape:'html':'UTF-8'}{else}{if $seller && $seller->latitude!=0}{$seller->latitude|floatVal}{/if}{/if}"  type="text" />
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-md-3 form-control-label"> {l s='Longitude' mod='ets_marketplace'} </label>
                                    <div class="col-md-9">
                                        <input class="form-control" id="longitude" name="longitude" value="{if isset($smarty.post.longitude)}{$smarty.post.longitude|escape:'html':'UTF-8'}{else}{if $seller && $seller->longitude!=0}{$seller->longitude|floatVal}{/if}{/if}"  type="text" />
                                    </div>
                                </div>
                            {/if}
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label required"> {l s='Shop phone number' mod='ets_marketplace'} </label>
                                <div class="col-md-9">
                                    <input class="form-control" name="shop_phone" value="{if isset($smarty.post.shop_phone)}{$smarty.post.shop_phone|escape:'html':'UTF-8'}{else}{if $seller}{$seller->shop_phone|escape:'html':'UTF-8'}{else}{$number_phone|escape:'html':'UTF-8'}{/if}{/if}"  type="text" />
                                </div>
                            </div>
                            {*<div class="form-group row">
                                <label class="col-md-3 form-control-label"> {l s='VAT number' mod='ets_marketplace'} </label>
                                <div class="col-md-9">
                                    <input class="form-control" name="vat_number" value="{if isset($smarty.post.vat_number)}{$smarty.post.vat_number|escape:'html':'UTF-8'}{else}{if $seller}{$seller->vat_number|escape:'html':'UTF-8'}{else}{$vat_number|escape:'html':'UTF-8'}{/if}{/if}"  type="text" />
                                </div>
                            </div>*}
                            <div class="form-group row shop-logo">
                                <label class="col-md-3 form-control-label"> {l s='Shop logo' mod='ets_marketplace'} </label>
                                <div class="col-md-9">
                                    {if $seller && $seller->shop_logo}
                                        <div class="shop_logo">
                                            <img class="ets_mp_shop_logo" src="{$link_base|escape:'html':'UTF-8'}/img/mp_seller/{$seller->shop_logo|escape:'html':'UTF-8'}" width="80px" />
                                            {*<a class="btn btn-default" href="{$link->getModuleLink('ets_marketplace','create',['deletelogo'=>1])|escape:'html':'UTF-8'}" onclick="return confirm('{l s='Do you want to delete this logo?' mod='ets_marketplace' js='1'}');">
                            					<i class="fa fa-trash"></i> {l s='Delete' mod='ets_marketplace'}
                            				</a>*}
                                        </div>
                                    {/if}
                                    <div class="ets_upload_file_custom">
                                        <input class="form-control custom-file-input" name="shop_logo" type="file" id="shop_logo" />
                                        <label class="custom-file-label" for="shop_logo" data-browser="{l s='Browse' mod='ets_marketplace'}">
                                           {l s='Choose file' mod='ets_marketplace'}
                                        </label>
                                    </div>
                                    <div class="desc">{l s='Recommended size: 250x250 px. Accepted formats: jpg, png, gif' mod='ets_marketplace'}. {l s='Limit:' mod='ets_marketplace'}&nbsp;{Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE')|escape:'html':'UTF-8'}Mb</div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label"> {l s='Shop banner' mod='ets_marketplace'} </label>
                                <div class="col-md-9">
                                    {if $languages && count($languages)>1}
                                        <div class="form-group">
                                            {foreach from=$languages item='language'}
                                                <div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang!=$id_lang_default} style="display:none;"{/if}>
                                                    <div class="col-lg-10">
                                                        {if $seller && $seller->shop_banner}
                                                            <div class="shop_logo">
                                                                <img class="ets_mp_shop_logo" src="{$link_base|escape:'html':'UTF-8'}/img/mp_seller/{$seller->shop_banner|escape:'html':'UTF-8'}" width="150px" />
                                                            </div>
                                                        {/if}
                                                        <div class="ets_upload_file_custom">
                                                            <input class="form-control shop_banner custom-file-input" name="shop_banner_{$language.id_lang|intval}" type="file" id="shop_banner_{$language.id_lang|intval}" />
                                                            <label class="custom-file-label" for="shop_banner_{$language.id_lang|intval}" data-browser="{l s='Browse' mod='ets_marketplace'}">
                                                               {l s='Choose file' mod='ets_marketplace'}
                                                            </label>
                                                        </div>
                                                        <div class="desc">{l s='Recommended size: 1170x170 px. Accepted formats: jpg, png, gif' mod='ets_marketplace'}. {l s='Limit:' mod='ets_marketplace'}&nbsp;{Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE')|escape:'html':'UTF-8'}Mb</div>
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
                                        {if $seller && $seller->shop_banner}
                                            <div class="shop_logo">
                                                <img class="ets_mp_shop_logo" src="{$link_base|escape:'html':'UTF-8'}/img/mp_seller/{$seller->shop_banner|escape:'html':'UTF-8'}" width="150px" />
                                            </div>
                                        {/if}
                                        <div class="ets_upload_file_custom">
                                            <input class="form-control shop_banner custom-file-input" name="shop_banner_{$id_lang_default|intval}" type="file" id="shop_banner_{$id_lang_default|intval}"/>
                                             <label class="custom-file-label" for="shop_banner_{$id_lang_default|intval}" data-browser="{l s='Browse' mod='ets_marketplace'}">
                                               {l s='Choose file' mod='ets_marketplace'}
                                            </label>
                                        </div>
                                        <div class="desc">{l s='Recommended size: 1170x170 px. Accepted formats: jpg, png, gif' mod='ets_marketplace'}. {l s='Limit:' mod='ets_marketplace'}&nbsp;{Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE')|escape:'html':'UTF-8'}Mb</div>
                                    {/if}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label"> {l s='Banner URL' mod='ets_marketplace'} </label>
                                <div class="col-md-9">
                                    {if $languages && count($languages)>1}
                                        <div class="form-group">
                                            {foreach from=$languages item='language'}
                                                <div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang!=$id_lang_default} style="display:none;"{/if}>
                                                    <div class="col-lg-10">
                                                        {if isset($valueFieldPost)}
                                                            {assign var='value_text' value=$valueFieldPost['banner_url'][$language.id_lang]}
                                                        {/if}
                                                        <input class="form-control" name="banner_url_{$language.id_lang|intval}" value="{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{else}{if $seller}{$seller->banner_url|escape:'html':'UTF-8'}{/if}{/if}"  type="text" />
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
                                            {assign var='value_text' value=$valueFieldPost['banner_url'][$id_lang_default]}
                                        {/if}
                                        <input class="form-control" name="banner_url_{$id_lang_default|intval}" value="{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{else}{if $seller}{$seller->banner_url|escape:'html':'UTF-8'}{/if}{/if}"  type="text" />
                                    {/if}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label"> {l s='Facebook link' mod='ets_marketplace'} </label>
                                <div class="col-md-9">
                                    <input class="form-control" name="link_facebook" placeholder="Ex : https://www.facebook.com/onceagainch/" value="{if isset($smarty.post.link_facebook)}{$smarty.post.link_facebook|escape:'html':'UTF-8'}{else}{if $seller}{$seller->link_facebook|escape:'html':'UTF-8'}{/if}{/if}"  type="text" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label"> {l s='Instagram link' mod='ets_marketplace'} </label>
                                <div class="col-md-9">
                                    <input class="form-control" name="link_instagram" placeholder="Ex : https://www.instagram.com/onceagain.ch/" value="{if isset($smarty.post.link_instagram)}{$smarty.post.link_instagram|escape:'html':'UTF-8'}{else}{if $seller}{$seller->link_instagram|escape:'html':'UTF-8'}{/if}{/if}"  type="text" />
                                </div>
                            </div>
                            {*<div class="form-group row">
                                <label class="col-md-3 form-control-label"> {l s='Google link' mod='ets_marketplace'} </label>
                                <div class="col-md-9">
                                    <input class="form-control" name="link_google" value="{if isset($smarty.post.link_google)}{$smarty.post.link_google|escape:'html':'UTF-8'}{else}{if $seller}{$seller->link_google|escape:'html':'UTF-8'}{/if}{/if}"  type="text" />
                                </div>
                            </div>*}
                            <div class="form-group row">
                                <label class="col-md-3 form-control-label"> {l s='Twitter link' mod='ets_marketplace'} </label>
                                <div class="col-md-9">
                                    <input class="form-control" name="link_twitter" value="{if isset($smarty.post.link_twitter)}{$smarty.post.link_twitter|escape:'html':'UTF-8'}{else}{if $seller}{$seller->link_twitter|escape:'html':'UTF-8'}{/if}{/if}"  type="text" />
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-3"> </div>
                                <div class="col-md-9">
                                    <input name="submitSaveSeller" value="1" type="hidden" />
                                    <button class="btn btn-primary form-control-submit float-xs-right" type="submit">
                                        <i class="icon icon-save"></i> {l s='Create shop' mod='ets_marketplace'}
                                    </button>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    {/if}
                    </div>
                </section> 
           </form> 
        </section>
        {/if}
        </div>
    </div>
{/if}