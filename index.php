<?php
include "connect-sqlite.php";
ob_start();
$idcompany='';
$tether=false;
$passwordreset=false;
$phone='';
$name='';
$page='index.php';
$anonymous=false;
$who = "";
$user_id = -1;
$valid = false;
$secret_word = "tims-dope";
$error = array('cause' => '', 'url' => "", 'form'=>'');
$username="";
$password="";
if (isset($_POST["who"])) {
  $who = $_POST["who"];
  $username = $_POST['username'];
  $password = $_POST['password'];
  if ($who == "user") {
    $error['form']='#userloginform';
    $error['url']='user.php?loggedin=true';
    $stmt = $db->prepare("select * from $who where username=?");
    $stmt->execute([$username]);
    $row = $stmt->fetch();
    if (!$row) {
      $error["success"] = false;
      $error['reason'] = 'The username is incorrect!';
      $error['cause']='username';
      echo json_encode($error);
      exit;
    }
    $stmt = $db->prepare("select * from $who where username=? and password=?");
    $stmt->execute(["$username", "$password"]);
    $row = $stmt->fetch();
    if (!$row) {
      $error["reason"] = "The password is incorrect! You may reset it if you have forgotten.<div><button class='btn btn-primary btn-sm reset-btn1  w3-center w3-margin' data-username='$username' data-who='$who'>Reset Password</button><button class='btn btn-default cancel' onclick='(function (args) {
        $.notifyClose();
      })()'>Close</button></div>";
        $error['success'] = false;
        $error['cause']='password';
        echo json_encode($error);
        exit;
    } else {
      session_name('user');
      session_set_cookie_params(time() + 3600 * 24 * 30, '/tims-alpha/');
      session_start();
      session_regenerate_id();
      setcookie(session_name(), session_id(), time() + 3600 * 24 * 30, '/tims-alpha/');
      $_SESSION['user'] = $_POST['username'];
      $_SESSION['username'] = $_POST['username'];
      $_SESSION['phone'] = $row['phone'];
      $_SESSION['name'] = $row['name'];
      $_SESSION['tid']=$row['iduser'];
      $error['success'] = true;
      echo json_encode($error);
      exit;
    }
  } elseif ($who == "truckdriver") {
    $error['form']='#truckdriverloginform';
    $error['url']='truckdriver.php';
    $stmt = $db->prepare("select * from truckdriver where username=?");
    $stmt->execute([$username]);
    $row = $stmt->fetch();
    if (!$row) {
      $error["success"] = false;
      $error['reason'] = 'The username is incorrect!';
      $error['cause']='username';
      echo json_encode($error);
      exit;
    }

    $stmt = $db->prepare("SELECT c.name companyname, c.username companyusername,t.username, t.phone, t.name, t.idtruckdriver, b.idbranch, c.idcompany, b.name branch  from truckdriver t left join branch b on t.idbranch=b.idbranch  left join company c on c.idcompany=b.idcompany where t.username=? and t.password=?");
    $stmt->execute(["$username", $password]);
    $row = $stmt->fetch();
    if (!$row) {
      $error["reason"] = "The password is incorrect! You may reset it if you have forgotten.<div><button class='btn btn-primary btn-sm reset-btn1  w3-center w3-margin' data-username='$username' data-who='$who'>Reset Password</button><button class='btn btn-default cancel' onclick='(function (args) {
        $.notifyClose();
      })()'>Close</button></div>";
        $error['success'] = false;
        $error['cause']='password';
        echo json_encode($error);
        exit;
    }  else {
      session_name('truckdriver');
      session_set_cookie_params(time() + 3600 * 24 * 30, '/tims-alpha/');
      session_start();
      session_regenerate_id();
      setcookie(session_name(), session_id(), time() + 3600 * 24 * 30, '/tims-alpha/');
      $_SESSION['truckdriver'] = $_POST['username'];
      $_SESSION['username'] = $_POST['username'];
      $_SESSION['phone'] = $row['phone'];
      $_SESSION['name'] = $row['name'];
      $_SESSION['branch']=$row['branch'];
      $_SESSION['companyname']=$row['companyname'];
      $_SESSION['companyusername']=$row['companyusername'];
      $error['success'] = true;
      echo json_encode($error);
      exit;
    }
  } elseif ($who == "company") {
    $error['form']='#companyloginform';
    $error['url']='company.php';
    $stmt = $db->prepare("select * from company where username=?");
    $stmt->execute([$username]);
    $row = $stmt->fetch();
    if (!$row) {
      $error["success"] = false;
      $error['cause']='username';
      $error['reason'] = 'The username is incorrect!';
      echo json_encode($error);
      exit;
    }
    $stmt = $db->prepare("select * from company where username=? and password=?");
    $stmt->execute(["$username", "$password"]);
    $row = $stmt->fetch();
    if (!$row) {
      $error["reason"] = "The password is incorrect! You may reset it if you have forgotten.<div><button class='btn btn-primary btn-sm reset-btn1  w3-center w3-margin' data-username='$username' data-who='$who'>Reset Password</button><button class='btn btn-default cancel' onclick='(function (args) {
        $.notifyClose();
      })()'>Close</button></div>";
        $error['success'] = false;
        $error['cause']='password';
        echo json_encode($error);
        exit;
    } else {
      session_name('company');
      session_set_cookie_params(time() + 3600 * 24 * 30, '/tims-alpha/');
      session_start();
      session_regenerate_id();
      setcookie(session_name(), session_id(), time() + 3600 * 24 * 30, '/tims-alpha/');
      $_SESSION['company'] = $_POST['username'];
      $_SESSION['username'] = $_POST['username'];
      $_SESSION['phone'] = $row['phone'];
      $_SESSION['name'] = $row['name'];
      $error['success'] = true;
      echo json_encode($error);
      exit;
    }
  }
}

?>

  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RVTIS&copy;</title>
    <link rel="stylesheet" href="assets/css/w3.css">
    <link rel="stylesheet" href="assets/css/font-awesome.css">
    <link rel="stylesheet" href="assets/css/ionicons.css">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/offline-theme-chrome-indicator.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="assets/css/animate.css/animate.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <script src="assets/js/jquery.js"></script>
    <script src="assets/jquery-ui/jquery-ui.js"></script>
    <script src="assets/js/jquery.easing.1.3.js"></script>
    <script src="assets/js/tether.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/bootstrap-notify.js"></script>

  </head>

  <body>
    <div class="container w3-round-large" style="background-image:url('bg.jpg');">
      <div class="row">
        <div class="col-lg-12 ">
          <h1 class="h1 w3-center w3-animate-top w3-margin well w3-hide-small w3-amber ">Real-Time Vehicle Towing Information System
            <p class="">(RVTIS
              <sup></sup>&copy;</sup>)</p>
          </h1>
          <h3 class="h3 w3-center w3-animate-top w3-margin well w3-hide-large w3-hide-medium w3-amber">Real-Time Vehicle Towing Information System
            <p class="">(RVTIS
              <sup>&copy;</sup>)</p>
          </h3>
          <space class="w3-margin"></space>
          <h3 class="w3-margin h3 w3-center tell-us w3-amber w3-text-black w3-round-large w3-card  fadeInDownBig">
            <span class="">Who are you?</span>
            <span class="glyphicon glyphicon-sunglasses"></span>
          </h3>
          <space class="w3-margin"></space>
          <div class="col-lg-6 col-lg-push-3 col-md-6 col-md-push-3 col-xs-12 w3-amber w3-round-large">
            <form action="#" class="form-horizontal w3-padding-16">
              <div class="input-group ">
                <span class="input-group-addon w3-amber">
                  <input type="radio" aria-label="..." name="user" id="user1" data-user="a vehicle owner" value="user1">
                </span>
                <input type="text" class="form-control text-bold w3-large w3-amber" aria-label="..." value="A Vehicle Owner?" data-user="a vehicle owner" data-target="#userlogin"
                  data-toggle="modal" onclick="(()=>{$('#user1').click(); })();" readonly>
              </div>
              <div class="input-group">
                <span class="w3-amber input-group-addon">
                  <input type="radio" aria-label="..." name="user" id="user2" value="user2" data-user="a tow truck driver">
                </span>
                <input type="text" class="form-control inp text-bold w3-large w3-amber" aria-label="..." value="A Tow Truck Driver?" data-user="a tow truck driver" onclick="(()=>{$('#user2').click()})();"
                  data-target="#truckdriverloginmodal" data-toggle="modal" readonly>
              </div>
              <div class="input-group">
                <span class="w3-amber input-group-addon">
                  <input type="radio" aria-label="..." name="user" id="user3" value="user3" data-user="a tow company">
                </span>
                <input type="text" class="form-control inp text-bold w3-large w3-amber" aria-label="..." value="A Tow Company?" data-user="a tow company" data-toggle="modal"
                  data-target="#companyloginmodal" onclick="(()=>{$('#user3').click()})();" readonly>
              </div>
              <space class="w3-margin"></space>
              <div class="w3-center w3-margin begin hide hide-registration-button">

                <div>
                  <p class="w3-margin-right" title="Click this button below to begin registration">Click this button
                    <span class="glyphicon glyphicon-arrow-down"></span> to register
                    <span></span>
                  </p>
                  <button type="button" class="btn  btn-default registration-button" data-toggle="modal" data-target="">Begin Registration</button>
                  <p class="w3-margin">or</p>
                </div>

                Click this to login
                <span class="glyphicon glyphicon-arrow-right"></span>
                <button type="button" class="btn  btn-default hide" data-toggle="modal" data-target="">Login</button>

                <span></span>
              </div>
            </form>
          </div>

        </div>

      </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="resetPassword" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true"
      data-dismiss="false">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="modal-title" id="modelTitleId">Enter Secret Pin</h4>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="pin">Secret Pin</label>
              <input type="text" class="form-control" name="pin" id="pin" aria-describedby="helpId" placeholder="" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Reset Password</button>
          </div>
        </div>
      </div>
    </div>
    <!-- Modals.html -->
    <?php include('modals.html'); ?>
    <!-- Modals.html // -->
    <script>
      //# sourceURL=index1.js
      function initialize() { }
      window.initialize = initialize;

      var goggle = $(".glyphicon-sunglasses");

      function rotateGoggle() {
        if (goggle.hasClass("rotate1")) {
          oldClass = "rotate1";
          newClass = "rotate2";
          goggle.switchClass(oldClass, newClass);
        } else if (goggle.hasClass("rotate2")) {
          oldClass = "rotate2";
          newClass = "rotate1";
          goggle.switchClass(oldClass, newClass);
        } else {
          goggle.addClass("rotate1");
        }
      }

      var t; // to clear interval
      $(() => {
        t = setInterval(rotateGoggle, 3000);
        // setInterval(()=>{button.toggle("explode")}, 1000);
      });
    </script>

    <script>
      //# sourceURL=index2.js
      var gcaptchaChecked = false;
      var gcaptchaLoaded = false;

      window.onload = function name() {
        $.fn.extend({
          showHidePopover: showHidePopover
        });

        $("#companyregistrationform").validator().on('submit', function name(evt) {
          if (evt.isDefaultPrevented()) {
            return false;
          }
          var form = $(this);
          var url = "company.php";
          $.post("submit_registration.php", form.serialize())
            .done(function (result) {
              try {
                let json = JSON.parse(result);
                if (!json.has_error) {
                  $('.modal').modal('hide');
                  location.href = 'company.php?newregistration=true'
                } else {
                  json.data.forEach(element => {
                    if (element.cause == 'username') {
                      $('#companyregistrationform input[name="username"]').focus()
                      $.notify('This username has already been taken', {
                        z_index: 1060, type: 'info',
                        type: 'warning',
                        icon: 'glyphicon glyphicon-alert'
                      })
                    }
                  });
                }
              } catch (error) {
                log(error);
              }
            })
          evt.preventDefault()
        });

        // $("[data-toggle='popover']").popover();
        // $(".modal-footer button:eq(0) ").click(() => {
        //   $(".control-feedback").addClass("hidden").html("");
        //   $(".has-error").removeClass("has-error");
        // });

        $("#userregistrationform").on('submit', function name(e) {
          var form = $(this);
          if (e.isDefaultPrevented()) {
            return false;
          }
          $.post("submit_registration.php", form.serialize())
            .done(function (result) {
              try {
                let json = JSON.parse(result);
                if (!json.has_error) {
                  $('.modal').modal('hide');
                  window.location.href = 'user.php?newregistration=true';
                } else {
                  json.data.forEach(element => {
                    if (element.cause == 'username') {
                      $('#userregistrationform input[name="username"]').focus()
                      $.notify('This username has already been taken', {
                        z_index: 1060, type: 'info',
                        type: 'warning',
                        icon: 'glyphicon glyphicon-alert'
                      })
                    }
                  });
                }
              } catch (error) {
                log(error);
              }
            })
          e.preventDefault()
        });
      }

      function log(params) {
        console.log(params);
      }

      function isEmpty(cnt) {
        let control = cnt instanceof jQuery ? cnt : $(cnt);
        let text = control.val().trim();
        if (text == "") {
          control.attr("data-content", "<span class='text-danger'>This field is required!</span>");
          control.showHidePopover();
          control.focus();
          return true;
        }
        return false;
      }
      var checkConnectionTimer;
      var connectiontesturl = "https://www.google.com/recaptcha/api2/reload?k=6LcCmUAUAAAAAJRwh9NT9cT_LV5WsawVzhMv667B";

      function showHidePopover(timeout = 7000, content = "") {
        this.attr("data-content", `<span class='text-danger'>${content}</span>`);
        this.popover("show");
        setTimeout(() => {
          this.popover("hide");
        }, timeout);
      }

      addEventListener('load', function (event) {
        $(document).on('click', '.reset-btn2', function (evt) {
          $.notifyClose();
        })
        $(document).on('click', '.reset-btn1', (evt) => {
          $(".modal").modal("hide");
          var username = $(evt.currentTarget).attr('data-username');
          var who = $(evt.currentTarget).attr('data-who');
          var modal = who + 'login';
          $.get('resetpassword.php?username=' + username + '&who=' + who, function (ret) {
            //$.notifyClose()
            window.notif.update({
              message: `We have sent a pin to your phone number. Please use the pin as your temporal login password. Make sure you set a new password. <br><button class="btn btn-success btn-sm reset-btn2" data-target="#${modal}" data-toggle='modal'>OK</button>`,
              type: 'success',
              allow_dismiss: false
            })
          })
        })
        $('.login-form').validator().on('submit', function functionName(evt) {
          if (evt.isDefaultPrevented()) {
            return false;
          }
          $.post('index.php', $(this).serialize(), function (ret) {
            $.notifyClose()
            try {
              var json = JSON.parse(ret);
              var form = json.form
              if (json.success) {
             location.href  =json.url;
              } else {
                var cause = json.cause
                if (cause == 'password') {
                  $.notify({
                    message: json.reason,
                  }, {
                      type: 'danger',
                      z_index: 1060,
                      delay:0
                    })
                    $(form).find('input[name="password"]').focus();
                } else {
                  $.notify({
                    message: json.reason,
                  }, {
                      type: 'danger',
                      z_index: 1060,
                    })
                    $(form).find('input[name="username"]').focus();
                }

              }
            } catch (err) {
            }
          })
          evt.preventDefault();
        })
      })
    </script>
  </body>

  </html>