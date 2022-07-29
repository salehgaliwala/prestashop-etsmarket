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
    <div class="col-lg-12">
        <div class="panel">
            <div class="panel-heading">
                <i class="icon-upload"></i>
                {l s='Import products' mod='ets_marketplace'}
            </div>
            <form id="preview_import" class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="control-label col-lg-4" for="truncate">
                        {l s='Select a CSV file to import' mod='ets_marketplace'}
                    </label>
                    <div class="col-lg-8">
                        <input type="file" name="file_import_product" />
                        <p class="help-block">{l s='Upload .csv file of your products following the format of sample file:' mod='ets_marketplace'}<a href="{$link_sample|escape:'html':'UTF-8'}">{l s='Download sample file' mod='ets_marketplace'}</a></p>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-lg-4" for="truncate">
                        &nbsp;
                    </label>
                    <div class="col-lg-8">
                    <input type="hidden" name="submitUploadImportProduct" value="1" />
                    <button type="submit" class="btn btn-primary" name="submitUploadImportProduct"><i class="fa fa-import"></i>&nbsp;{l s='Import' mod='ets_marketplace'}</button>
                    </div>
                </div>
                <div class="row">
                    <p class="note category_import">{l s='*Note: Categories which allow importing products' mod='ets_marketplace'}</p>
                    {if $categories}
                        <table class="list-categories-import">
                            <tr>
                                <td>{l s='Category ID' mod='ets_marketplace'}</td>
                                <td>{l s='Category Name' mod='ets_marketplace'}</td>
                            </tr>
                            {foreach from=$categories item='category'}
                                <tr>
                                    <td>{$category.id_category|intval}</td>
                                    <td>{$category.name|escape:'html':'UTF-8'}</td>
                                </tr>
                            {/foreach}
                        </table>
                    {/if}
                </div>
                <div class="clearfix"></div>
            </form>
        </div>
    </div>
</div>