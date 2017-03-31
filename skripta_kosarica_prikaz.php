<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$status = "";
$ukupno = 0;
if(empty($_SESSION["kosarica"])) {
    $status = "prazna";
}
else {
    $tablica = '<table style="border-collapse: collapse; width: 80%; margin: 35px auto">';
    $tablica .= "<tr><th>Proizvođač</th><th>Proizvod</th><th>Količina</th><th>Cijena</th><th>Ukupno</th><th></th></tr>";
    foreach($_SESSION["kosarica"] as $element) {
        $tablica .= "<tr><td>$element[5]</td><td>$element[1]</td><td>" .
                    '<div style="float: left"><input id="' . $element[0] .
                    '" style="width: 60px" class="kolicina w3-input w3-border" type="text" value="' .
                    $element[2] . '"></div><div style="float: left; padding: 8px">' . $element[3] . "</div>" .
                    "</td><td>$element[4] kn</td><td>" . number_format((float)($element[2] * $element[4]), 2) . " kn</td>" .
                    '<td><div><p><a href="#" title="Ukloni" onclick="kosaricaUkloni(' . $element[0] . '); return false;">' .
                    '<i style="color: #E63B26; font-size: 1.2em" class="fa fa-times"></i></p></div></td></tr>';
        $ukupno += number_format((float)($element[2] * $element[4]), 2);
    }
    $tablica .= '<tr><td colspan="3"></td><td style="font-weight: bold">Ukupno</td>';
    $tablica .= '<td style="font-weight: bold">' . number_format((float)($ukupno), 2) . ' kn</td><td></td></tr></table>';
    $status = $tablica;
}
echo json_encode($status);

?>