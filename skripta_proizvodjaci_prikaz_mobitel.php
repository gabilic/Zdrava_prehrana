<?php

$boja = $_GET["boja"];
$status = "";
require_once("skripta_baza.php");
$bp = new Baza();
$sql = "SELECT korisnicko_ime, naziv_opg FROM korisnici " .
        "WHERE tip_korisnika_id = 2 " .
        "ORDER BY naziv_opg ASC";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit();
}
while (list($k_ime, $opg) = $rs->fetch_array()) {
    $status .= '<li><a class="w3-hover-none w3-text-' . ($boja === "bijela" ? "white" : "black") .
                ' w3-padding-large" href="proizvodjac.php?id=' . $k_ime . '">' . $opg . "</a></li>";
}
$rs->close();
$bp->zatvoriDB();
echo json_encode($status);

?>