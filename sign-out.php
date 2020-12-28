<?php
if (isset($_GET['who'])) {
    $who = $_GET['who'];
    if(isset($_GET['anonymous'])){
      session_name('anonymous');
    } else {
      session_name($who);
    }
    session_start();
    $params = session_get_cookie_params();
    setcookie(session_name(), '', 0, '/tims-alpha/', $params['domain'], $params['secure'], isset($params['httponly']));
    session_destroy();
    header("Location: index.php");
}
