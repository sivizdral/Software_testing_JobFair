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
<div class='wrap hidden' id='greskaPoruka'>
    <div class="alert alert-danger alert-dismissible fade show" role="alert" id='poruka'>
        <div id='porukaSadrzaj'></div>
        <!---<button type="button" class="close" data-dismiss="alert" aria-label="Close" onClick='skloniPoruku()'>
            <span aria-hidden="true">&times;</span>
        </button>-->
    </div>
</div>
<?php
if (isset($_GET['idk']) && isset($_GET['rez'])) {
    $idk = $dbConn->real_escape_string($_GET['idk']);
    $rez = $dbConn->real_escape_string($_GET['rez']);
    $sad = date('Y-m-d H:i:s');
    $q = "SELECT * FROM k_prijave,konkurs,osoba WHERE k_prijave.username=osoba.username AND konkurs.idKonkursa=k_prijave.idKonkursa AND k_prijave.idKonkursa='" . $idk . "';";
    // echo $q;exit();
    $queryRes = $dbConn->query($q);
    if ($queryRes->num_rows > 0) {
        ?>

        <div class="wrap80 odaljiGore">
            <h3 align="center">Rezultati konkursa</h3>
            <table class="table table-striped table-dark">
                <thead>
                    <tr>
                        <th scope="col">Osoba</th>
                        <th scope="col">Status</th>
                        <th scope="col">Poruka</th>
                    </tr>
                </thead>
                <tbody id='teloRezK'>

                    <?php
                    if ($queryRes->num_rows > 0) {
                        while ($n = $queryRes->fetch_assoc()) {
                            $st = "Čekanje";
                            if ($n['status'] == -1)
                                $st = "<a href='#' class='crveniLink' data-toggle='tooltip' data-placement='bottom' title='" . $n['porukaOdbijanja'] . "' >Odbijen</a>";
                            if ($n['status'] > 0)
                                $st = "<font class='zeleno'>Primljen: rang " . $n['status'] . "</font>";
                            ?>
                            <tr>
                                <td><?php echo $n['ime'] . " " . $n['prezime']; ?></td>
                                <td><?php echo $st; ?></td>
                                <td><?php echo $n['porukaOdbijanja']; ?></td>
                            </tr>

                            <?php
                        }
                    }else { ?>
                            <tr><td colspan="3">Nema rezultata</td></tr>
                  <?php  } ?>

                </tbody>
            </table>

            <div class='wrap80 odaljiGore centar' border='1'>
                <a href='studentRez.php'><button type="button" class="btn btn-warning">Povratak na rezultate konkursa</button></a>          
            </div>


            <?php
        }exit();
    }

    if (isset($_GET['idk']) && isset($_GET['ocena'])) {
        $sad = date('Y-m-d H:i:s');
        $idk = $dbConn->real_escape_string($_GET['idk']);
        $oc = $dbConn->real_escape_string($_GET['ocena']);
        if (!is_numeric($oc)) {
            echo "<script>prikaziGresku('Greska!')</script>";
            exit();
        }
        $oc = (int) $oc;
        if ($oc < 0 || $oc > 10) {
            echo "<script>prikaziGresku('Greska!')</script>";
            exit();
        }
        $sadM1 = "";
        $sad = date('Y-m-d H:i:s');
        $sad = strtotime($sad . "-1 months");
        $sadM1 = date('Y-m-d', $sad);

        $q = "SELECT * FROM k_primljeni WHERE username='" . $un . "' AND idKonkursa='" . $idk . "' AND ocena=0 AND datumPocetkaRada<='" . $sadM1 . "';";
        $queryRes = $dbConn->query($q);
        if ($queryRes->num_rows == 1) {

            $q = "UPDATE k_primljeni SET ocena=" . $oc . " WHERE username='" . $un . "' AND idKonkursa='" . $idk . "'";
            $queryRes = $dbConn->query($q);
            if ($queryRes)
                echo "<script>prikaziUspeh('Uspesno ste uneli ocenu!')</script>";
            header("location: studentRez.php");
        }else {
            echo "<script>prikaziGresku('Vec ste uneli ocenu!')</script>";
        }
    }
    ?>

    <h3 class='regNaslov odaljiGore' align="center">Rezultati konkursa</h3>
    <div class="wrap80">
        <table class="table table-striped table-dark">
            <thead>
                <tr>
                    <th scope="col">Naizv kompanije</th>
                    <th scope="col">Naziv konkursa</th>
                    <th scope="col">Tip konkursa</th>
                    <th scope="col">Status</th>
                    <th scope="col">Rezultati</th>
                </tr>
            </thead>
            <tbody id='teloRezK'>
                <tr>
                    <?php
                    $sad = date('Y-m-d H:i:s');
                    $query = "SELECT * FROM k_prijave,konkurs INNER JOIN kompanija ON (konkurs.hostKonkursa=kompanija.username) WHERE konkurs.idKonkursa=k_prijave.idKonkursa AND k_prijave.username='" . $un . "';";
                    $queryRes = $dbConn->query($query);
// while ($niz = $queryRes->fetch_assoc()) {print_r($niz); echo "<br><br>";}
                    if ($queryRes->num_rows > 0) {
                        $r = "";
                        $red = "";
                        while ($niz = $queryRes->fetch_assoc()) {
                            $status = "Čekanje";
                            if ($niz['status'] == -1)
                                $status = "<a href='#' class='crveniLink' data-toggle='tooltip' data-placement='bottom' title='" . $niz['porukaOdbijanja'] . "' >Odbijen</a>";
                            if ($niz['status'] > 0)
                                $status = "<font class='zeleno'>Prihvaćen</font>";
                            $ttt = "Posao";
                            if ($niz['tipKonkursa'] == 0)
                                $ttt = "Praksa";
                            $r = "<tr>\n" .
                                    "<td scope='row'><a class='beliLinkovi' href='studentKompanija.php?unk=" . $niz['username'] . "'>" . $niz['naziv'] . "</a></td>\n" .
                                    "<td scope='row'><a class='beliLinkovi' href='studentKonkurs.php?idk=" . $niz['idKonkursa'] . "'>" . $niz['nazivKonkursa'] . "</a></td>\n" .
                                    "<td scope='row'>" . $ttt . "</a></td>\n" .
                                    "<td scope='row'>" . $status . "</td>\n" .
                                    "<td scope='row'><a href='studentRez.php?idk=" . $niz['idKonkursa'] . "&rez=1'>Rezultati</a></td>\n" .
                                    "</tr>\n";
                            $red = $red . $r;
                        }
                        echo $red;
                    }
                    ?>
                </tr>

            </tbody>
        </table>
        <br/><br/>
        <h3 align="center">Oceni</h3>
        <table class="table table-striped table-dark">
            <thead>
                <tr>
                    <th scope="col">Naizv kompanije</th>
                    <th scope="col">Naziv konkursa</th>
                    <th scope="col">Tip konkursa</th>
                    <th scope="col">Početak rada</th>
                    <th scope="col">Ocena</th>
                </tr>
            </thead>
            <tbody id='teloOceneK'>
                <tr>
                    <?php
                    $sad = date('Y-m-d H:i:s');
                    $query = "SELECT * FROM k_primljeni,konkurs INNER JOIN kompanija ON (konkurs.hostKonkursa=kompanija.username) WHERE konkurs.idKonkursa=k_primljeni.idKonkursa AND k_primljeni.username='" . $un . "';";
                    $queryRes = $dbConn->query($query);
//while ($niz = $queryRes->fetch_assoc()) {print_r($niz); echo "<br><br>";}
                    if ($queryRes) {
                        $r = "";
                        $red = "";
                        while ($niz = $queryRes->fetch_assoc()) {
                            $ocena = "Ne možete još uvek da ocenite kompaniju";
                            $poc1 = strtotime($niz['datumPocetkaRada'] . "+1 months");
                            $sad = strtotime($sad);
                            if ($sad >= $poc1) {
                                $ocena = "<input type='number' min='1' max='10' width='2' style='margin-right:10px;' id='ocena" . $niz['idKonkursa'] . "'><a href='#' onclick='upisiOcenu(" . $niz['idKonkursa'] . ");'><button type='button' class='btn btn-warning'>Oceni</button></a>";
                            }
                            if ($niz['ocena'] > 0)
                                $ocena = $niz['ocena'];

                            $ttt = "Posao";
                            if ($niz['tipKonkursa'] == 0)
                                $ttt = "Praksa";
                            $r = "<tr>\n" .
                                    "<td scope='row'><a class='beliLinkovi' href='studentKompanija.php?unk=" . $niz['username'] . "'>" . $niz['naziv'] . "</a></td>\n" .
                                    "<td scope='row'><a class='beliLinkovi' href='studentKonkurs.php?idk=" . $niz['idKonkursa'] . "'>" . $niz['nazivKonkursa'] . "</a></td>\n" .
                                    "<td scope='row'>" . $ttt . "</a></td>\n" .
                                    "<td scope='row'>" . $niz['datumPocetkaRada'] . "</a></td>\n" .
                                    "<td scope='row'>" . $ocena . "</td>\n" .
                                    "</tr>\n";
                            $red = $red . $r;
                        }
                        echo $red;
                    }
                    ?>
                </tr>

            </tbody>
        </table>
    </div>


    <?php
    require_once 'footer.php';
    ?>