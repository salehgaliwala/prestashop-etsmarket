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
<div class="row product-actions">
    <div class="col-md-8 qty d-flex align-items-center">
        <div class="md-checkbox mt-2">
            <label>
                <input id="bulk-action" class="" type="checkbox" onclick="$('table').find('td input:checkbox').prop('checked', $(this).prop('checked'));ets_mp_updateBulkStock($(this));"/>
                <i class="md-checkbox-control"></i>
            </label>
        </div>
        <div class="ml-1">
            <small>{l s='Bulk edit quantity' mod='ets_marketplace'}</small>
            <div class="ps-number bulk-qty">
                <input class="form-control" placeholder="0" type="number" name="mp_stocks_quantity_all" id="mp_stocks_quantity_all" />
                <div class="ps-number-spinner d-flex">
                    <span class="ps-number-up"></span>
                    <span class="ps-number-down"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <button id="apply-new-quanitty" class="btn update-qty float-sm-right my-4 btn-primary btn-primary" type="button" disabled="disabled">
            <i class="fa fa-pencil icon-pencil"></i>
            {l s='Apply new quantity' mod='ets_marketplace'}
        </button>
    </div>
</div>