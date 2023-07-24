async function getData(form){
    let link=makelink(form);

    //eliminazione dei precedenti
    let table=document.getElementById("viewResult");
    for(let i=table.rows.length-1;i>=0;i--){
        table.deleteRow(i);
    }

    if(link.error!="yes"){
        // console.log(link.link);
        const response = await fetch("../php/getJob.php"+link.link);
        const data = await response.json();
        // console.log(data);

        if(data.error.code==""){
            let resultset=data.resultset;
            for(let k in resultset){
                let line=table.insertRow();
                line.insertCell(0).appendChild(document.createTextNode(k));
                line.insertCell(1).appendChild(document.createTextNode(resultset[k]["Movimenti"][0]["stato"]));
                line.insertCell(2).appendChild(document.createTextNode(composedate(new Date(resultset[k]["consegna"]))));
                let cell4=line.insertCell(3);

                cell4.style.whiteSpace="pre-line";
                cell4.appendChild(document.createTextNode(composeAddress(resultset[k]["Mittente"][0])));

                let cell5=line.insertCell(4);
                cell5.style.whiteSpace="pre-line";
                cell5.appendChild(document.createTextNode(composeAddress(resultset[k]["Destinatario"][0])));
                line.insertCell(5).appendChild(document.createTextNode(resultset[k]["tipologia"]==1?"SPE":"RIT"));
            }

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