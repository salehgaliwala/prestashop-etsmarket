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
<input type="hidden" value="{$current_page|intval}" class="ets_mp_current_tab"/>
<section class="products">
    <div>
        {*<div id="js-product-list-top" class="row products-selection js-product-list-top">
            <input type="hidden" name="idCategories" value="{$idCategories|escape:'html':'UTF-8'}" />
        </div>*}
        <div class="ets_mp_search_product" style="display:none;">
            {if $products || $product_name || $products}
                {*if $products || $product_name}
                    {if $current_tab=='all'}
                        <div class="block-search">
                            <span class="col_search_icon"><i class="fa fa-search"></i></span>
                            <div class="col_search">
                                {if $product_name}
                                    <button name="reset_product_name" class="reset_product_name" title="{l s='Reset' mod='ets_marketplace'}"><i class="fa fa-rotate-left"></i></button>
                                {/if}
                                <input style="text" name="product_search" value="{$product_name|escape:'html':'UTF-8'}" placeholder="{l s='Search' mod='ets_marketplace'}" />
                                <i class="fa fa-search"></i>
                            </div>
                        </div>
                    {/if}
                {/if*}
                {if $products }
                    <div class="col_sortby sort-by-row">
                        <div class="products-sort-order dropdown">
                            <span class="sort-by">{l s='Sort by:' mod='ets_marketplace'}<i class="fa fa-list-ul"></i></span>
                            <ul class="ets_mp_sort_by_dropdown_ul">
                                <li data-value="position.asc" {if isset($smarty.post.order_by) && $smarty.post.order_by=='position.asc'} selected="selected"{/if}>{l s='Relevance' mod='ets_marketplace'}</li>
                                <li data-value="name.asc" {if isset($smarty.post.order_by) && $smarty.post.order_by=='name.asc'} selected="selected"{/if}>{l s='Name, A to Z' mod='ets_marketplace'}</li>
                                <li data-value="name.desc" {if isset($smarty.post.order_by) && $smarty.post.order_by=='name.desc'} selected="selected"{/if}>{l s='Name, Z to A' mod='ets_marketplace'}</li>
                                <li data-value="price.asc" {if isset($smarty.post.order_by) && $smarty.post.order_by=='price.asc'} selected="selected"{/if}>{l s='Price, low to high' mod='ets_marketplace'}</li>
                                <li data-value="price.desc" {if isset($smarty.post.order_by) && $smarty.post.order_by=='price.desc'} selected="selected"{/if}>{l s='Price, high to low' mod='ets_marketplace'}</li>
                                <li data-value="new_product" {if isset($smarty.post.order_by) && $smarty.post.order_by=='new_product'} selected="selected"{/if}>{l s='New products' mod='ets_marketplace'}</li>
                                <li data-value="best_sale" {if isset($smarty.post.order_by) && $smarty.post.order_by=='best_sale'} selected="selected"{/if}>{l s='Best sellers' mod='ets_marketplace'}</li>
                            </ul>
                            <select class="ets_mp_sort_by_product_list">
                                <option value="position.asc" {if isset($smarty.post.order_by) && $smarty.post.order_by=='position.asc'} selected="selected"{/if}>{l s='Relevance' mod='ets_marketplace'}</option>
                                <option value="name.asc" {if isset($smarty.post.order_by) && $smarty.post.order_by=='name.asc'} selected="selected"{/if}>{l s='Name, A to Z' mod='ets_marketplace'}</option>
                                <option value="name.desc" {if isset($smarty.post.order_by) && $smarty.post.order_by=='name.desc'} selected="selected"{/if}>{l s='Name, Z to A' mod='ets_marketplace'}</option>
                                <option value="price.asc" {if isset($smarty.post.order_by) && $smarty.post.order_by=='price.asc'} selected="selected"{/if}>{l s='Price, low to high' mod='ets_marketplace'}</option>
                                <option value="price.desc" {if isset($smarty.post.order_by) && $smarty.post.order_by=='price.desc'} selected="selected"{/if}>{l s='Price, high to low' mod='ets_marketplace'}</option>
                                <option value="new_product" {if isset($smarty.post.order_by) && $smarty.post.order_by=='new_product'} selected="selected"{/if}>{l s='New products' mod='ets_marketplace'}</option>
                                <option value="best_sale" {if isset($smarty.post.order_by) && $smarty.post.order_by=='best_sale'} selected="selected"{/if}>{l s='Best sellers' mod='ets_marketplace'}</option>
                            </select>
                        </div>
                    </div>
                {/if}
            {/if}
        </div>
    </div>
    <div>
        <div class="js-product-list {count($products)|intval}">
            <div class="products row">
                {if $products}
                    {if $is17}
                        {foreach from=$products item="product"}
                              {include file="catalog/_partials/miniatures/product.tpl" product=$product position=""}
                        {/foreach}
                    {else}
                        {include file="$tpl_dir./product-list.tpl" class="product_list grid row products" id="product_page_seller"}
                    {/if}
                {else}
                    <div class="clearfix"></div>
                    <span class="alert alert-warning">{l s='No products available' mod='ets_marketplace'}</span>
                {/if}
            </div>
            {if $paggination}
                <div class="pagination">
                    {$paggination nofilter}
                </div>
            {/if}
        </div>
    </div>
</section>