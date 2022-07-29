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
    {if isset($products) && $products}
        <div class="featured-products ets_market_products col-xs-12 col-sm-12 ets_mp_products_other">
            <h4 class="follow-title">{l s='Other products from' mod='ets_marketplace'} <a href="{$link_seller|escape:'html':'UTF-8'}">{$seller->shop_name|escape:'html':'UTF-8'}</a> </h4>
           	{include file="$tpl_dir./product-list.tpl" class="product_list grid row products ets_marketpllce_product_list_wrapper" id="list_product_seller"}
        </div>
    {else}
        <div class="no-product crosssell_product_list_wrapper">
            <div class="col-sm-12 col-xs-12"><div class="clearfix"></div><span class="alert alert-warning">{l s='No products available' mod='ets_marketplace'}</span></div>
        </div>
    {/if}
</div>