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

<div id="js_form_step1_inputPackItems">
    <h2 class="title-products">{l s='List of products for this pack' mod='ets_marketplace'}</h2>
    <ul id="form_step1_inputPackItems-data" class="typeahead-list pack nostyle row">
        {if $pack_products}
            {foreach from=$pack_products item='pack_product'}
                <li class="col-xl-3 col-lg-6 mb-1" data-product="{$pack_product.id_product_item|intval}-{$pack_product.id_product_attribute_item|intval}">
                    <div class="pack-product">
                        <img class="cover" src="{$pack_product.url_image|escape:'html':'UTF-8'}" />
                        <h4>{$pack_product.name|escape:'html':'UTF-8'} {$pack_product.attribute_name|escape:'html':'UTF-8'}</h4>
                        {if $pack_product.reference}
                            <div class="ref">{l s='REF:' mod='ets_marketplace'} {$pack_product.reference|escape:'html':'UTF-8'}</div>
                        {/if}
                        <div class="quantity text-md-right">x{$pack_product.quantity|intval}</div>
                        <input class="inputPackItems" name="inputPackItems[]" value="{$pack_product.id_product_item|intval}x{$pack_product.id_product_attribute_item|intval}x{$pack_product.quantity|intval}" type="hidden" />
                        <button class="btn btn-danger btn-sm delete ets_mp_delete_pack_product" type="button" title="{l s='Delete' mod='ets_marketplace'}">
                            <i class="icon delete-icon"></i>
                        </button>
                    </div>
                </li>
            {/foreach}
        {/if}
    </ul>
    <h2>{l s='Add products to your pack' mod='ets_marketplace'}</h2>
    <div class="row">
        <div id="search_product_pack_content" style="display:none;">
            
        </div>
        <div class="col-lg-6">
            <input type="text" id="search_product_pack" name="search_product_pack" placeholder="{l s='Search for a product' mod='ets_marketplace'}"/>
        </div>
        
        <div class="col-lg-3">
            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">×</span>
                </div>
                <input id="form_step1_inputPackItems-curPackItemQty" class="form-control curPackItemQty" min="1" value="1" type="number" />
            </div>
        </div>
        <div class="col-lg-3">
            <div class="input-group">
                <button id="form_step1_inputPackItems-curPackItemAdd" class="btn btn-secondary btn-block">
                <i class="icon new-icon"></i>{l s='Add' mod='ets_marketplace'}
                </button>
            </div>
        </div>
    </div>
</div>