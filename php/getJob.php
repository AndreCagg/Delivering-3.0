<?php

    require_once("../conf.php");
    require_once("tool.php");

    session_start();
    isLogged("../",$_SESSION["login"]["level"],"0");

    // print_r($_GET);
    // echo "<br>";
    $tabella="";
    $mainQuery="";
    $criterio="";

    //estrazione tabella
    if(isset($_GET["id"])){
        $criterio="ID Incarico";
        $tabella="incarichi";
    }

    if(isset($_GET["idCollo"])){
        $criterio="ID Collo/Bancale";
        $tabella="colli";
    }

    if(isset($_GET["rifddtN"])){
        $criterio="Riferimenti";
        $tabella="incarichi";
    }

    if(isset($_GET["clienti"])){
        $criterio="Cliente";
        $tabella="clienti";
    }

    //creazione della stringa del form interessato
    $link=doLink($_GET,$tabella,"");


    /*NON USO LE VIEW VISTO CHE RICHIEDONO DROP E/O ALTER, NON USO LE TAB TEMP VISTO CHE AVREI CALO DI PRESTAZIONI*/
    if($tabella!="incarichi" && $tabella!="clienti"){
        $mainQuery="SELECT * FROM colli WHERE $link;
        SELECT * FROM incarichi WHERE id_inc=(SELECT incarico FROM colli WHERE $link) ORDER BY consegna DESC;
        SELECT * FROM clienti WHERE clienti.id IN ((SELECT mitt FROM incarichi WHERE id_inc=(SELECT incarico FROM colli WHERE $link)) UNION (SELECT dest FROM incarichi WHERE id_inc=(SELECT incarico FROM colli WHERE $link)));
        SELECT id_inc,data,stato FROM movimenti WHERE id_inc=(SELECT id_inc FROM incarichi WHERE id_inc=(SELECT incarico FROM colli WHERE $link))";
    }elseif($tabella=="clienti"){
        if($_GET["clienti"]!=""){
            $mainQuery="SELECT * 
            FROM incarichi 
            WHERE mitt=".$_GET["clienti"]." OR dest=".$_GET["clienti"]."
            ORDER BY consegna DESC; /*incarichi*/

            SELECT id_inc,data,stato
            FROM movimenti
            WHERE id_inc IN (SELECT id_inc 
            FROM incarichi 
            WHERE mitt=".$_GET["clienti"]." OR dest=".$_GET["clienti"]."
            ORDER BY consegna DESC);

            SELECT * 
            FROM clienti
            WHERE id IN (
                SELECT mitt 
                FROM incarichi 
                WHERE mitt=".$_GET["clienti"]." OR dest=".$_GET["clienti"]."
                UNION
                SELECT dest
                FROM incarichi 
                WHERE mitt=".$_GET["clienti"]." OR dest=".$_GET["clienti"]."
            );

            SELECT * 
            FROM colli 
            WHERE incarico IN (SELECT id_inc FROM incarichi WHERE mitt=".$_GET["clienti"]." OR dest=".$_GET["clienti"].");"; /*colli*/
        }else{
            unset($_GET["clienti"]);
            $link=doLink($_GET,$tabella,"like");
        

            // $subquery="SELECT id 
            // FROM clienti 
            // WHERE $link"; 

            $mainQuery="SELECT * 
            FROM incarichi 
            WHERE mitt IN (SELECT id 
            FROM clienti 
            WHERE $link) OR dest IN (SELECT id 
            FROM clienti 
            WHERE $link)
            ORDER BY consegna DESC; /*incarichi*/

            SELECT id_inc,data,stato
            FROM movimenti
            WHERE id_inc IN (SELECT id_inc 
            FROM incarichi 
            WHERE mitt IN (SELECT id 
            FROM clienti 
            WHERE $link) OR dest IN (SELECT id 
            FROM clienti 
            WHERE $link));

            SELECT * 
            FROM colli 
            WHERE incarico IN (SELECT id_inc FROM incarichi WHERE mitt IN (SELECT id 
            FROM clienti 
            WHERE $link) OR dest IN (SELECT id 
            FROM clienti 
            WHERE $link)); /*colli*/
            
            SELECT DISTINCT * FROM clienti WHERE id IN (
                SELECT id FROM clienti WHERE $link
                UNION
                SELECT dest FROM incarichi WHERE mitt IN (SELECT id FROM clienti WHERE $link)
                UNION
                SELECT mitt FROM incarichi WHERE dest IN (SELECT id FROM clienti WHERE $link)
            );"; /*clienti*/
        }
    }elseif($tabella=="incarichi" && isset($_GET["id"])){
        //query per ricerca da id
        $link=doLink($_GET,$tabella,"");

        $mainQuery="SELECT * 
        FROM incarichi 
        WHERE $link
        ORDER BY consegna DESC; /*incarico*/

        SELECT id_inc,data,stato
        FROM movimenti
        WHERE id_inc IN (SELECT id_inc 
        FROM incarichi 
        WHERE $link);

        SELECT clienti.* 
        FROM clienti, incarichi 
        WHERE $link AND clienti.id IN (incarichi.mitt,incarichi.dest); /*clienti*/

        SELECT * 
        FROM colli 
        WHERE incarico=".$_GET["id"].";"; /*colli*/
    }elseif($tabella=="incarichi" && isset($_GET["rifddtN"])){
        $mainQuery="SELECT * 
        FROM incarichi 
        WHERE $link
        ORDER BY consegna DESC; /*incarico*/

        SELECT id_inc,data,stato
        FROM movimenti
        WHERE id_inc IN (SELECT id_inc 
        FROM incarichi 
        WHERE $link);

        SELECT clienti.*
        FROM clienti,incarichi 
        WHERE $link AND clienti.id IN (incarichi.mitt,incarichi.dest); /*clienti*/

        SELECT * 
        FROM colli 
        WHERE incarico = (SELECT id_inc FROM incarichi WHERE $link);"; /*colli*/
    }

    // echo $mainQuery;
    // echo "<br><br>";

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    // ce da capire come dividere il multi query

    $conn=null;
    try {
        $conn = new mysqli($dbAddress, $userOperator, $passOperator, $dbName);

        // Esegue la query multipla
        $conn->multi_query($mainQuery);
        // Inizializza un array per gli incarichi
        $risultati = [];
        $incarichi=[];
        $colli=[];
        $clienti=[];
        $movimenti=[];
        $b=false;
        
        // Recupera i risultati per ciascuna query
        do {
            if ($result = $conn->store_result()) {
                while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                    // $incarichi["incarichi"][]["id_inc"] = $row;
                    // print_r($row);
                    // echo "<br>-<br>";
                    $risultati=$row;
                    // echo "RISULTATI: ";
                    // print_r($risultati);

                    if(count($risultati)>0){
                        if(isset($risultati["segnacollo"])){
                            $colli[$risultati["segnacollo"]]=$risultati;
                        }

                        if(isset($risultati["id_inc"]) && !isset($risultati["stato"])){
                            $incarichi[$risultati["id_inc"]]=$risultati;
                        }

                        if(isset($risultati["ragioneSociale"])){
                            $clienti[$risultati["id"]]=$risultati;
                        }

                        if(isset($risultati["stato"])){
                            $movimenti[]=$risultati;
                        }
                    }
                }
                $result->free(); // Liberare la memoria dal set di risultati
            }

        } while ($conn->more_results() && $conn->next_result());
        
        //DA FARE COMPRESSIONE TESTO DEL RESULTSET
        $missionMatch=0;
        $missionString="";
        if(count($incarichi)>0){
            $resultset=[];
            foreach($incarichi as $k=>$v){
                $missionString.=$k.", ";
                if(!isset($incarichi[$k]["rifDDt"]))
                    $incarichi[$k]["rifDDt"]="";

                if(!isset($incarichi[$k]["dataRif"]))
                    $incarichi[$k]["dataRif"]="";
                $resultset[$k]=$incarichi[$k];
                $resultset[$k]["Mittente"]=getSet($incarichi[$k]["mitt"],$clienti,"id");
                $resultset[$k]["Destinatario"]=getSet($incarichi[$k]["dest"],$clienti,"id");
                $resultset[$k]["Colli"]=getSet($k,$colli,"incarico");
                $resultset[$k]["Movimenti"]=getSet($k,$movimenti,"id_inc");
            }

            // echo "<br><br>";
            // print_r(json_encode($resultset));
            // echo "<br><br>";
            // echo json_encode("Risultano ".count($resultset)." missioni");
            echo json_encode(["error"=>["code"=>""],"resultset"=>$resultset]);
            $missionMatch=count($resultset);
            $missionString=substr($missionString,0,strlen($missionString)-2);
        }
        
        
        //log attività
        $conn->change_user($userLogger,$passLogger,$dbName);
        logActivity($_SESSION["login"]["id"],"ricerca incarico attraverso [$criterio], [$missionMatch] match: [$missionString]",$conn);

        $conn->close();

        unset($clienti);
        unset($incarichi);
        unset($colli);
        unset($risultati);
        unset($movimenti);


        // echo "<br><br> *** Clienti:";
        // print_r(json_encode($clienti));
        // echo "<br> Colli:";
        // print_r(json_encode($colli));
        // echo "<br> Incarichi:";
        // print_r(json_encode($incarichi));
    } catch (Exception $e) {
        echo "-- " . $e->getMessage() . " (" . $e->getCode() . ")";
        echo json_encode(["error"=>["code"=>$e->getCode(),"message"=>$e->getMessage()]]);
    }

    function getSet($match,$arr,$mainKey){
        $val=[];
        foreach($arr as $k=>$v){
            if($arr[$k][$mainKey]==$match){
                $val[]=$arr[$k];
            }
        }

        return $val;
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
            if(!empty($v))
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
?>