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
<div class="etsmp-left-panel etsmp-sidebar-menu col-lg-12">
    <div class="list-group">
        {foreach from = $sidebars key='key' item='sidebar'}
            <div class="list-tab{if isset($sidebar.subs) && $sidebar.subs} has_sub{/if}" id="tab-{$sidebar.controller|escape:'html':'UTF-8'}">
                <a class="list-group-item{if $control==$key || (isset($sidebar.controller) && ($sidebar.controller==$controller || (isset($sidebar.subs) && isset($sidebar.subs.$controller))))} active{/if}" href="{if isset($sidebar.controller) && $sidebar.controller}{$link->getAdminLink($sidebar.controller)|escape:'html':'UTF-8'}{else}{$mp_link_module|escape:'html':'UTF-8'}&control={$key|escape:'html':'UTF-8'}{/if}">
                    <i class="icon-{$key|escape:'html':'UTF-8'}"></i>
                    {$sidebar.title|escape:'html':'UTF-8'}
                </a>
                {if isset($sidebar.subs) && $sidebar.subs}
                    <div class="list-tab-subs">
                        {foreach from=$sidebar.subs item='sub'}
                            <a class="list-group-item{if $controller==$sub.icon ||  (isset($sub.controller) && $sub.controller==$controller)} active{/if}" href="{if isset($sub.controller) && $sub.controller}{$link->getAdminLink($sub.controller)|escape:'html':'UTF-8'}{else}{$mp_link_module|escape:'html':'UTF-8'}&control={$sub.icon|escape:'html':'UTF-8'}{/if}">
                                <i class="icon-{$sub.icon|escape:'html':'UTF-8'}"></i>
                                {$sub.title|escape:'html':'UTF-8'}
                            </a>
                        {/foreach}
                    </div>
                {/if}
            </div>
        {/foreach}
    </div>
</div>
<div class="etsmp-sidebar-height"></div>