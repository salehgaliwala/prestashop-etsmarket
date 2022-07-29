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
function ets_checkRateShopby(){
    if ( $('.product_list_shop_by:visible').length > 0 ){
        $('.product_list_shop_by:visible').each(function(){
            $(this).parents('.thumbnail-container').addClass('has_shopby');   
        });
    }
    if ( $('.product-list-reviews .star-content, .product-list-reviews .comments-note').length > 0 ){
        $('.product-list-reviews .star-content, .product-list-reviews .comments-note').each(function(){
            $(this).parents('.thumbnail-container').addClass('hasreview');   
        });
    }
}
function ets_mp_refreshCapchaReport()
{
    if($('.ets_mp_report_g_recaptcha').length)
    {
        grecaptcha.reset(
            ets_mp_report_g_recaptcha
        ); 
    }
    else
    if($('#ets_mp_report_g_recaptcha').length > 0)
    {
        ets_mp_report_g_recaptchaonloadCallback(); 
    }
}
$(document ).ajaxComplete(function( event, xhr, settings ) {
    if(xhr.responseText && xhr.responseText.indexOf("rendered_products_top")>=0)
    {

       setTimeout(function(){ets_checkRateShopby();},500);
    }
});
$(document).ready(function(){
    $(document).on('click','.ets_mp_close_popup',function(){
        $('.ets_mp_popup').hide();
        $('body').removeClass('report_popup_show');
    });
    $(document).keyup(function(e) { 
        if(e.keyCode == 27) {
            if($('.ets_mp_popup').length >0)
                $('.ets_mp_popup').hide();
        }
    });
    ets_checkRateShopby();
    $(document).mouseup(function (e)
    {
        if($('.ets_mp_popup').length >0 && !$('.ets_mp_popup').hasClass('ets_mp_shop_manager_popup'))
        {
           if (!$('.mp_pop_table').is(e.target)&& $('.mp_pop_table').has(e.target).length === 0 && !$('.ui-datepicker').is(e.target) && $('.ui-datepicker').has(e.target).length === 0 && !$('.alert').is(e.target) && $('.alert').has(e.target).length === 0)
           {
                $('.ets_mp_popup').hide();
           } 
        }
    });
    $(document).on('click','button.ets_mp_report',function(){
        $('.ets_mp_shop_report_popup').show();
        $('body').addClass('report_popup_show');
        ets_mp_refreshCapchaReport();
        return false;
    });
    $(document).on('click','button[name="submitReportShop"]',function(){
        $('button[name="submitReportShop"]').addClass('loading');
        $('button[name="submitReportShop"]').prev('.bootstrap').remove();
        $('#ets_mp_report_shop_form .form-wrapper').prev('.bootstrap').remove();
        var formData = new FormData($(this).parents('form').get(0));
        formData.append('ajax', 1);
        formData.append('submitReportShopSeller', 1);
        $.ajax({
            url: '',
            data: formData,
            type: 'post',
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(json){
                $('button[name="submitReportShop"]').removeClass('loading');
                if(json.errors)
                {
                    $('button[name="submitReportShop"]').parents('.panel-footer').prev('.form-wrapper').before(json.errors);
                }
                if(json.success)
                {
                    $.growl.notice({ message: json.success });
                    $('.ets_mp_shop_report_popup').remove();
                    $('button.ets_mp_report').remove();
                }   
                ets_mp_refreshCapchaReport(); 
            },
            error: function(xhr, status, error)
            {     
                $('button[name="submitReportShop"]').removeClass('loading');
                ets_mp_refreshCapchaReport();
            }
        });
        return false; 
    });
});