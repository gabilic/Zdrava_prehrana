$(document).ready(function() {
    $.ajax({
        async: false,
        url: "skripta_proizvodjaci_prikaz.php",
        method: "GET",
        dataType: "json",
        success: function(status) {
            $("#dizajn_proizvodjaci").append(status);
        }
    });
});