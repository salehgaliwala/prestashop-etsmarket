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
<script type="text/javascript">
    var expired_text ='{l s='Expired' mod='ets_marketplace' js=1}';
    var pending_text ='{l s='Pending' mod='ets_marketplace' js=1}';
    var actived_text ='{l s='Active' mod='ets_marketplace' js=1}'; 
    var disabled_text ='{l s='Disabled' mod='ets_marketplace' js=1}';
    var declined_text ='{l s='Declined' mod='ets_marketplace' js=1}';
    var reason_added_text= '{l s='Added by admin' mod='ets_marketplace' js=1}';
    var reason_deducted_text= '{l s='Deducted by admin' mod='ets_marketplace' js=1}';
</script>
{if $seller->latitude!=0 && $seller->latitude!=0}
    <div class="ets_mp_popup ets_mp_map_seller" style="display:none">
        <div class="mp_pop_table">
            <div class="mp_pop_table_cell">
                <div class="map-content">
                    <div class="ets_mp_close_popup" title="Close">Close</div>
                    <div id="map"></div>
                    <div class="store-content-select selector3" style="display:none;">
                    	<select id="locationSelect" class="form-control">
                    		<option>-</option>
                    	</select>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script type="text/javascript">
    var markers=[];
    var infoWindow = '';
    var locationSelect = '';
    var defaultLat = {$seller->latitude|floatval};
    var defaultLong = {$seller->longitude|floatval};
    var hasStoreIcon = true;
    var distance_unit = 'km';
    var img_ps_dir = '{$base_link|escape:'html':'UTF-8'}/modules/ets_marketplace/views/img/';
    var searchUrl = '{$link->getAdminLink('AdminMarketPlaceSellers') nofilter}&getmapseller=1&id_seller='+{$seller->id|intval};
    var logo_map = 'logo_map.png';
    var translation_1 = '{l s='No stores were found. Please try selecting a wider radius.' mod='ets_marketplace' js=1}';
    var translation_2 = '{l s='store found -- see details:' mod='ets_marketplace' js=1}';
    var translation_3 = '{l s='stores found -- view all results:' mod='ets_marketplace' js=1}';
    var translation_4 = '{l s='Phone:' mod='ets_marketplace' js=1}' ;
    var translation_5 = '{l s='Get directions' mod='ets_marketplace' js=1}';
    var translation_6 = '{l s='Not found' mod='ets_marketplace' js=1}';
</script>
<script src="{$link_map_google nofilter}"></script>
<script type="text/javascript" src="{$ets_mp_module_dir|escape:'html':'UTF-8'}views/js/map.js"></script>
{/if}
<div class="row display-flex-nocenter md-block ets_mp-panel"> 
    <div class="col-lg-8">
        <div class="panel">
            <div class="panel-header">
                <h3 class="panel-title">{*<i class="fa fa-address-card"></i>&nbsp;*}{l s='Shop info' mod='ets_marketplace'}
                </h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-horizontal label_bold">
                            {if $seller->shop_name}
                                <div class="row">
                                    <label class="control-label col-lg-4 col-sm-6">{l s='Shop name' mod='ets_marketplace'}</label>
                                    <div class="col-lg-8 col-sm-6">
                                        <p class="form-control-static">{if $seller->shop_name}<a href="{$seller->getLink()|escape:'html':'UTF-8'}" target="_blank">{$seller->shop_name|escape:'html':'UTF-8'}</a>{/if}</p>
                                    </div>
                                </div>
                            {/if}
                            <div class="row">
        						<label class="control-label col-lg-4 col-sm-6">{l s='Seller name' mod='ets_marketplace'}</label>
        						<div class="col-lg-8 col-sm-6">
        							<p class="form-control-static"> <a href="{$link_customer|escape:'html':'UTF-8'}" title="{l s='View customer' mod='ets_marketplace'}" target="_blank">{if $seller->seller_name}{$seller->seller_name|escape:'html':'UTF-8'}{else}{$customer->firstname|escape:'html':'UTF-8'}&nbsp;{$customer->lastname|escape:'html':'UTF-8'}{/if}</a></p>
        						</div>
        					</div>
                            <div class="row">
                                <label class="control-label col-lg-4 col-sm-6">{l s='Email' mod='ets_marketplace'}</label>
                                <div class="col-lg-8 col-sm-6">
                                    <p class="form-control-static">{if $seller->seller_email}{$seller->seller_email|escape:'html':'UTF-8'}{else}{$customer->email|escape:'html':'UTF-8'}{/if}</p>
                                </div>
                            </div>
                            <div class="row">
        						<label class="control-label col-lg-4 col-sm-6">{l s='Registration date' mod='ets_marketplace'}</label>
        						<div class="col-lg-8 col-sm-6">
        							<p class="form-control-static">{dateFormat date=$seller->date_add full=1}</p>
        						</div>
        					</div>
                            <div class="row">
                                <label class="control-label col-lg-4 col-sm-6">{l s='Shop status' mod='ets_marketplace'}</label>
                                <div class="col-lg-8 col-sm-6">
                                    <div class="seller-status">
                                        {if $seller->active==-2}
                                            <span class="ets_mp_status expired">{l s='Expired' mod='ets_marketplace'}</span>
                                        {elseif $seller->active==-1}
                                            <span class="ets_mp_status pending">{l s='Pending' mod='ets_marketplace'}</span>
                                        {elseif $seller->active==0}
                                            <span class="ets_mp_status disabled">{l s='Disabled' mod='ets_marketplace'}</span>
                                        {elseif $seller->active==1}
                                            <span class="ets_mp_status approved">{l s='Active' mod='ets_marketplace'}</span>
                                        {elseif $seller->active==-3}
                                            <span class="ets_mp_status declined">{l s='Declined payment' mod='ets_marketplace'}</span>
                                        {/if}
                                    </div>
                                </div>
                            </div>
                            {if $seller_billing}
                                <div class="row">
                                    <label class="control-label col-lg-4 col-sm-6">{l s='Payment status' mod='ets_marketplace'}</label>
                                        <div class="col-lg-8 col-sm-6 payment_verify">
                                            {if $seller_billing}
                                                {if $seller_billing->active==0}
                                                    <span class="ets_mp_status awaiting_payment">{l s='Pending' mod='ets_marketplace'}{if $seller_billing->seller_confirm} ({l s='Seller confirmed' mod='ets_marketplace'}){/if}</span>
                                                {/if}
                                                {if $seller_billing->active==-1}
                                                    <span class="ets_mp_status deducted">{l s='Canceled' mod='ets_marketplace'}</span>
                                                {/if}
                                                {if $seller_billing->active==1}
                                                    <span class="ets_mp_status purchased">{l s='Paid' mod='ets_marketplace'}</span>
                                                {/if}
                                            {else}
                                                <span class="ets_mp_status purchased">{l s='Paid' mod='ets_marketplace'}</span>
                                            {/if}
                                        </div>
                                </div>
                            {/if}                 
                            <div class="row change_date_seller" {if $seller->active!=1} style="display:none;" {/if}>
                                <label class="control-label col-lg-4 col-sm-6">{l s='Available' mod='ets_marketplace'} </label>
                                <div class="col-lg-8 col-sm-6">
                                    <span class="date_seller_approve">                                
                                        {if ($seller->date_from && $seller->date_from!='0000-00-00') || ($seller->date_to && $seller->date_to!='0000-00-00')}
                                            {if $seller->date_from && $seller->date_from!='0000-00-00'}{l s='from' mod='ets_marketplace'} {dateFormat date=$seller->date_from full=0}{/if}
                                            {if $seller->date_to && $seller->date_to!='0000-00-00'}{l s='to' mod='ets_marketplace'} {dateFormat date=$seller->date_to full=0}{/if}                                                                                    
                                        {else}
                                            {l s='Unlimited' mod='ets_marketplace'}                                        
                                        {/if}
                                    </span>
                                    <span>
                                        <span class="btn btn-default approve_registration" data-id="{$seller->id|intval}">
                                            <i class="icon icon-edit"></i> {l s='Change' mod='ets_marketplace'}
                                        </span>
                                        <div class="approve_registration_form" style="display:none">
                                            <div class="ets_mp_close_popup" title="Close">{l s='Close' mod='ets_marketplace'}</div>
                                            <div class="form-group">
                                                <label class="control-label col-lg-3">{l s='Available from' mod='ets_marketplace'}</label>
                                                <div class="col-lg-9">
                                                    <div class="row">
                                                        <div class="input-group col-lg-8 ets_mp_datepicker">
                                                            <input name="date_from" value="{$seller->date_from|escape:'html':'UTF-8'}" class="" type="text" autocomplete="off" />
                                                            <span class="input-group-addon">
                                                                <i class="icon-calendar-empty"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="control-label col-lg-3">{l s='Available to' mod='ets_marketplace'}</label>
                                                <div class="col-lg-9">
                                                    <div class="row">
                                                        <div class="input-group col-lg-8 ets_mp_datepicker">
                                                            <input name="date_to" value="{$seller->date_to|escape:'html':'UTF-8'}" class="" type="text" autocomplete="off"/>
                                                            <span class="input-group-addon">
                                                                <i class="icon-calendar-empty"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <input name="active_seller" value="1" type="hidden" />
                                            <input name="saveStatusSeller" value="1" type="hidden" />
                                            <input name="seller_id" value="{$seller->id|intval}" type="hidden" />
                                            <div class="panel_footer form-group">
                                                <div class="control-label col-lg-3"></div>
                                                <div class="col-lg-9">
                                                    <button type="submit" value="1" name="saveStatusSeller" class="btn btn-default saveStatusSeller">
                                                        <i class="icon-save"></i> {l s='Save' mod='ets_marketplace'}
                                                    </button>
                                                </div>
                                            </div>           
                                        </div>
                                    </span>
                                </div>                                                                                                                
                            </div>                                                           
                            <div class="row">
                                <label class="control-label col-lg-4 col-sm-6">&nbsp;</label>
                                <div class="col-lg-8 col-sm-6">
                                <span>
                                    <span class="ets_mp_status approved btn btn-default approve_registration action_approve_seller" data-id="{$seller->id|intval}" {if $seller->active==1}style="display:none;"{/if}>
                                        <i class="fa fa-check icon-check"></i> {l s='Activate' mod='ets_marketplace'}
                                    </span>
                                    <div class="approve_registration_form" style="display:none">
                                        <div class="ets_mp_close_popup" title="Close">{l s='Close' mod='ets_marketplace'}</div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-3">{l s='Status' mod='ets_marketplace'}</label>
                                            <div class="col-lg-9">
                                                <span class="ets_mp_status approved">{l s='Active' mod='ets_marketplace'}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-3">{l s='Available from' mod='ets_marketplace'}</label>
                                            <div class="col-lg-9">
                                                <div class="row">
                                                    <div class="input-group col-lg-8 ets_mp_datepicker">
                                                        <input name="date_from" value="{$seller->date_from|escape:'html':'UTF-8'}" class="" type="text" />
                                                        <span class="input-group-addon">
                                                            <i class="icon-calendar-empty"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-3">{l s='Available to' mod='ets_marketplace'}</label>
                                            <div class="col-lg-9">
                                                <div class="row">
                                                    <div class="input-group col-lg-8 ets_mp_datepicker">
                                                        <input name="date_to" value="{$seller->date_to|escape:'html':'UTF-8'}" class="" type="text" />
                                                        <span class="input-group-addon">
                                                            <i class="icon-calendar-empty"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input name="active_seller" value="1" type="hidden" />
                                        <input name="saveStatusSeller" value="1" type="hidden" />
                                        <input name="seller_id" value="{$seller->id|intval}" type="hidden" />
                                        <div class="panel_footer form-group">
                                            <div class="control-label col-lg-3"></div>
                                            <div class="col-lg-9">
                                                <button type="submit" value="1" name="saveStatusSeller" class="btn btn-default saveStatusSeller">
                                                    <i class="icon-save"></i> {l s='Save' mod='ets_marketplace'}
                                                </button>
                                            </div>
                                        </div>           
                                    </div>
                                </span>
                                <span>
                                    <span class="ets_mp_status declined btn btn-default approve_registration action_decline_seller" data-id="{$seller->id|intval}" {if $seller->active!=-1} style="display:none;"{/if}>
                                        <i class="icon icon-close"></i> {l s='Decline payment' mod='ets_marketplace'}
                                    </span>
                                    <div class="approve_registration_form" style="display:none">
                                        <div class="ets_mp_close_popup" title="Close">{l s='Close' mod='ets_marketplace'}</div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-3">{l s='Status' mod='ets_marketplace'}</label>
                                            <div class="col-lg-9">
                                                <span class="ets_mp_status declined">{l s='Decline payment' mod='ets_marketplace'}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-3">{l s='Reason' mod='ets_marketplace'}</label>
                                            <div class="col-lg-9">
                                                <textarea name="reason">{$seller->reason|escape:'html':'UTF-8'}</textarea>
                                            </div>
                                        </div>
                                        <input name="active_seller" value="-3" type="hidden" />
                                        <input name="saveStatusSeller" value="1" type="hidden" />
                                        <input name="seller_id" value="{$seller->id|intval}" type="hidden" />
                                        <div class="panel_footer form-group">
                                            <div class="control-label col-lg-3"></div>
                                            <div class="col-lg-9">
                                                <button type="submit" value="1" name="saveStatusSeller" class="btn btn-default saveStatusSeller">
                                                    <i class="icon-save"></i> {l s='Save' mod='ets_marketplace'}
                                                </button>
                                            </div>
                                        </div>           
                                    </div>
                                </span>
                                <span>
                                    <span class="ets_mp_status declined btn btn-default approve_registration action_disable_seller" data-id="{$seller->id|intval}" {if $seller->active==0} style="display:none;"{/if}>
                                        <i class="icon icon-ban"></i> {l s='Disable' mod='ets_marketplace'}
                                    </span>
                                    <div class="approve_registration_form" style="display:none">
                                        <div class="ets_mp_close_popup" title="Close">{l s='Close' mod='ets_marketplace'}</div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-3">{l s='Status' mod='ets_marketplace'}</label>
                                            <div class="col-lg-9">
                                                <span class="ets_mp_status disabled">{l s='Disable' mod='ets_marketplace'}</span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-lg-3">{l s='Reason' mod='ets_marketplace'}</label>
                                            <div class="col-lg-9">
                                                <textarea name="reason">{$seller->reason|escape:'html':'UTF-8'}</textarea>
                                            </div>
                                        </div>
                                        <input name="active_seller" value="0" type="hidden" />
                                        <input name="saveStatusSeller" value="1" type="hidden" />
                                        <input name="seller_id" value="{$seller->id|intval}" type="hidden" />
                                        <div class="panel_footer form-group">
                                            <div class="control-label col-lg-3"></div>
                                            <div class="col-lg-9">
                                                <button type="submit" value="1" name="saveStatusSeller" class="btn btn-default saveStatusSeller">
                                                    <i class="icon-save"></i> {l s='Save' mod='ets_marketplace'}
                                                </button>
                                            </div>
                                        </div>           
                                    </div>
                                </span>
                                <a class="btn btn-default ets_mk_seller_delete" onclick="return confirm('{l s='Do you want to delete this item?' mod='ets_marketplace'}');" href="{$link->getAdminLink('AdminMarketPlaceSellers')|escape:'html':'UTF-8'}&id_seller={$seller->id|intval}&del=yes">
                                    <i class="fa fa-trash icon-trash"></i>
                                    {l s='Delete' mod='ets_marketplace'}
                                </a>
                                </div>
                            </div>
                            {if $seller->shop_address}
                                <div class="row">
                                    <label class="control-label col-lg-4 col-sm-6">{l s='Shop address' mod='ets_marketplace'}</label>
                                    <div class="col-lg-8 col-sm-6">
                                        <p class="form-control-static">{if $seller->shop_address}{$seller->shop_address|nl2br nofilter}{/if}</p>
                                    </div>
                                </div>
                            {/if}
                            {if $seller->latitude!=0 && $seller->longitude!=0}
                                <div class="row">
                                    <label class="control-label col-lg-4 col-sm-6">&nbsp;</label>
                                    <div class="col-lg-8 col-sm-6">
                                        <div class="ets_mp_map">
                                            <a class="view_map" href="#"> 
                                                <i class="fa fa-map-marker"></i> {l s='View map' mod='ets_marketplace'}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            {/if}
                            {if $seller->shop_description}
                                <div class="row">
                                    <label class="control-label col-lg-4 col-sm-6">{l s='Shop description' mod='ets_marketplace'}</label>
                                    <div class="col-lg-8 col-sm-6">
                                        <p class="form-control-static">{if $seller->shop_description}{$seller->shop_description|nl2br nofilter}{/if}</p>
                                    </div>
                                </div>
                            {/if}
                            {if $seller->shop_phone}
                                <div class="row form-group">
                                    <label class="control-label col-lg-4 col-sm-6">{l s='Shop phone number' mod='ets_marketplace'}</label>
                                    <div class="col-lg-8 col-sm-6">
                                        <p class="form-control-static">{if $seller->shop_phone}{$seller->shop_phone|escape:'html':'UTF-8'}{/if}</p>
                                    </div>
                                </div>
                            {/if}
                            {*if $seller->vat_number}
                                <div class="row form-group">
                                    <label class="control-label col-lg-4 col-sm-6">{l s='VAT number' mod='ets_marketplace'}</label>
                                    <div class="col-lg-8 col-sm-6">
                                        <p class="form-control-static">{if $seller->vat_number}{$seller->vat_number|escape:'html':'UTF-8'}{/if}</p>
                                    </div>
                                </div>
                            {/if*}
                            
                            <div class="row form-group">
                                <label class="control-label col-lg-4 col-sm-6">{l s='Logo' mod='ets_marketplace'}</label>
                                <div class="col-lg-8 col-sm-6">
                                    {if $seller->shop_logo}
                                        <img src="../img/mp_seller/{$seller->shop_logo|escape:'html':'UTF-8'}" style="width:80px" />
                                    {else}
                                        <img src="../img/mp_seller/default.png" style="width:80px" />
                                    {/if}
                                </div>
                            </div>
                            
                            {if $seller->shop_banner}
                                <div class="row form-group">
                					<label class="control-label col-lg-4 col-sm-6">{l s='Shop banner' mod='ets_marketplace'}</label>
                					<div class="col-lg-8 col-sm-6">
                                        {if $seller->shop_banner}
                						  <img class="seller_shop_banner" src="../img/mp_seller/{$seller->shop_banner|escape:'html':'UTF-8'}" />
                                          
                                        {/if}
                					</div>
               				     </div>
                            {/if}
                            {if $seller->link_facebook || $seller->link_google || $seller->link_instagram || $seller->link_twitter}
                                <div class="row">
                					<label class="control-label col-lg-4 col-sm-6">{l s='Social' mod='ets_marketplace'}</label>
                					<div class="col-lg-8 col-sm-6 seller-social">
                                        {if $seller->link_facebook}
                                            <a class="facebook" href="{$seller->link_facebook|escape:'html':'UTF-8'}"><i class="icon icon-facebook fa fa-facebook" title="{l s='Facebook' mod='ets_marketplace'}"></i></a>
                                        {/if}
                                        {if $seller->link_google}
                                            <a class="google" href="{$seller->link_google|escape:'html':'UTF-8'}"><i class="icon icon-google fa fa-google" title="{l s='Google' mod='ets_marketplace'}"></i></a>
                                        {/if}
                                        {if $seller->link_instagram}
                                            <a class="instagram" href="{$seller->link_instagram|escape:'html':'UTF-8'}"><i class="icon icon-instagram fa fa-instagram" title="{l s='Instagram' mod='ets_marketplace'}"></i></a>
                                        {/if} 
                                        {if $seller->link_twitter}
                                            <a class="twitter" href="{$seller->link_twitter|escape:'html':'UTF-8'}"><i class="icon icon-twitter fa fa-twitter" title="{l s='Twitter' mod='ets_marketplace'}"></i></a> 
                                        {/if}
                                    </div>
                                </div>
                            {/if}
                            
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-horizontal label_bold">
                            <div class="row">
                                <label class="control-label col-lg-6">
                                    {l s='Total commission balance' mod='ets_marketplace'}
                                    <i class="fa fa-question-circle">
                                        <span class="ets_tooltip" data-tooltip="top">{l s='The remaining amount of commission after converting into voucher, withdrawing, paying for orders or being deducted by marketplace admin' mod='ets_marketplace'}</span>
                                    </i>
                                </label>
                                <div class="col-lg-6">
                                    {assign var='total_commission_balance' value=$seller->getTotalCommission(1)-$seller->getToTalUseCommission(1)}
                                    <p class="form-control-static" id="total_commission">{displayPrice price=$total_commission_balance}</p>
                                </div>
                            </div>
                            <div class="row">
                                <label class="control-label col-lg-6">
                                    {l s='Total commission' mod='ets_marketplace'}
                                    <i class="fa fa-question-circle">
                                        <span class="ets_tooltip" data-tooltip="top">{l s='Total commission money this seller has earned' mod='ets_marketplace'}</span>
                                    </i>
                                </label>
                                <div class="col-lg-6">
                                    <p class="form-control-static" id="total_commission">{displayPrice price=$seller->getTotalCommission(1)}</p>
                                </div> 
                            </div>
                            <div class="row">
								<label class="control-label col-lg-6">
                                    {l s='Withdrawn' mod='ets_marketplace'}
									<i class="fa fa-question-circle">
                                        <span class="ets_tooltip" data-tooltip="top">{l s='Total amount of commission money this seller has withdrawn' mod='ets_marketplace'}</span>
                                    </i>
                                </label>
								<div class="col-lg-6">
									<p class="form-control-static" id="total_withdrawn">{displayPrice price=$seller->getToTalUseCommission(1,false,false,true)}</p>
								</div>
							</div>
                            <div class="row">
								<label class="control-label col-lg-6">
                                    {l s='Paid for orders' mod='ets_marketplace'}
									<i class="fa fa-question-circle">
                                        <span class="ets_tooltip" data-tooltip="top">{l s='Total amount of commission money this seller has used to pay for orders' mod='ets_marketplace'}</span>
                                    </i>
                                </label>
								<div class="col-lg-6">
									<p class="form-control-static" id="total_paid_for_orders">{displayPrice price=$seller->getToTalUseCommission(1,true)}</p>
								</div>
							</div>
                            <div class="row">
								<label class="control-label col-lg-6">
                                    {l s='Converted to voucher' mod='ets_marketplace'}
									<i class="fa fa-question-circle">
                                        <span class="ets_tooltip" data-tooltip="top">{l s='Total amount of commission money this seller has used to convert into vouchers' mod='ets_marketplace'}</span>
                                    </i>
                                </label>
								<div class="col-lg-6">
									<p class="form-control-static" id="total_convert_to_voucher">{displayPrice price=$seller->getToTalUseCommission(1,false,true)}</p>
								</div>
							</div>
                            <div class="row">
								<label class="control-label col-lg-6">
                                    {l s='Total used' mod='ets_marketplace'}
									<i class="fa fa-question-circle">
                                        <span class="ets_tooltip" data-tooltip="top">{l s='Total amount of commission money this seller has withdrawn, paid for orders, converted into vouchers and commission money deducted by marketplace admin' mod='ets_marketplace'}</span>
                                    </i>
                                </label>
								<div class="col-lg-6">
									<p class="form-control-static" id="total_commission_used">{displayPrice price=$seller->getToTalUseCommission(1)}</p>
								</div>
							</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="panel">
            <div class="panel-header">
                <h3 class="panel-title">{*<i class="fa fa-database"></i>&nbsp;*}{l s='Modify seller balance' mod='ets_marketplace'}
                </h3>
            </div>
            <div class="panel-body">
                <form id="eamFormActionCommissionUser" class="form-horizontal" method="POST" action="{$link->getAdminLink('AdminMarketPlaceSellers')|escape:'html':'UTF-8'}&viewseller=1&id_seller={$seller->id|intval}">
                    <div class="form-group">
						<label class="col-lg-3 control-label">{l s='Action' mod='ets_marketplace'}</label>
						<div class="col-lg-9">
							<select name="action">
								<option value="deduct"{if $action =='deduct'} selected="selected"{/if}>{l s='Deduct' mod='ets_marketplace'}</option>
								<option value="add"{if $action =='add'} selected="selected"{/if}>{l s='Add' mod='ets_marketplace'}</option>
							</select>
						</div>
					</div>
                    <div class="form-group">
                        <label class="col-lg-3 control-label">{l s='Amount' mod='ets_marketplace'}</label>
                        <div class="col-lg-9">
							<div class="input-group ">
								<input name="amount" value="{$amount|escape:'html':'UTF-8'}" placeholder="" class="form-control" type="text" />
								<span class="input-group-addon">{$currency->sign|escape:'html':'UTF-8'}</span>
							</div>
						</div>
                    </div>
                    <div class="form-group">
						<label class="col-lg-3 control-label">{l s='Reason' mod='ets_marketplace'}</label>
						<div class="col-lg-9">
							<textarea name="reason" class="form-control">{if $reason}{$reason|escape:'html':'UTF-8'}{else}{if $action=='add'}{l s='Added by admin' mod='ets_marketplace' js=1}{else}{l s='Deducted by admin' mod='ets_marketplace' js=1}{/if} {/if}</textarea>
						</div>
					</div>
                    <div class="form-group text-right">
						<div class="col-lg-12">
							<button type="submit" name="deduct_commission_by_admin" class="btn btn-default" {if $action=='add'}style="display: none;"{/if} ><i class="fa fa-minus-circle"></i>{l s='Deduct' mod='ets_marketplace'}</button>
							<button type="submit" name="add_commission_by_admin" class="btn btn-default" {if $action!='add'}style="display: none;"{/if}><i class="fa fa-plus-circle"></i> {l s='Add' mod='ets_marketplace'}</button>
						</div>
					</div>
                </form>
            </div>
        </div>
    </div>
</div>
{$history_billings nofilter}
{$history_commissions nofilter}
<div>
    <a class="btn btn-default" href="{$link->getAdminLink('AdminMarketPlaceSellers')|escape:'html':'UTF-8'}" title="">
        <i class="fa fa-back icon icon-back process-icon-back"></i> {l s='Back' mod='ets_marketplace'}
    </a>
</div>
