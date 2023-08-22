<?php
    require_once("tool.php");
    require_once("../config.php");

    session_start();
    isLogged("../",$_SESSION["login"]["level"],0);

    if(!isset($_POST["autid"]) || !isset($_POST["missions"]))
        goLogin("../");

    
?>