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
          <i class="fa fa-envelope"></i> {l s='Messages' mod='ets_marketplace'} <span class="badge cicle">{sizeof($messages)|escape:'html':'UTF-8'}</span>
        </div>
        <div class="panel order-detail">
            {if $contact->name}
                <p><strong>{l s='Name' mod='ets_marketplace'}</strong>: {$contact->name|escape:'html':'UTF-8'}</p>
            {/if}
            {if Configuration::get('ETS_MP_DISPLAY_CUSTOMER_EMAIL') &&  $contact->email}
                <p><strong>{l s='Email' mod='ets_marketplace'}</strong>: {$contact->email|escape:'html':'UTF-8'}</p>
            {/if}
            {if $contact->phone}
                <p><strong>{l s='Phone' mod='ets_marketplace'}</strong>: {$contact->phone|escape:'html':'UTF-8'}</p>
            {/if}
            {if $contact->id_product}
                <p><strong>{l s='Product' mod='ets_marketplace'}</strong>: {if $link_image}&nbsp;<a href="{$link->getProductLink($product)|escape:'html':'UTF-8'}" target="_blank"><img src="{$link_image|escape:'html':'UTF-8'}"/></a>&nbsp;{/if}<a href="{$link->getProductLink($product)|escape:'html':'UTF-8'}" target="_blank">{$product->name|escape:'html':'UTF-8'}</a></p>
            {/if}
            <p><strong>{l s='Title' mod='ets_marketplace'}</strong>: {$contact->getTitle()|escape:'html':'UTF-8'}</p>
            {if $contact->attachment}
                <p><strong>{l s='Attachment' mod='ets_marketplace'}</strong>: <a href="{$link->getModuleLink('ets_marketplace',$smarty.get.controller,['id_contact'=>$contact->id,'downloadfile'=>1])|escape:'html':'UTF-8'}" target="_blank">{if $contact->attachment_name}{$contact->attachment_name|escape:'html':'UTF-8'}{else}{$contact->attachment|escape:'html':'UTF-8'}{/if}</a> </p>
            {/if}
            <p><strong>{l s='Message' mod='ets_marketplace'}</strong>: <br />{$contact->message|nl2br nofilter}</p>
            {if $contact->id_order}
                <p><strong>{l s='Order reference' mod='ets_marketplace'}</strong>: <a class="" href="{if $contact->id && !isset($seller_page)}{$link->getPageLink('order-detail',null,null,['id_order'=>$contact->id_order])|escape:'html':'UTF-8'}{else}{$link->getModuleLink('ets_marketplace','orders',['id_order'=>$order_message->id|intval])}{/if}">{$order_message->reference|escape:'html':'UTF-8'}</a></p>
            {/if}
        </div>
        <br />
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
                    {if $message.id_customer}
                        {$message.customer_name|escape:'html':'UTF-8'}
                    {/if}
                    {if $message.id_employee}
                        {$message.employee_name|escape:'html':'UTF-8'} ({l s='admin' mod='ets_marketplace'})
                    {/if}
                    {if $message.id_manager}
                        {$message.manager_name|escape:'html':'UTF-8'} ({l s='manager' mod='ets_marketplace'})
                    {elseif $message.id_seller}
                        {$message.seller_name|escape:'html':'UTF-8'} ({l s='seller' mod='ets_marketplace'})
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
                    <input type="hidden" name="id_contact" value="{$contact->id|intval}" />
                    <input type="hidden" name="submitMessage" value="1" />
                    <a href="{$link->getModuleLink('ets_marketplace',$smarty.get.controller)|escape:'html':'UTF-8'}" class="ets_cancel_message btn btn-primary float-xs-left">
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