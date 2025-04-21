<?php
    if(!isset($_SESSION))
        session_start();
?>
<script>
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

        news.forEach(article => {
            const div = document.createElement('div');
            div.className = 'news-item';

            let saveButton = "";
            if (isLogged) {
                saveButton = "<button class='save-btn' onclick='salvaNotizia(`" + article.title + "`, `" + article.description + "`, `" + article.url + "`, `" + article.urlToImage + "`)'>Salva</button>";
          }

            div.innerHTML = `
            <h3>${article.title}</h3>
            <img src="${article.urlToImage || 'images/immagine non trovata.png'}" alt="Immagine notizia">
            <p>${article.description || ''}</p>
            <a href="${article.url}" target="_blank">Leggi</a>
            ${saveButton}
            `;
            container.appendChild(div);
        });


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

    async function RenderRegistrazione()
    {
        let response = await fetch("ajax/richieste.php?op=getRegist");
        let result = await response.json();
        if(result["status"] == "OK")
            document.getElementById("pagina").innerHTML = result["data"];
    }
    
    async function renderLogin()
    {
        let response = await fetch("ajax/richieste.php?op=getLogin");
        let result = await response.json();
        if(result["status"] == "OK")
            document.getElementById("pagina").innerHTML = result["data"];
    }
   
    async function registrazione()
    {
        let nome = document.getElementById("nome").value;
        let cognome = document.getElementById("cognome").value;
        let email = document.getElementById("register-email").value;
        let password = document.getElementById("register-password").value;
        let confirmPassword = document.getElementById("confirm-password").value;

        let response = await fetch(`ajax/richieste.php?op=registrazione&nome=${nome}&cognome=${cognome}&email=${email}&password=${password}&confirmPassword=${confirmPassword}`);
        let result = await response.json();
        if(result["status"] == "OK")
        {
            location.reload();
        }
        else
        {
            let errorDiv = document.getElementById("register-error");
            errorDiv.innerHTML = result["msg"];
        }
       
      
    }
   
   async function logout()
    {
        let response = await fetch("ajax/richieste.php?op=logout");
        let result = await response.json();
        if(result["status"] == "OK")
        {
            alert("Logout avvenuto con successo!");
            location.reload();
        }
        else
        {
            alert("Errore durante il logout: " + result["msg"]);
            location.reload();
        }
    }

    async function login()
    {
        let email = document.getElementById("login-email").value;
        let password = document.getElementById("login-password").value;

        let response = await fetch(`ajax/richieste.php?op=login&email=${email}&password=${password}`);
        let result = await response.json();
        if(result["status"] == "OK")
        {
            alert("Login avvenuto con successo!");
            location.reload();
        }
        else
        {
            let errorDiv = document.getElementById("login-error");
            errorDiv.innerHTML = result["msg"];
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
   
</head>
<body>



    <div id="pagina">
        <?php include("header.php"); ?>

        <div id="search-bar">
            <input type="text" id="search-input" placeholder="Cerca notizie...">
            <button id="search-button" onclick="getAreaPersonale()">Cerca</button>
        </div>

        <div id="search-error" class="error-message" style="display: none;"></div>


        <nav id="category-bar">
        
        </nav>

        <div id="container">

        </div>
    </div>

    
</body>
</html>