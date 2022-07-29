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
{assign var='has_attributes' value=false}
{if $attributeGroups &&  is_array($attributeGroups)}
    {foreach from =$attributeGroups item='attributeGroup'}
        {if $attributeGroup.attributes}
            {assign var='has_attributes' value=true}
        {/if}
    {/foreach}
{/if}
<div class="form-group">
    <div class="ets_mp_combination_left {if $has_attributes}col-lg-9{else}col-lg-12{/if}"> 
        <h2>
            {l s='Manage your product combinations' mod='ets_marketplace'}
            <span class="help-box">
                <span>{l s='Combinations are the different variations of a product, with attributes like its size, weight or color taking different values. To create a combination, you need to create your product attributes first. Go to Catalog > Attributes & Features for this!' mod='ets_marketplace'}</span>
            </span>
        </h2>
        <div id="attributes-generator">
            <div class="alert alert-info">
                <p class="alert-text">
                    {l s='To add combinations, you first need to create proper attributes and values in' mod='ets_marketplace'} <a class="alert-link" href="{$link->getModuleLink('ets_marketplace','attributes')|escape:'html':'UTF-8'}" target="_blank">{l s='Attributes and Features' mod='ets_marketplace'}</a>. <br/> {l s='When done, you may enter the wanted attributes (like "size" or "color") and their respective values ("XS", "red", "all", etc.) in the field below; or simply select them from the right column. Then click on "Generate": it will automatically create all the combinations for you!' mod='ets_marketplace'}
                </p>
            </div>
            {if $has_attributes}
                <div class="row">
                    <div class="col-lg-9">
                        <fieldset class="form-group">
                            <div class="tokenfield form-control">
                                
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-lg-3">
                        <button id="create-combinations" class="btn btn-outline-primary"> {l s='Generate' mod='ets_marketplace'} </button>
                    </div>
                </div>
            {/if}
        </div>
        <div id="combinations-bulk-form" class="{if $productAttributes|Count==0}inactive{/if}">
            <div class="row">
                <div class="col-md-12">
                    <p class="form-control bulk-action ets-mp-bulk-action-form-attribute">
                        <strong>{l s='Bulk actions' mod='ets_marketplace'} (<span class="js-bulk-combinations">0</span>/<span id="js-bulk-combinations-total">{$productAttributes|count}</span> {l s='combination(s) selected' mod='ets_marketplace'})</strong>
                        <i class="icon-arrow_down float-right"></i>
                    </p>
                </div>
                <div id="bulk-combinations-container" class="col-md-12" style="display:none;">
                    <div class="bulk-combinations-container-form">
                        <div id="bulk-combinations-container-fields" class="">
                            <div class="col-lg-4 col-md-3 col-sm-6">
                                <label class="form-control-label">{l s='Quantity' mod='ets_marketplace'}</label>
                                <input id="product_combination_bulk_quantity" class="form-control" name="product_combination_bulk[quantity]" type="text" />
                            </div>
                            <div class="col-lg-4 col-md-3 col-sm-6">
                                <label class="form-control-label">{l s='Cost price' mod='ets_marketplace'}</label>
                                <div class="input-group money-type">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{$default_currency->sign|escape:'html':'UTF-8'} </span>
                                    </div>
                                    <input id="product_combination_bulk_cost_price" name="product_combination_bulk[cost_price]" data-display-price-precision="6" class="form-control" type="text" />
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-3 col-sm-6">
                                <label class="form-control-label">{l s='Impact on weight' mod='ets_marketplace'}</label>
                                <input id="product_combination_bulk_impact_on_weight" class="form-control" name="product_combination_bulk[impact_on_weight]" type="text" />
                            </div>
                            <div class="col-lg-4 col-md-3 col-sm-6">
                                <label class="form-control-label">{l s='Impact on price (tax excl.)' mod='ets_marketplace'}</label>
                                <div class="input-group money-type">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{$default_currency->sign|escape:'html':'UTF-8'}</span>
                                    </div>
                                    <input id="product_combination_bulk_impact_on_price_te" name="product_combination_bulk[impact_on_price_te]" class="form-control" type="text" />
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-3 col-sm-6">
                                <label class="form-control-label">{l s='Impact on price (tax incl.)' mod='ets_marketplace'}</label>
                                <div class="input-group money-type">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">{$default_currency->sign|escape:'html':'UTF-8'}</span>
                                    </div>
                                    <input id="product_combination_bulk_impact_on_price_ti" name="product_combination_bulk[impact_on_price_ti]" class="form-control" type="text" />
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-3 col-sm-6">
                                <label class="form-control-label">{l s='Availability date' mod='ets_marketplace'}</label>
                                <div class="input-group datepicker">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="icon icon-date"></i></span>
                                    </div>
                                    <input id="product_combination_bulk_date_availability" name="product_combination_bulk[date_availability]" class="form-control" type="text" placeholder="YYYY-MM-DD" />

                                </div>
                            </div>
                            <div class="col-lg-4 col-md-3 col-sm-6">
                                <label class="form-control-label">{l s='Reference' mod='ets_marketplace'}</label>
                                <input id="product_combination_bulk_reference" name="product_combination_bulk[reference]" class="form-control" type="text" />
                            </div>
                            <div class="col-lg-4 col-md-3 col-sm-6">
                                <label class="form-control-label">{l s='Minimum quantity' mod='ets_marketplace'}</label>
                                <input id="product_combination_bulk_minimal_quantity" name="product_combination_bulk[minimal_quantity]" class="form-control" type="text" />
                            </div>
                            {if $is17}
                                <div class="col-lg-4 col-md-3 col-sm-6">
                                    <label class="form-control-label">{l s='Low stock level' mod='ets_marketplace'}
                                      <span class="help-box" title="{l s='You can increase or decrease low stock levels in bulk. You cannot disable them in bulk: you have to do it on a per-combination basis.' mod='ets_marketplace'}">
                                          <span class="ets_tooltip" data-tooltip="{l s='You can increase or decrease low stock levels in bulk. You cannot disable them in bulk: you have to do it on a per-combination basis.' mod='ets_marketplace'}" >
                                              
                                          </span>
                                      </span>
                                    </label>
                                    <input id="product_combination_bulk_low_stock_threshold" name="product_combination_bulk[low_stock_threshold]" class="form-control" type="text" />
                                </div>
                            {/if}
                            <div class="col-lg-12 col-md-12 col-sm-12 widget-checkbox-inline">
                                <div class="widget-checkbox-inline">
                                      <div class="checkbox">                          
                                        <label><input id="product_combination_bulk_low_stock_alert" name="product_combination_bulk[low_stock_alert]" value="1" type="checkbox" />
                                            {l s='Send me an email when the quantity is below or equals this level' mod='ets_marketplace'}
                                            <span class="help-box" title="{l s='The email will be sent to all the users who have the right to run the stock page. To modify the permissions, go to Advanced Parameters > Team' mod='ets_marketplace'}" title="">
                                                <span class="ets_tooltip" data-tooltip="{l s='The email will be sent to all the users who have the right to run the stock page. To modify the permissions, go to Advanced Parameters > Team' mod='ets_marketplace'}" >
                                                    
                                                </span>
                                            </span>
                                        </label>
                                      </div>

                                </div>
                            </div>
                        </div>
                        <div class="justify-content-end mt-2">
                            <button id="delete-combinations" class="btn btn-primary mr-2 btn-outline-secondary">
                                <i class="icon icon-delete"></i>
                                {l s='Delete combinations' mod='ets_marketplace'}
                            </button>
                            <button id="apply-on-combinations" class="btn btn-outline-primary">
                                {l s='Apply' mod='ets_marketplace'}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="combinations-list">
            {$list_product_attributes nofilter}
        </div>
    </div>
    <div class="ets_mp_combination_right col-lg-3">
        {if $has_attributes}
            <div id="attributes-list">
                {foreach from=$attributeGroups item='attributeGroup'}
                    {if $attributeGroup.attributes}
                        <div class="attribute-group">
                            <a  class="attribute-group-name" data-toggle="collapse" aria-expanded="true" href="#attribute-group-{$attributeGroup.id_attribute_group|intval}"> {$attributeGroup.name|escape:'html':'UTF-8'} </a>
                            <div id="attribute-group-{$attributeGroup.id_attribute_group|intval}" class="attributes show collapse in" aria-expanded="true">
                                <div class="attributes-overflow">
                                    {foreach from =$attributeGroup.attributes item='attribute'}
                                        <div class="attribute">
                                            <div class="ets_input_group">
                                                <input  name="attribute_options[{$attribute.id_attribute_group|intval}][{$attribute.id_attribute|intval}]" id="attribute-{$attribute.id_attribute|intval}" class="js-attribute-checkbox" data-label="{$attributeGroup.name|escape:'html':'UTF-8'} : {$attribute.name|escape:'html':'UTF-8'}" data-value="{$attribute.id_attribute|intval}" data-group-id="{$attribute.id_attribute_group|intval}" type="checkbox" value="{$attribute.id_attribute|intval}" />
                                                <div class="ets_input_check"></div>
                                            </div>
                                            <label class="attribute-label" for="attribute-{$attribute.id_attribute|intval}">
                                                <span class="pretty-checkbox {if $attributeGroup.is_color_group}ets-item-color{/if} " {if $attributeGroup.is_color_group} {if $attribute.color} style="background-color:{$attribute.color|escape:'html':'UTF-8'}"{elseif isset($attribute.image) && $attribute.image} style="background-image: url('{$attribute.image|escape:'html':'UTF-8'}');"{/if}{/if}> {$attribute.name|escape:'html':'UTF-8'}</span>
                                            </label>
                                        </div>
                                    {/foreach}
                                </div>
                            </div>
                        </div>
                    {/if}
                {/foreach}
            </div>
        {/if}
    </div>
    <div class="clearfix"></div>
</div>
