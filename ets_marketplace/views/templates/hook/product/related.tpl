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
<label class="col-lg-3 form-control-label" for="">{l s='Related product' mod='ets_marketplace'}</label>
<div class="col-lg-9">
    <div id="related-content" class="row{if !$related_products} hide{/if}">
        <div class="col-xl-8 col-lg-11">
            <fieldset class="form-group">
                <div class="autocomplete-search">
                    <div class="search search-with-icon">
                        <span class="twitter-typeahead" style="position: relative; display: block;">
                            <input id="form_step1_related_products" class="form-control search typeahead form_step1_related_products tt-input" placeholder="{l s='Search and add a related product' mod='ets_marketplace'}" autocomplete="off" spellcheck="false" dir="auto" style="position: relative; vertical-align: top;" type="text" />
                        </span>
                    </div>
                </div>
            </fieldset>
            <small class="form-text text-muted text-right typeahead-hint"> </small>
            <ul id="form_step1_related_products-data" class="typeahead-list nostyle col-sm-12 product-list">
                {if $related_products}
                    {foreach from=$related_products item='related_product'}
                        <li class="media">
                            <div class="media-left">
                                {if isset($related_product.img) && $related_product.img}
                                    <img class="media-object image" src="{$related_product.img|escape:'html':'UTF-8'}" />
                                {/if}
                            </div>
                            <div class="media-body media-middle">
                                <span class="label">{$related_product.name|escape:'html':'UTF-8'}{if $related_product.reference} (ref:{$related_product.reference|escape:'html':'UTF-8'}){/if}</span>
                                <i class="fa fa-times delete delete_related"></i>
                            </div>
                            <input name="related_products[]" value="{$related_product.id_product|intval}" type="hidden" />
                        </li>
                    {/foreach}
                {/if}
            </ul>
            <div id="tplcollection-form_step1_related_products" class="invisible">
                <span class="label">%s</span>
                <i class="icon delete-icon"></i>
            </div>
        </div>
        <div class="col-md-1">
            <fieldset class="form-group">
                <a id="reset_related_product" class="btn tooltip-link delete pl-0 pr-0">
                    <i class="fa fa-trash-o"></i>
                </a>
            </fieldset>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <button id="add-related-product-button" class="btn btn-outline-primary" type="button"{if $related_products} style="display:none;"{/if} >
                <i class="icon-new"></i>
                {l s='Add a related product' mod='ets_marketplace'}
            </button>
        </div>
    </div>
</div>