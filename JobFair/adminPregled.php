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
?>
<div class='wrap hidden' id='greskaPoruka'>
    <div class="alert alert-danger alert-dismissible fade show" role="alert" id='poruka'>
        <div id='porukaSadrzaj'></div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close" onClick='skloniPoruku()'>
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
</div>
<?php
$ids = imaLiSajma();
if ($ids == 0) {
    greska('Ne postoji sajam');
    pocetna();
    exit();
}
?>

<?php
if (isset($_GET['idp']) && isset($_GET['por']) && isset($_GET['odg'])) {
    $idp = sredi($_GET['idp']);
    $por = sredi($_GET['por']);
    $odg = sredi($_GET['odg']);
    if ($por == "") {
        greska('Poruka ne sme biti prazna');
        pregled();
        kraj();
    }
    $q = "SELECT * FROM s_prijave WHERE idPrijave=" . $idp . ";";
    $qr = qq($q);
    if ($brpr = $qr->num_rows != 1) {
        greska('Ne postoji takva prijava');
        pregled();
        kraj();
    }
    $n = $qr->fetch_assoc();
    $idtp = $n['idPaketa'];
    if ($n['primljen'] != 0) {
        greska('Kompanija je već ocenjena');
        pregled();
        kraj();
    }
    if ($odg != 1 && $odg != -1) {
        greska('Nepravilno unešen rang');
        pregled();
        kraj();
    }

    $q = "SELECT * FROM s_prijave INNER JOIN s_paket ON s_paket.idPaketa=s_prijave.idPaketa WHERE primljen=1 AND s_prijave.idPaketa=" . $idtp . ";";
    $qr = qq($q);
    $brpr = $qr->num_rows;
    if ($brpr > 0) {
        $n = $qr->fetch_assoc();
        if ($n['maksKomp'] == $brpr) {
            greska('Ovaj paket poseduje makismalan broj kompanija');
            pregled();
            kraj();
        }
    }

    $q = "UPDATE s_prijave SET primljen=" . $odg . ", porukaOdbijanja='" . $por . "' WHERE idPrijave=" . $idp . ";";
    $qr = qq($q);
    if (!$qr) {
        greska('Doslo je do greske prilokom potvrde/odbijanja prijave za sajam');
        pregled();
        kraj();
    }
    header("location: adminPregled.php");
}
?>

<?php
if (isset($_GET['unk'])) {
    require 'inc/nocFun.php';
    echo "<br><h2 class='centar' class='odaljiGore'>Informacije o kompaniji</h2>";
    dajKompaniju();
    pregled();

    kraj();
}
?>


<div class='wrap80' style='padding-top:40px;' id='sPregled'>
    <form name='rangForma' action='asd.php' onsubmit="alert(1); return proveraRang();">
        <table class="table belaslova odaljiGore velikaSlova table-dark table-striped">
            <thead>
                <tr>
                    <th scope="col" class="bezGornjeIvice centar" colspan='6'><h3>Sajam: <?php echo $imeSajma; ?></h3></th>
                </tr>
                <tr>
                    <th scope="col">Naziv kompanije</th>
                    <th scope="col">Osnovni paket</th>
                    <th scope="col">Dodatni paketi</th>
                    <th scope="col">Cena</th>
                    <th scope="col" colspan="2" class='centar'>Opcije</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $q = "SELECT * FROM s_prijave INNER JOIN s_paket ON (s_paket.idPaketa=s_prijave.idPaketa) INNER JOIN sajam ON (sajam.idSajma=s_prijave.idSajma) INNER JOIN kompanija ON (kompanija.username=s_prijave.username) WHERE s_prijave.idSajma=" . $ids . " ORDER BY cena DESC;";
                $qr = qq($q);
                if ($qr->num_rows < 1) {
                    echo "<tr><td colspan='6' class='centar'>Nema prijavljenih kompanija</td></tr></tbody></table><br>";
                    pocetna();
                    kraj();
                }
                while ($n = $qr->fetch_assoc()) {
                    ?>
                    <tr>
                        <td> <a href='<?php echo $_SERVER['PHP_SELF'] . "?unk=" . $n['username']; ?>' class='beliLinkovi'><?php echo $n['naziv']; ?></a></td>
                        <td> <?php echo $n['nazivPaketa']; ?></td>
                        <td> <?php
                            $q = "SELECT * FROM s_prijave_dodatni INNER JOIN s_paket ON (s_paket.idPaketa=s_prijave_dodatni.idPaketa) WHERE username='" . $n['username'] . "' AND s_prijave_dodatni.idSajma=" . $ids . ";";
                            $qr1 = qq($q);
                            if ($qr1->num_rows > 0) {
                                while ($n1 = $qr1->fetch_assoc()) {
                                    echo $n1['nazivPaketa'] . "<br>";
                                }
                            } else
                                echo "";
                            ?>
                        </td>
                        <td>
                            <?php echo $n['cena']; ?> 
                        </td>
                        <?php
                        if ($n['primljen'] == 0) {
                            ?>
                            <td><button type='button' class='btn btn-success' onclick="priSaj(true,<?php echo $n['idPrijave']; ?>);">PRIHVATI</button></td>
                            <td><button type='button' class='btn btn-danger' onclick="priSaj(false,<?php echo $n['idPrijave']; ?>);">ODBIJ</button></td>
                        <?php } ?>
                        <?php if ($n['primljen'] == -1) { ?>
                            <td></td>
                            <td><button type='button' class='btn btn-warning' disabled="">ODBIJEN</button></td>
                        <?php } ?>
                        <?php if ($n['primljen'] == 1) { ?>
                            <td></td>
                            <td><button type='button' class='btn btn-warning' onclick="satSaj(<?php echo $n['idPrijave']; ?>);">SATNICA</button></td>
                        <?php } ?>
                    </tr>
                    <tr>
                        <td>Poruka</td>
                        <td colspan="5">
                            <?php if ($n['primljen'] == 0) { ?>
                                <div class="form-group">
                                    <textarea class="form-control" name='porSaj<?php echo $n['idPrijave'] ?>' id='porSaj<?php echo $n['idPrijave'] ?>' rows="2"></textarea>
                                </div>
                            <?php } ?>
                            <?php
                            if ($n['primljen'] != 0) {
                                echo $n['porukaOdbijanja'];
                            }
                            ?>
                        </td>
                    </tr>

                    <?php
                }
                ?>

            </tbody>
        </table>
    </form>
</div>





<?php
require_once 'footer.php';
?>