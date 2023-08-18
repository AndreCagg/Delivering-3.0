<?php
function insert(){
?>
    <form action="../php/validateInsert.php" method="post" id="main-form" enctype="multipart/form-data">
        <?php
        if (isset($_SESSION["draft"]["noerror"])) {
            echo "<input name='oldID' id='oldID' type='hidden' value='" . $_SESSION["draft"]["id"] . "'>";
        }
        ?>
        <div class="row">
            <div class="col-auto me-2">
                ID&nbsp;<input type="text" name="id" id="ddt" size="14" class="form-control" style="width:170px;height:35px;" value="<?php echo isset($_SESSION["draft"]["id"]) ? $_SESSION["draft"]["id"] : "" ?>"><br>
                <button type="button" class="btn btn-success btn-sm mb-3" id="generaIDDDT"><b>GENERA</b></button>
            </div>
            <div class="col-auto">
                rif. DDT n. <input type="text" name="ddtN" id="ddtN" class="form-control" style="width:170px;height:35px;" value="<?php echo isset($_SESSION["draft"]["ddtN"]) ? $_SESSION["draft"]["ddtN"] : "" ?>"><br>
                del &nbsp;<input type="date" name="ddtD" id="ddtD" class="form-control" style="width:170px;height:35px;" value="<?php echo isset($_SESSION["draft"]["ddtD"]) ? $_SESSION["draft"]["ddtD"] : "" ?>">
            </div>
            <div class="col">
                <div class="row">
                    <div class="d-flex col mt-3">
                        <div class="form-check">
                            <input class="form-check-input form-check-input-xl" type="checkbox" name="interno" id="interno">
                            <label class="form-check-label" for="interno">
                                Interna
                            </label>
                        </div>
                        <div class="form-check ms-2">
                            <input class="form-check-input form-check-input-xl" type="checkbox" name="riserva" id="riserva">
                            <label class="form-check-label" for="riserva">
                                Riserva
                            </label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col mt-3">
                        <div class="form-check">
                            <input class="form-check-input form-check-input-xl" type="checkbox" name="contrassegno" id="contrassegno">
                            <label class="form-check-label" for="contrassegno">
                                Contrassegno
                            </label>
                        </div>
                        <div class="col-2">
                            <label class="form-control-label" for="impContrassegno">
                                Importo &euro;
                            </label>
                            <input class="form-control form-control-sm" type="number" step="0.01" name="impContrassegno" id="impContrassegno" min="0">
                        </div>
                    </div>
                </div>
            </div>
            <script>
                <?php
                if (isset($_SESSION["draft"]["riserva"]) && $_SESSION["draft"]["riserva"] == 1) {
                ?>
                    document.getElementById("riserva").setAttribute("checked", "checked");
                <?php
                }

                if (isset($_SESSION["draft"]["interno"]) && $_SESSION["draft"]["interno"] == 1) {
                ?>
                    document.getElementById("interno").setAttribute("checked", "checked");
                <?php
                }

                if (isset($_SESSION["draft"]["contrassegno"]) && $_SESSION["draft"]["contrassegno"] == 1) {
                ?>
                    document.getElementById("contrassegno").setAttribute("checked", "checked");

                    let impContr = document.getElementById("impContrassegno");
                    if (impContr.hasAttribute("disabled"))
                        impContr.removeAttribute("disabled");

                    impContr.value = <?php echo $_SESSION["draft"]["impContr"]; ?>;
                <?php
                }
                ?>
            </script>
        </div>
        <hr style="width:90%;">
        <div class="row mt-3">
            <br>
            <br>
            <div class="col-4 me-5" id="colMitt">
                <fieldset class="border rounded-3 p-3" id="field-set">
                    <legend class="float-none w-auto px-3">Mittente</legend>
                    Seleziona
                    <select name="clientiMitt" id="clientiMitt" class="form-select" style="width:250px;">
                        <option value=""></option>
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
                <small style="color:red;display:none;" id="Mitt-alert">
                    <img src="../icons/!.png" width="15px" height="16px">
                    Compila correttamente tutti i campi
                </small>
            </div>
            <div class="col-4 ms-5" id="colDest">
                <fieldset class="border rounded-3 p-3" id="field-set">
                    <legend class="float-none w-auto px-3">Destinatario</legend>
                    Seleziona
                    <select name="clientiDest" id="clientiDest" class="form-select" style="width:250px;">
                        <option value=""></option>
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
                <small style="color:red;display:none;" id="Dest-alert">
                    <img src="../icons/!.png" width="15px" height="16px">
                    Compila correttamente tutti i campi
                </small>
            </div>
            <br><br>
            <div class="d-flex align-items-center" style="margin-top:25px;">
                <div class="label-form" for="Epal">Epal</div>
                &nbsp;
                <input type="number" name="Epal" id="Epal" class="form-control my-1" min="0" max="1000" value="0" style="width:80px;margin-right:20px;" value="<?php echo isset($_SESSION["draft"]["epal"]) ? $_SESSION["draft"]["epal"] : "" ?>">&nbsp;
                <div class="label-form" for="tipo">Tipologia</div>
                &nbsp;
                <select name="tipo" id="tipo" class="form-select" style="width:200px;margin-right:20px;">
                    <option value=""></option>
                    <option value="1">SPEDIZIONE</option>
                    <option value="2">RITIRO</option>
                </select>
                &nbsp;
                <script>
                    <?php
                    if (isset($_SESSION["draft"]["tipo"])) {
                    ?>
                        $("#tipo option[value=" + <?php echo $_SESSION["draft"]["tipo"]; ?> + "]").attr("selected", "selected");
                    <?php
                    }

                    if (isset($_SESSION["draft"]["Mitt"])) {
                    ?>
                        $("#clientiMitt option[value=" + <?php echo $_SESSION["draft"]["Mitt"]; ?> + "]").attr("selected", "selected");
                    <?php
                    }

                    if (isset($_SESSION["draft"]["Dest"])) {
                    ?>
                        $("#clientiDest option[value=" + <?php echo $_SESSION["draft"]["Dest"]; ?> + "]").attr("selected", "selected");
                    <?php
                    }
                    ?>
                </script>
                <div class="label-form" for="dataConsegna">Data consegna</div>
                &nbsp;
                <input type="date" name="dataConsegna" id="dataConsegna" class="form-control my-1" min="0" max="1000" style="width:150px;" value="<?php echo isset($_SESSION["draft"]["dataConsegna"]) ? $_SESSION["draft"]["dataConsegna"] : "" ?>">&nbsp;
                <small style="color:red;display:none;" id="invaliDate-alert">
                    <img src="../icons/!.png" width="15px" height="16px">
                    La data di consegna non è valida
                </small>
            </div>
        </div>
        <br>
        <hr style="width:90%;">
        <div class="row flex-nowrap">
            <div class="col-4" id="pack-form">
                <h5 class="fw-normal">Colli/Bancali</h5>
                <div class="label-form" for="segnacollo">Identificativo</div>
                <input type="text" id="segnacollo" class="form-control my-1" style="width:200px;">
                <button type="button" class="btn btn-success btn-sm mb-3" id="generaID"><b>GENERA</b></button>
                <br>
                <div class="form-check-label" for="bancale">Bancale</div>
                <input type="checkbox" id="bancale" class="form-check-input my-1">
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
                    L'ID e il peso sono obbligatori
                </small>
                <small style="color:red;display:none;" id="duplicateID-alert">
                    <img src="../icons/!.png" width="15px" height="16px">
                    L'ID risulta già inserito
                </small>
                <div>
                    <br>
                    <button type="button" class="btn btn-danger" id="addPack">Aggiungi</button>
                </div>
            </div>
            <div class="col-1 p-0" style="width:1px; background-color:#bfc0c1;"></div>
            <div class="col-4 justify-content-end scrollable-tbody mb-5 mx-4">
                <table class="table table-striped table-hover" id="colliList" name="colliList">
                    <thead>
                        <tr>
                            <th>SEGNACOLLO</th>
                            <th>PESO</th>
                            <th>DIMENSIONI</th>
                            <th>DESCRIZIONE</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                    </tbody>
                </table>
                <small style="color:red;display:none;" id="tableAlert">
                    <img src="../icons/!.png" width="15px" height="16px">
                    Ci deve essere almeno un collo/bancale
                </small>
                <?php if (isset($_SESSION["draft"]["packs"])) { ?>
                    <script>
                        let packs = <?php echo $_SESSION["draft"]["packs"]; ?>;
                        // console.log(packs);
                        for (let k in packs) {
                            document.getElementById("segnacollo").value = k;
                            document.getElementById("bancale").checked = packs[k]["bancale"] == "true" ? true : false;
                            let peso = packs[k]["peso"].split(" ");
                            document.getElementById("peso").value = peso[0];
                            document.getElementById("um").value = peso[1] == "Kg" ? 1 : 2;
                            document.getElementById("descrizione").value = packs[k]["descrizione"];
                            document.getElementById("dimensioni").value = packs[k]["dimensioni"];
                            loadPack();
                        }

                        document.getElementById("segnacollo").value = "";
                        document.getElementById("bancale").checked = false;
                        document.getElementById("peso").value = "";
                        document.getElementById("um").value = "";
                        document.getElementById("descrizione").value = "";
                        document.getElementById("dimensioni").value = "";
                    </script>
                <?php } ?>
            </div>
        </div>
        <div class="row justify-content-right mx-1 mt-3">
            <hr style="width:90%; opacity:20%;">
            <h5 class="fw-normal">Note</h5>
            <textarea class="form-control w-25" id="note" name="note" rows="5"><?php echo isset($_SESSION["draft"]["note"]) ? $_SESSION["draft"]["note"] : "" ?></textarea>
        </div>

        <div class="row mx-1 mt-3">
            <hr style="width:90%; opacity:20%;">
            <h4 class="fw-normal fs-3">Allegati</h4>
            <div class="row" id="images">

            </div>
        </div>
        <?php
        if (isset($_SESSION["draft"]["noerror"])) { ?>
            <hr>
            <div class="row">
                <div class="row">
                    <div class="col fs-5 mb-3">Movimenti</div>
                </div>
                <div id="movementList">
                    <?php
                    $movimenti = $_SESSION["draft"]["Movimenti"];
                    $i = 0;
                    foreach ($movimenti as $m) {
                        echo "<div class='row'> <!--movimento-->
                    <div class='col-auto'>";
                        if ($i == 0) {
                            echo "<div class='containerElement'><div id='triangle'></div></div>";
                        }


                        echo "<div><div class='stateLine'></div></div>
                        <div class='containerElement'><div class='pallino'></div></div>
                    </div>
                        
                    <div class='col-auto'><!--testo-->";
                        echo "<div style='top:0;'>" . $m["data"] . "<br>" . $m["stato"] . "</div>";
                        echo "</div>
                </div>";
                        $i++;
                    } ?>

                    <div class="row mb-1"> <!--movimento-->
                        <div class="col-auto">
                            <div class="stateLine"></div>
                            <div class="containerElement">
                                <div class="pallino"></div>
                            </div>
                        </div>

                        <div class="col-auto"><!--testo-->
                            <div>
                                <select name="nuovoStato" class="form-select form-select-sm" id="stateEvent" style="bottom:0;">
                                    <option value="0"></option>
                                    <option value="9">PRONTO PER PARTENZA</option>
                                    <option value="17">PRONTO PER RITIRO</option>
                                    <option value="11">ARRIVATO IN MAGAZZINO</option>
                                    <option value="14">RIENTRATO IN MAGAZZINO</option>
                                    <option value="15">ACCANTONA MERCE</option>
                                    <option value="12">CARICATO</option>
                                    <option value="1">IN CONSEGNA</option>
                                    <option value="2">CONSEGNATO</option>
                                    <option value="16">CONSEGNA FALLITA (specifica in note)</option>
                                    <option value="3">RITIRO EFFETTUATO</option>
                                    <option value="13">RITIRATO DA CORRIERE PRESSO NS SEDE</option>
                                    <option value="4">IN GIACENZA</option>
                                    <option value="5">RIFIUTATO</option>
                                    <option value="6">ASSEGNATO</option>
                                    <option value="7">SMARRITO</option>
                                    <option value="10">RISPEDITO AL MITT</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="row m-4 mt-5 justify-content-center">
            <button class="btn btn-danger w-25" type="submit" onclick="event.preventDefault();checkForm();">
                <?php echo !isset($_SESSION["draft"]["noerror"]) ? "Inserisci" : "Modifica"; ?>
            </button>
        </div>
    </form>
<?php
}

function search(){
?>
    <div class="row" id="ricercAlert" style="display:none;">
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <div id="ricercAlertMessage"></div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
    <div class="row">
        <div class="col-auto">
            <div class="col-auto mb-2">
                <fieldset class="border rounded-3 p-3" id="field-set">
                    <legend class="fs-5 float-none w-auto px-3">ID</legend>
                    <form action="../php/getJob.php" method="get" id="id-form">
                        <input type="text" class="form-control form-control-sm" name="id" id="id" style="min-width:30%;">
                    </form>
                </fieldset>
            </div>
            <div class="col-auto">
                <fieldset class="border rounded-3 p-3" id="field-set">
                    <legend class="fs-5 float-none w-auto px-3">ID collo/bancale</legend>
                    <form action="../php/getJob.php" method="get" id="packs">
                        <input type="text" class="form-control form-control-sm" name="idCollo" id="idCollo" style="min-width:30%;">
                    </form>
                </fieldset>
            </div>
        </div>
        <div class="col">
            <fieldset class="border rounded-3 p-3" id="field-set">
                <legend class="fs-5 float-none w-auto px-3">Riferimenti</legend>
                <form action="../php/getJob.php" method="get" id="ext">
                    <label for="rifddtN" class="form-label">Rif. num</label>
                    <input type="text" class="form-control form-control-sm" name="rifddtN" id="rifddtN"><br>
                    <label for="rifddtN" class="form-label">Data rif.</label>
                    <input type="date" class="form-control form-control-sm" name="rifddtD" id="rifddtD">
                </form>
            </fieldset>
        </div>
        <div class="col-auto">
            <fieldset class="border rounded-3 p-3" id="field-set">
                <legend class="float-none w-auto px-3">Cliente</legend>
                <form action="../php/getJob.php" method="get" id="customer-form">
                    <div class="row">
                        <div class="col">
                            Seleziona
                            <select name="clienti" id="clienti" class="form-select" style="min-width:50%;">
                                <option value=""></option>
                            </select>
                        </div>
                        <div class="col">
                            <div class="label-form" for="Cell">Cellulare</div>
                            <input type="text" name="Cell" id="Cell" class="form-control my-1" style="width:170px;">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-auto">
                            <div class="label-form" for="RagSoc">Ragione sociale</div>
                            <input type="text" name="RagSoc" id="RagSoc" class="form-control my-1" style="width:250px;">
                        </div>
                        <div class="col-auto">
                            <div class="label-form" for="Indirizzo">Indirizzo</div>
                            <input type="text" name="Indirizzo" id="Indirizzo" class="form-control my-1" style="width:250px;">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-auto">
                            <div class="label-form" for="Citta">Citt&agrave;</div>
                            <input type="text" name="citta" id="Citta" class="form-control my-1" style="width:240px;">
                        </div>
                        <div class="col-auto">
                            <div class="label-form" for="Prov">Prov</div>
                            <input type="text" name="Prov" id="Prov" class="form-control my-1" style="width:100px;">
                        </div>
                        <div class="col-auto">
                            <div class="label-form" for="Cap">Cap</div>
                            <input type="number" name="cap" id="cap" class="form-control my-1" style="width:100px;" />
                        </div>
                    </div>
                    <input type="submit" style="display: none;">
                </form>
            </fieldset>
        </div>
    </div>
    <div class="row">
        <button class="btn mt-4" style="background-color:#ffb3b3;" type="button" id="searchbtn"><img src="../icons/cerca.png" width="18" class="me-1 pb-1">Cerca</button>
    </div>
    <br>
    <div class="row">
        <div class="row">
            <hr>
        </div>

        <div class="row">
            <div class="row">
                <div class="col mb-3 fw-bold" id="occourrences"></div>
            </div>
            <table class="table table-striped table-hover align-middle" id="main-tab">
                <tbody id="viewResult">
                </tbody>
            </table>
        </div>
    </div>
<?php
}
function autisti(){
    ?>
<div id="alert-space"></div>
<div id="draft-space" class="d-flex fw-bold"></div>
<form action="../php/validateAut.php" method="post" id="autisti-form">
    <div class="row">
        <div class="col">
            <label for="cognome">Cognome</label>
            <input type="text" class="form-control" name="cognome" id="cognome">
        </div>
        <div class="col">
            <label for="nome">Nome</label>
            <input type="text" class="form-control" name="nome" id="nome">
        </div>
    </div>
    <div class="row">
        <div class="col">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" id="email">
        </div>
        <div class="col">
            <label for="tel">Cellulare</label>
            <input type="text" class="form-control" name="tel" id="tel">
        </div>
    </div>
    <div class="row mx-auto" style="width:200px;">
        <input type="submit" class="mt-5 btn" value="Inserisci" id="saveAutisti" style="background-color:#cc0000;color:white;">
    </div>
</form>
<table id="autisti-tab">

</table>
    <?php
}
?>