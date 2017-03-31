<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$korisnik = $_SESSION["prijava"][0];
require_once("skripta_baza.php");
$bp = new Baza();
$sql = "SELECT id, dat_i_vrij_primitka FROM narudzba " .
        "WHERE korisnici_korisnicko_ime = '" . $korisnik . "'";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit();
}
$status = "[";
while (list($id, $dat_i_vrij_primitka) = $rs->fetch_array()) {
    $pom_lista = explode(" ", $dat_i_vrij_primitka);
    $datum = explode("-", $pom_lista[0]); $vrijeme = $pom_lista[1];
    $narudzba = "$datum[2].$datum[1].$datum[0] $vrijeme";
    $lista[] = '{"id":"' . $id . '","narudzba":"' . $narudzba . '"}';
}
$status .= join(",", $lista);
$status .= "]";
$rs->close();
$bp->zatvoriDB();

echo $status;

?>