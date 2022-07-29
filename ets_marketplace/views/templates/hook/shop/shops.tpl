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
<div id="js-shop-list-top" class="row shop-selection">
    <div class="col-xs-12"><h4>{l s='Shops' mod='ets_marketplace'}</h4></div>
    <div class="col-md-4 col-lg-4 ets_mp_shops_info">
        {if isset($ETS_MP_ENABLE_MAP) && $ETS_MP_ENABLE_MAP && Ets_mp_seller::getMaps(false,true)}
            <div class="ets_mp_maps">
                <a class="view_maps" href="{$link->getModuleLink('ets_marketplace','map')|escape:'html':'UTF-8'}"> 
                    <i class="fa fa-map-marker"></i> {l s='View maps' mod='ets_marketplace'}
                </a>
            </div>
        {/if}
    </div>
    <div class="col-md-6 col-lg-6 col_sortby">
        <div class="sort-by-row">
            <span class="hidden-sm-down sort-by">{l s='Sort by:' mod='ets_marketplace'}</span>
            <div class="products-sort-order dropdown">
                <select class="ets_mp_sort_by_shop_list">
                    <option value="sale.desc">{l s='Popular' mod='ets_marketplace'}</option>
                    {if Module::isEnabled('ets_productcomments') || Module::isEnabled('productcomments')}
                        <option value="rate.desc">{l s='Rating' mod='ets_marketplace'}</option>
                    {/if}
                    <option value="quantity.desc">{l s='Product quantity' mod='ets_marketplace'}</option>
                    <option value="name.asc">{l s='Name, A to Z' mod='ets_marketplace'}</option>
                    <option value="name.desc">{l s='Name, Z to A' mod='ets_marketplace'}</option>
                    <option value="date_add.desc">{l s='Newest' mod='ets_marketplace'}</option>
                    <option value="date_add.asc">{l s='Oldest' mod='ets_marketplace'}</option>
                </select>
            </div>
        </div>
    </div>
</div>
<ul class="ets_mp_list_seller">
    {$shop_list nofilter}
</ul>
<div class="paggination">
    {$paggination nofilter}
</div>
