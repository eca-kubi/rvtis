<?php
include 'smsGateway.php';
include 'mysmsconfig.php';
include 'connect-sqlite.php';
if (isset($_GET['username']) && isset($_GET['who'])) {
  $username = $_GET['username'];
  $who = $_GET['who'];
  $stmt = $pdo->prepare("select * from $who where username=?");
  $stmt->execute([$username]);
  $row = $stmt->fetch();
  if ($row) {
    for ($i = 0; $i < 6; $i++) {
      $vericode .= mt_rand(0, 9);
    }
    $message = "Secret pin: $vericode <Message from: RTVTIS©>";
    if (send($message)) {
      $sql = "UPDATE $who SET password=?, passwordreset=? WHERE username=?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$vericode, 1, $username]);
      echo json_encode("{'success':true}");
    } else {
      echo json_encode("{'success':false}");
    }
  }
}
