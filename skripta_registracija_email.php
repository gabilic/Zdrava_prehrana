<?php

$email = $_POST["email"];
$zauzeto = "";
$pogreska = false;
require_once("skripta_baza.php");
$bp = new Baza();
$sql1 = "SELECT dat_i_vrij_registracije FROM korisnici " .
        "WHERE email = '" . $email . "'";
$sql2 = "DELETE FROM korisnici WHERE email = '" .
        $email . "'";
$sql3 = "SELECT email FROM korisnici " .
        "WHERE email = '" . $email . "'";
$bp->spojiDB();
$rs1 = $bp->selectDB($sql1);
if ($bp->pogreskaDB()) {
    exit();
}
while (list($datum_i_vrijeme) = $rs1->fetch_array()) {
    if((intval(time()) - intval(strtotime($datum_i_vrijeme))) > 86400) {
        $pogreska = true;
    }
}
$rs1->close();
if($pogreska) {
    $bp->updateDB($sql2);
    if ($bp->pogreskaDB()) {
        exit();
    }
}
$rs2 = $bp->selectDB($sql3);
if ($bp->pogreskaDB()) {
    exit();
}
while (list($mail) = $rs2->fetch_array()) {
    if($mail === $email) {
        $zauzeto = "zauzeto";
    }
}
$rs2->close();
$bp->zatvoriDB();
echo json_encode($zauzeto);

?>