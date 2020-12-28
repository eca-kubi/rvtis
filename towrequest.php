<?php
include 'connect-sqlite.php';
$idtruckdriver = $_REQUEST['idtruckdriver'];
$iduser = $_REQUEST['iduser'];
$received = $_REQUEST['timereceived'];
$dispatched = $_REQUEST['timedispatched'];
$vehicleno = $_REQUEST['vehicleno'];

$ret = $db->exec("INSERT into towrequest (idtruckdriver, iduser, timereceived, timedispatched, vehicleno) values ('$idtruckdriver', '$iduser', '$received', '$dispatched', '$vehicleno')");
if($ret){
  $id = $db->lastInsertID();
  $ret = $db->query("SELECT * from towrequest where idtowrequest = '$id'")->fetch(PDO::FETCH_ASSOC);
  $ret['complete']=false;
  echo json_encode(['success'=>true, 'data'=>$ret]);
} else {
  echo json_encode(['success'=>false, 'reason'=>'An error occured']);
}
?>