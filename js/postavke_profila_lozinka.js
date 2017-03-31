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

function lozinkaUspjeh(tip) {
    if($("#error_lozinka_" + tip).length) {
        $("#lozinka_" + tip).removeClass("error_red");
        $("#error_lozinka_" + tip).remove();
        $("#d_lozinka_" + tip).css("margin-bottom", "15px");
    }
}

function lozinkaPogreska(poruka, tip) {
    $("#d_lozinka_" + tip).css("margin-bottom", "0px");
    $("#d_lozinka_" + tip).append('<div id="error_lozinka_' + tip + '"><p style="margin-top:0px"><span style="color:#FF0000;font-style:italic">' + poruka + "</span></p></div>");
    $("#lozinka_" + tip).addClass("error_red");
}

function provjeriLozinku(tip) {
    var lozinka = $("#lozinka_" + tip).val();
    var provjera = true;
    lozinkaUspjeh(tip);
    if(lozinka === "") {
        lozinkaPogreska("Polje za unos lozinke ne smije biti prazno!", tip);
        provjera = false;
    }
    else {
        if(lozinka.length < 8 || lozinka.length > 16) {
            lozinkaPogreska("Lozinka mora sadržavati minimalno 8, a maksimalno 16 znakova!", tip);
            provjera = false;
        }
        else {
            var znak = znakovi(lozinka);
            if(znak > 0) {
                lozinkaPogreska('Lozinka smije sadržavati samo velika i mala slova hrvatske abecede, brojeve i poseban znak "_"!', tip);
                provjera = false;
            }
        }
    }
    return provjera;
}

function tocnaLozinka() {
    var provjera = true;
    lozinkaUspjeh("trenutna");
    var formData = new FormData();
    formData.append("korisnicko_ime", $("#korisnicko_ime").val());
    formData.append("lozinka", $("#lozinka_trenutna").val());
    $.ajax({
        async: false,
        url: "skripta_prijava_lozinka.php",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function(status) {
            if(status !== "točna") {
                lozinkaPogreska("Unesena lozinka nije točna!", "trenutna");
                provjera = false;
            }
        }
    });
    return provjera;
}

function potvrdaLozinke() {
    var potvrda = $("#lozinka_potvrda").val();
    var lozinka = $("#lozinka_nova").val();
    var provjera = true;
    lozinkaUspjeh("potvrda");
    if(potvrda === "") {
        lozinkaPogreska("Polje za unos potvrde lozinke ne smije biti prazno!", "potvrda");
        provjera = false;
    }
    else {
        if(potvrda !== lozinka) {
            lozinkaPogreska("Lozinke se ne poklapaju!", "potvrda");
            provjera = false;
        }
    }
    return provjera;
}

function poljeUFokusu() {
    $("#lozinka_trenutna").blur(function() {
        if(provjeriLozinku("trenutna")) {
            tocnaLozinka();
        }
    });
    $("#lozinka_trenutna").focus(function() {
        $("#lozinka_trenutna").removeClass("error_red");
    });
    $("#lozinka_nova").blur(function() {
        provjeriLozinku("nova");
    });
    $("#lozinka_nova").focus(function() {
        $("#lozinka_nova").removeClass("error_red");
    });
    $("#lozinka_potvrda").blur(function() {
        potvrdaLozinke()();
    });
    $("#lozinka_potvrda").focus(function() {
        $("#lozinka_potvrda").removeClass("error_red");
    });
}

function promjenaTekstaUPolju() {
    $("#lozinka_trenutna").on("input", function() {
        provjeriLozinku("trenutna");
    });
    $("#lozinka_nova").on("input", function() {
        provjeriLozinku("nova");
    });
    $("#lozinka_potvrda").on("input", function() {
        potvrdaLozinke()();
    });
}

function promjenaLozinke() {
    poljeUFokusu();
    promjenaTekstaUPolju();
    $("#forma").submit(function(event) {
        var pogreska = false;
        if(!provjeriLozinku("trenutna")) {
            pogreska = true;
        }
        else if(!tocnaLozinka()) {
            pogreska = true;
        }
        if(!provjeriLozinku("nova")) {
            pogreska = true;
        }
        if(!potvrdaLozinke()) {
            pogreska = true;
        }
        if(pogreska) event.preventDefault();
    });
}