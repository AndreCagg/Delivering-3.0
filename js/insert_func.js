function createDeleteIconClickListener(idCount) {
    return function() {
        let file = document.getElementById("file" + idCount);
        if (file.files[0] != undefined) {
            document.getElementById("previewImage" + idCount).src = "../icons/addImage.png";
            document.getElementById("fileName" + idCount).innerHTML = "";
            file.value = "";
            this.style.display = "none";

            document.getElementById("images").removeChild(document.getElementById("imageAdder" + idCount));
        } else if (document.getElementById("imageAdder" + idCount).hasAttribute("fakePic")) {
            //tenere traccia delle foto eliminate
            if (document.getElementById("delPic") != null) {
                document.getElementById("delPic").value += document.getElementById("imageAdder" + idCount).getAttribute("imgId") + ";";
            } else {
                let delPic = document.createElement("input");
                delPic.type = "hidden";
                delPic.name = "delPic";
                delPic.id = "delPic";
                delPic.value = "";
                document.getElementById("images").appendChild(delPic);

                delPic.value += document.getElementById("imageAdder" + idCount).getAttribute("imgId") + ";";
            }

            document.getElementById("images").removeChild(document.getElementById("imageAdder" + idCount));
        }
    }
}

function createFileInputChangeListener(idCount) {
    return function() {
        let file = document.getElementById("file" + idCount);
        if (file.files[0] != undefined && ((file.files[0].size) / (1024 * 1024)) <= 16) {
            imageAdderCreator(idCount + 1);

            document.getElementById("fileName" + idCount).innerHTML = file.files[0].name;
            document.getElementById("previewImage" + idCount).src = URL.createObjectURL(file.files[0]);
            document.getElementById("previewImage" + idCount).classList.add("m-1");
            document.getElementById("deleteIc" + idCount).style.display = "block";
            file.addEventListener("click", (event) => {
                event.preventDefault();
            });
        } else if (((file.files[0].size) / (1024 * 1024)) > 16) {
            alert("IL FILE NON PUO' SUPERARE I 16 MB!")
        }
    }
}

async function getImg() {
    const response = await fetch("../php/getPhoto.php?id=" + document.getElementById("oldID").value);
    const data = await response.json();
    // console.log(data);

    if (data.error != "yes" && data.num > 0) { //ci sono foto e posso inserirle
        for (let i = 0; i < data.resultset.filesName.length; i++) {
            imageAdderCreator(i);
            let file = document.getElementById("file" + i);
            let deleteIc = document.getElementById("deleteIc" + i);
            let filename = [];
            filename = data.resultset.filesName[i].split("/");
            document.getElementById("fileName" + i).innerHTML = filename[filename.length - 1];
            let previewImg = document.getElementById("previewImage" + i);
            previewImg.src = data.resultset.filesName[i];
            previewImg.classList.add("m-1");
            document.getElementById("descrizione" + i).innerHTML = data.resultset.descrizione[i];
            deleteIc.style.display = "block";

            let imageAdder = document.getElementById("imageAdder" + i);
            imageAdder.setAttribute("fakePic", "fakePic");
            imageAdder.setAttribute("imgId", data.resultset.id[i]);

            file.addEventListener("click", (event) => {
                event.preventDefault();
            });

            //ev_list per eliminazione
            deleteIc.addEventListener("click", createDeleteIconClickListener(i));
        }

        imageAdderCreator(data.resultset.filesName.length);
    } else if (data.error == "yes") {
        let div = document.createElement("div");
        div.classList.add("fs-5");
        div.classList.add("fw-bold");
        div.innerHTML = "Impossibile caricare le immagini";
        document.getElementById("images").insertBefore(div, imageAdderCreator(0));
    }
}

function imageAdderCreator(idCount) {
    let imageAdder = document.createElement("div");
    imageAdder.classList.add("col-3");
    imageAdder.classList.add("mt-2");
    imageAdder.id = "imageAdder" + idCount;

    let previewImage = document.createElement("img");
    previewImage.src = "../icons/addImage.png";
    previewImage.style.height = "50px";
    previewImage.style.width = "50px";
    previewImage.classList.add("addImages");
    previewImage.id = "previewImage" + idCount;

    let fileLbl = document.createElement("label");
    fileLbl.setAttribute("for", "file" + idCount);
    previewImage.src = "../icons/addImage.png";
    fileLbl.appendChild(previewImage);

    let deleteIc = document.createElement("img");
    deleteIc.src = "../icons/delete.png";
    deleteIc.style.height = "22px";
    deleteIc.style.width = "22px";
    deleteIc.classList.add("addImages");
    deleteIc.id = "deleteIc" + idCount;
    deleteIc.style.display = "none";

    let deleteIcLbl = document.createElement("label");
    deleteIcLbl.setAttribute("for", "deleteIc" + idCount);
    deleteIcLbl.appendChild(deleteIc);

    let filename = document.createElement("small");
    filename.id = "fileName" + idCount;
    filename.classList.add("text-break");

    let fileInput = document.createElement("input");
    fileInput.type = "file";
    fileInput.accept = "image/jpeg, image/png";
    fileInput.name = "file[" + idCount + "]";
    fileInput.id = "file" + idCount;
    fileInput.style.display = "none";

    let descrizione = document.createElement("textarea");
    descrizione.classList.add("form-control");
    descrizione.name = "descrizione[" + idCount + "]";
    descrizione.id = "descrizione" + idCount;
    descrizione.style.height = "50px";
    descrizione.style.width = "150px";

    let previewContainer = document.createElement("div");
    previewContainer.id = "previewContainer";
    previewContainer.classList.add("mx-auto");
    previewContainer.appendChild(fileLbl);
    previewContainer.appendChild(deleteIcLbl);
    previewContainer.appendChild(descrizione);

    imageAdder.appendChild(previewContainer);
    imageAdder.appendChild(filename);
    imageAdder.appendChild(fileInput);

    fileInput.addEventListener("change", createFileInputChangeListener(idCount));
    deleteIc.addEventListener("click", createDeleteIconClickListener(idCount));

    document.getElementById("images").appendChild(imageAdder);
    idCount++;

    return imageAdder;
}

function disableContr() {
    let impContr = document.getElementById("impContrassegno");
    let contr = document.getElementById("contrassegno");

    if (!contr.checked && impContr.classList.contains("is-invalid"))
        impContr.classList.remove("is-invalid");

    if (!contr.checked){
        impContr.value="";
        impContr.setAttribute("disabled", "disabled");
    }else{
        impContr.removeAttribute("disabled");
    }
}

async function logFromFront(id) {
    const response = await fetch("../php/logFromFront.php?id=" + id);
    const data = await response;
}