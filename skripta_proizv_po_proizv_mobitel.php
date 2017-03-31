<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$korisnicko_ime = "";
if(isset($_SESSION["prijava"])) {
    $korisnicko_ime = $_SESSION["prijava"][0];
}
$boja = $_GET["boja"];
$status = "";
require_once("skripta_baza.php");
$bp = new Baza();
$sql = "SELECT id, naziv FROM proizvod " .
        "WHERE korisnici_korisnicko_ime = '" .
        $korisnicko_ime . "'";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit();
}
while (list($kor_proizv_id, $kor_proizv_naziv) = $rs->fetch_array()) {
    $status .= '<li><a class="w3-hover-none w3-text-' . ($boja === "bijela" ? "white" : "black") .
                ' w3-padding-large" href="proizvod_azuriraj.php?id=' . $kor_proizv_id . '">' .
                $kor_proizv_naziv . "</a></li>";
}
$rs->close();
$bp->zatvoriDB();
echo json_encode($status);

?>