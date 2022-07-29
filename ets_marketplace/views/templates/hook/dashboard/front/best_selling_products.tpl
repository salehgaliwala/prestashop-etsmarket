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
            <th class="text-center"> {l s='Quantity' mod='ets_marketplace'}</th>
            <th class="text-center"> {l s='Commission' mod='ets_marketplace'}</th>
            <th class="text-center">{l s='Active' mod='ets_marketplace'}</th>
            <th>{l s='Added date' mod='ets_marketplace'}</th>
        </tr>
    </thead>
    <tbody>
        {if $products}
            {foreach from=$products item='product'}
                <tr>
                    <td class="text-center">{$product.id_product|intval}</td>
                    <td class="text-center">{$product.image nofilter}</td>
                    <td>{$product.name nofilter}</td>
                    <td class="text-center">{$product.price|escape:'html':'UTF-8'}</td>
                    <td class="text-center">{$product.quantity_sale|intval}</td>
                    <td class="text-center">{$product.commission|escape:'html':'UTF-8'}</td>
                    <td class="text-center">
                        {if $product.active}
                            <a title="{l s='Active' mod='ets_marketplace'}">
                                <i class="fa fa-check"></i>
                            </a>
                        {else}
                            <a title="{l s='Disabled' mod='ets_marketplace'}">
                                <i class="fa fa-remove"></i>
                            </a>
                        {/if}
                    </td>
                    <td>{dateFormat date=$product.date_add full=1}</td>
                </tr>
            {/foreach}
        {else}
        <tr>
            <td colspan="100%">{l s='No data' mod='ets_marketplace'}</td>
        </tr>
        {/if}
    </tbody>
</table>