<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

$proizvodjac = "";
$k_ime = "";
if($_SERVER["REQUEST_METHOD"] == "GET") {
    $k_ime = $_GET["id"];
}
require_once("skripta_baza.php");
$bp = new Baza();
$sql = "SELECT naziv_opg FROM korisnici " .
        "WHERE korisnicko_ime = '" . $k_ime . "'";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if ($bp->pogreskaDB()) {
    exit();
}
while (list($k_proizvodjac) = $rs->fetch_array()) {
    $proizvodjac = $k_proizvodjac;
}
$rs->close();
$bp->zatvoriDB();

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
        <style type="text/css">
            td {
                padding: 0 15px 0 15px;
            }
        </style>
    </head>
    <body class="background_2" onload="proizvod();">
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
                        <p class="w3-center w3-text-white"><?php echo $proizvodjac; ?></p>
                    </header>

                    <div class="w3-container w3-light-grey w3-padding-32 m_padding_small">
                        <div>
                            <?php
                            
                            $tablica = "<table><tr>";
                            $sql2 = "SELECT broj_telefona, email, grad, ulica, kucni_broj, naziv_opg, slika, ocjena, broj_glasova " .
                                    "FROM korisnici WHERE korisnicko_ime = '" . $k_ime . "'";
                            $bp->spojiDB();
                            $rs2 = $bp->selectDB($sql2);
                            if ($bp->pogreskaDB()) {
                                exit();
                            }
                            while (list($broj_telefona, $email, $grad, $ulica, $kucni_broj, $naziv_opg, $slika, $k_ocjena, $broj_glasova) = $rs2->fetch_array()) {
                                $adresa = $ulica . " " . $kucni_broj . ", " . $grad;
                                $opg = $naziv_opg; $ocjena = $k_ocjena; $br_glasova = $broj_glasova;
                                $tablica .= '<td rowspan="' . ($ocjena === NULL ? "4" : "5") . '"><img src="' . ($slika === NULL ? "img/users/default.png?" . filemtime("img/users/default.png") : $slika . "?" . filemtime($slika)) . '" alt="Slika profila" width="250" height="200" style="border-radius: 10px"></td>' .
                                            '<td style="padding-top: 40px"><span style="font-weight: bold">Naziv OPG-a:</span><span id="opg"> ' . $naziv_opg . "</span></td></tr>";
                                if($ocjena !== NULL) {
                                    if($br_glasova == "1") {
                                        $glasovi = "glas";
                                    }
                                    else if($br_glasova >= "2" && $br_glasova <= "4") {
                                        $glasovi = "glasa";
                                    }
                                    else {
                                        $glasovi = "glasova";
                                    }
                                    $tablica .= '<tr><td><span style="font-weight: bold">Ocjena:</span><span id="ocjena"> ' . $ocjena . " ($br_glasova $glasovi)</span></td></tr>";
                                }
                                $tablica .= '<tr><td><span style="font-weight: bold">Broj telefona:</span><span id="telefon"> ' . $broj_telefona . "</span></td></tr>" .
                                            '<tr><td><span style="font-weight: bold">E-mail:</span><span id="email"> ' . $email . "</span></td></tr>" .
                                            '<tr><td style="padding-bottom: 40px"><span style="font-weight: bold">Adresa:</span><span id="adresa"> ' . $adresa . "</span></td></tr>";
                            }
                            $rs2->close();
                            $bp->zatvoriDB();
                            $tablica .= "</table>";
                            echo '<div id="tablica" style="right: 0px; overflow-x: auto">' . $tablica . "</div>";
                            echo '<p style="font-size: 16px; font-weight: bold">Moji proizvodi:</p>';
                            
                            $sql3 = "SELECT id, naziv, kolicina, mjerna_jedinica, cijena, slika FROM proizvod " .
                                    "WHERE korisnici_korisnicko_ime = '" . $k_ime . "'";
                            $bp->spojiDB();
                            $rs3 = $bp->selectDB($sql3);
                            if ($bp->pogreskaDB()) {
                                exit();
                            }
                            if($rs3->num_rows === 0) {
                                echo "<p>Nažalost, lista proizvoda je prazna.</p>";
                            }
                            echo '<div class="w3-row">';
                            while (list($id, $naziv, $kolicina, $mjerna_jedinica, $cijena, $slika) = $rs3->fetch_array()) {
                                echo '<div class="w3-col s6 m4 l3">';
                                    echo '<div class="w3-container" style="text-align: center">';
                                        echo "<p><b>$naziv</b></p>";
                                        echo '<div class="w3-display-container">';
                                            echo '<img class="kosarica" src="' . ($slika === NULL ? "img/products/default.png?" . filemtime("img/products/default.png") : $slika . "?" . filemtime($slika)) . '" alt="Slika proizvoda" width="80%" height="180px">';
                                            echo '<div class="w3-display-middle">';
                                                echo '<button type="button" class="w3-red button gumb_kosarica" style="display:none" onclick="kosaricaDodaj(' . $id . '); return false;">Dodaj u <i class="fa fa-shopping-cart"></i></button>';
                                            echo "</div>";
                                        echo "</div>";
                                        echo '<p><a href="proizvodjac.php?id=' . $k_ime . '" class="proizvod_opg">' . $opg . "</a><br>";
                                        if($ocjena !== NULL) {
                                            echo "$ocjena ";
                                            for($i = 0; $i < floor($ocjena); $i++) {
                                                echo '<i class="fa fa-star"></i>';
                                            }
                                            if(floor($ocjena) != "5" && ((($ocjena - floor($ocjena)) * 100) <= 20)) {
                                                echo '<i class="fa fa-star-o"></i>';
                                            }
                                            else if((($ocjena - floor($ocjena)) * 100) > 20 && (($ocjena - floor($ocjena)) * 100) < 80) {
                                                echo '<i class="fa fa-star-half-o"></i>';
                                            }
                                            else if((($ocjena - floor($ocjena)) * 100) >= 80) {
                                                echo '<i class="fa fa-star"></i>';
                                            }
                                            for($i = floor($ocjena) + 1; $i < 5; $i++) {
                                                echo '<i class="fa fa-star-o"></i>';
                                            }
                                            echo ' &nbsp; ' . $br_glasova . ' <i class="fa fa-user"></i><br>';
                                        }
                                        echo "Dostupno: $kolicina $mjerna_jedinica<br><b>$cijena kn</b></p>";
                                    echo "</div>";
                                echo "</div>";
                            }
                            echo "</div>";
                            $rs3->close();
                            $bp->zatvoriDB();
                            
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
        <script type="text/javascript" src="js/proizvod.js?<?php echo filemtime("js/proizvod.js") ?>"></script>
        <script type="text/javascript" src="js/proizvodi.js?<?php echo filemtime("js/proizvodi.js") ?>"></script>
        <script type="text/javascript" src="js/proizvodjaci.js?<?php echo filemtime("js/proizvodjaci.js") ?>"></script>
    </body>
</html>