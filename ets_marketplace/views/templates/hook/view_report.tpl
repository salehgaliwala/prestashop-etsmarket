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
<div class="row display-flex-nocenter md-block ets_mp-panel">
     <div class="col-lg-12">
        <div class="panel">
            <div class="panel-body detail-report">
                <h4 class="">{l s='Report detail:' mod='ets_marketplace'}</h4>
                <div class="row">
                    <label>{l s='Report title:' mod='ets_marketplace'}</label> {$report.title|escape:'html':'UTF-8'}   
                </div>
                <div class="row">
                    <label>{l s='Report content:' mod='ets_marketplace'}</label> {$report.content|escape:'html':'UTF-8'}   
                </div>
                {if $report.product_name}
                    <div class="row">
                        <label>{l s='Reported product:' mod='ets_marketplace'}</label> <a href="{$link->getProductLink($report.id_product)|escape:'html':'UTF-8'}" target="_blank">{$report.product_name|escape:'html':'UTF-8'} </a>  
                    </div>
                {/if}
                <div class="row">
                    <label>{l s='Reported shop:' mod='ets_marketplace'}</label> <a href="{$module_marketplace->getShopLink(['id_seller'=>$report.id_seller])|escape:'html':'UTF-8'}" target="_blank">{$report.shop_name|escape:'html':'UTF-8'}</a>   
                </div>
                <div class="row">
                    <label>{l s='Reporter:' mod='ets_marketplace'}</label> {$report.reporter_name|escape:'html':'UTF-8'} ({$report.email|escape:'html':'UTF-8'}) 
                </div>
            </div>
        </div>
    </div>
</div>
