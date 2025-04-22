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
</head>
<body>
<?php include("header.php"); ?>	

    <div class="dashboard-container">
  <h2 class="welcome-message"></h2>

  <div class="dashboard-cards">
    <div class="dashboard-card">
      <h3>Il mio profilo</h3>
      <p>Modifica le tue informazioni personali o la password.</p>
      <button class="primary-btn" onclick="modificaProfilo()">Modifica profilo</button>
    </div>

    <div class="dashboard-card">
      <h3>Le mie notizie salvate</h3>
      <p>Consulta l'elenco delle notizie che hai salvato.</p>
      <button class="primary-btn" onclick="visualizzaNotizie()">Visualizza notizie</button>
    </div>
  </div>

  <div class="go-home">
    <a href="index.php">Torna alla Home</a>
  </div>
</div>
</body>
</html>
