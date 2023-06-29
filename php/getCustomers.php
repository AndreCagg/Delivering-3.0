<?php
    require_once("../conf.php");
    require_once("tool.php");

    session_start();
    isLogged("../");

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $clienti=[];
    $tmp=[];
    $conn=null;
    try{
        $conn=new mysqli($dbAddress,$userOperator,$passOperator,$dbName);
        $query=$conn->query("SELECT * FROM clienti");

        while($row=$query->fetch_object()){
            $clienti[]=$row;
        }

        $tmp["clienti"]=$clienti;
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