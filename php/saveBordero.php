<?php
    require_once("tool.php");
    require_once("../conf.php");

    session_start();
    isLogged("../",$_SESSION["login"]["level"],0);

    $bord=json_decode(file_get_contents("php://input"),true);
    if(!isset($bord["autid"]) || !isset($bord["missions"]) || !isset($bord["targa"]))
        goLogin("../");

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $mission=explode(";",$bord["missions"]);
    unset($mission[count($mission)-1]);
    $conn=null;
    try{
        $conn=new mysqli($dbAddress,$userOperator,$passOperator,$dbName);//vedi ordine della query
        $conn->begin_transaction();
        $id=0;

        /*ottenimento ultimo id*/
        $stmt=$conn->query("SELECT MAX(id) AS id FROM bordero");
        while($row=$stmt->fetch_object()){
            $id=$row->id;
        }
        $id++;
        
        $stmt=$conn->prepare("INSERT INTO bordero (id,id_inc) VALUES (?,?)");
        
        foreach($mission as $v){
            $stmt->bind_param("is",$id,$v);
            $stmt->execute();
        }
        $id=$conn->insert_id;
        $autid=$bord["autid"];
        $targa=$bord["targa"];

        $stmt=$conn->prepare("INSERT INTO bordero_autisti (id_bord,autista,data,targa) VALUES (?,?,NOW(3),?)");
        $stmt->bind_param("iis",$id,$autid,$targa);
        $stmt->execute();

        $conn->commit();
        echo json_encode(["error"=>"false"]);
    }catch(Exception $e){
        $conn->rollback();

        $conn->change_user($userLogger,$passLogger,$dbName);
        logActivity($_SESSION["login"]["id"],"Generated Error (".json_encode($mission).")'".$e->getMessage()."' in 'saveBordero' module",$conn);
        echo json_encode(["error"=>"true"]);
    }finally{
        $stmt->close();
        $conn->close();
    }
?>