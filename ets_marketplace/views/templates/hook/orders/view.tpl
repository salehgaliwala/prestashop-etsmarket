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
<script type="text/javascript">
  var admin_order_tab_link = "{$link->getAdminLink('AdminOrders')|addslashes|escape:'html':'UTF-8'}";
  var id_order = {$order->id|intval};
  var id_lang = {$current_id_lang|intval};
  var id_currency = {$order->id_currency|intval};
  var id_customer = {$order->id_customer|intval};
  {assign var=PS_TAX_ADDRESS_TYPE value=Configuration::get('PS_TAX_ADDRESS_TYPE')}
  var id_address = {$order->$PS_TAX_ADDRESS_TYPE|intval};
  var currency_sign = "{$currency->sign|escape:'html':'UTF-8'}";
  var currency_format = "{$currency->format|escape:'html':'UTF-8'}";
  var currency_blank = "{$currency->blank|escape:'html':'UTF-8'}";
  var priceDisplayPrecision = {$smarty.const._PS_PRICE_DISPLAY_PRECISION_|intval};
  var use_taxes = {if $order->getTaxCalculationMethod() == $smarty.const.PS_TAX_INC}true{else}false{/if};
  var stock_management = {$stock_management|intval};
  var txt_add_product_stock_issue = "{l s='Are you sure you want to add this quantity?' mod='ets_marketplace' js=1}";
  var txt_add_product_new_invoice = "{l s='Are you sure you want to create a new invoice?' mod='ets_marketplace' js=1}";
  var txt_add_product_no_product = "{l s='Error: No product has been selected' mod='ets_marketplace' js=1}";
  var txt_add_product_no_product_quantity = "{l s='Error: Quantity of products must be set' mod='ets_marketplace' js=1}";
  var txt_add_product_no_product_price = "{l s='Error: Product price must be set' mod='ets_marketplace' js=1}";
  var txt_confirm = "{l s='Are you sure?' mod='ets_marketplace' js=1}";
  var statesShipped = new Array();
  var has_voucher = {if count($discounts)}1{else}0{/if};
  {foreach from=$states item=state}
  {if (isset($currentState->shipped) && !$currentState->shipped && $state['shipped'])}
    statesShipped.push({$state['id_order_state']|intval});
  {/if}
  {/foreach}
  var order_discount_price = {if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
          {$order->total_discounts_tax_excl|floatval}
          {else}
          {$order->total_discounts_tax_incl|floatval}
          {/if};

  var errorRefund = "{l s='Error. You cannot refund a negative amount.' mod='ets_marketplace'}";
</script>

{assign var="hook_invoice" value={hook h="displayInvoice" id_order=$order->id}}
{if ($hook_invoice)}
  <div>{$hook_invoice nofilter}</div>
{/if}
{assign var="order_documents" value=$order->getDocuments()}
{assign var="order_shipping" value=$order->getShipping()}
{assign var="order_return" value=$order->getReturn()}

<div class="panel kpi-container">
  <div class="row">
    <div class="col-xs-6 col-sm-3 box-stats color3" >
      <div class="kpi-content">
        <i class="fa fa-calendar-empty"></i>
        <span class="title">{l s='Date' mod='ets_marketplace'}</span>
        <span class="value">{dateFormat date=$order->date_add full=false}</span>
      </div>
    </div>
    <div class="col-xs-6 col-sm-3 box-stats color4" >
      <div class="kpi-content">
        <i class="fa fa-money"></i>
        <span class="title">{l s='Total' mod='ets_marketplace'}</span>
        <span class="value price">{displayPrice price=$order->total_paid_tax_incl currency=$currency->id}</span>
      </div>
    </div>
    <div class="col-xs-6 col-sm-3 box-stats color2" >
      <div class="kpi-content">
        <i class="fa fa-comments"></i>
        <span class="title">{l s='Messages' mod='ets_marketplace'}</span>
        <span class="value">{sizeof($customer_thread_message)|escape:'html':'UTF-8'}</span>
      </div>
    </div>
    <div class="col-xs-6 col-sm-3 box-stats color1" >
        <div class="kpi-content">
          <i class="fa fa-book"></i>
          <span class="title">{l s='Products' mod='ets_marketplace'}</span>
          <span class="value">{sizeof($products)|escape:'html':'UTF-8'}</span>
        </div>
    </div>
  </div>
</div>
<div class="row kpi-content">
  <div class="col-lg-7">
    <div class="panel">
      <div class="panel-heading">
        <i class="fa fa-credit-card"></i>
        {l s='Order' mod='ets_marketplace'}
        <span class="badge">{$order->reference|escape:'html':'UTF-8'}</span>
        <span class="badge">#{$order->id|intval}</span>
        <div class="panel-heading-action kpi_panel-heading-action">
          <div class="btn-group">
            <a class="btn btn-default{if !$previousOrder} disabled{/if}" href="{$link->getModuleLink('ets_marketplace','orders',['id_order' => $previousOrder|intval])|escape:'html':'UTF-8'}">
              <i class="fa fa-backward"></i>
            </a>
            <a class="btn btn-default{if !$nextOrder} disabled{/if}" href="{$link->getModuleLink('ets_marketplace','orders',['id_order' => $nextOrder|intval])|escape:'html':'UTF-8'}">
              <i class="fa fa-forward"></i>
            </a>
          </div>
        </div>
      </div>
      <!-- Orders Actions -->
      <div class="well hidden-print kpi_printorder">
        <a class="btn btn-default" href="javascript:window.print()">
          <i class="fa fa-print"></i>
          {l s='Print order' mod='ets_marketplace'}
        </a>
        {if Configuration::get('PS_INVOICE') && count($invoices_collection) && $order->invoice_number}
            <a data-selenium-id="view_invoice" class="btn btn-default _blank" href="{$link->getModuleLink('ets_marketplace','pdf',['submitAction' => 'generateInvoicePDF', 'id_order' => $order->id|intval])|escape:'html':'UTF-8'}">
              <i class="fa fa-file"></i>
              {l s='View invoice' mod='ets_marketplace'}
            </a>
          {else}
            <span class="span label label-inactive">
              <i class="fa fa-remove"></i>
              {l s='No invoice' mod='ets_marketplace'}
            </span>
          {/if}
          &nbsp;
          {if $order->delivery_number}
            <a class="btn btn-default _blank"  href="{$link->getModuleLink('ets_marketplace','pdf', ['submitAction' => 'generateDeliverySlipPDF', 'id_order' => $order->id|intval])|escape:'html':'UTF-8'}">
              <i class="fa fa-truck"></i>
              {l s='View delivery slip' mod='ets_marketplace'}
            </a>
          {else}
            <span class="span label label-inactive">
              <i class="fa fa-remove"></i>
              {l s='No delivery slip' mod='ets_marketplace'}
            </span>
          {/if}
        {hook h='displayBackOfficeOrderActions' id_order=$order->id|intval}
      </div>
      <!-- Tab nav -->
      <ul class="nav nav-tabs" id="tabOrder">
        {$HOOK_TAB_ORDER nofilter}
        <li class="active">
          <a href="#status">
            <i class="fa fa-time"></i>
            {l s='Status' mod='ets_marketplace'} <span class="badge">{$history|@count|intval}</span>
          </a>
        </li>
        <li>
          <a href="#documents">
            <i class="fa fa-file-text"></i>
            {l s='Documents' mod='ets_marketplace'} <span class="badge">{$order_documents|@count|intval}</span>
          </a>
        </li>
      </ul>
      <!-- Tab content -->
      <div class="tab-content panel">
        {$HOOK_CONTENT_ORDER nofilter}
        <!-- Tab status -->
        <div class="tab-pane active" id="status">
         
          <!-- History of status -->
          <div class="table-responsive">
            <table class="table history-status row-margin-bottom">
              <tbody>
              {foreach from=$history item=row key=key}
                {if ($key == 0)}
                  <tr>
                    <td style="background-color:{$row['color']|escape:'html':'UTF-8'}"><img src="{$link_base|escape:'html':'UTF-8'}/img/os/{$row['id_order_state']|intval}.gif" width="16" height="16" alt="{$row['ostate_name']|stripslashes}" /></td>
                    <td style="background-color:{$row['color']|escape:'html':'UTF-8'};color:{$row['text-color']|escape:'html':'UTF-8'}">{$row['ostate_name']|stripslashes|escape:'html':'UTF-8'}</td>
                    <td style="background-color:{$row['color']|escape:'html':'UTF-8'};color:{$row['text-color']|escape:'html':'UTF-8'}">{dateFormat date=$row['date_add'] full=true}</td>
                    <td style="background-color:{$row['color']|escape:'html':'UTF-8'};color:{$row['text-color']|escape:'html':'UTF-8'}" class="text-right">
                      
                    </td>
                  </tr>
                {else}
                  <tr>
                    <td><img src="{$link_base|escape:'html':'UTF-8'}/img/os/{$row['id_order_state']|intval}.gif" width="16" height="16" /></td>
                    <td>{$row['ostate_name']|stripslashes|escape:'html':'UTF-8'}</td>
                    <td>{dateFormat date=$row['date_add'] full=true}</td>
                    <td class="text-right">
    
                    </td>
                  </tr>
                {/if}
              {/foreach}
              </tbody>
            </table>
          </div>
          <!-- Change status form -->
          {if $ETS_MP_SELLER_CAN_CHANGE_ORDER_STATUS && $states}
            <form action="" method="post" class="form-horizontal well hidden-print">
              <div class="row">
                <div class="col-lg-12">
                  <select id="id_order_state" class="chosen form-control" name="id_order_state">
                    {foreach from=$states item=state}
                      <option value="{$state['id_order_state']|intval}"{if isset($currentState) && $state['id_order_state'] == $currentState->id} selected="selected"{/if}>{$state['name']|escape}</option>
                    {/foreach}
                  </select>
                  <input type="hidden" name="id_order" value="{$order->id|intval}" />
                </div>
                <div class="col-lg-12">
                  <input type="hidden" name="submitChangeState" value="1"/>
                  <button type="submit" name="submitChangeState" id="submit_state" class="btn btn-primary">
                    {l s='Update status' mod='ets_marketplace'}
                  </button>
                </div>
              </div>
            </form>
          {/if}
        </div>
        <!-- Tab documents -->
        <div class="tab-pane" id="documents">
          
          {* Include document template *}
          {include file='modules/ets_marketplace/views/templates/hook/orders/_documents.tpl'}
        </div>
      </div>
      <hr />
      <!-- Tab nav -->
      <ul class="nav nav-tabs myTab_order" id="myTab">
        {$HOOK_TAB_SHIP nofilter}
        <li class="active">
          <a href="#shipping">
            <i class="fa fa-truck "></i>
            {l s='Shipping' mod='ets_marketplace'} <span class="badge">{$order_shipping|@count|intval}</span>
          </a>
        </li>
        <li>
          <a href="#returns">
            <i class="fa fa-undo"></i>
            {l s='Merchandise Returns' mod='ets_marketplace'} <span class="badge">{$order_return|@count|intval}</span>
          </a>
        </li>
      </ul>
      <!-- Tab content -->
      <div class="tab-content panel">
        {$HOOK_CONTENT_SHIP nofilter}
        <!-- Tab shipping -->
        <div class="tab-pane active" id="shipping">
          <!-- Shipping block -->
          {if !$order->isVirtual()}
            <div class="form-horizontal">
              {if $order->gift_message}
                <div class="form-group">
                  <label class="control-label col-lg-3">{l s='Message' mod='ets_marketplace'}</label>
                  <div class="col-lg-9">
                    <p class="form-control-static">{$order->gift_message|nl2br nofilter}</p>
                  </div>
                </div>
              {/if}
              {include file='modules/ets_marketplace/views/templates/hook/orders/_shipping.tpl'}
              {if $carrierModuleCall}
                {$carrierModuleCall nofilter}
              {/if}
              <hr />
              {if $order->recyclable}
                <span class="label label-success"><i class="fa fa-check"></i> {l s='Recycled packaging' mod='ets_marketplace'}</span>
              {else}
                <span class="label label-inactive"><i class="fa fa-remove"></i> {l s='Recycled packaging' mod='ets_marketplace'}</span>
              {/if}

              {if $order->gift}
                <span class="label label-success"><i class="fa fa-check"></i> {l s='Gift wrapping' mod='ets_marketplace'}</span>
              {else}
                <span class="label label-inactive"><i class="fa fa-remove"></i> {l s='Gift wrapping' mod='ets_marketplace'}</span>
              {/if}
            </div>
          {/if}
        </div>
        <!-- Tab returns -->
        <div class="tab-pane" id="returns">
          {if !$order->isVirtual()}
            
            <!-- Return block -->
            {if $order_return|count > 0}
              <div class="table-responsive">
                <table class="table">
                  <thead>
                  <tr>
                    <th><span class="title_box ">{l s='Date' mod='ets_marketplace'}</span></th>
                    <th><span class="title_box ">{l s='Type' mod='ets_marketplace'}</span></th>
                    <th><span class="title_box ">{l s='Carrier' mod='ets_marketplace'}</span></th>
                    <th><span class="title_box ">{l s='Tracking number' mod='ets_marketplace'}</span></th>
                  </tr>
                  </thead>
                  <tbody>
                  {foreach from=$order_return item=line}
                    <tr>
                      <td>{$line.date_add|escape:'html':'UTF-8'}</td>
                      <td>{$line.type|escape:'html':'UTF-8'}</td>
                      <td>{$line.state_name|escape:'html':'UTF-8'}</td>
                      <td class="actions">
                        <span class="shipping_number_show">{if isset($line.url) && isset($line.tracking_number)}<a href="{$line.url|replace:'@':$line.tracking_number|escape:'html':'UTF-8'}">{$line.tracking_number|escape:'html':'UTF-8'}</a>{elseif isset($line.tracking_number)}{$line.tracking_number|escape:'html':'UTF-8'}{/if}</span>
                        {if $line.can_edit}
                          <form method="post" action="{$link->getAdminLink('AdminOrders')|escape:'html':'UTF-8'}&amp;vieworder&amp;id_order={$order->id|intval}&amp;id_order_invoice={if $line.id_order_invoice}{$line.id_order_invoice|intval}{else}0{/if}&amp;id_carrier={if $line.id_carrier}{$line.id_carrier|escape:'html':'UTF-8'}{else}0{/if}">
                          <span class="shipping_number_edit" style="display:none;">
                            <button type="button" name="tracking_number">
                              {$line.tracking_number|htmlentities|escape:'html':'UTF-8'}
                            </button>
                            <button type="submit" class="btn btn-default" name="submitShippingNumber">
                              {l s='Update' mod='ets_marketplace'}
                            </button>
                          </span>
                            <button href="#" class="edit_shipping_number_link">
                              <i class="fa fa-pencil"></i>
                              {l s='Edit' mod='ets_marketplace'}
                            </button>
                            <button href="#" class="cancel_shipping_number_link" style="display: none;">
                              <i class="fa fa-remove"></i>
                              {l s='Cancel' mod='ets_marketplace'}
                            </button>
                          </form>
                        {/if}
                      </td>
                    </tr>
                  {/foreach}
                  </tbody>
                </table>
              </div>
            {else}
              <div class="list-empty hidden-print">
                <div class="list-empty-msg">
                  <i class="fa fa-warning-sign list-empty-icon"></i>
                  {l s='No merchandise returned yet' mod='ets_marketplace'}
                </div>
              </div>
            {/if}
            {if $carrierModuleCall}
              {$carrierModuleCall nofilter}
            {/if}
          {/if}
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-5">
    <!-- Customer informations -->
    <div class="panel">
      {if $order_customer->id && !$order_customer->isGuest()}
        <div class="panel-heading">
              <i class="fa fa-user"></i>
              {l s='Customer' mod='ets_marketplace'}
              <span class="customer">
                  <strong>
                    {if Configuration::get('PS_B2B_ENABLE')}{$order_customer->company|escape:'html':'UTF-8'} - {/if}
                    {$gender->name|escape:'html':'UTF-8'}
                    {$order_customer->firstname|escape:'html':'UTF-8'}
                    {$order_customer->lastname|escape:'html':'UTF-8'}
                  </strong>
              </span>
              <span class="badge">
                  {l s='#' mod='ets_marketplace'}{$order_customer->id|intval}
              </span>
        </div>
        <div class="row">
          <div class="col-xs-12">
            {if (!$order_customer->isGuest())}
              <dl class="well list-detail">
                {if Configuration::get('ETS_MP_DISPLAY_CUSTOMER_EMAIL')}
                    <dt>{l s='Email' mod='ets_marketplace'}</dt>
                    <dd class="email"><i class="fa fa-envelope-o"></i> {$order_customer->email|escape:'html':'UTF-8'}</dd>
                {/if}
                <dt>{l s='Account registered' mod='ets_marketplace'}</dt>
                <dd class="text-muted"><i class="fa fa-calendar-o"></i> {dateFormat date=$order_customer->date_add full=true}</dd>
                <dt>{l s='Valid orders placed' mod='ets_marketplace'}</dt>
                <dd><span class="badge">{$customerStats['nb_orders']|intval}</span></dd>
                <dt>{l s='Total spent since registration' mod='ets_marketplace'}</dt>
                <dd><span class="badge badge-success">{displayPrice price=Tools::ps_round(Tools::convertPrice($customerStats['total_orders'], $currency), 2) currency=$currency->id}</span></dd>
                {if Configuration::get('PS_B2B_ENABLE')}
                  <dt>{l s='SIRET' mod='ets_marketplace'}</dt>
                  <dd>{$order_customer->siret|escape:'html':'UTF-8'}</dd>
                  <dt>{l s='APE' mod='ets_marketplace'}</dt>
                  <dd>{$order_customer->ape|escape:'html':'UTF-8'}</dd>
                {/if}
              </dl>
            {/if}
          </div>
        </div>
      {/if}
      <!-- Tab nav -->
      <div class="ets_mp_address_tab">
        <ul class="nav nav-tabs myTab_order" id="tabAddresses">
          <li class="active">
            <a href="#addressShipping">
              <i class="fa fa-truck"></i>
              {l s='Shipping address' mod='ets_marketplace'}
            </a>
          </li>
          <li>
            <a href="#addressInvoice">
              <i class="fa fa-file-text"></i>
              {l s='Invoice address' mod='ets_marketplace'}
            </a>
          </li>
        </ul>
        <!-- Tab content -->
        <div class="tab-content panel">
          <!-- Tab status -->
          <div class="tab-pane  in active" id="addressShipping">
            <!-- Addresses -->
            
            {if !$order->isVirtual()}
              <!-- Shipping address -->
              <h4 class="visible-print">{l s='Shipping address' mod='ets_marketplace'}</h4>
              {if $can_edit}
                <form class="form-horizontal hidden-print" method="post" action="{$link->getAdminLink('AdminOrders', true, [], ['vieworder' => 1, 'id_order' => $order->id|intval])|escape:'html':'UTF-8'}">
                  <div class="form-group">
                    <div class="col-lg-9">
                      <select name="id_address">
                        {foreach from=$customer_addresses item=address}
                          <option value="{$address['id_address']|intval}"
                                  {if $address['id_address'] == $order->id_address_delivery}
                            selected="selected"
                                  {/if}>
                            {$address['alias']|escape:'html':'UTF-8'} -
                            {$address['address1']|escape:'html':'UTF-8'}
                            {$address['postcode']|escape:'html':'UTF-8'}
                            {$address['city']|escape:'html':'UTF-8'}
                            {if !empty($address['state'])}
                              {$address['state']|escape:'html':'UTF-8'}
                            {/if},
                            {$address['country']|escape:'html':'UTF-8'}
                          </option>
                        {/foreach}
                      </select>
                    </div>
                    <div class="col-lg-3">
                      <button class="btn btn-default" type="submit" name="submitAddressShipping"><i class="fa fa-refresh"></i> {l s='Change' mod='ets_marketplace'}</button>
                    </div>
                  </div>
                </form>
              {/if}
              <div class="well">
                <div class="row">
                  <div class="col-sm-12">
                    {displayAddressDetail address=$addresses.delivery newLine='<br />'}
                    {if $addresses.delivery->other}
                      <hr />{$addresses.delivery->other|escape:'html':'UTF-8'}<br />
                    {/if}
                  </div>
                  <div class="col-sm-12 hidden-print">
                    <div id="map-delivery-canvas"></div>
                  </div>
                </div>
              </div>
            {/if}
          </div>
          <div class="tab-pane " id="addressInvoice">
            <!-- Invoice address -->
            <h4 class="visible-print">{l s='Invoice address' mod='ets_marketplace'}</h4>
            {if $can_edit}
              <form class="form-horizontal hidden-print" method="post" action="{$link->getAdminLink('AdminOrders', true, [], ['vieworder' => 1, 'id_order' => $order->id|intval])|escape:'html':'UTF-8'}">
                <div class="form-group">
                  <div class="col-lg-9">
                    <select name="id_address">
                      {foreach from=$customer_addresses item=address}
                        <option value="{$address['id_address']|intval}"
                                {if $address['id_address'] == $order->id_address_invoice}
                          selected="selected"
                                {/if}>
                          {$address['alias']|escape:'html':'UTF-8'} -
                          {$address['address1']|escape:'html':'UTF-8'}
                          {$address['postcode']|escape:'html':'UTF-8'}
                          {$address['city']|escape:'html':'UTF-8'}
                          {if !empty($address['state'])}
                            {$address['state']|escape:'html':'UTF-8'}
                          {/if},
                          {$address['country']|escape:'html':'UTF-8'}
                        </option>
                      {/foreach}
                    </select>
                  </div>
                  <div class="col-lg-3">
                    <button class="btn btn-default" type="submit" name="submitAddressInvoice"><i class="fa fa-refresh"></i> {l s='Change' mod='ets_marketplace'}</button>
                  </div>
                </div>
              </form>
            {/if}
            <div class="well">
              <div class="row">
                <div class="col-sm-12">
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
      </div>
    </div>
    {*start messages*}
    {if $ETS_MP_SELLER_MESSAGE_DISPLAYED}
      <div class="panel">
        <div class="panel-heading">
          <i class="fa fa-envelope"></i> {l s='Messages' mod='ets_marketplace'} <span class="badge cicle">{sizeof($customer_thread_message)|escape:'html':'UTF-8'}</span>
        </div>
        {if (sizeof($messages))}
          <div class="panel panel-highlighted">
            <div class="message-item">
              {foreach from=$messages item=message}
                <div class="message-avatar">
                  <div class="avatar-md">
                    <i class="fa fa-user fa fa-2x"></i>
                  </div>
                </div>
                <div class="message-body">

                      <span class="message-date">&nbsp;<i class="fa fa-calendar"></i>
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
                    {$message['message']|nl2br nofilter}
                  </p>
                </div>
              {/foreach}
            </div>
          </div>
        {/if}
        <div id="messages" class="well hidden-print">
          <form action="" method="post">
            <div id="message" class="form-horizontal">
              <div class="form-group row">
                <label class="control-label col-lg-12">{l s='Message' mod='ets_marketplace'}</label>
                <div class="col-lg-12">
                  <textarea id="txt_msg" class="textarea-autosize" name="message">{Tools::getValue('message')|escape:'html':'UTF-8'}</textarea>
                  <p id="nbchars"></p>
                </div>
              </div>
              <input type="hidden" name="id_order" value="{$order->id|intval}" />
              <input type="hidden" name="id_customer" value="{$order->id_customer|escape:'html':'UTF-8'}" />
              <input type="hidden" name="submitMessage" value="1" />
              <button type="submit" id="submitMessage" class="btn btn-primary" name="submitMessage">
                {l s='Send message' mod='ets_marketplace'}
              </button>
            </div>
          </form>
        </div>
      </div>
    {/if}
    {*end nessage*}
  </div>
</div>
<div class="row" id="start_products">
  <div class="col-lg-12">
    <form class="container-command-top-spacing" action="" method="post" onsubmit="return orderDeleteProduct('{l s='This product cannot be returned.' mod='ets_marketplace'}', '{l s='Quantity to cancel is greater than available quantity.' mod='ets_marketplace'}');">
      <input type="hidden" name="id_order" value="{$order->id|intval}" />
      <div style="display: none">
        <input type="hidden" value="{$order->getWarehouseList()|implode|escape:'html':'UTF-8'}" id="warehouse_list" />
      </div>

      <div class="panel">
        <div class="panel-heading">
          <i class="fa fa-shopping-cart"></i>
          {l s='Products' mod='ets_marketplace'} <span class="badge cicle">{$products|@count|intval}</span>
        </div>
        <div id="refundForm">
          <!--
            <a href="#" class="standard_refund"><img src="../img/admin/add.gif" alt="{l s='Process a standard refund' mod='ets_marketplace'}" /> {l s='Process a standard refund' mod='ets_marketplace'}</a>
            <a href="#" class="partial_refund"><img src="../img/admin/add.gif" alt="{l s='Process a partial refund' mod='ets_marketplace'}" /> {l s='Process a partial refund' mod='ets_marketplace'}</a>
          -->
        </div>

        {capture "TaxMethod"}
          {if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
            {l s='Tax excluded' mod='ets_marketplace'}
          {else}
            {l s='Tax included' mod='ets_marketplace'}
          {/if}
        {/capture}
        {if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
          <input type="hidden" name="TaxMethod" value="0">
        {else}
          <input type="hidden" name="TaxMethod" value="1">
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
                <i class="fa fa-minus-sign"></i>
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
              {if !$order->hasBeenDelivered()}
                <th></th>
              {/if}
            </tr>
            </thead>
            <tbody>
            {foreach from=$products item=product key=k}
              {* Include customized datas partial *}
              {include file='modules/ets_marketplace/views/templates/hook/orders/_customized_data.tpl'}
              {* Include product line partial *}
              {include file='modules/ets_marketplace/views/templates/hook/orders/_product_line.tpl'}
            {/foreach}
            {if $can_edit}
              {include file='modules/ets_marketplace/views/templates/hook/orders/_new_product.tpl'}
            {/if}
            </tbody>
          </table>
        </div>

        {if $can_edit}
          <div class="row-margin-bottom row-margin-top order_action">
            {if !$order->hasBeenDelivered()}
              <button type="button" id="add_product" class="btn btn-default">
                <i class="fa fa-plus-sign"></i>
                {l s='Add a product' mod='ets_marketplace'}
              </button>
            {/if}
            <button id="add_voucher" class="btn btn-default" type="button" >
              <i class="fa fa-ticket"></i>
              {l s='Add a new discount' mod='ets_marketplace'}
            </button>
          </div>
        {/if}
        <div class="clear">&nbsp;</div>
        <div class="row">
          <div class="col-xs-6">
            <div class="alert alert-warning">
              {* [1][/1] is for a HTML tag. *}
              {l s='For this customer group, prices are displayed as:' mod='ets_marketplace'}
              <strong>{$smarty.capture.TaxMethod|escape:'html':'UTF-8'}</strong>
              {if !Configuration::get('PS_ORDER_RETURN')}
                <br/><strong>{l s='Merchandise returns are disabled' mod='ets_marketplace'}</strong>
              {/if}
            </div>
          </div>
          <div class="col-xs-6">
            <div class="panel panel-vouchers" style="{if !sizeof($discounts)}display:none;{/if}">
              {if (sizeof($discounts) || $can_edit)}
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
                      {if $can_edit}
                        <th></th>
                      {/if}
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
                          {displayPrice price=$discount['value'] currency=$currency->id}
                        </td>
                        {if $can_edit}
                          <td>
                            <a href="">
                              <i class="fa fa-minus-sign"></i>
                              {l s='Delete voucher' mod='ets_marketplace'}
                            </a>
                          </td>
                        {/if}
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
            <div class="panel-total front_total_info">
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
                    <td class="text-right">{l s='Products:' mod='ets_marketplace'}</td>
                    <td class="amount text-right nowrap">
                      {displayPrice price=$order_product_price currency=$currency->id}
                    </td>
                    <td class="partial_refund_fields current-edit" style="display:none;"></td>
                  </tr>
                  <tr id="total_discounts" {if $order->total_discounts_tax_incl == 0}style="display: none;"{/if}>
                    <td class="text-right">{l s='Discounts' mod='ets_marketplace'}</td>
                    <td class="amount text-right nowrap">
                      -{displayPrice price=$order_discount_price currency=$currency->id}
                    </td>
                    <td class="partial_refund_fields current-edit" style="display:none;"></td>
                  </tr>
                  <tr id="total_wrapping" {if $order->total_wrapping_tax_incl == 0}style="display: none;"{/if}>
                    <td class="text-right">{l s='Wrapping' mod='ets_marketplace'}</td>
                    <td class="amount text-right nowrap">
                      {displayPrice price=$order_wrapping_price currency=$currency->id}
                    </td>
                    <td class="partial_refund_fields current-edit" style="display:none;"></td>
                  </tr>
                  <tr id="total_shipping">
                    <td class="text-right">{l s='Shipping' mod='ets_marketplace'}</td>
                    <td class="amount text-right nowrap" >
                      {displayPrice price=$order_shipping_price currency=$currency->id}
                    </td>
                    <td class="partial_refund_fields current-edit" style="display:none;">
                      <div class="input-group">
                        <div class="input-group-addon">
                          {$currency->sign|escape:'html':'UTF-8'}
                        </div>
                        <input type="text" name="partialRefundShippingCost" value="0" />
                      </div>
                      <p class="help-block"><i class="fa fa-warning-sign"></i> {l
                        s='(Max %s %s)'
                        sprintf=[Tools::displayPrice(Tools::ps_round($shipping_refundable, 2), $currency->id) , $smarty.capture.TaxMethod]
                        mod='ets_marketplace'
                        }
                      </p>
                    </td>
                  </tr>
                  {if ($order->getTaxCalculationMethod() == $smarty.const.PS_TAX_EXC)}
                    <tr id="total_taxes">
                      <td class="text-right">{l s='Taxes' mod='ets_marketplace'}</td>
                      <td class="amount text-right nowrap" >{displayPrice price=($order->total_paid_tax_incl-$order->total_paid_tax_excl) currency=$currency->id}</td>
                      <td class="partial_refund_fields current-edit" style="display:none;"></td>
                    </tr>
                  {/if}
                  {assign var=order_total_price value=$order->total_paid_tax_incl}
                  <tr id="total_order">
                    <td class="text-right"><strong>{l s='Total' mod='ets_marketplace'}</strong></td>
                    <td class="amount text-right nowrap">
                      <strong>{displayPrice price=$order_total_price currency=$currency->id}</strong>
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
            <i class="fa fa-check"></i> {l s='Partial refund' mod='ets_marketplace'}
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="row">
  <div class="col-lg-12">
    <!-- Sources block -->
    {if (sizeof($sources))}
      <div class="panel">
        <div class="panel-heading">
          <i class="fa fa-globe"></i>
          {l s='Sources' mod='ets_marketplace'} <span class="badge">{$sources|@count|intval}</span>
        </div>
        <ul {if sizeof($sources) > 3}style="height: 200px; overflow-y: scroll;"{/if}>
          {foreach from=$sources item=source}
            <li>
              {dateFormat date=$source['date_add'] full=true}<br />
              <b>{l s='From' mod='ets_marketplace'}</b>{if $source['http_referer'] != ''}<a href="{$source['http_referer']|escape:'html':'UTF-8'}">{parse_url($source['http_referer'], $smarty.const.PHP_URL_HOST)|regex_replace:'/^www./':''|escape:'html':'UTF-8'}</a>{else}-{/if}<br />
              <b>{l s='To' mod='ets_marketplace'}</b> <a href="http://{$source['request_uri']|escape:'html':'UTF-8'}">{$source['request_uri']|truncate:100:'...'|escape:'html':'UTF-8'}</a><br />
              {if $source['keywords']}<b>{l s='Keywords' mod='ets_marketplace'}</b> {$source['keywords']|escape:'html':'UTF-8'}<br />{/if}<br />
            </li>
          {/foreach}
        </ul>
      </div>
    {/if}
  </div>
</div>