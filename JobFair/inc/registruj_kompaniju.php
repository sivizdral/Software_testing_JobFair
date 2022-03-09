<?php

if (!isset($_POST['regKompanijaButton']))
    header('location:login.php');

require_once 'inc/database.php';
require_once 'phpFunkcije.php';
$un = $dbConn->real_escape_string($_POST['regKompanijaUsername']);
$pa = $dbConn->real_escape_string($_POST['regKompanijaPassword']);
$na = $dbConn->real_escape_string($_POST['regKompanijaNaziv']);
$gr = $dbConn->real_escape_string($_POST['regKompanijaGrad']);
$ad = $dbConn->real_escape_string($_POST['regKompanijaAdresa']);
$ma = $dbConn->real_escape_string($_POST['regKompanijaMail']);
$di = $dbConn->real_escape_string($_POST['regKompanijaDirektor']);
$pi = $dbConn->real_escape_string($_POST['regKompanijaPib']);
$bz = $dbConn->real_escape_string($_POST['regKompanijaBrZap']);
$ww = $dbConn->real_escape_string($_POST['regKompanijaWww']);
$de = $dbConn->real_escape_string($_POST['regKompanijaDelatnost']);
$us = $dbConn->real_escape_string($_POST['regKompanijaSpec']);
//$sl = $dbConn->real_escape_string($_POST['logoKompanija']);
$sl="";
$query = "SELECT * FROM korisnik WHERE username='" . $un . "';";
$queryRes = $dbConn->query($query);


if ($queryRes->num_rows > 0) {
    $poruka = "Takav korisnik već postoji!<br/>";
} else {
    
    $putanja='';
    if (!uploadSlike('logoKompanija','kompanije',$un)) {
        $poruka = $poruka . "Došlo je do greške!<br/>";
        kraj();
    }
    
    $pa=md5($pa);
    $query = "INSERT INTO korisnik (username, password, email, tip) VALUES ('" . $un . "','" . $pa . "','" . $ma . "','2');";
    $queryRes1 = $dbConn->query($query);
    $query = "INSERT INTO kompanija (username, naziv, adresa, grad, direktor, pib, brZaposlenih, webAdresa, slika, delatnost, uzaSpecijalnost) VALUES "
            . "('" . $un . "','" . $na . "','" . $ad . "', '" . $gr . "', '" . $di . "','" . $pi . "','" . $bz . "', '" . $ww . "','" . $putanja . "' , '" . $de . "', '" . $us . "');";

    // echo "<script>alert(".$query.");</script>";
    $queryRes2 = $dbConn->query($query);
    if ($queryRes1 && $queryRes2) {
        $porukaU = "Uspešno ste se registrovali kao kompanija!";
        
    } else {
        if (!isset($poruka))
            $poruka = '';
        $poruka = $poruka + "Došlo je do greške!<br/>";
        
    }
}
?>