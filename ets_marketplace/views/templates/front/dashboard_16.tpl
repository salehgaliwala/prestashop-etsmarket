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
    {if $going_to_be_expired}
        <div class="alert alert-info">
            {if $seller->payment_verify==1}
                {$ETS_MP_MESSAGE_CONFIRMED_PAYMENT nofilter}
            {else}
                {$ETS_MP_MESSAGE_SELLER_GOING_TOBE_EXPIRED nofilter}
            {/if}
            {if $seller->payment_verify==-1}
                <br/>
                <button type="button" class="btn btn-primary i_have_just_sent_the_fee">{l s='I have just sent the fee' mod='ets_marketplace'}</button>
            {/if}
        </div>
    {/if}
    <div class="ets_mp_content_left col-lg-3" >
        {hook h='displayMPLeftContent'}
    </div>
    <div class="ets_mp_content_left col-lg-9" >
        {$html_content nofilter}
    </div>
</div>
{hook h='displayETSMPFooterYourAccount'}