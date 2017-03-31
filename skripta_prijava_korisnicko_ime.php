<?php

$korisnicko_ime = $_POST["korisnicko_ime"];
$zauzeto = "";
require_once("skripta_baza.php");
$bp = new Baza();
$sql = "SELECT korisnicko_ime FROM korisnici " .
        "WHERE korisnicko_ime = '" . $korisnicko_ime . "'";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit();
}
while (list($kor_ime) = $rs->fetch_array()) {
    if($kor_ime === $korisnicko_ime) {
        $zauzeto = "zauzeto";
    }
}
$rs->close();
$bp->zatvoriDB();
echo json_encode($zauzeto);

?>