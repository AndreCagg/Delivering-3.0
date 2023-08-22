async function getMagazzino(type){
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
            table.classList.add("table","table-striped","table-hover","align-middle");

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
                
                insertData(obj,line,cell1,false);
                
                let cell10=line.insertCell(9);
                let elem="";
                if(type=="button"){
                    elem=document.createElement("button");
                    elem.classList.add("btn","btn-sm","btn-warning");
                    elem.innerHTML="Visualizza";
                    elem.addEventListener("click",async function(){
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
                }else if(type=="checkbox"){
                    elem=document.createElement("input");
                    elem.type="checkbox";
                    elem.id="mission["+j+"]";
                    elem.classList.add("form-check-input");
                    elem.style.width="25px";
                    elem.style.height="25px";

                    line.addEventListener("click",function(e){
                        let missions=document.getElementById("missions");
                        
                        let target=e.target.type;//undefined è line
                        if((!elem.checked && target==undefined) || (elem.checked && target=="checkbox")){
                            addSelection(j,obj);
                            missions.value+=j+";";
                            elem.checked=true;
                        }else{
                            let selectedMis=document.getElementById("selectedMissions").querySelector("table");
                            let rows=selectedMis.rows;
                            let thisid=this.querySelector("td").innerHTML;
                            missions=missions.value.replace(j+";","");

                            //eliminazione
                            for(let k=0;k<rows.length;k++){
                                if(rows[k].querySelector("td").innerHTML==thisid){
                                    selectedMis.deleteRow(k);
                                }
                            }
                            elem.checked=false;
                        }
                    });
                }
                cell10.appendChild(elem);

                line.style.cursor="pointer";
                tabCont.appendChild(table);
            }
        }
    }else{
        createAlert("Si è verificato un errore durante l'ottenimento dei dati");
    }
}

function selectLine(){
    
}

function addSelection(j,obj){
    //ottenimento della selezione
    let selectedMis=document.getElementById("selectedMissions").querySelector("table");
    let table="";
    if(selectedMis==null){
        table=document.createElement("table");
        table.classList.add("table","table-striped","table-hover","align-middle");
        table.style.display="block";
        table.style.maxHeight="400px";
        table.style.maxWidth="10%";
        table.style.fontSize="13px";
        document.getElementById("selectedMissions").appendChild(table);
    }else{
        table=selectedMis;
    }
    let line=table.insertRow();
    let cell1=line.insertCell(0);
    cell1.appendChild(document.createTextNode(j));

    insertData(obj,line,cell1,true);
}

function insertData(obj,line,cell1,sumarize){
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

    let index=0;
    if(!sumarize){
        let cell2=line.insertCell(++index);
        cell2.appendChild(document.createTextNode(obj.rifDDt));

        let cell3=line.insertCell(++index);
        cell3.appendChild(document.createTextNode(obj.dataRif));
    }

    let cell4=line.insertCell(++index);
    cell4.style.whiteSpace="pre-line";
    cell4.appendChild(document.createTextNode(composeAddress(obj["Mittente"])));

    let cell5=line.insertCell(++index);
    cell5.style.whiteSpace="pre-line";
    cell5.appendChild(document.createTextNode(composeAddress(obj["Destinatario"])));

    let cell6=line.insertCell(++index);
    cell6.appendChild(document.createTextNode(obj.epal));

    let cell7=line.insertCell(++index);
    cell7.appendChild(document.createTextNode(obj.tipologia));

    let cell8=line.insertCell(++index);
                    
    const dataCons = new Date(formatDateForComparison(obj.consegna));
    
    if (dataCons < new Date()) {
        cell8.style.color = "red";
        cell8.style.fontWeight="bold";
    }
    
    cell8.appendChild(document.createTextNode(obj.consegna));

    let cell9=line.insertCell(++index);
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