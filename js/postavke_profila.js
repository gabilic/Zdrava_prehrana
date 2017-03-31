function provjeriNazivOpga() {
    var opg = $("#naziv_opg").val();
    var provjera = true;
    if($("#error_naziv_opg").length) {
        $("#error_naziv_opg").remove();
    }
    if(opg === "") {
        $("#d_naziv_opg p:last-child").append('<span id="error_naziv_opg" style="color:#FF0000;font-style:italic">Polje za unos naziva OPG-a ne smije biti prazno!</span>');
        provjera = false;
    }
    return provjera;
}

function provjeriIme() {
    var ime = $("#ime").val();
    var provjera = true;
    if($("#error_ime").length) {
        $("#error_ime").remove();
    }
    if(ime === "") {
        $("#d_ime p:last-child").append('<span id="error_ime" style="color:#FF0000;font-style:italic">Polje za unos imena ne smije biti prazno!</span>');
        provjera = false;
    }
    return provjera;
}

function provjeriPrezime() {
    var prezime = $("#prezime").val();
    var provjera = true;
    if($("#error_prezime").length) {
        $("#error_prezime").remove();
    }
    if(prezime === "") {
        $("#d_prezime p:last-child").append('<span id="error_prezime" style="color:#FF0000;font-style:italic">Polje za unos prezimena ne smije biti prazno!</span>');
        provjera = false;
    }
    return provjera;
}

function datumUspjeh() {
    if($("#error_rodjendan").length) {
        $("#dan").removeClass("error_red");
        $("#godina").removeClass("error_red");
        $("#error_rodjendan").prev("div").remove();
        $("#error_rodjendan").remove();
        $("#d_rodjendan div p").css("margin-bottom", "");
    }
}

function datumPogreska(tip) {
    var pogreska = "";
    if(tip === "dan") {
        pogreska = "Pogreška pri unosu dana!";
        $("#dan").addClass("error_red");
    }
    else if(tip === "godina") {
        pogreska = "Pogreška pri unosu godine!";
        $("#godina").addClass("error_red");
    }
    $("#d_rodjendan div p").css("margin-bottom", "0px");
    $("#d_rodjendan").append('<div style="clear:both"></div><div id="error_rodjendan"><p style="margin-top:0px"><span style="color:#FF0000;font-style:italic">' + pogreska + "</span></p></div>");
}

function provjeriDatum() {
    var prijestupna = false;
    var provjera = true;
    datumUspjeh();
    var dan = $("#dan").val();
    var mjesec = $("#mjesec").val();
    var godina = $("#godina").val();
    if(!$.isNumeric(godina) || godina < 1900 || godina > 2015) {
        datumPogreska("godina");
        provjera = false;
    }
    else {
        if(godina % 400 == 0) prijestupna = true;
        else if(godina % 100 == 0) prijestupna = false;
        else if(godina % 4 == 0) prijestupna = true;
        else prijestupna = false;
        if(!$.isNumeric(dan) || dan < 1) {
            datumPogreska("dan");
            provjera = false;
        }
        else {
            if(mjesec == 1 || mjesec == 3 || mjesec == 5 || mjesec == 7 || mjesec == 8 || mjesec == 10 || mjesec == 12) {
                if(dan > 31) {
                    datumPogreska("dan");
                    provjera = false;
                }
            }
            else if(mjesec == 4 || mjesec == 6 || mjesec == 9 || mjesec == 11) {
                if(dan > 30) {
                    datumPogreska("dan");
                    provjera = false;
                }
            }
            else if(mjesec == 2) {
                if(prijestupna) {
                    if(dan > 29) {
                        datumPogreska("dan");
                        provjera = false;
                    }
                }
                else {
                    if(dan > 28) {
                        datumPogreska("dan");
                        provjera = false;
                    }
                }
            }
        }
    }
    return provjera;
}

function provjeriTelefon() {
    var telefon = $("#broj_telefona").val();
    var provjera = true;
    if($("#error_broj_telefona").length) {
        $("#error_broj_telefona").remove();
    }
    if(telefon === "") {
        $("#d_broj_telefona p:last-child").append('<span id="error_broj_telefona" style="color:#FF0000;font-style:italic">Polje za unos broja telefona ne smije biti prazno!</span>');
        provjera = false;
    }
    else {
        if(!$.isNumeric(telefon)) {
            $("#d_broj_telefona p:last-child").append('<span id="error_broj_telefona" style="color:#FF0000;font-style:italic">Broj telefona nije ispravno unesen!</span>');
            provjera = false;
        }
    }
    return provjera;
}

function provjeriSpol() {
    var spol = $("#spol").val();
    var provjera = true;
    if($("#error_spol").length) {
        $("#error_spol").remove();
    }
    if(spol === null) {
        $("#d_spol p:last-child").append('<span id="error_spol" style="color:#FF0000;font-style:italic">Odaberite spol!</span>');
        provjera = false;
    }
    return provjera;
}

function adresaUspjeh() {
    if($("#error_adresa").length) {
        $("#grad").removeClass("error_red");
        $("#ulica").removeClass("error_red");
        $("#broj").removeClass("error_red");
        $("#error_adresa").prev("div").remove();
        $("#error_adresa").remove();
        $("#d_adresa div p").css("margin-bottom", "");
    }
}

function adresaPogreska() {
    $("#d_adresa div p").css("margin-bottom", "0px");
    $("#d_adresa").append('<div style="clear:both"></div><div id="error_adresa"><p style="margin-top:0px"><span style="color:#FF0000;font-style:italic">Polja za unos adrese ne smiju biti prazna!</span></p></div>');
}

function provjeriAdresu() {
    var provjera = true;
    adresaUspjeh();
    var grad = $("#grad").val();
    var ulica = $("#ulica").val();
    var broj = $("#broj").val();
    if(grad === "") {
        $("#grad").addClass("error_red");
        provjera = false;
    }
    if(ulica === "") {
        $("#ulica").addClass("error_red");
        provjera = false;
    }
    if(broj === "") {
        $("#broj").addClass("error_red");
        provjera = false;
    }
    if(!provjera) {
        adresaPogreska();
    }
    return provjera;
}

function poljeUFokusu() {
    $("#naziv_opg").blur(function() {
        if(!provjeriNazivOpga()) {
            $("#naziv_opg").addClass("error_red");
        }
        else {
            $("#naziv_opg").removeClass("error_red");
        }
    });
    $("#naziv_opg").focus(function() {
        $("#naziv_opg").removeClass("error_red");
    });
    $("#ime").blur(function() {
        if(!provjeriIme()) {
            $("#ime").addClass("error_red");
        }
        else {
            $("#ime").removeClass("error_red");
        }
    });
    $("#ime").focus(function() {
        $("#ime").removeClass("error_red");
    });
    $("#prezime").blur(function() {
        if(!provjeriPrezime()) {
            $("#prezime").addClass("error_red");
        }
        else {
            $("#prezime").removeClass("error_red");
        }
    });
    $("#prezime").focus(function() {
        $("#prezime").removeClass("error_red");
    });
    $("#dan").blur(function() {
        provjeriDatum();
    });
    $("#mjesec").blur(function() {
        provjeriDatum();
    });
    $("#godina").blur(function() {
        provjeriDatum();
    });
    $("#dan").focus(function() {
        $("#dan").removeClass("error_red");
    });
    $("#godina").focus(function() {
        $("#godina").removeClass("error_red");
    });
    $("#broj_telefona").blur(function() {
        if(!provjeriTelefon()) {
            $("#broj_telefona").addClass("error_red");
        }
        else {
            $("#broj_telefona").removeClass("error_red");
        }
    });
    $("#broj_telefona").focus(function() {
        $("#broj_telefona").removeClass("error_red");
    });
    $("#spol").blur(function() {
        if(!provjeriSpol()) {
            $("#spol").addClass("error_red");
        }
        else {
            $("#spol").removeClass("error_red");
        }
    });
    $("#spol").focus(function() {
        $("#spol").removeClass("error_red");
    });
    $("#grad").blur(function() {
        provjeriAdresu();
    });
    $("#ulica").blur(function() {
        provjeriAdresu();
    });
    $("#broj").blur(function() {
        provjeriAdresu();
    });
    $("#grad").focus(function() {
        $("#grad").removeClass("error_red");
    });
    $("#ulica").focus(function() {
        $("#ulica").removeClass("error_red");
    });
    $("#broj").focus(function() {
        $("#broj").removeClass("error_red");
    });
}

function promjenaTekstaUPolju() {
    $("#naziv_opg").on("input", function() {
        if(!provjeriNazivOpga()) {
            $("#naziv_opg").addClass("error_red");
        }
        else {
            $("#naziv_opg").removeClass("error_red");
        }
    });
    $("#ime").on("input", function() {
        if(!provjeriIme()) {
            $("#ime").addClass("error_red");
        }
        else {
            $("#ime").removeClass("error_red");
        }
    });
    $("#prezime").on("input", function() {
        if(!provjeriPrezime()) {
            $("#prezime").addClass("error_red");
        }
        else {
            $("#prezime").removeClass("error_red");
        }
    });
    $("#dan").on("input", function() {
        provjeriDatum();
    });
    $("#mjesec").change(function() {
        provjeriDatum();
    });
    $("#godina").on("input", function() {
        provjeriDatum();
    });
    $("#broj_telefona").on("input", function() {
        if(!provjeriTelefon()) {
            $("#broj_telefona").addClass("error_red");
        }
        else {
            $("#broj_telefona").removeClass("error_red");
        }
    });
    $("#spol").change(function() {
        if(!provjeriSpol()) {
            $("#spol").addClass("error_red");
        }
        else {
            $("#spol").removeClass("error_red");
        }
    });
    $("#grad").on("input", function() {
        provjeriAdresu();
    });
    $("#ulica").on("input", function() {
        provjeriAdresu();
    });
    $("#broj").on("input", function() {
        provjeriAdresu();
    });
}

function dizajnOsvjezi() {
    if($("#slika").val().length) {
        if($("div.button_refresh").hasClass("button_disabled")) {
            $("div.button_refresh").removeClass("button_disabled");
        }
    }
    else {
        if(!($("div.button_refresh").hasClass("button_disabled"))) {
            $("div.button_refresh").addClass("button_disabled");
        }
    }
}

function dizajnUkloni() {
    var postoji = false;
    $.ajax({
        async: false,
        url: "skripta_postavke_profila_provjeri_sliku.php",
        method: "GET",
        dataType: "json",
        success: function(status) {
            if(status === "postoji") {
                postoji = true;
            }
        }
    });
    if(postoji) {
        if($("div.button_remove").hasClass("button_disabled")) {
            $("div.button_remove").removeClass("button_disabled");
        }
    }
    else {
        if(!($("div.button_remove").hasClass("button_disabled"))) {
            $("div.button_remove").addClass("button_disabled");
        }
    }
}

function osvjeziSlikuProfila() {
    if(!($("div.button_refresh").hasClass("button_disabled"))) {
        if(confirm("Jeste li sigurni da želite učitati novu sliku profila (Vaša stara slika profila će se automatski izbrisati)? Postupak može trajati nekoliko sekundi ili minuta, ovisno o veličini slike!")) {
            var slika = "";
            var formData = new FormData();
            formData.append("slika", $("#slika")[0].files[0]);
            $.ajax({
                async: false,
                url: "skripta_postavke_profila_osvjezi_sliku.php",
                method: "POST",
                data: formData,
                processData: false,
                contentType: false,
                dataType: "json",
                success: function(status) {
                    slika = status;
                }
            });
            dizajnOsvjezi();
            dizajnUkloni();
            $("img").attr("src", slika);
        }
    }
}

function ukloniSlikuProfila() {
    if(!($("div.button_remove").hasClass("button_disabled"))) {
        if(confirm("Jeste li sigurni da želite ukloniti Vašu sliku profila?")) {
            var slika = "";
            $.ajax({
                async: false,
                url: "skripta_postavke_profila_ukloni_sliku.php",
                method: "GET",
                dataType: "json",
                success: function(status) {
                    slika = status;
                }
            });
            dizajnOsvjezi();
            dizajnUkloni();
            $("img").attr("src", slika);
        }
    }
}

function azurirajProfil() {
    poljeUFokusu();
    promjenaTekstaUPolju();
    dizajnOsvjezi();
    dizajnUkloni();
    $("#slika").change(function() {
        dizajnOsvjezi();
    });
    $("#forma").submit(function(event) {
        var pogreska = false;
        if($("#naziv_opg").length) {
            if(!provjeriNazivOpga()) {
                $("#naziv_opg").addClass("error_red");
                pogreska = true;
            }
            else {
                $("#naziv_opg").removeClass("error_red");
            }
        }
        if(!provjeriIme()) {
            $("#ime").addClass("error_red");
            pogreska = true;
        }
        else {
            $("#ime").removeClass("error_red");
        }
        if(!provjeriPrezime()) {
            $("#prezime").addClass("error_red");
            pogreska = true;
        }
        else {
            $("#prezime").removeClass("error_red");
        }
        if(!provjeriDatum()) {
            pogreska = true;
        }
        if(!provjeriTelefon()) {
            $("#broj_telefona").addClass("error_red");
            pogreska = true;
        }
        else {
            $("#broj_telefona").removeClass("error_red");
        }
        if(!provjeriSpol()) {
            $("#spol").addClass("error_red");
            pogreska = true;
        }
        else {
            $("#spol").removeClass("error_red");
        }
        if(!provjeriAdresu()) {
            pogreska = true;
        }
        if(pogreska) event.preventDefault();
    });
}