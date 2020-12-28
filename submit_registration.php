<?php
include "connect-sqlite.php";
ob_start();
extract($_POST);
$username = strtolower(trim($username));
$response = array('has_error' => false, 'data' => array());
if ($_POST['who'] == 'user') {
  session_name('user');
  session_set_cookie_params(time() + 3600 * 24 * 30, '/tims-alpha/');
  session_start();
  session_regenerate_id();
  setcookie(session_name(), session_id(), time() + 3600 * 24 * 30, '/tims-alpha/');
  if (count_row($username, 'user', 'username') > 0) {
    $response["has_error"] = true;
    array_push($response['data'], array('cause' => 'username', 'message' => 'Username already exists'));
    $json = json_encode($response);
    echo $json;
    exit;
  } else {
    $db = getPdo();
    $ret = $db->exec("insert into user (name, phone, username, password) values ('$fullname', '$phone', '$username', '$password')");
    if ($ret > 0) {
      $_SESSION["$who"] = $username;
      $_SESSION['phone']=$phone;
      $_SESSION['name']=$fullname;
      $json = json_encode($response);
      echo $json;
    } else {
      $response["has_error"] = true;
      array_push($response['data'], array('cause' => 'general', 'message' => 'A general error occured!'));
      $json = json_encode($response);
      echo $json;
      exit;
    }
    exit;
  }
} else if ($_POST['who'] == 'company') {
  session_name('company');
  session_start();
  if (count_row($username, 'company', 'username') > 0) {
    $response["has_error"] = true;
    array_push($response['data'], array('cause' => 'username', 'message' => 'Username already exists'));
    $json = json_encode($response);
    echo $json;
    exit;
  } else {
    $db = getPdo();
    $ret = $db->exec("insert into company (name, phone, username, password) values ('$fullname', '$phone', '$username', '$password')");
    if ($ret > 0) {
      $_SESSION["$who"] = $username;
      $json = json_encode($response);
      echo $json;
    } else {
      $response["has_error"] = true;
      array_push($response['data'], array('cause' => 'general', 'message' => 'A general error occured!'));
      $json = json_encode($response);
      echo $json;
      exit;
    }
    exit;
  }
} else {
  if (count_row($username, 'truckdriver', 'username') > 0) {
    $response["has_error"] = true;
    array_push($response['data'], array('cause' => 'username', 'message' => 'Username already exists'));
    $json = json_encode($response);
    echo $json;
    exit;
  } else {
    $db = getPdo();
    $ret = $db->exec("insert into truckdriver (name, phone, username, password, company) values ('$fullname', '$phone', '$username', '$password', '$company')");
    if ($ret > 0) {
      $row = $db->query("select * from truckdriver where company='$company' and username='$username'")->fetch();
      array_push($response["data"], $row);
      $json = json_encode($response);
      echo $json;
    } else {
      $response["has_error"] = true;
      array_push($response['data'], array('cause' => 'general', 'message' => 'A general error occured!'));
      $json = json_encode($response);
      echo $json;
      exit;
    }
  }
}
function count_row($data, $table, $column)
{
  $db = getPdo();
  $stmt = $db->prepare("SELECT COUNT(*) as count from {$table} where {$column} = ?");
  $stmt->execute([$data]);
  $result = $stmt->fetch();
  return $result['count'];
}
