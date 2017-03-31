<?php

$provjera = "uspjeh";
$captcha = $_GET["captcha"];
$privatekey = "6LciNA4UAAAAAPSdWlLiBbbogsQXyW7XBGPpNjcX";
$resp = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . $privatekey . "&response=" . $captcha . "&remoteip=" . $_SERVER['REMOTE_ADDR']);
$decode = json_decode($resp, true);
if($decode["success"] === false) {
    $provjera = "pogreška";
}
echo json_encode($provjera);

?>