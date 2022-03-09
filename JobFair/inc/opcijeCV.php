<?php
if (isset($_GET['p'])) {
    require_once 'database.php';
    $p=$dbConn->real_escape_string($_GET['p']);
    switch ($p){
        case "ri": $q="SELECT * FROM b_posao"; break;
        case "rr": $q="SELECT * FROM b_racunar"; break;
        case "ve": $q="SELECT * FROM b_vestine"; break;
        case "do": $q="SELECT * FROM b_dozvole"; break;
    }
    $queryRes = $dbConn->query($q);
    $rez="";
    if($queryRes->num_rows>0){
        while($n=$queryRes->fetch_array())
        $rez=$rez."<option value='".$n[0]."'>".$n[1]."</option>\n";
    }
    echo $rez;  
}
?>

