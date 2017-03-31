function dodajNarudzbe() {
    $.ajax({
        async: false,
        url: "skripta_narudzbe_k_dodaj.php",
        method: "GET",
        dataType: "json",
        success: function(status) {
            for(var i = 0; i < status.length; i++) {
                $("#narudzba").append('<option value="' + status[i].id + '">' + status[i].narudzba + "</option>");
            }
        }
    });
}

function prikaziNarudzbu() {
    if($("#narudzba").val() > "0") {
        $.ajax({
            async: false,
            url: "skripta_narudzbe_k_prikazi.php",
            method: "GET",
            data: {"narudzba": $("#narudzba").val()},
            dataType: "json",
            success: function(status) {
                $("#tablica").html("");
                $("#tablica").append(status);
            }
        });
    }
}

function narudzbeKupac() {
    dodajNarudzbe();
}