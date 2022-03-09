<?php
session_start();
if (!isset($_SESSION['tip']))
    header("location: login.php");
if ($_SESSION['tip'] != 3)
    header("location: index.php");
$un = $_SESSION['kime'];
require_once 'header.php';
require_once 'inc/database.php';
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

if(isset($_GET['dugmeAdminRok'])){
    $g='';
    $gg=true;
    if(isset($_GET['rokCVdole'])&& isset($_GET['rokCVgore'])){
        $cv1=$dbConn->real_escape_string($_GET['rokCVdole']);
        $cv2=$dbConn->real_escape_string($_GET['rokCVgore']);    
        if(strtotime($cv1)>strtotime($cv2))$g=$g."Los datum za CV";
    }else $gg=false;
    if(isset($_GET['rokSAdole'])&& isset($_GET['rokSAgore'])){
        $sa1=$dbConn->real_escape_string($_GET['rokSAdole']);
        $sa2=$dbConn->real_escape_string($_GET['rokSAgore']);    
        if(strtotime($sa1)>strtotime($sa2))$g=$g."Los datum za sajam";
    }else $gg=false;
    if($g!=''){greska($g);povratakRok();exit();}
    if(!$gg){greska("Niste došli sa forme!");povratakRok();exit();}
    $q="UPDATE rokovi SET rokDole='".$cv1."', rokGore='".$cv2."' WHERE tipRoka=0;";
    $qr=$dbConn->query($q);
    if(!$qr){greska("Greska prilikom upisa datuma za CV");povratakRok();exit();}
    $q="UPDATE rokovi SET rokDole='".$sa1."', rokGore='".$sa2."' WHERE tipRoka=1;";
    $qr=$dbConn->query($q);
    if(!$qr){greska("Greska prilikom upisa datuma za sajam");povratakRok();exit();}
    uspeh("Uspešno su promenjeni rokovi!");
    
}

if (true) {
    $q = "SELECT * FROM rokovi";
    $qr = $dbConn->query($q);
    if ($qr->num_rows < 1) {
        greska('Greska');
        exit();
    }
    ?>
    <form name="rokAdmin" action="<?php $_SERVER['PHP_SELF'] ?>" onsubmit="return proveraRokova()">

        <?php
        while ($n = $qr->fetch_assoc()) {
            if ($n['tipRoka'] == 0) {
                ?>
                <br><br>
                <div class="wrap80">
                    <h3>Rok za ostavljanje CV-a studenta</h3>
                    <div class="form-row">
                        <div class="form-group col">
                            <label for="rokCVdole">OD</label>
                            <input type="date" class="form-control" id="rokCVdole" name="rokCVdole" value="<?php echo $n['rokDole'] ?>">
                        </div>
                        <div class="form-group col">
                            <label for="rokCVgore">DO</label>
                            <input type="date" class="form-control" id="rokCVgore" name="rokCVgore" value="<?php echo $n['rokGore'] ?>">
                        </div>
                    </div>

                </div>
                <?php
            } else {
                ?>
                <br><br>
                <div class="wrap80">
                    <h3>Rok za prijavljivanje kompanija na sajmove</h3>
                    <div class="form-row">
                        <div class="form-group col">
                            <label for="rokSAdole">OD</label>
                            <input type="date" class="form-control" id="rokSAdole" name="rokSAdole" value="<?php echo $n['rokDole'] ?>">
                        </div>
                        <div class="form-group col">
                            <label for="rokSAgore">DO</label>
                            <input type="date" class="form-control" id="rokSAgore" name="rokSAgore" value="<?php echo $n['rokGore'] ?>">
                        </div>
                    </div>

                </div>
                <?php
            }
        }
        ?>
        <br><br>
        <div class="form-group centar">
            <button type='submit' name='dugmeAdminRok' class='btn btn-warning'>AŽURIRAJ</button>
        </div>


    </form>
<?php } ?>



<?php



function sad() {
    return date('Y-m-d');

}
function povratakRok() {
    echo "<div align='center'><a href='adminRokovi.php'><button type='button' class='btn btn-warning'>ROKOVI</button></a></div>";
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