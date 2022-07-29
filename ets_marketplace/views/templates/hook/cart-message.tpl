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
<div class="alert alert-info" style="margin-top: 10px;">
    {l s='You have ' mod='ets_marketplace'}&nbsp;{$commission_total_balance|escape:'html':'UTF-8'}&nbsp;{l s=' in your balance. It can be converted into voucher code.' mod='ets_marketplace'}&nbsp;<a href="{$link->getModuleLink('ets_marketplace','voucher')|escape:'html':'UTF-8'}">{l s='Convert now' mod='ets_marketplace'}</a>
</div>