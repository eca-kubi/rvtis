<?php
include 'connect-sqlite.php';
extract($_GET);
$truckdrivers = $db->query("SELECT t.name name from truckdriver t left join branch b on t.idbranch=b.idbranch where idcompany='$idcompany'")->fetchAll(PDO::FETCH_ASSOC);
if(count($truckdrivers)>0){
  echo json_encode(['success'=>false, 'reason'=>'The following drivers belong to a branch. Please assign them to a new branch before you will be able  to delete the branches affected:','drivers'=>$truckdrivers]);
  exit;
}

$ret = $db->exec("DELETE from branch where idcompany='$idcompany'");
if($ret >0) {
  echo json_encode(['success'=>true]);
} else { echo json_encode(['success'=>false, 'reason'=>'An error occured!']); }
?>