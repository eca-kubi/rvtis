<?php
include "smsGateway.php";
$desc = $_POST['problem'];
$name = $_POST['name'];
$vehicleno = $_POST['vehicleno'];
$type = $_POST['cartype'];
$phone = $_POST['phone'];
$companyname = $_POST['companyname'];
$companyusername= $_POST['companyusername'];
$companyphone = $_POST['phone'];

$email = "appiahmakuta70@gmail.com";
$password = "gmail300";
$id = array("z10" => 75821, "techno" => 75823, "blu"=>85174);
$smsGateway = new SmsGateway($email, $password);
$page = 1;
$deviceID = $id["blu"];
$number = '+233547468603';

$message = <<<L
Name: $name||Vehicle No. $vehicleno||Type:$type||Problem: $desc
L;

$options = [
    'send_at' => strtotime('+1 minutes'), // Send the message in 10 minutes
    'expires_at' => strtotime('+1 hour'), // Cancel the message in 1 hour if the message is not yet sent
    'deviceID'=> $deviceID
];

$result = $smsGateway->sendMessageToNumber($companyphone, $message, $deviceID, $options);
$ret=['success'=>false
];
if ($result['status'] == 200) {
  if (!$result['response']['success']) {
    //throw new Exception("SMS sending failed!", 1);
    echo json_encode($ret);
  } else if (count($result['response']['result']['success']) <= 0) {
    //throw new Exception("SMS sending failed!", 1);
    echo json_encode($ret);
  } else {
    $ret['success']=true;
    echo json_encode($ret);
  }
} else {
    //throw new Exception("SMS sending failed!", 1);
    echo json_encode($ret);
}
?>