<?php
session_start();
if (!isset($_SESSION['tip']))
header("location: login.php");
if ($_SESSION['tip'] != 1)
header("location: login.php");
require_once 'header.php';
require_once 'inc/database.php';
?>
<br/>
<h2 class='centar' class='odaljiGore'>Informacije o kompanijama</h2><br/>
            <h4 class='centar'>Poslovi i konkursi</h4>
        <div class='wrapForma nobgc'>
            
            <form name='formaPretragaKP'>
                <div class="form-group">
                    <label for="preNazivK">Naziv kompanije</label>
                    <input type="text" class="form-control" id="preNazivK" name='preNazivK' placeholder="Naziv kompanije" onkeyup="prikaziRezultatPretrageKP()">
                </div>
                <div class="form-group">
                    <label for="preNazivPP">Naziv posla/konkursa</label>
                    <input type="text" class="form-control" id="preNazivPP" name='preNazivPP' placeholder="Grad" onkeyup="prikaziRezultatPretrageKP()">
                </div>

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="0" id="prePr" onchange="prikaziRezultatPretrageKP();">
                    <label class="form-check-label" for="prePr">
                        Praksa
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="1" id="prePo" onchange="prikaziRezultatPretrageKP()">
                    <label class="form-check-label" for="prePo">
                        Posao
                    </label>
                </div>

            </form>
        </div>
        <div class="wrap80">
            <table class="table table-striped table-dark">
                <thead>
                    <tr>
                        <th scope="col">Naizv kompanije</th>
                        <th scope="col">Naziv konkursa</th>
                        <th scope="col">Tip konkursa</th>
                        <th scope="col">Rok za prijavu</th>

                    </tr>
                </thead>
                <tbody id='teloPretragaKP'>
                    <tr>
                        <td colspan="4" align='center'>Unesite parametre pretrage</td>
                    </tr>

                </tbody>
            </table>
        </div>
            <script>prikaziRezultatPretrageKP();</script>

<?php

require_once 'footer.php';
?>