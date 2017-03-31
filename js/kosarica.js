function datumUspjeh() {
    if($("#error_dat_i_vrij_preuzimanja").length) {
        $("#dan").removeClass("error_red");
        $("#godina").removeClass("error_red");
        $("#sat").removeClass("error_red");
        $("#minuta").removeClass("error_red");
        $("#error_dat_i_vrij_preuzimanja").prev("div").remove();
        $("#error_dat_i_vrij_preuzimanja").remove();
        $("#d_dat_i_vrij_preuzimanja div p").css("margin-bottom", "");
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
    else if(tip === "sat") {
        pogreska = "Pogreška pri unosu sata!";
        $("#sat").addClass("error_red");
    }
    else if(tip === "minuta") {
        pogreska = "Pogreška pri unosu minuta!";
        $("#minuta").addClass("error_red");
    }
    $("#d_dat_i_vrij_preuzimanja div p").css("margin-bottom", "0px");
    $("#d_dat_i_vrij_preuzimanja").append('<div style="clear:both"></div><div id="error_dat_i_vrij_preuzimanja"><p style="margin-top:0px"><span style="color:#FF0000;font-style:italic">' + pogreska + "</span></p></div>");
}

function provjeriDatum() {
    var prijestupna = false;
    var provjera = true;
    datumUspjeh();
    var dan = $("#dan").val();
    var mjesec = $("#mjesec").val();
    var godina = $("#godina").val();
    var sat = $("#sat").val();
    var minuta = $("#minuta").val();
    if(!$.isNumeric(sat) || sat < 0 || sat > 23) {
        datumPogreska("sat");
        provjera = false;
    }
    else if(!$.isNumeric(minuta) || minuta < 0 || minuta > 59) {
        datumPogreska("minuta");
        provjera = false;
    }
    else if(!$.isNumeric(godina) || godina < 2000 || godina > 2050) {
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

function kolicinaUspjeh(element) {
    if($("#error_kolicina_na_skladistu").length) {
        element.removeClass("error_red");
        $("#error_kolicina_na_skladistu").remove();
    }
}

function kolicinaPogreska(poruka, element) {
    element.addClass("error_red");
    $("#tablica").after('<div id="error_kolicina_na_skladistu"><p style="margin-top:5px"><span style="color:#FF0000;font-style:italic">' + poruka + "</span></p></div>");
}

function provjeriKolicinu(element) {
    var kolicina = element.val();
    var provjera = true;
    kolicinaUspjeh(element);
    if(kolicina === "") {
        kolicinaPogreska("Polje za unos trenutne količine na skladištu ne smije biti prazno!", element);
        provjera = false;
    }
    else if(isNaN(parseFloat(kolicina.replace(",", ".").replace(" ", "")))) {
        kolicinaPogreska("Neispravan unos količine na skladištu!", element);
        provjera = false;
    }
    else if(parseFloat(kolicina.replace(",", ".").replace(" ", "")) <= 0) {
        kolicinaPogreska("Neispravan unos količine na skladištu!", element);
        provjera = false;
    }
    else {
        $.ajax({
            async: false,
            url: "skripta_kosarica_skladiste.php",
            method: "GET",
            data: {"id": element.attr("id")},
            dataType: "json",
            success: function(status) {
                if(parseFloat(kolicina.replace(",", ".").replace(" ", "")) > parseFloat(status)) {
                    kolicinaPogreska("Stanje proizvoda na skladištu je " + status + ". Unijeli ste količinu koja premašuje stanje na skladištu!", element);
                    provjera = false;
                }
            }
        });
    }
    return provjera;
}

function poljeUFokusu() {
    $("#dan").blur(function() {
        provjeriDatum();
    });
    $("#mjesec").blur(function() {
        provjeriDatum();
    });
    $("#godina").blur(function() {
        provjeriDatum();
    });
    $("#sat").blur(function() {
        provjeriDatum();
    });
    $("#minuta").blur(function() {
        provjeriDatum();
    });
    $(".kolicina").blur(function() {
        if(provjeriKolicinu($(this))) {
            $.ajax({
                async: false,
                url: "skripta_kosarica_osvjezi.php",
                method: "GET",
                data: {"id": $(this).attr("id"),
                      "kolicina": parseFloat($(this).val().replace(",", ".").replace(" ", ""))},
                dataType: "json",
                success: function(status) {
                    if(status === "uspjeh") {
                        kosaricaPrikaz();
                        poljeUFokusu();
                    }
                }
            });
        }
    });
}

function kosaricaPrikaz() {
    $.ajax({
        async: false,
        url: "skripta_kosarica_prikaz.php",
        method: "GET",
        dataType: "json",
        success: function(status) {
            if(status === "prazna") {
                $("#d_dat_i_vrij_preuzimanja").css("visibility", "hidden").css("display", "none");
                $("#tablica").html("");
                $("#tablica").append("<p>Košarica je prazna!</p>");
                $("#gumb").css("visibility", "hidden").css("display", "none");
            }
            else {
                $("#d_dat_i_vrij_preuzimanja").css("visibility", "").css("display", "");
                $("#tablica").html("");
                $("#tablica").append(status);
                $("#gumb").css("visibility", "").css("display", "");
            }
        }
    });
}

function kosaricaUkloni(id) {
    if(confirm("Jeste li sigurni da želite ukloniti proizvod iz košarice?")) {
        $.ajax({
            async: false,
            url: "skripta_kosarica_ukloni.php",
            method: "GET",
            data: {"id": id},
            dataType: "json",
            success: function(status) {
                if(status === "uspjeh") {
                    kosaricaPrikaz();
                    poljeUFokusu();
                }
            }
        });
    }
}

function potvrdi() {
    var pogreska = false;
    if(!provjeriDatum()) {
        pogreska = true;
    }
    $(".kolicina").each(function() {
        if(!provjeriKolicinu($(this))) {
            pogreska = true;
            return false;
        }
    });
    if(!pogreska) {
        if(confirm("Jeste li sigurni da želite izvršiti narudžbu?")) {
            var dat_i_vrij_preuzimanja = [$("#godina").val(), $("#mjesec").val(), $("#dan").val(), $("#sat").val(), $("#minuta").val()];
            $.ajax({
                async: false,
                url: "skripta_naruci_proizvod.php",
                method: "GET",
                data: {"dat_i_vrij_preuzimanja": dat_i_vrij_preuzimanja},
                dataType: "json",
                success: function(status) {
                    if(status === "uspjeh") {
                        location.href = "index.php";
                    }
                }
            });
        }
    }
}

function kosarica() {
    kosaricaPrikaz();
    poljeUFokusu();
}