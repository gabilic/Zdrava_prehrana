<?php

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
    $status .= '<a href="proizvodjac.php?id=' . $k_ime . '">' . $opg . "</a>";
}
$rs->close();
$bp->zatvoriDB();
echo json_encode($status);

?>