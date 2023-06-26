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

    function checkCustomerField($type){
        $cond=($_POST["clienti".$type]!="" && $_POST["clienti".$type]!=0) && $_POST["RagSoc".$type]!="" && $_POST["Indirizzo".$type]!="" && $_POST["citta".$type]!="" && $_POST["Prov".$type]!="" && $_POST["cap".$type]!="" && $_POST["Cell".$type]!="";
        return $cond;
    }

    echo "ciaoooooo";
?>