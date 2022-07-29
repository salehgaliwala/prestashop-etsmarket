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
{if $_errors}
    {$_errors nofilter}
{/if}
{if $_success}
    {$_success nofilter}
{/if}
<div class="row">
    <div class="ets_mp_content_left col-lg-3" >
        {hook h='displayMPLeftContent'}
    </div>
    <div class="ets_mp_content_left col-lg-9" >
        <div class="panel">
            <section>
                <form id="seller-form" action="{$link->getModuleLink('ets_marketplace','profile')|escape:'html':'UTF-8'}" method="post" enctype="multipart/form-data">
                    <section>
                        <div class="ets_mp_step_content">
                            <div class="step step_1 active">
                                {*<h4>
                                    <p class="alert alert-success">{l s='Your shop is available' mod='ets_marketplace'}{if ($seller->date_from && $seller->date_from!='0000-00-00') || ($seller->date_to && $seller->date_to!='0000-00-00')}{if $seller->date_from && $seller->date_from!='0000-00-00'} {l s='from' mod='ets_marketplace'} {dateFormat date=$seller->date_from full=0}{/if}{if $seller->date_to && $seller->date_to!='0000-00-00'} {l s='to' mod='ets_marketplace'} {dateFormat date=$seller->date_to full=0}{/if}{/if}</p>
                                </h4>*}
                                <div class="form-group row" style="display: none;">
                                    <label class="col-md-3 form-control-label required"> {l s='Seller name' mod='ets_marketplace'} </label>
                                    <div class="col-md-9">
                                        <input class="form-control" name="seller_name" value="{$profile_customer->firstname|escape:'html':'UTF-8'} {$profile_customer->lastname|escape:'html':'UTF-8'}" type="text" disabled="disabled" />
                                    </div>
                                </div>
                                <div class="form-group row" style="display: none;">
                                    <label class="col-md-3 form-control-label required"> {l s='Seller email' mod='ets_marketplace'} </label>
                                    <div class="col-md-9">
                                        <input class="form-control" name="seller_email" value="{$profile_customer->email|escape:'html':'UTF-8'}" type="text" disabled="disabled" />
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
                                                            <input class="form-control" name="shop_name_{$language.id_lang|intval}" value="{$profile_customer->firstname|escape:'html':'UTF-8'} {$profile_customer->lastname|escape:'html':'UTF-8'}"  type="text" />
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
                                            <input class="form-control" name="shop_name_{$id_lang_default|intval}" value="{$profile_customer->firstname|escape:'html':'UTF-8'} {$profile_customer->lastname|escape:'html':'UTF-8'}"  type="text" />
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
                                                            <textarea class="form-control" name="shop_description_{$language.id_lang|intval}">{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{/if}</textarea>
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
                                            <textarea class="form-control" name="shop_description_{$id_lang_default|intval}">{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{/if}</textarea>
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
                                                            <input{if $language.id_lang==$id_lang_default} id="search_shop_address"{/if} type="text" class="form-control" name="shop_address_{$language.id_lang|intval}" value="{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{/if}" /> 
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
                                            <input id="search_shop_address" type="text" class="form-control shop_address" name="shop_address_{$id_lang_default|intval}" value="{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{/if}" />
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
                                            <input class="form-control" id="latitude" name="latitude" value="{if isset($smarty.post.latitude)}{$smarty.post.latitude|escape:'html':'UTF-8'}{else}{if $seller && $seller->latitude!=0}{$seller->latitude|escape:'html':'UTF-8'}{/if}{/if}"  type="text" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label"> {l s='Longitude' mod='ets_marketplace'} </label>
                                        <div class="col-md-9">
                                            <input class="form-control" id="longitude" name="longitude" value="{if isset($smarty.post.longitude)}{$smarty.post.longitude|escape:'html':'UTF-8'}{else}{if $seller && $seller->longitude!=0}{$seller->longitude|escape:'html':'UTF-8'}{/if}{/if}"  type="text" />
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
                                                {*<a class="btn btn-default" href="{$link->getModuleLink('ets_marketplace','profile',['deletelogo'=>1])|escape:'html':'UTF-8'}" onclick="return confirm('{l s='Do you want to delete this logo?' mod='ets_marketplace' js='1'}');">
                                					<i class="fa fa-trash"></i> {l s='Delete' mod='ets_marketplace'}
                                				</a>*}
                                            </div>
                                        {/if}
                                        <div class="ets_upload_file_custom">
                                            <input class="form-control custom-file-input" name="shop_logo" type="file" id="shop_logo" />
                                            <label class="custom-file-label" for="shop_logo" data-browser="{l s='Browse' mod='ets_marketplace'}">
                                               {l s='Choose a file' mod='ets_marketplace'}
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
                                                            {assign var='shop_banners' value=$seller->shop_banner}
                                                            {if $seller && isset($shop_banners[$language.id_lang]) &&  $shop_banners[$language.id_lang]}
                                                                <div class="shop_logo logo_banner_shop">
                                                                    <img class="ets_mp_shop_logo" src="{$link_base|escape:'html':'UTF-8'}/img/mp_seller/{$shop_banners[$language.id_lang]|escape:'html':'UTF-8'}" width="150px" />
                                                                    <a class="btn btn-default" href="{$link->getModuleLink('ets_marketplace','profile',['deletebanner'=>1,'banner_lang'=>$language.id_lang])|escape:'html':'UTF-8'}" onclick="return confirm('{l s='Do you want to delete this banner?' mod='ets_marketplace' js='1'}');"><i class="fa fa-trash"></i></a>
                                                                </div>
                                                            {/if}
                                                            <div class="ets_upload_file_custom">
                                                                <input class="form-control shop_banner custom-file-input" name="shop_banner_{$language.id_lang|intval}" type="file" id="shop_banner_{$language.id_lang|intval}" />
                                                                <label class="custom-file-label" for="shop_banner_{$language.id_lang|intval}" data-browser="{l s='Browse' mod='ets_marketplace'}">
                                                                    {l s='Choose a file' mod='ets_marketplace'}
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
                                            {assign var='shop_banners' value=$seller->shop_banner}
                                            {if $seller && isset($shop_banners[$id_lang_default]) && $shop_banners[$id_lang_default] }
                                                <div class="shop_logo logo_banner_shop">
                                                    <img class="ets_mp_shop_logo" src="{$link_base|escape:'html':'UTF-8'}/img/mp_seller/{$shop_banners[$id_lang_default]|escape:'html':'UTF-8'}" width="150px" />
                                                    <a class="btn btn-default" href="{$link->getModuleLink('ets_marketplace','profile',['deletebanner'=>1,'banner_lang'=>$id_lang_default])|escape:'html':'UTF-8'}" onclick="return confirm('{l s='Do you want to delete this banner?' mod='ets_marketplace' js='1'}');"><i class="fa fa-trash"></i></a>
                                                </div>
                                            {/if}
                                            <div class="ets_upload_file_custom">
                                                <input class="form-control shop_banner custom-file-input" name="shop_banner_{$id_lang_default|intval}" type="file" id="shop_banner_{$id_lang_default|intval}" />
                                                <label class="custom-file-label" for="shop_banner_{$id_lang_default|intval}" data-browser="{l s='Browse' mod='ets_marketplace'}">
                                                   {l s='Choose a file' mod='ets_marketplace'}
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
                                                            <input class="form-control" name="banner_url_{$language.id_lang|intval}" value="{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{/if}"  type="text" />
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
                                            <input class="form-control" name="banner_url_{$id_lang_default|intval}" value="{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{/if}"  type="text" />
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
                                {if $ETS_MP_VACATION_MODE_FOR_SELLER}
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label"> {l s='Enable vacation mode' mod='ets_marketplace'} </label>
                                        <div class="col-md-9">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                    			<input name="vacation_mode" id="vacation_mode_on" value="1" {if isset($smarty.post.vacation_mode)}{if $smarty.post.vacation_mode} checked="checked"{/if}{else}{if $seller->vacation_mode==1} checked="checked"{/if}{/if} type="radio" />
                                    			<label for="vacation_mode_on" class="radioCheck">
                                    				<i class="color_success"></i> {l s='Yes' mod='ets_marketplace'}
                                    			</label>
                                    			<input name="vacation_mode" id="vacation_mode_off" value="0" {if isset($smarty.post.vacation_mode)}{if !$smarty.post.vacation_mode} checked="checked"{/if}{else}{if $seller->vacation_mode==0} checked="checked"{/if}{/if} type="radio" />
                                    			<label for="vacation_mode_off" class="radioCheck">
                                    				<i class="color_danger"></i> {l s='No' mod='ets_marketplace'}
                                    			</label>
                                    			<a class="slide-button btn"></a>
                                    		</span>
                                        </div>
                                    </div>
                                    <div class="form-group row enable_vacation_mode">
                                        <label class="col-md-3 form-control-label"> {l s='Vacation mode' mod='ets_marketplace'} </label>
                                        <div class="col-md-9">
                                            <select name="vacation_type" id="vacation_type" class="form-control">
                                                <option value="show_notifications" {if isset($smarty.post.vacation_type)}{if $smarty.post.vacation_type=='show_notifications'} selected="selected"{/if}{else}{if $seller->vacation_type=='show_notifications'} selected="selected"{/if}{/if}>{l s='Show notifications' mod='ets_marketplace'}</option>
                                                <option value="disable_product" {if isset($smarty.post.vacation_type)}{if $smarty.post.vacation_type=='disable_product'} selected="selected"{/if}{else}{if $seller->vacation_type=='disable_product'} selected="selected"{/if}{/if}>{l s='Disable product' mod='ets_marketplace'}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row enable_vacation_mode show_notifications">
                                        <label class="col-md-3 form-control-label required"> {l s='Notification' mod='ets_marketplace'} </label>
                                        <div class="col-md-9">
                                            {if $languages && count($languages)>1}
                                                <div class="form-group">
                                                    {foreach from=$languages item='language'}
                                                        <div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang!=$id_lang_default} style="display:none;"{/if}>
                                                            <div class="col-lg-10">
                                                                {if isset($valueFieldPost)}
                                                                    {assign var='value_text' value=$valueFieldPost['vacation_notifications'][$language.id_lang]}
                                                                {/if}
                                                                <textarea class="form-control" name="vacation_notifications_{$language.id_lang|intval}">{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{/if}</textarea>
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
                                                    {assign var='value_text' value=$valueFieldPost['vacation_notifications'][$id_lang_default]}
                                                {/if}
                                                <textarea class="form-control" name="vacation_notifications_{$id_lang_default|intval}">{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{/if}</textarea>
                                            {/if}
                                        </div>
                                    </div>  
                                {/if}
                                <div class="form-group row">
                                    <div class="col-md-3"> </div>
                                    <div class="col-md-9">
                                        <input name="submitSaveSeller" value="1" type="hidden" />
                                        <button class="btn btn-primary form-control-submit float-xs-right" type="submit">
                                            <i class="icon icon-save"></i> {l s='Save' mod='ets_marketplace'}
                                        </button>
                                    </div>
                                </div>
                                <div class="clearfix"></div>
                            </div>
                        </div>
                    </section> 
               </form> 
            </section>
        </div>
    </div>
</div>