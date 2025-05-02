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
    <title>Document</title>
    <link rel="stylesheet" href="styles/login.css"> 
    <script src="script.js"></script>
</head>
<body>
    <div class="form-wrapper">
        <h2>Login</h2>
        <div id="login-error" class="error-message"></div>

        <input type="email" id="login-email" placeholder="Email">
        <input type="password" id="login-password" placeholder="Password">
        <button onclick="login()">Accedi</button>

        <p>Non hai un account?</p>
        <a href="registrazione.php">Registrati</a>
    </div>
</body>
</html>