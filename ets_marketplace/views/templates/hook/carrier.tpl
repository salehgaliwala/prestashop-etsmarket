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
* needs please, contact us for extra customization service at an affordable price
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
    <div class="panel ets_mp-panel">
        {if (isset($display_form) && $display_form) || !($ETS_MP_SELLER_USER_GLOBAL_SHIPPING && $ETS_MP_SELLER_CREATE_SHIPPING)}
            {$carrier_content nofilter}
        {else}
            <div class="ets_mp_carrier_type">
                <div class="panel-heading">{l s='Carriers' mod='ets_marketplace'}</div>
                
                    <div class="form-group row">
                        <label class="control-label col-md-3">{l s='Using carriers' mod='ets_marketplace'}</label>
                        <div class="col-md-9">
                            <ul class="radio-inputs">
                                <li><label for="user_shipping_1"><input type="radio" name="user_shipping" value="1" id="user_shipping_1"{if $ets_seller->user_shipping==1} checked="checked"{/if} /> {l s='Use the store\'s global carriers' mod='ets_marketplace'}</label></li>
                                <li><label for="user_shipping_2"><input type="radio" name="user_shipping" value="2" id="user_shipping_2"{if $ets_seller->user_shipping==2} checked="checked"{/if}/> {l s='Create your own carriers and transportation costs' mod='ets_marketplace'}</label></li>
                                <li><label for="user_shipping_3"><input type="radio" name="user_shipping" value="3" id="user_shipping_3"{if $ets_seller->user_shipping==3} checked="checked"{/if}/> {l s='Use both store\'s global carriers and your own carriers' mod='ets_marketplace'}</label></li>
                            </ul>
                        </div>
                    </div>
            </div>
            <div class="ets_mp_carrier_content">
                {$carrier_content nofilter}
            </div>
        {/if}
    </div>
</div>
</div>