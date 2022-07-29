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
<script type="text/javascript">
var ets_mp_url_search_customer = '{$ets_mp_url_search_customer nofilter}';
var ets_mp_url_search_product = '{$ets_mp_url_search_product nofilter}';
</script>
<div class="row">
    <div class="col-lg-12">
        <div class="panel">
            <h3>
                <i class="icon-tag"></i>
                {l s='Add new discount' mod='ets_marketplace'}
            </h3>
            <div class="productTabs">
        		<ul class="tab nav nav-tabs">
        			<li class="tab-row{if $currentFormTab=='informations'} active{/if}">
        				<a class="tab-page" id="cart_rule_link_informations" href="javascript:ets_displayCartRuleTab('informations');"><i class="icon-info"></i> {l s='Information' mod='ets_marketplace'}</a>
        			</li>
        			<li class="tab-row{if $currentFormTab=='conditions'} active{/if}">
        				<a class="tab-page" id="cart_rule_link_conditions" href="javascript:ets_displayCartRuleTab('conditions');"><i class="icon-random"></i> {l s='Conditions' mod='ets_marketplace'}</a>
        			</li>
        			<li class="tab-row{if $currentFormTab=='actions'} active{/if}">
        				<a class="tab-page" id="cart_rule_link_actions" href="javascript:ets_displayCartRuleTab('actions');"><i class="icon-wrench"></i> {l s='Actions' mod='ets_marketplace'}</a>
        			</li>
        		</ul>
        	</div>
            <form id="cart_rule_form" class="form-horizontal" action="" method="post">
                <input id="currentFormTab" name="currentFormTab" value="{$currentFormTab|escape:'html':'UTF-8'}" type="hidden" />
                <input type="hidden" name="id_cart_rule" value="{$id_cart_rule|intval}"/>
                <div class="ets_mp-form-content">
                    <div id="cart_rule_informations" class="panel cart_rule_tab"{if $currentFormTab=='informations'} style="display: block;"{else} style="display: none;"{/if}>
                        {$html_informations nofilter}
                    </div>
                    <div id="cart_rule_conditions" class="panel cart_rule_tab"{if $currentFormTab=='conditions'} style="display: block;"{else} style="display: none;"{/if}>
                        {$html_conditions nofilter}
                    </div>
                    <div id="cart_rule_actions" class="panel cart_rule_tab"{if $currentFormTab=='actions'} style="display: block;"{else} style="display: none;"{/if}>
                        {$html_actions nofilter}
                    </div>
                </div>
                <div class="ets_mp-form-footer">
                    <input type="hidden" name="submitSaveCartRule" value="1"/>
                    <a class="btn btn-secondary bd text-uppercase float-xs-left" href="{$link->getModuleLink('ets_marketplace','discount',['list'=>1])|escape:'html':'UTF-8'}" title="">
                        <i class="fa fa-back icon icon-back process-icon-back"></i> {l s='Back' mod='ets_marketplace'}
                    </a>
                    <button name="submitSaveCartRule" type="submit" class="btn btn-primary form-control-submit float-xs-right">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
