<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$status = "";
$id = $_GET["id"];
$kolicina = $_GET["kolicina"];
if(isset($_SESSION["kosarica"])) {
    for($i = 0; $i < count($_SESSION["kosarica"]); $i++) {
        if($_SESSION["kosarica"][$i][0] == $id) {
            $_SESSION["kosarica"][$i][2] = number_format((float)($kolicina), 2);
        }
    }
}
$status = "uspjeh";
echo json_encode($status);

?>