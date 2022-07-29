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
<script type="text/javascript">
var colorImageFolder ='{$colorImageFolder nofilter}';
var add_keyword_text ='{l s='Add keyword' mod='ets_marketplace' js=1}';
var confirm_delete = '{l s='Do you want to delete this item?' mod='ets_marketplace' js=1}';
var Edit_text = '{l s='Edit' mod='ets_marketplace' js=1}';
var Delete_text = '{l s='Delete' mod='ets_marketplace' js=1}';
</script>
{foreach from= $fields item='field'}
    {if isset($field.title_group) && $field.title_group}
        <h2>{$field.title_group|escape:'html':'UTF-8'}</h2>
    {/if}
    <div class="row form-group{if isset($field.form_group_class)} {$field.form_group_class|escape:'html':'UTF-8'}{/if}">
        {if $field.type=='custom_form'}
            {$field.html_form nofilter}
        {/if}
        {if $field.type=='select' && $field.values}
            <label class="col-lg-3 form-control-label{if isset($field.required) && $field.required} required{/if}" for="{$field.name|escape:'html':'UTF-8'}">
                {$field.label|escape:'html':'UTF-8'}
                {if isset($field.placeholder)}
                    <small class="form-text">{$field.placeholder}</small>
                {/if}
            </label>
            <div class="col-lg-9">
                <select name="{$field.name|escape:'html':'UTF-8'}" id="{$field.name|escape:'html':'UTF-8'}" class="form-control">
                    {foreach from=$field.values item='value'}
                        <option{if $value.id== $valueFieldPost[$field.name]} selected="selected"{/if} value="{$value.id|escape:'html':'UTF-8'}"{if isset($value.parent)} data-parent="{$value.parent|intval}"{/if}>{$value.name|escape:'html':'UTF-8'}</option>
                    {/foreach}
                </select>
                {if isset($field.desc) && $field.desc}
                    <span class="help-block">
                        {$field.desc nofilter}
                    </span>
                {/if}
            </div>
        {/if}
        {if $field.type=='radio'}
            <label class="col-lg-3 form-control-label{if isset($field.required) && $field.required} required{/if}" for="{$field.name|escape:'html':'UTF-8'}">{$field.label|escape:'html':'UTF-8'}</label>
            <div class="col-lg-9">
                {foreach from=$field.values item='value'}
                    <div class="radio">
                        <label class="">
                            <input id="{$field.name|escape:'html':'UTF-8'}_{$value.id|escape:'html':'UTF-8'}" name="{$field.name|escape:'html':'UTF-8'}" value="{$value.id|escape:'html':'UTF-8'}" type="radio" {if $value.id== $valueFieldPost[$field.name]} checked="checked"{/if}/>
                            {$value.name|escape:'html':'UTF-8'}
                        </label>
                    </div>
                {/foreach}
                {if isset($field.desc) && $field.desc}
                    <span class="help-block">
                        {$field.desc nofilter}
                    </span>
                {/if}
            </div>
        {/if}
        {if $field.type=='checkbox'}
            <label class="col-lg-3 form-control-label{if isset($field.required) && $field.required} required{/if}" for="{$field.name|escape:'html':'UTF-8'}">{$field.label|escape:'html':'UTF-8'}</label>
            <div class="col-lg-9">
                <div class="checkbox_all checkbox">
                    <label class="">
                        <input id="{$field.name|escape:'html':'UTF-8'}_all" name="{$field.name|escape:'html':'UTF-8'}[]" value="all" type="checkbox" {if is_array($valueFieldPost[$field.name]) && in_array('all',$valueFieldPost[$field.name])} checked="checked"{/if}/>
                        {l s='All' mod='ets_marketplace'}
                    </label>
                </div>
                {foreach from=$field.values item='value'}
                    <div class="checkbox">
                        <label class="">
                            <input id="{$field.name|escape:'html':'UTF-8'}_{$value.id|escape:'html':'UTF-8'}" name="{$field.name|escape:'html':'UTF-8'}[]" value="{$value.id|escape:'html':'UTF-8'}" type="checkbox" {if is_array($valueFieldPost[$field.name]) && (in_array($value.id,$valueFieldPost[$field.name]) || in_array('all',$valueFieldPost[$field.name]))} checked="checked"{/if}/>
                            {$value.name|escape:'html':'UTF-8'}
                        </label>
                    </div>
                {/foreach}
                {if isset($field.desc) && $field.desc}
                    <span class="help-block">
                        {$field.desc nofilter}
                    </span>
                {/if}
            </div>
        {/if}
        {if $field.type=='switch'}
             <label class="col-lg-3 form-control-label{if isset($field.required) && $field.required} required{/if}" for="{$field.name|escape:'html':'UTF-8'}">{$field.label|escape:'html':'UTF-8'}</label>
             <div class="col-lg-9">
                <span class="switch prestashop-switch fixed-width-lg">
        			<input name="{$field.name|escape:'html':'UTF-8'}" id="{$field.name|escape:'html':'UTF-8'}_on" value="1"{if $valueFieldPost[$field.name]==1} checked="checked"{/if} type="radio" />
        			<label for="{$field.name|escape:'html':'UTF-8'}_on" class="radioCheck">
        				<i class="color_success"></i> {l s='Yes' mod='ets_marketplace'}
        			</label>
        			<input name="{$field.name|escape:'html':'UTF-8'}" id="{$field.name|escape:'html':'UTF-8'}_off" value="0" {if $valueFieldPost[$field.name]==0} checked="checked"{/if} type="radio" />
        			<label for="{$field.name|escape:'html':'UTF-8'}_off" class="radioCheck">
        				<i class="color_danger"></i> {l s='No' mod='ets_marketplace'}
        			</label>
        			<a class="slide-button btn"></a>
        		</span>
                {if isset($field.desc) && $field.desc}
                    <span class="help-block">
                        {$field.desc nofilter}
                    </span>
                {/if}
             </div>
        {/if}
        {if $field.type=='text' || $field.type=='date' || $field.type=='tags'}
            <label class="col-lg-3 form-control-label{if isset($field.required) && $field.required} required{/if}" for="{$field.name|escape:'html':'UTF-8'}">{$field.label|escape:'html':'UTF-8'}</label>
            <div class="col-lg-9{if $field.type=='date'} datepicker {/if}">
                {if isset($field.lang) && $field.lang}
                    {if $languages && count($languages)>1}
                        <div class="form-group">
                            {foreach from=$languages item='language'}
                                <div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang!=$id_lang_default} style="display:none;"{/if}>
                                    <div class="col-lg-11">
                                        {if isset($valueFieldPost)}
                                            {assign var='value_text' value=$valueFieldPost[$field.name][$language.id_lang]}
                                        {/if}
                                        <input{if isset($field.autocomplete) && !$field.autocomplete} autocomplete="off"{/if} {if isset($field.placeholder)}placeholder="{$field.placeholder}"{/if} class="form-control {if $field.type=='tags'} tagify{/if}" id="{$field.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}" name="{$field.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}" value="{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{/if}"  type="text" />
                                    </div>
                                    <div class="col-lg-1">
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
                            {assign var='value_text' value=$valueFieldPost[$field.name][$id_lang_default]}
                        {/if}
                        <input{if isset($field.autocomplete) && !$field.autocomplete} autocomplete="off"{/if} class="form-control {if $field.type=='tags'} tagify{/if}" name="{$field.name|escape:'html':'UTF-8'}_{$id_lang_default|intval}" {if isset($field.placeholder)}placeholder="{$field.placeholder}"{/if} value="{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{/if}"  type="text" />
                    {/if}
                {else}
                    {if (isset($field.suffix) && $field.suffix) || (isset($field.group_addon) && $field.group_addon)}
                        <div class="input-group">
                    {/if}
                    {if isset($field.group_addon) && $field.group_addon}
                        <div class="input-group-prepend">
                             <span class="input-group-text">
                                {$field.group_addon nofilter}
                             </span>
                        </div>
                    {/if}
                        <input{if isset($field.autocomplete) && !$field.autocomplete} autocomplete="off"{/if} {if isset($field.placeholder)}placeholder="{$field.placeholder}"{/if} class="form-control{if $field.type=='tags'} tagify{/if}" name="{$field.name|escape:'html':'UTF-8'}" value="{if isset($valueFieldPost[$field.name])}{$valueFieldPost[$field.name]|escape:'html':'UTF-8'}{/if}"  type="text" {if isset($field.readonly) && $field.readonly} readonly="true"{/if} />
                    {if isset($field.suffix) && $field.suffix}
                        <div class="input-group-append">
                            <span class="input-group-text">
                                {$field.suffix nofilter}
                            </span>
                        </div>
                    {/if}
                    {if (isset($field.suffix) && $field.suffix) || (isset($field.group_addon) && $field.group_addon)}
                        </div>
                    {/if}
                {/if}
                {if isset($field.desc) && $field.desc}
                    <span class="help-block">
                        {$field.desc nofilter}
                    </span>
                {/if}
            </div>
        {/if}
        {if $field.type=='textarea'}
            <label class="col-lg-3 form-control-label{if isset($field.required) && $field.required} required{/if}" for="{$field.name|escape:'html':'UTF-8'}">{$field.label|escape:'html':'UTF-8'}</label>
            <div class="col-lg-9">
                {if isset($field.lang) && $field.lang}
                    {if $languages && count($languages)>1}
                        <div class="form-group">
                            {foreach from=$languages item='language'}
                                <div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang!=$id_lang_default} style="display:none;"{/if}>
                                    <div class="col-lg-11">
                                        {if isset($valueFieldPost)}
                                            {assign var='value_text' value=$valueFieldPost[$field.name][$language.id_lang]}
                                        {/if}
                                        <textarea {if isset($field.placeholder)} placeholder="{$field.placeholder|escape:'html':'UTF-8'}"{/if} class="form-control{if isset($field.autoload_rte) && $field.autoload_rte} ets_mp_autoload_rte{/if}{if isset($field.small_text) && $field.small_text} change_length{/if}" name="{$field.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}">{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{/if}</textarea>
                                    </div>
                                    <div class="col-lg-1">
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
                                    {if isset($field.small_text) && $field.small_text}
                                        <small class="form-text text-muted text-right col-xs-12 maxLength ">
                                            <em>
                                                <span class="currentLength">0</span>
                                                {l s='of' mod='ets_marketplace'}
                                                <span class="currentTotalMax">{$field.max_text|intval}</span>
                                                {$field.small_text|escape:'html':'UTF-8'}
                                            </em>
                                        </small>
                                    {/if}
                                </div>
                            {/foreach}
                        </div>
                    {else}
                        {if isset($valueFieldPost)}
                            {assign var='value_text' value=$valueFieldPost[$field.name][$id_lang_default]}
                        {/if}
                        <textarea {if isset($field.placeholder)} placeholder="{$field.placeholder|escape:'html':'UTF-8'}"{/if} class="form-control{if isset($field.autoload_rte) && $field.autoload_rte} ets_mp_autoload_rte{/if}{if isset($field.small_text) && $field.small_text} change_length{/if}" name="{$field.name|escape:'html':'UTF-8'}_{$id_lang_default|intval}">{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{/if}</textarea>
                        {if isset($field.small_text) && $field.small_text}
                            <small class="form-text text-muted text-right col-xs-12 maxLength ">
                                <em>
                                    <span class="currentLength">0</span>
                                    {l s='of' mod='ets_marketplace'}
                                    <span class="currentTotalMax">{$field.max_text|intval}</span>
                                    {$field.small_text|escape:'html':'UTF-8'}
                                </em>
                            </small>
                        {/if}
                    {/if}
                {else}
                    <textarea {if isset($field.placeholder)} placeholder="{$field.placeholder|escape:'html':'UTF-8'}"{/if} class="form-control{if isset($field.autoload_rte) && $field.autoload_rte} ets_mp_autoload_rte{/if}{if isset($field.small_text) && $field.small_text} change_length{/if}" name="{$field.name|escape:'html':'UTF-8'}">{if isset($valueFieldPost[$field.name])}{$valueFieldPost[$field.name]|escape:'html':'UTF-8'}{/if}</textarea>
                    {if isset($field.small_text) && $field.small_text}
                    <small class="form-text text-muted text-right col-xs-12 maxLength ">
                        <em>
                            <span class="currentLength">0</span>
                            {l s='of' mod='ets_marketplace'}
                            <span class="currentTotalMax">{$field.max_text|intval}</span>
                            {$field.small_text|escape:'html':'UTF-8'}
                        </em>
                    </small>
                {/if}
                {/if}
                {if isset($field.desc) && $field.desc}
                    <span class="help-block">
                        {$field.desc nofilter}
                    </span>
                {/if}
            </div>
        {/if}
        {if $field.type=='categories'}
            <label class="col-lg-3 form-control-label{if isset($field.required) && $field.required} required{/if}" for="{$field.name|escape:'html':'UTF-8'}">
                {$field.label|escape:'html':'UTF-8'}
                {if isset($field.placeholder)}
                  <!--  <small class="form-text">{$field.placeholder}</small>-->
                {/if}
            </label> 
            <div class="col-lg-9"><select class="form-control" id="category-path"><option>Sélectionner une catégorie </option></select></div>  
            <label class="col-lg-3 form-control-label"> &nbsp;</label>
            <div class="col-lg-9">
                <ul class="category-tree" >
                    <li class="form-control-label text-right main-category">{l s='Main category' mod='ets_marketplace'}</li>
                    {$field.categories_tree nofilter}
                </ul>
            </div>
        {/if}
        {if $field.type=='input_group'}
            <label class="col-lg-3 form-control-label{if isset($field.required) && $field.required} required{/if}" for="">{$field.label|escape:'html':'UTF-8'}</label>
            <div class="col-lg-9 form-group ets-mp-input-groups">
                <div class="row">
                    {if $field.inputs}
                        {foreach from=$field.inputs item='input'}
                            <div class="{$input.col|escape:'html':'UTF-8'} from-group">
                                {if isset($input.label) && $input.label}
                                    <label class="form-control-label" for="">{$input.label|escape:'html':'UTF-8'}</label>
                                {/if}
                                <div>
                                    {if $input.type=='text' || $input.type=='date'}
                                        {if (isset($input.suffix) && $input.suffix) || (isset($input.group_addon) && $input.group_addon)}
                                            <div class="input-group{if $input.type=='date'} datepicker{/if}">
                                            {if isset($input.group_addon) && $input.group_addon}
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">
                                                        {$input.group_addon|escape:'html':'UTF-8'}
                                                    </span>
                                                </div>
                                            {/if}
                                        {/if}
                                            <input autocomplete="off" type="text" {if isset($field.placeholder)}placeholder="{$field.placeholder}"{/if} name="{$input.name|escape:'html':'UTF-8'}" id="{$input.name|escape:'html':'UTF-8'}" value="{if isset($valueFieldPost[$input.name])}{$valueFieldPost[$input.name]|escape:'html':'UTF-8'}{/if}" />
                                        {if isset($input.suffix) && $input.suffix}
                                            <div class="input-group-append">
                                                <span class="input-group-text">{$input.suffix nofilter}</span>
                                            </div>
                                        {/if}
                                        {if (isset($input.suffix) && $input.suffix) || (isset($input.group_addon) && $input.group_addon)}
                                             </div>
                                        {/if}
                                    {/if}
                                    {if $input.type=='select'}
                                        <select name="{$input.name|escape:'html':'UTF-8'}">
                                            {foreach from = $input.values.query item='option'}
                                                <option value="{$option[$input.values.id]|escape:'html':'UTF-8'}"{if $valueFieldPost[$input.name]==$option[$input.values.id]} selected="selected"{/if}>{$option[$input.values.name]|escape:'html':'UTF-8'}</option>
                                            {/foreach}
                                        </select>
                                    {/if}
                                </div>
                            </div>
                        {/foreach}
                    {/if}
                </div>
            </div>
        {/if}
        {if $field.type=='product_features'}
         <!--   <label class="col-lg-12 form-control-label{if isset($field.required) && $field.required} required{/if}" for="" style="text-align:left">{$field.label|escape:'html':'UTF-8'}</label>-->
            <div class="col-lg-9 form-group ets-mp-input-groups" ">
                {$field.list_features nofilter}
            </div>
        {/if}
        {if $field.type=='color'}
            <label class="col-lg-3 form-control-label{if isset($field.required) && $field.required} required{/if}" for="">{$field.label|escape:'html':'UTF-8'}</label>
            <div class="col-lg-3">
                <input class="color" type="color" name="{$field.name|escape:'html':'UTF-8'}" value="{$valueFieldPost[$field.name]|escape:'html':'UTF-8'}" data-hex="true" />
            </div>
        {/if}
        {if $field.type=='file'}
            <label class="col-lg-3 form-control-label{if isset($field.required) && $field.required} required{/if}" for="">{$field.label|escape:'html':'UTF-8'}</label>
            <div class="col-lg-9">
                {if isset($valueFieldPost[$field.name]) && $valueFieldPost[$field.name]}    
                    <div class="shop_logo">
                        <img class="img-thumbnail ets_mp_shop_logo" src="{$valueFieldPost[$field.name]|escape:'html':'UTF-8'}?time={time()|escape:'html':'UTF-8'}" alt="" style="width:98px" />
                        {if isset($field.link_del) && $field.link_del}
            				<a class="btn btn-default" onclick="return confirm('{l s='Do you want to delete this image?' mod='ets_marketplace' js=1}');"  href="{$field.link_del|escape:'html':'UTF-8'}">
            					<i class="icon-trash"></i> {l s='Delete' mod='ets_marketplace'}
            				</a>
             			{/if}
                    </div>
                {/if}
                <input type="file" name="{$field.name|escape:'html':'UTF-8'}" />
                {if isset($field.desc) && $field.desc}
                    <span class="help-block">
                        {$field.desc nofilter}
                    </span>
                {/if}
            </div>
        {/if}
    </div>
{/foreach}