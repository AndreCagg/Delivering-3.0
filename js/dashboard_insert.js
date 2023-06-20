var state="none";
var row=-1;
function loadPack(){
    //NON E OBBLIGATORIO IL CAMPO DIMENSIONE
    let id=document.getElementById("segnacollo");
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


        let literalUM="";

        switch(um.value){
            case "1":
                literalUM="Kg";
            break;
            case "2":
                literalUM="Q.li";
            break;
        }

        cell1.appendChild(document.createTextNode(segnacollo.value.trim()));
        cell2.appendChild(document.createTextNode(peso.value+" "+literalUM));
        cell3.appendChild(document.createTextNode(dimensione.value.trim()));
        cell4.className="text-break";
        cell4.appendChild(document.createTextNode(descrizione.value.trim()));
        
        let edit=document.createElement("button");
        edit.innerHTML='<img src="../icons/modifica.png" width="10px" height="10px">&nbsp;Modifica';
        let rowId=-1;
        if(row!=-1)
            rowId=row;
        else
            rowId=table.rows.length-1;

        edit.setAttribute("onclick","event.preventDefault(); updatePack("+String(rowId)+")");
        edit.classList.add("btn");
        edit.classList.add("btn-info");
        edit.classList.add("btn-sm");
        cell5.appendChild(edit);

        let del=document.createElement("button");
        del.innerHTML='<img src="../icons/elimina.png" width="11px" height="12px">&nbsp;Elimina';
        del.setAttribute("onclick","event.preventDefault(); deletePack("+String(rowId)+")");
        del.classList.add("btn");
        del.classList.add("btn-warning");
        del.classList.add("btn-sm");
        cell6.appendChild(del);
        
        row=-1;

        id.value="";
        dimensione.value="";
        peso.value="";
        descrizione.value="";
        document.getElementById("addPack").innerHTML="Aggiungi";
    }
}

function updatePack(line){
    let riga=document.getElementById("colliList").rows[line];
    let id=document.getElementById("segnacollo");
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
        list[k].deleteCell(5);
        list[k].deleteCell(4);
        let editCell=list[k].insertCell(4);
        let delCell=list[k].insertCell(5);

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

function generateID(){
    let id=Math.round(Math.random()*(9999999999999-1000000000000)+9999999999999);
    let segnacollo=document.getElementById("segnacollo");
    segnacollo.value=id;
}

async function loadCostumers(){
    //estrazione 
    if(document.getElementById("clientiMitt").value==""){
        const response=await fetch("../php/getCustomers.php");
        const data=await response.json();
        console.log(data);
    }
<<<<<<< Updated upstream
=======
    const response=await fetch("../php/getCustomers.php");
    const data=await response.json();
    // console.log(data);
    if(data.error==true){
        //alert "impossibile caricare i clienti"
        console.log("errore trovato");
    }else{
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
    }
}

async function fillCustomer(target,type){
    let clienti=JSON.parse(localStorage.clienti);

    if(target.value!="" && target.value!=0){
        for(let k in clienti["clienti"]){
            // $("#clientiDest option[value='"+clienti["clienti"][k]["id"]+"']").css("display","block");
            // $("#clientiMitt option[value='"+clienti["clienti"][k]["id"]+"']").css("display","block");
            
            if(clienti["clienti"][k]["id"]==target.value){
                //rimozione del cliente dall'altra select - non funziona bene
                // if(type=="Mitt"){
                //     $("#clientiDest option[value='"+target.value+"']").css("display","none");
                //     // if(document.getElementById("clientiDest").value!="" && document.getElementById("clientiDest").value!=0){
                //     //     document.getElementById("clientiDest").value="";
                //     //     await fillField("Dest");
                //     // }
                // }else{
                //     // if(document.getElementById("clientiMitt").value!="" && document.getElementById("clientiMitt").value!=0){
                //     //     document.getElementById("clientiMitt").value="";
                //     //     await fillField("Mitt");
                //     // }
                //     $("#clientiMitt option[value='"+target.value+"']").css("display","none");
                // }
                fillField(type,clienti["clienti"][k]["ragioneSociale"],clienti["clienti"][k]["indirizzo"],
                clienti["clienti"][k]["citta"],clienti["clienti"][k]["prov"],clienti["clienti"][k]["cap"],clienti["clienti"][k]["cellulare"]);
            }
            
        }

        if((document.getElementById("clientiMitt").value!="" && document.getElementById("clientiMitt").value!=0) && 
        (document.getElementById("clientiDest").value!="" && document.getElementById("clientiDest").value!=0)){
            for(let k in clienti["clienti"]){
                $("#clientiDest option[value='"+clienti["clienti"][k]["id"]+"']").css("display","block");
                $("#clientiMitt option[value='"+clienti["clienti"][k]["id"]+"']").css("display","block");
            }
        }
        localStorage.lastCustomer=target.value;
    }else{
        fillField(type);
    }
}

async function fillField(type,ragioneSociale="",indirizzo="",cittaF="",provF="",capF="",cellF=""){
    let ragsoc=document.getElementById("RagSoc"+type).value=ragioneSociale;
    let ind=document.getElementById("Indirizzo"+type).value=indirizzo;
    let citta=document.getElementById("Citta"+type).value=cittaF;
    let prov=document.getElementById("Prov"+type).value=provF;
    let cap=document.getElementById("cap"+type).value=capF;
    let cell=document.getElementById("Cell"+type).value=cellF;
}

function checkForm(){
    //controllo campi
    let data=document.getElementById("dataConsegna").value;
    let tipo=document.getElementById("tipo").value;
    if(checkField("Mitt") && checkField("Dest") && data!="" && tipo!=""){
        let form=document.getElementById("main-form");
        form.submit();
    }
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
>>>>>>> Stashed changes
}