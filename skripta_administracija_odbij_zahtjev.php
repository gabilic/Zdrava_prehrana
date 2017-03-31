<?php

$korisnicko_ime = $_GET["korisnicko_ime"];
$email = str_replace(" ", "", $_GET["email"]);
require_once("skripta_baza.php");
$bp = new Baza();
$sql = "UPDATE korisnici SET aktiviran = 'NE' " .
        "WHERE korisnicko_ime = '" . $korisnicko_ime . "'";
$bp->spojiDB();
$bp->updateDB($sql);
if ($bp->pogreskaDB()) {
    exit();
}
$bp->zatvoriDB();
mail($email, "Zahtjev odbijen", wordwrap("Poštovani/a\n\nNažalost, nismo mogli kreirati Vaš korisnički račun (najvjerojatnije zbog neispravnih " .
                "korisničkih podataka). Za više informacija, obratite se administratoru sustava.\n\n" .
                "Zdrava prehrana", 100), "From: Zdrava prehrana <zdrava.prehrana@goldner.xyz>");

$status = "uspjeh";
echo json_encode($status);

?>