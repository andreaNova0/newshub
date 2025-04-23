<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit;
}
?>

<script>
  document.addEventListener("DOMContentLoaded",async function() {
    await getNomeCognomeUtente();
    await getDatiUtente();
  });

  async function getNomeCognomeUtente()
  {
    let response = await fetch("ajax/richieste.php?op=getNomeCognome");
    let result = await response.json();
    if(result["status"] == "OK")
    {
        let nome = result["data"]["nome"];
        let cognome = result["data"]["cognome"];
        document.querySelector(".welcome-message").innerHTML = `Benvenuto, ${nome} ${cognome}!`;
    }
  }
</script>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Area Personale</title>
    <link rel="stylesheet" href="styles/area_personale.css">
    <link rel="stylesheet" href="styles/style.css">
    <script src="script.js"></script>
</head>
<body>
<?php include("header.php"); ?>	

  <div class="dashboard-container" id="parteSopra">
    <h2 class="welcome-message"></h2>

    <div class="dashboard-cards">
      <div class="dashboard-card">
        <h3>Il mio profilo</h3>
        <p>Modifica le tue informazioni personali o la password.</p>
        <button class="primary-btn" onclick="getModificaProfilo()">Modifica profilo</button>
      </div>

      <div class="dashboard-card">
        <h3>Le mie notizie salvate</h3>
        <p>Consulta l'elenco delle notizie che hai salvato.</p>
        <button class="primary-btn" onclick="getNotizieSalvate()">Visualizza notizie</button>
      </div>
    </div>

   

    
  </div>
  <div class="go-home">
      <a href="index.php">Torna alla Home</a>
    </div>
  <div id="container">

  </div>

  <div class="form-wrapper" id="form-wrapper" style="display: none;">
    <div class="form-container" id="form-container">
      <h2>Modifica Profilo</h2>
        <label for="nome">Nome</label>
        <input type="text" id="nome" name="nome" required>

        <label for="cognome">Cognome</label>
        <input type="text" id="cognome" name="cognome" required>

        <label for="password">Nuova Password</label>
        <input type="password" id="password" name="password">

        <label for="confirm-password">Conferma Password</label>
        <input type="password" id="confirm-password" name="confirm-password">

        <div class="error" id="error-message"></div>

        <button type="submit" class="btn">Salva Modifiche</button>
    </div>
  </div>

</body>
</html>
