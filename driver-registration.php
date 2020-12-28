<?php
include 'connect-sqlite.php';
extract($_POST,EXTR_PREFIX_ALL,'d');
$ret =$pdo->exec("INSERT into truckdriver (username,password, name, phone, idbranch) VALUES ('$d_username','$d_password','$d_name','$d_phone', $d_branch)"  );
if($ret){
  echo json_encode(array('success'=>true));
} else {
  echo json_encode(array('success'=>false));
}
?>