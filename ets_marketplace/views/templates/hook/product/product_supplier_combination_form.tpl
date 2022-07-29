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
{if $product_suppliers}
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>{$supplier_class->name|escape:'html':'UTF-8'}</strong>
        </div>
        <div id="supplier_combination_{$supplier_class->id|intval}" class="supplier_combination_product panel-body">
            <div>
                <table class="table">
                    <thead class="thead-default">
                        <tr>
                            <th width="30%">{l s='Product name' mod='ets_marketplace'}</th>
                            <th width="30%">{l s='Supplier reference' mod='ets_marketplace'}</th>
                            <th width="20%">{l s='Price (tax excl.)' mod='ets_marketplace'}</th>
                            <th width="20%">{l s='Currency' mod='ets_marketplace'}</th>
                        </tr>
                    </thead>
                    <tbody>
                        {foreach from=$product_suppliers item='product_supplier'}
                            <tr>
                                <td>{$product_supplier.product_name|escape:'html':'UTF-8'}</td>
                                <td>
                                    <input id="form_step6_supplier_combination_{$product_supplier.id_product_attribute|intval}_{$supplier_class->id|intval}_supplier_reference" class="form-control" name="product_supplier_reference[{$supplier_class->id|intval}][{$product_supplier.id_product_attribute|intval}]" type="text" value="{$product_supplier.product_supplier_reference|escape:'html':'UTF-8'}" />
                                </td>
                                <td>
                                    <div class="input-group money-type">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text currency">{$product_supplier.symbol|escape:'html':'UTF-8'} </span>
                                        </div>
                                        <input id="form_step6_supplier_combination_{$product_supplier.id_product_attribute|intval}_{$supplier_class->id|intval}_product_price" class="form-control" name="product_supplier_price[{$supplier_class->id|intval}][{$product_supplier.id_product_attribute|intval}]" value="{$product_supplier.product_supplier_price_te|floatval}" type="text" />
                                    </div>
                                </td>
                                <td>
                                    <select id="form_step6_supplier_combination_{$product_supplier.id_product_attribute|intval}_{$supplier_class->id|intval}_product_price_currency" class="custom-select custom-select-supplier-currency" name="product_supplier_price_currency[{$supplier_class->id|intval}][{$product_supplier.id_product_attribute|intval}]">
                                        {if $currencies}
                                            {foreach from=$currencies item='currency'}
                                                <option value="{$currency.id_currency|intval}"{if $product_supplier.id_currency==$currency.id_currency} selected="selected"{/if} data-symbol="{$currency.symbol|escape:'html':'UTF-8'}">{$currency.name|escape:'html':'UTF-8'}</option>
                                            {/foreach}
                                        {/if}
                                    </select>
                                </td>    
                            </tr>
                        {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
{/if}