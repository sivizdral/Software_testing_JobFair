<?php
session_start();
if (!isset($_SESSION['tip']))
    header("location: login.php");
if ($_SESSION['tip'] != 2)
    header("location: index.php");
$un = $_SESSION['kime'];
require_once 'header.php';
require_once 'inc/database.php';
?>
<div class='wrap hidden' id='greskaPoruka'>
    <div class="alert alert-danger alert-dismissible fade show" role="alert" id='poruka'>
        <div id='porukaSadrzaj'></div>
        <!---<button type="button" class="close" data-dismiss="alert" aria-label="Close" onClick='skloniPoruku()'>
            <span aria-hidden="true">&times;</span>
        </button>-->
    </div>
</div>

<?php
if (isset($_POST['noviKonDugme'])) {

    $g = '';
    $sad = date('Y-m-d');
    $query = "SELECT * FROM rokovi WHERE rokDole <= '" . $sad . "' AND rokGore >= '" . $sad . "' AND tipRoka=1;";
    $queryRes = $dbConn->query($query);
    if ($queryRes->num_rows < 1)
        $g = $g . "Istekao je rok za prijavu konkursa!";
    if (isset($_POST['nazivKon'])) {
        $naz = $dbConn->real_escape_string($_POST['nazivKon']);
    } else
        $g = $g . "Neispravan unos naziva";
    if (isset($_POST['tipKon'])) {
        $ti = $dbConn->real_escape_string($_POST['tipKon']);
        $tip = 0;
        if ($ti == '1')
            $tip = 1;
    } else
        $g = $g . "Neispravan unos tipa";
    if (isset($_POST['rokKon'])) {
        $rok = $dbConn->real_escape_string($_POST['rokKon']);
        $rokx = strtotime($rok);
        $sad = strtotime(date('Y-m-d'));
        if ($rokx < $sad) {
            $g = $g . "Neispravan unos roka";
        }
    } else
        $g = $g . "Neispravan unos roka";
    if (isset($_POST['tekstKon'])) {
        $tekst = $dbConn->real_escape_string($_POST['tekstKon']);
    } else
        $g = $g . "Neispravan unos teksta konkursa";
    if ($g != '') {
        ?>
        <script>prikaziGresku('<?php echo $g; ?>')</script>
        <div  align='center'>
            <a class='beliLinkovi' href='kompanijaKonkurs.php?nk'><button type='button' class='btn btn-warning'> DODAJ NOVI KONKURS! </button></a>
        </div>
        <?php
        exit();
    }

    $q = "SELECT * FROM konkurs WHERE nazivKonkursa='" . $naz . "' AND hostKonkursa='" . $_SESSION['kime'] . "'";
    $queryRes = $dbConn->query($q);
    if ($queryRes->num_rows > 0) {
        $g = $g . "Konkurs sa takvim imenom vec postoji!";
        ?>
        <script>prikaziGresku('<?php echo $g; ?>')</script>
        <?php
        nazadNoviKonkurs(1);
        exit();
    }

    $q = "INSERT INTO konkurs (hostKonkursa,nazivKonkursa,tipKonkursa,tekstKonkursa,rokZaPrijavu) VALUES ('" . $_SESSION['kime'] . "','" . $naz . "'," . $tip . ",'" . $tekst . "','$rok')";
    $queryRes = $dbConn->query($q);
    if (!$queryRes) {
        $g = $g . "Greska!";
        greska($g);
        kraj();
    }

    $idkon = $dbConn->insert_id;
    if (isset($_FILES['prilog'])) {
        $prvi = true;
        $q0 = "INSERT INTO k_fajlovi (konkursVlasnik,putanja) VALUES ";
        $q = "INSERT INTO k_fajlovi (konkursVlasnik,putanja) VALUES ";
        if(!mkdir("files/konkursi/" .$idkon,0777)){
            greska('Greska file upload!');
            kraj();
        }
        $name_array = $_FILES['prilog']['name'];
        $tmp_name_array = $_FILES['prilog']['tmp_name'];
        $type_array = $_FILES['prilog']['type'];
        $size_array = $_FILES['prilog']['size'];
        $error_array = $_FILES['prilog']['error'];
        for ($i = 0; $i < count($tmp_name_array); $i++) {
            $putanja[$i] = "files/konkursi/" .$idkon."/". $name_array[$i];
            if ($error_array[$i] == 0) {
                if (move_uploaded_file($tmp_name_array[$i], $putanja[$i])) {
                    if (!$prvi) {
                        $q = $q . ",";
                    }
                    $q = $q . "(" . $idkon . ",'" . $putanja[$i] . "')";
                    $prvi = false;
                } else {
                    greska('Greska pri file upload-u!');
                    kraj();
                }
            }
        }
    }
    if ($q != $q0) {
        $qr = qq($q);
        if (!$qr) {
            greska('Greska prilikom file upload-a!');
            kraj();
        }
    }
    ?>
    <script>prikaziUspeh('Uspesno prijavljen konkurs')</script>
<?php }
?>

<?php if (isset($_GET['nk'])) { ?>
    <div class='wrap80' style='padding-top:40px;' id='kompKon'>
        <h3 align="center">Novi konkurs</h3>
        <form name='noviKonkurs' method='post' action="<?php echo $_SERVER['PHP_SELF']; ?>" onsubmit="return proveraKonkursa();" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nazivKon">Naziv konkursa</label>
                <input type="text" class="form-control" id="nazivKon" name='nazivKon' placeholder="Naziv konkursa" required="">
            </div>
            <div class="form-group">
                <label for="tipKon">Tip</label>
                <div class="form-group" style="padding-left: 20px;">
                    <input class="form-check-input" type="radio" name="tipKon" id="tipKonPraksa" value="0" checked>
                    <label class="form-check-label" for="tipKonPraksa">
                        Praksa
                    </label><br>
                    <input class="form-check-input" type="radio" name="tipKon" id="tipKonPosao" value="1" checked>
                    <label class="form-check-label" for="tipKonPosao">
                        Posao
                    </label>
                </div>
            </div>
            <div class="form-group">
                <label for="rokKon">Rok za prijavu</label>
                <input class="form-control" type="date" value="" id="rokKon" name="rokKon" required="">

            </div>
            <div class="form-group">
                <label for="tekstKon">Tekst konkursa</label>
                <textarea id="tekstKon" name="tekstKon" class="form-control" rows="5" required=""></textarea>
            </div>

            <div class="form-group">
                <label for="slikaStudent">Fajlovi</label>
                <div id='fajlKonkursDiv' style="padding-bottom:10px;">
                    <input type='file' class='form-control-file' name='prilog[]'>
                </div>
                <button id='dodFajJos' name='dodFajJos' type="button" class="btn btn-warning mb-2" onclick="dodajFajlKonkurs();">DODAJ</button>
                <button id='oduFajJos' name='oduFajJos' type="button" class="btn btn-warning mb-2" onclick="oduzFajlKonkurs();">ODUZMI</button>
            </div> 

            <div class="form-group centar">
                <button id='noviKonDugme' name='noviKonDugme' type="submit" class="btn btn-warning mb-2">Kreiraj konkurs</button>
            </div>


            <br/>
            <div  align='center'>
                <a class='beliLinkovi' href='kompanijaKonkurs.php'><button type='button' class='btn btn-warning'> Povratak na pregled konkursa! </button></a>
            </div>
        </form>
    </div>
<?php } ?>

<?php
if (isset($_GET['rng']) && isset($_GET['idk'])) {
    $g = '';

    $q = "SELECT * FROM konkurs WHERE idKonkursa=" . $_GET['idk'] . " AND hostKonkursa='" . $un . "' AND rangiran=1";

    $queryRes = $dbConn->query($q);
    if ($queryRes->num_rows == 1) {
        $g = $g . "Vec je rangiran";
        greska($g);
        nazadNoviKonkurs(0);
        exit();
    }


    $q = "SELECT * FROM konkurs WHERE idKonkursa=" . $_GET['idk'] . " AND hostKonkursa='" . $un . "'";
    $queryRes = $dbConn->query($q);
    if ($queryRes->num_rows != 1) {
        $g = $g . "Ne postoji takav konkurs";
        greska($g);
        nazadNoviKonkurs(0);
        exit();
    }

    $sad = strtotime(sad());
    $n = $queryRes->fetch_assoc();
    $rok = strtotime($n['rokZaPrijavu']);
    if ($rok > $sad) {
        $g = $g . "Jos uvek nije istekao rok za prijavu!";
        greska($g);
        nazadNoviKonkurs(0);
        exit();
    }

    $naziv = $n['nazivKonkursa'];
    ?>

    <div class="wrap80 odaljiGore">
        <h4 align='center'>Rangiranje konkursa: <?php echo $naziv; ?></h4>
        <input type="text" class="form-control hidden" name="idko" id="idko" value='<?php echo $_GET['idk']; ?>' >
        <br>
        <form name='rangForma' action='asd.php' onsubmit="alert(1); return proveraRang();">
            <table class="table table-striped table-dark">
                <thead>
                    <tr>
                        <th scope="col">Ime i prezime</th>
                        <th scope="col">Telefon</th>
                        <th scope="col">Godina studija</th>

                        <th scope="col" style='width:15%;'>RANG <br> (-1 za odbijanje)</th>
                        <th scope="col">Poruka</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $q = "SELECT * FROM k_prijave INNER JOIN osoba ON k_prijave.username=osoba.username WHERE idKonkursa='" . $n['idKonkursa'] . "'";
                    $queryRes = $dbConn->query($q);
                    if ($queryRes->num_rows > 0) {
                        $x = 0;
                        while ($nn = $queryRes->fetch_assoc()) {
                            $gs = $nn['godinaStudija'];
                            if ($nn['diplomirao'])
                                $gs = "Diplomirao";
                            $x++;
                            ?>
                            <tr>
                                <td><a href="#" class='beliLinkovi' onclick=" kandidatCV('<?php echo $nn['username']; ?>')"><?php echo $nn['ime'] . " " . $nn['prezime']; ?></a></td>
                                <td><?php echo $nn['telefon']; ?></td>
                                <td><?php echo $gs; ?></td>

                                <td>
                                    <input type="number" style='width: 80% !important; margin:auto;' min='-1' class="form-control" name="ocena<?php echo $x; ?>" id="ocena<?php echo $x; ?>" value='' >
                                    <input type="text" class="form-control hidden" style='width: 80% !important;' name="id<?php echo $x; ?>" id="id<?php echo $x; ?>" value='<?php echo $nn['username']; ?>' >

                                </td>
                                <td>
                                    <input type="text" class="form-control" name="po<?php echo $x; ?>" id="po<?php echo $x; ?>" value='' >
                                </td>
                            </tr>
                            <tr>
                                <td scope="col">Cover letter</th>    
                                <td colspan='4'><?php echo $nn['CL']; ?></td>
                            </tr>

                            <?php
                        }
                    } else {
                        echo "<tr><td align='center' colspan='5'>Nema prijavljenih kandidata</td></tr></table>";
                    }
                    ?>
                </tbody>
            </table>
            <br>
            <div class="centar"><button type='button' class='btn btn-warning' onclick="proveraRang();">RANGIRAJ</button></div>
        </form>
        <br>

        <br>
        <div id='kandCV'>

        </div>


    </div>

    <?php
    nazadNoviKonkurs(0);
}
?>

<?php
if (isset($_GET['r']) && isset($_GET['idk'])) {
    $g = '';
    $idk = $dbConn->real_escape_string($_GET['idk']);
    $q = "SELECT * FROM konkurs WHERE idKonkursa=" . $idk . " AND hostKonkursa='" . $un . "' AND rangiran=1";

    $queryRes = $dbConn->query($q);
    if ($queryRes->num_rows == 1) {
        $g = $g . "Vec je rangiran";
        greska($g);
        nazadNoviKonkurs(0);
        exit();
    }
    $r = 0;
    $idk = $dbConn->real_escape_string($_GET['idk']);
    if (is_numeric($dbConn->real_escape_string($_GET['r'])) && is_numeric($idk)) {
        $r = (int) $_GET['r'];
    } else {
        greska('Greska');
        exit();
    }
    $q = "SELECT * FROM k_prijave WHERE idKonkursa=" . $idk;
    $queryRes = $dbConn->query($q);
    if ($queryRes->num_rows != $r) {
        greska('Greska');
        exit();
    }
    $nr = $queryRes->num_rows;
    $i = 1;
    $ocena = array();
    $kand = array();
    $po = array();
    while (isset($_GET['id' . $i]) && isset($_GET['ocena' . $i]) && isset($_GET['po' . $i])) {

        if (isset($_GET['id' . $i]) && is_numeric($_GET['ocena' . $i])) {
            $po[$i] = $dbConn->real_escape_string($_GET['po' . $i]);
            $ocena[$i] = $dbConn->real_escape_string($_GET['ocena' . $i]);
            $kand[$i] = $dbConn->real_escape_string($_GET['id' . $i]);
        }
        $i++;
    }
    $i--;
    if ($nr != $i) {
        greska('Greska');
        exit();
    }
    $i = 1;
    while ($i <= $nr) {
        $g = '';
        $q = "UPDATE k_prijave SET status=" . $ocena[$i] . ", porukaOdbijanja='" . $po[$i] . "' WHERE username='" . $kand[$i] . "' AND idKonkursa='" . $idk . "';";
        $queryRes1 = $dbConn->query($q);
        $queryRes2 = true;
        if ($ocena[$i] > 0) {
            $sad = sad();
            $q = "INSERT INTO k_primljeni (username,idKonkursa,usernameKompanije,datumPocetkaRada,ocena) VALUES('" . $kand[$i] . "'," . $idk . ",'" . $un . "','" . $sad . "',0)";
            $queryRes2 = $dbConn->query($q);
        }
        if (!$queryRes1 || !$queryRes1) {
            $g = $g . "Greska za korisnika:" . $kand[$i] . "<br>";
        }
        $i++;
    }
    if ($g != '') {
        greska('Greska');
        exit();
    }

    $q = "UPDATE konkurs SET rangiran=1 WHERE hostKonkursa='" . $un . "' AND idKonkursa='" . $idk . "';";

    $queryRes = $dbConn->query($q);
    if (!$queryRes) {
        $g = "Greska koukurs:" . $idk . "<br>";
        greska($g);
        exit();
    }

    uspeh('Uspesno ste rangirali');

    nazadNoviKonkurs(0);
}
?>

<?php if (!isset($_GET['r']) && !isset($_GET['rng']) && !isset($_GET['nk'])) { ?>
    <br>
    <h3 align='center'>Konkursi</h3>
    <div class="wrap80 odaljiGore">
        <table class="table table-striped table-dark">
            <thead>
                <tr>
                    <th scope="col">Naziv konkursa</th>
                    <th scope="col">Tip konkursa</th>
                    <th scope="col">Rok za prijavu</th>
                    <th scope="col">Tekst konkursa</th>
                    <th scope="col">Rangiranje</th>

                </tr>
            </thead>
            <tbody>
                <?php
                $q = "SELECT * FROM konkurs WHERE hostKonkursa='" . $un . "' ORDER BY rokZaPrijavu";
                $queryRes = $dbConn->query($q);
                if ($queryRes->num_rows > 0) {
                    $red = "";
                    $r = "";
                    while ($niz = $queryRes->fetch_assoc()) {
                        $ttt = "Posao";
                        $sad = sad();
                        $sad = strtotime($sad);
                        $rp = strtotime($niz['rokZaPrijavu']);
                        $d = "";
                        if ($rp > $sad) {
                            $d = $niz['rokZaPrijavu'];
                        }
                        if ($niz['rangiran'] == 1)
                            $d = "RANGIRAN";
                        if ($d == "") {
                            $d = "<a class='beliLinkovi' href='kompanijaKonkurs.php?rng&idk=" . $niz['idKonkursa'] . "'><button type='button' class='btn btn-warning'> RANG </button></a>";
                        }
                        if ($niz['tipKonkursa'] == 0)
                            $ttt = "Praksa";
                        $r = "<tr>\n" .
                                "<td scope='row'>" . $niz['nazivKonkursa'] . "</td>\n" .
                                "<td scope='row'>" . $ttt . "</td>\n" .
                                "<td scope='row'>" . $niz['rokZaPrijavu'] . "</td>\n" .
                                "<td scope='row'>" . $niz['tekstKonkursa'] . "</td>\n" .
                                "<td scope='row'>" . $d . "</td>\n" .
                                "</tr>\n";

                        $red = $red . $r;
                    }
                    echo $red;
                } else
                    echo "<td colspan='5' align='center'>Nemate konkursa</td>"
                    ?>
            </tbody>
        </table>
    </div>
    <br/>

    <?php
    nazadNoviKonkurs(1);
}
?>


<?php

function nazadNoviKonkurs($a) {
    $t = "KONKURSI";
    $p = "";
    if ($a == 1) {
        $p = "?nk";
        $t = "DODAJ NOVI KONKURS";
    }
    echo "    <div  align='center'>
        <a class='beliLinkovi' href='kompanijaKonkurs.php" . $p . "'><button type='button' class='btn btn-warning'>" . $t . "</button></a>
    </div>";
}

function sad() {
    return date('Y-m-d');
}

function greska($g) {
    ?>
    <script>prikaziGresku("<?php echo $g; ?>");</script>
    <?php
}

function uspeh($g) {
    ?>
    <script>prikaziUspeh("<?php echo $g; ?>");</script>
    <?php
}

require_once 'footer.php';
?>