<?php
    require_once("../conf.php");
    require_once("tool.php");

    session_start();
    isLogged("../",$_SESSION["login"]["level"],"0");

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $autisti=[];
    $tmp=[];
    $conn=null;
    try{
        $field="*";
        if(!$_GET["detail"])
            $field="id,nome,cognome";

        $conn=new mysqli($dbAddress,$userOperator,$passOperator,$dbName);
        $query=$conn->query("SELECT $field FROM autisti ORDER BY cognome ASC");

        while($row=$query->fetch_object()){
            $autisti[]=$row;
        }

        $tmp["autisti"]=$autisti;
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