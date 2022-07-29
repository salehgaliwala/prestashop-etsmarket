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
{extends file="helpers/form/form.tpl"}
{block name="input"}
{if $input.type == 'checkbox'}
    {if isset($input.values.query) && $input.values.query}
        {if $input.name=='ETS_MP_REGISTRATION_FIELDS'}
            {assign var=checkall value=true}
    		{foreach $input.values.query as $value}
    			{if !(isset($fields_value[$input.name]) && is_array($fields_value[$input.name]) && $fields_value[$input.name] && in_array($value[$input.values.id],$fields_value[$input.name]))} 
                    {assign var=checkall value=false}
                {/if}
    		{/foreach}
            {assign var=checkall_validate value=true}
    		{foreach $input.values.query as $value}
    			{if $value[$input.values.id]!='seller_name' && $value[$input.values.id]!='seller_email' && !(isset($fields_value['ETS_MP_REGISTRATION_FIELDS_VALIDATE']) && is_array($fields_value['ETS_MP_REGISTRATION_FIELDS_VALIDATE']) && $fields_value['ETS_MP_REGISTRATION_FIELDS_VALIDATE'] && in_array($value[$input.values.id],$fields_value['ETS_MP_REGISTRATION_FIELDS_VALIDATE']))} 
                    {assign var=checkall_validate value=false}
                {/if}
    		{/foreach}
            <table class="table table_ets_mp_registration_fields">
                <tr>
                    <td>{l s='Fields' mod='ets_marketplace'}</td>
                    <td>{l s='Enable' mod='ets_marketplace'}</td>
                    <td>{l s='Require' mod='ets_marketplace'}</td>
                </tr>
                <tr>
                    <td><b>{l s='All' mod='ets_marketplace'}</b></td>
                    <td>
                        <input value="0" type="checkbox" name="{$input.name|escape:'html':'UTF-8'}[]" id="{$input.name|escape:'html':'UTF-8'}_all" {if $checkall} checked="checked"{/if} />
                    </td>
                    <td>
                        <input value="0" type="checkbox" name="{$input.name|escape:'html':'UTF-8'}_VALIDATE[]" id="{$input.name|escape:'html':'UTF-8'}_VALIDATE_all" {if $checkall_validate} checked="checked"{/if} />
                    </td>
                </tr>
                {foreach $input.values.query as $value}
        			{assign var=id_checkbox value=$input.name|cat:'_'|cat:$value[$input.values.id]|escape:'html':'UTF-8'}
                    <tr>
                        <td>{$value[$input.values.name]|escape:'html':'UTF-8'}</td>
                        <td>
                            <input class="registration_field" type="checkbox" name="{$input.name|escape:'html':'UTF-8'}[]" id="{$id_checkbox|escape:'html':'UTF-8'}" {if isset($value[$input.values.id])} value="{$value[$input.values.id]|escape:'html':'UTF-8'}"{/if}{if isset($fields_value[$input.name]) && is_array($fields_value[$input.name]) && $fields_value[$input.name] && in_array($value[$input.values.id],$fields_value[$input.name])} checked="checked"{/if} />
                        </td>
                        <td>
                            {if $value[$input.values.id]!='seller_name' && $value[$input.values.id]!='seller_email'}
                                <input class="registration_field_validate"  type="checkbox" name="{$input.name|escape:'html':'UTF-8'}_VALIDATE[]" id="{$id_checkbox|escape:'html':'UTF-8'}_validate" {if isset($value[$input.values.id])} value="{$value[$input.values.id]|escape:'html':'UTF-8'}"{/if}{if isset($fields_value['ETS_MP_REGISTRATION_FIELDS_VALIDATE']) && is_array($fields_value['ETS_MP_REGISTRATION_FIELDS_VALIDATE']) && $fields_value['ETS_MP_REGISTRATION_FIELDS_VALIDATE'] && in_array($value[$input.values.id],$fields_value['ETS_MP_REGISTRATION_FIELDS_VALIDATE'])} checked="checked"{/if} />
                            {/if}
                        </td>
                    </tr>
        		{/foreach}
            </table>
        {elseif $input.name=='ETS_MP_CONTACT_FIELDS'}
            {assign var=checkall value=true}
    		{foreach $input.values.query as $value}
    			{if !(isset($fields_value[$input.name]) && is_array($fields_value[$input.name]) && $fields_value[$input.name] && in_array($value[$input.values.id],$fields_value[$input.name]))} 
                    {assign var=checkall value=false}
                {/if}
    		{/foreach}
            {assign var=checkall_validate value=true}
    		{foreach $input.values.query as $value}
    			{if $value[$input.values.id]!='seller_name' && $value[$input.values.id]!='seller_email' && !(isset($fields_value['ETS_MP_CONTACT_FIELDS_VALIDATE']) && is_array($fields_value['ETS_MP_CONTACT_FIELDS_VALIDATE']) && $fields_value['ETS_MP_CONTACT_FIELDS_VALIDATE'] && in_array($value[$input.values.id],$fields_value['ETS_MP_CONTACT_FIELDS_VALIDATE']))} 
                    {assign var=checkall_validate value=false}
                {/if}
    		{/foreach}
            <table class="table table_ets_mp_contact_fields">
                <tr>
                    <td>{l s='Fields' mod='ets_marketplace'}</td>
                    <td>{l s='Enable' mod='ets_marketplace'}</td>
                    <td>{l s='Require' mod='ets_marketplace'}</td>
                </tr>
                <tr>
                    <td><b>{l s='All' mod='ets_marketplace'}</b></td>
                    <td>
                        <input value="0" type="checkbox" name="{$input.name|escape:'html':'UTF-8'}[]" id="{$input.name|escape:'html':'UTF-8'}_all" {if $checkall} checked="checked"{/if} />
                    </td>
                    <td>
                        <input value="0" type="checkbox" name="{$input.name|escape:'html':'UTF-8'}_VALIDATE[]" id="{$input.name|escape:'html':'UTF-8'}_VALIDATE_all" {if $checkall_validate} checked="checked"{/if} />
                    </td>
                </tr>
                {foreach $input.values.query as $value}
        			{assign var=id_checkbox value=$input.name|cat:'_'|cat:$value[$input.values.id]|escape:'html':'UTF-8'}
                    <tr>
                        <td>{$value[$input.values.name]|escape:'html':'UTF-8'}</td>
                        <td>
                            <input type="checkbox" name="{$input.name|escape:'html':'UTF-8'}[]" id="{$id_checkbox|escape:'html':'UTF-8'}" {if isset($value[$input.values.id])} value="{$value[$input.values.id]|escape:'html':'UTF-8'}"{/if}{if isset($fields_value[$input.name]) && is_array($fields_value[$input.name]) && $fields_value[$input.name] && in_array($value[$input.values.id],$fields_value[$input.name])} checked="checked"{/if} {if $value[$input.values.id]=='email' || $value[$input.values.id]=='message' || $value[$input.values.id]=='title'} checked="checked" disabled="disabled"{else} class="contact_field"{/if} />
                        </td>
                        <td>
                            {if $value[$input.values.id]!='product_link'}
                                <input type="checkbox" name="{$input.name|escape:'html':'UTF-8'}_VALIDATE[]" id="{$id_checkbox|escape:'html':'UTF-8'}_validate" {if isset($value[$input.values.id])} value="{$value[$input.values.id]|escape:'html':'UTF-8'}"{/if}{if isset($fields_value['ETS_MP_CONTACT_FIELDS_VALIDATE']) && is_array($fields_value['ETS_MP_CONTACT_FIELDS_VALIDATE']) && $fields_value['ETS_MP_CONTACT_FIELDS_VALIDATE'] && in_array($value[$input.values.id],$fields_value['ETS_MP_CONTACT_FIELDS_VALIDATE'])} checked="checked"{/if} {if $value[$input.values.id]=='email' || $value[$input.values.id]=='message' || $value[$input.values.id]=='title'} checked="checked" disabled="disabled"{else} class="contact_field_validate"{/if} />
                            {/if}
                        </td>
                    </tr>
        		{/foreach}
            </table>
        {else}
            {assign var=id_checkbox value=$input.name|cat:'_'|cat:'all'}
            {assign var=checkall value=true}
    		{foreach $input.values.query as $value}
    			{if !(isset($fields_value[$input.name]) && is_array($fields_value[$input.name]) && $fields_value[$input.name] && in_array($value[$input.values.id],$fields_value[$input.name]))} 
                    {assign var=checkall value=false}
                {/if}
    		{/foreach}
            {if count($input.values.query) >1 && !in_array($input.name,array('ETS_MP_COMMISSION_PENDING_WHEN','ETS_MP_COMMISSION_APPROVED_WHEN','ETS_MP_COMMISSION_CANCELED_WHEN'))}
                <div class="checkbox_all checkbox">
    				{strip}
    					<label for="{$id_checkbox|escape:'html':'UTF-8'}">                                
    						<input value="0" type="checkbox" name="{$input.name|escape:'html':'UTF-8'}[]" id="{$id_checkbox|escape:'html':'UTF-8'}" {if $checkall} checked="checked"{/if} />
    						<i class="md-checkbox-control"></i>
                            {l s='All' mod='ets_marketplace'}
    					</label>
    				{/strip}
    			</div>
            {/if}
            {foreach $input.values.query as $value}
    			{assign var=id_checkbox value=$input.name|cat:'_'|cat:$value[$input.values.id]|escape:'html':'UTF-8'}
    			<div class="checkbox{if isset($input.expand) && strtolower($input.expand.default) == 'show'} hidden{/if}">
    				 {strip}
    					<label for="{$id_checkbox|escape:'html':'UTF-8'}" >                                
    						<input {if $input.name=='ETS_MP_REGISTRATION_FIELDS'} class="ets_mp_extrainput" {/if} type="checkbox" name="{$input.name|escape:'html':'UTF-8'}[]" id="{$id_checkbox|escape:'html':'UTF-8'}" {if isset($value[$input.values.id])} value="{$value[$input.values.id]|escape:'html':'UTF-8'}"{/if}{if isset($fields_value[$input.name]) && is_array($fields_value[$input.name]) && $fields_value[$input.name] && in_array($value[$input.values.id],$fields_value[$input.name])} checked="checked"{/if} />
                            <i class="md-checkbox-control"></i>
                            {$value[$input.values.name]|escape:'html':'UTF-8'}
                            <br />
                            {if $input.name=='ETS_MP_REGISTRATION_FIELDS'}
                                <label {if isset($fields_value[$input.name]) && is_array($fields_value[$input.name]) && $fields_value[$input.name] && in_array($value[$input.values.id],$fields_value[$input.name])} style="display:block"{else} style="display:none" {/if}  for="{$id_checkbox|escape:'html':'UTF-8'}_validate" >                                
            						<input  type="checkbox" name="{$input.name|escape:'html':'UTF-8'}_VALIDATE[]" id="{$id_checkbox|escape:'html':'UTF-8'}_validate" {if isset($value[$input.values.id])} value="{$value[$input.values.id]|escape:'html':'UTF-8'}"{/if}{if isset($fields_value['ETS_MP_REGISTRATION_FIELDS_VALIDATE']) && is_array($fields_value['ETS_MP_REGISTRATION_FIELDS_VALIDATE']) && $fields_value['ETS_MP_REGISTRATION_FIELDS_VALIDATE'] && in_array($value[$input.values.id],$fields_value['ETS_MP_REGISTRATION_FIELDS_VALIDATE'])} checked="checked"{/if} />
                                    <i class="md-checkbox-control"></i>
                                    {l s='Option require' mod='ets_marketplace'}
            					</label>
                            {/if}
    					</label> 
    				{/strip}
    			</div>
    		{/foreach}
        {/if}
    {/if}
{elseif $input.type == 'tre_categories'}
    {$input.tree nofilter}
{elseif $input.type == 'file_lang'}
    {if $languages|count > 1}
      <div class="form-group">
    {/if}
    	{foreach from=$languages item=language}
    		{if $languages|count > 1}
    			<div class="translatable-field lang-{$language.id_lang|intval}" {if $language.id_lang != $defaultFormLanguage}style="display:none"{/if}>
    		{/if}
    			<div class="col-lg-9">
                    {if isset($fields_value[$input.name]) && $fields_value[$input.name] && $fields_value[$input.name][$language.id_lang]}
                        <div class="col-lg-12 uploaded_img_wrapper">
                    		<a  class="ybc_fancy" href="{$image_baseurl|escape:'html':'UTF-8'}{$fields_value[$input.name][$language.id_lang]|escape:'html':'UTF-8'}"><img title="{l s='Click to see full size image' mod='ets_marketplace'}" style="display: inline-block; max-width: 200px;" src="{$image_baseurl|escape:'html':'UTF-8'}{$fields_value[$input.name][$language.id_lang]|escape:'html':'UTF-8'}" /></a>
                            <a onclick="return confirm('{l s='Do you want to delete this banner?' mod='ets_marketplace' js=1}');" class="btn btn-default del_banner" href="{$banner_del_link|escape:'html':'UTF-8'}&id_lang={$language.id_lang|intval}">
                                <i class="icon-trash"></i>
                            </a>
                        </div>
    				{/if}
                    <input id="{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}" type="file" name="{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}" class="hide {$input.name|escape:'html':'UTF-8'}" />
    				<div class="dummyfile input-group">
    					<span class="input-group-addon"><i class="icon-file"></i></span>
    					<input id="{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}-name" type="text" name="{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}" readonly="true" class="{$input.name|escape:'html':'UTF-8'}" />
    					<span class="input-group-btn">
    						<button id="{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}-selectbutton" type="button" name="submitAddAttachments" class="btn btn-default">
    							<i class="icon-folder-open"></i> {l s='Add file' mod='ets_marketplace'}
    						</button>
    					</span>
    				</div>
    			</div>
    		{if $languages|count > 1}
    			<div class="col-lg-2">
    				<button type="button" class="btn btn-default dropdown-toggle" tabindex="-1" data-toggle="dropdown">
    					{$language.iso_code|escape:'html':'UTF-8'}
    					<span class="caret"></span>
    				</button>
    				<ul class="dropdown-menu">
    					{foreach from=$languages item=lang}
    					<li><a href="javascript:hideOtherLanguage({$lang.id_lang|intval});" tabindex="-1">{$lang.name|escape:'html':'UTF-8'}</a></li>
    					{/foreach}
    				</ul>
    			</div>
    		{/if}
    		{if $languages|count > 1}
    			</div>
    		{/if}
    		<script>
    		$(document).ready(function(){
    			$("#{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}-selectbutton").click(function(e){
    				$("#{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}").trigger('click');
    			});
                $("#{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}-name").click(function(e){
    				$("#{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}").trigger('click');
    			});
    			$("#{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}").change(function(e){
    				var val = $(this).val();
    				var file = val.split(/[\\/]/);
    				$("#{$input.name|escape:'html':'UTF-8'}_{$language.id_lang|intval}-name").val(file[file.length-1]);
    			});
    		});
    	</script>
    	{/foreach}
    {if $languages|count > 1}
      </div>
    {/if}
{else}
    {$smarty.block.parent}
{/if}
{/block}
{block name="legend"}
    {$smarty.block.parent}
    {if isset($configTabs) && $configTabs}
        <ul class="mkt_config_tab_header">
            {foreach from=$configTabs item='tab' key='tabId'}
                <li class="confi_tab config_tab_{$tabId|escape:'html':'UTF-8'} {if isset($current_tab) && $current_tab==$tabId}active{/if}" data-tab-id="{$tabId|escape:'html':'UTF-8'}">{$tab|escape:'html':'UTF-8'}</li>
            {/foreach}
        </ul>
    {/if}
{/block}
{block name="input_row"}
    {if $input.name=='ETS_MP_EMAIL_ADMIN_APPLICATION_REQUEST'}
        <div class="ets_mp_form email_settings">
            <p>{l s='Send email to Administrator'  mod='ets_marketplace'}</p>
        </div>
    {/if}
    {if $input.name=='ETS_MP_EMAIL_SELLER_APPLICATION_APPROVED_OR_DECLINED'}
        <div class="ets_mp_form email_settings">
            <p>{l s='Send email to Seller'  mod='ets_marketplace'}</p>
        </div>
    {/if}
    {if isset($input.tab) && $input.tab}
        <div class="ets_mp_form {$input.tab|escape:'html':'UTF-8'}">
    {/if}
    {if $input.name=='ETS_MP_SELLER_GROUP_DEFAULT'}
        <div class="alert alert-warning">{l s='You can separately customize each seller group.' mod='ets_marketplace'} {l s='Click' mod='ets_marketplace'} <a href="{$link->getAdminLink('AdminMarketPlaceShopGroups')|escape:'html':'UTF-8'}">{l s='Here' mod='ets_marketplace'}</a> {l s='to config' mod='ets_marketplace'}</div>
    {/if}
    
    {$smarty.block.parent}
    {if $input.type == 'file' && isset($input.imageType) && $input.imageType && isset($input.display_img) && $input.display_img}
        <div class="form-group ets_uploaded_img_wrapper {$input.imageType|escape:'html':'UTF-8'}">
            <label class="control-label col-lg-3 uploaded_image_label" style="font-style: italic;">&nbsp;</label>
            <div class="col-lg-9 uploaded_img_wrapper">
        		<a  class="ybc_fancy" href="{$input.display_img|escape:'html':'UTF-8'}"><img title="{l s='Click to see full size image' mod='ets_marketplace'}" style="display: inline-block; max-width: 150px;" src="{$input.display_img|escape:'html':'UTF-8'}" /></a>
                {if isset($input.img_del_link) && $input.img_del_link && !(isset($input.required) && $input.required)}
                    <a class="delete_url" style="display: inline-block; text-decoration: none!important;" href="{$input.img_del_link|escape:'html':'UTF-8'}" onclick="return confirm('{l s='Do you want to delete this image?' mod='ets_marketplace' js=1}');"><span style="color: #666"><i style="font-size: 20px;" class="process-icon-delete"></i></span></a>
                {/if}
            </div>
        </div>
      {/if}
    {if isset($input.tab) && $input.tab}
        </div>
    {/if}
{/block}
{block name="label"}
	{if isset($input.label)}
		<label class="control-label col-lg-3{if ((isset($input.required) && $input.required) || (isset($input.required2) && $input.required2))} required{/if}">
			{if isset($input.hint)}
			<span class="label-tooltip" data-toggle="tooltip" data-html="true" title="{if is_array($input.hint)}
						{foreach $input.hint as $hint}
							{if is_array($hint)}
								{$hint.text|escape:'html':'UTF-8'}
							{else}
								{$hint|escape:'html':'UTF-8'}
							{/if}
						{/foreach}
					{else}
						{$input.hint|escape:'html':'UTF-8'}
					{/if}">
			{/if}
			{$input.label nofilter}
			{if isset($input.hint)}
			</span>
			{/if}
		</label>
	{/if}
{/block}
{block name='description'}
    {if isset($input.desc) && !is_array($input.desc)}
        <p class="help-block">{$input.desc|replace:'[highlight]':'<code>'|replace:'[end_highlight]':'</code>' nofilter}</p>
    {else}
        {$smarty.block.parent}
    {/if}
{/block}