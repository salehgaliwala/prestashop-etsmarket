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
{if $is_captcha}
    {if $ETS_MP_ENABLE_CAPTCHA_TYPE=='google_v2'}
        <script type="text/javascript">
            var ETS_MP_ENABLE_CAPTCHA_SITE_KEY = '{$ETS_MP_ENABLE_CAPTCHA_SITE_KEY2|escape:'html':'UTF-8'}';
            var ets_mp_contact_g_recaptchaonloadCallback = function() {
                ets_mp_contact_g_recaptcha = grecaptcha.render(document.getElementById('ets_mp_contact_g_recaptcha'), {
                    'sitekey':ETS_MP_ENABLE_CAPTCHA_SITE_KEY,
                    'theme':'light'
                });
            };
        </script>
    {else}
        <script src="https://www.google.com/recaptcha/api.js?render={$ETS_MP_ENABLE_CAPTCHA_SITE_KEY3|escape:'html':'UTF-8'}"></script>
        <script type="text/javascript">
            var ETS_MP_ENABLE_CAPTCHA_SITE_KEY = '{$ETS_MP_ENABLE_CAPTCHA_SITE_KEY3|escape:'html':'UTF-8'}';
            {literal}
            var ets_mp_contact_g_recaptchaonloadCallback = function() {
                grecaptcha.ready(function() {
                    grecaptcha.execute(ETS_MP_ENABLE_CAPTCHA_SITE_KEY, {action: 'homepage'}).then(function(token) {
                        $('#ets_mp_contact_g_recaptcha').val(token);
                    });
                });
            };
            {/literal}
        </script>
    {/if}
{/if}
<section class="contact-form">
    <form id="form-contact-seller" action="" method="post" enctype="multipart/form-data">
        <section class="form-fields">
            <div class="form-group row form-group-shop_name">
                <label class="col-md-3 form-control-label"></label>
                <div class="col-md-9">
                    <p>{l s='Shop' mod='ets_marketplace'}: <a href="{$seller_link|escape:'html':'UTF-8'}">{$seller->shop_name|escape:'html':'UTF-8'}</a></p>
                    {if isset($product_link)}
                            <p>{l s='Product' mod='ets_marketplace'}: <a href="{$product_link|escape:'html':'UTF-8'}">
                            {$product_class->name|escape:'html':'UTF-8'}</a>
                            </p>
                    {/if}
                </div>
                
            </div>
            
            {if in_array('name',$ETS_MP_CONTACT_FIELDS)}
                <div class="form-group row">
                    <label class="col-md-3 form-control-label {if in_array('name',$ETS_MP_CONTACT_FIELDS_VALIDATE)} required{/if}">{l s='Name' mod='ets_marketplace'}</label>
                    <div class="col-md-6">
                        <input class="form-control" name="name"{if $logged} value="{$contact_customer->firstname|escape:'html':'UTF-8'} {$contact_customer->lastname|escape:'html':'UTF-8'}" readonly="true"{/if} placeholder="" type="text" />
                    </div>
                </div>
            {/if}
            <div class="form-group row">
                <label class="col-md-3 form-control-label required">{l s='Your email' mod='ets_marketplace'}</label>
                <div class="col-md-6">
                    <input class="form-control" name="email"{if $logged} value="{$contact_customer->email|escape:'html':'UTF-8'}" readonly="true"{/if} placeholder="" type="text" />
                </div>
            </div>
            {if in_array('phone',$ETS_MP_CONTACT_FIELDS)}
                <div class="form-group row">
                    <label class="col-md-3 form-control-label {if in_array('phone',$ETS_MP_CONTACT_FIELDS_VALIDATE)} required{/if}">{l s='Phone' mod='ets_marketplace'}</label>
                    <div class="col-md-6">
                        <input class="form-control" name="phone" value="" placeholder="" type="text" />
                    </div>
                </div>
            {/if}
            <div class="form-group row">
                <label class="col-md-3 form-control-label required">{l s='Title' mod='ets_marketplace'}</label>
                <div class="col-md-6">
                    <input class="form-control" name="title" value="" placeholder="" type="text" />
                </div>
            </div>
            {if in_array('reference',$ETS_MP_CONTACT_FIELDS) && $logged}
                {if $order_references}
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label{if in_array('reference',$ETS_MP_CONTACT_FIELDS_VALIDATE)} required{/if}">{l s='Order reference' mod='ets_marketplace'}</label>
                        <div class="col-md-6">
                            <select name="reference" class="form-control">
                                <option value="">---</option>
                                {foreach $order_references item='order'}
                                    <option value="{$order.reference|escape:'html':'UTF-8'}">{$order.reference|escape:'html':'UTF-8'}</option>
                                 {/foreach}
                            </select>
                        </div>
                    </div>
                {elseif in_array('reference',$ETS_MP_CONTACT_FIELDS_VALIDATE)}
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label{if in_array('reference',$ETS_MP_CONTACT_FIELDS_VALIDATE)} required{/if}">{l s='Order reference' mod='ets_marketplace'}</label>
                        <div class="col-md-6">
                            <input class="form-control" name="reference" value="" placeholder="" type="text" />
                        </div>
                    </div>
                {/if}
            {/if}
            <div class="form-group row">
                <label class="col-md-3 form-control-label required">{l s='Message' mod='ets_marketplace'}</label>
                <div class="col-md-6">
                    <textarea name="message" class="form-control"></textarea>
                </div>
            </div>
            {if in_array('attachment',$ETS_MP_CONTACT_FIELDS)}
                <div class="form-group row">
                    <label class="col-md-3 form-control-label{if in_array('attachment',$ETS_MP_CONTACT_FIELDS_VALIDATE)} required{/if}">{l s='Attachment' mod='ets_marketplace'}</label>
                    <div class="col-md-6">
                        <div class="ets_upload_file_custom">
                            <input class="form-control custom-file-input" name="attachment" type="file" id="attachment" />
                            <label class="custom-file-label" for="attachment" data-title="{l s='Choose file' mod='ets_marketplace'}" data-browser="{l s='Browse' mod='ets_marketplace'}">
                               {l s='Choose file' mod='ets_marketplace'}
                            </label>
                        </div>
                        <div class="help-block">{l s='Allowed file types (' mod='ets_marketplace'}{$max_sizefile|escape:'html':'UTF-8'}{l s='MB max): jpg, png, gif, zip, rar, pdf' mod='ets_marketplace'}</div>
                    </div>
                </div>
            {/if}
            {if $is_captcha}
                <div class="form-group row">
                    <label class="col-md-3 form-control-label"></label>
                    <div class="col-md-6">
                        {if $ETS_MP_ENABLE_CAPTCHA_TYPE=='google_v2'}
                            <script src="https://www.google.com/recaptcha/api.js?onload=ets_mp_contact_g_recaptchaonloadCallback&render=explicit" async defer></script>
                            <div id="ets_mp_contact_g_recaptcha" class="ets_mp_contact_g_recaptcha" ></div>
                        {/if}
                        {if $ETS_MP_ENABLE_CAPTCHA_TYPE=='google_v3'}
                            <input type="hidden" id="ets_mp_contact_g_recaptcha" name="g-recaptcha-response" />
                            <script type="text/javascript">
                                ets_mp_contact_g_recaptchaonloadCallback();
                            </script>
                        {/if}
                    </div>
                </div>
            {/if}
        </section>
        <footer class="form-footer text-sm-right">
            <input type="hidden" name="id_seller" value="{$seller->id|intval}" />
            <input type="hidden" name="id_product" value="{if isset($id_product) && in_array('product_link',$ETS_MP_CONTACT_FIELDS)}{$id_product|intval}{/if}" />
            <input type="hidden" name="submitNewMessage" value="1" />
            <a class="btn btn-primary pull-left link_contact_back" href="{if isset($product_link)}{$product_link|escape:'html':'UTF-8'}{else}{$seller_link|escape:'html':'UTF-8'}{/if}">{if isset($product_link)}{l s='Back to product' mod='ets_marketplace'}{else}{l s='Back to shop' mod='ets_marketplace'}{/if}</a>
            <button class="btn btn-primary" name="submitNewMessage" type="submit">{l s='Send' mod='ets_marketplace'}</button>
        </footer>
    </form>
    {if !$logged}
        <div class="mp_contact_popup bootstrap mp_popup_wapper" style="display:none">
            <div class="pop_table">
                <div class="pop_table_cell">
                    <div class="mp_popup_content">
                        <span class="mp_close close_popup" title="{l s='Close' mod='ets_marketplace'}">{l s='Close' mod='ets_marketplace'}</span>
                        <p>{l s='You have to log in with your account before submitting contact message. Click on "Login" button if you already have an account. Click on "Create account" button to create a new one. ' mod='ets_marketplace'}</p>
                        <button class="mp_bt_cretate_account btn btn-primary">{l s='Create account' mod='ets_marketplace'}</button>
                        <button class="mp_bt_login_account btn btn-primary">{l s='Login' mod='ets_marketplace'}</button>
                    </div>
                </div>
            </div>
        </div>
        <form id="customer-form-register" action="" method="post" style="display:none">
            <section>
                <div class="form-group row ">
                    <label class="col-md-3 form-control-label">{l s='Social title' mod='ets_marketplace'}</label>
                    <div class="col-md-6 form-control-valign">
                        <label class="radio-inline">
                            <span class="custom-radio">
                                <input name="id_gender" value="1" type="radio" />
                                <span></span>
                            </span>
                            {l s='Mr.' mod='ets_marketplace'}
                        </label>
                        <label class="radio-inline">
                            <span class="custom-radio">
                                <input name="id_gender" value="2" type="radio" />
                                <span></span>
                            </span>
                            {l s='Mrs.' mod='ets_marketplace'}
                        </label>
                    </div>
                    <div class="col-md-3 form-control-comment">      
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label required"> {l s='First name' mod='ets_marketplace'} </label>
                    <div class="col-md-6">
                        <input class="form-control" name="firstname" value="" type="text" />
                    </div>
                    <div class="col-md-3 form-control-comment"> </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label required"> {l s='Last name' mod='ets_marketplace'} </label>
                    <div class="col-md-6">
                        <input class="form-control" name="lastname" value="" type="text" />
                    </div>
                    <div class="col-md-3 form-control-comment"> </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label required"> {l s='Email' mod='ets_marketplace'} </label>
                    <div class="col-md-6">
                        <input class="form-control" name="email" value="" type="email" />
                    </div>
                    <div class="col-md-3 form-control-comment"></div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label required"> {l s='Password' mod='ets_marketplace'} </label>
                    <div class="col-md-6">
                        <input class="form-control" name="password" value="" type="password" />
                    </div>
                    <div class="col-md-3 form-control-comment"></div>
                </div>
            </section>
            <footer class="form-footer clearfix">
                <input type="hidden" value="1" name="submitCustomerContact" />
                <button class="btn btn-primary form-control-submit float-xs-left cancel_contact_login"> {l s='Cancel' mod='ets_marketplace'} </button>
                <button class="btn btn-primary form-control-submit float-xs-right" type="submit" name="submitCustomerContact"> {l s='Create account' mod='ets_marketplace'} </button>
            </footer>
        </form>
        <form id="customer-form-login" action="" method="post" style="display:none">
            <section>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label required"> {l s='Email' mod='ets_marketplace'} </label>
                    <div class="col-md-6">
                        <input class="form-control" name="email" value="" type="email" />
                    </div>
                    <div class="col-md-3 form-control-comment"></div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 form-control-label required"> {l s='Password' mod='ets_marketplace'} </label>
                    <div class="col-md-6">
                        <input class="form-control" name="password" value="" type="password" />
                    </div>
                    <div class="col-md-3 form-control-comment"></div>
                </div>
            </section>
            <footer class="form-footer clearfix">
                <input type="hidden" value="1" name="submitLoginCustomerContact" />
                <button class="btn btn-primary form-control-submit float-xs-left cancel_contact_login"> {l s='Cancel' mod='ets_marketplace'} </button>
                <button class="btn btn-primary form-control-submit float-xs-right" type="submit" name="submitLoginCustomerContact"> {l s='Login' mod='ets_marketplace'} </button>
            </footer>
        </form>    
    {/if}
    
</section>
