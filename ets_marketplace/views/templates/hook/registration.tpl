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
{if isset($ETS_MP_GOOGLE_MAP_API) && $ETS_MP_GOOGLE_MAP_API && isset($ETS_MP_SEARCH_ADDRESS_BY_GOOGLE) && $ETS_MP_SEARCH_ADDRESS_BY_GOOGLE}
    <script>
        {literal}
        var address_autocomplete;
        function ets_mp_initAutocomplete() {
          address_autocomplete = new google.maps.places.Autocomplete(
              document.getElementById('search_shop_address'), {types: ['geocode']});
          address_autocomplete.setFields(['address_component']);
          address_autocomplete.addListener('place_changed', ets_mp_fillInAddress);
        }
        function ets_mp_fillInAddress() {
            var address = document.getElementById('search_shop_address').value;
        	var geocoder = new google.maps.Geocoder();
        	geocoder.geocode({address: address}, function(results, status) {
                if (status === google.maps.GeocoderStatus.OK)
      			{
      			   var center = results[0].geometry.location;
                   if(document.getElementsByName('latitude').length)
                        document.getElementById('latitude').value = Math.round(center.lat()*1000000)/1000000;
                   if(document.getElementsByName('longitude').length)
                        document.getElementById('longitude').value = Math.round(center.lng()*1000000)/1000000;
      			}
        	});
        }
        {/literal}
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={$ETS_MP_GOOGLE_MAP_API|escape:'html':'UTF-8'}&libraries=places&callback=ets_mp_initAutocomplete" async defer></script>
{/if}
{if $manager_shop}
    {if $manager_shop.active==-1}
        <form id="seller-register-form" action="" method="post" enctype="multipart/form-data">
            <div class="alert alert-info">
                {l s='You have a shop management invitation' mod='ets_marketplace'} {$manager_shop.shop_name|escape:'html':'UTF-8'} {l s='from ' mod='ets_marketplace'} {$manager_shop.firstname|escape:'html':'UTF-8'} {$manager_shop.lastname|escape:'html':'UTF-8'}
            </div>
            <div class="ets_button_group" style="display: block;">
                <button type="submit" id="submitDeclinceManageShop" class="btn btn-primary" name="submitDeclinceManageShop">{l s='Decline' mod='ets_marketplace'}</button>
                <button type="submit" id="submitApproveManageShop" class="btn btn-primary" name="submitApproveManageShop">{l s='Approve' mod='ets_marketplace'}</button>
            </div>
        </form>
    {else}
        <div class="alert alert-info">  
            {l s='You accepted a shop management invitation' mod='ets_marketplace'} {$manager_shop.shop_name|escape:'html':'UTF-8'}. <a href="{$link->getModuleLink('ets_marketplace','myseller')|escape:'html':'UTF-8'}">{l s='Click here' mod='ets_marketplace'}</a> {l s='to manage shop' mod='ets_marketplace'}
        </div>
    {/if}
{elseif !$seller && $ETS_MP_REGISTRATION_FIELDS}
    <section id="content" class="ets-mp-page-content-seller">
        {if !isset($smarty.post.submitSeller)}
            <div class="alert alert-info">
                {$ETS_MP_MESSAGE_INVITE nofilter}
            </div>
            <button type="button" id="submitApplication" class="btn btn-primary">{l s='Submit application' mod='ets_marketplace'}</button>
        {/if}
        <form id="seller-register-form" action="" method="post" enctype="multipart/form-data"{if !isset($smarty.post.submitSeller)} style="display:none;{/if}">
            <h3>{l s='Application' mod='ets_marketplace'}</h3>
            <section>
                {if in_array('seller_name',$ETS_MP_REGISTRATION_FIELDS)}
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label required"> {l s='Seller name' mod='ets_marketplace'} </label>
                        <div class="col-md-9">
                            <input class="form-control" name="seller_name" value="{$register_customer->firstname|escape:'html':'UTF-8'} {$register_customer->lastname|escape:'html':'UTF-8'}"  type="text" disabled="disabled" />
                        </div>
                    </div>
                {/if}
                {if in_array('seller_email',$ETS_MP_REGISTRATION_FIELDS)}
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label required"> {l s='Seller email' mod='ets_marketplace'} </label>
                        <div class="col-md-9">
                            <input class="form-control" name="seller_email" value="{$register_customer->email|escape:'html':'UTF-8'}"  type="text" disabled="disabled" />
                        </div>
                    </div>
                {/if}
                {if in_array('shop_phone',$ETS_MP_REGISTRATION_FIELDS)}
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label{if in_array('shop_phone',$ETS_MP_REGISTRATION_FIELDS_VALIDATE)} required{/if}"> {l s='Phone number' mod='ets_marketplace'} </label>
                        <div class="col-md-9">
                            <input class="form-control" name="shop_phone" value="{if isset($smarty.post.shop_phone)}{$smarty.post.shop_phone|escape:'html':'UTF-8'}{else}{$number_phone|escape:'html':'UTF-8'}{/if}"  type="text" />
                        </div>
                    </div>
                {/if}
                {if in_array('message_to_administrator',$ETS_MP_REGISTRATION_FIELDS)}
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label{if in_array('message_to_administrator',$ETS_MP_REGISTRATION_FIELDS_VALIDATE)} required{/if}"> {l s='Introduction' mod='ets_marketplace'} </label>
                        <div class="col-md-9">
                            <textarea class="form-control" name="message_to_administrator">{if isset($smarty.post.message_to_administrator)}{$smarty.post.message_to_administrator|escape:'html':'UTF-8'}{/if}</textarea>
                        </div>
                        <div class="col-md-12 clearfix" style="float: left"></div>

                        <label class="col-md-3 form-control-label clearfix">&nbsp;</label>
                        <div class="col-md-9 form-control-comment">{l s='Give us more information about you and your products that you are going to sell on our marketplace' mod='ets_marketplace'}</div>
                    </div>
                {/if}
                {if in_array('shop_name',$ETS_MP_REGISTRATION_FIELDS)}
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label{if in_array('shop_name',$ETS_MP_REGISTRATION_FIELDS_VALIDATE)} required{/if}"> {l s='Shop name' mod='ets_marketplace'} </label>
                        <div class="col-md-9">
                            <input class="form-control" name="shop_name" value="{if isset($smarty.post.shop_name)}{$smarty.post.shop_name|escape:'html':'UTF-8'}{/if}"  type="text" />
                        </div>
                    </div>
                {/if}
                {if in_array('shop_description',$ETS_MP_REGISTRATION_FIELDS)}
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label{if in_array('shop_description',$ETS_MP_REGISTRATION_FIELDS_VALIDATE)} required{/if}"> {l s='Shop description' mod='ets_marketplace'} </label>
                        <div class="col-md-9">
                            <textarea class="form-control" name="shop_description">{if isset($smarty.post.shop_description)}{$smarty.post.shop_description|escape:'html':'UTF-8'}{/if}</textarea>
                        </div>
                    </div>
                {/if}
                {if in_array('shop_address',$ETS_MP_REGISTRATION_FIELDS)}
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label{if in_array('shop_address',$ETS_MP_REGISTRATION_FIELDS_VALIDATE)} required{/if}"> {l s='Shop address' mod='ets_marketplace'} </label>
                        <div class="col-md-9">
                            <input class="form-control" id="search_shop_address" name="shop_address" value="{if isset($smarty.post.shop_address)}{$smarty.post.shop_address|escape:'html':'UTF-8'}{/if}"  type="text" />
                        </div>
                    </div>
                {/if}
                {if in_array('latitude',$ETS_MP_REGISTRATION_FIELDS) && isset($ETS_MP_ENABLE_MAP) && $ETS_MP_ENABLE_MAP}
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label{if in_array('latitude',$ETS_MP_REGISTRATION_FIELDS_VALIDATE)} required{/if}"> {l s='Latitude' mod='ets_marketplace'} </label>
                        <div class="col-md-9">
                            <input class="form-control" id="latitude" name="latitude" value="{if isset($smarty.post.latitude)}{$smarty.post.latitude|escape:'html':'UTF-8'}{/if}"  type="text" />
                        </div>
                    </div>
                {/if}
                {if in_array('longitude',$ETS_MP_REGISTRATION_FIELDS) && isset($ETS_MP_ENABLE_MAP) && $ETS_MP_ENABLE_MAP}
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label{if in_array('longitude',$ETS_MP_REGISTRATION_FIELDS_VALIDATE)} required{/if}"> {l s='Longitude' mod='ets_marketplace'} </label>
                        <div class="col-md-9">
                            <input class="form-control" id="longitude" name="longitude" value="{if isset($smarty.post.longitude)}{$smarty.post.longitude|escape:'html':'UTF-8'}{/if}"  type="text" />
                        </div>
                    </div>
                {/if}
                {if in_array('vat_number',$ETS_MP_REGISTRATION_FIELDS) }
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label{if in_array('vat_number',$ETS_MP_REGISTRATION_FIELDS_VALIDATE)} required{/if}"> {l s='VAT number' mod='ets_marketplace'} </label>
                        <div class="col-md-9">
                            <input class="form-control" name="vat_number" value="{if isset($smarty.post.vat_number)}{$smarty.post.vat_number|escape:'html':'UTF-8'}{else}{$vat_number|escape:'html':'UTF-8'}{/if}"  type="text" />
                        </div>
                    </div>
                {/if}
                {if in_array('shop_logo',$ETS_MP_REGISTRATION_FIELDS)}
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label{if in_array('shop_logo',$ETS_MP_REGISTRATION_FIELDS_VALIDATE)} required{/if}"> {l s='Shop logo' mod='ets_marketplace'} </label>
                        <div class="col-md-9">
                            <div class="ets_upload_file_custom">
                                <input class="form-control custom-file-input" name="shop_logo" type="file" id="shop_logo" />
                                <label class="custom-file-label" for="shop_logo" data-browser="{l s='Browse' mod='ets_marketplace'}">
                                   {l s='Choose file' mod='ets_marketplace'}
                                </label>
                            </div>
                            <div class="desc">{l s='Recommended size: 250x250 px. Accepted formats: jpg, png, gif' mod='ets_marketplace'}. {l s='Limit:' mod='ets_marketplace'}&nbsp;{Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE')|escape:'html':'UTF-8'}Mb</div>
                        </div>
                    </div>
                {/if}
                {if in_array('shop_banner',$ETS_MP_REGISTRATION_FIELDS)}
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label{if in_array('shop_banner',$ETS_MP_REGISTRATION_FIELDS_VALIDATE)} required{/if}"> {l s='Shop banner' mod='ets_marketplace'} </label>
                        <div class="col-md-9">
                            <div class="ets_upload_file_custom">
                                <input class="form-control custom-file-input shop_banner" name="shop_banner" type="file" id="shop_banner" />
                                <label class="custom-file-label" for="shop_banner" data-browser="{l s='Browse' mod='ets_marketplace'}">
                                   {l s='Choose file' mod='ets_marketplace'}
                                </label>
                            </div>
                            <div class="desc">{l s='Recommended size: 1170x170 px. Accepted formats: jpg, png, gif' mod='ets_marketplace'}. {l s='Limit:' mod='ets_marketplace'}&nbsp;{Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE')|escape:'html':'UTF-8'}Mb</div>
                        </div>
                    </div>
                {/if}
                {if in_array('banner_url',$ETS_MP_REGISTRATION_FIELDS)}
                    <div class="form-group row">
                        <label for="banner_url" class="col-md-3 form-control-label{if in_array('banner_url',$ETS_MP_REGISTRATION_FIELDS_VALIDATE)} required{/if}"> {l s='Banner URL' mod='ets_marketplace'} </label>
                        <div class="col-md-9">
                            <input class="form-control banner_url" name="banner_url" type="text" id="banner_url" />
                        </div>
                    </div>
                {/if}
                {if in_array('link_facebook',$ETS_MP_REGISTRATION_FIELDS)}
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label{if in_array('link_facebook',$ETS_MP_REGISTRATION_FIELDS_VALIDATE)} required{/if}"> {l s='Facebook link' mod='ets_marketplace'} </label>
                        <div class="col-md-9">
                            <input class="form-control" name="link_facebook" value="{if isset($smarty.post.link_facebook)}{$smarty.post.link_facebook|escape:'html':'UTF-8'}{/if}"  type="text" />
                        </div>
                    </div>
                {/if}
                {if in_array('link_instagram',$ETS_MP_REGISTRATION_FIELDS)}
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label{if in_array('link_instagram',$ETS_MP_REGISTRATION_FIELDS_VALIDATE)} required{/if}"> {l s='Instagram link' mod='ets_marketplace'} </label>
                        <div class="col-md-9">
                            <input class="form-control" name="link_instagram" value="{if isset($smarty.post.link_instagram)}{$smarty.post.link_instagram|escape:'html':'UTF-8'}{/if}"  type="text" />
                        </div>
                    </div>
                {/if}
                {if in_array('link_google',$ETS_MP_REGISTRATION_FIELDS)}
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label{if in_array('link_google',$ETS_MP_REGISTRATION_FIELDS_VALIDATE)} required{/if}"> {l s='Google link' mod='ets_marketplace'} </label>
                        <div class="col-md-9">
                            <input class="form-control" name="link_google" value="{if isset($smarty.post.link_google)}{$smarty.post.link_google|escape:'html':'UTF-8'}{/if}"  type="text" />
                        </div>
                    </div>
                {/if}
                {if in_array('link_twitter',$ETS_MP_REGISTRATION_FIELDS)}
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label{if in_array('link_twitter',$ETS_MP_REGISTRATION_FIELDS_VALIDATE)} required{/if}"> {l s='Twitter link' mod='ets_marketplace'} </label>
                        <div class="col-md-9">
                            <input class="form-control" name="link_twitter" value="{if isset($smarty.post.link_twitter)}{$smarty.post.link_twitter|escape:'html':'UTF-8'}{/if}"  type="text" />
                        </div>
                    </div>
                {/if}
                {if $ETS_MP_TERM_LINK}
                    <div class="form-group row">
                        <div class="col-md-3"></div>
                        <div class="col-md-9">
                            <label for="ets_mp_temp_link_registration"><input type="checkbox" id="ets_mp_temp_link_registration" name="ETS_MP_TERM_LINK" value="1"{if isset($smarty.post.ETS_MP_TERM_LINK) && $smarty.post.ETS_MP_TERM_LINK } checked="checked"{/if} /> {l s='I agree to the' mod='ets_marketplace'}&nbsp;<a href="{$ETS_MP_TERM_LINK|escape:'html':'UTF-8'}" target="_blank">{l s='Terms of service' mod='ets_marketplace'}</a>&nbsp;{l s='and will adhere to them unconditionally.' mod='ets_marketplace'}</label>
                        </div>
                    </div>
                {/if}
            </section> 
            <footer class="form-footer clearfix row">
                <div class="col-md-3"></div>
                <div class="col-md-9">
                  <input name="submitSeller" value="1" type="hidden" />
                  <button name="submitSeller" id="submitSeller" class="btn btn-primary form-control-submit float-xs-right" type="submit">
                      {l s='Submit' mod='ets_marketplace'}
                  </button>
                </div>
            </footer>   
        </form> 
    </section>
{/if}