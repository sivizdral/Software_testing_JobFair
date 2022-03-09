<?php

$un = $dbConn->real_escape_string($_POST['loginUsername']);
$pa = $dbConn->real_escape_string($_POST['loginPassword']);
$pa=md5($pa);
$query = "SELECT * FROM korisnik WHERE username='" . $un . "' AND password='" . $pa . "'";
$queryRes = $dbConn->query($query);
if ($queryRes->num_rows == 1) {
    $niz = $queryRes->fetch_assoc();
    $_SESSION['kime'] = $niz['username'];
    $_SESSION['tip'] = $niz['tip'];
    if ($niz['tip'] == 1) {
        $query = "SELECT * FROM osoba WHERE username='" . $un . "'";
        $queryRes = $dbConn->query($query);
        $_SESSION['korisnik'] = "Student: ";
        if ($queryRes->num_rows > 0) {
            $n = $queryRes->fetch_assoc();
            $_SESSION['korisnik'] = "Student: " . $n['ime'] . " " . $n['prezime'];
            $_SESSION['slika']=$n['slika'];
        }
    }
    if ($niz['tip'] == 2) {
        $query = "SELECT * FROM kompanija WHERE username='" . $un . "'";
        $queryRes = $dbConn->query($query);
        $_SESSION['korisnik'] = "Kompanija: ";
        if ($queryRes->num_rows > 0) {
            $n = $queryRes->fetch_assoc();
            $_SESSION['korisnik'] = "Kompanija: " . $n['naziv'];
            $_SESSION['slika']=$n['slika'];
        }
    }
    if ($niz['tip'] == 3) {
        $_SESSION['korisnik'] = "Administrator";
        $_SESSION['slika']="img/studenti/admin.png";
    }

} else {
    $poruka = "Pogrešan username ili šifra";
}
?>
