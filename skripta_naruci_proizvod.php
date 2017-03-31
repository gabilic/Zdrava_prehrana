<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$status = "";
$korisnik = $_SESSION["prijava"][0];
$n_id = "1";
$div_lista = $_GET["dat_i_vrij_preuzimanja"];
$div_preuzimanja = $div_lista[0] . "-" . $div_lista[1] . "-" . $div_lista[2];
$div_preuzimanja .= " " . $div_lista[3] . ":" . $div_lista[4] . ":00";
$div_primitka = date("Y-m-d H:i:s");
require_once("skripta_baza.php");
$bp = new Baza();
$sql = "SELECT id FROM narudzba ORDER BY id ASC";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit();
}
if($rs->num_rows === 0) {
    $pom_lista[] = 0;
}
while (list($id) = $rs->fetch_array()) {
    $pom_lista[] = $id;
}
$rs->close();
while(in_array($n_id, $pom_lista)) {
    $n_id++;
}
$sql2 = "INSERT INTO narudzba VALUES (" . $n_id . ", '" . $div_primitka .
        "', '" . $div_preuzimanja . "', '" . $korisnik . "')";
$bp->updateDB($sql2);
if ($bp->pogreskaDB()) {
    exit();
}
foreach($_SESSION["kosarica"] as $element) {
    $p_id = $element[0]; $kolicina = $element[2];
    $sql3 = "INSERT INTO naruceni_proizvodi VALUES (" . $n_id .
            ", " . $p_id . ", " . $kolicina . ", NULL)";
    $bp->updateDB($sql3);
    if ($bp->pogreskaDB()) {
        exit();
    }
    $sql4 = "UPDATE proizvod SET kolicina = kolicina - " . $kolicina .
            " WHERE id = " . $p_id;
    $bp->updateDB($sql4);
    if ($bp->pogreskaDB()) {
        exit();
    }
}
$bp->zatvoriDB();
$_SESSION["kosarica"] = array();
$status = "uspjeh";
echo json_encode($status);

?>