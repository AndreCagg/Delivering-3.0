<?php
    require_once("../conf.php");
    require_once("tool.php");

    session_start();
    isLogged("../",$_SESSION["login"]["level"],"1");

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $veic=[];
    $tmp=[];
    $conn=null;
    try{
        $conn=new mysqli($dbAddress,$userSuperior,$passSuperior,$dbName);
        $query=$conn->query("SELECT * FROM veicoli ORDER BY targa ASC");

        while($row=$query->fetch_object()){
            $veic[]=$row;
        }

        $tmp["veicoli"]=$veic;
        $tmp["error"]="false";
        $query->close();
    }catch(Exception $e){
        $tmp["error"]="true";
    } finally {
        echo json_encode($tmp);
        if ($conn) {
            $conn->close();
        }
    }
?>