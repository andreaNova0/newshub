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
?>