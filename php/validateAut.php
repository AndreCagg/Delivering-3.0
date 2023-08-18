<?php
    require_once("tool.php");
    require_once("../conf.php");
    session_start();
    isLogged("../",$_SESSION["login"]["level"],1);
    $autista=json_decode(file_get_contents("php://input"),true);

    if($_SERVER["REQUEST_METHOD"]!="DELETE"){
        foreach($autista as $k=>$a){
            if($a=="" || ($k=="Email" && filter_var($a,FILTER_VALIDATE_EMAIL)===false)){
                logError("tried to save driver but generated error for '$k' value",$dbAddress,$userLogger,$passLogger,$dbName);
                
                echo json_encode(["error"=>"yes"]);
                die();
            }
        }
    }
    
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $conn=null;
    try{
        $conn=new mysqli($dbAddress,$userSuperior,$passSuperior,$dbName);
        $conn->begin_transaction();

        $op="";
        $insertid=0;
        if($_SERVER["REQUEST_METHOD"]!="DELETE"){
            if(isset($autista["draft"])){
                $stmt=$conn->prepare("UPDATE autisti SET nome=?,cognome=?,email=?,tel=? WHERE id=?");
                $stmt->bind_param("ssssi",$autista["Nome"],$autista["Cognome"],$autista["Email"],$autista["Tel"],$autista["draft"]);
                $op="Edited";
                $insertid=$autista["draft"];
            }else{
                $stmt=$conn->prepare("INSERT INTO autisti (nome,cognome,email,tel) VALUES (?,?,?,?)");
                $stmt->bind_param("ssss",$autista["Nome"],$autista["Cognome"],$autista["Email"],$autista["Tel"]);
                $op="Saved";
                $insertid=$conn->insert_id;
            }
        }else{
            $stmt=$conn->prepare("DELETE FROM autisti WHERE id=?");
            $stmt->bind_param("i",$autista["id"]);
            $op="Deleted";
            $insertid=$autista["id"];
        }
        $stmt->execute();
        
        logError("$op driver n. ".$insertid,$dbAddress,$userLogger,$passLogger,$dbName);
        $stmt->close();
        $conn->commit();

        echo json_encode(["error"=>"no"]);
    }catch(Exception $e){
        $conn->rollback();
        logError("Generated an error while saving a driver (".$e->getCode().")",$dbAddress,$userLogger,$passLogger,$dbName);
        echo json_encode(["error"=>"yes"]);
    }finally{
        if(isset($conn))
            $conn->close();
    }
    
    function logError($desc,$dbAddress,$userLogger,$passLogger,$dbName){
        $conn=new mysqli($dbAddress,$userLogger,$passLogger,$dbName);
        logActivity($_SESSION["login"]["id"],$desc,$conn);
        $conn->close();
    }
?>