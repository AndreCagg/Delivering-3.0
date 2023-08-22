async function loadVeicoli(){
    clearTable();
    const response=await fetch("../php/getVeic.php");
    const data=await response.json();

    if(data.error=="false"){
        let table=document.getElementById("veic-tab");
        if(data.veicoli.length>0){
            table.classList.add("table","table-striped","table-hover","align-middle","mt-4","text-center");
    
            let line=table.insertRow();
            line.classList.add("fw-bold");
            let cell1=line.insertCell(0);
            cell1.appendChild(document.createTextNode("TARGA"));
            let cell2=line.insertCell(1);
            cell2.appendChild(document.createTextNode("NOME"));
            let cell3=line.insertCell(2);
            let cell4=line.insertCell(3);
        }

        for(let k in data.veicoli){
            let line=table.insertRow();
            let cell1=line.insertCell(0);
            cell1.appendChild(document.createTextNode(data.veicoli[k].targa));
            let cell2=line.insertCell(1);
            cell2.appendChild(document.createTextNode(data.veicoli[k].nome));

            let cell3=line.insertCell(2);
            let cell4=line.insertCell(3);

            let modifica=document.createElement("button");
            modifica.classList.add("btn","btn-warning");
            modifica.innerHTML="MODIFICA";
            modifica.addEventListener("click",function(){
                let form=document.getElementById("veic-form");
                let draft=document.createElement("input");
                draft.type="hidden";
                draft.id="draft";
                draft.name="draft";
                draft.setAttribute("veicid",data.veicoli[k].targa);
                form.appendChild(draft);

                let space=document.getElementById("draft-space");
                space.innerHTML="Stai modificando '"+data.veicoli[k].targa+"'";
                let hint=document.createElement("div");
                hint.classList.add("form-text","fw-normal");
                hint.innerHTML="&nbsp;(Clicca qui per uscire dalla modifica)";
                hint.addEventListener("click",()=>{
                    clearForm();
                });
                hint.style.cursor="pointer";
                space.appendChild(hint);

                document.getElementById("targa").value=data.veicoli[k].targa;
                document.getElementById("nome").value=data.veicoli[k].nome;
                document.getElementById("saveVeic").value="Modifica";
            });
            cell3.appendChild(modifica);

            let elimina=document.createElement("button");
            elimina.innerHTML="ELIMINA";
            elimina.classList.add("btn","btn-danger");
            elimina.addEventListener("click",function(){
                deleteVeic(data.veicoli[k].targa);
            });
            cell4.appendChild(elimina);
        }
    }
}

function clearTable(){ /*ATTENZIONE: FUNZIONE RIDONDATA IN FIND,veicli*/
    let table=document.getElementById("veic-tab");
    for(let i=table.rows.length-1;i>=0;i--){
        table.deleteRow(i);
    }
}

function clearForm(){
    if(document.getElementById("draft")!=null)
        document.getElementById("veic-form").removeChild(document.getElementById("draft"));

    document.getElementById("targa").value="";
    document.getElementById("nome").value="";
    document.getElementById("draft-space").innerHTML="";
    document.getElementById("saveVeic").value="Inserisci"; 
}

async function deleteVeic(id){
    const response=await fetch("../php/validateVeic.php",{
            method: "DELETE",
            headers:{
                "Content-Type":"text/plain",
            },
            body: JSON.stringify({"id":id}),
        });
    const data=await response.json();
    loadVeicoli();

    if(data.error=="no"){
        createAlert("info","Il veicolo è stato correttamente eliminato",document.getElementById("alert-space"));
    }else{
        createAlert("warning","Si è verificato un errore imprevisto e non è stato possibibile eliminare il veicolo",document.getElementById("alert-space"));
    }
}