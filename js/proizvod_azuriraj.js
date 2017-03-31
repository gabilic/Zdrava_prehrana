function provjeriNaziv() {
    var naziv = $("#naziv_proizvoda").val();
    var provjera = true;
    if($("#error_naziv_proizvoda").length) {
        $("#error_naziv_proizvoda").remove();
    }
    if(naziv === "") {
        $("#d_naziv_proizvoda p:last-child").append('<span id="error_naziv_proizvoda" style="color:#FF0000;font-style:italic">Polje za unos naziva proizvoda ne smije biti prazno!</span>');
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

function dizajnUkloni(id) {
    var postoji = false;
    $.ajax({
        async: false,
        url: "skripta_proizvod_azuriraj_provjeri_sliku.php",
        method: "GET",
        data: {"id": id},
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

function osvjeziSlikuProizvoda(id) {
    if(!($("div.button_refresh").hasClass("button_disabled"))) {
        if(confirm("Jeste li sigurni da želite učitati novu sliku proizvoda (Vaša stara slika proizvoda će se automatski izbrisati)? Postupak može trajati nekoliko sekundi ili minuta, ovisno o veličini slike!")) {
            var slika = "";
            var formData = new FormData();
            formData.append("slika", $("#slika")[0].files[0]);
            formData.append("id", id);
            $.ajax({
                async: false,
                url: "skripta_proizvod_azuriraj_osvjezi_sliku.php",
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
            dizajnUkloni(id);
            $("img").attr("src", slika);
        }
    }
}

function ukloniSlikuProizvoda(id) {
    if(!($("div.button_remove").hasClass("button_disabled"))) {
        if(confirm("Jeste li sigurni da želite ukloniti Vašu sliku proizvoda?")) {
            var slika = "";
            $.ajax({
                async: false,
                url: "skripta_proizvod_azuriraj_ukloni_sliku.php",
                method: "GET",
                data: {"id": id},
                dataType: "json",
                success: function(status) {
                    slika = status;
                }
            });
            dizajnOsvjezi();
            dizajnUkloni(id);
            $("img").attr("src", slika);
        }
    }
}

function proizvodAzuriraj(id) {
    poljeUFokusu();
    promjenaTekstaUPolju();
    dizajnOsvjezi();
    dizajnUkloni(id);
    $("#slika").change(function() {
        dizajnOsvjezi();
    });
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
        if(pogreska) event.preventDefault();
    });
}

function proizvodUkloni(id) {
    if(confirm("Jeste li sigurni da želite ukloniti proizvod?")) {
        $.ajax({
            async: false,
            url: "skripta_proizvod_ukloni.php",
            method: "GET",
            data: {"id": id},
            dataType: "json",
            success: function(status) {
                if(status === "uspjeh") {
                    location.reload();
                }
            }
        });
    }
}