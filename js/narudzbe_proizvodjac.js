function dodajNarudzbe() {
    $.ajax({
        async: false,
        url: "skripta_narudzbe_p_dodaj.php",
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
            url: "skripta_narudzbe_p_prikazi.php",
            method: "GET",
            data: {"narudzba": $("#narudzba").val()},
            dataType: "json",
            success: function(status) {
                $("#tablica").html("");
                $("#tablica").append(status);
                if($("#gumbi").length) {
                    $("#gumbi").remove();
                }
                var prviGumb = '<button id="potvrdi" name="potvrdi" type="button" class="w3-btn w3-padding w3-red w3-margin-top w3-margin-bottom w3-margin-right button" onclick="potvrdiNarudzbu(); return false;">Potvrdi narudžbu</button>';
                var drugiGumb = '<button id="odbij" name="odbij" type="button" class="w3-btn w3-padding w3-red w3-margin-top w3-margin-bottom w3-margin-right button" onclick="odbijNarudzbu(); return false;">Odbij narudžbu</button>';
                $("#tablica").after('<div id="gumbi" style="text-align: center; margin-top: 20px"></div>');
                $("#gumbi").append(prviGumb + drugiGumb);
            }
        });
    }
}

function potvrdiNarudzbu() {
    if(confirm("Jeste li sigurni da želite potvrditi navedenu narudžbu?")) {
        $.ajax({
            async: false,
            url: "skripta_narudzbe_p_potvrdi.php",
            method: "GET",
            data: {"n_id": $("#n_id").val(),
                   "kupac": $("#kupac").val(),
                   "proizvodjac": $("#proizvodjac").val()},
            dataType: "json",
            success: function(status) {
                if(status === "uspjeh") {
                    location.reload();
                }
            }
        });
    }
}

function odbijNarudzbu() {
    if(confirm("Jeste li sigurni da želite odbiti navedenu narudžbu?")) {
        $.ajax({
            async: false,
            url: "skripta_narudzbe_p_odbij.php",
            method: "GET",
            data: {"n_id": $("#n_id").val(),
                   "proizvodjac": $("#proizvodjac").val()},
            dataType: "json",
            success: function(status) {
                if(status === "uspjeh") {
                    location.reload();
                }
            }
        });
    }
}

function narudzbeProizvodjac() {
    dodajNarudzbe();
}