<?php

$status = "";
$stil = 0;
require_once("skripta_baza.php");
$bp = new Baza();
$sql = "SELECT id, naziv FROM kategorija_proizvoda " .
        "ORDER BY id ASC";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit();
}
while (list($id, $naziv) = $rs->fetch_array()) {
    $status .= '<ul><li class="w3-hide-small w3-dropdown-hover w3-hover-none w3-white">' .
               '<a href="#">' . ucfirst($naziv) . "</a>" .
               '<div class="dizajn_vrsta_proizvoda w3-white w3-dropdown-content w3-card-4" ' .
               'style="left: 100%; top: ' . $stil . '%">';
    $bp2 = new Baza();
    $sql2 = "SELECT id, naziv FROM vrsta_proizvoda " .
            "WHERE kategorija_proizvoda_id = " . $id . " ORDER BY id ASC";
    $bp2->spojiDB();
    $rs2 = $bp2->selectDB($sql2);
    if ($bp2->pogreskaDB()) {
        exit();
    }
    while (list($p_id, $naziv2) = $rs2->fetch_array()) {
        $status .= '<a href="proizvod.php?id=' . $p_id . '">' . ucfirst($naziv2) . "</a>";
    }
    $rs2->close();
    $bp2->zatvoriDB();
    $status .= "</div></li></ul>";
    $stil += 10;
}
$rs->close();
$bp->zatvoriDB();
echo json_encode($status);

?>