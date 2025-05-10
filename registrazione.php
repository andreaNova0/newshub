<?php
    if(!isset($_SESSION)) 
        session_start(); 
    if(isset($_SESSION["user"]))
    {
        header("Location: index.php");
        exit();
    }
?>
<script>

</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NewsHub - Registrazione</title>
    <link rel="stylesheet" href="styles/registrazione.css">
    <script src="script.js"></script>
</head>
<body>
    <div class="form-wrapper">
        <h2>Registrazione</h2>
        <div id="register-error" class="error-message"></div>
        <input type="text" id="nome" placeholder="Nome">
        <input type="text" id="cognome" placeholder="Cognome">
        <input type="email" id="register-email" placeholder="Email">
        <input type="password" id="register-password" placeholder="Password">
        <input type="password" id="confirm-password" placeholder="Conferma Password">
        <button onclick="registrazione()">Registrati</button>
    </div>
</body>
</html>