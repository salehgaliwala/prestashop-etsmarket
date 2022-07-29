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
var etsMPAddSellerName = function(event,data,formatted)
{
    if (data == null)
		return false;
  $('#id_seller').val(data[0]);
  if($('#seller_name').next('.seller_selected').length <=0)
  {
       $('#seller_name').before('<div class="seller_selected">'+data[1]+' ('+data[2]+') <span class="delete_seller_search">delete</span><div>');
       $('#seller_name').val(''); 
       $('.seller_selected').parent().addClass('has_seller');
  }
}
function etsMPDisplayFormCommissionUsage()
{
    if($('input[name="ETS_MP_ALLOW_BALANCE_TO_PAY"]').length)
    {
        if($('input[name="ETS_MP_ALLOW_BALANCE_TO_PAY"]:checked').val()==1)
            $('.form-group.usage_order').show();
        else
            $('.form-group.usage_order').hide();
        if($('input[name="ETS_MP_ALLOW_CONVERT_TO_VOUCHER"]:checked').val()==1)
            $('.form-group.usage_voucher').show();
        else
            $('.form-group.usage_voucher').hide();
        if($('input[name="ETS_MP_ALLOW_WITHDRAW"]:checked').val()==1)
            $('.form-group.usage_withdraw').show();
        else
            $('.form-group.usage_withdraw').hide();
    }
}
function etsMpDisplayFormShopGroups()
{
    if($('input[name="use_fee_global"]').length)
    {
        
        if($('input[name="use_fee_global"]:checked').val()==1)
        {
            $('.form-group.global_fee').hide();
        }
        else
        {
            $('.form-group.global_fee').show();
            if($('input[name="fee_type"]:checked').val()=='no_fee')
                $('.form-group.global_fee.ets_mp_fee').hide();
            else
                $('.form-group.global_fee.ets_mp_fee').show();
        }
        if($('input[name="use_commission_global"]:checked').val()==1)
        {
            $('.form-group.global_commission').hide();
        }
        else
        {
            $('.form-group.global_commission').show();
        }
        
    }
}
$(document).ready(function(){
    $(document).on('click','.ets_mp_map .view_map',function(){
        $('.ets_mp_map_seller').show();
        return false;
    })
    etsMpDisplayFormShopGroups();
    $(document).on('click','input[name="use_fee_global"],input[name="fee_type"],input[name="use_commission_global"]',function(){
        etsMpDisplayFormShopGroups();
    });
    etsMPDisplayFormCommissionUsage();
    $(document).on('click','input[name="ETS_MP_ALLOW_WITHDRAW"],input[name="ETS_MP_ALLOW_CONVERT_TO_VOUCHER"],input[name="ETS_MP_ALLOW_BALANCE_TO_PAY"]',function(){
        etsMPDisplayFormCommissionUsage();
    });
    if($('#active').length)
    {
        if($("#active").val()==1)
        {
            $('.form-group.seller_date').show();
        }
        else
            $('.form-group.seller_date').hide();
        if($("#active").val()==-3 || $("#active").val()==0)
        {
            $('.form-group.seller_reason').show();
        }
        else
            $('.form-group.seller_reason').hide();
    }
    $(document).on('change','#active',function(){
        if($("#active").val()==1)
        {
            $('.form-group.seller_date').show();
        }
        else
            $('.form-group.seller_date').hide();
        if($("#active").val()==-3 || $("#active").val()==0)
        {
            $('.form-group.seller_reason').show();
        }
        else
            $('.form-group.seller_reason').hide();
    });
    $(document).on('click','#shop_logo-images-thumbnails a',function(){
        if(!confirm(confim_delete_logo))
            return false;
    });
    $(document).on('click','.seller_categories .category',function(){
        if(!$(this).is(':checked'))
        {
            if($(this).parent().parent().next('.children').length)
            {
                $(this).parent().parent().next('.children').find('.category').removeAttr('checked');
            }
        }
        else
        {
            $(this).parents('.children').prev('.has-child').find('.category').attr('checked','checked');
        }
    });
    $('.seller_categories .category').each(function(){
        if($(this).parent().parent().next('.children').length)
        {
            if($(this).is(':checked'))
            {
                $(this).parent().parent().next('.children').show();
                $(this).parent().addClass('opend');
            }
            else
            {
                $(this).parent().parent().next('.children').hide();
                $(this).parent().removeClass('opend');
            }
        }   
    });
    $(document).on('click','.seller_categories .label',function(){
        $(this).parent().parent().next('.children').toggle();
        $(this).parent().toggleClass('opend');
    });
    $(document).on('click','.delete_seller_search',function(e){
        e.preventDefault();
        $('.seller_selected').remove();
        $('#id_seller').val('');
    });
    if($('.form_search_seller #seller_name').length)
    {
        $('.form_search_seller #seller_name').autocomplete(ets_link_search_seller,{
    		minChars: 3,
    		autoFill: true,
    		max:20,
    		matchContains: true,
    		mustMatch:false,
    		scroll:false,
    		cacheLength:0,
    		formatItem: function(item) {
    			return item[1]+' ('+item[2]+')';
    		}
    	}).result(etsMPAddSellerName);
    }
    setTimeout(function(){
        $('.alert.alert-success').remove(); 
        $('.module_error.alert').remove();
    }, 3000);
    $(document).on('click','.change_seller_status',function(){
       $('.ets_mp_status_seller').toggle(); 
       return false;
    });
    ets_mp_sidebar_height();
    $(window).load(function(){
        ets_mp_sidebar_height();
    });
    $(window).resize(function(){
        ets_mp_sidebar_height();
    });
    $(document).on('click','.ets_mp_close_popup',function(){
        $('.ets_mp_popup').hide();
    });
    $(document).on('click','.approve_registration',function(){
        var $html ='<div class="ets_mp_popup ets_mp_status_seller" style="display:block">';
        $html +='<div class="mp_pop_table">';                                                                            
        $html +='<div class="mp_pop_table_cell">';                                                                                
        $html +='<form id="eamFormActionRegistration" class="form-horizontal " method="POST">';
        $html +=$(this).next('.approve_registration_form').html();
        $html +='</form></div></div></div>';
        if($('.etsmp-left-panel > .ets_mp-panel').prev('.ets_mp_status_seller').length)
            $('.etsmp-left-panel > .ets_mp-panel').prev('.ets_mp_status_seller').remove();
        $('.etsmp-left-panel > .ets_mp-panel:first').before($html);
        if ($(".ets_mp_datepicker input").length > 0) {
            $('.hasDatepicker').removeClass('hasDatepicker');
    		$(".ets_mp_datepicker input").datepicker({
    			dateFormat: 'yy-mm-dd',
    		});
    	}
    });
    $(document).keyup(function(e) { 
        if(e.keyCode == 27) {
            if($('.ets_mp_popup').length >0)
                $('.ets_mp_popup').hide();
        }
    });
    $(document).mouseup(function (e)
    {
        if($('.ets_mp_popup').length >0)
        {
           if (!$('.ets_mp_popup').is(e.target)&& $('.ets_mp_popup').has(e.target).length === 0 && !$('.ui-datepicker').is(e.target) && $('.ui-datepicker').has(e.target).length === 0)
           {
                $('.ets_mp_popup').hide();
           } 
        }
        if($('#eamFormActionRegistration').length>0)
        {
            if (!$('#eamFormActionRegistration').is(e.target)&& $('#eamFormActionRegistration').has(e.target).length === 0 && !$('.ui-datepicker').is(e.target)&& $('.ui-datepicker').has(e.target).length === 0)
            {
                $('.ets_mp_popup').hide();
            } 
        }
        if($('#eamFormActionSellerBilling').length>0)
        {
            if (!$('#eamFormActionSellerBilling').is(e.target)&& $('#eamFormActionSellerBilling').has(e.target).length === 0 && !$('.ui-datepicker').is(e.target)&& $('.ui-datepicker').has(e.target).length === 0)
           {
                $('.ets_mp_popup').hide();
           } 
        }
    });
    ets_mpToggleFeePayment($('.payment_method_fee_type'));
    $(document).on('change', '.payment_method_fee_type', function (event) {
        ets_mpToggleFeePayment(this);
    });
    $(document).on('click', '.js-add-payment-method-field', function (event) {
        event.preventDefault();
        $this = $(this);
        if (typeof ets_mp_languages !== 'undefined' && ets_mp_languages) {
            ets_mpRenderFieldsMethodPayment(this, ets_mp_languages, ets_mp_currency);
        }
        else {
            $.ajax({
                url: ets_snw_link_ajax,
                type: 'GET',
                data: {
                    'getLanguage': true,
                },
                success: function (res) {
                    if (typeof res !== 'object') {
                        res = JSON.parse(res);
                    }
                    ets_mp_languages = res.languages;
                    ets_mpRenderFieldsMethodPayment($this, res.languages);
                }
            })
        }
    });
    $(document).on('click', '.js-btn-delete-field', function (event) { 
        event.preventDefault();
        if(confirm(confirm_delete_field_text)){
            $(this).closest('.payment-method-field').remove();
        }
    });
    if ($(".ets_mp_datepicker input").length > 0) {
		$(".ets_mp_datepicker input").datepicker({
			dateFormat: 'yy-mm-dd',
		});
	}
    $(document).on('click','.checkbox_all input',function(){
        if($(this).is(':checked'))
        {
            $(this).closest('.form-group').find('input').attr('checked','checked');
            $('.ets_mp_extrainput').each(function(){
                $('label[for="'+$(this).attr('id')+'_validate"]').show();
            });
        }
        else
        {
            $(this).closest('.form-group').find('input').removeAttr('checked');
            $('.ets_mp_extrainput').each(function(){
                $('label[for="'+$(this).attr('id')+'_validate"]').hide();
            });
        }
    });
    $(document).on('click','.checkbox input',function(){
        if($(this).is(':checked'))
        {
            if($(this).closest('.form-group').find('input:checked').length==$(this).closest('.form-group').find('input[type="checkbox"]').length-1)
                 $(this).closest('.form-group').find('.checkbox_all input').attr('checked','checked');
        }
        else
        {
            $(this).closest('.form-group').find('.checkbox_all input').removeAttr('checked');
        } 
    });
    $(document).on('click','.table_ets_mp_registration_fields #ETS_MP_REGISTRATION_FIELDS_all',function(e){
        if($(this).is(':checked'))
            $('.table_ets_mp_registration_fields .registration_field').attr('checked','checked');
        else
        {
            $('.table_ets_mp_registration_fields .registration_field').removeAttr('checked');
            $('.table_ets_mp_registration_fields .registration_field_validate').removeAttr('checked');
        }
        if($('.table_ets_mp_registration_fields .registration_field').length== $('.table_ets_mp_registration_fields .registration_field:checked').length)
            $('#ETS_MP_REGISTRATION_FIELDS_all').attr('checked','checked');
        else
            $('#ETS_MP_REGISTRATION_FIELDS_all').removeAttr('checked');
        if($('.table_ets_mp_registration_fields .registration_field_validate').length== $('.table_ets_mp_registration_fields .registration_field_validate:checked').length)
            $('#ETS_MP_REGISTRATION_FIELDS_VALIDATE_all').attr('checked','checked');
        else
            $('#ETS_MP_REGISTRATION_FIELDS_VALIDATE_all').removeAttr('checked');
    });
    $(document).on('click','.table_ets_mp_registration_fields #ETS_MP_REGISTRATION_FIELDS_VALIDATE_all',function(e){
        if($(this).is(':checked'))
        {
            $('.table_ets_mp_registration_fields .registration_field').attr('checked','checked');
            $('.table_ets_mp_registration_fields .registration_field_validate').attr('checked','checked');
        }
        else
           $('.table_ets_mp_registration_fields .registration_field_validate').removeAttr('checked'); 
        if($('.table_ets_mp_registration_fields .registration_field').length== $('.table_ets_mp_registration_fields .registration_field:checked').length)
            $('#ETS_MP_REGISTRATION_FIELDS_all').attr('checked','checked');
        else
            $('#ETS_MP_REGISTRATION_FIELDS_all').removeAttr('checked');
        if($('.table_ets_mp_registration_fields .registration_field_validate').length== $('.table_ets_mp_registration_fields .registration_field_validate:checked').length)
            $('#ETS_MP_REGISTRATION_FIELDS_VALIDATE_all').attr('checked','checked');
        else
            $('#ETS_MP_REGISTRATION_FIELDS_VALIDATE_all').removeAttr('checked');
    });
    $(document).on('click','.table_ets_mp_contact_fields #ETS_MP_CONTACT_FIELDS_all',function(e){
        if($(this).is(':checked'))
            $('.table_ets_mp_contact_fields .contact_field').attr('checked','checked');
        else
        {
            $('.table_ets_mp_contact_fields .contact_field').removeAttr('checked');
            $('.table_ets_mp_contact_fields .contact_field_validate').removeAttr('checked');
        }
        if($('.table_ets_mp_contact_fields .contact_field').length== $('.table_ets_mp_contact_fields .contact_field:checked').length)
            $('#ETS_MP_CONTACT_FIELDS_all').attr('checked','checked');
        else
            $('#ETS_MP_CONTACT_FIELDS_all').removeAttr('checked');
        if($('.table_ets_mp_contact_fields .contact_field_validate').length== $('.table_ets_mp_contact_fields .contact_field_validate:checked').length)
            $('#ETS_MP_CONTACT_FIELDS_VALIDATE_all').attr('checked','checked');
        else
            $('#ETS_MP_CONTACT_FIELDS_VALIDATE_all').removeAttr('checked');
    });
    $(document).on('click','.table_ets_mp_contact_fields #ETS_MP_CONTACT_FIELDS_VALIDATE_all',function(e){
        if($(this).is(':checked'))
        {
            $('.table_ets_mp_contact_fields .contact_field').attr('checked','checked');
            $('.table_ets_mp_contact_fields .contact_field_validate').attr('checked','checked');
        }
        else
           $('.table_ets_mp_contact_fields .contact_field_validate').removeAttr('checked'); 
        if($('.table_ets_mp_contact_fields .contact_field').length== $('.table_ets_mp_contact_fields .contact_field:checked').length)
            $('#ETS_MP_CONTACT_FIELDS_all').attr('checked','checked');
        else
            $('#ETS_MP_CONTACT_FIELDS_all').removeAttr('checked');
        if($('.table_ets_mp_contact_fields .contact_field_validate').length== $('.table_ets_mp_contact_fields .contact_field_validate:checked').length)
            $('#ETS_MP_CONTACT_FIELDS_VALIDATE_all').attr('checked','checked');
        else
            $('#ETS_MP_CONTACT_FIELDS_VALIDATE_all').removeAttr('checked');
    });
    $(document).on('click','.table_ets_mp_registration_fields .registration_field',function(){
        if(!$(this).is(':checked'))
        {
            $(this).parent().parent().find('.registration_field_validate').removeAttr('checked');
        }
        if($('.table_ets_mp_registration_fields .registration_field').length== $('.table_ets_mp_registration_fields .registration_field:checked').length)
            $('#ETS_MP_REGISTRATION_FIELDS_all').attr('checked','checked');
        else
            $('#ETS_MP_REGISTRATION_FIELDS_all').removeAttr('checked');
        if($('.table_ets_mp_registration_fields .registration_field_validate').length== $('.table_ets_mp_registration_fields .registration_field_validate:checked').length)
            $('#ETS_MP_REGISTRATION_FIELDS_VALIDATE_all').attr('checked','checked');
        else
            $('#ETS_MP_REGISTRATION_FIELDS_VALIDATE_all').removeAttr('checked');
    });
    $(document).on('click','.table_ets_mp_registration_fields .registration_field_validate',function(){
        if($(this).is(':checked'))
        {
            $(this).parent().parent().find('.registration_field').attr('checked','checked');
        }
        if($('.table_ets_mp_registration_fields .registration_field').length== $('.table_ets_mp_registration_fields .registration_field:checked').length)
            $('#ETS_MP_REGISTRATION_FIELDS_all').attr('checked','checked');
        else
            $('#ETS_MP_REGISTRATION_FIELDS_all').removeAttr('checked');
        if($('.table_ets_mp_registration_fields .registration_field_validate').length== $('.table_ets_mp_registration_fields .registration_field_validate:checked').length)
            $('#ETS_MP_REGISTRATION_FIELDS_VALIDATE_all').attr('checked','checked');
        else
            $('#ETS_MP_REGISTRATION_FIELDS_VALIDATE_all').removeAttr('checked');
    });
    //
    $(document).on('click','.table_ets_mp_contact_fields .contact_field',function(){
        if(!$(this).is(':checked'))
        {
            $(this).parent().parent().find('.contact_field_validate').removeAttr('checked');
        }
        if($('.table_ets_mp_contact_fields .contact_field').length== $('.table_ets_mp_contact_fields .contact_field:checked').length)
            $('#ETS_MP_CONTACT_FIELDS_all').attr('checked','checked');
        else
            $('#ETS_MP_CONTACT_FIELDS_all').removeAttr('checked');
        if($('.table_ets_mp_contact_fields .contact_field_validate').length== $('.table_ets_mp_contact_fields .contact_field_validate:checked').length)
            $('#ETS_MP_CONTACT_FIELDS_VALIDATE_all').attr('checked','checked');
        else
            $('#ETS_MP_CONTACT_FIELDS_VALIDATE_all').removeAttr('checked');
    });
    $(document).on('click','.table_ets_mp_contact_fields .contact_field_validate',function(){
        if($(this).is(':checked'))
        {
            $(this).parent().parent().find('.contact_field').attr('checked','checked');
        }
        if($('.table_ets_mp_contact_fields .contact_field').length== $('.table_ets_mp_contact_fields .contact_field:checked').length)
            $('#ETS_MP_CONTACT_FIELDS_all').attr('checked','checked');
        else
            $('#ETS_MP_CONTACT_FIELDS_all').removeAttr('checked');
        if($('.table_ets_mp_contact_fields .contact_field_validate').length== $('.table_ets_mp_contact_fields .contact_field_validate:checked').length)
            $('#ETS_MP_CONTACT_FIELDS_VALIDATE_all').attr('checked','checked');
        else
            $('#ETS_MP_CONTACT_FIELDS_VALIDATE_all').removeAttr('checked');
    });
    if($('.confi_tab.active').length >0)
    {
        $('.ets_mp_form:not(.'+$('.confi_tab.active').data('tab-id')+')').hide();
    }
    $(document).on('click','input[name="ETS_MP_SAVE_CRONJOB_LOG"]',function(){
        $.ajax({
			type: 'POST',
			headers: { "cache-control": "no-cache" },
			url: '',
			async: true,
			cache: false,
			dataType : "json",
			data:'ETS_MP_SAVE_CRONJOB_LOG='+$('input[name="ETS_MP_SAVE_CRONJOB_LOG"]:checked').val(),
			success: function(json)
			{
                if(json.success)
                {
                    $.growl.notice({ message: json.success });
                }
                if(json.errors)
                {
                    $.growl.error({message:json.errors});
                }
            }
		});
    });
    $(document).on('click','.confi_tab',function(){
        if(!$(this).hasClass('active'))
        {
            $('.confi_tab').removeClass('active');
            $(this).addClass('active');
            $('.ets_mp_form.'+$('.confi_tab.active').data('tab-id')).show();
            $('.ets_mp_form:not(.'+$('.confi_tab.active').data('tab-id')+')').hide();
            $('#current_tab').val($('.confi_tab.active').data('tab-id'));
        }
    });
    $(document).on('click','.ets_mp_extrainput',function(){
        $('label[for="'+$(this).attr('id')+'_validate"]').toggle();
    });
    $(document).on('click','input[name="ETS_MP_DISPLAY_FOLLOWED_SHOP"],input[name="ETS_MP_DISPLAY_PRODUCT_FOLLOWED_SHOP"],input[name="ETS_MP_DISPLAY_PRODUCT_TRENDING_SHOP"],input[name="ETS_MP_ENABLE_MAP"], input[name="ETS_MP_SEARCH_ADDRESS_BY_GOOGLE"],input[name="ETS_MP_ENABLE_CAPTCHA"],SELECT[name="ETS_MP_ENABLE_CAPTCHA_TYPE"],input[name="ETS_MP_SELLER_CREATE_PRODUCT_ATTRIBUTE"],input[name="ETS_MP_APPLICABLE_CATEGORIES"],input[name="ETS_MP_SELLER_FEE_TYPE"],input[name="ETS_MP_SELLER_CAN_CHANGE_ORDER_STATUS"]',function(){
        ets_mp_displayFormInput();
    });
    $('.form-group.commission_status input[type="checkbox"]').each(function(){
        if($(this).is(':checked'))
        {
            $('.form-group.commission_status input[type="checkbox"][value="'+$(this).val()+'"]:not(#'+$(this).attr('id')+')').parent().parent().hide();
        } 
    });
    $(document).on('click','.form-group.commission_status input[type="checkbox"]',function(){
        if($(this).is(':checked'))
        {
            if($('.form-group.commission_status input[value="'+$(this).val()+'"]:not(#'+$(this).attr('id')+'):checked').length>0)
            {
                $(this).prop('checked', false);
                showErrorMessage('Duplicate item');
            }
            else
                $('.form-group.commission_status input[value="'+$(this).val()+'"]:not(#'+$(this).attr('id')+')').parent().parent().hide();
        }
        else
            $('.form-group.commission_status input[value="'+$(this).val()+'"]:not(#'+$(this).attr('id')+')').parent().parent().show();
    });
    $(document).on('click','.ets_mp-panel .list-action',function(){
        if(!$(this).hasClass('disabled'))
        {            
            $(this).addClass('disabled');
            var $this= $(this);
            $.ajax({
                url: $(this).attr('href')+'&ajax=1',
                data: {},
                type: 'post',
                dataType: 'json',                
                success: function(json){ 
                    if(json.success)
                    {
                        if(json.enabled=='1')
                        {
                            $this.removeClass('action-disabled').addClass('action-enabled');
                            $this.html('<i class="icon-check"></i>');
                        }                        
                        else
                        {
                            $this.removeClass('action-enabled').addClass('action-disabled');
                            $this.html('<i class="icon-remove"></i>');
                        }
                        $this.attr('href',json.href);
                        $this.removeClass('disabled');
                        if(json.title)
                            $this.attr('title',json.title); 
                        $.growl.notice({ message: json.success }); 
                    }
                    if(json.errors)
                        $.growl.error({message:json.errors});
                        
                                                                
                },
                error: function(error)
                {                                      
                    $this.removeClass('disabled');
                }
            });
        }
        return false;
    });
    ets_mp_displayFormInput();
    if($('#list-payment-methods').length)
    {
        var $mypayment = $("#list-payment-methods");
    	$mypayment.sortable({
    		opacity: 0.6,
            handle: ".eam-active-sortable",
            cursor: 'move',
    		update: function() {
    			var order = $(this).sortable("serialize") + "&action=updatePaymentMethodOrdering";	
                var $this=  $(this);					
                $.ajax({
        			type: 'POST',
        			headers: { "cache-control": "no-cache" },
        			url: '',
        			async: true,
        			cache: false,
        			dataType : "json",
        			data:order,
        			success: function(json)
        			{
                        if(json.success)
                        {
                            $.growl.notice({ message: json.success });
                            var i=1;
                            $('#list-payment-methods tr').each(function(){
                                $(this).find('.sort-order').html(i);
                                i++;
                            });
                        }
                        if(json.errors)
                        {
                            $.growl.error({message:json.errors});
                            $mypayment.sortable("cancel");
                        }
                    }
        		});
    		},
        	stop: function( event, ui ) {
       		}
    	});
    }
    if($('#eam_method_fields_append').length)
    {
        var $myfield = $("#eam_method_fields_append");
    	$myfield.sortable({
    		opacity: 0.6,
            cursor: 'move',
    		update: function() {
    			var order = $(this).sortable("serialize") + "&action=updatePaymentMethodFieldOrdering";	
                var $this=  $(this);					
                $.ajax({
        			type: 'POST',
        			headers: { "cache-control": "no-cache" },
        			url: '',
        			async: true,
        			cache: false,
        			dataType : "json",
        			data:order,
        			success: function(json)
        			{
                        if(json.success)
                        {
                            $.growl.notice({ message: json.success });
                        }
                        if(json.errors)
                        {
                            $.growl.error({message:json.errors});
                            $myfield.sortable("cancel");
                        }
                    }
        		});
    		},
        	stop: function( event, ui ) {
       		}
    	});
    }
    $(document).on('change','#eamFormActionCommissionUser select[name="action"]',function(){
        if($(this).val()=='deduct')
        {
            $('button[name="deduct_commission_by_admin"]').show();
            $('button[name="add_commission_by_admin"]').hide();
            $('textarea[name="reason"]').val(reason_deducted_text);
        }
        else
        {
            $('button[name="deduct_commission_by_admin"]').hide();
            $('button[name="add_commission_by_admin"]').show();
            $('textarea[name="reason"]').val(reason_added_text);
        }
    });
    $(document).on('click','input[name="etsmpSubmitUpdateToken"]',function(){
        $.ajax({
			type: 'POST',
			headers: { "cache-control": "no-cache" },
			url: '',
			async: true,
			cache: false,
			dataType : "json",
			data:'etsmpSubmitUpdateToken=1&ETS_MP_CRONJOB_TOKEN='+$('#ETS_MP_CRONJOB_TOKEN').val(),
			success: function(json)
			{
                if(json.success)
                {
                    $.growl.notice({ message: json.success });
                    $('.js-emp-test-cronjob').attr('data-secure',$('#ETS_MP_CRONJOB_TOKEN').val());
                    $('.emp-cronjob-secure-value').html($('#ETS_MP_CRONJOB_TOKEN').val());
                }
                if(json.errors)
                {
                    $.growl.error({message:json.errors});
                }
            }
		});
    });
    $(document).on('click','.js-emp-test-cronjob',function(e){
        e.preventDefault();
        if(!$(this).hasClass('loading'))
        {   $(this).addClass('loading');
            var url_ajax= $(this).attr('href');
            var secure = $(this).attr('data-secure');
            $.ajax({
    			type: 'POST',
    			headers: { "cache-control": "no-cache" },
    			url: url_ajax,
    			async: true,
    			cache: false,
    			dataType : "json",
    			data:'ajax=1&secure='+secure,
    			success: function(json)
    			{
                    if(json.success)
                    {
                        $.growl.notice({ message: json.success });
                        $('.cronjob_log').val(json.cronjob_log);
                    }
                    if(json.errors)
                    {
                        $.growl.error({message:json.errors});
                    }
                    $('.js-emp-test-cronjob').removeClass('loading');
                }
    		});
        }
        
    });
    $(document).on('click','button[name="etsmpSubmitClearLog"]',function(e){
        e.preventDefault();
        $(this).addClass('loading');
        $.ajax({
			type: 'POST',
			headers: { "cache-control": "no-cache" },
			url: '',
			async: true,
			cache: false,
			dataType : "json",
			data:'ajax=1&etsmpSubmitClearLog=1',
			success: function(json)
			{
                if(json.success)
                {
                    $.growl.notice({ message: json.success });
                    $('.cronjob_log').val('');
                }
                if(json.errors)
                {
                    $.growl.error({message:json.errors});
                }
                $('button[name="etsmpSubmitClearLog"]').removeClass('loading');
            }
		});
    });
});
function ets_mp_sidebar_height()
{
    var sidebar_height = $('.etsmp-left-panel.etsmp-sidebar-menu').height();
    $('.etsmp-sidebar-height').height(sidebar_height);
}
function ets_mp_displayFormInput()
{
    if($('input[name="ETS_MP_SELLER_FEE_TYPE"]').length)
    {
        if($('input[name="ETS_MP_SELLER_FEE_TYPE"]:checked').val()!='no_fee')
        {
            $('.form-group.ets_mp_fee').show();
            $('.form-group.ets_mp_no_fee').hide();
        }
        else
        {
            $('.form-group.ets_mp_fee').hide();
            $('.form-group.ets_mp_no_fee').show();
        }
    }
    if($('input[name="ETS_MP_SELLER_CAN_CHANGE_ORDER_STATUS"]:checked').val()==1)
        $('.form-group.ets_mp_allowed_statuses').show();
    else
        $('.form-group.ets_mp_allowed_statuses').hide();
    if($('input[name="ETS_MP_APPLICABLE_CATEGORIES"]:checked').val()=='all_product_categories')
    {
        $('.form-group.seller_categories').hide();
    }
    else
    {
        $('.form-group.seller_categories').show();
    }
    if($('input[name="ETS_MP_ENABLE_CAPTCHA"]:checked').val()==1)
    {
        $('.form-group.captcha').show();
        if($('#ETS_MP_ENABLE_CAPTCHA_TYPE').val()=='google_v2')
            $('.form-group.captcha.v3').hide();
        else
            $('.form-group.captcha.v2').hide();
    }
    else
        $('.form-group.captcha').hide();
    if($('input[name="ETS_MP_SEARCH_ADDRESS_BY_GOOGLE"]:checked').val()==0)
        $('.form-group.map.search').hide();
    else
        $('.form-group.map.search').show();
    if($('input[name="ETS_MP_DISPLAY_FOLLOWED_SHOP"]:checked').val()==0)
        $('.form-group.shop_home').hide();
    else
        $('.form-group.shop_home').show();
    if($('input[name="ETS_MP_DISPLAY_PRODUCT_FOLLOWED_SHOP"]:checked').val()==0)
        $('.form-group.shop_product_home').hide();
    else
        $('.form-group.shop_product_home').show();
    if($('input[name="ETS_MP_DISPLAY_PRODUCT_TRENDING_SHOP"]:checked').val()==0)
        $('.form-group.trending_product_home').hide();
    else
        $('.form-group.trending_product_home').show();
    if($('input[name="ETS_MP_SELLER_CREATE_PRODUCT_ATTRIBUTE"]:checked').val()==0)
        $('.form-group.create_attribute').hide();
    else
        $('.form-group.create_attribute').show()
}
function ets_mpHideOtherLang(id_lang) {
    $('.trans_field').addClass('hidden');
    $('.trans_field_' + id_lang).removeClass('hidden');
}
function ets_mpToggleFeePayment(input) {
    if (typeof input !== 'object') {
        input = $(input + ':selected');
    }
    if ($(input).val() == 'FIXED') {
        $(input).closest('.payment-method').find('.payment_method_fee_percent').closest('.form-group').hide();
        $(input).closest('.payment-method').find('.payment_method_fee_fixed').closest('.form-group').show();
    }
    else if ($(input).val() == 'PERCENT') {
        $(input).closest('.payment-method').find('.payment_method_fee_percent').closest('.form-group').show();
        $(input).closest('.payment-method').find('.payment_method_fee_fixed').closest('.form-group').hide();
    }
    else {
        $(input).closest('.payment-method').find('.payment_method_fee_percent').closest('.form-group').hide();
        $(input).closest('.payment-method').find('.payment_method_fee_fixed').closest('.form-group').hide();
    }
}
function ets_mpRenderFieldsMethodPayment(input, langs, currency) {
    var date = new Date();
    var rand_num = parseInt(date.getTime());
    method_name_html = '';
    method_name_html += '<div class="form-group payment-method-field">';
        method_name_html += '<div class="form-group row">';
            method_name_html += '<label class="control-label required col-lg-3">' + method_field_title + '</label>';
            method_name_html += '<div class="col-lg-6">';
            for (var l = 0; l < langs.length; l++) {
                lang = langs[l];
                method_name_html += '<div class="form-group row trans_field trans_field_' + lang.id_lang + ' ' + (l > 0 ? 'hidden' : '') + '">';
                method_name_html += '<div class="col-lg-9">';
                method_name_html += '<input type="text" name="payment_method_field['+rand_num+'][title][' + lang.id_lang + ']" value="" class="form-control '+(lang.id_lang == currency.id ? 'required' : '')+'" data-error="'+pmf_title_required+'">';
                method_name_html += '</div>';
                method_name_html += '<div class="col-lg-2">';
                method_name_html += '<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">';
                method_name_html += lang.iso_code + ' ';
                method_name_html += '<span class="caret"></span>';
                method_name_html += '</button>';
                method_name_html += '<ul class="dropdown-menu">';
                for (var i = 0; i < langs.length; i++) {
                    method_name_html += '<li><a href="javascript:ets_mpHideOtherLang(' + langs[i].id_lang + ')" title="">' + langs[i].name + '</a></li>';
                }
                method_name_html += '</ul>';
                method_name_html += '</div>';
                method_name_html += '</div>';
            }
            method_name_html += '</div>';
        method_name_html += '</div>';
    method_name_html += '<div class="form-group row">';
    method_name_html += '<label class="control-label col-lg-3">' + method_field_type + '</label>';
    method_name_html += '<div class="col-lg-5">';
    method_name_html += '<select name="payment_method_field['+rand_num+'][type]" class="form-control">';
    method_name_html += '<option value="text" selected>Text</option>';
    method_name_html += '<option value="textarea">Textarea</option>';
    /*method_name_html += '<option value="checkbox">Checkbox</option>';
    method_name_html += '<option value="select">Selection</option>';*/
    method_name_html += '</select>';
    method_name_html += '</div>';
    method_name_html += '</div>';
    method_name_html += '<div class="form-group row">';
        method_name_html += '<label class="control-label col-lg-3">' + method_description_text + '</label>';
        method_name_html += '<div class="col-lg-6">';
        for (var l = 0; l < langs.length; l++) {
            lang = langs[l];
            method_name_html += '<div class="form-group row trans_field trans_field_' + lang.id_lang + ' ' + (l > 0 ? 'hidden' : '') + '">';
            method_name_html += '<div class="col-lg-9">';
            method_name_html += '<textarea name="payment_method_field['+rand_num+'][description][' + lang.id_lang + ']" class="form-control"></textarea>';
            method_name_html += '</div>';
            method_name_html += '<div class="col-lg-2">';
            method_name_html += '<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">';
            method_name_html += lang.iso_code + ' ';
            method_name_html += '<span class="caret"></span>';
            method_name_html += '</button>';
            method_name_html += '<ul class="dropdown-menu">';
            for (var i = 0; i < langs.length; i++) {
                method_name_html += '<li><a href="javascript:ets_mpHideOtherLang(' + langs[i].id_lang + ')" title="">' + langs[i].name + '</a></li>';
            }
            method_name_html += '</ul>';
            method_name_html += '</div>';
            method_name_html += '</div>';
        }
        method_name_html += '</div>';   
        method_name_html += '<div class="col-lg-1">';
            method_name_html += '<a class="btn btn-default btn-sm btn-delete-field js-btn-delete-field" href="javascript:void(0)"><i class="fa fa-trash"></i> ' + delete_text + '</a>';
        method_name_html += '</div>';
    method_name_html += '</div>';
    
    method_name_html += '<div class="form-group row ">';
        method_name_html += '<label class="control-label col-lg-3">'+required_text+'</label>';
        method_name_html += '<div class="col-lg-3">';
            method_name_html += '<select name="payment_method_field['+rand_num+'][required]" class="form-control">';
                method_name_html += '<option value="1">'+yes_text+'</option>';
                method_name_html += '<option value="0">'+no_text+'</option>';
            method_name_html += '</select>';
        method_name_html += '</div>';
    method_name_html +=  '</div>';

    method_name_html += '<div class="form-group row ">';
        method_name_html += '<label class="control-label col-lg-3">'+Enabled_text+'</label>';
        method_name_html += '<div class="col-lg-9">';
            method_name_html +=  '<span class="switch prestashop-switch fixed-width-lg">';
            method_name_html += '<input type="radio" name="payment_method_field['+rand_num+'][enable]" id="payment_method_field_'+rand_num+'_enable_on" value="1" class="payment_method_field_enable" checked="checked">';
            method_name_html += '<label for="payment_method_field_'+rand_num+'_enable_on">'+yes_text+'</label>';
            method_name_html += '<input type="radio" name="payment_method_field['+rand_num+'][enable]" id="payment_method_field_'+rand_num+'_enable_off" class="payment_method_field_enable" value="0">';
            method_name_html += '<label for="payment_method_field_'+rand_num+'_enable_off">'+no_text+'</label>';
            method_name_html += '<a class="slide-button btn"></a>';
            method_name_html += '</span>'
        method_name_html += '</div>';
    method_name_html +=  '</div>';
    
    method_name_html += '</div>';

    $(input).closest('.form-group').before(method_name_html);
}
$(document).on('click','.action_approve_registration',function(){
    ets_mp_registrationUpdateStatus($(this),$(this).data('id'),1);
});
$(document).on('click','button[name="saveStatusRegistration"]',function(e){
    e.preventDefault();
    ets_mp_registrationUpdateStatus($(this),$('#eamFormActionRegistration input[name="id_registration"]').val(),$('#eamFormActionRegistration input[name="active_registration"]').val());
});
$(document).on('click','button[name="saveStatusSeller"]',function(e){
    e.preventDefault();
    var $this= $(this);
    if(!$this.hasClass('loading'))
    {
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
                    $.growl.notice({ message: json.success });
                    if($('#list-ets_seller').length>0)
                    {
                        $('tr[data-id="'+json.id_seller+'"]').find('.active').html(json.status);
                        $('tr[data-id="'+json.id_seller+'"] .action_approve_seller').parent().show();
                        $('tr[data-id="'+json.id_seller+'"] .action_decline_seller').parent().show();
                        $('tr[data-id="'+json.id_seller+'"] .action_disable_seller').parent().show();
                        if(json.active==1)
                        {
                            $('tr[data-id="'+json.id_seller+'"] .action_approve_seller').parent().hide();
                        }
                        if(json.active==0)
                            $('tr[data-id="'+json.id_seller+'"] .action_disable_seller').parent().hide();
                        if(json.active!=-1)
                            $('tr[data-id="'+json.id_seller+'"] .action_decline_seller').parent().hide();
                        if(json.payment_verify)
                            $('tr[data-id="'+json.id_seller+'"] .payment_verify').html(json.payment_verify);
                        //else
                        //    $('tr[data-id="'+json.id_seller+'"] .payment_verify').html('--');
                    }
                    else
                    {
                        if(json.payment_verify)
                            $('.payment_verify').html(json.payment_verify);
                        $('.seller-status').html(json.status);
                        $('.action_approve_seller').show();
                        $('.action_decline_seller').show();
                        $('.action_disable_seller').show();
                        if(json.active==1)
                        {
                            $('.action_approve_seller').hide();
                            $('.change_date_seller').show();
                            $('.date_seller_approve').html(json.date_approved); 
                        }
                        else
                            $('.change_date_seller').hide();
                        if(json.active==0)
                            $('.action_disable_seller').hide();
                        if(json.active!=-1)
                            $('.action_decline_seller').hide();
                    }
                    $('.ets_mp_popup').hide();
                    
                }
                if(json.errors)
                {
                    $.growl.error({message:json.errors});
                }
                
            },
            error: function(xhr, status, error)
            {
                $this.removeClass('loading');            
            }
        });
    }
});
function ets_mp_registrationUpdateStatus($this,id_registration,active_registration){
    $this.addClass('loading');
    $.ajax({
		type: 'POST',
		headers: { "cache-control": "no-cache" },
		url: '',
		async: true,
		cache: false,
		dataType : "json",
		data:'ajax=1&saveStatusRegistration=1&id_registration='+id_registration+'&active_registration='+active_registration+'&reason='+$('#eamFormActionRegistration textarea[name="reason"]').val(),
		success: function(json)
		{
            if(json.success)
            {
                $.growl.notice({ message: json.success });
                if($('#list-ets_registration').length>0)
                {
                    $('tr[data-id="'+json.id_seller+'"]').find('.active').html(json.status);
                    if(active_registration==1)
                    {
                        $('tr[data-id="'+json.id_seller+'"]').find('.action_approve_registration').hide();
                        if(json.seller)
                        {
                            $('tr[data-id="'+json.id_seller+'"]').find('.action_decline_registration').hide();
                            $('tr[data-id="'+json.id_seller+'"]').find('.approve_registration.declined').hide();
                        }
                        else
                        {
                            $('tr[data-id="'+json.id_seller+'"]').find('.action_decline_registration').show();
                            $('tr[data-id="'+json.id_seller+'"]').find('.approve_registration.declined').show();
                        }
                    }
                    else
                    {
                        $('tr[data-id="'+json.id_seller+'"]').find('.action_decline_registration').hide();
                        $('tr[data-id="'+json.id_seller+'"]').find('.approve_registration.declined').hide();
                        $('tr[data-id="'+json.id_seller+'"]').find('.action_approve_registration').show();
                    }
                }
                else
                {
                    $('.registration-status').html(json.status);
                    if(active_registration==1)
                    {
                        $('.action_approve_registration').hide();
                        if(json.seller)
                        {
                            $('.action_decline_registration').hide();
                            $('.approve_registration.declined').hide();
                        }
                        else
                        {
                            $('.action_decline_registration').show();
                            $('.approve_registration.declined').show();
                        }
                    }
                    else
                    {
                        $('.action_decline_registration').hide();
                        $('.approve_registration.declined').hide();
                        $('.action_approve_registration').show();
                    }
                }
                $('.ets_mp_popup').hide();
                
            }
            if(json.errors)
            {
                $.growl.error({message:json.errors});
            }
            $this.removeClass('loading');
        }
	});  
}
$(document).on('change','input[name="shop_logo"],input[name="logo"]',function(){
    var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];
    if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) != -1) {
        ets_mp_readShopLogoURL(this);            
    }
});
$(document).on('change','input.shop_banner',function(){
    var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];
    if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) != -1) {
        ets_mp_readShopBannerURL(this);            
    }
});
$(document).on('change','input[name="badge_image"],input[name="ETS_MP_GOOGLE_MAP_LOGO"]',function(){
    var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];
    if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) != -1) {
        ets_mp_readLevelBadgeImageURL(this);            
    }
});
$(document).on('click','.delete_logo_upload',function(e){
    e.preventDefault(); 
    $(this).parent().remove();
    $('input[name="shop_logo"]').val('');
    $('input[name="logo"]').val('');
    $('#shop_logo-images-thumbnails').show(); 
});
$(document).on('click','.delete_banner_upload',function(e){
    e.preventDefault(); 
    $(this).parent().prev('.uploaded_img_wrapper').show(); 
    $(this).parent().parent().find('.shop_banner').val('');
    $(this).parent().parent().find('input[name="filename"]').val('');
    $(this).parent().remove();
    
});
$(document).on('click','.delete_badge_image_upload',function(e){
    e.preventDefault(); 
    if($('.form-group.badge_image').next('.ets_uploaded_img_wrapper').length)
        $('.form-group.badge_image').next('.ets_uploaded_img_wrapper').show();
    $('.form-group.badge_image input').val('');
    $(this).parent().remove();
    
});
function ets_mp_readShopLogoURL(input){
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            if($(input).prev('.shop_logo').length <= 0)
            {
                $(input).before('<div class="shop_logo"><img class="ets_mp_shop_logo" src="'+e.target.result+'" width="160px"><a class="btn btn-default delete_logo_upload" href=""><i class="fa fa-trash"></i> Delete</a></div>');
            }
            else
            {
                $(input).prev('.shop_logo').find('.ets_mp_shop_logo').attr('src',e.target.result);
            }
            $('#shop_logo-images-thumbnails').hide();                          
        }
        reader.readAsDataURL(input.files[0]);
    }
}
function ets_mp_readShopBannerURL(input)
{
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            if($(input).parent().prev('.shop_banner').length <= 0)
            {
                $(input).parent().before('<div class="shop_banner"><img class="ets_mp_shop_banner" src="'+e.target.result+'" width="160px"><a class="btn btn-default delete_banner_upload" href=""><i class="fa fa-trash"></i></a></div>');
            }
            else
            {
                $(input).parent().prev('.shop_banner').find('.ets_mp_shop_banner').attr('src',e.target.result);
            }
            $(input).parent().parent().find('.uploaded_img_wrapper').hide();                          
        }
        reader.readAsDataURL(input.files[0]);
    }
}
function ets_mp_readLevelBadgeImageURL(input)
{
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            if($(input).closest('.form-group.badge_image').next('.ets_uploaded_img_wrapper').length)
                $(input).closest('.form-group.badge_image').next('.ets_uploaded_img_wrapper').hide(); 
            if($(input).closest('.form-group.badge_image .col-lg-9').find('.level_badge_image').length <= 0)
            {
                $(input).closest('.form-group.badge_image .col-lg-9').append('<div class="level_badge_image"><img class="ets_mp_level_badge_image" src="'+e.target.result+'" style="display: inline-block; max-width: 200px;"><a class="btn btn-default delete_badge_image_upload" href="" title="Delete"><i class="fa fa-trash"></i></a></div>');
            }
            else
            {
                $(input).closest('.form-group.badge_image').find('.ets_mp_level_badge_image').attr('src',e.target.result);
            }                       
        }
        reader.readAsDataURL(input.files[0]);
    }
}