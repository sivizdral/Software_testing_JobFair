<?php
session_start();
if (!isset($_SESSION['tip']))
    header("location: login.php");
if ($_SESSION['tip'] != 2 && $_SESSION['tip'] != 3)
    header("location: index.php");
if (!isset($dbConn))require "database.php";
?>


<?php
if (isset($_GET['idsPP'])) {
    $ids = $dbConn->real_escape_string($_GET['idsPP']);
    if(isset($_GET['idpPP'])){
    $idp = $dbConn->real_escape_string($_GET['idpPP']);
    $q = "SELECT * FROM s_paket WHERE idSajma=" . $ids . " AND idPaketa=" . $idp;
    }else{
    $q = "SELECT * FROM s_paket WHERE idSajma=" . $ids . " ORDER BY dodatni ASC";
    }
    tabelaStavke($q);
}

function tabelaStavke($q){
    global $dbConn;
    $qr = $dbConn->query($q);
    if ($qr->num_rows < 1) {
        exit();
    }
    ?>

    <table class="table table-dark">
        <?php
        $x = 0;
        $y = 0;
        while ($niz = $qr->fetch_assoc()) {
            if ($niz['dodatni'] == 0) {
                $x++;
                $j = 0;
                $ip[$x] = $niz['idPaketa'];
                $np[$x] = $niz['nazivPaketa'];
                $brojPredavanja = $niz['brojPredavanja'];
                $brojRadionica = $niz['brojRadionica'];
                $trajanjeVideo = $niz['trajanjeVideo'];
                $maksKomp = $niz['maksKomp'];
                $cena[$x] = $niz['cenaPaketa'];
                if ($brojPredavanja > 0)
                    $j++;
                if ($brojRadionica > 0)
                    $j++;
                if ($trajanjeVideo > 0)
                    $j++;
                $j = $j + 3;
                $q = "SELECT * FROM s_paket_sadrzaj,s_stavke WHERE s_paket_sadrzaj.idStavke=s_stavke.idStavke AND s_paket_sadrzaj.idPaketa=" . $ip[$x];
                $qr1 = $dbConn->query($q);
                if ($qr1->num_rows > 0)
                    $j++;
                echo"<tr>";
                echo "<td rowspan='" . $j . "'>" . $np[$x] . "</td>";
                $red=false;
                if ($qr1->num_rows > 0) {
                    $r = "";
                    while ($nn = $qr1->fetch_assoc())
                        $r = $r . $nn['nazivStavke'] . "<br>";
                    echo "<td>Stavke</td><td>" . $r . "</td></tr>";
                    $red = true;
                }

                if ($brojPredavanja > 0) {
                    if ($red)
                        echo "<tr>";
                    echo "<td>Broj predavanja</td><td>" . $brojPredavanja . "</td></tr>";
                    $red = true;
                }

                if ($brojRadionica > 0) {
                    if ($red)
                        echo "<tr>";
                    echo "<td>Broj radionica</td><td>" . $brojRadionica . "</td></tr>";
                    $red = true;
                }

                if ($trajanjeVideo > 0) {
                    if ($red)
                        echo "<tr>";
                    echo "<td>Trajanje video prezentacije</td><td>" . $trajanjeVideo . "</td></tr>";
                    $red = true;
                }
                if ($maksKomp == 0)
                    $maksKomp = "Neograničen";
                echo "<tr><td>Maksimalan broj kompanija</td><td>" . $maksKomp . "</td></tr>";
                echo "<tr><td>Cena</td><td>" . $cena[$x] . "</td></tr>";
                echo "<tr><td class='nemaGornju'></td><td class='nemaGornju'></td></tr>";
            }else {
                $y++;
                $dp[$y] = $niz['idPaketa'];
                $ndp[$y] = $niz['nazivPaketa'];
                $cenad[$y] = $niz['cenaPaketa'];
                echo"<tr>";
                echo "<td rowspan='2'>Dodatni paket</td>";
                echo "<td>Stavka</td>";
                echo "<td>" . $ndp[$y] . "</td>";
                echo "</tr>";
                echo"<tr>";
                echo "<td>Cena</td>";
                echo "<td>" . $cenad[$y] . "</td>";
                echo "</tr>";
            }
        }
        ?>
    </table>
<?php
}
?>



