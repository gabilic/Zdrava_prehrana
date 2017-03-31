<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$status = "pogreška";
$tip_korisnika = "";
if(isset($_SESSION["prijava"])) {
    $tip_korisnika = $_SESSION["prijava"][3];
}
if($tip_korisnika === "kupac") {
    $status = "uspjeh";
}
echo json_encode($status);

?>