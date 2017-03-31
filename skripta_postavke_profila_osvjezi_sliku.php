<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$korisnicko_ime = "";
if(isset($_SESSION["prijava"])) {
    $korisnicko_ime = $_SESSION["prijava"][0];
}
require_once("skripta_baza.php");
$bp = new Baza();
$sql = "SELECT slika FROM korisnici " .
        "WHERE korisnicko_ime = '" . $korisnicko_ime . "'";
$sql2 = "UPDATE korisnici SET slika = NULL " .
        "WHERE korisnicko_ime = '" . $korisnicko_ime . "'";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit();
}
while (list($p_slika) = $rs->fetch_array()) {
    $slika = $p_slika;
}
$rs->close();
if($slika !== NULL) {
    unlink($slika);
    $bp->updateDB($sql2);
    if ($bp->pogreskaDB()) {
        exit();
    }
}
$slika = "img/users/default.png";
if(isset($_FILES["slika"])) {
    if(!($_FILES["slika"]["error"] > 0)) {
        if(getimagesize($_FILES["slika"]["tmp_name"])) {
            $temp = explode(".", $_FILES["slika"]["name"]);
            $datoteka = $korisnicko_ime . "." . end($temp);
            move_uploaded_file($_FILES["slika"]["tmp_name"], "img/users/" . $datoteka);
            $slika = "img/users/" . $datoteka;
        }
    }
}
$sql3 = "UPDATE korisnici SET slika = " . ($slika === "img/users/default.png" ? "NULL " : "'" . $slika . "' ") .
        "WHERE korisnicko_ime = '" . $korisnicko_ime . "'";
$bp->updateDB($sql3);
if ($bp->pogreskaDB()) {
    exit();
}
$bp->zatvoriDB();
$slika = $slika . "?" . filemtime($slika);
echo json_encode($slika);

?>