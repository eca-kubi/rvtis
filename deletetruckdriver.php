<?php
include 'connect-sqlite.php';
extract($_POST);
$ret = $db->exec("DELETE from truckdriver where idtruckdriver='$idtruckdriver'");
if($ret >0) {
  echo json_encode(['success'=>true, 'idtruckdriver'=>$idtruckdriver]);
} else { echo (['success'=>false, 'reason'=>'An error occured!']); }
?>