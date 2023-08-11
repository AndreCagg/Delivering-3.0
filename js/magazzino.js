async function getMagazzino(){
    const response=await fetch("../php/getMagazzino.php");
    const data=await response.json();

    console.log(data);

    if(data.error=="no"){
        let table=document.createElement("table");

        for(let k in data.resultset){//zone
            for(let j in data.resultset[k]){//incarichi, O(k*j), Ohm(1)
                let obj=data.resultset[k][j];
                let line=table.insertRow();
                let cell1=line.insertCell(0);
                cell1.appendChild(document.createTextNode(j));

                let cell2=line.insertCell(1);
                cell2.appendChild(document.createTextNode(obj.rifDDt));

                let cell3=line.insertCell(2);
                cell3.appendChild(document.createTextNode(obj.dataRif));

                let cell4=line.insertCell(3);
                cell4.appendChild(document.createTextNode(composeAddress(obj["Mittente"])));

                let cell5=line.insertCell(4);
                cell5.appendChild(document.createTextNode(composeAddress(obj["Destinatario"])));

                let cell6=line.insertCell(5);
                cell6.appendChild(document.createTextNode(obj.epal));

                let cell7=line.insertCell(6);
                cell7.appendChild(document.createTextNode(obj.tipologia));

                let cell8=line.insertCell(7);
                cell8.appendChild(document.createTextNode(obj.consegna));

                let cell9=line.insertCell(8);
                cell9.appendChild(document.createTextNode(obj.riserva));

                document.getElementById("table-container").appendChild(table);
            }
        }
    }
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