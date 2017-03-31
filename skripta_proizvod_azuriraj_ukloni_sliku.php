<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$korisnicko_ime = "";
if(isset($_SESSION["prijava"])) {
    $korisnicko_ime = $_SESSION["prijava"][0];
}
$p_id = $_GET["id"];
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
unlink($slika);
$bp->updateDB($sql2);
if ($bp->pogreskaDB()) {
    exit();
}
$bp->zatvoriDB();
$slika = "img/products/default.png?" . filemtime("img/products/default.png");
echo json_encode($slika);

?>