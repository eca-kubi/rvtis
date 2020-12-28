<?php
include 'connect-sqlite.php';
extract($_POST);
$truckdrivers = $db->query("SELECT t.idtruckdriver, t.username username, t.name name, t.phone phone from truckdriver t left join branch b on b.idbranch=t.idbranch where b.idbranch='$idbranch'")->fetchAll(PDO::FETCH_ASSOC);
if(count($truckdrivers)>0){
  echo json_encode(['success'=>false,  'reason'=>'The following drivers are belong to this branch. Please assign them to a new branch before you will be able  to delete the branch:','drivers'=>$truckdrivers, 'idbranch'=>$idbranch]);
  exit;
}

$ret = $db->exec("DELETE from branch where idbranch= '$idbranch'");
if($ret >0) {
  echo json_encode(['success'=>true, 'idbranch'=>"$idbranch"]);
} else { echo json_encode(['success'=>false, 'reason'=>'An error occured!']); }
?>