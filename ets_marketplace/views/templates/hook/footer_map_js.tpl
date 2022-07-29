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
{if isset($link_map_google) && $link_map_google}
    {if $ETS_MP_GOOGLE_MAP_API}
        <script>
            {literal}
            var address_autocomplete;
            
            var componentForm = {
              locality: 'long_name',
              country: 'short_name',
              postal_code: 'short_name'
            };
            
            function ets_mp_initAutocomplete() {
              address_autocomplete = new google.maps.places.Autocomplete(
                  document.getElementById('addressInput'), {types: ['geocode']});
              address_autocomplete.setFields(['address_component']);
              address_autocomplete.addListener('place_changed', ets_mp_fillInAddress);
            }
            function ets_mp_fillInAddress() {
                $('button[name="search_locations"]').click();
            }
            function geolocate() {
              if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                  var geolocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                  };
                  var circle = new google.maps.Circle(
                      {center: geolocation, radius: position.coords.accuracy});
                  autocomplete.setBounds(circle.getBounds());
                });
              }
            }
            {/literal}
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key={$ETS_MP_GOOGLE_MAP_API|escape:'html':'UTF-8'}&libraries=places&callback=ets_mp_initAutocomplete"></script>
    {else}
        <script src="{$link_map_google nofilter}"></script>
    {/if}
    <script src="{$link_map_js|escape:'html':'UTF-8'}"></script>
{/if}