<?php
$user = "admin";
$password = "";
$try_again = isset($_POST['try_again'])?$_POST['try_again']:0;
session_start();

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    authenticate();
}
if ($_SERVER['PHP_AUTH_USER'] == $user && $_SERVER['PHP_AUTH_PW'] == $password) {
    echo "ok";
    exit;
} else {
    if($try_again) {
        authenticate();
    }
    ?>
      <html><head></head><body onload="init();"><form id='tryAgainForm' action='gen-authcode.php' method='post'>
        <input type='hidden' value='1' name='tryAgain' id='tryAgain'>
        <input type='button' value='try again' id='tryAgainBtn'>
        </form>
        <script>//# sourceURL=gen-authcode.js
        function init(){
            alert('You may have entered the wrong credentials.\n click on try again');
            tryAgainBtn.onclick = (e) => {
                v =tryAgain.value;
                if(v == 1) {
                    tryAgain.value = 0;
                } else if (v == 0) {
                    tryAgain.value = 1;
                }
                tryAgainForm.submit();
                /*var fd = new FormData(tryAgainForm);
                var xttp = new XMLHttpRequest();
                xttp.open('post', tryAgainForm.action);
                xttp.send(fd);*/
            }
        }
        </script></body></html>
    <?php

}

function authenticate()
{
    $realm = 'My Website for ' . time();
    http_response_code(401);
    header('WWW-Authenticate: Basic realm="' . $realm . '"');
    echo "You need to enter a valid username and password.";
    exit;
}
?>