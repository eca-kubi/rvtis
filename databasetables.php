<?php
$branches=[];
$rows = $db->query("SELECT b.idbranch, b.name branch, r.longname region, d.longname district, b.iddistrict, r.idregion,b.idcompany from branch b left join company c on c.idcompany=b.idcompany left join district d on d.iddistrict=b.iddistrict left join region r on r.idregion=d.idregion where c.idcompany='$idcompany'  order by b.name asc ")->fetchAll(PDO::FETCH_ASSOC);
foreach ($rows as $key => $value) {
  $idbranch=$value['idbranch'];
  $branches[$idbranch]=$value;
}

$regions=[];
  $rows = $db->query('SELECT * from region order by longname asc')->fetchAll(PDO::FETCH_ASSOC);
  foreach ($rows as $key => $value) {
    $idregion=$value['idregion'];
    $regions[$idregion]=$value;
  }

  $districts=[];
  $rows = $db->query('SELECT d.iddistrict iddistrict, d.longname district,d.idregion idregion, r.longname region from district d left join region r on r.idregion=d.idregion order by district asc')->fetchAll(PDO::FETCH_ASSOC);
  foreach ($rows as $key => $value) {
    $iddistrict=$value['iddistrict'];
    $districts[$iddistrict]=$value;
  }

  $drivers=[];
  $rows = $db->query("SELECT d.idtruckdriver, d.name fullname, d.username, d.password, d.phone, d.idbranch from truckdriver d left join branch b on b.idbranch=d.idbranch left join company c on c.idcompany=b.idcompany where c.idcompany='$idcompany'")->fetchAll(PDO::FETCH_ASSOC);
  foreach ($rows as $key => $value) {
    $idtruckdriver=$value['idtruckdriver'];
    $drivers[$idtruckdriver]=$value;
  }

  $companylist = [];
$stmt =$db->prepare("SELECT name, idcompany, phone, username from company");
$stmt->execute();
$ret = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($ret as $key => $value) {
  $idcompany = $value['idcompany'];
  $companylist[$idcompany]=$value;
}
?>