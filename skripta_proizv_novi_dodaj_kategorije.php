<?php

require_once("skripta_baza.php");
$bp = new Baza();
$sql = "SELECT naziv FROM kategorija_proizvoda " .
        "ORDER BY id ASC";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit();
}
$status = "[";
while (list($kategorija) = $rs->fetch_array()) {
    $lista[] = '{"kategorija":"' . ucfirst($kategorija) . '"}';
}
$status .= join(",", $lista);
$status .= "]";
$rs->close();
$bp->zatvoriDB();

echo $status;

?>