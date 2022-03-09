<?php
session_start();

require_once 'header.php';
require_once 'inc/database.php';
require_once 'inc/nocFun.php';
if (isset($_GET['unk'])) {
    echo "<br><h2 class='centar' class='odaljiGore'>Informacije o kompaniji</h2>";
    dajKompaniju();
    ?>

    <div class='wrap80 odaljiGore centar' border='1'>
        <a href='pretragaKompanija.php'><button type="button" class="btn btn-warning">Povratak na pretragu</button></a>          
    </div>

    <?php
} else {
    ?>
    <br/>
    <h2 class='centar' class='odaljiGore'>Informacije o kompanijama</h2><br/>

    <div class='wrapForma nobgc'>
        <form name='formaPretragaKomp'>
            <div class="form-group">
                <label for="preNazivKompanije">Naziv kompanije</label>
                <input type="text" class="form-control" id="preNazivKompanije" name='preNazivKompanije' placeholder="Naziv kompanije" onkeyup="prikaziRezultatPretrageKompanija()">
            </div>
            <div class="form-group">
                <label for="preGradKompanije">Grad</label>
                <input type="text" class="form-control" id="preGradKompanije" name='preGradKompanije' placeholder="Grad" onkeyup="prikaziRezultatPretrageKompanija()">
            </div>

            <div class="form-group">
                <label for="preDelatnostKompanije">Delatnosti</label>
                <select multiple class="form-control" id="preDelatnostKompanije" name='preDelatnostKompanije' onchange="prikaziRezultatPretrageKompanija()">
    <?php
    $query = "SELECT * FROM sifrarnik_delatnosti WHERE 1";
    $queryRes = $dbConn->query($query);
    while ($niz = $queryRes->fetch_assoc()) {
        echo "<option value=" . $niz['idDelatnosti'] . ">" . $niz['nazivDelatnosti'] . "</option>\n";
    }
    ?>
                </select>
            </div>

        </form>
    </div>
    <div class="wrap80">
        <table class="table table-striped table-dark">
            <thead>
                <tr>
                    <th scope="col">Naizv kompanije</th>
                    <th scope="col">Delatnost</th>
                    <th scope="col">Grad</th>

                </tr>
            </thead>
            <tbody id='teloPretragaKomp'>
                <tr>
                    <td colspan="3" align='center'>Unesite parametre pretrage</td>
                </tr>

            </tbody>
        </table>
    </div>

    <?php
} echo " <script>prikaziRezultatPretrageKompanija();</script>";
require_once 'footer.php';
?>