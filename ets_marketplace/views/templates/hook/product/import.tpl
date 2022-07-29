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
<div id="container-customer" class="panel">
    <h3>
        <i class="icon-list-alt"></i>
        {l s='View your data' mod='ets_marketplace'}
    </h3>
    <div class="alert alert-info">
        <p>{l s='Please match each column of your source CSV file to one of the destination columns.' mod='ets_marketplace'}</p>
    </div>
    <form id="import_form" class="form-horizontal" action="" method="post" name="import_form">
        <div class="form-group">
            <div class="col-lg-12 pd_0">
                <div class="scroll_form">
                <table id="table0" class="table table-bordered" style="display: table;">
                    <thead>
                        <tr>
                            <th>
                                <select name="col_product_name">
                                    <option value="0" selected="selected">{l s='Name' mod='ets_marketplace'}</option>
                                    <option value="1">{l s='Image' mod='ets_marketplace'}</option>
                                    <option value="2">{l s='Quantity' mod='ets_marketplace'}</option>
                                    <option value="3">{l s='Price' mod='ets_marketplace'}</option>
                                    <option value="4">{l s='Description' mod='ets_marketplace'}</option>
                                    <option value="5">{l s='Summary' mod='ets_marketplace'}</option>
                                    <option value="6">{l s='Link rewrite' mod='ets_marketplace'}</option>
                                    <option value="7">{l s='Categories' mod='ets_marketplace'}</option>
                                    <option value="8">{l s='Default category' mod='ets_marketplace'}</option>
                                    <option value="9">{l s='Combinations' mod='ets_marketplace'}</option>
                                    <option value="10">{l s='Specific price' mod='ets_marketplace'}</option>
                                </select>
                            </th>
                            <th>
                                <select name="col_product_image">
                                    <option value="0">{l s='Name' mod='ets_marketplace'}</option>
                                    <option value="1" selected="selected">{l s='Image' mod='ets_marketplace'}</option>
                                    <option value="2">{l s='Quantity' mod='ets_marketplace'}</option>
                                    <option value="3">{l s='Price' mod='ets_marketplace'}</option>
                                    <option value="4">{l s='Description' mod='ets_marketplace'}</option>
                                    <option value="5">{l s='Summary' mod='ets_marketplace'}</option>
                                    <option value="6">{l s='Link rewrite' mod='ets_marketplace'}</option>
                                    <option value="7">{l s='Categories' mod='ets_marketplace'}</option>
                                    <option value="8">{l s='Default category' mod='ets_marketplace'}</option>
                                    <option value="9">{l s='Combinations' mod='ets_marketplace'}</option>
                                    <option value="10">{l s='Specific price' mod='ets_marketplace'}</option>
                                </select>
                            </th>
                            <th>
                                <select name="col_product_quantity">
                                    <option value="0">{l s='Name' mod='ets_marketplace'}</option>
                                    <option value="1">{l s='Image' mod='ets_marketplace'}</option>
                                    <option value="2" selected="selected">{l s='Quantity' mod='ets_marketplace'}</option>
                                    <option value="3">{l s='Price' mod='ets_marketplace'}</option>
                                    <option value="4">{l s='Description' mod='ets_marketplace'}</option>
                                    <option value="5">{l s='Summary' mod='ets_marketplace'}</option>
                                    <option value="6">{l s='Link rewrite' mod='ets_marketplace'}</option>
                                    <option value="7">{l s='Categories' mod='ets_marketplace'}</option>
                                    <option value="8">{l s='Default category' mod='ets_marketplace'}</option>
                                    <option value="9">{l s='Combinations' mod='ets_marketplace'}</option>
                                    <option value="10">{l s='Specific price' mod='ets_marketplace'}</option>
                                </select>
                            </th>
                            <th>
                                <select name="col_product_price">
                                    <option value="0">{l s='Name' mod='ets_marketplace'}</option>
                                    <option value="1">{l s='Image' mod='ets_marketplace'}</option>
                                    <option value="2">{l s='Quantity' mod='ets_marketplace'}</option>
                                    <option value="3" selected="selected">{l s='Price' mod='ets_marketplace'}</option>
                                    <option value="4">{l s='Description' mod='ets_marketplace'}</option>
                                    <option value="5">{l s='Summary' mod='ets_marketplace'}</option>
                                    <option value="6">{l s='Link rewrite' mod='ets_marketplace'}</option>
                                    <option value="7">{l s='Categories' mod='ets_marketplace'}</option>
                                    <option value="8">{l s='Default category' mod='ets_marketplace'}</option>
                                    <option value="9">{l s='Combinations' mod='ets_marketplace'}</option>
                                    <option value="10">{l s='Specific price' mod='ets_marketplace'}</option>
                                </select>
                            </th>
                            <th>
                                <select name="col_product_description">
                                    <option value="0">{l s='Name' mod='ets_marketplace'}</option>
                                    <option value="1">{l s='Image' mod='ets_marketplace'}</option>
                                    <option value="2">{l s='Quantity' mod='ets_marketplace'}</option>
                                    <option value="3">{l s='Price' mod='ets_marketplace'}</option>
                                    <option value="4" selected="selected">{l s='Description' mod='ets_marketplace'}</option>
                                    <option value="5">{l s='Summary' mod='ets_marketplace'}</option>
                                    <option value="6">{l s='Link rewrite' mod='ets_marketplace'}</option>
                                    <option value="7">{l s='Categories' mod='ets_marketplace'}</option>
                                    <option value="8">{l s='Default category' mod='ets_marketplace'}</option>
                                    <option value="9">{l s='Combinations' mod='ets_marketplace'}</option>
                                    <option value="10">{l s='Specific price' mod='ets_marketplace'}</option>
                                </select>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {if $datas}
                            {foreach from=$datas item='data'}
                                <tr>
                                    <td>{if isset($data[0])}{$data[0]|escape:'html':'UTF-8'}{/if}</td>
                                    <td>{if isset($data[1])}{$data[1]|escape:'html':'UTF-8'}{/if}</td>
                                    <td>{if isset($data[2])}{$data[2]|escape:'html':'UTF-8'}{/if}</td>
                                    <td>{if isset($data[3])}{$data[3]|escape:'html':'UTF-8'}{/if}</td>
                                    <td>{if isset($data[4])}{$data[4]|escape:'html':'UTF-8'}{/if}</td>
                                </tr>
                            {/foreach}
                        {/if}
                    </tbody>
                </table>
                <table id="table1" class="table table-bordered" style="display:none;">
                    <thead>
                        <tr>
                            <th>
                                <select name="col_product_description_short">
                                    <option value="0">{l s='Name' mod='ets_marketplace'}</option>
                                    <option value="1">{l s='Image' mod='ets_marketplace'}</option>
                                    <option value="2">{l s='Quantity' mod='ets_marketplace'}</option>
                                    <option value="3">{l s='Price' mod='ets_marketplace'}</option>
                                    <option value="4">{l s='Description' mod='ets_marketplace'}</option>
                                    <option value="5" selected="selected">{l s='Summary' mod='ets_marketplace'}</option>
                                    <option value="6">{l s='Link rewrite' mod='ets_marketplace'}</option>
                                    <option value="7">{l s='Categories' mod='ets_marketplace'}</option>
                                    <option value="8">{l s='Default category' mod='ets_marketplace'}</option>
                                    <option value="9">{l s='Combinations' mod='ets_marketplace'}</option>
                                    <option value="10">{l s='Specific price' mod='ets_marketplace'}</option>
                                </select>
                            </th>
                            <th>
                                <select name="col_product_link_rewrite">
                                    <option value="0">{l s='Name' mod='ets_marketplace'}</option>
                                    <option value="1">{l s='Image' mod='ets_marketplace'}</option>
                                    <option value="2">{l s='Quantity' mod='ets_marketplace'}</option>
                                    <option value="3">{l s='Price' mod='ets_marketplace'}</option>
                                    <option value="4">{l s='Description' mod='ets_marketplace'}</option>
                                    <option value="5">{l s='Summary' mod='ets_marketplace'}</option>
                                    <option value="6" selected="selected">{l s='Link rewrite' mod='ets_marketplace'}</option>
                                    <option value="7">{l s='Categories' mod='ets_marketplace'}</option>
                                    <option value="8">{l s='Default category' mod='ets_marketplace'}</option>
                                    <option value="9">{l s='Combinations' mod='ets_marketplace'}</option>
                                    <option value="10">{l s='Specific price' mod='ets_marketplace'}</option>
                                </select>
                            </th>
                            <th>
                                <select name="col_product_category">
                                    <option value="0" >{l s='Name' mod='ets_marketplace'}</option>
                                    <option value="1">{l s='Image' mod='ets_marketplace'}</option>
                                    <option value="2">{l s='Quantity' mod='ets_marketplace'}</option>
                                    <option value="3">{l s='Price' mod='ets_marketplace'}</option>
                                    <option value="4">{l s='Description' mod='ets_marketplace'}</option>
                                    <option value="5">{l s='Summary' mod='ets_marketplace'}</option>
                                    <option value="6">{l s='Link rewrite' mod='ets_marketplace'}</option>
                                    <option value="7" selected="selected">{l s='Categories' mod='ets_marketplace'}</option>
                                    <option value="8">{l s='Default category' mod='ets_marketplace'}</option>
                                    <option value="9">{l s='Combinations' mod='ets_marketplace'}</option>
                                    <option value="10">{l s='Specific price' mod='ets_marketplace'}</option>
                                </select>
                            </th>
                            <th>
                                <select name="col_product_default_category">
                                    <option value="0">{l s='Name' mod='ets_marketplace'}</option>
                                    <option value="1">{l s='Image' mod='ets_marketplace'}</option>
                                    <option value="2">{l s='Quantity' mod='ets_marketplace'}</option>
                                    <option value="3">{l s='Price' mod='ets_marketplace'}</option>
                                    <option value="4">{l s='Description' mod='ets_marketplace'}</option>
                                    <option value="5">{l s='Summary' mod='ets_marketplace'}</option>
                                    <option value="6">{l s='Link rewrite' mod='ets_marketplace'}</option>
                                    <option value="7">{l s='Categories' mod='ets_marketplace'}</option>
                                    <option value="8" selected="selected">{l s='Default category' mod='ets_marketplace'}</option>
                                    <option value="9">{l s='Combinations' mod='ets_marketplace'}</option>
                                    <option value="10">{l s='Specific price' mod='ets_marketplace'}</option>
                                </select>
                            </th>
                            <th>
                                <select name="col_product_combination">
                                    <option value="0">{l s='Name' mod='ets_marketplace'}</option>
                                    <option value="1">{l s='Image' mod='ets_marketplace'}</option>
                                    <option value="2">{l s='Quantity' mod='ets_marketplace'}</option>
                                    <option value="3">{l s='Price' mod='ets_marketplace'}</option>
                                    <option value="4">{l s='Description' mod='ets_marketplace'}</option>
                                    <option value="5">{l s='Summary' mod='ets_marketplace'}</option>
                                    <option value="6">{l s='Link rewrite' mod='ets_marketplace'}</option>
                                    <option value="7">{l s='Categories' mod='ets_marketplace'}</option>
                                    <option value="8">{l s='Default category' mod='ets_marketplace'}</option>
                                    <option value="9" selected="selected">{l s='Combinations' mod='ets_marketplace'}</option>
                                    <option value="10">{l s='Specific price' mod='ets_marketplace'}</option>
                                </select>
                            </th>
                            <th>
                                <select name="col_specific_price">
                                    <option value="0">{l s='Name' mod='ets_marketplace'}</option>
                                    <option value="1">{l s='Image' mod='ets_marketplace'}</option>
                                    <option value="2">{l s='Quantity' mod='ets_marketplace'}</option>
                                    <option value="3">{l s='Price' mod='ets_marketplace'}</option>
                                    <option value="4">{l s='Description' mod='ets_marketplace'}</option>
                                    <option value="5">{l s='Summary' mod='ets_marketplace'}</option>
                                    <option value="6">{l s='Link rewrite' mod='ets_marketplace'}</option>
                                    <option value="7">{l s='Categories' mod='ets_marketplace'}</option>
                                    <option value="8">{l s='Default category' mod='ets_marketplace'}</option>
                                    <option value="9">{l s='Combinations' mod='ets_marketplace'}</option>
                                    <option value="10" selected="selected">{l s='Specific price' mod='ets_marketplace'}</option>
                                </select>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        {if $datas}
                            {foreach from=$datas item='data'}
                                <tr>
                                    <td>{if isset($data[5])}{$data[5]|escape:'html':'UTF-8'}{/if}</td>
                                    <td>{if isset($data[6])}{$data[6]|escape:'html':'UTF-8'}{/if}</td>
                                    <td>{if isset($data[7])}{$data[7]|escape:'html':'UTF-8'}{/if}</td>
                                    <td>{if isset($data[8])}{$data[8]|escape:'html':'UTF-8'}{/if}</td>
                                    <td>{if isset($data[9])}{$data[9]|escape:'html':'UTF-8'}{/if}</td>
                                    <td>{if isset($data[10])}{$data[10]|escape:'html':'UTF-8'}{/if}</td>
                                </tr>
                            {/foreach}
                        {/if}
                    </tbody>
                </table>
                </div>
                <button id="btn_import_left" class="btn btn-primary pull-left" type="button" disabled="disabled">
                    <i class="fa fa-chevron-sign-left"></i> {l s='Prev' mod='ets_marketplace'}
                </button>
                <button id="btn_import_right" class="btn btn-primary pull-right" type="button">
                    {l s='Next' mod='ets_marketplace'} <i class="fa fa-chevron-sign-right"></i>
                </button>
            </div>
        </div>
        <div class="panel-footer">
            <button class="btn btn-primary pull-left" type="submit" name="cancelSubmitImport">
                {l s='Cancel' mod='ets_marketplace'}
            </button>
            <button id="import" class="btn btn-primary pull-right" name="submitImportProduct" type="submit">
                {l s='Import .CSV data' mod='ets_marketplace'}
            </button>
        </div>
    </form>
</div>