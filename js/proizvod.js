function kosaricaDodaj(id) {
    var provjera = true;
    $.ajax({
        async: false,
        url: "skripta_kosarica_provjera.php",
        method: "GET",
        dataType: "json",
        success: function(status) {
            if(status === "pogreška") {
                provjera = false;
            }
        }
    });
    if(provjera) {
        $.ajax({
            async: false,
            url: "skripta_kosarica_dodaj.php",
            method: "GET",
            data: {"id": id},
            dataType: "json",
            success: function(status) {
                if(status === "uspjeh") {
                    alert("Uspješno ste dodali proizvod u košaricu!");
                }
            }
        });
    }
    else {
        alert("Registrirajte se kao kupac kako biste mogli pristupiti košarici!");
    }
}

function proizvod() {
    $(".kosarica").css("-webkit-transition", "all 0.5s ease")
                  .css("-moz-transition", "all 0.5s ease")
                  .css("-o-transition", "all 0.5s ease")
                  .css("-ms-transition", "all 0.5s ease")
                  .css("transition", "all 0.5s ease");
    $(".kosarica").hover(function() {
        $(this).css("-webkit-filter", "blur(3px)");
        $(this).next().find("button").fadeIn(500);
    }, function() {
        if(!($(this).next().find("button").is(":hover"))) {
            $(this).css("-webkit-filter", "");
            $(this).next().find("button").fadeOut(500);
        }
    });
}