<?php
    require_once("../db/gestioneDb.php");
    if(!isset($_SESSION))
        session_start();

    $db = gestioneDb::getInstance();
/*{
"status": "OK"|"ERR",
"msg":"", | "dato":""
}*/
    $ret = []; 
    $ret["data"] = "ciao";
    if(!isset($_GET["op"]))
    {
        $ret["status"] = "ERR";
        $ret["msg"] = "manca l'operazione";
        echo json_encode($ret);
        die();
    }

    if($_GET["op"] == "getCt")
    {
        $data = $db->getCategories();
        $ret["status"] = "OK";
        $ret["data"] = $data;
        echo json_encode($ret);
        die;
    }
    else if($_GET["op"] == "getLogin")
    {
        $ret[ "status"] = "OK";
        $ret["data"] = '
        <div id="login-form-container">
            <form id="login-form" method="POST" action="login.php">
                <h2>Accedi</h2>
                
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Login</button>

                <p>Non hai un account? <a onclick="getRegistrazione()" href="">Registrati qui</a></p>

            </form>
            
        </div>'
        ;
        echo json_encode($ret);
        die;
    }
    else if($_GET["op"] == "getRegist")
    {
        $ret[ "status"] = "OK";
        $ret["data"] = '
        <div id="registration-form-container">
            <form id="registration-form" method="POST" action="register.php">
                <h2>Registrati</h2>
                
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>

                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>

                <label for="confirm-password">Conferma Password</label>
                <input type="password" id="confirm-password" name="confirm-password" required>

                <button type="submit">Registrati</button>
            </form>
        </div>
        ';
        echo json_encode($ret);
        die;
    }
?>