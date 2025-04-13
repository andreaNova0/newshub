<script>
   document.addEventListener("DOMContentLoaded",async function(){
        
        let news = await getLatestNews("general");
        RenderLatestNews(news);
        getCategories();
    });
    
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
        news.forEach(article => {
            const div = document.createElement('div');
            div.className = 'news-item';
            div.innerHTML = `
            <h3>${article.title}</h3>
            <img src="${article.urlToImage || 'images/immagine non trovata.png'}" alt="Immagine notizia">
            <p>${article.description || ''}</p>
            <a href="${article.url}" target="_blank">Leggi</a>
            `;
            container.appendChild(div);
        });


    }

    async function getCategories()
    {
        let response = await (await fetch("ajax/operazioni.php?op=getCt")).json();
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
                RenderLatestNews(news);
            })
            category.appendChild(button);
        });
    }

    async function getRegistrazione()
    {
        let response = await fetch("ajax/operazioni.php?op=getRegist");
        let result = await response.json();
        if(result["status"] == "OK")
            document.getElementById("pagina").innerHTML = result["data"];
    }
    
    async function getLogin()
    {
        let response = await fetch("ajax/operazioni.php?op=getLogin");
        let result = await response.json();
        if(result["status"] == "OK")
            document.getElementById("pagina").innerHTML = result["data"];
    }
   
   
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/styleBottoniCategorie.css">
    <link rel="stylesheet" href="styles/barraRicerca.css">
    <link rel="stylesheet" href="styles/bottoneLogin.css">
    <link rel="stylesheet" href="styles/login.css">    
    <link rel="stylesheet" href="styles/registrazione.css">
</head>
<body>

    <div id="pagina">
        <header id="top-bar">
            <button id="login-button" onclick="getLogin()">Login</button>
        </header>

        <div id="search-bar">
            <input type="text" id="search-input" placeholder="Cerca notizie...">
            <button id="search-button">Cerca</button>
        </div>

        <nav id="category-bar">
        
        </nav>

        <div id="container">

        </div>
    </div>

    
</body>
</html>