<?php

$korisnicko_ime = $_GET["korisnicko_ime"];
$email = str_replace(" ", "", $_GET["email"]);
require_once("skripta_baza.php");
$bp = new Baza();
$sql = "UPDATE korisnici SET aktiviran = 'NE' " .
        "WHERE korisnicko_ime = '" . $korisnicko_ime . "'";
$sql2 = "SELECT aktivacijski_kod FROM korisnici " .
        "WHERE korisnicko_ime = '" . $korisnicko_ime . "'";
$bp->spojiDB();
$bp->updateDB($sql);
if ($bp->pogreskaDB()) {
    exit();
}
$rs = $bp->selectDB($sql2);
if ($bp->pogreskaDB()) {
    exit();
}
while (list($akt_kod) = $rs->fetch_array()) {
    $aktivacijski_kod = $akt_kod;
}
$rs->close();
$bp->zatvoriDB();
mail($email, "Aktivacijski link", wordwrap("Poštovani/a\n\nHvala Vam što ste se registrirali kao proizvođač na sustavu Zdrava prehrana! " .
                "Klikom na poveznicu: https://goldner.xyz/gabriel/registracija_aktivacija.php?kod=" . $aktivacijski_kod .
                " možete aktivirati Vaš korisnički račun.\nOvaj e-mail ste primili, budući da je kreiran korisnički račun koji koristi " .
                "Vašu e-mail adresu. Ako pak niste kreirali ovaj korisnički račun, ignorirajte pristiglu poruku.\n\n" .
                "Zdrava prehrana", 100), "From: Zdrava prehrana <zdrava.prehrana@goldner.xyz>");

$status = "uspjeh";
echo json_encode($status);

?>