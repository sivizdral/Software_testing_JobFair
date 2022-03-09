var idPakS = new Array();
var cePakS = new Array();
var brPakS = 0;
function proveraKonkursa() {
    var g = '';
    if (document.noviKonkurs.nazivKon.value == '')
        g += 'Niste uneli naziv<br>';
    if (document.noviKonkurs.tipKon.checked)
        g += 'Niste uneli tip<br>';
    if (document.noviKonkurs.rokKon.value == '')
        g += 'Niste uneli datum<br>';
    if (g == '') {

        var s = new Date();
        var f = new Date(document.noviKonkurs.rokKon.value);
        if (f < s)
            g += 'Rok za prijavu je prosao<br>';
    }
    if (document.noviKonkurs.tekstKon.value == '')
        g += 'Niste uneli tekst konkursa<br>';
    if (g == '')
        return true;
    prikaziGresku(g);
    return false;

}

function kandidatCV(uno) {

    {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                if (this.responseText != "")
                    document.getElementById("kandCV").innerHTML = this.responseText;

                else
                    document.getElementById("kandCV").innerHTML = "<tr>\n<td colspan='3' align='center'>Greska</td>\n</tr>";
            }
        }
        xmlhttp.open("GET", "inc/pozadinskaTabStudCV.php?uno=" + uno, true);
        xmlhttp.send();
    }

}

function proveraRang() {
    var link = '';
    var i = 1;
    while (document.getElementById('id' + i) != null) {
        var id1 = 'id' + i;
        var id2 = 'ocena' + i;
        var id3 = 'po' + i;
        var uno = document.getElementById(id1).value;
        var oce = document.getElementById(id2).value;
        var por = document.getElementById(id3).value;
        if (isNaN(oce) || oce == '' || oce < -1 || oce == 0) {
            var greska = "Neispravno unete ocene";
            prikaziGresku(greska);
            return;
        }
        if (oce == -1 && por == '') {
            var greska = "Obavezna poruka za odbijene";
            prikaziGresku(greska);
            return;
        }
        link = link + '&' + id1 + '=' + uno + '&' + id2 + '=' + oce + '&' + id3 + '=' + por;
        i++;
    }
    i--;
    if (i < 0) {
        prikaziGresku('Greska');
        return;
    }
    var idk = document.getElementById('idko').value;
    link = 'kompanijaKonkurs.php?r=' + i + '&idk=' + idk + link;
    window.location.href = link;
}

function pozadinskaPaket(tip, id = 0) {
    var link = "";
    switch (tip) {
        case 1:
            link = glavniPaket();
            break;
        case 3:
            link = "idsPP=" + id;
            break;
    }

    {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById('oP').innerHTML = this.responseText;

            }
        }
        link = "inc/pozadinskaOpisPaketa.php?" + link;
        xmlhttp.open("GET", link, true);
        xmlhttp.send();

}

}

function glavniPaket() {
    var s = document.getElementById("osnovniPaket").value;
    var link = "idsPP=1&idpPP=" + s;
    return link;
}

function proveraPrijaveZaSajam(q) {
    if (q == 0) {
        document.getElementById('divCena').innerHTML = "Cena paketa je: " + cenaPaketa();
        $('#cenaModal').modal('show');
        return false;
    }
    return true;
}

function saljiSajamPrijava() {
    document.getElementById("priSajam").submit();
}

function cenaPaketa() {

    var os = document.getElementById('osnovniPaket').value;
    var osc = Number(cenaZaIDpaketa(os));

    var brdod = document.getElementById('brDod').value;
    var sd = 0;
    for (var j = 0; j < brdod; j++) {
        if (document.getElementById('dod' + (j + 1)).checked)
            sd += Number(cenaZaIDpaketa(document.getElementById('dod' + (j + 1)).value));
    }
    var u=sd + osc;
    document.getElementById('ce').value=u;
    var rez = "Osnovni paket: " + osc + " din<br>" +
            "Suma dodatnih paketa: " + sd + " din<br>" +
            "Ukupno: " + u + " din<br>";
    return rez;
}

function cenaZaIDpaketa(idp) {
    for (var i = 0; i < idPakS.length; i++) {
        if (idPakS[i] == idp)
            return cePakS[i];
    }
}

function dodajFajlKonkurs(){
    $( "#fajlKonkursDiv" ).append( "<div id='dx'><input type='file' class='form-control-file' name='prilog[]'></div>" );
}

function oduzFajlKonkurs(){
    $('#dx:last-child').remove();
}