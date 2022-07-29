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
<div class="form-group">
    <div class="col-lg-12">
        <h2 class="ets_mb_5">{l s='Price' mod='ets_marketplace'}</h2>
    </div>
    <div class="col-lg-12 form-group ets-mp-input-groups">
        <div class="row">
            <div class="col-lg-6 from-group">
                <label class="form-control-label" for="">{l s='Minimum 5 CHF' mod='ets_marketplace'} </label>
                <div>
                    <div class="input-group">
                        <input id="price_excl2" autocomplete="off" placeholder="{l s='CHF 0,00' mod='ets_marketplace'}" name="" value="{$valueFieldPost.price_excl|escape:'html':'UTF-8'}" type="text" />
                        <div class="input-group-append">
                            <span class="input-group-text">{$currency_default->sign|escape:'html':'UTF-8'}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 from-group">
                <label class="form-control-label" for="">{l s='Price (tax incl.)' mod='ets_marketplace'}</label>
                <div>
                    <div class="input-group">
                        <input id="price_incl2" autocomplete="off" placeholder="{l s='CHF 0,00' mod='ets_marketplace'}" name="price_incl" value="{$valueFieldPost.price_incl|escape:'html':'UTF-8'}" type="text" />
                            <div class="input-group-append">
                                <span class="input-group-text">{$currency_default->sign|escape:'html':'UTF-8'}</span>
                            </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 from-group">
                <label class="form-control-label" for="">{l s='Tax rule' mod='ets_marketplace'}</label>
                <div>
                    <select name="id_tax_rules_group2">
                        {foreach from =$tax_rules_groups item='tax_rules_group'}
                            <option value="{$tax_rules_group.id_tax_rules_group|intval}"{if $tax_rules_group.id_tax_rules_group==$valueFieldPost.id_tax_rules_group} selected="selected"{/if}>{$tax_rules_group.name|escape:'html':'UTF-8'}</option>
                        {/foreach}
                    </select>
                </div>
            </div>
            <div class="col-lg-12 from-group clearfix"></div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="">
            <div class="col-md-12">
                <h2>
                  {l s='Specific prices' mod='ets_marketplace'}
                  <span class="help-box">
                      <span>
                      {l s='You can set specific prices for customers belonging to different groups, different countries, etc.' mod='ets_marketplace'}
                      </span>
                  </span>
                </h2>
            </div>
            <div class="col-md-12">
                <div id="specific-price" class="mb-2">
                    <a id="js-open-create-specific-price-form" class="btn btn-outline-primary" href="#specific_price_form">
                        <i class="icon-new"></i>
                        {l s='Add a specific price' mod='ets_marketplace'}
                    </a>
                    <div id="specific_price_form" class="hide" style="">
                        {$specific_prices_from nofilter}
                    </div>
                    <div class="table-responsive">
                        <table id="js-specific-price-list" class="table seo-table">
                            <thead class="thead-default">
                                <tr>
                                  <th>{l s='Rule' mod='ets_marketplace'}</th>
                                  <th>{l s='Combination' mod='ets_marketplace'}</th>
                                  <th>{l s='Currency' mod='ets_marketplace'}</th>
                                  <th>{l s='Country' mod='ets_marketplace'}</th>
                                  <th>{l s='Group' mod='ets_marketplace'}</th>
                                  <th>{l s='Customer' mod='ets_marketplace'}</th>
                                  <th>{l s='Fixed price' mod='ets_marketplace'}</th>
                                  <th>{l s='Impact' mod='ets_marketplace'}</th>
                                  <th>{l s='Period' mod='ets_marketplace'}</th>
                                  <th>{l s='From' mod='ets_marketplace'}</th>
                                  <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                {if $specific_prices}
                                    {foreach from= $specific_prices item='specific_price'}
                                        <tr id="specific_price-{$specific_price.id_specific_price|intval}">
                                            <td>--</td>
                                            <td>
                                                {if $specific_price.id_product_attribute==0}
                                                    {l s='All combinations' mod='ets_marketplace'}
                                                {else}
                                                    {$specific_price.attribute_name|escape:'html':'UTF-8'}
                                                {/if}
                                            </td>
                                            <td>
                                                {if $specific_price.id_currency==0}
                                                    {l s='All currencies' mod='ets_marketplace'}
                                                {else}
                                                    {$specific_price.currency_name|escape:'html':'UTF-8'}
                                                {/if}
                                            </td>
                                            <td>
                                                {if $specific_price.id_country==0}
                                                    {l s='All countries' mod='ets_marketplace'}
                                                {else}
                                                    {$specific_price.country_name|escape:'html':'UTF-8'}
                                                {/if}
                                            </td>
                                            <td>
                                                {if $specific_price.id_group==0}
                                                    {l s='All groups' mod='ets_marketplace'}
                                                {else}
                                                    {$specific_price.group_name|escape:'html':'UTF-8'}
                                                {/if}
                                            </td>
                                            <td>
                                                {if $specific_price.id_customer==0}
                                                    {l s='All customers' mod='ets_marketplace'}
                                                {else}
                                                    {$specific_price.customer_name|escape:'html':'UTF-8'}
                                                {/if}
                                            </td>
                                            <td>
                                                {$specific_price.price_text|escape:'html':'UTF-8'}
                                            </td>
                                            <td>
                                                -{$specific_price.reduction|escape:'html':'UTF-8'}
                                            </td>
                                            <td>
                                                {if $specific_price.from!='0000-00-00 00:00:00' || $specific_price.to!='0000-00-00 00:00:00'}
                                                    {l s='From' mod='ets_marketplace'}: {dateFormat date=$specific_price.from full=1}<br />
                                                    {l s='to' mod='ets_marketplace'}: {dateFormat date=$specific_price.to full=1}<br />
                                                {else}
                                                    {l s='Unlimited' mod='ets_marketplace'}
                                                {/if}
                                            </td>
                                            <td>
                                                {$specific_price.from_quantity|intval}
                                            </td>
                                            <td class="ets-special-edit">
                                                <a title="{l s='Delete' mod='ets_marketplace'}" href="#" class="js-delete delete btn ets_mp_delete_specific delete pl-0 pr-0" data-id_specific_price="{$specific_price.id_specific_price|intval}">
                                                    <i class="icon-delete"></i>{l s='Delete' mod='ets_marketplace'}
                                                </a>
                                                <a title="{l s='Edit' mod='ets_marketplace'}" class="js-edit edit btn tooltip-link delete pl-0 pr-0" href="#" data-id_specific_price="{$specific_price.id_specific_price|intval}">
                                                    <i class="icon-edit"></i>{l s='Edit' mod='ets_marketplace'}
                                                </a>
                                            </td>
                                        </tr>
                                    {/foreach}
                                {/if}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>