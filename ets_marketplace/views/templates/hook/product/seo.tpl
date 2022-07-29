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
            <h2>{l s='Search Engine Optimization' mod='ets_marketplace'}</h2>
            <p class="subtitle">{l s='Improve your ranking and how your product page will appear in search engines results.' mod='ets_marketplace'}</p> 
            <div class="row">
                <div class="col-lg-12">
                    <fieldset class="form-group">
                        <label class="px-0 control-label">
                        {l s='Meta title' mod='ets_marketplace'}
                        <span class="help-box">
                            <span>{l s='Public title for the product page and for search engines. Leave blank to use the product name. The number of remaining characters is displayed to the left of the field.' mod='ets_marketplace'}</span>
                        </span>
                        </label>
                        {if $languages && count($languages)>1}
                            <div class="form-group">
                                {foreach from=$languages item='language'}
                                    <div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang!=$id_lang_default} style="display:none;"{/if}>
                                        <div class="col-lg-10">
                                            {if isset($valueFieldPost)}
                                                {assign var='value_text' value=$valueFieldPost['meta_title'][$language.id_lang]}
                                            {/if}
                                            <input placeholder="{l s='To have a different title from the product name, enter it here.' mod='ets_marketplace'}" class="form-control change_length" name="meta_title_{$language.id_lang|intval}" value="{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{/if}"  type="text" />
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
                                        <small class="form-text text-muted text-right col-xs-12 maxLength">
                                            <em>
                                                <span class="currentLength">0</span>
                                                {l s='of' mod='ets_marketplace'}
                                                <span class="currentTotalMax">70</span>
                                                {l s='characters used (recommended)' mod='ets_marketplace'}
                                            </em>
                                        </small>
                                    </div>
                                {/foreach}
                            </div>
                        {else}
                            {if isset($valueFieldPost)}
                                {assign var='value_text' value=$valueFieldPost['meta_title'][$id_lang_default]}
                            {/if}
                            <input placeholder="{l s='To have a different title from the product name, enter it here.' mod='ets_marketplace'}" class="form-control change_length" name="meta_title_{$id_lang_default|intval}" value="{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{/if}"  type="text" />
                            <small class="form-text text-muted text-right maxLength ">
                                <em>
                                    <span class="currentLength">0</span>
                                    {l s='of' mod='ets_marketplace'}
                                    <span class="currentTotalMax">70</span>
                                    {l s='characters used (recommended)' mod='ets_marketplace'}
                                </em>
                            </small>
                        {/if}
                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <fieldset class="form-group">
                        <label class="px-0 control-label">
                        {l s='Meta description' mod='ets_marketplace'}
                        <span class="help-box">
                            <span>{l s='This description will appear in search engines. You need a single sentence, shorter than 160 characters (including spaces).' mod='ets_marketplace'}</span>
                        </span>
                        </label>
                        {if $languages && count($languages)>1}
                            <div class="form-group">
                                {foreach from=$languages item='language'}
                                    <div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang!=$id_lang_default} style="display:none;"{/if}>
                                        <div class="col-lg-10">
                                            {if isset($valueFieldPost)}
                                                {assign var='value_text' value=$valueFieldPost['meta_description'][$language.id_lang]}
                                            {/if}
                                            <textarea placeholder="{l s='To have a different description than your product summary in search results pages, write it here..' mod='ets_marketplace'}" class="form-control change_length" name="meta_description_{$language.id_lang|intval}">{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{/if}</textarea>
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
                                        <small class="form-text text-muted text-right col-xs-12 maxLength ">
                                            <em>
                                                <span class="currentLength">0</span>
                                                {l s='of' mod='ets_marketplace'}
                                                <span class="currentTotalMax">160</span>
                                                {l s='characters used (recommended)' mod='ets_marketplace'}
                                            </em>
                                        </small>
                                    </div>
                                {/foreach}
                            </div>
                        {else}
                            {if isset($valueFieldPost)}
                                {assign var='value_text' value=$valueFieldPost['meta_description'][$id_lang_default]}
                            {/if}
                            <textarea placeholder="{l s='To have a different description than your product summary in search results pages, write it here..' mod='ets_marketplace'}" class="form-control change_length" name="meta_description_{$id_lang_default|intval}">{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{/if}</textarea>
                            <small class="form-text text-muted text-right maxLength ">
                                <em>
                                    <span class="currentLength">0</span>
                                    {l s='of' mod='ets_marketplace'}
                                    <span class="currentTotalMax">160</span>
                                    {l s='characters used (recommended)' mod='ets_marketplace'}
                                </em>
                            </small>
                        {/if}
                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <fieldset class="form-group">
                        <label class="px-0 control-label">
                        {l s='Friendly URL' mod='ets_marketplace'}
                        <span class="help-box">
                            <span>{l s='This is the human-readable URL, as generated from the product\'s name. You can change it if you want.' mod='ets_marketplace'}</span>
                        </span>
                        </label>
                        {if $languages && count($languages)>1}
                            <div class="form-group">
                                {foreach from=$languages item='language'}
                                    <div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang!=$id_lang_default} style="display:none;"{/if}>
                                        <div class="col-lg-10">
                                            {if isset($valueFieldPost)}
                                                {assign var='value_text' value=$valueFieldPost['link_rewrite'][$language.id_lang]}
                                            {/if}
                                            <input class="form-control change_length" name="link_rewrite_{$language.id_lang|intval}" value="{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{/if}"  type="text" />
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
                                {assign var='value_text' value=$valueFieldPost['link_rewrite'][$id_lang_default]}
                            {/if}
                            <input class="form-control" name="link_rewrite_{$id_lang_default|intval}" value="{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{/if}"  type="text" />
                        {/if}
                    </fieldset>
                </div>
            </div>
        </div>  
    </div>
</div>