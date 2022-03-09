<?php

$dbHost = 'localhost';
$dbUser = 'root';
$dbPass = '';
$dbName = 'jobfair1';

$dbConn = mysqli_connect($dbHost, $dbUser, $dbPass, $dbName) or mysqli_connect_error();

function sredi($p) {
    global $dbConn;
    return $dbConn->real_escape_string($p);
}

function qq($q) {
    global $dbConn;
    return $dbConn->query($q);
}

function kraj() {
    global $dbConn;
    $dbConn->close();
    require_once 'footer.php';
    exit();
}

?>