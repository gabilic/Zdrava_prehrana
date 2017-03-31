function provjeriKategoriju() {
    var kategorija = $("#kategorija").val();
    var provjera = true;
    if(kategorija === null) {
        provjera = false;
    }
    return provjera;
}

function provjeriVrstu() {
    var vrsta = $("#vrsta").val();
    var provjera = true;
    if(vrsta === null) {
        provjera = false;
    }
    return provjera;
}

function provjeriNaziv() {
    var naziv = $("#naziv_proizvoda").val();
    var provjera = true;
    if($("#error_naziv_proizvoda").length) {
        $("#error_naziv_proizvoda").remove();
    }
    if(naziv === "") {
        $("#d_naziv_proizvoda p:first-child").append('<span id="error_naziv_proizvoda" style="color:#FF0000;font-style:italic">Polje za unos naziva proizvoda ne smije biti prazno!</span>');
        provjera = false;
    }
    return provjera;
}

function kolicinaUspjeh() {
    if($("#error_kolicina_na_skladistu").length) {
        $("#kolicina").removeClass("error_red");
        $("#error_kolicina_na_skladistu").prev("div").remove();
        $("#error_kolicina_na_skladistu").remove();
        $("#d_kolicina_na_skladistu div p").css("margin-bottom", "");
    }
}

function kolicinaPogreska(poruka) {
    $("#kolicina").addClass("error_red");
    $("#d_kolicina_na_skladistu div p").css("margin-bottom", "0px");
    $("#d_kolicina_na_skladistu").append('<div style="clear:both"></div><div id="error_kolicina_na_skladistu"><p style="margin-top:0px"><span style="color:#FF0000;font-style:italic">' + poruka + "</span></p></div>");
}

function provjeriKolicinu() {
    var kolicina = $("#kolicina").val();
    var provjera = true;
    kolicinaUspjeh();
    if(kolicina === "") {
        kolicinaPogreska("Polje za unos trenutne količine na skladištu ne smije biti prazno!");
        provjera = false;
    }
    else if(isNaN(parseFloat(kolicina.replace(",", ".").replace(" ", "")))) {
        kolicinaPogreska("Neispravan unos količine na skladištu!");
        provjera = false;
    }
    else if(parseFloat(kolicina.replace(",", ".").replace(" ", "")) < 0) {
        kolicinaPogreska("Neispravan unos količine na skladištu!");
        provjera = false;
    }
    return provjera;
}

function provjeriCijenu() {
    var cijena = $("#cijena").val();
    var provjera = true;
    if($("#error_cijena").length) {
        $("#error_cijena").remove();
    }
    if(cijena === "") {
        $("#d_cijena p:last-child").append('<span id="error_cijena" style="color:#FF0000;font-style:italic">Polje za unos jedinične cijene proizvoda ne smije biti prazno!</span>');
        provjera = false;
    }
    else if(isNaN(parseFloat(cijena.replace(",", ".").replace(" ", "")))) {
        $("#d_cijena p:last-child").append('<span id="error_cijena" style="color:#FF0000;font-style:italic">Neispravan unos jedinične cijene proizvoda!</span>');
        provjera = false;
    }
    else if(parseFloat(cijena.replace(",", ".").replace(" ", "")) <= 0) {
        $("#d_cijena p:last-child").append('<span id="error_cijena" style="color:#FF0000;font-style:italic">Neispravan unos jedinične cijene proizvoda!</span>');
        provjera = false;
    }
    return provjera;
}

function poljeUFokusu() {
    $("#naziv_proizvoda").blur(function() {
        if(!provjeriNaziv()) {
            $("#naziv_proizvoda").addClass("error_red");
        }
        else {
            $("#naziv_proizvoda").removeClass("error_red");
        }
    });
    $("#naziv_proizvoda").focus(function() {
        $("#naziv_proizvoda").removeClass("error_red");
    });
    $("#kolicina").blur(function() {
        provjeriKolicinu();
    });
    $("#kolicina").focus(function() {
        $("#kolicina").removeClass("error_red");
    });
    $("#cijena").blur(function() {
        if(!provjeriCijenu()) {
            $("#cijena").addClass("error_red");
        }
        else {
            $("#cijena").removeClass("error_red");
        }
    });
    $("#cijena").focus(function() {
        $("#cijena").removeClass("error_red");
    });
}

function promjenaTekstaUPolju() {
    $("#kategorija").change(function() {
        $("#vrsta").html("");
        $("#vrsta").append('<option value="0" disabled selected>Vrsta proizvoda</option>');
        dodajVrsteProizvoda();
        promjenaKategorijeIVrste();
    });
    $("#vrsta").change(function() {
        promjenaKategorijeIVrste();
    });
    $("#naziv_proizvoda").on("input", function() {
        if(!provjeriNaziv()) {
            $("#naziv_proizvoda").addClass("error_red");
        }
        else {
            $("#naziv_proizvoda").removeClass("error_red");
        }
    });
    $("#kolicina").on("input", function() {
        provjeriKolicinu();
    });
    $("#cijena").on("input", function() {
        if(!provjeriCijenu()) {
            $("#cijena").addClass("error_red");
        }
        else {
            $("#cijena").removeClass("error_red");
        }
    });
}

function dodajKategorijeProizvoda() {
    $.ajax({
        async: false,
        url: "skripta_proizv_novi_dodaj_kategorije.php",
        method: "GET",
        dataType: "json",
        success: function(status) {
            for(var i = 0; i < status.length; i++) {
                $("#kategorija").append('<option value="' + (i + 1) + '">' + status[i].kategorija + "</option>");
            }
        }
    });
}

function dodajVrsteProizvoda() {
    $.ajax({
        async: false,
        url: "skripta_proizv_novi_dodaj_vrste.php",
        method: "GET",
        data: {"naziv": $("#kategorija option:selected").text()},
        dataType: "json",
        success: function(status) {
            for(var i = 0; i < status.length; i++) {
                $("#vrsta").append('<option value="' + (i + 1) + '">' + status[i].vrsta + "</option>");
            }
        }
    });
}

function proizvodNovi() {
    dodajKategorijeProizvoda();
    dodajVrsteProizvoda();
    poljeUFokusu();
    promjenaTekstaUPolju();
    promjenaKategorijeIVrste();
    $("#forma").submit(function(event) {
        var pogreska = false;
        if(!provjeriNaziv()) {
            $("#naziv_proizvoda").addClass("error_red");
            pogreska = true;
        }
        else {
            $("#naziv_proizvoda").removeClass("error_red");
        }
        if(!provjeriKolicinu()) {
            pogreska = true;
        }
        else {
            $("#kolicina_unos").val(parseFloat($("#kolicina").val().replace(",", ".").replace(" ", "")));
        }
        if(!provjeriCijenu()) {
            $("#cijena").addClass("error_red");
            pogreska = true;
        }
        else {
            $("#cijena").removeClass("error_red");
            $("#cijena_unos").val(parseFloat($("#cijena").val().replace(",", ".").replace(" ", "")));
        }
        $("#vrsta_unos").val($("#vrsta option:selected").text());
        if(pogreska) event.preventDefault();
    });
}

function promjenaKategorijeIVrste() {
    if(!(provjeriKategoriju() && provjeriVrstu())) {
        $("#dodaj_proizvod").attr("disabled", true);
    }
    else {
        $("#dodaj_proizvod").attr("disabled", false);
    }
}