<?php
ob_start();
include 'connect-sqlite.php';
$tether=true;
$driverpasswords=[];
$anonymous='';
$secretword = 'tims-dope';
$username = 'anonymous company';
$page ='company.php';
$phone="";
$who='company';
$passwordreset = false;
$name="";
$idcompany="";

session_name('company');
session_set_cookie_params(time() + 3600 * 24 * 30, '/tims-alpha/');
session_start();
session_regenerate_id();
setcookie(session_name(), session_id(), time() + 3600 * 24 * 30, '/tims-alpha/');
if (isset($_SESSION['company'])) {
  $username = $_SESSION['company'];
  $phone = isset($_SESSION['phone'])? $_SESSION['phone'] : "";
  $name=isset($_SESSION['name'])? $_SESSION['name'] : "";
  $stmt =$db->prepare('SELECT passwordreset, idcompany from company where username=? LIMIT 1');
  $stmt->execute([$username]);
  $row =$stmt->fetch();
  $idcompany=$row[1];
  if($row){
    $passwordreset = boolval($row[0]);
  }
} else {
  header("Location: index.php");
  exit;
}

?>


  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>RVTIS&copy; Company -
      <?php echo 
    $name; ?>
    </title>
    <script src="assets/js/pace.js"></script>
    <script src="assets/js/jquery.js"></script>
    <script type="text/javascript">
      $.extend(window.Pace.options, { ajax: { trackMethods: ['GET', 'POST', 'DELETE', 'PUT', 'PATCH'], ignoreURLs: [/.*subscribe\/sub-c-1adc0c20-01bf-11e8-91aa-36923a88c219.*/] } }) </script>
    <link rel="stylesheet" href="assets/css/pace-theme-minimal-yellow.css">
    <script src='assets/js/pubnub.4.20.3.js'></script>
    <script src="assets/jquery-ui/jquery-ui.js"></script>
    <script src="assets/js/jquery.easing.1.3.js"></script>
    <script src="assets/js/tether.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/bootstrap-notify.js"></script>
    <link rel="stylesheet" href="./assets/AdminLTE/css/AdminLTE.css">
    <link rel="stylesheet" href="assets/css/w3.css">
    <link rel="stylesheet" href="assets/css/ionicons.css">
    <link rel="stylesheet" href="assets/css/font-awesome.css">
    <link rel="stylesheet" href="assets/css/tether.css">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <script src="assets/grunticon-loader/grunticon.loader.js"></script>
    <script>
      grunticon(["assets/grunticon-loader/icons.data.svg.css", "assets/grunticon-loader/icons.data.png.css",
        "assets/grunticon-loader/icons.fallback.css"
      ], grunticon.svgLoadedCallback);
    </script>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/grunticon-loader/icons.data.svg.css">
    <link rel="stylesheet" href="assets/grunticon-loader/icons.data.png.css">
    <link rel="stylesheet" href="assets/grunticon-loader/icons.fallback.css">
    <link rel="stylesheet" href="assets/datatable.net/css/datatables.css">
    <link rel="stylesheet" href="assets/css/offline-theme-default.css">
    <link rel="stylesheet" href="assets/css/offline-theme-slide.css">
    <link rel="stylesheet" href="assets/css/offline-language-english.css">
    <link rel="stylesheet" href="assets/css/animate.css/animate.css">
    <script src="assets/js/offline.js"></script>
    <script>
      var offlinenotif;
      var onlinenotif;
      Offline.on('up', function (param)
      {
        if (typeof map == 'undefined')
        {
          $.getScript("https://maps.googleapis.com/maps/api/js?libraries=geometry&key=AIzaSyCqjMdWdNss5BgNzucIYPBBMGkKs7Y5dlI&callback=initMap", function (data, textStatus, jqxhr)
          {
            // console.log(data); //data returned
            // console.log(textStatus); //success
            // console.log(jqxhr.status); //200
            console.log('Map Load was performed.');
          });
        }
        if (offlinenotif)
        {
          offlinenotif.close();
          delete offlinenotif;
        }
        if (!onlinenotif)
        {
          onlinenotif = $.notify('You are online.', {
            type: 'success', delay: 3000, placement: {
              from: "bottom",
              align: "right"
            }, animate: {
              enter: 'animated fadeInUp',
              exit: 'animated fadeOutDown'
            }
          });
        } else
        {
          onlinenotif = $.notify('You are online.', {
            type: 'success', delay: 3000, placement: {
              from: "bottom",
              align: "right"
            }, animate: {
              enter: 'animated fadeInUp',
              exit: 'animated fadeOutDown'
            }
          });
        }

      });

      Offline.on('down', function (param)
      {
        if (onlinenotif)
        {
          onlinenotif.close();
          delete onlinenotif;
        }

        if (!offlinenotif)
        {
          offlinenotif = $.notify('You are offline', {
            type: 'danger', delay: 0, placement: {
              from: "bottom",
              align: "right"
            },
            animate: {
              enter: 'animated fadeInUp',
              exit: 'animated fadeOutDown'
            }
          });
        } else
        {
          offlinenotif = $.notify('You are offline', {
            type: 'danger', delay: 0, placement: {
              from: "bottom",
              align: "right"
            },
            animate: {
              enter: 'animated fadeInUp',
              exit: 'animated fadeOutDown'
            }
          });
        }
      });
    </script>
    <link rel="stylesheet" href="assets/css/fonts.css">
    <link rel="stylesheet" href="assets/css/scroller.dataTables.css">
  </head>

  <body>
    <?php require 'modals.html'; ?>
    
    <div class="alert alert-danger offline-ui offline-ui-connecting">
      <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
      <strong>You are offline</strong>
    </div>
    
    <input type="hidden" id="passwordreset" value="<?php echo strval ($passwordreset);?>">
    <div>
      <nav class="navbar navbar-default m-b-0 navbar-fixed-top w3-border-bottom pad">
        <div class="container-fluid row">
         <i><span class="icon-icon-delivery-man w3-left w3-margin" data-grunticon-embed style="width:45px!important"></span></i> 
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header ">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"
              aria-expanded="false">

              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>

            <a class="navbar-brand" href="#">Welcome to
              <span>RVTIS
                <sup>&copy;</sup>
              </span>
              <br>
              <small class="w3-select w3-serif w3-sepia-min w3-tiny">Real-Time Vehicle Towing Information System</small>
            </a>
            <a class="navbar-brand text-bold text-fuchsia w3-margin" href="#">
              <?php echo $username; ?> </a>
            <a class="navbar-brand total-tow-request w3-margin" href="#">
              <div>
                <p onclick="$('body').scrollTo('#jobstatus',1000,{offset:-120})" class="w3-small font-weight-bold w3-text-amber">Total tow requests:
                  <span class="label label-warning w3-tiny">0</span>
                </p>
                <i class="icon-car grump-icon w3-animate-fading hide" data-grunticon-embed>
                </i>
              </div>
            </a>
            <div class="w3-right">
              <ul style="list-style: none" class="w3-padding-16">
                <li class="dropdown notifications-menu" style="margin-right: 32px">
                  <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                    <i class="icon-car-breakdown grump-icon" data-grunticon-embed title="Tow requests">
                    </i>
                    <span class="label label-warning tow-requests-label" title="Tow requests"></span>
                  </a>
                  <ul class="dropdown-menu">
                    <li class="header dropdown-header you-have">You have
                      <span></span> tow requests</li>
                    <li>
                      <!-- inner menu: contains the actual data -->
                      <ul class="menu list-group">
                        <li class="list-group-item tow-request-description">
                          <a href="#">
                            <i class="fa fa-car text-aqua"></i>
                            <small class="tow-request-description text-muted"></small>
                          </a>
                        </li>
                      </ul>
                    </li>
                    <li class="footer">
                      <a href="#">View all</a>
                    </li>
                  </ul>
                </li>
              </ul>
            </div>
          </div>

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse w3-right col-xs-12" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
              <li>
                <a href="#maptop" onclick="$('body').scrollTo('#maptop',1000,{offset:-140})">
                  <span class="ion ion-android-map"></span> Map</a>
              </li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">View
                  <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                  <li>
                    <a href="#jobstatus" onclick="$('body').scrollTo('#jobstatus',1000,{offset:-120})">
                      <span class="fa fa-briefcase"></span> Tow Requests</a>
                  </li>
                  <li class="hide">
                    <a href="#viewfeedback" onclick="$('body').scrollTo('#viewfeedback',1000,{offset:-120})" <i class="fa fa-history" aria-hidden="true">
                      </i> Feedback</a>
                  </li>
                  <li>
                    <a href="#livedriverstatus" onclick="$('body').scrollTo('#livedriverstatus',1000,{offset:-120})">
                      <i class="fa fa-dashboard" aria-hidden="true"></i> Live Status</a>
                  </li>
                </ul>
              </li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Edit/ Add
                  <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                  <li>
                    <a href="#" onclick="$('body').scrollTo('#editdrivers',1000,{offset:-120})">
                      <span class="icon-delivery-man" data-grunticon-embed></span> Driver</a>
                  </li>
                  <li>
                    <a href="#" onclick="$('body').scrollTo('#branches',1000,{offset:-120})">
                      <span class="ion ion-leaf"></span> Branches</a>
                  </li>
                </ul>
              </li>
              <?php if($passwordreset){ ?>
              <li id="createpassword">
                <a href="" data-target="#newpassword" data-toggle="modal">
                  <span class="ion ion-lock-combination"></span> Create Password</a>
              </li>
              <?php } ?>
              <li>
                <a href="" data-target="#editprofile" data-toggle="modal">
                  <span class="ion ion-edit"></span> Edit Profile</a>
              </li>
              <?php if($username =='anonymous company'){ ?>
              <li>
                <a href="" data-toggle="modal" data-target="#companyloginmodal">
                  <span class="ion ion-android-open"></span> Sign In</a>
              </li>
              <?php } else {
                  ?>
              <li>
                <a href="sign-out.php?who=company">
                  <span class="ion ion-locked"></span> Sign Out</a>
              </li>
              <?php } ?>
            </ul>
            <form class="navbar-form navbar-right hide">
              <input type="hidden" name="username" value="<?php echo $username; ?>">
              <div class="form-group ">
                <div class="input-group">
                  <input type="text" class="form-control" style="border: 0" placeholder="Search Company">
                  <span class="times-bar w3-text-dark-gray w3-hover-text-blue-gray" role="button">&timesbar;</span>
                </div>
              </div>
              <button type="submit" class="btn btn-default">Submit</button>
            </form>
            <ul class="nav navbar-nav hide">
              <li>
                <a href="#">Link</a>
              </li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown
                  <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                  <li>
                    <a href="#">Action</a>
                  </li>
                  <li>
                    <a href="#">Another action</a>
                  </li>
                  <li>
                    <a href="#">Something else here</a>
                  </li>
                  <li role="separator" class="divider"></li>
                  <li>
                    <a href="#">Separated link</a>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
          <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
      </nav>
    </div>
    <?php
$ret = $db->query("SELECT name, idcompany from company where username='$username'")->fetch();
$companyname = $ret['name'];
$idcompany=$ret['idcompany'];
?>
      <div class="w3-container w3-margin-bottom w3-bottombar margin-topxlarge" id="editdrivers">
        <div class="table-responsive">
          <table class="table table-bordered driverstable table-striped dt-responsive">
            <caption class="  w3-large text-bold">Edit/ Add New Drivers
              <span class="fa fa-plus btn-primary btn" title="Add a new driver" data-target="#adddrivermodal" data-toggle="modal"></span>
              <span class="  fa fa-trash btn-danger btn" title="Delete all drivers" data-target="#delete-all-drivers-modal" data-toggle="modal"></span>
            </caption>
            <thead class="w3-amber text-uppercase">
              <tr>
                <th>Name</th>
                <th>Username</th>
                <th>Phone</th>
                <th>Branch</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody class="w3-text-yellow">
              <?php
foreach ($drivers as $key => $value) {
  ?>
                <tr class="font-bold" data-idbranch="<?php echo $value['idbranch']; ?>" data-idtruckdriver="<?php echo $value['idtruckdriver']; ?>">
                  <td class="text-capitalize">
                    <?php echo $value['fullname']; ?>
                  </td>
                  <td>
                    <?php echo $value['username']; ?>
                  </td>
                  <td>
                    <?php echo $value['phone']; ?>
                  </td>
                  <td>
                    <?php
                    $idbranch = $value['idbranch'];
                     echo $branches[$idbranch]['branch']; ?>
                  </td>
                  <td>
                    <div>
                      <a href="#no" class='btn-danger w3-btn w3-text-yellow col-xs-6' data-toggle='modal' data-target='#deletetruckdriverrecordmodal'
                        data-username='<?php echo $value["username"]; ?>' data-fullname='<?php echo $value["fullname"]; ?>'
                        data-idtruckdriver='<?php echo $value["idtruckdriver"]; ?>' title="Delete">
                        <i class="glyphicon glyphicon-trash"></i>
                      </a>
                      <a href="" class='w3-teal w3-btn w3-text-yellow col-xs-6' role="button" data-toggle='modal' data-target='#editdrivermodal'
                        data-username='<?php echo $value["username"]; ?>' title="Edit truck driver profile " data-fullname='<?php echo $value["fullname"]; ?>'
                        data-phone='<?php echo $value["phone"]; ?>' data-idtruckdriver='<?php echo $value["idtruckdriver"]; ?>'
                        data-idbranch='<?php echo $value["idbranch"]; ?>' data-branch='<?php  $idbranch = $value["idbranch"];
        echo $branches[$idbranch]["branch"];  ?>'>
                        <i class="glyphicon glyphicon-edit"></i>
                      </a>
                    </div>

                  </td>
                </tr>
                <?php
}?>
            </tbody>
          </table>
        </div>
      </div>
      <!-- Branches -->
      <div class="w3-container w3-margin-bottom w3-bottombar" id="branches">
        <div class="table-responsive">
          <table class="table table-bordered branchestable table-striped dt-responsive">
            <caption class="  w3-large text-bold">Branches
              <a href="" class="fa fa-plus btn-primary btn" title="Add a new branch" data-target="#addbranchmodal" data-toggle="modal"
                role="button"></a>
              <a href="" class="fa fa-trash btn-danger btn" title="Delete all branches" data-target="#deleteallbranchesmodal" data-toggle="modal"
                role="button"></a>
            </caption>
            <thead class="w3-amber text-uppercase">
              <tr>
                <th>Name</th>
                <th>District</th>
                <th>Region</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody class="w3-text-yellow">


              <?php 
            $tbody='';
                foreach ($branches as $key => $branch) {
                  $idbranch = $branch['idbranch'];
                  $district = $branch['district'];
                  $branchname = $branch['branch'];
                  $region = $branch['region'];
                  $iddistrict=$branch['iddistrict'];
                  $idregion=$branch['idregion'];
$tbody .= <<<T
                  <tr data-idbranch='$idbranch' data-iddistrict='$iddistrict' data-idregion='$idregion'>
                    <td>$branchname</td>
                    <td>$district</td>
                    <td>$region</td>
                    <td class="">
                    <div>
              <a href="" class='btn-danger w3-btn w3-text-yellow col-xs-6' data-toggle='modal' data-target='#deletebranchmodal'
              data-idbranch='$idbranch' data-idcompany='$idcompany'
              data-branch='$branchname'
              title="Delete">
              <i class="glyphicon glyphicon-trash"></i>
            </a>
            <a href="" class='w3-teal w3-btn w3-text-yellow col-xs-6' role="button" data-toggle='modal' data-target='#editbranchmodal'
              data-idbranch='$idbranch' title="Edit branch profile " data-idcompany='$idcompany' data-branch='$branchname'
              data-district='$district'
              data-region='$region' data-iddistrict='$iddistrict'
              data-idregion='$idregion'>
              <i class="glyphicon glyphicon-edit"></i>
            </a>
            </div>
            </td>
</tr>
T;
                }
                echo $tbody;
                ?>

            </tbody>
          </table>
        </div>
      </div>

      <div class="w3-container w3-margin-bottom w3-bottombar" id="livedriverstatus">
        <div class="table-responsive">
          <table class="table table-bordered livedriverstatustable table-striped dt-responsive">
            <caption class="  w3-large text-bold">Live Driver Status

            </caption>
            <thead class="w3-amber text-uppercase">
              <tr>
                <th>Name</th>
                <th>Branch</th>
                <th>Currently At</th>
                <!-- <th>In District</th> -->
              </tr>
            </thead>

            <tbody class="w3-text-yellow">
              <?php 
foreach ($drivers as $key => $value) {
  $idtruckdriver = $value['idtruckdriver'];
  $fullname = $value['fullname'];
  $idbranch = $value['idbranch'];
  $branch = ($branches[$idbranch])['branch'];
  echo <<<L
<tr data-idtruckdriver="$idtruckdriver"> 
<td>$fullname</td>
<td>$branch</td>
<td>N/A</td>
</tr>
L;
}
?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="w3-container w3-margin-bottom w3-bottombar" id="jobstatus">
        <div class="table-responsive">
          <table class="table table-bordered jobstatustable table-striped dt-responsive">
            <caption class="  w3-large text-bold">Tow Requests</caption>
            <thead class="w3-amber text-uppercase">
              <tr>
                <th>Client</th>
                <th>Vehicle No.</th>
                <th>Phone No.</th>
                <th>
                  <span class="ion ion-location"></span> Location</th>
                <th>Driver Assigned</th>
                <th title="Estimated Time of Arrival">
                  <span class="ion ion-ios-speedometer"></span> ETA </th>
                <th>Status</th>
                <th>Client Feedback</th>
                <th>Driver Feedback</th>
              </tr>
            </thead>
            <tbody class="w3-text-yellow">
            </tbody>
          </table>
        </div>

        <style>
          .tether-element {
            display: block
          }
        </style>
      </div>

      <div class="w3-container w3-margin-bottom w3-bottombar hide" id="viewfeedback">
        <div class="table-responsive">
          <table class="table table-bordered feedbacktable table-striped dt-responsive">
            <caption class="  w3-large text-bold">Feedback</caption>
            <thead class="w3-amber text-uppercase">
              <tr>
                <th>Client</th>
                <th>Vehicle No.</th>
                <th>Phone No.</th>
                <th>Driver</th>
                <th>Client Feedback</th>
                <th>Driver Feedback</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody class="w3-text-yellow">
<?php 
// $stmt = $db->query("SELECT f.idtowrequest, u.name client, t.name truckdriver, f.vehicleno vehicleno, f.userfeedback, f.driverfeedback, u.phone phoneno from user u inner join towrequest f on u.iduser=f.iduser inner join truckdriver t on t.idtruckdriver=f.idtruckdriver ");
// $get = $stmt->fetchAll(PDO::FETCH_ASSOC);
// foreach ($get as $key => $value) {
//   $idtowrequest = $value['idtowrequest'];
//   $client = $value['client'];
//   $truckdriver = $value['truckdriver'];
//   $vehicleno = $value['vehicleno'];
//   $clientphone = $value['phoneno'];
//   $userfeedback = $value['userfeedback'];
//   $driverfeedback = $value['driverfeedback'];
//   echo <<<L
//   <tr data-idtowrequest="$idtowrequest">
//   <td>$client</td>
//   <td class="text-uppercase">$vehicleno</td>
//   <td>$clientphone</td>
//   <td>$truckdriver</td>
//   <td>$userfeedback</td>
//   <td>$driverfeedback</td>
//   <td> <button  class='btn-danger w3-btn w3-text-yellow col-xs-6' data-toggle='modal' data-target='#deletefeedbackmodal'
//    data-idtowrequest='$idtowrequest'
//   title="Delete">
//   <i class="glyphicon glyphicon-trash"></i>
// </button></td>
//   </tr>
// L;
?>
<?php //} ?>
            </tbody>
          </table>
        </div>
      </div>

      <div class="w3-container w3-margin-bottom w3-bottombar">
        <div class="mappanel" style="z-index: 5; ">
          <p class="w3-large hide">Districts</p>
        </div>
        <h4 class="w3-large text-bold   w3-padding-small">Map</h4>
        <div class="well row" id="maptop">
          <div class="eon-real-time-screen" id="map" style="height:950px">
          </div>
        </div>
      </div>

      <!-- // edittruckdriverrecord -->
      <div class="modal fade" id="editdrivermodal">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title">Edit Driver Record</h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="editdriverform" role="form" data-toggle="validator">
              <div class="modal-body">
                <div class="form-group">
                  <label class="form-control-label" for="fullname">
                    <span class="ion ion-person"></span> Full Name
                  </label>
                  <input type="text" class="form-control" name="fullname" pattern="^[A-Za-z]+[A-Za-z -]*$" required>
                  <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                  <div class="help-block with-errors"></div>
                </div>
                <div class="form-group">
                  <label class="form-control-label" for="username">
                    <span class="ion ion-social-freebsd-devil"></span> Username
                  </label>
                  <input type="text" required class="form-control" name="username" form="editdriverform" pattern="[A-Za-z0-9]+" title="">
                  <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                  <div class="help-block with-errors"></div>
                </div>
                <div class="form-group">
                  <label class="form-control-label" for="phone">
                    <span class="glyphicon glyphicon-phone"></span> Phone Number
                  </label>
                  <input type="text" class="form-control" required name="phone" placeholder="0245558900" pattern="^[0-9]{10}$">
                  <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                  <div class="help-block with-errors"></div>
                </div>
                <div class="form-group">
                  <label for="">Branch</label>
                  <select class="form-control text-capitalize" name="idbranch" required>
                    <?php 
                  
                      foreach($branches as $key=>$value){ 
                        $branch = $value['branch'];
                        $idbranch = $value['idbranch'];
                        echo "<option value='$idbranch'>$branch</option>";
                       } ?>
                  </select>
                  <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                  <div class="help-block with-errors"></div>
                </div>
                <div class="form-group input-password">
                  <label class="form-control-label" for="password">
                    <span class="ion ion-unlocked"></span> Password
                  </label>
                  <input type="password" required class="form-control input-password" name="password" id="edpassword" data-minlength="6" data-minlength-error="A minimum of six characters is required">

                  <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                  <div class="help-block with-errors"></div>
                </div>
                <div class="form-group">
                  <label for="">Confirm Password</label>
                  <input type="password" class="form-control input-password" data-match="#edpassword" data-match-error="The passwords must match"
                    name="repassword" aria-describedby="" required>

                  <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                  <div class="help-block with-errors"></div>
                </div>
              </div>
              <div class="modal-footer w3-margin">
                <button type="submit" class="btn btn-primary" form="editdriverform">Submit
                  <span class="fa fa-spinner fa-spin hidden"></span>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal" form="editdriverform">Close</button>
              </div>
              <input type="hidden" name="idtruckdriver">
            </form>

          </div>
        </div>
      </div>

      <!-- // deletetruckdriverrecord -->
      <div class="modal fade" id="deletetruckdriverrecordmodal">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Delete Driver Record</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="deletetruckdriverrecordform">
              <div class="modal-body">
              </div>
              <div class="modal-footer w3-margin">
                <button type="submit" class="btn btn-warning" form="deletetruckdriverrecordform">Okay
                  <span class="fa fa-warning"></span>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal" form="deletetruckdriverrecordform">Cancel</button>
              </div>
              <input type="hidden" name="idtruckdriver">
            </form>
          </div>
        </div>
      </div>

      <!-- //delete branch record -->
      <div class="modal fade" id="deletebranchmodal">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Delete Branch Record</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form id="deletebranchform">
              <div class="modal-body">
              </div>
              <div class="modal-footer w3-margin">
                <button type="submit" class="btn btn-warning" form="deletebranchform">Okay
                  <span class="fa fa-warning"></span>
                </button>
                <button type="button" class="btn btn-default" data-dismiss="modal" form="deletebranchform">Cancel</button>
              </div>
              <input type="hidden" name="idbranch">
            </form>
          </div>
        </div>
      </div>
      <nav class="navbar navbar-default" role="navigation">
      <a class="navbar-brand" href="#"><b>RVTIS
        <sup>&COPY;</sup></b>
      </a>
      <ul class="nav navbar-nav">
        <div class="panel panel-default text-bold text-center well" style="font-family: 'Cinzel Decorative'; font-weight:900">
          <div class="panel-body" style="line-height: 3.5rem;">
            A Project Work Submitted to the Department of Computer Science and Informatics, University of Energy and Natural Resources,
            In partial fulfilment of the requirements of the degree of BACHELOR OF SCIENCE IN INFORMATION TECHNOLOGY SCHOOL
            OF SCIENCE, May, 2018
          </div>
          <div class="panel-footer">
            <p> Developed By: Adzorlolo Hayford, Appiah-Kubi Eric Clinton & Gyan Shadrack.</p>
            <p>Supervised By: PROF.Adekoya Felix Adebayo, Mr. Agyemang Baffour Kwame and Mrs. Faiza Umar Bawah </p>
          </div>
        </div>
      </ul>
    </nav>
      
      <script>
        //# sourceURL=company.js

        var jobstatustable;
        var branchestable;
        var driverstable;
        var feedbacktable;

        var livedriverstatustable;
        var drivermarkers = {}
        var driverinfowins = {}
        var jobpool = {}
        var clientpool = {}
        var oms
        var map
        var pubnub
        var userinfowins = {}
        var usermarkers = {}
        var users = {}
        var idcompany = `<?php echo $idcompany; ?>`
        var icons = {
          truckdriveronline: 'assets/svg/towtruckdriveronline.svg',
          truckdriveroffline: 'assets/svg/towtruckdriveroffline.svg',
          car: 'assets/svg/car.svg',
          brokencar: "assets/grunticon-loader/png/car-breakdown.png"
        }
        var currentlocation = {
          lat: 7.350576666666666,
          lng: -2.341095
        };
        var directionsService;
        var directionsDisplay;
        var iconpath = {
          png: {
            towtruck: 'assets/png/tow-truck.png',
            towtruck2: 'assets/png/tow-truck-2.png',
            flag: 'assets/png/flag.png',
            carbreakdown: "assets/png/car-breakdown.png"
          },
          svg: {
            car: 'assets/svg/car.svg',
            towtruck: "assets/svg/towtruck.svg"
          }
        }
        var data = {
          username: `<?php echo $username; ?>`,
          name: `<?php echo $name; ?>`,
          phone: `<?php echo $phone; ?>`,
          who: 'company',
          currentlocation: currentlocation,
          currentaddress: '',
          statesetcount: 0,
          uuid: '',
          tid: idcompany,
          district: '',
          region: '',
          addressobject: {}
        }
        var notificationgranted = false
        var regions = JSON.parse(`<?php echo json_encode($regions); ?>`);
        var districts = JSON.parse(`<?php echo json_encode($districts); ?>`);
        var drivers = JSON.parse(`<?php echo json_encode($drivers); ?>`)
        var branches = JSON.parse(`<?php echo json_encode($branches); ?>`)
        var tether = `<?php echo $tether?true:''; ?>`
        var page = `<?php echo $page; ?>`
        var passwordreset = `<?php echo $passwordreset?'true':''; ?>`
        var isNewRegistration = `<?php echo $newregistration? 'true': '';?>`
        $(document).ready(function functionName()
        {
          $.notifyDefaults({
            delay: 0,
            newest_on_top: true, placement: { align: 'center' }, animate: {
              enter: 'animated bounceIn',
              exit: 'animated bounceOut'
            }
          })

          Notification.requestPermission().then(function (notiperm)
          {
            if (notiperm === "granted")
            {
              notificationgranted = true;
              spawnNotification("Welcome to RVTIS©", iconpath.png.towtruck2, "RVTIS©");
            }
          });

          $('i[class*="grump-icon"] svg').addClass("media-bottom");

          $('#editdrivermodal').on('show.bs.modal', function (event)
          {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var username = button.data('username'); // Extract info from data-* attributes
            var fullname = button.data('fullname');
            var phone = button.data('phone');
            var idtruckdriver = button.data('idtruckdriver')
            var idbranch = button.data('idbranch')
            var password = drivers[idtruckdriver]['password']
            var modal = $(this)
            modal.find('input[name="username"]').val(username);
            modal.find('input[name="fullname"]').val(fullname);
            modal.find('input[name="password"]').val(password);
            modal.find('input[name="repassword"]').val(password);
            modal.find('input[name="phone"]').val(phone);
            modal.find('input[name="idtruckdriver"]').val(idtruckdriver);
            modal.find('select[name="idbranch"]').find(`option[value="${idbranch}"]`).attr('selected', true)
            modal.find('form').validator('validate')
          });

          $('#editdriverform').validator().on('submit', function name(event)
          {
            if (event.isDefaultPrevented())
            {
              return false
            }
            var form = $(this);
            form.validator('validate')
            $.post('edit-driver.php', form.serialize())
              .done(function name(result)
              {
                $('.modal').modal('hide')
                try
                {
                  var json = JSON.parse(result)
                  if (!json.success)
                  {
                    $.notify(json.reason, { type: 'danger', placement: { align: 'center' } })
                  } else
                  {
                    $.notify('Truck driver record edited', { type: 'success', placement: { align: 'center' } })
                    var data = json.data
                    var username = data.username
                    var fullname = data.fullname
                    var phone = data.phone
                    var idtruckdriver = data.idtruckdriver
                    var idbranch = data.idbranch
                    var row = driverstable.row('[data-idtruckdriver="' + idtruckdriver + '"]');
                    var datanode = row.data();
                    datanode[0] = fullname;
                    datanode[1] = username;
                    datanode[2] = phone;
                    datanode[3] = data.branch
                    var node = $(row.node()).attr('data-idtruckdriver', idtruckdriver)
                    var btns = node.find('a').attr('data-username', username).attr('data-idtruckdriver', idtruckdriver)
                      .attr('data-idbranch', idbranch)
                      .attr('data-phone', phone)
                      .attr('data-fullname', fullname)
                    driverstable.row(row).data(datanode).draw(false);
                    row.invalidate()
                    drivers[`${idtruckdriver}`] = data
                    //node.select()
                  }
                } catch (error)
                {
                }
              })
              .fail(function name(error) { });
            event.preventDefault();
          });

          $('#deletetruckdriverrecordmodal').on('show.bs.modal', function name(event)
          {
            var button = $(event.relatedTarget);
            var fullname = button.data('fullname');
            var username = button.data('username');
            var idtruckdriver = button.data('idtruckdriver');
            var modal = $(this);
            modal.find('.modal-body').html('<span class="text-bold w3-large text-capitalize"> Delete ' + fullname + '?</span>');
            modal.find('input[name="username"]').val(username);
            modal.find('input[name="idtruckdriver"]').val(idtruckdriver);
          });

          $('#deletetruckdriverrecordform').on('submit', function name(event)
          {
            var form = $(this);
            var username = form.find('input[name="username"]').val();
            var idtruckdriver = form.find('input[name="idtruckdriver"]').val();
            $.post('deletetruckdriver.php', form.serialize())
              .done(function name(result)
              {
                $('.modal').modal('hide')
                try
                {
                  var json = JSON.parse(result)
                  if (!json.success)
                  {
                    $.notify(json.reason, { type: 'danger', placement: { align: 'center' } })
                  } else
                  {
                    var idtruckdriver = json.idtruckdriver
                    $.notify('Truck driver record deleted', { type: 'success', placement: { align: 'center' } })
                    driverstable.row('[data-idtruckdriver="' + idtruckdriver + '"]').remove().draw(false);
                    drivers[idtruckdriver].deleted = true
                  }
                } catch (error)
                {
                }
              })
              .fail(function name(error) { });
            event.preventDefault();
          });

          $("a[data-target='#anonymousloginmodal']").on('click', function (e) { $('.modal').modal('hide') })

          $("#anonymousloginform").validator().on('submit', function (e)
          {
            if (e.isDefaultPrevented())
            {
              return false
            }
            var phone = $(this).find('input[name="phone"]').val()
            // $.ajax({
            //       type: "post",
            //       data: $(this).serialize(),
            //       url: "user.php",
            //       success: function (response) {
            //         $('.modal').modal('hide')
            //         try {
            //           var json = JSON.parse(response)
            //           if (!json.success) {
            //             $.notify(json.reason, { type: 'danger', placement: { align: 'center' } })
            //           } else if (json.success) {
            //             $.notify('Welcome to RVTIS<sup>&copy;</sup>', { type: 'success', placement: { align: 'center' } })
            //             setTimeout(() => {
            //               $.notifyClose()
            //               href="user.php?phone="+phone
            //             }, 3000)
            //           }
            //         } catch (error) {

            //         }
            //       }
            //     });
            //     e.preventDefault()
          });

          $.extend(true, $.fn.dataTable.defaults, {
            processing: true,
            scrollY: 200,
            deferRender: true,
            scroller: true,
            select: true,
            //"dom": 'lrtip',
            "language": {
              processing: true,
              "emptyTable": "No data is available ",
              search: "_INPUT_",
              searchPlaceholder: "Search..."
            },
            //       buttons: {
            //     buttons: [
            //         'copy',
            //         {
            //           extend: 'pdf',
            //             //text: 'My button',
            //             action: function ( dt ) {
            //               $(dt.table().header()).find('th:last-child').addClass('hide')
            //             }
            //         }
            //     ]
            // }
          });
          $('.rating').rate({
            max_value: 6,
            step_size: 0.5,
          });

          if ($('#passwordreset').val() == "1")
          {
            $.notify(`Your are using a temporal password. Please set a new password. <div class='w3-center w3-margin'><button class='btn btn-primary  btn-newpassword w3-margin-right'>OK</button><button class='btn btn-default' onclick=';(function (args) {
            $.notifyClose()
          })()'>Close</button></div>`, { type: 'warning', delay: 0, allow_dismiss: true, z_index: 1060, placement: { align: 'center' } });
          }

          if (isNewRegistration == 'true')
          {
            $.notify(`Welcome to RVTIS<sup>&copy;</sup>`,
              {
                z_index: 1060, type: 'success', placement: { align: 'center' }
              })
          }
          if (tether)
          {
            new Tether({
              element: $('.mappanel'),
              target: $('#map'),
              attachment: 'top left',
              targetAttachment: 'top left',
              offset: '0 -10px'
            });
          }
        })

        $(document).ready(function ()
        {
          $('#delete-all-drivers-form').on('submit', function (e)
          {
            $.ajax({
              type: "get",
              url: "delete-all-drivers.php?idcompany=<?php echo $idcompany; ?>",
              success: function (response)
              {
                $('.modal').modal('hide')
                try
                {
                  var json = JSON.parse(response)
                  if (!json.success)
                  {
                    $.notify('An error occured', { type: 'danger', placement: { align: 'center' } })
                  } else if (json.success)
                  {
                    $.notify('Records deleted succesfully!', { type: 'success', placement: { align: 'center' } })
                    driverstable.clear().draw()
                  }
                } catch (error)
                {

                }
              }
            });
            e.preventDefault()
          });

          $('.dropdown-toggle').dropdownHover({ delay: 300 });

          // $('.branchestable').on('click', "tr", function functionName() {
          //   var idbranch = $(this).attr('data-idbranch');
          //   $("#editbranchform").find("[name='idbranch']").val(idbranch);
          // })

          /*$(document).on('change', 'select[name="district"]', function functionName() {
            var $this = $(this);
            var form = $this.parents('form');
            if(form.attr('id')=='editbranchform' || form.attr('id')=='addbranchform')
           form.find('input[name="name"]').val($this.get(0).selectedOptions[0].text + ' Branch');
          })*/

          $("#editbranchmodal").on('show.bs.modal', function functionName(event)
          {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var branch = button.data('branch'); // Extract info from data-* attributes
            var district = button.data('district');
            var region = button.data('region')
            var idregion = button.data('idregion')
            var iddistrict = button.data('iddistrict')
            var idbranch = button.data('idbranch')
            var modal = $(this)
            modal.find('input[name="name"]').val(branch);
            modal.find('select[name="region"]').find(`option[value='${idregion}']`).attr('selected', true).trigger('change')
            modal.find('select[name="district"]').find(`option[value='${iddistrict}']`).attr('selected', true)
            modal.find('input[name="idbranch"]').val(idbranch);
            modal.find('form').validator('validate')
          })

          $("#addbranchmodal").on('show.bs.modal', function functionName()
          {
            var $this = $(this);
            // $this.find('#addbranchform select[name=district]').trigger('change')
            // var form = $this.find('form').validator('validate')
          })

          $("#addbranchform").validator().on('submit', function (e)
          {
            if (e.isDefaultPrevented())
            {
              return false
            }
            var form = $(this)
            $.ajax({
              type: "post",
              url: "addbranch.php",
              data: form.serialize(),
              success: function (response)
              {
                try
                {
                  $('.modal').modal('hide')
                  var json = JSON.parse(response);
                  if (!json.success)
                  {
                    $.notify(json.reason, { type: 'danger', placement: { align: 'center' } })
                  } else if (json.success)
                  {
                    var json = json.data[0]
                    var idbranch = json.idbranch
                    var branch = json.branch
                    var region = json.region
                    var district = json.district
                    var iddistrict = json.iddistrict
                    var idregion = json.idregion
                    var idcompany = json.idcompany
                    var datanode = [branch, district, region, `<div>
                    
                    <a href="" class='btn-danger w3-btn w3-text-yellow col-xs-6' data-toggle='modal' data-target='#deletebranchmodal'
                    data-idbranch='${idbranch}' data-idcompany='${idcompany}'
                    data-branch='${branch}'
                    title="Delete">
                    <i class="glyphicon glyphicon-trash"></i>
                  </a>
                  <a href="" class='w3-teal w3-btn w3-text-yellow col-xs-6' role="button" data-toggle='modal' data-target='#editbranchmodal'
                    data-idbranch='${idbranch}' title="Edit branch profile " data-idcompany='${idcompany}' data-branch="${branch}" data-district="${district}" data-region="${region}" data-iddistrict="${iddistrict}" data-idregion="${idregion}">
                    <i class="glyphicon glyphicon-edit"></i>
                  </a>
                  </div>`]
                    $.notify('Branch added succesfully!', { type: 'success', placement: { align: 'center' } })
                    var node = branchestable.row.add(datanode).draw(false).node()
                    $(node).attr('data-idcompany', json.idcompany).attr('data-iddistrict', json.iddistrict)
                      .attr('data-idbranch', idbranch)
                    $('select[name="idbranch"]').append(`<option value='${idbranch}'>${branch}</option>`)
                    $("select[name='idbranch']").each(function (index, element)
                    {
                      // element == this
                      $(element).sortSelect()
                    });
                    branchestable.rows('tr').select(false)
                    branchestable.rows(node).select()
                    branchestable.rows(node).invalidate()
                    $(".dataTables_scrollBody").scrollTo($(node))
                    branches[idbranch] = json
                  }
                } catch (error)
                {

                }
              }
            });
            e.preventDefault()
          });
          $('#deleteallbranchesform').on('submit', function (e)
          {
            $.ajax({
              type: "get",
              url: "deleteallbranches.php?idcompany=<?php echo $idcompany; ?>",
              success: function (response)
              {
                $('.modal').modal('hide')
                try
                {
                  var json = JSON.parse(response)
                  if (!json.success)
                  {
                    var $div = ('<div>')
                    $div.append(json.reason)
                    if ('drivers' in json)
                    {
                      var $ul = $('<ul>')
                      json.drivers.forEach(element =>
                      {
                        $ul.append(`<li>${element.name}</li>`)
                      });
                      $div.append($ul)
                    }
                    $.notify($div, { type: 'danger', placement: { align: 'center' } })
                  } else if (json.success)
                  {
                    $.notify('Record deleted succesfully!', { type: 'success', placement: { align: 'center' } })
                    $('[name="idbranch"]').find("option").remove()
                    $('[name="idbranch"]').sortSelect()
                    branchestable.clear().draw()
                  }
                } catch (error)
                {

                }
              }
            });
            e.preventDefault()
          });

          $('#deletebranchmodal').on('show.bs.modal', function functionName(event)
          {
            var button = $(event.relatedTarget);
            var idbranch = button.data('idbranch');
            var branch = button.data('branch')
            var modal = $(this);
            modal.find('.modal-body').html('<span class="text-bold w3-large text-capitalize"> Delete ' + branch + '?</span>');
            modal.find('input[name="idbranch"]').val(idbranch);
          });

          $("#deletebranchform").on('submit', function (e)
          {
            $.ajax({
              type: "post",
              data: $(this).serialize(),
              url: "deletebranch.php",
              success: function (response)
              {
                $('.modal').modal('hide')
                try
                {
                  var json = JSON.parse(response)
                  if (!json.success)
                  {
                    var $div = $('<div>')
                    $div.append(json.reason)
                    if ('drivers' in json)
                    {
                      var $ul = $('<ul>')
                      json.drivers.forEach(element =>
                      {
                        $ul.append(`<li>${element.name}</li>`)
                      });
                      $div.append($ul)
                    }
                    $.notify($div.html(), { type: 'danger', placement: { align: 'center' } })
                  } else if (json.success)
                  {
                    $.notify('Record deleted succesfully!', { type: 'success', placement: { align: 'center' } })
                    $('[name="idbranch"]').find(`option[value="${json.idbranch}"]`).remove()
                    $('[name="idbranch"]').sortSelect()
                    branches[json.idbranch].deleted = true
                    branchestable.row(`[data-idbranch="${json.idbranch}"]`).remove()
                    branchestable.draw(false)
                  }
                } catch (error)
                {

                }
              }
            });
            e.preventDefault();
          });

          $(document).on('change', 'select[name=region]', function name()
          {
            $this = $(this);
            var sel = $(this)[0].selectedOptions[0]
            if (sel != undefined)
            {
              var idregion = sel.value;
              var $district = $this.parents('form').find('select[name=district]')
              $district.find('option').remove();
              $.each(districts, function (indexInArray, element)
              {
                if (element.idregion == idregion)
                {
                  $district.append(`<option value='${element.iddistrict}' >${element.longname}</option>`);
                }
              });
            }
          })

          $('select[name=region]').trigger('change');

          $("#editprofileForm").validator().on('submit', function functionName(evt)
          {
            if (evt.isDefaultPrevented())
            {
              return false;
            }
            $.post('editprofile.php?who=<?php echo $who; ?>&username=<?php echo $username; ?>', $(this).serialize(), function functionName(ret)
            {
              $.notify("Profile edited succesfully!", { type: success, allow_dismis: false })
            })
          })

          $('form[data-toggle="validator"]').validator({
            feedback: {
              error: 'glyphicon glyphicon-warning-sign',
              success: 'glyphicon glyphicon-check'
            },
            disable: true,
            focus: true
          })


          $("#newPasswordForm").validator().on('submit', function functionName(evt)
          {
            if (evt.isDefaultPrevented())
            {
              return false;
            }
            $.post('newpassword.php', $(this).serialize(), function functionName(ret)
            {
              var json;
              try
              {
                json = JSON.parse(ret);
                if (!json.success)
                {
                  $.notify('The passwords do not match', { type: 'danger' })
                } else if (json.success)
                {
                  $("#newpassword").modal('hide');
                  $.notify('Password changed successfully!', { type: 'success' })
                  $("#createpassword").addClass('hide');
                }
              } catch (err)
              {
              }
            })
            evt.preventDefault();
          })

          $(document).on('click', '.btn-newpassword', function name(args)
          {
            $.notifyClose();
            $("#newpassword").modal('show');
          })
          if (page != 'index.php' && passwordreset)
          {
            $.notify("You are using a temporal password. Please set a new password. <br><button class='btn btn-warning btn-sm btn-newpassword m3'>OK</button>", { type: 'warning', delay: 0, allow_dismiss: true });
          }



          $('#adddriverform').validator().on('submit', function functionName(e)
          {
            if (e.isDefaultPrevented())
            {
              return false
            }
            var form = $(this)
            $.post('add-driver.php', form.serialize(), function (data)
            {
              try
              {
                var json = JSON.parse(data)

                if (json.success)
                {
                  var driver = json.data
                  $('.modal').modal('hide')
                  $.notify('Driver added succesfully.', {
                    type: 'success', placement: { align: 'center' }
                  })
                  var idtruckdriver = json.idtruckdriver
                  var datanode = [driver.fullname, driver.username, driver.phone, driver.branch, `<a href="" class='btn-danger w3-btn w3-text-yellow col-xs-6' data-toggle='modal' data-target='#deletetruckdriverrecordmodal'
            data-phone='${driver.phone}'
                        data-username='${driver.username}' data-idtruckdriver='${driver.idtruckdriver}'
                        data-fullname='${driver.fullname}'
                        title="Delete">
                        <i class="glyphicon glyphicon-trash"></i>
                      </a>
                      <a href="" class='w3-teal w3-btn w3-text-yellow col-xs-6' role="button" data-toggle='modal' data-target='#recordmodal'
                        data-username='${driver.username}' title="Edit truck driver profile " data-fullname='${driver.fullname}'
                        data-phone='${driver.phone}' data-idtruckdriver='${driver.idtruckdriver}'>
                        <i class="glyphicon glyphicon-edit"></i>
                      </a>`]
                  drivers[`${driver.idtruckdriver}`] = driver
                  var node = $(driverstable.row.add(datanode).draw(false).node())
                  node.attr('data-idtruckdriver', driver.idtruckdriver)
                  driverstable.rows(node).invalidate()
                  driverstable.rows('tr').select(false)
                  driverstable.rows(node).select()
                  $(".dataTables_scrollBody").scrollTo($(node))
                } else
                {
                  $('.modal').modal('hide')
                  $.notify(json.reason, {
                    type: 'danger', placement: { align: 'center' }
                  })
                }
              } catch (error)
              {

              }
            })
            e.preventDefault();
          })



          $('#editbranchform').validator().on('submit', function functionName(e)
          {
            if (e.isDefaultPrevented())
            {
              return false
            }
            $.post("editbranch.php", $(this).serialize(),
              function (response, textStatus, jqXHR)
              {
                try
                {
                  var json = JSON.parse(response)
                  if (json.success)
                  {
                    $('.modal').modal('hide')
                    $.notify('Branch edited succesfully.', {
                      type: 'success', placement: { align: 'center' }
                    })
                    var data = [json.branch, json.district, json.region, `<div><a href="" class='btn-danger w3-btn w3-text-yellow col-xs-6' data-toggle='modal' data-target='#deletebranchmodal'
                        data-idbranch='${json.idbranch}  ' data-idcompany='${json.idcompany}'
                        data-branch='${json.branch}'
                        title="Delete">
                        <i class="glyphicon glyphicon-trash"></i>
                      </a>
                      <a href="" class='w3-teal w3-btn w3-text-yellow col-xs-6' role="button" data-toggle='modal' data-target='#editbranchmodal'
                        data-idbranch='${json.idbranch}' title="Edit branch profile " data-idcompany='${json.idcompany}'>
                        <i class="glyphicon glyphicon-edit"></i>
                      </a></div>`]
                    var node = branchestable.row(`[data-idbranch=${json.idbranch}]`).data(data).draw(false).node()
                    $(node).attr('data-idcompany', json.idcompany).attr('iddistrict', json.iddistrict)
                    $('select[name="idbranch"]').find(`option[value=${json.idbranch}]`).text(json.branch)
                    $('select[name="idbranch"]').sortSelect()
                  } else
                  {
                    $('.modal').modal('hide')
                    $.notify('An unknown error occured.', {
                      type: 'danger', placement: { align: center }
                    })
                  }
                } catch (error)
                {

                }
              });
            e.preventDefault()
          })

        });

        function initMap()
        {
          var currentlocation = {
            lat: 7.350576666666666,
            lng: -2.341095
          };
          map = new google.maps.Map(
            document.getElementById("map"), {
              center: new google.maps.LatLng(currentlocation.lat, currentlocation.lng),
              zoom: 6,
              mapTypeId: google.maps.MapTypeId.ROADMAP
            });
          map.panTo(new google.maps.LatLng(currentlocation.lat, currentlocation.lng))
          map.addListener('zoom_changed', function (event)
          {
            // for (const key in drivermarkers) {
            //   if (drivermarkers.hasOwnProperty(key)) {
            //     var marker = drivermarkers[key];
            //     marker.map_changed();
            //   }
            // }
          });
          map.addListener('map_changed', function (event)
          {
            // for (const key in drivermarkers) {
            //   if (drivermarkers.hasOwnProperty(key)) {
            //     var marker = drivermarkers[key];
            //     marker.map_changed();
            //   }
            // }
          });
          // directionsService = new google.maps.DirectionsService();
          // directionsDisplay = new google.maps.DirectionsRenderer();
          // //directionsDisplay.setMap(map);
          loadScripts();
        }

        function getOnlineTowTruckDrivers()
        {
          return new Promise((resolve, reject) =>
          {
            pubnub.hereNow({
              channels: ['rvtis-truckdriver'],
              includeState: true
            },
              function (s, r)
              {
                //console.log(status, response);
                if (s.error == false)
                {
                  var channel = r.channels['rvtis-truckdriver']
                  var occupants = channel.occupants
                  if (channel.occupancy > 0)
                  {
                    resolve(occupants)
                    // filter criteria 1: MUST HAVE STATE
                    // var stateful = occupants.filter(driver => {
                    //   return typeof driver.state !== 'undefined'
                    // });
                    // if (stateful.length > 0) {
                    //   // filter criteria 2: MUST BE A COMPANY DRIVER
                    //   var towtruckdrivers = stateful.filter(driver => {
                    //     return driver.state.tid in drivers
                    //   });
                    //  
                    // }
                  } else
                  {
                    reject("No company towtruck driver is online")
                  }
                } reject("An error occured while determining the company's tow trucks online")
              });
          });
        }

        function drawPath(coordinates, username)
        {
          new google.maps.Polyline({
            path: coordinates,
            geodesic: true,
            strokeColor: '#BDBB41',
            strokeOpacity: 1.0,
            strokeWeight: 2,
            icons: [{
              icon: destinationSymbol,
              offset: '0%'
            }, {
              icon: sourceSymbol,
              offset: '100%'
            }],
            map: map
          });
        }

        function drawDrivingRoute(start, end)
        {
          var request = {
            origin: start,
            destination: end,
            travelMode: 'DRIVING'
          };
          directionsService.route(request, function (result, status)
          {
            if (status == 'OK')
            {
              directionsDisplay.setDirections(result);
            }
          });
        }


        function showHidePopover(timeout = 7000, content = "")
        {
          this.attr("data-content", `<span class='text-danger'>${content}</span>`);
          this.popover("show");
          setTimeout(() =>
          {
            this.popover("hide");
          }, timeout);
        }

        getAllMessages = function (timetoken)
        {
          pubnub.history({
            channel: 'pubnub-company',
            stringifiedTimeToken: true, // false is the default
            start: timetoken // start time token to fetch
          },
            function (status, response)
            {
              var msgs = response.messages;
              var start = response.startTimeToken;
              var end = response.endTimeToken;
              // if msgs were retrieved, do something useful with them
              if (msgs != undefined && msgs.length > 0)
              {
                console.log(msgs.length);
                console.log("start : " + start);
                console.log("end : " + end);
              }
              // if 100 msgs were retrieved, there might be more; call history again
              if (msgs.length == 100)
              {
                getAllMessages(start);
              }

            }
          );
        }

        function initPubnub()
        {

          if ("PubNub" in window)
          {
            // data.uuid = data.username + data.phone
            pubnub = new PubNub({
              publishKey: 'pub-c-cb902de7-4d32-42f1-b755-09518db50855',
              subscribeKey: 'sub-c-1adc0c20-01bf-11e8-91aa-36923a88c219',
              uuid: data.phone + data.username
            });
            data.uuid = pubnub.getUUID()
            // filter
            // pubnub.setFilterExpression("ctid=="+idcompany);
            // pubnub listener
            pubnub.addListener({
              presence: function (presenceEvent)
              {
                //console.log('presence event came in:');
                var uuid = presenceEvent.uuid;
                var action = presenceEvent.action;
                var channel = presenceEvent.subscribedChannel
                var state = presenceEvent.state;

                // truckdriver action
                switch (action)
                {
                  case 'leave':
                    if (channel == 'rvtis-truckdriver-pnpres')
                    {
                      if (typeof state !== 'undefined' && typeof google !== 'undefined' && state.tid in drivers)
                      {
                        updateTruckDriverState(state, 'offline')
                      }
                    }
                    break;

                  case 'timeout':
                    break;

                  case 'join':
                    break;
                  case 'state-change':
                    if (channel == 'rvtis-truckdriver-pnpres')
                    {
                      if (typeof state !== 'undefined' && typeof google !== 'undefined' && state.tid in drivers)
                      {
                        updateTruckDriverState(state, 'online')
                      }
                    }
                    break;

                  default:
                    break;
                }

                // user action
                switch (action)
                {
                  case 'leave':
                    if (channel == 'rvtis-user-pnpres')
                    {
                      if (typeof state !== 'undefined' && state.tid in users)
                      {
                      }
                    }
                    break;

                  case 'timeout':
                    break;

                  case 'join':
                    break;
                  case 'state-change':
                    if (channel == 'rvtis-user-pnpres')
                    {
                      if (typeof state !== 'undefined' && state.tid in users)
                      {
                      }
                    }
                    break;

                  default:
                    break;
                }
              },

              message: function (m)
              {
                var publisher = m.publisher;
                var message = m.message;
                var channel = m.channel;
                var timetoken = m.timetoken;

                if ('action' in message)
                {
                  var target = message.target;
                  var subject = message.subject
                  if (subject == 'towrequest') {
                      var clientname = target.name
                      var address = target.currentaddress
                      var district = target.district
                      var vehicleno = target.vehicleno.toLowerCase();
                      var location = target.currentlocation
                      var phone = target.phone
                      var username = target.username
                      var vehiclenoexits = false;
                      $.each(jobpool, function (indexInArray, valueOfElement) { 
                         var job = valueOfElement;
                         var invehicleno = valueOfElement.clientdata.vehicleno;
                         if(invehicleno == vehicleno){
                           vehiclenoexits = true;
                           var reason='<span class="text-capitalize">' +window.data.name+' </span> says: Your tow request could not be  processed because a pending request exists with the same vehicle number';
                          pubnub.publish({
                            channel: 'rvtis-user',
                            message: { action: true, subject: 'towrequestresponse', target: { requestserved: false, notice: reason } },
                            storeInHistory: true,
                            ttl: 24,
                            meta: { tid: valueOfElement.clientdata.tid }

                          }, (s, r) =>
                            {
                              if (s.error)
                              {
                                switch (s.category)
                                {
                                  case "PNNetworkIssuesCategory":
                                    // var body = encodeURIComponent(message)
                                    // $.notify(`A network error occured whiles sending a response to the client`, { type: 'danger', placement: { align: 'center' } })
                                    console.log('network error');
                                    break;
                                  default:
                                    break;
                                }
                              } 
                            });
                            return false;
                         }
                       
                      });
                      if (vehiclenoexits) {
                        return;
                      }
                      var tid = target.tid
                      var clientdata = {
                        name: clientname,
                        currentaddress: address,
                        district: district,
                        vehicleno: vehicleno,
                        username: username,
                        phone: phone,
                        currentlocation: location,
                        tid: tid
                      }
                     
                      $.notifyClose();
                      $.notify('<div class="text-bold">Tow request from <span class="text-capitalize">' + clientname + '</span><br><span>Phone Number: ' + phone + '</span><br><span>Vehicle Number: <span class="text-uppercase">' + vehicleno + '</span><br></span><span>Location: ' + address + '</span></div>', { type: 'info', z_index: 1060, placement: { align: 'center' } })
                      getOnlineTowTruckDrivers()
                        .then(function (drivers)
                        {
                          var driver = getNearestTruckDriver(drivers, clientdata.currentlocation)

                          processTowRequest({ driverdata: driver.state, clientdata: clientdata, receivetimetoken: timetoken })

                        }).catch(function (error)
                        {
                          console.log(error)
                          $.notify(`<span class="text-bold">The current tow request could not be dispatched because there are no truck drivers available to serve a tow request at this time.</span>`, { type: 'danger', placement: { align: 'center' } })
                          var message = `Hi, ${clientdata.name}.<br> Your request for tow service couldn't be processed because there are currently no tow truck drivers available to serve your request. You may try again later. or call the company on <a href="tel:${window.data.phone}">${window.data.phone}</a> for assistance.`
                          delete clientpool[clientdata.tid]
                          pubnub.publish({
                            channel: 'rvtis-user',
                            message: { action: true, subject: 'towrequestresponse', target: { requestserved: false, notice: message } },
                            storeInHistory: true,
                            ttl: 24,
                            meta: { tid: clientdata.tid }

                          }, (s, r) =>
                            {
                              if (s.error)
                              {
                                switch (s.category)
                                {
                                  case "PNNetworkIssuesCategory":
                                    var body = encodeURIComponent(message)
                                    $.notify(`Sending notice of receipt of tow request to customer failed. Please check your network connection or try sending <a href="javascript:sendSMS(${body},${clientdata.phone})">internet SMS or <a href="sms:${clientdata.phone}?body=${body}"> local SMS </a>)`, { type: 'danger', placement: { align: 'center' } })
                                    break;
                                  default:
                                    break;
                                }
                              } else
                              {
                              
                                $.notify(`<span class="text-bold">The customer <span class="text-capitalize">(${clientdata.name})</span> has been notified. </span>`, { type: 'success', placement: { align: 'center' } })
                              }
                            });
                        })
                  } else if(subject=='towrequestfeedback'){
                    if (target.who == 'truckdriver') {
                      $.notify(`<b>Tow job assigned to <span class="text-capitalize">${target.data.truckdriverdata.name}</span> complete. <br>Tow Truck Driver's Feedback: ${target.feedback}</b>`, { type: 'warning' });
                          jobpool[target.idtowrequest].drivercomplete = true
                          if (jobpool[target.idtowrequest].drivercomplete && jobpool[target.idtowrequest].clientcomplete)
                          {
                            jobpool[target.idtowrequest].complete = true
                          }
                          // append to jobstatus datatable
                          // todo:

                          
                          var node = jobstatustable.row(`[data-idtowrequest='${target.idtowrequest}']`);
                            (node.data())[6] = 'complete';
                            node.data()[8]= target.feedback;
                            jobstatustable.rows('tr').invalidate()
                            node.draw();
                          $(".jobstatustable").parent(".dataTables_scrollBody").scrollTo($(node.node()));
                    } else {
                      $.notify(`<b>Tow service requested by <span class="text-capitalize">${target.data.clientdata.name} </span>complete. <br>Client's Feedback:<span> ${target.feedback}</span></b>`, { type: 'warning', icon:'fa fa-check' });
                      var node = jobstatustable.row(`[data-idtowrequest='${target.idtowrequest}']`);
                            (node.data())[6] = 'complete';
                            node.data()[7]= target.feedback;
                            jobstatustable.rows('tr').invalidate()
                            node.draw();
                          $(".jobstatustable").parent(".dataTables_scrollBody").scrollTo($(node.node()));

                          jobpool[target.idtowrequest].clientcomplete = true
                          if (jobpool[target.idtowrequest].drivercomplete && jobpool[target.idtowrequest].clientcomplete)
                          {
                            jobpool[target.idtowrequest].complete = true
                            delete jobpool[target.idtowrequest];
                          }
                    }
                  }
                }
              },

              status: function (statusEvent)
              {
                //console.log(statusEvent);
                switch (statusEvent.category)
                {
                  case "PNConnectedCategory":
                    reverseGeocode(data.currentlocation).then((address) =>
                    {
                      window.data.currentaddress = address.formatted_address;
                      data.addressobject = address
                      data.district = address.administrative_area_level_2
                      data.region = address.administrative_area_level_1
                      window.data.statesetcount++;
                      pubnub.setState({
                        state: window.data,
                        channels: ['rvtis-company']
                      }, (status, response) =>
                        {
                          console.log(status, response)
                        });
                    })


                    getOnlineTowTruckDrivers().then((drivers) =>
                    {
                      $.each(drivers, function (indexInArray, driver)
                      {
                        var state = driver.state
                        if (typeof state !== 'undefined' && typeof google !== 'undefined' && state.tid in window.drivers)
                        {
                          updateTruckDriverState(state, 'online')
                        }
                      });
                    }).catch((err) =>
                    {

                    });
                    break;
                  case "PNNetworkDownCategory":
                    console.log('network down');
                    break;
                  case "PNNetworkUpCategory":
                    console.log('network online');
                    subscribe();
                    break;
                  case "PNReconnectedCategory":
                    console.log('reconnected');
                    break;
                  case "PNTimeoutCategory":
                    console.log('timedout whiles attempting to connect');
                    break;
                  case "PNMalformedResponseCategory":
                    console.log('Json error')
                    break;
                  default:
                    break;
                }
              }
            });

            subscribe();
            reverseGeocode(data.currentlocation)
              .then(function (address)
              {
                watchid = navigator.geolocation.watchPosition(function success(position)
                {
                  data.currentlocation.lat = position.coords.latitude;
                  data.currentlocation.lng = position.coords.longitude;
                  reverseGeocode({
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                  })
                    .then(function (address)
                    {
                      window.data.currentaddress = address.formatted_address;
                      data.addressobject = address
                      data.district = address.administrative_area_level_2
                      data.region = address.administrative_area_level_1
                      //$('.location').html(`<span class="w3-tiny text-bold">@ ${data.currentaddress}</span>`)
                      //data.locationchanged = true;
                      data.statesetcount++
                      pubnub.setState({
                        state: window.data,
                        channels: ['rvtis-company']
                      }, (s, r) =>
                        {
                          console.log(s, r)
                        });
                    }).catch(function (reason)
                    {
                      console.log(reason)
                    });
                }, function error(params) { }, {
                    // timeout: 5 * 1000,
                    // maximumAge: 10000,
                    // enableHighAccuracy: true
                  });
              })
          }
        }

        function processTowRequest(data)
        {
          var clientdata = data.clientdata
          var receivetimetoken = data.receivetimetoken
          var driver = data.driverdata
          var standardtimetoken = parsePubNubTimeToken(receivetimetoken).standardtimetoken
          var receivedatetime = moment(standardtimetoken).format('YYYY-MM-DD hh:mm:ss.ssss')
          var dispatchdatetime = receivedatetime

          $.getJSON(`towrequest.php?idtruckdriver=${driver.tid}&iduser=${clientdata.tid}&timereceived=${receivedatetime}&timedispatched=${dispatchdatetime}&vehicleno=${clientdata.vehicleno}`,
            function (response, textStatus, jqXHR)
            {
              try
              {
                if (response.success)
                {
                  var res = response.data
                  var ref = res.idtowrequest
                  clientpool[res.iduser] = jobpool[res.idtowrequest] = { truckdriverdata: driver, clientdata: clientdata, complete: res.complete ? true : false, idtowrequest: res.idtowrequest }

                  // update tow request count
                  $(".total-tow-request .label").html(Object.keys(jobpool).length)
                  users[clientdata.tid] = clientdata

                  // update user marker and infowindow 
                  if (typeof google !== 'undefined')
                  {
                    updateUserState(clientdata, 'online')
                  }

                  // 3. Dispatch optimum driver
                  var notice = `<b><u>Tow Dispatch:</u></b><br>You have been assigned to provide a towing service to a customer.<br><b>Customer Details</b>:</br><span class="text-bold text-capitalize">Name: ${clientdata.name}<br>Phone: ${clientdata.phone}<br>Vehicle Number: <span class="text-uppercase">${clientdata.vehicleno}</span><br>Location: ${clientdata.currentaddress} <p class="text-bold get-directions w3-text-amber" style="cursor: pointer "><u>Click here to get directions</u></p>`

                  pubnub.publish({
                    channel: 'rvtis-truckdriver',
                    message: {
                      action: true, subject: 'towdispatch', target: {
                        clientdata: jobpool[res.idtowrequest].clientdata,
                        truckdriverdata: jobpool[res.idtowrequest].clientdata,
                        idtowrequest: res.idtowrequest,
                        tid: window.data.tid,
                        notice: notice
                      }
                    },
                    storeInHistory: true,
                    ttl: 24,
                    meta: { tid: driver.tid }
                  }, (s, r) =>
                    {
                      if (s.error)
                      {
                        switch (s.category)
                        {
                          case "PNNetworkIssuesCategory":
                            var body = encodeURIComponent(`Please attend to customer with vehicle no. ${clientdata.vehicleno} and phone no. ${clientdata.phone}. Ref no.: ${ref}`)
                            $.notify(`Dispatching failed. Please check your network connection or try sending <a class="text-blue" href="javascript:sendSMS(${body},${driver.phone})">internet SMS</a> or <a class="text-blue" href="sms:${driver.phone}?body=${body}"> local SMS </a>)`, { type: 'danger', placement: { align: 'center' } })
                            break;
                          default:
                            break;
                        }
                      } else
                      {
                        $.notify(`<span class="text-bold">Tow request dispatched to <span class="text-capitalize">${driver.name} </span>(the nearest tow truck driver available)</span>`, { type: 'success', placement: { align: 'center' } });

                        // notify customer
                        getETA(clientdata.currentlocation, driver.currentlocation).then((ETA) =>
                        {
                          var duration = ETA[0].duration
                          var distance = ETA[0].distance
                          var from = ETA[0].from
                          var to = ETA[0].to

                          // append to jobstatus datatable
                          // todo:
                          var nodedata = [clientdata.name, clientdata.vehicleno, clientdata.phone, clientdata.currentaddress, driver.name, `${distance}= ${duration}`, 'ongoing', '', '']
                          var node = jobstatustable.row.add(nodedata).draw(false).node()
                          $(node).find('td:eq(1)').addClass('text-uppercase');
                          $(node).attr('data-idtowrequest', res.idtowrequest)
                          jobstatustable.rows('tr').select(false)
                          jobstatustable.rows(node).select()
                          jobstatustable.rows(node).invalidate()
                          $(".dataTables_scrollBody").scrollTo($(node))

                          var message = `Your request for tow service has been received. Please note that a tow truck has been dispatched to you and must get to your location in an estimated ${duration}. You may call the driver on <a href="tel:${driver.phone}">${driver.phone}</a> or call the company on <a href="tel:${window.data.phone}">${window.data.phone}</a>. Ref no.: ${ref}`

                          pubnub.publish({
                            channel: 'rvtis-user',
                            message: { action: true, subject: 'towrequestresponse', target: { data: jobpool[res.idtowrequest], requestserved: true, notice: message } },
                            storeInHistory: true,
                            ttl: 24,
                            meta: { tid: clientdata.tid }

                          }, (s, r) =>
                            {
                              if (s.error)
                              {
                                switch (s.category)
                                {
                                  case "PNNetworkIssuesCategory":
                                    var body = encodeURIComponent(message)
                                    $.notify(`Sending notice of receipt of tow request to customer failed. Please check your network connection or try sending <a href="javascript:sendSMS(${body},${clientdata.phone})">internet SMS or <a href="sms:${clientdata.phone}?body=${body}"> local SMS </a>)`, { type: 'danger', placement: { align: 'center' } })
                                    break;
                                  default:
                                    break;
                                }
                              } else
                              {
                                $.notify(`<span class="text-bold">A notice of receipt of tow request has been sent to customer,<span class="text-capitalize"> ${clientdata.name}</span></span>`, { type: 'info', placement: { align: 'center' } })
                              }
                            });

                        }).catch((err) =>
                        {
                          console.log(err)
                          $.notify('An error occured', { type: 'danger' })
                        });
                      }
                    });
                } else
                {
                  $.notify('An error occured', { type: 'danger' })
                }
              } catch (error)
              {
                console.log(error)
                $.notify('An error occured', { type: 'danger' })
              }
            }).fail(function (error)
            {
              console.log(error);
              $.notify('An error occured', { type: 'danger' })
            })
        }

        function updateUserState(state, status)
        {
          if (typeof state != 'undefined')
          {
            var tid = state.tid
            var infowindow
            var marker
            var lblcont
            var content
            var infoWindowContent = { online: `<small class=' w3-text-black font-bold text-capitalize'><i class="fa fa-dot-circle-o text-success"></i> ${state.name}<br>${state.currentaddress}<br> </small>`, offline: `<small class='w3-text-black font-bold text-capitalize '><i class="fa fa-dot-circle-o text-danger"></i> ${state.name}<br>${state.currentaddress}<br> </small>` }

            var labelContent = { online: `<span class=" text-black font-bold text-capitalize"><i class="fa fa-dot-circle-o text-success"></i> ${state.name}</span>`, offline: `<span class="text-black font-bold text-capitalize"><i class="fa fa-dot-circle-o text-danger"></i> ${state.name}</span>` }



            if (status == 'online')
            {
              lblcont = labelContent.online
              content = infoWindowContent.online
            } else
            {
              content = infoWindowContent.offline
              lblcont = labelContent.offline
            }

            // update marker position and info window content
            if (tid in usermarkers && users[tid].vechicleno == state.vehicleno)
            {
              marker = usermarkers[tid]
              infowindow = userinfowins[tid]

              marker.animateTo(new google.maps.LatLng(state.currentlocation.lat, state.currentlocation.lng));
              marker.set('labelContent', lblcont)

              infowindow.setContent(content);
            } else
            {
              infowindow = new google.maps.InfoWindow(
                {
                  content: content,
                  position: new google.maps.LatLng(state.currentlocation)
                });
              marker = new MarkerWithLabel({
                animation: google.maps.Animation.DROP,
                draggable: true,
                labelAnchor: new google.maps.Point(25, 0),
                labelClass: "label w3-white", // the CSS class for the label
                labelContent: lblcont,
                position: state.currentlocation,
                labelStyle: {
                  opacity: 0.5
                },
                icon: {
                  url: icons.car,
                  scaledSize: new google.maps.Size(23, 32)  // makes SVG icons work in IE
                }
              });
              oms.addMarker(marker, function (e)
              {
                infowindow.open(map, marker);
                map.panTo(marker.getPosition());
              });

              usermarkers[tid] = marker
              userinfowins[tid] = infowindow
            }
          }
        }

        function updateTruckDriverState(state, status)
        {
          if (typeof state != 'undefined')
          {
            var tid = state.tid
            var infowindow
            var marker
            var lblcont
            var content
            var infoWindowContent = { online: `<small class=' w3-text-black font-bold text-capitalize'><i class="fa fa-dot-circle-o text-success"></i> ${state.name}<br>${state.currentaddress}<br> </small>`, offline: `<small class='w3-text-black font-bold text-capitalize '><i class="fa fa-dot-circle-o text-danger"></i> ${state.name}<br>${state.currentaddress}<br> </small>` }

            var labelContent = { online: `<span class=" text-black font-bold text-capitalize"><i class="fa fa-dot-circle-o text-success"></i> ${state.name}</span>`, offline: `<span class="text-black font-bold text-capitalize"><i class="fa fa-dot-circle-o text-danger"></i> ${state.name}</span>` }



            if (status == 'online')
            {
              lblcont = labelContent.online
              content = infoWindowContent.online
            } else
            {
              content = infoWindowContent.offline
              lblcont = labelContent.offline
            }

            // update marker position and info window content
            if (tid in drivermarkers)
            {
              marker = drivermarkers[tid]
              infowindow = driverinfowins[tid]

              marker.animateTo(new google.maps.LatLng(state.currentlocation.lat, state.currentlocation.lng));
              marker.set('labelContent', lblcont)

              infowindow.setContent(content);
            } else
            {
              infowindow = new google.maps.InfoWindow(
                {
                  content: content,
                  position: new google.maps.LatLng(state.currentlocation)
                });
              marker = new MarkerWithLabel({
                animation: google.maps.Animation.DROP,
                draggable: true,
                labelAnchor: new google.maps.Point(25, 0),
                labelClass: "label w3-white", // the CSS class for the label
                labelContent: lblcont,
                position: state.currentlocation,
                labelStyle: {
                  opacity: 0.5
                },
                icon: {
                  url: icons.truckdriveronline,
                  scaledSize: new google.maps.Size(23, 32)  // makes SVG icons work in IE
                }
              });
              oms.addMarker(marker, function (e)
              {
                infowindow.open(map, marker);
                map.panTo(marker.getPosition());
              });

              drivermarkers[tid] = marker
              driverinfowins[tid] = infowindow
            }
            var datanode = livedriverstatustable.row(`[data-idtruckdriver="${tid}"]`).data()
            datanode[2] = state.currentaddress
            livedriverstatustable.row(`[data-idtruckdriver="${tid}"]`).data(datanode)
            livedriverstatustable.draw(false)
          }
        }

        function subscribe()
        {
          pubnub.subscribe({
            channels: ['rvtis-company', 'rvtis-truckdriver-pnpres', 'rvtis-user-pnpres']
          }, function (s, r)
            {
              console.log(s, r)
            })
        }


        function getNearestTruckDriver(drivers, clientlocation)
        {
          function compareDistance(driver1, driver2)
          {
            if (getDistance(clientlocation, driver1.currentlocation) > getDistance(clientlocation,
              driver2.currentlocation))
            {
              return 1
            }
            else
            {
              return -1
            }
          }

          if (drivers.length > 0)
          {
            drivers.sort(compareDistance);
            return drivers[0]
          }
        }


        function getTowTruckDriversAvailable()
        {
          return new Promise(function (resolve, reject)
          {
            getOnlineTowTruckDrivers().then(function (drivers)
            {
              // filter criteria : MUST BE AVAILABLE TO RECEIVE JOB
              var available = drivers.filter(driver =>
              {
                return driver.state.available
              });
              if (available.length > 0)
              {
                resolve(available)
              } else
              {
                reject("There are currently no truck drivers available to serve a tow request")
              }
            }).catch(function (error)
            {
              reject(error)
            })
          });
        }

        function sendSMS(message, phone_number)
        {
          var endpoint = 'https://smsgateway.me/api/v4/message/send'
          var token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhZG1pbiIsImlhdCI6MTUyNjA2OTI2OCwiZXhwIjo0MTAyNDQ0ODAwLCJ1aWQiOjQ1OTU3LCJyb2xlcyI6WyJST0xFX1VTRVIiXX0.9YUK41Yb_ghaErrbBsfU7Pj_J57jrfOum-5AJI-yBOo'
          var device_id = 85174
          var phone_number = phone_number
          var message = message
          var data = [{ device_id: device_id, phone_number: phone_number, message: message, send_at: '+1 minutes' }]
          $.ajax({
            contentType: 'application/json',
            headers: {
              Authorization: token,
            },
            data: JSON.stringify(data),
            dataType: 'json',
            success: function (data)
            {
              $.notify('SMS sent succesfully', { type: 'success', placement: { align: 'center' } })
            },
            error: function (e)
            {
              $.notify('Sending sms failed due to network error', { type: 'danger', placement: { align: 'center' } })
            },
            type: 'POST',
            url: endpoint
          });
        }

        var load = (function ()
        {
          // Function which returns a function: https://davidwalsh.name/javascript-functions
          function _load(tag, onload)
          {
            return function (url)
            {
              // This promise will be used by Promise.all to determine success or failure
              return new Promise(function (resolve, reject)
              {
                var element = document.createElement(tag);
                var parent = 'body';
                var attr = 'src';

                // Important success and error for the promise
                element.onload = function ()
                {
                  resolve(url);
                };
                element.onerror = function ()
                {
                  reject(url);
                };

                // Need to set different attributes depending on tag type
                switch (tag)
                {
                  case 'script':
                    element.async = true;
                    break;
                  case 'link':
                    element.type = 'text/css';
                    element.rel = 'stylesheet';
                    attr = 'href';
                    parent = 'head';
                }

                // Inject into document to kick off loading
                element[attr] = url;
                document[parent].appendChild(element);
              });
            };
          }

          return {
            css: _load('link'),
            js: _load('script'),
            img: _load('img')
          }
        })();

        function loadScripts()
        {
          // Usage:  Load different file types with one callback
          Promise.all([
            load.js('assets/js/markerwithlabel.js'),
            load.js('assets/js/oms.js'),
            load.js('assets/js/markerAnimate.js'),
            load.js('assets/js/SlidingMarker.min.js')
          ]).then(function ()
          {
            SlidingMarker.initializeGlobally();
            oms = new OverlappingMarkerSpiderfier(map, {
              markersWontMove: true,
              markersWontHide: true,
              basicFormatEvents: true
            });
            console.log('Everything has loaded!');
          }).catch(function ()
          {
            console.log('Oh no, epic failure!');
          });
        }

        function getETA(clientlocation, driverlocation)
        {
          return new Promise(function (resolve, reject)
          {
            var service = new google.maps.DistanceMatrixService();

            service.getDistanceMatrix({
              origins: [driverlocation],
              destinations: [clientlocation],
              travelMode: 'DRIVING',
              unitSystem: google.maps.UnitSystem.METRIC
            }, function (response, status)
              {
                if (status == 'OK')
                {
                  var origins = response.originAddresses;
                  var destinations = response.destinationAddresses;
                  var etas = []
                  for (var i = 0; i < origins.length; i++)
                  {
                    var results = response.rows[i].elements;
                    for (var j = 0; j < results.length; j++)
                    {
                      var element = results[j];
                      var distance = element.distance.text;
                      var duration = element.duration.text;
                      var from = origins[i];
                      var to = destinations[j];
                    }
                    etas.push({ distance: distance, duration: duration, from: from, to: to })
                  }
                  resolve(etas)
                } else
                {
                  reject(status)
                }
              });
          });
        }

        function setStateCounter()
        {
          if (window.data.statesetcount == 1000)
          {
            window.data.statesetcount = 0;
          }
          window.data.statesetcount++
        }

        function getFormObj(formId)
        {
          var formObj = {};
          var inputs = $('#' + formId).serializeArray();
          $.each(inputs, function (i, input)
          {
            formObj[input.name] = input.value;
          });
          return formObj;
        }

        function reverseGeocode(latlng)
        {
          if (typeof latlng == "string")
          {
            url = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${latlng}&key=AIzaSyCqjMdWdNss5BgNzucIYPBBMGkKs7Y5dlI`
          } else
          {
            lat = latlng.lat;
            lng = latlng.lng;
            url = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lng}&key=AIzaSyCqjMdWdNss5BgNzucIYPBBMGkKs7Y5dlI`;
          }
          return new Promise(function (resolve, reject)
          {
            var request = new XMLHttpRequest();
            var method = 'GET';
            var async = true;
            request.open(method, url, async);
            request.onreadystatechange = function ()
            {
              if (request.readyState == 4)
              {
                if (request.status == 200)
                {
                  var results = JSON.parse(request.responseText).results;
                  var formatted_address_obj = {
                    street_address: '',
                    route: '',
                    administrative_area_level_1: '',
                    administrative_area_level_2: '',
                    locality: '',
                    neighborhood: '',
                    country: '',
                    sublocality: '',
                    formatted_address: results[0].formatted_address
                  }
                  $.each(results, function (indexInArray, valueOfElement)
                  {
                    if (valueOfElement.types.includes("street_address"))
                    {
                      formatted_address_obj.street_address = valueOfElement.formatted_address
                      formatted_address_obj.route = this.address_components[1].long_name
                    } else if (valueOfElement.types.includes("neighborhood"))
                    {
                      formatted_address_obj.neighborhood = valueOfElement.formatted_address
                    } else if (valueOfElement.types.includes("locality"))
                    {
                      formatted_address_obj.locality = valueOfElement.formatted_address
                    } else if (valueOfElement.types.includes("sublocality"))
                    {
                      formatted_address_obj.sublocality = valueOfElement.formatted_address
                    } else if (valueOfElement.types.includes("route"))
                    {
                      formatted_address_obj.route = valueOfElement.formatted_address
                    } else if (valueOfElement.types.includes("country"))
                    {
                      formatted_address_obj.country = valueOfElement.formatted_address
                    } else if (valueOfElement.types.includes("administrative_area_level_1"))
                    {
                      formatted_address_obj.administrative_area_level_1 = valueOfElement.formatted_address
                    } else if (valueOfElement.types.includes("administrative_area_level_2"))
                    {
                      formatted_address_obj.administrative_area_level_2 = valueOfElement.formatted_address
                    }
                  });
                  resolve(formatted_address_obj);
                } else
                {
                  reject(request.status);
                }
              }
            };
            request.send();
          });
        }

        function geocode(address)
        {
          url = `https://maps.googleapis.com/maps/api/geocode/json?address=${address}&key=AIzaSyCqjMdWdNss5BgNzucIYPBBMGkKs7Y5dlI`
          return new Promise(function (resolve, reject)
          {
            var request = new XMLHttpRequest();
            var method = 'GET';
            var async = true;
            request.open(method, url, async);
            request.onreadystatechange = function ()
            {
              if (request.readyState == 4)
              {
                if (request.status == 200)
                {
                  var data = JSON.parse(request.responseText);
                  resolve(data);
                } else
                {
                  reject(request.status);
                }
              }
            };
            request.send();
          });
        }

        function getDistance(latlng1, latlng2)
        {
          return parseFloat(google.maps.geometry.spherical.computeDistanceBetween(new google
            .maps.LatLng(latlng1.lat, latlng1.lng), new google.maps.LatLng(
              latlng2.lat, latlng2.lng)) / 1000).toFixed(2);
        }

        function spawnNotification(theBody, theIcon, theTitle, data, tag, action)
        {
          var options = {
            body: theBody,
            icon: theIcon,
            vibrate: [200, 100, 200, 100, 200, 100, 400],
            tag: tag || "welcome",
            // actions: action || [
            //   { "action": "yes", "title": "Yes", "icon": "images/yes.png" },
            //   { "action": "no", "title": "No", "icon": "images/no.png" }
            // ],
            data: data || {}
          }
          var n = new Notification(theTitle, options);
          setTimeout(n.close.bind(n), 5000);
          n.onclick = function functionName(e)
          {
            if (n.tag == 'towrequest')
            {

            }
          }
        }

        function getPosition()
        {
          var defaultPos = {
            lat: 7.350576666666666,
            lng: -2.341095
          }
          return new Promise(function (resolve, reject)
          {
            if ('geolocation' in navigator)
            {
              navigator.geolocation.getCurrentPosition(function (position)
              {
                var pos = {
                  lat: position.coords.latitude,
                  lng: position.coords.longitude
                }
                resolve(pos);
              }, function (error)
                {
                  //console.log('Default position used:', defaultPos);
                  //console.log(error);
                  resolve(defaultPos);
                },
                // {
                //     timeout: 5 * 1000,
                //     enableHighAccuracy: true
                //   }
              )
            }
          });
        }

        function sendSMS(message, phone_number)
        {
          var endpoint = 'https://smsgateway.me/api/v4/message/send'
          var token = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJhZG1pbiIsImlhdCI6MTUyNjA2OTI2OCwiZXhwIjo0MTAyNDQ0ODAwLCJ1aWQiOjQ1OTU3LCJyb2xlcyI6WyJST0xFX1VTRVIiXX0.9YUK41Yb_ghaErrbBsfU7Pj_J57jrfOum-5AJI-yBOo'
          var device_id = 85174
          var phone_number = phone_number
          var message = message
          var data = [{ device_id: device_id, phone_number: phone_number, message: message, send_at: '+1 minutes' }]
          $.ajax({
            contentType: 'application/json',
            headers: {
              Authorization: token,
            },
            data: JSON.stringify(data),
            dataType: 'json',
            success: function (data)
            {
              $('.modal').modal('hide')
              $.notify('Tow request sent succesfully', { type: 'success', placement: { align: 'center' } })
            },
            error: function (e)
            {
              $('.modal').modal('hide')
              $.notify('Sending sms failed due to network error', { type: 'danger', placement: { align: 'center' } })
            },
            type: 'POST',
            url: endpoint
          });
        }

        function parsePubNubTimeToken(pubnubtimetoken)
        {
          var standardtimetoken = pubnubtimetoken / 1e4
          return { date: new Date(standardtimetoken), standardtimetoken: standardtimetoken }
        }

        $(window).on('load', function (event)
        {
          initPubnub();

          $.fn.extend({
            showHidePopover: showHidePopover
          });

          // driver datatable
          driverstable = $(".driverstable").DataTable({
            "language": {
              "emptyTable": "No data is available ",
              search: "_INPUT_",
              searchPlaceholder: "Search..."
            },
            "createdRow": function (row, data, index)
            {
              var driverstable = window.driverstable
              var username = data[1];
              // if(driverstable) {
              //   driverstable.row('.new-row').node().removeClass('.new-row')
              //   $(row).addClass('.new-row')
              //    }
              $(row).attr('data-username', username).addClass('text-bold text-capitalize');
            },
            "createdCell": function (td, cellData, rowData, row, col)
            {

            }
          });

          // branches datatable
          branchestable = $(".branchestable").DataTable({
            "language": {
              "emptyTable": "No data is available ",
              search: "_INPUT_",
              searchPlaceholder: "Search..."
            },
            "createdRow": function (row, data, index)
            {
              $(row).addClass('font-bold text-capitalize');
            },
            "createdCell": function (td, cellData, rowData, row, col)
            {

            }
          });

          // feeback datatable
          feedbacktable = $(".feedbacktable").DataTable({
            "language": {
              "emptyTable": "No data is available ",
              search: "_INPUT_",
              searchPlaceholder: "Search..."
            },
            "createdRow": function (row, data, index)
            {
              $(row).addClass('font-bold text-capitalize');
            },
            "createdCell": function (td, cellData, rowData, row, col)
            {

            }
          });


          // live status  datatable
          livedriverstatustable = $(".livedriverstatustable").DataTable({
            "language": {
              "emptyTable": "No data is available ",
              search: "_INPUT_",
              searchPlaceholder: "Search..."
            },
            "createdRow": function (row, data, index)
            {

              $(row).addClass('font-bold text-capitalize');
            },
            "createdCell": function (td, cellData, rowData, row, col)
            {
            }
          });

          // job status datatable
          jobstatustable = $(".jobstatustable").DataTable({
            "language": {
              "emptyTable": "No data is available ",
              search: "_INPUT_",
              searchPlaceholder: "Search..."
            },
            "createdRow": function (row, data, index)
            {

              $(row).addClass('font-bold text-capitalize');
            },
            "createdCell": function (td, cellData, rowData, row, col)
            {
            }
          });

          jQuery.fn.extend({
            sortSelect: function ()
            {
              var options = jQuery.makeArray(this.find('option'));

              var sorted = options.sort(function (a, b)
              {
                return (jQuery(a).text() > jQuery(b).text()) ? 1 : -1;
              });

              this.append(jQuery(sorted))
                .attr('selectedIndex', 0);
            }
          });
        });
      </script>
      <script src="https://maps.googleapis.com/maps/api/js?libraries=geometry&key=AIzaSyCqjMdWdNss5BgNzucIYPBBMGkKs7Y5dlI&callback=initMap"></script>
  </body>

  </html>