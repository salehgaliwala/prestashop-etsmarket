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
<script>
var commissions_line_datasets ={$commissions_line_datasets|json_encode};
var turn_over_bar_datasets ={$turn_over_bar_datasets|json_encode};
var chart_labels=[{foreach from=$chart_labels item='data'}'{$data|escape:'html':'UTF-8'}',{/foreach}];
var charxlabelString = '{l s='Month' mod='ets_marketplace' js=1}';
var charylabelString = '{$default_currency->iso_code|escape:'html':'UTF-8'}';
</script>
<script type="text/javascript" src="{$ets_mp_module_dir|escape:'html':'UTF-8'}views/js/moment.min.js"></script>
<script type="text/javascript" src="{$ets_mp_module_dir|escape:'html':'UTF-8'}views/js/daterangepicker.js"></script>
<script type="text/javascript" src="{$ets_mp_module_dir|escape:'html':'UTF-8'}views/js/dashboard.js"></script>
<div class="ets-sn-admin__content ets_mp-dashboard-page">
    <div class="ets-sn-admin__body">
        <div class="stats-box-info">
            <div class="row margin-15 ">
                <div class="col-lg-2 box-padding-col box-static box-static-turnover">
                    <div class="box-info js-type-info-stats" style="background: #f06295;">
                        <div class="box-inner turnover">
                            <div class="box-inner-top">
                                <h5 class="box-info-title">{l s='Turnover' mod='ets_marketplace'}</h5>
                                <div class="box-info-content"> {displayPrice price=$totalTurnOver} </div>
                            </div>
                            <span>{l s='Total money earned from selling seller products' mod='ets_marketplace'} </span>
                        </div>
                    </div>
                </div>
                {*<div class="col-lg-2 box-padding-col box-static box-static-products">
                    <div class="box-info js-type-info-stats" style="background: #57c2a0;">
                        <div class="box-inner products">
                            <div class="box-inner-top">
                                <h5 class="box-info-title">{l s='Products' mod='ets_marketplace'}</h5>
                                <div class="box-info-content"> {$totalSellerProduct|intval} </div>
                            </div>
                            <span>{l s='Total number of products added by sellers' mod='ets_marketplace'} </span>
                        </div>
                    </div>
                </div>*}
                <div class="col-lg-2 box-padding-col box-static box-static-commissions">
                    <div class="box-info js-type-info-stats" style="background: #f87f6f;">
                        <div class="box-inner commissions">
                            <div class="box-inner-top">
                                <h5 class="box-info-title">{l s='Seller commissions' mod='ets_marketplace'}</h5>
                                <div class="box-info-content"> {displayPrice price=$totalSellerCommission|floatval} </div>
                            </div>
                            <span>{l s='Total commission that all sellers have earned' mod='ets_marketplace'} </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 box-padding-col box-static box-static-earning">
                    <div class="box-info js-type-info-stats" style="background: #45bbe2;">
                        <div class="box-inner earning">
                            <div class="box-inner-top">
                                <h5 class="box-info-title">{l s='Admin earning' mod='ets_marketplace'}</h5>
                                {assign var=totalAdminEarning value=$totalSellerRevenve+$totalSellerFee}
                                <div class="box-info-content"> {displayPrice price=$totalAdminEarning|floatval} </div>
                            </div>
                            <span>{l s='Total money admin earned from seller products and membership fee' mod='ets_marketplace'} </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 box-padding-col box-static box-static-revenve">
                    <div class="box-info js-type-info-stats" style="background: #ff546b;">
                        <div class="box-inner revenve">
                            <div class="box-inner-top">
                                <h5 class="box-info-title">{l s='Revenue' mod='ets_marketplace'}</h5>
                                <div class="box-info-content"> {displayPrice price=$totalSellerRevenve|floatval} </div>
                            </div>
                            <span>{l s='Total money admin earned from seller products' mod='ets_marketplace'} </span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-2 box-padding-col box-static box-static-fee">
                    <div class="box-info js-type-info-fee" style="background: #fbbb21;">
                        <div class="box-inner revenve">
                            <div class="box-inner-top">
                                <h5 class="box-info-title">{l s='Membership fee' mod='ets_marketplace'}</h5>
                                <div class="box-info-content"> {displayPrice price=$totalSellerFee|floatval} </div>
                            </div>
                            <span>{l s='Total money admin earned from membership fee' mod='ets_marketplace'} </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section-item ets_mp-section-commissions">
            <div class="row margin-15 row-991">
                <div class="col-lg-4 col-xs-4 col-sm-4 plr-15">
                    <div class="stats-data-commissions">
                        <div class="stats-container">
                            <div class="stats-body">
                                <div class="box-dashboard line-chart-commissions">
                                    <div class="box-header">
                                        <h4 class="box-title">
                                            {l s='Admin earning' mod='ets_marketplace'}
                                            <i class="fa fa-question-circle">
                                                <span class="ets_tooltip" data-tooltip="top">{l s='Total money admin earned from successfully sold products of sellers and the fee which sellers paid to maintain seller accounts' mod='ets_marketplace'}</span>
                                            </i>
                                        </h4>

                                        <div class="box-tool-dropdown">
                                            <select name="filter-time-stats-commissions">
                                                <option value="all_time">{l s='All time' mod='ets_marketplace'}</option>
                                                <option value="this_month">{l s='This month' mod='ets_marketplace'}</option>
                                                <option value="_month">{l s='Month -1' mod='ets_marketplace'}</option>
                                                <option value="this_year" selected="selected">{l s='This year' mod='ets_marketplace'}</option>
                                                <option value="time_range">{l s='Time range' mod='ets_marketplace'}</option>
                                            </select>
                                        </div>
                                        <div class="box-tool">
                                            <div class="box-tool-timerange box-date-ranger" style="display: none;">
                                                <input class="ajax-date-range ets_mp_date_ranger_filter" type="text" autocomplete="off" />
                                                <input class="date_from_commissions" value="" type="hidden" />
                                                <input class="date_to_commissions" value="" type="hidden" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box-body">
                                        <div class="no_data" {if !$no_data_char_commission}style="display:none;"{/if}>{l s='No data' mod='ets_marketplace'}</div>
                                        <canvas id="ets_mp_stats_commision_line" {if $no_data_char_commission}style="display:none;"{/if}>
                                            
                                        </canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xs-4 col-sm-4 plr-15">
                    <div class="stats-data-commissions">
                        <div class="stats-container">
                            <div class="stats-body">
                                <div class="box-dashboard bar-chart-turn-over">
                                    <div class="box-header">
                                        <h4 class="box-title">
                                            {l s='Turnover & Seller commissions' mod='ets_marketplace'}
                                            <i class="fa fa-question-circle">
                                                <span class="ets_tooltip" data-tooltip="top">{l s='Total money earned from selling seller products and total commission that all sellers of your website have earned' mod='ets_marketplace'}</span>
                                            </i>
                                        </h4>

                                        <div class="box-tool-dropdown">
                                            <select name="filter-time-stats-turnover">
                                                <option value="all_time">{l s='All time' mod='ets_marketplace'}</option>
                                                <option value="this_month">{l s='This month' mod='ets_marketplace'}</option>
                                                <option value="_month">{l s='Month -1' mod='ets_marketplace'}</option>
                                                <option value="this_year" selected="selected">{l s='This year' mod='ets_marketplace'}</option>
                                                <option value="time_range">{l s='Time range' mod='ets_marketplace'}</option>
                                            </select>
                                        </div>
                                        <div class="box-tool">
                                            <div class="box-tool-timerange box-date-ranger" style="display: none;">
                                                <input class="ajax-date-range ets_mp_date_ranger_filter" type="text" autocomplete="off" />
                                                <input class="date_from_order" value="" type="hidden" />
                                                <input class="date_to_order" value="" type="hidden" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box-body">
                                        <div class="no_data" {if !$no_data_char_turn_over}style="display:none;"{/if}>{l s='No data' mod='ets_marketplace'}</div>
                                        <canvas id="ets_mp_stats_turn-over_bar" {if $no_data_char_turn_over}style="display:none;"{/if}>
                                        </canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-xs-4 col-sm-4 plr-15">
                    <div class="stats-data-commissions">
                        <div class="stats-container">
                            <div class="stats-body">
                                <div class="box-dashboard latest-withdrawals">
                                    <div class="box-header">
                                        <h4 class="box-title">
                                            {l s='Latest withdrawals request' mod='ets_marketplace'}
                                            <i class="fa fa-question-circle">
                                                <span class="ets_tooltip" data-tooltip="top">{l s='Latest withdrawal requests from sellers' mod='ets_marketplace'}</span>
                                            </i>
                                        </h4>
                                    </div>
                                    <div class="box-body">
                                        <div id="ets_mp_list_withdrawals">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">{l s='ID' mod='ets_marketplace'}</th>
                                                        <th>{l s='Seller name' mod='ets_marketplace'}</th>
                                                        <th class="text-center">{l s='Amount' mod='ets_marketplace'}</th>
                                                        <th>{l s='Status' mod='ets_marketplace'}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {if $last_withdraws}
                                                        {foreach from=$last_withdraws item='withdraw'}
                                                            <tr>
                                                                <td class="text-center">
                                                                    {$withdraw.id_ets_mp_withdrawal|intval}
                                                                </td>
                                                                <td class="seller_name">
                                                                    {if $withdraw.id_customer_seller}
                                                                        <a href="{$module->getLinkCustomerAdmin($withdraw.id_customer_seller)|escape:'html':'UTF-8'}&viewseller=1&id_seller={$withdraw.id_seller|intval}">{$withdraw.seller_name|escape:'html':'UTF-8'}</a>
                                                                    {else}
                                                                        <span class="row_deleted">{l s='Seller deleted' mod='ets_marketplace'}</span>
                                                                    {/if}
                                                                </td>
                                                                <td class="text-center">
                                                                    {displayPrice price =$withdraw.amount}
                                                                </td>
                                                                <td >
                                                                    {if $withdraw.status==0}
                                                                        <span class="ets_mp_status pending">{l s='Pending' mod='ets_marketplace'}</span>
                                                                    {/if}
                                                                    {if $withdraw.status==-1}
                                                                        <span class="ets_mp_status declined">{l s='Declined' mod='ets_marketplace'}</span>
                                                                    {/if}
                                                                    {if $withdraw.status==1}
                                                                        <span class="ets_mp_status approved">{l s='Approved' mod='ets_marketplace'}</span>
                                                                    {/if}
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
                                            {if $last_withdraws}
                                                <span class="text-center view_detail">
                                                    <a href="{$link->getAdminLink('AdminMarketPlaceWithdrawals')|escape:'html':'UTF-8'}">{l s='View all' mod='ets_marketplace'}</a>
                                                </span>
                                            {/if}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xs-6 col-sm-6 plr-15">
                    <div class="stats-data-commissions">
                        <div class="stats-container">
                            <div class="stats-body">
                                <div class="box-dashboard latest-payment-billings">
                                    <div class="box-header">
                                        <h4 class="box-title">
                                            {l s='Latest membership' mod='ets_marketplace'}
                                            <i class="fa fa-question-circle">
                                                <span class="ets_tooltip" data-tooltip="top">{l s='Latest payment billings generated when sellers pay for membership fee' mod='ets_marketplace'}</span>
                                            </i>
                                        </h4>
                                    </div>
                                    <div class="box-body">
                                        <div id="ets_mp_list_billings">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">{l s='ID' mod='ets_marketplace'}</th>
                                                        <th>{l s='Seller name' mod='ets_marketplace'}</th>
                                                        <th>{l s='Shop name' mod='ets_marketplace'}</th>
                                                        <th class="text-center">{l s='Amount' mod='ets_marketplace'}</th>
                                                        <th>{l s='Status' mod='ets_marketplace'}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    {if $last_payment_billings}
                                                        {foreach from=$last_payment_billings item='billing'}
                                                            <tr>
                                                                <td class="text-center">{$billing.id_ets_mp_seller_billing|intval}</td>
                                                                <td class="seller_name">
                                                                    {if $billing.id_customer_seller}
                                                                        <a href="{$module->getLinkCustomerAdmin($billing.id_customer_seller)|escape:'html':'UTF-8'}&viewseller=1&id_seller={$billing.id_seller|intval}">{$billing.seller_name|escape:'html':'UTF-8'}</a>
                                                                    {else}
                                                                        <span class="row_deleted">{l s='Seller deleted' mod='ets_marketplace'}</span>
                                                                    {/if}
                                                                </td>
                                                                <td>
                                                                    {if $billing.id_seller}
                                                                        <a href="{$module->getShopLink(['id_seller'=>$billing.id_seller])|escape:'html':'UTF-8'}" target="_blank">{$billing.shop_name|escape:'html':'UTF-8'}</a>
                                                                    {else}
                                                                        <span class="deleted_shop row_deleted">{l s='Shop deleted' mod='ets_marketplace'}</span>
                                                                    {/if}
                                                                </td>
                                                                <td class="text-center">{displayPrice price=$billing.amount}</td>
                                                                <td>
                                                                    {if $billing.active==-1}
                                                                        <span class="ets_mp_status deducted">{l s='Canceled' mod='ets_marketplace'}</span>
                                                                    {/if}
                                                                    {if $billing.active==0}
                                                                        <span class="ets_mp_status pending">{l s='Pending' mod='ets_marketplace'}</span>
                                                                    {/if}
                                                                    {if $billing.active==1}
                                                                        <span class="ets_mp_status purchased">{l s='Paid' mod='ets_marketplace'}</span>
                                                                    {/if}
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
                                            {if $last_payment_billings}
                                                <span class="text-center view_detail">
                                                    <a href="{$link->getAdminLink('AdminMarketPlaceBillings')|escape:'html':'UTF-8'}">{l s='View all' mod='ets_marketplace'}</a>
                                                </span>
                                            {/if}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xs-6 col-sm-6 plr-15">
                    <div class="stats-data-commissions">
                        <div class="stats-container">
                            <div class="stats-body">
                                <div class="box-dashboard going-to-expired">
                                    <div class="box-header">
                                        <h4 class="box-title">
                                            {l s='Seller accounts are going to be expired' mod='ets_marketplace'}
                                            <i class="fa fa-question-circle">
                                                <span class="ets_tooltip" data-tooltip="top">{l s='Seller accounts need to be renewed soon' mod='ets_marketplace'}</span>
                                            </i>
                                        </h4>
                                    </div>
                                    <div class="box-body">
                                        <div id="ets_mp_list_billings">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">{l s='ID' mod='ets_marketplace'}</th>
                                                        <th>{l s='Seller name' mod='ets_marketplace'}</th>
                                                        <th>{l s='Seller email' mod='ets_marketplace'}</th>
                                                        <th>{l s='Expiration date' mod='ets_marketplace'}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                {if $going_tobe_expired_sellers}
                                                    {foreach from=$going_tobe_expired_sellers item='seller'}
                                                        <tr>
                                                            <td class="text-center">{$seller.id_seller|intval}</td>
                                                            <td><a href="{$link->getAdminLink('AdminMarketPlaceSellers')|escape:'html':'UTF-8'}&viewseller=1&id_seller={$seller.id_seller|intval}">{$seller.seller_name|escape:'html':'UTF-8'}</a></td>
                                                            <td>{$seller.seller_email|escape:'html':'UTF-8'}</td>
                                                            <td>{dateFormat date=$seller.date_to full=0}</td>
                                                        </tr>
                                                    {/foreach}
                                                {else}
                                                    <tr>
                                                        <td colspan="100%" class="text-center no_data">{l s='No data' mod='ets_marketplace'}</td>
                                                    </tr>
                                                {/if}
                                                </tbody>
                                            </table>
                                            {if $going_tobe_expired_sellers}
                                                <span class="text-center view_detail">
                                                    <a href="{$link->getAdminLink('AdminMarketPlaceSellers')|escape:'html':'UTF-8'}">{l s='View all' mod='ets_marketplace'}</a>
                                                </span>
                                            {/if}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 px-10 sale_products_form" id="ets_mp_dashboards">
                <div class="panel ets_mp-position-relative js-ets_mp-dashboard">
                    <div class="panel-header">
                        <div class="box-header">
                            <h4 class="box-title">
                                {l s='Sales, products & sellers' mod='ets_marketplace'}
                                <i class="fa fa-question-circle">
                                    <span class="ets_tooltip" data-tooltip="top">{l s='Statistics about sales, seller products and sellers' mod='ets_marketplace'}</span>
                                </i>
                            </h4>
                        </div>
                    </div>
                    <div class="panel-bodys pt-0">
                        <ul id="nav-tab-rank" class="nav nav-pills nav-tabs" role="tablist">
                            <li class="active" role="presentation">
                                <a href="#statis_latest_orders" aria-controls="tab_statis_lates_orders" role="tab" data-toggle="tab">
                                    <i class="fa fa-latest-order"></i>
                                    {l s='Latest orders' mod='ets_marketplace'}
                                    <i class="fa fa-question-circle">
                                        <span class="ets_tooltip" data-tooltip="top">{l s='Latest orders from the shops of sellers' mod='ets_marketplace'}</span>
                                    </i>
                                </a>
                            </li>
                            <li class="" role="presentation">
                                <a href="#statis_latest_seller_commissions" aria-controls="tab_latest_seller_commissions" role="tab" data-toggle="tab">
                                    <i class="fa fa-latest-seller-commissions"></i>
                                    {l s='Latest seller commissions' mod='ets_marketplace'}
                                    <i class="fa fa-question-circle">
                                        <span class="ets_tooltip" data-tooltip="top">{l s='Latest commissions which sellers earned by selling seller products' mod='ets_marketplace'}</span>
                                    </i>
                                </a>
                            </li>
                            <li class="" role="presentation">
                                <a href="#statis_latest_products" aria-controls="tab_statis_latest_products" role="tab" data-toggle="tab">
                                    <i class="fa fa-lastest-products"></i>
                                    {l s='Latest products' mod='ets_marketplace'}
                                    <i class="fa fa-question-circle">
                                        <span class="ets_tooltip" data-tooltip="top">{l s='Latest products added by sellers' mod='ets_marketplace'}</span>
                                    </i>
                                </a>
                            </li>
                            <li class="" role="presentation">
                                <a href="#statis_best_selling_products" aria-controls="tab_statis_best_selling_products" role="tab" data-toggle="tab">
                                    <i class="fa fa-best-selling-products"></i>
                                    {l s='Best selling products' mod='ets_marketplace'}
                                    <i class="fa fa-question-circle">
                                        <span class="ets_tooltip" data-tooltip="top">{l s='Best selling products from shops of sellers' mod='ets_marketplace'}</span>
                                    </i>
                                </a>
                            </li>
                            <li class="" role="presentation">
                                <a href="#statis_top_seller" aria-controls="tab_statis_top_seller" role="tab" data-toggle="tab">
                                    <i class="fa fa-top-seller"></i>
                                    {l s='Top sellers' mod='ets_marketplace'}
                                    <i class="fa fa-question-circle">
                                        <span class="ets_tooltip" data-tooltip="top">{l s='Sellers who have the largest number of sold products' mod='ets_marketplace'}</span>
                                    </i>
                                </a>
                            </li>
                            <li class="" role="presentation">
                                <a href="#statis_top_seller_commission" aria-controls="tab_statis_top_seller_commission" role="tab" data-toggle="tab">
                                    <i class="fa fa-top-seller-commission"></i>
                                    {l s='Top seller commissions' mod='ets_marketplace'}
                                    <i class="fa fa-question-circle">
                                        <span class="ets_tooltip" data-tooltip="top">{l s='Sellers who earned the largest amount of commission' mod='ets_marketplace'}</span>
                                    </i>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div id="statis_latest_orders" class="tab-pane active ets_mp-content-tab" role="tabpanel">
                                <div class="sub-panel">
                                    <div class="panel-body">
                                        {$latest_orders nofilter}
                                    </div>
                                </div>
                            </div>
                            <div id="statis_latest_seller_commissions" class="tab-pane ets_mp-content-tab" role="tabpanel">
                                <div class="sub-panel">
                                    <div class="panel-body">
                                        {$latest_seller_commissions nofilter}
                                    </div>
                                </div>
                            </div>
                            <div id="statis_latest_products" class="tab-pane ets_mp-content-tab" role="tabpanel">
                                <div class="sub-panel">
                                    <div class="panel-body">
                                        {$latest_products nofilter}
                                    </div>
                                </div>
                            </div>
                            <div id="statis_best_selling_products" class="tab-pane ets_mp-content-tab" role="tabpanel">
                                <div class="sub-panel">
                                    <div class="panel-body">
                                        {$best_selling_products nofilter}
                                    </div>
                                </div>
                            </div>
                            <div id="statis_top_seller" class="tab-pane ets_mp-content-tab" role="tabpanel">
                                <div class="sub-panel">
                                    <div class="panel-body">
                                        {$top_sellers nofilter}
                                    </div>
                                </div>
                            </div>
                            <div id="statis_top_seller_commission" class="tab-pane ets_mp-content-tab" role="tabpanel">
                                <div class="sub-panel">
                                    <div class="panel-body">
                                        {$top_seller_commissions nofilter}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>