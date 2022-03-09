<?php

if (!isset($_POST['regStudentButton']))
    header('location:login.php');

require_once 'phpFunkcije.php';

$un = $dbConn->real_escape_string($_POST['regStudentUsername']);
$pa = $dbConn->real_escape_string($_POST['regStudentPassword']);
$im = $dbConn->real_escape_string($_POST['regStudentIme']);
$pr = $dbConn->real_escape_string($_POST['regStudentPrezime']);
$ma = $dbConn->real_escape_string($_POST['regStudentMail']);
$te = $dbConn->real_escape_string($_POST['regStudentTel']);
$gs = $dbConn->real_escape_string($_POST['regStudentGodStudija']);
$di = $dbConn->real_escape_string($_POST['regStudentDiploma']);
//$sl = $dbConn->real_escape_string($_POST['slikaStudent']);
$sl = "";

$query = "SELECT * FROM korisnik WHERE username='" . $un . "';";
$queryRes = $dbConn->query($query);

$poruka = '';
if ($queryRes->num_rows > 0) {
    $poruka = "Takav korisnik već postoji!<br/>";
} else {
 $putanja='';
    if (!uploadSlike('slikaStudent','studenti',$un)) {
        $poruka = $poruka . "Došlo je do greške!<br/>";
        kraj();
    }


    $pa = md5($pa);
    $query = "INSERT INTO korisnik (username, password, email, tip) VALUES ('" . $un . "','" . $pa . "','" . $ma . "','1');";
    $queryRes1 = $dbConn->query($query);
    $query = "INSERT INTO osoba (username, ime, prezime, telefon,slika,godinaStudija,diplomirao) VALUES ('" . $un . "','" . $im . "','" . $pr . "','" . $te . "','" . $putanja . "'," . $gs . "," . $di . ");";
    $queryRes2 = $dbConn->query($query);




    if ($queryRes1 && $queryRes2) {
        $porukaU = "Uspešno ste se registrovali!";
        echo "<script>forme(1,true);</script>";
    } else {
        if (!isset($poruka))
            $poruka = '';
        $poruka = $poruka . "Došlo je do greške!<br/>";
    }
}



?>