async function getData(form){
    makelink(form);
    return;
    const response = await fetch("../php/getJob.php");
    const data = await response.json();
    console.log(data);
}

function makelink(form){
    let link="?";
    const elements=Array.from(form.elements, element => ({
        name: element.name,
        value: element.value,
    }));

    for(let k in elements){
        link+=elements[k]["name"]+"="+elements[k]["value"]+"&";
    }

    link=link.slice(0,link.length-1);
    console.log(link);
}