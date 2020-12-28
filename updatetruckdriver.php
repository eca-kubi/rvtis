<?php
include 'connect-sqlite.php';
extract($_POST);
$db = getPdo();
$ret = $db->exec("UPDATE truckdriver set phone='$phone', fullname='$fullname' where username='$username'");
if($ret > 0) {
  echo 'success';
} else { echo 'failure'; }
?>