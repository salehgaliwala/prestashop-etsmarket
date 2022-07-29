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
    <div class="ets_mp_content_left col-lg-3" >
        {hook h='displayMPLeftContent'}
    </div>
    <div class="ets_mp_content_left col-lg-9" >
        <div class="ets_mp_popup ets_mp_billing_popup" style="display:none;">
                <div class="mp_pop_table">
                    <div class="mp_pop_table_cell">
                        
                        <form id="ets_mp_billing_form" action="" method="post" enctype="multipart/form-data">
                        <div class="ets_mp_close_popup" title="{l s='Close' mod='ets_marketplace'}">{l s='Close' mod='ets_marketplace'}</div>
                            <div id="fieldset_0" class="panel">
                                <div class="panel-heading">
                                    <i class="icon-envelope-o fa-envelope-o"></i>
                                    {l s='Contact marketplace' mod='ets_marketplace'}
                                </div>
                                <div class="form-wrapper">
                                    <div class="row form-group">
                                        <label class="col-lg-3 form-control-label" for="biling_contact_subject">{l s='Subject' mod='ets_marketplace'}</label>
                                        <div class="col-lg-9">
                                            <input id="biling_contact_subject" class="form-control" name="biling_contact_subject" value="" type="text" />
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="col-lg-3 form-control-label" for="biling_contact_message">{l s='Message' mod='ets_marketplace'}</label>
                                        <div class="col-lg-9">
                                            <textarea id="biling_contact_message" class="form-control" name="biling_contact_message"></textarea>
                                        </div>
                                    </div>
                                    <div class="row form-group">
                                        <label class="col-lg-3 form-control-label" for="biling_contact_paid_invoice">{l s='Have you paid this invoice?' mod='ets_marketplace'}</label>
                                         <div class="col-lg-9">
                                            <span class="switch prestashop-switch fixed-width-lg">
                                    			<input name="biling_contact_paid_invoice" id="biling_contact_paid_invoice_on" value="1" type="radio" />
                                    			<label for="biling_contact_paid_invoice_on" class="radioCheck">
                                    				<i class="color_success"></i> {l s='Yes' mod='ets_marketplace'}
                                    			</label>
                                    			<input name="biling_contact_paid_invoice" id="biling_contact_paid_invoice_off" value="0" checked="checked" type="radio" />
                                    			<label for="biling_contact_paid_invoice_off" class="radioCheck">
                                    				<i class="color_danger"></i> {l s='No' mod='ets_marketplace'}
                                    			</label>
                                    			<a class="slide-button btn"></a>
                                    		</span>
                                         </div>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <span class=""></span>
                                    <input name="submitContactMarketplace" value="1" type="hidden" />
                                    <input name="id_billing_contact" value="0" type="hidden" id="id_billing_contact" />
                                    <button class="btn btn-primary form-control-submit float-xs-right" name="submitContactMarketplace" type="submit">{l s='Send' mod='ets_marketplace'}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        {$html_content nofilter}
    </div>
</div>
{hook h='displayETSMPFooterYourAccount'}