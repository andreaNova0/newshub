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


</script>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Area Personale</title>
    <link rel="stylesheet" href="styles/area_personale.css">
    <link rel="stylesheet" href="styles/style.css">
    <script src="script.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
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
      <a href="index.php">Torna alla pagina principale</a>
    </div>
  <div id="container">

  </div>

  <div class="form-wrapper" id="form-wrapper" style="display: none;">
  <div class="form-container" id="form-container">
    <div class="alert alert-warning alert-dismissible fade show" role="alert" id="alert-message" style="display: none;">
      <div id="testoError"></div>
      <button type="button" class="close" onclick="nascondiAlert()" aria-label="Close">
      <span aria-hidden="true">&times;</span>
      </button>
    </div>

    <h2>Modifica Profilo</h2>

    <label for="nome">Nome</label>
    <input type="text" id="nome" name="nome" required>

    <label for="cognome">Cognome</label>
    <input type="text" id="cognome" name="cognome" required>

    <div class="error" id="info-error-message"></div>
    <button class="btn" onclick="modificaDatiPersonali()">Salva Dati Personali</button>

    <hr style="margin: 2rem 0;">

    <label for="old-password">Vecchia Password</label>
    <input type="password" id="old-password" name="old-password" required>

    <label for="password">Nuova Password</label>
    <input type="password" id="password" name="password" required>

    <label for="confirm-password">Conferma Password</label>
    <input type="password" id="confirm-password" name="confirm-password" required>

    <div class="error" id="password-error-message"></div>
    <button class="btn" onclick="cambiaPassword()">Cambia Password</button>
  </div>
</div>

</body>
</html>
