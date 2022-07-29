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
var change_select_tax_group= false;
var change_select_tax_group2= false;
if($('input.color').length>0)
{
    $.fn.mColorPicker.defaults.imageFolder =  colorImageFolder;
}
$(document).ready(function(){
    $(document).on('click','.custom-select-supplier-currency option',function(){
        $(this).closest('tr').find('.input-group-text.currency').html($(this).data('symbol'));
    });
    $(document).on('click','.media-body .delete_related',function(){
        if(confirm(delete_item_comfirm))
            $(this).closest('.media').remove();
    });
    $(document).on('click','#add-related-product-button',function(){
        $(this).hide();
        $('#related-content').removeClass('hide');
    });
    $(document).on('click','#reset_related_product',function(){
           if(confirm(delete_file_comfirm))
           {
                $('#add-related-product-button').show();
                $('#related-content').addClass('hide');
                $('#form_step1_related_products-data').html('');
           } 
    });
    if($('#form_step6_suppliers input[type="checkbox"]:checked').length)
        $('#supplier_combination_collection').show();
    else
        $('#supplier_combination_collection').hide();
    $(document).on('click','#form_step6_suppliers input[type="checkbox"]',function(){
        if($('#form_step6_suppliers input[type="checkbox"]:checked').length)
            $('#supplier_combination_collection').show();
        else
            $('#supplier_combination_collection').hide();
        var id_supplier = $(this).val();
        if($(this).is(':checked'))
        {
            /*$('#form_step6_default_supplier_'+id_supplier).show();
            $('#form_step6_default_supplier_'+id_supplier).parents('.radio#uniform-form_step6_default_supplier_'+id_supplier).show();*/
            $('#form_step6_default_supplier_'+id_supplier).show();
            $('#uniform-form_step6_default_supplier_'+id_supplier).show();
            $.ajax({
                url: '',
                data:'id_supplier='+id_supplier+'&refreshProductSupplierCombinationForm=1&id_product='+$('#ets_mp_id_product').val(),
                type: 'post',
                dataType: 'json',                
                success: function(json){ 
                    if(json.html_form)
                        $('#supplier_combination_collection .row').append(json.html_form);                                 
                },
                error: function(error)
                {                                      
                    
                }
            });
        }
        else
        {
            $('#supplier_combination_'+id_supplier).parent().remove();
            $('#form_step6_default_supplier_'+id_supplier).hide();
        }
    });
    $(document).on('keydown','#search_shop_address',function(e){
        if (e.keyCode === 13) {
            return false;
        }
    });
    $('.ets_mp_content_left').find('.filter.row_hover').attr('autocomplete','off');
    $(document).on('change','input[name="vacation_mode"],select[name="vacation_type"]',function(){
        ets_mpChanegVacationMode();
    });
    ets_mpChanegVacationMode();
    if($('#product_type option').length==1)
        $('.product_type_select').hide();
    ets_mp_moveblocksearchPageShop();
    $(document).on('change input','#ets_mp_manager_shop_form input[name="email"]',function(){
        $.ajax({
            url: '',
            data: 'searchCustomerByEmail&email='+$('#ets_mp_manager_shop_form input[name="email"]').val(),
            type: 'post',
            dataType: 'json',
            success: function(json){
                if($('#ets_mp_manager_shop_form input[name="email"]').next('.customer_name').length)
                    $('#ets_mp_manager_shop_form input[name="email"]').next('.customer_name').html(json.customer_name);
                else
                    $('#ets_mp_manager_shop_form input[name="email"]').after('<span class="customer_name">'+json.customer_name+'</span>');
            },
            error: function(xhr, status, error)
            {     
                
            }
        });
    });
    $(document).on('click', '.products-sort-order .sort-by', function(){
       $('.ets_mp_sort_by_dropdown_ul').toggleClass('active'); 
    });
    $(document).on('click', '.ets_mp_block-categories_arrow', function(){
       $(this).stop().toggleClass('active').next().toggleClass('active');
    });
    $(document).mouseup(function (e)
    {
        if ( $('.ets_mp_tabs_content_link_all').has(e.target).length === 0 && $('.ets_mp_block-categories').has(e.target).length === 0 && $('.ets_mp_block-categories').hasClass('active') ) {
            $('.ets_mp_block-categories').removeClass('active');
            $('.ets_mp_block-categories_arrow').removeClass('active');
        }
    });
    $(document).on('click', '.col_search_icon', function(){
       $(this).next().toggleClass('active'); 
    });
    $(document).mouseup(function (e)
    {
        if ( $('.ets_mp_tabs .block-search').has(e.target).length === 0 && $('.col_search').has(e.target).length === 0 && $('.col_search').hasClass('active') ) {
            $('.col_search').removeClass('active');
        }
    });
    $(document).mouseup(function (e)
    {
        if ( $('.products-sort-order').has(e.target).length === 0 && $('.ets_mp_sort_by_dropdown_ul').has(e.target).length === 0 && $('.ets_mp_sort_by_dropdown_ul').hasClass('active') ) {
            $('.ets_mp_sort_by_dropdown_ul').removeClass('active');
        }
    });
    $(document).on('click','.ets_mp_sort_by_dropdown_ul li',function(){
        if ( $(this).hasClass('selected') ){
            return false;
        } else {
            var dropdown_li = $(this).attr('data-value');
            $('.ets_mp_sort_by_dropdown_ul li').removeClass('selected');
            $('.ets_mp_sort_by_product_list option').removeClass('selected');
            $(this).parent().next().val(dropdown_li).trigger('change');
            $('.product_tab.ets_mp_shop_tab.active .ets_mp_sort_by_product_list').val(dropdown_li).trigger('change');
            $(this).addClass('selected');
        }
    });
    $(document).on('click','.ets_mp_cat-arrow',function(e){
        e.preventDefault(); 
        $(this).stop().parent().toggleClass('active').next().toggleClass('active');
    });
    $(document).on('click','.ets_mp_contact_marketplace',function(e){
        e.preventDefault(); 
        $('.ets_mp_popup.ets_mp_billing_popup').show();
        $('#id_billing_contact').val($(this).data('id-billing'));
        $('#biling_contact_paid_invoice_off').click();
        $('#biling_contact_message').val('');
        $('#biling_contact_subject').val('');
    });
    $(document).on('click','button[name="submitContactMarketplace"]',function(e){
        e.preventDefault(); 
        if(!$('button[name="submitContactMarketplace"]').hasClass('loading'))
        {
            $('button[name="submitContactMarketplace"]').addClass('loading');
            $('button[name="submitContactMarketplace"]').prev('.bootstrap').remove();
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
                    $('button[name="submitContactMarketplace"]').removeClass('loading');
                    if(json.errors)
                    {
                        $('button[name="submitContactMarketplace"]').parents('.ets_mp_popup').find('.form-wrapper').prepend(json.errors);
                    }
                    if(json.success)
                    {
                        $.growl.notice({ message: json.success });
                        $('.ets_mp_popup').hide();
                        if(json.seller_confirm==1)
                        {
                            $('.ets_mp_contact_marketplace[data-id-billing="'+json.id_billing+'"]').remove();
                            $('tr[data-id="'+json.id_billing+'"] .ets_mp_status.pending').append(' ('+json.seller_confirm_text+')');
                        }
                    }    
                },
                error: function(xhr, status, error)
                {     
                    $('button[name="submitContactMarketplace"]').removeClass('loading');
                }
            });
        }
    });
    $(document).on('click','button[name="submitSaveManagerShop"]',function(e){
        e.preventDefault(); 
        if(!$('button[name="submitSaveManagerShop"]').hasClass('loading'))
        {
            $('button[name="submitSaveManagerShop"]').addClass('loading');
            $('button[name="submitSaveManagerShop"]').parents('#ets_mp_manager_shop_form').find('.form-wrapper').prev('.bootstrap').remove();
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
                    $('button[name="submitSaveManagerShop"]').removeClass('loading');
                    if(json.errors)
                    {
                        $('button[name="submitSaveManagerShop"]').parents('#ets_mp_manager_shop_form').find('.form-wrapper').before(json.errors);
                    }
                    if(json.success)
                    {
                        $.growl.notice({ message: json.success });
                        $('.ets_mp_popup').hide();
                        var $html ='';
                        $html += '<td class="id_ets_mp_seller_manager "> '+json.id_manager+'</td>';
                        $html += '<td class="name "> '+json.name+' </td>';
                        $html += '<td class="email "> '+json.email+' </td>';
                        $html += '<td class="permission ">'+json.permission+'</td>';
                        $html += '<td class="active ">'+json.active+'</td>';
                        $html += '<td class="text-right">';
                            $html += '<div class="btn-group-action">';
                                $html += '<div class="btn-group pull-right">';
                                    $html += '<a class="btn btn-default link_edit" href="'+json.link_edit+'"><i class="icon-pencil fa fa-pencil"></i> '+Edit_text+'</a>';
                                    $html += '<button class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="true"><i class="icon-caret-down"></i></button>';
                                    $html += '<ul class="dropdown-menu"><li>';
                                        $html +='<a onclick="return confirm(\''+confirm_delete+'\');" href="'+json.link_delete+'"><i class="fa fa-trash icon-trash"></i>'+Delete_text+'</a>';
                                    $html +='</li></ul>';
                                $html += '</div>';
                            $html += '</div>';
                        $html += '</td>';
                        if($('#list-mp_manager tr[data-id="'+json.id_manager+'"]').length)
                            $('#list-mp_manager tr[data-id="'+json.id_manager+'"]').html($html);
                        else
                            $('#list-mp_manager').append('<tr data-id="'+json.id_manager+'">'+$html+'</tr>');
                        $('#list-mp_manager .no-record').remove();
                    }    
                },
                error: function(xhr, status, error)
                {     
                    $('button[name="submitSaveManagerShop"]').removeClass('loading');
                }
            });
        }
    });
    $(document).on('click','.ets_mp_close_popup,.ets_mp_cancel_popup',function(){
        $('.ets_mp_popup').hide();
    });
    $(document).keyup(function(e) { 
        if(e.keyCode == 27) {
            if($('.ets_mp_popup').length >0)
                $('.ets_mp_popup').hide();
        }
    });
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
    $(document).on('click','.checkbox_all input',function(){
        if($(this).is(':checked'))
        {
            $(this).closest('.form-group').find('input').prop( "checked", true );
            $('.form-group.delete_product').show();
        }
        else
        {
            $(this).closest('.form-group').find('input').removeAttr('checked');
            $('.form-group.delete_product').hide();
        }
        
    });
    $(document).on('click','.checkbox input',function(){
        if($(this).is(':checked'))
        {
            if($(this).closest('.form-group').find('input:checked').length==$(this).closest('.form-group').find('input[type="checkbox"]').length-1)
                 $(this).closest('.form-group').find('.checkbox_all input').prop( "checked", true );
        }
        else
        {
            $(this).closest('.form-group').find('.checkbox_all input').removeAttr('checked');
        } 
        if($('#permission_products').is(':checked'))
            $('.form-group.delete_product').show();
        else 
            $('.form-group.delete_product').hide();
    });
    $(document).on('click','#module-ets_marketplace-manager .add_new_link,#module-ets_marketplace-manager .link_edit',function()
    {
        if(!$(this).hasClass('loading'))
        {
            $(this).addClass('loading');
            var $this = $(this);
            var add_new_link = $(this).attr('href');
            $.ajax({
                url: add_new_link,
                data: 'ajax=1',
                type: 'post',
                dataType: 'json',                
                success: function(json){   
                    $this.removeClass('loading');
                    var $html ='<div class="ets_mp_popup ets_mp_shop_manager_popup">';
                    $html +='<div class="mp_pop_table">';                                                                            
                    $html +='<div class="mp_pop_table_cell">';                                                                                
                    $html +=json.form_html;
                    $html +='</div></div></div>';
                    if($('.ets_mp-panel').prev('.ets_mp_shop_manager_popup').length)
                        $('.ets_mp-panel').prev('.ets_mp_shop_manager_popup').remove();
                    $('.ets_mp-panel').before($html);
                    if($('#permission_products').is(':checked'))
                        $('.form-group.delete_product').show();
                    else 
                        $('.form-group.delete_product').hide();
                        
                },
                error: function(error)
                {   
                    $this.removeClass('loading');
                }
            });
        }
        return false;
    });
    if($('#price_excl').length && $('#price_excl').val())
    {
        var price_excl = parseFloat($('#price_excl').val());
        $('#price_excl').val(price_excl.toFixed(2));
        $('#price_excl2').val(price_excl.toFixed(2));
    }
    if($('#price_incl').length && $('#price_incl').val())
    {
        var price_incl = parseFloat($('#price_incl').val());
        $('#price_incl').val(price_incl.toFixed(2));
        $('#price_incl2').val(price_incl.toFixed(2));
    }
    $(document).on('change','.custom-file-input',function(){
        $(this).next('.custom-file-label').html($(this).val().replace('C:\\fakepath\\',''));
    }); 
    if($('input[name="user_attribute"]').length)
    {
        $(document).on('click','input[name="user_attribute"]',function(){
            var data = {
               ajax:1,
               changeUserAttribute : 1,
               user_attribute: $(this).val(), 
            };
            $.ajax({
                url: '',
                data: data,
                type: 'post',
                dataType: 'json',                
                success: function(json){   
                    if(json.success)
                    {
                        $.growl.notice({ message: json.success }); 
                        location.reload();
                    }
                    if(json.errors)
                        $.growl.error({ message: json.errors });  
                },
                error: function(error)
                {   
                    
                }
            });
        });
    }
    if($('input[name="user_feature"]').length)
    {
        $(document).on('click','input[name="user_feature"]',function(){
            var data = {
               ajax:1,
               changeUserFeature : 1,
               user_feature: $(this).val(), 
            };
            $.ajax({
                url: '',
                data: data,
                type: 'post',
                dataType: 'json',                
                success: function(json){   
                    if(json.success)
                    {
                        $.growl.notice({ message: json.success });
                        location.reload();
                    } 
                    if(json.errors)
                        $.growl.error({ message: json.errors }); 
                },
                error: function(error)
                {   
                    
                }
            });
        });
    }
    if($('input[name="user_shipping"]').length)
    {
        $(document).on('click','input[name="user_shipping"]',function(){
            var data = {
               ajax:1,
               changeUserShipping : 1,
               user_shipping: $(this).val(), 
            };
            $.ajax({
                url: '',
                data: data,
                type: 'post',
                dataType: 'json',                
                success: function(json){   
                    if(json.success)
                    {
                        $.growl.notice({ message: json.success }); 
                        location.reload(); 
                    }
                    if(json.errors)
                        $.growl.error({ message: json.errors }); 
                },
                error: function(error)
                {   
                    
                }
            });
        });
    }
    if($('input[name="user_brand"]').length)
    {

        $(document).on('click','input[name="user_brand"]',function(){
            var data = {
               ajax:1,
               changeUserBrands : 1,
               user_brand: $(this).val(), 
            };
            $.ajax({
                url: '',
                data: data,
                type: 'post',
                dataType: 'json',                
                success: function(json){   
                    if(json.success)
                    {
                        $.growl.notice({ message: json.success }); 
                        location.reload();
                    }
                    if(json.errors)
                        $.growl.error({ message: json.errors }); 
                },
                error: function(error)
                {   
                    
                }
            });
        });
    }
    if($('input[name="user_supplier"]').length)
    {
        $(document).on('click','input[name="user_supplier"]',function(){
            var data = {
               ajax:1,
               changeUserSuppliers : 1,
               user_supplier: $(this).val(), 
            };
            $.ajax({
                url: '',
                data: data,
                type: 'post',
                dataType: 'json',                
                success: function(json){   
                    if(json.success)
                    {
                        $.growl.notice({ message: json.success }); 
                        location.reload();
                    }
                    if(json.errors)
                        $.growl.error({ message: json.errors }); 
                },
                error: function(error)
                {   
                    
                }
            });
        });
    }
    if($('.category-tree .has-child').length)
    {
        $('.category-tree .has-child').each(function(){
            if($(this).next('.children').find('li').length==0)
                $(this).removeClass('has-child');
        })
    }
    if($('.ets_mp_content_left.col-lg-3 > .alert.alert-info').length)
    {
        $('.ets_mp_content_left.col-lg-3').before('<div class="alert-info alert">'+$('.ets_mp_content_left.col-lg-3 > .alert.alert-info').html()+'</div>');
        $('.ets_mp_content_left.col-lg-3 > .alert.alert-info').remove()
    } 
    if($('.change_length').length)
    {
        $('.change_length').each(function(){
            $(this).parent().parent().find('.currentLength').html($(this).val().replace(/(<([^>]+)>)/ig,"").length);
        });
    }  
    $(document).on('keyup','.change_length',function(){
        $(this).parent().parent().find('.currentLength').html($(this).val().length);
    });
    $(document).on('click','.delete_logo_upload',function(e){
        e.preventDefault(); 
        $(this).parent().next('.ets_upload_file_custom').find('input[type="file"]').val('');
        $(this).parent().next('.ets_upload_file_custom').find('.custom-file-label').html('Choose file');
        $(this).parent().remove();
    });
    $(document).on('click','.ets_mp-submit-request',function(){
        return confirm(confirm_withdraw+' '+$('#amount_withdraw').next('.input-group-append').find('span').html()+$('#amount_withdraw').val());
    });
    
    if($('input[name="apply_discount"]').length>0)
    {
        $('.form-group.apply_discount').hide();
        if($('input[name="apply_discount"]:checked').val()=='percent')
            $('.form-group.apply_discount.reduction_percent').show();
        if($('input[name="apply_discount"]:checked').val()=='amount')
            $('.form-group.apply_discount.reduction_amount').show();
    } 
    if($('.js-manufacturer-address-country').length>0)
    {
        var id_country = $('.js-manufacturer-address-country #id_country').val();
        $('.js-manufacturer-address-state #id_state option').hide();
        if($('.js-manufacturer-address-state #id_state option[data-parent="'+id_country+'"]').length >0)
        {
            $('.js-manufacturer-address-state').show();
            $('.js-manufacturer-address-state #id_state option[data-parent="'+id_country+'"]').show();
            if($('.js-manufacturer-address-state #id_state option[selected="selected"]').data('parent')!=id_country)
            {
                $('.js-manufacturer-address-state #id_state option').removeAttr('selected');
                $('.js-manufacturer-address-state #id_state option[data-parent="'+id_country+'"]:first').attr('selected','selected');
                $('.js-manufacturer-address-state #id_state').val($('.js-manufacturer-address-state #id_state option[data-parent="'+$(this).val()+'"]:first').val());
                $('.js-manufacturer-address-state #id_state').change();
            }
 
        }
        else
        {
            $('.js-manufacturer-address-state #id_state').val('');
            $('.js-manufacturer-address-state').hide();
        }
   }
   if($('input.tagify').length>0)
   {
        $('input.tagify').tagify({delimiters: [13,44], addTagPrompt: add_keyword_text});
        $('form').submit( function(){
            $('form input.tagify').each(function(){
                $(this).val($(this).tagify('serialize'))
            });
        });
   }
   $('.category-tree .category').each(function(){
        if($(this).parent().parent().next('.children').length)
        {
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
        }
    });
    $(document).on('change','input[name="shop_logo"],input[name="logo"],input.shop_banner,input[name="shop_banner"]',function(){
        $(this).next('.custom-file-label').html($(this).val().replace('C:\\fakepath\\',''));
        var fileExtension = ['jpeg', 'jpg', 'png', 'gif', 'bmp'];
        if ($.inArray($(this).val().split('.').pop().toLowerCase(), fileExtension) != -1) {
            ets_mp_readShopLogoURL(this);     
        }
    });
    $('#custom_fields a.add').on('click', function(e) {
        e.preventDefault();
        var collectionHolder = $('ul.customFieldCollection');
        var maxCollectionChildren = collectionHolder.children().length;
        var newForm = collectionHolder.attr('data-prototype').replace(/__name__/g, maxCollectionChildren);
        collectionHolder.append('<li>' + newForm + '</li>');
    });
    $(document).on('click', 'ul.customFieldCollection .delete', function(e) {
        e.preventDefault();
        var _this = $(this);
        if(confirm('Are you sure to delete this?'))
        {
            _this.parent().parent().parent().remove();
        }
    });
    $('#form_step6_attachment_product_add').click(function() {
        $('#form_step6_attachment_product').prev('.bootstrap').remove();
        var buttonSave = $('#form_step6_attachment_product_add');
        var buttonCancel = $('#form_step6_attachment_product_cancel');
        var _this = $(this);
        var data = new FormData();
        
        if ($('#form_step6_attachment_product_file')[0].files[0]) {
          data.append('product_attachment_file', $('#form_step6_attachment_product_file')[0].files[0]);
        }
        data.append('product_attachment_name', $('#form_step6_attachment_product_name').val());
        data.append('product_attachment_description', $('#form_step6_attachment_product_description').val());
        data.append('submitProductAttachment',1);
        data.append('id_product',$('#ets_mp_id_product').val());
        $.ajax({
                type: 'POST',
                url: '',
                data: data,
                contentType: false,
                processData: false,
                dataType: 'json', 
                beforeSend: function() {
                    buttonSave.prop('disabled', 'disabled');
                    $('ul.text-danger').remove();
                    $('*.has-danger').removeClass('has-danger');
                },
                success: function(response) {
                    if (response.id) {
                        var row = '<tr>\
                        <td>' + response.real_name + '</td>\
                        <td>' + response.file_name + '</td>\
                        <td>' + response.mime + '</td>\
                        </tr>';
                        $('#product-attachments tbody').append(row);
                        $('.js-options-no-attachments').addClass('hide');
                        $('#product-attachments').removeClass('hide');
                        $('#form_step6_attachment_product_file').val('');
                        $('#form_step6_attachment_product_file').next('label').html('');
                        $('#form_step6_attachment_product_name').val('');
                        $('#form_step6_attachment_product_description').val('');
                    }
                    if(response.success)
                    {
                        $.growl.notice({ message: response.success });
                    }
                    if(response.errors)
                    {
                        $('#form_step6_attachment_product').before(response.errors);
                    }
                },
                error: function(response) {
                    $.each(jQuery.parseJSON(response.responseText), function(key, errors) {
                      var html = '<ul class="list-unstyled text-danger">';
                      $.each(errors, function(key, error) {
                        html += '<li>' + error + '</li>';
                      });
                      html += '</ul>';
                    
                      $('#form_step6_attachment_product_' + key).parent().append(html);
                      $('#form_step6_attachment_product_' + key).parent().addClass('has-danger');
                    });
                },
                complete: function() {
                    buttonSave.removeAttr('disabled');
                }
        });
      });
});
$(document).on('click','#btn_import_left',function(){
    $('#table0').show();
    $('#table1').hide();
    $('#btn_import_left').attr('disabled','disabled');
    $('#btn_import_right').removeAttr(('disabled'));
});
$(document).on('click','#btn_import_right',function(){
    $('#table1').show();
    $('#table0').hide();
    $('#btn_import_right').attr('disabled','disabled');
    $('#btn_import_left').removeAttr(('disabled'));
});
$(document).on('click','input[name="apply_discount"]',function(e){
    $('.form-group.apply_discount').hide();
    if($('input[name="apply_discount"]:checked').val()=='percent')
        $('.form-group.apply_discount.reduction_percent').show();
    if($('input[name="apply_discount"]:checked').val()=='amount')
        $('.form-group.apply_discount.reduction_amount').show();
});
$(document).on('change','.js-manufacturer-address-country #id_country',function(){
    $('.js-manufacturer-address-state #id_state option').hide();
    $('.js-manufacturer-address-state #id_state option').removeAttr('selected');
    if($('.js-manufacturer-address-state #id_state option[data-parent="'+$(this).val()+'"]').length >0)
    {
        $('.js-manufacturer-address-state').show();
        $('.js-manufacturer-address-state #id_state option[data-parent="'+$(this).val()+'"]').show();
        $('.js-manufacturer-address-state #id_state option[data-parent="'+$(this).val()+'"]:first').attr('selected','selected');
        $('.js-manufacturer-address-state #id_state').val($('.js-manufacturer-address-state #id_state option[data-parent="'+$(this).val()+'"]:first').val());
        $('.js-manufacturer-address-state #id_state').change();
    }
    else
    {
        $('.js-manufacturer-address-state #id_state').val('');
        $('.js-manufacturer-address-state').hide();
    }
});
$(document).on('click','#toggle-all-combinations',function(){
    if($(this).is(':checked'))
    {
        $('.js-combination').prop( "checked", true );
        $('.js-combination').parent().addClass('checked');
    }
    else
    {
        $('.js-combination').removeAttr('checked');
       $('.js-combination').parent().removeClass('checked');
    }
    $('.js-bulk-combinations').text($('.js-combination:checked').length);
});
$(document).on('click','.js-combination',function(){
    if($(this).is(':checked'))
    {
        if($('.js-combination:checked').length==$('.js-combination').length)
        {
            $('#toggle-all-combinations').prop( "checked", true );
            $('#toggle-all-combinations').parent().addClass('checked');
        }
    }
    else
    {
        $('#toggle-all-combinations').removeAttr('checked');
        $('#toggle-all-combinations').parent().removeClass('checked');
    } 
    $('.js-bulk-combinations').text($('.js-combination:checked').length);
});
$(document).on('click','.tab_link',function(e){
    e.preventDefault();
    if(!$(this).hasClass('active'))
    {
        var data_tab= $(this).data('tab');
        var $this_tab= $(this);
        var idCategories = '';
        if(!$('.ets_mp_shop_tab.tab_'+data_tab+' .products').length)
        {
            $.ajax({
                url: link_ajax_sort_product_list,
                data: {
                    ajax:1,
                    order_by : '',
                    current_tab : data_tab,
                    idCategories :idCategories,
                },
                type: 'post',
                dataType: 'json',                
                success: function(json){    
                    $('body > .faceted-overlay').remove();   
                    $('#content-wrapper').removeClass('loading');
                    $('.product_tab.ets_mp_shop_tab.tab_'+data_tab).html(json.product_list);
                    //$('.category_tab.ets_mp_shop_tab.tab_'+data_tab).html(json.list_categories);
                    $('.tab_link').removeClass('active');
                    $this_tab.addClass('active');
                    $('.ets_mp_shop_tab').removeClass('active');
                    $('.ets_mp_shop_tab.tab_'+data_tab).addClass('active');   
                    if(!$('.category_tab.ets_mp_shop_tab.tab_'+data_tab+' .shop_categories').length)
                    {
                        $('.ets_myshop_right').removeClass('col-xs-12').removeClass('col-sm-12').removeClass('col-md-8').removeClass('col-lg-9').addClass('col-xs-12').addClass('col-sm-12').addClass('col-md-12').addClass('col-lg-12');
                    }  
                    else
                        $('.ets_myshop_right').addClass('col-xs-12').addClass('col-sm-12').addClass('col-md-8').addClass('col-lg-9').removeClass('col-xs-12').removeClass('col-sm-12').removeClass('col-md-12').removeClass('col-lg-12');
                    if(data_tab=='all')
                    {
                        if($('.ets_mp_tabs  input[name="product_search"]').length &&  $('.ets_mp_tabs  input[name="product_search"]').val().trim()!='')
                            if(link_ajax_sort_product_list.indexOf('?')>=0)
                                window.history.pushState("", "", link_ajax_sort_product_list+'&product_name='+$('.ets_mp_tabs  input[name="product_search"]').val());
                            else
                                window.history.pushState("", "", link_ajax_sort_product_list+'?product_name='+$('.ets_mp_tabs  input[name="product_search"]').val());    
                            
                        else 
                            window.history.pushState("", "", link_ajax_sort_product_list);
                    }
                    else
                        window.history.pushState("", "", $('.tab_link[data-tab="'+data_tab+'"]').attr('href'));    
                    ets_mp_moveblocksearchPageShop();       
                    if(is_product_comment)
                    {
                        ets_mp_loadProductComment(data_tab);
                    }
                    ets_checkRateShopby();                               
                },
            });
        } else {
            $('.tab_link').removeClass('active');
            $(this).addClass('active');
            $('.ets_mp_shop_tab').removeClass('active');
            $('.ets_mp_shop_tab.tab_'+data_tab).addClass('active');
            if(!$('.category_tab.ets_mp_shop_tab.tab_'+data_tab+' .shop_categories').length)
            {
                $('.ets_myshop_right').removeClass('col-xs-12').removeClass('col-sm-12').removeClass('col-md-8').removeClass('col-lg-9').addClass('col-xs-12').addClass('col-sm-12').addClass('col-md-12').addClass('col-lg-12');
            }  
            else
                $('.ets_myshop_right').addClass('col-xs-12').addClass('col-sm-12').addClass('col-md-8').addClass('col-lg-9').removeClass('col-xs-12').removeClass('col-sm-12').removeClass('col-md-12').removeClass('col-lg-12');
            if(data_tab=='all')
            {
                if($('.ets_mp_tabs  input[name="product_search"]').val() && $('.ets_mp_tabs  input[name="product_search"]').val().trim()!='')
                    if(link_ajax_sort_product_list.indexOf('?')>=0)
                        window.history.pushState("", "", link_ajax_sort_product_list+'&product_name='+$('.ets_mp_tabs  input[name="product_search"]').val());
                    else
                        window.history.pushState("", "", link_ajax_sort_product_list+'?product_name='+$('.ets_mp_tabs  input[name="product_search"]').val());    
                    
                else 
                    window.history.pushState("", "", link_ajax_sort_product_list);
            }
            else
                window.history.pushState("", "", $('.tab_link[data-tab="'+data_tab+'"]').attr('href'));   
            ets_mp_moveblocksearchPageShop();     
        }
    }
});
$(document).on('click','.shop_categories',function(e){
    $('.product_tab.ets_mp_shop_tab.active input[name="product_search"]').val('');
    ets_mpLoadProductShopPage();
});
$(document).on('click','.ets_mp_block-categories .clear_selection',function(e){
    if($('.shop_categories:checked').length)
    {
        $('.shop_categories:checked').removeAttr('checked').parent('.checked').removeClass('checked');
        ets_mpLoadProductShopPage();
    }
    
});
$(document).on('change','.product_tab.ets_mp_shop_tab .ets_mp_sort_by_product_list',function(e){
    $('.ets_mp_sort_by_dropdown_ul').removeClass('active');
    ets_mpLoadProductShopPage();
});
$(document).on('change','.ets_mp_sort_by_shop_list',function(e){
    $.ajax({
        url: '',
        data: {
            ajax:1,
            order_by : $('.ets_mp_sort_by_shop_list').val(),
        },
        type: 'post',
        dataType: 'json',                
        success: function(json){    
            $('.ets_mp_list_seller').html(json.shop_list);
                                            
        },
    });
});
$(document).on('click','button[name="submitfollow"],button[name="submitunfollow"]',function(e){
   e.preventDefault();
   if(!$(this).hasClass('loading'))
   {
        $(this).addClass('loading');
        var $this= $(this);
        if($this.attr('name')=='submitfollow')
            var data = {
               ajax:1,
               submitfollow : 1, 
            };
        else
            var data = {
               ajax:1,
               submitunfollow : 1, 
            };
        $.ajax({
            url: '',
            data: data,
            type: 'post',
            dataType: 'json',                
            success: function(json){    
                 $this.removeClass('loading');  
                 if(json.success)
                 {
                    $.growl.notice({ message: json.success }); 
                    if(json.follow)
                    {
                        $('.wapper-follow .block-followed').show();
                        $('.wapper-follow .block-follow').hide();
                    }
                    else
                    {
                        $('.wapper-follow .block-followed').hide();
                        $('.wapper-follow .block-follow').show();
                    }
                    $('.total_follow').html(($('.total_follow').prev('.total').length ? '':'')+json.total_follow);
                 }                                      
            },
            error: function(error)
            {   
                $this.removeClass('loading');
            }
        }); 
   }
});
$(document).on('click','button[name="reset_product_name"]',function(e){
   e.preventDefault(); 
   if($('.ets_mp_tabs  input[name="product_search"]').val())
   {
        $('.ets_mp_tabs  input[name="product_search"]').val('');
        $('.product_tab.ets_mp_shop_tab.active input[name="product_search"]').val('');
        ets_mpLoadProductShopPage();
   }
});
$(document).on('keyup','.ets_mp_tabs  input[name="product_search"]',function(e){
    if(e.keyCode==13)
    {
        $('.product_tab.ets_mp_shop_tab.active input[name="product_search"]').val($(this).val());
        if($('.tab_link[data-tab="all"]').hasClass('active'))
            $('.shop_categories').removeAttr('checked');
        ets_mpLoadProductShopPage();
    }
});
$(document).on('click','.ets_mp_shop_tab .paggination .links a',function(e){
    e.preventDefault();
    if($('#content-wrapper').hasClass('loading'))
        return false;
    var url_ajax = $(this).attr('href');
    var data_tab = $('.ets_mp_tabs .tab_link.active').data('tab');
    var idCategories='';
    if($('.category_tab.ets_mp_shop_tab.tab_'+data_tab+' .shop_categories:checked').length)
    {
        $('.category_tab.ets_mp_shop_tab.tab_'+data_tab+' .shop_categories:checked').each(function(){
            idCategories +=$(this).val()+',';
        });
    }
    $.ajax({
        url: url_ajax,
        data: {
            ajax:1,
            order_by : $('.product_tab.ets_mp_shop_tab.tab_'+data_tab+' .ets_mp_sort_by_product_list').val(),
            current_tab : data_tab,
            product_name: $('.ets_mp_tabs  input[name="product_search"]').val(),
            idCategories: idCategories
        },
        type: 'post',
        dataType: 'json',                
        success: function(json){    
            $('.product_tab.ets_mp_shop_tab.tab_'+data_tab).html(json.product_list);
            if(data_tab=='all')
            {
                if($('.ets_mp_tabs  input[name="product_search"]').val().trim()!='')
                    if(url_ajax.indexOf('?')>=0)
                        window.history.pushState("", "", url_ajax+'&product_name='+$('.ets_mp_tabs  input[name="product_search"]').val());
                    else
                        window.history.pushState("", "", url_ajax+'?product_name='+$('.ets_mp_tabs  input[name="product_search"]').val());    
                    
                else 
                    window.history.pushState("", "", url_ajax);
            }
            else
                window.history.pushState("", "", url_ajax);
            if(is_product_comment)
            {
                ets_mp_loadProductComment(data_tab);
            }
            ets_checkRateShopby();
                                             
        },
        error: function(error)
        {      
        }
    });
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
                        $this.html('<i class="fa fa-check"></i>');
                    }                        
                    else
                    {
                        $this.removeClass('action-enabled').addClass('action-disabled');
                        $this.html('<i class="fa fa-remove"></i>');
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
$(document).on('click','.next_step',function(e){
    $('#seller-form .step').removeClass('active');
    $('#seller-form .step_2').addClass('active');
    return false;
});
$(document).on('click','.prev_step',function(e){
    $('#seller-form .step').removeClass('active');
    $('#seller-form .step_1').addClass('active');
    return false;
});
$(document).on('click','.hideOtherLanguage',function(){
   hideOtherLanguage($(this).data('id-lang')) ;
   return false; 
});
$(document).on('click','.ets_mp_product_tab .ets_mp_tab',function(){
   if(!$(this).hasClass('active')) 
   {
        $('.ets_mp_product_tab .ets_mp_tab').removeClass('active');
        $(this).addClass('active');
        $('.ets_mp_product_tab_content .ets_mp_tab_content').removeClass('active');
        $('.ets_mp_product_tab_content .ets_mp_tab_content.'+$(this).data('tab')).addClass('active');
   } 
});
$(document).on('click','.category-tree .label',function(){
    $(this).parent().parent().next('.children').toggle();
    $(this).parent().toggleClass('opend');
});
$(document).on('click','#submitApplication',function(){
    $('#seller-register-form').show();
    $(this).prev().hide(); 
    $(this).hide();
    $('.module_warning.alert-warning').remove();
});
$(document).on('change','.id_features',function(){
    var id_feature = $(this).val();
    $(this).closest('.etm-mp-product-feature').find('.id_feature_value').hide();
    $(this).closest('.etm-mp-product-feature').find('.id_feature_value').removeAttr('selected');
    $(this).closest('.etm-mp-product-feature').find('.id_feature_value[value="0"]').attr('selected','selected');
    $(this).closest('.etm-mp-product-feature').find('.id_feature_values').removeAttr('disabled');
    if($(this).closest('.etm-mp-product-feature').find('.id_feature_value[data-id-feature="'+id_feature+'"]').length)
    {
        $(this).closest('.etm-mp-product-feature').find('.id_feature_value[data-id-feature="'+id_feature+'"]').show();
    }
    else
    {
        $(this).closest('.etm-mp-product-feature').find('.id_feature_values').attr('disabled','disabled');
    }
});
$(document).on('click','#ets_mp_add_feature_button',function(){
   $('#ets-mp-features-content').append($('#ets-mp-feature-add-content').html()); 
});
$(document).on('click','.ets-mp-delete',function(){
   if(confirm(delete_file_comfirm))
   {
        $(this).closest('.etm-mp-product-feature').remove();
   } 
});
$(document).on('click','.js-attribute-checkbox',function(){
    if($(this).is(':checked'))
    {
        $('#attributes-generator .tokenfield').append('<div class="token" data-value="'+$(this).data('value')+'"><span class="token-label" style="max-width: 713.184px;">'+$(this).data('label')+'</span><a href="#" class="ets_mp_close_attribute" tabindex="-1"></a></div>');
    }
    else
    {
        $('#attributes-generator .tokenfield .token[data-value="'+$(this).data('value')+'"]').remove();
    }
});
$(document).on('click','.ets_mp_close_attribute',function(){
    $('.js-attribute-checkbox[data-value="'+$(this).parent().data('value')+'"]').removeAttr('checked');
    $(this).parent().remove();
    return false;
});
$(document).on('click','#combinations-bulk-form .ets-mp-bulk-action-form-attribute',function(){
    $('#bulk-combinations-container').toggle(); 
});
$(document).on('click','.attribute-actions.edit .btn-open',function(e){
    if($(this).next($(this).attr('href')).length && $('.ets_mp-form-content-setting-combination '+$(this).attr('href')).length )
        $('.ets_mp-form-content-setting-combination '+$(this).attr('href')).remove();
    if($('.ets_mp-form-content-setting-combination '+$(this).attr('href')).length==0)
    {
        if($(this).next($(this).attr('href')).length)
        {
            $('.ets_mp-form-content-setting-combination').append($(this).next($(this).attr('href')).clone());
            $(this).next($(this).attr('href')).remove();
        }     
    } 
    $('.combination-form.row').addClass('hide');
    $('.ets_mp-form-content-setting-combination '+$(this).attr('href')).removeClass('hide');  
    $('.ets_mp-form-content').hide();
    $('.ets_mp_product_tab').hide();
    if ($(".datepicker input").length > 0) {
        var dateToday = new Date();
        $(".datepicker input").removeClass('hasDatepicker');
		$(".datepicker input").datepicker({
			dateFormat: 'yy-mm-dd',
            timeFormat: 'hh:mm:ss',
            minDate: dateToday,
		});
	}  
    return false;
});
$(document).on('click','.attribute-actions.delete .btn.delete',function(e){
    e.preventDefault();
    if(!$(this).hasClass('active'))
    {
        if(confirm(delete_file_comfirm))
        {
            $(this).addClass('active');
            var $this= $(this);
            var id_product_attribute= $(this).attr('data');
            $.ajax({
                url: '',
                data: {
                    id_product_attribute:id_product_attribute,
                    submitDeleteProductAttribute:1,
                    id_product : $('#ets_mp_id_product').val(),
                },
                type: 'post',
                dataType: 'json',                
                success: function(json){ 
                    if(json.success)
                    {
                        $.growl.notice({ message: json.success });
                        $('#attribute_'+id_product_attribute).remove();
                        $('#html_form_supplier').html(json.html_form_supplier);
                    }
                    if(json.errors)
                        $.growl.error({message:json.errors});
                    $this.removeClass('active');                                              
                },
                error: function(error)
                {                                      
                    $this.removeClass('active');
                }
            });
        }
    }
    
});
$(document).on('click','.combination-form .back-to-product',function(){
    $('.combination-form').addClass('hide');
    $('.ets_mp-form-content').show();
    $('.ets_mp_product_tab').show();
});
$(document).on('click','#js-open-create-specific-price-form',function(){
    $('#specific_price_form').toggleClass('hide');
    $('#specific_price_form input[name="specific_price_sp_reduction"]').val('');
    $('#specific_price_form input[name="specific_price_from_quantity"]').val('1');
    $('#specific_price_form input[name="id_specific_price"]').val('');
    if ($(".datepicker input").length > 0) {
        var dateToday = new Date();
		$(".datepicker input").datepicker({
			dateFormat: 'yy-mm-dd',
            timeFormat: 'hh:mm:ss',
            minDate: dateToday,
		});
	}
    $('#specific_price_id_customer_hide').val('');
    $('.specific_price_id_customer .customer_selected').remove();
    return false;
});
$(document).on('click','.ets-special-edit a.edit',function(e){
   e.preventDefault(); 
   var id_specific_price = $(this).data('id_specific_price');
   $.ajax({
        url: '',
        data: {
            id_specific_price:id_specific_price,
            getFormSpecificPrice:1,
            id_product : $('#ets_mp_id_product').val(),
        },
        type: 'post',
        dataType: 'json',                
        success: function(json){ 
            if(json.form_html)
            {
                $('#specific_price_form').html(json.form_html);
                $('#specific_price_form').removeClass('hide');  
                if ($(".datepicker input").length > 0) {
                    var dateToday = new Date();
            		$(".datepicker input").datepicker({
            			dateFormat: 'yy-mm-dd',
                        timeFormat: 'hh:mm:ss',
                        minDate: dateToday,
            		});
            	}
                $('#specific_price_id_customer').autocomplete(ets_mp_url_search_customer,{
            		minChars: 1,
            		autoFill: true,
            		max:20,
            		matchContains: true,
            		mustMatch:false,
            		scroll:false,
            		cacheLength:0,
            		formatItem: function(item) {
            			return item[1]+' ('+item[2]+')';
            		}
            	}).result(etsMPAddCustomerSpecific);  
            }
            if(json.errors)
                $.growl.error({message:json.errors});                                          
        },
        error: function(error)
        {                                      
            $('#specific_price_form').removeClass('active');
        }
    });
});
$(document).on('click','.ets_mp_delete_specific',function(e){
    if(!$(this).hasClass('active'))
    {
        if(confirm(confirm_delete_specific))
        {
            var $this = $(this);
            $this.addClass('active')
            var id_specific_price = $(this).data('id_specific_price');
            $.ajax({
                url: '',
                data: {
                    id_specific_price:id_specific_price,
                    submitDeleteSpecificPrice:1,
                    id_product :$('#ets_mp_id_product').val()
                },
                type: 'post',
                dataType: 'json',                
                success: function(json){ 
                    if(json.success)
                    {
                        $.growl.notice({ message: json.success });
                        $('#specific_price-'+id_specific_price).remove();
                    }
                    if(json.errors)
                        $.growl.error({message:json.errors});
                    $this.removeClass('active');                                              
                },
                error: function(error)
                {                                      
                    $this.removeClass('active');
                }
            });
        }
    }
    return false;
});
$(document).on('click','.i_have_just_sent_the_fee',function(e){
    if(!$(this).hasClass('active'))
    {
        if(confirm(ets_mp_text_confim_payment))
        {
            var $this = $(this);
            $this.addClass('active');
            $.ajax({
                url: '',
                data: {
                    i_have_just_sent_the_fee:1,
                },
                type: 'post',
                dataType: 'json',                
                success: function(json){ 
                    if(json.success)
                    {
                        $.growl.notice({ message: text_sent_successfully });
                        if($this.prev('.alert').length)
                           $this.prev('.alert').html(json.success);
                        else if($('.alert.alert-success').length)
                            $('.alert.alert-success').html(json.success);
                        else if($('.alert.alert-info').length)
                            $('.alert.alert-info').html(json.success);
                        else
                            $this.parent().html(json.success);                     
                        $this.remove();
                        if($('.fee_explanation').length)
                            $('.fee_explanation').remove();   
                    }
                    if(json.errors)
                        $.growl.error({message:json.errors});
                    $this.removeClass('active');                                              
                },
                error: function(error)
                {                                      
                    $this.removeClass('active');
                }
            });
        }
    }
    return false; 
});
$(document).on('click','button[name="specific_price_cancel"]',function(e){
    e.preventDefault();
    $('#specific_price_form').addClass('hide');
});
$(document).on('click','#delete-combinations',function(e){
    e.preventDefault();
    var $this = $(this);
    $('.ets_mp_errors').html('');
    if(!$(this).hasClass('loading'))
    {
        if($('.js-combination:checked').length==0)
            alert('attribute null');
        else
        {
            if(confirm("Are you sure to delete this?"))
            {
                $(this).addClass('loading');
                $.ajax({
                    url: '',
                    data: $('.ets_mp_combination_left :input').serialize()+'&submitDeletecombinations=1&id_product='+$('#ets_mp_id_product').val(),
                    type: 'post',
                    dataType: 'json',                
                    success: function(json){ 
                        $this.removeClass('loading');
                        if(json.errors)
                        {
                            $('.ets_mp_errors').html(json.errors);
                        }   
                        if(json.success)
                        {
                            $.growl.notice({ message: json.success });
                            $('.combinations-list').html(json.list_combinations);
                            $('.js-bulk-combinations').text('0');
                            $('#js-bulk-combinations-total').text($('.js-combination').length);
                            $('#html_form_supplier').html(json.html_form_supplier);
                        }                                  
                    },
                    error: function(error)
                    {                                      
                        $this.removeClass('loading');
                    }
                });
            }
        }
    }
    
});
$(document).on('click','#apply-on-combinations',function(e){
    e.preventDefault();
    var $this = $(this);
    $('.ets_mp_errors').html('');
    if(!$(this).hasClass('loading'))
    {
        if($('.js-combination:checked').length==0)
            alert('attribute null');
        else
        {
            $(this).addClass('loading');
            $.ajax({
                url: '',
                data: $('.ets_mp_combination_left :input').serialize()+'&submitSavecombinations=1&id_product='+$('#ets_mp_id_product').val(),
                type: 'post',
                dataType: 'json',                
                success: function(json){ 
                    $this.removeClass('loading');
                    if(json.errors)
                    {
                        $('.ets_mp_errors').html(json.errors);
                    }   
                    if(json.success)
                    {
                        $.growl.notice({ message: json.success });
                        $('.combinations-list').html(json.list_combinations);
                        $('.js-bulk-combinations').text('0');
                        $('#js-bulk-combinations-total').text($('.js-combination').length);
                    }                                  
                },
                error: function(error)
                {                                      
                    $this.removeClass('loading');
                }
            });
        }
    }
    
});
$(document).on('click','button[name="specific_price_save"]',function(e){
    e.preventDefault();
    var $this = $(this);
    $('.ets_mp_errors').html('');
    if(!$(this).hasClass('loading'))
    {
        $(this).addClass('loading');
        $.ajax({
            url: '',
            data: $('#specific_price_form :input').serialize()+'&id_product='+$('#ets_mp_id_product').val(),
            type: 'post',
            dataType: 'json',                
            success: function(json){ 
                $('button[name="specific_price_save"]').removeClass('loading');
                if(json.errors)
                {
                    $('.ets_mp_errors').html(json.errors);
                }   
                if(json.success)
                {
                    $.growl.notice({ message: json.success });
                    var $html_tr = '<td>--</td>';
                    if(json.specific.id_product_attribute!=0)
                        $html_tr += '<td>'+json.specific.attribute_name+'</td>';
                    else
                        $html_tr +='<td>'+all_combinations_text+'</td>';
                    if(json.specific.id_currency!=0)
                        $html_tr +='<td>'+json.specific.currency_name+'</td>';
                    else
                        $html_tr +='<td>'+all_currencies_text+'</td>';
                    if(json.specific.id_country!=0)
                        $html_tr +='<td>'+json.specific.country_name+'</td>'; 
                    else
                        $html_tr +='<td>'+all_countries_text+'</td>';   
                    if(json.specific.id_group!=0)
                        $html_tr +='<td>'+json.specific.group_name+'</td>';
                    else
                        $html_tr +='<td>'+all_groups_text+'</td>';
                    if(json.specific.id_customer!=0)
                        $html_tr +='<td>'+json.specific.customer_name+'</td>';
                    else
                        $html_tr +='<td>'+all_customer_text+'</td>';
                    $html_tr +='<td>'+json.specific.price_text+'</td>';
                    $html_tr +='<td>-'+json.specific.reduction+'</td>';
                    if(json.specific.from!='0000-00-00 00:00:00' || json.specific.to!='0000-00-00 00:00:00')
                        $html_tr += '<td>'+from_text+': '+json.specific.from+'<br/>'+to_text+': '+json.specific.to+'</td>';
                    else
                        $html_tr += '<td>'+Unlimited_text+'</td>';
                    $html_tr += '<td>'+json.specific.from_quantity+'</td>';
                    $html_tr += '<td class="ets-special-edit"><a class="js-delete delete btn ets_mp_delete_specific delete pl-0 pr-0" title="Delete" href="#" data-id_specific_price="'+json.specific.id_specific_price+'"><i class="icon-delete"></i>Delete</a><a class="js-edit edit btn tooltip-link delete pl-0 pr-0" title="Edit" href="#" data-id_specific_price="'+json.specific.id_specific_price+'"><i class="icon-edit"></i>Edit</a></td>';
                    if($('#specific_price-'+json.specific.id_specific_price).length==0)
                        $('#js-specific-price-list tbody').append('<tr id="specific_price-'+json.specific.id_specific_price+'">'+$html_tr+'</tr>');
                    else
                        $('#specific_price-'+json.specific.id_specific_price).html($html_tr);
                    $('#specific_price_form').addClass('hide');
                }                                  
            },
            error: function(error)
            {                                      
                $('button[name="specific_price_save"]').removeClass('loading');
            }
        });
    }
});
$(document).on('click','button[name="submitSaveProduct"]',function(e){
    /** _ARM_ Force stop event if required feature are not selected */
    var stop = false;
    $('#ets-mp-features-content .etm-mp-product-feature select.id_feature_values').each(function (index) {
        if ($(this).prop('required') && $(this).val() == 0) {
            stop = true;
        }
    });
    if (stop) {
        return false;
    }

    e.preventDefault();
    tinymce.triggerSave();
    if(!$('#ets_mp_product_form').hasClass('loading'))
    {
        $('#ets_mp_product_form').addClass('loading');
        var formData = new FormData($(this).parents('form').get(0));
        formData.append('submitSaveProduct', 1);
        formData.delete('submitSavePecificPrice');
        formData.append('ajax', 1);
        var url_ajax= $('#ets_mp_product_form').attr('action');
        $('.ets_mp_errors').html('');
        $.ajax({
            url: url_ajax,
            data: formData,
            type: 'post',
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(json){
                $('#ets_mp_product_form').removeClass('loading');
                if(json.success)
                {
                    $.growl.notice({ message: json.success });
                    if($('.module_confirmation.alert-success').length)
                        $('.module_confirmation.alert-success').remove();
                    if(json.virtual)
                    {
                        $('#form_step3_virtual_product_file_input').removeClass('show').addClass('hide');
                        $('#form_step3_virtual_product_file_details').removeClass('hide').addClass('show');
                        var html_link = '<a class="btn btn-default btn-sm download ets_mp_download_file" href="'+json.virtual.link_download_file+'" target="_blank">'+download_file_text+'</a>';
                        html_link += '<a href="'+json.virtual.link_delete_file+'" class="btn btn-danger btn-sm delete ets_mp_delete_file">'+delete_file_text+'</a>';
                        $('#form_step3_virtual_product_file_details').html(html_link);
                    }
                    $('.combinations-list').html(json.list_combinations);
                    $('#ets_mp_id_product').val(json.id_product);
                    $('#product-images-container').show();
                    $('#html_form_supplier').show();
                    $('#html_form_supplier').html(json.html_form_supplier);
                    if($('button[name="submitSaveProduct"]').next('.preview_product').length==0)
                    {
                        $('button[name="submitSaveProduct"]').after('<a class="btn btn-primary float-xs-right preview_product" href="'+json.link_product+'" target="_blank">'+json.preview_text+'</a>');
                        $('button[name="submitSaveProduct"]').html(json.save_text);
                    }
                }
                else if(json.errors)
                {  
                    $('.ets_mp_errors').html(json.errors);
                }
            },
            error: function(xhr, status, error)
            {     
                $('#ets_mp_product_form').removeClass('loading');
            }
        });
    }
});
$(document).on('click','.ets_mp_delete_file',function(e){
   e.preventDefault(); 
   if(!$(this).hasClass('loading'))
   {
        if(confirm(delete_file_comfirm))
        {
            $(this).addClass('loading');
            var $this= $(this);
            url_ajax= $(this).attr('href');
            $.ajax({
                url: url_ajax,
                data: '',
                type: 'post',
                dataType: 'json',
                success: function(json){
                    $this.removeClass('loading');
                    if(json.success)
                    {
                        $.growl.notice({ message: json.success });
                        $('#form_step3_virtual_product_file_details').html('').removeClass('show').addClass('hide');
                        $('#form_step3_virtual_product_file_input').addClass('show').removeClass('hide');
                        $('#form_step3_virtual_product_name').val('');
                        $('label[for="form_step3_virtual_product_file"]').html('Choose file(s)');
                    }
                    else if(json.errors)
                    {  
                        $this.removeClass('loading');
                    }
                },
                error: function(xhr, status, error)
                {     
                    $('#ets_mp_product_form').removeClass('loading');
                }
            });
        }
   }
});
$(document).on('click','#specific_price_leave_bprice',function(){
   if($(this).is(':checked'))
        $('input[name="specific_price_product_price"]').attr('disabled','disabled');
   else
        $('input[name="specific_price_product_price"]').removeAttr('disabled');
});
$(document).on('change','#specific_price_sp_reduction_type',function(){
    $('#specific_price_sp_reduction').prev('.input-group-prepend').html('<span class="input-group-text">'+$('#specific_price_sp_reduction_type option[value="'+$(this).val()+'"]').html()+'</span>');
    if($(this).val()=='percentage')
    {
        $('select[name="specific_price_sp_reduction_tax"]').hide();
    } 
    else
       $('select[name="specific_price_sp_reduction_tax"]').show(); 
});
$(document).on('change','#ets_mp_product_form input[name^="name_"]',function(){
    ets_mp_updateFriendlyURL();
});
$(document).on('change','select[name="product_type"]',function(){
    ets_mp_displayProductType();
});
$(document).on('change','.attribute_priceTE',function(){
    var id_product_attribute= $(this).data('id_product_attribute');
    var impact_price = $(this).val();
    $('.attribute-finalprice span[data-uniqid="'+id_product_attribute+'"]').html(parseFloat($('.attribute-finalprice span[data-uniqid="'+id_product_attribute+'"]').data('price')) + parseFloat(impact_price));
    $('.attribute_priceTE[data-id_product_attribute="'+id_product_attribute+'"]').val(impact_price);
    $('.final-price[data-uniqid="'+id_product_attribute+'"]').html(parseFloat($('.attribute-finalprice span[data-uniqid="'+id_product_attribute+'"]').data('price')) + parseFloat(impact_price));
});
$(document).on('change','.quantity_product_attributes',function(){
   var id_product_attribute = $(this).data('id_product_attribute');
   var quantity_product_attribute = $(this).val();
   $('#combination_'+id_product_attribute+'_attribute_quantity').val(quantity_product_attribute); 
});
$(document).on('change','.combinations_attribute_quantity',function(){
    var id_product_attribute = $(this).data('id_product_attribute');
    var quantity_product_attribute = $(this).val();
    $('input.quantity_product_attributes[data-id_product_attribute="'+id_product_attribute+'"]').val(quantity_product_attribute);
});
$(document).on('click','.attribute-default',function(){
    $('.attribute_default_checkbox:checked').removeAttr('checked');
    $('#combination_'+$(this).val()+'_attribute_default').attr('checked','checked');
});
$(document).on('click','button#create-combinations',function(e){
    e.preventDefault();
    tinymce.triggerSave();
    if($('.js-attribute-checkbox:checked').length>0)
    {
        if(!$('#ets_mp_product_form').hasClass('loading'))
        {
            $('#ets_mp_product_form').addClass('loading');
            var formData = new FormData($(this).parents('form').get(0));
            formData.append('submitCreateCombination',1);
            formData.delete('submitSavePecificPrice');
            formData.append('ajax', 1);
            var url_ajax= $('#ets_mp_product_form').attr('action');
            $('.ets_mp_errors').html('');
            $.ajax({
                url: url_ajax,
                data: formData,
                type: 'post',
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(json){
                    $('#ets_mp_product_form').removeClass('loading');
                    if(json.success)
                    {
                        $.growl.notice({ message: json.success });
                        $('.ets_mp-form-content-setting-combination').html('');
                        $('#attributes-generator .tokenfield').html('');
                        $('.js-attribute-checkbox:checked').removeAttr('checked');
                        $('.combinations-list').html(json.list_combinations);
                        $('.js-bulk-combinations').html('0');
                        $('#js-bulk-combinations-total').html($('.combinations-list tbody tr').length);
                        $('#ets_mp_id_product').val(json.id_product);
                        $('#product-images-container').show();
                        $('#html_form_supplier').show();
                        $('#html_form_supplier').html(json.html_form_supplier);
                    }
                    else if(json.errors)
                    {  
                        $('.ets_mp_errors').html(json.errors);
                    }
                },
                error: function(xhr, status, error)
                {     
                    $('#ets_mp_product_form').removeClass('loading');
                }
            });
        }
    }
});
$(document).on('change','#form_step3_virtual_product_file',function(){
    var file_name= $(this).val().replace('C:\\fakepath\\','');
    $('#form_step3_virtual_product_name').val(file_name);
    $('label[for="form_step3_virtual_product_file"]').html(file_name);
});
$(document).on('click','#form_step1_inputPackItems-curPackItemAdd',function(e){
    e.preventDefault();
    if($('#search_product_pack_content > li').length==0)
    {
        alert('Product is null');
        return false;
    }
    if(parseInt($('#form_step1_inputPackItems-curPackItemQty').val()) <=0)
    {
        alert('Quantity <=0');
        return false;
    }
    else
    {
        var quantity_pack_product = parseInt($('#form_step1_inputPackItems-curPackItemQty').val());
        $('#search_product_pack_content > li .quantity').html('x'+quantity_pack_product);
        $('#search_product_pack_content .inputPackItems').attr('name','inputPackItems[]')
        $('#search_product_pack_content .inputPackItems').val($('#search_product_pack_content .inputPackItems').val()+'x'+quantity_pack_product);
        $('#form_step1_inputPackItems-data').append($('#search_product_pack_content').html());
        $('#search_product_pack_content').html('');
        $('#search_product_pack').val('');
    }
});
$(document).on('click','.ets_mp_delete_pack_product',function(e){
   e.preventDefault(); 
   if(confirm(delete_item_comfirm))
   {
        $(this).closest('li').remove();
   }
});
$(document).on('click','.ets_mp_edit_image',function(){
    
    var imageID = $(this).data('id');
    var $this = $(this);
     $.ajax({
        url: '',
        data: 'getFromImageProduct=1&id_image='+imageID+'&id_product='+$('#ets_mp_id_product').val(),
        type: 'post',
        dataType: 'json',                
        success: function(json){ 
            $('.ets_mp_edit_image').removeClass('active');
            $this.addClass('active');  
            $this.parents('#product-images-container').addClass('show_info'); 
            $('#product-images-form-container').html(json.form_image);     
                                   
        },
        error: function(error)
        {                                      
            
        }
    });
});
$(document).on('click','.ets_mp_close_image',function(){
    $('#product-images-form-container').html('');
    $('.ets_mp_edit_image').removeClass('active');
    $('#product-images-container').removeClass('show_info');
});
$(document).on('click','.ets_mp_save_image',function(e){
    e.preventDefault();
    var $this = $(this);
    $('.ets_mp_errors').html('');
    if(!$(this).hasClass('loading'))
    {
        $(this).addClass('loading');
        $.ajax({
            url: '',
            data: $('#product-images-form-container :input').serialize()+'&id_product='+$('#ets_mp_id_product').val()+'&submitImageProduct',
            type: 'post',
            dataType: 'json',                
            success: function(json){ 
                $('.ets_mp_save_image').removeClass('loading');
                if(json.errors)
                {
                    $('.ets_mp_errors').html(json.errors);
                }   
                if(json.success)
                {
                    $.growl.notice({ message: json.success });
                    if(json.cover)
                    {
                        $('.ets_mp_edit_image .iscover').remove();
                        $('.ets_mp_edit_image[data-id="'+json.id_image+'"]').append('<div class="iscover">'+cover_text+'</div>');
                    }
                    $('.combinations-list').html(json.list_combinations);
                    $('.ets_mp-form-content-setting-combination').html('');
                }                                  
            },
            error: function(error)
            {                                      
                $('.ets_mp_save_image').removeClass('loading');
            }
        });
    }
});
$(document).on('click','.ets_mp_delete_image',function(e){
    e.preventDefault();
    var $this = $(this);
    if(!$('.ets_mp_delete_image').hasClass('loading'))
    {
        if(confirm(delete_image_comfirm))
        {
            $('.ets_mp_delete_image').addClass('loading');
            $.ajax({
                url: '',
                data: $('#product-images-form-container :input').serialize()+'&id_product='+$('#ets_mp_id_product').val()+'&deleteImageProduct',
                type: 'post',
                dataType: 'json',                
                success: function(json){ 
                    if(json.errors)
                    {
                        $('.ets_mp_errors').html(json.errors);
                    }   
                    if(json.success)
                    {
                        $.growl.notice({ message: json.success });
                        $('#product-images-form-container').html('');
                        $('.ets_mp_edit_image[data-id="'+json.id_image+'"]').remove();
                        $this.remove();
                        if($('.ets_mp_edit_image').length==0)
                        {
                            $('#product-images-dropzone').removeClass('dz-started');
                        }
                        $('.combinations-list').html(json.list_combinations);
                        $('.ets_mp-form-content-setting-combination').html('');
                        $('#product-images-container.show_info').removeClass('show_info');
                    }                                  
                },
                error: function(error)
                {                                      
                    $('.ets_mp_delete_image').removeClass('loading');
                }
            });
        }
        
    }
    
});
$(document).on('change','#product_combination_bulk_impact_on_price_te',function(){
    if(tax = ets_mp_tax_rule_groups[$('select[name="id_tax_rules_group"]').val()])
    {
        var price_incl = parseFloat($(this).val()) + parseFloat($(this).val())* parseFloat(tax);
        $('#product_combination_bulk_impact_on_price_ti').val(price_incl.toFixed(2));
    }
    else
        $('#product_combination_bulk_impact_on_price_ti').val($(this).val());
});
$(document).on('change','#price_excl,#price_excl2',function(){
    if($(this).attr('id')=='price_excl')
        $('#price_excl2').val($(this).val());
    else
        $('#price_excl').val($(this).val());
    if(tax = ets_mp_tax_rule_groups[$('select[name="id_tax_rules_group"]').val()])
    {
        var price_incl = parseFloat($(this).val()) + parseFloat($(this).val())* parseFloat(tax);
        $('#price_incl').val(price_incl.toFixed(2));
        $('#price_incl2').val(price_incl.toFixed(2));
    }
    else
    {
        $('#price_incl').val($(this).val());
        $('#price_incl2').val($(this).val());
    }
});
$(document).on('change','.attribute_priceTE',function(){
    tax = parseFloat(ets_mp_tax_rule_groups[$('select[name="id_tax_rules_group"]').val()]);
    var id_product_attribute= $(this).data('id_product_attribute');
    if(tax)
    {
        var price_attribute_incl = parseFloat($(this).val()) + parseFloat($(this).val())* parseFloat(tax);
        $('.attribute_priceTI[data-id_product_attribute="'+id_product_attribute+'"]').val(price_attribute_incl.toFixed(2));
    }
    else
        $('.attribute_priceTI[data-id_product_attribute="'+id_product_attribute+'"]').val($(this).val())
});
$(document).on('change','#ets_mp_product_form select[name="id_tax_rules_group2"]',function(){
    if(!change_select_tax_group)
    {
        change_select_tax_group = true;
        $('#ets_mp_product_form select[name="id_tax_rules_group"] option').removeAttr('selected');
        $('#ets_mp_product_form select[name="id_tax_rules_group"] option[value="'+$(this).val()+'"]').attr('selected','selected');
        $('#ets_mp_product_form select[name="id_tax_rules_group"]').val($(this).val());
        $('#ets_mp_product_form select[name="id_tax_rules_group"]').change();
        
    }
    else
        change_select_tax_group = false;
    
});
$(document).on('change','#ets_mp_product_form select[name="id_tax_rules_group"]',function(){
    if(!change_select_tax_group)
    {
        change_select_tax_group = true;
        $('#ets_mp_product_form select[name="id_tax_rules_group2"] option').removeAttr('selected');
        $('#ets_mp_product_form select[name="id_tax_rules_group2"] option[value="'+$(this).val()+'"]').attr('selected','selected');
        $('#ets_mp_product_form select[name="id_tax_rules_group2"]').val($(this).val());
        $('#ets_mp_product_form select[name="id_tax_rules_group2"]').change();
        
    }
    else
        change_select_tax_group = false;    
});
$(document).on('change','#ets_mp_product_form select[name="id_tax_rules_group"],#ets_mp_product_form select[name="id_tax_rules_group2"]',function(){
    var tax = ets_mp_tax_rule_groups[$(this).val()]
    var price_excl = parseFloat($('#price_excl').val());
    if(tax)
    {
        var price_incl = price_excl + price_excl* parseFloat(tax);
        $('#price_incl').val(price_incl.toFixed(2));
        $('#price_incl2').val(price_incl.toFixed(2));
    }
    else
    {
        $('#price_incl').val(price_excl.toFixed(2));
        $('#price_incl2').val(price_excl.toFixed(2));
    }
    var product_combination_bulk_impact_on_price_te = parseFloat($('#product_combination_bulk_impact_on_price_te').val());
    if(tax)
    {
        var product_combination_bulk_impact_on_price_ti = product_combination_bulk_impact_on_price_te + product_combination_bulk_impact_on_price_te* parseFloat(tax);
        $('#product_combination_bulk_impact_on_price_ti').val(price_incl.toFixed(2));
    }
    else
        $('#product_combination_bulk_impact_on_price_ti').val(product_combination_bulk_impact_on_price_te.toFixed(2));
    if($('.attribute_priceTE').length>0)
    {
        $('.attribute_priceTE').each(function(){
            var id_product_attribute= $(this).data('id_product_attribute');
            var price_attribute_excl = parseFloat($(this).val());
            var price_attribute_incl = price_attribute_excl + price_attribute_excl* parseFloat(tax);
            $('.attribute_priceTI[data-id_product_attribute="'+id_product_attribute+'"]').val(price_attribute_incl.toFixed(2));
        });
    }
});
$(document).on('change','#product_combination_bulk_impact_on_price_ti',function(){
    if(tax = ets_mp_tax_rule_groups[$('select[name="id_tax_rules_group"]').val()])
    {
        var product_combination_bulk_impact_on_price_te = parseFloat($(this).val())/(1+tax);
        $('#product_combination_bulk_impact_on_price_te').val(product_combination_bulk_impact_on_price_te.toFixed(2));
    }
    else
        $('#product_combination_bulk_impact_on_price_te').val($(this).val());
});
$(document).on('change','#price_incl,#price_incl2',function(){
    if($(this).attr('id')=='price_incl')
        $('#price_incl2').val($(this).val());
    else
        $('#price_incl').val($(this).val());
    if(tax = ets_mp_tax_rule_groups[$('select[name="id_tax_rules_group"]').val()])
    {
        var price_excl = parseFloat($(this).val())/(1+tax);
        $('#price_excl').val(price_excl.toFixed(2));
        $('#price_excl2').val(price_excl.toFixed(2));
    }
    else
    {
        $('#price_excl').val($(this).val());
        $('#price_excl2').val($(this).val());
    }
});
$(document).on('change','.attribute_priceTI',function(){
    tax = parseFloat(ets_mp_tax_rule_groups[$('select[name="id_tax_rules_group"]').val()]);
    var id_product_attribute= $(this).data('id_product_attribute');
    if(tax)
    {
        var price_attribute_excl = parseFloat($(this).val())/(1+tax);
        $('.attribute_priceTE[data-id_product_attribute="'+id_product_attribute+'"]').val(price_attribute_excl.toFixed(2));
    }
    else
    {
        $('.attribute_priceTE[data-id_product_attribute="'+id_product_attribute+'"]').val($(this).val());
        var price_attribute_excl = parseFloat($(this).val());
    }
    $('.attribute-finalprice span[data-uniqid="'+id_product_attribute+'"]').html(parseFloat($('.attribute-finalprice span[data-uniqid="'+id_product_attribute+'"]').data('price')) + parseFloat(price_attribute_excl));
    $('.attribute_priceTE[data-id_product_attribute="'+id_product_attribute+'"]').val(price_attribute_excl);
    $('.final-price[data-uniqid="'+id_product_attribute+'"]').html(parseFloat($('.attribute-finalprice span[data-uniqid="'+id_product_attribute+'"]').data('price')) + parseFloat(price_attribute_excl));
});

$(document).on('change','#amount_withdraw',function(){
    $.ajax({
        url: '',
        data: 'checkamountWithdraw=1&amount_withdraw='+$(this).val(),
        type: 'post',
        dataType: 'json',                
        success: function(json){ 
           if(json.error)
           {
                $('#amount_withdraw').parent().parent().addClass('has-error');
                $('#amount_withdraw').parent().parent().find('.help-block').html(json.error);
                $('button.ets_mp-button[type="submit"]').attr('disabled','disabled');
                $('.ets_mp-withdraw-boxes .price').html('');
           } 
           else
           {
                $('#amount_withdraw').parent().parent().removeClass('has-error');
                $('#amount_withdraw').parent().parent().find('.help-block').html('');
                $('button.ets_mp-button[type="submit"]').removeAttr('disabled');
                $('.ets_mp-withdraw-boxes .price').html(json.amount_withdraw);
           }       
        },
        error: function(error)
        {                                      
            
        }
    });
});
$(document).on('click','.ets_mp-apply-voucher',function(){
    $.ajax({
        url: '',
        data: 'addVoucherTocart=1&id_voucher='+$(this).data('voucher-code'),
        type: 'post',
        dataType: 'json',                
        success: function(json){ 
            if(json.success)
            {
                $('.ets_mp-voucer-message').find('.alert').remove();
                $.growl.notice({ message: json.message });
            } 
            else
            {
                $.growl.error({ message: json.message });
            }
        },
        error: function(error)
        {                                      
            
        }
    });
});
$(document).on('click','.product-combination-image > input',function(){
    $(this).closest ('.js-combination-images').find('.number-of-images').html($(this).closest('.js-combination-images').find('input[type="checkbox"]:checked').length+'/'+$(this).closest('.js-combination-images').find('input[type="checkbox"]').length )
});

$(document).ready(function(){
    if($('.ets_marketplace_product_list_wrapper.slide:not(.slick-slider)').length >0)
    {
       $('.ets_marketplace_product_list_wrapper.slide:not(.slick-slider)').slick({
          slidesToShow: 4,
          slidesToScroll: 1,
          arrows: true,
          responsive: [
              {
                  breakpoint: 1199,
                  settings: {
                      slidesToShow: 4
                  }
              },
              {
                  breakpoint: 992,
                  settings: {
                      slidesToShow: 2
                  }
              },
              {
                  breakpoint: 768,
                  settings: {
                      slidesToShow: 2
                  }
              },
              {
                  breakpoint: 480,
                  settings: {
                    slidesToShow: 1
                  }
              }
           ]
       });
    }
    ets_mp_displayProductType();
    if($('#ets_mp_product_form select[name="id_tax_rules_group"]').length>0)
    {
        if(no_user_tax)
            $('select[name="id_tax_rules_group"]').parent().parent().hide();
    }
    if($('#ets_mp_temp_link_registration').length>0)
    {
        if($('#ets_mp_temp_link_registration').is(':checked'))
        {
            $('#submitSeller').removeAttr('disabled');
        }
        else
            $('#submitSeller').attr('disabled','disabled');
        
        $('#ets_mp_temp_link_registration').click(function(){
            if($(this).is(':checked'))
            {
                $('#submitSeller').removeAttr('disabled');
            }
            else
                $('#submitSeller').attr('disabled','disabled');
        });
    }
    $('#tabOrder a').click(function (e) {
        e.preventDefault();
        $('#tabOrder').next('.tab-content').find('.tab-pane').removeClass('active');
        $('#tabOrder li').removeClass('active');
        $(this).parent().addClass('active');
        $('.tab-content '+$(this).attr('href')).addClass('active');
    });
    $('#myTab a').click(function (e) {
        e.preventDefault();
        $('#myTab li').removeClass('active');
        $(this).parent().addClass('active');
        $('#myTab').next('.tab-content').find('.tab-pane').removeClass('active');
        $('.tab-content '+$(this).attr('href')).addClass('active');
      });
      $('#tabAddresses a').click(function (e) {
        e.preventDefault();
        $('#tabAddresses li').removeClass('active');
        $(this).parent().addClass('active');
        $('#tabAddresses').next('.tab-content').find('.tab-pane').removeClass('active');
        $('.tab-content '+$(this).attr('href')).addClass('active');
      });
    $(document).on('click','.delete_customer_search',function(){
        $('.customer_selected').remove();
        if($('#customerFilter').length)
        {
            $('#id_customer').val('');
            $('#customerFilter').val('');
        }
        if($('#specific_price_id_customer_hide').length)
        {
            $('#specific_price_id_customer_hide').val('');
            $('#specific_price_id_customer').val('');
        }
        
    });
    $(document).on('click','.delete_product_search',function(){
        $('.product_selected').remove();
        $('#reduction_product').val('');
        $('#productFilter').val('');
    });
    if ($(".datepicker input").length > 0) {
        var dateToday = new Date();
		$(".datepicker input").datepicker({
			dateFormat: 'yy-mm-dd',
            timeFormat: 'hh:mm:ss',
            minDate: dateToday,
		});
	}
    if ($(".ets_mp_datepicker input").length > 0) {
		$(".ets_mp_datepicker input").datepicker({
			dateFormat: 'yy-mm-dd',
		});
	}
    if($('.ets_mp_autoload_rte').length)
    {
        tinymce.init({
            selector: '.ets_mp_autoload_rte',
            plugins: "align link image media code",
            browser_spellcheck: true,
            themes: "modern",
            toolbar1: "code,colorpicker,bold,italic,underline,strikethrough,blockquote,link,align,bulli,numlist,table,image,media,formatselect",
            convert_urls: false,
            /* enable title field in the Image dialog*/
            image_title: true,
            /* enable automatic uploads of images represented by blob or data URIs*/
            automatic_uploads: true,
            /*
            URL of our upload handler (for more details check: https://www.tiny.cloud/docs/configure/file-image-upload/#images_upload_url)
            images_upload_url: 'postAcceptor.php',
            here we add custom filepicker only to Image dialog
            */
            file_picker_types: 'image',
            /* and here's our custom image picker*/
            file_picker_callback: function(cb, value, meta) {
                var input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                
                /*
                Note: In modern browsers input[type="file"] is functional without
                even adding it to the DOM, but that might not be the case in some older
                or quirky browsers like IE, so you might want to add it to the DOM
                just in case, and visually hide it. And do not forget do remove it
                once you do not need it anymore.
                */
                
                input.onchange = function() {
                    var file = this.files[0];
                    console.log(file);
                    
                    var reader = new FileReader();
                    reader.onload = function() {
                        /*
                        Note: Now we need to register the blob in TinyMCEs image blob
                        registry. In the next release this part hopefully won't be
                        necessary, as we are looking to handle it internally.
                        */
                        var id = 'blobid' + (new Date()).getTime();
                        var blobCache = tinymce.activeEditor.editorUpload.blobCache;
                        var base64 = reader.result.split(',')[1];
                        console.log(reader.result);
                        var blobInfo = blobCache.create(id, file, base64);
                        blobCache.add(blobInfo);
                        
                        / call the callback and populate the Title field with the file name /
                        cb(blobInfo.blobUri(), {
                            title: file.name
                        });
                    };
                    reader.readAsDataURL(file);
                };
                input.click();
            },
            setup: function(editor) {
                editor.on('keyup', function(e) {
                //console.log('edited. Contents: ' + editor.getContent());
                    if($('#'+editor.id).hasClass('change_length'))
                        $('#'+editor.id).parent().parent().find('.currentLength').html(editor.getContent().replace(/(<([^>]+)>)/ig,"").length);
                });
                editor.on('init', function(){
                    if (editor.getContent() == '' && $('#'+editor.id).attr('placeholder')){
                        editor.setContent("<p id='#etsmp_imThePlaceholder' style='opacity: 0.5;font-size: 12px;'><em>"+$('#'+editor.id).attr('placeholder')+"</em></p>");
                }
                });
                //and remove it on focus
                editor.on('focus',function(){
                    if(editor.getContent().indexOf("#etsmp_imThePlaceholder") >0)
                        editor.setContent('');
                });
                editor.on('blur',function(){
                    if (editor.getContent() == '' && $('#'+editor.id).attr('placeholder')){
                        editor.setContent("<p id='#etsmp_imThePlaceholder' style='opacity: 0.5;font-size: 12px;'><em>"+$('#'+editor.id).attr('placeholder')+"</em></p>");
                    }
                });
            }
        });
        $(document).on('focus','.mce-textbox',function(){
            setTimeout(function(){
               if($('.mce-textbox').val().indexOf("#etsmp_imThePlaceholder") >0)
                    $('.mce-textbox').val(''); 
            },100);
            
        });
    } 
    if($('#search_product_pack').length)
    {
        $('#search_product_pack').autocomplete(ets_mp_url_search_product,{
    		minChars: 1,
    		autoFill: true,
    		max:20,
    		matchContains: true,
    		mustMatch:false,
    		scroll:false,
    		cacheLength:0,
    		formatItem: function(item) {
    			return (item[4] ? '<img src="'+item[4]+'" style="width:24px;"/>':'')+' - '+item[2]+' <br/> '+(item[3] ? 'REF: '+item[3]:'');
    		}
    	}).result(etsMPAddProductPack);
    }
    if($('#form_step1_related_products').length)
    {
        $('#form_step1_related_products').autocomplete(ets_mp_url_search_related_product,{
    		minChars: 1,
    		autoFill: true,
    		max:20,
    		matchContains: true,
    		mustMatch:false,
    		scroll:false,
    		cacheLength:0,
    		formatItem: function(item) {
    			return (item[4] ? '<img src="'+item[4]+'" style="width:24px;"/>':'') +' - '+item[2]+' <br/> '+(item[3] ? 'REF: '+item[3]:'');
    		}
    	}).result(etsMPAddProductRelated);
    }
    if($('#customerFilter').length)
    {
        $('#customerFilter').autocomplete(ets_mp_url_search_customer,{
    		minChars: 1,
    		autoFill: true,
    		max:20,
    		matchContains: true,
    		mustMatch:false,
    		scroll:false,
    		cacheLength:0,
    		formatItem: function(item) {
    			return item[1]+' ('+item[2]+')';
    		}
    	}).result(etsMPAddCustomerCartRule);
    }
    if($('#specific_price_id_customer').length)
    {
        $('#specific_price_id_customer').autocomplete(ets_mp_url_search_customer,{
    		minChars: 1,
    		autoFill: true,
    		max:20,
    		matchContains: true,
    		mustMatch:false,
    		scroll:false,
    		cacheLength:0,
    		formatItem: function(item) {
    			return item[1]+' ('+item[2]+')';
    		}
    	}).result(etsMPAddCustomerSpecific);
    }
    if($('#productFilter').length)
    {
        $('#productFilter').autocomplete(ets_mp_url_search_product,{
    		minChars: 1,
    		autoFill: true,
    		max:20,
    		matchContains: true,
    		mustMatch:false,
    		scroll:false,
    		cacheLength:0,
    		formatItem: function(item) {
    			return '<img src="'+item[4]+'" style="width:24px;"/>'+' - '+item[2]+' <br/> '+(item[3] ? 'REF: '+item[3]:'');
    		}
    	}).result(etsMPAddProudctCartRule);
    }
    if($('#amount_withdraw').length && $('#amount_withdraw').val()!='' && $('#amount_withdraw').val()!='0')
    {
        $.ajax({
            url: '',
            data: 'checkamountWithdraw=1&amount_withdraw='+$('#amount_withdraw').val(),
            type: 'post',
            dataType: 'json',                
            success: function(json){ 
               if(json.error)
               {
                    $('#amount_withdraw').parent().parent().addClass('has-error');
                    $('#amount_withdraw').parent().parent().find('.help-block').html(json.error);
                    $('button.ets_mp-button[type="submit"]').attr('disabled','disabled');
                    $('.ets_mp-withdraw-boxes .price').html('');
               } 
               else
               {
                    $('#amount_withdraw').parent().parent().removeClass('has-error');
                    $('#amount_withdraw').parent().parent().find('.help-block').html('');
                    $('button.ets_mp-button[type="submit"]').removeAttr('disabled');
                    $('.ets_mp-withdraw-boxes .price').html(json.amount_withdraw);
               }       
            },
            error: function(error)
            {                                      
                
            }
        });
    }
});
var etsMPAddCustomerCartRule = function(event,data,formatted)
{
    if (data == null)
        return false;
    $('#id_customer').val(data[0]);
    if($('#customerFilter').next('.customer_selected').length <=0)
    {
       $('#customerFilter').after('<div class="customer_selected">'+data[1]+' ('+data[2]+') <span class="delete_customer_search">delete</span><div>');
       $('#customerFilter').val(''); 
    }
}
var etsMPAddCustomerSpecific = function(event,data,formatted)
{
    if (data == null)
        return false;
    $('#specific_price_id_customer_hide').val(data[0]);
    if($('#specific_price_id_customer').next('.customer_selected').length <=0)
    {
       $('#specific_price_id_customer').after('<div class="customer_selected">'+data[1]+' <span class="delete_customer_search">delete</span><div>');
       $('#specific_price_id_customer').val(data[0]); 
    }
}
var etsMPAddProudctCartRule = function(event,data,formatted)
{
    if (data == null)
		return false;
    $('#reduction_product').val(data[0]);
    if($('#productFilter').next('.products_selected').length <=0)
    {
       $('#productFilter').before('<div class="product_selected">'+data[2]+' <span class="delete_product_search">delete</span><div>');
       $('#productFilter').val(''); 
    }
}
var etsMPAddProductPack = function(event, data, formatted)
{
	if (data == null)
		return false;
	var id_product = data[0];
	var id_product_attribute = data[1];
    var name_product = data[2];
    var reference_product= data[3];
    var image_product= data[4];
    $('#search_product_pack').val(name_product);
    var $html = '<li class="col-xl-3 col-lg-6 mb-1" data-product="'+data[0]+'-'+data[1]+'">';
        $html +='<div class="pack-product">';
            if(image_product)
            $html +='<img class="cover" src="'+image_product+'" />';
            $html +='<h4>'+name_product+'</h4>';
            if(reference_product)
                $html +='<div class="ref">REF: '+reference_product+'</div>';
            $html += '<div class="quantity text-md-right"></div>';
            $html += '<input class="inputPackItems" name="" value="'+id_product+'x'+id_product_attribute+'" type="hidden" />';
            $html += '<button class="btn btn-danger btn-sm delete ets_mp_delete_pack_product" type="button" title="Delete">';
            $html += '<i class="icon delete-icon"></i>';
            $html += '</button>';
        $html += '</div>';
    $html +='</li>';
    $('#search_product_pack_content').html($html);
};
var etsMPAddProductRelated = function(event, data, formatted)
{
	if (data == null)
		return false;
	var id_product = data[0];
	var id_product_attribute = data[1];
    var name_product = data[2];
    var reference_product= data[3];
    var image_product= data[4];
    $('#form_step1_related_products').val('');
    var $html = '<li class="media">';
        $html +=' <div class="media-left">'+(image_product ? '<img class="media-object image" src="'+image_product+'" />':'')+' </div>';
        $html +='<div class="media-body media-middle">';
            $html +='<span class="label">'+name_product+(reference_product ? ' (ref: '+reference_product+')':'')+'</span>';
            $html +='<i class="fa fa-times delete delete_related"></i>';
        $html +='</div>';
        $html +='<input name="related_products[]" value="'+id_product+'" type="hidden">';
    $html +='</li>';
    $('#form_step1_related_products-data').append($html);
};
function hideOtherLanguage(id)
{
    $('.translatable-field').hide();
    $('.translatable-field.lang-'+id).show();
    $('.col-lg-2.open').removeClass('open');
}
function str2url(str, encoding, ucfirst)
{
	str = str.toUpperCase();
	str = str.toLowerCase();
	if (PS_ALLOW_ACCENTED_CHARS_URL)
		str = str.replace(/[^a-z0-9\s\'\:\/\[\]-]\\u00A1-\\uFFFF/g,'');
	else
	{

	  /* Lowercase */
    str = str.replace(/[\u00E0\u00E1\u00E2\u00E3\u00E5\u0101\u0103\u0105\u0430\u1EA7\u1EA3\u1EA1\u1EAF\u1EB1\u1EB3\u1EB5\u1EB7\u1EA5\u1EA9\u1EAB\u1EAD]/g, 'a');
    str = str.replace(/[\u0431]/g, 'b');
    str = str.replace(/[\u00E7\u0107\u0109\u010D\u0446]/g, 'c');
    str = str.replace(/[\u010F\u0111\u0434]/g, 'd');
    str = str.replace(/[\u00E8\u00E9\u00EA\u00EB\u0113\u0115\u0117\u0119\u011B\u0435\u044D\u1EC7\u1EBB\u1EBD\u1EB9\u1EBF\u1EC1\u1EC3\u1EC5]/g, 'e');
    str = str.replace(/[\u0444]/g, 'f');
    str = str.replace(/[\u011F\u0121\u0123\u0433\u0491]/g, 'g');
    str = str.replace(/[\u0125\u0127]/g, 'h');
    str = str.replace(/[\u00EC\u00ED\u00EE\u00EF\u0129\u012B\u012D\u012F\u0131\u0438\u0456\u1EC9\u1ECB]/g, 'i');
    str = str.replace(/[\u0135\u0439]/g, 'j');
    str = str.replace(/[\u0137\u0138\u043A]/g, 'k');
    str = str.replace(/[\u013A\u013C\u013E\u0140\u0142\u043B]/g, 'l');
    str = str.replace(/[\u043C]/g, 'm');
    str = str.replace(/[\u00F1\u0144\u0146\u0148\u0149\u014B\u043D]/g, 'n');
    str = str.replace(/[\u00F2\u00F3\u00F4\u00F5\u00F8\u014D\u014F\u0151\u043E\u01A1]/g, 'o');
    str = str.replace(/[\u043F]/g, 'p');
    str = str.replace(/[\u0155\u0157\u0159\u0440]/g, 'r');
    str = str.replace(/[\u015B\u015D\u015F\u0161\u0441]/g, 's');
    str = str.replace(/[\u00DF]/g, 'ss');
    str = str.replace(/[\u0163\u0165\u0167\u0442]/g, 't');
    str = str.replace(/[\u00F9\u00FA\u00FB\u0169\u016B\u016D\u016F\u0171\u0173\u0443\u1EED]/g, 'u');
    str = str.replace(/[\u0432]/g, 'v');
    str = str.replace(/[\u0175]/g, 'w');
    str = str.replace(/[\u00FF\u0177\u00FD\u044B]/g, 'y');
    str = str.replace(/[\u017A\u017C\u017E\u0437]/g, 'z');
    str = str.replace(/[\u00E4\u00E6]/g, 'ae');
    str = str.replace(/[\u0447]/g, 'ch');
    str = str.replace(/[\u0445]/g, 'kh');
    str = str.replace(/[\u0153\u00F6]/g, 'oe');
    str = str.replace(/[\u00FC]/g, 'ue');
    str = str.replace(/[\u0448]/g, 'sh');
    str = str.replace(/[\u0449]/g, 'ssh');
    str = str.replace(/[\u044F]/g, 'ya');
    str = str.replace(/[\u0454]/g, 'ye');
    str = str.replace(/[\u0457]/g, 'yi');
    str = str.replace(/[\u0451]/g, 'yo');
    str = str.replace(/[\u044E]/g, 'yu');
    str = str.replace(/[\u0436]/g, 'zh');

    /* Uppercase */
    str = str.replace(/[\u0100\u0102\u0104\u00C0\u00C1\u00C2\u00C3\u00C4\u00C5\u0410\u1EA2\u1EA0\u1EAE\u1EB0\u1EB2\u1EB4\u1EB6\u1EA4\u1EA6\u1EA8\u1EAA\u1EAC]/g, 'A');
    str = str.replace(/[\u0411]/g, 'B');
    str = str.replace(/[\u00C7\u0106\u0108\u010A\u010C\u0426]/g, 'C');
    str = str.replace(/[\u010E\u0110\u0414]/g, 'D');
    str = str.replace(/[\u00C8\u00C9\u00CA\u00CB\u0112\u0114\u0116\u0118\u011A\u0415\u042D\u1EBA\u1EBC\u1EB8\u1EBE\u1EC0\u1EC2\u1EC4\u1EC6]/g, 'E');
    str = str.replace(/[\u0424]/g, 'F');
    str = str.replace(/[\u011C\u011E\u0120\u0122\u0413\u0490]/g, 'G');
    str = str.replace(/[\u0124\u0126]/g, 'H');
    str = str.replace(/[\u0128\u012A\u012C\u012E\u0130\u0418\u0406\u00CD\u00CC\u1EC8\u1ECA]/g, 'I');
    str = str.replace(/[\u0134\u0419]/g, 'J');
    str = str.replace(/[\u0136\u041A]/g, 'K');
    str = str.replace(/[\u0139\u013B\u013D\u0141\u041B]/g, 'L');
    str = str.replace(/[\u041C]/g, 'M');
    str = str.replace(/[\u00D1\u0143\u0145\u0147\u014A\u041D]/g, 'N');
    str = str.replace(/[\u00D3\u014C\u014E\u0150\u041E]/g, 'O');
    str = str.replace(/[\u041F]/g, 'P');
    str = str.replace(/[\u0154\u0156\u0158\u0420]/g, 'R');
    str = str.replace(/[\u015A\u015C\u015E\u0160\u0421]/g, 'S');
    str = str.replace(/[\u0162\u0164\u0166\u0422]/g, 'T');
    str = str.replace(/[\u00D9\u00DA\u00DB\u0168\u016A\u016C\u016E\u0170\u0172\u0423]/g, 'U');
    str = str.replace(/[\u0412]/g, 'V');
    str = str.replace(/[\u0174]/g, 'W');
    str = str.replace(/[\u0176\u042B]/g, 'Y');
    str = str.replace(/[\u0179\u017B\u017D\u0417]/g, 'Z');
    str = str.replace(/[\u00C4\u00C6]/g, 'AE');
    str = str.replace(/[\u0427]/g, 'CH');
    str = str.replace(/[\u0425]/g, 'KH');
    str = str.replace(/[\u0152\u00D6]/g, 'OE');
    str = str.replace(/[\u00DC]/g, 'UE');
    str = str.replace(/[\u0428]/g, 'SH');
    str = str.replace(/[\u0429]/g, 'SHH');
    str = str.replace(/[\u042F]/g, 'YA');
    str = str.replace(/[\u0404]/g, 'YE');
    str = str.replace(/[\u0407]/g, 'YI');
    str = str.replace(/[\u0401]/g, 'YO');
    str = str.replace(/[\u042E]/g, 'YU');
    str = str.replace(/[\u0416]/g, 'ZH');

		str = str.toLowerCase();

		str = str.replace(/[^a-z0-9\s\'\:\/\[\]-]/g,'');
	}
	str = str.replace(/[\u0028\u0029\u0021\u003F\u002E\u0026\u005E\u007E\u002B\u002A\u002F\u003A\u003B\u003C\u003D\u003E]/g, '');
	str = str.replace(/[\s\'\:\/\[\]-]+/g, ' ');

	// Add special char not used for url rewrite
	str = str.replace(/[ ]/g, '-');
	str = str.replace(/[\/\\"'|,;%]*/g, '');

	if (ucfirst == 1) {
		var first_char = str.charAt(0);
		str = first_char.toUpperCase()+str.slice(1);
	}

	return str;
}
function ets_mp_str2url(str)
{
    var ok=true;
    while(ok)
    {
        var first_char = str.charAt(0);
        if(!isNaN(first_char))
        {
            str =str.slice(1);
        }
        else
            return str;
        
    }
}
function ets_mp_updateFriendlyURL()
{
    if(!parseInt($('#ets_mp_id_product').val()))
    {
        $('input[name="link_rewrite_'+id_lang_default+'"]').val(str2url(ets_mp_str2url($('input[name="name_'+id_lang_default+'"').val()), 'UTF-8')); 
    }        
    else
        if($('input[name="link_rewrite_'+id_lang_default+'"]').val() == '')
            $('input[name="link_rewrite_'+id_lang_default+'"]').val(str2url(ets_mp_str2url($('input[name="name_'+id_lang_default+'"').val()), 'UTF-8')); 
    if($('.change_length').length)
    {
        $('.change_length').each(function(){
            $(this).parent().parent().find('.currentLength').html($(this).val().length);
        });
    }
}
function ets_mp_displayProductType()
{
    if($('#product_type').length>0)
    {
        var product_type= $('#product_type').val();
        if(product_type==0 || product_type==1)
        {
            $('.ets_mp_tab[data-tab="Quantities"]').html(quantities_text);
            $('#virtual_product').hide();
            $('.ets_mp_tab[data-tab="Shipping"]').show();
            if(product_type==0)
            {
                
                $('#pack_stock_type').hide();
                $('.form-group.ets_mp_show_variations').show();
                $('.form-group.ets_mp_form_pack_product').hide();
                if($('input[name="show_variations"]:checked').val()==1)
                {
                    $('.ets_mp_tab[data-tab="Combinations"]').show();
                    $('.ets_mp_tab[data-tab="Quantities"]').hide();
                    $('#product_type').attr('disabled','disabled');
                }
                else
                {
                    $('.ets_mp_tab[data-tab="Combinations"]').hide();
                    $('.ets_mp_tab[data-tab="Quantities"]').show();
                    $('#product_type').removeAttr('disabled');
                }
                $('input[name="show_variations"]').click(function(){
                    if($(this).val()==1)
                    {
                        $('.ets_mp_tab[data-tab="Combinations"]').show();
                        $('.ets_mp_tab[data-tab="Quantities"]').hide();
                        $('#product_type').attr('disabled','disabled');
                    }
                    else
                    {
                        $('.ets_mp_tab[data-tab="Combinations"]').hide();
                        $('.ets_mp_tab[data-tab="Quantities"]').show();
                        $('#product_type').removeAttr('disabled');
                    }
                });
                
            }
            else
            {
                $('.ets_mp_tab[data-tab="Combinations"]').hide(); 
                $('#pack_stock_type').show();
                $('.form-group.ets_mp_show_variations').hide();
                $('.ets_mp_tab[data-tab="Quantities"]').show();
                $('.form-group.ets_mp_form_pack_product').show();
            }   
        }
        else
        {
            $('.ets_mp_tab[data-tab="Combinations"]').hide(); 
            $('.ets_mp_tab[data-tab="Shipping"]').hide(); 
            $('.ets_mp_tab[data-tab="Quantities"]').html(virtual_product_text);
            $('#virtual_product').show();
            $('#pack_stock_type').hide();
            $('.ets_mp_tab[data-tab="Quantities"]').show();
            $('.form-group.ets_mp_show_variations').hide();
            $('.form-group.ets_mp_form_pack_product').hide();
            if($('input[name="is_virtual_file"]:checked').val()==1)
                $('#virtual_product_content').show();
            else
                $('#virtual_product_content').hide();
            $('input[name="is_virtual_file"]').click(function(){
                if($(this).val()==1)
                    $('#virtual_product_content').show();
                else
                    $('#virtual_product_content').hide();
            });
        }
    }
}
function ets_displayCartRuleTab(tab)
{
    $('.productTabs .tab-row').removeClass('active');
    $('.cart_rule_tab').hide();
    $('#cart_rule_form #cart_rule_'+tab).show();
    $('#currentFormTab').val(tab);
    $('#cart_rule_link_'+tab).parent().addClass('active');
}
function ets_cart_rulegencode(size)
{
    code = '';
	/* There are no O/0 in the codes in order to avoid confusion */
	var chars = "123456789ABCDEFGHIJKLMNPQRSTUVWXYZ";
	for (var i = 1; i <= size; ++i)
		code += chars.charAt(Math.floor(Math.random() * chars.length));
    $('input[name="code"]').val(code); 
}
function ets_mp_OrderOverwriteMessage(sl,confirm_text)
{
    if(confirm(confirm_text))
        $('#txt_msg').val($('#order_message').val());
    else
        return false;
}    
function ets_mpLoadProductShopPage()
{
    if($('#content-wrapper').hasClass('loading'))
        return false;
    var data_tab = $('.ets_mp_tabs .tab_link.active').data('tab');
    var current_page = $('.product_tab.ets_mp_shop_tab.tab_'+data_tab+' .ets_mp_current_tab').val();
    var idCategories='';
    if($('.shop_categories:checked').length)
    {
        $('.shop_categories:checked').each(function(){
            idCategories +=$(this).val()+',';
        });
        $('.product_categories_selected').html(selected_categories+' ('+$('.shop_categories:checked').length+')');
        $('.product_categories_selected').show();
        $('.all_product_text').hide();
        $('.ets_mp_block-categories').addClass('show_clear');
    }
    else
    {
        $('.product_categories_selected').hide();
        $('.all_product_text').show();
        $('.ets_mp_block-categories').removeClass('show_clear');
    }
    $.ajax({
        url: link_ajax_sort_product_list,
        data: {
            ajax:1,
            order_by : $('.product_tab.ets_mp_shop_tab.tab_'+data_tab+' .ets_mp_sort_by_product_list').val(),
            current_tab : data_tab,
            page: 1,
            product_name: $('.product_tab.ets_mp_shop_tab.tab_'+data_tab+' input[name="product_search"]').val(),
            idCategories: idCategories
        },
        type: 'post',
        dataType: 'json',                
        success: function(json){
            $('.product_tab.ets_mp_shop_tab.tab_'+data_tab).html(json.product_list);
            if(data_tab=='all')
            {
                if($('.ets_mp_tabs input[name="product_search"]').val().trim()!='')
                    if(link_ajax_sort_product_list.indexOf('?')>=0)
                        window.history.pushState("", "", link_ajax_sort_product_list+'&product_name='+$('.ets_mp_tabs input[name="product_search"]').val());
                    else
                        window.history.pushState("", "", link_ajax_sort_product_list+'?product_name='+$('.ets_mp_tabs input[name="product_search"]').val());    
                    
                else 
                    window.history.pushState("", "", link_ajax_sort_product_list);
            }
            else
                window.history.pushState("", "", $('.tab_link[data-tab="'+data_tab+'"]').attr('href')); 
            if(is_product_comment)
            {
                ets_mp_loadProductComment(data_tab);
            }
            ets_mp_moveblocksearchPageShop();        
            if(is_product_comment)
            {
                ets_mp_loadProductComment(data_tab);
            }
            ets_checkRateShopby();                       
        }
    });
}
function ets_mp_loadProductComment(tab)
{
    if($('.tab_'+tab+' .js-product-miniature').length)
    {
        $('.tab_'+tab+' .js-product-miniature').each(function(){
            const productId = $(this).data('id-product');
            const productReview = $('.js-product-miniature[data-id-product="'+productId+'"] .product-list-reviews');
            $.get(product_comment_grade_url, { id_product: productId }, function(jsonResponse) {
              var jsonData = false;
                  try {
                    jsonData = JSON.parse(jsonResponse);
                  } catch (e) {
              }
              if (jsonData) {
                    if (jsonData.id_product && jsonData.comments_nb) {
                      $('.grade-stars', productReview).rating({ grade: jsonData.average_grade, starWidth: 16 });
                      $('.comments-nb', productReview).html('('+jsonData.comments_nb+')');
                      productReview.closest('.thumbnail-container').addClass('has-reviews');
                      productReview.css('visibility', 'visible');
                    }
                    ets_checkRateShopby();
              }
            });
        });
    }
}
function ets_mp_readShopLogoURL(input){
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            if($(input).parents('.ets_upload_file_custom').prev('.shop_logo').length <= 0)
            {
                //alert('c');
                $(input).parents('.ets_upload_file_custom').before('<div class="shop_logo"><img class="ets_mp_shop_logo" src="'+e.target.result+'" width="150px"><a class="btn btn-default delete_logo_upload" href=""><i class="fa fa-trash"></i></a></div>');

            }
            else
            {
                //alert('s');
                $(input).parents('.ets_upload_file_custom').prev('.shop_logo').find('.ets_mp_shop_logo').attr('src',e.target.result);
            }
                                      
        }
        reader.readAsDataURL(input.files[0]);
    }
}
function TogglePackage(id_product)
{
    $('#pack_items_'+id_product).toggle();
}
function ets_mp_moveblocksearchPageShop()
{
    if($('.product_tab.ets_mp_shop_tab.active .js-product-list-top').next('.ets_mp_search_product').length)
    {
        if($('.ets_mp_tabs .block-search').length)
            $('.ets_mp_tabs .block-search').remove();
            
        if($('.ets_mp_tabs .col_sortby').length)
            $('.ets_mp_tabs .col_sortby').remove();
            
            var tab_search_content = $('.product_tab.ets_mp_shop_tab.active .js-product-list-top').next('.ets_mp_search_product').html();
        $('.ets_mp_tabs_content + .ets_mp_tabs_content_search').append(tab_search_content);
        //alert($('.product_tab.ets_mp_shop_tab.active .js-product-list-top').next('.ets_mp_search_product').html());
    }
}
function ets_mpChanegVacationMode()
{
    if($('input[name="vacation_mode"]').length)
    {
        if($('input[name="vacation_mode"]:checked').val()==1)
        {
            $('.form-group.enable_vacation_mode').show();
            if($('#vacation_type').val()=='show_notifications')
                $('.form-group.show_notifications').show();
            else
                $('.form-group.show_notifications').hide();
        }
        else
            $('.form-group.enable_vacation_mode').hide();
    }
}