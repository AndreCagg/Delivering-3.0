async function getData(form){
    let link=makelink(form);

    //eliminazione dei precedenti
    let table=document.getElementById("viewResult");
    for(let i=table.rows.length-1;i>=0;i--){
        table.deleteRow(i);
    }
    document.getElementById("occourrences").innerHTML="";


    if(link.error!="yes"){
        // console.log(link.link);
        const response = await fetch("../php/getJob.php"+link.link);
        const data = await response.json();
        console.log(data);

        if(data.error.code==""){
            let resultset=data.resultset;
            let j=0;
            for(let k in resultset){
                j++;
                let line=table.insertRow();

                let cell1=line.insertCell(0);
                let img = document.createElement("img");
                img.src = "../icons/barcode.png";
                img.width="20";

                // Aggiunta dell'elemento <img> alla cella
                cell1.appendChild(img);
                cell1.appendChild(document.createTextNode(" "));

                cell1.appendChild(document.createTextNode(k));
                
                let cell2=line.insertCell(1);
                let img2=document.createElement("img");
                img2.src="../icons/posizione.png";
                img2.width="20";
                
                cell2.appendChild(img2);
                cell2.appendChild(document.createTextNode(" "));

                cell2.appendChild(document.createTextNode(resultset[k]["Movimenti"][0]["stato"]));

                let cell3=line.insertCell(2);
                let img3=document.createElement("img");
                img3.src="../icons/calendario.png";
                img3.width="17";

                cell3.appendChild(img3);
                cell3.appendChild(document.createTextNode(" "));

                cell3.appendChild(document.createTextNode(composedate(new Date(resultset[k]["consegna"]))));
                let cell4=line.insertCell(3);

                cell4.style.whiteSpace="pre-line";
                cell4.appendChild(document.createTextNode(composeAddress(resultset[k]["Mittente"][0])));

                let cell5=line.insertCell(4);
                cell5.style.whiteSpace="pre-line";
                cell5.appendChild(document.createTextNode(composeAddress(resultset[k]["Destinatario"][0])));
                line.insertCell(5).appendChild(document.createTextNode(resultset[k]["tipologia"]==1?"SPE":"RIT"));

                let cell7=line.insertCell(6);
                let img4=document.createElement("img");
                img4.src="../icons/visualizza2.png";
                img4.width="17";
                let div=document.createElement("button");
                div.classList.add("btn");
                div.classList.add("btn-warning");
                div.innerHTML="Visualizza ";
                div.appendChild(img4);
                cell7.appendChild(div);
            }

            document.getElementById("occourrences").innerHTML="Trovati "+j+" risultati";
        }else{
            alert(data.error.code+"\n"+data.error.message);
        }

    }
}

function aggiungiZero(n){
    return n<10?"0"+n:n;
}

function composedate(data){
    return aggiungiZero(data.getDate())+"/"+aggiungiZero(data.getMonth()+1)+"/"+data.getFullYear();
}

function composeAddress(set){
    let indirizzo="";
    indirizzo+=set["ragioneSociale"]+"\n";
    indirizzo+=set["indirizzo"]+"\n";
    indirizzo+=set["cap"]+"\n";
    indirizzo+=set["citta"]+" ("+set["prov"]+")\n";
    indirizzo+=set["cellulare"];
    return indirizzo;
}

function makelink(form){
    let link="?",obj={};
    // console.log(form.elements);

    const elements=Array.from(form.elements, element => ({
        name: element.name,
        value: element.value,
    }));

    let elementForm=0;
    for(let k in elements){
        if(elements[k].value!=""){
            elementForm++;
        }
        link+=elements[k]["name"]+"="+elements[k]["value"]+"&";
    }

    if(elementForm>0){
        link=link.slice(0,link.length-1);
        obj={"link":link,"error":"no"};
    }else{
        obj={"error":"yes"};
    }

    return obj;
}