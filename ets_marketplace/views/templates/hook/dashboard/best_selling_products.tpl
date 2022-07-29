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
<table class="table">
    <thead>
        <tr>
            <th class="text-center">{l s='ID' mod='ets_marketplace'}</th>
            <th class="text-center">{l s='Image' mod='ets_marketplace'}</th>
            <th>{l s='Product name' mod='ets_marketplace'}</th>
            <th class="text-center">{l s='Price' mod='ets_marketplace'}</th>
            <th class="text-center"> {l s='Sold quantity' mod='ets_marketplace'}</th>
            <th>{l s='Seller name' mod='ets_marketplace'}</th>
            <th class="text-center">{l s='Active' mod='ets_marketplace'}</th>
            <th>{l s='Added date' mod='ets_marketplace'}</th>
            <th>{l s='Action' mod='ets_marketplace'}</th>
        </tr>
    </thead>
    <tbody>
        {if $products}
            {foreach from=$products item='product'}
                <tr>
                    <td class="text-center">{if $product.id_product}{$product.id_product|intval}{else}--{/if}</td>
                    <td class="text-center">{if $product.image}{$product.image nofilter}{else}--{/if}</td>
                    <td>{if $product.name}{$product.name nofilter}{else}--{/if}</td>
                    <td class="text-center">{if $product.price}{$product.price|escape:'html':'UTF-8'}{else}--{/if}</td>
                    <td class="text-center">{if $product.quantity_sale}{$product.quantity_sale|intval}{else}--{/if}</td>
                    <td class="seller_name">
                        {if $product.id_customer_seller}
                            <a href="{$module->getLinkCustomerAdmin($product.id_customer_seller)|escape:'html':'UTF-8'}&viewseller=1&id_seller={$product.id_seller|intval}">{$product.seller_name|escape:'html':'UTF-8'}</a>
                        {else}
                            <span class="row_deleted">{l s='Seller deleted' mod='ets_marketplace'}</span>
                        {/if}
                    </td> 
                    <td class="text-center">
                        {if $product.active}
                            <i class="fa fa-check" title="{l s='Active' mod='ets_marketplace'}"></i>
                        {else}
                            <i class="fa fa-remove" title="{l s='Disabled' mod='ets_marketplace'}"></i>
                        {/if} 
                    </td>
                    <td>{dateFormat date=$product.date_add full=1}</td>
                    <td>
                        <a class="btn btn-default" href="{$product.link|escape:'html':'UTF-8'}">
                            <i class="icon-search-plus fa fa-search-plus"></i>
                            {l s='View' mod='ets_marketplace'}
                        </a>
                    </td>
                </tr>
            {/foreach}
        {else}
        <tr>
            <td colspan="100%" class="text-center no_data">{l s='No data' mod='ets_marketplace'}</td>
        </tr>
        {/if}
    </tbody>
</table>