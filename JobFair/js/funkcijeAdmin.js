var brSal = 1;
var sale = new Array();
var pop2 = false;
var pop3 = false;
var brd = 0;
var brs = 0;
var pred = 0;
var prez = 0;
var radi = 0;
var brStav = new Array(0, 1);
var brPak = 1;
var _brPredP;
var _brRadiP;
var _brPrezP;
var bra;
var zauz = new Array();
var kzauz = new Array();
var idSala = new Array();
var nazSala = new Array();
var d1;
var d2;
var v1;
var v2;
var brTab = new Array();

function proveraRokova() {
    var cv1 = document.getElementById('rokCVdole').value;
    var cv2 = document.getElementById('rokCVgore').value;
    var sa1 = document.getElementById('rokSAdole').value;
    var sa2 = document.getElementById('rokSAgore').value;
    cv1 = new Date(cv1);
    cv2 = new Date(cv2);
    sa1 = new Date(sa1);
    sa2 = new Date(sa2);
    var g = '';
    if (cv1 > cv2)
        g += 'Datumi za CV nisu dobri<br>';
    if (sa1 > sa2)
        g += 'Datumi za sajmove nisu dobri<br>';
    if (g != '') {
        prikaziGresku(g);
        return false;
    }
    return true;
}

function noviSajamRute(b, y = false) {
    if (!y) {
        $("#greskaPoruka").hide();
    }
    if (b == 0) {
        window.location.href = 'index.php';
    }
    if (b == 1) {
        $("#korak1").show();
        $("#korak2").hide();
        $("#korak3").hide();
    }
    if (b == 2) {
        $("#korak1").hide();
        $("#korak2").show();
        $("#korak3").hide();
    }

    if (b == 3) {
        $("#korak1").hide();
        $("#korak2").hide();
        $("#korak3").show();
}

}

function dodSalu() {
    var stariElid = "sale" + brSal;
    brSal = brSal + 1;
    var noviElid = "sale" + brSal;
    var id1 = "nazSale" + brSal;
    var noviEl =
            "<div id='" + noviElid + "' class='form-group dolePad1'>" +
            "<label for=" + id1 + ">Sala</label>" +
            "<input type='text' class='form-control' id=" + id1 + " name=" + id1 + " placeholder='Naziv sale'>" +
            "</div>";
    $(noviEl).insertAfter('#' + stariElid);
}

function proveraNoviSajamK1() {
    //return true;
    var naz = document.formaNoviSajam.nazivSajma.value;
    var opi = document.formaNoviSajam.opisSajma.value;
    var mes = document.formaNoviSajam.mestoSajma.value;
    var d1 = document.formaNoviSajam.sajamDatumOD.value;
    var d2 = document.formaNoviSajam.sajamDatumDO.value;
    var v1 = document.formaNoviSajam.sajamVremeOD.value;
    var v2 = document.formaNoviSajam.sajamVremeDO.value;
    var rok = document.formaNoviSajam.rokZaprijavuSajam.value;
    sale = new Array();
    var i = 1;
    var b = new Array();
    var p = true;
    while (p) {
        var name = "nazSale" + i;
        var a = document.getElementById(name);
        if (a) {
            if (a.value) {
                b[i - 1] = a.value;
                i++;
            } else
                p = false;
        } else
            p = false
    }
    var g = '';
    if (!naz)
        g += 'Niste uneli naziv<br>';
    if (!opi)
        g += 'Niste uneli opis<br>';
    if (!mes)
        g += 'Niste uneli mesto<br>';
    if (!d1)
        g += 'Niste uneli datum početka<br>';
    if (!d2)
        g += 'Niste uneli datum završetka<br>';
    if (!v1)
        g += 'Niste uneli vreme početka<br>';
    if (!v2)
        g += 'Niste uneli vreme završetka<br>';
    if (!rok)
        g += 'Niste uneli rok za prijavu<br>';
    if (b.length == 0)
        g += 'Niste uneli salu<br>';
    if (g == '') {
        d1 = new Date(d1);
        d2 = new Date(d2);
        rok = new Date(rok);
        if (d1 > d2)
            g += 'Datumi su pogrešnom redosledu. <br>';
        if (rok > d1)
            g += 'Rok za prijavu je nakon početka sajma. <br>';
        v1 = Date.parse('01/01/2000 ' + v1);
        v2 = Date.parse('01/01/2000 ' + v2);
        if (v1 > v2)
            g += 'Vreme je u pogrešnom redosledu.<br>';
    }
    sale = b;
    brd = brDana();
    brs = brSati();
    if (g != '') {
        prikaziGresku(g);
        return false;
    }

    return true;
}

function kn(k) {
    if (k === 1) {
        if (proveraNoviSajamK1()) {
            popuniKorak2();
            noviSajamRute(2);
        }
    }
    if (k === 2) {
        if (proveraNoviSajamK2()) {
            if (!pop3)
                popuniKorak3();
            noviSajamRute(3);
        }
    }
    if (k === 3) {
        if (proveraNoviSajamK3()) {
            document.getElementById('formaNoviSajam').submit();
        }
    }
}

function popuniKorak2() {
    if (!proveraNoviSajamK1())
        return;
    var s = sale;
    var n = s.length;
    var pocd = document.formaNoviSajam.sajamDatumOD.value;
    var pocv = document.formaNoviSajam.sajamVremeOD.value;
    var pocd1 = new Date(pocd);
    var pocd2 = new Date(pocd);
//    var brd = brDana();
//    var brs = brSati();
    var pocv1 = new Date(Date.parse('01/01/2000 ' + pocv));
    var pocv2 = new Date(Date.parse('01/01/2000 ' + pocv));
    var res = '';
    for (var i = 0; i < n; i++) {
        res += "<h4 align='center'>" + s[i] + "</h4>\n";
        res += "<table class='table belaslova table-dark table-striped'>\n" +
                "<thead class='bezGornjeIvice'>\n" +
                "<tr><th scope='col' class='bezGornjeIvice'></th>\n";
        for (var l = 0; l < brd; l++) {
            res += "<th scope='col' class='bezGornjeIvice tekst90d b1'><span class='tekst90 '>PREDAVANJE</span></th>";
            res += "<th scope='col' class='bezGornjeIvice tekst90d b2'><span class='tekst90'>RADIONICA</span></th>";
            res += "<th scope='col' class='bezGornjeIvice tekst90d b3'><span class='tekst90'>PREZENTACIJA</span></th>";
        }
        res += "</tr><tr><th scope='col' class='bezGornjeIvice'></th>";
        for (var j = 0; j < brd; j++) {
            pocd2.setDate(pocd1.getDate() + j);
            res += "<th scope='col' colspan='3' class='centar bezGornjeIvice brl'>" + dajDatum(pocd2) + "</th>\n";
        }
        res += "</tr>";
        for (var k = 0; k < brs; k++) {
            res += "<tr>\n" +
                    "<th scope='row' class=''>" + dajVreme(pocv2.setHours(pocv1.getHours() + k)) + "</th>\n";

            for (var j = 0; j < brd; j++) {
                res += "<td class='brl'>" +
                        "<div class='form-check centar b1'>" +
                        "<input class='form-check-input rb' type='radio' name='" + i + j + k + "' id='" + i + j + k + "1' value='pred'>" +
                        "</div>" +
                        "</td>\n" +
                        "<td>" +
                        "<div class='form-check centar b1'>" +
                        "<input class='form-check-input rb' type='radio' name='" + i + j + k + "' id='" + i + j + k + "2' value='radi'>" +
                        "</div>" +
                        "</td>\n" +
                        "<td>" +
                        "<div class='form-check centar b1'>" +
                        "<input class='form-check-input rb' type='radio' name='" + i + j + k + "' id='" + i + j + k + "3' value='prez'>" +
                        "</div>" +
                        "</td>\n";
            }
            res += "</tr>";

        }
        res += "</tbody>\n</table><br>\n";

    }
    res += "<input type='text' name='brSatiSajma' value='" + brs + "' hidden=''/><input type='text' name='brDanaSajma' value='" + brd + "' hidden=''/><input type='text' name='brSalaSajma' value='" + n + "' hidden=''/>";
    document.getElementById('satnica').innerHTML = res;

    pop2 = true;
}

function popuniKorak3() {

    var res = '';

    document.getElementById('paketiK3').innerHTML += res;

    document.getElementById('dugmeK3').innerHTML =
            "<button type='button' name='k3nak2' class='btn btn-primary org' style='float: left;' onclick='noviSajamRute(2)'>KORAK 2</button>" +
            "<button type='' name='k3sub' class='btn btn-primary org' style='float: right;' onclick='kn(3);'>KREIRAJ SAJAM</button>";
    pop3 = true;
}

function brDana(k = true) {
    if (k) {
        var d1 = new Date(document.getElementById('sajamDatumOD').value);
        var d2 = new Date(document.getElementById('sajamDatumDO').value);
    }
    var d = d2.getDay() - d1.getDay() + 1;
    if (d <= 0)
        d += 7;
    return d;
}

function brSati(k = true) {
    if (k) {
        var v1 = document.formaNoviSajam.sajamVremeOD.value;
        var v2 = document.formaNoviSajam.sajamVremeDO.value;
    }
    v1 = Date.parse('01/01/2000 ' + v1);
    v2 = Date.parse('01/01/2000 ' + v2);
    v1 = new Date(v1);
    v2 = new Date(v2);
    var v = new Date(v2 - v1);
    v = v.getHours() - 1;
    return v;
}

function brsda() {
    brd = d2.getDay() - d1.getDay() + 1;
    if (brd <= 0)
        brd += 7;

    var v11 = Date.parse('01/01/2000 ' + v1);
    var v21 = Date.parse('01/01/2000 ' + v2);
    v11 = new Date(v11);
    v21 = new Date(v21);
    var v = new Date(v21 - v11);
    brs = v.getHours() - 1;
    bra = idSala.length;
}

function dajDatum(date1) {
    var date = new Date(date1);
    return date.getDate() + '/' + date.getMonth() + 1 + '/' + date.getFullYear();
}

function dajVreme(vre1) {
    var v = new Date(vre1);
    var v1 = new Date(v);
    v1.setHours(v1.getHours() + 1);
    return v.getHours() + 'h - ' + v1.getHours() + 'h';
}

function proveraNoviSajamK2() {
    if (!proveraNoviSajamK1())
        return false;
    return true;
    var n = '';
    var g = '';
    var t;
    var o;
    pred = 0;
    prez = 0;
    radi = 0;
    for (var l = 0; l < sale.length; l++) {
        for (var i = 0; i < brd; i++) {
            for (var j = 0; j < brs; j++) {
                t = false;
                n = '' + l + i + j;
                if (document.getElementById(n + '1').checked) {
                    t = true;
                    pred++;
                }
                if (document.getElementById(n + '2').checked) {
                    t = true;
                    radi++;
                }
                if (document.getElementById(n + '3').checked) {
                    t = true;
                    prez++;
                }
                if (!t) {
                    g += 'Niste popunili ' + (j + 1) + '. termin ' + (i + 1) + '. dana za salu ' + sale[l] + '<br>';
                }
            }
        }
    }
    //alert('Pred:' + pred + ' Prez:' + prez + ' Razi:' + radi);


    if (g != '') {
        prikaziGresku(g);
        return false;
    }
    return true;
}

function proveraNoviSajamK3() {
    if (!proveraNoviSajamK2())
        return false;


    return true;
}

function dodStav(brPak) {
    var stariID = 'p' + brPak + '_' + (brStav[brPak]) + '_div';
    var kopija = document.getElementById('p1_1').innerHTML;
    brStav[brPak]++;
    var id = 'p' + brPak + '_' + (brStav[brPak]);
    var ne = "<div id='" + id + "_div' class='dolePad'>" +
            "<label for='" + id + "'>Stavke</label>" +
            "<select class='form-control' name='" + id + "' id='" + id + "' required=''>" +
            kopija +
            "</select>" +
            "</div>";
    $(ne).insertAfter('#' + stariID);
}

function oduStav(brPak) {
    if (brStav[brPak] === 1)
        return;
    stariID = 'p' + brPak + '_' + (brStav[brPak]) + '_div';
    $("#" + stariID).remove();
    brStav[brPak]--;
}

function dodPak() {
    var stariID = 'p' + brPak;
    brPak++;
    brStav[brPak] = 1;
    var noviID = 'p' + brPak;

    var res = "<div id='" + noviID + "' >" +
            "<br><hr style='border-top: 1px solid #ccc;'>" +
            "<div id='pn" + brPak + "div' class='dolePad'>" +
            "<label for='pn" + brPak + "'>Naziv</label>" +
            "<input type='text' class='form-control' name='pn" + brPak + "' id='pn" + brPak + "' placeholder='Naziv paketa' required=''>" +
            "</div>" +
            "<div id='pn" + brPak + "div' class='dolePad'>" +
            "<div class='form-check'>" +
            "<input class='form-check-input' type='radio' name='tipP" + brPak + "' id='osn" + brPak + "' value='0' checked onchange='osnDod(" + brPak + ")'>" +
            "<label class='form-check-label' for='osn" + brPak + "'>" +
            "Osnovni" +
            " </label>" +
            " </div>" +
            "<div class='form-check'>" +
            "<input class='form-check-input' type='radio' name='tipP" + brPak + "' id='dod" + brPak + "' value='1' onchange='osnDod(" + brPak + ")'>" +
            "<label class='form-check-label' for='dod" + brPak + "'>" +
            "Dodatni" +
            " </label>" +
            " </div>" +
            "</div>" +
            "<div id='samoOsn" + brPak + "'>" +
            "<div class='dolePad' id='p" + brPak + "_ddiv'>" +
            "<div id='p" + brPak + "_1_div' class='dolePad'>" +
            "<label for='p" + brPak + "_1'>Stavke</label>" +
            "<select class='form-control' name='p" + brPak + "_1' id='p" + brPak + "_1'>" +
            document.getElementById('p1_1').innerHTML +
            "</select>" +
            "</div>" +
            "<button type='button' name='' class='btn btn-primary org'onclick='dodStav(" + brPak + ")'>DODAJ</button>" +
            "<button type='button' name='' class='btn btn-primary org'  onclick='oduStav(" + brPak + ");'>ODUZMI</button>" +
            "</div>" +
            "<div id='pnpdiv' class='dolePad'>" +
            " <label for='pp" + brPak + "'>Broj predavanja</label>" +
            "<input type='number' class='form-control' name='pp" + brPak + "' id='pp" + brPak + "' placeholder='' min='0'>" +
            "</div>" +
            "<div id='pnpdiv' class='dolePad'>" +
            " <label for='pr" + brPak + "'>Broj Radionica</label>" +
            "<input type='number' class='form-control' name='pr" + brPak + "' id='pr" + brPak + "' placeholder='' min='0'>" +
            "</div>" +
            "<div id='pnpdiv' class='dolePad'>" +
            " <label for='pv" + brPak + "'>Trajanje video prezentacije</label>" +
            "<input type='number' class='form-control' name='pv" + brPak + "' id='pv" + brPak + "' placeholder='' min='0'>" +
            "</div>" +
            "<div id='pnkdiv' class='dolePad'>" +
            " <label for='pk" + brPak + "'>Maksimalni broj kompanija (0 za neograničeno)</label>" +
            "<input type='number' class='form-control' name='pk" + brPak + "' id='pk" + brPak + "' placeholder='' min='0'>" +
            "</div>" +
            "</div>" +
            "<div id='pn" + brPak + "div' class='dolePad'>" +
            "<label for='pc" + brPak + "'>Cena paketa</label>" +
            "<input type='number' class='form-control' name='pc" + brPak + "' id='pc" + brPak + "' placeholder='Cena' min='0' required=''>" +
            " </div>" +
            "</div>";


    $(res).insertAfter('#' + stariID);
}

function osnDod(brPak) {
    var o;
    o = document.getElementById('dod' + brPak).checked;
    if (o) {
        $("#samoOsn" + brPak).hide();
    } else
        $("#samoOsn" + brPak).show();
}

function oduPak(){
    if(brPak>1){$('#p'+brPak).remove();
    brPak--;}
}

function priSaj(r, idp) {
    var por = document.getElementById('porSaj' + idp).value;
    if (por == "") {
        prikaziGresku('Niste uneli poruku!');
        return;
    }
    if (r)
        window.location.href = 'adminPregled.php?odg=1&idp=' + idp + '&por=' + por;
    if (!r)
        window.location.href = 'adminPregled.php?odg=-1&idp=' + idp + '&por=' + por;
}

function satSaj(idp) {
    window.location.href = 'adminSatnica.php?idp=' + idp;
}

function popuniSatnicu() {
    brsda();
    if (_brPredP == 0)
        document.getElementById('dugmePred').disabled = true;
    if (_brPrezP == 0)
        document.getElementById('dugmePrez').disabled = true;
    if (_brRadiP == 0)
        document.getElementById('dugmeRadi').disabled = true;
    popuniInicijalno(0, "predavanja", _brPredP);
    popuniInicijalno(1, "radionice", _brRadiP);
    popuniInicijalno(2, "prezentacije", _brPrezP);
    if (_brPredP > 0)
        satn(0);
    else if (_brRadiP)
        satn(1);
    else
        satn(2);
}

function popuniInicijalno(t, src, f) {
    if (f > 0) {
        brTab[t] = 0;
        var pocd = d1;
        var idc;
        var pocv1 = new Date(Date.parse('01/01/2000 ' + v1));
        var pocv2 = new Date(Date.parse('01/01/2000 ' + v1));
        var h = 0;
        var res = "<div id='s" + t + "' class='satKlas'>";
        res += "<h2 align='center' class=' odaljiGore'>Raspored " + src + "</h2>";
        res += "<h5 align='center' class='dolePad '>Preostalo termina: <span id='spans" + t + "'></span></h5>";
        for (var kk = 0; kk < bra; kk++) {
            var pocd1 = new Date(pocd);
            var pocd2 = new Date(pocd);
            res += "<h4 align='center'>" + nazSala[kk] + "</h4>\n" +
                    "<table class='table belaslova table-dark table-striped'>\n" +
                    "<thead class='bezGornjeIvice'>\n" +
                    "<tr><th scope='col' class='bezGornjeIvice'></th>\n";

            for (var j = 0; j < brd; j++) {
                pocd2.setDate(pocd1.getDate() + j);
                res += "<th scope='col' class='centar bezGornjeIvice brl'>" + dajDatum(pocd2) + "</th>\n";
            }
            res += "</tr></thead><tbody>\n";
            for (var k = 0; k < brs; k++) {
                res += "<tr>\n" +
                        "<th scope='row' class=''>" + dajVreme(pocv2.setHours(pocv1.getHours() + k)) + "</th>\n";

                for (var j = 0; j < brd; j++) {
                    idc = 'rezervacije[' + t + kk + j + k + ']';
                    var idcf = '"' + idc + '"';
                    var xc = "";
                    var koDrzi = "";
                    if (kzauz[h] == 1 && zauz[h] == t)
                    {
                        xc = "checked";
                        brTab[t]++;
                    }
                    if (kzauz[h] != 0 && kzauz[h] != 1)
                    {
                        xc = "disabled";
                        koDrzi = kzauz[h];
                    }
                    res += "<td class='brl'>" +
                            "<div class='form-check centar b1'>";
                    if (zauz[h] == t)
                        res += "<input class='form-check-input rb' type='checkbox' title='" + koDrzi + "' name='" + idc + "' id='" + idc + "' value='1'" + xc + " onchange='tabela(" + t + "," + idcf + ");'>";
                    res += "</div>" +
                            "</td>\n";
                    h++;
                }
                res += "</tr>";

            }
            res += "</tbody>\n</table><br>\n";

        }
        res += "</div>";

        document.getElementById('zaSatnicu').innerHTML += res;
        document.getElementById('spans' + t).innerHTML = (f - brTab[t]);
    }

}

function satn(tip) {
    if (tip == 0 && _brPredP > 0) {
        $("#s0").show();
        $("#s1").hide();
        $("#s2").hide();
    }
    if (tip == 1 && _brRadiP > 0) {
        $("#s0").hide();
        $("#s1").show();
        $("#s2").hide();
    }
    if (tip == 2 && _brPrezP > 0) {
        $("#s0").hide();
        $("#s1").hide();
        $("#s2").show();
    }
}

function tabela(t, id) {
    var brMax = new Array();
    brMax[0] = _brPredP;
    brMax[1] = _brRadiP;
    brMax[2] = _brPrezP;

    var dugme = document.getElementById(id).checked;
    if (dugme) {
        if (brTab[t] == brMax[t]) {
            document.getElementById(id).checked = false;
        } else
            brTab[t]++;
    } else {
        brTab[t]--;
    }
    document.getElementById('spans' + t).innerHTML = (brMax[t] - brTab[t]);
}

function proveraSatnice() {
    var brMax = new Array();
    brMax[0] = _brPredP;
    brMax[1] = _brRadiP;
    brMax[2] = _brPrezP;
    var id = '';
    var e;
    var br = 0;
    var kk = 0;
    var mod=bra*brs*brd;
    for (var t = 0; t < 3; t++) {
        br = 0;
        for (var h = 0; h < bra; h++) {
            for (var i = 0; i < brs; i++) {
                for (var j = 0; j < brd; j++) {
                    id = 'rezervacije[' + t + h + j + i + ']';
                    if (e = document.getElementById(id)) {
                        if (e.checked == true) {
                            br++;
                            if (kzauz[kk % mod] != 0 && kzauz[kk % mod] != 1) {
                                alert('Previse kontrole, previse!!!');
                                location.reload();
                                return false;
                            }
                        }

                    }
                    kk++;
                }
            }
        }
        if (br > brMax[t]) {
            alert('Previse kontrole, previse!');
            location.reload();
            return false;
        }
    }
    return true;
}