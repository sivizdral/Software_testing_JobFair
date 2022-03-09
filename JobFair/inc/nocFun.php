<?php

function dajKompaniju() {
    global $dbConn;
    if (isset($_GET['unk'])) {
        $un = $dbConn->real_escape_string($_GET['unk']);
        $query = "SELECT * FROM kompanija LEFT JOIN sifrarnik_delatnosti ON (kompanija.delatnost=sifrarnik_delatnosti.idDelatnosti) WHERE username='" . $un . "';";

        $queryRes = $dbConn->query($query);
        if ($niz = $queryRes->fetch_assoc()) {

            $naziv = $niz['naziv'];
            $adresa = $niz['adresa'];
            $grad = $niz['grad'];
            $direktor = $niz['direktor'];
            $pib = $niz['pib'];
            $brZaposlenih = $niz['brZaposlenih'];
            $web = $niz['webAdresa'];
            $delatnost = $niz['nazivDelatnosti'];
            $uzaSpec = $niz['uzaSpecijalnost'];
            $sp=$niz['slika'];
        }
        ?>
        
        <div class='wrap80' style='padding-top:20px;' id='infoK'>
            <div class="centar dolePad">
                <img  class='okruglaSlika' src="<?php echo $sp; ?>" style="max-width: 200px; max-height: 200px;" alt='<?php echo $naziv; ?>'/>
            </div>
            <table class="table belaslova odaljiGore">
                <thead class="bezGornjeIvice">
                    <tr>
                        <th scope="col" class="bezGornjeIvice">Naziv</th>
                        <th scope="col" class="bezGornjeIvice"><?php echo $naziv; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">Grad</th>
                        <td><?php echo $grad; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Adresa</th>
                        <td><?php echo $adresa; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Direktor</th>
                        <td><?php echo $direktor; ?></td>
                    </tr>
                    <tr>
                        <th scope="row">PIB</th>
                        <td><?php echo $pib; ?></td>
                    </tr>                
                    <tr>
                        <th scope="row">Broj zaposlenih</th>
                        <td><?php echo $brZaposlenih; ?></td>
                    </tr>                
                    <tr>
                        <th scope="row">WEB Adresa</th>
                        <td><?php echo "<a class='beliLinkovi' target='_blank'href='" . $web . "'>" . $web . "</a>"; ?></td>
                    </tr>                
                    <tr>
                        <th scope="row">Delatnost</th>
                        <td><?php echo $delatnost; ?></td>
                    </tr>                
                    <tr>
                        <th scope="row">Uža specijalnost</th>
                        <td><?php echo $uzaSpec; ?></td>
                    </tr>                 


                </tbody>
            </table>
            
        </div>
        <?php
    }
}
?>

