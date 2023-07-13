<?php
    //login
    require_once("tool.php");
    session_start();
    isLogged("../",$_SESSION["login"]["level"],"0");
    date_default_timezone_set("Europe/Rome");
    $goback=false;

    $rifddt=null;
    $rifddtD=null;
    $interno=0;
    $riserva=0;
    $contrassegno=0;

    require_once("../conf.php");
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn=null;
    try{
        if($_POST["id"]==""){
            $conn=new mysqli($dbAddress,$userLogger,$passLogger,$dbName);
            goback($interno,$riserva,true,$conn,null,$rifddt,$rifddtD,$contrassegno);
            header("Location:setService.php?service=1");
            die();
        }
    }finally{
        if(isset($conn))
            $conn->close();
    }

    try{
        if(!isset($_POST["interno"])){
            if($_POST["ddtN"]=="" || $_POST["ddtD"]=="0000-00-00"){
                $conn=new mysqli($dbAddress,$userLogger,$passLogger,$dbName);
                goback($interno,$riserva,true,$conn,null,$rifddt,$rifddtD,$contrassegno);
                header("Location:setService.php?service=1");
                die();
            }
        }
    }finally{
        if(isset($conn))
            $conn->close();
    }

    try{
        if(!checkCustomerField("Mitt") || !checkCustomerField("Dest")){
            $conn=new mysqli($dbAddress,$userLogger,$passLogger,$dbName);
            goback($interno,$riserva,true,$conn,null,$rifddt,$rifddtD,$contrassegno);
            header("Location:setService.php?service=1");
            die();
        }
    }finally{
        if(isset($conn))
            $conn->close();
    }

    try{
        if($_POST["tipo"]==""){
            $conn=new mysqli($dbAddress,$userLogger,$passLogger,$dbName);
            goback($interno,$riserva,true,$conn,null,$rifddt,$rifddtD,$contrassegno);
            header("Location:setService.php?service=1");
            die();
        }
    }finally{
        if(isset($conn))
            $conn->close();
    }
    
    try{
        if($_POST["dataConsegna"]=="0000-00-00" || $_POST["dataConsegna"]<date("Y-m-d")){
            $conn=new mysqli($dbAddress,$userLogger,$passLogger,$dbName);
            goback($interno,$riserva,true,$conn,null,$rifddt,$rifddtD,$contrassegno);
            header("Location:setService.php?service=1");
            die();
        }
    }finally{
        if(isset($conn))
            $conn->close();
    }

    try{
        if(count(json_decode($_POST["packs"],true))<=0){
            $conn=new mysqli($dbAddress,$userLogger,$passLogger,$dbName);
            goback($interno,$riserva,true,$conn,null,$rifddt,$rifddtD,$contrassegno);
            header("Location:setService.php?service=1");
            die();
        }
    }finally{
        if(isset($conn))
            $conn->close();
    }
    
    if(empty($_POST["Epal"]))
        $_POST["Epal"]=0;
    
    if(isset($_POST["interno"])){
        $interno=1;
    }else{
        $rifddt=trim($_POST["ddtN"]);
        $rifddtD=trim($_POST["ddtD"]);
    }
    
    $epal=trim($_POST["Epal"]);
    $tipo=$_POST["tipo"];
    $consegna=$_POST["dataConsegna"];
    if(isset($_POST["riserva"]))
        $riserva=1;
    
    if(isset($_POST["contrassegno"]))
        $contrassegno=1;

    $note=trim($_POST["note"]);
    $message="";
    $code="";
    
    
    //parte la transazione
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

        $servizio=$conn->prepare("INSERT INTO incarichi (id_inc,rifDDt,dataRif,mitt,dest,epal,tipologia,consegna,interno,riserva,contrassegno,note) VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
        $servizio->bind_param("sssiiiisiiis",$id,$rifddt,$rifddtD,$idMitt,$idDest,$epal,$tipo,$consegna,$interno,$riserva,$contrassegno,$note);
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

        $operatore=$_SESSION["login"]["id"];
        $descrizione="inserito incarico ".$id;

        logActivity($operatore,$descrizione,$conn);
        
        $conn->commit();
        $_SESSION["success"]="Incarico salvato correttamente!";
    }catch(Exception $e){
        if(isset($conn))
            $conn->rollback();

        $goback=true;
        $message=$e->getMessage();
        $code=$e->getCode();
        goback($interno,$riserva,true,new mysqli($dbAddress,$userLogger,$passLogger,$dbName),$code,$rifddt,$rifddtD,$contrassegno);
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
    function goback($interno,$riserva,$goback,$conn,$code,$rifddt,$rifddtD,$contrassegno){
        if($goback==true){
            $_SESSION["draft"]["id"]=isset($_POST["id"])?$_POST["id"]:"";
            $_SESSION["draft"]["interno"]=$interno==1?$interno:0;
            $_SESSION["draft"]["ddtN"]=$rifddt?$rifddt:null;
            $_SESSION["draft"]["ddtD"]=$rifddtD?$rifddtD:null;
            $_SESSION["draft"]["riserva"]=$riserva==1?$riserva:0;
            $_SESSION["draft"]["contrassegno"]=$contrassegno==1?$contrassegno:0;
            $_SESSION["draft"]["Mitt"]=isset($_POST["clientiMitt"])?$_POST["clientiMitt"]:"";
            $_SESSION["draft"]["Dest"]=isset($_POST["clientiDest"])?$_POST["clientiDest"]:"";
            $_SESSION["draft"]["epal"]=isset($_POST["epal"])?$_POST["epal"]:"";
            $_SESSION["draft"]["tipo"]=isset($_POST["tipo"])?$_POST["tipo"]:"";
            $_SESSION["draft"]["dataConsegna"]=isset($_POST["dataConsegna"])?$_POST["dataConsegna"]:"";
            $_SESSION["draft"]["note"]=isset($_POST["note"])?$_POST["note"]:"";
            $_SESSION["draft"]["packs"]=isset($_POST["packs"])?$_POST["packs"]:"";
            
            if($code!=null){
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
            }else{
                $message="Errore vincoli dei dati inseriti. Ricontrolla i dati nel form, potrebbero non essere corretti";
                $code="Unk";
                $_SESSION["draft"]["error"]["code"]=$code;
            }

            $_SESSION["draft"]["error"]["message"]=$message.". Ricontrolla i dati nel form, potrebbero non essere corretti";

        }

        $operatore=$_SESSION["login"]["id"];
        $descrizione="Generated Error ".$code;

        logActivity($operatore,$descrizione,$conn);
    }
    header("Location:setService.php?service=1");
    ?>