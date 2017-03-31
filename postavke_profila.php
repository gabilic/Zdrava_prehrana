<?php

session_name("prijava_sesija");
if(session_id() === "") {
    session_start();
}

if(!(isset($_SESSION["prijava"]))) {
    header("Location: index.php");
    exit();
}

$korisnicko_ime = "";
if(isset($_SESSION["prijava"])) {
    $korisnicko_ime = $_SESSION["prijava"][0];
}
require_once("skripta_baza.php");
$bp = new Baza();
$sql = "SELECT ime, prezime, rodjendan, broj_telefona, " .
        "spol, grad, ulica, kucni_broj, naziv_opg, slika " .
        "FROM korisnici WHERE korisnicko_ime = '" .
        $korisnicko_ime . "'";
$bp->spojiDB();
$rs = $bp->selectDB($sql);
if($bp->pogreskaDB()) {
    exit();
}
while (list($k_ime, $k_prezime, $k_rodjendan, $k_telefon, $k_spol, $k_grad, $k_ulica, $k_kucni_broj, $k_opg, $k_slika) = $rs->fetch_array()) {
    $ime = $k_ime; $prezime = $k_prezime; $rodjendan = explode("-", $k_rodjendan); $telefon = $k_telefon; $spol = $k_spol; $grad = $k_grad;
    $ulica = $k_ulica; $kucni_broj = $k_kucni_broj; $opg = $k_opg; $slika = $k_slika;
    $dan = $rodjendan[2]; $mjesec = $rodjendan[1]; $godina = $rodjendan[0];
}
$rs->close();
$bp->zatvoriDB();

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $naziv_opg = "";
    if($_SESSION["prijava"][3] === "administrator" || $_SESSION["prijava"][3] === "proizvođač") {
        $naziv_opg = $_POST["naziv_opg"];
    }
    $korisnicko_ime = "";
    if(isset($_SESSION["prijava"])) {
        $korisnicko_ime = $_SESSION["prijava"][0];
    }
    $ime = $_POST["ime"];
    $prezime = $_POST["prezime"];
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
    require_once("skripta_baza.php");
    $bp = new Baza();
    $sql = "UPDATE korisnici SET ime = '" . $ime . "', prezime = '" .
            $prezime . "', rodjendan = '" . $rodjendan . "', broj_telefona = '" .
            $broj_telefona . "', spol = '" . $spol . "', grad = '" . $grad .
            "', ulica = '" . $ulica . "', kucni_broj = '" . $broj . "', naziv_opg = " .
            ($_SESSION["prijava"][3] === "administrator" || $_SESSION["prijava"][3] === "proizvođač" ? "'" .
            $naziv_opg . "'" : "NULL") . " WHERE korisnicko_ime = '" . $korisnicko_ime . "'";
    $bp->spojiDB();
    $bp->updateDB($sql);
    if($bp->pogreskaDB()) {
        exit();
    }
    $_SESSION["prijava"][1] = $ime;
    $_SESSION["prijava"][2] = $prezime;
    $bp->zatvoriDB();
    header("Location: postavke_profila_lozinka.php?id=-2");
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
    <body class="background_2" onload="azurirajProfil();">
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
                        <p class="w3-center w3-text-white">Postavke profila</p>
                    </header>

                    <div class="w3-container w3-light-grey w3-padding-32 m_padding_small">
                        <div>
                            <form id="forma" action="" method="post" enctype="multipart/form-data">
                                <div>
                                    <p>Ako želite, možete ažurirati postavke Vašeg profila u sljedećem obrascu:</p>
                                </div>
                                <div>
                                    <p style="margin-top: 5px; margin-bottom: 5px">Slika profila:</p>
                                </div>
                                <div>
                                    <p style="margin-top: 0px">
                                        <?php
                                        echo '<img src="' . ($slika === NULL ? "img/users/default.png?" . filemtime("img/users/default.png") : $slika . "?" . filemtime($slika)) . '" alt="Slika profila" width="250" height="200" style="border-radius: 10px">';
                                        ?>
                                    </p>
                                </div>
                                <div class="left_input_45 m_input">
                                    <p style="margin-top: 0px">
                                        <input id="slika" name="slika" class="w3-input w3-border" type="file">
                                    </p>
                                </div>
                                <div class="button_refresh">
                                    <p style="margin-top: 0px; margin-bottom: 0px">
                                        <a href="#" title="Osvježi" onclick="osvjeziSlikuProfila(); return false;">
                                            <i class="fa fa-refresh fa-lg fa-fw"></i>
                                        </a>
                                    </p>
                                </div>
                                <div class="button_remove">
                                    <p style="margin-top: 0px; margin-bottom: 0px">
                                        <a href="#" title="Ukloni" onclick="ukloniSlikuProfila(); return false;">
                                            <i class="fa fa-times"></i>
                                        </a>
                                    </p>
                                </div>
                                <div style="clear: both"></div>
                                <?php
                                if($_SESSION["prijava"][3] === "proizvođač") {
                                    echo '<div id="d_naziv_opg" class="m_input">';
                                        echo '<p style="margin-top: 5px; margin-bottom: 1px">Naziv OPG-a:</p>';
                                        echo '<p style="margin-top: 0px">';
                                            echo '<input id="naziv_opg" name="naziv_opg" class="w3-input w3-border" type="text" placeholder="Naziv OPG-a" value="' . ($opg === NULL ? "" : $opg) . '">';
                                        echo "</p>";
                                    echo "</div>";
                                }
                                ?>
                                <div id="d_ime" class="left_input_50 m_input">
                                    <p style="margin-top: 5px; margin-bottom: 1px">Ime:</p>
                                    <p style="margin-top: 0px">
                                        <input id="ime" name="ime" class="w3-input w3-border" type="text" placeholder="Ime" value="<?php echo $ime; ?>">
                                    </p>
                                </div>
                                <div id="d_prezime" class="right_input_50 m_input">
                                    <p style="margin-top: 5px; margin-bottom: 1px">Prezime:</p>
                                    <p style="margin-top: 0px">
                                        <input id="prezime" name="prezime" class="w3-input w3-border" type="text" placeholder="Prezime" value="<?php echo $prezime; ?>">
                                    </p>
                                </div>
                                <div style="clear: both"></div>
                                <div id="d_rodjendan" class="left_input_50 m_input">
                                    <p style="margin-top: 5px; margin-bottom: 1px">Datum rođenja:</p>
                                    <div class="left_input_20 m_left_input_20">
                                        <p style="margin-top: 0px">
                                            <input id="dan" name="dan" class="w3-input w3-border" type="number" min="1" max="31" value="<?php echo intval($dan); ?>">
                                        </p>
                                    </div>
                                    <div class="middle_input_40 m_middle_input_40">
                                        <p style="margin-top: 0px">
                                            <select id="mjesec" name="mjesec" class="w3-select w3-border">
                                                <?php
                                                if(intval($mjesec) == "1") {
                                                    echo '<option value="1" selected>siječanj</option>';
                                                }
                                                else {
                                                    echo '<option value="1">siječanj</option>';
                                                }
                                                if(intval($mjesec) == "2") {
                                                    echo '<option value="2" selected>veljača</option>';
                                                }
                                                else {
                                                    echo '<option value="2">veljača</option>';
                                                }
                                                if(intval($mjesec) == "3") {
                                                    echo '<option value="3" selected>ožujak</option>';
                                                }
                                                else {
                                                    echo '<option value="3">ožujak</option>';
                                                }
                                                if(intval($mjesec) == "4") {
                                                    echo '<option value="4" selected>travanj</option>';
                                                }
                                                else {
                                                    echo '<option value="4">travanj</option>';
                                                }
                                                if(intval($mjesec) == "5") {
                                                    echo '<option value="5" selected>svibanj</option>';
                                                }
                                                else {
                                                    echo '<option value="5">svibanj</option>';
                                                }
                                                if(intval($mjesec) == "6") {
                                                    echo '<option value="6" selected>lipanj</option>';
                                                }
                                                else {
                                                    echo '<option value="6">lipanj</option>';
                                                }
                                                if(intval($mjesec) == "7") {
                                                    echo '<option value="7" selected>srpanj</option>';
                                                }
                                                else {
                                                    echo '<option value="7">srpanj</option>';
                                                }
                                                if(intval($mjesec) == "8") {
                                                    echo '<option value="8" selected>kolovoz</option>';
                                                }
                                                else {
                                                    echo '<option value="8">kolovoz</option>';
                                                }
                                                if(intval($mjesec) == "9") {
                                                    echo '<option value="9" selected>rujan</option>';
                                                }
                                                else {
                                                    echo '<option value="9">rujan</option>';
                                                }
                                                if(intval($mjesec) == "10") {
                                                    echo '<option value="10" selected>listopad</option>';
                                                }
                                                else {
                                                    echo '<option value="10">listopad</option>';
                                                }
                                                if(intval($mjesec) == "11") {
                                                    echo '<option value="11" selected>studeni</option>';
                                                }
                                                else {
                                                    echo '<option value="11">studeni</option>';
                                                }
                                                if(intval($mjesec) == "12") {
                                                    echo '<option value="12" selected>prosinac</option>';
                                                }
                                                else {
                                                    echo '<option value="12">prosinac</option>';
                                                }
                                                ?>
                                            </select>
                                        </p>
                                    </div>
                                    <div class="right_input_40 m_right_input_40">
                                        <p style="margin-top: 0px">
                                            <input id="godina" name="godina" class="w3-input w3-border" type="number" min="1900" max="2015" value="<?php echo $godina; ?>">
                                        </p>
                                    </div>
                                </div>
                                <div id="d_broj_telefona" class="middle_input_30 m_left_input_60">
                                    <p style="margin-top: 5px; margin-bottom: 1px">Broj telefona:</p>
                                    <p style="margin-top: 0px">
                                        <input id="broj_telefona" name="broj_telefona" class="w3-input w3-border" type="tel" placeholder="Broj telefona" value="<?php echo $telefon; ?>">
                                    </p>
                                </div>
                                <div id="d_spol" class="right_input_20 m_right_input_40">
                                    <p style="margin-top: 5px; margin-bottom: 1px">Spol:</p>
                                    <p style="margin-top: 0px">
                                        <select id="spol" name="spol" class="w3-select w3-border">
                                            <option value="0" disabled>Spol</option>
                                            <?php
                                            if($spol === "M") {
                                                echo '<option value="1" selected>muški</option>';
                                            }
                                            else {
                                                echo '<option value="1">muški</option>';
                                            }
                                            if($spol === "Ž") {
                                                echo '<option value="2" selected>ženski</option>';
                                            }
                                            else {
                                                echo '<option value="2">ženski</option>';
                                            }
                                            ?>
                                        </select>
                                    </p>
                                </div>
                                <div style="clear: both"></div>
                                <div id="d_adresa" class="left_input_50 m_input">
                                    <p style="margin-top: 5px; margin-bottom: 1px">Adresa:</p>
                                    <div class="left_input_40 m_left_input_40">
                                        <p style="margin-top: 0px">
                                            <input id="grad" name="grad" class="w3-input w3-border" type="text" placeholder="Grad" value="<?php echo $grad; ?>">
                                        </p>
                                    </div>
                                    <div class="middle_input_40 m_middle_input_40">
                                        <p style="margin-top: 0px">
                                            <input id="ulica" name="ulica" class="w3-input w3-border" type="text" placeholder="Ulica" value="<?php echo $ulica; ?>">
                                        </p>
                                    </div>
                                    <div class="right_input_20 m_right_input_20">
                                        <p style="margin-top: 0px">
                                            <input id="broj" name="broj" class="w3-input w3-border" type="text" placeholder="Broj" value="<?php echo $kucni_broj; ?>">
                                        </p>
                                    </div>
                                </div>
                                <div style="clear: both"></div>
                                <div style="text-align: center">
                                    <button id="azuriraj_profil" name="azuriraj_profil" type="submit" class="w3-btn w3-padding w3-red w3-margin-top w3-margin-bottom w3-margin-right button">Ažuriraj profil</button>
                                    <button id="promijeni_lozinku" name="promijeni_lozinku" type="button" onclick="location.href='postavke_profila_lozinka.php'" class="w3-btn w3-padding w3-red w3-margin-top w3-margin-bottom w3-margin-right button">Promijeni lozinku</button>
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
        <script type="text/javascript" src="js/postavke_profila.js?<?php echo filemtime("js/postavke_profila.js") ?>"></script>
        <script type="text/javascript" src="js/proizvodi.js?<?php echo filemtime("js/proizvodi.js") ?>"></script>
        <script type="text/javascript" src="js/proizvodjaci.js?<?php echo filemtime("js/proizvodjaci.js") ?>"></script>
    </body>
</html>