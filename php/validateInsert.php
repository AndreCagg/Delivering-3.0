<?php
    print_r($_POST);
    //login
    require_once("tool.php");
    session_start();
    isLogged("../",$_SESSION["login"]["level"],"0");
    date_default_timezone_set("Europe/Rome");
    $goback=false;

    if($_POST["id"]==""){
        echo "compila le informazioni correttamente (id)";
        die();
    }

    if(!isset($_POST["interno"])){
        if($_POST["ddtN"]=="" || $_POST["ddtD"]=="0000-00-00"){
            echo "compila le informazioni correttamente (interno)";
            die();
        }
    }

    if(!checkCustomerField("Mitt") || !checkCustomerField("Dest")){
        echo "compila le informazioni correttamente (clienti)";
        die();
    }

    if($_POST["tipo"]==""){
        echo "compila le informazioni correttamente (tipo)";
        die();
    }
    
    if($_POST["dataConsegna"]=="0000-00-00" || $_POST["dataConsegna"]<date("Y-m-d")){
        echo "compila le informazioni correttamente (consegna)";
        die();
    }

    if(count(json_decode($_POST["packs"],true))<=0){
        echo "compila le informazioni correttamente (colli)";
        die();
    }
    
    if($_POST["Epal"]=="")
        $_POST["Epal"]=0;
    
    // $idServ=$_POST["id"];
    $rifddt=null;
    $rifddtD=null;
    $interno=1;
    if(!isset($_POST["interno"])){
        $rifddt=trim($_POST["ddtN"]);
        $rifddtD=trim($_POST["ddtD"]);
        $interno=0;
    }
    
    $epal=trim($_POST["Epal"]);
    $tipo=$_POST["tipo"];
    $consegna=$_POST["dataConsegna"];
    $riserva=0;
    if(isset($_POST["riserva"]))
        $riserva=1;

    $note=trim($_POST["note"]);
    $message="";
    $code="";
    
    
    //parte la transazione
    require_once("../conf.php");
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    try{
        $conn=new mysqli($dbAddress,$userOperator,$passOperator,$dbName);
        $conn->begin_transaction();
        
        //controllo utente
        $id=trim($_POST["id"]);
        $idMitt="";
        if($_POST["clientiMitt"]=="0"){
            $inserisciCliente=$conn->prepare("INSERT INTO clienti (ragioneSociale,indirizzo,citta,prov,cap,cellulare) VALUES (?,?,?,?,?,?)");
            $campi=getCustomerField("Mitt");
            $inserisciCliente->bind_param("ssssss",$campi[0],$campi[1],$campi[2],$campi[3],$campi[4],$campi[5]);
            $inserisciCliente->execute();
            $idMitt=$inserisciCliente->insert_id;
        }else{
            $idMitt=$_POST["clientiMitt"];
            $inserisciCliente=$conn->prepare("UPDATE clienti SET ragioneSociale=?,indirizzo=?,citta=?,prov=?,cap=?,cellulare=? WHERE id=?");
            $campi=getCustomerField("Mitt");
            $inserisciCliente->bind_param("ssssssi",$campi[0],$campi[1],$campi[2],$campi[3],$campi[4],$campi[5],$_POST["clientiMitt"]);
            $inserisciCliente->execute();
        }
        $inserisciCliente->close();


        $idDest="";
        if($_POST["clientiDest"]=="0"){
            $inserisciCliente=$conn->prepare("INSERT INTO clienti (ragioneSociale,indirizzo,citta,prov,cap,cellulare) VALUES (?,?,?,?,?,?)");
            $campi=getCustomerField("Dest");
            $inserisciCliente->bind_param("ssssss",$campi[0],$campi[1],$campi[2],$campi[3],$campi[4],$campi[5]);
            $inserisciCliente->execute();
            $idDest=$inserisciCliente->insert_id;
        }else{
            $idDest=$_POST["clientiDest"];
            $inserisciCliente=$conn->prepare("UPDATE clienti SET ragioneSociale=?,indirizzo=?,citta=?,prov=?,cap=?,cellulare=? WHERE id=?");
            $campi=getCustomerField("Dest");
            $inserisciCliente->bind_param("ssssssi",$campi[0],$campi[1],$campi[2],$campi[3],$campi[4],$campi[5],$_POST["clientiDest"]);
            $inserisciCliente->execute();
        }
        $inserisciCliente->close();

        //salvataggio servizio

        $servizio=$conn->prepare("INSERT INTO incarichi (id_inc,rifDDt,dataRif,mitt,dest,epal,tipologia,consegna,interno,riserva,note) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
        $servizio->bind_param("sssiiiisiis",$id,$rifddt,$rifddtD,$idMitt,$idDest,$epal,$tipo,$consegna,$interno,$riserva,$note);
        $servizio->execute();
        $servizio->close();
        
        //inserimento colli
        $colli=json_decode($_POST["packs"],true);
        $inserisciColli=$conn->prepare("INSERT INTO colli (segnacollo,incarico,peso,um,descrizione,bancale) VALUES (?,?,?,?,?,?)");
        
        foreach($colli as $k=>$v){
            $peso=explode(" ",$colli[$k]["peso"]);
            $bancale=0;
            if($colli[$k]["bancale"]=="true") $bancale=1;
            $inserisciColli->bind_param("ssdssi",$k,$id,$peso[0],$peso[1],$colli[$k]["descrizione"],$bancale);
            $inserisciColli->execute();
        }
        $inserisciColli->close();

        // $movimenti=$conn->prepare("INSERT INTO movimenti (id_inc,data,stato) VALUES  (?,?,?)");
        // $oggi=date("Y-m-d H:i:s");
        // $stato="INSERITO";
        // $movimenti->bind_param("sss",$id,$oggi,$stato);
        // $movimenti->execute();
        // $movimenti->close();
        
        $log=$conn->prepare("INSERT INTO logoffice (autore, descrizione, data) VALUES (?,?,?)");
        $operatore=$_SESSION["login"]["id"];
        $descrizione="inserito incarico ".$id;
        $adesso=date("Y-m-d H:i:s");
        $log->bind_param("iss",$operatore,$descrizione,$adesso);
        $log->execute();
        $log->close();
        
        $conn->commit();
    }catch(Exception $e){
        if(isset($conn))
            $conn->rollback();

        $goback=true;
        $message=$e->getMessage();
        $code=$e->getCode();
        // echo "<br>(".$e->getCode().") ".$e->getMessage();
    }finally{
        if(isset($conn)){
            $conn->close();
        }
    }
    
    function getCustomerField($type){
        $ragSoc=trim($_POST["RagSoc".$type]);
        $ind=trim($_POST["Indirizzo".$type]);
        $citta=trim($_POST["citta".$type]);
        $prov=trim($_POST["Prov".$type]);
        $cap=trim($_POST["cap".$type]);
        $cell=trim($_POST["Cell".$type]);
        return [$ragSoc,$ind,$citta,$prov,$cap,$cell];
    }

    function checkCustomerField($type){
        $cond=$_POST["clienti".$type]!="" && $_POST["RagSoc".$type]!="" && $_POST["Indirizzo".$type]!="" && $_POST["citta".$type]!="" && $_POST["Prov".$type]!="" && $_POST["cap".$type]!="" && $_POST["Cell".$type]!="";
        return $cond;
    }

    //torno indietro
    if($goback){
        $_SESSION["draft"]["id"]=$_POST["id"];
        $_SESSION["draft"]["interno"]=$interno;
        $_SESSION["draft"]["ddtN"]=$rifddt;
        $_SESSION["draft"]["ddtD"]=$rifddtD;
        $_SESSION["draft"]["riserva"]=$riserva;
        $_SESSION["draft"]["Mitt"]=$_POST["clientiMitt"];
        $_SESSION["draft"]["Dest"]=$_POST["clientiDest"];
        $_SESSION["draft"]["epal"]=$_POST["epal"];
        $_SESSION["draft"]["tipo"]=$_POST["tipo"];
        $_SESSION["draft"]["dataConsegna"]=$_POST["dataConsegna"];
        $_SESSION["draft"]["note"]=$_POST["note"];
        $_SESSION["draft"]["packs"]=$_POST["packs"];
        $_SESSION["draft"]["error"]["code"]=$code;
        
        switch($code){
            case 1044:
                $message="Impossibile trovare il Database";
            break;
            case 1045:
                $message="Errore di login al Database";
            break;
            case 1062:
                $message="Codice identificativo dell'incarico e/o già esistene nel Database, generarne uno nuovo";
            break;
            case 1146:
                $message="Impossibile salvare l'incarico a causa di una tabella inesistente";
            break;
            case 1216:
                $message="Impossibile salvare l'incarico a causa di una chiave esterna inesistente";
            break;
            case 1451:
                $message="Impossibile salvare l'incarico, violazione vincolo di chiave esterna";
            break;
            case 1064:
                $message="Impossibile salvare l'incarico, chiamare un tecnico per risolvere i problemi di sintassi";
            break;
            case 2006:
                $message="Connessione al Database persa, riprovare";
            break;
            case 2013:
                $message="Impossibile salvare i dati a causa di una configurazione che va in conflitto con quella esistente, riprovare. Se il problema persiste contattare un tecnico";
            break;
            default:
                $message="Impossibile salvare i dati. Errore generico. Se il problema persiste contattare il tecnico";
            break;
        }

        $_SESSION["draft"]["error"]["message"]=$message;

    }else{
        $_SESSION["success"]="Incarico salvato correttamente!";
    }
    header("Location:setService.php?service=1");
    ?>