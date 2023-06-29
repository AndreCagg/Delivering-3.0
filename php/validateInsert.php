<?php
    print_r($_POST);
    //login
    require_once("tool.php");
    session_start();
    isLogged("../");

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

    
    echo "ciaoooooo";
    // die();
    
    //parte la transazione
    require_once("../conf.php");
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn=new mysqli($dbAddress,$userOperator,$passOperator,$dbName);
    try{
        $conn->begin_transaction();
        
        //controllo utente
        $id=$_POST["id"];
        $idMitt="";
        if($_POST["clientiMitt"]=="0"){
            $inserisciCliente=$conn->prepare("INSERT INTO clienti (ragioneSociale,indirizzo,citta,prov,cap,cellulare) VALUES (?,?,?,?,?,?)");
            $campi=getCustomerField("Mitt");
            $inserisciCliente->bind_param("ssssss",$campi[0],$campi[1],$campi[2],$campi[3],$campi[4],$campi[5]);
            $inserisciCliente->execute();
            $idMitt=$inserisciCliente->insert_id;
            $inserisciCliente->close();
        }else{
            $idMitt=$_POST["clientiMitt"];
        }


        $idDest="";
        if($_POST["clientiDest"]=="0"){
            $inserisciCliente=$conn->prepare("INSERT INTO clienti (ragioneSociale,indirizzo,citta,prov,cap,cellulare) VALUES (?,?,?,?,?,?)");
            $campi=getCustomerField("Dest");
            $inserisciCliente->bind_param("ssssss",$campi[0],$campi[1],$campi[2],$campi[3],$campi[4],$campi[5]);
            $inserisciCliente->execute();
            $idDest=$inserisciCliente->insert_id;
            $inserisciCliente->close();
        }else{
            $idDest=$_POST["clientiDest"];
        }

        //salvataggio servizio
        $idServ=$_POST["id"];
        $rifddt=null;
        $rifddtD=null;
        $interno=1;
        if(!isset($_POST["interno"])){
            $rifddt=$_POST["ddtN"];
            $rifddtD=$_POST["ddtD"];
            $interno=0;
        }
        
        $epal=$_POST["Epal"];
        $tipo=$_POST["tipo"];
        $consegna=$_POST["dataConsegna"];
        $riserva=0;
        if(isset($_POST["riserva"]))
            $riserva=1;

        $note=$_POST["note"];

        $servizio=$conn->prepare("INSERT INTO incarichi (id_inc,rifDDt,dataRif,mitt,dest,epal,tipologia,consegna,interno,riserva,note) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
        $servizio->bind_param("sssiiiisiis",$id,$rifddt,$rifddtD,$idMitt,$idDest,$epal,$tipo,$consegna,$interno,$riserva,$note);
        $servizio->execute();
        $conn->commit();

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
    }catch(Exception $e){
        $conn->rollback();
        echo $e->getMessage();
    }finally{
        if(isset($conn)){
            $conn->close();
        }
    }
    
    function getCustomerField($type){
        $ragSoc=$_POST["RagSoc".$type];
        $ind=$_POST["Indirizzo".$type];
        $citta=$_POST["citta".$type];
        $prov=$_POST["Prov".$type];
        $cap=$_POST["cap".$type];
        $cell=$_POST["Cell".$type];
        return [$ragSoc,$ind,$citta,$prov,$cap,$cell];
    }

    function checkCustomerField($type){
        $cond=$_POST["clienti".$type]!="" && $_POST["RagSoc".$type]!="" && $_POST["Indirizzo".$type]!="" && $_POST["citta".$type]!="" && $_POST["Prov".$type]!="" && $_POST["cap".$type]!="" && $_POST["Cell".$type]!="";
        return $cond;
    }
    ?>