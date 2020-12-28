<?php
include 'connect-sqlite.php';
extract($_POST);
$username =strtolower($username);
$ret =$db->query("SELECT COUNT(*) username from truckdriver where username='$username'")->fetch();
if($ret[0] >0){
  echo json_encode(['success'=>false, 'reason'=>'The username exists!']);
  exit;
}
$ret =$db->query("SELECT COUNT(*) phone from truckdriver where phone='$phone'")->fetch();
if($ret[0] >0){
  echo json_encode(['success'=>false, 'reason'=>'The phone number is already taken!']);
  exit;
}
$ret =$db->exec("INSERT into truckdriver (phone, name, username, password, idbranch) VALUES ('$phone', '$name', '$username', '$password', '$idbranch')");

if($ret){
  $idtruckdriver =$db->lastInsertID();
  $row = $db->query("SELECT t.idtruckdriver,t.password, t.name fullname, t.phone, t.username, b.name branch, b.idbranch  from truckdriver t left join branch b on t.idbranch=b.idbranch where t.idtruckdriver='$idtruckdriver'")->fetch(PDO::FETCH_ASSOC);
  echo json_encode(['success'=>true, 'idtruckdriver'=>$idtruckdriver, 'data'=>$row]);
} else {
  echo json_encode(['success'=>false, 'reason'=>'An error occured!']);
}
?>