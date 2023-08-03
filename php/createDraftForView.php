<?php
    require_once("tool.php");
    session_start();
    // isLogged("../",$_SESSION["login"]["level"],0);

    $arr=json_decode(file_get_contents("php://input"),true);

    $packs=[];
    
    $colli=$arr["Colli"];

    
    foreach($colli as $k=>$v2){
        $packs[$colli[$k]["segnacollo"]]=[
            "bancale"=>$colli[$k]["bancale"]==1?"true":"false",
            "peso"=>$colli[$k]["peso"]." ".$colli[$k]["um"],
            "descrizione"=>isset($colli[$k]["descrizione"])?$colli[$k]["descrizione"]:"",
            "dimensioni"=>$colli[$k]["dimensioni"]
        ];
    }
    

    $_SESSION["draft"]["id"]=$arr["id_inc"];
    $_SESSION["draft"]["interno"]=$arr["interno"];
    $_SESSION["draft"]["ddtN"]=isset($arr["rifDDt"])?$arr["rifDDt"]:"";
    $_SESSION["draft"]["ddtD"]=isset($arr["dataRif"])?$arr["dataRif"]:"";
    $_SESSION["draft"]["riserva"]=$arr["riserva"];
    $_SESSION["draft"]["contrassegno"]=$arr["contrassegno"];
    $_SESSION["draft"]["Mitt"]=$arr["mitt"];
    $_SESSION["draft"]["Dest"]=$arr["dest"];
    $_SESSION["draft"]["epal"]=$arr["epal"];
    $_SESSION["draft"]["tipo"]=$arr["tipologia"];
    $_SESSION["draft"]["dataConsegna"]=$arr["consegna"];
    $_SESSION["draft"]["note"]=$arr["note"];
    $_SESSION["draft"]["packs"]=json_encode($packs);
    $_SESSION["draft"]["Movimenti"]=$arr["Movimenti"];
    $_SESSION["draft"]["noerror"]="noerror";
    $_SESSION["draft"]["popup"]="";
    // echo json_encode($_SESSION["draft"], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    $_SESSION["backService"]=2;

    // print_r(json_encode($_SESSION["draft"]));
?>