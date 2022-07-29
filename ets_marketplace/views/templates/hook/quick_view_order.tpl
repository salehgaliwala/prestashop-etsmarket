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
<div class="quick-view-order" id="quick-view-order">
    {assign var="hook_invoice" value={hook h="displayInvoice" id_order=$order->id}}
    {assign var="order_documents" value=$order->getDocuments()}
    {assign var="order_shipping" value=$order->getShipping()}
    {assign var="order_return" value=$order->getReturn()}
        <div class="header_poup">
            <div class="group_action">
                {if $order->invoice_number}
                    <a class="order_file" title="{l s='Download invoice as PDF file' mod='ets_marketplace'}" href="{$link->getAdminLink('AdminPdf',true)|escape:'html':'UTF-8'}&submitAction=generateInvoicePDF&id_order={$order->id|intval}">
                        <i class="icon-file-text"></i>
                    </a>
                {/if}
                {if $order->delivery_number}
                    <a class="order_file" title="{l s='Download delivery slip as PDF file' mod='ets_marketplace'}" href="{$link->getAdminLink('AdminPdf',true)|escape:'html':'UTF-8'}&submitAction=generateDeliverySlipPDF&id_order={$order->id|intval}"><i class="icon-truck"></i></a>
                {/if}
                <a class="order_print" title="{l s='Print this order' mod='ets_marketplace'}" href="javascript:window.print();"><i class="icon-print"></i></a>
            </div>
            {l s='Order details' mod='ets_marketplace'}<span class="id_order">(<a title="{l s='View order' mod='ets_marketplace'}" href="{$link->getAdminLink('AdminOrders')|escape:'html':'UTF-8'}&id_order={$order->id|intval}&vieworder">#{$order->id|intval} {if $order->reference != ''}| {$order->reference|escape:'html':'UTF-8'} {/if}</a>)</span>
        </div>
    <div class="row form-group main-order">
    <div class="col-sm-6">
        <span><strong><i class="icon-calendar" aria-hidden="true"></i> {l s='Date' mod='ets_marketplace'}</strong>: {$order->date_add|escape:'html':'UTF-8'}</span>
    </div>
    <div class="col-sm-6">
        <span><strong><i class="icon-user" aria-hidden="true"></i> {l s='Customer' mod='ets_marketplace'}</strong>: {if ($customer->isGuest())}{l s='This order has been placed by a guest.' mod='ets_marketplace'}{else}{$customer->firstname|escape:'html':'UTF-8'}&nbsp;{$customer->lastname|escape:'html':'UTF-8'}{/if}</span>
    </div>
    <div class="col-sm-6">
        <span><strong><i class="icon-credit-card" aria-hidden="true"></i> {l s='Payment method' mod='ets_marketplace'}</strong>: {$order->payment|escape:'html':'UTF-8'}</span>
    </div>
    
    <div class="col-sm-6">
        <span><strong><i class="icon-envelope-o" aria-hidden="true"></i> {l s='Email' mod='ets_marketplace'}</strong>: {$customer->email|escape:'html':'UTF-8'}</span>
    </div>
    <div class="col-sm-6">
        <span><strong><i class="icon-clock-o" aria-hidden="true"></i> {l s='Order status' mod='ets_marketplace'}</strong>: {$order_state->name|escape:'html':'UTF-8'}</span>
    </div>
    <div class="col-sm-6">
        <span><strong><i class="icon-phone" aria-hidden="true"></i> {l s='Phone number' mod='ets_marketplace'}</strong>: {if $addresses.delivery->phone}{$addresses.delivery->phone|escape:'html':'UTF-8'}{elseif $addresses.delivery->phone_mobile}{$addresses.delivery->phone_mobile|escape:'html':'UTF-8'}{else}--{/if}</span>
    </div>
    </div>
    <div class="row" id="start_products">
    <div class="col-lg-12">
      <form class="container-command-top-spacing" action="" method="post" onsubmit="return orderDeleteProduct('{l s='This product cannot be returned.' mod='ets_marketplace'}', '{l s='Quantity to cancel is greater than available quantity.' mod='ets_marketplace'}');">
        <input type="hidden" name="id_order" value="{$order->id|escape:'html':'UTF-8'}" />
        <div style="display: none">
          <input type="hidden" value="{$order->getWarehouseList()|implode|escape:'html':'UTF-8'}" id="warehouse_list" />
        </div>
        <div class="prdouct-list">
          <div id="refundForm">
          </div>
          {capture "TaxMethod"}
            {if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
              {l s='Tax excluded' mod='ets_marketplace'}
            {else}
              {l s='Tax included' mod='ets_marketplace'}
            {/if}
          {/capture}
          {if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
            <input type="hidden" name="TaxMethod" value="0" />
          {else}
            <input type="hidden" name="TaxMethod" value="1" />
          {/if}
          <div class="table-responsive">
            <table class="table" id="orderProducts">
              <thead>
                <tr>
                  <th></th>
                  <th><span class="title_box ">{l s='Product' mod='ets_marketplace'}</span></th>
                  <th>
                    <span class="title_box ">{l s='Price per unit' mod='ets_marketplace'}</span>
                    <small class="text-muted">{$smarty.capture.TaxMethod|escape:'html':'UTF-8'}</small>
                  </th>
                  <th class="text-center"><span class="title_box ">{l s='Qty' mod='ets_marketplace'}</span></th>
                  {if $display_warehouse}<th><span class="title_box ">{l s='Warehouse' mod='ets_marketplace'}</span></th>{/if}
                  {if ($order->hasBeenPaid())}<th class="text-center"><span class="title_box ">{l s='Refunded' mod='ets_marketplace'}</span></th>{/if}
                  {if ($order->hasBeenDelivered() || $order->hasProductReturned())}
                    <th class="text-center"><span class="title_box ">{l s='Returned' mod='ets_marketplace'}</span></th>
                  {/if}
                  {if $stock_location_is_available}<th class="text-center"><span class="title_box ">{l s='Stock location' mod='ets_marketplace'}</span></th>{/if}
                  {if $stock_management}<th class="text-center"><span class="title_box ">{l s='Available quantity' mod='ets_marketplace'}</span></th>{/if}
                  <th>
                    <span class="title_box ">{l s='Total' mod='ets_marketplace'}</span>
                    <small class="text-muted">{$smarty.capture.TaxMethod|escape:'html':'UTF-8'}</small>
                  </th>
                  <th style="display: none;" class="add_product_fields"></th>
                  <th style="display: none;" class="edit_product_fields"></th>
                  <th style="display: none;" class="standard_refund_fields">
                    <i class="icon-minus-sign"></i>
                    {if ($order->hasBeenDelivered() || $order->hasBeenShipped())}
                      {l s='Return' mod='ets_marketplace'}
                    {elseif ($order->hasBeenPaid())}
                      {l s='Refund' mod='ets_marketplace'}
                    {else}
                      {l s='Cancel' mod='ets_marketplace'}
                    {/if}
                  </th>
                  <th style="display:none" class="partial_refund_fields">
                    <span class="title_box ">{l s='Partial refund' mod='ets_marketplace'}</span>
                  </th>
                </tr>
              </thead>
              <tbody>
              {foreach from=$products item=product key=k}
                {* Include customized datas partial *}
                {include file='modules/ets_marketplace/views/templates/hook/orders/_customized_data.tpl'}
                {* Include product line partial *}
                {include file='modules/ets_marketplace/views/templates/hook/orders/_product_line.tpl'}
              {/foreach}
              </tbody>
            </table>
          </div>
          <div class="clear">&nbsp;</div>
          <div class="row">
            <div class="col-xs-6">
              
            </div>
            <div class="col-xs-6">
              <div class="panel panel-vouchers" style="{if !sizeof($discounts)}display:none;{/if}">
                {if (sizeof($discounts))}
                <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>
                          <span class="title_box ">
                            {l s='Discount name' mod='ets_marketplace'}
                          </span>
                        </th>
                        <th>
                          <span class="title_box ">
                            {l s='Value' mod='ets_marketplace'}
                          </span>
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                      {foreach from=$discounts item=discount}
                      <tr>
                        <td>{$discount['name']|escape:'html':'UTF-8'}</td>
                        <td>
                        {if $discount['value'] != 0.00}
                          -
                        {/if}
                        {Tools::displayPrice($discount['value'],$currency)|escape:'html':'UTF-8'}
                        </td>
                        
                      </tr>
                      {/foreach}
                    </tbody>
                  </table>
                </div>
                <div class="current-edit" id="voucher_form" style="display:none;">
                  {include file='modules/ets_marketplace/views/templates/hook/orders/_discount_form.tpl'}
                </div>
                {/if}
              </div>
              <div class="panel panel-total">
                <div class="table-responsive">
                  <table class="table">
                    {* Assign order price *}
                    {if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
                      {assign var=order_product_price value=($order->total_products)}
                      {assign var=order_discount_price value=$order->total_discounts_tax_excl}
                      {assign var=order_wrapping_price value=$order->total_wrapping_tax_excl}
                      {assign var=order_shipping_price value=$order->total_shipping_tax_excl}
                      {assign var=shipping_refundable value=$shipping_refundable_tax_excl}
                    {else}
                      {assign var=order_product_price value=$order->total_products_wt}
                      {assign var=order_discount_price value=$order->total_discounts_tax_incl}
                      {assign var=order_wrapping_price value=$order->total_wrapping_tax_incl}
                      {assign var=order_shipping_price value=$order->total_shipping_tax_incl}
                      {assign var=shipping_refundable value=$shipping_refundable_tax_incl}
                    {/if}
                    <tr id="total_products">
                      <td class="text-right"><strong>{l s='Products:' mod='ets_marketplace'}</strong></td>
                      <td class="amount text-right nowrap">
                        {Tools::displayPrice($order_product_price,$currency)|escape:'html':'UTF-8'}
                      </td>
                      <td class="partial_refund_fields current-edit" style="display:none;"></td>
                    </tr>
                    <tr id="total_discounts" {if $order->total_discounts_tax_incl == 0}style="display: none;"{/if}>
                      <td class="text-right"><strong>{l s='Discounts:' mod='ets_marketplace'}</strong></td>
                      <td class="amount text-right nowrap">
                        -{Tools::displayPrice($order_discount_price,$currency)|escape:'html':'UTF-8'}
                      </td>
                      <td class="partial_refund_fields current-edit" style="display:none;"></td>
                    </tr>
                    <tr id="total_wrapping" {if $order->total_wrapping_tax_incl == 0}style="display: none;"{/if}>
                      <td class="text-right"><strong>{l s='Wrapping:' mod='ets_marketplace'}</strong></td>
                      <td class="amount text-right nowrap">
                        {Tools::displayPrice($order_wrapping_price,$currency)|escape:'html':'UTF-8'}
                      </td>
                      <td class="partial_refund_fields current-edit" style="display:none;"></td>
                    </tr>
                    <tr id="total_shipping">
                      <td class="text-right"><strong>{l s='Shipping:' mod='ets_marketplace'}</strong></td>
                      <td class="amount text-right nowrap" >
                        {Tools::displayPrice($order_shipping_price,$currency)|escape:'html':'UTF-8'}
                      </td>
                      <td class="partial_refund_fields current-edit" style="display:none;">
                        <div class="input-group">
                          <div class="input-group-addon">
                            {$currency->sign|escape:'html':'UTF-8'}
                          </div>
                          <input type="text" name="partialRefundShippingCost" value="0" />
                        </div>
                      </td>
                    </tr>
                    {if isset($payment_fee)}
                        <tr id="total_payment_fee">
                          <td class="text-right"><strong>{l s='Payment fee:' mod='ets_marketplace'}</strong></td>
                          <td class="amount text-right nowrap" >{$payment_fee|escape:'html':'UTF-8'}</td>
                          <td class="partial_refund_fields current-edit" style="display:none;"></td>
                        </tr>
                    {/if}
                    {if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC) && $order->total_paid_tax_incl-$order->total_paid_tax_excl >0}
                    <tr id="total_taxes">
                      <td class="text-right"><strong>{l s='Taxes:' mod='ets_marketplace'}</strong></td>
                      <td class="amount text-right nowrap" >{Tools::displayPrice(($order->total_paid_tax_incl-$order->total_paid_tax_excl))|escape:'html':'UTF-8'}</td>
                      <td class="partial_refund_fields current-edit" style="display:none;"></td>
                    </tr>
                    {/if}
                    {assign var=order_total_price value=$order->total_paid_tax_incl}
                    <tr id="total_order">
                      <td class="text-right"><strong>{l s='Total:' mod='ets_marketplace'}</strong></td>
                      <td class="amount text-right nowrap">
                        <strong>{Tools::displayPrice($order_total_price,$currency)|escape:'html':'UTF-8'}</strong>
                      </td>
                      <td class="partial_refund_fields current-edit" style="display:none;"></td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
          <div style="display: none;" class="standard_refund_fields form-horizontal panel">
            <div class="form-group">
              {if ($order->hasBeenDelivered() && Configuration::get('PS_ORDER_RETURN'))}
              <p class="checkbox">
                <label for="reinjectQuantities">
                  <input type="checkbox" id="reinjectQuantities" name="reinjectQuantities" />
                  {l s='Re-stock products' mod='ets_marketplace'}
                </label>
              </p>
              {/if}
              {if ((!$order->hasBeenDelivered() && $order->hasBeenPaid()) || ($order->hasBeenDelivered() && Configuration::get('PS_ORDER_RETURN')))}
              <p class="checkbox">
                <label for="generateCreditSlip">
                  <input type="checkbox" id="generateCreditSlip" name="generateCreditSlip" onclick="toggleShippingCost()" />
                  {l s='Generate a credit slip' mod='ets_marketplace'}
                </label>
              </p>
              <p class="checkbox">
                <label for="generateDiscount">
                  <input type="checkbox" id="generateDiscount" name="generateDiscount" onclick="toggleShippingCost()" />
                  {l s='Generate a voucher' mod='ets_marketplace'}
                </label>
              </p>
              <p class="checkbox" id="spanShippingBack" style="display:none;">
                <label for="shippingBack">
                  <input type="checkbox" id="shippingBack" name="shippingBack" />
                  {l s='Repay shipping costs' mod='ets_marketplace'}
                </label>
              </p>
              {if $order->total_discounts_tax_excl > 0 || $order->total_discounts_tax_incl > 0}
              <br/><p>{l s='This order has been partially paid by voucher. Choose the amount you want to refund:' mod='ets_marketplace'}</p>
              <p class="radio">
                <label id="lab_refund_total_1" for="refund_total_1">
                  <input type="radio" value="0" name="refund_total_voucher_off" id="refund_total_1" checked="checked" />
                  {l s='Include amount of initial voucher: ' mod='ets_marketplace'}
                </label>
              </p>
              <p class="radio">
                <label id="lab_refund_total_2" for="refund_total_2">
                  <input type="radio" value="1" name="refund_total_voucher_off" id="refund_total_2"/>
                  {l s='Exclude amount of initial voucher: ' mod='ets_marketplace'}
                </label>
              </p>
              <div class="nowrap radio-inline">
                <label id="lab_refund_total_3" class="pull-left" for="refund_total_3">
                  {l s='Amount of your choice: ' mod='ets_marketplace'}
                  <input type="radio" value="2" name="refund_total_voucher_off" id="refund_total_3"/>
                </label>
                <div class="input-group col-lg-1 pull-left">
                  <div class="input-group-addon">
                    {$currency->sign|escape:'html':'UTF-8'}
                  </div>
                  <input type="text" class="input fixed-width-md" name="refund_total_voucher_choose" value="0"/>
                </div>
              </div>
              {/if}
            {/if}
            </div>
            {if (!$order->hasBeenDelivered() || ($order->hasBeenDelivered() && Configuration::get('PS_ORDER_RETURN')))}
            <div class="row">
              <input type="submit" name="cancelProduct" value="{if $order->hasBeenDelivered()}{l s='Return products' mod='ets_marketplace'}{elseif $order->hasBeenPaid()}{l s='Refund products' mod='ets_marketplace'}{else}{l s='Cancel products' mod='ets_marketplace'}{/if}" class="btn btn-default" />
            </div>
            {/if}
          </div>
          <div style="display:none;" class="partial_refund_fields">
            <p class="checkbox">
              <label for="reinjectQuantitiesRefund">
                <input type="checkbox" id="reinjectQuantitiesRefund" name="reinjectQuantities" />
                {l s='Re-stock products' mod='ets_marketplace'}
              </label>
            </p>
            <p class="checkbox">
              <label for="generateDiscountRefund">
                <input type="checkbox" id="generateDiscountRefund" name="generateDiscountRefund" onclick="toggleShippingCost()" />
                {l s='Generate a voucher' mod='ets_marketplace'}
              </label>
            </p>
            {if $order->total_discounts_tax_excl > 0 || $order->total_discounts_tax_incl > 0}
            <p>{l s='This order has been partially paid by voucher. Choose the amount you want to refund: ' mod='ets_marketplace'}</p>
            <p class="radio">
              <label id="lab_refund_1" for="refund_1">
                <input type="radio" value="0" name="refund_voucher_off" id="refund_1" checked="checked" />
                {l s='Product(s) price: ' mod='ets_marketplace'}
              </label>
            </p>
            <p class="radio">
              <label id="lab_refund_2" for="refund_2">
                <input type="radio" value="1" name="refund_voucher_off" id="refund_2"/>
                {l s='Product(s) price, excluding amount of initial voucher: ' mod='ets_marketplace'}
              </label>
            </p>
            <div class="nowrap radio-inline">
                <label id="lab_refund_3" class="pull-left" for="refund_3">
                  {l s='Amount of your choice: ' mod='ets_marketplace'}
                  <input type="radio" value="2" name="refund_voucher_off" id="refund_3"/>
                </label>
                <div class="input-group col-lg-1 pull-left">
                  <div class="input-group-addon">
                    {$currency->sign|escape:'html':'UTF-8'}
                  </div>
                  <input type="text" class="input fixed-width-md" name="refund_voucher_choose" value="0"/>
                </div>
              </div>
            {/if}
            <br/>
            <button type="submit" name="partialRefund" class="btn btn-default">
              <i class="icon-check"></i> {l s='Partial refund' mod='ets_marketplace'}
            </button>
          </div>
        </div>
      </form>
    </div>
    </div>
        <hr/>
    <div class="row">
    <div class="form-group">
        <span><i class="icon-truck"></i> <strong>{l s='Shipping method' mod='ets_marketplace'}</strong>: {$carrier->name|escape:'html':'UTF-8'}</span>
    </div>
    </div>
    <div class="row">
        <div class="customer-address">
            <div class="row">
            <div id="addressShipping" class="col-sm-6">
              <div class="title">
                   <i class="icon-map-marker"></i> <strong>{l s='Shipping address' mod='ets_marketplace'}</strong>
              </div>
              {if !$order->isVirtual()}
              <!-- Shipping address -->
                <div class="col-sm-6 address_order">
                  {displayAddressDetail address=$addresses.delivery newLine='<br />'}
                  {if $addresses.delivery->other}
                    <hr />{$addresses.delivery->other|escape:'html':'UTF-8'}<br />
                  {/if}
                </div>
                <div class="col-sm-6 hidden-print">
                  <div id="map-delivery-canvas" style="height: 100px"></div>
                </div>
              {/if}
            </div>
                  <!-- Invoice address -->
            <div id="invoiceShipping" class="col-sm-6">
              <div class="title">
                  <i class="icon-file-text"></i> <strong>{l s='Invoice address' mod='ets_marketplace'}</strong>
              </div>
              <div class="col-sm-12 address_order">
                {displayAddressDetail address=$addresses.invoice newLine='<br />'}
                {if $addresses.invoice->other}
                    <hr />{$addresses.invoice->other|escape:'html':'UTF-8'}<br />
                {/if}
              </div>
              <div class="col-sm-12 hidden-print">
                    <div id="map-invoice-canvas"></div>
              </div>
            </div>
          </div>
        </div>
    </div>
    <script>
      $('#tabAddresses a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
      });
    </script>
    {if (sizeof($messages))}
      <div class="row order-messages">
        <div class="messages-heading">
            <i class="icon-envelope"></i> {l s='Messages' mod='ets_marketplace'} <span class="badge">{sizeof($customer_thread_message)|escape:'html':'UTF-8'}</span>
        </div>
        {if (sizeof($messages))}
          <div class="panel panel-highlighted">
                <div class="message-item">
                  {foreach from=$messages item=message}
                    <div class="message-avatar">
                      <div class="avatar-md">
                        <i class="icon-user icon-2x"></i>
                      </div>
                    </div>
                    <div class="message-body">
                          <span class="message-date">&nbsp;<i class="icon-calendar"></i>
                            {dateFormat date=$message['date_add']} -
                          </span>
                          <h4 class="message-item-heading">
                            {if ($message['elastname']|escape:'html':'UTF-8')}{$message['efirstname']|escape:'html':'UTF-8'}
                              {$message['elastname']|escape:'html':'UTF-8'}{else}{$message['cfirstname']|escape:'html':'UTF-8'} {$message['clastname']|escape:'html':'UTF-8'}
                            {/if}
                            {if ($message['private'] == 1)}
                              <span class="badge badge-info">{l s='Private' mod='ets_marketplace'}</span>
                            {/if}
                          </h4>
                          <p class="message-item-text">
                            {$message['message']|escape:'html':'UTF-8'|nl2br}
                          </p>
                    </div>
                  {/foreach}
                </div>
          </div>
        {/if}
      </div>
    {/if}
</div>
<script type="text/javascript">
function PrintElem(elem)
{
var mywindow = window.open('', 'PRINT', 'height=400,width=600');

mywindow.document.write('<html><head><title>' + document.title  + '</title>');
mywindow.document.write('</head><body >');
mywindow.document.write('<h1>' + document.title  + '</h1>');
mywindow.document.write(document.getElementById(elem).innerHTML);
mywindow.document.write('</body></html>');

mywindow.document.close(); // necessary for IE >= 10
mywindow.focus(); // necessary for IE >= 10*/

mywindow.print();
mywindow.close();

return true;
}
</script>