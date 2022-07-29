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

<div class="panel ets_mp-panel">
    <div class="panel-heading">
        {*<i class="icon-cronjob"></i>&nbsp;*}{l s='Cronjob' mod='ets_marketplace'}<br />
    </div>
    <ul class="mkt_config_tab_header cronjob">
        <li class="confi_tab config_tab_configuration active" data-tab-id="configuration">{l s='Configuration' mod='ets_marketplace'}</li>
        <li class="confi_tab config_tab_cronjob_log" data-tab-id="cronjob_log">{l s='Cronjob log' mod='ets_marketplace'}</li>
    </ul>
    <div class="clearfix">
        <div class="emp-cronjob ets_mp_form configuration active">
        	<div class="row mt-15">
        		<div class="col-lg-12">
        			<p class="ets-mp-text-strong mb-10"><span style="color: red;">*</span> {l s='Some important notes before setting Cronjob:' mod='ets_marketplace'}</p>
        			<ul>
        				<li>{l s='Cronjob frequency should be at least twice per day, the recommended frequency is once per minute' mod='ets_marketplace'}</li>
        				<li>{l s='How to setup a cronjob is different depending on your server. If you\'re using a Cpanel hosting, watch this video for more reference:' mod='ets_marketplace'}
        					<a href="https://www.youtube.com/watch?v=bmBjg1nD5yA" target="_blank">https://www.youtube.com/watch?v=bmBjg1nD5yA</a> <br>
        					{l s='You can also contact your hosting provider to ask them for support on setting up the cronjob' mod='ets_marketplace'}
        				</li>
        				
        			</ul>
        			<p class="ets-mp-text-strong emp-block mb-15"><span style="color: red;">*</span> {l s=' Setup a cronjob as below on your server to automatically send emails to sellers when their seller account is going to be expired and to automatically upgrade seller shops.' mod='ets_marketplace'}</p>
        			<p class="mb-15 emp-block"><span class="ets-mp-text-bg-light-gray">* * * * * php {$dir_cronjob|escape:'html':'UTF-8'} secure=<span class="emp-cronjob-secure-value">{$ETS_MP_CRONJOB_TOKEN|escape:'html':'UTF-8'}</span></span></p>
        			<p class="ets-mp-text-strong mb-10"><span style="color: red;">*</span> {l s='Execute the cronjob manually by clicking on the button below' mod='ets_marketplace'}</p>
        			<a href="{$link_conjob nofilter}" data-secure="{$ETS_MP_CRONJOB_TOKEN|escape:'html':'UTF-8'}" class="btn btn-default btn-sm mb-10 js-emp-test-cronjob"><i></i>{l s='Execute cronjob manually' mod='ets_marketplace'}</a>
        		</div>
        	</div>
        	<div class="mb-15 emp-block form-horizontal">
        		<div class="form-group">
        			<label class="control-label col-lg-12 required" style="text-align: left;margin-bottom: 10px;margin-top: 25px;">
        				{l s='Cronjob secure token:' mod='ets_marketplace'}
        			</label>
        			<div class="col-lg-6 flex">
        				<input name="ETS_MP_CRONJOB_TOKEN" id="ETS_MP_CRONJOB_TOKEN" value="{$ETS_MP_CRONJOB_TOKEN|escape:'html':'UTF-8'}" type="text" />
        				<input name="etsmpSubmitUpdateToken" class="btn btn-default" value="{l s='Update' mod='ets_marketplace'}" type="submit" />
        			</div>
        		</div>
        	</div>
            {if $cronjob_last}
                <div class="mb-15 emp-block form-horizontal">
            		<p class="alert alert-info">{l s='Last time cronjob run' mod='ets_marketplace'}: {$cronjob_last|escape:'html':'UTF-8'}</p>
            	</div>
            {/if}
         </div>
         <div class="ets_mp_form cronjob_log">
            <div class="mb-15 emp-block form-horizontal">
                <div class="form-group">
                    <label class="control-label" style="float:left;text-align: left;margin-bottom: 10px;padding-left:5px;">
        				{l s='Save cronjob log' mod='ets_marketplace'}
        			</label>
                    <div class="col-lg-9 flex">
                        <span class="switch prestashop-switch fixed-width-lg">
				            <input name="ETS_MP_SAVE_CRONJOB_LOG" id="ETS_MP_SAVE_CRONJOB_LOG_on" value="1" {if $ETS_MP_SAVE_CRONJOB_LOG}checked="checked"{/if} type="radio" />
							<label for="ETS_MP_SAVE_CRONJOB_LOG_on">{l s='Yes' mod='ets_marketplace'}</label>
				            <input name="ETS_MP_SAVE_CRONJOB_LOG" id="ETS_MP_SAVE_CRONJOB_LOG_off" value="0" type="radio" {if !$ETS_MP_SAVE_CRONJOB_LOG}checked="checked"{/if} />
							<label for="ETS_MP_SAVE_CRONJOB_LOG_off">{l s='No' mod='ets_marketplace'}</label>
							<a class="slide-button btn"></a>
						</span>
                        <p class="help-block">{l s='Only recommended for debug purpose' mod='ets_marketplace'}</p>
                    </div>
                </div>
                <div class="form-group">
        			<label class="control-label col-lg-12" style="text-align: left;margin-bottom: 10px;">
        				{l s='Cronjob log:' mod='ets_marketplace'}
        			</label>
        			<div class="col-lg-12 flex">
        				<textarea class="cronjob_log">{$cronjob_log nofilter}</textarea><br />
                        <button class="btn btn-default" name="etsmpSubmitClearLog"><i class="icon icon-trash"></i> {l s='Clear log' mod='ets_marketplace'}</button>
        			</div>
        		</div>
            </div>
         </div>
    </div>
</div>