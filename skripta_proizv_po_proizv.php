<?php

$proizv_kor_ime = $_SESSION["prijava"][0];
require_once("skripta_baza.php");
$bp = new Baza();
$sql = "SELECT id, naziv FROM proizvod " .
        "WHERE korisnici_korisnicko_ime = '" .
        $proizv_kor_ime . "'";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit();
}
while (list($kor_proizv_id, $kor_proizv_naziv) = $rs->fetch_array()) {
    $pom_lista = array($kor_proizv_id, $kor_proizv_naziv);
    $proizv_lista[] = $pom_lista;
}
$rs->close();
$bp->zatvoriDB();

?>