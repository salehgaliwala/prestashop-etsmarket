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
<script type="text/javascript" src="{$ets_mp_module_dir|escape:'html':'UTF-8'}views/js/order_product.js"></script>
<script type="text/javascript">
    var total_registrations = {$total_registrations|intval};
    var total_seller_wait_approve = {$total_seller_wait_approve|intval};
    $(document).ready(function(){
        if(total_registrations >0)
        {
            if($('#subtab-AdminMarketPlaceRegistrations span').length)
                $('#subtab-AdminMarketPlaceRegistrations span').append('<b class="total"> '+total_registrations+'</b>');
            else
                $('#subtab-AdminMarketPlaceRegistrations a').append('<b class="total"> '+total_registrations+'</b>');
            $('#tab-AdminMarketPlaceRegistrations a').append('<b class="total"> '+total_registrations+'</b>');
        } 
        if(total_seller_wait_approve >0)
        {
            if($('#subtab-AdminMarketPlaceSellers span').length)
                $('#subtab-AdminMarketPlaceSellers span').append('<b class="total"> '+total_seller_wait_approve+'</b>');
            else
                $('#subtab-AdminMarketPlaceSellers a').append('<b class="total"> '+total_seller_wait_approve+'</b>');
            $('#tab-AdminMarketPlaceSellers > a').append('<b class="total"> '+total_seller_wait_approve+'</b>');
        }
    });
</script>