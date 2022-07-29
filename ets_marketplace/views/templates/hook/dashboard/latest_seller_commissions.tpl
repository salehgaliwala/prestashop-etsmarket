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
        <th>{l s='Seller name' mod='ets_marketplace'}</th>    
        <th>{l s='Shop name' mod='ets_marketplace'}</th>
        <th class="text-center">{l s='Order ID' mod='ets_marketplace'}</th>
        <th>{l s='Product name' mod='ets_marketplace'}</th>
        <th class="text-center">{l s='Product price' mod='ets_marketplace'}</th>
        <th class="text-center">{l s='Product quantity' mod='ets_marketplace'}</th>
        <th class="text-center">{l s='Commission' mod='ets_marketplace'}</th>
        <th>{l s='Status' mod='ets_marketplace'}</th>
        <th>{l s='Date' mod='ets_marketplace'}</th>
        <th>{l s='Action' mod='ets_marketplace'}</th>
    </tr>
</thead>
<tbody>
    {if $latest_seller_commissions}
        {foreach from=$latest_seller_commissions item='commission'}
            <tr>
                <td class="text-center"> {$commission.id_seller_commission|intval}</td>
                <td class="seller_name">
                    {if $commission.id_customer_seller}
                        <a href="{$module->getLinkCustomerAdmin($commission.id_customer_seller)|escape:'html':'UTF-8'}&viewseller=1&id_seller={$commission.id_seller|intval}">{$commission.seller_name|escape:'html':'UTF-8'}</a>
                    {else}
                        <span class="row_deleted">{l s='Seller deleted' mod='ets_marketplace'}</span>
                    {/if}
                </td>
                <td class="shop_name">
                    {if $commission.id_seller}
                        <a href="{$module->getShopLink(['id_seller'=>$commission.id_seller])|escape:'html':'UTF-8'}">{$commission.shop_name|escape:'html':'UTF-8'}</a>
                    {else}
                        <span class="deleted_shop row_deleted">{l s='Shop deleted' mod='ets_marketplace'}</span>
                    {/if}
                </td>
                <td class="text-center">{if $commission.id_order}{$commission.id_order|escape:'html':'UTF-8'}{else}--{/if}</td>
                <td>{if $commission.product_name}{$commission.product_name nofilter}{else}--{/if}</td>
                <td class="text-center">{if $commission.price_tax_incl!=0}{displayPrice price=$commission.price_tax_incl}{else}--{/if}</td>
                <td class="text-center">{if $commission.quantity}{$commission.quantity|escape:'html':'UTF-8'}{else}--{/if}</td>
                <td class="text-center">{if $commission.commission}{displayPrice price=$commission.commission}{else}--{/if}</td>
                <td>
                    {if $commission.status==-1}
                        <span class="ets_mp_status pending">{l s='Pending' mod='ets_marketplace'}</span>
                    {/if}
                    {if $commission.status==0}
                        <span class="ets_mp_status canceled">{l s='Canceled' mod='ets_marketplace'}</span>
                    {/if}
                    {if $commission.status==1}
                        <span class="ets_mp_status approved">{l s='Approved' mod='ets_marketplace'}</span>
                    {/if}
                </td>
                <td>{dateFormat date=$commission.date_add full=1}</td>
                <td>
                    <a class="btn btn-default" href="{$link->getAdminLink('AdminMarketPlaceSellers')|escape:'html':'UTF-8'}&viewseller=1&id_seller={$commission.id_seller|intval}">
                        <i class="icon-search-plus fa fa-search-plus"></i>
                        {l s='View' mod='ets_marketplace'}
                    </a>
                </td>
            </tr>
        {/foreach}
    {else}
        <tr>
            <td colspan="100%" class="text-center">{l s='No data' mod='ets_marketplace'}</td>
        </tr>
    {/if}
</tbody>
</table>