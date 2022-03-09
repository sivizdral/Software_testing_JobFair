<?php
session_start();
if (!isset($_SESSION['tip']))
    header("location: login.php");
if ($_SESSION['tip'] != 1)
    header("location: index.php");
$un = $_SESSION['kime'];
require_once 'header.php';
require_once 'inc/database.php';
?>
<h3 class='regNaslov odaljiGore' align="center">CV</h3>

<div class='wrap hidden' id='greskaPoruka'>
    <div class="alert alert-danger alert-dismissible fade show" role="alert" id='poruka'>
        <div id='porukaSadrzaj'></div>
    </div>
</div>
<?php
if (isset($_GET['bio'])) {

    $sad = date('Y-m-d H:i:s');
    $query = "SELECT * FROM rokovi WHERE rokDole <= '" . $sad . "' AND rokGore >= '" . $sad . "' AND tipRoka=0;";
    $queryRes1 = $dbConn->query($query);
    if ($queryRes1->num_rows == 0) {
        echo "<script>prikaziGresku('Isteklo je vreme za prijavu');</script>";
        return;
    }
    ?>

    <div id='formaCVwrap' class='wrap80'>

        <form name="formaCV" method="get" action="<?php $_SERVER["PHP_SELF"] ?>" onsubmit="return true;">
            <div class="form-group">
                <label for="opisCV">Opis</label>
                <textarea type="text" class="form-control" name="opisCV" id="opisCV" placeholder="Opis"></textarea>
            </div>
            <div class="form-group">
                <div id="obr0" class="hidden"></div>
                <div id="obr1" class="form-row dolePad">
                    <div class="col">
                        <label for="tipObr1">Edukacija</label>
                        <select class="form-control " name="tipObr1" id="tipObr1" required="">
                            <?php
                            $q = "SELECT * FROM b_tip_institucije,b_institucija WHERE b_tip_institucije.idTipInstitucije=b_institucija.tipInstitucije ORDER BY b_institucija.tipInstitucije";

                            $queryRes = $dbConn->query($q);
                            $rez = "";
                            if ($queryRes->num_rows > 0) {
                                while ($n = $queryRes->fetch_assoc())
                                //print_r($n);
                                    $rez = $rez . "<option value='" . $n['tipInstitucije'] . "-" . $n['idInstitucije'] . "'>" . $n['nazivTipaInstitucije'] . ": " . $n['nazivInstitucije'] . "</option>\n";
                            }
                            echo $rez;
                            ?>
                        </select>

                    </div>
                </div>
                <button type="button" name='obrCVdod' class="btn btn-primary org" onclick="dodObr()">Dodaj</button>
                <button type="button" name='obrCVbri' class="btn btn-primary org" onclick="obrPosl(1)">Oduzmi</button>
            </div>
            <div class="form-group">
                <div id="jez0" class="hidden"></div>
                <div id="jez1" class="form-row dolePad">
                    <div class="col" >
                        <label for="jezCV1">Jezik</label>

                        <select class="form-control" name="jezCV1" id="jezCV1" required="" id="klonJezik">
                            <?php
                            $q = "SELECT * FROM b_jezici";

                            $queryRes = $dbConn->query($q);
                            $rez = "";
                            if ($queryRes->num_rows > 0) {
                                while ($n = $queryRes->fetch_array())
                                    $rez = $rez . "<option value='" . $n[0] . "'>" . $n[1] . "</option>\n";
                            }
                            echo $rez;
                            ?>
                        </select>
                    </div>
                    <div class="col" >
                        <label for="jezMatCV1">Maternji</label>
                        <select class="form-control" name="jezMatCV1" id="jezMatCV1" required="">
                            <option value="0">NE</option>
                            <option value="1">DA</option>
                        </select>
                    </div>
                </div>
                <button type="button" name='jezCVdod' class="btn btn-primary org" onclick="dodJez()">Dodaj</button>
                <button type="button" name='jezCVbri' class="btn btn-primary org" onclick="obrPosl(2)">Oduzmi</button>
            </div>
            <div class="form-group">
                <div id="ri0" class="hidden"></div>
                <div id="ri1" class="dolePad">    
                    <label for="riCV1">Radno iskustvo</label>

                    <select class="form-control" name="riCV1" id="riCV1" required="">
                        <?php
                        $q = "SELECT * FROM b_posao";

                        $queryRes = $dbConn->query($q);
                        $rez = "";
                        if ($queryRes->num_rows > 0) {
                            while ($n = $queryRes->fetch_array())
                                $rez = $rez . "<option value='" . $n[0] . "'>" . $n[1] . "</option>\n";
                        }
                        echo $rez;
                        ?>
                    </select>
                </div>
                <button type="button" name='riCVdod' class="btn btn-primary org" onclick="dodPolje(3)">Dodaj</button>
                <button type="button" name='riCVbri' class="btn btn-primary org" onclick="obrPosl(3)">Oduzmi</button>
            </div>
            <div class="form-group">
                <div id="rr0" class="hidden"></div>
                <div id="rr1" class="dolePad">    
                    <label for="rrCV1">Rad na računaru</label>

                    <select class="form-control" name="rrCV1" id="rrCV1" required="" id="klonJezik">
                        <?php
                        $q = "SELECT * FROM b_racunar";

                        $queryRes = $dbConn->query($q);
                        $rez = "";
                        if ($queryRes->num_rows > 0) {
                            while ($n = $queryRes->fetch_array())
                                $rez = $rez . "<option value='" . $n[0] . "'>" . $n[1] . "</option>\n";
                        }
                        echo $rez;
                        ?>
                    </select>
                </div>
                <button type="button" name='rrCVdod' class="btn btn-primary org" onclick="dodPolje(4)">Dodaj</button>
                <button type="button" name='rrCVbri' class="btn btn-primary org" onclick="obrPosl(4)">Oduzmi</button>
            </div>
            <div class="form-group">
                <div id="ve0" class="hidden"></div>
                <div id="ve1" class="dolePad">    
                    <label for="veCV1">Veštine</label>

                    <select class="form-control" name="veCV1" id="veCV1" required="" id="klonJezik">
                        <?php
                        $q = "SELECT * FROM b_vestine";

                        $queryRes = $dbConn->query($q);
                        $rez = "";
                        if ($queryRes->num_rows > 0) {
                            while ($n = $queryRes->fetch_array())
                                $rez = $rez . "<option value='" . $n[0] . "'>" . $n[1] . "</option>\n";
                        }
                        echo $rez;
                        ?>
                    </select>
                </div>
                <button type="button" name='veCVdod' class="btn btn-primary org" onclick="dodPolje(5)">Dodaj</button>
                <button type="button" name='veCVbri' class="btn btn-primary org" onclick="obrPosl(5)">Oduzmi</button>
            </div>
            <div class="form-group">
                <div id="do0" class="hidden"></div>
                <div id="do1" class="dolePad">    
                    <label for="doCV1">Dozvole</label>

                    <select class="form-control" name="doCV1" id="doCV1" required="" id="klonJezik">
                        <?php
                        $q = "SELECT * FROM b_dozvole";
                        $queryRes = $dbConn->query($q);
                        $rez = "";
                        if ($queryRes->num_rows > 0) {
                            while ($n = $queryRes->fetch_array())
                                $rez = $rez . "<option value='" . $n[0] . "'>" . $n[1] . "</option>\n";
                        }
                        echo $rez;
                        ?>
                    </select>
                </div>
                <button type="button" name='doCVdod' class="btn btn-primary org" onclick="dodPolje(6)">Dodaj</button>
                <button type="button" name='doCVbri' class="btn btn-primary org" onclick="obrPosl(6)">Oduzmi</button>
            </div>
            <br/>
            <button type="submit" name='btnCV' class="btn btn-primary org">Pošalji</button>
        </form>

    </div>

    <?php
    $query = "SELECT * FROM biografija WHERE  username='" . $un . "';";
    $queryRes = $dbConn->query($query);
    if ($queryRes->num_rows == 1) {
        
    }
}
if (isset($_GET['btnCV'])) {

    $pop = true;
    $poruka = "";
    if (isset($_GET['opisCV'])) {
        if ($_GET['opisCV'] != '') {

            $opis = $dbConn->real_escape_string($_GET['opisCV']);
        } else {
            $pop = false;
            $poruka = $poruka . "Niste uneli opis!<br/>";
        }
    } else {
        $pop = false;
        $poruka = $poruka . "Niste uneli opis!<br/>";
    }

    $i = 1;
    while (isset($_GET['tipObr' . $i])) {
        $tipObr[$i] = explode('-', $dbConn->real_escape_string($_GET['tipObr' . $i]))[0];
        $nazObr[$i] = explode('-', $dbConn->real_escape_string($_GET['tipObr' . $i]))[1];
        $i = $i + 1;
    }
    if ($i == 1) {
        $pop = false;
        $poruka = $poruka . "Niste uneli edukaciju!<br/>";
    }
    $i = 1;
    while (isset($_GET['jezCV' . $i]) && isset($_GET['jezMatCV' . $i])) {
        $jez[$i] = $dbConn->real_escape_string($_GET['jezCV' . $i]);
        $jezMat[$i] = $dbConn->real_escape_string($_GET['jezMatCV' . $i]);
        $i = $i + 1;
    }
    if ($i == 1) {
        $pop = false;
        $poruka = $poruka . "Niste uneli jezik!<br/>";
    }
    $i = 1;
    while (isset($_GET['riCV' . $i])) {
        $ri[$i] = $dbConn->real_escape_string($_GET['riCV' . $i]);
        $i = $i + 1;
    }
    if ($i == 1) {
        $pop = false;
        $poruka = $poruka . "Niste uneli radno iskustvo!<br/>";
    }
    $i = 1;
    while (isset($_GET['rrCV' . $i])) {
        $rr[$i] = $dbConn->real_escape_string($_GET['rrCV' . $i]);
        $i = $i + 1;
    }

    if ($i == 1) {
        $pop = false;
        $poruka = $poruka . "Niste uneli znanje rada na računaru!<br/>";
    }
    $i = 1;
    while (isset($_GET['veCV' . $i])) {
        $ve[$i] = $dbConn->real_escape_string($_GET['veCV' . $i]);
        $i = $i + 1;
    }
    if ($i == 1) {
        $pop = false;
        $poruka = $poruka . "Niste uneli veštine!<br/>";
    }
    $i = 1;
    while (isset($_GET['doCV' . $i])) {
        $do[$i] = $dbConn->real_escape_string($_GET['doCV' . $i]);
        $i = $i + 1;
    }
    if ($i == 1) {
        $pop = false;
        $poruka = $poruka . "Niste uneli dozvole!<br/>";
    }

    if (!$pop)
        echo "<script>prikaziGresku('" . $poruka . "');</script>";else {
        $sad = date('Y-m-d');
        $query = "SELECT * FROM rokovi WHERE rokDole <= '" . $sad . "' AND rokGore >= '" . $sad . "' AND tipRoka=0;";
        $queryRes1 = $dbConn->query($query);
        $query = "SELECT * FROM biografija WHERE  username='" . $un . "';";
        $queryRes2 = $dbConn->query($query);
        if ($queryRes1->num_rows == 0) {
            echo "<script>prikaziGresku('Isteklo je vreme za prijavu');</script>";
        } else {
            if ($queryRes2->num_rows == 1) {
                $qb = "DELETE FROM biografija WHERE username='" . $un . "';";
                $queryRes = $dbConn->query($qb);

                if (!$queryRes)
                    echo "<script>prikaziGresku('Doslo je do greske');</script>";
            } {

                $q = "";
                $k = 0;
                $qq = "";
                $g = false;
                $q = "INSERT INTO biografija (username,opis) VALUES ('" . $un . "','" . $opis . "');";
                $i = 1;
                $queryRes = $dbConn->query($q);
                if (!$queryRes)
                    $g = true;
                while (isset($tipObr[$i])) {
                    $q = "INSERT INTO b_veza_institucija (username,idInstitucije) VALUES ('" . $un . "'," . $nazObr[$i] . ");";
                    $i++;
                    if (strpos($qq, $q) == false) {
                        $qq = $qq . $q;
                        $queryRes = $dbConn->query($q);
                        if (!$queryRes)
                            $g = true;
                    }
                }
                $i = 1;
                while (isset($jez[$i])) {
                    $q = "INSERT INTO b_veza_jezici (username,idJezika,maternjiJezik) VALUES ('" . $un . "','" . $jez[$i] . "','" . $jezMat[$i] . "');";
                    $i++;
                    if (strpos($qq, $q) == false) {
                        $qq = $qq . $q;
                        $queryRes = $dbConn->query($q);
                        if (!$queryRes)
                            $g = true;
                    }
                }

                $i = 1;
                while (isset($ri[$i])) {
                    $q = "INSERT INTO b_veza_posao (username,idPosla) VALUES ('" . $un . "','" . $ri[$i] . "');";
                    $i++;
                    if (strpos($qq, $q) == false) {
                        $qq = $qq . $q;
                        $queryRes = $dbConn->query($q);
                        if (!$queryRes)
                            $g = true;
                    }
                }
                $i = 1;
                while (isset($rr[$i])) {
                    $q = "INSERT INTO b_veza_racunar (username,idRadRacunar) VALUES ('" . $un . "','" . $rr[$i] . "');";
                    $i++;
                    if (strpos($qq, $q) == false) {
                        $qq = $qq . $q;
                        $queryRes = $dbConn->query($q);
                        if (!$queryRes)
                            $g = true;
                    }
                }
                $i = 1;
                while (isset($ve[$i])) {
                    $q = "INSERT INTO b_veza_vestine (username,idVestine) VALUES ('" . $un . "','" . $ve[$i] . "');";
                    $i++;
                    if (strpos($qq, $q) == false) {
                        $qq = $qq . $q;
                        $queryRes = $dbConn->query($q);
                        if (!$queryRes)
                            $g = true;
                    }
                }
                $i = 1;
                while (isset($do[$i])) {
                    $q = "INSERT INTO b_veza_dozvole (username,idDozvole) VALUES ('" . $un . "','" . $do[$i] . "');";
                    $i++;
                    if (strpos($qq, $q) == false) {
                        $qq = $qq . $q;
                        $queryRes = $dbConn->query($q);
                        if (!$queryRes)
                            $g = true;
                    }
                }

                if ($g) {
                    $qb = "DELETE FROM biografija WHERE username='" . $un . "';";
                    $queryRes = $dbConn->query($qb);
                    echo "<script>prikaziGresku('Doslo je do greske');</script>";
                } else
                    echo "<script>prikaziUspeh('Bravo!');</script>";
                header('location: studentCV.php?ppp=Bravo!');
            }
        }
    }
} {
    $query = "SELECT * FROM biografija WHERE  username='" . $un . "';";
    $queryRes = $dbConn->query($query);
    if ($queryRes->num_rows != 0 && !isset($_GET['bio'])) {

        $un = $_SESSION['kime'];
        $query = "SELECT * FROM biografija,osoba WHERE biografija.username=osoba.username AND biografija.username='" . $un . "'";
        $queryRes = $dbConn->query($query);
        if ($queryRes->num_rows > 0) {
            $niz = $queryRes->fetch_assoc();
            $tekstBio = $niz['opis'];
            $ime = $niz['ime'] . " " . $niz['prezime'];
            $sp = $niz['slika'];
        }
        $query = "SELECT * FROM biografija,b_dozvole,b_veza_dozvole  WHERE biografija.username='" . $un . "' AND b_veza_dozvole.username=biografija.username AND b_veza_dozvole.idDozvole=b_dozvole.idDozvole";
        $queryRes = $dbConn->query($query);
        if ($queryRes->num_rows > 0) {
            $i = 0;
            while ($niz = $queryRes->fetch_assoc()) {
                $dozvole[$i] = $niz['nazivDozvole'];
                $i++;
            }
        }
        $query = "SELECT * FROM biografija,b_institucija INNER JOIN b_tip_institucije ON b_institucija.tipInstitucije = b_tip_institucije.idTipInstitucije,b_veza_institucija WHERE biografija.username='" . $un . "' AND b_veza_institucija.username=biografija.username AND b_veza_institucija.idInstitucije=b_institucija.idInstitucije";
        $queryRes = $dbConn->query($query);
        if ($queryRes->num_rows > 0) {
            $i = 0;
            while ($niz = $queryRes->fetch_assoc()) {
                $inst[$i] = $niz['nazivInstitucije'];
                $tipi[$i] = $niz['nazivTipaInstitucije'];
                $i++;
            }
        }
        $query = "SELECT * FROM biografija,b_jezici,b_veza_jezici  WHERE biografija.username='" . $un . "' AND b_veza_jezici.username=biografija.username AND b_veza_jezici.idJezika=b_jezici.idJezika";
        $queryRes = $dbConn->query($query);
        if ($queryRes->num_rows > 0) {
            $i = 0;
            while ($niz = $queryRes->fetch_assoc()) {
                $jezik[$i] = $niz['nazivJezika'];
                $mater[$i] = $niz['maternjiJezik']; //0-ne 1-da
                $i++;
            }
        }
        $query = "SELECT * FROM biografija,b_posao,b_veza_posao  WHERE biografija.username='" . $un . "' AND b_veza_posao.username=biografija.username AND b_veza_posao.idPosla=b_posao.idPosla";
        $queryRes = $dbConn->query($query);
        if ($queryRes->num_rows > 0) {
            $i = 0;
            while ($niz = $queryRes->fetch_assoc()) {
                $posao[$i] = $niz['nazivPosla'];
                $i++;
            }
        }
        $query = "SELECT * FROM biografija,b_racunar,b_veza_racunar  WHERE biografija.username='" . $un . "' AND b_veza_racunar.username=biografija.username AND b_veza_racunar.idRadRacunar=b_racunar.idRadRacunar";
        $queryRes = $dbConn->query($query);
        if ($queryRes->num_rows > 0) {
            $i = 0;
            while ($niz = $queryRes->fetch_assoc()) {
                $rr[$i] = $niz['nazivRadRacunar'];
                $i++;
            }
        }
        $query = "SELECT * FROM biografija,b_vestine,b_veza_vestine  WHERE biografija.username='" . $un . "' AND b_veza_vestine.username=biografija.username AND b_veza_vestine.idVestine=b_vestine.idVestine";
        $queryRes = $dbConn->query($query);
        if ($queryRes->num_rows > 0) {
            $i = 0;
            while ($niz = $queryRes->fetch_assoc()) {
                $vest[$i] = $niz['nazivVestine'];
                $i++;
            }
        }

//        $q = "SELECT * FROM osoba WHERE  username='" . $un . "';";
//        $qr = qq($query);
//        if($qr->num_rows>0){
//            $n=$qr->fetch_assoc();
//            $sp=$n['slika'];
//        }
        ?>


        <div class='wrap80' style='padding-top:20px;' id='infoK'>
            <div class="centar dolePad">
                <img  class='okruglaSlika' src="<?php echo $sp; ?>" style="max-width: 200px; max-height: 200px;" alt='<?php echo $ime; ?>'/>
            </div>
            <font class='velikaSlova'>
            <table class="table belaslova odaljiGore velikaSlova table-dark table-striped">
                <thead class="bezGornjeIvice">
                    <tr>
                        <th scope="col" class="bezGornjeIvice">Ime i prezime</th>
                        <th scope="col" class="bezGornjeIvice"><?php echo $ime; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">Opis</th>
                        <td><?php if (isset($tekstBio)) echo $tekstBio; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Edukacija</th>
                        <td><?php
        if (isset($inst)) {
            $nn = count($inst);
            for ($x = 0; $x < $nn; $x++) {
                echo $tipi[$x] . " - " . $inst[$x];
                echo "<br>";
            }
        }
        ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Jezici</th>
                        <td><?php
                            if (isset($jezik)) {
                                $nn = count($jezik);
                                for ($x = 0; $x < $nn; $x++) {
                                    echo $jezik[$x];
                                    if ($mater[$x] == 1)
                                        echo " - Maternji";
                                    echo "<br>";
                                }
                            }
                            ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Radno iskustvo</th>
                        <td><?php
                            if (isset($posao)) {
                                $nn = count($posao);
                                for ($x = 0; $x < $nn; $x++) {
                                    echo $posao[$x];
                                    echo "<br>";
                                }
                            }
                            ?></td>
                    </tr>                    
                    <tr>
                        <th scope="row">Rad na računaru</th>
                        <td><?php
                            if (isset($rr)) {
                                $nn = count($rr);
                                for ($x = 0; $x < $nn; $x++) {
                                    echo $rr[$x];
                                    echo "<br>";
                                }
                            }
                            ?></td>
                    </tr>   
                    <tr>
                        <th scope="row">Veštine</th>
                        <td><?php
                            if (isset($vest)) {
                                $nn = count($vest);
                                for ($x = 0; $x < $nn; $x++) {
                                    echo $vest[$x];
                                    echo "<br>";
                                }
                            }
                            ?></td>
                    </tr>   
                    <tr>
                        <th scope="row">Dozvole</th>
                        <td><?php
                            if (isset($dozvole)) {
                                $nn = count($dozvole);
                                for ($x = 0; $x < $nn; $x++) {
                                    echo $dozvole[$x];
                                    echo "<br>";
                                }
                            }
                            ?></td>
                    </tr>                       

                </tbody>
            </table>
            </font>
        </div>
        <?php
    }
}

if (!isset($_GET['bio'])) {
    ?>
    <div class="wrap80" align="center">
        <a href="studentCV.php?bio=1"><button type="button" name='izmCV' class="btn btn-primary org" onclick="">Izmeni CV</button></a>
    </div>
    <?php
}


if (isset($_GET['ppp'])) {
    echo "<script>prikaziUspeh('" . $_GET['ppp'] . "');</script>";
}

require_once 'footer.php';
?>