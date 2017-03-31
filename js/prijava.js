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

function korisnickoImeUspjeh() {
    if($("#error_korisnicko_ime").length) {
        $("#korisnicko_ime").removeClass("error_red");
        $("#error_korisnicko_ime").remove();
        $("#d_korisnicko_ime").css("margin-bottom", "15px");
    }
}

function korisnickoImePogreska(poruka) {
    $("#d_korisnicko_ime").css("margin-bottom", "0px");
    $("#d_korisnicko_ime").append('<div id="error_korisnicko_ime"><p style="margin-top:0px"><span style="color:#FF0000;font-style:italic">' + poruka + "</span></p></div>");
    $("#korisnicko_ime").addClass("error_red");
}

function provjeriKorisnickoIme() {
    var korisnickoIme = $("#korisnicko_ime").val();
    var provjera = true;
    korisnickoImeUspjeh();
    if(korisnickoIme === "") {
        korisnickoImePogreska("Polje za unos korisničkog imena ne smije biti prazno!");
        provjera = false;
    }
    else {
        if(korisnickoIme.length < 8 || korisnickoIme.length > 16) {
            korisnickoImePogreska("Korisničko ime mora sadržavati minimalno 8, a maksimalno 16 znakova!");
            provjera = false;
        }
        else {
            var znak = znakovi(korisnickoIme);
            if(znak > 0) {
                korisnickoImePogreska('Korisničko ime smije sadržavati samo velika i mala slova hrvatske abecede, brojeve i poseban znak "_"!');
                provjera = false;
            }
        }
    }
    return provjera;
}

function postojiKorisnickoIme() {
    var provjera = true;
    korisnickoImeUspjeh();
    $.ajax({
        async: false,
        url: "skripta_prijava_korisnicko_ime.php",
        method: "POST",
        data: $("#forma").serialize(),
        dataType: "json",
        success: function(status) {
            if(status !== "zauzeto") {
                korisnickoImePogreska("Uneseno korisničko ime ne postoji u bazi podataka!");
                provjera = false;
            }
        }
    });
    return provjera;
}

function lozinkaUspjeh() {
    if($("#error_lozinka").length) {
        $("#lozinka").removeClass("error_red");
        $("#error_lozinka").remove();
        $("#d_lozinka").css("margin-bottom", "15px");
    }
}

function lozinkaPogreska(poruka) {
    $("#d_lozinka").css("margin-bottom", "0px");
    $("#d_lozinka").append('<div id="error_lozinka"><p style="margin-top:0px"><span style="color:#FF0000;font-style:italic">' + poruka + "</span></p></div>");
    $("#lozinka").addClass("error_red");
}

function provjeriLozinku() {
    var lozinka = $("#lozinka").val();
    var provjera = true;
    lozinkaUspjeh();
    if(lozinka === "") {
        lozinkaPogreska("Polje za unos lozinke ne smije biti prazno!");
        provjera = false;
    }
    else {
        if(lozinka.length < 8 || lozinka.length > 16) {
            lozinkaPogreska("Lozinka mora sadržavati minimalno 8, a maksimalno 16 znakova!");
            provjera = false;
        }
        else {
            var znak = znakovi(lozinka);
            if(znak > 0) {
                lozinkaPogreska('Lozinka smije sadržavati samo velika i mala slova hrvatske abecede, brojeve i poseban znak "_"!');
                provjera = false;
            }
        }
    }
    return provjera;
}

function tocnaLozinka() {
    var provjera = true;
    lozinkaUspjeh();
    $.ajax({
        async: false,
        url: "skripta_prijava_lozinka.php",
        method: "POST",
        data: $("#forma").serialize(),
        dataType: "json",
        success: function(status) {
            if(status !== "točna") {
                lozinkaPogreska("Unesena lozinka nije točna!");
                provjera = false;
            }
        }
    });
    return provjera;
}

function aktiviranKorisnik() {
    var provjera = true;
    korisnickoImeUspjeh();
    $.ajax({
        async: false,
        url: "skripta_prijava_aktiviran_korisnik.php",
        method: "POST",
        data: $("#forma").serialize(),
        dataType: "json",
        success: function(status) {
            if(status !== "aktiviran") {
                korisnickoImePogreska("Vaš korisnički račun nije aktiviran! Morate aktivirati svoj korisnički račun kako biste se mogli prijaviti u sustav.");
                provjera = false;
            }
        }
    });
    return provjera;
}

function poljeUFokusu() {
    $("#korisnicko_ime").blur(function() {
        if(provjeriKorisnickoIme()) {
            postojiKorisnickoIme();
        }
    });
    $("#korisnicko_ime").focus(function() {
        $("#korisnicko_ime").removeClass("error_red");
    });
    $("#lozinka").blur(function() {
        provjeriLozinku();
    });
    $("#lozinka").focus(function() {
        $("#lozinka").removeClass("error_red");
    });
}

function promjenaTekstaUPolju() {
    $("#korisnicko_ime").on("input", function() {
        provjeriKorisnickoIme();
    });
    $("#lozinka").on("input", function() {
        provjeriLozinku();
    });
}

function ocistiObrazac() {
    $("#forma").on("reset", function() {
        korisnickoImeUspjeh();
        lozinkaUspjeh();
    });
}

function prijava() {
    poljeUFokusu();
    promjenaTekstaUPolju();
    ocistiObrazac();
    $("#forma").submit(function(event) {
        var pogreska = false;
        if(!provjeriKorisnickoIme()) {
            pogreska = true;
        }
        else if(!postojiKorisnickoIme()) {
            pogreska = true;
        }
        if(!provjeriLozinku()) {
            pogreska = true;
        }
        if(!pogreska) {
            if(!tocnaLozinka()) {
                pogreska = true;
            }
            else if(!aktiviranKorisnik()) {
                pogreska = true;
            }
        }
        if(pogreska) event.preventDefault();
    });
}