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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</head>
<body>

    <div id="pagina">
        <?php include("header.php"); ?>

        <div class="alert alert-warning alert-dismissible fade show" role="alert" id="alert-message" style="display: none;">
  <div id="testoError"></div>
  <button type="button" class="close" onclick="nascondiAlert()" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

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