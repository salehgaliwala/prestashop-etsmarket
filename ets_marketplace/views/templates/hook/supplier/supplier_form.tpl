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
<script type="text/javascript" src="{$url_path|escape:'html':'UTF-8'}views/js/tinymce/tinymce.min.js"></script>
<form id="ets_mp_supplier_group_form" action="" method="post" enctype="multipart/form-data">
     <div id="fieldset_0" class="panel">
         <div class="panel-heading">
            <i class="fa fa-truck"></i>
            {l s='Supplier' mod='ets_marketplace'}
         </div>
         <div class="form-wrapper">
            {$html_form nofilter}
         </div>
         <div class="panel-footer">
            <input type="hidden" name="submitSaveSupplier" value="1"/>
            <input type="hidden" name="id_supplier" value="{$id_supplier|intval}" />
            <a class="btn btn-secondary bd from-control-submit float-xs-left" href="{$link_cancel|escape:'html':'UTF-8'}">{l s='Cancel' mod='ets_marketplace'}</a>
            <button name="submitSaveSupplier" type="submit" class="btn btn-primary form-control-submit float-xs-right">{l s='Save' mod='ets_marketplace'}</button>
         </div>
     </div>
</form>