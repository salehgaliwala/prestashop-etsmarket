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
{if $going_to_be_expired && !$isManager}
    <div class="alert alert-info">
        {if $seller_billing &&  $seller_billing->seller_confirm==1 && $seller_billing->active!=1}
            {$ETS_MP_MESSAGE_CONFIRMED_PAYMENT nofilter}
        {else}
            {$ETS_MP_MESSAGE_SELLER_GOING_TOBE_EXPIRED nofilter}
        {/if}
        {if $seller_billing &&  $seller_billing->active==0 && $seller_billing->seller_confirm==0}
            <br />
            <button type="button" class="btn btn-primary i_have_just_sent_the_fee">{l s='I have just sent the fee' mod='ets_marketplace'}</button>
        {/if}
    </div>
{/if}
<ul>
    {foreach from = $tabs item='tab'}
        <li class="ets_mp_item{if $tab.page==$controller} active{/if}">
            <a href="{if isset($tab.link)} {$tab.link|escape:'html':'UTF-8'} {else}{$link->getModuleLink('ets_marketplace',$tab.page)|escape:'html':'UTF-8'}{/if}" {if isset($tab.new_tab) && $tab.new_tab} target="_blank"{/if}>
                <i class="{if isset($tab.icon)}{$tab.icon|escape:'html':'UTF-8'}{else}mp-seller-{$tab.page|escape:'html':'UTF-8'}icons{/if}"></i>
                {$tab.name|escape:'html':'UTF-8'}
                {if $tab.page=='messages' && isset($total_message) && $total_message}
                    <b class="number_message">{$total_message|intval}</b>
                {/if}
            </a>
        </li>
    {/foreach}
</ul>