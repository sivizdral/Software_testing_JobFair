function forme(x, y = false) {
    if (!y)
        $("#greskaPoruka").hide();
    if (x == 1) {
        $("#wrapKompanijaForm").hide();
        $("#wrapStudentForm").hide();
        $("#wrapLoginForm").show();
        $("#wrapPassForm").hide();
    } else if (x == 2) {
        $("#wrapKompanijaForm").hide();
        $("#wrapStudentForm").show();
        $("#wrapLoginForm").hide();
        $("#wrapPassForm").hide();
    } else if (x == 3) {
        $("#wrapKompanijaForm").show();
        $("#wrapStudentForm").hide();
        $("#wrapLoginForm").hide();
        $("#wrapPassForm").hide();
    } else if (x == 4) {
        $("#wrapKompanijaForm").hide();
        $("#wrapStudentForm").hide();
        $("#wrapLoginForm").hide();
        $("#wrapPassForm").show();

}
}

function upisiOcenu(idk) {
    var oc = document.getElementById('ocena' + idk).value;
    g = false;
    if (!isNaN(oc)) {
        if (oc > 0 && oc < 11)
            window.location.href = 'studentRez.php?idk=' + idk + '&ocena=' + oc;
        else
            g = true;
    } else
        g = true;
    if (g)
        alert('Niste ispravno uneli ocenu');
}

function proveraLoginPodataka() {
    var poruka = '';
    var isp = true;
    if (document.loginForma.loginUsername.value == '')
    {
        poruka += 'Korisničko ime ne sme biti prazno!<br/>';
        isp = false;
    }
    if (document.loginForma.loginPassword.value == '')
    {
        poruka += 'Korisničko ime ne sme biti prazno!<br/>';
        isp = false;
    }
    if (isp) {
        return true;
    } else {
        prikaziGresku(poruka);
        return false;
    }

}

function proveraPassPodataka() {
    var poruka = '';
    var isp = true;
    if (document.passForma.passUsername.value == '')
    {
        poruka += 'Korisničko ime ne sme biti prazno!<br/>';
        isp = false;
    }
    if (document.passForma.passOldPass.value == '')
    {
        poruka += 'Stara lozinka ne sme biti prazna!<br/>';
        isp = false;
    }
    if (document.passForma.passNewPass.value == '')
    {
        poruka += 'Nova lozinka ne sme biti prazna!<br/>';
        isp = false;
    }
    if (document.passForma.passNewPass.value == '' && document.passForma.passNewPass2.value != '')
    {
        poruka += 'Morate ponoviti novu lozinku!<br/>';
        isp = false;
    }
    if (document.passForma.passNewPass.value != document.passForma.passNewPass2.value)
    {
        poruka += 'Lozinke se moraju slagati!<br/>';
        isp = false;
    }

    if (isp) {
        if (!proveraIspravnostiSifre(document.passForma.passNewPass.value)) {
            poruka += 'Lozinka ne zadovoljava kriterijume tezine!<br/>';
            isp = false;
        }
    }
alert(1);
    if (isp) {
        return true;
    } else {
        prikaziGresku(poruka);
        return false;
    }
}

function proveraStudenta() {
    var poruka = '';
    var isp = true;
    if (document.regStudentForma.regStudentUsername.value == '')
    {
        poruka += 'Korisničko ime ne sme biti prazno!<br/>';
        isp = false;
    }
    if (document.regStudentForma.regStudentPassword.value == '')
    {
        poruka += 'Lozinka ne sme biti prazna!<br/>';
        isp = false;
    }
    if (document.regStudentForma.regStudentPassword.value != document.regStudentForma.regStudentPassword2.value)
    {
        poruka += 'Lozinke se moraju slagati!<br/>';
        isp = false;
    }
    if (!proveraUsername(document.regStudentForma.regStudentUsername.value))
    {
        poruka += 'Korisničko ime nije ispravno uneto!<br/>';
        isp = false;
    }


    if (isp) {
        if (!proveraIspravnostiSifre(document.regStudentForma.regStudentPassword.value)) {
            poruka += 'Lozinka ne zadovoljava kriterijume tezine!<br/>';
            isp = false;
        }
    }
    if (!proveraIme(document.regStudentForma.regStudentIme.value))
    {
        poruka += 'Ime nije ispravno uneto!<br/>';
        isp = false;
    }
    if (!proveraIme(document.regStudentForma.regStudentPrezime.value))
    {
        poruka += 'Prezime nije ispravno uneto!<br/>';
        isp = false;
    }
    if (!proveraMail(document.regStudentForma.regStudentMail.value))
    {
        poruka += 'eMail nije ispravno unet!<br/>';
        isp = false;
    }
    if (!proveraTel(document.regStudentForma.regStudentTel.value))
    {
        poruka += 'Telefon nije ispravno unet!<br/>';
        isp = false;
    }
    if (!slikaProveraExt(document.regStudentForma.slikaStudent.value))
    {
        poruka += 'Slika nije u ispravnom formatu!<br/>';
        isp = false;
    }
    if (isp)
        if (!proveraSlike('slikaStudent'))
    {
        poruka += 'Slika nije ispravnih dimenzija!<br/>Sirina: '+sirinaSlike+' (min:100px max: 300px) <br/>Visina: '+visinaSlike+' (min:100px max: 300px) <br/>';
        isp = false;
    }



    if (isp) {
        return true;
    } else {
        prikaziGresku(poruka);
        return false;
    }

}

function proveraKompanije() {

    var poruka = '';
    var isp = true;
    if (document.regKompanijaForma.regKompanijaUsername.value == '')
    {
        poruka += 'Korisničko ime ne sme biti prazno!<br/>';
        isp = false;
    }
    if (document.regKompanijaForma.regKompanijaPassword.value == '')
    {
        poruka += 'Lozinka ne sme biti prazna!<br/>';
        isp = false;
    }
    if (document.regKompanijaForma.regKompanijaPassword.value != document.regKompanijaForma.regKompanijaPassword2.value)
    {
        poruka += 'Lozinke se moraju slagati!<br/>';
        isp = false;
    }
    if (isp) {
        if (!proveraUsername(document.regKompanijaForma.regKompanijaUsername.value))
        {
            poruka += 'Korisničko ime nije ispravno uneto!<br/>';
            isp = false;
        }
    }

    if (isp) {
        if (!proveraIspravnostiSifre(document.regKompanijaForma.regKompanijaPassword.value)) {
            poruka += 'Lozinka ne zadovoljava kriterijume tezine!<br/>';
            isp = false;
        }
    }

    if (!proveraIme(document.regKompanijaForma.regKompanijaNaziv.value))
    {
        poruka += 'Naziv nije ispravno unet!<br/>';
        isp = false;
    }
    if (!proveraIme(document.regKompanijaForma.regKompanijaGrad.value))
    {
        poruka += 'Grad nije ispravno unet!<br/>';
        isp = false;
    }

    if (!proveraAdresa(document.regKompanijaForma.regKompanijaAdresa.value))
    {
        poruka += 'Adresa nije ispravno uneta!<br/>';
        isp = false;
    }
    if (!proveraIme(document.regKompanijaForma.regKompanijaDirektor.value))
    {
        poruka += 'Direktor nije ispravno unet!<br/>';
        isp = false;
    }

    if (!proveraPIB(document.regKompanijaForma.regKompanijaPib.value))
    {
        poruka += 'PIB nije ispravno unet!<br/>';
        isp = false;
    }
    if (!proveraWWW(document.regKompanijaForma.regKompanijaWww.value))
    {
        poruka += 'WEB stranica nije ispravno uneta!<br/>';
        isp = false;
    }

    if (!slikaProveraExt(document.regKompanijaForma.logoKompanija.value))
    {
        poruka += 'Slika nije u ispravnom formatu!<br/>';
        isp = false;
    }

    if (isNaN(document.regKompanijaForma.regKompanijaBrZap.value))
    {
        poruka += 'Broj zaposlenih nije u ispravnom formatu!<br/>';
        isp = false;
    }

    if (isp) {
        return true;
    } else {
        prikaziGresku(poruka);
        return false;
    }

}

function prikaziGresku(poruka1) {

    $("#porukaSadrzaj").html(poruka1);
    $("#poruka").removeClass("alert-success");
    $("#poruka").addClass("alert-danger");
    $("#greskaPoruka").show();
    document.getElementById('glavniKont').scrollIntoView();
}

function prikaziUspeh(poruka1) {
    $("#porukaSadrzaj").html(poruka1);
    $("#poruka").removeClass("alert-danger");
    $("#poruka").addClass("alert-success");
    $("#greskaPoruka").show();
    document.getElementById('glavniKont').scrollIntoView();
}

function proveraUsername(un) {
    r = /^[a-z]{1}([-\_.A-Za-z0-9]){1,}$/;
    if (!r.test(un))
        return false;
    return true;
}

function proveraIspravnostiSifre(sifra) {
    sr = /^([A-Z]|[a-z]){1}([#*.!?$]|[A-Z]|[a-z]|[0-9]){7,11}$/;
    if (!sr.test(sifra))
        return false;
    sr = /\d/g;
    if (!sr.test(sifra))
        return false;
    sr = /[A-Z]/g;
    if (!sr.test(sifra))
        return false;
    sr = /[#*.!?$]/g;
    if (!sr.test(sifra))
        return false;
    sr = /[a-z]/g;
    if (sifra.match(sr).length < 3)
        return false;
    for (i = 0; i < sifra.length - 1; i++) {
        if (sifra[i] == sifra[i + 1])
            return false;
    }
    return true;
}

function proveraIme(ime) {
    r = /^[A-Z][a-z]+(\s[A-Z][a-z]+)?$/;
    if (!r.test(ime))
        return false;
    return true;
}

function proveraMail(mail) {
    r = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (!(r.test(String(mail).toLowerCase())))
        return false;
    return true;
}

function proveraTel(tel) {
    r = /^[+]381[0-9]{8,9}$/;
    if (!r.test(tel))
        return false;
    return true;
}

function slikaProveraExt(slika) {
    if (slika == '')
        return false;
    var x = slika.split('.');
    var ext = x[x.length - 1];
    switch (ext.toLowerCase()) {
        case 'jpg':
        case 'png':
            break;
        default:
            return false;
    }
    return true;
}

function prikaziDiplomu() {
    if (document.getElementById('regStudentGodStudija').value == 4) {
        document.getElementById("wrapDiploma").style.display = "block";
    } else {
        document.getElementById("wrapDiploma").style.display = "none";
        document.getElementById("regStudentDiplomaNe").checked = true;
        document.getElementById("regStudentDiplomaDa").checked = false;
    }
}

function skloniPoruku() {
    $("#greskaPoruka").hide();
}

function proveraAdresa(adr) {
    r = /^[A-z][a-z]+(\s[A-z]?[a-z]+)*(\s(((\d*)|(\d+[a-z]))(\/\d*)?)|\s(bb|BB))$/;
    if (!r.test(adr))
        return false;
    return true;
}

function proveraPIB(pib) {
    r = /^[1-9]([0-9]){8}$/;
    if (!r.test(pib))
        return false;
    return true;
}

function proveraWWW(url) {
    r = /^(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9]\.[^\s]{2,})$/;
    if (!r.test(url))
        return false;
    return true;
}

function dohvatiSelekciju(select) {
    var ret = [];
    if (select.selectedOptions != undefined) {
        for (var i = 0; i < select.selectedOptions.length; i++) {
            ret.push(select.selectedOptions[i].value);
        }

    } else {
        for (var i = 0; i < select.options.length; i++) {
            if (select.options[i].selected) {
                ret.push(select.options[i].value);
            }
        }
    }
    return ret;
}

function prikaziRezultatPretrageKompanija() {

    var komp = document.formaPretragaKomp.preNazivKompanije.value;
    var grad = document.formaPretragaKomp.preGradKompanije.value;
    var dela = dohvatiSelekciju(document.formaPretragaKomp.preDelatnostKompanije);
    var xx = komp == '' && grad == '' && dela.length == 0;
    if (xx) {
        document.getElementById("teloPretragaKomp").innerHTML = "<tr>\n<td colspan='3' align='center'>Unesite parametre pretrage</td>\n</tr>";
        return false;
    } else {
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("teloPretragaKomp").innerHTML = "<tr>\n<td colspan='3' align='center'>Ne postoji takva firma</td>\n</tr>";
                if (this.responseText != "")
                    document.getElementById("teloPretragaKomp").innerHTML = this.responseText;
            }
        }
        xmlhttp.open("GET", "inc/pozadinskaPretragaKompanija.php?k=" + komp + "&g=" + grad + "&d=" + dela, true);
        xmlhttp.send();
    }

}


function prikaziRezultatPretrageKP() {

    var komp = document.formaPretragaKP.preNazivK.value;
    var npp = document.formaPretragaKP.preNazivPP.value;
    var pra = document.formaPretragaKP.prePr.checked;
    var pos = document.formaPretragaKP.prePo.checked;
    var tra = -1;
    var xx = komp == '' && npp == '' && !pra && !pos;
    tra = 3;

    if (pra)
        tra = 0;
    if (pos)
        tra = 1;
    if (pra && pos)
        tra = 2;

    var xmlhttp = new XMLHttpRequest();

    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {

            if (this.responseText != "") {

                document.getElementById("teloPretragaKP").innerHTML = this.responseText;
            } else
                document.getElementById("teloPretragaKP").innerHTML = "<tr>\n<td colspan='4' align='center'>Ne postoji rezultat</td>\n</tr>";
        }


    }

    xmlhttp.open("GET", "inc/pozadinskaPretragaKP.php?k=" + komp + "&n=" + npp + "&t=" + tra, true);
    xmlhttp.send();



}

function prijaviStudentaNaKonkurs(idk) {
    var clta = document.getElementById('CLta').value;
    var link = 'studentKonkurs.php?idk=' + idk + '&pri=1&cl=' + clta;
    window.location.href = link;
}

var _URL = window.URL || window.webkitURL;
var sirinaSlike=0;
var visinaSlike=0;

function proveraSlike(id) {   
            if(sirinaSlike>100 && sirinaSlike<300 && visinaSlike>100 && visinaSlike<300)return true;
            return false;
}

function slikeTest(e) {
    var image, file;

    if ((file = e.files[0])) {
       
        image = new Image();
        
        image.onload = function() {
            sirinaSlike=this.width;
            visinaSlike=this.height;
        };
    
        image.src = _URL.createObjectURL(file);


    }else(alert(1));

}