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
unlink($slika);
$bp->updateDB($sql2);
if ($bp->pogreskaDB()) {
    exit();
}
$bp->zatvoriDB();
$slika = "img/users/default.png?" . filemtime("img/users/default.png");
echo json_encode($slika);

?>