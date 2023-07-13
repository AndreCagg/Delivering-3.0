<?php
   function insert(){
?>
    <form action="../php/validateInsert.php" method="post" id="main-form">
    <div class="row">
        <div class="col-auto me-2">
            ID&nbsp;<input type="text" name="id" id="ddt" size="14" class="form-control" style="width:170px;height:35px;" value="<?php echo isset($_SESSION["draft"]["id"])?$_SESSION["draft"]["id"]:"" ?>"><br>
            <button type="button" class="btn btn-success btn-sm mb-3" id="generaIDDDT"><b>GENERA</b></button>
        </div>
        <div class="col-auto">
            rif. DDT n. <input type="text" name="ddtN" id="ddtN" class="form-control" style="width:170px;height:35px;" value="<?php echo isset($_SESSION["draft"]["ddtN"])?$_SESSION["draft"]["ddtN"]:"" ?>"><br>
            del &nbsp;<input type="date" name="ddtD" id="ddtD" class="form-control" style="width:170px;height:35px;" value="<?php echo isset($_SESSION["draft"]["ddtD"])?$_SESSION["draft"]["ddtD"]:"" ?>">
        </div>
        <div class="col-auto mt-3">
            <div class="form-check">
                <input class="form-check-input form-check-input-xl" type="checkbox" name="interno" id="interno">
                <label class="form-check-label" for="interno">
                Interna
                </label>
            </div>
        </div>
        <div class="col-auto mt-3">
            <div class="form-check">
                <input class="form-check-input form-check-input-xl" type="checkbox" name="riserva" id="riserva">
                <label class="form-check-label" for="riserva">
                Riserva
                </label>
            </div>
        </div>
        <script>
            <?php
                if(isset($_SESSION["draft"]["riserva"]) && $_SESSION["draft"]["riserva"]==1){
                    ?>
                    document.getElementById("riserva").setAttribute("checked","checked");
                    <?php
                }
                
                if(isset($_SESSION["draft"]["interno"]) && $_SESSION["draft"]["interno"]==1){
                    ?>
                    document.getElementById("interno").setAttribute("checked","checked");
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
                <select name="clientiDest" id="clientiDest" class="form-select" style="width:250px;" value="<?php echo isset($_SESSION["draft"]["clientiDest"])?$_SESSION["draft"]["clientiDest"]:"" ?>">
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
            <input type="number" name="Epal" id="Epal" class="form-control my-1" min="0" max="1000" value="0" style="width:80px;margin-right:20px;" value="<?php echo isset($_SESSION["draft"]["epal"])?$_SESSION["draft"]["epal"]:"" ?>">&nbsp; 
            <div class="label-form" for="tipo">Tipologia</div>
            &nbsp; 
            <select name="tipo" id="tipo" class="form-select" style="width:200px;margin-right:20px;" value="<?php echo isset($_SESSION["draft"]["tipo"])?$_SESSION["draft"]["tipo"]:"" ?>">
                <option value=""></option>
                <option value="1">SPEDIZIONE</option>
                <option value="2">RITIRO</option>
            </select>
            &nbsp;
            <script>
                <?php
                if(isset($_SESSION["draft"]["tipo"])){
                    ?>
                    $("#tipo option[value="+<?php echo $_SESSION["draft"]["tipo"];?>+"]").attr("selected","selected");
                    <?php
                }
                ?>
            </script>
            <div class="label-form" for="dataConsegna">Data consegna</div>
            &nbsp;
            <input type="date" name="dataConsegna" id="dataConsegna" class="form-control my-1" min="0" max="1000" style="width:150px;" value="<?php echo isset($_SESSION["draft"]["dataConsegna"])?$_SESSION["draft"]["dataConsegna"]:"" ?>">&nbsp;
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
            <h5 class="fw-normal">
            Colli/Bancali</h3>
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
            <script>
                let packs=<?php echo $_SESSION["draft"]["packs"];?>;
                for(let k in packs){
                    document.getElementById("segnacollo").value=k;
                    document.getElementById("bancale").checked=packs[k]["bancale"]=="true"?true:false;
                    let peso=packs[k]["peso"].split(" ");
                    document.getElementById("peso").value=peso[0];
                    document.getElementById("um").value=peso[1]=="Kg"?1:2;
                    document.getElementById("descrizione").value=packs[k]["descrizione"];
                    document.getElementById("dimensioni").value=packs[k]["dimensioni"];
                    loadPack();
                }
                
                document.getElementById("segnacollo").value="";
                document.getElementById("bancale").checked=false;
                document.getElementById("peso").value="";
                document.getElementById("um").value="";
                document.getElementById("descrizione").value="";
                document.getElementById("dimensioni").value="";
            </script>
        </div>
    </div>
    <div class="row justify-content-right mx-1 mt-3">
        <hr style="width:90%; opacity:20%;">
        <h5 class="fw-normal">Note</h5>
        <textarea class="form-control w-25" id="note" name="note" rows="5"><?php echo isset($_SESSION["draft"]["note"])?$_SESSION["draft"]["note"]:"" ?></textarea>
    </div>
    <div class="row m-4 mt-5 justify-content-center">
        <button class="btn btn-danger w-25" type="submit" onclick="event.preventDefault();checkForm();">Inserisci</button>
    </div>
    </form>
<?php 
   }
?>