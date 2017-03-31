<?php

$naziv = $_GET["naziv"];
require_once("skripta_baza.php");
$bp = new Baza();
$sql = "SELECT naziv FROM vrsta_proizvoda " .
        "WHERE kategorija_proizvoda_id = " .
        "(SELECT id FROM kategorija_proizvoda " .
        "WHERE naziv = '" . strtolower($naziv) . "') " .
        "ORDER BY vrsta_proizvoda.id ASC";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit();
}
$status = "[";
while (list($vrsta) = $rs->fetch_array()) {
    $lista[] = '{"vrsta":"' . ucfirst($vrsta) . '"}';
}
$status .= join(",", $lista);
$status .= "]";
$rs->close();
$bp->zatvoriDB();

echo $status;

?>