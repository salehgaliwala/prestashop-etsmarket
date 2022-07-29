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
<div class="panel ets_mp-panel">
    <div class="panel-header">
        <h3 class="panel-title">{*<i class="fa fa-address-card"></i>&nbsp;*}{l s='Application info' mod='ets_marketplace'}
        </h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-horizontal">
                    <div class="row">
						<label class="control-label col-lg-4 col-sm-6"><b>{l s='Full name' mod='ets_marketplace'}</b></label>
						<div class="col-lg-8 col-sm-6">
							<p class="form-control-static"> <a href="{$link_customer|escape:'html':'UTF-8'}" title="{l s='View customer' mod='ets_marketplace'}" target="_blank">{if $registration->seller_name}{$registration->seller_name|escape:'html':'UTF-8'}{else}{$customer->firstname|escape:'html':'UTF-8'}&nbsp;{$customer->lastname|escape:'html':'UTF-8'}{/if}</a></p>
						</div>
					</div>
                    <div class="row">
                        <label class="control-label col-lg-4 col-sm-6"><b>{l s='Email' mod='ets_marketplace'}</b></label>
                        <div class="col-lg-8 col-sm-6">
                            <p class="form-control-static">{if $registration->seller_email}{$registration->seller_email|escape:'html':'UTF-8'}{else}{$customer->email|escape:'html':'UTF-8'}{/if}</p>
                        </div>
                    </div>
                    <div class="row">
						<label class="control-label col-lg-4 col-sm-6"><b>{l s='Registration date' mod='ets_marketplace'}</b></label>
						<div class="col-lg-8 col-sm-6">
							<p class="form-control-static">{dateFormat date=$registration->date_add full=1}</p>
						</div>
					</div>
                    <div class="row">
                        <label class="control-label col-lg-4 col-sm-6"><b>{l s='Status' mod='ets_marketplace'}</b></label>
                        <div class="col-lg-8 col-sm-6">
                            <div class="registration-status">
                                {if $registration->active==0}
                                    <span class="ets_mp_status disabled">{l s='Declined' mod='ets_marketplace'}</span>
                                {elseif $registration->active==1}
                                    <span class="ets_mp_status approved">{l s='Approved' mod='ets_marketplace'}</span>
                                {elseif $registration->active==-1}
                                    <span class="ets_mp_status pending">{l s='Pending' mod='ets_marketplace'}</span>
                                {/if}
                            </div>
                            <br />
                            
                            <span class="btn btn-default ets_mp_status approved action_approve_registration" data-id="{$registration->id|intval}" {if $registration->active==1} style="display:none;" {/if}>
                                <i class="fa fa-check icon-check"></i> {l s='Approve' mod='ets_marketplace'}
                            </span>
                            <div class="approve_registration_form" style="display:none">
                                <div class="ets_mp_close_popup" title="Close">{l s='Close' mod='ets_marketplace'}</div>
                                <div class="form-group">
                                    <label class="control-label col-lg-3">{l s='Status' mod='ets_marketplace'}</label>
                                    <div class="col-lg-9">
                                        <span class="ets_mp_status approved">{l s='Approve' mod='ets_marketplace'}</span>
                                    </div>
                                </div>
                                {*
                                <div class="form-group">
                                    <label class="control-label col-lg-3">{l s='Comment' mod='ets_marketplace'}</label>
                                    <div class="col-lg-9">
                                        <textarea name="comment"></textarea>
                                    </div>
                                </div>
                                *}
                                <input name="active_registration" value="1" type="hidden" />
                                <input name="saveStatusRegistration" value="1" type="hidden" />
                                <input name="id_registration" value="{$registration->id|intval}" type="hidden" />
                                <div class="panel_footer form-group">
                                    <div class="control-label col-lg-3"></div>
                                    <div class="col-lg-9">
                                        <button type="submit" value="1" name="saveStatusRegistration" class="btn btn-default saveStatusRegistration">
                                            <i class="icon-save"></i> {l s='Save' mod='ets_marketplace'}
                                        </button>
                                    </div>
                                </div>           
                            </div>
                            <span class="btn btn-default approve_registration ets_mp_status declined" {if $registration->active==0 || ($registration->active==1 && $has_seller)} style="display:none;" {/if}>
                                <i class="fa fa-times icon-close"></i> {l s='Decline' mod='ets_marketplace'}
                            </span>
                            <div class="approve_registration_form" style="display:none">
                                <div class="ets_mp_close_popup" title="Close">{l s='Close' mod='ets_marketplace'}</div>
                                <div class="form-group">
                                    <label class="control-label col-lg-3">{l s='Status' mod='ets_marketplace'}</label>
                                    <div class="col-lg-9">
                                        <span class="ets_mp_status declined">{l s='Decline' mod='ets_marketplace'}</span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-lg-3">{l s='Reason' mod='ets_marketplace'}</label>
                                    <div class="col-lg-9">
                                        <textarea name="reason"></textarea>
                                    </div>
                                </div>
                                <input name="active_registration" value="0" type="hidden" />
                                <input name="saveStatusRegistration" value="1" type="hidden" />
                                <input name="id_registration" value="{$registration->id|intval}" type="hidden" />
                                <div class="panel_footer form-group">
                                    <div class="control-label col-lg-3"></div>
                                    <div class="col-lg-9">
                                        <button type="submit" value="1" name="saveStatusRegistration" class="btn btn-default saveStatusRegistration">
                                            <i class="icon-save"></i> {l s='Save' mod='ets_marketplace'}
                                        </button>
                                    </div>
                                </div>           
                            </div>
                            <a class="btn btn-default ets_mk_register_delete" onclick="return confirm('{l s='Do you want to delete this item?' mod='ets_marketplace'}');" href="{$link->getAdminLink('AdminMarketPlaceRegistrations')|escape:'html':'UTF-8'}&id_registration={$registration->id|intval}&del=yes">
                                <i class="fa fa-trash icon-trash"></i>
                                {l s='Delete' mod='ets_marketplace'}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-horizontal">
                    {if $registration->shop_name}
                        <div class="row">
        					<label class="control-label col-lg-4 col-sm-6"><b>{l s='Shop name' mod='ets_marketplace'}</b></label>
        					<div class="col-lg-8 col-sm-6">
        						<p class="form-control-static"> {$registration->shop_name|escape:'html':'UTF-8'}</p>
        					</div>
        				</div>
                    {/if}
                    {if $registration->shop_description}
                        <div class="row">
        					<label class="control-label col-lg-4 col-sm-6"><b>{l s='Shop description' mod='ets_marketplace'}</b></label>
        					<div class="col-lg-8 col-sm-6">
        						<p class="form-control-static"> {$registration->shop_description|nl2br nofilter}</p>
        					</div>
        				</div>
                    {/if}
                    {if $registration->shop_phone}
                        <div class="row">
        					<label class="control-label col-lg-4 col-sm-6"><b>{l s='Shop phone number' mod='ets_marketplace'}</b></label>
        					<div class="col-lg-8 col-sm-6">
        						<p class="form-control-static"> {$registration->shop_phone|escape:'html':'UTF-8'}</p>
        					</div>
        				</div>
                    {/if}
                    {if $registration->shop_address}
                        <div class="row">
        					<label class="control-label col-lg-4 col-sm-6"><b>{l s='Shop address' mod='ets_marketplace'}</b></label>
        					<div class="col-lg-8 col-sm-6">
                                <div class="form-control-static">{$registration->shop_address|nl2br nofilter}</div>
        					</div>
        				</div>
                    {/if}
                    {if $registration->message_to_administrator}
                        <div class="row">
        					<label class="control-label col-lg-4 col-sm-6"><b>{l s='Introduction' mod='ets_marketplace'}</b></label>
        					<div class="col-lg-8 col-sm-6">
        						<p class="form-control-static"> {$registration->message_to_administrator|nl2br nofilter}</p>
        					</div>
        				</div>
                    {/if}
                    {if $registration->shop_logo}
                        <div class="row form-group">
        					<label class="control-label col-lg-4 col-sm-6"><b>{l s='Logo' mod='ets_marketplace'}</b></label>
        					<div class="col-lg-8 col-sm-6">
                                {if $registration->shop_logo}
        						  <img src="../img/mp_seller/{$registration->shop_logo|escape:'html':'UTF-8'}" style="width:80px;" />
                                {/if}
        					</div>
        				</div>
                    {/if}
                    {if $registration->shop_banner}
                        <div class="row form-group">
        					<label class="control-label col-lg-4 col-sm-6"><b>{l s='Banner' mod='ets_marketplace'}</b></label>
        					<div class="col-lg-8 col-sm-6">
                                {if $registration->shop_banner}
        						  <img class="seller_shop_banner" src="../img/mp_seller/{$registration->shop_banner|escape:'html':'UTF-8'}"/>
                                {/if}
        					</div>
        				</div>
                    {/if}
                    {if $registration->link_facebook || $registration->link_google || $registration->link_instagram || $registration->link_twitter}
                        <div class="row">
        					<label class="control-label col-lg-4 col-sm-6"><b>{l s='Social' mod='ets_marketplace'}</b></label>
        					<div class="col-lg-8 col-sm-6 seller-social">
                                {if $registration->link_facebook}
                                    <a class="facebook" href="{$registration->link_facebook|escape:'html':'UTF-8'}"><i class="icon icon-facebook fa fa-facebook" title="{l s='Facebook' mod='ets_marketplace'}"></i></a>
                                {/if}
                                {if $registration->link_google}
                                    <a class="google" href="{$registration->link_google|escape:'html':'UTF-8'}"><i class="icon icon-google fa fa-google" title="{l s='Google' mod='ets_marketplace'}"></i></a>
                                {/if}
                                {if $registration->link_instagram}
                                    <a class="instagram" href="{$registration->link_instagram|escape:'html':'UTF-8'}"><i class="icon icon-instagram fa fa-instagram" title="{l s='Instagram' mod='ets_marketplace'}"></i></a>
                                {/if} 
                                {if $registration->link_twitter}
                                    <a class="twitter" href="{$registration->link_twitter|escape:'html':'UTF-8'}"><i class="icon icon-twitter fa fa-twitter" title="{l s='Twitter' mod='ets_marketplace'}"></i></a> 
                                {/if}
                            </div>
                        </div>
                    {/if}
                </div>
            </div>
        </div>
        <a class="btn btn-default" href="{$link->getAdminLink('AdminMarketPlaceRegistrations')|escape:'html':'UTF-8'}" title="">
            <i class="fa fa-back icon icon-back process-icon-back"></i> {l s='Back' mod='ets_marketplace'}
        </a>
    </div>
</div>