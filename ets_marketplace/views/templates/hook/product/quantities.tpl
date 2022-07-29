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

<div class="row">
    <div class="col-lg-12">
        <div class="container-fluid">
            <div class="">
                <div id="quantities" style="">
                    <h2>{l s='Quantities' mod='ets_marketplace'}</h2>
                    <fieldset class="form-group">
                        <div class="row">
                            <div class="col-lg-4">
                                <label class="form-control-label">{l s='Quantity' mod='ets_marketplace'}</label>
                                <input id="form_step3_qty_0" class="form-control" name="product_quantity" value="{$valueFieldPost.quantity|intval}" type="text" />
                            </div>
                            <div class="col-lg-4">
                                <label class="form-control-label">{l s='Minimum quantity for sale' mod='ets_marketplace'}</label>
                                <span class="help-box">
                                    <span>{l s='The minimum quantity required to buy this product (set to 1 to disable this feature). E.g.: if set to 3, customers will be able to purchase the product only if they take at least 3 in quantity.' mod='ets_marketplace'}</span>
                                </span>
                                <input id="form_step3_qty_1" class="form-control" name="product_minimal_quantity" value="{$product_class->minimal_quantity|intval}" type="text" />
                            </div>
                        </div>
                    </fieldset>
                    <h2>{l s='Stock' mod='ets_marketplace'}</h2>
                    <fieldset class="form-group">
                        <div class="row">
                            <div class="col-lg-4">
                                <label class="form-control-label">{l s='Stock location' mod='ets_marketplace'}</label>
                                <input id="form_step3_location" class="form-control" name="product_location" type="text" value="{$product_class->location|escape:'html':'UTF-8'}" />
                            </div>
                        </div>
                        {if $is17}
                            <div class="row">
                                <div class="col-lg-4">
                                    <label class="form-control-label">{l s='Low stock level' mod='ets_marketplace'}</label>
                                    <input id="form_step3_low_stock_threshold" class="form-control" name="product_low_stock_threshold" placeholder="{l s='Leave empty to disable' mod='ets_marketplace'}" type="text" value="{if $product_class->low_stock_threshold!=0}{$product_class->low_stock_threshold|intval}{/if}" />
                                </div>
                                <div class="col-lg-8">
                                    <div class="widget-checkbox-inline">
                                        <label class="form-control-label"></label>
                                        <div class="checkbox">
                                            <label>
                                                <input id="form_step3_low_stock_alert" name="product_low_stock_alert" value="1" type="checkbox"{if $product_class->low_stock_alert} checked="checked"{/if} />
                                                {l s='Send me an email when the quantity is below or equals this level' mod='ets_marketplace'} 
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        {/if}
                    </fieldset>
                </div>
                <div id="virtual_product" class="col-lg-12">
                    <div class="row">
                        <div class="col-lg-6">
                            <h2>{l s='Does this product have an associated file?' mod='ets_marketplace'}</h2>
                        </div>
                        <div class="col-lg-6">
                            <fieldset class="">
                                <div id="form_step3_virtual_product_is_virtual_file">
                                    <div class="radio">
                                        <label class="">
                                            <input id="form_step3_virtual_product_is_virtual_file_1" name="is_virtual_file" value="1" type="radio"{if $productDownload && $productDownload.active} checked="checked"{/if} />
                                            {l s='Yes' mod='ets_marketplace'}
                                        </label>
                                        <label class="">
                                            <input id="form_step3_virtual_product_is_virtual_file_0" name="is_virtual_file" value="0" type="radio" {if !$productDownload || ($productDownload && !$productDownload.active)} checked="checked"{/if} />
                                            {l s='No' mod='ets_marketplace'}
                                        </label>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div id="virtual_product_content" class="row" style="">
                        <input id="virtual_product_filename" name="virtual_product_filename" value="{if $productDownload}{$productDownload.filename|escape:'html':'UTF-8'}{/if}" type="hidden" />
                        <input name="virtual_product_id" type="hidden" value="{if $productDownload}{$productDownload.id_product_download|intval}{/if}"/>
                        <div class="col-md-12">
                            <fieldset class="form-group">
                                <label class="form-control-label">{l s='File' mod='ets_marketplace'}</label>
                                <span class="help-box">
                                    <span>{l s='Upload a file from your computer (20M max.)' mod='ets_marketplace'}</span>
                                </span>
                                <div id="form_step3_virtual_product_file_input" class="{if $productDownload && $productDownload.active && $productDownload.filename}hide{else}show{/if}">
                                    <div class="custom-file">
                                        <input id="form_step3_virtual_product_file" class="custom-file-input" name="virtual_product_file_uploader" type="file" />
                                        <label class="custom-file-label" for="form_step3_virtual_product_file"> {l s='Choose file(s)' mod='ets_marketplace'} </label>
                                    </div>
                                </div>
                                <div id="form_step3_virtual_product_file_details" class="{if $productDownload && $productDownload.active && $productDownload.filename}show{else}hide{/if}">
                                  {if $productDownload && $productDownload.active && $productDownload.filename}
                                      <a href="{$link_download_file|escape:'html':'UTF-8'}" target="_blank" class="btn btn-default btn-sm download ets_mp_download_file">{l s='Download file' mod='ets_marketplace'}</a>
                                      <a href="{$link_delete_file|escape:'html':'UTF-8'}" class="btn btn-danger btn-sm delete ets_mp_delete_file">{l s='Delete this file' mod='ets_marketplace'}</a>
                                  {/if}
                                </div>
                            </fieldset>
                        </div>
                        <div class="col-md-6">
                            <fieldset class="form-group">
                                <label class="form-control-label">{l s='Filename' mod='ets_marketplace'}</label>
                                <span class="help-box" title="">
                                    <span>{l s='The full filename with its extension (e.g. Book.pdf)' mod='ets_marketplace'}</span>
                                </span>
                                <input id="form_step3_virtual_product_name" class="form-control" name="virtual_product_name" type="text" />
                            </fieldset>
                        </div>
                        <div class="col-md-6">
                            <fieldset class="form-group">
                                <label class="form-control-label">{l s='Number of allowed downloads' mod='ets_marketplace'}</label>
                                <span class="help-box">
                                    <span>{l s='Number of downloads allowed per customer. Set to 0 for unlimited downloads.' mod='ets_marketplace'}</span>
                                </span>
                                <input id="form_step3_virtual_product_nb_downloadable" class="form-control" name="virtual_product_nb_downloadable" type="text" />
                            </fieldset>
                        </div>
                        <div class="col-md-6">
                            <fieldset class="form-group">
                                <label class="form-control-label">{l s='Expiration date' mod='ets_marketplace'}</label>
                                <span class="help-box" title="">
                                    <span>{l s='If set, the file will not be downloadable after this date. Leave blank if you do not wish to attach an expiration date.' mod='ets_marketplace'}</span>
                                </span>
                                <div class="input-group datepicker">
                                    <input class="form-control" id="form_step3_virtual_product_expiration_date" name="virtual_product_expiration_date" placeholder="YYYY-MM-DD" type="text" />
                                    <div class="input-group-append">
                                        <div class="input-group-text">
                                            <i class="icon icon-date"></i>
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div id="pack_stock_type" class="row col-lg-12">
                    <h2>{l s='Pack quantities' mod='ets_marketplace'}</h2>
                    <div class="row col-md-5">
                        <fieldset class="form-group">
                            <select id="form_step3_pack_stock_type" class="" name="pack_stock_type">
                                <option value="0"{if $product_class->pack_stock_type==0} selected="selected"{/if}>{l s='Decrement pack only.' mod='ets_marketplace'}</option>
                                <option value="1"{if $product_class->pack_stock_type==1} selected="selected"{/if}>{l s='Decrement products in pack only.' mod='ets_marketplace'}</option>
                                <option value="2"{if $product_class->pack_stock_type==2} selected="selected"{/if}>{l s='Decrement both.' mod='ets_marketplace'}</option>
                                <option value="3"{if $product_class->pack_stock_type==3} selected="selected"{/if}>{l s='Default: Decrement pack only.' mod='ets_marketplace'}</option>
                            </select>
                        </fieldset>
                    </div>
                </div>
                <div class="clearfix"></div>
                {if in_array('out_of_stock_behavior',$seller_product_information)}
                    <div class="form-group">
                        <div class="row">
                            <div class="col-md-12">
                                <h2>{l s='Availability preferences' mod='ets_marketplace'}</h2>
                            </div>
                            <div class="col-md-12">
                                <label class="form-control-label">{l s='Behavior when product is out of stock' mod='ets_marketplace'}</label>
                                <div id="form_step3_out_of_stock">
                                    <div class="radio">
                                        <label class="">
                                            <input id="form_step3_out_of_stock_0" name="out_of_stock" value="0" type="radio"{if $product_class->out_of_stock==0} checked="checked"{/if} />
                                        {l s='Deny orders' mod='ets_marketplace'}    
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="">
                                            <input id="form_step3_out_of_stock_1" name="out_of_stock" value="1" type="radio"{if $product_class->out_of_stock==1} checked="checked"{/if} />
                                        {l s='Allow orders' mod='ets_marketplace'}    
                                        </label>
                                    </div>
                                    <div class="radio">
                                        <label class="">
                                            <input id="form_step3_out_of_stock_2" name="out_of_stock" value="2" type="radio"{if $product_class->out_of_stock==2 || !$product_class->id} checked="checked"{/if} />
                                            {l s='Use default behavior (Deny orders)' mod='ets_marketplace'}    
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                {/if}
            </div>
        </div>
    </div>
</div>