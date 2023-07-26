<?php
    require_once("tool.php");
    require_once("../conf.php");
    session_start();
    // isLogged("../",$_SESSION["login"]["level"],0);
    
    if(isset($_GET["id"]))
        logActivity("2","Read mission [".$_GET["id"]."]",new mysqli($dbAddress,$userLogger,$passLogger,$dbName));
    else
        goLogin("../");

?>