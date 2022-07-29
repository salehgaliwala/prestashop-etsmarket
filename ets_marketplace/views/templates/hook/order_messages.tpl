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
<div id="ets_mp_order_messages_page">
    <div class="panel">
        <div class="panel-heading">
          <i class="fa fa-envelope"></i> {l s='Messages' mod='ets_marketplace'} <span class="badge cicle">{sizeof($customer_thread_message)|escape:'html':'UTF-8'}</span>
        </div>
        <div class="panel order-detail">
            <p><strong>{l s='Order reference' mod='ets_marketplace'}</strong>: <a class=" " href="{$link->getModuleLink('ets_marketplace','orders',['id_order'=>$order->id|intval])}">{$order->reference|escape:'html':'UTF-8'}</a></p>
            <p><strong>{l s='Customer name' mod='ets_marketplace'}</strong>: {$customer->firstname|escape:'html':'UTF-8'} {$customer->lastname|escape:'html':'UTF-8'}</p>
            {if Configuration::get('ETS_MP_DISPLAY_CUSTOMER_EMAIL')}
                <p><strong>{l s='Customer email' mod='ets_marketplace'}</strong>: {$customer->email|escape:'html':'UTF-8'}</p>
            {/if}
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
                        {dateFormat date=$message['date_add'] full=1} -
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
        <div id="messages" class="">
          <form action="" method="post">
            <div id="message" class="form-horizontal">
              <div class="form-group row">
                <label class="control-label col-lg-12">{l s='Message' mod='ets_marketplace'}</label>
                <div class="col-lg-12">
                  <textarea id="txt_msg" class="textarea-autosize" name="message" placeholder="{l s='Write a message to customer' mod='ets_marketplace'}">{if $_errors}{Tools::getValue('message')|escape:'html':'UTF-8'}{/if}</textarea>
                  <p id="nbchars"></p>
                </div>
              </div>
              <input type="hidden" name="id_order" value="{$order->id|intval}" />
              <input type="hidden" name="id_customer" value="{$order->id_customer|intval}" />
              <input type="hidden" name="submitMessage" value="1" />
              <a href="{$link->getModuleLink('ets_marketplace','messages')|escape:'html':'UTF-8'}" class="ets_cancel_message btn btn-primary float-xs-left">
                {l s='Back to messages' mod='ets_marketplace'}
              </a>
              <button type="submit" id="submitMessage" class="ets_submit_message btn btn-primary float-xs-right" name="submitMessage">
                {l s='Send message' mod='ets_marketplace'}
              </button>
                <div class="clearfix"></div>
            </div>
          </form>
        </div>
    </div>
</div>