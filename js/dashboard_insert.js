var state="none";
var row=-1;
function loadPack(){
    //NON E OBBLIGATORIO IL CAMPO DIMENSIONE
    let id=document.getElementById("segnacollo");
    let peso=document.getElementById("peso");
    let dimensione=document.getElementById("dimensioni");
    
    let alert=document.getElementById("alert");
    if(id.value=="" || peso.value==""){
        alert.style.display="block";
    }else{
        alert.style.display="none";
        let um=document.getElementById("um");

        // let obj={
        //     segnacollo: id.value,
        //     peso:{
        //         peso: peso.value,
        //         um: um.value
        //     },
        //     dimensione: dimensione.value
        // }

        // console.log(obj);

        let table=document.getElementById("colliList");
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


        let literalUM="";

        switch(um.value){
            case "1":
                literalUM="Kg";
            break;
            case "2":
                literalUM="Q.li";
            break;
        }

        cell1.appendChild(document.createTextNode(segnacollo.value));
        cell2.appendChild(document.createTextNode(peso.value+" "+literalUM));
        cell3.appendChild(document.createTextNode(dimensione.value));
        
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
        cell4.appendChild(edit);

        let del=document.createElement("button");
        del.innerHTML='<img src="../icons/elimina.png" width="11px" height="12px">&nbsp;Elimina';
        del.setAttribute("onclick","event.preventDefault(); deletePack("+String(rowId)+")");
        del.classList.add("btn");
        del.classList.add("btn-warning");
        del.classList.add("btn-sm");
        cell5.appendChild(del);
        
        row=-1;

        id.value="";
        dimensione.value="";
        peso.value="";
        document.getElementById("addPack").innerHTML="Aggiungi";
    }
}

function updatePack(line){
    let riga=document.getElementById("colliList").rows[line];
    let id=document.getElementById("segnacollo");
    let peso=document.getElementById("peso");
    let dimensione=document.getElementById("dimensioni");
    let umF=document.getElementById("um");
    let um=riga.cells[1].innerHTML.split(" ");


    id.value=riga.cells[0].innerHTML;
    peso.value=um[0];
    umF.value=(um[1]=="Kg")?1:2;
    dimensione.value=riga.cells[2].innerHTML;

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
        list[k].deleteCell(4);
        list[k].deleteCell(3);
        let editCell=list[k].insertCell(3);
        let delCell=list[k].insertCell(4);

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