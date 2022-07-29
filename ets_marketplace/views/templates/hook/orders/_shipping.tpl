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
	<table class="table" id="shipping_table">
		<thead>
			<tr>
				<th>
					<span class="title_box ">{l s='Date' mod='ets_marketplace'}</span>
				</th>
				<th>
					<span class="title_box ">&nbsp;</span>
				</th>
				<th>
					<span class="title_box ">{l s='Carrier' mod='ets_marketplace'}</span>
				</th>
				<th>
					<span class="title_box ">{l s='Weight' mod='ets_marketplace'}</span>
				</th>
				<th>
					<span class="title_box ">{l s='Shipping cost' mod='ets_marketplace'}</span>
				</th>
				<th>
					<span class="title_box ">{l s='Tracking number' mod='ets_marketplace'}</span>
				</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$order->getShipping() item=line}
			<tr>
				<td>{dateFormat date=$line.date_add full=true}</td>
				<td>&nbsp;</td>
				<td>{$line.carrier_name|escape:'html':'UTF-8'}</td>
				<td class="weight">{$line.weight|string_format:"%.3f"|escape:'html':'UTF-8'} {Configuration::get('PS_WEIGHT_UNIT')|escape:'html':'UTF-8'}</td>
				<td class="price_carrier_{$line.id_carrier|intval}" class="center">
					<span>
					{if $order->getTaxCalculationMethod() == $smarty.const.PS_TAX_INC}
						{displayPrice price=$line.shipping_cost_tax_incl currency=$currency->id}
					{else}
						{displayPrice price=$line.shipping_cost_tax_excl currency=$currency->id}
					{/if}
					</span>
				</td>
				<td>
					<span class="shipping_number_show">{if $line.url && $line.tracking_number}<a class="_blank" href="{$line.url|replace:'@':$line.tracking_number|escape:'html':'UTF-8'}">{$line.tracking_number|escape:'html':'UTF-8'}</a>{else}{$line.tracking_number|escape:'html':'UTF-8'}{/if}</span>
				</td>
				<td>
					{*if $line.can_edit}
						<a href="#" class="edit_shipping_link btn btn-default"
						data-id-order-carrier="{$line.id_order_carrier|intval}"
						data-id-carrier="{$line.id_carrier|intval}"
						data-tracking-number="{$line.tracking_number|htmlentities}"
						>
 							<i class="fa fa-pencil"></i>
 							{l s='Edit' mod='ets_marketplace'}
 						</a>
					{/if*}
				</td>
			</tr>
			{/foreach}
		</tbody>
	</table>

	<!-- shipping update modal -->
	<div class="modal fade" id="modal-shipping">
		<div class="modal-dialog">
			<form method="post" action="{$link->getAdminLink('AdminOrders', true, [], ['id_order' => $order->id|intval, 'vieworder' => 1])|escape:'html':'UTF-8'}">
				<input type="hidden" name="submitShippingNumber" id="submitShippingNumber" value="1" />
				<input type="hidden" name="id_order_carrier" id="id_order_carrier" />
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="{l s='Close' mod='ets_marketplace'}"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">{l s='Edit shipping details' mod='ets_marketplace'}</h4>
					</div>
					<div class="modal-body">
						<div class="container-fluid">
							{if !$recalculate_shipping_cost}
							<div class="alert alert-info">
							{l s='Please note that carrier change will not recalculate your shipping costs, if you want to change this please visit Shop Parameters > Order Settings' mod='ets_marketplace'}
							</div>
							{/if}
							<div class="form-group">
								<div class="col-lg-5">{l s='Tracking number' mod='ets_marketplace'}</div>
								<div class="col-lg-7"><input type="text" name="shipping_tracking_number" id="shipping_tracking_number" /></div>
							</div>
							<div class="form-group">
								<div class="col-lg-5">{l s='Carrier' mod='ets_marketplace'}</div>
								<div class="col-lg-7">
									<select name="shipping_carrier" id="shipping_carrier">
										{foreach from=$carrier_list item=carrier}
											<option value="{$carrier.id_carrier|intval}">{$carrier.name|escape:'html':'UTF-8'} {if isset($carrier.delay)}({$carrier.delay|escape:'html':'UTF-8'}){/if}</option>
										{/foreach}
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">{l s='Cancel' mod='ets_marketplace'}</button>
						<button type="submit" class="btn btn-primary">{l s='Update' mod='ets_marketplace'}</button>
					</div>
				</div>
			</form>
		</div>
	</div>
	<!-- END shipping update modal -->
</div>
