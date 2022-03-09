<?php

if (!isset($_POST['passButton']))
    header('location:login.php');

require_once 'inc/database.php';
$un = $dbConn->real_escape_string($_POST['passUsername']);
$pa0 = $dbConn->real_escape_string($_POST['passOldPass']);
$pa1 = $dbConn->real_escape_string($_POST['passNewPass']);
$pa0=md5($pa0);
$pa1=md5($pa1);
$query = "SELECT * FROM korisnik WHERE username='" . $un . "' AND password='" . $pa0 . "';";
$queryRes = $dbConn->query($query);
if ($queryRes->num_rows == 1) {
    $niz = $queryRes->fetch_assoc();
    $ki = $niz['username'];
    $query = "UPDATE korisnik SET password='" . $pa1 . "' WHERE username='".$ki."';";
    
$queryRes1 = $dbConn->query($query);
    if ($queryRes1) {
        $porukaU = "Uspešno ste promenili lozinku!";
        
    }else $poruka = "Došlo je do greške!<br/>";
} else {
    $poruka = "Pogrešan username ili šifra";
}

echo"<script>alert('Bravo');</script>";
?>