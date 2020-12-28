<?php
include 'connect-sqlite.php';
extract($_POST);
$username =strtolower($username);
$ret =$db->query("SELECT username, idtruckdriver from truckdriver")->fetch();
if($ret['username'] == $username && $ret['idtruckdriver']!=$idtruckdriver) {
echo json_encode(['success'=>false, 'reason'=>'The username exists!']);
  exit;
}

$ret =$db->query("SELECT phone, idtruckdriver from truckdriver")->fetch();
if($ret['phone'] == $phone && $ret['idtruckdriver']!=$idtruckdriver){
  echo json_encode(['success'=>false, 'reason'=>'The phone number is already taken!']);
  exit;
}

$ret =$db->exec("UPDATE truckdriver set name='$fullname', username='$username' ,phone='$phone', password='$password', idbranch='$idbranch' where idtruckdriver=$idtruckdriver");

if($ret){
  $row = $db->query("SELECT t.idtruckdriver,t.password, t.name fullname, t.phone, t.username, b.name branch, b.idbranch  from truckdriver t left join branch b on t.idbranch=b.idbranch where t.idtruckdriver='$idtruckdriver'")->fetch(PDO::FETCH_ASSOC);
  if($row){
    echo json_encode(['success'=>true, 'data'=>$row]);
  }
} else {
  echo json_encode(['success'=>false,'reason'=>'An error occured']);
}
?>