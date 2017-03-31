<?php

$id = $_GET["id"];
require_once("skripta_baza.php");
$bp = new Baza();
$sql = "SELECT kolicina, mjerna_jedinica FROM proizvod " .
        "WHERE id = " . $id;
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit();
}
while (list($kolicina, $jedinica) = $rs->fetch_array()) {
    $status = "$kolicina $jedinica";
}
$rs->close();
$bp->zatvoriDB();
echo json_encode($status);

?>