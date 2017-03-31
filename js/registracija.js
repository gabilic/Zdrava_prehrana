function znakovi(rijec) {
    var brojac = 0;
    var znak = "";
    for(var i = 0; i < rijec.length; i++) {
        znak = rijec.charCodeAt(i);
        if(!(znak >= 48 && znak <= 57 || znak >= 65 && znak <= 90 || znak === 95 || znak >= 97 && znak <= 122 || znak === 262 || znak === 263 || znak === 268 || znak === 269 || znak === 272 || znak === 273 || znak === 352 || znak === 353 || znak === 381 || znak === 382)) {
            brojac++;
        }
    }
    return brojac;
}

function provjeriNazivOpga() {
    var opg = $("#naziv_opg").val();
    var provjera = true;
    if($("#error_naziv_opg").length) {
        $("#error_naziv_opg").remove();
    }
    if(opg === "") {
        $("#d_naziv_opg p:first-child").append('<span id="error_naziv_opg" style="color:#FF0000;font-style:italic">Polje za unos naziva OPG-a ne smije biti prazno!</span>');
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
        $("#d_ime p:first-child").append('<span id="error_ime" style="color:#FF0000;font-style:italic">Polje za unos imena ne smije biti prazno!</span>');
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
        $("#d_prezime p:first-child").append('<span id="error_prezime" style="color:#FF0000;font-style:italic">Polje za unos prezimena ne smije biti prazno!</span>');
        provjera = false;
    }
    return provjera;
}

function provjeriKorisnickoIme() {
    var korisnickoIme = $("#korisnicko_ime").val();
    var provjera = true;
    if($("#error_korisnicko_ime").length) {
        $("#error_korisnicko_ime").remove();
    }
    if(korisnickoIme === "") {
        $("#d_korisnicko_ime p:first-child").append('<span id="error_korisnicko_ime" style="color:#FF0000;font-style:italic">Polje za unos korisničkog imena ne smije biti prazno!</span>');
        provjera = false;
    }
    else {
        if(korisnickoIme.length < 8 || korisnickoIme.length > 16) {
            $("#d_korisnicko_ime p:first-child").append('<span id="error_korisnicko_ime" style="color:#FF0000;font-style:italic">Korisničko ime mora sadržavati minimalno 8, a maksimalno 16 znakova!</span>');
            provjera = false;
        }
        else {
            var znak = znakovi(korisnickoIme);
            if(znak > 0) {
                $("#d_korisnicko_ime p:first-child").append('<span id="error_korisnicko_ime" style="color:#FF0000;font-style:italic">Korisničko ime smije sadržavati samo velika i mala slova hrvatske abecede, brojeve i poseban znak "_"!</span>');
                provjera = false;
            }
        }
    }
    return provjera;
}

function zauzetoKorisnickoIme() {
    var provjera = true;
    if($("#error_korisnicko_ime").length) {
        $("#error_korisnicko_ime").remove();
    }
    $.ajax({
        async: false,
        url: "skripta_registracija_korisnicko_ime.php",
        method: "POST",
        data: $("#forma").serialize(),
        dataType: "json",
        success: function(status) {
            if(status === "zauzeto") {
                $("#d_korisnicko_ime p:first-child").append('<span id="error_korisnicko_ime" style="color:#FF0000;font-style:italic">Uneseno korisničko ime je zauzeto!</span>');
                provjera = false;
            }
        }
    });
    return provjera;
}

function provjeriLozinku() {
    var lozinka = $("#lozinka").val();
    var provjera = true;
    if($("#error_lozinka").length) {
        $("#error_lozinka").remove();
    }
    if(lozinka === "") {
        $("#d_lozinka p:first-child").append('<span id="error_lozinka" style="color:#FF0000;font-style:italic">Polje za unos lozinke ne smije biti prazno!</span>');
        provjera = false;
    }
    else {
        if(lozinka.length < 8 || lozinka.length > 16) {
            $("#d_lozinka p:first-child").append('<span id="error_lozinka" style="color:#FF0000;font-style:italic">Lozinka mora sadržavati minimalno 8, a maksimalno 16 znakova!</span>');
            provjera = false;
        }
        else {
            var znak = znakovi(lozinka);
            if(znak > 0) {
                $("#d_lozinka p:first-child").append('<span id="error_lozinka" style="color:#FF0000;font-style:italic">Lozinka smije sadržavati samo velika i mala slova hrvatske abecede, brojeve i poseban znak "_"!</span>');
                provjera = false;
            }
        }
    }
    return provjera;
}

function potvrdaLozinke() {
    var potvrda = $("#lozinka_potvrda").val();
    var lozinka = $("#lozinka").val();
    var provjera = true;
    if($("#error_lozinka_potvrda").length) {
        $("#error_lozinka_potvrda").remove();
    }
    if(potvrda === "") {
        $("#d_lozinka_potvrda p:first-child").append('<span id="error_lozinka_potvrda" style="color:#FF0000;font-style:italic">Polje za unos potvrde lozinke ne smije biti prazno!</span>');
        provjera = false;
    }
    else {
        if(potvrda !== lozinka) {
            $("#d_lozinka_potvrda p:first-child").append('<span id="error_lozinka_potvrda" style="color:#FF0000;font-style:italic">Lozinke se ne poklapaju!</span>');
            provjera = false;
        }
    }
    return provjera;
}

function provjeriEmail() {
    var re = RegExp(/^\S+@\S+$/);
    var email = $("#email").val();
    var provjera = true;
    if($("#error_email").length) {
        $("#error_email").remove();
    }
    if(email === "") {
        $("#d_email p:first-child").append('<span id="error_email" style="color:#FF0000;font-style:italic">Polje za unos e-mail adrese ne smije biti prazno!</span>');
        provjera = false;
    }
    else {
        var ok = re.test(email);
        if(!ok) {
            $("#d_email p:first-child").append('<span id="error_email" style="color:#FF0000;font-style:italic">E-mail adresa nije ispravno unesena!</span>');
            provjera = false;
        }
    }
    return provjera;
}

function zauzetEmail() {
    var provjera = true;
    if($("#error_email").length) {
        $("#error_email").remove();
    }
    $.ajax({
        async: false,
        url: "skripta_registracija_email.php",
        method: "POST",
        data: $("#forma").serialize(),
        dataType: "json",
        success: function(status) {
            if(status === "zauzeto") {
                $("#d_email p:first-child").append('<span id="error_email" style="color:#FF0000;font-style:italic">Uneseni e-mail je zauzet!</span>');
                provjera = false;
            }
        }
    });
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
        $("#d_broj_telefona p:first-child").append('<span id="error_broj_telefona" style="color:#FF0000;font-style:italic">Polje za unos broja telefona ne smije biti prazno!</span>');
        provjera = false;
    }
    else {
        if(!$.isNumeric(telefon)) {
            $("#d_broj_telefona p:first-child").append('<span id="error_broj_telefona" style="color:#FF0000;font-style:italic">Broj telefona nije ispravno unesen!</span>');
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
        $("#d_spol p:first-child").append('<span id="error_spol" style="color:#FF0000;font-style:italic">Odaberite spol!</span>');
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

function captchaUspjeh() {
    if($("#error_captcha").length) {
        $("#error_captcha").remove();
    }
}

function provjeriCaptchu() {
    var provjera = true;
    captchaUspjeh();
    var captcha = grecaptcha.getResponse();
    $.ajax({
        async: false,
        url: "skripta_registracija_captcha.php",
        method: "GET",
        data: {"captcha": captcha},
        dataType: "json",
        success: function(status) {
            if(status === "pogreška") {
                $("#captcha").append('<p id="error_captcha" style="margin-top: 0px"><span style="color:#FF0000;font-style:italic">Potvrdite da niste robot!</span></p>');
                provjera = false;
            }
        }
    });
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
    $("#korisnicko_ime").blur(function() {
        if(!provjeriKorisnickoIme()) {
            $("#korisnicko_ime").addClass("error_red");
        }
        else if(!zauzetoKorisnickoIme()) {
            $("#korisnicko_ime").addClass("error_red");
        }
        else {
            $("#korisnicko_ime").removeClass("error_red");
        }
    });
    $("#korisnicko_ime").focus(function() {
        $("#korisnicko_ime").removeClass("error_red");
    });
    $("#lozinka").blur(function() {
        if(!provjeriLozinku()) {
            $("#lozinka").addClass("error_red");
        }
        else {
            $("#lozinka").removeClass("error_red");
        }
    });
    $("#lozinka").focus(function() {
        $("#lozinka").removeClass("error_red");
    });
    $("#lozinka_potvrda").blur(function() {
        if(!potvrdaLozinke()) {
            $("#lozinka_potvrda").addClass("error_red");
        }
        else {
            $("#lozinka_potvrda").removeClass("error_red");
        }
    });
    $("#lozinka_potvrda").focus(function() {
        $("#lozinka_potvrda").removeClass("error_red");
    });
    $("#email").blur(function() {
        if(!provjeriEmail()) {
            $("#email").addClass("error_red");
        }
        else if(!zauzetEmail()) {
            $("#email").addClass("error_red");
        }
        else {
            $("#email").removeClass("error_red");
        }
    });
    $("#email").focus(function() {
        $("#email").removeClass("error_red");
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
    $("#korisnicko_ime").on("input", function() {
        if(!provjeriKorisnickoIme()) {
            $("#korisnicko_ime").addClass("error_red");
        }
        else {
            $("#korisnicko_ime").removeClass("error_red");
        }
    });
    $("#lozinka").on("input", function() {
        if(!provjeriLozinku()) {
            $("#lozinka").addClass("error_red");
        }
        else {
            $("#lozinka").removeClass("error_red");
        }
    });
    $("#lozinka_potvrda").on("input", function() {
        if(!potvrdaLozinke()) {
            $("#lozinka_potvrda").addClass("error_red");
        }
        else {
            $("#lozinka_potvrda").removeClass("error_red");
        }
    });
    $("#email").on("input", function() {
        if(!provjeriEmail()) {
            $("#email").addClass("error_red");
        }
        else {
            $("#email").removeClass("error_red");
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

function ocistiObrazac() {
    $("#forma").on("reset", function() {
        if($("#error_naziv_opg").length) {
            $("#error_naziv_opg").remove();
            $("#naziv_opg").removeClass("error_red");
        }
        if($("#error_ime").length) {
            $("#error_ime").remove();
            $("#ime").removeClass("error_red");
        }
        if($("#error_prezime").length) {
            $("#error_prezime").remove();
            $("#prezime").removeClass("error_red");
        }
        if($("#error_korisnicko_ime").length) {
            $("#error_korisnicko_ime").remove();
            $("#korisnicko_ime").removeClass("error_red");
        }
        if($("#error_lozinka").length) {
            $("#error_lozinka").remove();
            $("#lozinka").removeClass("error_red");
        }
        if($("#error_lozinka_potvrda").length) {
            $("#error_lozinka_potvrda").remove();
            $("#lozinka_potvrda").removeClass("error_red");
        }
        if($("#error_email").length) {
            $("#error_email").remove();
            $("#email").removeClass("error_red");
        }
        datumUspjeh();
        if($("#error_broj_telefona").length) {
            $("#error_broj_telefona").remove();
            $("#broj_telefona").removeClass("error_red");
        }
        if($("#error_spol").length) {
            $("#error_spol").remove();
            $("#spol").removeClass("error_red");
        }
        adresaUspjeh();
        grecaptcha.reset();
    });
}

function registracija() {
    tipRegistracije();
    poljeUFokusu();
    promjenaTekstaUPolju();
    ocistiObrazac();
    grecaptcha.reset();
    $("input[name=tip_registracije]:radio").change(function() {
        tipRegistracije();
    });
    $("#forma").submit(function(event) {
        var pogreska = false;
        if($("#registracija_proizvodjaca")[0].checked) {
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
        if(!provjeriKorisnickoIme()) {
            $("#korisnicko_ime").addClass("error_red");
            pogreska = true;
        }
        else if(!zauzetoKorisnickoIme()) {
            $("#korisnicko_ime").addClass("error_red");
            pogreska = true;
        }
        else {
            $("#korisnicko_ime").removeClass("error_red");
        }
        if(!provjeriLozinku()) {
            $("#lozinka").addClass("error_red");
            pogreska = true;
        }
        else {
            $("#lozinka").removeClass("error_red");
        }
        if(!potvrdaLozinke()) {
            $("#lozinka_potvrda").addClass("error_red");
            pogreska = true;
        }
        else {
            $("#lozinka_potvrda").removeClass("error_red");
        }
        if(!provjeriEmail()) {
            $("#email").addClass("error_red");
            pogreska = true;
        }
        else if(!zauzetEmail()) {
            $("#email").addClass("error_red");
            pogreska = true;
        }
        else {
            $("#email").removeClass("error_red");
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
        if(!provjeriCaptchu()) {
            pogreska = true;
        }
        if(pogreska) event.preventDefault();
    });
}

function tipRegistracije() {
    if($("#registracija_kupaca")[0].checked) {
        $(".kupac").css("visibility", "").css("display", "");
        $(".proizvodjac").css("visibility", "hidden").css("display", "none");
    }
    else if($("#registracija_proizvodjaca")[0].checked) {
        $(".kupac").css("visibility", "hidden").css("display", "none");
        $(".proizvodjac").css("visibility", "").css("display", "");
    }
}