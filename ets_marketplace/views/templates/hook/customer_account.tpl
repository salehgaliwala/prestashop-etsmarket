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
{if !$require_registration || ($registration && $registration->active==1)||$seller} 
    {if !$is17}
        <li class="ets_mp-box">
    {/if}
	<a id="ets_mp_registration-link" href="{$link->getModuleLink('ets_marketplace','products')|escape:'html':'UTF-8'}" class="{if isset($is17) && $is17}col-lg-4 col-md-6 col-sm-6 col-xs-12{/if}">
        <span class="link-item">
            <i class="mp-seller-icons"></i>
              {if $seller}{l s='My seller account' mod='ets_marketplace'}{else}{l s='My seller account' mod='ets_marketplace'}{/if}
        </span>
	</a>
    {if !$is17}
        </li>
    {/if}
{else}
    {if !$is17}
        <li class="ets_mp-box">
    {/if}
	<a id="ets_mp_registration-link" href="{$link->getModuleLink('ets_marketplace','registration')|escape:'html':'UTF-8'}" class="{if isset($is17) && $is17}col-lg-4 col-md-6 col-sm-6 col-xs-12{/if}">
        <span class="link-item">
            <i class="mp-registration-icons"></i>
              {l s='My seller account' mod='ets_marketplace'}
        </span>
	</a>
    {if !$is17}
        </li>
    {/if}
{/if}
{*if !$is17}
    <li class="ets_mp-box">
{/if}
    <a id="ets_mp_messages-link" href="{$link->getModuleLink('ets_marketplace','contactseller')|escape:'html':'UTF-8'}" class="{if isset($is17) && $is17}col-lg-4 col-md-6 col-sm-6 col-xs-12{/if}">
        <span class="link-item">
            <i class="fa fa-comments"></i>
            {l s='Contact shop' mod='ets_marketplace'}  
        </span>
    </a>
{if !$is17}
    </li>
{/if*}