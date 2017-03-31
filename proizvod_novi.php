<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

if(!(isset($_SESSION["prijava"]) && $_SESSION["prijava"][3] === "proizvođač")) {
    header("Location: index.php");
    exit();
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $korisnik = $_SESSION["prijava"][0];
    $naziv = $_POST["naziv_proizvoda"];
    $kolicina = $_POST["kolicina_unos"];
    $mjerna_jedinica = $_POST["mjerna_jedinica"];
    $cijena = $_POST["cijena_unos"];
    $vrsta = $_POST["vrsta_unos"];
    $slika_proizvoda = "NULL";
    $p_id = "1";
    require_once("skripta_baza.php");
    $bp = new Baza();
    $sql = "SELECT id FROM proizvod " .
            "ORDER BY id ASC";
    $bp->spojiDB();
    $rs = $bp->selectDB($sql);
    if ($bp->pogreskaDB()) {
        exit();
    }
    if($rs->num_rows === 0) {
        $pom_lista[] = 0;
    }
    while (list($id) = $rs->fetch_array()) {
        $pom_lista[] = $id;
    }
    $rs->close();
    while(in_array($p_id, $pom_lista)) {
        $p_id++;
    }
    if(isset($_FILES["slika"])) {
        if(!($_FILES["slika"]["error"] > 0)) {
            if(getimagesize($_FILES["slika"]["tmp_name"])) {
                $temp = explode(".", $_FILES["slika"]["name"]);
                $datoteka = $p_id . "." . end($temp);
                move_uploaded_file($_FILES["slika"]["tmp_name"], "img/products/" . $datoteka);
                $slika_proizvoda = "'img/products/" . $datoteka . "'";
            }
        }
    }
    $sql2 = "INSERT INTO proizvod VALUES (" . $p_id . ", '" . $naziv .
            "', " . $kolicina . ", '" . $mjerna_jedinica . "', " . $cijena . ", " . $slika_proizvoda .
            ", '" . $korisnik . "', (SELECT id FROM vrsta_proizvoda " .
            "WHERE naziv = '" . strtolower($vrsta) . "'))";
    $bp->updateDB($sql2);
    if($bp->pogreskaDB()) {
        exit();
    }
    $bp->zatvoriDB();
    header("Location: index.php");
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
    <body class="background_2" onload="proizvodNovi();">
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
                        <p class="w3-center w3-text-white">Novi proizvod</p>
                    </header>

                    <div class="w3-container w3-light-grey w3-padding-32 m_padding_small">
                        <div>
                            <form id="forma" action="" method="post" enctype="multipart/form-data">
                                <p>Ispunite sljedeći obrazac kako biste dodali novi proizvod:</p>
                                <div id="d_kategorija" class="left_input_25 m_left_input_50">
                                    <p style="margin-top: 0px">
                                        <select id="kategorija" name="kategorija" class="w3-select w3-border">
                                            <option value="0" disabled selected>Kategorija proizvoda</option>
                                        </select>
                                    </p>
                                </div>
                                <div id="d_vrsta" class="middle_input_25 m_right_input_50">
                                    <p style="margin-top: 0px">
                                        <select id="vrsta" name="vrsta" class="w3-select w3-border">
                                            <option value="0" disabled selected>Vrsta proizvoda</option>
                                        </select>
                                    </p>
                                </div>
                                <input id="vrsta_unos" name="vrsta_unos" type="hidden">
                                <div id="d_naziv_proizvoda" class="right_input_50 m_input">
                                    <p style="margin-top: 0px">
                                        <input id="naziv_proizvoda" name="naziv_proizvoda" class="w3-input w3-border" type="text" placeholder="Naziv proizvoda">
                                    </p>
                                </div>
                                <div style="clear: both"></div>
                                <div id="d_kolicina_na_skladistu" class="left_input_25 m_left_input_50">
                                    <p style="margin-top: 5px; margin-bottom: 1px">Količina na skladištu:</p>
                                    <div id="d_kolicina" class="left_input_66_5 m_left_input_66_5">
                                        <p style="margin-top: 0px">
                                            <input id="kolicina" name="kolicina" class="w3-input w3-border" type="text" placeholder="Količina na skladištu">
                                        </p>
                                    </div>
                                    <div id="d_mjerna_jedinica" class="right_input_33_5 m_right_input_33_5">
                                        <p style="margin-top: 0px">
                                            <select id="mjerna_jedinica" name="mjerna_jedinica" class="w3-select w3-border">
                                                <option value="kg" selected>kg</option>
                                                <option value="dag">dag</option>
                                                <option value="g">g</option>
                                                <option value="l">l</option>
                                                <option value="dl">dl</option>
                                                <option value="ml">ml</option>
                                                <option value="kom">kom</option>
                                            </select>
                                        </p>
                                    </div>
                                </div>
                                <input id="kolicina_unos" name="kolicina_unos" type="hidden">
                                <div id="d_cijena" class="middle_input_25 m_right_input_50">
                                    <p style="margin-top: 5px; margin-bottom: 1px">Jedinična cijena (kn):</p>
                                    <p style="margin-top: 0px">
                                        <input id="cijena" name="cijena" class="w3-input w3-border" type="text" placeholder="Jedinična cijena (kn)">
                                    </p>
                                </div>
                                <input id="cijena_unos" name="cijena_unos" type="hidden">
                                <div class="right_input_50 m_input">
                                    <p style="margin-top: 5px; margin-bottom: 1px">Slika proizvoda (opcionalno):</p>
                                    <p style="margin-top: 0px">
                                        <input id="slika" name="slika" class="w3-input w3-border" type="file">
                                    </p>
                                </div>
                                <div style="clear: both"></div>
                                <div style="text-align: center">
                                    <button id="dodaj_proizvod" name="dodaj_proizvod" type="submit" class="w3-btn w3-padding w3-red w3-margin-top w3-margin-bottom w3-margin-right button" disabled>Dodaj proizvod</button>
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
        
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script type="text/javascript" src="js/navigacija.js?<?php echo filemtime("js/navigacija.js") ?>"></script>
        <script type="text/javascript" src="js/proizvod_novi.js?<?php echo filemtime("js/proizvod_novi.js") ?>"></script>
        <script type="text/javascript" src="js/proizvodi.js?<?php echo filemtime("js/proizvodi.js") ?>"></script>
        <script type="text/javascript" src="js/proizvodjaci.js?<?php echo filemtime("js/proizvodjaci.js") ?>"></script>
    </body>
</html>