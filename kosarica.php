<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

if(!(isset($_SESSION["prijava"]) && $_SESSION["prijava"][3] === "kupac")) {
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
        <style type="text/css">
            th {
                font-weight: bold;
                background-color: #006699;
                color: #FFFFFF;
            }
            th, td {
                text-align: left;
                padding: 8px;
            }
            tr {
                background-color: #DEDEDE;
            }
            tr:hover {
                background-color: #FFFFFF;
            }
        </style>
    </head>
    <body class="background_2" onload="kosarica();">
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
                        <p class="w3-center w3-text-white">Košarica</p>
                    </header>

                    <div class="w3-container w3-light-grey w3-padding-32 m_padding_small">
                        <div>
                            <div id="d_dat_i_vrij_preuzimanja" class="m_input" style="visibility: hidden; display: none">
                                <p style="margin-top: 5px; margin-bottom: 1px">Datum i vrijeme preuzimanja narudžbe:</p>
                                <div class="left_input_7_5 m_left_input_15">
                                    <p style="margin-top: 0px">
                                        <input id="dan" name="dan" class="w3-input w3-border" type="number" min="1" max="31" value="<?php echo date("j"); ?>">
                                    </p>
                                </div>
                                <div class="middle_input_15 m_middle_input_30">
                                    <p style="margin-top: 0px">
                                        <select id="mjesec" name="mjesec" class="w3-select w3-border">
                                            <?php
                                            if(date("n") == "1") {
                                                echo '<option value="1" selected>siječanj</option>';
                                            }
                                            else {
                                                echo '<option value="1">siječanj</option>';
                                            }
                                            if(date("n") == "2") {
                                                echo '<option value="2" selected>veljača</option>';
                                            }
                                            else {
                                                echo '<option value="2">veljača</option>';
                                            }
                                            if(date("n") == "3") {
                                                echo '<option value="3" selected>ožujak</option>';
                                            }
                                            else {
                                                echo '<option value="3">ožujak</option>';
                                            }
                                            if(date("n") == "4") {
                                                echo '<option value="4" selected>travanj</option>';
                                            }
                                            else {
                                                echo '<option value="4">travanj</option>';
                                            }
                                            if(date("n") == "5") {
                                                echo '<option value="5" selected>svibanj</option>';
                                            }
                                            else {
                                                echo '<option value="5">svibanj</option>';
                                            }
                                            if(date("n") == "6") {
                                                echo '<option value="6" selected>lipanj</option>';
                                            }
                                            else {
                                                echo '<option value="6">lipanj</option>';
                                            }
                                            if(date("n") == "7") {
                                                echo '<option value="7" selected>srpanj</option>';
                                            }
                                            else {
                                                echo '<option value="7">srpanj</option>';
                                            }
                                            if(date("n") == "8") {
                                                echo '<option value="8" selected>kolovoz</option>';
                                            }
                                            else {
                                                echo '<option value="8">kolovoz</option>';
                                            }
                                            if(date("n") == "9") {
                                                echo '<option value="9" selected>rujan</option>';
                                            }
                                            else {
                                                echo '<option value="9">rujan</option>';
                                            }
                                            if(date("n") == "10") {
                                                echo '<option value="10" selected>listopad</option>';
                                            }
                                            else {
                                                echo '<option value="10">listopad</option>';
                                            }
                                            if(date("n") == "11") {
                                                echo '<option value="11" selected>studeni</option>';
                                            }
                                            else {
                                                echo '<option value="11">studeni</option>';
                                            }
                                            if(date("n") == "12") {
                                                echo '<option value="12" selected>prosinac</option>';
                                            }
                                            else {
                                                echo '<option value="12">prosinac</option>';
                                            }
                                            ?>
                                        </select>
                                    </p>
                                </div>
                                <div class="middle_input_10 m_middle_input_20">
                                    <p style="margin-top: 0px">
                                        <input id="godina" name="godina" class="w3-input w3-border" type="number" min="2000" max="2050" value="<?php echo date("Y"); ?>">
                                    </p>
                                </div>
                                <div class="m_novi_red"></div>
                                <div class="middle_input_10 m_left_input_15" style="padding-left: 2em">
                                    <p style="margin-top: 0px">
                                        <input id="sat" name="sat" class="w3-input w3-border" type="number" min="0" max="23" value="<?php echo date("G"); ?>">
                                    </p>
                                </div>
                                <div style="float: left; padding-top: 7.5px; padding-bottom: 7.5px" class="m_razmak_ld">h
                                </div>
                                <div class="right_input_7_5 m_right_input_15">
                                    <p style="margin-top: 0px">
                                        <input id="minuta" name="minuta" class="w3-input w3-border" type="number" min="0" max="59" value="<?php echo intval(date("i")); ?>">
                                    </p>
                                </div>
                                <div style="float: left; padding: 7.5px">min
                                </div>
                            </div>
                            <div style="clear: both"></div>
                            <div id="tablica" style="right: 0px; overflow-x: auto">
                            </div>
                            <div id="gumb" style="text-align: center; visibility: hidden; display: none">
                                <button id="potvrdi" name="potvrdi" type="button" class="w3-btn w3-padding w3-red w3-margin-top w3-margin-bottom w3-margin-right button" onclick="potvrdi(); return false;">Potvrdi narudžbu</button>
                            </div>
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
        <script type="text/javascript" src="js/kosarica.js?<?php echo filemtime("js/kosarica.js") ?>"></script>
        <script type="text/javascript" src="js/proizvodi.js?<?php echo filemtime("js/proizvodi.js") ?>"></script>
        <script type="text/javascript" src="js/proizvodjaci.js?<?php echo filemtime("js/proizvodjaci.js") ?>"></script>
    </body>
</html>