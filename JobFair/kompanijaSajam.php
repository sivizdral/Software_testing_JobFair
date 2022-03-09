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
if (isset($_GET['osnovniPaket']) && isset($_GET['brDod']) && isset($_GET['ids']) && isset($_GET['ce'])) {
    if (!adminDozvolaKompanije()) {
        greska('Administrator ne dozvoljava prijavu!');
        backSajam();
        exit();
    }

    $ids = $dbConn->real_escape_string($_GET['ids']);
    $op = $dbConn->real_escape_string($_GET['osnovniPaket']);
    $ce = $dbConn->real_escape_string($_GET['ce']);
    $k = 1;
    $i = 0;
    $e = (int) $dbConn->real_escape_string($_GET['brDod']);
    while ($k <= $e) {
        if (isset($_GET['dod' . $k]))
            $dp[$i++] = (int) $dbConn->real_escape_string($_GET['dod' . $k]);
        $k++;
    }
    if (prijavljenNaSajam($un, $ids)) {
        greska("Vec ste prijavljeni");
        backSajam();
        exit();
    }
    $q = "SELECT * FROM s_paket WHERE idSajma=" . $ids;
    // moze se proveriti da li je paket vlasnistvo sajma
    $q = "INSERT INTO s_prijave (idSajma,username,idPaketa, cena) VALUES (" . $ids . ",'" . $un . "'," . $op . ",".$ce.");";
    $qr = $dbConn->query($q);
    if (!$qr) {
        greska("Doslo je do greske");
        exit();
    }
    if (isset($dp)) {
        foreach ($dp as $d) {
            $q = "INSERT INTO s_prijave_dodatni (idSajma,username,idPaketa) VALUES (" . $ids . ",'" . $un . "'," . $d . ");";
            $qr = $dbConn->query($q);
            if (!$qr) {
                greska("Doslo je do greske");
                exit();
            }
        }
    }
    uspeh('Uspesno ste se prijavili');
}
?>

<?php
if (!isset($_GET['pri'])) {
// da li postoje sajmovi na koje moze da se prijavi


    $sad = strtotime(sad());
    $q = "SELECT * FROM sajam ORDER BY krajDatumSajma DESC";
    $queryRes = $dbConn->query($q);
    ?>


    <div class="wrap80 odaljiGore" id="opisS">
        <h3 align='center'>Sajmovi</h3>
        <table class="table table-striped table-dark">
            <thead>
                <tr>
                    <th scope="col">Naziv sajma</th>
                    <th scope="col">Datum održavanja</th>
                    <th scope="col">Rok za prijavu</th>
                    <th scope="col">Opis</th>
                    <th scope="col">Prijava</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($queryRes->num_rows < 1) {
                    echo "<tr><td colspan='5' align='center'>Nema sajmova</td></tr>";
                    exit();
                }

                while ($n = $queryRes->fetch_assoc()) {
                    $r = strtotime($n['rokZaprijavuSajam']);
                    $ks = strtotime($n['krajDatumSajma']);
                    $podb = primljenNaSajam($un, $n['idSajma']);
                    $p = "";
                    $kom = prijavljenNaSajam($un, $n['idSajma']);
                    if (!adminDozvolaKompanije()) {
                        $p = "<button type='button' class='btn btn-warning' disabled> Administrator ne dozvoljava prijavu! </button>";
                    }

                    if ($kom)
                        $p = "<button type='button' class='btn btn-warning' disabled> PRIJAVLJENI STE </button>";
                    if (primljenNaSajam($un, $n['idSajma']) == "DA")
                        $p = "<button type='button' class='btn btn-success' disabled> PRIMLJENI STE </button>";
                    if ($podb != "DA" && $podb != "CEKANJE")
                        $p = "<button type='button' class='btn btn-danger' disabled data-toggle='tooltip' data-placement='top' title='" . $podb . "'> ODBIJENI STE </button>";
                    if ($r < $sad)
                        $p = "<button type='button' class='btn btn-warning' disabled> ROK JE ISTEKAO </button>";
                    if ($ks < $sad)
                        $p = "<button type='button' class='btn btn-warning' disabled> SAJAM JE ZAVRŠEN </button>";
                    if ($p == "" && !$kom)
                        $p = "<a class='beliLinkovi' href='kompanijaSajam.php?pri&ids=" . $n['idSajma'] . "'><button type='button' class='btn btn-warning'> PRIJAVA </button></a>";
                    ?>
                    <tr>
                        <td><?php echo $n['nazivSajma']; ?></td>
                        <td><?php echo $n['pocDatumSajma'] . " - " . $n['krajDatumSajma']; ?></td>
                        <td><?php echo $n['rokZaprijavuSajam']; ?></td>
                        <td><?php echo $n['opisSajma']; ?></td>
                        <td style="width:14% !important;"><?php echo $p ?></td>

                    </tr>
                    <?php
                }
                ?>

            </tbody>
        </table>
    </div>
    <?php
}
?>

<?php
if (isset($_GET['pri']) && isset($_GET['ids'])) {
    if (!adminDozvolaKompanije()) {
        greska('Administrator ne dozvoljava prijavu!');
        backSajam();
        exit();
    }

// prijava na sajam
    $sad = strtotime(sad());
    $ids = $dbConn->real_escape_string($_GET['ids']);
    $q = "SELECT * FROM sajam WHERE idSajma=" . $ids;
    $queryRes = $dbConn->query($q);
    if ($queryRes->num_rows != 1) {
        greska('Greska!');
        backSajam();
        exit();
    }
    $n = $queryRes->fetch_assoc();
    $naz = $n['nazivSajma'];
    if ($sad > strtotime($n['krajDatumSajma'])) {
        greska('Sajam je zavrsen!');
        backSajam();
        exit();
    }
    if ($sad > strtotime($n['rokZaprijavuSajam'])) {
        greska('Rok za prijavu je istekao!');
        backSajam();
        exit();
    }

    if ($sad > strtotime($n['rokZaprijavuSajam'])) {
        greska('Rok za prijavu je istekao!');
        backSajam();
        exit();
    }

    if (prijavljenNaSajam($un, $ids)) {
        greska('Vec ste prijavljeni na sajam!');
        backSajam();
        exit();
    }

    $q = "SELECT * FROM s_paket WHERE idSajma=" . $ids . " ORDER BY dodatni ASC";
    $qr = $dbConn->query($q);
    if ($qr->num_rows < 1) {
        greska('Greska');
        exit();
    }
    $z = 0;
    while ($n = $qr->fetch_assoc()) {
        $paketi[$z] = $n;
        ?>
        <script>
            idPakS[brPakS] =<?php echo json_encode($n['idPaketa']); ?>;
            cePakS[brPakS] =<?php echo json_encode($n['cenaPaketa']); ?>;
            brPakS++;
        </script>
        <?php
        $z++;
    }
    ?>

    <form class="sirina40p" name='priSajam' id='priSajam' action="<?php $_SERVER["PHP_SELF"] ?>" method="get" onsubmit="return proveraPrijaveZaSajam(0);">
        <h5 align='center'>Izbor paketa</h5>
        <div class="form-group">

            <h6>Osnovni paket</h6>
            <select class="form-control" name='osnovniPaket' id="osnovniPaket" onchange="pozadinskaPaket(1);">
                <?php
                for ($i = 0; $i < $z; $i++) {
                    if ($paketi[$i]['dodatni'] == 0) {
                        ?>
                        <option value="<?php echo $paketi[$i]['idPaketa'] ?>"><?php echo $paketi[$i]['nazivPaketa'] . ": " . $paketi[$i]['cenaPaketa'] . " din"; ?></option>   
                        <?php
                    }
                }
                ?>
            </select>

        </div>
        <div class="form-group">

            <h6>Dodatni paketi</h6>
            <?php
            $brDoda = 0;
            $k = 0;
            for ($i = 0; $i < $z; $i++) {
                if ($paketi[$i]['dodatni'] == 1) {
                    $brDoda++;
                    ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" value="<?php echo $paketi[$i]['idPaketa'] ?>" name='dod<?php echo ++$k; ?>' id="dod<?php echo $k; ?>">
                        <label class="form-check-label" for="dod<?php echo $k; ?>">
                            <?php echo $paketi[$i]['nazivPaketa'] . ": " . $paketi[$i]['cenaPaketa'] . " din"; ?>
                        </label>
                    </div>

                    <?php
                }
            }
            ?>
            <input class="form-check-input" type="text" value="<?php echo $brDoda ?>" name='brDod' id='brDod' readonly="" hidden="">
            <input class="form-check-input" type="text" value="<?php echo $ids ?>" name='ids' readonly="" hidden="">
            <input class="form-check-input" type="text" value="" name='ce' id='ce' readonly="" hidden="">

        </div>
        <br>
        <div class="form-group" align="center">
            <button type='submit' name='dugmePriSajam' class='btn btn-warning'>PRIJAVI SE</button>
        </div>
    </form>

    <br>
    <br>
    <div class="form-group" align="center">
        <button type='button' class='btn btn-warning' onclick="pozadinskaPaket(3,<?php echo $ids; ?>)">SVI PAKETI</button>
    </div>

    <div class="wrap80">
        <div id="oP"></div>
        <div id="dP"></div>
    </div>



    <?php ?>
    <div class="modal fade" id="cenaModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle" style='color:black;'>Cena prijave</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button> 
                </div>
                <div class="modal-body" id='divCena' style='color:black;'>
                    Cena
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ZATVORI</button>
                    <button type="button" class="btn btn-warning" onclick="saljiSajamPrijava();">POTVRDI</button>
                </div>
            </div>
        </div>
    </div>




    <?php
}
?>

<?php

function prijavljenNaSajam($un, $ids) {
    global $dbConn;
    $q = "SELECT * FROM s_prijave WHERE idSajma=" . $ids . " AND username='" . $un . "'";
    $queryRes1 = $dbConn->query($q);
    if ($queryRes1->num_rows > 0)
        return true;
    return false;
}

function primljenNaSajam($un, $ids) {
    global $dbConn;
    $q = "SELECT * FROM s_prijave WHERE idSajma=" . $ids . " AND username='" . $un . "'";
    $queryRes1 = $dbConn->query($q);
    if ($queryRes1->num_rows > 0) {

        $n = $queryRes1->fetch_assoc();
        if ($n['primljen'] == 1) {
            return "DA";
        }
        if ($n['primljen'] == -1) {
            return $n['porukaOdbijanja'];
        }
    }
    return "CEKANJE";
}

function adminDozvolaKompanije() {
    global $dbConn;
    $sad = sad();
    $q = "SELECT * FROM rokovi WHERE tipRoka=1 AND rokDole<='" . $sad . "' AND rokGore>='" . $sad . "';";
    $queryRes1 = $dbConn->query($q);
    if ($queryRes1->num_rows > 0)
        return true;
    return false;
}

function sad() {
    return date('Y-m-d');
}

function backSajam() {
    echo "<div align='center'><a class='beliLinkovi' href='kompanijaSajam.php'><button type='button' class='btn btn-warning'> POVRATAK NA PREGLED SAJMOVA </button></a></div>";
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