/**
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
 * needs please contact us for extra customization service at an affordable price
 *
 *  @author ETS-Soft <etssoft.jsc@gmail.com>
 *  @copyright  2007-2020 ETS-Soft
 *  @license    Valid for 1 website (or project) for each purchase of license
 *  International Registered Trademark & Property of ETS-Soft
 */
function ets_mp_refreshCapchaContact()
{
    if($('.ets_mp_contact_g_recaptcha').length)
    {
        grecaptcha.reset(
            ets_mp_contact_g_recaptcha
        ); 
    }
    else
    if($('#ets_mp_contact_g_recaptcha').length > 0)
    {
        ets_mp_contact_g_recaptchaonloadCallback(); 
    }
}
$(document).on('click','button[name="submitNewMessage"]',function(e){
    e.preventDefault();
    var $this= $(this);
    if(!$this.hasClass('loading'))
    {
        $('.module_error.alert').parent().remove();
        $('.alert.alert-success').remove();
        var attachment_default = $('.custom-file-label[for="attachment"]').attr('data-title');
        $this.addClass('loading');
        var formData = new FormData($(this).parents('form').get(0));
        formData.append('ajax', 1);
        $.ajax({
            url: '',
            data: formData,
            type: 'post',
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(json){
                ets_mp_refreshCapchaContact();
                $this.removeClass('loading');
                if(json.success)
                {
                    $('form#form-contact-seller').before('<div class="col-xs-12 alert alert-success"><ul><li>'+json.success+'</li></ul></div><div class="clearfix"></div>');
                    $('input[name="title"]').val('');
                    $('textarea[name="message"]').val('');
                    $('input[name="attachment"]').val('');
                    $('.custom-file-label[for="attachment"]').html(attachment_default);
                    $('select[name="reference"] option').removeAttr('selected');
                    $('select[name="reference"] option[value=""]').attr('selected','selected');
                    $('select[name="reference"]').change();
                    $('.alert-success li').append('<a class="pull-right" href="'+$('.link_contact_back').attr('href')+'">'+$('.link_contact_back').html()+'</a>');
                }
                if(json.errors)
                {
                    $('form#form-contact-seller').before(json.errors);
                }
                if(json.display_form_login)
                {
                    $('.mp_contact_popup').show();
                }
            },
            error: function(xhr, status, error)
            {
                $this.removeClass('loading');   
                ets_mp_refreshCapchaContact();         
            }
        });
    }
});
$(document).on('click','.mp_bt_cretate_account',function(){
    $('.mp_contact_popup').hide();
    $('form#form-contact-seller').hide();
    $('form#customer-form-register').show();
    return false; 
});
$(document).on('click','.mp_bt_login_account',function(){
    $('.mp_contact_popup').hide();
    $('form#form-contact-seller').hide();
    $('form#customer-form-login').show();
    return false; 
});
$(document).on('click','.cancel_contact_login',function(){
    $('.mp_contact_popup').hide();
    $('form#form-contact-seller').show();
    $('form#customer-form-login').hide();
    $('form#customer-form-register').hide();
    $('.module_error.alert').parent().remove();
    return false; 
});
$(document).on('click','.close_popup',function(){
    $('.mp_popup_wapper').hide();
});
$(document).mouseup(function (e)
{
    var container_popup = $('.mp_popup_content');
    if (!container_popup.is(e.target)&& container_popup.has(e.target).length === 0)
    {
        $('.mp_popup_wapper').hide();
    }
});
$(document).keyup(function(e) { 
    if(e.keyCode == 27) {
        $('.mp_popup_wapper').hide();
    }
});
$(document).on('click','button[name="submitCustomerContact"],button[name="submitLoginCustomerContact"]',function(e){
    e.preventDefault();
    var $this= $(this);
    if(!$this.hasClass('loading'))
    {
        $('.module_error.alert').parent().remove();
        $this.addClass('loading');
        var formData = new FormData($(this).parents('form').get(0));
        formData.append('ajax', 1);
        $.ajax({
            url: '',
            data: formData,
            type: 'post',
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(json){
                $this.removeClass('loading');
                if(json.success)
                {
                    $('form#form-contact-seller').show();
                    $('form#customer-form-register').remove();
                    $('form#customer-form-login').remove();
                }
                if(json.errors)
                {
                    $('form#customer-form-register').before(json.errors);
                }
            },
            error: function(xhr, status, error)
            {
                $this.removeClass('loading');            
            }
        });
    }
});