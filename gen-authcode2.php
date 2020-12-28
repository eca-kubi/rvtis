<?php
$user = "admin";
$pw = "";
session_start();

$post_user = isset($_POST['user']) ? $_POST['user'] : "";
$post_pw = isset($_POST['pw']) ? $_POST['pw'] : "";

if (isset($_SESSION['user']) || $post_user == $user && $pw == $post_pw) {
    $_SESSION['user'] = $post_user;
    $_SESSION['pw'] = $post_pw;
    include('generator.html');
    ?>
    
    <?php
    exit;
}
if ($post_user == $user && $pw == $post_pw) {
    $_SESSION['user'] = $post_user;
    $_SESSION['pw'] = $post_pw;
}


 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/w3.css">
    <link rel="stylesheet" href="assets/css/ionicons.css">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body>

<div class="row m-top-l">
<div class="col-lg-4 col-sm-3 col-xs-1"></div>
<div class="w3-container w3-panel w3-card-4 col-lg-4  col-sm-6 col-xs-10 w3-round">
    <div class="w3-content">
        <header class="w3-container w3-center w3-margin">
            <h3>Company Code Generator</h3>
            <small><span class="glyphicon glyphicon-console"></span> Admin Login Console</small>
        </header>
        <form action="" method="post" class="form-horizontal hidden-xs hidden-sm">
            <div class="form-group row">
                <label for="user" class="control-label col-lg-3 col-lg-push-1 col-sm-3 col-sm-push-1 col-xs-3 col-xs-push-1">
                    <span class="glyphicon glyphicon-user"></span> 
                    User
                </label>
                <div class="col-lg-7 col-lg-push-1 col-sm-7 col-sm-push-1 col-xs-7 col-xs-push-1">
                    <input type="text" class="form-control" name="user">
                </div>
            </div>
            <div class="form-group row">
                <label for="pw" class="control-label col-lg-3 col-lg-push-1 col-sm-3 col-sm-push-1 col-xs-3 col-xs-push-1">
                    <span class="ion ion-key"></span> 
                    Password
                </label>
                <div class="col-lg-7 col-lg-push-1 col-sm-7 col-sm-push-1 col-xs-7 col-xs-push-1">
                    <input type="password" class="form-control" name="pw">
                </div>
            </div>
            <div class="form-group">
                <div class=" col-sm-8 col-lg-offset-4 col-md-offset-4">
                    <button type="submit" class="btn btn-default">Sign in</button>
                </div>
            </div>
        </form>
        <form action="" method="post" class="form-horizontal hidden-lg hidden-md">
            <div class="row">
                <div class="input-group w3-margin">
                    <div class="input-group-addon">
                        <span class="glyphicon glyphicon-user"></span>  
                    </div>                        
                    <input type="text" class="form-control" name="user" placeholder="Username">
                </div>
                <div class="input-group w3-margin">
                    <div class="input-group-addon">
                        <span class="ion ion-key"></span>  
                    </div>                        
                    <input type="password" class="form-control" name="pw" placeholder="Password">
                </div>
            </div>
            <div class="form-group">
                <div class=" col-sm-10">
                    <button type="submit" class="btn btn-default">Sign in</button>
                </div>
            </div>
        </form>
    </div> 
        
        
    </div>
<div class="col-lg-4 col-sm-3 col-xs-1"></div>
</div>
    
    <script src="assets/js/jquery.js"></script>
    <script src="assets/js/jquery-ui.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/tether.min.js"></script>
</body>
</html>
<?php

?>