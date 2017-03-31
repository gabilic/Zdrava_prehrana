function navigacija() {
    var a = document.getElementById("navig");
    var b = document.getElementById("proizv");
    var c = document.getElementById("proizv2");
    var d = document.getElementById("proizvdj");
    var e = document.getElementById("moji_proizv");
    if (a.className.indexOf("w3-show") === -1) {
        a.className += " w3-show";
    } else { 
        a.className = a.className.replace(" w3-show", "");
        if (b.className.indexOf("w3-show") !== -1) {
            b.className = b.className.replace(" w3-show", "");
        }
        if (c.className.indexOf("w3-show") !== -1) {
            c.className = c.className.replace(" w3-show", "");
        }
        if (d.className.indexOf("w3-show") !== -1) {
            d.className = d.className.replace(" w3-show", "");
        }
        if (e.className.indexOf("w3-show") !== -1) {
            e.className = e.className.replace(" w3-show", "");
        }
    }
}

function proizvodiKategorija(boja) {
    var a = document.getElementById("proizv");
    var b = document.getElementById("proizv2");
    if (a.className.indexOf("w3-show") === -1) {
        $.ajax({
            async: false,
            url: "skripta_proizvodi_prikaz_mobitel.php",
            method: "GET",
            data: {"tip": "kategorija", "boja": boja},
            dataType: "json",
            success: function(status) {
                $("#proizv ul").html("");
                $("#proizv ul").append(status);
            }
        });
        a.className += " w3-show";
    } else { 
        a.className = a.className.replace(" w3-show", "");
        if (b.className.indexOf("w3-show") !== -1) {
            b.className = b.className.replace(" w3-show", "");
        }
    }
}

function proizvodiVrsta(id, boja) {
    var a = document.getElementById("proizv2");
    if (a.className.indexOf("w3-show") === -1) {
        $.ajax({
            async: false,
            url: "skripta_proizvodi_prikaz_mobitel.php",
            method: "GET",
            data: {"tip": "vrsta", "id": id, "boja": boja},
            dataType: "json",
            success: function(status) {
                $("#proizv2 ul").html("");
                $("#proizv2 ul").append(status);
            }
        });
        $("#proizv2").css("margin-top", id * 46 + 138 + "px");
        a.className += " w3-show";
    } else { 
        a.className = a.className.replace(" w3-show", "");
    }
}

function proizvodjaci(boja) {
    var a = document.getElementById("proizvdj");
    if (a.className.indexOf("w3-show") === -1) {
        $.ajax({
            async: false,
            url: "skripta_proizvodjaci_prikaz_mobitel.php",
            method: "GET",
            data: {"boja": boja},
            dataType: "json",
            success: function(status) {
                $("#proizvdj ul").html("");
                $("#proizvdj ul").append(status);
            }
        });
        a.className += " w3-show";
    } else { 
        a.className = a.className.replace(" w3-show", "");
    }
}

function mojiProizvodi(boja) {
    var a = document.getElementById("moji_proizv");
    var novi = '<li><a class="w3-hover-none w3-text-' + (boja === "bijela" ? "white" : "black") +
                ' w3-padding-large" href="proizvod_novi.php">' +
                "NOVI PROIZVOD</a></li>";
    if (a.className.indexOf("w3-show") === -1) {
        $.ajax({
            async: false,
            url: "skripta_proizv_po_proizv_mobitel.php",
            method: "GET",
            data: {"boja": boja},
            dataType: "json",
            success: function(status) {
                $("#moji_proizv ul").html("");
                $("#moji_proizv ul").append(status);
                $("#moji_proizv ul").append(novi);
            }
        });
        a.className += " w3-show";
    } else { 
        a.className = a.className.replace(" w3-show", "");
    }
}