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
<script type="text/javascript">
    var commissions_line_datasets ={$commissions_line_datasets|json_encode nofilter};
    var chart_labels=[{foreach from=$chart_labels item='data'}'{$data|escape:'html':'UTF-8'}',{/foreach}];
    var charxlabelString = '{l s='Month' mod='ets_marketplace' js=1}';
    var charylabelString = '{$current_currency->iso_code|escape:'html':'UTF-8'}';
    var ets_mp_url_search_product='{$ets_mp_url_search_product nofilter}';
</script>
<div class="ets_mp-dashboard-page">
    
    <div class="stats-box-info">
        <div class="row margin-15 ">
            <div class="col-lg-3 box-padding-col box-static box-static-turnover">
                <div class="box-info js-type-info-stats" style="background: #f06295;">
                    <div class="box-inner turnover">
                        <div class="box-inner-top">
                            <h5 class="box-info-title">{l s='Turnover' mod='ets_marketplace'}</h5>
                            <div class="box-info-content"> {displayPrice price=$total_turn_over|floatval} </div>
                            <div class="box-tooltip button">{l s='Total money you have earned from selling products' mod='ets_marketplace'}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 box-padding-col box-static box-static-commission-balance">
                <div class="box-info js-type-info-stats" style="background: #57c2a0;">
                    <div class="box-inner commission-balance">
                        <div class="box-inner-top">
                            <h5 class="box-info-title">{l s='Commission balance' mod='ets_marketplace'}</h5>
                            <div class="box-info-content"> {displayPrice price=$total_commission_balance|floatval} </div>
                            <div class="box-tooltip button">{l s='Total commission available to withdraw, pay for order, convert to voucher codes' mod='ets_marketplace'}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 box-padding-col box-static box-static-withdrawals">
                <div class="box-info js-type-info-stats" style="background: #f87f6f;">
                    <div class="box-inner withdrawals">
                        <div class="box-inner-top">
                            <h5 class="box-info-title">{l s='Withdrawals' mod='ets_marketplace'}</h5>
                            <div class="box-info-content"> {displayPrice price=$total_withdrawls|floatval} </div>
                            <div class="box-tooltip button">{l s='Total money you have withdrawal successfully' mod='ets_marketplace'}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 box-padding-col box-static box-static-used-commission">
                <div class="box-info js-type-info-stats" style="background: #45bbe2;">
                    <div class="box-inner used-commission">
                        <div class="box-inner-top">
                            <h5 class="box-info-title">{l s='Commission' mod='ets_marketplace'}</h5>
                            <div class="box-info-content"> {displayPrice price=$total_commission_used|floatval} </div>
                            <div class="box-tooltip button">{l s='Total commission money you have withdrawal, paid for orders, converted into voucher' mod='ets_marketplace'}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section-item ets_mp-section-commissions">
        <div class="title titleblock">{l s='Statistics' mod='ets_marketplace'}</div>
        <div class="row margin-15 row-991">
            <div class="col-lg-12 col-xs-12 col-sm-12 plr-15">
                <div class="stats-data-commissions">
                    <div class="stats-container">
                        <div class="stats-body">
                            <div class="box-dashboard line-chart-commissions">
                                <div class="box-header">
                                    <div class="stats-options-left">
                                        <div class="box-tool-buttons">
                                            <label for="chart_by_product_all"><input type="radio" name="chart_by_product" id="chart_by_product_all" value="all" checked="checked" />{l s='All products' mod='ets_marketplace'}</label>
                                            <label for="chart_by_product_search"><input type="radio" name="chart_by_product" id="chart_by_product_search" value="search" />{l s='Single product' mod='ets_marketplace'}</label>
                                            <div class="box-tool">
                                                <div class="box-tool-search box-search" style="display: none;">
                                                    <input type="hidden" value="" name="id_product_chart" id="id_product_chart" />
                                                    <input id="product_search_chart" name="product_search_chart" placeholder="{l s='Enter product name, ID or reference' mod='ets_marketplace'}" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="stats-options-right">
                                        <div class="box-tool-dropdown">
                                            <select name="filter-time-stats-commissions">
                                                <option value="all_time">{l s='All time' mod='ets_marketplace'}</option>
                                                <option value="this_month">{l s='This month' mod='ets_marketplace'}</option>
                                                <option value="_month">{l s='Month-1' mod='ets_marketplace'}</option>
                                                <option value="this_year">{l s='This year' mod='ets_marketplace'}</option>
                                                <option value="_year">{l s='Year-1' mod='ets_marketplace'}</option>
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
                                </div>
                                <div class="box-body">
                                    {*<div class="no_data" {if !$no_data_char_commission}style="display:none;"{/if}>{l s='No data' mod='ets_marketplace'}</div>*}
                                    <canvas id="ets_mp_stats_commision_line" {*if $no_data_char_commission}style="display:none;"{/if*}>
                                        
                                    </canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 col-xs-12 col-sm-12 plr-15">
                <table class="table">
                    <tr>
                        <td>{l s='Total number of product sold' mod='ets_marketplace'}</td>
                        <td class="text-right" id="total_number_of_product_sold">{$total_number_of_product_sold|intval}</td>
                    </tr>
                    <tr>
                        <td>{l s='Turnover' mod='ets_marketplace'}</td>
                        <td class="text-right" id="total_turn_over">{displayPrice price=$total_turn_over|floatval}</td>
                    </tr>
                    <tr>
                        <td>{l s='Earning commission' mod='ets_marketplace'}</td>
                        <td class="text-right" id="total_earning_commission">{displayPrice price=$total_earning_commission|floatval}</td>
                    </tr>
                </table>
            </div>
            <div class="col-lg-12 col-xs-12 col-sm-12 plr-15 list-best-selling-products">
                <h3>{l s='Best selling products' mod='ets_marketplace'}</h3>
                {$best_selling_products nofilter}
            </div>
        </div>
    </div>
</div>