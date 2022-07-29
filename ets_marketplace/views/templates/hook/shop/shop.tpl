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
<script type="text/javascript">
var link_ajax_sort_product_list='{$link_ajax_sort_product_list nofilter}';
var selected_categories = '{l s='Selected categories' mod='ets_marketplace' js=1}';
var is_product_comment = {$is_product_comment|intval};
var product_comment_grade_url  ='{$product_comment_grade_url nofilter}';
</script>
{if $is_captcha && !$reported}
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
<section id="main" class="ets_mp_shop_main_detail">
    {if $seller->shop_banner}
        <div class="card card-block block-seller-banner">
            <div class="seller-banner">
                {if $seller->banner_url}
                    <a href="{$seller->banner_url|escape:'html':'UTF-8'}">
                {/if}
                <img src="{$link->getMediaLink("`$smarty.const.__PS_BASE_URI__`img/mp_seller/`$seller->shop_banner|escape:'htmlall':'UTF-8'`")}" alt="{$seller->shop_name|escape:'html':'UTF-8'}" />
                {if $seller->banner_url}
                    </a>
                {/if}
            </div>
        </div>
    {/if}
    <div id="js-product-list-header">
        <div class="block-seller card card-block">
            {if !$reported}
                {if !$customer_logged}
                    <div class="alert alert-danger not_login">
                        {l s='You need to sign in to report this shop' mod='ets_marketplace'}. <a href="{$link->getPageLink('authentication',null,null,['back'=>$seller->getLink()])|escape:'html':'UTF-8'}">{l s='Sign in' mod='ets_marketplace'}</a>
                        <span class="close">x</span>
                    </div>
                {/if}
            {/if}
            <div class="seller-cover">
                {if $seller->shop_logo}
                    <img style="width:120px" src="{$link->getMediaLink("`$smarty.const.__PS_BASE_URI__`img/mp_seller/`$seller->shop_logo|escape:'htmlall':'UTF-8'`")}" alt="{$seller->shop_name|escape:'html':'UTF-8'}" />
                {else}
                    <img style="width:120px" src="{$link->getMediaLink("`$smarty.const.__PS_BASE_URI__`img/mp_seller/default.png")}" alt="{$seller->shop_name|escape:'html':'UTF-8'}" />
                {/if}
                <a class="btn btn-primary mp_link_contact_form" href="{$link->getModuleLink('ets_marketplace','contactseller',['id_seller'=>$seller->id])|escape:'html':'UTF-8'}">{l s='Contact shop' mod='ets_marketplace'}</a>
                {if $seller->link_facebook || $seller->link_google || $seller->link_instagram || $seller->link_twitter}
                    <div class="seller-social">
                        {if $seller->link_facebook}
                            <a class="facebook" title="Facebook" href="{$seller->link_facebook|escape:'html':'UTF-8'}"><i class="icon icon-facebook fa fa-facebook"></i></a>
                        {/if}
                        {if $seller->link_google}
                            <a class="google" title="Google" href="{$seller->link_google|escape:'html':'UTF-8'}"><i class="icon icon-google fa fa-google"></i></a>
                        {/if}
                        {if $seller->link_instagram}
                            <a class="instagram" title="Instagram" href="{$seller->link_instagram|escape:'html':'UTF-8'}"><i class="icon icon-instagram fa fa-instagram"></i></a>
                        {/if} 
                        {if $seller->link_twitter}
                            <a class="twitter" title="Twitter" href="{$seller->link_twitter|escape:'html':'UTF-8'}"><i class="icon icon-twitter fa fa-twitter"></i></a> 
                        {/if}
                    </div>
                {/if}
            </div>
            <div class="block-seller-inner{if isset($seller_group) && $seller_group} block-seller_has-group{/if}">
                <h1 class="h1">{$seller->shop_name|escape:'html':'UTF-8'}</h1>
                {if $seller_follow >=0}
                    <div class="wapper-follow">
                        <div class="block-followed"{if !$seller_follow} style="display:none;"{/if}>
                            {*<span class="btn btn-primary follow following">{l s='Following' mod='ets_marketplace'}</span>*}
                            <button class="btn btn-primary follow" name="submitunfollow">{l s='Unfollow' mod='ets_marketplace'}</button>
                        </div>
                        <div class="block-follow"{if $seller_follow} style="display:none;"{/if}>
                            <button class="btn btn-primary follow" name="submitfollow" >{l s='Follow' mod='ets_marketplace'}</button>
                        </div>
                    </div>
                {/if}
                {if !$reported}
                    <button class="ets_mp_report btn btn-primary" title="{l s='Report as abused' mod='ets_marketplace'}"><i class="fa fa-flag"></i></button>
                {/if}
                {if isset($seller_group) && $seller_group && $seller_group->level_name}
                    <div class="block-seller-group">
                        {if $seller_group->badge_image}
                            <img class="badge_image" src="{$link->getMediaLink("`$smarty.const.__PS_BASE_URI__`img/mp_group/`$seller_group->badge_image|escape:'htmlall':'UTF-8'`")}" />
                        {/if}
                        <div class="group-name">{$seller_group->level_name|escape:'html':'UTF-8'}</div>
                    </div>
                {/if}
                <div class="product product_review_shop">
                    {if $total_products}
                        <div class="total col">
                            <i class="fa fa-cubes"></i> {if $total_products > 1}{l s='Products: ' mod='ets_marketplace'}{else}{l s='Product:' mod='ets_marketplace'}{/if} <span>{$total_products|intval}</span>
                        </div>  
                        <div class="total_follow col">
                            <i class="fa fa-thumbs-o-up"></i> {if $total_follow > 1}{l s='Followers:' mod='ets_marketplace'}{else}{l s='Follower:' mod='ets_marketplace'}{/if} <span>{$total_follow|intval}</span>
                        </div>
                    {else}
                        <div class="total_follow col">
                            <i class="fa fa-thumbs-o-up"></i> {if $total_follow > 1}{l s='Followers:' mod='ets_marketplace'}{else}{l s='Follower:' mod='ets_marketplace'}{/if} <span>{$total_follow|intval}</span>
                        </div>
                    {/if}
                    {if $total_reviews}
                        <div class="ets_review col">
                            <a href="{url entity='module' name='sba_comments' controller='comments' params=['shopId' => $seller->id]}">
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
                            </a>
                        </div>
                    {else}
                        <div class="ets_review col">
                            <a href="{url entity='module' name='sba_comments' controller='comments' params=['shopId' => $seller->id]}">
                                <i class="fa fa-star-o"></i> {l s='Noter ce vendeur' mod='ets_marketplace'}
                            </a>
                        </div>
                    {/if}
                    {if $response_rate!==false}
                        <div class="response_rate col">
                            <i class="fa fa-commenting-o"></i> {l s='Response rate' mod='ets_marketplace'}: <span>{$response_rate|floatval}%</span>
                        </div>
                    {/if}
                    <div class="shop_date_add col">
                        <i class="fa fa-calendar"></i> {l s='Date created' mod='ets_marketplace'}: <span>{dateFormat date=$seller->date_add}</span>
                    </div>
                    {if $total_products && $total_product_sold!==false}
                        <div class="total_product_sold col"> 
                            <i class="fa fa-shopping-cart"></i> {if $total_product_sold>1}{l s='Products sold:' mod='ets_marketplace'}{else}{l s='Product sold:' mod='ets_marketplace'}{/if} <span>{$total_product_sold|intval}</span>
                        </div>
                    {/if}
                </div>
                <div id="seller-description" class="text-muted">
                    {$seller->shop_description|nl2br nofilter}
                </div>
                {if isset($ETS_MP_ENABLE_MAP) && $ETS_MP_ENABLE_MAP && $seller->latitude!=0 && $seller->longitude!=0}
                    <div class="ets_mp_map">
                        <a class="view_map" href="#"> 
                            <i class="fa fa-map-marker"></i> {l s='View map' mod='ets_marketplace'}
                        </a>
                    </div>
                {/if}
            </div>
        </div>
        {if $seller->vacation_mode && $seller->vacation_type=='show_notifications'}
            <div class="alert alert-warning">{$seller->vacation_notifications|nl2br nofilter}</div>
        {/if}
    </div>
    <section class="wrapper">
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 ets_mp_shop_nocategory ets_myshop_right">
                {*if $total_all_products}
                    <div class="ets_mp_tabs">
                        <div class="ets_mp_tabs_content">
                            
                            {if $total_new_products || $total_best_seller_products || $total_special_products}
                                <div class="ets_mp_tabs_content_link_all">
                                    <a title="{l s='Sub categories' mod='ets_marketplace'}" href="{$link_all|escape:'html':'UTF-8'}" class="tab_link{if $current_tab=='all' || ($current_tab=='new_product' && !$total_new_products) ||($current_tab=='best_seller' && !$total_best_seller_products) || ($current_tab=='special' && !$total_special_products)} active{/if}" data-tab="all"><span class="all_product_text">{l s='All products' mod='ets_marketplace'} ({$total_all_products|intval})</span><span class="product_categories_selected"></span></a>
                                    {$list_categories nofilter}
                                </div>
                            {/if}
                            {if $total_new_products}
                                <a href="{$link_new_product|escape:'html':'UTF-8'}" class="tab_link{if $current_tab=='new_product'} active{/if}" data-tab="new_product">{l s='New products' mod='ets_marketplace'} ({$total_new_products|intval})</a>
                            {/if}
                            {if $total_best_seller_products}
                                <a href="{$link_best_seller|escape:'html':'UTF-8'}" class="tab_link{if $current_tab=='best_seller'} active{/if}" data-tab="best_seller">{l s='Best sellers' mod='ets_marketplace'} ({$total_best_seller_products|intval})</a>
                            {/if}
                            {if $total_special_products}
                                <a href="{$link_special|escape:'html':'UTF-8'}" class="tab_link{if $current_tab=='special'} active{/if}" data-tab="special">{l s='Discounted' mod='ets_marketplace'} ({$total_special_products|intval})</a>
                            {/if}
                        </div>
                        <div class="ets_mp_tabs_content_search"></div>
                    </div>
                {/if*}
                <div id="products">
                    <div class="product_tab ets_mp_shop_tab tab_all{if $current_tab=='all' || ($current_tab=='new_product' && !$total_new_products) ||($current_tab=='best_seller' && !$total_best_seller_products) || ($current_tab=='special' && !$total_special_products)} active{/if}">
                        {if $current_tab=='all'}
                            {$product_list nofilter}
                        {/if}
                    </div>
                    {*if $total_new_products}
                        <div class="product_tab ets_mp_shop_tab tab_new_product{if $current_tab=='new_product'} active{/if}">
                            {if $current_tab=='new_product'}
                                {$product_list nofilter}
                            {/if}
                        </div>
                    {/if}
                    {if $total_best_seller_products}
                        <div class="product_tab ets_mp_shop_tab tab_best_seller{if $current_tab=='best_seller'} active{/if}">
                            {if $current_tab=='best_seller'}
                                {$product_list nofilter}
                            {/if}
                        </div>
                    {/if}
                    {if $total_special_products}
                        <div class="product_tab ets_mp_shop_tab tab_special{if $current_tab=='special'} active{/if}">
                            {if $current_tab=='special'}
                                {$product_list nofilter}
                            {/if}
                        </div>
                    {/if*}
                    
                </div>
            </div>
        </div>
    </section>
</section>
{if !$reported && $customer_logged}
    <div class="ets_mp_popup ets_mp_shop_report_popup" style="display:none;">
        <div class="mp_pop_table">
            <div class="mp_pop_table_cell">
                <form id="ets_mp_report_shop_form" action="" method="post" enctype="multipart/form-data">
                    <div class="ets_mp_close_popup" title="{l s='Close' mod='ets_marketplace'}">{l s='Close' mod='ets_marketplace'}</div>
                    <div id="fieldset_0" class="panel">
                        
                            <div class="panel-heading">
                                <i class="icon-info-sign"></i>
                                {l s='Report shop' mod='ets_marketplace'}
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
                                <input name="id_product_report" value="0" type="hidden"/>
                                <input name="id_seller_report" value="{$seller->id|intval}" type="hidden"/>
                                <button class="btn btn-primary form-control-submit float-xs-right" name="submitReportShop" type="submit">{l s='Report' mod='ets_marketplace'}</button>
                            </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
{/if}
{if isset($ETS_MP_ENABLE_MAP) && $ETS_MP_ENABLE_MAP && $seller->latitude!=0 && $seller->longitude!=0}
    <div class="ets_mp_popup ets_mp_shop_maps_popup" style="display:none;">
        <div class="mp_pop_table">
            <div class="mp_pop_table_cell">
                <div>
                    <div class="ets_mp_close_popup" title="Close">Close</div>
                    <div id="map"></div>
                    <div class="store-content-select selector3" style="display:none;">
                    	<select id="locationSelect" class="form-control">
                    		<option>-</option>
                    	</select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var markers=[];
        var infoWindow = '';
        var locationSelect = '';
        var defaultLat = {$seller->latitude|floatval};
        var defaultLong = {$seller->longitude|floatval};
        var hasStoreIcon = true;
        var distance_unit = 'km';
        var img_ps_dir = '{$base_link|escape:'html':'UTF-8'}/modules/ets_marketplace/views/img/';
        var searchUrl = '{$link->getModuleLink('ets_marketplace','shop',['id_seller'=>$seller->id,'getmaps'=>1]) nofilter}';
        var logo_map = {if Configuration::get('ETS_MP_GOOGLE_MAP_LOGO')}'{Configuration::get('ETS_MP_GOOGLE_MAP_LOGO')|escape:'html':'UTF-8'}'{else}'logo_map.png'{/if};
        var translation_1 = '{l s='No stores were found. Please try selecting a wider radius.' mod='ets_marketplace' js=1}';
        var translation_2 = '{l s='store found -- see details:' mod='ets_marketplace' js=1}';
        var translation_3 = '{l s='stores found -- view all results:' mod='ets_marketplace' js=1}';
        var translation_4 = '{l s='Phone:' mod='ets_marketplace' js=1}' ;
        var translation_5 = '{l s='Get directions' mod='ets_marketplace' js=1}';
        var translation_6 = '{l s='Not found' mod='ets_marketplace' js=1}';
    </script>
{/if}
