<?php

$email = $_POST["email"];
$zauzeto = "";
require_once("skripta_baza.php");
$bp = new Baza();
$sql = "SELECT email FROM korisnici " .
        "WHERE email = '" . $email . "'";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit();
}
while (list($mail) = $rs->fetch_array()) {
    if($mail === $email) {
        $zauzeto = "zauzeto";
    }
}
$rs->close();
$bp->zatvoriDB();
echo json_encode($zauzeto);

?>