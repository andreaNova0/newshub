<header id="top-bar"> 

<div class="logo-container">
    <img src="images/logo.png" alt="Logo NewsHub" class="site-logo">
</div>
<?php if(isset($_SESSION["user"])){ ?>
<img src="images/user.png" alt="Profilo" class="profile-icon" onclick="getAreaPersonale()">
<button id="login-button" onclick="logout()">Logout</button>
<?php } else {?>
<button id="login-button" onclick="renderLogin()">Login</button>
<?php } ?>
</header>