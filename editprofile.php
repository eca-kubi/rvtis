<?php
include ('connect-sqlite.php');
$idwho ="";
$id="";
$who="";
$result = ['success'=>false, 'reason'=>'', 'data'=>''];
extract($_GET);
if(isset($_GET['name']) && isset($_GET['phone'])){
if ($db->exec("UPDATE '$who' set name='$name', phone='$phone' where $idwho='$id'")) {
$result['data']= ["name"=>$name, "phone"=>$phone];
$result['success']=true;
$anonymous = boolval($anonymous);
$session_name = $anonymous?'anonymous':'user';
session_name($session_name);
session_start();
if (isset($_SESSION['user'])) {
  $_SESSION['phone'] = $phone;
  $_SESSION['name'] = $name;
}
} else {
  $result['success']=false;
  $result['reason']='An error occured!';
}
}
echo json_encode($result);
?>