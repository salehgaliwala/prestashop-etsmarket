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
{if $is_captcha && !$reported && !$quick_view}
    {if $ETS_MP_ENABLE_CAPTCHA_TYPE=='google_v2'}
        <script type="text/javascript">
            var ETS_MP_ENABLE_CAPTCHA_SITE_KEY = '{$ETS_MP_ENABLE_CAPTCHA_SITE_KEY2|escape:'html':'UTF-8'}';
            var ets_mp_report_g_recaptchaonloadCallback = function() {
                ets_mp_report_g_recaptcha = grecaptcha.render(document.getElementById('ets_mp_report_g_recaptcha'), {
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
            var ets_mp_report_g_recaptchaonloadCallback = function() {
                grecaptcha.ready(function() {
                    grecaptcha.execute(ETS_MP_ENABLE_CAPTCHA_SITE_KEY, {action: 'homepage'}).then(function(token) {
                        $('#ets_mp_report_g_recaptcha').val(token);
                    });
                });
            };
            {/literal}
        </script>
    {/if}
{/if}
<div class="mp_shop_seller_detail">
    <div class="mp_left_content">
        <div class="shop_logo">
            {if $logo_seller}
                <img src="{$link->getMediaLink("`$smarty.const.__PS_BASE_URI__`img/mp_seller/`$logo_seller|escape:'htmlall':'UTF-8'`")}" width="80px"/>
            {else}
                <img src="{$link->getMediaLink("`$smarty.const.__PS_BASE_URI__`img/mp_seller/default.png")}" width="80px"/>
            {/if}
        </div>
    </div>
    <div class="mp_right_content">
        {if $vacation_notifications}
            <div class="alert alert-warning">{$vacation_notifications|nl2br nofilter}</div>
        {/if}
        <div class="name">{l s='About shop' mod='ets_marketplace'}: <a href="{$link_shop_seller|escape:'html':'UTF-8'}">{$shop_name|escape:'html':'UTF-8'}</a> 
        {if !$reported && (!$quick_view || !$is_captcha)}
            <button class="ets_mp_report btn btn-primary" title="{l s='Report as abused' mod='ets_marketplace'}"><i class="fa fa-flag"></i></button>
        {/if}
        </div>
            <div class="product product_review_shop">
                {if $total_products}
                    <div class="total col">
                        <i class="fa fa-cubes"></i> {if $total_products > 1}{l s='Products: ' mod='ets_marketplace'}{else}{l s='Product:' mod='ets_marketplace'}{/if} <span>{$total_products|intval}</span>
                    </div>  
                    <div class="total_follow col">
                        <i class="fa fa-thumbs-o-up"></i> {if $total_follow > 1}{l s='Followers' mod='ets_marketplace'}{else}{l s='Follower:' mod='ets_marketplace'}{/if} <span>{$total_follow|intval}</span>
                    </div>
                    {if $total_reviews}
                        <div class="ets_review col">
                                <i class="fa fa-star-o"></i> {l s='Shop rating' mod='ets_marketplace'}:
                                {for $foo=1 to $total_reviews_int}
                                    <span class="ets_star fa fa-star"></span>
                                {/for}
                                {if $total_reviews_int < $total_reviews}
                                    <span class="ets_star fa fa-star-half-o"></span>
                                    {for $foo= $total_reviews_int+2 to 5}
                                        <span class="ets_star fa fa-star-o"></span>
                                    {/for}
                                {else}
                                    {for $foo= $total_reviews_int+1 to 5}
                                        <span class="ets_star fa fa-star-o"></span>
                                    {/for}
                                {/if}
                                <span class="total_review">({$count_reviews|intval})</span>
                        </div>
                    {/if}
                {else}
                    <div class="total_follow col">
                        <i class="fa fa-thumbs-o-up"></i> {if $total_follow > 1}{l s='Followers:' mod='ets_marketplace'}{else}{l s='Follower:' mod='ets_marketplace'}{/if} <span>{$total_follow|intval}</span>
                    </div>
                {/if}
                {if $response_rate!==false}
                    <div class="response_rate col">
                        <i class="fa fa-commenting-o"></i> {l s='Response rate' mod='ets_marketplace'}: <span>{$response_rate|floatval}%</span>
                    </div>
                {/if}
                <div class="shop_date_add col">
                    <i class="fa fa-calendar"></i> {l s='Date created' mod='ets_marketplace'}: <span>{dateFormat date=$seller_date_add}</span>
                </div>
                {if $total_product_sold!==false}
                    <div class="total_product_sold col"> 
                        <i class="fa fa-shopping-cart"></i> {if $total_product_sold>1}{l s='Products sold:' mod='ets_marketplace'}{else}{l s='Product sold:' mod='ets_marketplace'}{/if} <span>{$total_product_sold|intval}</span>
                    </div>
                {/if}
            </div>
        <a href="{$link_contact_form|escape:'html':'UTF-8'}" class="mp_link_contact_form btn btn-primary" {if isset($smarty.get.content_only) && $smarty.get.content_only} target="_parent"{/if}>{l s='Contact shop' mod='ets_marketplace'}</a>
        
    </div>
</div>
{if !$reported && (!$quick_view || !$is_captcha)}
    {if $customer_logged}
    <div class="ets_mp_popup ets_mp_shop_report_popup" style="display:none;">
        <div class="mp_pop_table">
            <div class="mp_pop_table_cell">
                <form id="ets_mp_report_shop_form" action="" method="post" enctype="multipart/form-data">
                    
                    <div id="fieldset_0" class="panel">
                        <div class="ets_mp_close_popup ss1" title="{l s='Close' mod='ets_marketplace'}">{l s='Close' mod='ets_marketplace'}</div>
                            <div class="panel-heading">
                                <i class="icon-info-sign"></i>
                                {l s='Report product' mod='ets_marketplace'}
                            </div>
                            <div class="form-wrapper">
                                <div class="row form-group">
                                    <label class="col-lg-3 form-control-label" for="email">{l s='Email' mod='ets_marketplace'}</label>
                                    <div class="col-lg-9">
                                        <input disabled="disabled" class="form-control" name="email" value="{$report_customer->email|escape:'html':'UTF-8'}" type="text" id="email" readonly="true" />
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-lg-3 form-control-label" for="name">{l s='Name' mod='ets_marketplace'}</label>
                                    <div class="col-lg-9">
                                        <input disabled="disabled" class="form-control" name="name" value="{$report_customer->firstname|escape:'html':'UTF-8'} {$report_customer->lastname|escape:'html':'UTF-8'}" type="text" id="name" readonly="true" />
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-lg-3 form-control-label required" for="report_title">{l s='Title' mod='ets_marketplace'}</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" name="report_title" value="" type="text" id="report_title" />
                                    </div>
                                </div>
                                <div class="row form-group">
                                    <label class="col-lg-3 form-control-label required" for="report_content">{l s='Content' mod='ets_marketplace'}</label>
                                    <div class="col-lg-9"><textarea class="form-control" name="report_content" id="report_content"></textarea></div>
                                </div>
                                {if $is_captcha}
                                    <div class="form-group row">
                                        <label class="col-md-3 form-control-label"></label>
                                        <div class="col-md-6">
                                            {if $ETS_MP_ENABLE_CAPTCHA_TYPE=='google_v2'}
                                                <script src="https://www.google.com/recaptcha/api.js?onload=ets_mp_report_g_recaptchaonloadCallback&render=explicit" async defer></script>
                                                <div id="ets_mp_report_g_recaptcha" class="ets_mp_report_g_recaptcha" ></div>
                                            {/if}
                                            {if $ETS_MP_ENABLE_CAPTCHA_TYPE=='google_v3'}
                                                <input type="hidden" id="ets_mp_report_g_recaptcha" name="g-recaptcha-response" />
                                                <script type="text/javascript">
                                                    ets_mp_report_g_recaptchaonloadCallback();
                                                </script>
                                            {/if}
                                        </div>
                                    </div>
                                {/if}
                            </div>
                            <div class="panel-footer">
                                <input name="submitReportShop" value="1" type="hidden" />
                                <input name="id_product_report" value="{$id_product_report|intval}" type="hidden"/>
                                <input name="id_seller_report" value="{$id_seller_report|intval}" type="hidden"/>
                                <button class="btn btn-primary form-control-submit float-xs-right" name="submitReportShop" type="submit">{l s='Report' mod='ets_marketplace'}</button>
                            </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {else}
        <div class="alert alert-danger not_login" style="text-align: left;">
            {l s='You need to sign in to report this product' mod='ets_marketplace'}. <a href="{$link->getPageLink('authentication',null,null,['back'=>$link_proudct])|escape:'html':'UTF-8'}">{l s='Sign in' mod='ets_marketplace'}</a>
        <span class="close">×</span>
        </div>
    {/if}
{/if}