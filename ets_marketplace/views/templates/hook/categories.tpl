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
{if $blockCategTree}
    {if !in_array($blockCategTree[0].id_category,$disabled_categories)}
    <li style="list-style: none;">
        <div class="checkbox {if $blockCategTree[0].children} has-child{/if}">
            <span>
                {if $displayInput}
                    <input class="category" name="{$name|escape:'html':'UTF-8'}[]" value="{$blockCategTree[0].id_category|intval}"{if in_array($blockCategTree[0].id_category,$selected_categories)} checked="checked"{/if}{if in_array($blockCategTree[0].id_category,$disabled_categories)} disabled="disabled"{/if}  type="checkbox" />
                {/if}
                <span class="label">{$blockCategTree[0].name|escape:'html':'UTF-8'}</span>
                {if !$backend}
                    <input class="default-category" value="{$blockCategTree[0].id_category|intval}" name="id_category_default" type="radio" {if in_array($blockCategTree[0].id_category,$disabled_categories)} disabled="disabled"{/if}{if in_array($blockCategTree[0].id_category,$selected_categories)} checked="checked"{/if} />
                {/if}
                {if !$displayInput}
                    (ID: {$blockCategTree[0].id_category|intval})
                {/if}
            </span>
        </div>
    {/if}
        {if $blockCategTree[0].children}
            {if !in_array($blockCategTree[0].id_category,$disabled_categories)}
  		        <ul class="children">
            {/if}
                {foreach from=$blockCategTree[0].children item=child name=blockCategTree}
                    {if $smarty.foreach.blockCategTree.last}
            			{include file="$branche_tpl_path_input" node=$child last='true'}
            		{else}
            			{include file="$branche_tpl_path_input" node=$child}
            		{/if}
                    
            	{/foreach}
            {if !in_array($blockCategTree[0].id_category,$disabled_categories)}
            </ul>
            {/if}
        {/if}
    {if !in_array($blockCategTree[0].id_category,$disabled_categories)}
    </li>
    {/if}
{/if}