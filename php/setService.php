<?php
    session_start();
    $_SESSION["service"]=$_GET["service"];
    header("Location:../html/dashboard.php");
?>