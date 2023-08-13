<?php
    session_start();

    require_once("tool.php");
    require_once("../conf.php");
    isLogged("../",$_SESSION["login"]["level"],0);
    logActivity($_SESSION["login"]["id"],"logout",new mysqli($dbAddress,$userLogger,$passLogger,$dbName));

    session_destroy();

    header("Location: ../");
?>