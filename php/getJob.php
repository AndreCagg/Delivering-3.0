<?php
    print_r($_GET);
    echo "<br>";
    $tabella="";
    $fk="";
    $mainQuery="";

    //estrazione tabella
    if(isset($_GET["id"]))
        $tabella="incarichi";
    
    if(isset($_GET["idCollo"])){
        $fk="incarico";
        $tabella="colli";
    }

    if(isset($_GET["rifddtN"]))
        $tabella="incarichi";

    if(isset($_GET["clienti"])){
        $tabella="clienti";
    }

    //creazione della stringa del form interessato
    $link=doLink($_GET,$tabella,"");
    


    if($tabella!="incarichi" && $tabella!="clienti"){
        $mainQuery="SELECT * FROM colli WHERE $link; /*colli*/
        SELECT * FROM incarichi WHERE id_inc=(SELECT incarico FROM colli WHERE $link); /*incarico*/
        SELECT * FROM clienti WHERE clienti.id IN ((SELECT mitt FROM incarichi WHERE id_inc=(SELECT incarico FROM colli WHERE $link)) UNION (SELECT dest FROM incarichi WHERE id_inc=(SELECT incarico FROM colli WHERE $link)));"; /*clienti*/
    }elseif($tabella=="clienti"){
        if($_GET["clienti"]!=""){
            //non prendo il cliente perche gia mi viene passato
            $mainQuery="SELECT * 
            FROM incarichi 
            WHERE mitt=".$_GET["clienti"]." OR dest=".$_GET["clienti"].";

            SELECT * 
            FROM colli 
            WHERE incarico IN (SELECT id_inc FROM incarichi WHERE mitt=".$_GET["clienti"]." OR dest=".$_GET["clienti"].");";
        }else{
            unset($_GET["clienti"]);
            $link=doLink($_GET,$tabella,"like");
        

            $subquery="SELECT id 
            FROM clienti 
            WHERE $link";

            $mainQuery="SELECT * 
            FROM incarichi 
            WHERE mitt IN ($subquery) OR dest IN ($subquery);

            SELECT * 
            FROM colli 
            WHERE incarico IN (SELECT id_inc FROM incarichi WHERE mitt IN ($subquery) OR dest IN ($subquery));";
        }
    }elseif($tabella=="incarichi" && isset($_GET["id"])){
        //query per ricerca da id
        $link=doLink($_GET,$tabella,"");

        $mainQuery="SELECT * 
        FROM incarichi 
        WHERE $link; /*incarico*/

        SELECT clienti.* 
        FROM clienti, incarichi 
        WHERE $link AND clienti.id IN (incarichi.mitt,incarichi.dest); /*clienti*/

        SELECT * 
        FROM colli 
        WHERE incarico=".$_GET["id"].";"; /*colli*/
    }elseif($tabella=="incarichi" && isset($_GET["rifddtN"])){
        $mainQuery="SELECT * 
        FROM incarichi 
        WHERE $link; /*incarico*/

        SELECT clienti.*
        FROM clienti,incarichi 
        WHERE $link AND clienti.id IN (incarichi.mitt,incarichi.dest); /*clienti*/

        SELECT * 
        FROM colli 
        WHERE incarico = (SELECT id_inc FROM incarichi WHERE $link);";
    }


    function doLink($arr,$tabella,$mode){
        $link="";
        $start="";
        $end="";

        if($mode=="like"){
            $start=" LIKE '%";
            $end="%'";
        }else{
            $start="='";
            $end="'";
        }

        foreach($arr as $k=>$v){
            $link.=$tabella.".".map($k).$start.$v.$end." AND ";
        }
        $link=substr($link,0,strlen($link)-5);
        return $link;
    }

    function map($str){
        switch($str){
            case "id":
                return "id_inc";
            case "idCollo":
                return "segnacollo";
            case "rifddtN":
                return "rifDDt";
            case "rifddtD":
                return "dataRif";
            case "clienti":
                return "id";
            case "Cell":
                return "cellulare";
            case "RagSoc":
                return "ragioneSociale";
            case "Indirizzo":
                return "indirizzo";
            case "citta":
                return "citta";
            case "Prov":
                return "prov";
            case "cap":
                return "cap";
        }
    }

    echo $mainQuery;
    echo "<br><br>";

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    // ce da capire come dividere il multi query
    try {
        $conn = new mysqli("localhost", "operator", "", "delivering");
    
        // Esegue la query multipla
        $query = $conn->multi_query($mainQuery);
    
        // Inizializza un array per gli incarichi
        $incarichi = [];
    
        // Recupera i risultati per ciascuna query
        do {
            if ($result = $conn->store_result()) {
                while ($row = $result->fetch_object()) {
                    $incarichi[] = $row;
                }
                $result->free(); // Liberare la memoria dal set di risultati
            }
        } while ($conn->more_results() && $conn->next_result());
    
        print_r($incarichi);
        // $query->close();
        $conn->close();
    } catch (Exception $e) {
        echo "-- " . $e->getMessage() . " (" . $e->getCode() . ")";
    }
    

?>