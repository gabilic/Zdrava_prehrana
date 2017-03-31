<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$korisnicko_ime = "";
if(isset($_SESSION["prijava"])) {
    $korisnicko_ime = $_SESSION["prijava"][0];
}
$p_id = $_POST["id"];
require_once("skripta_baza.php");
$bp = new Baza();
$sql = "SELECT slika FROM proizvod " .
        "WHERE korisnici_korisnicko_ime = '" . $korisnicko_ime . "' " .
        "AND id = " . $p_id;
$sql2 = "UPDATE proizvod SET slika = NULL " .
        "WHERE korisnici_korisnicko_ime = '" . $korisnicko_ime . "' " .
        "AND id = " . $p_id;
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit();
}
if($rs->num_rows === 0) {
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
$slika = "img/products/default.png";
if(isset($_FILES["slika"])) {
    if(!($_FILES["slika"]["error"] > 0)) {
        if(getimagesize($_FILES["slika"]["tmp_name"])) {
            $temp = explode(".", $_FILES["slika"]["name"]);
            $datoteka = $p_id . "." . end($temp);
            move_uploaded_file($_FILES["slika"]["tmp_name"], "img/products/" . $datoteka);
            $slika = "img/products/" . $datoteka;
        }
    }
}
$sql3 = "UPDATE proizvod SET slika = " . ($slika === "img/products/default.png" ? "NULL " : "'" . $slika . "' ") .
        "WHERE korisnici_korisnicko_ime = '" . $korisnicko_ime . "' " .
        "AND id = " . $p_id;
$bp->updateDB($sql3);
if ($bp->pogreskaDB()) {
    exit();
}
$bp->zatvoriDB();
$slika = $slika . "?" . filemtime($slika);
echo json_encode($slika);

?>