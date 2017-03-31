<?php

$tip = $_GET["tip"];
$boja = $_GET["boja"];
$status = "";
require_once("skripta_baza.php");
$bp = new Baza();
if($tip === "kategorija") {
    $sql = "SELECT id, naziv FROM kategorija_proizvoda " .
            "ORDER BY id ASC";
    $bp->spojiDB();
    $rs = $bp->selectDB($sql);
    if ($bp->pogreskaDB()) {
        exit();
    }
    while (list($id, $naziv) = $rs->fetch_array()) {
        $status .= '<li><a class="w3-hover-none w3-text-' . ($boja === "bijela" ? "white" : "black") .
                   ' w3-padding-large" href="javascript:void(0)" ' .
                   'onclick="proizvodiVrsta(' . $id . ', \'' . $boja . '\')">' . ucfirst($naziv) . "</a></li>";
    }
    $rs->close();
}
else if($tip === "vrsta") {
    $v_id = $_GET["id"];
    $sql = "SELECT id, naziv FROM vrsta_proizvoda " .
            "WHERE kategorija_proizvoda_id = " . $v_id .
            " ORDER BY id ASC";
    $bp->spojiDB();
    $rs = $bp->selectDB($sql);
    if ($bp->pogreskaDB()) {
        exit();
    }
    while (list($p_id, $naziv) = $rs->fetch_array()) {
        $status .= '<li><a class="w3-hover-none w3-text-' . ($boja === "bijela" ? "white" : "black") .
                   ' w3-padding-large" href="proizvod.php?id=' . $p_id . '">' .
                   ucfirst($naziv) . "</a></li>";
    }
    $rs->close();
}
$bp->zatvoriDB();
echo json_encode($status);

?>