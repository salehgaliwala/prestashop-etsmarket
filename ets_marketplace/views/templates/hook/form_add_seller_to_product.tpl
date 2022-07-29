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
{if $is_ps16}
    <div class="from_product_seller_is16">
        <div class="form-group">
            <label class="control-label col-lg-3" for="tags_1">
                <span> {l s='Seller' mod='ets_marketplace'} </span>
            </label>
            <div class="col-lg-9">
                <div class="col-xl-12 col-lg-12" id="form_step6_seller_product_field">
                    <div class="search search-with-icon{if $seller_product} has_seller{/if}">
                        {if $seller_product}
                            <div class="seller_selected">
                                <div class="seller_name">
                                    {$seller_product->shop_name|escape:'html':'UTF-8'} ({$seller_product->seller_email|escape:'html':'UTF-8'})
                                </div>
                                <span class="delete_seller_search" data-id_product="{$id_product|intval}">{l s='Delete' mod='ets_marketplace'}</span>
                            </div>
                        {/if}
                        <input id="form_step6_seller_product" name="form_step6_seller_product" class="form-control ac_input" placeholder="{l s='Search by ID, name or shop' mod='ets_marketplace'}" type="text" autocomplete="off"/>
                        <input id="id_seller_product" type="hidden" value="{$id_product|intval}" />
                    </div>
                </div>
            </div>
        </div>
    </div>
{else}
    <div class="form-group mb-4">
        <h2>
            {l s='Seller' mod='ets_marketplace'}
        </h2>
        <div class="row">
            <div class="col-xl-12 col-lg-12" id="form_step6_seller_product_field">
                <div class="search search-with-icon{if $seller_product} has_seller{/if}">
                    {if $seller_product}
                        <div class="seller_selected">
                            <div class="seller_name">
                                {$seller_product->shop_name|escape:'html':'UTF-8'} ({$seller_product->seller_email|escape:'html':'UTF-8'})
                            </div>
                            <span class="delete_seller_search" data-id_product="{$id_product|intval}">{l s='Delete' mod='ets_marketplace'}</span>
                        </div>
                    {/if}
                    <input id="form_step6_seller_product" name="form_step6_seller_product" class="form-control ac_input" placeholder="{l s='Search by ID, name or shop' mod='ets_marketplace'}" type="text" autocomplete="off"/>
                    <input id="id_seller_product" type="hidden" value="{$id_product|intval}" />
                </div>
            </div>
        </div>
    </div>
{/if}
<script type="text/javascript">
var link_search_seller ='{$link_search_seller nofilter}';
var confirm_del_seller ='{l s='Do you want to delete this seller?' mod='ets_marketplace' js=1}';
var xhr;
{literal}
$(document).ready(function(){
    if($('.from_product_seller_is16').length){
        $('#product-informations .panel-footer').before($('.from_product_seller_is16').html());
        $('.from_product_seller_is16').remove();
    };
    $(document).on('blur','#form_step6_seller_product',function(){
       $('.list_sellers li.active').removeClass('active');
    });
    $(document).on('keyup','#form_step6_seller_product',function(e){
        if((e.keyCode==13 || e.keyCode==38 || e.keyCode==40) && $('.list_sellers').length)
        {
            if(e.keyCode==40)
            {
                if($('.list_sellers li.active').length==0)
                {
                    $('.list_sellers li:first').addClass('active');
                }
                else
                {
                    var $li_active = $('.list_sellers li.active');
                    $('.list_sellers li.active').removeClass('active');
                    if($li_active.next('li').length)
                        $li_active.next('li').addClass('active');
                    else
                        $('.list_sellers li:first').addClass('active');
                }
            }
            if(e.keyCode==38)
            {
                if($('.list_sellers li.active').length==0)
                {
                    $('.list_sellers li:last').addClass('active');
                }
                else
                {
                    var $li_active = $('.list_sellers li.active');
                    $('.list_sellers li.active').removeClass('active');
                    if($li_active.prev('li').length)
                        $li_active.prev('li').addClass('active');
                    else
                        $('.list_sellers li:last').addClass('active');
                }
            }
            if(e.keyCode==13)
            {
                $('.list_sellers li.active').click();
            }
        }
        else
        {
            if(xhr)
                xhr.abort();
            $('#form_step6_seller_product').next('.list_sellers').remove();
            xhr = $.ajax({
    			type: 'POST',
    			headers: { "cache-control": "no-cache" },
    			url: link_search_seller,
    			async: true,
    			cache: false,
    			dataType : "json",
    			data:'getSellerProductByAdmin=1&q='+$('#form_step6_seller_product').val(),
    			success: function(json)
    			{
                    if(json.sellers)
                    {
                        var $html ='<ul class="list_sellers">';
                        $(json.sellers).each(function(){
    						$html +='<li data-id_customer="'+this.id_customer+'"> '+this.shop_name+ '('+this.email+') </li>';
    					});
                        $html +='</ul>';
                        $('#form_step6_seller_product').after($html);
                        $('.list_sellers li').hover(function(){ $('.list_sellers li.active').removeClass('active'); $(this).addClass('active');});
                    }
                }
    		});
        }
    });
    $(document).on('click','.list_sellers li',function(){
        var id_product = $('#id_seller_product').val();
        var id_customer = $(this).data('id_customer');
        var seller_name =$(this).html();
        $.ajax({
			type: 'POST',
			headers: { "cache-control": "no-cache" },
			url: link_search_seller,
			async: true,
			cache: false,
			dataType : "json",
			data:'submitAddSellerProduct=1&id_product='+id_product+'&id_customer='+id_customer,
			success: function(json)
			{
                if(json.success)
                {
                    $.growl.notice({ message: json.success });
                    if($('#form_step6_seller_product').prev('.seller_selected').length)
                    {
                        $('.seller_selected .seller_name').html(seller_name);
                    } else{
                        $('#form_step6_seller_product').before('<div class="seller_selected"><div class="seller_name">'+seller_name+'</div><span class="delete_seller_search" data-id_product="'+id_product+'">Delete</span></div>');
                        $('.seller_selected').parent().addClass('has_seller');
                    }
                    $('#form_step6_seller_product').val('');
                    $('.list_sellers li').remove();
                }
                if(json.errors)
                    $.growl.error({message:json.errors});
            }
		});
    });
    $(document).on('click','.delete_seller_search',function(){
        if(confirm(confirm_del_seller))
        {
            var id_product = $('#id_seller_product').val();
            $.ajax({
    			type: 'POST',
    			headers: { "cache-control": "no-cache" },
    			url: link_search_seller,
    			async: true,
    			cache: false,
    			dataType : "json",
    			data:'submitDeleteSellerProduct=1&id_product='+id_product,
    			success: function(json)
    			{
                    if(json.success)
                    {
                        $.growl.notice({ message: json.success });
                        $('.seller_selected').parent().removeClass('has_seller');
                        $('.seller_selected').remove();
                    }    
                    if(json.errors)
                        $.growl.error({message:json.errors});
                }
    		});
        }
    });
});
{/literal}
</script>