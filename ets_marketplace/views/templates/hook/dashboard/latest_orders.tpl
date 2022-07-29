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
            <th>{l s='Order reference' mod='ets_marketplace'}</th>
            <th>{l s='Customer' mod='ets_marketplace'}</th>
            <th class="text-center">{l s='Total price (tax incl)' mod='ets_marketplace'}</th>
            <th>{l s='Seller name' mod='ets_marketplace'}</th>
            <th>{l s='Shop name' mod='ets_marketplace'}</th>
            <th class="text-center">{l s='Seller commissions' mod='ets_marketplace'}</th>
            <th class="text-center">{l s='Admin earned' mod='ets_marketplace'}</th>
            <th class="text-center">{l s='Status' mod='ets_marketplace'}</th>
            <th class="text-center">{l s='Date' mod='ets_marketplace'}</th>
            <th class="text-right">{l s='Action' mod='ets_marketplace'}</th>
        </tr>
    </thead>
    <tbody>
    {if $latest_orders}
        {foreach from=$latest_orders item='order'}
            <tr>
                <td class="text-center">{if $order.id_order}{$order.id_order|intval}{else}--{/if}</td>
                <td>{if $order.reference}{$order.reference|escape:'html':'UTF-8'}{else}--{/if}</td>
                <td>{if $order.customer_name}{$order.customer_name nofilter}{else}--{/if}</td>
                <td class="text-center">{if $order.total_paid_tax_incl}{displayPrice price=$order.total_paid_tax_incl currency=$order.id_currency}{/if}</td>
                <td class="seller_name">
                    {if $order.id_customer_seller}
                        <a href="{$module->getLinkCustomerAdmin($order.id_customer_seller)|escape:'html':'UTF-8'}&viewseller=1&id_seller={$order.id_seller|intval}">{$order.seller_name|escape:'html':'UTF-8'}</a>
                    {else}
                        <span class="row_deleted">{l s='Seller deleted' mod='ets_marketplace'}</span>
                    {/if}
                </td>
                <td class="shop_name">
                    {if $order.id_seller}
                        <a href="{$module->getShopLink(['id_seller'=>$order.id_seller])|escape:'html':'UTF-8'}">{$order.shop_name|escape:'html':'UTF-8'}</a>
                    {else}
                        <span class="deleted_shop row_deleted">{l s='Shop deleted' mod='ets_marketplace'}</span>
                    {/if}
                </td>
                <td class="text-center">{if $order.total_commission}{displayPrice price=$order.total_commission}{else}--{/if}</td>
                <td class="text-center">{if $order.admin_earned}{displayPrice price=$order.admin_earned}{else}--{/if}</td>
                <td class="text-center">{if $order.current_state}{$order.current_state nofilter}{else}--{/if}</td>
                <td class="text-center">{dateFormat date=$order.date_add full=1}</td>
                <td class="text-right">
                    <a class="btn btn-default" href="{$link->getAdminLink('AdminOrders')|escape:'html':'UTF-8'}&id_order={$order.id_order|intval}&vieworder">
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