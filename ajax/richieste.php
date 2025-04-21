<?php
    require_once("funzioni.php");
    if(!isset($_SESSION))
        session_start();

    if(!isset($_GET["op"]))
    {
        echo json_encode(errorOperation());
        die();
    }

    switch ($_GET["op"]) {
        case 'getCt':
            echo json_encode(getCt());
            die();   
        case 'getLogin':
            echo json_encode(getLogin());
            die();
        case 'getRegist':
            echo json_encode(getRegist());
            die();
        case 'registrazione':
            echo json_encode(registrazione());
            die();
        case 'logout':
            echo json_encode(logout());
            die(); 
        case 'login':
            echo json_encode(login());
            die();    
        case 'checkLog':
            $isLogged = isset($_SESSION["user"]);
            echo json_encode(["logged" => $isLogged]);
            exit;
        case 'salvaNotizia':
            echo json_encode(saveNews($_GET["title"],$_GET["description"],$_GET["url"],$_GET["urlToImage"]));
            die();        
        default:
            echo json_encode(errorOperation());
            die();
    }

?>