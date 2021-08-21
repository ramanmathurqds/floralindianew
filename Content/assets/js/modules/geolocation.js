$(document).on('click', '.btn-locate', function(){
  getCurrentLocation();
});

function getCurrentLocation(){
  if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(callBackLatLong);
  } else {
      console.log("Geolocation is not supported by this browser.");
  }
}

function callBackLatLong(position){
  var lat = position.coords.latitude,
  long = position.coords.longitude;

  //Converter for lat and long for full address..
  latlongConverter(lat, long);
}

function latlongConverter(lati, longi){
  var geocoder = new google.maps.Geocoder();
  var latlng = { lat: parseFloat(lati), lng: parseFloat(longi) };

  geocoder.geocode({ 'location': latlng }, function (results, status) {
      if (status === 'OK') {
          if (results) {
              var mainAddress = "";
              for (var i = 0; i <= results.length - 1; i++) {
                  for (var a = 0; a <= results[i].address_components.length - 1; a++) {
                      if (results[i].address_components[a].types[0] === "locality") {
                          mainAddress = (results[i].address_components[a].long_name);
                      }
                  }
              }
              const urlParams = new URLSearchParams(window.location.search);
              const locationParam = urlParams.get('loc');
              if (locationParam) {
                  $('.txt-city').val(locationParam);
                  getCityListFromApi($('.txt-city'));
              }else {
                  $('.txt-city').val(mainAddress);
                  getCityListFromApi($('.txt-city'));
              }
          } else {
              console.log('No results found');
          }
      } else {
          console.log('Geocoder failed due to: ' + status);
      }
  });
}