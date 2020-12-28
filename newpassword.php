<?php
include 'connect-sqlite.php';
$password = "";
$repassword = "";
$username = "";
$who="";
$error = ['cause' => '', 'message' => '', 'success' => false];
if (isset($_POST['password']) && isset($_POST['repassword']) && isset($_POST['username'])&& isset($_POST['who'])) {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $repassword = $_POST['repassword'];
  $who = $_POST['who'];
  if ($password == $repassword) {
    $stmt = $pdo->prepare("UPDATE $who set password=?, passwordreset=? where username=?");
    $row = $stmt->execute([$password, 0,$username]);
    if ($row) {
      $error['success'] = true;
    }
  } else {
    $error['success']=false;
    $error['cause']='password mismatch';
    $error['message'] = 'The passwords do not match';
  }
  echo json_encode($error);
}
