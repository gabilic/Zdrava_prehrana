function ocijeni() {
    if(confirm("Jeste li sigurni da želite proizvođaču " + $("#opg").val() + " dati ocjenu " + $("#ocjena").val() + "?")) {
        $.ajax({
            async: false,
            url: "skripta_ocijeni_proizvodjaca.php",
            method: "GET",
            data: {"kupac": $("#kupac").val(),
                   "proizvodjac": $("#proizvodjac").val(),
                   "ocjena": $("#ocjena").val()},
            dataType: "json",
            success: function(status) {
                if(status === "uspjeh") {
                    location.href = "ocjena.php?id=-1";
                }
            }
        });
    }
}