<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Zdrava prehrana</title>
        <meta charset="UTF-8">
        <meta name="author" content="Gabriel Ilić">
        <meta name="keywords" content="zavrsni_rad, zdrava_prehrana, HTML, CSS, Javascript, PHP">
        <meta name="viewport" content="width = device-width, initial-scale = 1.0">
        <meta name="description" content="Završni rad - Projekt zdrava prehrana">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="css/w3.css?<?php echo filemtime("css/w3.css") ?>" rel="stylesheet" type="text/css" media="screen">
        <link href="css/dizajn.css?<?php echo filemtime("css/dizajn.css") ?>" rel="stylesheet" type="text/css" media="screen">
        <link href="css/responsive.css?<?php echo filemtime("css/responsive.css") ?>" rel="stylesheet" type="text/css" media="screen">
    </head>
    <body class="background_2" onload="oporavak();">
        <div id="container">
            <div class="w3-top">
                <ul class="w3-navbar button w3-text-white w3-left-align">
                    <li class="w3-hide-medium w3-hide-large w3-opennav w3-right">
                        <a href="javascript:void(0)" class="w3-hover-none w3-text-white w3-padding-large" onclick="navigacija()">
                        <i class="fa fa-bars fa-lg fa-fw"></i></a>
                    </li>
                    <li class="w3-hide-medium w3-hide-large w3-opennav w3-left">
                        <a href="index.php" class="w3-hover-none w3-text-white w3-padding-large">
                        <i class="fa fa-home fa-lg fa-fw"></i></a>
                    </li>
                    <li class="w3-hide-small"><a href="index.php" class="w3-hover-light-grey w3-hover-text-black w3-padding-large">
                            <i class="fa fa-home fa-lg fa-fw"></i> POČETNA</a></li>
                    <li class="w3-hide-small w3-dropdown-hover w3-hover-light-grey">
                        <a href="#" class="w3-hover-light-grey w3-hover-text-black w3-padding-large">PROIZVOĐAČI</a>
                        <div id="dizajn_proizvodjaci" class="w3-dropdown-content w3-white w3-card-4"></div>
                    </li>
                    <li class="w3-hide-small w3-dropdown-hover w3-hover-light-grey">
                        <a href="#" class="w3-hover-light-grey w3-hover-text-black w3-padding-large">PROIZVODI</a>
                        <div id="dizajn_proizvodi" class="w3-dropdown-content w3-white w3-card-4"></div>
                    </li>
                    <?php
                    if(isset($_SESSION["prijava"])) {
                        $ime = $_SESSION["prijava"][1];
                        $prezime = $_SESSION["prijava"][2];
                        echo '<li class="w3-hide-small w3-dropdown-hover w3-hover-light-grey w3-right">';
                            echo '<a href="#" class="w3-hover-light-grey w3-hover-text-black w3-padding-large">';
                            echo '<i class="fa fa-user fa-lg fa-fw"></i></a>';
                            echo '<div class="w3-dropdown-content w3-white w3-card-4" style="right: 0">';
                                echo '<p style="margin:0;padding:8px 16px">' . $ime . " " . $prezime . "</p>";
                                echo '<a href="postavke_profila.php">MOJ PROFIL</a>';
                                echo '<a href="odjava.php">ODJAVA</a>';
                            echo "</div>";
                        echo "</li>";
                        if($_SESSION["prijava"][3] === "administrator") {
                            echo '<li class="w3-hide-small w3-right">';
                                echo '<a href="administracija.php" class="w3-hover-light-grey w3-hover-text-black w3-padding-large" title="Administracija" style="padding-top: 14px!important; padding-bottom: 10px!important">';
                                echo '<i class="fa fa-tasks fa-lg fa-fw"></i></a>';
                            echo "</li>";
                        }
                        if($_SESSION["prijava"][3] === "proizvođač") {
                            require_once("skripta_proizv_po_proizv.php");
                            echo '<li class="w3-hide-small w3-dropdown-hover w3-hover-light-grey w3-right">';
                                echo '<a href="#" class="w3-hover-light-grey w3-hover-text-black w3-padding-large" title="Moji proizvodi">';
                                echo '<i class="fa fa-shopping-basket fa-lg fa-fw"></i></a>';
                                echo '<div class="w3-dropdown-content w3-white w3-card-4" style="right: 20px">';
                                    if(isset($proizv_lista)) {
                                        foreach($proizv_lista as $element) {
                                            echo '<a href="proizvod_azuriraj.php?id=' . $element[0] . '">';
                                            echo $element[1] . '</a>';
                                        }
                                    }
                                    echo '<a href="proizvod_novi.php">';
                                    echo '<i class="fa fa-plus fa-lg fa-fw" style="color: #0677D0"></i> NOVI PROIZVOD</a>';
                                echo "</div>";
                            echo "</li>";
                            echo '<li class="w3-hide-small w3-right">';
                                echo '<a href="narudzbe_proizvodjac.php" class="w3-hover-light-grey w3-hover-text-black w3-padding-large" title="Narudžbe">';
                                echo '<i class="fa fa-file-text fa-lg fa-fw"></i></a>';
                            echo "</li>";
                        }
                        if($_SESSION["prijava"][3] === "kupac") {
                            echo '<li class="w3-hide-small w3-right">';
                                echo '<a href="kosarica.php" class="w3-hover-light-grey w3-hover-text-black w3-padding-large" title="Košarica">';
                                echo '<i class="fa fa-shopping-cart fa-lg fa-fw"></i></a>';
                            echo "</li>";
                            echo '<li class="w3-hide-small w3-right">';
                                echo '<a href="narudzbe_kupac.php" class="w3-hover-light-grey w3-hover-text-black w3-padding-large" title="Moje narudžbe">';
                                echo '<i class="fa fa-file-text fa-lg fa-fw"></i></a>';
                            echo "</li>";
                        }
                    }
                    else {
                        echo '<li class="w3-hide-small w3-dropdown-hover w3-hover-light-grey w3-right">';
                            echo '<a href="#" class="w3-hover-light-grey w3-hover-text-black w3-padding-large">';
                            echo '<i class="fa fa-lock fa-lg fa-fw"></i></a>';
                            echo '<div class="w3-dropdown-content w3-white w3-card-4" style="right: 0">';
                                echo '<a href="prijava.php">PRIJAVA</a>';
                                echo '<a href="registracija.php">REGISTRACIJA</a>';
                            echo "</div>";
                        echo "</li>";
                    }
                    ?>
                </ul>
            </div>

            <div id="navig" class="w3-hide w3-hide-large w3-hide-medium w3-top" style="margin-top: 46px">
                <ul class="w3-navbar w3-left-align w3-card-2 w3-white">
                    <li>
                        <a class="w3-hover-none w3-text-black w3-padding-large" href="javascript:void(0)" onclick="proizvodjaci('crna')">PROIZVOĐAČI</a>
                    </li>
                    <li>
                        <a class="w3-hover-none w3-text-black w3-padding-large" href="javascript:void(0)" onclick="proizvodiKategorija('crna')">PROIZVODI</a>
                    </li>
                    <?php
                    if(isset($_SESSION["prijava"])) {
                        echo '<li class="nav_mob_border"><p class="w3-hover-none w3-text-black w3-padding-large" style="margin:0">' . $ime . " " . $prezime . "</p></li>";
                        if($_SESSION["prijava"][3] === "administrator") {
                            echo '<li><a class="w3-hover-none w3-text-black w3-padding-large" href="administracija.php">ADMINISTRACIJA</a></li>';
                        }
                        if($_SESSION["prijava"][3] === "proizvođač") {
                            echo '<li><a class="w3-hover-none w3-text-black w3-padding-large" href="narudzbe_proizvodjac.php">NARUDŽBE</a></li>';
                            echo "<li>";
                                echo '<a class="w3-hover-none w3-text-black w3-padding-large" href="javascript:void(0)" onclick="mojiProizvodi(\'crna\')">MOJI PROIZVODI</a>';
                            echo "</li>";
                        }
                        if($_SESSION["prijava"][3] === "kupac") {
                            echo '<li><a class="w3-hover-none w3-text-black w3-padding-large" href="narudzbe_kupac.php">MOJE NARUDŽBE</a></li>';
                            echo '<li><a class="w3-hover-none w3-text-black w3-padding-large" href="kosarica.php">KOŠARICA</a></li>';
                        }
                        echo '<li><a class="w3-hover-none w3-text-black w3-padding-large" href="postavke_profila.php">MOJ PROFIL</a></li>';
                        echo '<li><a class="w3-hover-none w3-text-black w3-padding-large" href="odjava.php">ODJAVA</a></li>';
                    }
                    else {
                        echo '<li class="nav_mob_border"><a class="w3-hover-none w3-text-black w3-padding-large" href="prijava.php">PRIJAVA</a></li>';
                        echo '<li><a class="w3-hover-none w3-text-black w3-padding-large" href="registracija.php">REGISTRACIJA</a></li>';
                    }
                    ?>
                </ul>
            </div>
            
            <div id="proizv" class="w3-hide w3-hide-large w3-hide-medium w3-top" style="margin-top: 138px; bottom: 0; overflow: scroll">
                <ul class="w3-navbar w3-left-align w3-card-2 w3-white" style="border-top-width: 1px; border-top-style: dotted; border-bottom-width: 1px; border-bottom-style: dotted;">
                </ul>
            </div>
            
            <div id="proizv2" class="w3-hide w3-hide-large w3-hide-medium w3-top" style="margin-top: 138px; bottom: 0; overflow: scroll">
                <ul class="w3-navbar w3-left-align w3-card-2 w3-white" style="border-top-width: 1px; border-top-style: dashed; border-bottom-width: 1px; border-bottom-style: dashed;">
                </ul>
            </div>
            
            <div id="proizvdj" class="w3-hide w3-hide-large w3-hide-medium w3-top" style="margin-top: 92px; bottom: 0; overflow: scroll">
                <ul class="w3-navbar w3-left-align w3-card-2 w3-white" style="border-top-width: 1px; border-top-style: dotted; border-bottom-width: 1px; border-bottom-style: dotted;">
                </ul>
            </div>
            
            <div id="moji_proizv" class="w3-hide w3-hide-large w3-hide-medium w3-top" style="margin-top: 276px; bottom: 0; overflow: scroll">
                <ul class="w3-navbar w3-left-align w3-card-2 w3-white" style="border-top-width: 1px; border-top-style: dotted; border-bottom-width: 1px; border-bottom-style: dotted;">
                </ul>
            </div>

            <div class="w3-content" style="max-width:1200px">
                <div class="w3-main">
                    <div style="padding-top:50px"></div>

                    <header class="w3-container w3-xxxlarge">
                        <p class="w3-center w3-text-white">Oporavak lozinke</p>
                    </header>

                    <div class="w3-container w3-light-grey w3-padding-32 m_padding_small" style="text-align: center">
                        <div>
                            <?php
                            if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["email"])) {
                                $email = $_POST["email"];
                                require_once("skripta_baza.php");
                                $bp = new Baza();
                                $sql = "SELECT korisnicko_ime, lozinka FROM korisnici " .
                                        "WHERE email = '" . $email . "'";
                                $bp->spojiDB();
                                $rs = $bp->selectDB($sql);
                                if ($bp->pogreskaDB()) {
                                    exit();
                                }
                                while (list($korisnicko_ime, $lozinka) = $rs->fetch_array()) {
                                    $id = $korisnicko_ime;
                                    $kod = hash("sha256", $email . $lozinka);
                                }
                                $rs->close();
                                $bp->zatvoriDB();
                                mail($email, "Oporavak lozinke", wordwrap("Poštovani/a\n\nKako biste obnovili Vašu lozinku za korisnički račun " .
                                        $id . " na sustavu Zdrava prehrana, kliknite na sljedeću poveznicu: " .
                                        "https://goldner.xyz/gabriel/prijava_oporavak_lozinke.php?id=" . $id . "&kod=" . $kod .
                                        "\n\nZdrava prehrana", 100), "From: Zdrava prehrana <zdrava.prehrana@goldner.xyz>");
                                echo '<script>location.replace("prijava_oporavak_lozinke.php?id=-1");</script>';
                                exit();
                            }
                            else if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["lozinka"])) {
                                $korisnicko_ime = $_POST["korisnicko_ime"];
                                $lozinka = hash("sha256", $_POST["lozinka"]);
                                require_once("skripta_baza.php");
                                $bp = new Baza();
                                $sql = "UPDATE korisnici SET lozinka = '" . $lozinka . "' " .
                                        "WHERE korisnicko_ime = '" . $korisnicko_ime . "'";
                                $bp->spojiDB();
                                $bp->updateDB($sql);
                                if ($bp->pogreskaDB()) {
                                    exit();
                                }
                                $bp->zatvoriDB();
                                echo '<script>location.replace("prijava_oporavak_lozinke.php?id=-2");</script>';
                                exit();
                            }
                            else if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"]) && $_GET["id"] == "-1") {
                                echo "<p>Uspješno ste izvršili prvi korak u procesu oporavka lozinke! Na vašu e-mail adresu će uskoro stići " .
                                        "poruka koja sadrži link za obnovu lozinke. Posjetite navedeni link kako biste unijeli novu lozinku " .
                                        "za Vaš korisnički račun.</p>";
                            }
                            else if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"]) && $_GET["id"] == "-2") {
                                echo "<p>Uspješno ste obnovili lozinku za Vaš korisnički račun! Sada se možete prijaviti na sustav koristeći " .
                                        "Vašu novu lozinku.</p>";
                            }
                            else if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"]) && isset($_GET["kod"])) {
                                $id = $_GET["id"];
                                require_once("skripta_baza.php");
                                $bp = new Baza();
                                $sql = "SELECT email, lozinka FROM korisnici " .
                                        "WHERE korisnicko_ime = '" . $id . "'";
                                $bp->spojiDB();
                                $rs = $bp->selectDB($sql);
                                if ($bp->pogreskaDB()) {
                                    exit();
                                }
                                while (list($email, $lozinka) = $rs->fetch_array()) {
                                    $kod = hash("sha256", $email . $lozinka);
                                }
                                $rs->close();
                                $bp->zatvoriDB();
                                if($kod === $_GET["kod"]) {
                                    echo '<form id="forma2" action="" method="post">';
                                        echo "<p>Unesite novu lozinku za Vaš korisnički račun:</p>";
                                        echo '<div id="d_korisnicko_ime" class="m_input" style="margin-bottom: 15px">';
                                            echo '<div class="prijava_input m_prijava_input">';
                                                echo '<p class="prijava_input m_prijava_input">';
                                                    echo '<input id="korisnicko_ime" name="korisnicko_ime" class="w3-input w3-border prijava_input m_prijava_input" type="text" placeholder="Korisničko ime" value="' . $id . '" readonly>';
                                                echo "</p>";
                                            echo "</div>";
                                        echo "</div>";
                                        echo '<div id="d_lozinka" class="m_input" style="margin-bottom: 15px">';
                                            echo '<div class="prijava_input m_prijava_input">';
                                                echo '<p class="prijava_input m_prijava_input">';
                                                    echo '<input id="lozinka" name="lozinka" class="w3-input w3-border prijava_input m_prijava_input" type="password" placeholder="Nova lozinka">';
                                                echo "</p>";
                                            echo "</div>";
                                        echo "</div>";
                                        echo '<div id="d_lozinka_potvrda" class="m_input" style="margin-bottom: 15px">';
                                            echo '<div class="prijava_input m_prijava_input">';
                                                echo '<p class="prijava_input m_prijava_input">';
                                                    echo '<input id="lozinka_potvrda" name="lozinka_potvrda" class="w3-input w3-border prijava_input m_prijava_input" type="password" placeholder="Potvrda nove lozinke">';
                                                echo "</p>";
                                            echo "</div>";
                                        echo "</div>";
                                        echo '<button id="prihvati" name="prihvati" type="submit" class="w3-btn w3-padding w3-red w3-margin-top w3-margin-bottom w3-margin-right button">Prihvati</button>';
                                    echo "</form>";
                                }
                                else {
                                    echo "<p>Pojavila se pogreška prilikom identifikacije Vašeg korisničkog računa! Pokušajte ponoviti proces " .
                                            "oporavka lozinke.</p>";
                                }
                            }
                            else {
                                echo '<form id="forma1" action="" method="post">';
                                    echo "<p>Unesite e-mail adresu koju ste koristili prilikom registracije Vašeg korisničkog računa:</p>";
                                    echo '<div id="d_email" class="m_input" style="margin-bottom: 15px">';
                                        echo '<div class="prijava_input m_prijava_input">';
                                            echo '<p class="prijava_input m_prijava_input">';
                                                echo '<input id="email" name="email" class="w3-input w3-border prijava_input m_prijava_input" type="email" placeholder="E-mail">';
                                            echo "</p>";
                                        echo "</div>";
                                    echo "</div>";
                                    echo '<button id="prihvati" name="prihvati" type="submit" class="w3-btn w3-padding w3-red w3-margin-top w3-margin-bottom w3-margin-right button">Prihvati</button>';
                                echo "</form>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div style="padding-bottom:200px"></div>
            <div id="footer">
                <address style="padding-top: 15px">Kontakt: <a href="mailto:gabilic@foi.hr">Gabriel Ilić</a></address>
                <p>&copy; 2016/2017 G. Ilić</p>
            </div>
        </div>
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script type="text/javascript" src="js/navigacija.js?<?php echo filemtime("js/navigacija.js") ?>"></script>
        <script type="text/javascript" src="js/prijava_oporavak_lozinke.js?<?php echo filemtime("js/prijava_oporavak_lozinke.js") ?>"></script>
        <script type="text/javascript" src="js/proizvodi.js?<?php echo filemtime("js/proizvodi.js") ?>"></script>
        <script type="text/javascript" src="js/proizvodjaci.js?<?php echo filemtime("js/proizvodjaci.js") ?>"></script>
    </body>
</html>