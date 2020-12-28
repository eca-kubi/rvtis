<?php
//require_once 'connect-sqlite.php';
$rows = $db->query('SELECT idregion, longname from region')->fetchAll(PDO::FETCH_ASSOC);
$regiondistricts = [];
foreach ($rows as $key => $region) {
  $idregion=$region['idregion'];
  $longname=$region['longname'];
  $districts = $db->query("SELECT longname, iddistrict, latlng from district where idregion='$idregion' order by longname")->fetchAll(PDO::FETCH_ASSOC);
  if (count($districts) > 0) {
    $regiondistricts["$longname"] = $districts;
  }
}
$regiondistricts=json_encode($regiondistricts);

$districts=[];
$rows = $db->query('SELECT d.iddistrict iddistrict, d.longname district,d.idregion idregion, r.longname region from district d left join region r on r.idregion=d.idregion order by district asc')->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $key => $value) {
  $district=$value['district'];
  $districts[$district]=$value;
}
$districts = json_encode($districts);

function getregions(){
  global $db;
  $regions=[];
  $rows = $db->query('SELECT * from region order by longname asc')->fetchAll(PDO::FETCH_ASSOC);
  foreach ($rows as $key => $value) {
    $longname=$value['longname'];
    $regions[$longname]=$value;
  }
  return json_encode($regions);
}

function getbranches(){
  global $db;
  $branches=[];
  $rows = $db->query('SELECT b.idbranch, b.name, r.longname region, d.longname district, b.iddistrict, r.idregion from branch b  left join district d on d.iddistrict=b.iddistrict left join region r on r.idregion=d.idregion order by b.name asc')->fetchAll(PDO::FETCH_ASSOC);
  foreach ($rows as $key => $value) {
    $idbranch=$value['idbranch'];
    $branches[$idbranch]=$value;
  }
  return json_encode($regions);
}


function getRegionID($region){
  global $db;
  $id = $region=='Western Region'?1:$region=='Greater Accra Region'?2:$region=='Volta Region'?3:$region=='Western Region'?4:$region=='Brong-Ahafo Region'?5:$region=='Northern Region'?6:$region=='Upper East Region'?7:$region=='Upper West Region'?8:$region=='Eastern Region'?9:10;
}