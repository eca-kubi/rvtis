<?php
ob_start();
$tether=true;
$idcompany=0;
include 'connect-sqlite.php';
include 'databasetables.php';
$secretword = 'tims-dope';
$username = 'anonymous';
$who = 'user';
$page='user.php';
$name='Anonymous';
$phone="";
$passwordreset = false;
$anonymous = false;
$tid='';
$idcompany="";
if (isset($_REQUEST['loggedin'])){
session_name('user');
session_set_cookie_params(time() + 3600 * 24 * 30, '/tims-alpha/');
session_start();
session_regenerate_id();
setcookie(session_name(), session_id(), time() + 3600 * 24 * 30, '/tims-alpha/');
if (isset($_SESSION['user'])) {
  $who = 'user';
  $username = $_SESSION['user'];
  $phone = $_SESSION['phone'];
  $name = $_SESSION['name'];
  $tid=$_SESSION['tid'];
  $stmt =$db->prepare('SELECT passwordreset from user where username=?');
  $stmt->execute([$username]);
  $row =$stmt->fetch();
  if($row){
    $passwordreset = boolval($row[0]);
  }
}
} else {
  if(isset($_REQUEST['phone'])){
    $phone = $_REQUEST['phone'];
  }
    session_name('anonymous');
    session_set_cookie_params(time() + 3600 * 24 * 30, '/tims-alpha/');
    session_start();
    session_regenerate_id();
    setcookie(session_name(), session_id(), time() + 3600 * 24 * 30, '/tims-alpha/');
  if (isset($_SESSION['user'])) {
    $who = 'user';
    $tid = $_SESSION['tid'];
    $anonymous=true;
    $phone = $_SESSION['phone'];
    $name = $_SESSION['name'];
    $username = $_SESSION['user'];
  } else {
    $anonymous=true;
    $ret= $db->query("SELECT * from user where phone = '$phone' and anonymous=1")->fetch();
    if($ret){
      $_SESSION['user'] = $ret['username'];
    $_SESSION['phone']=$ret['phone'];
    $_SESSION['name']='anonymous';
   $_SESSION['tid'] =$ret['iduser'] ;
    } else {
      $username = 'anonymous_'.$phone;
      $ret =$db->exec("INSERT into user (username,phone, anonymous) values ('$username','$phone', 1)");
      if($ret){
        $_SESSION['tid'] = $db->lastInsertID();
    $_SESSION['user'] = $username;
    $_SESSION['phone']=$phone;
    $_SESSION['name']='anonymous';
      } else {
        echo "<script>alert('An  error occured!'); href = 'sign-out.php?who=user&anonymous=true'
        </script>";
      }
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
    <title>RVTIS&copy; Client-
      <?php echo $username; ?>
    </title>
    <script src="assets/js/pace.js"></script>
    <script src="assets/js/jquery.js"></script>
    <script type="text/javascript">
      $.extend(window.Pace.options, { ajax: { trackMethods: ['GET', 'POST', 'DELETE', 'PUT', 'PATCH'], ignoreURLs: [/.*subscribe\/sub-c-1adc0c20-01bf-11e8-91aa-36923a88c219.*/] } }); </script>
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
    <link rel="stylesheet" href="assets/css/pace-theme-minimal-blue.css">
    <script src="assets/js/pubnub.4.20.3.js"></script>
    <script src="assets/jquery-ui/jquery-ui.js"></script>
    <script src="assets/js/jquery.easing.1.3.js"></script>
    <script src="assets/js/tether.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/bootstrap-notify.js"></script>
    <link rel="stylesheet" href="assets/AdminLTE/css/AdminLTE.css">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/w3.css">
    <link rel="stylesheet" href="assets/css/ionicons.css">
    <link rel="stylesheet" href="assets/css/font-awesome.css">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/jquery-ui/themes/cupertino/jquery-ui.min.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="assets/datatable.net/css/datatables.min.css">
    <link rel="stylesheet" href="assets/datatable.net/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="assets/datatable.net/responsive/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/offline-theme-slide.css">
    <link rel="stylesheet" href="assets/css/offline-language-english.css">
    <link rel="stylesheet" href="assets/css/offline-language-english-indicator.css">
    <link rel="stylesheet" href="assets/css/animate.css/animate.css">
    <link rel="stylesheet" href="assets/css/fonts.css">
    <script src="assets/js/offline.js"></script>
    <script type="text/javascript">
      var offlinenotif;
      var onlinenotif;
      Offline.on('down', function ()
      {
        'use strict';
        console.log('down');
        $(".btn[data-target='#sendrequestsmsmodal']").addClass("disabled");
        $("button#towrequestbtn").addClass('disabled');
        if (onlinenotif)
        {
          onlinenotif.close();
          onlinenotif = undefined;
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

      Offline.on('up', function ()
      {
        //console.log('up');
        'use strict';
        $(".btn[data-target='#sendrequestsmsmodal']").removeClass("disabled");
        if (typeof map === 'undefined')
        {
          $.getScript("https://maps.googleapis.com/maps/api/js?libraries=geometry&key=AIzaSyCqjMdWdNss5BgNzucIYPBBMGkKs7Y5dlI&callback=initMap", function ()
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
          offlinenotif = undefined;
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

    </script>
    <link rel="stylesheet" href="assets/css/spin.css">

  </head>

  <body>
    <input type="hidden" name="name" value='<?php echo $name; ?>'>
    <div>
      <nav class="navbar navbar-default m-b-0">
        <div class="container-fluid">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed w3-padding-24" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"
              aria-expanded="false">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar margin-bottom"></span>

            </button>

            <a class="navbar-brand text-bold text-fuchsia" href="#">
              <?php echo $username; ?> </a>

            <a href="#" class="fa fa-location-arrow location pad w3-padding-16"> </a>
          </div>

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
              <?php if($passwordreset){ ?>
              <li id="createpassword">
                <a href="" data-target="#newpassword" data-toggle="modal">
                  <span class="ion ion-lock-combination"></span> Create Password</a>
              </li>
              <?php } ?>
              <li>
                <a href="" data-target="#userfeedbackmodal" data-toggle="modal" class="fa fa-sticky-note"> Feedback</a>
              </li>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-phone"> Call Security Services</i>
                  <b class="caret"></b>
                </a>
                <ul class="dropdown-menu">
                  <li>
                    <a href="tel:192">
                      <span class="fa fa-fire-extinguisher"></span> Fireservice</a>
                  </li>
                  <li>
                    <a href="tel:191">
                      <span class="fa fa-id-badge"></span> Police</a>
                  </li>
                  <li>
                    <a href="tel:193">
                      <span class="fa fa-ambulance"></span> Ambulance</a>
                  </li>
                </ul>
              </li>
              <li>
                <a href="#">
                  <span class="fa fa-dollar"></span> Make Payment</a>
              </li>
              <?php if(!$anonymous) { ?>
              <li>
                <a href="" data-target="#editprofile" data-toggle="modal">
                  <span class="ion ion-edit"></span> Edit Profile</a>
              </li>
              <?php } ?>
              <?php if($anonymous){ ?>
              <li>
                <a href="" data-target="#userlogin" data-toggle="modal">
                  <span class="ion ion-android-open"></span> Sign In</a>
              </li>
              <?php }else { ?>
              <li>
                <a href="sign-out.php?who=user&username=<?php echo $username; ?>">
                  <span class="ion ion-locked"></span> Sign Out</a>
              </li>
              <?php } ?>
            </ul>
          </div>
          <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
      </nav>
      <div class="row  well">
        <div class="description  row">
          <div class="w3-padding-large company-div">
            <div id="company-list">
              <input class="search form-control input-sm" placeholder="Search company...">
              <button class="sort btn btn-primary w3-margin btn-xs" data-sort="name">Sort by name
                <i class="fa fa-arrow-up"></i>
              </button>
              <button class="sort btn btn-primary w3-margin btn-xs" data-sort="status">Sort by status
                <i class="fa fa-arrow-up"></i>
              </button>
              <span class="btn btn-primary btn-xs companylist-filter filtered w3-margin" title="Show all companies">
                <i class="fa fa-eye">

                </i>
                <span>Show all companies</span>
              </span>

              <ul class="list w3-panel" style="list-style: none">
              </ul>
            </div>

            <div class="hide">
              <!-- A template element is needed when list is empty, TODO: needs a better solution -->

            </div>
          </div>
          <div class="mappanel"></div>
          <div class="location-screen col-lg-12 w3-margin-top w3-bottombar" id="yourlocation">
            <h4>
              <span class="ion ion-android-map"> Your Location</span>
            </h4>
            <div id="map">
              <div class="center-block">
                <span class="ion ion-navigate"></span> Map</div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="mappanel"></div>



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



    <?php include 'modals.html'; ?>
    <script type="text/javascript">
      //# sourceURL=user.js
      var iduser = `<?php echo $tid; ?>`
      var passwordreset = `<?php echo $passwordreset? true: ''; ?>`
      var uuidstatus = {}
      var companies = JSON.parse(`<?php echo json_encode($companylist); ?>`)
      var drivermarkers = {}
      var driverinfowins = {}
      var map;
      var pubnub;
      var watchid;
      var locationlng;
      var locationlat;
      var towresponse = {};
      var myMarker;
      var myInfoWindow;
      //var driverstable;
      var currentlocation = {
        lat: 7.349246666666667,
        lng: -2.341495
      };
      var icons = {
        truckdriveronline: 'assets/svg/towtruckdriveronline.svg',
        truckdriveroffline: 'assets/svg/towtruckdriveroffline.svg',
        car: 'assets/svg/car.svg',
        brokencar: "assets/grunticon-loader/png/car-breakdown.png"
      }
      var jobpool = [];
      var companytable
      var htmlStrings = {
        online: `<span class='badge w3-green w3-xlarge'> <i class='fa fa-check'></i></span>`,
        offline: `<span class='warning w3-text-red w3-xlarge'> <i class='fa fa-warning'></i></span>`,
      }
      var oms;
      var companyList = {};
      var data = {
        username: '<?php echo $username; ?>',
        name: '<?php echo $name; ?>',
        phone: '<?php echo $phone; ?>',
        who: 'user',
        currentlocation: window.currentlocation,
        currentaddress: "Unknown Address",
        statesetcount: 0,
        district: '',
        region: '',
        uuid: '',
        tid: iduser
      }
      var job = {
      }
      var anonymous = "<?php echo $anonymous?'true':'';?>"

      var regions = JSON.parse(`<?php echo json_encode($regions); ?>`);
      var districts = JSON.parse(`<?php echo json_encode($districts); ?>`);
      var drivers = JSON.parse(`<?php echo json_encode($drivers); ?>`)
      var branches = JSON.parse(`<?php echo json_encode($branches); ?>`)
      var tether = `<?php echo $tether?true:''; ?>`
      var page = `<?php echo $page; ?>`
      var passwordreset = `<?php echo $passwordreset?'true':''; ?>`
      var isNewRegistration = `<?php echo $newregistration? 'true': '';?>`

      $(document).ready(function ()
      {
        $('input:not([name="username"]):not([name="password"]):not([name="vehicleno"])').addClass('text-capitalize');

        $(document).on('click', "button[data-target='#sendrequestsmsmodal']", function (event)
        {
          var $this = $(event.currentTarget)
          var parent = $this.parents('li')
          var phone = parent.find('.phoneno').attr('data-phone');
          var name = parent.find('.namespan').attr('data-name');
          var username = parent.find('.username').text();
          var form = $('#sendrequestsmsform')
          form.find('.companyname').val(name)
          form.find('.companyusername').val(username)
          form.find('.companyphone').val(phone)
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
        // $('#delete-all-drivers-form').on('submit', function (e)
        // {
        //   $.ajax({
        //     type: "get",
        //     url: "delete-all-drivers.php?idcompany=<?php echo $idcompany; ?>",
        //     success: function (response)
        //     {
        //       $('.modal').modal('hide')
        //       try
        //       {
        //         var json = JSON.parse(response)
        //         if (!json.success)
        //         {
        //           $.notify('An error occured', { type: 'danger', placement: { align: 'center' } })
        //         } else if (json.success)
        //         {
        //           $.notify('Records deleted succesfully!', { type: 'success', placement: { align: 'center' } })
        //           driverstable.clear().draw()
        //         }
        //       } catch (error)
        //       {

        //       }
        //     }
        //   });
        //   e.preventDefault()
        // });

        $('.dropdown-toggle').dropdownHover({ delay: 1000 });

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

        $(document).on('click', '.disabled', function (e)
        {
          e.preventDefault();
        });
        $("#userfeedbackmodal").on('show.bs.modal', function (e)
        {
          if (Object.keys(job).length > 0)
          {
            $("#userfeedbackmodal").find('[name="name"]').val(window.data.name)
            $("#userfeedbackmodal").find('[name="driver"]').val(job.data.truckdriverdata.name)
            $("#userfeedbackmodal").find('[name="phone"]').val(job.data.clientdata.phone)
            $("#userfeedbackmodal").find('[name="vehicleno"]').val(job.data.clientdata.vehicleno)
          } else
          {
            $.notify('No tow request at the moment', { type: 'warning', delay: 3000 });
            return false;
          }
        });

        $("#userfeedbackform").on('submit', function (e)
        {
          $('.modal').modal('hide');
          var formObj = getFormObj("userfeedbackform")
          job.complete = true
          pubnub.publish({
            channel: 'rvtis-company',
            message: {
              action: true, subject: 'towrequestfeedback', target: {
                data: job.data,
                feedback: formObj.feedback,
                idtowrequest: job.data.idtowrequest,
                who: 'user'
              }
            },
            storeInHistory: true,
            ttl: 24
          }, function (s, r)
            {
              if (s.error)
              {
                switch (s.category)
                {
                  case "PNNetworkIssuesCategory":
                    $.notify('A network error occured please try again.', { type: 'danger' })
                    break;
                  default:
                }
              } else
              {
                $.notify('Feeback sent succesfully', { type: 'success' })
                //delete job;
              }
            })
          e.preventDefault();
        });

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

        // $("#addbranchmodal").on('show.bs.modal', function functionName()
        // {
        //   var $this = $(this);
        //   // $this.find('#addbranchform select[name=district]').trigger('change')
        //   // var form = $this.find('form').validator('validate')
        // })

        // $("#addbranchform").validator().on('submit', function (e)
        // {
        //   if (e.isDefaultPrevented())
        //   {
        //     return false
        //   }
        //   var form = $(this)
        //   $.ajax({
        //     type: "post",
        //     url: "addbranch.php",
        //     data: form.serialize(),
        //     success: function (response)
        //     {
        //       try
        //       {
        //         $('.modal').modal('hide')
        //         var jsondata = JSON.parse(response);
        //         if (!jsondata.success)
        //         {
        //           $.notify(jsondata.reason, { type: 'danger', placement: { align: 'center' } })
        //         } else if (jsondata.success)
        //         {
        //           var json = json.data[0]
        //           var idbranch = json.idbranch
        //           var branch = json.branch
        //           var region = json.region
        //           var district = json.district
        //           var iddistrict = json.iddistrict
        //           var idregion = json.idregion
        //           var idcompany = json.idcompany
        //           var datanode = [branch, district, region, `<div>

        //             <a href="" class='btn-danger w3-btn w3-text-yellow col-xs-6' data-toggle='modal' data-target='#deletebranchmodal'
        //             data-idbranch='${idbranch}' data-idcompany='${idcompany}'
        //             data-branch='${branch}'
        //             title="Delete">
        //             <i class="glyphicon glyphicon-trash"></i>
        //           </a>
        //           <a href="" class='w3-teal w3-btn w3-text-yellow col-xs-6' role="button" data-toggle='modal' data-target='#editbranchmodal'
        //             data-idbranch='${idbranch}' title="Edit branch profile " data-idcompany='${idcompany}' data-branch="${branch}" data-district="${district}" data-region="${region}" data-iddistrict="${iddistrict}" data-idregion="${idregion}">
        //             <i class="glyphicon glyphicon-edit"></i>
        //           </a>
        //           </div>`]
        //           $.notify('Branch added succesfully!', { type: 'success', placement: { align: 'center' } })
        //           var node = branchestable.row.add(datanode).draw(false).node()
        //           $(node).attr('data-idcompany', json.idcompany).attr('data-iddistrict', json.iddistrict)
        //             .attr('data-idbranch', idbranch)
        //           $('select[name="idbranch"]').append(`<option value='${idbranch}'>${branch}</option>`)
        //           $("select[name='idbranch']").each(function (index, element)
        //           {
        //             // element == this
        //             $(element).sortSelect()
        //           });
        //           branchestable.rows('tr').select(false)
        //           branchestable.rows(node).select()
        //           branchestable.rows(node).invalidate()
        //           $(".dataTables_scrollBody").scrollTo($(node))
        //           branches[idbranch] = json
        //         }
        //       } catch (error)
        //       {

        //       }
        //     }
        //   });
        //   e.preventDefault()
        // });
        // $('#deleteallbranchesform').on('submit', function (e)
        // {
        //   $.ajax({
        //     type: "get",
        //     url: "deleteallbranches.php?idcompany=<?php echo $idcompany; ?>",
        //     success: function (response)
        //     {
        //       $('.modal').modal('hide')
        //       try
        //       {
        //         var json = JSON.parse(response)
        //         if (!json.success)
        //         {
        //           var $div = ('<div>')
        //           $div.append(json.reason)
        //           if ('drivers' in json)
        //           {
        //             var $ul = $('<ul>')
        //             json.drivers.forEach(element =>
        //             {
        //               $ul.append(`<li>${element.name}</li>`)
        //             });
        //             $div.append($ul)
        //           }
        //           $.notify($div, { type: 'danger', placement: { align: 'center' } })
        //         } else if (json.success)
        //         {
        //           $.notify('Record deleted succesfully!', { type: 'success', placement: { align: 'center' } })
        //           $('[name="idbranch"]').find("option").remove()
        //           $('[name="idbranch"]').sortSelect()
        //           branchestable.clear().draw()
        //         }
        //       } catch (error)
        //       {

        //       }
        //     }
        //   });
        //   e.preventDefault()
        // });

        // $('#deletebranchmodal').on('show.bs.modal', function functionName(event)
        // {
        //   var button = $(event.relatedTarget);
        //   var idbranch = button.data('idbranch');
        //   var branch = button.data('branch')
        //   var modal = $(this);
        //   modal.find('.modal-body').html('<span class="text-bold w3-large text-capitalize"> Delete ' + branch + '?</span>');
        //   modal.find('input[name="idbranch"]').val(idbranch);
        // });

        // $("#deletebranchform").on('submit', function (e)
        // {
        //   $.ajax({
        //     type: "post",
        //     data: $(this).serialize(),
        //     url: "deletebranch.php",
        //     success: function (response)
        //     {
        //       $('.modal').modal('hide')
        //       try
        //       {
        //         var json = JSON.parse(response)
        //         if (!json.success)
        //         {
        //           var $div = $('<div>')
        //           $div.append(json.reason)
        //           if ('drivers' in json)
        //           {
        //             var $ul = $('<ul>')
        //             json.drivers.forEach(element =>
        //             {
        //               $ul.append(`<li>${element.name}</li>`)
        //             });
        //             $div.append($ul)
        //           }
        //           $.notify($div.html(), { type: 'danger', placement: { align: 'center' } })
        //         } else if (json.success)
        //         {
        //           $.notify('Record deleted succesfully!', { type: 'success', placement: { align: 'center' } })
        //           $('[name="idbranch"]').find(`option[value="${json.idbranch}"]`).remove()
        //           $('[name="idbranch"]').sortSelect()
        //           branches[json.idbranch].deleted = true
        //           branchestable.row(`[data-idbranch="${json.idbranch}"]`).remove()
        //           branchestable.draw(false)
        //         }
        //       } catch (error)
        //       {

        //       }
        //     }
        //   });
        //   e.preventDefault();
        // });

        $(document).on('change', 'select[name=region]', function name()
        {
          var $this = $(this);
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

        $("#editprofileForm").validator().on('submit', function (evt)
        {
          var who = `<?php echo $who; ?>`;
          var idwho = `<?php echo 'id'.$who; ?>`;
          var anonymous = `<?php echo $anonymous?'true':''; ?>`;
          if (evt.isDefaultPrevented())
          {
            return false;
          }
          var name = $(this).find('[name="name"]').val();
          var phone = $(this).find('[name="phone"]').val();
          $.get(`editprofile.php?who=${who}&idwho=${idwho}&name=${name}&phone=${phone}&id=${window.data.tid}&anonymous=${anonymous ? true : ''}`,
            function (data, textStatus, jqXHR)
            {
              try
              {
                $('.modal').modal('hide');
                var json = JSON.parse(data);
                if (json.success)
                {
                  $.notify("Profile edited succesfully!", { type: 'success' });
                  setTimeout(() =>
                  {
                    window.location.reload(true);
                  }, 1000);
                } else
                {
                  $.notify(json.reason, { type: 'danger', delay: 5000 });
                }
              } catch (error)
              {
                $.notify("An error occured!", { type: 'danger', delay: 5000 });
              }
            }
          );
          evt.preventDefault();
        });

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

        $(document).on('click', '.btn-newpassword', function (args)
        {
          $.notifyClose();
          $("#newpassword").modal('show');
        })
        if (page != 'index.php' && passwordreset)
        {
          $.notify("You are using a temporal password. Please set a new password. <br><button class='btn btn-warning btn-sm btn-newpassword m3'>OK</button>", { type: 'warning', delay: 0, allow_dismiss: true });
        }

        // $('#adddriverform').validator().on('submit', function (e)
        // {
        //   if (e.isDefaultPrevented())
        //   {
        //     return false
        //   }
        //   var form = $(this)
        //   $.post('add-driver.php', form.serialize(), function (data)
        //   {
        //     try
        //     {
        //       var json = JSON.parse(data)

        //       if (json.success)
        //       {
        //         var driver = json.data
        //         $('.modal').modal('hide')
        //         $.notify('Driver added succesfully.', {
        //           type: 'success', placement: { align: 'center' }
        //         })
        //         var idtruckdriver = json.idtruckdriver
        //         var datanode = [driver.fullname, driver.username, driver.phone, driver.branch, `<a href="" class='btn-danger w3-btn w3-text-yellow col-xs-6' data-toggle='modal' data-target='#deletetruckdriverrecordmodal'
        //     data-phone='${driver.phone}'
        //                 data-username='${driver.username}' data-idtruckdriver='${driver.idtruckdriver}'
        //                 data-fullname='${driver.fullname}'
        //                 title="Delete">
        //                 <i class="glyphicon glyphicon-trash"></i>
        //               </a>
        //               <a href="" class='w3-teal w3-btn w3-text-yellow col-xs-6' role="button" data-toggle='modal' data-target='#recordmodal'
        //                 data-username='${driver.username}' title="Edit truck driver profile " data-fullname='${driver.fullname}'
        //                 data-phone='${driver.phone}' data-idtruckdriver='${driver.idtruckdriver}'>
        //                 <i class="glyphicon glyphicon-edit"></i>
        //               </a>`]
        //         drivers[`${driver.idtruckdriver}`] = driver
        //         var node = $(driverstable.row.add(datanode).draw(false).node())
        //         node.attr('data-idtruckdriver', driver.idtruckdriver)
        //         driverstable.rows(node).invalidate()
        //         driverstable.rows('tr').select(false)
        //         driverstable.rows(node).select()
        //         $(".dataTables_scrollBody").scrollTo($(node))
        //       } else
        //       {
        //         $('.modal').modal('hide')
        //         $.notify(json.reason, {
        //           type: 'danger', placement: { align: 'center' }
        //         })
        //       }
        //     } catch (error)
        //     {

        //     }
        //   })
        //   e.preventDefault();
        // })



        // $('#editbranchform').validator().on('submit', function functionName(e)
        // {
        //   if (e.isDefaultPrevented())
        //   {
        //     return false
        //   }
        //   $.post("editbranch.php", $(this).serialize(),
        //     function (response, textStatus, jqXHR)
        //     {
        //       try
        //       {
        //         var json = JSON.parse(response)
        //         if (json.success)
        //         {
        //           $('.modal').modal('hide')
        //           $.notify('Branch edited succesfully.', {
        //             type: 'success', placement: { align: 'center' }
        //           })
        //           var data = [json.branch, json.district, json.region, `<div><a href="" class='btn-danger w3-btn w3-text-yellow col-xs-6' data-toggle='modal' data-target='#deletebranchmodal'
        //                 data-idbranch='${json.idbranch}  ' data-idcompany='${json.idcompany}'
        //                 data-branch='${json.branch}'
        //                 title="Delete">
        //                 <i class="glyphicon glyphicon-trash"></i>
        //               </a>
        //               <a href="" class='w3-teal w3-btn w3-text-yellow col-xs-6' role="button" data-toggle='modal' data-target='#editbranchmodal'
        //                 data-idbranch='${json.idbranch}' title="Edit branch profile " data-idcompany='${json.idcompany}'>
        //                 <i class="glyphicon glyphicon-edit"></i>
        //               </a></div>`]
        //           var node = branchestable.row(`[data-idbranch=${json.idbranch}]`).data(data).draw(false).node()
        //           $(node).attr('data-idcompany', json.idcompany).attr('iddistrict', json.iddistrict)
        //           $('select[name="idbranch"]').find(`option[value=${json.idbranch}]`).text(json.branch)
        //           $('select[name="idbranch"]').sortSelect()
        //         } else
        //         {
        //           $('.modal').modal('hide')
        //           $.notify('An unknown error occured.', {
        //             type: 'danger', placement: { align: center }
        //           })
        //         }
        //       } catch (error)
        //       {

        //       }
        //     });
        //   e.preventDefault()
        // })

        $("a[data-target='#anonymousloginmodal']").on('click', function (e)
        {
          $('.modal').modal('hide')
        })

        $("#anonymousloginform").validator().on('submit', function (e)
        {
          if (e.isDefaultPrevented())
          {
            return false
          }
          var phone = $(this).find('input[name="phone"]').val()

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
          var tetr = new Tether({
            element: $('.mappanel'),
            target: $('#map'),
            attachment: 'top left',
            targetAttachment: 'top left',
            offset: '0 -10px'
          });
        }
      })

      var values = []
      $.notifyDefaults({
        delay: 0,
        newest_on_top: true, placement: { align: 'center' }, animate: {
          enter: 'animated bounceIn',
          exit: 'animated bounceOut'
        }
      })
      $.each(companies, function (indexInArray, element)
      {
        values.push({
          name: //html
            `<span class="text-capitalize namespan" data-name="${element.name}">${element.name}</span>`,
          phone: //html
            `<a href="tel:${element.phone}" data-phone="${element.phone}" style="cursor:pointer;"><i class="ion ion-ios-telephone phoneno" data-phone= "${element.phone}"></i> Call now: ${element.phone}</a>`,
          status: 'offline',
          username: element.username,
          distance: '--',
          idcompany: element.idcompany
        })
      });

      var list = {
        options: {
          item: //html
            `<li class="well well-sm">
                  <h5 class="font-bold name"></h5>
                  <p class="phone"></p>
                  <p class="currentaddress"></p>
                  <p><span class="district"></span></p>
                  <p><span class="region"></span></p>
                  <p class="text-capitalize"><span class="fa fa-circle text-danger"></span> <span class="status"></span></p>
    
                  <div class="row">
          <div class="col-xs-4">
            <button class="btn btn-xs btn-success w3-animate-bottom  text-uppercase" data-target='#sendtowrequestmodal' data-toggle='modal'
              id="towrequestbtn">
              <i class='ion ion-chatbubble'></i> Send Request</button>
            <p class="w3-tiny">Send your request to Company's dashboard </p>
          </div>
          <div class="col-xs-4">
        
            <button class="btn btn-xs  w3-animate-bottom  text-uppercase btn-success " data-target='#sendrequestsmsmodal' data-toggle='modal'>
              <i class='ion ion-android-mail'></i> Send Internet SMS</button>
            <p class="w3-tiny">Send your request via Internet SMS </p>
          </div>
          <div class="col-xs-4">
            <a class="btn btn-xs  w3-animate-bottom  text-uppercase btn-warning "
             href="sms:">
              <i class='ion ion-android-mail'></i> Send Local SMS</a>
            <p class="w3-tiny">Send your request via Local SMS </p></div>
        </div>
                  <p class="hide username"/>
                </li>`,
          valueNames: ['name', 'phone', 'status', 'username', 'distance', 'idcompany', 'region', 'district', 'currentaddress']
        },
        values: values
      };

      window.companyList = new List('company-list', list.options, list.values);

      $.each(list.values, function (indexInArray, value)
      {
        $("#company-list ul li").each(
          (idx, li) =>
          {
            var data = {
              status: 'offline',
              district: '',
              region: '',
              currentaddress: ''
            }
            $(li).attr('data-idcompany', list.values[idx].idcompany)
            updateCompanyStatus(data, list.values[idx].idcompany);
          }
        )
      });

      $(document).on('click', '.sort', function functionName()
      {
        var fa = $(this).find("i");
        var valueName = $(this).attr("data-sort");
        if (fa.hasClass("fa-arrow-up"))
        {
          fa.removeClass("fa-arrow-up");
          fa.addClass("fa-arrow-down");
          companyList.sort(valueName, {
            order: 'desc'
          });
        } else
        {
          fa.removeClass("fa-arrow-down");
          fa.addClass("fa-arrow-up");
          companyList.sort(valueName, {
            order: 'asc'
          });
        }
      })

      $('#company-list ul').on('click', 'li #towrequestbtn', function functionName(e)
      {
        if ($(this).hasClass('disabled'))
        {
          return false
        }
        var form = $("#sendtowrequestform")
        var companyusername = $(this).parents('li').find('.username').text();
        var companyname = $(this).parents('li').find('.name').text();
        var phone = $(this).parents('li').find('.phone').text();
        var idcompany = $(this).parents('li').attr(
          "data-idcompany");
        form.find(".companyusername").val(companyusername);
        form.find(".companyname").val(companyname);
        form.find('.companyphone').val(phone)
        form.find('.idcompany').val(idcompany)
      });

      $('.companylist-filter').on('click', function functionName(e)
      {
        var $this = $(this)
        var caption = $this.find('span')
        if ($this.hasClass('filtered'))
        {
          companyList.filter()
          $this.find('.fa-eye').removeClass('fa-eye').addClass('fa fa-eye-slash')
          $this.removeClass('filtered')
          caption.text('Hide offline companies')
        } else
        {
          $this.addClass('filtered')
          companyList.filter(filterCompanyList)
          $this.find('.fa-eye-slash').removeClass('fa-eye-slash').addClass('fa-eye')
          caption.text('Show all companies')
        }
      })


      $('#sendtowrequestmodal').on('show.bs.modal', function (e)
      {
        if ($(e.relatedTarget).hasClass('disabled'))
        {
          e.preventDefault()
        }
        $(this).find("form").find("input[name='name']").val(data.name)
        $(this).find("form").find("input[name='phone']").val(data.phone)
      });

      $('#sendrequestsmsmodal').on('show.bs.modal', function (e)
      {
        $(this).find("form").find("input[name='name']").val(data.name)
        $(this).find("form").find("input[name='phone']").val(data.phone)

      });
      $("#sendrequestsmsform").validator().on('submit', function functionName(e)
      {
        var $this = $(this);
        if (e.isDefaultPrevented())
        {
          return false
        }
        var formdata = getFormObj('sendrequestsmsform')
        var district = data.district || 'N/A'
        var address = data.currentaddress || 'N/A'
        var name = formdata.name
        var phone = formdata.phone
        var cartype = formdata.cartype
        var vehicleno = formdata.vehicleno
        var problem = formdata.problem
        var companyname = formdata.companyname
        var companyusername = formdata.companyusername
        var companyphone = formdata.companyphone
        var message = `Tow request from ${name.toUpperCase()}. Phone: ${phone}. Vehicle No.: ${vehicleno.toUpperCase()}. Type: ${cartype}. Address: ${address}. Problem: ${problem}`;
        sendSMS(message, companyphone);
        e.preventDefault()
      })

      $("#sendtowrequestform").validator().on('submit', function functionName(e)
      {
        if (e.isDefaultPrevented())
        {
          return false
        }
        var formdata = getFormObj('sendtowrequestform');
        formdata.district = data.district
        formdata.currentaddress = data.currentaddress
        formdata.username = data.username
        formdata.currentlocation = data.currentlocation
        var tid = formdata.idcompany
        formdata.tid = tid
        var message = { action: true, target: formdata, subject: 'towrequest', };
        $('.modal').modal('hide');
        pubnub.publish({
          channel: 'rvtis-company',
          message: message,
          storeInHistory: true,
          ttl: 24,
          meta: { tid: tid }
        }, (status, response) =>
          {
            if (status.error)
            {
              switch (status.category)
              {
                case "PNNetworkIssuesCategory":
                  $('.modal').modal('hide')
                  var body = encodeURIComponent(`Please, I need a tow service. My name vehicle no. is ${formdata.vehicleno} and phone no. is ${formdata.phone}.`)
                  $.notify(`Sending tow request failed. Please check your network connection or try sending <a href="javascript:sendSMS(${body},${formdata.companyphone})">internet SMS </a> or <a href="sms:${formdata.companyphone}?body=${body}"> local SMS </a>)`, { type: 'danger', placement: { align: 'center' } })
                  break;

                default:
                  break;
              }
            } else
            {
              $.notifyClose();
              $.notify(`<span class="text-bold">Tow request sent to <span class="text-capitalize">${formdata.companyname}</span></span>`, { type: 'success', placement: { align: 'center' } })
            }
          });
        e.preventDefault();
      })

      window.addEventListener('load', function ()
      {
        initPubnub();
      })

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


      function geocode(address)
      {
        var url = `https://maps.googleapis.com/maps/api/geocode/json?address=${address}&key=AIzaSyCqjMdWdNss5BgNzucIYPBBMGkKs7Y5dlI`
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
          // if (n.tag == 'towrequest')
          // {

          // }
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
              }
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
        var device_id = 89911
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
        return new Date(standardtimetoken)
      }
      function initPubnub()
      {

        if ("PubNub" in window)
        {
          pubnub = new PubNub({
            publishKey: 'pub-c-cb902de7-4d32-42f1-b755-09518db50855',
            subscribeKey: 'sub-c-1adc0c20-01bf-11e8-91aa-36923a88c219',
            uuid: data.phone + data.username
          });
          data.uuid = pubnub.getUUID()
          // filter
          //pubnub.setFilterExpression("tid != '"+data.tid+"'");

          pubnub.addListener({
            presence: function (presenceEvent)
            {
              var state = presenceEvent.state;
              switch (presenceEvent.subscribedChannel)
              {
                case 'rvtis-truckdriver-pnpres':
                  switch (presenceEvent.action)
                  {
                    case 'join':

                      break;
                    case 'leave':
                      if (state && state.tid in drivermarkers)
                      {
                        updateTruckDriverState(state, 'offline')
                      }
                      break
                    case 'timeout':
                      break;
                    case 'state-change':
                      if (state && state.tid in drivermarkers)
                      {
                        updateTruckDriverState(state, 'online')
                      }
                      break;
                    default:
                      break;
                  }
                  break;
                case 'rvtis-company-pnpres':
                  switch (presenceEvent.action)
                  {
                    case 'join':
                      break;
                    case 'state-change':

                      if (typeof state !== 'undefined')
                      {
                        var data = {
                          status: 'online',
                          district: state.district,
                          region: state.region,
                          currentaddress: state.currentaddress
                        }
                        updateCompanyStatus(data, state.tid)
                      }
                      break;
                    case 'leave':
                      if (typeof state !== 'undefined')
                      {
                        var dat = {
                          status: 'offline',
                          district: state.district,
                          region: state.region,
                          currentaddress: state.currentaddress
                        }
                        updateCompanyStatus(dat, state.tid)
                      }
                      break;
                    case 'timeout':

                      break;

                    default:
                      break;
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
              var tid = message.tid;

              if ('action' in message)
              {
                var target = message.target;
                var subject = message.subject;
                var notice = target.notice
                switch (subject)
                {

                  case 'towrequestresponse':
                    $.notifyClose();
                    setTimeout(() =>
                    {
                      if (target.requestserved)
                      {
                        // add to current job
                        job = target
                        jobpool[target.idtowrequest] = job
                        updateTruckDriverState(target.truckdriverdata, 'online')
                        $.notify(target.notice, { type: 'info' })
                      } else
                      {

                        $.notify(target.notice, { type: 'danger' })
                      }
                    }, 5000)


                    break;
                  default:

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
                    $('.location').html(`<div class="w3-tiny text-bold"><p >@${data.currentaddress}</p><p>${data.district || ''}</p></div>`)
                    window.data.statesetcount++;
                    pubnub.setState({
                      state: window.data,
                      channels: ['rvtis-user']
                    }, (status, response) =>
                      {
                        console.log(status, response)
                      });
                  })

                  getOnlineCompanies().then(function (companies)
                  {
                    $.each(companies, function (indexInArray, company)
                    {
                      var state = company.state
                      if (typeof state !== 'undefined')
                      {
                        var data = {
                          status: 'online',
                          district: state.district,
                          region: state.region,
                          currentaddress: state.currentaddress
                        }
                        updateCompanyStatus(data, state.tid)
                      }
                    });
                  }).catch((err) => { })

                  break;
                case "PNNetworkDownCategory":
                  console.log('network down');
                  break;
                case "PNNetworkUpCategory":
                  console.log('network online'); subscribe();

                  break;
                case "PNReconnectedCategory":
                  console.log('reconnected');
                  break;
                case "PNTimeoutCategory":
                  console.log('timedout whiles attempting to connect');
                  break;
                default:
                  break;
              }
            }
          }); subscribe();

          if ("geolocation" in navigator)
          {
            watchid = navigator.geolocation.watchPosition(function success(position)
            {
              data.currentlocation.lat = position.coords.latitude
              data.currentlocation.lng = position.coords.longitude
              reverseGeocode(data.currentlocation).
                then(function (address)
                {
                  data.currentaddress = address.formatted_address;
                  data.addressobject = address
                  data.district = address.administrative_area_level_2
                  data.region = address.administrative_area_level_1
                  $('.location').html(`<div class="w3-tiny text-bold"><p >@${data.currentaddress}</p><p>${data.district || ''}</p></div>`)
                  data.statesetcount++
                  pubnub.setState({
                    state: data,
                    channels: ['rvtis-user']
                  }, (status, response) =>
                    {
                      console.log(status, response);
                    });
                })
                .catch(error => console.log(error));
              if (map)
              {
                myMarker.setPosition(new google.maps.LatLng(window.data.currentlocation))
                myInfoWindow.setContent('<small class=" font-bold w3-tiny">' + window.data.currentaddress +
                  '</small>');
                map.panTo(data.currentlocation);

              }
            }, function error(params) { }, {
                // timeout: 5 * 1000,
                // maximumAge: 10000,
                // enableHighAccuracy: true
              });
          }
        }

        function subscribe()
        {
          pubnub.subscribe({
            channels: ['rvtis-user', 'rvtis-company-pnpres', 'rvtis-truckdriver-pnpres']
          });
        }
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
          //load.js('https://maps.googleapis.com/maps/api/js?libraries=geometry&key=AIzaSyCqjMdWdNss5BgNzucIYPBBMGkKs7Y5dlI'),
          load.js('assets/js/oms.min.js'),
          load.js('assets/js/markerAnimate.js'),
          load.js('assets/js/SlidingMarker.min.js'),
          //load.js('assets/js/list.js')
          load.js('assets/js/markerwithlabel.js')
        ]).then(function ()
        {
          oms = new OverlappingMarkerSpiderfier(map, {
            ignoreMapClick: true
          });
          oms.addMarker(myMarker);
          SlidingMarker.initializeGlobally();
          console.log('Everything has loaded!');
        }).catch(function ()
        {
          console.log('Oh no, epic failure!');
        });
      }


      function updateCompanyStatus(d, tid)
      {
        companyList.filter();
        var data = {
          status: d.status || 'offline',
          district: d.district || '',
          region: d.region || '',
          currentaddress: d.currentaddress
        }
        var $li = $(`#company-list ul li[data-idcompany='${tid}'`);
        $li.find(".status").text(data.status)
        $li.find(".region").text(data.region)
        $li.find(".district").text(data.district)

        if (data.status == 'online')
        {
          $li.find('.fa-circle').removeClass('text-danger')
            .addClass('text-success');
        } else
        {
          $li.find('.fa-circle').removeClass('text-success')
            .addClass('text-danger');
        }

        companyList.reIndex();
        companyList.filter(filterCompanyList)

        // $('.companylist-filter').addClass('filtered').find('.fa-eye-slash').removeClass('fa-eye-slash').addClass('fa-eye')
        // $('.companylist-filter').find('span').text('Show all companies')
      }

      function filterCompanyList(item)
      {
        if (item.values().status == 'online')
        {
          $(item.elm).find('#towrequestbtn').removeClass('disabled')
          return true
        } else
        {
          $(item.elm).find('#towrequestbtn').addClass('disabled')
          return false
        }
      }

      function initMap()
      {
        var currentlocation = {
          lat: 7.350576666666666,
          lng: -2.341095
        };
        window.map = new google.maps.Map(document.getElementById("map"), {
          center: new google.maps.LatLng(currentlocation),
          zoom: 15
        });
        map.panTo(new google.maps.LatLng(currentlocation));


        myInfoWindow = new google.maps.InfoWindow({
          content: '<small class=" font-bold w3-tiny">' + window.data.currentaddress +
            '</small>'
        })
        myMarker = new google.maps.Marker({
          position: data.currentlocation,
          title: "Me",
          map: map,
          animation: google.maps.Animation.DROP
        });
        myMarker.addListener('spider_click', function ()
        {
          myInfoWindow.open(map, myMarker);
          setTimeout(() =>
          {
            myInfoWindow.close();
          }, 5 * 1000);
        });

        loadScripts();

        getPosition().then(position =>
        {
          reverseGeocode(position).then(address =>
          {
            data.currentlocation = {
              lat: position.lat,
              lng: position.lng
            }
            data.currentaddress = address.formatted_address;
            data.addressobject = address
            data.district = address.district
            data.region = address.region
            $('.location').html(`<div class="w3-tiny text-bold"><p >@${data.currentaddress}</p><p>${data.district || ''}</p></div>`)
            data.locationchanged = true;
            data.statesetcount++
            myInfoWindow = new google.maps.InfoWindow();
            myInfoWindow.setContent('<small class=" font-bold">Current address: ' + data.currentaddress +
              '</small>');
            map.panTo(new google.maps.LatLng(data.currentlocation));
          }).catch((err) => { console.log(err) });
        }).catch((err) => { });
      }
      function getOnlineCompanies()
      {
        return new Promise((resolve, reject) =>
        {
          pubnub.hereNow({
            channels: ['rvtis-company'],
            includeState: true
          },
            function (s, r)
            {
              //console.log(status, response);
              if (s.error == false)
              {
                var channel = r.channels['rvtis-company']
                var occupants = channel.occupants
                if (channel.occupancy > 0)
                {
                  // filter criteria 1: MUST HAVE STATE
                  var stateful = occupants.filter(company =>
                  {
                    return typeof company.state !== 'undefined'
                  });

                  if (stateful.length > 0)
                  {
                    resolve(stateful)
                  }
                } else
                {
                  reject("No company is online")
                }
              } reject("An error occured while determining companies online")
            });
        });
      }

      function reverseGeocode(latlng)
      {
        var url = `https://maps.googleapis.com/maps/api/geocode/json?latlng=${latlng}&key=AIzaSyCqjMdWdNss5BgNzucIYPBBMGkKs7Y5dlI`
        if (typeof latlng !== "string")
        {
          var lat = latlng.lat;
          var lng = latlng.lng;
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

        }
      }

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?libraries=geometry&key=AIzaSyCqjMdWdNss5BgNzucIYPBBMGkKs7Y5dlI&callback=initMap"></script>


  </body>

  </html>