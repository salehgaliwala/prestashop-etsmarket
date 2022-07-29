{*
* 2007-2018 ETS-Soft
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
{if isset($email) && $email}
    <p>{l s='Email' mod='ets_marketplace'}: {$email|escape:'html':'UTF-8'}</p>
{/if}
{if isset($name) && $name}
    <p>{l s='Name' mod='ets_marketplace'}: {$name|escape:'html':'UTF-8'}</p>
{/if}
{if isset($phone) && $phone}
    <p>{l s='Phone' mod='ets_marketplace'}: {$phone|escape:'html':'UTF-8'}</p>
{/if}
{if isset($title) && $title}
    <p>{l s='Message title' mod='ets_marketplace'}: {$title|escape:'html':'UTF-8'}</p>
{/if}
{if isset($product_link) && $product_link}
    <p>{l s='Product URL' mod='ets_marketplace'}: <a href="{$product_link|escape:'html':'UTF-8'}">{$product_link|escape:'html':'UTF-8'}</a></p>
{/if}
{if isset($reference) && $reference}
    <p>{l s='Order reference' mod='ets_marketplace'}: {$reference|escape:'html':'UTF-8'}</p>
{/if}
{if isset($message) && $message}
    <p>{l s='Message content' mod='ets_marketplace'}: {$message|nl2br nofilter}</p>
{/if}
{if isset($attachment) && $attachment}
    <p>{l s='Attached file' mod='ets_marketplace'}: <a href="{$link->getModuleLink('ets_marketplace','messages',['id_contact'=>$id_contact,'downloadfile'=>1])|escape:'html':'UTF-8'}">{if isset($attachment_name) && $attachment_name}{$attachment_name|escape:'html':'UTF-8'}{else}{$attachment|escape:'html':'UTF-8'}{/if}</a></p>
{/if}