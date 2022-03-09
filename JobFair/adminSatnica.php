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

if (isset($_POST['dugmeFinal'])) {

    $un = sredi($_POST['dugmeFinal']);
    if (isset($_POST['rezervacije'])) {


        $rez = $_POST['rezervacije'];
        $brM[0] = 0; // maks pred
        $brM[1] = 0; // maks video
        $brM[2] = 0; //maks prez
        $bra = 0;
        $qr_sale = null;
        $q = "SELECT * FROM s_prijave WHERE primljen=1 AND idSajma=" . $ids . " AND username='" . $un . "';";
        $qr = qq($q);
        if ($qr->num_rows < 1) {
            greska('Greska');
            pregled();
            kraj();
        }
        $n = $qr->fetch_assoc();
        $idtp = $n['idPaketa'];
        $q = "SELECT * FROM s_paket WHERE idPaketa=" . $idtp . ";";
        $qr = qq($q);
        if ($qr->num_rows < 1) {
            greska('Greska');
            pregled();
            kraj();
        }
        $n = $qr->fetch_assoc();
        $brM[0] = $n['brojPredavanja'];
        $brM[1] = $n['brojRadionica'];
        $brM[2] = (int) ($n['trajanjeVideo'] * 1.0 / 60) + 1;

        $q = "SELECT DISTINCT * FROM sale WHERE idSajma=" . $ids . " ORDER BY idSale;";
        $qr_sale = qq($q);
        $bra = $qr_sale->num_rows;

        $q = "SELECT * FROM s_satnica WHERE idSajma=" . $ids . " ORDER BY idSale ASC, dan ASC, sat ASC";
        $qr = qq($q);
        if ($qr->num_rows < 1) {
            greska('Nema sala!');
            pregled();
            kraj();
        }

        $qn = '';
        $br[0] = 0;
        $br[1] = 0;
        $br[2] = 0;
        for ($h = 0; $h < $bra; $h++) {
            for ($i = 0; $i < 3; $i++) {
                for ($j = 0; $j < 8; $j++) {
                    $n = $qr->fetch_assoc();
                    for ($t = 0; $t < 3; $t++) {
                        $id = '' . $t . $h . $i . $j;
                        if (isset($rez[$id])) {
                            $qn = $qn . "UPDATE s_satnica SET username='" . $un . "' WHERE idSatnice=" . $n['idSatnice'] . "; ";
                            $br[$t] ++;
                            if ($n['username'] != $un && $n['username'] != null) {
                                greska('Ne mozete uzimati tudje termine!');
                                pregled();
                                kraj();
                            }
                        }
                    }
                }
            }
            for ($t = 0; $t < 3; $t++) {
                if ($br[$t] > $brM[$t]) {
                    greska('Previse zahteva za terminima!');
                    pregled();
                    kraj();
                }
            }
        }
    }
    $q = "UPDATE s_satnica SET username=null WHERE username='" . $un . "';";
    $qr = qq($q);
    if (!$qr) {
        greska('Greska prilikom azuriranja satnice!!');
        pregled();
        kraj();
    }
    if (isset($qn)) {

        $qr = $dbConn->multi_query($qn);
        if (!$qr) {
            greska('Greska prilikom azuriranja satnice');
            pregled();
            kraj();
        }
    }
    
    header('location: adminPregled.php');
}

if (isset($_GET['idp'])) {
    $idp = sredi($_GET['idp']);
    $q = "SELECT * FROM s_prijave INNER JOIN kompanija ON kompanija.username=s_prijave.username WHERE idPrijave=" . $idp . ";";
    $qr = qq($q);
    if ($qr->num_rows < 1) {
        greska('Greska! Ne postoji takva pirjava na ovom sajmu.');
        pregled();
        exit();
    }
    $n = $qr->fetch_assoc();
    $un = $n['username'];
    $nk = $n['naziv'];
    $brd = 0;
    $brs = 0;
    $bra = 0;


    $q = "SELECT COUNT(DISTINCT idSale) FROM s_satnica WHERE idSajma=" . $ids;
    $qr = qq($q);
    if ($qr->num_rows > 0) {
        $bra = $qr->fetch_array()[0];
    }
//    $q = "SELECT COUNT(DISTINCT dan) FROM s_satnica WHERE idSajma=" . $ids;
//    $qr = qq($q);
//    if ($qr->num_rows > 0) {
//        $brd = $qr->fetch_array()[0];
//    }
//    $q = "SELECT COUNT(DISTINCT sat) FROM s_satnica WHERE idSajma=" . $ids;
//    $qr = qq($q);
//    if ($qr->num_rows > 0) {
//        $brs = $qr->fetch_array()[0];
//    }

    $q = "SELECT * FROM s_prijave INNER JOIN s_paket ON s_prijave.idPaketa=s_paket.idPaketa INNER JOIN sajam ON sajam.idSajma=s_prijave.idSajma WHERE s_prijave.idSajma=" . $ids . " AND username='" . $un . "'";
    $qr = qq($q);
    if ($qr->num_rows < 1) {
        greska('Greska!');
        pregled();
        exit();
    }

    $n = $qr->fetch_assoc();
    ?>
    <script>
        _brPredP = Number(<?php echo json_encode($n['brojPredavanja']); ?>);
        _brRadiP = Number(<?php echo json_encode($n['brojRadionica']); ?>);
        _brPrezP = Number(<?php echo json_encode((int) ($n['trajanjeVideo'] * 1.0 / 60) + 1); ?>);
        d1 = new Date(<?php echo json_encode($n['pocDatumSajma']); ?>);
        d2 = new Date(<?php echo json_encode($n['krajDatumSajma']); ?>);
        v1 =<?php echo json_encode($n['pocVremeSajma']); ?>;
        v2 =<?php echo json_encode($n['krajVremeSajma']); ?>;
    </script> 
    <?php
    $q = "SELECT * FROM s_satnica INNER JOIN sale ON sale.idSale=s_satnica.idSale WHERE s_satnica.idSajma=" . $ids . " ORDER BY s_satnica.idSale ASC, sat ASC, dan ASC";
    $qr = qq($q);
    if ($qr->num_rows < 1) {
        greska('Greska!');
        pregled();
        exit();
    }
    $u = $qr->num_rows;
    $kkk = $u / $bra;
    $uu = 0;

    for ($k = 0; $k < $u; $k++) {
        $n = $qr->fetch_assoc();
        if ($k % ($kkk) == 0) {
            echo"<script>idSala[" . ($uu) . "]=" . $n['idSale'] . ";</script>\n";
            echo"<script>nazSala[" . ($uu++) . "]=" . json_encode($n['nazivSale']) . ";</script>\n";
        }
        echo"<script>zauz[" . $k . "]=" . $n['tip'] . ";</script>\n";
        $p = $n['username'];
        if ($n['username'] == $un)
            $p = 1;
        if ($n['username'] == null || $n['username'] == "")
            $p = 0;
        echo"<script>kzauz[" . $k . "]=" . json_encode($p) . ";</script>\n";
    }
    ?>
    <div id='satF' class='wrap80'>
        <br>
        <h2 class='centar'>Satnica za kompaniju <?php echo $nk; ?></h2>
        <br>
        <div id='navSat' class="stotka centar dolePad">
            <button type='button' class='btn btn-warning ld20' onclick="satn(0)" id='dugmePred'>PREDAVANJA</button>
            <button type='button' class='btn btn-warning ld20' onclick="satn(1)" id='dugmeRadi'>RADIONICE</button>
            <button type='button' class='btn btn-warning ld20' onclick="satn(2)" id='dugmePrez'>PREZENTACIJE</button> 
            <a href='adminPregled.php'><button type='button' class='btn btn-light ld20'>PREGLED OSTALIH</button></a>
        </div>
        <form name='formaSatnica' action='<?php echo $_SERVER['PHP_SELF']; ?>' method="POST" onsubmit="return proveraSatnice()">
            <div class="centar" id='zaSatnicu'></div>
            <div class="form-group centar">
                <button class="btn btn-primary" name="dugmeFinal" value="<?php echo $un; ?>">AŽURIRAJ SATNICU</button>
            </div>
        </form>

        <script>popuniSatnicu();</script>
    </div>
    <?php
}
?>

<?php
require_once 'footer.php';
?>