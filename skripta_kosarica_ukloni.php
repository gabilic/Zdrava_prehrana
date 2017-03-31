<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$status = "";
$id = $_GET["id"];
if(isset($_SESSION["kosarica"])) {
    for($i = 0; $i < count($_SESSION["kosarica"]); $i++) {
        if($_SESSION["kosarica"][$i][0] == $id) {
            unset($_SESSION["kosarica"][$i]);
            $_SESSION["kosarica"] = array_values($_SESSION["kosarica"]);
        }
    }
}
$status = "uspjeh";
echo json_encode($status);

?>