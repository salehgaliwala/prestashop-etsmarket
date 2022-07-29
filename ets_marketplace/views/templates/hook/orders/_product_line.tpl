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
{assign var="currencySymbolBeforeAmount" value=$currency->format[0] === '¤'}
{* Assign product price *}
{if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
	{assign var=product_price value=($product['unit_price_tax_excl'] + $product['ecotax'])}
{else}
	{assign var=product_price value=$product['unit_price_tax_incl']}
{/if}

{if ($product['product_quantity'] > $product['customized_product_quantity'])}
<tr class="product-line-row">
	<td>{if isset($product.image) && $product.image->id}{$product.image_tag nofilter}{/if}</td>
	<td>
		<a href="{$link->getProductLink($product['product_id'])|escape:'html':'UTF-8'}">
			<span class="productName">{$product['product_name']|escape:'html':'UTF-8'}</span>
			{if $product.product_reference}{l s='Reference number:' mod='ets_marketplace'} {$product.product_reference|escape:'html':'UTF-8'}<br />{/if}
			{if $product.product_supplier_reference}{l s='Supplier reference:' mod='ets_marketplace'} {$product.product_supplier_reference|escape:'html':'UTF-8'}{/if}
		</a>
        {if isset($product.pack_items) && $product.pack_items|@count > 0}<br>
            <button name="package" class="btn btn-default ets_mk_pack" type="button" onclick="TogglePackage('{$product['id_order_detail']|intval}'); return false;" value="{$product['id_order_detail']|intval}">{l s='Package content' mod='ets_marketplace'}</button>
        {/if}
		<div class="row-editing-warning" style="display:none;">
			<div class="alert alert-warning">
				<strong>{l s='Editing this product line will remove the reduction and base price.' mod='ets_marketplace'}</strong>
			</div>
		</div>
	</td>
	<td>
		<span class="product_price_show">{Tools::displayPrice($product_price,$currency)|escape:'html':'UTF-8'}</span>
		{if $can_edit}
		<div class="product_price_edit" style="display:none;">
			<input type="hidden" name="product_id_order_detail" class="edit_product_id_order_detail" value="{$product['id_order_detail']|intval}" />
			<div class="form-group">
				<div class="fixed-width-xl">
					<div class="input-group">
						{if $currencySymbolBeforeAmount}<div class="input-group-addon">{$currency->sign|escape:'html':'UTF-8'} {l s='tax excl.' mod='ets_marketplace'}</div>{/if}
						<input type="text" name="product_price_tax_excl" class="edit_product_price_tax_excl edit_product_price" value="{Tools::ps_round($product['unit_price_tax_excl'], 2)|escape:'html':'UTF-8'}"/>
						{if !$currencySymbolBeforeAmount}<div class="input-group-addon">{$currency->sign|escape:'html':'UTF-8'} {l s='tax excl.' mod='ets_marketplace'}</div>{/if}
					</div>
				</div>
				<br/>
				<div class="fixed-width-xl">
					<div class="input-group">
						{if $currencySymbolBeforeAmount}<div class="input-group-addon">{$currency->sign|escape:'html':'UTF-8'} {l s='tax incl.' mod='ets_marketplace'}</div>{/if}
						<input type="text" name="product_price_tax_incl" class="edit_product_price_tax_incl edit_product_price" value="{Tools::ps_round($product['unit_price_tax_incl'], 2)|escape:'html':'UTF-8'}"/>
						{if !$currencySymbolBeforeAmount}<div class="input-group-addon">{$currency->sign|escape:'html':'UTF-8'} {l s='tax incl.' mod='ets_marketplace'}</div>{/if}
					</div>
				</div>
			</div>
		</div>
		{/if}
	</td>
	<td class="productQuantity text-center">
		<span class="product_quantity_show{if (int)$product['product_quantity'] - (int)$product['customized_product_quantity'] > 1} badge{/if}">{((int)$product['product_quantity'] - (int)$product['customized_product_quantity'])|intval}</span>
		{if $can_edit}
		<span class="product_quantity_edit" style="display:none;">
			<input type="text" name="product_quantity" class="edit_product_quantity" value="{$product['product_quantity']|intval}"/>
		</span>
		{/if}
	</td>
	{if $display_warehouse}
		<td>
			{$product.warehouse_name|escape:'html':'UTF-8'}
			{if $product.warehouse_location}
				<br>{l s='Location' mod='ets_marketplace'}: <strong>{$product.warehouse_location|escape:'html':'UTF-8'}</strong>
			{/if}
		</td>
	{/if}
	{if ($order->hasBeenPaid())}
		<td class="productQuantity text-center">
			{if !empty($product['amount_refund'])}
				{l s='%quantity_refunded% (%amount_refunded% refund)' sprintf=['%quantity_refunded%' => $product['product_quantity_refunded'], '%amount_refunded%' => $product['amount_refund']] mod='ets_marketplace'}
			{/if}
			<input type="hidden" value="{$product['quantity_refundable']|escape:'html':'UTF-8'}" class="partialRefundProductQuantity" />
			<input type="hidden" value="{(Tools::ps_round($product_price, 2) * ($product['product_quantity'] - $product['customizationQuantityTotal']))|escape:'html':'UTF-8'}" class="partialRefundProductAmount" />
			{if count($product['refund_history'])}
				<span class="tooltip">
					<span class="tooltip_label tooltip_button">+</span>
					<span class="tooltip_content">
					<span class="title">{l s='Refund history' mod='ets_marketplace'}</span>
					{foreach $product['refund_history'] as $refund}
						{l s='%refund_date% - %refund_amount%' sprintf=['%refund_date%' => {dateFormat date=$refund.date_add}, '%refund_amount%' => {Tools::displayPrice($refund.amount_tax_incl)|escape:'html':'UTF-8'}] mod='ets_marketplace'}<br />
					{/foreach}
					</span>
				</span>
			{/if}
		</td>
	{/if}
	{if $order->hasBeenDelivered() || $order->hasProductReturned()}
		<td class="productQuantity text-center">
			{$product['product_quantity_return']|escape:'html':'UTF-8'}
			{if count($product['return_history'])}
				<span class="tooltip">
					<span class="tooltip_label tooltip_button">+</span>
					<span class="tooltip_content">
					<span class="title">{l s='Return history' mod='ets_marketplace'}</span>
					{foreach $product['return_history'] as $return}
						{l s='%return_date% - %return_quantity% - %return_state%' sprintf=['%return_date%' =>{dateFormat date=$return.date_add}, '%return_quantity%' => $return.product_quantity, '3return_state%' => $return.state] mod='ets_marketplace'}<br />
					{/foreach}
					</span>
				</span>
			{/if}
		</td>
	{/if}
	{if $stock_location_is_available}<td class="productQuantity location text-center">{$product['location']|escape:'html':'UTF-8'}</td>{/if}
	{if $stock_management}<td class="productQuantity product_stock text-center">{$product['current_stock']|escape:'html':'UTF-8'}</td>{/if}
	<td class="total_product">
		{Tools::displayPrice((Tools::ps_round($product_price, 2) * ($product['product_quantity'] - $product['customizationQuantityTotal'])),$currency )|escape:'html':'UTF-8'}
	</td>
	<td colspan="2" style="display: none;" class="add_product_fields">&nbsp;</td>
	<td class="cancelCheck standard_refund_fields current-edit" style="display:none">
		<input type="hidden" name="totalQtyReturn" id="totalQtyReturn" value="{$product['product_quantity_return']|intval}" />
		<input type="hidden" name="totalQty" id="totalQty" value="{$product['product_quantity']|intval}" />
		<input type="hidden" name="productName" id="productName" value="{$product['product_name']|escape:'html':'UTF-8'}" />
	{if ((!$order->hasBeenDelivered() OR Configuration::get('PS_ORDER_RETURN')) AND (int)($product['product_quantity_return']) < (int)($product['product_quantity']))}
		<input type="checkbox" name="id_order_detail[{$product['id_order_detail']|intval}]" id="id_order_detail[{$product['id_order_detail']|intval}]" value="{$product['id_order_detail']|intval}" onchange="setCancelQuantity(this, {$product['id_order_detail']|intval}, {($product['product_quantity'] - $product['customizationQuantityTotal'] - $product['product_quantity_return'] - $product['product_quantity_refunded'])|intval})" {if ($product['product_quantity_return'] + $product['product_quantity_refunded'] >= $product['product_quantity'])}disabled="disabled" {/if}/>
	{else}
		--
	{/if}
	</td>
	<td class="cancelQuantity standard_refund_fields current-edit" style="display:none">
	{if ($product['product_quantity_return'] + $product['product_quantity_refunded'] >= $product['product_quantity'])}
		<input type="hidden" name="cancelQuantity[{$product['id_order_detail']|intval}]" value="0" />
	{elseif (!$order->hasBeenDelivered() OR Configuration::get('PS_ORDER_RETURN'))}
		<input type="text" id="cancelQuantity_{$product['id_order_detail']|intval}" name="cancelQuantity[{$product['id_order_detail']|intval}]" onchange="checkTotalRefundProductQuantity(this)" value="" />
	{/if}

	{if $product['customizationQuantityTotal']}
		{assign var=productQuantity value=($product['product_quantity']-$product['customizationQuantityTotal'])}
	{else}
		{assign var=productQuantity value=$product['product_quantity']}
	{/if}

	{if ($order->hasBeenDelivered())}
		{$product['product_quantity_refunded']|intval}/{($productQuantity-$product['product_quantity_refunded'])|intval}
	{elseif ($order->hasBeenPaid())}
		{$product['product_quantity_return']|intval}/{$productQuantity|intval}
	{else}
		0/{$productQuantity|intval}
	{/if}
	</td>
	<td class="partial_refund_fields current-edit" colspan="2" style="display:none; width: 250px; min-width: 250px;">
		{if $product['quantity_refundable'] > 0}
		{if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
			{assign var='amount_refundable' value=$product['amount_refundable']}
		{else}
			{assign var='amount_refundable' value=$product['amount_refundable_tax_incl']}
		{/if}
		<div class="form-group">
			<div class="{if $product['amount_refundable'] > 0}col-lg-4{else}col-lg-12{/if}">
				<label class="control-label">
					{l s='Quantity:' mod='ets_marketplace'}
				</label>
				<div class="input-group">
					<input onchange="checkPartialRefundProductQuantity(this)" type="text" name="partialRefundProductQuantity[{$product['id_order_detail']|intval}]" value="0" />
					<div class="input-group-addon">/ {$product['quantity_refundable']|intval}</div>
				</div>
			</div>
			<div class="{if $product['quantity_refundable'] > 0}col-lg-8{else}col-lg-12{/if}">
				<label class="control-label">
					<span class="title_box ">{l s='Amount' mod='ets_marketplace'}</span>
					<small class="text-muted">({$smarty.capture.TaxMethod|escape:'html':'UTF-8'})</small>
				</label>
				<div class="input-group">
					{if $currencySymbolBeforeAmount}<div class="input-group-addon">{$currency->sign|escape:'html':'UTF-8'}</div>{/if}
					<input onchange="checkPartialRefundProductAmount(this)" type="text" name="partialRefundProduct[{$product['id_order_detail']|intval}]" />
					{if !$currencySymbolBeforeAmount}<div class="input-group-addon">{$currency->sign|escape:'html':'UTF-8'}</div>{/if}
				</div>
                <p class="help-block"><i class="fa fa-warning-sign"></i> {l s='(Max %amount_refundable% %tax_method%)' sprintf=[ '%amount_refundable%' => Tools::displayPrice(Tools::ps_round($amount_refundable, 2), $currency->id), '%tax_method%' => $smarty.capture.TaxMethod] mod='ets_marketplace'}</p>
			</div>
		</div>
		{/if}
	</td>
	{if ($can_edit && !$order->hasBeenDelivered())}
	<td class="product_invoice" style="display: none;">
		{if sizeof($invoices_collection)}
		<select name="product_invoice" class="edit_product_invoice">
			{foreach from=$invoices_collection item=invoice}
			<option value="{$invoice->id|escape:'html':'UTF-8'}" {if $invoice->id == $product['id_order_invoice']}selected="selected"{/if}>
				#{Configuration::get('PS_INVOICE_PREFIX', $current_id_lang, null, $order->id_shop)|escape:'html':'UTF-8'}{'%06d'|sprintf:$invoice->number|escape:'html':'UTF-8'}
			</option>
			{/foreach}
		</select>
		{else}
		&nbsp;
		{/if}
	</td>
	<td class="product_action text-right">
		{* edit/delete controls *}
		<div class="btn-group" id="btn_group_action">
			<button type="button" class="btn btn-default edit_product_change_link">
				<i class="fa fa-pencil"></i>
				{l s='Edit' mod='ets_marketplace'}
			</button>
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu" role="menu">
				<li>
					<a href="#" class="delete_product_line">
						<i class="fa fa-trash"></i>
						{l s='Delete' mod='ets_marketplace'}
					</a>
				</li>
			</ul>
		</div>
		{* Update controls *}
		<button type="button" class="btn btn-default submitProductChange" style="display: none;">
			<i class="fa fa-ok"></i>
			{l s='Update' mod='ets_marketplace'}
		</button>
		<button type="button" class="btn btn-default cancel_product_change_link" style="display: none;">
			<i class="fa fa-remove"></i>
			{l s='Cancel' mod='ets_marketplace'}
		</button>
	</td>
    {else}
        <td></td>
	{/if}
</tr>
   {if isset($product.pack_items) && $product.pack_items|@count > 0}
    <tr>
        <td colspan="8" style="width:100%;border-bottom: medium none;">
            <table style="width: 100%; display:none;" class="table" id="pack_items_{$product['id_order_detail']|intval}">
            <thead>
                <th style="width:15%;">&nbsp;</th>
                <th style="width:15%;">&nbsp;</th>
                <th style="width:50%;"><span class="title_box ">{l s='Product' mod='ets_marketplace'}</span></th>
                <th style="width:10%;"><span class="title_box ">{l s='Qty' mod='ets_marketplace'}</th>
                {if $stock_management}<th><span class="title_box ">{l s='Available quantity' mod='ets_marketplace'}</span></th>{/if}
                <th>&nbsp;</th>
            </thead>
            <tbody>
            {foreach from=$product.pack_items item=pack_item}
                {if !empty($pack_item.active)}
                    <tr class="product-line-row" {if isset($pack_item.image) && $pack_item.image->id && isset($pack_item.image_size)} height="{($pack_item['image_size'][1] + 7)|escape:'html':'UTF-8'}"{/if}>
                        <td>{l s='Package item' mod='ets_marketplace'}</td>
                        <td>{if isset($pack_item.image) && $pack_item.image->id}{$pack_item.image_tag nofilter}{/if}</td>
                        <td>
                            <a href="{$link->getAdminLink('AdminProducts', true, ['id_product' => $pack_item.id_product, 'updateproduct' => '1'])|escape:'html':'UTF-8'}">
                                <span class="productName">{$pack_item.name|escape:'html':'UTF-8'}</span><br />
                                {if $pack_item.reference}{l s='Ref:' mod='ets_marketplace'} {$pack_item.reference|escape:'html':'UTF-8'}<br />{/if}
                                {if $pack_item.supplier_reference}{l s='Supplier ref:' mod='ets_marketplace'} {$pack_item.supplier_reference|escape:'html':'UTF-8'}{/if}
                            </a>
                        </td>
                        <td class="productQuantity">
                            <span class="product_quantity_show{if (int)$pack_item.pack_quantity > 1} red bold{/if}">{$pack_item.pack_quantity|intval}</span>
                        </td>
                        {if $stock_management}<td class="productQuantity product_stock">{$pack_item.current_stock|escape:'html':'UTF-8'}</td>{/if}
                        <td>&nbsp;</td>
                    </tr>
                {/if}
            {/foreach}
            </tbody>
            </table>
        </td>
    </tr>
    {/if}
{/if}
