<?php
    echo "benvenuto<br>";

    session_start();
    /*unset($_SESSION["login"]["logged"]);
    unset($_SESSION["login"]["id"]);
    unset($_SESSION["login"]["name"]);*/
    
    

    echo "<a href='../'>home</a>";
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="container-fluid">
    <div class="row flex-nowrap">
        <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 side-menu">
            <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 min-vh-100 shadow">
                <a href="/" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-decoration-none link-dark">
                    <b><span class="fs-5 d-none d-sm-inline">Menu</span></b>
                </a>
                <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                    <li>
                        <a href="#submenu1" data-bs-toggle="collapse" class="nav-link px-0 align-middle">
                            <i><img src="../icons/box.png" width="27px"/></i><span class="ms-1 d-none d-sm-inline">Servizi</span> </a>
                        <ul class="collapse nav flex-column ms-1" id="submenu1" data-bs-parent="#menu" style="font-size:14px;padding-left:15px;">
                            <li class="w-100">
                            <a href="#" class="nav-link px-0"><i><img src="../icons/add.png" width="20px"/></i><span class="d-none d-sm-inline">Inserisci</span></a>
                            </li>
                            <li>
                                <a href="#" class="nav-link px-0"><i><img src="../icons/search.png" width="23px"/></i> <span class="d-none d-sm-inline">Ricerca</span></a>
                            </li>
                        </ul>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link align-middle px-0">
                            <i><img src="../icons/magazzino.png" width="23px"/></i><span class="ms-1 d-none d-sm-inline">Magazzino</span>
                        </a>
                    </li>

                    <li>
                        <a href="#submenu2" data-bs-toggle="collapse" class="nav-link px-0 align-middle">
                            <i><img src="../icons/bordero.png" width="27px"/></i><span class="ms-1 d-none d-sm-inline">Borderò</span> </a>
                        <ul class="collapse nav flex-column ms-1" id="submenu2" data-bs-parent="#menu" style="font-size:14px;padding-left:15px;">
                            <li class="w-100">
                                <a href="#" class="nav-link px-0"><i><img src="../icons/visualizza.png" width="18px" /></i> <span class="d-none d-sm-inline">Visualizza</span></a>
                            </li>
                            <li>
                                <a href="#" class="nav-link px-0"><i><img src="../icons/crea.png" width="21px"/></i> <span class="d-none d-sm-inline">Crea</span></a>
                            </li>
                        </ul>
                    </li>
                <hr>
        </div>
        <div class="col py-3">
            Content area...
        </div>
    </div>
</div>
</body>
</html>