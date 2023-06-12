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
        cell4.className="word-wrap";
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