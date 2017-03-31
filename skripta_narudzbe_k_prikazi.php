<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$n_id = $_GET["narudzba"];
$kupac = $_SESSION["prijava"][0];
$ukupno = 0;
$tablica = '<table style="border-collapse: collapse; width: 80%; margin: 35px auto">';
$tablica .= "<tr><th>Proizvođač</th><th>Proizvod</th><th>Količina</th><th>Cijena</th><th>Ukupno</th><th></th></tr>";
require_once("skripta_baza.php");
$bp = new Baza();
$sql = "SELECT dat_i_vrij_preuzimanja FROM narudzba WHERE id = " . $n_id .
        " AND korisnici_korisnicko_ime = '" . $kupac . "'";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit();
}
while (list($dat_i_vrij_preuzimanja) = $rs->fetch_array()) {
    $pom_lista = explode(" ", $dat_i_vrij_preuzimanja);
    $datum = explode("-", $pom_lista[0]); $vrijeme = $pom_lista[1];
    $div_preuzimanja = "$datum[2].$datum[1].$datum[0] $vrijeme";
    $status = '<p style="width: 80%; margin: 7px auto 0 auto"><b>Datum i vrijeme preuzimanja:</b> ' . $div_preuzimanja . "</p>";
}
$rs->close();
$sql2 = "SELECT k.naziv_opg, p.naziv, np.kolicina, p.mjerna_jedinica, p.cijena, np.status " .
        "FROM narudzba n JOIN naruceni_proizvodi np ON n.id = np.narudzba_id " .
        "JOIN proizvod p ON np.proizvod_id = p.id JOIN korisnici k ON " .
        "p.korisnici_korisnicko_ime = k.korisnicko_ime WHERE n.korisnici_korisnicko_ime = '" .
        $kupac . "' AND n.id = " . $n_id;
$rs2 = $bp->selectDB($sql2);
if ($bp->pogreskaDB()) {
    exit();
}
while (list($proizvodjac, $proizvod, $kolicina, $mjerna_jedinica, $cijena, $stanje) = $rs2->fetch_array()) {
    if($stanje === NULL) {
        $stanje = "narudžba na čekanju";
        $boja = "#006699";
    }
    else if($stanje === "DA") {
        $stanje = "potvrđena narudžba";
        $boja = "#0AAA14";
    }
    else if($stanje === "NE") {
        $stanje = "odbijena narudžba";
        $boja = "#E63B26";
    }
    $tablica .= "<tr><td>$proizvodjac</td><td>$proizvod</td><td>$kolicina $mjerna_jedinica</td><td>$cijena kn" .
                "</td><td>" . number_format((float)($kolicina * $cijena), 2) . " kn</td>" .
                '<td style="color: ' . $boja . '">' . $stanje . '</td></tr>';
    $ukupno += number_format((float)($kolicina * $cijena), 2);
}
$rs2->close();
$bp->zatvoriDB();

$tablica .= '<tr><td colspan="3"></td><td style="font-weight: bold">Ukupno</td>';
$tablica .= '<td style="font-weight: bold">' . number_format((float)($ukupno), 2) . " kn</td><td></td></tr></table>";
$status .= $tablica;
echo json_encode($status);

?>