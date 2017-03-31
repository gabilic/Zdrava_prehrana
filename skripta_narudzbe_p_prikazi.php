<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$n_id = $_GET["narudzba"];
$proizvodjac = $_SESSION["prijava"][0];
$ukupno = 0;
$tablica = '<table style="border-collapse: collapse; width: 80%; margin: 35px auto">';
$tablica .= "<tr><th>Proizvod</th><th>Količina</th><th>Cijena</th><th>Ukupno</th></tr>";
require_once("skripta_baza.php");
$bp = new Baza();
$sql = "SELECT DISTINCT n.dat_i_vrij_preuzimanja, k.korisnicko_ime, k.ime, k.prezime " .
        "FROM korisnici k JOIN narudzba n ON k.korisnicko_ime = n.korisnici_korisnicko_ime " .
        "JOIN naruceni_proizvodi np ON n.id = np.narudzba_id JOIN proizvod p ON np.proizvod_id = p.id " .
        "WHERE p.korisnici_korisnicko_ime = '" . $proizvodjac . "' AND n.id = " . $n_id .
        " AND np.status IS NULL";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit();
}
while (list($dat_i_vrij_preuzimanja, $k_kupac, $k_ime, $k_prezime) = $rs->fetch_array()) {
    $pom_lista = explode(" ", $dat_i_vrij_preuzimanja);
    $datum = explode("-", $pom_lista[0]); $vrijeme = $pom_lista[1];
    $div_preuzimanja = "$datum[2].$datum[1].$datum[0] $vrijeme";
    $status = '<p style="width: 80%; margin: 0 auto 7px auto"><b>Kupac:</b> ' . $k_ime . ' ' . $k_prezime . "</p>";
    $status .= '<p style="width: 80%; margin: 7px auto 0 auto"><b>Datum i vrijeme preuzimanja:</b> ' . $div_preuzimanja . "</p>";
    $status .= '<input id="n_id" type="hidden" value="' . $n_id . '">';
    $status .= '<input id="kupac" type="hidden" value="' . $k_kupac . '">';
    $status .= '<input id="proizvodjac" type="hidden" value="' . $proizvodjac . '">';
}
$rs->close();
$sql2 = "SELECT p.naziv, np.kolicina, p.mjerna_jedinica, p.cijena " .
        "FROM narudzba n JOIN naruceni_proizvodi np ON n.id = np.narudzba_id " .
        "JOIN proizvod p ON np.proizvod_id = p.id WHERE p.korisnici_korisnicko_ime = '" .
        $proizvodjac . "' AND n.id = " . $n_id . " AND np.status IS NULL";
$rs2 = $bp->selectDB($sql2);
if ($bp->pogreskaDB()) {
    exit();
}
while (list($proizvod, $kolicina, $mjerna_jedinica, $cijena) = $rs2->fetch_array()) {
    $tablica .= "<tr><td>$proizvod</td><td>$kolicina $mjerna_jedinica</td><td>$cijena kn" .
                "</td><td>" . number_format((float)($kolicina * $cijena), 2) . " kn</td></tr>";
    $ukupno += number_format((float)($kolicina * $cijena), 2);
}
$rs2->close();
$bp->zatvoriDB();

$tablica .= '<tr><td colspan="2"></td><td style="font-weight: bold">Ukupno</td>';
$tablica .= '<td style="font-weight: bold">' . number_format((float)($ukupno), 2) . " kn</td></tr></table>";
$status .= $tablica;
echo json_encode($status);

?>