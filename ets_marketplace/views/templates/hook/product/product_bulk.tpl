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
{if (isset($is_admin) && $is_admin) || (Configuration::get('ETS_MP_ALLOW_SELLER_CREATE_PRODUCT') || (isset($has_edit_product) && $has_edit_product) || $has_delete_product)}
    <div id="catalog_deletion_modal" class="modal fade" tabindex="-1">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{l s='Delete products?' mod='ets_marketplace'}</h4>
                    <button class="close" type="button" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body"> {l s='These products will be deleted for good. Please confirm.' mod='ets_marketplace'} </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary btn-lg" type="button" data-dismiss="modal">{l s='Close' mod='ets_marketplace'}</button>
                    <button class="btn btn-outline-secondary btn-lg" type="button" value="confirm"> {l s='Delete now' mod='ets_marketplace'} </button>
                </div>
            </div>
        </div>
    </div>
    <div id="catalog_delete_all_modal" class="modal fade" tabindex="-1">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{l s='Deleting products' mod='ets_marketplace'}</h4>
                    <button class="close" type="button" data-dismiss="modal">×</button>
                </div>
                <div class="modal-body">
                    <p>{l s='Deletion in progress...' mod='ets_marketplace'}</p>
                    <span id="catalog_delete_all_failure" style="display: none;color: darkred;"> {l s='Deletion failed.' mod='ets_marketplace'} </span>
                    <div id="catalog_delete_all_progression">
                        <div class="float-right progress-details-text" default-value="{l s='Deleting...' mod='ets_marketplace'}"> {l s='Deleting...' mod='ets_marketplace'} </div>
                        <div class="progress active progress-striped" style="display: block; width: 100%">
                            <div class="progress-bar progress-bar-success" role="progressbar" style="width: 0%">
                                <span>0 %</span>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    {*<div class="modal fade" id="catalog_deactivate_all_modal" tabindex="-1">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{l s='Deactivating products' mod='ets_marketplace'}</h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>                 
                </div> 
                <div class="modal-body" id="catalog_deactivate_all_progression">
                    <p>{l s='Deactivation in progress...' mod='ets_marketplace'}</p>
                    <span id="catalog_deactivate_all_failure" style="display: none;color: darkred;">{l s='Deactivation failed.' mod='ets_marketplace'}</span>
                    <div class="float-right progress-details-text" default-value="{l s='Deactivating...' mod='ets_marketplace'}">
                        {l s='Deactivating...' mod='ets_marketplace'}
                    </div>
                    <div class="progress active progress-striped" style="display: block; width: 100%">
                        <div class="progress-bar progress-bar-success" role="progressbar" style="width: 0%">
                            <span>0 %</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-lg" data-dismiss="modal">{l s='Close' mod='ets_marketplace'}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="catalog_activate_all_modal" tabindex="-1">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{l s='Activating products' mod='ets_marketplace'}</h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>                    
                </div> 
                <div class="modal-body" id="catalog_activate_all_progression">
                    <p>{l s='Activation in progress...' mod='ets_marketplace'}</p>
                    <span id="catalog_activate_all_failure" style="display: none;color: darkred;">{l s='Activation failed.' mod='ets_marketplace'}</span>
                    <div class="float-right progress-details-text" default-value="{l s='Activating...' mod='ets_marketplace'}">
                        {l s='Activating...' mod='ets_marketplace'}
                    </div>
                    <div class="progress active progress-striped" style="display: block; width: 100%">
                        <div class="progress-bar progress-bar-success" role="progressbar" style="width: 0%">
                            <span>0 %</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-lg" data-dismiss="modal">{l s='Close' mod='ets_marketplace'}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="catalog_duplicate_all_modal" tabindex="-1">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">{l s='Duplicating products' mod='ets_marketplace'}</h4>
                    <button type="button" class="close" data-dismiss="modal">×</button>                    
                </div>
                
                <div class="modal-body" id="catalog_duplicate_all_progression">
                    <p>{l s='Duplication in progress...' mod='ets_marketplace'}</p>
                    <span id="catalog_duplicate_all_failure" style="display: none;color: darkred;">
                        {l s='Duplication failed.' mod='ets_marketplace'}
                    </span>
                    <div class="float-right progress-details-text" default-value="{l s='Duplicating...' mod='ets_marketplace'}">
                        {l s='Duplicating...' mod='ets_marketplace'}
                    </div>
                    <div class="progress active progress-striped" style="display: block; width: 100%">
                        <div class="progress-bar progress-bar-success" role="progressbar" style="width: 0%">
                            <span>0 %</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-lg" data-dismiss="modal">{l s='Close' mod='ets_marketplace'}</button>
                </div>
            </div>
        </div>
    </div>*}
{/if}