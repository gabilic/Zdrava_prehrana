<?php

$korisnicko_ime = $_POST["korisnicko_ime"];
$lozinka = $_POST["lozinka"];
$tocna = "";
require_once("skripta_baza.php");
$bp = new Baza();
$sql = "SELECT lozinka FROM korisnici " .
        "WHERE korisnicko_ime = '" . $korisnicko_ime . "'";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit();
}
while (list($lozinka_provjera) = $rs->fetch_array()) {
    if($lozinka_provjera === hash("sha256", $lozinka)) {
        $tocna = "točna";
    }
}
$rs->close();
$bp->zatvoriDB();
echo json_encode($tocna);

?>