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
<script>
	var labelNext = '{l s='Next' mod='ets_marketplace' js=1}';
	var labelPrevious = '{l s='Previous' mod='ets_marketplace' js=1}';
	var	labelFinish = '{l s='Finish' mod='ets_marketplace' js=1}';
	var	labelDelete = '{l s='Delete' mod='ets_marketplace' js=1}';
	var	labelValidate = '{l s='Validate' mod='ets_marketplace' js=1}';
    var labelCancel = '{l s='Cancel' mod='ets_marketplace' js=1}';
	var validate_url = '{$link->getModuleLink('ets_marketplace','carrier') nofilter}';
	var carrierlist_url = '{$link->getModuleLink('ets_marketplace','carrier') nofilter}';
	var nbr_steps = 4;
	var enableAllSteps = {if $carrier->id}true{else}false{/if};
	var need_to_validate = '{l s='Please validate the last range before creating a new one.' mod='ets_marketplace' js=1}';
	var delete_range_confirm = '{l s='Are you sure you want to delete this range ?' mod='ets_marketplace' js=1}';
	var currency_sign = '{$currency->sign|escape:'html':'UTF-8'}';
	var PS_WEIGHT_UNIT = 'kg';
	var invalid_range = '{l s='This range is not valid' mod='ets_marketplace' js=1}';
	var overlapping_range = '{l s='Ranges are overlapping' mod='ets_marketplace' js=1}';
	var range_is_overlapping = '{l s='Ranges are overlapping' mod='ets_marketplace' js=1}';
	var select_at_least_one_zone = '{l s='Please select at least one zone' mod='ets_marketplace' js=1}';
	var multistore_enable = '';
    var string_price = '{l s='Will be applied when the price is' mod='ets_marketplace' js=1}';
	var string_weight = '{l s='Will be applied when the weight is' mod='ets_marketplace' js=1}';
    var summary_translation_undefined = '[undefined]';
	var summary_translation_meta_informations = '{l s='This carrier is %1$s and the transit time is %2$s.' mod='ets_marketplace' js=1}';
	var summary_translation_free = '{l s='free' mod='ets_marketplace' js=1}';
	var summary_translation_paid = '{l s='not free' mod='ets_marketplace' js=1}';
	var summary_translation_range = '{l s='This carrier can deliver orders from %1$s to %2$s.' mod='ets_marketplace' js=1}';
	var summary_translation_range_limit =  '{l s='If the order is out of range, the behavior is to %3$s.' mod='ets_marketplace' js=1}';
	var summary_translation_shipping_cost = '{l s='Shipping costs are calculated %1$s and the tax rule %2$s will be applied.' mod='ets_marketplace' js=1}';
	var summary_translation_price = '{l s='according to the price' mod='ets_marketplace' js=1}';
	var summary_translation_weight = '{l s='according to the weight' mod='ets_marketplace' js=1}';
    var default_language ={$id_lang_default|intval};
</script>
<div id="carrier_wizard" class="panel swMain">
    <ul class="steps nbr_steps_4">
        <li>
            <a href="#step-1">
                <span class="stepNumber">1</span>
                <span class="stepDesc">
                     {l s='General settings' mod='ets_marketplace'}
                    <br/>
                </span>
                <span class="chevron"></span>
            </a>
        </li>
        <li>
            <a href="#step-2">
                <span class="stepNumber">2</span>
                <span class="stepDesc">
                     {l s='Shipping locations and costs' mod='ets_marketplace'}
                    <br/>
                </span>
                <span class="chevron"></span>
            </a>
        </li>
        <li>
            <a href="#step-3">
                <span class="stepNumber">3</span>
                <span class="stepDesc">
                     {l s='Size, weight, and group access' mod='ets_marketplace'}
                    <br/>
                </span>
                <span class="chevron"></span>
            </a>
        </li>
        <li>
            <a href="#step-4">
                <span class="stepNumber">4</span>
                <span class="stepDesc">
                     {l s='Summary' mod='ets_marketplace'}
                    <br/>
                </span>
                <span class="chevron"></span>
            </a>
        </li>
    </ul>
    {* step 1*}
    <div id="step-1" class="step_container">
        <form id="step_carrier_general" class="defaultForm form-horizontal" method="post" enctype="multipart/form-data" novalidate="">
            <input id="id_carrier" name="id_carrier" value="{$carrier->id|intval}" type="hidden" />
            <input name="submitAddcarrier" value="1" type="hidden" />
            <div id="fieldset_form" class="panel">
                <div class="form-wrapper">
                    <div class="form-group row">
                        <label class="control-label col-lg-3 required">
                            <span class="label-tooltip">
                            <span class="ets_tooltip" data-tooltip="{l s='Allowed characters: letters, spaces and "().-". The carrier\'s name will be displayed during checkout. For in-store pickup, enter 0 to replace the carrier name with your shop name.' mod='ets_marketplace'}"></span>
                                {l s='Carrier name' mod='ets_marketplace'}
                            </span>
                        </label>
                        <div class="col-lg-9">
                            <input id="name" class="" name="name" value="{$carrier->name|escape:'html':'UTF-8'}" required="required" type="text" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-lg-3 required">
                            <span class="label-tooltip">
                                <span class="ets_tooltip" data-tooltip="{l s='The delivery time will be displayed during checkout.' mod='ets_marketplace'}"></span>
                                {l s='Transit time' mod='ets_marketplace'} 
                            </span>
                        </label>
                        <div class="col-lg-9">
                            {if $languages && count($languages)>1}
                                <div class="form-group row">
                                    {foreach from=$languages item='language'}
                                        <div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang!=$id_lang_default} style="display:none;"{/if}>
                                            <div class="col-lg-11">
                                                <input class="form-control" id="delay_{$language.id_lang|intval}" name="delay_{$language.id_lang|intval}" value="{$delay[$language.id_lang]|escape:'html':'UTF-8'}"  type="text" />
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
                                <input class="form-control " id="delay_{$id_lang_default|intval}" name="delay_{$id_lang_default|intval}" value="{$delay[$id_lang_default]|escape:'html':'UTF-8'}"  type="text" />
                            {/if}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-lg-3">
                            <span class="label-tooltip">
                                <span class="ets_tooltip" data-tooltip="{l s='Enter "0" for a longest shipping delay, or "9" for the shortest shipping delay.' mod='ets_marketplace'}"></span>
                                {l s='Speed grade' mod='ets_marketplace'} 
                            </span>
                        </label>
                        <div class="col-lg-9">
                            <input id="grade" class="" name="grade" value="{$carrier->grade|escape:'html':'UTF-8'}" size="1" type="text" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-lg-3"> {l s='Logo' mod='ets_marketplace'} </label>
                        <div class="col-lg-9">
                            <input type="file" name="logo" />
                            <p class="help-block"> {l s='Accepted formats: jpg, gif, png. Limit:' mod='ets_marketplace'} {Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE')|escape:'html':'UTF-8'}Mb </p>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-lg-3">
                            <span class="label-tooltip"> 
                                <span class="ets_tooltip" data-tooltip="{l s='Delivery tracking URL: Type \'@\' where the tracking number should appear. It will be automatically replaced by the tracking number.' mod='ets_marketplace'}"></span>
                                {l s='Tracking URL' mod='ets_marketplace'} 
                            </span>
                        </label>
                        <div class="col-lg-9">
                            <input id="url" class="" name="url" value="{$carrier->url|escape:'html':'UTF-8'}" type="text" />
                            <p class="help-block"> {l s='For example: \'http://example.com/track.php?num=@\' with \'@\' where the tracking number should appear.' mod='ets_marketplace'} </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>{*end step 1*}
    {*step 2*}
    <div id="step-2" class="step_container">
        <form id="step_carrier_ranges" class="defaultForm form-horizontal" method="post" enctype="multipart/form-data" novalidate="">
            <input id="id_carrier" name="id_carrier" value="{$carrier->id|intval}" type="hidden" />
            <input name="submitAddcarrier" value="1" type="hidden" />
            <div id="fieldset_form_1" class="panel">
                <div class="form-wrapper">
                    <div class="form-group row">
                        <label class="control-label col-lg-3">
                            <span class="label-tooltip">
                                <span class="ets_tooltip" data-tooltip="{l s='Include the handling costs (as set in Shipping > Preferences) in the final carrier price.' mod='ets_marketplace'}"></span>
                                {l s='Add handling costs' mod='ets_marketplace'}
                            </span>
                        </label>
                        <div class="col-lg-9">
                            <span class="switch prestashop-switch fixed-width-lg">
                                <input id="shipping_handling_on" name="shipping_handling" value="1" type="radio"{if $carrier->shipping_handling} checked="checked"{/if} />
                                <label for="shipping_handling_on">{l s='Yes' mod='ets_marketplace'}</label>
                                <input id="shipping_handling_off" name="shipping_handling" value="0"{if !$carrier->shipping_handling} checked="checked"{/if} type="radio" />
                                <label for="shipping_handling_off">{l s='No' mod='ets_marketplace'}</label>
                                <a class="slide-button btn"></a>
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-lg-3">
                            {l s='Free shipping' mod='ets_marketplace'}
                        </label>
                        <div class="col-lg-9">
                            <span class="switch prestashop-switch fixed-width-lg">
                                <input id="is_free_on" name="is_free" value="1" type="radio"{if $carrier->is_free} checked="checked"{/if} />
                                <label for="is_free_on">{l s='Yes' mod='ets_marketplace'}</label>
                                <input id="is_free_off" name="is_free" value="0"{if !$carrier->is_free} checked="checked"{/if} type="radio" />
                                <label for="is_free_off">{l s='No' mod='ets_marketplace'}</label>
                                <a class="slide-button btn"></a>
                            </span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-lg-3"> {l s='Billing' mod='ets_marketplace'} </label>
                        <div class="col-lg-9">
                            <div class="radio t">
                                <label>
                                    <input id="billing_price" name="shipping_method" value="2" type="radio"{if $carrier->shipping_method==2} checked="checked"{/if} />
                                    {l s='According to total price.' mod='ets_marketplace'}
                                </label>
                            </div>
                            <div class="radio t">
                                <label>
                                    <input id="billing_weight" name="shipping_method" value="1" type="radio"{if $carrier->shipping_method==1 || $carrier->shipping_method==0 } checked="checked"{/if} />
                                    {l s='According to total weight.' mod='ets_marketplace'}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-lg-3"> {l s='Tax' mod='ets_marketplace'}</label>
                        <div class="col-lg-9">
                            <select id="id_tax_rules_group" class=" fixed-width-xl" name="id_tax_rules_group">
                                <option value="0">{l s='No tax' mod='ets_marketplace'}</option>
                                {if $tax_rule_groups}
                                    {foreach from=$tax_rule_groups item='tax_rule_group'}
                                        <option value="{$tax_rule_group.id_tax_rules_group|intval}" {if $carrier->getIdTaxRulesGroup()==$tax_rule_group.id_tax_rules_group} selected="selected"{/if}>{$tax_rule_group.name|escape:'html':'UTF-8'}</option>
                                    {/foreach}
                                {/if}
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-lg-3">
                            <span class="label-tooltip">
                                <span class="ets_tooltip" data-tooltip="{l s='Out-of-range behavior occurs when no defined range matches the customer\'s cart (e.g. when the weight of the cart is greater than the highest weight limit defined by the weight ranges).' mod='ets_marketplace'}"></span>
                                {l s='Out-of-range behavior' mod='ets_marketplace'}
                            </span>
                        </label>
                        <div class="col-lg-9">
                            <select id="range_behavior" class=" fixed-width-xl" name="range_behavior">
                                <option value="0"{if $carrier->range_behavior==0} selected="selected"{/if}>{l s='Apply the cost of the highest defined range' mod='ets_marketplace'}</option>
                                <option value="1" {if $carrier->range_behavior==1} selected="selected"{/if}>{l s='Disable carrier' mod='ets_marketplace'}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="ranges_not_follow warn" style="display:none">
                            <label>{l s='Ranges are not correctly ordered:' mod='ets_marketplace'}</label>
                            <a class="btn btn-default" href="#" onclick="checkRangeContinuity(true); return false;">{l s='Reordering' mod='ets_marketplace'}</a>
                        </div>
                        {$carrier_ranges_html nofilter}
                        <div class="new_range">
                            <a id="add_new_range" class="btn btn-default" href="#" onclick="add_new_range();return false;">{l s='Add new range' mod='ets_marketplace'}</a>
                        </div>
                        <div class="col-lg-9 col-lg-offset-3"> </div>
                    </div>
                </div>
            </div>
        </form>
    </div>{*end step2*}
    {*step 3*}
    <div id="step-3" class="step_container">
        <form id="step_carrier_conf" class="defaultForm form-horizontal" method="post" enctype="multipart/form-data" novalidate="">
            <input id="id_carrier" name="id_carrier" value="{$carrier->id|intval}" type="hidden" />
            <input name="submitAddcarrier" value="1" type="hidden" />
            <div id="fieldset_form_2" class="panel">
                <div class="form-wrapper">
                    <div class="form-group row">
                        <label class="control-label col-lg-3">
                            <span class="label-tooltip">
                                <span class="ets_tooltip" data-tooltip="{l s='Maximum width managed by this carrier. Set the value to "0", or leave this field blank to ignore. The value must be an integer.' mod='ets_marketplace'}"></span>
                                {l s='Maximum package width (cm)' mod='ets_marketplace'} 
                            </span>
                        </label>
                        <div class="col-lg-9">
                            <input id="max_width" class="" name="max_width" value="{$carrier->max_width|intval}" type="text" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-lg-3">
                            <span class="label-tooltip">
                                <span class="ets_tooltip" data-tooltip="{l s='Maximum height managed by this carrier. Set the value to "0", or leave this field blank to ignore. The value must be an integer.' mod='ets_marketplace'}"></span>
                                {l s='Maximum package height (cm)' mod='ets_marketplace'} 
                            </span>
                        </label>
                        <div class="col-lg-9">
                            <input id="max_height" class="" name="max_height" value="{$carrier->max_height|intval}" type="text" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-lg-3">
                            <span class="label-tooltip">
                                <span class="ets_tooltip" data-tooltip="{l s='Maximum depth managed by this carrier. Set the value to "0", or leave this field blank to ignore. The value must be an integer.' mod='ets_marketplace'}"></span>
                                {l s='Maximum package depth (cm)' mod='ets_marketplace'} 
                            </span>
                        </label>
                        <div class="col-lg-9">
                            <input id="max_depth" class="" name="max_depth" value="{$carrier->max_depth|intval}" type="text" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-lg-3">
                            <span class="label-tooltip">
                                <span class="ets_tooltip" data-tooltip="{l s='Maximum weight managed by this carrier. Set the value to "0", or leave this field blank to ignore.' mod='ets_marketplace'}"></span>
                                {l s='Maximum package weight (kg)' mod='ets_marketplace'} 
                            </span>
                        </label>
                        <div class="col-lg-9">
                            <input id="max_weight" class="" name="max_weight" value="{$carrier->max_weight|floatval}" type="text" />
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-lg-3">
                            <span class="label-tooltip">
                                <span class="ets_tooltip" data-tooltip="{l s='Mark the groups that are allowed access to this carrier.' mod='ets_marketplace'}"></span>
                                {l s='Group access' mod='ets_marketplace'}
                            </span>
                        </label>
                        <div class="col-lg-9">
                            <div class="row">
                                <div class="col-lg-6">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th class="fixed-width-xs">
                                                    <span class="title_box">
                                                        <input id="checkme" name="checkme" onclick="checkDelBoxes(this.form, 'groupBox[]', this.checked)" type="checkbox" />
                                                    </span>
                                                </th>
                                                <th class="fixed-width-xs">
                                                    <span class="title_box">{l s='ID' mod='ets_marketplace'}</span>
                                                </th>
                                                <th>
                                                    <span class="title_box"> {l s='Group name' mod='ets_marketplace'} </span>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {if $customer_groups}
                                                {foreach from=$customer_groups item='group'}
                                                    <tr>
                                                        <td>
                                                            <input id="groupBox_{$group.id_group|intval}" class="groupBox" name="groupBox[]" value="{$group.id_group|intval}" type="checkbox" {if $group.checked || !$carrier->id} checked="checked"{/if} />
                                                        </td>
                                                        <td>{$group.id_group|intval}</td>
                                                        <td>
                                                            <label for="groupBox_{$group.id_group|intval}">{$group.name|escape:'html':'UTF-8'}</label>
                                                        </td>
                                                    </tr>
                                                {/foreach}
                                            {/if}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>{*end step3*}
    {*step 4*}
    <div id="step-4" class="step_container">
        <div class="defaultForm">
            <div class="panel">
                <div class="panel-heading">
                    {l s='Carrier name:' mod='ets_marketplace'}
                    <strong id="summary_name"></strong>
                </div>
                <div class="panel-body" style="padding: 0;">
                    <p id="summary_meta_informations"></p>
                    <p id="summary_shipping_cost"></p>
                    <p id="summary_range"></p>
                </div>
                <div>
                    {l s='This carrier will be proposed for those delivery zones:' mod='ets_marketplace'}
                    <ul id="summary_zones"></ul>
                </div>
                <div>
                    {l s='And it will be proposed for those client groups:' mod='ets_marketplace'}
                    <ul id="summary_groups"></ul>
                </div>
            </div>
            <form id="step_carrier_summary" class="defaultForm form-horizontal" method="post" enctype="multipart/form-data" novalidate="">
                <input id="id_carrier" name="id_carrier" value="{$carrier->id|intval}" type="hidden" />
                <input name="submitAddcarrier" value="1" type="hidden" />
                <div id="fieldset_form_3" class="panel">
                    <div class="form-wrapper">
                        <div class="form-group row">
                            <label class="control-label col-lg-3">
                                <span class="label-tooltip">
                                    <span class="ets_tooltip" data-tooltip="{l s='Enable the carrier in the front office.' mod='ets_marketplace'}"></span>
                                    {l s='Enabled' mod='ets_marketplace'}
                                </span>
                            </label>
                            <div class="col-lg-9">
                                <span class="switch prestashop-switch fixed-width-lg">
                                    <input id="active_on" name="active" value="1"{if $carrier->active} checked="checked"{/if} type="radio" />
                                    <label for="active_on">{l s='Yes' mod='ets_marketplace'}</label>
                                    <input id="active_off" name="active" value="0" type="radio" />
                                    <label for="active_off">{l s='No' mod='ets_marketplace'}</label>
                                    <a class="slide-button btn"></a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>