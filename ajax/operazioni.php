<?php
    require_once("../db/gestioneDb.php");
    if(!isset($_SESSION))
        session_start();
/*{
"status": "OK"|"ERR",
"msg":"", | "dato":""
}*/
    $ret = []; 
    if(!isset($_GET["op"]))
    {
        $ret["status"] = "ERR";
        $ret["msg"] = "manca l'operazione";
        echo json_encode($ret);
        die();
    }

    if($_GET["op"] == "getCt")
    {

    }
?>