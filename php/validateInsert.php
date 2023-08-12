<?php
    require_once("tool.php");
    session_start();
    isLogged("../",$_SESSION["login"]["level"],"0");
    date_default_timezone_set("Europe/Rome");
    $operatore=$_SESSION["login"]["id"];

    $goback=false;

    $rifddt=null;
    $rifddtD=null;
    $interno=0;
    $riserva=0;
    $contrassegno=0;
    $GLOBALS["operation"]="inserting";

    if(isset($_POST["oldID"]))
        $GLOBALS["operation"]="editing";

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
            }else{
                $rifddt=trim($_POST["ddtN"]);
                $rifddtD=trim($_POST["ddtD"]);
            }
        }else{
            $interno=1;
        }
    }finally{
        if(isset($conn))
            $conn->close();
    }

    try{
        if(isset($_POST["contrassegno"])){
            if($_POST["impContrassegno"]=="" || $_POST["impContrassegno"]<0){
                $conn=new mysqli($dbAddress,$userLogger,$passLogger,$dbName);
                goback($interno,$riserva,true,$conn,null,$rifddt,$rifddtD,$contrassegno);
                header("Location:setService.php?service=1");
                die();
            }else{
                $contrassegno=1;
            }
        }
    }finally{
        if(isset($conn))
            $conn->close();
    }

    if(isset($_POST["riserva"]))
        $riserva=1;
    
    // if(isset($_POST["contrassegno"]))
    //     $contrassegno=1;

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
        if($_POST["dataConsegna"]=="0000-00-00" || ($_POST["dataConsegna"]<date("Y-m-d") && !isset($_POST["oldID"]))){
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
    
    
    $epal=trim($_POST["Epal"]);
    $tipo=$_POST["tipo"];
    $consegna=$_POST["dataConsegna"];
    // $contrassegno=1;

    $note=trim($_POST["note"]);
    $message="";
    $code="";
    
    class bigFileExc extends Exception{};
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
        $sql="";
        $impContr=isset($_POST["impContrassegno"])?trim($_POST["impContrassegno"]):0;
        if(isset($_POST["oldID"])){
            $sql="UPDATE incarichi SET id_inc=?,rifDDt=?,dataRif=?,mitt=?,dest=?,epal=?,tipologia=?,consegna=?,interno=?,riserva=?,contrassegno=?,impContr=?,note=? WHERE id_inc=?";
            $servizio=$conn->prepare($sql);
            $oldInc=$_POST["oldID"];
            $servizio->bind_param("sssiiiisiiidss",$id,$rifddt,$rifddtD,$idMitt,$idDest,$epal,$tipo,$consegna,$interno,$riserva,$contrassegno,$impContr,$note,$oldInc);
        }else{
            $sql="INSERT INTO incarichi (id_inc,rifDDt,dataRif,mitt,dest,epal,tipologia,consegna,interno,riserva,contrassegno,impContr,note) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)";
            $servizio=$conn->prepare($sql);
            $servizio->bind_param("sssiiiisiiids",$id,$rifddt,$rifddtD,$idMitt,$idDest,$epal,$tipo,$consegna,$interno,$riserva,$contrassegno,$impContr,$note); 
        }
        
        $servizio->execute();
        $servizio->close();

        
        //inserimento colli
        $colli=json_decode($_POST["packs"],true);

        if(isset($_POST["oldID"])){
            $inserisciColli=$conn->prepare("DELETE FROM colli WHERE incarico=?");
            $inserisciColli->bind_param("s",$id);
            $inserisciColli->execute();
        }

        $inserisciColli=$conn->prepare("INSERT INTO colli (segnacollo,incarico,peso,um,dimensioni,descrizione,bancale) VALUES (?,?,?,?,?,?,?)");
        
        foreach($colli as $k=>$v){
            $peso=explode(" ",$colli[$k]["peso"]);
            $bancale=0;
            if($colli[$k]["bancale"]=="true") $bancale=1;
            $inserisciColli->bind_param("ssdsssi",$k,$id,$peso[0],$peso[1],$colli[$k]["dimensioni"],$colli[$k]["descrizione"],$bancale);
            $inserisciColli->execute();
        }
        $inserisciColli->close();

        if(isset($_POST["oldID"]) && isset($_POST["delPic"])){
            $numToDel=explode(";",$_POST["delPic"]);
            unset($numToDel[count($numToDel)-1]);

            if($numToDel>0){
                $inserisciAllegati=$conn->prepare("DELETE FROM allegati WHERE id=? AND incarico=?");
                foreach($numToDel as $v){
                    $inserisciAllegati->bind_param("is",$v,$id);
                    $inserisciAllegati->execute();
                }
            }
        }

        if(isset($_FILES["file"])){
            foreach($_FILES["file"]["size"] as $v){
                if($v/(1024*1024)>16)
                    throw new bigFileExc("Error big file inserted");
            }
        }

        $files=$_FILES["file"]["tmp_name"];
        $i=0;
        foreach($files as $k=>$v){
            if($v!="")
                $i++;
            else
                unset($files[$k]);
        }

        if(isset($_POST["newDesc"])){
            $inserisciAllegati=$conn->prepare("UPDATE allegati SET descrizione=? WHERE id=?");
            $newDesc=json_decode($_POST["newDesc"],true);

            foreach($newDesc as $k=>$v){
                $inserisciAllegati->bind_param("si",$v,$k);
                $inserisciAllegati->execute();
            }
        }

        if($i>0){ //ci sono file
            $inserisciAllegati=$conn->prepare("INSERT INTO allegati (autore,incarico,data,foto,tipo,descrizione) VALUES (?,?,NOW(3),?,?,?)");

            foreach($files as $k=>$v){
                $file=addslashes(file_get_contents($files[$k]));
                $utente=$_SESSION["login"]["id"];
                $descrizione=$_POST["descrizione"][$k];
                $tipo=$_FILES["file"]["type"][$k];
                $inserisciAllegati->bind_param("isbss",$utente,$id,$file,$tipo,$descrizione);
                $inserisciAllegati->send_long_data(2,file_get_contents($files[$k]));
                $inserisciAllegati->execute();
            }

            $inserisciAllegati->close();
            $descrizione="nuovi allegati aggiunti a $id";
            logActivity($operatore,$descrizione,$conn);
        }

        if(isset($_POST["nuovoStato"]) && $_POST["nuovoStato"]!=0){
            $inserisciMov=$conn->prepare("INSERT INTO movimenti (id_inc,data,stato) VALUES (?,NOW(3),?)");
            $stato=mapStates($_POST["nuovoStato"]);
            $inserisciMov->bind_param("ss",$id,$stato);
            $inserisciMov->execute();
            $inserisciMov->close();
        }

        //NON VIENE CAMBIATO UTENTE ALTRIMENTI NON SALVEREBBE IL SERVIZIO, si puo provare a fare una connesione simultanea
        logActivity($operatore,$descrizione,$conn);
        $successMessage="";
        
        if(!isset($_POST["oldID"])){
            $descrizione="inserito incarico ".$id;
            $successMessage="salvato";
        }else{
            $descrizione="modificato incarico ".$_POST["oldID"]."->".$id;
            $successMessage="modificato";
        }
        
        $conn->commit();

        $_SESSION["success"]="Incarico $successMessage correttamente!";
        $_SESSION["draft"]["candelete"]="";
    }catch(Exception $e){
        if(isset($conn))
            $conn->rollback();

        $goback=true;
        $message=$e->getMessage();
        $code=$e->getCode();

        if($e instanceof bigFileExc)
            $code="bigFileExc";

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
            if(isset($_SESSION["draft"]["noerror"]))
                $_SESSION["draft"]["noerror"]="";

            if(isset($_POST["oldID"]))
                $_SESSION["draft"]["popup"]="";

            if($code!=null){
                $_SESSION["draft"]["error"]["code"]=$code;
                $message=mapSQLError($code);
            }else{
                $message="Errore vincoli dei dati inseriti";
                $code="Unk";
                $_SESSION["draft"]["error"]["code"]=$code;
            }

            $_SESSION["draft"]["error"]["message"]=$message.". Ricontrolla i dati nel form, potrebbero non essere corretti";

        }

        $operatore=$_SESSION["login"]["id"];
        $descrizione="Generated Error ".$code." in 'validateInsert' module during ".$GLOBALS["operation"];

        logActivity($operatore,$descrizione,$conn);
    }
    header("Location:setService.php?service=1");
    ?>