async function getMagazzino(){
    const response=await fetch("../php/getMagazzino.php");
    const data=await response.json();

    // console.log(data);

    if(data.error=="no"){
        let tabCont=document.getElementById("table-container");

        for(let k in data.resultset){//zone
            let div=document.createElement("div");
            div.style.fontWeight="bold";
            div.appendChild(document.createTextNode(k));
            div.style.fontSize="20px";
            tabCont.appendChild(div);
            
            let table=document.createElement("table");
            table.classList.add("table");
            table.classList.add("table-striped");
            table.classList.add("table-hover");
            table.classList.add("align-middle");

            //header

            let line=table.insertRow();
            line.style.fontWeight="bold";
            let cell1=line.insertCell(0);
            cell1.appendChild(document.createTextNode("ID"));

            let cell2=line.insertCell(1);
            cell2.appendChild(document.createTextNode("RIF DDT"));

            let cell3=line.insertCell(2);
            cell3.appendChild(document.createTextNode("DATA DDT"));

            let cell4=line.insertCell(3);
            cell4.appendChild(document.createTextNode("MITTENTE"));

            let cell5=line.insertCell(4);
            cell5.appendChild(document.createTextNode("DESTINATARIO"));

            let cell6=line.insertCell(5);
            cell6.appendChild(document.createTextNode("EPAL"));

            let cell7=line.insertCell(6);
            cell7.appendChild(document.createTextNode("TIPO"));

            let cell8=line.insertCell(7);
            cell8.appendChild(document.createTextNode("CONSEGNA"));

            let cell9=line.insertCell(8);
            cell9.appendChild(document.createTextNode("RISERVA"));

            let cell10=line.insertCell(9);
            for(let j in data.resultset[k]){//incarichi, O(k*j), Ohm(1)
                let obj=data.resultset[k][j];
                let line=table.insertRow();
                let cell1=line.insertCell(0);
                cell1.appendChild(document.createTextNode(j));
                if(obj.impContr>0){
                    let contr=document.createElement("div");
                    contr.style.minWidth="133px";
                    contr.style.minHeight="24px";
                    contr.style.borderRadius="4px";
                    contr.style.backgroundColor="#ccff00";
                    contr.style.padding="2px";
                    contr.style.marginTop="4px";
                    contr.style.fontWeight="bold";
                    contr.style.textAlign="center";
                    contr.style.fontSize="15px";
                    contr.appendChild(document.createTextNode(" CONTRASSEGNO "+obj.impContr+"€ "));
                    cell1.appendChild(contr);
                }

                let cell2=line.insertCell(1);
                cell2.appendChild(document.createTextNode(obj.rifDDt));

                let cell3=line.insertCell(2);
                cell3.appendChild(document.createTextNode(obj.dataRif));

                let cell4=line.insertCell(3);
                cell4.style.whiteSpace="pre-line";
                cell4.appendChild(document.createTextNode(composeAddress(obj["Mittente"])));

                let cell5=line.insertCell(4);
                cell5.style.whiteSpace="pre-line";
                cell5.appendChild(document.createTextNode(composeAddress(obj["Destinatario"])));

                let cell6=line.insertCell(5);
                cell6.appendChild(document.createTextNode(obj.epal));

                let cell7=line.insertCell(6);
                cell7.appendChild(document.createTextNode(obj.tipologia));

                let cell8=line.insertCell(7);
                                
                const dataCons = new Date(formatDateForComparison(obj.consegna));
                
                if (dataCons < new Date()) {
                    cell8.style.color = "red";
                    cell8.style.fontWeight="bold";
                }
                
                cell8.appendChild(document.createTextNode(obj.consegna));

                let cell9=line.insertCell(8);
                let ris=document.createTextNode("");
                if(obj.riserva==1){
                    ris=document.createElement("div");
                    ris.style.width="25px";
                    ris.style.height="35px";
                    ris.style.backgroundColor="#cc0000";
                    ris.style.color="white";
                    ris.style.textAlign="center";
                    ris.style.fontWeight="bold";
                    ris.style.borderRadius="4px";
                    ris.appendChild(document.createTextNode("R"));
                }

                cell9.appendChild(ris);
                
                let cell10=line.insertCell(9);
                let btn=document.createElement("button");
                btn.classList.add("btn","btn-sm","btn-warning");
                btn.innerHTML="Visualizza";
                btn.addEventListener("click",async function(){
                    // return async function(){
                    // }
                    const response=await fetch("../php/getJob.php?id="+j);
                    const missionList=await response.json();//ritorna l'incarico

                    if(missionList.error.code==""){//non ci sono errori
                        const response=await fetch("../php/createDraftForView.php?backservice=0",{
                            method: "POST",
                            headers:{
                                "Content-Type":"application/json",
                            },
                            body: JSON.stringify(missionList.resultset[j]),
                        });

                        const data=await response;
                        //apertura scheda
                        createAlert("Dopo la modifica di alcune informazioni potrebbe essere necessario ricaricare la pagina",true);
                        open("../php/setService.php?service=1","_blank","popup,width=1400px,height=600px,top=100px,left=50px,right=50px");
                    }
                });
                cell10.appendChild(btn);

                tabCont.appendChild(table);
            }
        }
    }else{
        createAlert("Si è verificato un errore durante l'ottenimento dei dati");
    }
}

function createAlert(testo,before){
    let alert=document.createElement("div");
    alert.classList.add("alert", "alert-warning", "alert-dismissible", "fade", "show");
    alert.role="alert";
    alert.appendChild(document.createTextNode(testo));
    let btn=document.createElement("button");
    btn.type="button";
    btn.classList.add("btn-close");
    btn.setAttribute("data-bs-dismiss", "alert");
    btn.setAttribute("aria-label", "Close");
    alert.appendChild(btn);

    let elem="";
    if(before)
        elem="alert-space";
    else
        elem="table-container";

    document.getElementById(elem).appendChild(alert);
}

function composeAddress(set){/*!! ATTENZIONE: FUNZIONE RIDONDATA IN find.JS*/
    let indirizzo="";
    indirizzo+=set["ragioneSociale"]+"\n";
    indirizzo+=set["indirizzo"]+"\n";
    indirizzo+=set["cap"]+"\n";
    indirizzo+=set["citta"]+" ("+set["prov"]+")\n";
    indirizzo+=set["cellulare"];
    return indirizzo;
}

function formatDateForComparison(dateString) {
    const parts = dateString.split("/");
    if (parts.length === 3) {
        return `${parts[2]}-${parts[1]}-${parts[0]}`;
    }
    return dateString; // Restituisci la stessa stringa se non è possibile formattarla
}