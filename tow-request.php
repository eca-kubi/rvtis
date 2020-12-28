<?php
require_once 'src/autoloader.php';
require_once 'vendor/autoload.php';
use PubNub\Callbacks\SubscribeCallback;
use PubNub\Enums\PNStatusCategory;
use PubNub\PNConfiguration;
use PubNub\PubNub;

$pnconf = new PNConfiguration();

$pubnub = new PubNub($pnconf);

$pnconf->setSubscribeKey("sub-c-9d9871ca-fcfc-11e7-9936-9295006507e1");
$pnconf->setPublishKey("pub-c-d83631d2-0a74-4ac9-97fe-653be63ac9a8");

$payload = array();

$error = array();
if (isset($_POST["registrationnumber"])) {
    if ($_POST['registrationnumber'] == "") {
        $error["registrationnumber"] = "This field is required";
    }
    if ($_POST['cartype'] == "") {
        $error["cartype"] = "Please select a car type";
    }
    if (sizeof($error) !== 0) {
        $error["success"] = false;
        $json = json_encode($error);
        echo $json;
        exit;
    } else {
        $error["success"] = true;
        $json = json_encode($error);
        echo $json;
    }

    // Subscribe to a channel, this is not async.
    /*$pubnub->subscribe()
    ->channels("hello_world")
    ->execute();*/
   /* $payload["user"] = htmlentities($_POST['username']);
    $payload["username"] = htmlentities($_POST['username']);
    $payload["private-channel"] = htmlentities($_POST['username']);
    $payload["locationlat"] = htmlentities($_POST["locationlat"]);
    $payload["locationlng"] = htmlentities($_POST["locationlng"]);
    $payload["vehiclenumber"] = htmlentities($_POST["registrationnumber"]);
    $payload["vehiclemake"] = htmlentities($_POST["vehiclemake"]);
    $payload["vehiclemodel"] = htmlentities($_POST["vehiclemodel"]);
    $payload["cartype"] = htmlentities($_POST["cartype"]);
    $payload["towrequested"] = true;
    $payload["who"] = "user";
    // Use the publish command separately from the Subscribe code shown above.
    // Subscribe is not async and will block the execution until complete.
    $result = $pubnub->publish()
        ->channel("pubnub-company")
        ->message($payload)
        ->sync();*/

} else {
    exit;
}

class MySubscribeCallback extends SubscribeCallback
{
    public function status($pubnub, $status)
    {
        if ($status->getCategory() === PNStatusCategory::PNUnexpectedDisconnectCategory) {
            // This event happens when radio / connectivity is lost
        } else if ($status->getCategory() === PNStatusCategory::PNConnectedCategory) {
            // Connect event. You can do stuff like publish, and know you'll get it
            // Or just use the connected event to confirm you are subscribed for
            // UI / internal notifications, etc
        } else if ($status->getCategory() === PNStatusCategory::PNDecryptionErrorCategory) {
            // Handle message decryption error. Probably client configured to
            // encrypt messages and on live data feed it received plain text.
        }
    }
    public function message($pubnub, $message)
    {
        // Handle new message stored in message.message
    }

    public function presence($pubnub, $presence)
    {
        // handle incoming presence data
    }
}

$subscribeCallback = new MySubscribeCallback();
$pubnub->addListener($subscribeCallback);

//print_r($result);
