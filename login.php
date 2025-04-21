<script>
    async function login()
{
    let email = document.getElementById("login-email").value;
    let password = document.getElementById("login-password").value;

    let response = await fetch(`ajax/richieste.php?op=login&email=${email}&password=${password}`);
    let result = await response.json();
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