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
{if $is17}
    <footer class="page-footer">
        {if $seller_account}
            <a href="{$seller_account|escape:'html':'UTF-8'}" class="account-link">
                <i class="material-icons">chevron_left</i>
                <span>{l s='Back to seller account' mod='ets_marketplace'}</span>
            </a>
        {else}
            <a href="{$link->getPageLink('my-account')|escape:'html':'UTF-8'}" class="account-link">
                <i class="material-icons">chevron_left</i>
                <span>{l s='Back to your account' mod='ets_marketplace'}</span>
            </a>
        {/if}
        <a href="{$link->getPageLink('index')|escape:'html':'UTF-8'}" class="account-link">
            <i class="material-icons">home</i>
            <span>{l s='Home' mod='ets_marketplace'}</span>
        </a>
    </footer>
{else}
    <ul class="footer_links clearfix">
        {if $seller_account}
            <li>
        		<a class="btn btn-default button button-small" href="{$seller_account|escape:'html':'UTF-8'}">
        			<span>
        				<i class="icon-chevron-left"></i> {l s='Back to seller account' mod='ets_marketplace'}
        			</span>
        		</a>
        	</li>
        {else}
        	<li>
        		<a class="btn btn-default button button-small" href="{$link->getPageLink('my-account')|escape:'html':'UTF-8'}">
        			<span>
        				<i class="icon-chevron-left"></i> {l s='Back to your account' mod='ets_marketplace'}
        			</span>
        		</a>
        	</li>
        {/if}
    	<li>
    		<a class="btn btn-default button button-small" href="{$link->getPageLink('index')|escape:'html':'UTF-8'}">
    			<span>
    				<i class="icon-chevron-left"></i> {l s='Home' mod='ets_marketplace'}
    			</span>
    		</a>
    	</li>
    </ul>
{/if}