<script>
    document.addEventListener("DOMContentLoaded",async function(){
        
        let news = await getLatestNews("general");
        RenderLatestNews(news);
    });
    async function getLatestNews(category)
    {
        let response = await fetch("https://newsapi.org/v2/top-headlines?country=us&apiKey=62aef4d314c54abca43e41856141a930&category="+category);
        let latestNews = await response.json();

        return latestNews["articles"];
    }

    async function RenderLatestNews(news)
    {
        let container = document.getElementById("container");
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
        let responde = await fetch("ajax/operazioni.php?op=getCt");
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
</head>
<body>
<nav id="category-bar">
  <button data-category="general" class="active">Tutte</button>
  <button data-category="business">Business</button>
  <button data-category="technology">Tecnologia</button>
  <button data-category="entertainment">Intrattenimento</button>
  <button data-category="health">Salute</button>
  <button data-category="science">Scienza</button>
  <button data-category="sports">Sport</button>
</nav>

    <div id="container">

    </div>
</body>
</html>