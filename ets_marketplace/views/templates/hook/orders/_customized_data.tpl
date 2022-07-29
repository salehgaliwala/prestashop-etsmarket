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
{if $product['customizedDatas']}
{* Assign product price *}
{if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
	{assign var=product_price value=($product['unit_price_tax_excl'] + $product['ecotax'])}
{else}
	{assign var=product_price value=$product['unit_price_tax_incl']}
{/if}
	<tr class="customized customized-{$product['id_order_detail']|intval} product-line-row">
		<td>
			<input type="hidden" class="edit_product_id_order_detail" value="{$product['id_order_detail']|intval}" />
			{if isset($product['image']) && $product['image']->id|intval}{$product['image_tag']|escape:'html':'UTF-8'}{else}--{/if}
		</td>
		<td>
			<a href="{$link->getAdminLink('AdminProducts', true, ['id_product' => $product['product_id']|intval, 'updateproduct' => '1'])|escape:'html':'UTF-8'}">
			<span class="productName">{$product['product_name']|escape:'html':'UTF-8'} - {l s='Customized' mod='ets_marketplace'}</span><br />
			{if ($product['product_reference'])}{l s='Reference number:' mod='ets_marketplace'} {$product['product_reference']|escape:'html':'UTF-8'}<br />{/if}
			{if ($product['product_supplier_reference'])}{l s='Supplier reference:' mod='ets_marketplace'} {$product['product_supplier_reference']|escape:'html':'UTF-8'}{/if}
			</a>
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
							<input type="text" name="product_price_tax_excl" class="edit_product_price_tax_excl edit_product_price" value="{Tools::ps_round($product['unit_price_tax_excl'], 2)|floatval}" size="5" />
							{if !$currencySymbolBeforeAmount}<div class="input-group-addon">{$currency->sign|escape:'html':'UTF-8'} {l s='tax excl.' mod='ets_marketplace'}</div>{/if}
						</div>
					</div>
					<br/>
					<div class="fixed-width-xl">
						<div class="input-group">
							{if $currencySymbolBeforeAmount}<div class="input-group-addon">{$currency->sign|escape:'html':'UTF-8'} {l s='tax incl.' mod='ets_marketplace'}</div>{/if}
							<input type="text" name="product_price_tax_incl" class="edit_product_price_tax_incl edit_product_price" value="{Tools::ps_round($product['unit_price_tax_incl'], 2)|floatval}" size="5" />
							{if $currencySymbolBeforeAmount}<div class="input-group-addon">{$currency->sign|escape:'html':'UTF-8'} {l s='tax incl.' mod='ets_marketplace'}</div>{/if}
						</div>
					</div>
				</div>
			</div>
			{/if}
		</td>
		<td class="productQuantity text-center">{$product['customizationQuantityTotal']|escape:'html':'UTF-8'}</td>
		{if $display_warehouse}<td>&nbsp;</td>{/if}
		{if ($order->hasBeenPaid())}<td class="productQuantity text-center">{$product['customizationQuantityRefunded']|escape:'html':'UTF-8'}</td>{/if}
		{if ($order->hasBeenDelivered() || $order->hasProductReturned())}<td class="productQuantity text-center">{$product['customizationQuantityReturned']|escape:'html':'UTF-8'}</td>{/if}
		{if $stock_location_is_available}<td class="productQuantity location text-center">{$product['location']|escape:'html':'UTF-8'}</td>{/if}
		{if $stock_management}<td class="text-center">{$product['current_stock']|escape:'html':'UTF-8'}</td>{/if}
		<td class="total_product">
		{if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
			{Tools::displayPrice(Tools::ps_round($product['product_price'] * $product['customizationQuantityTotal'],2),$currency)|escape:'html':'UTF-8'}
		{else}
			{Tools::displayPrice(Tools::ps_round($product['product_price_wt'] * $product['customizationQuantityTotal']),$currency)|escape:'html':'UTF-8'}
		{/if}
		</td>
		<td class="cancelQuantity standard_refund_fields current-edit" style="display:none" colspan="2">
			&nbsp;
		</td>
		<td class="edit_product_fields" colspan="2" style="display:none">&nbsp;</td>
		<td class="partial_refund_fields current-edit" style="text-align:left;display:none;"></td>
		{if ($can_edit && !$order->hasBeenDelivered())}
			<td class="product_action text-right">
				{* edit/delete controls *}
				<div class="btn-group">
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
		{/if}
	</tr>
	{foreach $product['customizedDatas'] as $customizationPerAddress}
		{foreach $customizationPerAddress as $customizationId => $customization}
			<tr class="customized customized-{$product['id_order_detail']|intval}">
				<td colspan="2">
				<input type="hidden" class="edit_product_id_order_detail" value="{$product['id_order_detail']|intval}" />
					<div class="form-horizontal">
						{foreach $customization.datas as $type => $datas}
							{if ($type == Product::CUSTOMIZE_FILE)}
								{foreach from=$datas item=data}
									<div class="form-group">
										<span class="col-lg-4 control-label"><strong>{if $data['name']}{$data['name']|escape:'html':'UTF-8'}{else}{l s='Picture #' mod='ets_marketplace'}{$data@iteration|escape:'html':'UTF-8'}{/if}</strong></span>
										<div class="col-lg-8">
											<a href="{$link->getAdminLink('AdminCarts', true, [], ['ajax' => 1, 'action' => 'customizationImage', 'img' => $data['value'], 'name' => $order->id|intval|cat:'-file'|cat:$data@iteration])}" class="_blank">
												<img class="img-thumbnail" src="{$smarty.const._THEME_PROD_PIC_DIR_|escape:'html':'UTF-8'}{$data['value']|escape:'html':'UTF-8'}_small" alt=""/>
											</a>
										</div>
									</div>
								{/foreach}
							{elseif ($type == Product::CUSTOMIZE_TEXTFIELD)}
								{foreach from=$datas item=data}
									<div class="form-group">
										<span class="col-lg-4 control-label"><strong>{if $data['name']}{$data['name']|escape:'html':'UTF-8'}{else}{l s='Text #%s' sprintf=[$data@iteration] mod='ets_marketplace'}{/if}</strong></span>
										<div class="col-lg-8">
											<p class="form-control-static">{$data['value']|escape:'html':'UTF-8'}</p>
										</div>
									</div>
								{/foreach}
							{/if}
						{/foreach}
					</div>
				</td>
				<td>-</td>
				<td class="productQuantity text-center">
					<span class="product_quantity_show{if (int)$customization['quantity'] > 1} red bold{/if}">{$customization['quantity']|escape:'html':'UTF-8'}</span>
					{if $can_edit}
					<span class="product_quantity_edit" style="display:none;">
						<input type="text" name="product_quantity[{$customizationId|intval}]" class="edit_product_quantity" value="{$customization['quantity']|htmlentities}" size="2" />
					</span>
					{/if}
				</td>
				{if $display_warehouse}<td>&nbsp;</td>{/if}
				{if ($order->hasBeenPaid())}
				<td class="text-center">
					{if !empty($product['amount_refund'])}
					{l s='%quantity_refunded% (%amount_refunded% refund)' sprintf=['%quantity_refunded%' => $customization['quantity_refunded'], '%amount_refunded%' => $product['amount_refund']] mod='ets_marketplace'}
					{/if}
					<input type="hidden" value="{$product['quantity_refundable']|escape:'html':'UTF-8'}" class="partialRefundProductQuantity" />
					<input type="hidden" value="{(Tools::ps_round($product_price, 2) * ($product['product_quantity'] - $product['customizationQuantityTotal']))|floatval}" class="partialRefundProductAmount" />
				</td>
				{/if}
				{if ($order->hasBeenDelivered())}<td class="text-center">{$customization['quantity_returned']|escape:'html':'UTF-8'}</td>{/if}
				<td class="text-center">-</td>
				{if $stock_location_is_available}<td class="text-center">-</td>{$product['location']|escape:'html':'UTF-8'}</td>{/if}
				<td class="total_product">
					{if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
						{Tools::displayPrice(Tools::ps_round($product['product_price'] * $customization['quantity'],2),$currency)|escape:'html':'UTF-8'}
					{else}
						{Tools::displayPrice(Tools::ps_round($product['product_price_wt'] * $customization['quantity'], 2),$currency)|escape:'html':'UTF-8'}
					{/if}
				</td>
				<td class="cancelCheck standard_refund_fields current-edit" style="display:none">
					<input type="hidden" name="totalQtyReturn" id="totalQtyReturn" value="{$customization['quantity_returned']|intval}" />
					<input type="hidden" name="totalQty" id="totalQty" value="{$customization['quantity']|intval}" />
					<input type="hidden" name="productName" id="productName" value="{$product['product_name']|escape:'html':'UTF-8'}" />
					{if ((!$order->hasBeenDelivered() OR Configuration::get('PS_ORDER_RETURN')) AND (int)($customization['quantity_returned']) < (int)($customization['quantity']))}
						<input type="checkbox" name="id_customization[{$customizationId|intval}]" id="id_customization[{$customizationId|intval}]" value="{$product['id_order_detail']|intval}" onchange="setCancelQuantity(this, {$customizationId|intval}, {($customization['quantity'] - $product['customizationQuantityTotal'] - $product['product_quantity_reinjected'])|intval})" {if ($product['product_quantity_return'] + $product['product_quantity_refunded'] >= $product['product_quantity'])}disabled="disabled" {/if}/>
					{else}
					--
				{/if}
				</td>
				<td class="cancelQuantity standard_refund_fields current-edit" style="display:none">
				{if ($customization['quantity_returned'] + $customization['quantity_refunded'] >= $customization['quantity'])}
					<input type="hidden" name="cancelCustomizationQuantity[{$customizationId|intval}]" value="0" />
				{elseif (!$order->hasBeenDelivered() OR Configuration::get('PS_ORDER_RETURN'))}
					<input type="text" id="cancelQuantity_{$customizationId|intval}" name="cancelCustomizationQuantity[{$customizationId|intval}]" size="2" onclick="selectCheckbox(this);" value="" />0/{($customization['quantity']-$customization['quantity_refunded'])|intval}
				{/if}
				</td>
				<td class="partial_refund_fields current-edit" colspan="2" style="display:none; width: 250px;">
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
								<input onchange="checkPartialRefundProductQuantity(this)" type="text" name="partialRefundProductQuantity[{$product['id_order_detail']|intval}]" value="{if ($customization['quantity']-$customization['quantity_refunded']) >0}1{else}0{/if}" />
								<div class="input-group-addon">/ {$product['quantity_refundable']|escape:'html':'UTF-8'}</div>
							</div>
						</div>
						<div class="{if $product['quantity_refundable'] > 0}col-lg-8{else}col-lg-12{/if}">
							<label class="control-label">
								<span class="title_box ">{l s='Amount:' mod='ets_marketplace'}</span>
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
					<td class="edit_product_fields" colspan="2" style="display:none"></td>
					<td class="product_action" style="text-align:right"></td>
				{/if}
			</tr>
		{/foreach}
	{/foreach}
{/if}
