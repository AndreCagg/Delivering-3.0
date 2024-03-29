<?php
session_start();
require_once "../php/tool.php";
isLogged("../", $_SESSION["login"]["level"], "0");
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
        <link rel="stylesheet" href="../css/dashboard_insert.css">
        <link rel="stylesheet" href="../css/style.css">
        <script src="../js/dashboard_insert.js"></script>
    </head>

    <body>
        <script>
            function logout(){
                window.location.replace("../php/logout.php");
            }
        </script>
        <?php
            $banServ=[4,6];
            if(isset($_SESSION["service"]) && in_array($_SESSION["service"],$banServ) && $_SESSION["login"]["level"]<=0)
                goLogin("../");
        ?>
        <div class="container-fluid">
            <div class="row dashboard-banner full-width-row">
                <div class="d-flex flex-row-reverse fw-bold p-1 fs-6">
                    <?php echo "Benvenuto, " . $_SESSION["login"]["name"]; ?>
                    <button class="mx-2 rounded logout-btn d-flex" id="logout" onclick="logout()">Esci <img alt="Esci" src="../icons/logout.png" width="23px" class="mx-1"></button>
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
                                    <i><img src="../icons/box.png" width="27px" alt="Gestisci un servizio" /></i><span class="ms-1 d-none d-sm-inline">Incarichi</span> </a>
                                <ul class="collapse nav flex-column ms-1" id="submenu1" data-bs-parent="#menu" style="font-size:14px;padding-left:15px;">
                                    <li class="w-100">
                                        <a href="../php/setService.php?service=1" class="nav-link px-0"><i><img src="../icons/add.png" width="20px" alt="Inserisci un incarico" /></i><span class="d-none d-sm-inline">Inserisci</span></a>
                                    </li>
                                    <li>
                                        <a href="../php/setService.php?service=2" class="nav-link px-0"><i><img src="../icons/search.png" width="23px" alt="Ricerca un incarico" /></i> <span class="d-none d-sm-inline">Ricerca</span></a>
                                    </li>
                                </ul>
                            </li>

                            <li class="nav-item">
                                <a href="../php/setService.php?service=3" class="nav-link align-middle px-0">
                                    <i><img src="../icons/magazzino.png" width="23px" alt="Gestisci il magazzino" /></i><span class="ms-1 d-none d-sm-inline">Magazzino</span>
                                </a>
                            </li>

                            <li>
                                <a href="#submenu2" data-bs-toggle="collapse" class="nav-link px-0 align-middle">
                                    <i><img src="../icons/bordero.png" width="27px" alt="Gestisci borderò" /></i><span class="ms-1 d-none d-sm-inline">Borderò</span> </a>
                                <ul class="collapse nav flex-column ms-1" id="submenu2" data-bs-parent="#menu" style="font-size:14px;padding-left:15px;">
                                    <li class="w-100">
                                        <a href="#" class="nav-link px-0"><i><img src="../icons/visualizza.png" width="18px" alt="Visualizza borderò" /></i> <span class="d-none d-sm-inline">Visualizza</span></a>
                                    </li>
                                    <li>
                                        <a href="../php/setService.php?service=5" class="nav-link px-0"><i><img src="../icons/crea.png" width="21px" alt="Crea borderò" /></i> <span class="d-none d-sm-inline">Crea</span></a>
                                    </li>
                                </ul>
                            </li>

                            <?php
                            if($_SESSION["login"]["level"]>0){// da fare submenu
                                ?>
                                <li>
                                    <a href="#submenu3" data-bs-toggle="collapse" class="nav-link px-0 align-middle">
                                        <i><img src="../icons/persona.png" width="27px" alt="Gestisci personale" /></i><span class="ms-1 d-none d-sm-inline">Gest. personale</span> </a>
                                    <ul class="collapse nav flex-column ms-1" id="submenu3" data-bs-parent="#menu" style="font-size:14px;padding-left:15px;">
                                        <li class="w-100">
                                            <a href="../php/setService.php?service=4" class="nav-link px-0"><i><img src="../icons/autista.png" width="18px" alt="Gestisci autisti" /></i> <span class="d-none d-sm-inline">Autisti</span></a>
                                        </li>
                                        <li>
                                            <a href="#" class="nav-link px-0"><i><img src="../icons/cliente.png" width="21px" alt="Gestisci clienti" /></i> <span class="d-none d-sm-inline">Clienti</span></a>
                                        </li>
                                        <li>
                                            <a href="#" class="nav-link px-0"><i><img src="../icons/impiegato.png" width="21px" alt="Gestisci impiegati" /></i> <span class="d-none d-sm-inline">Impiegati</span></a>
                                        </li>
                                    </ul>
                                </li> 
                                <li class="nav-item">
                                    <a href="../php/setService.php?service=6" class="nav-link align-middle px-0">
                                        <i><img src="../icons/camion.png" width="23px" alt="Gestisci le macchine" /></i><span class="ms-1 d-none d-sm-inline">Veicoli</span>
                                    </a>
                                </li>
                                <?php
                            }
                            ?>
                            </ul>
                        <hr>
                    </div>
                </div>
                <div class="col mx-4 py-3">
                    <!--CODICI SERVIZI 
                    1: INSERIMENTO
                    2: RICERCA
                    3: MAGAZZINO
                    4: AUTISTI-->
                    <?php
                    require_once "services.php";
                    if (!isset($_SESSION["service"]) || $_SESSION["service"] == 0) {
                        die();
                    }
                    switch ($_SESSION["service"]) {
                        case 1:
                            if ((!isset($_SESSION["draft"]["popup"]) && isset($_SESSION["draft"]["noerror"])) || isset($_SESSION["draft"]["candelete"]))
                                unset($_SESSION["draft"]);

                            if ((!isset($_SESSION["draft"]["noerror"]) && isset($_SESSION["draft"]["error"]["code"])) || isset($_SESSION["draft"]["error"]["code"])) { ?>
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    Si è verificato un errore durante il salvataggio dell'incarico: (<?php echo $_SESSION["draft"]["error"]["code"]; ?>) <?php echo $_SESSION["draft"]["error"]["message"]; ?>.
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php }
                            if (isset($_SESSION["success"])) { ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <?php echo $_SESSION["success"]; ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php unset($_SESSION["success"]);
                            }

                            insert();
                            ?>                        
                            <script src="../js/insert_func.js"></script>
                            <?php
                            if (isset($_SESSION["draft"]["noerror"])) {
                                $_SESSION["service"] = $_SESSION["backService"];
                            ?>
                                <script>
                                    logFromFront(<?php echo $_SESSION["draft"]["id"]; ?>);
                                </script>

                            <?php
                            }

                            ?>
                            <script>
                                document.getElementById("addPack").addEventListener("click", loadPack);
                                document.getElementById("generaID").addEventListener("click", () => {
                                    generateID(document.getElementById("segnacollo"));
                                });
                                document.getElementById("generaIDDDT").addEventListener("click", () => {
                                    generateID(document.getElementById("ddt"));
                                });
                                document.getElementById("pack-form").addEventListener("keydown", ev => {
                                    if (ev.keyCode == 13) {
                                        ev.preventDefault();
                                        loadPack();
                                        document.activeElement.blur();
                                    }

                                });

                                window.addEventListener("DOMContentLoaded", async () => {
                                    await loadCostumers("clientiMitt", "clientiDest");
                                    <?php
                                    if (isset($_SESSION["draft"]["Mitt"])) { ?>
                                        $("#clientiMitt option[value='<?php echo $_SESSION["draft"]["Mitt"]; ?>']").attr("selected", "selected");
                                        fillCustomer(document.getElementById("clientiMitt"), "Mitt");
                                    <?php }

                                    if (isset($_SESSION["draft"]["Dest"])) { ?>
                                        $("#clientiDest option[value='<?php echo $_SESSION["draft"]["Dest"]; ?>']").attr("selected", "selected");
                                        fillCustomer(document.getElementById("clientiDest"), "Dest");
                                    <?php
                                        unset($_SESSION["draft"]["popup"]);
                                    }
                                    ?>

                                    if (document.getElementById("oldID") != null)
                                        await getImg();
                                    else
                                        imageAdderCreator(0);
                                });
                                window.addEventListener("DOMContentLoaded", () => {
                                    if (document.getElementById("interno").hasAttribute("checked"))
                                        disableRif();

                                    disableContr();

                                    setVisibility("Mitt", true);
                                    setVisibility("Dest", true);
                                    document.getElementById("packs").remove();
                                    if (localStorage.refresh) {
                                        localStorage.removeItem("refresh");
                                        location.reload();
                                    }
                                });
                                document.getElementById("clientiDest").addEventListener("change", () => {
                                    fillCustomer(document.getElementById("clientiDest"), "Dest");
                                });
                                document.getElementById("clientiMitt").addEventListener("change", () => {
                                    fillCustomer(document.getElementById("clientiMitt"), "Mitt");
                                });
                                document.getElementById("peso").addEventListener("keydown", (e) => {
                                    if (e.key == ".") {
                                        e.preventDefault();
                                    }
                                });
                                document.getElementById("colMitt").addEventListener("keydown", e => {
                                    if (e.key == "Enter") {
                                        e.preventDefault();
                                        checkForm();
                                    }
                                });
                                document.getElementById("colDest").addEventListener("keydown", e => {
                                    if (e.key == "Enter") {
                                        e.preventDefault();
                                        checkForm();
                                    }
                                });
                                document.getElementById("interno").addEventListener("change", function() {
                                    if (this.checked) {
                                        disableRif();
                                    } else {
                                        document.getElementById("ddtN").removeAttribute("disabled");
                                        document.getElementById("ddtD").removeAttribute("disabled");
                                    }
                                });
                                document.getElementById("contrassegno").addEventListener("change", disableContr);
                            </script>
                        <?php
                            break;
                        case 2:
                        ?>
                            <script src="../js/dashboard_find.js"></script>
                            <?php search(); ?>
                            <script>
                                window.addEventListener("DOMContentLoaded", async () => {
                                    await loadCostumers("clienti", null);
                                });
                                document.getElementById("clienti").addEventListener("change", () => {
                                    let clienti = document.getElementById("clienti");
                                    fillCustomer(clienti, "");
                                    if (clienti.value != "")
                                        setVisibility("", true);
                                    else
                                        setVisibility("", false);

                                });

                                var $list = $("#id-form").find("input");

                                $list.each(function() {
                                    $(this).focus(function() {
                                        $("#searchbtn").attr("onclick", "getData(document.getElementById('id-form'))");
                                    });
                                });

                                document.getElementById("id-form").addEventListener("submit", async (e) => {
                                    e.preventDefault();
                                    await getData(document.getElementById("id-form"));
                                });

                                var $list = $("#ext").find("input");

                                $list.each(function() {
                                    $(this).focus(function() {
                                        $("#searchbtn").attr("onclick", "getData(document.getElementById('ext'))");
                                    });
                                });

                                document.getElementById("ext").addEventListener("submit", async (e) => {
                                    e.preventDefault();
                                    await getData(document.getElementById("ext"));
                                });

                                var $list = $("#customer-form").find("input");

                                $list.each(function() {
                                    $(this).focus(function() {
                                        $("#searchbtn").attr("onclick", "getData(document.getElementById('customer-form'))");
                                    });
                                });
                                $list = $("#customer-form").find("select").focus(function() {
                                    $("#searchbtn").attr("onclick", "getData(document.getElementById('customer-form'))");
                                });

                                document.getElementById("customer-form").addEventListener("submit", async (e) => {
                                    e.preventDefault();
                                    await getData(document.getElementById("customer-form"));
                                });

                                var $list = $("#packs").find("input");

                                $list.each(function() {
                                    $(this).focus(function() {
                                        $("#searchbtn").attr("onclick", "getData(document.getElementById('packs'))");
                                    });
                                });

                                document.getElementById("packs").addEventListener("submit", async (e) => {
                                    e.preventDefault();
                                    await getData(document.getElementById("packs"));
                                });
                            </script>

                    <?php
                            break;
                        case 3: ?>
                            <!-- caricamento dati-->
                            <script src="../js/magazzino.js"></script>
                            <div id="alert-space"></div>
                            <div id="table-container"></div>
                            <script>
                                getMagazzino("button");
                            </script>
                            <?php
                            break;
                        case 4:
                            ?>
                            <script src="../js/autisti.js"></script>
                            <?php
                            autisti();
                            ?>
                            <script>
                                document.getElementById("autisti-form").addEventListener("submit",async (e)=>{
                                    e.preventDefault();
                                    let b=true;
                                    $inputs=$("#autisti-form").find("input");
                                    $inputs.each(function(){
                                        if(this.value=="" && this.id!="draft"){
                                            this.classList.add("is-invalid");
                                            b=false;
                                        }else if(this.classList.contains("is-invalid")){
                                            this.classList.remove("is-invalid");
                                        }
                                    });

                                    if(b){
                                        let jsonobj={"Nome":document.getElementById("nome").value,
                                                "Cognome":document.getElementById("cognome").value,
                                                "Email":document.getElementById("email").value,
                                                "Tel":document.getElementById("tel").value};
                                        if(document.getElementById("draft")!=null){
                                            jsonobj["draft"]=document.getElementById("draft").getAttribute("userid");
                                        }
                                        const response=await fetch("../php/validateAut.php",{
                                            method: "POST",
                                            headers:{
                                                "Content-Type":"text/plain",
                                            },
                                            body: JSON.stringify(jsonobj),
                                        });

                                        const data=await response.json();
                                        if(data.error=="no"){
                                            populateFields("","","","");
                                            let keyword="";
                                            if(document.getElementById("draft")!=null){
                                                keyword="modificato";
                                            }else{
                                                keyword="creato";
                                            }

                                            if(document.getElementById("draft")!=null)
                                                document.getElementById("autisti-form").removeChild(document.getElementById("draft"));

                                            createAlert("success","Autista "+keyword+" con successo",document.getElementById("alert-space"));
                                            document.getElementById("draft-space").innerHTML="";
                                            loadAutisti();
                                            let inputs=$("#autisti-form").find("input");
                                            inputs.each(function(){
                                                this.blur();
                                            })
                                        }else{
                                            createAlert("warning","Si è verificato un errore imprevisto e non è stato possibibile salvere l'autista",document.getElementById("alert-space"));
                                        }

                                    }
                                });

                                window.addEventListener("DOMContentLoaded",async ()=>{loadAutisti()});
                            </script>
                            <?php
                            break;
                        case 5:
                            ?>
                            <script src="../js/magazzino.js"></script>
                            <div id="alert-space"></div>
                            <div class="row">
                                <div class="col-3" id="div-tab">
                                    <table id="bord-aut-tab"></table>
                                </div>
                                <div class="col-auto overflow-auto mb-3" id="selectedMissions"></div>
                                <hr>
                                <div class="col-auto overflow-auto" id="table-container"></div>
                            </div>
                            <form action="../php/saveBordero.php" method="post" id="form-bord">
                                <input type="hidden" name="autid" id="autid" value="">
                                <input type="hidden" name="missions" id="missions" value="">
                                <select name="targa" id="targa" class="form-select my-3" style="width:auto;">
                                    <option value=""></option>
                                </select>
                                <div class="row mx-auto" style="width:200px;">
                                    <input type="submit" value="Crea" class="btn btn-primary mt-3" id="submitBord">
                                </div>
                            </form>
                            <script>
                                getMagazzino("checkbox");
                                window.addEventListener("DOMContentLoaded",async ()=>{
                                    let missionCont=document.getElementById("table-container");//tabella delle missioni
                                    missionCont.style.display="block";
                                    missionCont.style.maxHeight="500px";
                                    missionCont.style.maxWidth="100%";
                                    missionCont.style.fontSize="13px";


                                    const response=await fetch("../php/getAutisti.php?detail=false");
                                    const data=await response.json();

                                    if(data.error=="false"){
                                        let autisti=data.autisti;
                                        let table=document.getElementById("bord-aut-tab");
                                        table.style.display="block";
                                        table.style.height="300px";
                                        table.style.width="210px";
                                        table.classList.add("table","table-striped","table-hover","align-middle","mt-3","fs-6","overflow-auto");

                                        let line=table.insertRow();
                                        line.classList.add("fw-bold","text-center","sticky-top","bg-light");

                                        let cell1=line.insertCell(0);
                                        cell1.appendChild(document.createTextNode("COGNOME"));

                                        let cell2=line.insertCell(1);
                                        cell2.appendChild(document.createTextNode("NOME"));

                                        for(let k in autisti){
                                            let line=table.insertRow();
                                            line.classList.add("text-center");

                                            let cell1=line.insertCell(0);
                                            line.id=autisti[k]["id"];
                                            cell1.appendChild(document.createTextNode(autisti[k]["cognome"]));

                                            let cell2=line.insertCell(1);
                                            cell2.appendChild(document.createTextNode(autisti[k]["nome"]));

                                            line.addEventListener("click",function(){
                                                //controllo di altri eventuali autisti selezionati
                                                //scorrere la tabella per cercare background color
                                                for(let i=0;i<table.rows.length;i++){
                                                    if(table.rows[i].style.backgroundColor!=""){
                                                        table.rows[i].style.backgroundColor="";
                                                    }
                                                }

                                                document.getElementById("autid").value=autisti[k]["id"];
                                                this.style.backgroundColor="yellow";
                                            });
                                        }
                                        if(autisti.length>0){
                                            let text=document.createElement("div");
                                            text.innerHTML="Clicca sulla riga corrispondente per selezionare l'autista";
                                            text.classList.add("text-form","mb-3");
                                            text.style.fontSize="13px";
                                            document.getElementById("div-tab").appendChild(text);
                                        }
                                    }else{
                                        createAlert("warning","Si è verificato un errore imprevisto e non è stato possibibile caricare gli autisti",document.getElementById("alert-space"));
                                    }

                                    const response2=await fetch("../php/getVeic.php");
                                    const data2=await response2.json();

                                    if(data2.error=="false"){
                                        let sel=document.getElementById("targa");
                                        let opt=null;
                                        for(let k in data2.veicoli){
                                            opt=document.createElement("option");
                                            opt.text=(data2.veicoli[k].targa+" - "+data2.veicoli[k].nome).toUpperCase();
                                            opt.value=data2.veicoli[k].targa;
                                            sel.add(opt);
                                        }
                                    }else{
                                        createAlert("warning","Si è verificato un errore imprevisto e non è stato possibibile caricare i veicoli",document.getElementById("alert-space"));
                                    }
                                });

                                document.getElementById("form-bord").addEventListener("submit",async function(e){
                                    e.preventDefault();
                                    if(document.getElementById("missions").value=="" || document.getElementById("autid").value=="" || document.getElementById("targa").value==""){
                                        createAlert("warning","Compila correttamente il form",document.getElementById("alert-space"),false);
                                    }else{
                                        const response=await fetch("../php/saveBordero.php",{
                                            method: "POST",
                                            headers:{
                                                "Content-Type":"text/plain",
                                            },
                                            body: JSON.stringify({"autid":document.getElementById("autid").value,
                                                "missions":document.getElementById("missions").value,
                                                "targa":document.getElementById("targa").value}),
                                        });

                                        const data=await response.json();
                                        let type="success";
                                        let message="Borderò salvato correttamente!";
                                        
                                        if(data.error==true){
                                            type="warning";
                                            message="Si è verificato un errore imprevisto e non è stato possibibile salvare il borderò";
                                        }
                                    
                                        createAlert(type,message,document.getElementById("alert-space"),!(type=="warning"));
                                    }
                                });

                                function createAlert(type, text, space,autodel){
                                    let alert=document.createElement("div");
                                    alert.classList.add("alert", "alert-"+type, "alert-dismissible", "fade", "show");
                                    alert.role="alert";
                                    alert.appendChild(document.createTextNode(text));
                                    let btn=document.createElement("button");
                                    btn.type="button";
                                    btn.classList.add("btn-close");
                                    btn.setAttribute("data-bs-dismiss", "alert");
                                    btn.setAttribute("aria-label", "Close");
                                    alert.appendChild(btn);
                                    space.appendChild(alert);

                                    if((autodel!=undefined && autodel) || autodel==undefined){
                                        setTimeout(()=>{
                                            space.removeChild(alert);
                                        },2000);
                                    }
                                }
                            </script>
                            <?php
                            break;
                        case 6:
                            ?>
                            <script src="../js/veicoli.js"></script>
                            <?php
                            veicoli();
                            ?>
                            <script>
                                document.getElementById("veic-form").addEventListener("submit",async (e)=>{
                                    e.preventDefault();
                                    let b=true;
                                    $inputs=$("#veic-form").find("input");
                                    $inputs.each(function(){
                                        if(this.value=="" && this.id!="draft"){
                                            this.classList.add("is-invalid");
                                            b=false;
                                        }else if(this.classList.contains("is-invalid")){
                                            this.classList.remove("is-invalid");
                                        }
                                    });

                                    if(b){
                                        let jsonobj={"Targa":document.getElementById("targa").value,
                                                "Nome":document.getElementById("nome").value};
                                        if(document.getElementById("draft")!=null){
                                            jsonobj["draft"]=document.getElementById("draft").getAttribute("veicid");
                                        }
                                        const response=await fetch("../php/validateVeic.php",{
                                            method: "POST",
                                            headers:{
                                                "Content-Type":"text/plain",
                                            },
                                            body: JSON.stringify(jsonobj),
                                        });

                                        const data=await response.json();
                                        if(data.error=="no"){
                                            document.getElementById("targa").value="";
                                            document.getElementById("nome").value="";
                                            let keyword="";
                                            if(document.getElementById("draft")!=null){
                                                keyword="modificato";
                                            }else{
                                                keyword="creato";
                                            }

                                            if(document.getElementById("draft")!=null)
                                                document.getElementById("veic-form").removeChild(document.getElementById("draft"));

                                            createAlert("success","Veicolo "+keyword+" con successo",document.getElementById("alert-space"));
                                            document.getElementById("draft-space").innerHTML="";
                                            loadVeicoli();
                                            let inputs=$("#veic-form").find("input");
                                            inputs.each(function(){
                                                this.blur();
                                            })
                                        }else{
                                            createAlert("warning","Si è verificato un errore imprevisto e non è stato possibibile salvere il veicolo",document.getElementById("alert-space"));
                                        }

                                    }
                                });

                                function createAlert(type, text, space){
                                    let alert=document.createElement("div");
                                    alert.classList.add("alert", "alert-"+type, "alert-dismissible", "fade", "show");
                                    alert.role="alert";
                                    alert.appendChild(document.createTextNode(text));
                                    let btn=document.createElement("button");
                                    btn.type="button";
                                    btn.classList.add("btn-close");
                                    btn.setAttribute("data-bs-dismiss", "alert");
                                    btn.setAttribute("aria-label", "Close");
                                    alert.appendChild(btn);
                                    space.appendChild(alert);

                                    setTimeout(()=>{
                                        space.removeChild(alert);
                                    },2000);
                                }

                                window.addEventListener("DOMContentLoaded",async ()=>{loadVeicoli()});
                            </script>
                            <?php
                            break;
                    }
                    ?>
                </div>
                <div class="col-1 mt-2">
                    <a href="../php/setService.php?service=0"><button type="button" class="btn btn-danger">X</button></a>
                </div>
            </div>
            <?php
            if (isset($_SESSION["service"])) {
                unset($_SESSION["service"]);
            }

            if (isset($_SESSION["draft"]["noerror"]))
                $_SESSION["service"] = $_SESSION["backService"];

            if (isset($_SESSION["draft"]["candelete"]) && isset($_SESSION["draft"]["popup"])) {
                unset($_SESSION["draft"]);
                echo "<script>setTimeout(()=>{
                    window.close();
                },1200);</script>";
            }

            if (isset($_SESSION["draft"]["candelete"])) {
                unset($_SESSION["draft"]);
            }
            ?>
    </body>

</html>