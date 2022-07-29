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
	if(!$('.ets_mp_shop_maps_popup').length)
    {
        setTimeout(function(){ ets_mp_load_map();},500);
    }
    $('#addressInput').keypress(function(e) {
		code = e.keyCode ? e.keyCode : e.which;
		if(code.toString() == 13)
			ets_mp_searchLocations();
	});
	$(document).on('click', 'input[name=location]', function(e){
		e.preventDefault();
		$(this).val('');
	});

	$(document).on('click', 'button[name=search_locations]', function(e){
		e.preventDefault();
		ets_mp_searchLocations();
	});
    $(document).on('click','button[name="reset_locations"]',function(){
        $('#addressInput').val('');
        ets_mp_load_map();
        $('.store-content-select').hide();
        $('.store-content-select').prev('.alert').hide();
        $('#stores-table').hide();
    });
    $(document).on('click','.ets_mp_map .view_map',function(e){
        e.preventDefault();
        ets_mp_load_map();
        $('.ets_mp_popup.ets_mp_shop_maps_popup').show();
    });
});
function ets_mp_load_map()
{
    if($('#map').length )
    {
        map = new google.maps.Map(document.getElementById('map'), {
    		center: new google.maps.LatLng(defaultLat, defaultLong),
    		zoom: 10,
    		mapTypeId: 'roadmap',
    		mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU}
    	});
    	infoWindow = new google.maps.InfoWindow();
        if($('#locationSelect').length)
        {
            locationSelect = document.getElementById('locationSelect');
        		locationSelect.onchange = function() {
        		var markerNum = locationSelect.options[locationSelect.selectedIndex].value;
        		if (markerNum !== 'none')
        		google.maps.event.trigger(markers[markerNum], 'click');
        	};
        }
    	
    	ets_mp_initMarkers();
    }
}
function ets_mp_searchLocations()
{
	$('button[name="search_locations"]').addClass('loading');
	var address = document.getElementById('addressInput').value;
	var geocoder = new google.maps.Geocoder();
	geocoder.geocode({address: address}, function(results, status) {
        if (status === google.maps.GeocoderStatus.OK)
			ets_mp_searchLocationsNear(results[0].geometry.location);
		else
		{
			$('.store-content-select').hide();
            $('.store-content-select').prev('.alert').show();
		}
		$('button[name="search_locations"]').removeClass('loading');
	});
}
function ets_mp_searchLocationsNear(center)
{
    var radius = document.getElementById('radiusSelect').value;
	var searchUrl2 = searchUrl+'&latitude=' + center.lat() + '&longitude=' + center.lng() + '&radius=' + radius;
	ets_mp_downloadUrl(searchUrl2, function(data) {
    	var xml = parseXml(data.trim());
    	var markerNodes = xml.documentElement.getElementsByTagName('marker');
    	var bounds = new google.maps.LatLngBounds();
    	ets_mp_clearLocations(markerNodes.length);
    	$('table#stores-table').hide().find('tbody tr').remove();
    	for (var i = 0; i < markerNodes.length; i++)
    	{
    		var name = markerNodes[i].getAttribute('name');
    		var address = markerNodes[i].getAttribute('address');
    		var addressNoHtml = markerNodes[i].getAttribute('addressNoHtml');
    		var other = markerNodes[i].getAttribute('other');
    		var distance = parseFloat(markerNodes[i].getAttribute('distance'));
    		var id_store = parseFloat(markerNodes[i].getAttribute('id_store'));
    		var phone = markerNodes[i].getAttribute('phone');
    		var has_store_picture = markerNodes[i].getAttribute('has_store_picture');
            var link_shop = markerNodes[i].getAttribute('link_shop');
    		var latlng = new google.maps.LatLng(
    		parseFloat(markerNodes[i].getAttribute('lat')),
    		parseFloat(markerNodes[i].getAttribute('lng')));
                
    		ets_mp_createOption(name, distance, i);
    		ets_mp_createMarker(latlng, name, address, other, id_store, has_store_picture);
    		bounds.extend(latlng);
    		address = address.replace(phone, '');
    
    		$('table#stores-table').find('tbody').append('<tr ><td class="num">'+parseInt(i + 1)+'</td><td class="name">'+(has_store_picture ? '<img src="'+has_store_picture+'" alt="" />' : '')+'<span><a href="'+link_shop+'">'+name+'</a></span></td><td class="address">'+address+(phone !== '' ? '<br/>'+translation_4+' '+phone : '')+'</td></tr>'); // <td class="distance">'+distance+' '+distance_unit+'</td>
    		$('#stores-table').show();
    	}
    
    	if (markerNodes.length)
    	{
    		map.fitBounds(bounds);
    		var listener = google.maps.event.addListener(map, "idle", function() {
    			if (map.getZoom() > 13) map.setZoom(13);
    			google.maps.event.removeListener(listener);
    		});
    	}
    	locationSelect.style.display = 'block';
    	//$(locationSelect).parent().addClass('active').show();
    	locationSelect.onchange = function() {
    		var markerNum = locationSelect.options[locationSelect.selectedIndex].value;
    		google.maps.event.trigger(markers[markerNum], 'click');
    	};
    });
}
function ets_mp_createOption(name, distance, num)
{
	var option = document.createElement('option');
	option.value = num;
	option.innerHTML = name; //+' ('+distance.toFixed(1)+' '+distance_unit+')'
	locationSelect.appendChild(option);
}
function ets_mp_clearLocations(n)
{
	infoWindow.close();
	for (var i = 0; i < markers.length; i++)
		markers[i].setMap(null);

	markers.length = 0;

	locationSelect.innerHTML = '';
	var option = document.createElement('option');
	option.value = 'none';
	if (!n)
	{
	   $('.store-content-select').hide();
       $('.store-content-select').prev('.alert').show();
	   //option.innerHTML = translation_1;
	}	
	else
	{
	   $('.store-content-select').show();
       $('.store-content-select').prev('.alert').hide();
		if (n === 1)
			option.innerHTML = '1'+' '+translation_2;
		else
			option.innerHTML = n+' '+translation_3;
	}
	locationSelect.appendChild(option);

	if (!!$.prototype.uniform)
		$("select#locationSelect").uniform();

	$('#stores-table tr.node').remove();
}
function ets_mp_initMarkers()
{
	ets_mp_downloadUrl(searchUrl+'&all=1', function(data) {
		var xml = parseXml(data.trim());
		var markerNodes = xml.documentElement.getElementsByTagName('marker');
		var bounds = new google.maps.LatLngBounds();
		for (var i = 0; i < markerNodes.length; i++)
		{
			var name = markerNodes[i].getAttribute('name');
			var address = markerNodes[i].getAttribute('address');
			var addressNoHtml = markerNodes[i].getAttribute('addressNoHtml');
			var other = markerNodes[i].getAttribute('other');
			var id_store = markerNodes[i].getAttribute('id_store');
			var has_store_picture = markerNodes[i].getAttribute('has_store_picture');
			var latlng = new google.maps.LatLng(
			parseFloat(markerNodes[i].getAttribute('lat')),
			parseFloat(markerNodes[i].getAttribute('lng')));
			ets_mp_createMarker(latlng, name, address, other, id_store, has_store_picture);
			bounds.extend(latlng);
		}
		map.fitBounds(bounds);
		var zoomOverride = map.getZoom();
        if(zoomOverride > 10)
        	zoomOverride = 10;
		map.setZoom(zoomOverride);
	});
}
function ets_mp_createMarker(latlng, name, address, other, id_store, has_store_picture)
{
	var html = '<img src="'+has_store_picture+'" alt="" style="width: 100px;float:left;margin-bottom: 6px;border: 1px solid #cccccc;margin-right: 10px;"/><span class="shop_map_info"><b>'+name+'</b><br/>'+address+(has_store_picture ? '</span>' : '')+other+'<a href="http://maps.google.com/maps?saddr=&daddr='+latlng+'" target="_blank">'+translation_5+'<\/a>';
    var image = new google.maps.MarkerImage(img_ps_dir+logo_map);
	var marker = '';

	if (hasStoreIcon)
		marker = new google.maps.Marker({ map: map, icon: image, position: latlng });
	else
		marker = new google.maps.Marker({ map: map, position: latlng });
	google.maps.event.addListener(marker, 'click', function() {
		infoWindow.setContent(html);
		infoWindow.open(map, marker);
	});
	markers.push(marker);
}
function ets_mp_downloadUrl(url, callback)
{
	var request = window.ActiveXObject ?
	new ActiveXObject('Microsoft.XMLHTTP') :
	new XMLHttpRequest();

	request.onreadystatechange = function() {
		if (request.readyState === 4) {
			request.onreadystatechange = doNothing;
			callback(request.responseText, request.status);
		}
	};
        
	request.open('GET', url, true);
	request.send(null);
}
function parseXml(str)
{
	if (window.ActiveXObject)
	{
		var doc = new ActiveXObject('Microsoft.XMLDOM');
		doc.loadXML(str);
		return doc;
	}
	else if (window.DOMParser)
		return (new DOMParser()).parseFromString(str, 'text/xml');
}

function doNothing()
{
}
