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
<div class="table-responsive">
	<table class="table" id="documents_table">
		<thead>
			<tr>
				<th>
					<span class="title_box ">{l s='Date' mod='ets_marketplace'}</span>
				</th>
				<th>
					<span class="title_box ">{l s='Document' mod='ets_marketplace'}</span>
				</th>
				<th>
					<span class="title_box ">{l s='Number' mod='ets_marketplace'}</span>
				</th>
				<th>
					<span class="title_box ">{l s='Amount' mod='ets_marketplace'}</span>
				</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$order->getDocuments() item=document}

				{if get_class($document) eq 'OrderInvoice'}
					{if isset($document->is_delivery)}
					<tr id="delivery_{$document->id|escape:'html':'UTF-8'}">
					{else}
					<tr id="invoice_{$document->id|escape:'html':'UTF-8'}">
					{/if}
				{elseif get_class($document) eq 'OrderSlip'}
					<tr id="orderslip_{$document->id|escape:'html':'UTF-8'}">
				{/if}

						<td>{dateFormat date=$document->date_add}</td>
						<td>
							{if get_class($document) eq 'OrderInvoice'}
								{if isset($document->is_delivery)}
									{l s='Delivery slip' mod='ets_marketplace'}
								{else}
									{l s='Invoice' mod='ets_marketplace'}
								{/if}
							{elseif get_class($document) eq 'OrderSlip'}
								{l s='Credit slip' mod='ets_marketplace'}
							{/if}
						</td>
						<td>
							{if get_class($document) eq 'OrderInvoice'}
								{if isset($document->is_delivery)}
									<a class="_blank" title="{l s='See the document' mod='ets_marketplace'}" href="{$link->getModuleLink('ets_marketplace','pdf',['submitAction' => 'generateDeliverySlipPDF', 'id_order_invoice' => $document->id])|escape:'html':'UTF-8'}">
								{else}
									<a class="_blank" title="{l s='See the document' mod='ets_marketplace'}" href="{$link->getModuleLink('ets_marketplace','pdf',['submitAction' => 'generateInvoicePDF', 'id_order_invoice' => $document->id])|escape:'html':'UTF-8'}">
							   {/if}
							{elseif get_class($document) eq 'OrderSlip'}
								<a class="_blank" title="{l s='See the document' mod='ets_marketplace'}" href="{$link->getModuleLink('ets_marketplace','pdf',['submitAction' => 'generateOrderSlipPDF', 'id_order_slip' => $document->id])|escape:'html':'UTF-8'}">
							{/if}
							{if get_class($document) eq 'OrderInvoice'}
								{if isset($document->is_delivery)}
									{Configuration::get('PS_DELIVERY_PREFIX', $current_id_lang, null, $order->id_shop)|escape:'html':'UTF-8'}{'%06d'|sprintf:$document->delivery_number|escape:'html':'UTF-8'}
								{else}
									{$document->getInvoiceNumberFormatted($current_id_lang, $order->id_shop)|escape:'html':'UTF-8'}
								{/if}
							{elseif get_class($document) eq 'OrderSlip'}
								{Configuration::get('PS_CREDIT_SLIP_PREFIX', $current_id_lang)|escape:'html':'UTF-8'}{'%06d'|sprintf:$document->id|escape:'html':'UTF-8'}
							{/if}
							</a>
						</td>
						<td>
						{if get_class($document) eq 'OrderInvoice'}
							{if isset($document->is_delivery)}
								--
							{else}
								{displayPrice price=$document->total_paid_tax_incl currency=$currency->id}&nbsp;
								{if $document->getTotalPaid()}
									<span>
									{if $document->getRestPaid() > 0}
										({displayPrice price=$document->getRestPaid() currency=$currency->id} {l s='not paid' mod='ets_marketplace'})
									{elseif $document->getRestPaid() < 0}
										({displayPrice price=-$document->getRestPaid() currency=$currency->id} {l s='overpaid' mod='ets_marketplace'})
									{/if}
									</span>
								{/if}
							{/if}
						{elseif get_class($document) eq 'OrderSlip'}
							{displayPrice price=$document->total_products_tax_incl+$document->total_shipping_tax_incl currency=$currency->id}
						{/if}
						</td>
						<td class="text-right document_action">
						</td>
					</tr>
			{foreachelse}
				<tr>
					<td colspan="5" class="list-empty">
						<div class="list-empty-msg">
							<p><i class="fa fa-warning-sign list-empty-fa fa"></i>
							{l s='There is no available document' mod='ets_marketplace'}</p>
						</div>
					</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
</div>
