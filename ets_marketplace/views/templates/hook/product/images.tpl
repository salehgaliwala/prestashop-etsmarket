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
<div id="product-images-container">
<!--<div id="product-images-container"{if $product_class->id} style="display:block"{else} style="display:none;"{/if}>-->
    <div id="product-images-dropzone" class="panel dropzone ui-sortable {if $images}dz-started{/if} col-md-12" style="">
        <div id="product-images-dropzone-error" class="text-danger"></div>
        
        <div id="list-images-product">
            {if $images}
                {foreach from=$images item='image'}
                    <div id="images-{$image.id_image|intval}" class="dz-preview dz-image-preview dz-complete ui-sortable-handle ets_mp_edit_image" data-id="{$image.id_image|intval}">
                        <div class="dz-image bg"><img src="{$image.link|escape:'html':'UTF-8'}" style="width:140px;heigth:140px;"/></div>
                        <div class="dz-progress">
                            <span class="dz-upload" style="width: 100%;"></span>
                        </div>
                        <div class="dz-error-message">
                            <span data-dz-errormessage=""></span>
                        </div>
                        <div class="dz-success-mark"></div>
                        <div class="dz-error-mark"></div>
                        {if $image.cover}
                            <div class="iscover">{l s='Cover' mod='ets_marketplace'}</div>
                        {/if}
                    </div>
                {/foreach}
            {/if} 
            <div id="form-images">
                <input id="ets_mp_multiple_images" name="multiple_imamges[]" multiple="multiple" type="file" />
                <label for="ets_mp_multiple_images">
                    <div class="dz-default dz-message openfilemanager dz-clickable">
                        <i class="icon icon-add-photo"></i> <br />
                        {l s='Select files' mod='ets_marketplace'} <br />
                        <small>
                            {l s='Recommended size 800 x 800px for default theme.' mod='ets_marketplace'}<br />
                            {l s='JPG, GIF or PNG format.' mod='ets_marketplace'}
                        </small>
                        </form>
                    </div> 
                    <div class="dz-preview disabled openfilemanager dz-clickable">
                        <div>
                            <span>+</span>
                        </div>
                    </div>
                </label>    
            </div>
        </div>
    </div>
    <div id="product-images-form-container">
    </div>
</div>