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

function emailUspjeh() {
    if($("#error_email").length) {
        $("#email").removeClass("error_red");
        $("#error_email").remove();
        $("#d_email").css("margin-bottom", "15px");
    }
}

function emailPogreska(poruka) {
    $("#d_email").css("margin-bottom", "0px");
    $("#d_email").append('<div id="error_email"><p style="margin-top:0px"><span style="color:#FF0000;font-style:italic">' + poruka + "</span></p></div>");
    $("#email").addClass("error_red");
}

function provjeriEmail() {
    var re = RegExp(/^\S+@\S+$/);
    var email = $("#email").val();
    var provjera = true;
    emailUspjeh();
    if(email === "") {
        emailPogreska("Polje za unos e-mail adrese ne smije biti prazno!");
        provjera = false;
    }
    else {
        var ok = re.test(email);
        if(!ok) {
            emailPogreska("E-mail adresa nije ispravno unesena!");
            provjera = false;
        }
    }
    return provjera;
}

function postojiEmail() {
    var provjera = true;
    emailUspjeh();
    $.ajax({
        async: false,
        url: "skripta_prijava_email.php",
        method: "POST",
        data: $("#forma1").serialize(),
        dataType: "json",
        success: function(status) {
            if(status !== "zauzeto") {
                emailPogreska("Unesena e-mail adresa ne postoji u bazi podataka!");
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

function potvrdaLozinkeUspjeh() {
    if($("#error_lozinka_potvrda").length) {
        $("#lozinka_potvrda").removeClass("error_red");
        $("#error_lozinka_potvrda").remove();
        $("#d_lozinka_potvrda").css("margin-bottom", "15px");
    }
}

function potvrdaLozinkePogreska(poruka) {
    $("#d_lozinka_potvrda").css("margin-bottom", "0px");
    $("#d_lozinka_potvrda").append('<div id="error_lozinka_potvrda"><p style="margin-top:0px"><span style="color:#FF0000;font-style:italic">' + poruka + "</span></p></div>");
    $("#lozinka_potvrda").addClass("error_red");
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

function potvrdaLozinke() {
    var potvrda = $("#lozinka_potvrda").val();
    var lozinka = $("#lozinka").val();
    var provjera = true;
    potvrdaLozinkeUspjeh();
    if(potvrda === "") {
        potvrdaLozinkePogreska("Polje za unos potvrde lozinke ne smije biti prazno!");
        provjera = false;
    }
    else {
        if(potvrda !== lozinka) {
            potvrdaLozinkePogreska("Lozinke se ne poklapaju!");
            provjera = false;
        }
    }
    return provjera;
}

function poljeUFokusu() {
    $("#email").blur(function() {
        if(provjeriEmail()) {
            postojiEmail();
        }
    });
    $("#email").focus(function() {
        $("#email").removeClass("error_red");
    });
    $("#lozinka").blur(function() {
        provjeriLozinku();
    });
    $("#lozinka").focus(function() {
        $("#lozinka").removeClass("error_red");
    });
    $("#lozinka_potvrda").blur(function() {
        potvrdaLozinke();
    });
    $("#lozinka_potvrda").focus(function() {
        $("#lozinka_potvrda").removeClass("error_red");
    });
}

function promjenaTekstaUPolju() {
    $("#email").on("input", function() {
        provjeriEmail();
    });
    $("#lozinka").on("input", function() {
        provjeriLozinku();
    });
    $("#lozinka_potvrda").on("input", function() {
        potvrdaLozinke();
    });
}

function oporavak() {
    poljeUFokusu();
    promjenaTekstaUPolju();
    $("#forma1").submit(function(event) {
        var pogreska = false;
        if(!provjeriEmail()) {
            pogreska = true;
        }
        else if(!postojiEmail()) {
            pogreska = true;
        }
        if(pogreska) event.preventDefault();
    });
    $("#forma2").submit(function(event) {
        var pogreska = false;
        if(!provjeriLozinku()) {
            pogreska = true;
        }
        if(!potvrdaLozinke()) {
            pogreska = true;
        }
        if(pogreska) event.preventDefault();
    });
}