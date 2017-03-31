<?php

$kupac = $_GET["kupac"];
$proizvodjac = $_GET["proizvodjac"];
$ocjena = $_GET["ocjena"];
$status = "";
require_once("skripta_baza.php");
$bp = new Baza();
$sql = "INSERT INTO ocjena VALUES ('" . $kupac . "', '" .
        $proizvodjac . "', " . $ocjena . ")";
$sql2 = "SELECT ocjena FROM korisnici WHERE " .
        "korisnicko_ime = '" . $proizvodjac . "'";
$bp->spojiDB();
$bp->updateDB($sql);
if ($bp->pogreskaDB()) {
    exit();
}
$rs = $bp->selectDB($sql2);
if ($bp->pogreskaDB()) {
    exit();
}
while (list($k_ocjena) = $rs->fetch_array()) {
    if($k_ocjena === NULL) {
        $sql3 = "UPDATE korisnici SET ocjena = " . $ocjena .
                ", broj_glasova = broj_glasova + 1 WHERE " .
                "korisnicko_ime = '" . $proizvodjac . "'";
        $bp->updateDB($sql3);
        if ($bp->pogreskaDB()) {
            exit();
        }
    }
    else {
        $sql4 = "UPDATE korisnici SET ocjena = (ocjena + " . $ocjena .
                ") / 2, broj_glasova = broj_glasova + 1 WHERE " .
                "korisnicko_ime = '" . $proizvodjac . "'";
        $bp->updateDB($sql4);
        if ($bp->pogreskaDB()) {
            exit();
        }
    }
}
$rs->close();
$bp->zatvoriDB();
$status = "uspjeh";
echo json_encode($status);

?>