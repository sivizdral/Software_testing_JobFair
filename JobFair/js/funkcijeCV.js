var brObr = 1;
var brJez = 1;
var brRi = 1;
var brRr = 1;
var brVe = 1;
var brDo = 1;

function dodObr() {
    var stariElid = "obr" + brObr;
    brObr = brObr + 1;
    var noviElid = "obr" + brObr;
    var id1 = "tipObr" + brObr;
    var id2 = "nazObr" + brObr;
    kopija = document.getElementById('tipObr1').innerHTML;
    var noviEl = "<div id='" + noviElid + "' class='dolePad form-row'>\n" +
            "<div class='col' class=''>" +
            "<label for'" + id1 + "' >Edukacija</label>" +
            "<select class='form-control' name='" + id1 + "' id='" + id1 + "' required=''>\n" +
            kopija +
            "</select>\n" +
            " </div>\n" +
            "</div>";
    $(noviEl).insertAfter('#' + stariElid);
}

function dodJez() {
    var stariElid = "jez" + brJez;
    brJez = brJez + 1;
    var noviElid = "jez" + brJez;
    var id1 = "jezCV" + brJez;
    var id2 = "jezMatCV" + brJez;
    kopija = document.getElementById('jezCV1').innerHTML;
    var noviEl =
            "<div id='" + noviElid + "' class='form-row dolePad'>\n" +
            "<div class='col'>\n" +
            "<label for='" + id1 + "'>Jezik</label>\n" +
            "<select class='form-control' name='" + id1 + "' id='" + id1 + "' required=''>\n" +
            kopija +
            "</select>\n" +
            "</div>\n" +
            "<div class='col' >\n" +
            "<label for='" + id2 + "'>Maternji</label>\n" +
            "<select class='form-control' name='" + id2 + "' id='" + id2 + "' required=''>\n" +
            "<option value='0'>NE</option>\n" +
            "<option value='1'>DA</option>\n" +
            "</select>\n" +
            "</div>\n" +
            "</div>\n";
    $(noviEl).insertAfter('#' + stariElid);
}

function dodPolje(koje) {
    var br;
    switch (koje) {
        case 3:
            br = brRi;
            tip = "ri";
            brRi = brRi + 1;
            tekst = "Radno iskustvo";
            break;    //Radno iskustvo       
        case 4:
            br = brRr;
            tip = "rr";
            brRr = brRr + 1;
            tekst = "Rad na računaru";
            break;    //Rad na racunaru   
        case 5:
            br = brVe;
            tip = "ve";
            brVe = brVe + 1;
            tekst = "Veštine";
            break;    //Vestine   
        case 6:
            br = brDo;
            tip = "do";
            brDo = brDo + 1;
            tekst = "Dozvole";
            break;    //Dozvole   
        default:
            br = 0;
    }

    if (koje >= 3 && koje <= 6) {
        var stariElid = tip + br;
        br = br + 1;
        var noviElid = tip + br;
        var id1 = tip + "CV" + br;
        kopija = document.getElementById(tip + "CV1").innerHTML;
        var noviEl =
                "            <div id='" + noviElid + "' class='dolePad'>" +
                "                    <label for='" + id1 + "'>" + tekst + "</label>" +
                "                    <select class='form-control' name='" + id1 + "' id='" + id1 + "' required=''>\n" +
                kopija +
                "                           </select>\n" +
                "            </div>";
        $(noviEl).insertAfter('#' + stariElid);
    }
}

function obrPosl(koji) {
    var br;
    switch (koji) {
        case 1:
            br = brObr;
            tip = "obr";
            if (br > 1)
                brObr = brObr - 1;
            break;    //Obrazovanje
        case 2:
            br = brJez;
            tip = "jez";
            if (br > 1)
                brJez = brJez - 1;
            break;    //Jezik
        case 3:
            br = brRi;
            tip = "ri";
            if (br > 1)
                brRi = brRi - 1;
            break;    //Radno iskustvo       
        case 4:
            br = brRr;
            tip = "rr";
            if (br > 1)
                brRr = brRr - 1;
            break;    //Rad na racunaru   
        case 5:
            br = brVe;
            tip = "ve";
            if (br > 1)
                brVe = brVe - 1;
            break;    //Vestine   
        case 6:
            br = brDo;
            tip = "do";
            if (br > 1)
                brDo = brDo - 1;
            break;    //Dozvole  
        case 7:
            br = brSal;
            tip = "sale";
            if (br > 1)
                brSal = brSal - 1;
            break;    //Sale    
            
        default:
            br = 0;
    }

    if (br > 1) {
        var poslednji = tip + br;
        $("#" + poslednji).remove();
    }
}

