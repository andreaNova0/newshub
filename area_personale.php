<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Area Personale</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/area_personale.css">
</head>
<body>
<?php include("header.php"); ?>	

    <div class="dashboard-container">
  <h2 class="welcome-message">Benvenuto, <?= $_SESSION["user"] ?>!</h2>

  <div class="dashboard-cards">
    <!-- Modifica profilo -->
    <div class="dashboard-card">
      <h3>Il mio profilo</h3>
      <p>Modifica le tue informazioni personali o la password.</p>
      <button class="primary-btn" onclick="modificaProfilo()">Modifica profilo</button>
    </div>

    <!-- Notizie salvate -->
    <div class="dashboard-card">
      <h3>Le mie notizie salvate</h3>
      <p>Consulta l'elenco delle notizie che hai salvato.</p>
      <button class="primary-btn" onclick="visualizzaNotizie()">Visualizza notizie</button>
    </div>
  </div>

  <div class="go-home">
    <a href="home.php">Torna alla Home</a>
  </div>
</div>
</body>
</html>
