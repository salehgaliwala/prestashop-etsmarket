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
<script>
    var zones_nbr = 11 ; 
</script>
<div id="zone_ranges" style="overflow:auto">
    <h4>{l s='Ranges' mod='ets_marketplace'}</h4>
    <table id="zones_table" class="table" style="max-width:100%">
        <tr class="range_inf">
            <td class="range_type"></td>
            <td class="border_left border_bottom range_sign">>=</td>
            {if $ranges}
                {foreach from=$ranges item='range'}
                    <td class="border_bottom">
                        <div class="input-group fixed-width-md">
                            <span class="input-group-addon weight_unit">kg</span>
                            <span class="input-group-addon price_unit">{$currency->sign|escape:'html':'UTF-8'}</span>
                            <input class="form-control" name="range_inf[{$range.id_range|intval}]" value="{$range.delimiter1|floatval}" type="text" />
                        </div>
                    </td>
                {/foreach}
            {else}
                <td class="border_bottom">
                    <div class="input-group fixed-width-md">
                        <span class="input-group-addon weight_unit">kg</span>
                        <span class="input-group-addon price_unit">{$currency->sign|escape:'html':'UTF-8'}</span>
                        <input class="form-control" name="range_inf[0]" value="0.000000" type="text" />
                    </div>
                </td>
            {/if}
        </tr>
        <tr class="range_sup">
            <td class="range_type"></td>
            <td class="border_left range_sign"><</td>
            {if $ranges}
                {foreach from=$ranges item='range'}
                    <td class="range_data">
                        <div class="input-group fixed-width-md">
                            <span class="input-group-addon weight_unit">kg</span>
                            <span class="input-group-addon price_unit">{$currency->sign|escape:'html':'UTF-8'}</span>
                            <input class="form-control" name="range_sup[{$range.id_range|intval}]" value="{$range.delimiter2|floatval}" autocomplete="off" type="text" />
                        </div>
                    </td>
                {/foreach}
            {else}
                <td class="range_data">
                    <div class="input-group fixed-width-md">
                        <span class="input-group-addon weight_unit">kg</span>
                        <span class="input-group-addon price_unit">{$currency->sign|escape:'html':'UTF-8'}</span>
                        <input class="form-control" name="range_sup[0]" value="" autocomplete="off" type="text" />
                    </div>
                </td>
            {/if}    
        </tr>
        <tr class="fees_all">
            <td class="border_top border_bottom border_bold">
                <span class="fees_all">{l s='All' mod='ets_marketplace'}</span>
            </td>
            <td style="">
                <input class="form-control" id="checked_all_zone" type="checkbox" />
            </td>
            {if $ranges}
                {foreach from=$ranges item='range'}
                    <td class="border_top border_bottom{if $range.delimiter2 > $range.delimiter1} validated{/if} ">
                        <div class="input-group fixed-width-md">
                            <span class="input-group-addon currency_sign" style="display:none">{$currency->sign|escape:'html':'UTF-8'}</span>
                            <input class="form-control" style="display:none" autocomplete="off" type="text" />
                        </div>
                    </td>
                {/foreach}
            {else}
                <td class="border_top border_bottom  ">
                    <div class="input-group fixed-width-md">
                        <span class="input-group-addon currency_sign" style="display:none">{$currency->sign|escape:'html':'UTF-8'}</span>
                        <input class="form-control" style="display:none" autocomplete="off" type="text" />
                    </div>
                </td>
            {/if}
        </tr>
        {if $zones}
            {foreach from=$zones item='zone'}
                <tr class="fees" data-zoneid="{$zone.id_zone|intval}">
                    <td>
                        <label for="zone_{$zone.id_zone|intval}">{$zone.name|escape:'html':'UTF-8'}</label>
                    </td>
                    <td class="zone">
                        <input id="zone_{$zone.id_zone|intval}" class="form-control input_zone" name="zone_{$zone.id_zone|intval}" value="1"{if $zone.checked} checked="checked"{/if} type="checkbox" />
                    </td>
                    {if $ranges}
                        {foreach from=$ranges item='range'}
                            <td>
                                <div class="input-group fixed-width-md">
                                    <span class="input-group-addon">{$currency->sign|escape:'html':'UTF-8'}</span>
                                    <input {if !$zone.checked} disabled="disabled"{/if} class="form-control" name="fees[{$zone.id_zone|intval}][{$range.id_range|intval}]" value="{if isset($deliveries[$zone.id_zone][$range.id_range]) && $deliveries[$zone.id_zone][$range.id_range]}{$deliveries[$zone.id_zone][$range.id_range]|escape:'html':'UTF-8'}{/if}" type="text" />
                                </div>
                            </td>
                        {/foreach}
                    {else}
                        <td>
                            <div class="input-group fixed-width-md">
                                <span class="input-group-addon">{$currency->sign|escape:'html':'UTF-8'}</span>
                                <input {if !$zone.checked} disabled="disabled"{/if} class="form-control" name="fees[{$zone.id_zone|intval}][0]" value="" type="text" />
                            </div>
                        </td>
                    {/if}
                </tr>
            {/foreach}
            <tr class="delete_range">
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                {if $ranges}
                    {foreach from=$ranges key='key' item='range'}
                        <td>
                            {if $key>0}
                                <button class="btn btn-default">{l s='Delete' mod='ets_marketplace'}</button>
                            {else}
                                &nbsp;
                            {/if}
                        </td>
                    {/foreach}
                {else}
                    <td>&nbsp;</td>
                {/if}
              
            </tr>
        {/if}
    </table>
</div>