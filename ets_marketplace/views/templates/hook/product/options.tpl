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
<div class="row">
    <div class="col-md-12">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <h2>{l s='Condition & References' mod='ets_marketplace'}</h2>
                        </div>
                    </div>
                    <div class="row">
                        <fieldset class="col-md-6 form-group">
                            <label class="form-control-label">
                                {l s='Condition' mod='ets_marketplace'}
                                <span class="help-box">
                                    <span>{l s='Not all shops sell new products. This option enables you to indicate the condition of the product. It can be required on some marketplaces.' mod='ets_marketplace'}</span>
                                </span>
                            </label>
                            <small class="form-text">
                                {l s='Indique l’état de ton article' mod='ets_marketplace'}
                            </small>
                            <select id="form_step6_condition" class="custom-select custom-select" name="condition">
                                <option value="new"{if $valueFieldPost.condition == 'new'} selected="selected"{/if}>{l s='New' mod='ets_marketplace'}</option>
                                <option value="used"{if $valueFieldPost.condition == 'used'} selected="selected"{/if}>{l s='Used' mod='ets_marketplace'}</option>
                                <option value="refurbished"{if $valueFieldPost.condition == 'refurbished'} selected="selected"{/if}>{l s='Refurbished' mod='ets_marketplace'}</option>
                            </select>
                        </fieldset>
                        {if isset($valueFieldPost.show_condition)}
                            <fieldset class="col-md-6 form-group">
                                <label class="form-control-label"> </label>
                                <div class="checkbox">
                                    <label for="form_step6_show_condition">
                                        <input id="form_step6_show_condition" name="show_condition" value="1" type="checkbox"{if $valueFieldPost.show_condition}  checked="checked"{/if}/>
                                        {l s='Display condition on product page' mod='ets_marketplace'}
                                    </label>
                                </div>
                            </fieldset>
                        {/if}
                    </div>
                    <div class="row">
                        {if isset($valueFieldPost.isbn)}
                            <fieldset class="col-md-6 form-group">
                                <label class="form-control-label" for="form_step6_isbn">
                                    {l s='ISBN' mod='ets_marketplace'}
                                    <span class="help-box">
                                        <span>{l s='The International Standard Book Number (ISBN) is used to identify books and other publications.' mod='ets_marketplace'}</span>
                                    </span>
                                </label>
                                <input id="form_step6_isbn" class="form-control" name="isbn" type="text" value="{$valueFieldPost.isbn|escape:'html':'UTF-8'}" />
                            </fieldset>
                        {/if}
                        <fieldset class="col-md-6 form-group">
                            <label class="form-control-label" for="form_step6_ean13">
                                {l s='EAN-13 or JAN barcode' mod='ets_marketplace'}
                                <span class="help-box">
                                    <span>{l s='This type of product code is specific to Europe and Japan, but is widely used internationally. It is a superset of the UPC code: all products marked with an EAN will be accepted in North America.' mod='ets_marketplace'}</span>
                                </span>
                            </label>
                            <input id="form_step6_ean13" class="form-control" name="ean13" type="text" value="{$valueFieldPost.ean13|escape:'html':'UTF-8'}" />
                        </fieldset>
                    </div>
                    <div class="row">
                        <fieldset class="col-md-4 form-group">
                            <label class="form-control-label" for="form_step6_upc">
                                {l s='UPC barcode' mod='ets_marketplace'}
                                <span class="help-box">
                                    <span>{l s='This type of product code is widely used in the United States, Canada, the United Kingdom, Australia, New Zealand and in other countries.' mod='ets_marketplace'}</span>
                                </span>
                            </label>
                            <input id="" name="upc" class="form-control" type="text" value="{$valueFieldPost.upc|escape:'html':'UTF-8'}"/>
                        </fieldset>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div id="custom_fields" class="mt-3">
                                <h2>{l s='Customization' mod='ets_marketplace'}</h2>
                                <p class="subtitle">{l s='Customers can personalize the product by entering some text or by providing custom image files.' mod='ets_marketplace'}</p>
                                <ul class='customFieldCollection nostyle' data-prototype='<div class="row">
    <input type="hidden" id="form_step6_custom_fields___name___id_customization_field" name="custom_fields[__name__][id_customization_field]" class="form-control" />
  
  <div class="col-md-4">
    <fieldset class="form-group">
        <label class="form-control-label">{l s='Label' mod='ets_marketplace' js=1}</label>
        <div class="translations tabbable" id="form_step6_custom_fields___name___label">
            <div class="translationsFields tab-content">
                {foreach from =$languages item='lang'}
                    <div data-locale="{$lang.iso_code|escape:'html':'UTF-8'}" class="translationsFields-form_step6_custom_fields___name___label_{$lang.id_lang|intval} tab-pane translation-field translatable-field lang-{$lang.id_lang|intval} {if $lang.id_lang==$id_lang_default} show active{/if}  translation-label-{$lang.iso_code|escape:'html':'UTF-8'}" {if $lang.id_lang!=$id_lang_default} style="display:none"{/if}>
                        <div class="col-xs-12 col-sm-12">
                            <input type="text" id="form_step6_custom_fields___name___label_{$lang.id_lang|intval}" name="custom_fields[__name__][label][{$lang.id_lang|intval}]" required="required" class="form-control" />
                        </div>
                    </div> 
                {/foreach} 
            </div>
        </div>
    </fieldset>
  </div>
  <div class="col-md-3">
    <fieldset class="form-group">
      <label class="form-control-label">{l s='Type' mod='ets_marketplace' js=1}</label>
      
      <select id="form_step6_custom_fields___name___type" name="custom_fields[__name__][type]" class="c-select custom-select"><option value="1">{l s='Text' mod='ets_marketplace' js=1}</option><option value="0">{l s='File' mod='ets_marketplace' js=1}</option></select>
    </fieldset>
  </div>
  <div class="col-md-1">
    <fieldset class="form-group">
      <label class="form-control-label">&nbsp;</label>
      <a class="btn btn-block delete" ><i class="fa fa-trash-o" aria-hidden="true"></i></a>
    </fieldset>
  </div>
  <div class="col-md-4">
    <fieldset class="form-group">
      <div>
        <label class="form-control-label">&nbsp;</label>
      </div>
      <div class="required-custom-field">
        <div class="checkbox">                          
            <label><input type="checkbox"
         data-toggle="switch" class="tiny" id="form_step6_custom_fields___name___require" name="custom_fields[__name__][require]" value="1" />
Required</label>
          </div>
      </div>
    </fieldset>
  </div>
</div>'>
                                    {if $valueFieldPost.customizationFields}
                                        {foreach from =$valueFieldPost.customizationFields key='index' item ='customizationField'}
                                            <li>
                                                <div class="row">
                                                    <input id="form_step6_custom_fields_{$index|intval}_id_customization_field" class="form-control" name="custom_fields[{$index|intval}][id_customization_field]" type="hidden" value="{$customizationField->id|intval}" />
                                                    <div class="col-md-4">
                                                        <fieldset class="form-group">
                                                            <label class="form-control-label">{l s='Label' mod='ets_marketplace'}</label>
                                                            <div id="form_step6_custom_fields_{$index|intval}_label" class="translations tabbable">
                                                                <div class="translationsFields tab-content">
                                                                    {foreach from=$languages item='lang'}
                                                                        <div data-locale="{$lang.iso_code|escape:'html':'UTF-8'}" class="translationsFields-form_step6_custom_fields_{$index|intval}_label_{$lang.id_lang|intval} tab-pane translation-field translatable-field lang-{$lang.id_lang|intval} {if $lang.id_lang==$id_lang_default} show active{/if}  translation-label-{$lang.iso_code|escape:'html':'UTF-8'}" {if $lang.id_lang!=$id_lang_default} style="display:none"{/if}>
                                                                            <div class="col-xs-12 col-sm-12">
                                                                                <input type="text" id="form_step6_custom_fields_{$index|intval}_label_{$lang.id_lang|intval}" name="custom_fields[{$index|intval}][label][{$lang.id_lang|intval}]" required="required" class="form-control" value="{$customizationField->name[$lang.id_lang]|escape:'html':'UTF-8'}"/>
                                                                            </div>
                                                                        </div> 
                                                                    {/foreach}
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <fieldset class="form-group">
                                                            <label class="form-control-label">{l s='Type' mod='ets_marketplace'}</label>
                                                            <select id="form_step6_custom_fields_{$index|intval}_type" class="c-select custom-select" name="custom_fields[{$index|intval}][type]">
                                                                <option value="1"{if $customizationField->type==1} selected="selected"{/if}>{l s='Text' mod='ets_marketplace'}</option>
                                                                <option value="0"{if $customizationField->type==0} selected="selected"{/if}>{l s='File' mod='ets_marketplace'}</option>
                                                            </select>
                                                        </fieldset>
                                                    </div>
                                                    <div class="col-md-1">
                                                        <fieldset class="form-group">
                                                            <label class="form-control-label xs-hide">&nbsp;</label>
                                                            <a class="btn btn-block delete">
                                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                            </a>
                                                        </fieldset>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <fieldset class="form-group">
                                                          <div>
                                                                <label class="form-control-label xs-hide">&nbsp;</label>
                                                          </div>
                                                          <div class="required-custom-field">
                                                            <div class="checkbox">                          
                                                                <label><input data-toggle="switch" class="tiny" id="form_step6_custom_fields_{$index|intval}_require" name="custom_fields[{$index|intval}][required]" value="1" type="checkbox"{if $customizationField->required} checked="checked"{/if} /> {l s='Required' mod='ets_marketplace'}</label>
                                                            </div>
                                                          </div>
                                                        </fieldset>
                                                    </div>
                                                </div>
                                            </li>
                                        {/foreach}
                                    {/if}
                                </ul>
                                <a class="btn btn-outline-secondary add ets_addfile_customization" href="#">
                                    <i class="fa fa-plus-circle"></i>
                                    {l s='Add a customization field' mod='ets_marketplace'}
                                </a>
                            </div>
                        </div>
                    </div>  
                    <br />
                    <div class="row">
                        <div class="col-md-12">
                            <h2>{l s='Attached files' mod='ets_marketplace'}</h2>
                            <p class="subtitle">{l s='Add files that customers can download directly on the product page (instructions, manual, recipe, etc.)' mod='ets_marketplace'}.</p>
                            <div class="js-options-no-attachments{if $valueFieldPost.attachments} hide{/if}">
                                <small>{l s='There is no attachment yet.' mod='ets_marketplace'}</small>
                            </div>
                            <div id="product-attachments" class="panel panel-default {if !$valueFieldPost.attachments} hide{/if}">
                                <div class="panel-body js-options-with-attachments ">
                                    <div>
                                        <table style="width: 100%;">
                                            <thead class="thead-default">
                                                <tr>
                                                    <th>{l s='Title' mod='ets_marketplace'}</th>
                                                    <th >{l s='File name' mod='ets_marketplace'}</th>
                                                    <th>{l s='Type' mod='ets_marketplace'}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {if $valueFieldPost.attachments}
                                                    {foreach from = $valueFieldPost.attachments item='attachment'}
                                                        <tr>
                                                            <td >{$attachment.name|escape:'html':'UTF-8'}</td>
                                                            <td>{$attachment.file_name|escape:'html':'UTF-8'}</td>
                                                            <td> {$attachment.mime|escape:'html':'UTF-8'}</td>
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
                    <div class="row">
                        <div class="col-md-12">
                            <a class="btn btn-outline-secondary mb-2 collapsed ets_add_attachfile" href="#collapsedForm" data-toggle="collapse" aria-expanded="false" aria-controls="collapsedForm">
                                <i class="fa fa-plus-circle"></i>
                                {l s='Attach a new file' mod='ets_marketplace'}
                            </a>
                            <fieldset id="collapsedForm" class="collapse" style="">
                                <div id="form_step6_attachment_product">
                                        <div class="form-group">
                                            <div class="custom-file">
                                                <input id="form_step6_attachment_product_file" class="custom-file-input" name="attachment_product_file" data-multiple-files-text="%count% file(s)" data-locale="en" type="file" />
                                                <label class="custom-file-label" for="form_step6_attachment_product_file">{l s='Choose file' mod='ets_marketplace'}</label>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input id="form_step6_attachment_product_name" class="form-control" name="attachment_product_name" placeholder="Title" type="text" />
                                        </div>
                                        <div class="form-group">
                                            <input id="form_step6_attachment_product_description" class="form-control" name="attachment_product_description" placeholder="Description" type="text" />
                                        </div>
                                        <div class="form-group">
                                            <button id="form_step6_attachment_product_add" class="btn-outline-primary pull-right btn" type="button" name="attachment_product_add">{l s='Add' mod='ets_marketplace'}</button>
                                            <button id="form_step6_attachment_product_cancel" class="btn-outline-secondary pull-right mr-1 btn collapsed" type="button" name="attachment_product_cancel" data-toggle="collapse" data-target="#collapsedForm" aria-expanded="false">{l s='Cancel' mod='ets_marketplace'}</button>
                                        </div>
                                        <div class="clearfix"></div>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                    <div id="html_form_supplier"{if !$product_class->id} style="display:none"{/if}>
                        {$html_form_supplier nofilter}
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>