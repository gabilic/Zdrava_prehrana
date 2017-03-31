<?php

$korisnicko_ime = $_GET["korisnicko_ime"];
$tablica = '<table style="margin: 0 auto"><tr>';
$stil1 = ' style="padding-top: 40px"';
$stil2 = ' style="padding-bottom: 40px"';
require_once("skripta_baza.php");
$bp = new Baza();
$sql = "SELECT ime, prezime, rodjendan, spol, broj_telefona, email, " .
        "grad, ulica, kucni_broj, naziv_opg, slika FROM korisnici " .
        "WHERE korisnicko_ime = '" . $korisnicko_ime . "'";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit();
}
while (list($ime, $prezime, $rodjendan, $spol, $broj_telefona, $email, $grad, $ulica, $kucni_broj, $naziv_opg, $slika) = $rs->fetch_array()) {
    $dat_rodj = explode("-", $rodjendan); $dan = intval($dat_rodj[2]); $mjesec = intval($dat_rodj[1]);
    $godina = $dat_rodj[0]; $datum = $dan . "." . $mjesec . "." . $godina . ".";
    if($spol === "M") {
        $spol = "muški";
    }
    else if($spol === "Ž") {
        $spol = "ženski";
    }
    if($slika !== NULL) {
        $tablica .= '<td rowspan="4"><img src="' . $slika . "?" . filemtime($slika) . '" alt="Slika profila" width="250" height="200" style="border-radius: 10px"></td>';
    }
    $adresa = $ulica . " " . $kucni_broj . ", " . $grad;
    $tablica .= "<td" . ($slika !== NULL ? $stil1 : "") . '><span style="font-weight: bold">Ime:</span><span id="ime"> ' . $ime . "</span></td>" .
                "<td" . ($slika !== NULL ? $stil1 : "") . '><span style="font-weight: bold">Naziv OPG-a:</span><span id="opg"> ' . $naziv_opg . "</span></td></tr>" .
                '<tr><td><span style="font-weight: bold">Prezime:</span><span id="prezime"> ' . $prezime . "</span></td>" .
                '<td><span style="font-weight: bold">Broj telefona:</span><span id="telefon"> ' . $broj_telefona . "</span></td></tr>" .
                '<tr><td><span style="font-weight: bold">Datum rođenja:</span><span id="datum"> ' . $datum . "</span></td>" .
                '<td><span style="font-weight: bold">E-mail:</span><span id="email"> ' . $email . "</span></td></tr>" .
                "<tr><td" . ($slika !== NULL ? $stil2 : "") . '><span style="font-weight: bold">Spol:</span><span id="spol"> ' . $spol . "</span></td>" .
                "<td" . ($slika !== NULL ? $stil2 : "") . '><span style="font-weight: bold">Adresa:</span><span id="adresa"> ' . $adresa . "</span></td></tr>" .
                '<tr><td><span id="korisnik" style="visibility: hidden; display: none">' . $korisnicko_ime . "</span></td>" .
                '<td><span style="visibility: hidden; display: none"></span></td></tr>';
}
$rs->close();
$bp->zatvoriDB();

$tablica .= "</table>";
echo json_encode($tablica);

?>