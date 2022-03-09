<?php
session_start();
if (!isset($_SESSION['tip']))
    header("location: login.php");
if ($_SESSION['tip'] != 3)
    header("location: index.php");
$un = $_SESSION['kime'];
require_once 'header.php';
require_once 'inc/database.php';
require_once 'inc/adminFun.php';
require_once 'inc/phpFunkcije.php';
?>
<div class='wrap hidden' id='greskaPoruka'>
    <div class="alert alert-danger alert-dismissible fade show" role="alert" id='poruka'>
        <div id='porukaSadrzaj'></div>
        <!--        <button type="button" class="close" data-dismiss="alert" aria-label="Close" onClick='skloniPoruku()'>
                    <span aria-hidden="true">&times;</span>
                </button>-->
    </div>
</div>
<?php
if (imaLiSajma() != 0) {
    greska('U toku je sajam.');
    echo "<div align='center'><a href='index.php'><button type='button' class='btn btn-warning'>POČETNA STRANA</button></a></div>";
    exit();
}
?>

<?php
if (isset($_POST['k3sub'])) {
    $g = "";
    if (!isset($_POST['nazivSajma']))
        $g = $g . "Niste uneli naziv<br>";
    if (!isset($_POST['opisSajma']))
        $g = $g . "Niste uneli opis<br>";
    if (!isset($_POST['mestoSajma']))
        $g = $g . "Niste uneli mesto<br>";
    if (!isset($_POST['sajamDatumOD']))
        $g = $g . "Niste uneli datum početka<br>";
    if (!isset($_POST['sajamDatumDO']))
        $g = $g . "Niste uneli datum završetka<br>";
    if (!isset($_POST['sajamVremeOD']))
        $g = $g . "Niste uneli vreme početka<br>";
    if (!isset($_POST['sajamVremeDO']))
        $g = $g . "Niste uneli vreme završetka<br>";
    if (!isset($_POST['rokZaprijavuSajam']))
        $g = $g . "Niste uneli rok za prijavu<br>";
    if ($g != "") {
        greska($g);
        nazadSajam();
        exit();
    }
    $naz = sredi($_POST['nazivSajma']);
    $op = sredi($_POST['opisSajma']);
    $me = sredi($_POST['mestoSajma']);
    $d1 = sredi($_POST['sajamDatumOD']);
    $d2 = sredi($_POST['sajamDatumDO']);
    $v1 = sredi($_POST['sajamVremeOD']);
    $v2 = sredi($_POST['sajamVremeDO']);
    $rok = sredi($_POST['rokZaprijavuSajam']);
    if (date($d1) < sad() || date($rok) < sad()) {
        greska('Sajam ne moze da ima datum pre danasnjeg');
        nazadSajam();
        exit();
    }
    $i = 0;
    while (isset($_POST['nazSale' . ($i + 1)])) {
        $sala[$i - 1] = sredi($_POST['nazSale' . ($i + 1)]);
        $i++;
    }
    $brSala = $i;

    $q = "INSERT INTO sajam (nazivSajma, opisSajma, pocDatumSajma, krajDatumSajma, pocVremeSajma, krajVremeSajma, mestoSajma, rokZaprijavuSajam) VALUES" .
            "('" . $naz . "', '" . $op . "', '" . $d1 . "', '" . $d2 . "', '" . $v1 . "', '" . $v2 . "', '" . $me . "', '" . $rok . "');";


    $qr = qq($q);
    if (!$qr) {
        greska('Doslo je do greske prilikom upisa');
        nazadSajam();
        exit();
    }

    $q = "SELECT * FROM sajam WHERE krajDatumSajma='" . $d2 . "';";
    $qr = qq($q);
    if ($qr->num_rows != 1) {
        greska('Greska');
        nazadSajam();
        exit();
    }
    $n = $qr->fetch_assoc();
    $ids = $n['idSajma'];
    print_r($_FILES);
    if (isset($_FILES['sajamLogo'])) {
        $putanja = '';
        if (!uploadSlike('sajamLogo', 'sajmovi', $ids)) {
            $poruka = $poruka . "Došlo je do greške!<br/>";
            kraj();
        }
        $q = "UPDATE sajam SET logo='" . $putanja . "' WHERE idSajma=" . $ids;
        $qr = qq($q);
        if (!$qr) {
            greska('Greska logo sajma!');
            kraj();
        }
    }
    $kk = 0;
    foreach ($sala as $s) {
        $q = "INSERT INTO sale (idSajma, nazivSale) VALUES ('" . $ids . "', '" . $s . "');";
        $qr = qq($q);
        if (!$qr) {
            greska('Doslo je do greske prilikom upisa sala');
            nazadSajam();
            exit();
        } else {
            $idSala[$kk++] = $dbConn->insert_id;
        }
    }

    //paketi
    $i = 1;
    $opb = -1;
    $dpb = -1;
    while (isset($_POST['pn' . $i])) {
        $np = sredi($_POST['pn' . $i]);
        if (isset($_POST['tipP' . $i])) {
            $t = sredi($_POST['tipP' . $i]);
        } else {
            exit();
        }
        if ($t == 0) {
            if (isset($_POST['pp' . $i]))
                $pp = sredi($_POST['pp' . $i]);
            else
                $pp = 0;
            if (isset($_POST['pr' . $i]))
                $pr = sredi($_POST['pr' . $i]);
            else
                $pr = 0;
            if (isset($_POST['pk' . $i]))
                $pk = sredi($_POST['pk' . $i]);
            else
                $pk = 0;
            if (isset($_POST['pv' . $i]))
                $pv = sredi($_POST['pv' . $i]);
            else
                $pv = 0;
            if (isset($_POST['pc' . $i]))
                $pc = sredi($_POST['pc' . $i]);
            else
                $pc = 0;
            $q = "INSERT INTO s_paket (nazivPaketa, cenaPaketa, idSajma, brojPredavanja, brojRadionica, trajanjeVideo, maksKomp, dodatni) VALUES ('" . $np . "', '" . $pc . "', '" . $ids . "', '" . $pp . "', '" . $pr . "', '" . $pv . "', '" . $pk . "', '0');";

            $qr = qq($q);
            if (!$qr) {
                greska('Doslo je do greske prilikom upisa osnovnog paketa');
                nazadSajam();
                exit();
            }
            $idp = $dbConn->insert_id;

            $j = 1;
            while (isset($_POST['p' . $i . '_' . $j])) {
                $idst = sredi($_POST['p' . $i . '_' . $j]);
                $q = "INSERT INTO s_paket_sadrzaj (idStavke, idPaketa) VALUES (" . $idst . ", " . $idp . ");";
                $qr = qq($q);
                if (!$qr) {
                    greska('Doslo je do greske prilikom upisa sadrzaja osnogvnog paketa');
                    nazadSajam();
                    exit();
                }
                $j++;
            }
        } else {
            if (isset($_POST['pc' . $i]))
                $pc = sredi($_POST['pc' . $i]);
            else
                $pc = 0;
            $q = "INSERT INTO s_paket (nazivPaketa, cenaPaketa, idSajma, dodatni) VALUES ('" . $np . "', '" . $pc . "', '" . $ids . "', '1');";
            $qr = qq($q);
            if (!$qr) {
                greska('Doslo je do greske prilikom upisa dodatnog paketa');
                nazadSajam();
                exit();
            }
        }
        $i++;
    }

    $brd = sredi($_POST['brDanaSajma']);
    $brs = sredi($_POST['brSatiSajma']);
    $bra = sredi($_POST['brSalaSajma']);
    $q = "INSERT INTO s_satnica (idSajma, idSale, dan, sat, tip) VALUES ";
    for ($k = 0; $k < $bra; $k++) {
        for ($i = 0; $i < $brd; $i++) {
            for ($j = 0; $j < $brs; $j++) {
                $t = sredi($_POST['' . $k . $i . $j]);
                $tt = "0"; //predavanje
                if ($t == "radi")
                    $tt = 1;
                if ($t == "prez")
                    $tt = 2;
                if ($k != 0 || $i != 0 || $j != 0)
                    $q = $q . ", ";
                $qp = "(" . $ids . "," . $idSala[$k] . "," . $i . "," . $j . "," . $tt . ")";
                $q = $q . $qp;
            }
        }
    }
    $q = $q . ";";
    $qr = qq($q);
    if (!$qr) {
        greska('Greska prilikom upisivanja satnice sala');
        nazadSajam();
        exit();
    }
    uspeh('Uspešno ste kreirali sajam');

    echo "<div align='center'><a href='index.php'><button type='button' class='btn btn-warning'>POČETNA STRANA</button></a></div>";
    exit();
}
?>

<?php
if (!isset($_GET['k'])) {
    ?>  
    <br>

    <form class="wrap80" name="formaNoviSajam" method="post" action="<?php $_SERVER["PHP_SELF"] ?>" onsubmit="return proveraNoviSajam();" onload="noviSajamRute(1);" enctype="multipart/form-data">
        <div id="korak1">
            <h2 align="center">Novi sajam - Korak 1</h2>
            <div class="form-group">
                <label for="nazivSajma">Naziv sajma</label>
                <input type="text" class="form-control" name="nazivSajma" id="nazivSajma" placeholder="Nazim sajma" value='JobFair 2019'/>
            </div>
            <div class="form-group">
                <label for="opisSajma">Opis</label>
                <textarea type="text" class="form-control" name="opisSajma" id="opisSajma" placeholder="Opis">Pokreni se</textarea>
            </div>
            <div class="form-group">
                <label for="mestoSajma">Mesto sajma</label>
                <input type="text" class="form-control" name="mestoSajma" id="mestoSajma" placeholder="Mesto sajma" value='ETF'/>
            </div>
            <div class="form-row">
                <div class="form-group col">
                    <label for="sajamDatumOD">Datum održavanja OD</label>
                    <input type="date" class="form-control" id="sajamDatumOD" name="sajamDatumOD" value="2019-01-27">
                </div>
                <div class="form-group col">
                    <label for="sajamDatumDO">Datum održavanja DO</label>
                    <input type="date" class="form-control" id="sajamDatumDO" name="sajamDatumDO" value="2019-01-29">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col">
                    <label for="sajamVremeOD">Vreme trajanja OD</label>
                    <input type="time" class="form-control" id="sajamVremeOD" name="sajamVremeOD" value="09:00">
                </div>
                <div class="form-group col">
                    <label for="sajamVremeDO">Vreme trajanja DO</label>
                    <input type="time" class="form-control" id="sajamVremeDO" name="sajamVremeDO" value="13:00">
                </div>
            </div>

            <div class="form-group">
                <div id="sale0" class="hidden"></div>
                <div id="sale1" class="form-group dolePad1">                 
                    <label for="nazSale1">Sala</label>
                    <input type="text" class="form-control" id="nazSale1" name="nazSale1" placeholder="Naziv sale" value="Amfiteatar 65">                
                </div>

                <button type="button" name='obrCVdod' class="btn btn-primary org" onclick="dodSalu()">Dodaj</button>
                <button type="button" name='obrCVbri' class="btn btn-primary org" onclick="obrPosl(7)">Oduzmi</button>
            </div>



            <div class="form-group">
                <label for="rokZaprijavuSajam">Rok za prijavu</label>
                <input type="date" class="form-control" id="rokZaprijavuSajam" name="rokZaprijavuSajam" value="2019-01-26">
            </div>
            <br>
            <div>
                <button type="button" name='k1napo' class="btn btn-primary org" style="float: left;" onclick="noviSajamRute(0)">POČETNA STRANA</button>
                <button type="button" name='k1nak2' class="btn btn-primary org" style="float: right;" onclick="kn(1);">KORAK 2</button>
            </div>
        </div>
        <div id="korak2" class="hidden">
            <h3 class="centar">Korak 2 - Logo i satnica</h3>
            <div class="form-group">
                <label for="sajamLogo">Logo</label><br>
                <input type="file" id="sajamLogo" name="sajamLogo">
            </div>

            <div class="form-group" style="width: 100%;">
                <div id="satnica">

                </div>
            </div>

            <br>
            <div>
                <button type="button" name='k2nak1' class="btn btn-primary org" style="float: left;" onclick="noviSajamRute(1)">KORAK 1</button>
                <button type="button" name='k2nak3' class="btn btn-primary org" style="float: right;" onclick="kn(2);">KORAK 3</button>
            </div>
        </div>
        <div id="korak3" class="hidden">

            <div id="paketiK3">
                <div class='sirina40p'>
                    <div id='p1' >
                        <div id='pn1div' class='dolePad'>
                            <label for='pn1'>Naziv</label>
                            <input type='text' class='form-control' name='pn1' id='pn1' placeholder='Naziv paketa' required=''>
                        </div>
                        <div id='pn1div' class='dolePad'>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipP1" id="osn1" value="0" checked onchange="osnDod(1)">
                                <label class="form-check-label" for="osn1">
                                    Osnovni
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipP1" id="dod1" value="1" onchange="osnDod(1)">
                                <label class="form-check-label" for="dod1">
                                    Dodatni
                                </label>
                            </div>
                        </div>
                        <div id='samoOsn1'>
                            <div class='dolePad' id='p1_ddiv'>
                                <div id='p1_1_div' class='dolePad'>
                                    <label for='p1_1'>Stavke</label>
                                    <select class="form-control " name="p1_1" id="p1_1" required="">
                                        <?php
                                        $q = "SELECT * FROM s_stavke ORDER BY nazivStavke DESC";
                                        $qr = $dbConn->query($q);
                                        if ($qr->num_rows > 0) {
                                            while ($n = $qr->fetch_assoc())
                                                echo "<option value=" . $n['idStavke'] . ">" . $n['nazivStavke'] . "</option>\n";
                                        }
                                        ?>
                                    </select>
                                </div>
                                <button type='button' name='k3nak2' class='btn btn-primary org'onclick='dodStav(1)'>DODAJ</button>
                                <button type='button' name='k3sub' class='btn btn-primary org'  onclick='oduStav(1);'>ODUZMI</button>
                            </div>

                            <div id='pnpdiv' class='dolePad'>
                                <label for='pp1'>Broj predavanja</label>
                                <input type='number' class='form-control' name='pp1' id='pp1' placeholder='' min='0'>
                            </div>


                            <div id='pnrdiv' class='dolePad'>
                                <label for='pr1'>Broj Radionica</label>
                                <input type='number' class='form-control' name='pr1' id='pr1' placeholder=''>
                            </div>

                            <div id='pnvdiv' class='dolePad'>
                                <label for='pv1'>Trajanje video prezentacije</label>
                                <input type='number' class='form-control' name='pv1' id='pv1' placeholder='' min="0">
                            </div>

                            <div id='pnkdiv' class='dolePad'>
                                <label for='pk1'>Maksimalni broj kompanija (0 za neograničeno)</label>
                                <input type='number' class='form-control' name='pk1' id='pk1' placeholder='' min='0' >
                            </div>
                        </div>
                        <div id='pn1div' class='dolePad'>
                            <label for='pc1'>Cena paketa</label>
                            <input type='number' class='form-control' name='pc1' id='pc1' placeholder='' min='0' required=''>
                        </div>


                    </div>
                    <div id='' class='dolePad'>
                        <button type='button' name='k3nak2' class='btn btn-primary org'onclick='dodPak()'>DODAJ PAKET</button>
                        <button type='button' name='k3sub' class='btn btn-primary org'  onclick='oduPak();'>ODUZMI PAKET</button>
                    </div>
                </div>
                <div id="dugmeK3">

                </div>
                <div style="padding:40px;"></div>

            </div>
        </div>
    </form>
    <div style="padding:40px;"></div>
    <?php
}
?>


<?php
require_once 'footer.php';
?>