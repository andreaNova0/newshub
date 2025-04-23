<script>
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
        window.location.href = "index.php";
    }
    else
    {
        let errorDiv = document.getElementById("register-error");
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