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
{assign var=color_header value="#F0F0F0"}
{assign var=color_border value="#dddddd"}
{assign var=color_border_lighter value="#dddddd"}
{assign var=color_line_even value="#FFFFFF"}
{assign var=color_line_odd value="#F9F9F9"}
{assign var=font_size_text value="9pt"}
{assign var=font_size_header value="9pt"}
{assign var=font_size_product value="9pt"}
{assign var=height_header value="20px"}
{assign var=table_padding value="4px"}

<style>
	table, th, td {
		vertical-align: middle;
		font-size: {$font_size_text|escape:'html':'UTF-8'};
		white-space: nowrap;
	}

	table.product {
		border: 1px solid {$color_border|escape:'html':'UTF-8'};
		border-collapse: collapse;
	}

	table#addresses-tab tr td {
		font-size: large;
	}

	table#summary-tab {
		padding: {$table_padding|escape:'html':'UTF-8'};
		border: 1pt solid {$color_border|escape:'html':'UTF-8'};
	}
	table#total-tab {
		padding: {$table_padding|escape:'html':'UTF-8'};
		border: 1pt solid {$color_border|escape:'html':'UTF-8'};
	}
	table#note-tab {
		padding: {$table_padding|escape:'html':'UTF-8'};
		border: 1px solid {$color_border|escape:'html':'UTF-8'};
	}
	table#note-tab td.note{
		word-wrap: break-word;
	}
	table#tax-tab {
		padding: {$table_padding|escape:'html':'UTF-8'};
		border: 1pt solid {$color_border|escape:'html':'UTF-8'};
	}
	table#payment-tab,
	table#shipping-tab {
		padding: {$table_padding|escape:'html':'UTF-8'};
		border: 1px solid {$color_border|escape:'html':'UTF-8'};
	}

	th.product {
		border-bottom: 1px solid {$color_border|escape:'html':'UTF-8'};
	}

	tr.discount th.header {
		border-top: 1px solid {$color_border|escape:'html':'UTF-8'};
	}

	tr.product td {
		border-bottom: 1px solid {$color_border_lighter|escape:'html':'UTF-8'};
	}

	tr.color_line_even {
		background-color: {$color_line_even|escape:'html':'UTF-8'};
	}

	tr.color_line_odd {
		background-color: {$color_line_odd|escape:'html':'UTF-8'};
	}

	tr.customization_data td {
	}

	td.product {
		vertical-align: middle;
		font-size: {$font_size_product|escape:'html':'UTF-8'};
	}

	th.header {
		font-size: {$font_size_header|escape:'html':'UTF-8'};
		height: {$height_header|escape:'html':'UTF-8'};
		background-color: {$color_header|escape:'html':'UTF-8'};
		vertical-align: middle;
		text-align: center;
		font-weight: bold;
	}

	th.header-right {
		font-size: {$font_size_header|escape:'html':'UTF-8'};
		height: {$height_header|escape:'html':'UTF-8'};
		background-color: {$color_header|escape:'html':'UTF-8'};
		vertical-align: middle;
		text-align: right;
		font-weight: bold;
	}

	th.payment,
	th.shipping {
		background-color: {$color_header|escape:'html':'UTF-8'};
		vertical-align: middle;
		font-weight: bold;
	}

	th.tva {
		background-color: {$color_header|escape:'html':'UTF-8'};
		vertical-align: middle;
		font-weight: bold;
	}

	tr.separator td {
		border-top: 1px solid #000000;
	}

	.left {
		text-align: left;
	}

	.fright {
		float: right;
	}

	.right {
		text-align: right;
	}

	.center {
		text-align: center;
	}

	.bold {
		font-weight: bold;
	}

	.border {
		border: 1px solid black;
	}

	.no_top_border {
		border-top:hidden;
		border-bottom:1px solid black;
		border-left:1px solid black;
		border-right:1px solid black;
	}

	.grey {
		background-color: {$color_header|escape:'html':'UTF-8'};

	}

	/* This is used for the border size */
	.white {
		background-color: #FFFFFF;
	}

	.big,
	tr.big td{
		font-size: 110%;
	}

	.small, table.small th, table.small td {
		font-size:small;
	}
</style>
<table width="100%" id="body" border="0" cellpadding="0" cellspacing="0" style="margin:0;border-collapse: collapse;">
    <tr>
		<td colspan="6" style="padding:8px 10px">
			<table id="addresses-tab" cellspacing="0" cellpadding="0" style="border-collapse: collapse;width:100%;">
            	<tr>
            		<td width="50%">
                        <span class="bold">{l s='From' mod='ets_marketplace'}</span>
            		</td>
            		<td width="50%">
						<span class="bold">{l s='Bill to' mod='ets_marketplace'}</span>
                    </td>
            	</tr>
				<tr>
					<td width="50%">
						<p style="margin:0;display:block;">&nbsp;&nbsp;{$PS_SHOP_NAME|escape:'html':'UTF-8'}</p>
						{if $PS_SHOP_ADDR1 || $PS_SHOP_ADDR2}
							<p style="margin:0;display:block;">&nbsp;&nbsp;{$PS_SHOP_ADDR1|escape:'html':'UTF-8'} {$PS_SHOP_ADDR2|escape:'html':'UTF-8'}</p>
						{/if}
						{if $PS_SHOP_CITY || $PS_SHOP_STATE || $PS_SHOP_CODE}
							<p style="margin:0;display:block;">&nbsp;&nbsp;{if $PS_SHOP_CITY}{$PS_SHOP_CITY|escape:'html':'UTF-8'}, {/if}{$PS_SHOP_STATE|escape:'html':'UTF-8'} {$PS_SHOP_CODE|escape:'html':'UTF-8'}</p>
						{/if}
						{if $PS_SHOP_COUNTRY}
							<p style="margin:0;display:block;">&nbsp;&nbsp;{$PS_SHOP_COUNTRY|escape:'html':'UTF-8'}</p>
						{/if}
						<p style="margin:0;display:block;">&nbsp;&nbsp;{$PS_SHOP_PHONE|escape:'html':'UTF-8'}</p>
					</td>
					<td width="50%">
						<p style="margin:0;display:block;">{$seller->seller_name|escape:'html':'UTF-8'} ({$seller->shop_name|escape:'html':'UTF-8'})</p>
						<p style="margin:0;display:block;">{$seller->shop_address|escape:'html':'UTF-8'}</p>
						<p style="margin:0;display:block;">{$seller->shop_phone|escape:'html':'UTF-8'}</p>
					</td>
				</tr>
            </table>
		</td>
	</tr>
    <tr>
		<td colspan="12" height="20">&nbsp;</td>
	</tr>
    <tr>
        <td colspan="12" style="padding:8px 10px">
            <table class="product" width="100%" cellpadding="4" cellspacing="0">
                <thead>
	               <tr>
                        <th class="product header small" style="padding:8px 10px">{l s='Reference' mod='ets_marketplace'}</th>
                        <th class="product header small" style="text-align:center;padding:8px 10px">{l s='Amount' mod='ets_marketplace'}</th>
                        <th class="product header small" style="text-align:center;padding:8px 10px">{l s='Description' mod='ets_marketplace'}</th>
                        <th class="product header small" style="text-align:center;padding:8px 10px">{l s='Invoice date' mod='ets_marketplace'}</th>
                   </tr>
                </thead>
                <tbody>
                    <tr class="product">
                        <td class="product" style="padding:15px 10px">{$billing_model->reference|escape:'html':'UTF-8'}</td>
                        <td class="product" style="text-align:center;padding:15px 10px">{$billing_model->amount|escape:'html':'UTF-8'}</td>
                        <td class="product" style="text-align:center;padding:15px 10px">
                            {if !$billing_model->id_employee}
                                {if $billing_model->fee_type=='pay_once'}
                                    {l s='Marketplace fee' mod='ets_marketplace'}
                                {/if}
                                {if $billing_model->fee_type=='monthly_fee'}
                                    {l s='Marketplace fee: from' mod='ets_marketplace'}&nbsp;{dateFormat date=$billing_model->date_from}&nbsp;{l s='to' mod='ets_marketplace'}&nbsp;{dateFormat date=$billing_model->date_to}
                                {/if}
                                {if $billing_model->fee_type=='quarterly_fee'}
                                    {l s='Marketplace fee: from' mod='ets_marketplace'}&nbsp;{dateFormat date=$billing_model->date_from}&nbsp;{l s='to' mod='ets_marketplace'}&nbsp;{dateFormat date=$billing_model->date_to}
                                {/if}
                                {if $billing_model->fee_type=='yearly_fee'}
                                    {l s='Marketplace fee: from' mod='ets_marketplace'}&nbsp;{dateFormat date=$billing_model->date_from}&nbsp;{l s='to' mod='ets_marketplace'}&nbsp;{dateFormat date=$billing_model->date_to}
                                {/if}
                            {else}
                                {if $billing_model->note}
                                    {$billing_model->note|escape:'html':'UTF-8'}
                                {/if}<br />
                                {if $billing_model->date_from && $billing_model->date_from!='0000-00-00'}
                                    {l s='From' mod='ets_marketplace'} {dateFormat date=$billing_model->date_from}
                                {/if}
                                {if $billing_model->date_to && $billing_model->date_to!='0000-00-00'}
                                    {l s='To' mod='ets_marketplace'} {dateFormat date=$billing_model->date_to}
                                {/if}
                            {/if}
                        
                        </td>
                        <td class="product" style="text-align:center;padding:15px 10px">{dateFormat date=$billing_model->date_add full=1}</td>
                    </tr>
                </tbody>
				<tfoot>
					<tr>
						<th class="" colspan="2" style="padding:8px 10px">&nbsp;</th>
						<th class="product header small" style="padding:8px 10px;text-transform: uppercase;">{l s='Total' mod='ets_marketplace'}</th>
						<th class="product header small" style="background: #fff;padding:8px 10px">{$billing_model->amount|escape:'html':'UTF-8'}</th>
					</tr>
				</tfoot>
            </table>
        </td>
    </tr>
</table>