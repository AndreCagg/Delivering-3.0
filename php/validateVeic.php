<?php
    require_once("tool.php");
    require_once("../conf.php");
    session_start();
    isLogged("../",$_SESSION["login"]["level"],1);
    $veic=json_decode(file_get_contents("php://input"),true);

    // if($_SERVER["REQUEST_METHOD"]!="DELETE"){
    //     foreach($autista as $k=>$a){
    //         if($a=="" || ($k=="Email" && filter_var($a,FILTER_VALIDATE_EMAIL)===false)){
    //             logError("tried to save driver but generated error for '$k' value",$dbAddress,$userLogger,$passLogger,$dbName);
                
    //             echo json_encode(["error"=>"yes"]);
    //             die();
    //         }
    //     }
    // }
    
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $conn=null;
    try{
        $conn=new mysqli($dbAddress,$userSuperior,$passSuperior,$dbName);
        $conn->begin_transaction();

        $op="";
        $insertid=0;
        if($_SERVER["REQUEST_METHOD"]!="DELETE"){
            if(isset($veic["draft"])){
                $stmt=$conn->prepare("UPDATE veicoli SET targa=?,nome=? WHERE targa=?");
                $stmt->bind_param("sss",$veic["Targa"],$veic["Nome"],$veic["draft"]);
                $op="Edited";
                $insertid=$veic["draft"];
            }else{
                $stmt=$conn->prepare("INSERT INTO veicoli (targa,nome) VALUES (?,?)");
                $stmt->bind_param("ss",$veic["Targa"],$veic["Nome"]);
                $op="Saved";
                $insertid=$veic["Targa"];
            }
        }else{
            $stmt=$conn->prepare("DELETE FROM veicoli WHERE targa=?");
            $stmt->bind_param("s",$veic["id"]);
            $op="Deleted";
            $insertid=$veic["id"];
        }
        $stmt->execute();
        
        logError("$op vehicle n. ".$insertid,$dbAddress,$userLogger,$passLogger,$dbName);
        $stmt->close();
        $conn->commit();

        echo json_encode(["error"=>"no"]);
    }catch(Exception $e){
        $conn->rollback();
        logError("Generated an error while saving a vehicle (".$e->getCode().")",$dbAddress,$userLogger,$passLogger,$dbName);
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