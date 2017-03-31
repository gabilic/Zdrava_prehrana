<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $tip_registracije = $_POST["tip_registracije"];
    if($tip_registracije === "2") {
        $naziv_opg = "'" . $_POST["naziv_opg"] . "'";
        $aktiviran = "NULL";
        $tip_korisnika = "2";
    }
    else {
        $naziv_opg = "NULL";
        $aktiviran = "'NE'";
        $tip_korisnika = "3";
    }
    $ime = $_POST["ime"];
    $prezime = $_POST["prezime"];
    $korisnicko_ime = $_POST["korisnicko_ime"];
    $email = $_POST["email"];
    $lozinka = hash("sha256", $_POST["lozinka"]);
    $dan = $_POST["dan"];
    $mjesec = $_POST["mjesec"];
    $godina = $_POST["godina"];
    $rodjendan = $godina . "-" . $mjesec . "-" . $dan;
    $broj_telefona = $_POST["broj_telefona"];
    $spol = $_POST["spol"];
    if($spol === "1") {
        $spol = "M";
    }
    else if($spol === "2") {
        $spol = "Ž";
    }
    $grad = $_POST["grad"];
    $ulica = $_POST["ulica"];
    $broj = $_POST["broj"];
    $slika_profila = "NULL";
    if(isset($_FILES["slika"])) {
        if(!($_FILES["slika"]["error"] > 0)) {
            if(getimagesize($_FILES["slika"]["tmp_name"])) {
                $temp = explode(".", $_FILES["slika"]["name"]);
                $datoteka = $korisnicko_ime . "." . end($temp);
                move_uploaded_file($_FILES["slika"]["tmp_name"], "img/users/" . $datoteka);
                $slika_profila = "'img/users/" . $datoteka . "'";
            }
        }
    }
    $datum_i_vrijeme = date("d.m.Y H:i:s");
    $aktivacijski_kod = hash("sha256", $datum_i_vrijeme . $korisnicko_ime . $email);
    require_once("skripta_baza.php");
    $bp = new Baza();
    $sql = "INSERT INTO korisnici VALUES ('" . $korisnicko_ime . "', '" . $ime . "', '" .
            $prezime . "', '" . $lozinka . "', '" . $rodjendan . "', '" . $spol . "', '" .
            $broj_telefona . "', '" . $email . "', '" . $grad . "', '" . $ulica . "', '" .
            $broj . "', " . $naziv_opg . ", NULL, 0, " . $slika_profila . ", '" . $aktivacijski_kod . "', '" .
            date("Y-m-d H:i:s", strtotime($datum_i_vrijeme)) . "', " .
            $aktiviran . ", " . $tip_korisnika . ")";
    $bp->spojiDB();
    $bp->updateDB($sql);
    if($bp->pogreskaDB()) {
        exit();
    }
    $bp->zatvoriDB();
    if($tip_registracije === "1") {
        mail($email, "Aktivacijski link", wordwrap("Poštovani/a\n\nHvala Vam što ste se registrirali kao kupac na sustavu Zdrava prehrana! " .
                "Klikom na poveznicu: https://goldner.xyz/gabriel/registracija_aktivacija.php?kod=" . $aktivacijski_kod .
                " možete aktivirati Vaš korisnički račun.\nOvaj e-mail ste primili, budući da je kreiran korisnički račun koji koristi " .
                "Vašu e-mail adresu. Ako pak niste kreirali ovaj korisnički račun, ignorirajte pristiglu poruku.\n\n" .
                "Zdrava prehrana", 100), "From: Zdrava prehrana <zdrava.prehrana@goldner.xyz>");
    }
    header("Location: registracija_uspjeh.php?tip=" . $tip_registracije);
    exit();
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
    <body class="background_2" onload="registracija();">
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
                        <p class="w3-center w3-text-white">Registracija</p>
                    </header>

                    <div class="w3-container w3-light-grey w3-padding-32 m_padding_small">
                        <div>
                            <form id="forma" action="" method="post" enctype="multipart/form-data">
                                <div style="float: left; margin-right: 10px">Tip registracije:</div>
                                <div style="overflow: hidden">
                                    <input id="registracija_kupaca" name="tip_registracije" type="radio" value="1" checked style="float: left; margin-top: 5px">
                                    <p style="float: left; margin: 0px auto 5px 5px"> Registracija kupaca</p>
                                    <div style="clear: both"></div>
                                    <input id="registracija_proizvodjaca" name="tip_registracije" type="radio" value="2" style="float: left; margin-top: 5px">
                                    <p style="float: left; margin: 0px auto 5px 5px">Registracija proizvođača</p>
                                </div>
                                <div style="clear: both"></div>
                                <div class="proizvodjac" style="visibility: hidden; display: none">
                                    <p>Ispunite sljedeći obrazac kako biste se registrirali kao proizvođač. Kada izvršite registraciju, Vaši osnovni
                                        podaci se šalju administratoru (da bi se izbjegla zloupotreba podataka), nakon čega on može
                                        potvrditi ili odbiti vaš korisnički račun:</p>
                                </div>
                                <div class="kupac">
                                    <p>Ispunite sljedeći obrazac kako biste se registrirali kao kupac:</p>
                                </div>
                                <div id="d_naziv_opg" class="proizvodjac m_input" style="visibility: hidden; display: none">
                                    <p style="margin-top: 0px">
                                        <input id="naziv_opg" name="naziv_opg" class="w3-input w3-border" type="text" placeholder="Naziv OPG-a">
                                    </p>
                                </div>
                                <div id="d_ime" class="left_input_50 m_input">
                                    <p style="margin-top: 0px">
                                        <input id="ime" name="ime" class="w3-input w3-border" type="text" placeholder="Ime">
                                    </p>
                                </div>
                                <div id="d_prezime" class="right_input_50 m_input">
                                    <p style="margin-top: 0px">
                                        <input id="prezime" name="prezime" class="w3-input w3-border" type="text" placeholder="Prezime">
                                    </p>
                                </div>
                                <div style="clear: both"></div>
                                <div id="d_korisnicko_ime" class="left_input_50 m_input">
                                    <p style="margin-top: 0px">
                                        <input id="korisnicko_ime" name="korisnicko_ime" class="w3-input w3-border" type="text" placeholder="Korisničko ime">
                                    </p>
                                </div>
                                <div id="d_email" class="right_input_50 m_input">
                                    <p style="margin-top: 0px">
                                        <input id="email" name="email" class="w3-input w3-border" type="email" placeholder="E-mail">
                                    </p>
                                </div>
                                <div style="clear: both"></div>
                                <div id="d_lozinka" class="left_input_50 m_input">
                                    <p style="margin-top: 0px">
                                        <input id="lozinka" name="lozinka" class="w3-input w3-border" type="password" placeholder="Lozinka">
                                    </p>
                                </div>
                                <div id="d_lozinka_potvrda" class="right_input_50 m_input">
                                    <p style="margin-top: 0px">
                                        <input id="lozinka_potvrda" name="lozinka_potvrda" class="w3-input w3-border" type="password" placeholder="Potvrda lozinke">
                                    </p>
                                </div>
                                <div style="clear: both"></div>
                                <div>
                                    <p style="margin-top: 5px; margin-bottom: 1px">Datum rođenja:</p>
                                </div>
                                <div id="d_rodjendan" class="left_input_50 m_input">
                                    <div class="left_input_20 m_left_input_20">
                                        <p style="margin-top: 0px">
                                            <input id="dan" name="dan" class="w3-input w3-border" type="number" min="1" max="31" value="1">
                                        </p>
                                    </div>
                                    <div class="middle_input_40 m_middle_input_40">
                                        <p style="margin-top: 0px">
                                            <select id="mjesec" name="mjesec" class="w3-select w3-border">
                                                <option value="1" selected>siječanj</option>
                                                <option value="2">veljača</option>
                                                <option value="3">ožujak</option>
                                                <option value="4">travanj</option>
                                                <option value="5">svibanj</option>
                                                <option value="6">lipanj</option>
                                                <option value="7">srpanj</option>
                                                <option value="8">kolovoz</option>
                                                <option value="9">rujan</option>
                                                <option value="10">listopad</option>
                                                <option value="11">studeni</option>
                                                <option value="12">prosinac</option>
                                            </select>
                                        </p>
                                    </div>
                                    <div class="right_input_40 m_right_input_40">
                                        <p style="margin-top: 0px">
                                            <input id="godina" name="godina" class="w3-input w3-border" type="number" min="1900" max="2015" value="2015">
                                        </p>
                                    </div>
                                </div>
                                <div id="d_broj_telefona" class="middle_input_30 m_left_input_60">
                                    <p style="margin-top: 0px">
                                        <input id="broj_telefona" name="broj_telefona" class="w3-input w3-border" type="tel" placeholder="Broj telefona">
                                    </p>
                                </div>
                                <div id="d_spol" class="right_input_20 m_right_input_40">
                                    <p style="margin-top: 0px">
                                        <select id="spol" name="spol" class="w3-select w3-border">
                                            <option value="0" disabled selected>Spol</option>
                                            <option value="1">muški</option>
                                            <option value="2">ženski</option>
                                        </select>
                                    </p>
                                </div>
                                <div style="clear: both"></div>
                                <div id="d_adresa" class="left_input_50 m_input">
                                    <p style="margin-top: 5px; margin-bottom: 1px">Adresa:</p>
                                    <div class="left_input_40 m_left_input_40">
                                        <p style="margin-top: 0px">
                                            <input id="grad" name="grad" class="w3-input w3-border" type="text" placeholder="Grad">
                                        </p>
                                    </div>
                                    <div class="middle_input_40 m_middle_input_40">
                                        <p style="margin-top: 0px">
                                            <input id="ulica" name="ulica" class="w3-input w3-border" type="text" placeholder="Ulica">
                                        </p>
                                    </div>
                                    <div class="right_input_20 m_right_input_20">
                                        <p style="margin-top: 0px">
                                            <input id="broj" name="broj" class="w3-input w3-border" type="text" placeholder="Broj">
                                        </p>
                                    </div>
                                </div>
                                <div class="right_input_50 m_input">
                                    <p style="margin-top: 5px; margin-bottom: 1px">Slika profila (opcionalno):</p>
                                    <p style="margin-top: 0px">
                                        <input id="slika" name="slika" class="w3-input w3-border" type="file">
                                    </p>
                                </div>
                                <div style="clear: both"></div>
                                <div id="captcha" class="g-recaptcha" data-sitekey="6LciNA4UAAAAADAbVuLU-gjIdJMcZGforTai0pBr" data-callback="captchaUspjeh"></div>
                                <div style="text-align: center">
                                    <button id="registriraj_se" name="registriraj_se" type="submit" class="w3-btn w3-padding w3-red w3-margin-top w3-margin-bottom w3-margin-right button">Registriraj se</button>
                                    <button id="ocisti_obrazac" name="ocisti_obrazac" type="reset" class="w3-btn w3-padding w3-red w3-margin-top w3-margin-bottom button">Očisti obrazac</button>
                                </div>
                            </form>
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
        
        <script src="https://www.google.com/recaptcha/api.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script type="text/javascript" src="js/navigacija.js?<?php echo filemtime("js/navigacija.js") ?>"></script>
        <script type="text/javascript" src="js/registracija.js?<?php echo filemtime("js/registracija.js") ?>"></script>
        <script type="text/javascript" src="js/proizvodi.js?<?php echo filemtime("js/proizvodi.js") ?>"></script>
        <script type="text/javascript" src="js/proizvodjaci.js?<?php echo filemtime("js/proizvodjaci.js") ?>"></script>
    </body>
</html>