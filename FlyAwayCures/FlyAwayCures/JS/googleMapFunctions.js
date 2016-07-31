/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var DEFAULT_LAT = '35.8833';
var DEFAULT_LNG = '14.410';

// Initialise normal map with / without coordinates
function initializeMap($lat, $lng, $zoom, $div_id)
{ 
   if($lat == "" || $lat == 0) 
   {
       $lat = DEFAULT_LAT;
       $lng = DEFAULT_LNG;
   }

  var mapOptions = {
    zoom: $zoom,
    center: new google.maps.LatLng($lat, $lng)
  };
   var map = new google.maps.Map(document.getElementById($div_id),mapOptions);
   
    google.maps.event.addDomListener(window, "resize", function() 
    {
        var center = map.getCenter();
        google.maps.event.trigger(map, "resize");
        map.setCenter(center); 
    });
}

// Initialise marked map with / without coordinates
function initializeMarkedMap($lat, $lng, $zoom, $div_id, $title)
{ 
   if($lat == "" || $lat == 0) 
   {
       $lat = DEFAULT_LAT;
       $lng = DEFAULT_LNG;
   }

    var myLatlng = new google.maps.LatLng($lat,$lng);
    var mapOptions = {
      zoom: $zoom,
      center: myLatlng
    }
    var map = new google.maps.Map(document.getElementById($div_id), mapOptions);
    
    var iconPath = '../Images/plane.png';
    
    // Custom Icon
    var marker = new google.maps.Marker({
        position: myLatlng,
        map: map,
        title: $title,
        icon: iconPath,
        scale: 15
    });   
}