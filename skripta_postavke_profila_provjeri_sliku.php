<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$korisnicko_ime = "";
if(isset($_SESSION["prijava"])) {
    $korisnicko_ime = $_SESSION["prijava"][0];
}
$postoji = "";
require_once("skripta_baza.php");
$bp = new Baza();
$sql = "SELECT slika FROM korisnici " .
        "WHERE korisnicko_ime = '" . $korisnicko_ime . "'";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit();
}
while (list($slika) = $rs->fetch_array()) {
    if($slika !== NULL) {
        $postoji = "postoji";
    }
}
$rs->close();
$bp->zatvoriDB();
echo json_encode($postoji);

?>