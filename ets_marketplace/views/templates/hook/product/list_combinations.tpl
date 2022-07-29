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

{if $productAttributes}
    <table class="table">
        <thead id="combinations_thead" class="thead-default">
            <tr>
            <th>
                <input id="toggle-all-combinations" type="checkbox" />
            </th>
            <th></th>
            <th>{l s='Combinations' mod='ets_marketplace'}</th>
            <th>{l s='Impact on price' mod='ets_marketplace'}</th>
            <th class="text-center">{l s='Final price' mod='ets_marketplace'}</th>
            <th>{l s='Quantity' mod='ets_marketplace'}</th>
            <th colspan="3" class="text-sm-right">{l s='Default combination' mod='ets_marketplace'}</th>
            </tr>
        </thead>
        <tbody>
            {foreach from=$productAttributes item='productAttribute'}
                <tr id="attribute_{$productAttribute.id_product_attribute|intval}" class="combination loaded" data="{$productAttribute.id_product_attribute|intval}" data-index="{$productAttribute.id_product_attribute|intval}" style="display: table-row;">
                    <td width="1%">
                        <input name="list_product_attributes[]" class="js-combination" value="{$productAttribute.id_product_attribute|intval}" data-id="{$productAttribute.id_product_attribute|intval}" data-index="{$productAttribute.id_product_attribute|intval}" type="checkbox" />
                    </td>
                    <td class="img">
                        <img class="img-responsive" src="" />
                    </td>
                    <td>{$productAttribute.name_attribute|escape:'html':'UTF-8'}</td>
                    <td class="attribute-price text-center">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">{$default_currency->sign|escape:'html':'UTF-8'}</span>
                            </div>
                            <input name="price_product_attributes[]" data-id_product_attribute="{$productAttribute.id_product_attribute|intval}" class="ets-mp-price-impact attribute_priceTE form-control text-sm-right price_product_attributes" value="{$productAttribute.price|floatval}" type="text" />
                        </div> 
                    </td>
                    <td class="attribute-finalprice text-center">
                        <span data-price="{$product_class->price|floatval}" data-uniqid="{$productAttribute.id_product_attribute|intval}">{$product_class->price+$productAttribute.price|floatval}</span>{$default_currency->sign|escape:'html':'UTF-8'}
                    </td>
                    <td class="attribute-quantity">
                        <div>
                            <input data-id_product_attribute="{$productAttribute.id_product_attribute|intval}" name="quantity_product_attributes[]" class="quantity_product_attributes form-control text-sm-right" value="{$productAttribute.quantity|intval}" type="text" />
                        </div>
                    </td>
                    <td class="attribute-actions edit">
                        <div class="btn-group btn-group-sm" role="group">
                            <a class="btn btn-open tooltip-link btn-sm" href="#combination_form_{$productAttribute.id_product_attribute|intval}">
                                <i class="icon-edit">{l s='Edit' mod='ets_marketplace'}</i>
                            </a>
                            <div id="combination_form_{$productAttribute.id_product_attribute|intval}" class="combination-form hide" data="{$productAttribute.id_product_attribute|intval}" style="">
                                <div class="col-sm-12 nav">
                                </div>
                                <div class="panel col-md-12 p-2">
                                    <div class="">
                                        <button type="button" class="back-to-product btn-outline-secondary btn btn-back">
                                            <i class="icon-back">arrow_back</i> {l s='Back to product' mod='ets_marketplace'}</button>
                                    </div>
                                    <h2 class="title pt-2"> {l s='Combination details' mod='ets_marketplace'} - {$productAttribute.name_attribute|escape:'html':'UTF-8'} </h2>
                                    <div class="checkbox">                          
                                        <label><input id="combination_{$productAttribute.id_product_attribute|intval}_attribute_default" name="combinations_attribute_default[{$productAttribute.id_product_attribute|intval}]" class="attribute_default_checkbox" value="1"{if $productAttribute.default_on} checked="checked"{/if} type="checkbox" /> {l s='Set as default combination' mod='ets_marketplace'}</label>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-2">
                                            <fieldset class="form-group">
                                                <label class="form-control-label">{l s='Quantity' mod='ets_marketplace'} </label>
                                                <input data-id_product_attribute="{$productAttribute.id_product_attribute|intval}" id="combination_{$productAttribute.id_product_attribute|intval}_attribute_quantity" class="combinations_attribute_quantity form-control" name="combinations_attribute_quantity[{$productAttribute.id_product_attribute|intval}]"  value="{$productAttribute.quantity|intval}" type="text" />
                                            </fieldset>
                                        </div>
                                        <div class="col-md-3">
                                            <fieldset class="form-group">
                                              <label class="form-control-label">{l s='Availability date' mod='ets_marketplace'}</label>
                                              <div class="input-group datepicker">
                                                <input class="form-control" id="combination_{$productAttribute.id_product_attribute|intval}_available_date_attribute" name="combinations_attribute_available_date[{$productAttribute.id_product_attribute|intval}]" placeholder="YYYY-MM-DD" value="{$productAttribute.available_date|escape:'html':'UTF-8'}" type="text" />
                                                    <div class="input-group-append">
                                                        <div class="input-group-text">
                                                            <i class="icon icon-date"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-4">
                                            <fieldset class="form-group">
                                                <label class="form-control-label">
                                                    {l s='Min. quantity for sale' mod='ets_marketplace'}
                                                    <span class="help-box">
                                                        <span>{l s='The minimum quantity required to buy this product (set to 1 to disable this feature). E.g.: if set to 3, customers will be able to purchase the product only if they take at least 3 in quantity.' mod='ets_marketplace'}
                                                        </span>
                                                    </span>
                                                </label>
                                                <input id="combination_{$productAttribute.id_product_attribute|intval}_attribute_minimal_quantity" name="combinations_attribute_minimal_quantity[{$productAttribute.id_product_attribute|intval}]" class="form-control" value="{$productAttribute.minimal_quantity|intval}" type="text" />
                                            </fieldset>
                                        </div>
                                        <div class="col-md-3">
                                            <fieldset class="form-group">
                                                <label class="form-control-label">{l s='Reference' mod='ets_marketplace'}</label>
                                                <input id="combination_{$productAttribute.id_product_attribute|intval}_attribute_reference" value="{if isset($productAttribute.reference) && $productAttribute.reference}{$productAttribute.reference|escape:'html':'utf-8'}{/if}" name="combinations_attribute_reference[{$productAttribute.id_product_attribute|intval}]" class="form-control" type="text" />
                                            </fieldset>
                                        </div>
                                    </div>
                                    <h2 class="title"> {l s='Stock' mod='ets_marketplace'}</h2>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <fieldset class="form-group">
                                                  <label class="form-control-label">{l s='Stock location' mod='ets_marketplace'}</label>
                                                  <input id="combination_{$productAttribute.id_product_attribute|intval}_attribute_location" name="combinations_attribute_location[{$productAttribute.id_product_attribute|intval}]" {$productAttribute.location|escape:'html':'UTF-8'} class="form-control" type="text" />
                                            </fieldset>
                                        </div>
                                        {if $is17}
                                            <div class="col-md-3">
                                                <fieldset class="form-group">
                                                    <label class="form-control-label">{l s='Low stock level' mod='ets_marketplace'}</label>
                                                    <input id="combination_{$productAttribute.id_product_attribute|intval}_attribute_low_stock_threshold" name="combinations_attribute_low_stock_threshold[{$productAttribute.id_product_attribute|intval}]" placeholder="{l s='Leave empty to disable' mod='ets_marketplace'}" class="form-control" value="{$productAttribute.low_stock_threshold|intval}" type="text" />
                                                </fieldset>
                                            </div>
                                        {/if}
                                        {if isset($productAttribute.low_stock_alert)}
                                            <div class="col-md-9">
                                                <fieldset class="form-group widget-checkbox-inline">
                                                    <label class="form-control-label"> </label>
                                                    <div class="widget-checkbox-inline">
                                                        <label class="form-control-label">
                                                            <input id="combination_{$productAttribute.id_product_attribute|intval}_attribute_low_stock_alert" name="combination_attribute_low_stock_alert[{$productAttribute.id_product_attribute|intval}]" value="1" {if $productAttribute.low_stock_alert}checked="checked"{/if} type="checkbox" />
                                                            {l s='Send me an email when the quantity is below or equals this level' mod='ets_marketplace'}
                                                            <span class="help-box" title="">
                                                                <span> {l s='The email will be sent to all the users who have the right to run the stock page.' mod='ets_marketplace'} </span>
                                                            </span>
                                                        </label>
                                                    </div>
                                                </fieldset>
                                            </div>
                                        {/if}
                                    </div>
                                    <h2 class="title">{l s='Price and impact' mod='ets_marketplace'}</h2>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <fieldset class="form-group">
                                                <label class="form-control-label">{l s='Cost price' mod='ets_marketplace'}</label> 
                                                <div class="input-group money-type">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">{$default_currency->sign|escape:'html':'UTF-8'}</span>
                                                    </div>
                                                    <input id="combination_{$productAttribute.id_product_attribute|intval}_attribute_wholesale_price" name="combinations_attribute_wholesale_price[{$productAttribute.id_product_attribute|intval}]" class="attribute_wholesale_price form-control" value="{$productAttribute.wholesale_price|escape:'html':'UTF-8'}" type="text" />
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-4">
                                            <fieldset class="form-group">
                                                <label class="form-control-label">
                                                    {l s='Impact on price (tax excl.)' mod='ets_marketplace'}
                                                    <span class="help-box" title="">
                                                        <span>
                                                            {l s='Does this combination have a different price? Is it cheaper or more expensive than the default retail price?' mod='ets_marketplace'}
                                                        </span>
                                                    </span>
                                                </label>
                                                <div class="input-group money-type">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">{$default_currency->sign|escape:'html':'UTF-8'}</span>
                                                    </div>
                                                        <input data-id_product_attribute="{$productAttribute.id_product_attribute|intval}" id="combination_{$productAttribute.id_product_attribute|intval}_attribute_price" name="combinations_attribute_price[{$productAttribute.id_product_attribute|intval}]" class="attribute_priceTE form-control" value="{$productAttribute.price|escape:'html':'UTF-8'}" type="text" />
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-4">
                                            <fieldset class="form-group">
                                                <label class="form-control-label">{l s='Impact on price (tax incl.)' mod='ets_marketplace'}</label>
                                                <div class="input-group money-type">
                                                    <div class="input-group-prepend">
                                                    <span class="input-group-text">{$default_currency->sign|escape:'html':'UTF-8'} </span>
                                                </div>
                                                    <input id="combination_{$productAttribute.id_product_attribute|intval}_attribute_priceTI" data-id_product_attribute="{$productAttribute.id_product_attribute|intval}" name="combinations_attribute_priceTI[{$productAttribute.id_product_attribute|intval}]" class="attribute_priceTI form-control" value="{$productAttribute.price_tax_incl|escape:'html':'UTF-8'}" type="text" />
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <div class="col-md-4">
                                            <fieldset class="form-group">
                                                <label class="form-control-label">
                                                    {l s='Impact on price per unit (tax excl.)' mod='ets_marketplace'}
                                                    <span class="help-box">
                                                        <span>
                                                            {l s='Does this combination have a different price per unit?' mod='ets_marketplace'}
                                                        </span>
                                                     </span>
                                                </label>
                                                <div class="input-group money-type">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">{$default_currency->sign|escape:'html':'UTF-8'} </span>
                                                    </div>
                                                    <input id="combination_{$productAttribute.id_product_attribute|intval}_attribute_unity" name="combinations_attribute_unity[{$productAttribute.id_product_attribute|intval}]" class="attribute_unity form-control" value="{$productAttribute.unit_price_impact|floatval}" type="text" />
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-4">
                                            <fieldset class="form-group">
                                                <label class="form-control-label">{l s='Impact on weight' mod='ets_marketplace'}</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text">kg</span>
                                                    </div>
                                                    <input id="combination_{$productAttribute.id_product_attribute|intval}_attribute_weight" name="combinations_attribute_weight[{$productAttribute.id_product_attribute|intval}]" class="form-control" value="{$productAttribute.weight|floatval}" type="text" />
                                                </div>
                                            </fieldset>
                                        </div>
                                        <div class="col-md-4">
                                            <span class="form-control-label vcenter">
                                                {l s='Final retail price (tax excl.) will be' mod='ets_marketplace'}
                                                <span class="final-price" data-price="{$product_class->price+$productAttribute.price|floatval}" data-uniqid="{$productAttribute.id_product_attribute|intval}">{$product_class->price+$productAttribute.price|floatval}</span>
                                                {$default_currency->sign|escape:'html':'UTF-8'}
                                            </span>
                                        </div>
                                    </div>
                                    <h2 class="title">{l s='Specific references' mod='ets_marketplace'}</h2>
                                    <div class="row form-group">
                                        {if isset($productAttribute.isbn)}
                                            <div class="col-md-4">
                                                <fieldset class="form-group">
                                                    <label class="form-control-label">{l s='ISBN code' mod='ets_marketplace'}</label>
                                                    <input id="combination_{$productAttribute.id_product_attribute|intval}_attribute_isbn" name="combinations_attribute_isbn[{$productAttribute.id_product_attribute|intval}]" class="form-control" value="{$productAttribute.isbn|escape:'html':'UTF-8'}" type="text" />
                                                </fieldset>
                                            </div>
                                        {/if}
                                        <div class="col-md-4">
                                            <fieldset class="form-group">
                                                <label class="form-control-label">
                                                    {l s='EAN-13 or JAN barcode' mod='ets_marketplace'}
                                                    <span class="help-box">
                                                        <span>{l s='This type of product code is specific to Europe and Japan, but is widely used internationally. It is a superset of the UPC code: all products marked with an EAN will be accepted in North America.' mod='ets_marketplace'}
                                                        </span>
                                                    </span>
                                                </label>
                                                <input id="combination_{$productAttribute.id_product_attribute|intval}_attribute_ean13" name="combinations_attribute_ean13[{$productAttribute.id_product_attribute|intval}]" class="form-control" value="{$productAttribute.ean13|escape:'html':'UTF-8'}" type="text" />
                                            </fieldset>
                                        </div>
                                        <div class="col-md-4">
                                            <fieldset class="form-group">
                                                <label class="form-control-label">{l s='UPC barcode' mod='ets_marketplace'}</label>
                                                <input id="combination_{$productAttribute.id_product_attribute|intval}_attribute_upc" name="combinations_attribute_upc[{$productAttribute.id_product_attribute|intval}]" class="form-control" type="text" />
                                            </fieldset>
                                        </div>
                                    </div>
                                    <h2 class="title">{l s='Images' mod='ets_marketplace'}</h2>
                                    <div class="row form-group">
                                        <div class="col-md-12">
                                            <fieldset class="form-group js-combination-images">
                                                <label>
                                                    <small class="form-control-label">{l s='Select images of this combination:' mod='ets_marketplace'}</small>
                                                    <small class="form-control-label number-of-images">{if $product_images}{$productAttribute.images|count}/{$product_images|count}{/if}</small>
                                                </label>
                                                <div id="combination_{$productAttribute.id_product_attribute|intval}_id_image_attr" class="images">
                                                    {if $product_images}
                                                        {foreach from=$product_images item='product_image'}
                                                            <div class="product-combination-image{if in_array($product_image.id_image,$productAttribute.images)} img-highlight{/if}">
                                                                <input name="combination_id_image_attr[{$productAttribute.id_product_attribute|intval}][]" value="{$product_image.id_image|intval}"{if in_array($product_image.id_image,$productAttribute.images)} checked="checked"{/if} type="checkbox" />
                                                                <img src="{$product_image.link|escape:'html':'UTF-8'}" alt="" title="" />
                                                            </div>
                                                        {/foreach}
                                                    {/if}
                                                </div>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <input id="combination_{$productAttribute.id_product_attribute|intval}_id_product_attribute" class="form-control" name="combinations_id_product_attribute[{$productAttribute.id_product_attribute|intval}]" value="{$productAttribute.id_product_attribute|intval}" type="hidden" />
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="attribute-actions delete">
                        <a class="btn tooltip-link btn-sm delete" href="#" data="{$productAttribute.id_product_attribute|intval}">
                            <i class="icon-delete">{l s='Delete' mod='ets_marketplace'}</i>
                        </a>
                    </td>
                    <td class="attribute-actions radio_active">
                        <input class="attribute-default"{if $productAttribute.default_on} checked="checked"{/if} data-id="{$productAttribute.id_product_attribute|intval}" type="radio" name="attribute-default" value="{$productAttribute.id_product_attribute|intval}" />
                    </td>
                </tr>
            {/foreach}
        </tbody>
    </table>
{/if}