<?php
include 'connect-sqlite.php';
extract($_POST);
$name=ucwords($name);
$ret = $db->query("SELECT COUNT(*) count from branch where name='$name' and idcompany='$idcompany'")->fetch();
if($ret['count'] >0){
  echo json_encode(['success'=>false, 'reason'=>'This name already exists.']);
  exit;
}
$name = ucwords($name);
$ret =$db->exec("INSERT into branch (iddistrict, name, idcompany) VALUES ('$district', '$name', '$idcompany')");
if($ret){
  $id = $db->lastInsertID();
  $rows = $db->query("SELECT b.idbranch, b.name branch, r.longname region, d.longname district, b.iddistrict, r.idregion,b.idcompany from branch b left join company c on c.idcompany=b.idcompany left join district d on d.iddistrict=b.iddistrict left join region r on r.idregion=d.idregion where c.idcompany='$idcompany' and b.idbranch='$id' order by b.name asc ")->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode(['success'=>true, 'data'=>$rows]);
} else {
  echo json_encode(['success'=>false, 'reason'=>'An error occured!']);
}


?>