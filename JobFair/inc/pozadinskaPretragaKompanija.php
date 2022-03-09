<?php

if (isset($_GET['g']) || isset($_GET['k']) || isset($_GET['d'])) {
    require_once 'database.php';
    $grad = "";
    $kompanija = "";
    $delatnosti = "(0)";
    if (isset($_GET['k'])) {
        $kompanija = ($dbConn->real_escape_string($_GET['k']));
    }
    if (isset($_GET['g'])) {
        $grad = $dbConn->real_escape_string($_GET['g']);
    }

    if (isset($_GET['d'])) {
        if (strlen($_GET['d']) > 0) {
            $delatnosti = "(";
            $x = FALSE;
            $niz = explode(',', $_GET['d']);
            foreach ($niz as $de) {
                if ($x)
                    $delatnosti = $delatnosti . ",";
                $delatnosti = $delatnosti . $de;
                $x = true;
            }
            $delatnosti = $delatnosti . ")";

            $query = "SELECT * FROM kompanija LEFT JOIN sifrarnik_delatnosti ON (kompanija.delatnost=sifrarnik_delatnosti.idDelatnosti) WHERE naziv LIKE '%" . $kompanija . "%' AND grad LIKE '%" . $grad . "%' AND delatnost IN " . $delatnosti . " ORDER BY naziv;";
        }
    }
    if (strlen($_GET['d']) == 0)
        $query = "SELECT * FROM kompanija LEFT JOIN sifrarnik_delatnosti ON (kompanija.delatnost=sifrarnik_delatnosti.idDelatnosti) WHERE naziv LIKE '%" . $kompanija . "%' AND grad LIKE '%" . $grad . "%' ORDER BY naziv;";
//echo $query;
    $queryRes = $dbConn->query($query);
    $red = "";
    if ($queryRes->num_rows > 0) {
        while ($niz = $queryRes->fetch_assoc()) {
            $red = $red."<tr>\n" .
                    "<td scope='row'><a class='beliLinkovi' href='pretragaKompanija.php?unk=" . $niz['username'] . "' onclick='prikaziKompaniju(" . $niz['username'] . "')>" . $niz['naziv'] . "</a></td>\n" .
                    "<td scope='row'>" . $niz['nazivDelatnosti'] . "</td>\n" .
                    "<td scope='row'>" . $niz['grad'] . "</td>\n" .
                    "</tr>\n";
        }
    }else{
        $red="<tr>\n<td colspan='3' align='center'>Nema rezultata pretrage</td>\n</tr>";
    }
    echo $red;
}
?>

