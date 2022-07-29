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

<script type="text/javascript" src="{$url_path|escape:'html':'UTF-8'}views/js/tinymce/tinymce.min.js"></script>
<script  type="text/javascript">
    var confirm_delete_specific = '{l s='This will delete the specific price. Do you wish to proceed?' mod='ets_marketplace' js=1}';
    var Unlimited_text ='{l s='Unlimited' mod='ets_marketplace' js=1}';
    var all_combinations_text = '{l s='All combinations' mod='ets_marketplace' js=1}';
    var all_currencies_text ='{l s='All currencies' mod='ets_marketplace' js=1}';
    var all_countries_text = '{l s='All countries' mod='ets_marketplace' js=1}';
    var all_groups_text ='{l s='All groups' mod='ets_marketplace' js=1}';
    var all_customer_text = '{l s='All customers' mod='ets_marketplace' js=1}';
    var from_text = '{l s='From' mod='ets_marketplace' js=1}';
    var to_text = '{l s='To' mod='ets_marketplace' js=1}';
    var id_lang_default ={$id_lang_default|intval};
    {if $product_class->id}
        var ets_mp_is_updating= true;
    {else}
        var ets_mp_is_updating = false;
    {/if}
    var virtual_product_text = '{l s='Virtual product' mod='ets_marketplace' js=1}';
    var quantities_text = '{l s='Quantities' mod='ets_marketplace' js=1}';
    var delete_all_combination_confirm = '{l s='This will delete all the combinations. Do you wish to proceed?' mod='ets_marketplace' js=1}';
    var download_file_text = '{l s='Download file' mod='ets_marketplace' js=1}';
    var delete_file_text = '{l s='Delete this file' mod='ets_marketplace' js=1}';
    var delete_file_comfirm = '{l s='Are you sure to delete this?' mod='ets_marketplace' js=1}';
    var delete_item_comfirm = '{l s='Do you want to delete this item?' mod='ets_marketplace' js=1}';
    var delete_image_comfirm = '{l s='Do you want to delete this image?' mod='ets_marketplace' js=1}';
    var PS_ALLOW_ACCENTED_CHARS_URL ={Configuration::get('PS_ALLOW_ACCENTED_CHARS_URL')|intval};
    var ets_mp_url_search_product = '{$ets_mp_url_search_product nofilter}';
    var ets_mp_url_search_related_product ='{$ets_mp_url_search_related_product nofilter}';
    var ets_mp_url_search_customer ='{$ets_mp_url_search_customer nofilter}';
    var cover_text = '{l s='Cover' mod='ets_marketplace' js=1}';
    var ets_mp_tax_rule_groups = {literal}{}{/literal};
    {if $tax_rule_groups}
        {foreach from=$tax_rule_groups item='rule_groups'}
            ets_mp_tax_rule_groups[{$rule_groups.id_tax_rules_group|intval}] = {$rule_groups.value_tax|floatval};
        {/foreach}
    {/if}
    {if !in_array('tax',$seller_product_information)}
        var no_user_tax= true;
    {else}
        var no_user_tax = false;
    {/if}
</script>
<form id="ets_mp_product_form" action="" method="post" enctype="multipart/form-data">
    <div class="ets_mp_product_tab_content_header">
        <div class="ets_mp_errors">
        </div>
        <div class="form-group">
            <div class="col-lg-9">
                {if $languages && count($languages)>1}
                    <div class="form-group mp_product_name">
                        {foreach from=$languages item='language'}
                            <div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang!=$id_lang_default} style="display:none;"{/if}>
                                <div class="col-lg-10">
                                    {if isset($valueFieldPost)}
                                        {assign var='value_text' value=$valueFieldPost['name'][$language.id_lang]}
                                    {/if}
                                    <input class="form-control" placeholder="{l s='ex : Pull Ralph Lauren gris' mod='ets_marketplace'}" name="name_{$language.id_lang|intval}" value="{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{/if}"  type="text" />
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
                        {assign var='value_text' value=$valueFieldPost['name'][$id_lang_default]}
                    {/if}
                    <input class="form-control" placeholder="{l s='ex : Pull Ralph Lauren gris' mod='ets_marketplace'}" name="name_{$id_lang_default|intval}" value="{if isset($value_text)}{$value_text|escape:'html':'UTF-8'}{/if}"  type="text" />
                {/if}
            </div>
            <div class="col-lg-3">
                <div class="product_type_select">
                    <select name="product_type" id="product_type" class="form-control {$valueFieldPost.product_type|escape:'html':'UTF-8'}">
                        {if in_array('standard_product',$seller_product_types) || ($valueFieldPost.product_type==0 && $product_class->id)}
                            <option value="0" {if $valueFieldPost.product_type==0} selected="selected"{/if}>{l s='Standard product' mod='ets_marketplace'}</option>
                        {/if}
                        {if in_array('pack_product',$seller_product_types) || $valueFieldPost.product_type==1}
                            <option value="1"{if $valueFieldPost.product_type==1} selected="selected"{/if}>{l s='Pack of products' mod='ets_marketplace'}</option>
                        {/if}
                        {if in_array('virtual_product',$seller_product_types) || $valueFieldPost.product_type==2}
                            <option value="2"{if $valueFieldPost.product_type==2} selected="selected"{/if}>{l s='Virtual product' mod='ets_marketplace'}</option>
                        {/if}
                    </select>
                </div>
            </div>
        </div>
    </div>
    <ul class="ets_mp_product_tab">
        {foreach from=$product_tabs item='product_tab'}
            <li class="ets_mp_tab{if $current_tab==$product_tab.tab} active{/if}" data-tab="{$product_tab.tab|escape:'html':'UTF-8'}">{$product_tab.name|escape:'html':'UTF-8'}</li>
        {/foreach}
    </ul>
    <div class="ets_mp_product_tab_content">
        <input name="id_product" type="hidden" id="ets_mp_id_product" value="{$product_class->id|intval}"/>
        <div class="ets_mp-form-content-setting-combination">
        </div>
        <div class="product_image">
        <!-- product Image  -->
        </div>
        <div class="ets_mp-form-content">
            {foreach from=$product_tabs item='product_tab'}
                <div class="ets_mp_tab_content {$product_tab.tab|escape:'html':'UTF-8'}{if $current_tab==$product_tab.tab} active{/if}">
                    {$product_tab.content_html nofilter}        
                </div>
            {/foreach}
        </div>
        <div class="ets_mp-form-footer">
            {$newMessage nofilter}

            <a class="btn btn-secondary bd text-uppercase" href="{$link->getModuleLink('ets_marketplace','products',['list'=>1])|escape:'html':'UTF-8'}" title="">
                <i class="fa fa-back icon icon-back process-icon-back"></i> {l s='Back' mod='ets_marketplace'}
            </a>
            
            <button name="submitSaveProduct" type="submit" class="btn btn-primary form-control-submit float-xs-right">{if $product_class->id}{l s='Save' mod='ets_marketplace'}{else}{l s='Submit' mod='ets_marketplace'}{/if}</button>
            {if $product_class->id}
                <a class="btn btn-primary float-xs-right preview_product" href="{$link->getProductLink($product_class->id)|escape:'html':'UTF-8'}" target="_blank">{l s='Preview' mod='ets_marketplace'}</a>
            {/if}
        </div>
    </div>
</form>