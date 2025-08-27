// type,text boxes params
function alertBox(text,type) {
    let alertElem = document.createElement('p');
    alertElem.innerText = text;
    alertElem.classList.add(type);
    document.getElementsByTagName('body')[0].prepend(alertElem);
    setTimeout(() => alertElem.remove(), 2800)
}

    if(!location.pathname.match(".*/.*.php") && location.pathname.split("/").length > 2) {  
        const crypt_split = location.pathname.
            replace("/",'-').
            replace("/","-").
            split("-");
        
        alertBox("Decrypting Url","success")
    }

