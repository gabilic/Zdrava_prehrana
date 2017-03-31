$(document).ready(function() {
    $.ajax({
        async: false,
        url: "skripta_proizvodi_prikaz.php",
        method: "GET",
        dataType: "json",
        success: function(status) {
            $("#dizajn_proizvodi").append(status);
        }
    });
});