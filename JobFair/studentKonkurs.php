<?php
session_start();
if (!isset($_SESSION['tip']))
    header("location: login.php");
if ($_SESSION['tip'] != 1)
    header("location: index.php");
require_once 'header.php';
require_once 'inc/database.php';
?>
<div class='wrap hidden' id='greskaPoruka'>
    <div class="alert alert-danger alert-dismissible fade show" role="alert" id='poruka'>
        <div id='porukaSadrzaj'></div>
    </div>
</div>




<?php
if (isset($_GET['idk']) && isset($_GET['pri'])) {
    $idk = $dbConn->real_escape_string($_GET['idk']);
    $pri = $dbConn->real_escape_string($_GET['pri']);
    $te = "";
    if (isset($_GET['cl']))
        $te = $dbConn->real_escape_string($_GET['cl']);
    if ($pri != 1)
        return;

    $gr = false;
    $sad = date('Y-m-d H:i:s');

    $query = "SELECT * FROM k_prijave,konkurs WHERE konkurs.idKonkursa=k_prijave.idKonkursa AND k_prijave.idKonkursa = '" . $idk . "' AND rokZaPrijavu >= '" . $sad . "';";
    $queryRes1 = $dbConn->query($query);
    $query = "SELECT * FROM biografija WHERE  username='" . $_SESSION['kime'] . "';";

    $queryRes2 = $dbConn->query($query);
    if ($queryRes1->num_rows == 0) {
        ?>
        <script>prikaziGresku('Ne postoji takav konkurs!');</script>
        <?php
    }
    if ($queryRes2->num_rows == 0) {
        ?>
        <script>prikaziGresku('Nemate biografiju!');</script>
        <?php
    } else {
        $query = "SELECT * FROM k_prijave WHERE username='" . $_SESSION['kime'] . "' AND idKonkursa = '" . $idk . "';";
        $queryRes = $dbConn->query($query);
        if ($queryRes->num_rows > 0) {
            $gr = true;
            ?>
            <script>prikaziGresku('Već ste prijavljeni na ovaj konkurs!');</script>
            <?php
        } else {
            $query = "INSERT INTO k_prijave (username,idKonkursa,CL) VALUES ('" . $_SESSION['kime'] . "','" . $idk . "','" . $te . "');";
            $queryRes = $dbConn->query($query);
            if ($queryRes) {
                ?>
                <script>prikaziUspeh('Uspešno ste se prijavili na konkurs!');</script>
                <?php
            } else {
                ?>
                <script>prikaziGresku('Došlo je do greške!');</script>
                <?php
            }
        }
    }
} else
if (isset($_GET['idk'])) {

    $idk = $dbConn->real_escape_string($_GET['idk']);
    $sad = date('Y-m-d H:i:s');
    $query = "SELECT * FROM kompanija,konkurs WHERE username=konkurs.hostKonkursa AND idKonkursa='" . $idk . "';";
//echo $query; exit();
    $queryRes = $dbConn->query($query);

    if (!$queryRes->num_rows == 1)
        header("location: index.php");

    if ($niz = $queryRes->fetch_assoc()) {
        $idk = $niz['idKonkursa'];
        ?>
        <p class="hidden" id='idKon'><?php echo $niz['idKonkursa']; ?></p>
        <div class='wrap80' style='padding-top:40px;' id='infoK'>
            <h2 class='centar'>Konkurs</h2><br/>
            <table class="table belaslova odaljiGore">
                <thead class="bezGornjeIvice">
                    <tr>
                        <th scope="col" class="bezGornjeIvice sirina25p">Naziv</th>
                        <th scope="col" class="bezGornjeIvice"><?php echo $niz['nazivKonkursa']; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">Tip konkursa</th>
                        <td><?php
                            if ($niz['tipKonkursa'] == 1)
                                echo "Posao";
                            else
                                echo "Praksa";
                            ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Kompanija</th>
                        <td><?php echo $niz['naziv']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Tekst konkursa</th>
                        <td><?php echo $niz['tekstKonkursa']; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Fajlovi</th>
                        <td><?php
                            $q = "SELECT * FROM k_fajlovi WHERE konkursVlasnik=" . $idk;
                            $qr = qq($q);
                            if ($qr->num_rows == 0)
                                echo "Nema fajlova";
                            else{$m=1;
                                while ($n = $qr->fetch_assoc()) {
                                    ?>
                            <a target='_blank' href='<?php echo $n['putanja'] ?>'><button type="button" class="btn btn-warning"><?php echo $m++;?>. FAJL</button></a>
                                    <?php
                            }}
                            ?>
                        </td>
                    </tr>                
                    <tr>
                        <th scope="row">Propratno pismo</th>
                        <td>
                            <div class="form-group">

                                <textarea id='CLta' class="form-control" rows="5" id="comment"></textarea>
                            </div>

                        </td>

                    </tr>                

                </tbody>
            </table>


            <br/>
            <div  align='center'>
                <a class='beliLinkovi' href='#' onclick='prijaviStudentaNaKonkurs(<?php echo $idk; ?>)'><button type='button' class='btn btn-warning'> PRIJAVI SE! </button></a>
            </div>
        </div>

        <?php
    }
}
?>
<br/>
<div class='wrap80 odaljiGore centar' border='1'>
    <a href='pretragaKompanijaKonkursa.php'><button type="button" class="btn btn-warning">Povratak na pretragu</button></a>          
</div>
<?php
require_once 'footer.php';
?>