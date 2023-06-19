<?php
    echo "<a href='../'>home</a>";

    session_start();
    require_once "../php/tool.php";
    isLogged("../");
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
    <script src="https://code.jquery.com/jquery-3.7.0.slim.min.js" integrity="sha256-tG5mcZUtJsZvyKAxYLVXrmjKBVLd6VpVccqz/r4ypFE=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/controlPanel.css">
    <link rel="stylesheet" href="../css/style.css">
    <script src="../js/dashboard_insert.js"></script>
</head>
<body>
    <div class="container-fluid">
        <div class="row dashboard-banner full-width-row">
            <div class="d-flex flex-row-reverse fw-bold p-1 fs-6">
                    <?php
                        echo "Benvenuto, ".$_SESSION["login"]["name"];
                    ?>
                <button class="mx-2 rounded logout-btn d-flex">Esci <img alt="Esci" src="../icons/logout.png" width="23px" class="mx-1"></button>

            </div>
        </div>
        <div class="row flex-nowrap">
            <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 side-menu">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 min-vh-100 shadow h-100">
                    <a href="/" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-decoration-none link-dark">
                        <b><span class="fs-5 d-none d-sm-inline">Menu</span></b>
                    </a>
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start" id="menu">
                        <li>
                            <a href="#submenu1" data-bs-toggle="collapse" class="nav-link px-0 align-middle">
                                <i><img src="../icons/box.png" width="27px" alt="Gestisci un servizio"/></i><span class="ms-1 d-none d-sm-inline">Servizi</span> </a>
                            <ul class="collapse nav flex-column ms-1" id="submenu1" data-bs-parent="#menu" style="font-size:14px;padding-left:15px;">
                                <li class="w-100">
                                <a href="../php/setService.php?service=1" class="nav-link px-0"><i><img src="../icons/add.png" width="20px" alt="Inserisci un servizio"/></i><span class="d-none d-sm-inline">Inserisci</span></a>
                                </li>
                                <li>
                                    <a href="#" class="nav-link px-0"><i><img src="../icons/search.png" width="23px" alt="Ricerca un servizio"/></i> <span class="d-none d-sm-inline">Ricerca</span></a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link align-middle px-0">
                                <i><img src="../icons/magazzino.png" width="23px" alt="Gestisci il magazzino"/></i><span class="ms-1 d-none d-sm-inline">Magazzino</span>
                            </a>
                        </li>

                        <li>
                            <a href="#submenu2" data-bs-toggle="collapse" class="nav-link px-0 align-middle">
                                <i><img src="../icons/bordero.png" width="27px" alt="Gestisci borderò"/></i><span class="ms-1 d-none d-sm-inline">Borderò</span> </a>
                            <ul class="collapse nav flex-column ms-1" id="submenu2" data-bs-parent="#menu" style="font-size:14px;padding-left:15px;">
                                <li class="w-100">
                                    <a href="#" class="nav-link px-0"><i><img src="../icons/visualizza.png" width="18px" alt="Visualizza borderò"/></i> <span class="d-none d-sm-inline">Visualizza</span></a>
                                </li>
                                <li>
                                    <a href="#" class="nav-link px-0"><i><img src="../icons/crea.png" width="21px" alt="Crea borderò"/></i> <span class="d-none d-sm-inline">Crea</span></a>
                                </li>
                            </ul>
                        </li>
                    <hr>
                </div>
            </div>
            <div class="col mx-5 py-3">
                <!--CODICI SERVIZI 
                1: INSERIMENTO-->
                <?php
                    if(!isset($_SESSION["service"])) die();
                    switch($_SESSION["service"]){
                        case 1:
                            ?>

                            <form action="#" method="post">
                                <div class="row justify-content-between">
                                    <div class="d-flex align-items-center">
                                        ID&nbsp;
                                        <input type="text" name="id" size="14" class="form-control" style="width:170px;height:35px;">&nbsp;
                                        rif. DDT n. <input type="number" name="ddtN" min="0" max="1000" class="form-control" style="width:170px;height:35px;">&nbsp;
                                        del &nbsp;<input type="date" name="ddtD" class="form-control" style="width:170px;height:35px;">&nbsp;&nbsp;
                                        <div class="form-check" style="width:10%;">
                                            <input class="form-check-input form-check-input-xl" type="checkbox" name="interno" id="interno" style="width:20%;height:50%;">&nbsp;
                                            <label class="form-check-label" for="interno">
                                                Interna
                                            </label>
                                        </div>

                                        <div class="form-check" style="width:10%;">
                                            <input class="form-check-input form-check-input-xl" type="checkbox" name="riserva" id="riserva" style="width:20%;height:50%;">&nbsp;
                                            <label class="form-check-label" for="riserva">
                                                Riserva
                                            </label>
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                    <div class="col-4">
                                        <fieldset class="border rounded-3 p-3" id="field-set">
                                            <legend class="float-none w-auto px-3">Mittente</legend>
                                            Seleziona <select name="clientiMitt" id="clientiMitt" class="form-select" style="width:250px;">
                                            <option></option>
                                            <option value="0">NUOVO</option>
                                            </select>

                                            <div class="label-form" for="RagSocMitt">Ragione sociale</div>
                                            <input type="text" name="RagSocMitt" id="RagSocMitt" class="form-control my-1" style="width:230px;">
                                            
                                            <div class="label-form" for="IndirizzoMitt">Indirizzo</div>
                                            <input type="text" name="IndirizzoMitt" id="IndirizzoMitt" class="form-control my-1" style="width:250px;">
                                        
                                            <div class="label-form" for="CittaMitt">Citt&agrave;</div>
                                            <input type="text" name="cittaMitt" id="CittaMitt" class="form-control my-1" style="width:240px;">

                                            <div class="label-form" for="ProvMitt">Prov</div>
                                            <input type="text" name="ProvMitt" id="ProvMitt" class="form-control my-1" style="width:100px;">
                                            
                                            <div class="label-form" for="CapMitt">Cap</div>
                                            <input type="number" name="capMitt" id="capMitt" class="form-control my-1" style="width:100px;">

                                            <div class="label-form" for="CellMitt">Cellulare</div>
                                            <input type="text" name="CellMitt" id="CellMitt" class="form-control my-1" style="width:170px;">
                                        </fieldset>
                                    </div>

    

                                    <div class="col-4">
                                        <fieldset class="border rounded-3 p-3" id="field-set">
                                            <legend class="float-none w-auto px-3">Destinatario</legend>
                                            Seleziona <select name="clientiDest" id="clientiDest" class="form-select" style="width:250px;">
                                                <option></option>
                                                <option value="0">NUOVO</option>
                                            </select>

                                            <div class="label-form" for="RagSocMitt">Ragione sociale</div>
                                            <input type="text" name="RagSocDest" id="RagSocDest" class="form-control my-1" style="width:230px;">
                                            
                                            <div class="label-form" for="IndirizzoMitt">Indirizzo</div>
                                            <input type="text" name="IndirizzoDest" id="IndirizzoDest" class="form-control my-1" style="width:250px;">
                                        
                                            <div class="label-form" for="CittaMitt">Citt&agrave;</div>
                                            <input type="text" name="cittaDest" id="CittaDest" class="form-control my-1" style="width:240px;">

                                            <div class="label-form" for="ProvMitt">Prov</div>
                                            <input type="text" name="ProvDest" id="ProvDest" class="form-control my-1" style="width:100px;">
                                            
                                            <div class="label-form" for="CapMitt">Cap</div>
                                            <input type="number" name="capDest" id="capDest" class="form-control my-1" style="width:100px;">

                                            <div class="label-form" for="CellMitt">Cellulare</div>
                                            <input type="text" name="CellDest" id="CellDest" class="form-control my-1" style="width:170px;">
                                        </fieldset>
                                    </div>

                                    <br><br>

                                    <div class="d-flex align-items-center" style="margin-top:25px;">
                                        <div class="label-form" for="Epal">Epal</div>&nbsp;
                                        <input type="number" name="Epal" id="Epal" class="form-control my-1" min="0" max="1000" value="0" style="width:80px;margin-right:20px;">&nbsp; <div class="label-form" for="tipo">Tipologia</div>&nbsp; 
                                        <select name="tipo" id="tipo" class="form-select" style="width:200px;margin-right:20px;">
                                            <option value="00"></option>
                                            <option value="1">SPEDIZIONE</option>
                                            <option value="2">RITIRO</option>
                                        </select>&nbsp;
                                        <div class="label-form" for="dataConsegna">Data consegna</div>&nbsp;
                                        <input type="date" name="dataConsegna" id="dataConsegna" class="form-control my-1" min="0" max="1000" style="width:150px;">
                                    </div>
                                </div><br>
                                <hr style="width:90%;">
                                <div class="row flex-nowrap">
                                    <div class="col-4" id="pack-form">
                                        <h5 class="fw-normal">Colli/Bancali</h3>

                                        <div class="label-form" for="segnacollo">Segnacollo</div>
                                        <input type="text" id="segnacollo" class="form-control my-1" style="width:200px;">
                                        <button type="button" class="btn btn-success btn-sm mb-3" id="generaID"><b>GENERA</b></button>

                                        <div class="label-form" for="peso">Peso</div>
                                        <input type="number" step="0.01" id="peso" class="form-control my-1" style="width:100px;">&nbsp;
                                        <select name="um" id="um" class="form-select" style="width:75px;margin-right:20px;margin-bottom:10px;">
                                            <option value="1">Kg</option>
                                            <option value="2">Q.li</option>
                                        </select>

                                        <div class="label-form" for="descrizione">Descrizione</div>
                                        <input type="text" id="descrizione" class="form-control my-1" style="width:200px;">

                                        <div class="label-form" for="dimensioni">Dimensioni (h x l x p)</div>
                                        <input type="text" id="dimensioni" class="form-control my-1" style="width:200px;">

                                        <small style="color:red;display:none;" id="alert">
                                            <img src="../icons/!.png" width="15px" height="16px">
                                            Il segnacollo e il peso sono obbligatori
                                        </small>
                                        <small style="color:red;display:none;" id="duplicateID-alert">
                                            <img src="../icons/!.png" width="15px" height="16px">
                                            Il segnacollo risulta già inserito
                                        </small>

                                        <div>
                                            <br>
                                            <button type="button" class="btn btn-danger" id="addPack">Aggiungi</button>
                                        </div>
                                    </div>
                                    <div class="col-1 p-0" style="width:1px; background-color:#bfc0c1;"></div>
                                    <div class="col-4 justify-content-end scrollable-tbody mb-5 mx-5">
                                        <table class="table table-striped table-hover" id="colliList">
                                            <thead>
                                                <tr>
                                                    <th>SEGNACOLLO</th>
                                                    <th>PESO</th>
                                                    <th>DIMENSIONI</th>
                                                    <th>DESCRIZIONE</th>
                                                    <th></th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody class="text-center">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row justify-content-right mx-1 mt-3">
                                <hr style="width:90%; opacity:20%;">
                                    <h5 class="fw-normal">Note</h5>
                                    <textarea class="form-control w-25" id="note" name="note" rows="5"></textarea>
                                </div>

                                <div class="row m-4 mt-5 justify-content-center">
                                    <button class="btn btn-danger w-25" type="submit" onclick="event.preventDefault()">Inserisci</button>
                                </div>
                            </form>

                        <?php
                        // unset($_SESSION["service"]);
                        break;
                    }
                ?>
            </div>
        </div>

        <script>
            document.getElementById("addPack").addEventListener("click",loadPack);
            document.getElementById("generaID").addEventListener("click",generateID);
            document.getElementById("pack-form").addEventListener("keydown",ev=>{
                if(ev.keyCode==13){
                    ev.preventDefault();
                    loadPack();
                    document.activeElement.blur();
                }

            });
            window.addEventListener("DOMContentLoaded",loadCostumers);
            document.getElementById("clientiMitt").addEventListener("change",()=>{fillCustomer(document.getElementById("clientiMitt"),"Mitt");});
            document.getElementById("clientiDest").addEventListener("change",()=>{fillCustomer(document.getElementById("clientiDest"),"Dest");});
        </script>
    </body>
</html>