{*
* 2007-2018 ETS-Soft
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
{if $categories}
<span class="ets_mp_block-categories_arrow s" title="{l s='Filter' mod='ets_marketplace'}"></span>
        <div class="ets_mp_block-categories block-categories">
            {function name="categories" nodes=[] depth=0}
              {strip}
                {if $nodes|count}
                  <ul class="ets_mp_category-sub-menu category-sub-menu">
                    {foreach from=$nodes item=node}
                      <li data-depth="{$depth|escape:'html':'UTF-8'}">
                        {if $depth===0}
                          <label class="" for="{$current_tab|escape:'html':'UTF-8'}_shop_categories_{$node.id|intval}"><input id="{$current_tab|escape:'html':'UTF-8'}_shop_categories_{$node.id|intval}" type="checkbox" class="shop_categories" name="shop_categories[]" value="{$node.id|intval}" /> {$node.name|escape:'html':'UTF-8'}{if $node.total_product} ({$node.total_product|intval}){/if}</label>
                          {if $node.children}
                            <div class="navbar-toggler collapse-icons collapsed" data-toggle="collapse" data-target="#{$current_tab|escape:'html':'UTF-8'}exCollapsingNavbar{$node.id|escape:'html':'UTF-8'}">
                              <i class="material-icons add">&#xE145;</i>
                              <i class="material-icons remove">&#xE15B;</i>
                            </div>
                            <div class="collapse" id="{$current_tab|escape:'html':'UTF-8'}exCollapsingNavbar{$node.id|escape:'html':'UTF-8'}">
                              {categories nodes=$node.children depth=$depth+1}
                            </div>
                          {/if}
                        {else}
                          <label for="{$current_tab|escape:'html':'UTF-8'}_shop_categories_{$node.id|intval}" class="category-sub-link chung" href="{$node.link|escape:'html':'UTF-8'}"><input id="{$current_tab|escape:'html':'UTF-8'}_shop_categories_{$node.id|intval}" type="checkbox" class="shop_categories" name="shop_categories[]" value="{$node.id|intval}" /> {$node.name|escape:'html':'UTF-8'} {if $node.total_product} ({$node.total_product|intval}){/if}</label>
                          {if $node.children}
                            <span class="arrows collapsed" data-toggle="collapse" data-target="#{$current_tab|escape:'html':'UTF-8'}exCollapsingNavbar{$node.id|escape:'html':'UTF-8'}">
                                  <i class="material-icons add">&#xE145;</i>
                                  <i class="material-icons remove">&#xE15B;</i>
                            </span>
                            <div class="collapse" id="{$current_tab|escape:'html':'UTF-8'}exCollapsingNavbar{$node.id|escape:'html':'UTF-8'}">
                              {categories nodes=$node.children depth=$depth+1}
                            </div>
                          {/if}
                        {/if}
                      </li>
                    {/foreach}
                  </ul>
                {/if}
              {/strip}
            {/function}
              <ul class="ets-mp-category-top-menu category-top-menu">
                <li data-depth="0">
                    <div class="" id="{$current_tab|escape:'html':'UTF-8'}exCollapsingNavbar{$categories.id|intval}">
                      {categories nodes=$categories.children depth=1}
                    </div>
                </li>
              </ul>
              <button class="btn btn-primary clear_selection">{l s='Clear selection' mod='ets_marketplace'}</button>
        </div>
{/if}