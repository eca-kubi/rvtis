<?php
ob_start();
include "connect-sqlite.php";
$page='truckdriver.php';
$companyphone;
$idcompany='';
$idbranch='';
$idtruckdriver='';
$tether=true;
$username = 'anonymous';
$name = "Anonymous";
$phone="";
$branch='';
$companyusername = '';
$companyname ='';
$who="truckdriver";
$passwordreset = false;
$district="";
$anonymous=false;
session_name('truckdriver');
session_set_cookie_params(time() + 3600 * 24 * 30, '/tims-alpha/');
session_start();
session_regenerate_id();
setcookie(session_name(), session_id(), time() + 3600 * 24 * 30, '/tims-alpha/');
if (isset($_SESSION['truckdriver'])) {
  $username = $_SESSION['truckdriver'];
  $phone = $_SESSION['phone'];
  $name=$_SESSION['name'];
  $branch=$_SESSION['branch'];
  $companyusername = $_SESSION['companyusername'];
  $companyname = $_SESSION['companyname'];
  $stmt =$db->prepare('SELECT passwordreset from truckdriver where username=? LIMIT 1');
  $stmt->execute([$username]);
  $row =$stmt->fetch();
  if($row){
    $passwordreset = boolval($row[0]);
  }
$driver =$db->query("SELECT  t.name, idtruckdriver, t.idbranch, b.idcompany, c.phone companyphone from truckdriver t  left join branch b on t.idbranch=b.idbranch left join company c on c.idcompany=b.idcompany where t.username='$username'")->fetch(PDO::FETCH_ASSOC);
$companyphone = $driver['companyphone'];
$idcompany=$driver['idcompany'];
$idtruckdriver=$driver['idtruckdriver'];
$idbranch=$driver['idbranch'];
$truckdriverdata = array('idcompany'=>$idcompany);
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
    <title>RVTIS&copy; Truck Driver -
      <?php echo $username; ?> </title>
    <script src="assets/js/pace.js"></script>
    <script src="assets/js/jquery.js"></script>
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
    <script type="text/javascript">
      $.extend(window.Pace.options, { ajax: { trackMethods: ['GET', 'POST', 'DELETE', 'PUT', 'PATCH'], ignoreURLs: [/.*subscribe\/sub-c-1adc0c20-01bf-11e8-91aa-36923a88c219.*/] } }) </script>
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
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="./assets/AdminLTE/css/AdminLTE.css">
    <link rel="stylesheet" href="assets/css/w3.css">
    <link rel="stylesheet" href="assets/css/ionicons.css">
    <link rel="stylesheet" href="assets/css/font-awesome.css">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <script src="assets/jquery-ui/jquery-ui.js"></script>
    <script src="assets/js/jquery.easing.1.3.js"></script>
    <script src="assets/js/tether.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/bootstrap-notify.js"></script>
    <link rel="stylesheet" href="assets/jquery-ui/themes/cupertino/jquery-ui.min.css">
    <link rel="stylesheet" href="assets/css/custom.css">
    <link rel="stylesheet" href="assets/datatable.net/css/datatables.min.css">
    <link rel="stylesheet" href="assets/datatable.net/css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="assets/datatable.net/responsive/css/responsive.bootstrap.min.css">

  </head>

  <body>
    <style>
      /* Always set the map height explicitly to define the size of the div
           * element that contains the map. */

      #map {
        height: 100%;
      }

      /* Optional: Makes the sample page fill the window. */

      html,
      body {
        height: 100%;
        margin: 0;
        padding: 0;
      }

      #floating-panel {
        position: absolute;
        top: 10px;
        left: 25%;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
        text-align: center;
        font-family: 'Roboto', 'sans-serif';
        line-height: 30px;
        padding-left: 10px;
      }

      #right-panel {
        font-family: 'Roboto', 'sans-serif';
        line-height: 30px;
        padding-left: 10px;
      }

      #right-panel select,
      #right-panel input {
        font-size: 15px;
      }

      #right-panel select {
        width: 100%;
      }

      #right-panel i {
        font-size: 12px;
      }

      #right-panel {
        height: 300px;
        float: right;
        width: 390px;
        overflow: scroll;
      }

      #map {
        margin-right: 400px;
      }

      #floating-panel {
        background: rgb(255, 185, 185);
        padding: 5px;
        font-size: 14px;
        font-family: Arial;
        border: 1px solid #ccc;
        box-shadow: 0 2px 2px rgba(33, 33, 33, 0.4);
        display: none;
      }

      @media print {
        #map {
          height: 500px;
          margin: 0;
        }
        #right-panel {
          float: none;
          width: auto;
        }
      }
    </style>
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
          <a href="#" class="fa fa-location-arrow location pad w3-padding-16"></a>
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
              <a href="" data-target="#driverfeedbackmodal" data-toggle="modal" class="fa fa-sticky-note"> Feedback</a>
            </li>

            <li>
              <a href="" data-target="#notificationhistorymodal" data-toggle="modal" class="fa fa-history"> Notification History</a>
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
              <a href="sign-out.php?who=truckdriver&username=<?php echo $username; ?>">
                <span class="ion ion-locked"></span> Sign Out</a>
            </li>
            <?php } ?>
          </ul>
        </div>
        <!-- /.navbar-collapse -->
      </div>
      <!-- /.container-fluid -->
    </nav>
    <div class="mappanel hidden"></div>
    <div class="container-fluid">
      <h4 class="w3-xxxlarge w3-margin-top w3-topbar w3-padding-small margin-top150">Map</h4>
      <div class="well row w3-margin-top w3-margin-bottom w3-bottombar "> 
        <div id="right-panel"> </div>
        <div class="eon-real-time-screen" id="map" style="height: 400px">
        </div>
      </div>
    </div>
    <button id="showrightpanel" class="hide"></button>
    <?php include 'modals.html'; ?>
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
      //# sourceURL=truckdriver.js
      var directionsDisplay
      var directionsService
      var notifications = []
      var oms
      var map
      var watchId
      var pubnub
      var myInfoWindow
      var currentlocation = {
        lat: 7.350576666666666,
        lng: -2.341095
      };
      var destination;
      var myMarker;
      var iconpath = {
        towtruck: 'assets/png/tow-truck.png',
        towtruck2: 'assets/png/tow-truck-2.png',
        car: 'assets/svg/car.svg',
        flag: 'assets/png/flag.png',
        carbreakdown: "assets/png/car-breakdown.png"
      }
      var jobpool = []
      var job = {
        truckdriverdata: data,
        clientdata: '',
        complete: false,
        idtowrequest: ''
      }
      var icons = {
        truckdriveronline: 'assets/svg/towtruckdriveronline.svg',
        truckdriveroffline: 'assets/svg/towtruckdriveroffline.svg',
        car: 'assets/svg/car.svg',
        brokencar: "assets/grunticon-loader/png/car-breakdown.png"
      }
      var companyusername = `<?php echo $companyusername; ?>`
      var companyphone = `<?php echo $companyphone; ?>`
      var companyname = `<?php echo $companyname; ?>`
      var username = `<?php echo $username; ?>`
      var anonymous = "<?php echo $anonymous?'true':'';?>"
      var available = true;
      var notificationGranted = false;
      var idtruckdriver = `<?php echo $idtruckdriver; ?>`
      var idbranch = `<?php echo $idbranch; ?>`
      var idcompany = JSON.parse(`<?php echo json_encode($truckdriverdata); ?>`).idcompany
      var usermarkers = {}
      var userinfowins = {}
      var data = {
        currentaddress: 'Unknown Address',
        name: `<?php echo $name; ?>`,
        username: `<?php echo $username; ?>`,
        presencechannel: companyusername + '-truckdriver',
        messagechannel: companyusername + '-truckdriver',
        companyusername: `<?php echo $companyusername; ?>`,
        companyname: `<?php echo $companyname; ?>`,
        currentlocation: window.currentlocation,
        who: 'truckdriver',
        available: window.available,
        statesetcount: 0,
        uuid: '',
        branch: `<?php echo $branch; ?>`,
        phone: `<?php echo $phone; ?>`,
        companyphone: companyphone,
        tid: idtruckdriver,
        district: '',
        region: '',
        addressobject: {},
        idcompany: JSON.parse(`<?php echo json_encode($truckdriverdata); ?>`).idcompany
      }
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
        $.notifyDefaults({
          delay: 0,
          newest_on_top: true, placement: { align: 'center' }, animate: {
            enter: 'animated bounceIn',
            exit: 'animated bounceOut'
          }
        })

        $("#driverfeedbackmodal").on('show.bs.modal', function (e)
        {
          if (Object.keys(jobpool).length <= 0)
          {
            $.notify('There is currently no job to send feedback about', { type: 'warning' })
            return false
          }
          if (Object.keys(job).length > 0)
          {
            $("#driverfeedbackmodal").find('[name="name"]').val(window.data.name)
            $("#driverfeedbackmodal").find('[name="clientname"]').val(job.clientdata.name)
            $("#driverfeedbackmodal").find('[name="phone"]').val(job.clientdata.phone)
            $("#driverfeedbackmodal").find('[name="vehicleno"]').val(job.clientdata.vehicleno)
          } else
          {
            $.notify('No tow job at the moment', { type: 'warning', delay: 3000 })
          }
        });

        $("#driverfeedbackform").on('submit', function (e)
        {
          var formObj = getFormObj("driverfeedbackform")
          if (Object.keys(job).length > 0)
          {
            $('.modal').modal('hide');
            job.complete = true
            pubnub.publish({
              channel: 'rvtis-company',
              message: {
                action: true, subject: 'towrequestfeedback', target: {
                  data: job,
                  feedback: formObj.feedback,
                  idtowrequest: job.idtowrequest,
                  who: 'truckdriver'
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
          }
          e.preventDefault();
        });

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

        $(document).on('click', '.get-directions', function (param)
        {
          if (typeof directionsDisplay != 'undefined' || directionsService)
          {
            $("#right-panel").removeClass('.hide')
            directionsDisplay.setMap(map);
            directionsDisplay.setPanel($("#right-panel").get(0));
            // $("#right-panel").prepend(` <h5 class="text-bold text-success">Navigation Panel</h5>`)
            var control = $("#showrightpanel").html('Hide Directions Panel').addClass('right-panel-hidden').get(0)
            control.style.display = 'block';
            map.controls[google.maps.ControlPosition.TOP_CENTER].push(control);

            calculateAndDisplayRoute(window.data.currentlocation, job.clientdata.currentlocation);

            var onChangeHandler = function (position)
            {
              calculateAndDisplayRoute(position, job.clientdata.currentlocation);
            };

            window.watchid2 = navigator.geolocation.watchPosition(onChangeHandler, function (err) { console.log(err) })
          } else
          {
            $.notify('Sorry directions service is not available at  the moment. It will be back shortly', { type: 'danger' })
          }
        })

        $('document').on('click', "#showrightpanel", function (e)
        {
          if ($(this).hasClass('right-panel-hidden'))
          {
            $(this).html('Show Directions Panel').removeClass('right-panel-hidden')
            $('#right-panel').removeClass('hide')
          } else
          {
            $(this).html('Hide Directions Panel').addClass('right-panel-hidden')
            $('#right-panel').addClass('hide')
          }
        });


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
          new Tether({
            element: $('.mappanel'),
            target: $('#map'),
            attachment: 'top left',
            targetAttachment: 'top left',
            offset: '0 -10px'
          });
        }

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
      })

      $(window).on('load', function ()
      {
        initPubnub()
      });

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


          pubnub.addListener({
            presence: function (presenceEvent)
            {
              var state = presenceEvent.state;
              switch (presenceEvent.subscribedChannel)
              {
                case 'rvtis-company-pnpres':
                  switch (presenceEvent.action)
                  {
                    case 'join':

                      break;
                    case 'leave':
                      break
                    case 'timeout':
                      break;
                    case 'state-change':


                      break;
                    default:
                      break;
                  }
                  break;
                case 'rvtis-user-pnpres':
                  switch (presenceEvent.action)
                  {
                    case 'join':

                      break;
                    case 'leave':
                      if (state && state.tid in usermarkers)
                      {
                        updateUserState(state, 'offline')
                      }
                      break;
                    case 'timeout':

                      break;
                    case 'state-change':
                      if (state && state.tid in usermarkers)
                      {
                        updateUserState(state, 'online')
                      }
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

              if ('action' in message)
              {
                var target = message.target;
                var subject = message.subject

                switch (subject)
                {

                  case 'towdispatch':
                    // if(Object.keys(jobpool).length > 0){
                    //   $.notify('A dispatch has been revoked because a job is still in progress. Make sure you click on "send feedback" if you have completed this job', {type: 'warning'})
                    //   return
                    // }
                    var tid = target.tid;
                    if (tid == idcompany)
                    {

                      var client = target.clientdata
                      var idtowrequest = target.idtowrequest
                      var notice = target.notice
                      $.notify(notice, { type: "info" })
                      updateUserState(client, 'online', target.idtowrequest)
                      job = target
                      jobpool[idtowrequest] = job
                      appendNotification('Click here to get directions', notice, idtowrequest)
                      notifications.push({ heading: 'Click here to get directions', content: notice })
                    }
                    break;
                  default:
                }
              }

            },

            status: function (statusEvent)
            {
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
                      channels: ['rvtis-truckdriver']
                    }, (status, response) =>
                    {
                      console.log(status, response)
                    });
                  })
                  console.log('connected')
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
                  console.log('timedout');
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
          // filter
          //pubnub.setFilterExpression("ctid=="+idcompany);
          reverseGeocode(data.currentlocation).then(function (address)
          {
            data.currentaddress = address.formatted_address

            //hereNow();
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
                    data.locationchanged = true;
                    data.statesetcount++
                    pubnub.setState({
                      state: data,
                      channels: ['rvtis-truckdriver']
                    }, (status, response) =>
                    {
                      console.log(status, response);
                    });
                  }).catch((err) => { })
                  .catch(error => console.log(error));

                if (map)
                {
                  myMarker.setPosition(new google.maps.LatLng(data.currentlocation))
                  myInfoWindow.setContent('<small class=" font-bold w3-tiny">' + data.currentaddress +
                    '</small>');
                  map.panTo(new google.maps.LatLng(data.currentlocation));

                }
              }, function error(params) { }, {
                  // timeout: 5 * 1000,
                  // maximumAge: 10000,
                  // enableHighAccuracy: true
                });
            }
          }).catch((err) => { })
        }

        function subscribe()
        {
          pubnub.subscribe({
            channels: ['rvtis-truckdriver', 'rvtis-company-pnpres', 'rvtis-user-pnpres']
          }, function (s, r)
          {
            console.log(s, r)
          })
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
          load.js('assets/js/oms.js'),
          load.js('assets/js/markerAnimate.js'),
          load.js('assets/js/SlidingMarker.min.js'),
          load.js('assets/js/markerwithlabel.js')

          //load.js('assets/js/list.js')
        ]).then(function ()
        {
          oms = new OverlappingMarkerSpiderfier(map, {
            ignoreMapClick: true,
            markersWontMove: true,
            markersWontHide: true,
            basicFormatEvents: true
          });

          oms.addMarker(myMarker);
          SlidingMarker.initializeGlobally();
          console.log('Everything has loaded!');
        }).catch(function ()
        {
          console.log('Oh no, epic failure!');
        });
      }

      function initMap()
      {
        var currentlocation = {
          lat: 7.350576666666666,
          lng: -2.341095
        };
        window.directionsDisplay = new google.maps.DirectionsRenderer();
        window.directionsService = new google.maps.DirectionsService();
        window.map = new google.maps.Map(document.getElementById("map"), {
          center: new google.maps.LatLng(currentlocation.lat, currentlocation.lng),
          zoom: 15
        });
        map.panTo(new google.maps.LatLng(currentlocation.lat, currentlocation.lng))
        myInfoWindow = new google.maps.InfoWindow({
          content: '<small class=" font-bold w3-tiny">' + window.data.currentaddress +
            '</small>'
        });
        myMarker = new google.maps.Marker({
          position: map.center,
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
            data.district = address.administrative_area_level_2
            data.region = address.administrative_area_level_1
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


      function updateUserState(state, status, idtowrequest)
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
          if (tid in usermarkers)
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
            map.panTo(marker.getPosition())
            calculateAndDisplayRoute(window.currentlocation, state.currentlocation)
          }
        }
      }


      function calculateAndDisplayRoute(start, end)
      {

        if (typeof directionsDisplay != 'undefined' || directionsService)
        {
          directionsService.route({
            origin: start,
            destination: end,
            travelMode: 'DRIVING'
          }, function (response, status)
          {
            if (status === 'OK')
            {
              directionsDisplay.setDirections(response);
            } else
            {
              console.log('Directions request failed due to ' + status);
            }
          });
        }

      }

      function appendNotification(heading, content, id)
      {
        var $append = `<a href="#" class="list-group-item active">
            <h4 class="list-group-item-heading">${heading}</h4>
            <p class="list-group-item-text">${content}</p>
          </a>`
        $('#notificationhistorymodal').find('.list-group').append($append).attr('data-id', id)
      }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?libraries=geometry&key=AIzaSyCqjMdWdNss5BgNzucIYPBBMGkKs7Y5dlI&callback=initMap"></script>
  </body>

  </html>