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
var text_update_position='{l s='Successful update' mod='ets_marketplace'}';
</script>
<div class="panel ets_mp-panel{if isset($class)} {$class|escape:'html':'UTF-8'}{/if}">
    <div class="panel-heading">{*if isset($icon) && $icon}<i class="{$icon|escape:'html':'UTF-8'}"></i>&nbsp;{/if*}{$title nofilter}
        {if isset($totalRecords) && $totalRecords>0}<span class="badge">{$totalRecords|intval}</span>{/if}
        <span class="panel-heading-action">
            {if isset($show_add_new) && $show_add_new}            
                <a class="list-toolbar-btn add_new_link" href="{if isset($link_new)}{$link_new|escape:'html':'UTF-8'}{else}{$currentIndex|escape:'html':'UTF-8'}{/if}">
                    <span data-placement="top" data-html="true" data-original-title="{l s='Add new' mod='ets_marketplace'}" class="label-tooltip" data-toggle="tooltip" title="">
        				<i class="process-icon-new"></i> {l s='Add new' mod='ets_marketplace'}
                    </span>
                </a>            
            {/if}
            {if isset($preview_link) && $preview_link}            
                <a target="_blank" class="list-toolbar-btn" href="{$preview_link|escape:'html':'UTF-8'}">
                    <span data-placement="top" data-html="true" data-original-title="{l s='Preview ' mod='ets_marketplace'} ({$title|escape:'html':'UTF-8'})" class="label-tooltip" data-toggle="tooltip" title="">
        				<i style="margin-left: 5px;" class="icon-search-plus"></i>
                    </span>
                </a>            
            {/if}
            {* _ARM_ Prevent export/import
            if isset($link_export) && $link_export}            
                <a  class="list-toolbar-btn" href="{$link_export|escape:'html':'UTF-8'}">
                    <span data-placement="top" data-html="true" data-original-title="{l s='Export ' mod='ets_marketplace'} ({$title|escape:'html':'UTF-8'})" class="label-tooltip" data-toggle="tooltip" title="">
        				<i style="margin-left: 5px;" class="icon icon-import fa fa-import"></i> {l s='Export' mod='ets_marketplace'}
                    </span>
                </a>            
            {/if}
            {if isset($link_import) && $link_import}            
                <a class="list-toolbar-btn" href="{$link_import|escape:'html':'UTF-8'}">
                    <span data-placement="top" data-html="true" data-original-title="{l s='Import ' mod='ets_marketplace'} ({$title|escape:'html':'UTF-8'})" class="label-tooltip" data-toggle="tooltip" title="">
        				<i style="margin-left: 5px;" class="icon icon-export fa fa-export"></i> {l s='Import' mod='ets_marketplace'}
                    </span>
                </a>            
            {/if*}
        </span>
    </div>
    {if $fields_list}
        <div class="table-responsive clearfix">
            <form method="post" action="{$currentIndex|escape:'html':'UTF-8'}">
                {if isset($bulk_action_html)}
                    {$bulk_action_html nofilter}
                {/if}
                <table class="table configuration alltab_ss{if isset($has_delete_product) && (Configuration::get('ETS_MP_ALLOW_SELLER_CREATE_PRODUCT') || (isset($has_edit_product) && $has_edit_product) || $has_delete_product) } allow_checkbox_product{/if} list-{$name|escape:'html':'UTF-8'}">
                    <thead>
                        <tr class="nodrag nodrop">
                            {assign var ='i' value=1}
                            {foreach from=$fields_list item='field' key='index'}
                                {if $index eq 'stock_quantity'}{continue}{/if}
                                <th class="{$index|escape:'html':'UTF-8'}{if isset($field.class)} {$field.class|escape:'html':'UTF-8'}{/if}" {if $show_action && !$actions && count($fields_list)==$i}colspan="2"{/if}>
                                    <span class="title_box">
                                        {$field.title|escape:'html':'UTF-8'}
                                        {if isset($field.sort) && $field.sort}
                                            <span class="soft">
                                            <a href="{$currentIndex|escape:'html':'UTF-8'}&sort={$index|escape:'html':'UTF-8'}&sort_type=desc{$filter_params nofilter}" {if isset($sort)&& $sort==$index && isset($sort_type) && $sort_type=='desc'} class="active"{/if}><i class="icon-caret-down"></i></a>
                                            <a href="{$currentIndex|escape:'html':'UTF-8'}&sort={$index|escape:'html':'UTF-8'}&sort_type=asc{$filter_params nofilter}" {if isset($sort)&& $sort==$index && isset($sort_type) && $sort_type=='asc'} class="active"{/if}><i class="icon-caret-up"></i></a>
                                            </span>
                                         {/if}
                                    </span>
                                </th>  
                                {assign var ='i' value=$i+1}                          
                            {/foreach}
                            {if $show_action && $actions}
                                <th class="table_action" style="text-align: right;">{l s='Action' mod='ets_marketplace'}</th>
                            {/if}
                        </tr>
                        {if $show_toolbar}
                            <tr class="nodrag nodrop filter row_hover">
                                {foreach from=$fields_list item='field' key='index'}
                                    {if $index eq 'stock_quantity'}{continue}{/if}
                                    <th class="{$index|escape:'html':'UTF-8'}{if isset($field.class)} {$field.class|escape:'html':'UTF-8'}{/if}">
                                        {if isset($field.filter) && $field.filter}
                                            {if $field.type=='text'}
                                                <input class="filter" name="{$index|escape:'html':'UTF-8'}" type="text" {if isset($field.width)}style="width: {$field.width|intval}px;"{/if} {if isset($field.active)}value="{$field.active|escape:'html':'UTF-8'}"{/if}/>
                                            {/if}
                                            {if $field.type=='select' || $field.type=='active'}
                                                <select  {if isset($field.width)}style="width: {$field.width|intval}px;"{/if}  name="{$index|escape:'html':'UTF-8'}">
                                                    <option value=""> -- </option>
                                                    {if isset($field.filter_list.list) && $field.filter_list.list}
                                                        {assign var='id_option' value=$field.filter_list.id_option}
                                                        {assign var='value' value=$field.filter_list.value}
                                                        {foreach from=$field.filter_list.list item='option'}
                                                            <option {if ($field.active!=='' && $field.active==$option.$id_option) || ($field.active=='' && $index=='has_post' && $option.$id_option==1 )} selected="selected"{/if} value="{$option.$id_option|escape:'html':'UTF-8'}">{$option.$value|escape:'html':'UTF-8'}</option>
                                                        {/foreach}
                                                    {/if}
                                                </select>                                            
                                            {/if}
                                            {if $field.type=='int'}
                                                <label for="{$index|escape:'html':'UTF-8'}_min"><input type="text" placeholder="{l s='Min' mod='ets_marketplace'}" name="{$index|escape:'html':'UTF-8'}_min" value="{$field.active.min|escape:'html':'UTF-8'}" /></label>
                                                <label for="{$index|escape:'html':'UTF-8'}_max"><input type="text" placeholder="{l s='Max' mod='ets_marketplace'}" name="{$index|escape:'html':'UTF-8'}_max" value="{$field.active.max|escape:'html':'UTF-8'}" /></label>
                                            {/if}
                                            {if $field.type=='date'}
                                                <fieldset class="form-group"> 
                                                    <div class="input-group ets_mp_datepicker">
                                                        <input id="{$index|escape:'html':'UTF-8'}_min" autocomplete="off" class="form-control" name="{$index|escape:'html':'UTF-8'}_min" placeholder="{l s='From' mod='ets_marketplace'}" value="{$field.active.min|escape:'html':'UTF-8'}" type="text" autocomplete="off" />
                                                        <div class="input-group-append">
                                                            <div class="input-group-text">
                                                                <i class="icon icon-date"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                                <fieldset class="form-group"> 
                                                    <div class="input-group ets_mp_datepicker">
                                                        <input id="{$index|escape:'html':'UTF-8'}_max" autocomplete="off" class="form-control" name="{$index|escape:'html':'UTF-8'}_max" placeholder="{l s='To' mod='ets_marketplace'}" value="{$field.active.max|escape:'html':'UTF-8'}" type="text" autocomplete="off" />
                                                        <div class="input-group-append">
                                                            <div class="input-group-text">
                                                                <i class="icon icon-date"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </fieldset>
                                            {/if}
                                        {elseif ( ($name == 'mp_front_products' || $name == 'mp_products') && $field.type == 'text' && isset($index) && $index == 'input_box') }
                                            <div class="md-checkbox">
                                                <label>
                                                  <input id="bulk_action_select_all" onclick="$('table').find('td input:checkbox').prop('checked', $(this).prop('checked')); ets_mp_updateBulkMenu();" value="" type="checkbox">
                                                  <i class="md-checkbox-control"></i>
                                                </label>
                                            </div>
                                        {else}
                                           {l s=' -- ' mod='ets_marketplace'}
                                        {/if}
                                    </th>
                                {/foreach}
                                {if $show_action}
                                    <th class="actions">
                                        <span class="pull-right flex">
                                            <input type="hidden" name="post_filter" value="yes" />
                                            {if $show_reset}<a  class="btn btn-warning"  href="{$currentIndex|escape:'html':'UTF-8'}"><i class="icon-eraser"></i> {l s='Reset' mod='ets_marketplace'}</a> &nbsp;{/if}
                                            <button class="btn btn-default" name="ets_mp_submit_{$name|escape:'html':'UTF-8'}" id="ets_mp_submit_{$name|escape:'html':'UTF-8'}" type="submit">
            									<i class="icon-search"></i> {l s='Filter' mod='ets_marketplace'}
            								</button>
                                        </span>
                                    </th>
                                {/if}
                            </tr>
                        {/if}
                    </thead>
                    <tbody id="list-{$name|escape:'html':'UTF-8'}">
                        {if $field_values}
                        {foreach from=$field_values item='row'}
                            <tr {if isset($row.read) && !$row.read}class="no-read"{/if} data-id="{$row.$identifier|intval}">
                                {assign var='i' value=1}
                                {foreach from=$fields_list item='field' key='key'}
                                    {if $key eq 'stock_quantity'}{continue}{/if}
                                    <td class="{$key|escape:'html':'UTF-8'} {if isset($sort)&& $sort==$key && isset($sort_type) && $sort_type=='asc' && isset($field.update_position) && $field.update_position}pointer dragHandle center{/if}{if isset($field.class)} {$field.class|escape:'html':'UTF-8'}{/if}" {if $show_action && !$actions && count($fields_list)==$i}colspan="2"{/if} >
                                        {if isset($field.rating_field) && $field.rating_field}
                                            {if isset($row.$key) && $row.$key > 0}
                                                {for $i=1 to (int)$row.$key}
                                                    <div class="star star_on"></div>
                                                {/for}
                                                {if (int)$row.$key < 5}
                                                    {for $i=(int)$row.$key+1 to 5}
                                                        <div class="star"></div>
                                                    {/for}
                                                {/if}
                                            {else}
                                            
                                                {l s=' -- ' mod='ets_marketplace'}
                                            {/if}
                                        {elseif $field.type != 'active'}
                                            {if $field.type=='date'}
                                                {if !$row.$key}
                                                --
                                                {else}
                                                    {if $key!='date_from' && $key!='date_to'}
                                                        {dateFormat date=$row.$key full=1}
                                                    {else}
                                                        {dateFormat date=$row.$key full=0}
                                                    {/if}
                                                {/if}
                                            {elseif $field.type=='checkbox'}
                                                <input type="checkbox" name="{$name|escape:'html':'UTF-8'}_boxs[]" value="{$row.$identifier|escape:'html':'UTF-8'}" class="{$name|escape:'html':'UTF-8'}_boxs" />
                                            {elseif $field.type=='input_number'}
                                                {assign var='field_input' value=$field.field}
                                                <div class="qty edit_quantity" data-v-599c0dc5="">
                                                    <div class="ps-number edit-qty hover-buttons" data-{$identifier|escape:'html':'UTF-8'}="{$row.$identifier|escape:'html':'UTF-8'}">
                                                        <input class="form-control {$name|escape:'html':'UTF-8'}_{$field_input|escape:'html':'UTF-8'}" type="number" name="{$name|escape:'html':'UTF-8'}_{$field_input|escape:'html':'UTF-8'}[{$row.$identifier|escape:'html':'UTF-8'}]" value="" placeholder="0" />
                                                        <div class="ps-number-spinner d-flex">
                                                            <span class="ps-number-up"></span>
                                                            <span class="ps-number-down"></span>
                                                        </div>
                                                    </div>
                                                    <button class="check-button" disabled="disabled"><i class="fa fa-check icon-check"></i></button>
                                                </div>
                                            {else}
                                                {if isset($field.update_position) && $field.update_position}
                                                    <div class="dragGroup">
                                                    <span class="positions">
                                                {/if}
                                                {if isset($row.$key) && $row.$key!=='' && !is_array($row.$key)}{if isset($field.strip_tag) && !$field.strip_tag}{$row.$key nofilter}{else}{$row.$key|strip_tags:'UTF-8'|truncate:120:'...'|escape:'html':'UTF-8'}{/if}{else}--{/if}
                                                {if isset($row.$key) && is_array($row.$key) && isset($row.$key.image_field) && $row.$key.image_field}
                                                    <a class="ets_mp_fancy" href="{$row.$key.img_url|escape:'html':'UTF-8'}"><img style="{if isset($row.$key.height) && $row.$key.height}max-height: {$row.$key.height|intval}px;{/if}{if isset($row.$key.width) && $row.$key.width}max-width: {$row.$key.width|intval}px;{/if}" src="{$row.$key.img_url|escape:'html':'UTF-8'}" /></a>
                                                {/if} 
                                                {if isset($field.update_position) && $field.update_position}
                                                    </div>
                                                    </span>
                                                {/if}  
                                            {/if}                                     
                                        {else}
                                            {if isset($row.$key) && $row.$key}
                                                {if $row.$key==-1}
                                                    {if (!isset($row.action_edit) || $row.action_edit) && ($name!='mp_front_products' || (isset($row.approved) && $row.approved))}
                                                        <a name="{$name|escape:'html':'UTF-8'}" href="{$currentIndex|escape:'html':'UTF-8'}&{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}&change_enabled=1&field={$key|escape:'html':'UTF-8'}" class="list-action field-{$key|escape:'html':'UTF-8'} list-action-enable action-disabled  list-item-{$row.$identifier|escape:'html':'UTF-8'}" data-id="{$row.$identifier|escape:'html':'UTF-8'}" title="{if $key=='reported'}{l s='Click to mark as reported' mod='ets_marketplace'}{else}{l s='Click to Enable' mod='ets_marketplace'}{/if}"><i class="icon-clock-o fa fa-clock-o"></i></a>
                                                    {else}
                                                        <span class="list-action-enable action-disabled" title="{l s='Pending' mod='ets_marketplace'}">
                                                            <i class="icon-clock-o fa fa-clock-o"></i>
                                                        </span>
                                                    {/if}
                                                {else}
                                                    {if (!isset($row.action_edit) || $row.action_edit) && ($name!='mp_front_products' || (isset($row.approved) && $row.approved))}
                                                    <a name="{$name|escape:'html':'UTF-8'}"  href="{$currentIndex|escape:'html':'UTF-8'}&{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}&change_enabled=0&field={$key|escape:'html':'UTF-8'}" class="list-action field-{$key|escape:'html':'UTF-8'} list-action-enable action-enabled list-item-{$row.$identifier|escape:'html':'UTF-8'}" data-id="{$row.$identifier|escape:'html':'UTF-8'}" title="{if $key=='reported'}{l s='Click to unreport' mod='ets_marketplace'}{else}{l s='Click to Disable' mod='ets_marketplace'}{/if}">
                                                        <i class="fa fa-check"></i>
                                                    </a>
                                                    {else}
                                                        <span class="list-action-enable action-enabled" title="{l s='Enabled' mod='ets_marketplace'}">
                                                            <i class="fa fa-check"></i>
                                                        </span>
                                                    {/if}
                                                {/if}
                                            {else}
                                                {if (!isset($row.action_edit) || $row.action_edit) && ($name!='mp_front_products' || (isset($row.approved) && $row.approved))}
                                                    <a name="{$name|escape:'html':'UTF-8'}" href="{$currentIndex|escape:'html':'UTF-8'}&{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}&change_enabled=1&field={$key|escape:'html':'UTF-8'}" class="list-action field-{$key|escape:'html':'UTF-8'} list-action-enable action-disabled  list-item-{$row.$identifier|escape:'html':'UTF-8'}" data-id="{$row.$identifier|escape:'html':'UTF-8'}" title="{if $key=='reported'}{l s='Click to mark as reported' mod='ets_marketplace'}{else}{l s='Click to Enable' mod='ets_marketplace'}{/if}">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                {else}
                                                    <span class="list-action-enable action-disabled" title="{l s='Disabled' mod='ets_marketplace'}">
                                                        <i class="fa fa-remove"></i>
                                                    </span>
                                                {/if}
                                            {/if} 
                                        {/if}
                                    </td>
                                    {assign var='i' value=$i+1}
                                {/foreach}
                                {if $show_action}
                                    {if $actions}  
                                        <td class="text-right">                            
                                            <div class="btn-group-action">
                                                <div class="btn-group pull-right">
                                                        {if $actions[0]=='view'}
                                                            {if isset($row.child_view_url) && $row.child_view_url}
                                                                <a class="btn btn-default link_view" href="{$row.child_view_url|escape:'html':'UTF-8'}" {if isset($view_new_tab) && $view_new_tab} target="_blank" {/if}><i class="icon-search-plus fa fa-search-plus"></i> {l s='View' mod='ets_marketplace'}</a>
                                                            {elseif !isset($row.action_edit) || $row.action_edit}
                                                                <a class="btn btn-default link_edit" href="{$currentIndex|escape:'html':'UTF-8'}&edit{$name|escape:'html':'UTF-8'}=1&{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}" ><i class="icon-pencil fa fa-pencil"></i> {l s='Edit' mod='ets_marketplace'}</a>
                                                            {/if}
                                                        {/if}
                                                        {if $actions[0]=='delete'}
                                                            <a class="btn btn-default" onclick="return confirm('{l s='Do you want to delete this item?' mod='ets_marketplace' js=1}');" href="{$currentIndex|escape:'html':'UTF-8'}&{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}&del=yes"><i class="icon-trash fa fa-trash"></i> {l s='Delete' mod='ets_marketplace'}</a>
                                                        {/if}
                                                        {if $actions[0]=='reply'}
                                                            <a class="btn btn-default link_edit" href="{$currentIndex|escape:'html':'UTF-8'}&{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}&reply=yes"><i class="icon-reply fa fa-reply"></i> {l s='Reply' mod='ets_marketplace'}</a>
                                                        {/if}
                                                        {if $actions[0]=='dowloadpdf'}
                                                            <a class="ets_mp_downloadpdf" href="{$currentIndex|escape:'html':'UTF-8'}&{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}&dowloadpdf=yes">
                                                                <i class="icon-pdf icon icon-pdf fa fa-file-pdf-o"></i>
                                                                {l s='Download pdf' mod='ets_marketplace'}
                                                            </a>
                                                        {/if}
                                                        {if $name=='ms_commissions' && isset($row.status_val)}
                                                            {if isset($row.type) && $row.type=='usage'}
                                                                {if $row.status_val==1}
                                                                    <a onclick="return confirm('{l s='Do you want to refund this commission?' mod='ets_marketplace' js=1}');" class="btn btn-default" href="{$currentIndex|escape:'html':'UTF-8'}&return{$name|escape:'html':'UTF-8'}=1&{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}"><i class="fa fa-undo icon-undo"></i> {l s='Refund' mod='ets_marketplace'}</a>
                                                                {else}
                                                                    <a onclick="return confirm('{l s='Do you want to deduct this commission?' mod='ets_marketplace' js=1}');" class="btn btn-default" href="{$currentIndex|escape:'html':'UTF-8'}&deduct{$name|escape:'html':'UTF-8'}=1&{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}"><i class="fa fa-minus icon-minus"></i> {l s='Deduct' mod='ets_marketplace'}</a>
                                                                {/if}
                                                            {else}
                                                                {if $row.status_val==-1 || $row.status_val==0}
                                                                    <a onclick="return confirm('{l s='Do you want to approve commission?' mod='ets_marketplace' js=1}');" class="btn btn-default" href="{$currentIndex|escape:'html':'UTF-8'}&approve{$name|escape:'html':'UTF-8'}=1&{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}"><i class="fa fa-check icon-check"></i> {l s='Approve' mod='ets_marketplace'}</a>
                                                                {else}
                                                                    <a onclick="return confirm('{l s='Do you want to cancel commission?' mod='ets_marketplace' js=1}');" class="btn btn-default" href="{$currentIndex|escape:'html':'UTF-8'}&cancel{$name|escape:'html':'UTF-8'}=1&{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}"><i class="fa fa-times icon-times"></i> {l s='Cancel' mod='ets_marketplace'}</a>
                                                                {/if}
                                                            {/if}
                                                        {/if}
                                                        {if $name=='ms_commissions_usage' && isset($row.status_val)}
                                                            {if $row.status_val==1}
                                                                <a onclick="return confirm('{l s='Do you want to refund this commission?' mod='ets_marketplace' js=1}');" class="btn btn-default" href="{$currentIndex|escape:'html':'UTF-8'}&return{$name|escape:'html':'UTF-8'}=1&{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}"><i class="fa fa-undo icon-undo"></i> {l s='Refund' mod='ets_marketplace'}</a>
                                                            {else}
                                                                <a onclick="return confirm('{l s='Do you want to deduct this commission?' mod='ets_marketplace' js=1}');" class="btn btn-default" href="{$currentIndex|escape:'html':'UTF-8'}&deduct{$name|escape:'html':'UTF-8'}=1&{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}"><i class="fa fa-minus icon-minus"></i> {l s='Deduct' mod='ets_marketplace'}</a>
                                                            {/if}
                                                        {/if}
                                                        {if $name=='ms_billings' && isset($row.status)}
                                                            {if $row.status==0 || $row.status==-1}
                                                                <a onclick="return confirm('{l s='Do you want to paid this invoice? Please make sure this seller already sent the fee to you' mod='ets_marketplace' js=1}');" class="btn btn-default" href="{$currentIndex|escape:'html':'UTF-8'}&purchase{$name|escape:'html':'UTF-8'}=1&{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}"><i class="fa fa-check icon-check"></i> {l s='Set as paid' mod='ets_marketplace'}</a>
                                                            {else}
                                                                <a onclick="return confirm('{l s='Do you want to cancel this invoice?' mod='ets_marketplace' js=1}');" class="btn btn-default" href="{$currentIndex|escape:'html':'UTF-8'}&cancel{$name|escape:'html':'UTF-8'}=1&{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}"><i class="fa fa-times icon-times"></i> {l s='Cancel' mod='ets_marketplace'}</a>
                                                            {/if}
                                                        {/if}
                                                        {if $name=='ets_registration'}
                                                            <a class="btn btn-default" href="{$row.child_view_url|escape:'html':'UTF-8'}"><i class="icon-search-plus fa fa-search-plus"></i> {l s='View' mod='ets_marketplace'}</a>
                                                        {/if}
                                                        {if $actions|count >=2 && (!isset($row.action_edit) || $row.action_edit || in_array('action',$actions) || (isset($row.action_delete) &&$row.action_delete) )}
                                                            <button data-toggle="dropdown" class="btn btn-default dropdown-toggle">
                                        						<i class="icon-caret-down"></i>&nbsp;
                                        					</button>
                                                            <ul class="dropdown-menu">
                                                                {if $name=='ets_withdraw' && isset($row.change_status) && $row.change_status}
                                                                    <li><a onclick="return confirm('{l s='Do you want to approve this withdrawal?' mod='ets_marketplace' js=1}');" class="btn btn-default" href="{$currentIndex|escape:'html':'UTF-8'}&approve{$name|escape:'html':'UTF-8'}=1&{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}"><i class="fa fa-check icon-check"></i> {l s='Approve' mod='ets_marketplace'}</a></li>
                                                                    <li><a onclick="return confirm('{l s='Do you want to decline with return commission this withdrawal?' mod='ets_marketplace' js=1}');" class="btn btn-default" href="{$currentIndex|escape:'html':'UTF-8'}&return{$name|escape:'html':'UTF-8'}=1&{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}"><i class="fa fa-undo icon-undo"></i> {l s='Decline - Return commission' mod='ets_marketplace'}</a></li>
                                                                    <li><a onclick="return confirm('{l s='Do you want to decline with deduct commission this withdrawal?' mod='ets_marketplace' js=1}');" class="btn btn-default" href="{$currentIndex|escape:'html':'UTF-8'}&deduct{$name|escape:'html':'UTF-8'}=1&{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}"><i class="fa fa-close icon-close"></i> {l s='Decline - Deduct commission' mod='ets_marketplace'}</a></li>
                                                                {/if}
                                                                {if $name=='ms_commissions' && isset($row.status_val)}
                                                                    {if $row.status_val==-1}
                                                                       <li><a onclick="return confirm('{l s='Do you want to cancel this commission?' mod='ets_marketplace' js=1}');" class="btn btn-default" href="{$currentIndex|escape:'html':'UTF-8'}&cancel{$name|escape:'html':'UTF-8'}=1&{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}"><i class="fa fa-times icon-times"></i> {l s='Cancel' mod='ets_marketplace'}</a></li>
                                                                    {/if}
                                                                {/if}
                                                                {if $name=='ms_billings' && isset($row.status)}
                                                                    {if $row.status==0}
                                                                        <a onclick="return confirm('{l s='Do you want to cancel this billing?' mod='ets_marketplace' js=1}');" class="btn btn-default" href="{$currentIndex|escape:'html':'UTF-8'}&cancel{$name|escape:'html':'UTF-8'}=1&{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}"><i class="fa fa-times icon-times"></i> {l s='Cancel' mod='ets_marketplace'}</a>
                                                                    {/if}
                                                                {/if}
                                                                {if $name=='ets_registration'}
                                                                    <li>
                                                                        <span class="btn btn-default action_approve_registration" data-id="{$row.$identifier|intval}" {if $row.status==1} style="display:none;"{/if}>
                                                                            <i class="fa fa-check icon-check"></i> {l s='Approve' mod='ets_marketplace'}
                                                                        </span>
                                                                        <div class="approve_registration_form" style="display:none">
                                                                            <div class="ets_mp_close_popup" title="Close">{l s='Close' mod='ets_marketplace'}</div>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-lg-3">{l s='Status' mod='ets_marketplace'}</label>
                                                                                <div class="col-lg-9">
                                                                                    <span class="ets_mp_status approved">{l s='Approve' mod='ets_marketplace'}</span>
                                                                                </div>
                                                                            </div>
                                                                            {*
                                                                            <div class="form-group">
                                                                                <label class="control-label col-lg-3">{l s='Comment' mod='ets_marketplace'}</label>
                                                                                <div class="col-lg-9">
                                                                                    <textarea name="comment"></textarea>
                                                                                </div>
                                                                            </div>
                                                                            *}
                                                                            <input name="active_registration" value="1" type="hidden" />
                                                                            <input name="saveStatusRegistration" value="1" type="hidden" />
                                                                            <input name="id_registration" value="{$row.$identifier|intval}" type="hidden" />
                                                                            <div class="panel_footer form-group">
                                                                                <div class="control-label col-lg-3"></div>
                                                                                <div class="col-lg-9">
                                                                                    <button type="submit" value="1" name="saveStatusRegistration" class="btn btn-default saveStatusRegistration">
                                                                                        <i class="icon-save"></i> {l s='Save' mod='ets_marketplace'}
                                                                                    </button>
                                                                                </div>
                                                                            </div>           
                                                                        </div>
                                                                    </li>
                                                                    <li>
                                                                        <span class="btn btn-default approve_registration action_decline_registration" data-id="{$row.$identifier|intval}" {if ($row.status==1 && $row.has_seller) || $row.status==0} style="display:none;"{/if}>
                                                                            <i class="fa fa-times icon-close"></i> {l s='Decline' mod='ets_marketplace'}
                                                                        </span>
                                                                        <div class="approve_registration_form" style="display:none">
                                                                            <div class="ets_mp_close_popup" title="Close">{l s='Close' mod='ets_marketplace'}</div>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-lg-3">{l s='Status' mod='ets_marketplace'}</label>
                                                                                <div class="col-lg-9">
                                                                                    <span class="ets_mp_status declined">{l s='Decline' mod='ets_marketplace'}</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-lg-3">{l s='Reason' mod='ets_marketplace'}</label>
                                                                                <div class="col-lg-9">
                                                                                    <textarea name="reason"></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <input name="active_registration" value="0" type="hidden" />
                                                                            <input name="saveStatusRegistration" value="1" type="hidden" />
                                                                            <input name="id_registration" value="{$row.$identifier|intval}" type="hidden" />
                                                                            <div class="panel_footer form-group">
                                                                                <div class="control-label col-lg-3"></div>
                                                                                <div class="col-lg-9">
                                                                                    <button type="submit" value="1" name="saveStatusRegistration" class="btn btn-default saveStatusRegistration">
                                                                                        <i class="icon-save"></i> {l s='Save' mod='ets_marketplace'}
                                                                                    </button>
                                                                                </div>
                                                                            </div>           
                                                                        </div>
                                                                    </li>
                                                                    <li><a onclick="return confirm('{l s='Do you want to delete this item?' mod='ets_marketplace'}');" href="{$currentIndex|escape:'html':'UTF-8'}&{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}&del=yes"><i class="fa fa-trash icon-trash"></i> {l s='Delete' mod='ets_marketplace'}</a></li>
                                                                {/if}
                                                                {if $name=='ets_seller'}
                                                                    <li {if $row.status_val==1}style="display:none;"{/if}>
                                                                        <span class="btn btn-default approve_registration action_approve_seller" data-id="{$row.$identifier|intval}">
                                                                            <i class="fa fa-check icon-check"></i> {l s='Activate' mod='ets_marketplace'}
                                                                        </span>
                                                                        <div class="approve_registration_form" style="display:none">
                                                                            <div class="ets_mp_close_popup" title="Close">{l s='Close' mod='ets_marketplace'}</div>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-lg-3">{l s='Status' mod='ets_marketplace'}</label>
                                                                                <div class="col-lg-9">
                                                                                    <span class="ets_mp_status approved">{l s='Active' mod='ets_marketplace'}</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-lg-3">{l s='Available from' mod='ets_marketplace'}</label>
                                                                                <div class="col-lg-9">
                                                                                    <div class="row">
                                                                                        <div class="input-group col-lg-8 ets_mp_datepicker">
                                                                                            <input name="date_from" value="{$row.date_from|escape:'html':'UTF-8'}" class="" type="text" />
                                                                                            <span class="input-group-addon">
                                                                                                <i class="icon-calendar-empty"></i>
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-lg-3">{l s='Available to' mod='ets_marketplace'}</label>
                                                                                <div class="col-lg-9">
                                                                                    <div class="row">
                                                                                        <div class="input-group col-lg-8 ets_mp_datepicker">
                                                                                            <input name="date_to" value="{$row.date_to|escape:'html':'UTF-8'}" class="" type="text" />
                                                                                            <span class="input-group-addon">
                                                                                                <i class="icon-calendar-empty"></i>
                                                                                            </span>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <input name="active_seller" value="1" type="hidden" />
                                                                            <input name="saveStatusSeller" value="1" type="hidden" />
                                                                            <input name="seller_id" value="{$row.$identifier|intval}" type="hidden" />
                                                                            <div class="panel_footer form-group">
                                                                                <div class="control-label col-lg-3"></div>
                                                                                <div class="col-lg-9">
                                                                                    <button type="submit" value="1" name="saveStatusSeller" class="btn btn-default saveStatusSeller">
                                                                                        <i class="icon-save"></i> {l s='Save' mod='ets_marketplace'}
                                                                                    </button>
                                                                                </div>
                                                                            </div>           
                                                                        </div>
                                                                    </li>
                                                                    <li {if $row.status_val!=-1} style="display:none;"{/if}>
                                                                        <span class="btn btn-default approve_registration action_decline_seller" data-id="{$row.$identifier|intval}">
                                                                            <i class="icon icon-close"></i> {l s='Decline payment' mod='ets_marketplace'}
                                                                        </span>
                                                                        <div class="approve_registration_form" style="display:none">
                                                                            <div class="ets_mp_close_popup" title="Close">{l s='Close' mod='ets_marketplace'}</div>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-lg-3">{l s='Status' mod='ets_marketplace'}</label>
                                                                                <div class="col-lg-9">
                                                                                    <span class="ets_mp_status declined">{l s='Decline payment' mod='ets_marketplace'}</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-lg-3">{l s='Reason' mod='ets_marketplace'}</label>
                                                                                <div class="col-lg-9">
                                                                                    <textarea name="reason">{$row.reason|escape:'html':'UTF-8'}</textarea>
                                                                                </div>
                                                                            </div>
                                                                            <input name="active_seller" value="-3" type="hidden" />
                                                                            <input name="saveStatusSeller" value="1" type="hidden" />
                                                                            <input name="seller_id" value="{$row.$identifier|intval}" type="hidden" />
                                                                            <div class="panel_footer form-group">
                                                                                <div class="control-label col-lg-3"></div>
                                                                                <div class="col-lg-9">
                                                                                    <button type="submit" value="1" name="saveStatusSeller" class="btn btn-default saveStatusSeller">
                                                                                        <i class="icon-save"></i> {l s='Save' mod='ets_marketplace'}
                                                                                    </button>
                                                                                </div>
                                                                            </div>           
                                                                        </div>
                                                                    </li>
                                                                    <li {if $row.status_val==0} style="display:none;"{/if}>
                                                                        <span class="btn btn-default approve_registration action_disable_seller" data-id="{$row.$identifier|intval}">
                                                                            <i class="icon icon-ban"></i> {l s='Disable' mod='ets_marketplace'}
                                                                        </span>
                                                                        <div class="approve_registration_form" style="display:none">
                                                                            <div class="ets_mp_close_popup" title="Close">{l s='Close' mod='ets_marketplace'}</div>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-lg-3">{l s='Status' mod='ets_marketplace'}</label>
                                                                                <div class="col-lg-9">
                                                                                    <span class="ets_mp_status disabled">{l s='Disable' mod='ets_marketplace'}</span>
                                                                                </div>
                                                                            </div>
                                                                            <div class="form-group">
                                                                                <label class="control-label col-lg-3">{l s='Reason' mod='ets_marketplace'}</label>
                                                                                <div class="col-lg-9">
                                                                                    <textarea name="reason">{$row.reason|escape:'html':'UTF-8'}</textarea>
                                                                                </div>
                                                                            </div>
                                                                            <input name="active_seller" value="0" type="hidden" />
                                                                            <input name="saveStatusSeller" value="1" type="hidden" />
                                                                            <input name="seller_id" value="{$row.$identifier|intval}" type="hidden" />
                                                                            <div class="panel_footer form-group">
                                                                                <div class="control-label col-lg-3"></div>
                                                                                <div class="col-lg-9">
                                                                                    <button type="submit" value="1" name="saveStatusSeller" class="btn btn-default saveStatusSeller">
                                                                                        <i class="icon-save"></i> {l s='Save' mod='ets_marketplace'}
                                                                                    </button>
                                                                                </div>
                                                                            </div>           
                                                                        </div>
                                                                    </li>
                                                                {/if}
                                                                {foreach from=$actions item='action' key='key'}
                                                                    {if $key!=0}
                                                                        {if $action=='delete' && (!isset($row.view_order_url) || (isset($row.view_order_url) && !$row.view_order_url) )}
                                                                            <li><a class="btn btn-default" onclick="return confirm('{if $name=='mp_front_products' || $name=='mp_products'}{l s='Do you want to delete this product?' mod='ets_marketplace'}{else}{l s='Do you want to delete this item?' mod='ets_marketplace'}{/if}');" href="{$currentIndex|escape:'html':'UTF-8'}&{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}&del=yes{if isset($row.type)}&type={$row.type|escape:'html':'UTF-8'}{/if}"><i class="fa fa-trash icon-trash"></i> {l s='Delete' mod='ets_marketplace'}</a></li>
                                                                        {/if}
                                                                        {if $action=='dowloadpdf'}
                                                                            <li>
                                                                                <a href="{$currentIndex|escape:'html':'UTF-8'}&{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}&dowloadpdf=yes{if isset($row.type)}&type={$row.type|escape:'html':'UTF-8'}{/if}">
                                                                                    <i class="fa fa-pdf icon icon-pdf"></i> {l s='Download pdf' mod='ets_marketplace'}
                                                                                </a>
                                                                            </li>
                                                                        {/if}
                                                                        {if $action=='view'}
                                                                            {if isset($row.child_view_url) && $row.child_view_url}
                                                                                <li><a class="btn btn-default" href="{$row.child_view_url|escape:'html':'UTF-8'}"><i class="fa fa-search-plus icon-search-plus"></i> {l s='View' mod='ets_marketplace'}</a></li>
                                                                            {else}
                                                                                <li><a class="btn btn-default" href="{$currentIndex|escape:'html':'UTF-8'}&{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}"><i class="fa fa-pencil icon-pencil"></i> {l s='Edit' mod='ets_marketplace'}</a></li>
                                                                            {/if}
                                                                        {/if}
                                                                        {if $action =='edit'}
                                                                            <li><a class="btn btn-default" href="{$currentIndex|escape:'html':'UTF-8'}&edit{$name|escape:'html':'UTF-8'}=1&{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}"><i class="fa fa-pencil icon-pencil"></i> {l s='Edit' mod='ets_marketplace'}</a></li>
                                                                        {/if}
                                                                        {if $action =='duplicate'}
                                                                            <li><a class="btn btn-default" href="{$currentIndex|escape:'html':'UTF-8'}&duplicate{$name|escape:'html':'UTF-8'}=1&{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}"><i class="fa fa-copy icon-copy"></i> {l s='Duplicate' mod='ets_marketplace'}</a></li>
                                                                        {/if}
                                                                        {if $action =='approve_review' && isset($row.action_approve) && $row.action_approve}
                                                                            <li><a class="btn btn-default" onclick="return confirm('{l s='Do you want to approve this item?' mod='ets_marketplace'}');" href="{$currentIndex|escape:'html':'UTF-8'}&{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}&approve=yes{if isset($row.type)}&type={$row.type|escape:'html':'UTF-8'}{/if}"><i class="fa fa-check icon-check"></i> {l s='Approve' mod='ets_marketplace'}</a></li>
                                                                        {/if}
                                                                        {if $action=='action'}
                                                                            <li>
                                                                                <a class="btn btn-default action-edit-inline" href="{$currentIndex|escape:'html':'UTF-8'}&action{$name|escape:'html':'UTF-8'}=1&{$identifier|escape:'html':'UTF-8'}={$row.$identifier|escape:'html':'UTF-8'}"><i class="fa fa-pencil icon-pencil"></i> {l s='Action' mod='ets_marketplace'}</a>
                                                                            </li>
                                                                        {/if}
                                                                        {if $action=='vieworder' && $row.view_order_url}
                                                                            <li>
                                                                                <a class="btn btn-default" href="{$row.view_order_url|escape:'html':'UTF-8'}"><i class="icon-search fa fa-search"></i> {l s='View order' mod='ets_marketplace'}</a>
                                                                            </li>
                                                                        {/if}
                                                                    {/if}
                                                                {/foreach}
                                                            </ul>
                                                        {/if}
                                                </div>
                                            </div>
                                        </td>
                                    {/if}
                                {/if}
                            </tr>
                        {/foreach}  
                        {/if}  
                        {if !$field_values}
                           <tr class="no-record not_items_found"> <td colspan="100%"><p>{l s='No items found' mod='ets_marketplace'}</p></td></tr> 
                        {/if}                
                    </tbody>
                </table>
                {if isset($has_delete_product) &&  ((isset($is_admin) && $is_admin) || (Configuration::get('ETS_MP_ALLOW_SELLER_CREATE_PRODUCT') || (isset($has_edit_product) && $has_edit_product) || $has_delete_product))}
                  <div id="catalog-actions" class="col order-first">
                    <div class="row">
                        <div class="col">
                            <div class="d-inline-block hide" bulkurl="{if isset($is_admin) && $is_admin}{$link->getAdminLink('AdminMarketPlaceProducts')|escape:'html':'UTF-8'}&bulk_action=activate_all{else}{$link->getModuleLink('ets_marketplace','products',['bulk_action'=>'activate_all'])|escape:'html':'UTF-8'}{/if}" redirecturl="{if isset($is_admin) && $is_admin}{$link->getAdminLink('AdminMarketPlaceProducts')|escape:'html':'UTF-8'}{else}{$link->getModuleLink('ets_marketplace','products',['list'=>1])|escape:'html':'UTF-8'}{/if}">
                                <div class="btn-group dropdown bulk-catalog">
                                    <button id="product_bulk_menu" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="true" disabled="" style="color:black;">
                                        {l s='Bulk actions' mod='ets_marketplace'}
                                        <i class="icon-caret-up"></i>
                                    </button>
                                    <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 35px, 0px); top: 0px; left: 0px; will-change: transform;">
                                        {*if (isset($is_admin) && $is_admin) || (isset($has_edit_product) && $has_edit_product)}
                                            <a class="dropdown-item" href="#" onclick="ets_mp_bulkProductAction(this, 'activate_all');">
                                                <i class="fa fa-dot-circle-o" aria-hidden="true"></i>
                                                {l s='Activate selection' mod='ets_marketplace'}
                                            </a>
                                            <a class="dropdown-item" href="#" onclick="ets_mp_bulkProductAction(this, 'deactivate_all');">
                                                <i class="fa fa-circle-o" aria-hidden="true"></i>
                                                {l s='Deactivate selection' mod='ets_marketplace'}
                                            </a>
                                        {/if*}
                                        {* _ARM_ Prevent duplicate products
                                        if (isset($is_admin) && $is_admin) || Configuration::get('ETS_MP_ALLOW_SELLER_CREATE_PRODUCT')}
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#" onclick="ets_mp_bulkProductAction(this, 'duplicate_all');">
                                                <i class="fa fa-clone" aria-hidden="true"></i>
                                                {l s='Duplicate selection' mod='ets_marketplace'}
                                            </a>
                                        {/if*}
                                        {if isset($has_delete_product) && $has_delete_product}
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item" href="#" onclick="ets_mp_bulkProductAction(this, 'delete_all');">
                                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                {l s='Delete selection' mod='ets_marketplace'}
                                            </a>
                                        {/if}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {/if}
                {if $paggination}
                    <div class="ets_mp_paggination" style="margin-top: 10px;">
                        {$paggination nofilter}
                    </div>
                {/if}
            </form>
        </div>
    {/if}
    {if isset($link_back_to_list)}
        <div class="panel-footer">
            <a id="desc-attribute-back" class="btn btn-default btn-primary" href="{$link_back_to_list|escape:'html':'UTF-8'}">
        		<i class="process-icon-back "></i> <span>{l s='Back to list' mod='ets_marketplace'}</span>
        	</a>
        </div>
    {/if}
</div>


