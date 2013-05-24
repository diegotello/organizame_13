<?php
/*
Es necesario implementar los metodos:
  getLat()
  getLong()
  getMapAddress()
*/
?>
<div class="row">
    <div class="span3">
        <b>Buscar</b>
    </div>
    <div class="span3">
        <b>Latitud</b>
    </div>
    <div class="span3">
        <b>Longitud</b>
    </div>
    <div class="span3">
        <b>Direccion conocida mas cercana:</b>
    </div>
</div>
<form id="mapform">
<div id="infoPanel" class="row">
    <div class="span3"><b><input id="searchTextField" type="text" size="50"/></b></div>
    <div class="span3"><b><input id="lat" type="text" size="45" readonly="true"/> </b></div>    
    <div class="span3"><b><input id="long" type="text" size="45" readonly="true"/></b></div>
    <div class="span3"><b><input id="mapaddress" type="text" size="45" readonly="true"/></b></div>
</div>
<input id="info" type="hidden" size="45">
</form>
<div id="map_canvas" style="height:70%" class="span6"></div>
<?php $this->headScript()->captureStart();?>
    var geocoder = new google.maps.Geocoder();
    var map;
    var marker;
    function initialize() {
        var latLng = new google.maps.LatLng(14.642969081600402, -90.51301373091354);
        var mapOptions = {
            center: latLng,
            zoom: 6,
            mapTypeId: google.maps.MapTypeId.HYBRID
        };
        map = new google.maps.Map(document.getElementById("map_canvas"),
            mapOptions);
        prepareGeolocation();
        doGeolocation();
		var input = document.getElementById('searchTextField');
        var autocomplete = new google.maps.places.Autocomplete(input);

        autocomplete.bindTo('bounds', map);
		autocomplete.setTypes([]);
		
        marker = new google.maps.Marker({
            position: latLng,
            title: 'Point A',
            map: map,
            draggable: true
        });

        // Update current position info.
        updateMarkerPosition(latLng);
        geocodePosition(latLng);

        // Add dragging event listeners.
        google.maps.event.addListener(marker, 'dragstart', function() {
            updateMarkerAddress('Dragging...');
        });

        google.maps.event.addListener(marker, 'drag', function() {
            updateMarkerPosition(marker.getPosition());
        });

        google.maps.event.addListener(marker, 'dragend', function() {
            geocodePosition(marker.getPosition());
			map.setCenter(marker.getPosition());
        });
		
		google.maps.event.addListener(autocomplete, 'place_changed', function() {
          var place = autocomplete.getPlace();
          if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
          } else {
            map.setCenter(place.geometry.location);
            map.setZoom(18);
          }
		  
          marker.setPosition(place.geometry.location);
		  updateMarkerPosition(marker.getPosition());
		  geocodePosition(marker.getPosition());
		  //setPosition(marker.getPosition());

          var address = '';
          if (place.address_components) {
            address = [
              (place.address_components[0] && place.address_components[0].short_name || ''),
              (place.address_components[1] && place.address_components[1].short_name || ''),
              (place.address_components[2] && place.address_components[2].short_name || '')
            ].join(' ');
          }
        });
    }

    function geocodePosition(pos) {
      geocoder.geocode({
        latLng: pos
      }, function(responses) {
        if (responses && responses.length > 0) {
          updateMarkerAddress(responses[0].formatted_address);
        } else {
          updateMarkerAddress('Cannot determine address at this location.');
        }
      });
    }

    function updateMarkerPosition(latLng) {
      document.forms['mapform'].elements["lat"].value = latLng.lat();
      document.forms['mapform'].elements["long"].value = latLng.lng();
      getLat();
      getLong();
    }

    function updateMarkerAddress(str) {
      document.forms['mapform'].elements["mapaddress"].value = str;
      getMapAddress();
    }
    
    function doGeolocation() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(positionSuccess, positionError);
    } else {
      positionError(-1);
    }
  }

  function positionError(err) {
    var msg;
    switch(err.code) {
      case err.UNKNOWN_ERROR:
        msg = "Unable to find your location";
        break;
      case err.PERMISSION_DENINED:
        msg = "Permission denied in finding your location";
        break;
      case err.POSITION_UNAVAILABLE:
        msg = "Your location is currently unknown";
        break;
      case err.BREAK:
        msg = "Attempt to find location took too long";
        break;
      default:
        msg = "Location detection not supported in browser";
    }
    document.forms['mapform'].elements["info"].value = msg;
  }

  function positionSuccess(position) {
    // Centre the map on the new location
    var coords = position.coords || position.coordinate || position;
    var latLng = new google.maps.LatLng(coords.latitude, coords.longitude);
    map.setCenter(latLng);
    map.setZoom(18);
    marker.setPosition(latLng);
    document.forms['mapform'].elements["info"].value = 'Looking for <b>' +
        coords.latitude + ', ' + coords.longitude + '</b>...';

    // And reverse geocode.
    (new google.maps.Geocoder()).geocode({latLng: latLng}, function(resp) {
		document.forms['mapform'].elements["lat"].value = latLng.lat();
        document.forms['mapform'].elements["long"].value = latLng.lng();
        getLat();
        getLong();
	  });
  }
  
  function setPosition(latLng){
		document.forms['mapform'].elements["info"].value = latLng;
  }

  function contains(array, item) {
	  for (var i = 0, I = array.length; i < I; ++i) {
		  if (array[i] == item) return true;
		}
		return false;
	}
      
      google.maps.event.addDomListener(window, 'load', initialize);
<?php $this->headScript()->captureEnd(); ?>