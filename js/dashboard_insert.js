var state="none";
var row=-1;

async function removeAlertPack(){
    document.getElementById("tableAlert").style.display="none";
}

function loadPack(){
    //NON E OBBLIGATORIO IL CAMPO 
    let id=document.getElementById("segnacollo");
    let bancale=document.getElementById("bancale");
    let peso=document.getElementById("peso");
    let dimensione=document.getElementById("dimensioni");
    let descrizione=document.getElementById("descrizione");
    let duplicateID=document.getElementById("duplicateID-alert");
    let alert=document.getElementById("alert");
    
    if(id.value=="" || peso.value==""){
        alert.style.display="block";
    }else{
        alert.style.display="none";
        
        let table=document.getElementById("colliList");
        
        // controllo se è gia presente il segnacollo
        for(let k=1;k<table.rows.length;k++){
            if((table.rows[k].cells[0].innerHTML==id.value) && state=="none"){
                duplicateID.style.display="block";
                return;
            }
        }
        
        duplicateID.style.display="none";
        let um=document.getElementById("um");
        
        
        let tbody=table.getElementsByTagName("tbody")[0];
        let line=tbody.insertRow(row);
        
        if(state=="updating"){
            table.deleteRow(row);
            state="none";
        }
        
        let cell1=line.insertCell(0);
        let cell2=line.insertCell(1);
        let cell3=line.insertCell(2);
        let cell4=line.insertCell(3);
        let cell5=line.insertCell(4);
        let cell6=line.insertCell(5);
        let cell7=line.insertCell(6);
        
        
        let literalUM="";
        
        switch(um.value){
            case "1":
                literalUM="Kg";
            break;
            case "2":
                literalUM="Q.li";
            break;
        }
                
        // let inCell1=document.createElement("input");
        // inCell1.name="segnacollo[]";
        // inCell1.type="text";
        // inCell1.setAttribute("readonly","");
        // inCell1.style.backgroundColor="transparent";
        // inCell1.style.borderStyle="inset";
        // inCell1.style.borderWidth="0";
        // inCell1.value=segnacollo.value.trim();

        cell1.appendChild(document.createTextNode(segnacollo.value.trim()));
        if(segnacollo.value.trim().length>14)
        cell1.className="text-break"; 
        
        cell2.className="text-break";
        cell2.appendChild(document.createTextNode(peso.value+" "+literalUM));
        cell3.appendChild(document.createTextNode(dimensione.value.trim()));
        cell3.className="text-break";

        cell4.className="text-break";
        cell4.appendChild(document.createTextNode(descrizione.value.trim()));
        
        let edit=document.createElement("button");
        edit.innerHTML='<img src="../icons/modifica.png" width="10px" height="10px">&nbsp;Modifica';
        let rowId=-1;
        if(row!=-1)
        rowId=row;
        else
        rowId=table.rows.length-1;
        
        
        let tipo=document.createElement("img");
        tipo.width="30";
        if(bancale.checked){
            tipo.src="../icons/bancale.png";
            tipo.toSet="true";
        }else{
            tipo.src="../icons/box.png";
            tipo.toSet="false";
        }
        tipo.id="packTipo";
        cell5.appendChild(tipo);
        
        edit.setAttribute("onclick","event.preventDefault(); updatePack("+String(rowId)+")");
        edit.classList.add("btn");
        edit.classList.add("btn-info");
        edit.classList.add("btn-sm");
        cell6.appendChild(edit);
        
        let del=document.createElement("button");
        del.innerHTML='<img src="../icons/elimina.png" width="11px" height="12px">&nbsp;Elimina';
        del.setAttribute("onclick","event.preventDefault(); deletePack("+String(rowId)+")");
        del.classList.add("btn");
        del.classList.add("btn-warning");
        del.classList.add("btn-sm");
        cell7.appendChild(del);
        
        row=-1;
        
        id.value="";
        dimensione.value="";
        peso.value="";
        descrizione.value="";
        bancale.checked=false;
        document.getElementById("addPack").innerHTML="Aggiungi";
        removeAlertPack();
    }
}

function updatePack(line){
    document.getElementById("alert").style.display="none";
    document.getElementById("duplicateID-alert").style.display="none";
    let riga=document.getElementById("colliList").rows[line];
    let id=document.getElementById("segnacollo");
    let bancale=document.getElementById("bancale");
    let peso=document.getElementById("peso");
    let dimensione=document.getElementById("dimensioni");
    let umF=document.getElementById("um");
    let descrizione=document.getElementById("descrizione");
    let um=riga.cells[1].innerHTML.split(" ");


    id.value=riga.cells[0].innerHTML;
    peso.value=um[0];
    umF.value=(um[1]=="Kg")?1:2;
    dimensione.value=riga.cells[2].innerHTML;
    descrizione.value=riga.cells[3].innerHTML;
    bancale.checked=riga.cells[4].querySelector("#packTipo").toSet=="true"?true:false;
    console.log(riga.cells[4].querySelector("#packTipo").toSet);

    row=line;
    state="updating";
    document.getElementById("addPack").innerHTML="Aggiorna";
}

function deletePack(line){
    let table=document.getElementById("colliList");
    table.deleteRow(line);

    let list=table.rows;

    //ricrea i pulsanti
    for(let k=1;k<list.length;k++){
        list[k].deleteCell(6);
        list[k].deleteCell(5);
        let editCell=list[k].insertCell(5);
        let delCell=list[k].insertCell(6);

        let edit=document.createElement("button");
        edit.innerHTML='<img src="../icons/modifica.png" width="10px" height="10px">&nbsp;Modifica';
        edit.setAttribute("onclick","event.preventDefault(); updatePack("+k+")");
        edit.classList.add("btn");
        edit.classList.add("btn-info");
        edit.classList.add("btn-sm");
        editCell.appendChild(edit);

        let del=document.createElement("button");
        del.innerHTML='<img src="../icons/elimina.png" width="11px" height="12px">&nbsp;Elimina';
        del.setAttribute("onclick","event.preventDefault(); deletePack("+k+")");
        del.classList.add("btn");
        del.classList.add("btn-warning");
        del.classList.add("btn-sm");
        delCell.appendChild(del);

    }
}

function generateID(target){
    let id=Math.round(Math.random()*(9999999999999-1000000000000)+9999999999999);
    target.value=id;

    if(target===document.getElementById("ddt")){
        target.classList.remove("is-invalid");
    }
}

async function loadCostumers(){
    //estrazione 
    if(document.getElementById("clientiMitt").value==""){
    }
    const response=await fetch("../php/getCustomers.php");

    const data=await response.json();       

    if(data.error!="true"){
        localStorage.clienti=JSON.stringify(data);
        let selectMitt=document.getElementById("clientiMitt");
        let selectDest=document.getElementById("clientiDest");
        for(let k in data.clienti){
            let opt=document.createElement("option");
                opt.text=data.clienti[k]["ragioneSociale"];
                opt.value=data.clienti[k]["id"];
                
                let opt2=document.createElement("option");
                opt2.text=data.clienti[k]["ragioneSociale"];
                opt2.value=data.clienti[k]["id"];
                
                selectMitt.appendChild(opt);
                selectDest.appendChild(opt2);
        }
    }else{
        alert("!! IMPOSSIBILE CARICARE I CLIENTI !!");
        localStorage.refresh=true;
        window.location="../php/setService.php?service=0";
    }
}

async function setVisibility(type,readonly){
    if(readonly){
        document.getElementById("RagSoc"+type).setAttribute("readonly","");
        document.getElementById("Indirizzo"+type).setAttribute("readonly","");
        document.getElementById("Citta"+type).setAttribute("readonly","");
        document.getElementById("Prov"+type).setAttribute("readonly","");
        document.getElementById("cap"+type).setAttribute("readonly","");
        document.getElementById("Cell"+type).setAttribute("readonly","");
    }else{
        document.getElementById("RagSoc"+type).removeAttribute("readonly");
        document.getElementById("Indirizzo"+type).removeAttribute("readonly");
        document.getElementById("Citta"+type).removeAttribute("readonly");
        document.getElementById("Prov"+type).removeAttribute("readonly");
        document.getElementById("cap"+type).removeAttribute("readonly");
        document.getElementById("Cell"+type).removeAttribute("readonly");
    }

}

async function fillCustomer(target,type){
    let clienti=JSON.parse(localStorage.clienti);
    if(target.value=="")
        setVisibility(type,true);
    else
        setVisibility(type,false);

    if(target.value!="" && target.value!=0){
        for(let k in clienti["clienti"]){
            
            if(clienti["clienti"][k]["id"]==target.value){
                fillField(type,clienti["clienti"][k]["ragioneSociale"],clienti["clienti"][k]["indirizzo"],
                clienti["clienti"][k]["citta"],clienti["clienti"][k]["prov"],clienti["clienti"][k]["cap"],clienti["clienti"][k]["cellulare"]);
            }
            
        }
        
        localStorage.lastCustomer=target.value;
    }else{
        fillField(type);
    }
}

async function fillField(type,ragioneSociale="",indirizzo="",cittaF="",provF="",capF="",cellF=""){
    document.getElementById("RagSoc"+type).value=ragioneSociale;
    document.getElementById("Indirizzo"+type).value=indirizzo;
    document.getElementById("Citta"+type).value=cittaF;
    document.getElementById("Prov"+type).value=provF;
    document.getElementById("cap"+type).value=capF;
    document.getElementById("Cell"+type).value=cellF;
}

function checkForm(){
    //controllo campi
    let id=document.getElementById("ddt");
    if(id.value.trim()==""){
        id.classList.add("is-invalid");
        return;
    }else{
        id.classList.remove("is-invalid");
    }

    let ddtN=document.getElementById("ddtN");
    if(!ddtN.hasAttribute("disabled")){
        if(ddtN.value.trim()==""){
            ddtN.classList.add("is-invalid");
            return;
        }else{
            ddtN.classList.remove("is-invalid");
        }
    }else{
    }

    let ddtD=document.getElementById("ddtD");
    let oggi=new Date(Date.now());
    oggi.setHours(0,0,0,0);
    if(!ddtD.hasAttribute("disabled")){
        let dataDdtD=new Date(ddtD.value);
        dataDdtD.setHours(0,0,0,0);
    
        if(ddtD.value==""){
            ddtD.classList.add("is-invalid");
            return;
        }else{
            ddtD.classList.remove("is-invalid");
        }
    
        if(dataDdtD>oggi){
            ddtD.classList.add("is-invalid");
            return;
        }else{
            ddtD.classList.remove("is-invalid");
        }
    }

    let mittAlert=document.getElementById("Mitt-alert");
    if(!checkField("Mitt")){
        mittAlert.style.display="block";
        return;
    }else{
        mittAlert.style.display="none";
    }

    let destAlert=document.getElementById("Dest-alert");
    if(!checkField("Dest")){
        destAlert.style.display="block";
        return;
    }else{
        destAlert.style.display="none";
    }

    let tipo=document.getElementById("tipo");
    if(tipo.value==""){
        tipo.classList.add("is-invalid");
        return;
    }else{
        tipo.classList.remove("is-invalid");
    }

    let dataCons=document.getElementById("dataConsegna");
    let consegna=new Date(dataCons.value);
    consegna.setHours(0,0,0,0);

    if(dataCons.value==""){
        dataCons.classList.add("is-invalid");
        return;
    }else{
        dataCons.classList.remove("is-invalid");
    }

    let dateAlert=document.getElementById("invaliDate-alert");
    if(consegna<oggi){
        dateAlert.style.display="block";
        return;
    }else{
        dateAlert.style.display="none";
    }

    let table=document.getElementById("colliList");
    let tableAlert=document.getElementById("tableAlert");
    if((table.rows.length-1)==0){
        tableAlert.style.display="block";
        return;
    }else{
        tableAlert.style.display="none";
    }

    let tbody=document.getElementsByTagName("tbody")[0].rows;
    let arr={};
    for(let k=0;k<tbody.length;k++){
        arr[tbody[k].cells[0].innerHTML]={
        bancale:tbody[k].cells[4].querySelector("#packTipo").toSet,
        peso:tbody[k].cells[1].innerHTML,
        descrizione:tbody[k].cells[3].innerHTML,
        dimensioni:tbody[k].cells[2].innerHTML};
    }
    
    let input=document.createElement("input");
    input.type="hidden";
    input.name="packs";
    input.id="packs";
    input.value=JSON.stringify(arr);
    document.getElementById("main-form").appendChild(input);


    let form=document.getElementById("main-form");
    form.submit();
}

function checkField(type){
    let ragsoc=document.getElementById("RagSoc"+type).value;
    let ind=document.getElementById("Indirizzo"+type).value;
    let citta=document.getElementById("Citta"+type).value;
    let prov=document.getElementById("Prov"+type).value;
    let cap=document.getElementById("cap"+type).value;
    let cell=document.getElementById("Cell"+type).value;

    ragsoc=ragsoc.trim();
    ind=ind.trim();
    citta=citta.trim();
    prov=prov.trim();
    cap=cap.trim();
    cell=cell.trim();

    if(ragsoc!="" && ind!="" && citta!="" && prov!="" && cap!="" && cell!="")
        return true;
    else
        return false;
}

function disableRif(){
    let ddtn=document.getElementById("ddtN");
    let ddtd=document.getElementById("ddtD");
    ddtn.value="";
    ddtd.value="0000-00-00";
    ddtn.setAttribute("disabled","");
    ddtd.setAttribute("disabled","");
    ddtn.classList.remove("is-invalid");
    ddtd.classList.remove("is-invalid");
}