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
$(document).ready(function(){
    $(document).on('keypress','input[type="number"]',function(event){  
        if(event.which != 8 && isNaN(String.fromCharCode(event.which)) && event.which!=45){
           event.preventDefault();
       }
    });
    $(document).on('keyup change','.mp_stocks_quantity',function(event){
       if($(this).val()!='')
       {
            $(this).parents('.edit_quantity').addClass('enabled');
            $(this).parents('.edit_quantity').find('button').removeAttr('disabled');
            var quantity = parseInt($(this).parents('tr').find('.quantity').html()) + parseInt($(this).val());
            if($(this).parents('tr').find('.quantity .qty-update').length==0)
                $(this).parents('tr').find('.quantity').append('<span class="qty-update"><i class="fa fa-long-arrow-right" aria-hidden="true"></i> '+quantity+'</span>');
            else
                $(this).parents('tr').find('.quantity .qty-update').html('<i class="fa fa-long-arrow-right" aria-hidden="true"></i> '+quantity);
       }
       else
       {
            if($(this).parents('tr').find('.quantity .qty-update').length)
                $(this).parents('tr').find('.quantity .qty-update').remove();
            $(this).parents('.edit_quantity').removeClass('enabled');
            $(this).parents('.edit_quantity').find('button').attr('disabled','disabled');
       }
       ets_mp_displayButtonApplyNewQuatity();
    });
    $(document).on('click','.ps-number-up',function(){
        var quantity = $(this).parent().prev('input[type="number"]').val()!='' ? parseInt($(this).parent().prev('input[type="number"]').val())+1 :1;
        $(this).parent().prev('input[type="number"]').val(quantity);
        $(this).parent().prev('input[type="number"]').change();
    });
    $(document).on('click','.ps-number-down',function(){
        var quantity = $(this).parent().prev('input[type="number"]').val()!=''? parseInt($(this).parent().prev('input[type="number"]').val())-1 :-1;
        $(this).parent().prev('input[type="number"]').val(quantity);
        $(this).parent().prev('input[type="number"]').change();
    });
    $(document).on('change','.mp_stocks_boxs',function(){
        
        if($(this).is(':checked'))
        {
            $(this).parents('tr').addClass('checked');
            $(this).parents('tr').find('.mp_stocks_quantity').val($('#mp_stocks_quantity_all').val()).change();
            if(!$('#bulk-action').is(':checked'))
            {
                $('#bulk-action').prop('checked', $(this).prop('checked'));
                $('#bulk-action').parents('.col-md-8').addClass('enabled');
            }
        }
        else
        {
            $(this).parents('tr').removeClass('checked');
            $(this).parents('tr').find('.mp_stocks_quantity').val('').change();
            if($('.mp_stocks_boxs:checked').length==0)
            {
                $('#bulk-action').prop('checked', $(this).prop('checked'));
                $('#bulk-action').parents('.col-md-8').removeClass('enabled');
            }
        }
        if($('.mp_stocks_boxs:checked').length!=0 && $('.mp_stocks_boxs:checked').length !=$('.mp_stocks_boxs').length)
            $('#bulk-action').addClass('indeterminate');
        else
            $('#bulk-action').removeClass('indeterminate');
    });
    $(document).on('keyup change','#mp_stocks_quantity_all',function(){
        $('tr.checked .mp_stocks_quantity').val($(this).val());
        $('tr.checked .mp_stocks_quantity').change();
    });
    $(document).on('click','#apply-new-quanitty',function(e){
        e.preventDefault();
        var formData = new FormData($(this).parents('form').get(0));
        formData.append('applyNewQuantity', 1);
        $.ajax({
            url: '',
            data: formData,
            type: 'post',
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(json){
                if(json.errors)
                    $.growl.error({message:json.errors});
                if(json.success)
                {
                    $.growl.notice({ message: json.success });
                    $('.mp_stocks_quantity').each(function(){
                       if($(this).val()!='')
                       {
                            var quantity = parseInt($(this).parents('tr').find('.quantity').html()) + parseInt($(this).val());
                            $(this).parents('tr').find('.quantity').html(quantity);
                            $(this).val('').change();
                       } 
                    });
                    $('#mp_stocks_quantity_all').val('').change();
                }
            },
            error: function(xhr, status, error)
            {     
                $('button[name="submitContactMarketplace"]').removeClass('loading');
            }
        });
    });
    $(document).on('click','.edit_quantity .check-button',function(e){
        e.preventDefault();
        var $this= $(this);
        var id_stock_available = $this.prev('.edit-qty').data('id_stock_available');
        var quantity = $this.prev('.edit-qty').find('.mp_stocks_quantity').val();
        var data = {
           editStockAvailable:1,
           id_stock_available : id_stock_available,
           quantity: quantity, 
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
                    $this.prev('.edit-qty').find('.mp_stocks_quantity').val('').change();
                    $this.parents('tr').find('.quantity').html(json.quantity);
                }
                if(json.errors)
                    $.growl.error({ message: json.errors });  
            },
            error: function(error)
            {   
                
            }
        });
    });
});
function ets_mp_updateBulkStock($this)
{
    if($this.is(':checked'))
    {
        $this.parents('.col-md-8').addClass('enabled');
        $('#list-mp_stocks tr').addClass('checked');
        if ( $('#list-mp_stocks tr').find('div.checker').length > 0 ){
            $('#list-mp_stocks tr').find('div.checker span').addClass('checked');
        }
        $('.mp_stocks_quantity').val($('#mp_stocks_quantity_all').val()).change();
    }
    else
    {
        $this.parents('.col-md-8').removeClass('enabled');
        $('#list-mp_stocks tr').removeClass('checked');
        if ( $('#list-mp_stocks tr').find('div.checker').length > 0 ){
            $('#list-mp_stocks tr').find('div.checker span').removeClass('checked');
        }
        $('.mp_stocks_quantity').val('').change();
    }
}
function ets_mp_displayButtonApplyNewQuatity()
{
    $('#apply-new-quanitty').attr('disabled','disabled');
    $('.mp_stocks_quantity').each(function(){
        if($(this).val()!='')
            $('#apply-new-quanitty').removeAttr('disabled');    
    });
    
}