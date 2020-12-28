<?php
function send($message)
{
  $email = "appiahmakuta70@gmail.com";
  $password = "gmail300";
  $id = array("z10" => 75821, "techno" => 75823, "blu" => "85174");
  $smsGateway = new SmsGateway($email, $password);
  $page = 1;
  $deviceID = $id["blu"];
  $number = '+233547468603';
  $options = [
    'send_at' => strtotime('+1 minutes'), // Send the message in 10 minutes
    'expires_at' => strtotime('+1 hour'), // Cancel the message in 1 hour if the message is not yet sent
  ];
  $result = $smsGateway->sendMessageToNumber($number, $message, $deviceID, $options);
  if ($result['status'] == 200) {
    if (!$result['response']['success']) {
      throw new Exception("SMS sending failed!", 1);
      exit;
    } else if (count($result['response']['result']['success']) <= 0) {
      throw new Exception("SMS sending failed!", 1);
      exit;
    }
  } else {
    throw new Exception("SMS sending failed!", 1);
    exit;
  }
  return true;
}

?>