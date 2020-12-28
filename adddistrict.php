<?php
include 'gmapfunctions.php';
include 'connect-sqlite.php';
$latlng='';
$result='';
$longname='';
$placeid='';
$region='';
if(isset($_REQUEST['latlng'])){
  $latlng = $_REQUEST['latlng'];
  $result =reverse_geocode($latlng);
  if($result != 'unknown Place'){
    $results = $result->results;
    $longname = $results[1]->address_components[0]->long_name;
    $region = $results[1]->address_components[1]->long_name;
    $idregion =getRegionID($region);
    $placeid=$results[1]->place_id;
    $stmt=$pdo->prepare('INSERT into district (longname, idregion, placeid, latlng) values (?,?,?,?)');
    if($stmt->execute([$longname,$idregion,$placeid,$latlng])){
      echo 'ok';
    }
  }
}
function getRegionID($region){
  $id = $region=='Western Region'?1:$region=='Greater Accra Region'?2:$region=='Volta Region'?3:$region=='Western Region'?4:$region=='Brong-Ahafo Region'?5:$region=='Northern Region'?6:$region=='Upper East Region'?7:$region=='Upper West Region'?8:$region=='Eastern Region'?9:10;
}
?>