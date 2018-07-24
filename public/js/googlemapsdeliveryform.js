
function initAutocomplete(){
  var map = new google.maps.Map(document.querySelector('#map'),
  {
      center: {lat: 46.787, lng: -92.102},
      zoom: 14,
      mapTypeId: 'roadmap'
  });

  var businessLocation = {
          lat: 46.7840, lng: -92.1022
        };

  var businessMarker = new google.maps.Marker({
    position : businessLocation,
    map: map,
    title: 'Lunch Mink: 302 W Superior St'
  });

   /* starts at bottom corner of superior street */
         var deliveryCoords = [
          {lat: 46.7777, lng: -92.1076},

          {lat: 46.7775, lng: -92.106},
           {lat: 46.7757, lng: -92.1033},
          {lat: 46.7811, lng: -92.0963},
           {lat: 46.7835, lng: -92.0978},
           {lat: 46.7838, lng: -92.0975},
           {lat: 46.7791, lng: -92.0942},
            {lat: 46.7800, lng: -92.0910},
           {lat: 46.788, lng: -92.094},
           {lat: 46.7921, lng: -92.0897},
            {lat: 46.7986, lng: -92.1007},
           {lat: 46.7983, lng: -92.1037},
           {lat: 46.796, lng: -92.1047},
           {lat: 46.7956, lng: -92.1054},
           {lat: 46.7953, lng: -92.1068},
           {lat: 46.7923, lng: -92.1068},
           {lat: 46.7879, lng: -92.1058},
           {lat: 46.786, lng: -92.1079}
        ];

         var deliveryArea = new google.maps.Polygon({
          paths: deliveryCoords,
          fillColor: '#003300',
          fillOpacity: .15,
          strokeColor: '#000000',
          strokeOpacity: 1,
          strokeWeight: 3,
          map: map
        });

         // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });

        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {

          var places = searchBox.getPlaces();

          if (places.length == 0) {
            return;
          }

            // Clear out the old markers.
          markers.forEach(function(marker) {
            marker.setMap(null);
          });
          markers = [];
               // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          places.forEach(function(place) {
            if (!place.geometry) {
              return;
            }
            var icon = {
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            markers.push(new google.maps.Marker({
              map: map,
              icon: icon,
              title: place.name,
              position: place.geometry.location
            }));

            if (place.geometry.viewport) {
              // Only geocodes have viewport.
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }

            $addressPurpose = document.getElementById('addressPurpose').innerHTML;

            if( google.maps.geometry.poly.containsLocation(place.geometry.location, deliveryArea))
            {
                document.getElementById('no-delivery-location').style.display = "none";
                document.getElementById('acceptable-delivery-location').innerHTML = "We can deliver to that location";
                document.getElementById('acceptable-delivery-location').style.display = "block";
                document.getElementById('map-instructions').style.display = "none";

                if($addressPurpose)
                {     
                  document.getElementById('account_location').style.display = "none"; 
                  document.getElementById('account_instructions').style.display = "none";
                  document.getElementById('display-delivery-location').innerHTML = place.name;
                  document.getElementById('error-delivery-location').style.display= "block";
                  document.getElementById('delivery-location-form').style.display= "block";
                  document.getElementById('delivery-location-input').value = place.name;
                  document.getElementById('current-location').style.display = "block";
                  document.getElementById('error-delivery-location').innerHTML= "";
                  document.getElementById('error-delivery-location').style.display = "block";
                }
               
            } else {
                document.getElementById('no-delivery-location').style.display = "block";
                document.getElementById('acceptable-delivery-location').style.display="none";
                document.getElementById('map-instructions').style.display = "block";
               
                if($addressPurpose)
                {   
                  document.getElementById('display-delivery-location').innerHTML = "";
                  document.getElementById('delivery-location-form').style.display = "none";
                  document.getElementById('delivery-location-input').value = "";
                  document.getElementById('current-location').style.display = "none";
                  document.getElementById('error-delivery-location').innerHTML= "";
                  document.getElementById('error-delivery-location').style.display= "none";
                }
            }
          });
          map.fitBounds(bounds);

        }); //searchbox end

}


