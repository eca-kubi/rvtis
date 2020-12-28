<?php
include "connect-sqlite.php";//include "connect-mysql.php";
include_once "recaptchalib.php";

$secret = "6LcCmUAUAAAAAMXS5r0p6SQ-2jbY0Pqa2T9XB5Iw";
$fullname = $_POST["fullname"];
$phone= $_POST["phone"];
$vehicleno = $_POST["vehicleNo"];
$companycode = $_POST["companyCode"];
$gresponsedata = null;
$err = array();
$pdo = null;


$validated = validate($vehicleno, $companycode);
$user_id = -1;
if ($validated) {
    $gresponsedata = $_POST["g-recaptcha-response"];
    $recaptcha = new ReCaptcha($secret);
    $response = $recaptcha->verifyResponse($_SERVER["REMOTE_ADDR"], $gresponsedata);
    if($response->success) {
        // process form data
        $user_id = do_registration();
        exit;
    } else {
        echo json_encode($response);
        exit;
    }
} else {
    echo json_encode($err);
    exit;
}

function validate($vehicleNo, $companyCode)
{
   global $err;
    if (!verify_truck($vehicleNo)) {
        array_push($err, array("selector" => "vehicleNo", "errorMessage" =>
            'Sorry, this vehicle number has not been registered under any of our patner tow companies.
        You may call this number for assistance:
        <a href="tel:+233547468666">
            <span class="glyphicon glyphicon-phone-alt"> </span>+233547468666</a>'));
    } else {
        if (!verify_company_code($companyCode)) {
            array_push($err, array("selector" => "companyCode", "errorMessage" => 'Sorry, 
                this company code was not found in our database.
                You may call this number for assistance:
                <a href="tel:+233547468666">
                    <span class="glyphicon glyphicon-phone-alt"> </span>+233547468666</a>'));
        } else {
            if (!verify_truck_and_company_code($vehicleNo, $companyCode)) {
                array_push($err, array("selector" => "vehicleNo", "errorMessage" => 'Sorry, 
                this truck has not been registered under this company code.
                You may call this number for assistance:
                <a href="tel:+233547468666">
                    <span class="glyphicon glyphicon-phone-alt"> </span>+233547468666</a>'));
            } else {
                if (check_company_status($companyCode) == 0) {
                    array_push($err, array("selector" => "companyCode", "errorMessage" => 'The company with this code has been de-activated!
                Please call <a href="tel:+233547468666">
                <span class="glyphicon glyphicon-phone-alt"> </span>+233547468666</a> for assistance. '));
                }
            }
        }
    }
    if(!isset($_POST["g-recaptcha-response"])) {
        array_push($err, array("selector"=>"g-recaptcha", "errorMessage"=> "You must check the reCAPTCHA box!"));
    }
    if (json_encode($err, JSON_PRETTY_PRINT) == "[]") {
        return true;
    } else {
        return false;
    }
}

function do_registration()
{

}

function verify_truck_and_company_code($license, $comp_code)
{
    $pdo = getPdo();
    $ret = null;
    $query = "select company.name, truck.license, company.code FROM company 
    left JOIN truck on truck.idcompany where truck.license=? and company.code=? ";
    $stmt = $pdo->prepare($query);
    $ret = $stmt ? $stmt->execute(array($license, $comp_code)) : $stmt;
    return $ret ? $stmt->fetch() : $ret;
}


function verify_company_code($comp_code)
{
    $pdo = getPdo();
    $ret = null;
    $query = "select idcompany from company where code=?";
    $stmt = $pdo->prepare($query);
    $ret = $stmt ? $stmt->execute(array($comp_code)) : $stmt;
    return $ret ? $stmt->fetch() : $ret;
}

function verify_truck($license)
{
    $pdo = getPdo();
    $ret = null;
    $query = "select idtruck from truck where license=?";
    $stmt = $pdo->prepare($query);
    $ret = $stmt ? $stmt->execute(array($license)) : $stmt;
    return $ret ? $stmt->fetch() : $ret;
}

function check_company_status($company_code)
{
    $pdo = getPdo();
    $ret = null;
    $query = "select status from company where code=?";
    $stmt = $pdo->prepare($query);
    $ret = $stmt ? $stmt->execute(array($company_code)) : $stmt;
    return $ret ? $stmt->fetch()[0] : $ret;
}

?>