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
<p><strong>{l s='Seller email' mod='ets_marketplace'}</strong>: {$seller_email|escape:'html':'UTF-8'}</p>
{if $submit_fields}
    {foreach $submit_fields item='submit_field'}
        {if $submit_field!='seller_email' && isset($seller_fields[$submit_field]) && isset($submit_values[$submit_field])}
            <p><strong>{$seller_fields[$submit_field]|escape:'html':'UTF-8'}</strong>: {$submit_values[$submit_field]|escape:'html':'UTF-8'}</p>
        {/if}
    {/foreach}
{/if}