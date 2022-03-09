<?php
if (!isset($_SESSION))
    session_start();
if (!isset($_SESSION['tip']))
    header('location: login.php');

function headerStudent() {
    ?>
    <li class="nav-item">
        <a class="nav-link" href="pretragaKompanijaKonkursa.php">Konkursi</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="studentCV.php">CV</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="studentRez.php">Rezultati</a>
    </li>

    <?php
}

function headerKompanija() {
    ?>
    <li class="nav-item">
        <a class="nav-link" href="kompanijaKonkurs.php">Konkursi</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="kompanijaSajam.php">Sajmovi</a>
    </li>


    <?php
}

function headerAdmin() {
    ?>
    <li class="nav-item">
        <a class="nav-link" href="adminRokovi.php">Rokovi</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="adminNoviSajam.php">Novi sajam</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="adminPregled.php">Pregled</a>
    </li>

    <?php
}
?>

