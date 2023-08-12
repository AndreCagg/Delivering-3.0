<?php
    require_once("tool.php");
    require_once("../conf.php");

    session_start();
    isLogged("../",$_SESSION["login"]["level"],0);

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    try{
        $sql="SELECT *,(SELECT CONCAT(ragioneSociale,';',indirizzo,';',citta,';',prov,';',cap,';',cellulare) FROM clienti WHERE id=t.mitt) as mittente,(SELECT CONCAT(ragioneSociale,';',indirizzo,';',citta,';',prov,';',cap,';',cellulare) FROM clienti WHERE id=t.dest) as destinatario
        FROM (SELECT incarichi.id_inc, incarichi.rifDDt, incarichi.dataRif, incarichi.mitt, incarichi.dest, incarichi.epal, incarichi.tipologia, incarichi.consegna, incarichi.riserva, incarichi.impContr, zone.nome AS nome_zona
                FROM incarichi
                JOIN clienti ON incarichi.dest = clienti.id
                JOIN zone_luoghi ON clienti.cap = zone_luoghi.cap_luogo
                JOIN zone ON zone_luoghi.id_zona = zone.id
                WHERE incarichi.id_inc IN (
                    SELECT m.id_inc
                    FROM movimenti AS m
                    WHERE m.stato IN ('IN GIACENZA', 'PRONTO PER PARTENZA', 'ARRIVATO IN MAGAZZINO', 'RIENTRATO IN MAGAZZINO', 'ASSEGNATO')
                    AND m.data = (
                        SELECT MAX(data)
                        FROM movimenti
                        WHERE id_inc = m.id_inc
                    )
                )
                ORDER BY zone.nome) AS t;";

        $conn=new mysqli($dbAddress,$userOperator,$passOperator,$dbName);
        $stmt=$conn->query($sql);
        $arr=[];
        $obj=[];
        $mitt=[];
        $dest=[];
        while($row=$stmt->fetch_object()){
            $mittente=explode(";",$row->mittente);
            $destinatario=explode(";",$row->destinatario);
            $obj["id_inc"]=$row->id_inc;
            $obj["rifDDt"]=isset($row->rifDDt)?$row->rifDDt:"INT";
            $obj["dataRif"]=isset($row->dataRif)?date("d-m-Y",strtotime($row->dataRif)):"INT";
            $obj["epal"]=$row->epal;
            $obj["tipologia"]=$row->tipologia==1?"SPE":"RIT";
            $obj["consegna"]=date("d-m-Y",strtotime($row->consegna));
            $obj["riserva"]=$row->riserva;
            $obj["impContr"]=isset($row->impContr)?$row->impContr:0;
            
            //mittente
            $mitt["ragioneSociale"]=$mittente[0];
            $mitt["indirizzo"]=$mittente[1];
            $mitt["citta"]=$mittente[2];
            $mitt["prov"]=$mittente[3];
            $mitt["cap"]=$mittente[4];
            $mitt["cellulare"]=$mittente[5];

            //destinatario
            $dest["ragioneSociale"]=$destinatario[0];
            $dest["indirizzo"]=$destinatario[1];
            $dest["citta"]=$destinatario[2];
            $dest["prov"]=$destinatario[3];
            $dest["cap"]=$destinatario[4];
            $dest["cellulare"]=$destinatario[5];

            $obj["Mittente"]=$mitt;
            $obj["Destinatario"]=$dest;

            $arr[strtoupper($row->nome_zona)][$row->id_inc]=$obj;
        }
        $stmt->close();

        $conn->change_user($userLogger,$passLogger,$dbName);
        logActivity($_SESSION["login"]["id"],"get data from 'magazzino'",$conn);
        echo json_encode(["error"=>"no","resultset"=>$arr]);
    }catch(Exception $e){
        $conn->change_user($userLogger,$passLogger,$dbName);
        logActivity($_SESSION["login"]["id"],"Generated Error '".$e->getMessage()."' in 'getMagazzino' module",$conn);
        echo json_encode(["error"=>"yes"]);
    }finally{
        if(isset($conn))
            $conn->close();
    }
?>