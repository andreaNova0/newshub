
document.addEventListener("DOMContentLoaded",async function(){
    await getHomePage();
});    

async function getHomePage()
{
    let news = await getLatestNews("general");
    await RenderLatestNews(news);
    getCategories();
}

async function getLatestNews(category)
{
    let response = await fetch(`https://newsapi.org/v2/top-headlines?country=us&apiKey=62aef4d314c54abca43e41856141a930&category=${category}&pageSize=100`);
    let latestNews = await response.json();

    return latestNews["articles"];
}

async function RenderLatestNews(news)
{
    let container = document.getElementById("container");
    container.innerHTML = "";

    let checkResponse = await fetch("ajax/richieste.php?op=checkLog");
    let check = await checkResponse.json();
    let isLogged = check.logged;

    for (const article of news) {
        const div = document.createElement('div');
        div.className = 'news-item';
    
        let saveButton = "";
        if (isLogged) {
            let response = await fetch(`ajax/richieste.php?op=checkSavedNews&title=${article.title}&description=${article.description}&url=${article.url}&urlToImage=${article.urlToImage}`);
            let result = await response.json();
            // opzionale: puoi decidere di mostrare il bottone solo se la notizia NON è già salvata
            if (result["status"] != "OK") {
                saveButton = `
                    <button class="save-btn" onclick="salvaNotizia(
                      \`${article.title}\`,
                      \`${article.description}\`,
                      \`${article.url}\`,
                      \`${article.urlToImage}\`
                    )">Aggiungi ai preferiti</button>
                `;
            }
            else if(result["status"] == "OK")
            {
                saveButton = `
                    <button class="save-btn" disabled>Rimuovi dai preferiti</button>
                `;
            }

        }
    
        div.innerHTML = `
            <h3>${article.title}</h3>
            <img src="${article.urlToImage || 'images/immagine non trovata.png'}" alt="Immagine notizia">
            <p>${article.description || ''}</p>
            <a href="${article.url}" target="_blank">Leggi</a>
            ${saveButton}
        `;
        container.appendChild(div);
    }


}

async function getCategories()
{
    let response = await (await fetch("ajax/richieste.php?op=getCt")).json();
    let category = document.getElementById("category-bar");
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
    let result = await response.json();
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
        let div = document.getElementById("search-error");
        div.style.display = "block";

        div.innerHTML = "Devi essere loggato per cercare notizie!";
        return;
    }
    else
    {
        let query = document.getElementById("search-input").value;
    let response = await fetch(`https://newsapi.org/v2/everything?q=${query}&apiKey=62aef4d314c54abca43e41856141a930`);
    let news = await response.json();
    await RenderLatestNews(news["articles"]); 
    }
    
}

async function checkLog()
{
    let checkResponse = await fetch("ajax/richieste.php?op=checkLog");
    let check = await checkResponse.json();
    let isLogged = check.logged;
    return isLogged;
    
}

async function salvaNotizia(title,description,url,urlToImage)
{
    let isLogged = await checkLog();
    if(!isLogged)
    {
        alert("Devi essere loggato per salvare le notizie!");
        return;
    }
    else
    {
        let response = await fetch(`ajax/richieste.php?op=salvaNotizia&title=${title}&description=${description}&url=${url}&urlToImage=${urlToImage}`);
        let result = await response.json();
        if(result["status"] == "OK")
        {
            alert("Notizia salvata con successo!");
        }
        else
        {
            alert("Errore durante il salvataggio della notizia: " + result["msg"]);
        }
    }
    
}

async function getAreaPersonale()
{
    let isLogged = await checkLog();
    if(!isLogged)
    {
        alert("Devi essere loggato per accedere all'area personale!");
        return;
    }
    else
    {
        let response = await fetch("ajax/richieste.php?op=getAreaPersonale");
        let result = await response.json();
        if(result["status"] == "OK")
            document.getElementById("pagina").innerHTML = result["data"];
    }
}
