function dodajKorisnike() {
    $.ajax({
        async: false,
        url: "skripta_administracija_dodaj_korisnike.php",
        method: "GET",
        dataType: "json",
        success: function(status) {
            for(var i = 0; i < status.length; i++) {
                $("#korisnicko_ime").append('<option value="' + (i + 1) + '">' + status[i].korisnicko_ime + "</option>");
            }
        }
    });
}

function prikaziKorisnika() {
    if($("#korisnicko_ime").val() > "0") {
        $.ajax({
            async: false,
            url: "skripta_administracija_prikazi_korisnika.php",
            method: "GET",
            data: {"korisnicko_ime": $("#korisnicko_ime option:selected").text()},
            dataType: "json",
            success: function(status) {
                $("#tablica").html("");
                $("#tablica").append(status);
                if($("#gumbi").length) {
                    $("#gumbi").remove();
                }
                var prviGumb = '<button id="odobri" name="odobri" type="button" class="w3-btn w3-padding w3-red w3-margin-top w3-margin-bottom w3-margin-right button" onclick="odobriZahtjev(); return false;">Odobri zahtjev</button>';
                var drugiGumb = '<button id="odbij" name="odbij" type="button" class="w3-btn w3-padding w3-red w3-margin-top w3-margin-bottom w3-margin-right button" onclick="odbijZahtjev(); return false;">Odbij zahtjev</button>';
                $("#tablica").after('<div id="gumbi" style="text-align: center; margin-top: 20px"></div>');
                $("#gumbi").append(prviGumb + drugiGumb);
            }
        });
    }
}

function odobriZahtjev() {
    if(confirm("Jeste li sigurni da želite odobriti zahtjev za izradu navedenog korisničkog računa?")) {
        $.ajax({
            async: false,
            url: "skripta_administracija_odobri_zahtjev.php",
            method: "GET",
            data: {"korisnicko_ime": $("#korisnik").html(),
                   "email": $("#email").html()},
            dataType: "json",
            success: function(status) {
                if(status === "uspjeh") {
                    location.reload();
                }
            }
        });
    }
}

function odbijZahtjev() {
    if(confirm("Jeste li sigurni da želite odbiti zahtjev za izradu navedenog korisničkog računa?")) {
        $.ajax({
            async: false,
            url: "skripta_administracija_odbij_zahtjev.php",
            method: "GET",
            data: {"korisnicko_ime": $("#korisnik").html(),
                   "email": $("#email").html()},
            dataType: "json",
            success: function(status) {
                if(status === "uspjeh") {
                    location.reload();
                }
            }
        });
    }
}

function administracija() {
    dodajKorisnike();
}