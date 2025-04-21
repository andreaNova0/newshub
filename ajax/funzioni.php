<?php
    require_once("gestioneDb.php");
    if(!isset($_SESSION))
        session_start();

    function errorOperation()
    {
        $ret = [];
        $ret["status"] = "ERR";
        $ret["msg"] = "manca l'operazione";
        return $ret;
    }    
    function getCt()
    {
        $ret = [];
        $db = gestioneDb::getInstance();
        $data = $db->getCategories();
        $ret["status"] = "OK";
        $ret["data"] = $data;
        return $ret;
    }
    function getLogin()
    {
        $ret = [];
        $ret[ "status"] = "OK";
        $ret["data"] = '
        <link rel="stylesheet" href="styles/login.css"> 
        <div class="form-wrapper">
            <h2>Login</h2>
            <div id="login-error" class="error-message"></div>

            <input type="email" id="login-email" placeholder="Email">
            <input type="password" id="login-password" placeholder="Password">
            <button onclick="login()">Accedi</button>

            <p>Non hai un account? </p>
            <button onclick="RenderRegistrazione()">Registrati</button>
        </div>'
        ;
        return $ret;    
    }
    function getRegist()
    {        
        $ret = [];
        $ret[ "status"] = "OK";
        $ret["data"] = '
        <link rel="stylesheet" href="styles/registrazione.css">
         <div class="form-wrapper">
            <h2>Registrazione</h2>
            <div id="register-error" class="error-message"></div>
            <input type="text" id="nome" placeholder="Nome">
            <input type="text" id="cognome" placeholder="Cognome">
            <input type="email" id="register-email" placeholder="Email">
            <input type="password" id="register-password" placeholder="Password">
            <input type="password" id="confirm-password" placeholder="Conferma Password">
            <button onclick="registrazione()">Registrati</button>

        </div>
        ';
        return $ret;
    }
    function registrazione()
    {
        $ret = [];
        $db = gestioneDb::getInstance();
        if(!isset($_GET["password"], $_GET["confirmPassword"], $_GET["nome"], $_GET["cognome"], $_GET["email"]))
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "mancano i parametri";
            return $ret;
        }
        else if(empty($_GET["password"]) || empty($_GET["confirmPassword"]) || empty($_GET["nome"]) || empty($_GET["email"]) || empty($_GET["cognome"]))
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "mancano i parametri";
            return $ret;
        }
        else
        {
            if($_GET["password"] != $_GET["confirmPassword"])
            {
                $ret["status"] = "ERR";
                $ret["msg"] = "le password non coincidono";
                return $ret;
            }
            else
            {
                $nome = $_GET["nome"];
                $cognome = $_GET["cognome"];    
                $email = $_GET["email"];
                $password = $_GET["password"];
                
                if($db->checkEmail($email))
                {
                    
                    $response = $db->registrazione($nome, $cognome, $email,$password);
                    if($response == true)
                    {
                        $ret["status"] = "OK";
                        $ret["msg"] = "Registrazione avvenuta con successo!";
                        $_SESSION["user"] = $db->getLastId(); //prendo l'id dell'utente appena registrato
                    }
                    else
                    {
                        $ret["status"] = "ERR";
                        $ret["msg"] = $response;
                    }
                    
                }
                else
                {
                    $ret["status"] = "ERR";
                    $ret["msg"] = "Email già registrata.";
                }
                return $ret;
            }
        }
    }
    function logout()
    {
        $ret = [];
        if(isset($_SESSION["user"]))
        {
            unset($_SESSION["user"]);
            $ret["status"] = "OK";
            $ret["msg"] = "Logout avvenuto con successo!";
        }
        else
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "Nessun utente loggato.";
        }
        return $ret;
    }

    function login()
    {
        $ret = [];
        $db = gestioneDb::getInstance();
        if(!isset($_GET["email"], $_GET["password"]))
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "mancano i parametri";
            return $ret;
        }
        else if(empty($_GET["email"]) || empty($_GET["password"]))
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "mancano i parametri";
            return $ret;
        }
        else
        {
            $email = $_GET["email"];
            $password = $_GET["password"];
            
            if(!$db->checkEmail($email))
            {
                $result = $db->login($email, $password);
                if($result != -1)
                {
                    $_SESSION["user"] = $result;
                    $ret["status"] = "OK";
                    $ret["msg"] = "Login avvenuto con successo!";
                }
                else
                {
                    $ret["status"] = "ERR";
                    $ret["msg"] = "Password errata.";
                }
                
            }
            else
            {
                $ret["status"] = "ERR";
                $ret["msg"] = "Email non registrata.";
            }
            return $ret;
        }
    }

    function checkLog()
    {
        $isLogged = isset($_SESSION["user"]);
        echo json_encode(["logged" => $isLogged]);
        exit;
       
    }

    function saveNews($title, $description, $url, $urlToImage)
    {
        $ret = [];
       
        if(!isset($_SESSION["user"]))
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "Devi essere loggato per salvare una notizia!";
            return $ret;
        }
        if(!isset($title, $description, $url, $urlToImage))
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "mancano i parametri";
            return $ret;
        }
        else if(empty($title) || empty($description) || empty($url) || empty($urlToImage))
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "mancano i parametri";
            return $ret;
        }
      
        $news = [
            "title" => $title,
            "description" => $description,
            "url" => $url,
            "urlToImage" => $urlToImage
        ];
  
       //controllo se la notizia è gia salvata (solo la notizia)
       $db = gestioneDb::getInstance();
   
       $id_notizia = $db->isNewsPresente($news);

        if($id_notizia == -1) //se la notizia non è presente
       {
     
            if(!$db->saveNews($news))
            {
                $ret["status"] = "ERR";
                $ret["msg"] = "Errore durante il salvataggio della notizia.";
            }
            else
            {
                $id_notizia = $db->getLastId();
                if($db->saveAssociazione($_SESSION["user"], $id_notizia))
                {
                    $ret["status"] = "OK";
                    $ret["msg"] = "Notizia salvata con successo!";
                }
                else
                {
                    $ret["status"] = "ERR";
                    $ret["msg"] = "Errore durante il salvataggio della notizia.";
                }
            }
       }
       else // se la notizia è già presente 
       {   
       
           //controllo se è già presente anche l'associazione con l'utente
            $result = $db->isNewsAndUtentePresente($id_notizia, $_SESSION["user"]); //lo faccio solo per motivi di sicurezza
           
            if($result)
            {
                $ret["status"] = "ERR";
                $ret["msg"] = "Notizia già salvata.";
            }
            else
            {
                if($db->saveAssociazione($_SESSION["user"], $id_notizia))
                {
                    $ret["status"] = "OK";
                    $ret["msg"] = "Notizia salvata con successo!";
                }
                else
                {
                    $ret["status"] = "ERR";
                    $ret["msg"] = "Errore durante il salvataggio della notizia.";
                }
               
            }
       }

       return $ret;

    }



?>