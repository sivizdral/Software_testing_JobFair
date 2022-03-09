<?php
session_start();
require_once 'header.php';
if (isset($_POST['loginButton'])) {
    require_once 'inc/database.php';
    require_once 'inc/login_pokusaj.php';
} elseif (isset($_POST['regStudentButton'])) {
    require_once 'inc/database.php';
    require_once 'inc/registruj_studenta.php';
   
} elseif (isset($_POST['regKompanijaButton'])) {
    require_once 'inc/database.php';
    require_once 'inc/registruj_kompaniju.php';
    
}elseif (isset($_POST['passButton'])) {
    require_once 'inc/database.php';
    require_once 'inc/promeni_lozinku.php';
    
}

if (isset($_SESSION['tip']))
    header("location: index.php");



?>

<div class='wrap hidden' id='greskaPoruka'>
    <div class="alert alert-danger alert-dismissible fade show" role="alert" id='poruka'>
        <div id='porukaSadrzaj'></div>
        <!---<button type="button" class="close" data-dismiss="alert" aria-label="Close" onClick='skloniPoruku()'>
            <span aria-hidden="true">&times;</span>
        </button>-->
    </div>
</div>

<?php
if (isset($poruka)) {
    echo "<script>prikaziGresku('" . $poruka . "');</script>";
}
if (isset($porukaU)) {
    echo "<script>prikaziUspeh('" . $porukaU . "');</script>";
}
?>

<div class="wrapForma" id='wrapLoginForm'>
    <h3 class='regNaslov'>Pristup portalu</h3>
    <form name="loginForma" method="post" action="<?php $_SERVER["PHP_SELF"] ?>" onsubmit="return proveraLoginPodataka();">
        <div class="form-group">
            <label for="loginUsername">Korisničko ime</label>
            <input type="username" class="form-control" name="loginUsername" id="loginUsername" placeholder="username" required="">
        </div>
        <div class="form-group">
            <label for="loginPassword">Lozinka</label>
            <input type="password" class="form-control" name="loginPassword" id="loginPassword" placeholder="password" required="">
        </div>
        <button type="submit" name='loginButton' class="btn btn-primary org">Uloguj se</button>
    </form>
    <a class='linkoviForme' href="#" onclick="forme(2)">Student si, a nemaš nalog? Registruj se!</a><br/>
    <a class='linkoviForme' href="#" onclick="forme(3)">Kompanija si koja hoće da učestvuje na sajmu? Registruj se!</a><br/>
    <a class='linkoviForme' href="#" onclick="forme(4)">Promeni lozinku</a>
</div>

<div class="wrapForma hidden" id='wrapStudentForm'>
    <h3 class='regNaslov'>Registracija studenta</h3>
    <form name="regStudentForma" method="post" action="<?php $_SERVER["PHP_SELF"] ?>" onsubmit="return proveraStudenta();" enctype="multipart/form-data">
        <div class="form-group">
            <label for="regStudentUsername">Korisničko ime</label>
            <input type="text" class="form-control" id="regStudentUsername" name='regStudentUsername' placeholder="username" required>
        </div>
        <div class="form-group">
            <label for="regStudentPassword">Lozinka</label>
            <input type="password" class="form-control" id="regStudentPassword" name='regStudentPassword' placeholder="password" required>
        </div>
        <div class="form-group">
            <label for="regStudentPassword2">Ponovi lozinku</label>
            <input type="password" class="form-control" id="regStudentPassword2" name='regStudentPassword2' placeholder="password" required>
        </div>      
        <div class="form-group">
            <label for="regStudentIme">Ime</label>
            <input type="text" class="form-control" id="regStudentIme" name='regStudentIme' placeholder="Ime" required>
        </div>     
        <div class="form-group">
            <label for="regStudentPrezime">Prezime</label>
            <input type="text" class="form-control" id="regStudentPrezime" name='regStudentPrezime' placeholder="Prezime" required>
        </div> 
        <div class="form-group">
            <label for="regStudentMail">e-mail</label>
            <input type="text" class="form-control" id="regStudentMail" name='regStudentMail' placeholder="example@domain.com" required>
        </div>    
        <div class="form-group">
            <label for="regStudentTel">Telefon</label>
            <input type="text" class="form-control" id="regStudentTel" name='regStudentTel' placeholder="+381XXXXXXXXX" required>
        </div>  
        <div class="form-group">
            <label for="regStudentGodStudija">Godina studija</label>
            <select class="form-control" id="regStudentGodStudija" name="regStudentGodStudija" onChange='prikaziDiplomu()'>
                <option value="1" selected>1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
        </div>
        <div class='hidden' id='wrapDiploma'>
            <div class="form-group" >
                <label>Da li ste diplomirali?</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="regStudentDiploma" id="regStudentDiplomaDa" value="1">
                    <label class="form-check-label" for="regStudentDiplomaDa">
                        DA
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="regStudentDiploma" id="regStudentDiplomaNe" value="0" checked>
                    <label class="form-check-label" for="regStudentDiplomaNe">
                        NE
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label for="slikaStudent">Profilna slika</label>
            <input type="file" class="form-control-file" id="slikaStudent" name='slikaStudent' onchange="slikeTest(this);">
        </div>           
        <br/>
        <button type="submit" name="regStudentButton" class="btn btn-primary org">Registruj se</button>
    </form>
    <a class='linkoviForme' href="#" onclick="forme(1)">Već imaš nalog? Uloguj se!</a><br/>
    <a class='linkoviForme' href="#" onclick="forme(3)">Kompanija si koja hoće da učestvuje na sajmu? Registruj se!</a>
</div>

<div class="wrapForma hidden" id='wrapKompanijaForm'>
    <h3 class='regNaslov'>Registracija kompanije</h3>
    <form name="regKompanijaForma" method="post" action="<?php $_SERVER["PHP_SELF"] ?>" onsubmit="return proveraKompanije();" enctype="multipart/form-data">
        <div class="form-group">
            <label for="regKompanijaUsername">Korisničko ime</label>
            <input type="text" class="form-control" id="regKompanijaUsername" name='regKompanijaUsername' placeholder="username">
        </div>
        <div class="form-group">
            <label for="regKompanijaPassword">Lozinka</label>
            <input type="password" class="form-control" id="regKompanijaPassword" name='regKompanijaPassword' placeholder="password">
        </div>
        <div class="form-group">
            <label for="regKompanijaPassword2">Ponovi lozinku</label>
            <input type="password" class="form-control" id="regKompanijaPassword2" name='regKompanijaPassword2' placeholder="password">
        </div>      
        <div class="form-group">
            <label for="regKompanijaNaziv">Naziv</label>
            <input type="text" class="form-control" id="regKompanijaNaziv" name='regKompanijaNaziv' placeholder="Naziv kompanije">
        </div>     
        <div class="form-group">
            <label for="regKompanijaGrad">Grad</label>
            <input type="text" class="form-control" id="regKompanijaGrad" name='regKompanijaGrad' placeholder="Grad">
        </div> 
        <div class="form-group">
            <label for="regKompanijaAdresa">Adresa</label>
            <input type="text" class="form-control" id="regKompanijaAdresa" name='regKompanijaAdresa' placeholder="Adresa">
        </div>         
        <div class="form-group">
            <label for="regKompanijaMail">e-mail</label>
            <input type="text" class="form-control" id="regKompanijaMail" name='regKompanijaMail' placeholder="example@domain.com">
        </div>    
        <div class="form-group">
            <label for="regKompanijaDirektor">Direktor</label>
            <input type="text" class="form-control" id="regKompanijaDirektor" name='regKompanijaDirektor' placeholder="Ime i prezime">
        </div> 
        <div class="form-group">
            <label for="regKompanijaPib">PIB</label>
            <input type="text" class="form-control" id="regKompanijaPib" name='regKompanijaPib' placeholder="XXXXXXXXX">
        </div> 
        <div class="form-group">
            <label for="regKompanijaBrZap">Broj zaposlenih</label>
            <input type="number" class="form-control" id="regKompanijaBrZap" name='regKompanijaBrZap' placeholder="Broj zaposlenih">
        </div>
        <div class="form-group">
            <label for="regKompanijaWww">WEB adresa</label>
            <input type="text" class="form-control" id="regKompanijaWww" name='regKompanijaWww' placeholder="www.example.com">
        </div>         

        <div class="form-group">
            <label for="regKompanijaDelatnost">Delatnost</label>
            <select class="form-control" id="regKompanijaDelatnost" name='regKompanijaDelatnost'>
                <?php
                require_once 'inc/database.php';
                $query = "SELECT * FROM sifrarnik_delatnosti WHERE 1";
                $queryRes = $dbConn->query($query);
                while ($niz = $queryRes->fetch_assoc()) {
                    echo "<option value=" . $niz['idDelatnosti'] . ">" . $niz['nazivDelatnosti'] . "</option>\n";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="regKompanijaSpec">Uža specijalnost</label>
            <input type="text" class="form-control" id="regKompanijaSpec" name='regKompanijaSpec' placeholder="Navesti užu specijalnost">
        </div> 
        <div class="form-group">
            <label for="logoKompanija">Logo</label>
            <input type="file" class="form-control-file" id="logoKompanija" name='logoKompanija' onchange="slikeTest(this);">
        </div>
        <br/>
        <button type="submit" name="regKompanijaButton" class="btn btn-primary org">Registruj se</button>
    </form>
    <a class='linkoviForme' href="#" onclick="forme(1)">Već imaš nalog? Uloguj se!</a><br/>
    <a class='linkoviForme' href="#" onclick="forme(2)">Student si, a nemaš nalog? Registruj se!</a><br/>
</div>

<div class="wrapForma hidden" id='wrapPassForm'>
    <h3 class='regNaslov'>Promena lozinke</h3>
    <form name="passForma" method="post" action="<?php $_SERVER["PHP_SELF"] ?>" onsubmit="return proveraPassPodataka();">
        <div class="form-group">
            <label for="passUsername">Korisničko ime</label>
            <input type="username" class="form-control" name="passUsername" id="passUsername" placeholder="username" required="">
        </div>
        <div class="form-group">
            <label for="passOldPass">Stara lozinka</label>
            <input type="password" class="form-control" name="passOldPass" id="passOldPass" placeholder="old password" required="">
        </div>
                <div class="form-group">
            <label for="passNewPass">Nova lozinka</label>
            <input type="password" class="form-control" name="passNewPass" id="passNewPass" placeholder="new password" required="">
        </div>
                <div class="form-group">
            <label for="passNewPass2">Ponovite novu lozinku</label>
            <input type="password" class="form-control" name="passNewPass2" id="passNewPass2" placeholder="new password" required="">
        </div>
        <button type="submit" name='passButton' class="btn btn-primary org">Promeni lozinku</button>
    </form>
    <a class='linkoviForme' href="#" onclick="forme(1)">Povratak na login!</a><br/>
    <a class='linkoviForme' href="#" onclick="forme(2)">Student si, a nemaš nalog? Registruj se!</a><br/>
    <a class='linkoviForme' href="#" onclick="forme(3)">Kompanija si koja hoće da učestvuje na sajmu? Registruj se!</a>
</div>



<?php
prikazForma();

function prikazForma(){
    if(isset($_POST['regStudentButton']))echo "<script>forme(2,true);</script>";
    else if(isset($_POST['regKompanijaButton']))echo "<script>forme(3,true);</script>";
    else echo "<script>forme(1,true);</script>";
}
require_once 'footer.php';
?>