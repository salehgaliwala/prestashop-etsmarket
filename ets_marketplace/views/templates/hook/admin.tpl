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
<script>
    var confim_delete_logo = '{l s='Do you want to delete this logo' mod='ets_marketplace' js=1}';
</script>
<script type="text/javascript" src="{$ets_mp_module_dir|escape:'html':'UTF-8'}views/js/admin.js"></script>
<script type="text/javascript">
    {if isset($ets_link_search_seller)}
        var ets_link_search_seller ='{$ets_link_search_seller nofilter}';
    {/if}
</script>
{$ets_mp_sidebar nofilter}
<div class="etsmp-left-panel col-lg-12">
    {if isset($smarty.get.controller) && $smarty.get.controller!='AdminMarketPlaceDashboard'}
    <nav  class="breadcrumb hidden-sm-down">
      <ol>
          <li>
            <a href="{$link->getAdminLink('AdminMarketPlaceDashboard')|escape:'html':'UTF-8'}">
                <span ><i class="icon fa-home"></i></span>
            </a>
          </li>
          {if $smarty.get.controller=='AdminMarketPlacePayments' || $smarty.get.controller=='AdminMarketPlaceCommissionsUsage' || $smarty.get.controller=='AdminMarketPlaceSettingsGeneral' || $smarty.get.controller=='AdminMarketPlaceCronJob'}
              <li>
                <span >{l s='Setting' mod='ets_marketplace'}</span>
              </li>
          {/if}
          {if $smarty.get.controller=='AdminMarketPlaceReport' || $smarty.get.controller=='AdminMarketPlaceShopGroups'}
              <li>
                <span >{l s='Shops' mod='ets_marketplace'}</span>
              </li>  
          {/if}
          <li>
                {if $smarty.get.controller=='AdminMarketPlacePayments'}
                    <span >{l s='Payment method' mod='ets_marketplace'}</span>
                {elseif $smarty.get.controller=='AdminMarketPlaceCommissionsUsage'}
                    <span >{l s='Commission' mod='ets_marketplace'}</span>
                {elseif $smarty.get.controller=='AdminMarketPlaceCronJob'}
                    <span >{l s='Cronjob' mod='ets_marketplace'}</span>
                {elseif $smarty.get.controller=='AdminMarketPlaceOrders'}
                    <span >{l s='Orders' mod='ets_marketplace'}</span>
                {elseif $smarty.get.controller=='AdminMarketPlaceProducts'}
                    <span >{l s='Products' mod='ets_marketplace'}</span>
                {elseif $smarty.get.controller=='AdminMarketPlaceCommissions'}
                    <span >{l s='Commissions' mod='ets_marketplace'}</span>
                {elseif $smarty.get.controller=='AdminMarketPlaceBillings'}
                    <span >{l s='Membership' mod='ets_marketplace'}</span>
                {elseif $smarty.get.controller=='AdminMarketPlaceWithdrawals'}
                    <span >{l s='Withdrawals' mod='ets_marketplace'}</span>
                {elseif $smarty.get.controller=='AdminMarketPlaceRegistrations'}
                    <span >{l s='Applications' mod='ets_marketplace'}</span>
                {elseif $smarty.get.controller=='AdminMarketPlaceSellers'}
                    <span >{l s='Shops' mod='ets_marketplace'}</span>
                {elseif $smarty.get.controller=='AdminMarketPlaceReport'}
                    <span >{l s='Reports' mod='ets_marketplace'}</span>
                {elseif $smarty.get.controller=='AdminMarketPlaceShopGroups'}
                    <span >{l s='Shop groups' mod='ets_marketplace'}</span>
                {elseif $smarty.get.controller=='AdminMarketPlaceRatings'}
                    <span >{l s='Ratings' mod='ets_marketplace'}</span>
                {else}
                    <span >{l s='General' mod='ets_marketplace'}</span>
                {/if}
          </li>
      </ol>
    </nav>
    {/if}
{if isset($tabActive)}
        <div class="etsws-panel">
            <style>
                #commission_usage_form .panel-heading{
                    display:none;
                }
            </style>
            <div class="title-content">
                <h1>{l s='Commission' mod='ets_marketplace'} </h1>
            </div>
            <div class="ets-ws-admin__subtabs">
                    <ul class="subtab-list">
                        <li class="{if $tabActive=='commission_usage'}active{/if}">
                            <a href="{$link->getAdminLink('AdminMarketPlaceCommissionsUsage')|escape:'html':'UTF-8'}" title="">
                                <i class="fa fa-cog"></i>
                                {l s='Usage settings' mod='ets_marketplace'}
                            </a>
                        </li>
                        <li class="{if $tabActive=='payment_settings'}active{/if}">
                            <a href="{$link->getAdminLink('AdminMarketPlaceCommissionsUsage')|escape:'html':'UTF-8'}&tabActive=payment_settings" title="">
                                <i class="fa fa-credit-card"></i>
                                {l s='Withdrawal methods' mod='ets_marketplace'}
                            </a>
                        </li>
                    </ul>
            </div>
        {/if}
{$ets_mp_body_html nofilter}
{if isset($tabActive)}
        </div>
    {/if}
</div>
<div class="clearfix"></div>