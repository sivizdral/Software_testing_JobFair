<?php

session_start();
if (!isset($_SESSION['tip']))
    header("location: login.php");
if ($_SESSION['tip'] != 1)
    header("location: login.php");

if (isset($_GET['n']) && isset($_GET['k']) && isset($_GET['t'])) {

    require_once 'database.php';
    $k = "";
    $n = "";
    $tt = "(0,1)";
    if (isset($_GET['k'])) {
        $k = ($dbConn->real_escape_string($_GET['k']));
    }
    if (isset($_GET['n'])) {
        $n = $dbConn->real_escape_string($_GET['n']);
    }

    if (isset($_GET['t'])) {
        $t = $dbConn->real_escape_string($_GET['t']);
        if ($t == 0)
            $tt = "(0)";
        if ($t == 1)
            $tt = "(1)";
        if ($t == 2)
            $tt = "(0,1)";
    }

    $sad = date('Y-m-d H:i:s');
    $query = "SELECT * FROM kompanija INNER JOIN konkurs ON (konkurs.hostKonkursa=kompanija.username) WHERE naziv LIKE '%" . $k . "%' AND nazivKonkursa LIKE '%" . $n . "%' AND tipKonkursa IN " . $tt . " AND rokZaPrijavu >= '" . $sad . "';";
    //echo $query;



    $queryRes = $dbConn->query($query);

    $red = "";
    if ($queryRes->num_rows > 0) {

        $r = "";
        while ($niz = $queryRes->fetch_assoc()) {
            $ttt = "Posao";
            if ($niz['tipKonkursa'] == 0)
                $ttt = "Praksa";
            $r = "<tr>\n" .
                    "<td scope='row'><a class='beliLinkovi' href='studentKompanija.php?unk=" . $niz['username'] . "'>" . $niz['naziv'] . "</a></td>\n" .
                    "<td scope='row'><a class='beliLinkovi' href='studentKonkurs.php?idk=" . $niz['idKonkursa'] . "'>" . $niz['nazivKonkursa'] . "</a></td>\n" .
                    "<td scope='row'>" . $ttt . "</a></td>\n" .
                    "<td scope='row'>" . $niz['rokZaPrijavu'] . "</td>\n" .
                    "</tr>\n";

            $red = $red . $r;
        }
    }
    echo $red;
}
?>
