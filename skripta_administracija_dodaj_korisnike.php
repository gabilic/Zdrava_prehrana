<?php

require_once("skripta_baza.php");
$bp = new Baza();
$sql = "SELECT korisnicko_ime FROM korisnici " .
        "WHERE tip_korisnika_id = 2 AND aktiviran IS NULL";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit();
}
$status = "[";
while (list($korisnicko_ime) = $rs->fetch_array()) {
    $lista[] = '{"korisnicko_ime":"' . $korisnicko_ime . '"}';
}
$status .= join(",", $lista);
$status .= "]";
$rs->close();
$bp->zatvoriDB();

echo $status;

?>