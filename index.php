<?php
    if(!isset($_SESSION))
        session_start();
?>
<script>
    
document.addEventListener("DOMContentLoaded",async function(){
    await getHomePage();
}); 
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
    <script src="script.js"></script>
   
</head>
<body>

    <div id="pagina">
        <?php include("header.php"); ?>

        <div id="search-bar">
            <input type="text" id="search-input" placeholder="Cerca notizie...">
            <button id="search-button" onclick="cerca()">Cerca</button>
        </div>

        <div id="search-error" class="error-message" style="display: none;"></div>


        <nav id="category-bar">
        
        </nav>

        <div id="container">

        </div>
    </div>

    
</body>
</html>