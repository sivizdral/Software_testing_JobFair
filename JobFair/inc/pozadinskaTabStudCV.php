<?php
session_start();
if (!isset($_SESSION['tip']))
    header("location: login.php");
if ($_SESSION['tip'] != 1 && $_SESSION['tip'] != 2)
    header("location: index.php");

if (isset($_GET['uno'])) {
    require_once 'database.php';
    $un = $dbConn->real_escape_string($_GET['uno']);
    if ($_SESSION['tip'] == 1 && $un!=$_SESSION['kime'])exit();
    $query = "SELECT * FROM biografija WHERE  username='" . $un . "';";
    $queryRes = $dbConn->query($query);
    if ($queryRes->num_rows != 0 && !isset($_GET['bio'])) {


        $query = "SELECT * FROM biografija,osoba WHERE biografija.username=osoba.username AND biografija.username='" . $un . "'";
        $queryRes = $dbConn->query($query);
        if ($queryRes->num_rows > 0) {
            $niz = $queryRes->fetch_assoc();
            $tekstBio = $niz['opis'];
            $ime = $niz['ime'] . " " . $niz['prezime'];
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
        ?>


        <div class='wrap80' style='padding-top:40px;' id='infoK'>

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
?>