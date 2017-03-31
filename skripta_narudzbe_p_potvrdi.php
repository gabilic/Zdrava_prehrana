<?php

$n_id = $_GET["n_id"];
$kupac = $_GET["kupac"];
$proizvodjac = $_GET["proizvodjac"];
require_once("skripta_baza.php");
$bp = new Baza();
$sql = "UPDATE naruceni_proizvodi np JOIN proizvod p ON np.proizvod_id = p.id " .
        "SET np.status = 'DA' WHERE np.narudzba_id = " . $n_id .
        " AND p.korisnici_korisnicko_ime = '" . $proizvodjac . "' " .
        "AND np.status IS NULL";
$sql2 = "SELECT dat_i_vrij_primitka, (SELECT email FROM korisnici " .
        "WHERE korisnicko_ime = korisnici_korisnicko_ime) FROM narudzba " .
        "WHERE id = " . $n_id;
$sql3 = "SELECT naziv_opg FROM korisnici WHERE korisnicko_ime = '" .
        $proizvodjac . "'";
$bp->spojiDB();
$bp->updateDB($sql);
if ($bp->pogreskaDB()) {
    exit();
}
$rs = $bp->selectDB($sql2);
if ($bp->pogreskaDB()) {
    exit();
}
while (list($n_div_primitka, $n_kupac) = $rs->fetch_array()) {
    $pom_lista = explode(" ", $n_div_primitka);
    $datum = explode("-", $pom_lista[0]); $v_primitka = $pom_lista[1];
    $d_primitka = "$datum[2].$datum[1].$datum[0]";
    $email = $n_kupac;
}
$rs->close();
$rs2 = $bp->selectDB($sql3);
if ($bp->pogreskaDB()) {
    exit();
}
while (list($n_opg) = $rs2->fetch_array()) {
    $opg = $n_opg;
}
$rs2->close();
$bp->zatvoriDB();
$kod = hash("sha256", $kupac . $proizvodjac);
mail($email, "Narudžba", wordwrap("Poštovani/a\n\nProizvođač $opg je potvrdio Vašu narudžbu primljenu na dan $d_primitka u " .
                "$v_primitka. Klikom na poveznicu: https://goldner.xyz/gabriel/ocjena.php?id=$kod&p=$proizvodjac" .
                " možete ocijeniti $opg.\n\nZdrava prehrana", 100), "From: Zdrava prehrana <zdrava.prehrana@goldner.xyz>");

$status = "uspjeh";
echo json_encode($status);

?>