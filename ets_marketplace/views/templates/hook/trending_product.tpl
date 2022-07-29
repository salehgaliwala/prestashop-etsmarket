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
* needs please, contact us for extra customization service at an affordable price
*
*  @author ETS-Soft <etssoft.jsc@gmail.com>
*  @copyright  2007-2020 ETS-Soft
*  @license    Valid for 1 website (or project) for each purchase of license
*  International Registered Trademark & Property of ETS-Soft
*}
{assign var='nbItemsPerLine' value=4}
{assign var='nbItemsPerLineTablet' value=3}
{assign var='nbItemsPerLineMobile' value=2}
<script type="text/javascript">
    var ets_mp_nbItemsPerLine ={$nbItemsPerLine|intval};
    var ets_mp_nbItemsPerLineTablet ={$nbItemsPerLineTablet|intval};
    var ets_mp_nbItemsPerLineMobile ={$nbItemsPerLineMobile|intval};
</script>
{if isset($products) && $products}
    <div class="page_seller_follow">
        <h4 class="follow-title">{l s='Trending products' mod='ets_marketplace'}</h4>
    	<div id="page_seller_follow" class="product_list products ets_marketplace_product_list_wrapper slide">
        	{foreach from=$products item="product"}
                  {include file="catalog/_partials/miniatures/product.tpl" product=$product}
            {/foreach}
        </div>
    </div>
{/if}
