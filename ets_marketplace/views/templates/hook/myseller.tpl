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
{if !$seller && $registration && !$registration->active}
    <div class="ets_mp_content_left">
        <div class="panel">
            <div class="alert alert-info">
                {l s='Your application is waiting for approval' mod='ets_marketplace'}
                {*$ETS_MP_MESSAGE_CUSTOMER_WAITTING_APPROVAL nofilter*}
            </div>
        </div>
    </div>
{else}
    
    {if $seller}
        {if !$seller->active || $seller->active==-3}
            <div class="ets_mp_content_left">
                <div class="panel">
                    <div class="alert alert-error" style="margin-bottom:0;">
                        {if !$seller->active}
                            {$ETS_MP_MESSAGE_SELLER_IS_DISABLED nofilter} 
                        {else}
                            {$ETS_MP_MESSAGE_SHOP_DECLINED nofilter}
                        {/if}
                    </div>
                </div>
            </div>
        {else}
            {if $seller->active==-1 || $seller->active==-2}
                <div class="ets_mp_content_left">
                    <div class="panel">
                        <div class="alert alert-info">
                            {if $seller->active==-1}
                                {if $seller->getFeeType()!='no_fee'}
                                    {if $seller_billing &&  $seller_billing->seller_confirm ==1}
                                        {$ETS_MP_MESSAGE_CREATED_SHOP_FEE_REQUIRED nofilter}
                                    {else}
                                        {$ETS_MP_MESSAGE_CONFIRMED_PAYMENT nofilter}
                                    {/if}
                                {else}
                                    {$ETS_MP_MESSAGE_CREATED_SHOP_NO_FEE nofilter}
                                {/if}
                            {/if}
                            {if $seller->active==-2}
                                {if $seller_billing &&  $seller_billing->seller_confirm ==1}
                                    {$ETS_MP_MESSAGE_CONFIRMED_PAYMENT nofilter}
                                {else}
                                    {$ETS_MP_MESSAGE_SELLER_IS_EXPIRED nofilter}
                                {/if}
                            {/if}
                        </div>
                        {if $seller->active==-1 && $seller->getFeeType()!='no_fee' && $ETS_MP_SELLER_FEE_EXPLANATION}
                            {if $ETS_MP_SELLER_FEE_EXPLANATION && $seller_billing->active==0 && $seller_billing->seller_confirm==0}
                                <div class="fee_explanation">
                                    <b>{l s='Fee explanation' mod='ets_marketplace'}:</b> {$ETS_MP_SELLER_FEE_EXPLANATION nofilter}
                                </div>
                            {/if}
                        {/if}
                        {if $seller_billing &&  $seller_billing->active==0 && $seller_billing->seller_confirm==0 && !$isManager}
                            <button type="button" class="btn btn-primary i_have_just_sent_the_fee">{l s='I have just sent the fee' mod='ets_marketplace'}</button>
                        {/if}
                    </div>
                </div>
            {else}
                
                <section id="main" class="page-my-account myseller">
                    <header class="page-header">
                        <h1> {l s='Your seller account' mod='ets_marketplace'} </h1>
                    </header>
                    {if $going_to_be_expired}
                        <div class="alert alert-info">
                            {if $seller_billing &&  $seller_billing->seller_confirm ==1}
                                {$ETS_MP_MESSAGE_CONFIRMED_PAYMENT nofilter}
                            {else}
                                {$ETS_MP_MESSAGE_SELLER_GOING_TOBE_EXPIRED nofilter}
                            {/if}
                            {if $seller_billing &&  $seller_billing->active==0 && $seller_billing->seller_confirm==0 && !$isManager}
                                <br/>
                                <button type="button" class="btn btn-primary i_have_just_sent_the_fee">{l s='I have just sent the fee' mod='ets_marketplace'}</button>
                            {/if}
                        </div>
                    {/if}
                    <section id="content" class="page-content">
                        <div class="row myseller-list">
                            <div class="links">
                                {foreach from=$seller_pages item='page'}
                                	<a id="ets_mp_{$page.page|escape:'html':'UTF-8'}-link" href="{if isset($page.link)}{$page.link|escape:'html':'UTF-8'}{else} {$link->getModuleLink('ets_marketplace',$page.page)|escape:'html':'UTF-8'}{/if}" class="seller_link col-lg-4 col-md-6 col-sm-6 col-xs-12" {if isset($page.new_tab) && $page.new_tab} target="_blank"{/if}>
                                        <span class="link-item">
                                            <i class="mp-seller-{$page.page|escape:'html':'UTF-8'}icons"></i>
                                            {$page.name|escape:'html':'UTF-8'}
                                        </span>
                                	</a>
                                 {/foreach}
                            </div>
                        </div>
                    </section>
                </section>
            {/if}
        {/if}
    {else}
        <div class="ets_mp_content_left">
        <div class="panel">
        {if $registration && $registration->active}
            {$ETS_MP_MESSAGE_APPLICATION_ACCEPTED nofilter}
        {else}
            <div class="alert alert-info">
                {$ETS_MP_MESSAGE_INVITE nofilter} 
            </div>
        {/if}
        <br />
        <a class="btn btn-primary" style="margin-top: 15px" href="{$link->getModuleLink('ets_marketplace','create')|escape:'html':'UTF-8'}">
            <i class="icon icon-new"></i> {l s='Create shop' mod='ets_marketplace'}</a>
        </div>
        </div>
    {/if}
{/if}