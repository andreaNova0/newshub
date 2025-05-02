   

async function getHomePage()
{
    let news = await getLatestNews("general");
    await RenderLatestNews(news);
    getCategories();
}

async function getLatestNews(category)
{
    let response = await fetch(`https://newsapi.org/v2/top-headlines?country=us&apiKey=62aef4d314c54abca43e41856141a930&category=${category}&pageSize=100`);
    let txt = await response.text();
    latestNews = JSON.parse(txt);

    return latestNews["articles"];
}

async function RenderLatestNews(news)
{
    let container = document.getElementById("container");
    container.innerHTML = "";

    let checkResponse = await fetch("ajax/richieste.php?op=checkLog");
    let txt = await checkResponse.text();
    let check = JSON.parse(txt);
    let isLogged = check.logged;

    for (const article of news) {
        const div = document.createElement('div');
        div.className = 'news-item';
    
        const button = document.createElement('button');
        button.className = 'save-btn';
        button.dataset.title = article.title;
        button.dataset.description = article.description;
        button.dataset.url = article.url;
        button.dataset.image = article.urlToImage;
    
        // Funzioni persistenti da poter aggiungere/rimuovere
        const salvaHandler = async () => {
            await salvaNotizia(article.title, article.description, article.url, article.urlToImage, button, salvaHandler, eliminaHandler);
        };
        const eliminaHandler = async () => {
            await eliminaNotizia(article.title, article.description, article.url, article.urlToImage, button, salvaHandler, eliminaHandler);
        };
    
        if (isLogged) {
            const response = await fetch(`ajax/richieste.php?op=checkSavedNews&title=${encodeURIComponent(article.title)}&description=${encodeURIComponent(article.description)}&url=${encodeURIComponent(article.url)}&urlToImage=${encodeURIComponent(article.urlToImage)}`);
            const txt = await response.text();
            const result = JSON.parse(txt);
            if (result["status"] !== "OK") {
                button.textContent = 'Aggiungi ai preferiti';
                button.addEventListener('click', salvaHandler);
            } else {
                button.textContent = 'Rimuovi dai preferiti';
                button.addEventListener('click', eliminaHandler);
            }
        }
    
        div.innerHTML = `
            <h3>${article.title}</h3>
            <img src="${article.urlToImage || 'images/immagine non trovata.png'}" alt="Immagine notizia">
            <p>${article.description || ''}</p>
            <a href="${article.url}" target="_blank">Leggi</a>
        `;
        if(isLogged)
            div.appendChild(button);
        container.appendChild(div);
    }


}

async function getCategories()
{
    let txt = await (await fetch("ajax/richieste.php?op=getCt")).text();
    let category = document.getElementById("category-bar");
    let response = JSON.parse(txt);
    response["data"].forEach(categoria =>{
        let button = document.createElement("button");
        button.textContent = categoria["nome"];
        button.setAttribute("value",categoria["nome"]);
        button.addEventListener("click",async function(){
            let cat = this.textContent;
            document.querySelectorAll('#category-bar button').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            let news = await getLatestNews(cat);
            await RenderLatestNews(news);
        })
        category.appendChild(button);
    });
}

async function logout()
{
    let response = await fetch("ajax/richieste.php?op=logout");
    let txt = await response.text();
    let result = JSON.parse(txt);
    if(result["status"] == "OK")
    {
        location.reload();
    }
    else
    {
        alert("Errore durante il logout: " + result["msg"]);
        location.reload();
    }
}

async function cerca()
{
    
    let isLogged = await checkLog();
    
    if(!isLogged)
    {
       mostraAlert("Devi essere loggato per cercare notizie!");
    }
    else
    {
        let query = document.getElementById("search-input").value;
        if(query == "")
        {
            mostraAlert("Devi inserire almeno un parola!");
            return;
        }
        let response = await fetch(`https://newsapi.org/v2/everything?q=${query}&apiKey=62aef4d314c54abca43e41856141a930`);
        let txt = await response.text();
        let news = JSON.parse(txt);
        await RenderLatestNews(news["articles"]); 
    }
    
}

async function checkLog()
{
    let checkResponse = await fetch("ajax/richieste.php?op=checkLog");
    let txt = await checkResponse.text();
    let check = JSON.parse(txt);
    let isLogged = check.logged;
    return isLogged;
    
}

function getSalvaListener(button) {
    return async function salvaHandler() {
        await salvaNotizia(
            button.dataset.title,
            button.dataset.description,
            button.dataset.url,
            button.dataset.image,
            button
        );
    }
}

function getEliminaListener(button) {
    return async function eliminaHandler() {
        await eliminaNotizia(
            button.dataset.title,
            button.dataset.description,
            button.dataset.url,
            button.dataset.image,
            button
        );
    }
}

async function salvaNotizia(title, description, url, urlToImage, button, salvaHandler, eliminaHandler) {
    const isLogged = await checkLog();
    if (!isLogged) {
        alert("Devi essere loggato per salvare le notizie!");
        return;
    }

    const response = await fetch(`ajax/richieste.php?op=salvaNotizia&title=${encodeURIComponent(title)}&description=${encodeURIComponent(description)}&url=${encodeURIComponent(url)}&urlToImage=${encodeURIComponent(urlToImage)}`);
    const txt = await response.text();
    const result = JSON.parse(txt);
    if (result["status"] === "OK") {
        button.textContent = "Rimuovi dai preferiti";
        button.removeEventListener('click', salvaHandler);
        button.addEventListener('click', eliminaHandler);
    } else {
        mostraAlert("Errore durante il salvataggio della notizia: " + result["msg"]);
    }
}
async function getAreaPersonale()
{
    let isLogged = await checkLog();
    if(!isLogged)
    {
        mostraAlert("Devi essere loggato per accedere all'area personale!");
    }
    else
    {
        let response = await fetch("ajax/richieste.php?op=getAreaPersonale");
        let txt = await response.text();
        let result = JSON.parse(txt);
        if(result["status"] == "OK")
            document.getElementById("pagina").innerHTML = result["data"];
    }
}

async function eliminaNotizia(title, description, url, urlToImage, button, salvaHandler, eliminaHandler) {
    const isLogged = await checkLog();
    if (!isLogged) {
        mostraAlert("Devi essere loggato per rimuovere le notizie salvate!");
        return;
    }

    const response = await fetch(`ajax/richieste.php?op=eliminaNotizia&title=${encodeURIComponent(title)}&description=${encodeURIComponent(description)}&url=${encodeURIComponent(url)}&urlToImage=${encodeURIComponent(urlToImage)}`);
    const txt = await response.text();
    const result = JSON.parse(txt);
    if (result["status"] === "OK") {
        button.textContent = "Aggiungi ai preferiti";
        button.removeEventListener('click', eliminaHandler);
        button.addEventListener('click', salvaHandler);
    } else {
        mostraAlert("Errore durante la rimozione della notizia: " + result["msg"]);
    }
}

async function getNotizieSalvate()
{
    let isLogged = await checkLog();
    if(!isLogged)
    {
        mostraAlert("Devi essere loggato per accedere alle notizie salvate!");
    }
    else
    {
        let response = await fetch("ajax/richieste.php?op=getSavedNews");
        let txt = await response.text();
        let result = JSON.parse(txt);
        if(result["status"] == "OK")
        {
            document.getElementById("parteSopra").style.display = "none";
            await RenderLatestNews(result["data"]);

        }
    }
}

async function getModificaProfilo()
{
    let isLogged = await checkLog();
    if(!isLogged)
    {
        mostraAlert("Devi essere loggato per accedere alla modifica del profilo!");
        return;
    }
    else
    {
        document.getElementById("parteSopra").style.display = "none";
        document.getElementById("form-wrapper").style.display = "flex";
    }
    
}

async function getDatiUtente()
{
    let isLogged = await checkLog();
    if(!isLogged)
    {
        alert("Devi essere loggato per accedere ai dati utente!");
        return;
    }
    else
    {
        let response = await fetch("ajax/richieste.php?op=getNomeCognome");
        let txt = await response.text();
        let result = JSON.parse(txt);
        if(result["status"] == "OK")
        {
            document.getElementById("nome").value = result["data"]["nome"];
            document.getElementById("cognome").value = result["data"]["cognome"];
        }
    }
}
async function modificaDatiPersonali()
{
    let isLogged = await checkLog();
    if(!isLogged)
    {
        mostraAlert("Devi essere loggato per accedere alla modifica del profilo!");
    }
    else
    {
        let nome = document.getElementById("nome").value;
        let cognome = document.getElementById("cognome").value;
        let response = await fetch(`ajax/richieste.php?op=modificaDatiPersonali&nome=${nome}&cognome=${cognome}`);
        let txt = await response.text();
        let result = JSON.parse(txt);
        if(result["status"] == "OK")
        {            
            location.reload();
            mostraAlert("Modifica avvenuta con successo!");
        }
        else
        {
            mostraAlert("Errore durante la modifica dei dati: " + result["msg"]);
        }
    }
}

async function cambiaPassword()
{
    let isLogged = await checkLog();
    if(!isLogged)
    {
        alert("Devi essere loggato per accedere alla modifica del profilo!");
        return;
    }
    else
    {
        let vecchiaPassword = document.getElementById("old-password").value;
        let password = document.getElementById("password").value;
        let newPassword = document.getElementById("confirm-password").value;
        let response = await fetch(`ajax/richieste.php?op=cambiaPassword&newPassword=${password}&confirmPassword=${newPassword}&oldPassword=${vecchiaPassword}`);
        let txt = await response.text();
        let result = JSON.parse(txt);
        if(result["status"] == "OK")
        {
            alert("Modifica avvenuta con successo!");
            location.reload();
        }
        else
        {
            mostraAlert("Errore durante la modifica della password: " + result["msg"]);
        }
    }
}

function nascondiAlert()
{
    const alertBox = document.getElementById("alert-message");
    alertBox.style.display = "none";
    alertBox.classList.remove("show");
}

function mostraAlert(messaggio) 
{
    const alertBox = document.getElementById("alert-message");
    document.getElementById("testoError").innerText = messaggio;
    alertBox.style.display = "block";
    alertBox.classList.add("show");
}

async function getNomeCognomeUtente()
{
    let response = await fetch("ajax/richieste.php?op=getNomeCognome");
    let txt = await response.text();
    let result = JSON.parse(txt);
    if(result["status"] == "OK")
    {
        let nome = result["data"]["nome"];
        let cognome = result["data"]["cognome"];
        document.querySelector(".welcome-message").innerHTML = `Benvenuto, ${nome} ${cognome}!`;
    }
}

async function registrazione()
{
    let nome = document.getElementById("nome").value;
    let cognome = document.getElementById("cognome").value;
    let email = document.getElementById("register-email").value;
    let password = document.getElementById("register-password").value;
    let confirmPassword = document.getElementById("confirm-password").value;

    let response = await fetch(`ajax/richieste.php?op=registrazione&nome=${nome}&cognome=${cognome}&email=${email}&password=${password}&confirmPassword=${confirmPassword}`);
    let txt = await response.text();
    let result = JSON.parse(txt);
    if(result["status"] == "OK")
    {
        window.location.href = "index.php";
    }
    else
    {
        let errorDiv = document.getElementById("register-error");
        errorDiv.innerHTML = result["msg"];
        errorDiv.style.display = "block";
    }
    
    
}

async function login()
{
    let email = document.getElementById("login-email").value;
    let password = document.getElementById("login-password").value;

    let response = await fetch(`ajax/richieste.php?op=login&email=${email}&password=${password}`);
    let txt = await response.text();
    let result = JSON.parse(txt);
    if(result["status"] == "OK")
    {
        window.location.href = "index.php";
    }
    else
    {
        let errorDiv = document.getElementById("login-error");
        errorDiv.innerHTML = result["msg"];
        errorDiv.style.display = "block";
    }
}