<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$status = "";
$id = $_GET["id"];
$provjera = false;
require_once("skripta_baza.php");
$bp = new Baza();
$sql = "SELECT naziv, kolicina, mjerna_jedinica, cijena, (SELECT naziv_opg " .
        "FROM korisnici WHERE korisnicko_ime = korisnici_korisnicko_ime) " .
        "FROM proizvod WHERE id = " . $id;
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit();
}
while (list($k_naziv, $k_kolicina, $k_mjerna_jedinica, $k_cijena, $k_opg) = $rs->fetch_array()) {
    $naziv = $k_naziv; $kolicina = $k_kolicina; $mjerna_jedinica = $k_mjerna_jedinica;
    $cijena = $k_cijena; $opg = $k_opg;
}
$rs->close();
$bp->zatvoriDB();
if(isset($_SESSION["kosarica"])) {
    for($i = 0; $i < count($_SESSION["kosarica"]); $i++) {
        if($_SESSION["kosarica"][$i][0] == $id) {
            if($_SESSION["kosarica"][$i][2] < $kolicina) {
                $_SESSION["kosarica"][$i][2] = number_format((float)($_SESSION["kosarica"][$i][2] + 1), 2);
            }
            $provjera = true;
        }
    }
    if(!$provjera) {
        $element = array($id, $naziv, "1.00", $mjerna_jedinica, $cijena, $opg);
        $_SESSION["kosarica"][] = $element;
    }
}
$status = "uspjeh";
echo json_encode($status);

?>