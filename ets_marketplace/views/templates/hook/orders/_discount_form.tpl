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
<div class="form-horizontal well">
	<div class="form-group">
		<label class="control-label col-lg-3">
			{l s='Name' mod='ets_marketplace'}
		</label>
		<div class="col-lg-9">
			<input class="form-control" type="text" name="discount_name" value="" />
		</div>
	</div>

	<div class="form-group">
		<label class="control-label col-lg-3">
			{l s='Type' mod='ets_marketplace'}
		</label>
		<div class="col-lg-9">
			<select class="form-control" name="discount_type" id="discount_type">
				<option value="1">{l s='Percentage' mod='ets_marketplace'}</option>
				<option value="2">{l s='Amount' mod='ets_marketplace'}</option>
				<option value="3">{l s='Free shipping' mod='ets_marketplace'}</option>
			</select>
		</div>
	</div>

	<div id="discount_value_field" class="form-group">
		<label class="control-label col-lg-3">
			{l s='Value' mod='ets_marketplace'}
		</label>
		<div class="col-lg-9">
			<div class="input-group">
				<div class="input-group-addon">
					<span id="discount_currency_sign" style="display: none;">{$currency->sign|escape:'html':'UTF-8'}</span>
					<span id="discount_percent_symbol">%</span>
				</div>
				<input class="form-control" type="text" name="discount_value"/>
			</div>
			<p class="text-muted" id="discount_value_help" style="display: none;">
				{l s='This value must include taxes.' mod='ets_marketplace'}
			</p>
		</div>
	</div>

	{if $order->hasInvoice()}
	<div class="form-group">
		<label class="control-label col-lg-3">
			{l s='Invoice' mod='ets_marketplace'}
		</label>
		<div class="col-lg-9">
			<select name="discount_invoice">
				{foreach from=$invoices_collection item=invoice}
    				<option value="{$invoice->id|intval}" selected="selected">
    					{$invoice->getInvoiceNumberFormatted($current_id_lang)|escape:'html':'UTF-8'} - {Tools::displayPrice($invoice->total_paid_tax_incl,$currency)|escape:'html':'UTF-8'}
    				</option>
				{/foreach}
			</select>
		</div>
	</div>

	<div class="form-group">
		<div class="col-lg-9 col-lg-offset-3">
			<p class="checkbox">
				<label class="control-label" for="discount_all_invoices">
					<input type="checkbox" name="discount_all_invoices" id="discount_all_invoices" value="1" />
					{l s='Apply on all membership' mod='ets_marketplace'}
				</label>
			</p>
			<p class="help-block">
				{l s='If you choose to create this discount for all membership, only one discount will be created per order invoice.' mod='ets_marketplace'}
			</p>
		</div>
	</div>
	{/if}

	<div class="row">
		<div class="col-lg-9 col-lg-offset-3">
			<button class="btn btn-default" type="button" id="cancel_add_voucher">
				<i class="fa fa-remove text-danger"></i>
				{l s='Cancel' mod='ets_marketplace'}
			</button>
			<button class="btn btn-default" type="submit" name="submitNewVoucher">
				<i class="fa fa-ok text-success"></i>
				{l s='Add' mod='ets_marketplace'}
			</button>
		</div>
	</div>
</div>
