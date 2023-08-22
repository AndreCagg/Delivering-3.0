function createAlert(type, text, space){ //ATTENZIONE funzione ridondata in 
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

async function loadAutisti(){
    clearTable();
    const response=await fetch("../php/getAutisti.php?detail=true");
    const data=await response.json();

    if(data.error=="false"){
        let autisti=data.autisti;
        let table=document.getElementById("autisti-tab");
        table.classList.add("table","table-striped","table-hover","align-middle","mt-5");

        let line=table.insertRow();
        line.classList.add("fw-bold","text-center");

        let cell1=line.insertCell(0);
        cell1.appendChild(document.createTextNode("COGNOME"));

        let cell2=line.insertCell(1);
        cell2.appendChild(document.createTextNode("NOME"));

        let cell3=line.insertCell(2);
        cell3.appendChild(document.createTextNode("EMAIL"));

        let cell4=line.insertCell(3);
        cell4.appendChild(document.createTextNode("TEL"));

        let cell5=line.insertCell(4);

        let cell6=line.insertCell(5);

        for(let k in autisti){
            let line=table.insertRow();
            line.classList.add("text-center");

            let cell1=line.insertCell(0);
            cell1.appendChild(document.createTextNode(autisti[k]["cognome"]));

            let cell2=line.insertCell(1);
            cell2.appendChild(document.createTextNode(autisti[k]["nome"]));

            let cell3=line.insertCell(2);
            cell3.appendChild(document.createTextNode(autisti[k]["email"]));

            let cell4=line.insertCell(3);
            cell4.appendChild(document.createTextNode(autisti[k]["tel"]));

            let cell5=line.insertCell(4);
            let modifica=document.createElement("button");
            modifica.classList.add("btn","btn-sm","btn-warning");
            modifica.innerHTML="MODIFICA";
            modifica.addEventListener("click",()=>{
                compileForm(autisti[k]);
            });
            cell5.appendChild(modifica);

            let cell6=line.insertCell(5);
            let elimina=document.createElement("button");
            elimina.classList.add("btn","btn-sm","btn-danger");
            elimina.innerHTML="ELIMINA";
            elimina.addEventListener("click",async function(){
                deleteAut(autisti[k]["id"]);
            });
            cell6.appendChild(elimina);

        }
    }else{
        createAlert("warning","Si è verificato un errore imprevisto e non è stato possibibile caricare gli autisti",document.getElementById("alert-space"));
    }
}

function compileForm(obj){
    let space=document.getElementById("draft-space");
    let input=document.getElementById("draft");
    if(input!=null){
        input.setAttribute("userid",obj["id"]);
    }else{
        input=document.createElement("input");
        input.type="hidden";
        input.name="draft";
        input.id="draft";
        input.setAttribute("userid",obj["id"]);
        document.getElementById("autisti-form").appendChild(input);
    }

    populateFields(obj["nome"],obj["cognome"],obj["email"],obj["tel"]);

    document.getElementById("saveAutisti").value="Modifica";

    space.innerHTML="Stai modificando '"+obj["cognome"]+" "+obj["nome"]+"'";
    let hint=document.createElement("div");
    hint.classList.add("form-text","fw-normal");
    hint.innerHTML="&nbsp;(Clicca qui per uscire dalla modifica)";
    hint.addEventListener("click",()=>{
        clearForm();
    });
    hint.style.cursor="pointer";
    space.appendChild(hint);
}

function clearForm(){
    if(document.getElementById("draft")!=null)
        document.getElementById("autisti-form").removeChild(document.getElementById("draft"));

    populateFields("","","","");
    document.getElementById("draft-space").innerHTML="";
    document.getElementById("saveAutisti").value="Inserisci"; 
}

function populateFields(nome,cognome,email,tel){
    document.getElementById("nome").value=nome;
    document.getElementById("cognome").value=cognome;
    document.getElementById("email").value=email;
    document.getElementById("tel").value=tel;
}

function clearTable(){ /*ATTENZIONE: FUNZIONE RIDONDATA IN FIND,veicoli*/
    let table=document.getElementById("autisti-tab");
    for(let i=table.rows.length-1;i>=0;i--){
        table.deleteRow(i);
    }
}

async function deleteAut(id){
    const response=await fetch("../php/validateAut.php",{
            method: "DELETE",
            headers:{
                "Content-Type":"text/plain",
            },
            body: JSON.stringify({"id":id}),
        });
    const data=await response.json();
    loadAutisti();

    if(data.error=="no"){
        createAlert("info","L'autista è stato correttamente eliminato",document.getElementById("alert-space"));
    }else{
        createAlert("warning","Si è verificato un errore imprevisto e non è stato possibibile eliminare l'autista",document.getElementById("alert-space"));
    }
}