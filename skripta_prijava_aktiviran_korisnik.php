<?php

$korisnicko_ime = $_POST["korisnicko_ime"];
$aktiviran = "";
require_once("skripta_baza.php");
$bp = new Baza();
$sql = "SELECT aktiviran FROM korisnici " .
        "WHERE korisnicko_ime = '" . $korisnicko_ime . "'";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit();
}
while (list($aktiviran_korisnik) = $rs->fetch_array()) {
    if($aktiviran_korisnik === "DA") {
        $aktiviran = "aktiviran";
    }
}
$rs->close();
$bp->zatvoriDB();
echo json_encode($aktiviran);

?>