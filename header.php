<link rel="stylesheet" href="styles/header.css">
<header id="top-bar"> 
    <div class="logo-container">
        <img src="images/logo.png" alt="Logo NewsHub" class="site-logo">
    </div>
    <?php if(isset($_SESSION["user"])){ ?>
        <a href="area_personale.php">
            <img src="images/user.png" alt="Profilo" class="profile-icon" onclick="getAreaPersonale()">
        </a>
        <div class="action-container" onclick="logout()">Logout</div>
    <?php } else { ?>
        <a href="login.php">
            <div class="action-container">Login</div>
        </a>
    <?php } ?>
</header>