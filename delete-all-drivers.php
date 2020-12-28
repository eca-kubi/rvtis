<?php
include 'connect-sqlite.php';
$ret = $db->exec("DELETE from truckdriver where idcompany='$idcompany'");
if($ret >0) {
  echo json_encode(['success'=>true]);
} else { echo json_encode(['success'=>false, 'reason'=>'An error occured!']); }
?>