<?php
session_start();
if (!isset($_SESSION['tip']))
    header("location: login.php");
if ($_SESSION['tip'] != 1)
    header("location: index.php");
require_once 'header.php';
require_once 'inc/nocFun.php';
require_once 'inc/database.php';
if (isset($_GET['unk'])) {
    echo "<br><h2 class='centar'>Informacije o kompaniji</h2><br/>";
dajKompaniju();
    $unk = $dbConn->real_escape_string($_GET['unk']);

    ?>


        <br/>
        <h2 class='centar'>Konkursi</h2><br/>

        <table class="table table-striped table-dark">
            <thead>
                <tr>
                    <th scope="col">Naizv konkursa</th>
                    <th scope="col">Tip konkursa</th>
                    <th scope="col">Rok za prijavu</th>
                    <th scope="col">Tekst konkursa</th>
                    <th scope="col">Prijava</th>    
                </tr>
            </thead>
            <tbody id='konkursiKomp'>
                <?php
                $sad = date('Y-m-d H:i:s');

                $query = "SELECT * FROM kompanija,konkurs WHERE username=konkurs.hostKonkursa AND username='" . $unk . "' AND rokZaPrijavu >= '" . $sad . "';";

                $queryRes = $dbConn->query($query);
                if ($queryRes->num_rows > 0) {


                    while ($niz = $queryRes->fetch_assoc()) {
                        $p = "Praksa";
                        if ($niz['tipKonkursa'] == 1)
                            $p = "Posao";
                        ?>

                        <tr>

                            <td><a class='beliLinkovi' href='studentKonkurs.php?idk=<?php echo $niz['idKonkursa'] ?>'> <?php echo $niz['nazivKonkursa']; ?> </a></td> 
                            <td><?php echo $p ?></td>
                            <td><?php echo $niz['rokZaPrijavu']; ?></td>
                            <td><?php echo $niz['tekstKonkursa']; ?></td>
                            <td><a class='beliLinkovi' href='studentKonkurs.php?&idk=<?php echo $niz['idKonkursa'] ?>'><button type='button' class='btn btn-warning'> PRIJAVI SE! </button></a></td>
                        </tr>

                        <?php
                    }
                }
                ?>
            </tbody>
        </table>

        <div class='wrap80 odaljiGore centar' border='1'>
            <a href='pretragaKompanijaKonkursa.php'><button type="button" class="btn btn-warning">Povratak na pretragu</button></a>          
        </div>

    </div>

    <?php
}
require_once 'footer.php';
?>