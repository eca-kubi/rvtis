<?php
include 'connect-sqlite.php';
extract($_POST);
$ret =$db->query("SELECT name, idbranch from branch")->fetch();
if($ret['name'] == $name && $ret['idbranch']!=$idbranch) {
echo json_encode(['success'=>false, 'reason'=>'There is an existing branch with the same name!']);
  exit;
}
$ret =$db->exec("UPDATE branch set name='$name', iddistrict=$district where idbranch=$idbranch and idcompany=$idcompany");
if($ret){
  $row =$db->query("SELECT d.idregion, d.iddistrict,b.idcompany, b.name branch, r.longname region, d.longname district from branch b  left join district d on d.iddistrict=b.iddistrict left join region r on r.idregion=d.idregion left join company c on b.idcompany=c.idcompany where idbranch='$idbranch'")->fetch(PDO::FETCH_ASSOC);
 extract($row);
  echo json_encode(['success'=>true, 'branch'=>"$branch", 'district'=>"$district", 'idbranch'=>"$idbranch", 'region'=>"$region", 'idregion'=>"$idregion", 'iddistrict'=>"$iddistrict", 'idcompany'=>"$idcompany"]);
} else {
  echo json_encode(['success'=>false]);
}
?>