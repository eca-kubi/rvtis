<?php
function reverse_geocode($latlon) {
  $url = "https://maps.google.com/maps/api/geocode/json?latlng=$latlon&sensor=false&key=AIzaSyCqjMdWdNss5BgNzucIYPBBMGkKs7Y5dlI";
  $contents = file_get_contents($url);
  $data = json_decode($contents);
  if (!isset($data->results[0]->address_components)){
      return "unknown Place";
  }
  return $data;
}
?>