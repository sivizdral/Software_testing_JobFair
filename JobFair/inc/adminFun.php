<?php

function imaLiSajma() {
    global $dbConn;
    global $imeSajma;
    $sad = date('Y-m-d');
    $query = "SELECT * FROM sajam WHERE krajDatumSajma>='" . $sad . "'";
    $queryRes = $dbConn->query($query);

    if ($queryRes->num_rows > 0) {
        $n = $queryRes->fetch_assoc();
        $imeSajma = $n['nazivSajma'];
        return $n['idSajma'];
    }
    return 0;
}

function nazadSajam() {
    echo "<div  align='center'><a class='beliLinkovi' href='adminNoviSajam.php'><button type='button' class='btn btn-warning'>NOVI SAJAM</button></a></div>";
}

function sad() {
    return date('Y-m-d');
}

function povratakRok() {
    echo "<div align='center'><a href='adminRokovi.php'><button type='button' class='btn btn-warning'>ROKOVI</button></a></div>";
}

function pocetna() {
    echo "<div align='center'><a href='index.php'><button type='button' class='btn btn-warning'>POČETNA</button></a></div>";
}

function pregled() {
    echo "<div align='center'><a href='adminPregled.php'><button type='button' class='btn btn-warning'>PREGLED PRIJAVA</button></a></div>";
}

function greska($g) {
    ?>
    <script>prikaziGresku("<?php echo $g; ?>");</script>
    <?php
}

function uspeh($g) {
    ?>
    <script>prikaziUspeh("<?php echo $g; ?>");</script>
    <?php
}

?>