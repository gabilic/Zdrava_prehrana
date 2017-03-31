<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

if (session_id() !== "") {
    session_unset();
    session_destroy();
}
header("Location: index.php")

?>