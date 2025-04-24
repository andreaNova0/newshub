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

    function saveNews($title, $description, $url, $urlToImage)  //da sistemare
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

    function getNomeCognomeUtente()
    {
        $ret = [];
        if(!isset($_SESSION["user"]))
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "Si è verificato un errore!";
            return $ret;
        }
        $db = gestioneDb::getInstance();
        $result = $db->getNomeCognomeUtente($_SESSION["user"]);
        if($result != null)
        {
            $ret["status"] = "OK";
            $ret["data"] = $result;
        }
        else
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "Errore durante il recupero del nome e cognome.";
        }
        return $ret;
    }

    function checkSavedNews()
    {
        $ret= [];
        if(!isset($_SESSION["user"]))
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "Devi essere loggato per controllare se la notizia è già salvata!";
            return $ret;
        }

        if(!isset($_GET["title"], $_GET["description"], $_GET["url"], $_GET["urlToImage"]))
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "mancano i parametri";
            return $ret;
        }    
        if(empty($_GET["title"]) || empty($_GET["description"]) || empty($_GET["url"]) || empty($_GET["urlToImage"]))
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "mancano i parametri";
            return $ret;
        }   
        
        
        $db = gestioneDb::getInstance();
        $result = $db->checkSavedNews($_SESSION["user"], $_GET["title"], $_GET["description"], $_GET["url"], $_GET["urlToImage"]);
        if($result)
        {
            $ret["status"] = "OK";
            $ret["msg"] = "Notizia già salvata.";
        }
        else
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "Notizia non salvata.";
        }
        return $ret;
    }

    function deleteSavedNews()
    {
        $ret = [];
        if(!isset($_SESSION["user"]))
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "Devi essere loggato per eliminare una notizia salvata!";
            return $ret;
        }
       if(!isset($_GET["title"], $_GET["description"], $_GET["url"], $_GET["urlToImage"]))
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "mancano i parametri";
            return $ret;
        }
        else if(empty($_GET["title"]) || empty($_GET["description"]) || empty($_GET["url"]) || empty($_GET["urlToImage"]))
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "mancano i parametri";
            return $ret;
        }
        
        $db = gestioneDb::getInstance();

        $id_notizia = $db->getIdNews($_GET["title"], $_GET["description"], $_GET["url"], $_GET["urlToImage"]);
        if($id_notizia == -1)
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "Notizia non trovata.";
            return $ret;
        }
        if(!$db->isNewsAndUtentePresente($id_notizia, $_SESSION["user"]))
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "Notizia non trovata.";
            return $ret;
        }
        if($db->deleteSavedNews($_SESSION["user"], $id_notizia))
        {
            $ret["status"] = "OK";
            $ret["msg"] = "Notizia eliminata con successo!";
        }
        else
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "Errore durante l'eliminazione della notizia.";
        }

        return $ret;
    }

    function getSavedNews()
    {
        $ret = [];
        if(!isset($_SESSION["user"]))
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "Devi essere loggato per visualizzare le notizie salvate!";
            return $ret;
        }
        $db = gestioneDb::getInstance();
        $result = $db->getSavedNews($_SESSION["user"]);
        if($result != null)
        {
            $ret["status"] = "OK";
            $ret["data"] = $result;
        }
        else
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "Nessuna notizia salvata.";
        }
        return $ret;
    }

    function modificaDatiPersonali()
    {
        $ret = [];
        if(!isset($_SESSION["user"]))
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "Devi essere loggato per modificare il profilo!";
            return $ret;
        }
        if(!isset($_GET["nome"], $_GET["cognome"]))
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "mancano i parametri";
            return $ret;
        }
        else if(empty($_GET["nome"]) || empty($_GET["cognome"]))
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "mancano i parametri";
            return $ret;
        }
        
        $db = gestioneDb::getInstance();
        $result = $db->modificaDatiPersonali($_SESSION["user"], $_GET["nome"], $_GET["cognome"]);
        if($result == true)
        {
            $ret["status"] = "OK";
            $ret["msg"] = "Modifica avvenuta con successo!";
        }
        else
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "Errore durante la modifica del profilo.";
        }
        
        return $ret;
       

    }

    function cambiaPassword()
    {
        $ret = [];
        if(!isset($_SESSION["user"]))
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "Devi essere loggato per cambiare la password!";
            return $ret;
        }
        if(!isset($_GET["oldPassword"], $_GET["newPassword"], $_GET["confirmPassword"]))
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "mancano i parametri";
            return $ret;
        }
        else if(empty($_GET["oldPassword"]) || empty($_GET["newPassword"]) || empty($_GET["confirmPassword"]))
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "mancano i parametri";
            return $ret;
        }

        $db = gestioneDb::getInstance();
        if(!$db->checkPassword($_SESSION["user"], $_GET["oldPassword"]))
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "Password errata.";
            return $ret;
        }
        
        if($_GET["newPassword"] != $_GET["confirmPassword"])
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "le password non coincidono";
            return $ret;
        }
        
        $result = $db->cambiaPassword($_SESSION["user"], $_GET["newPassword"]);
        if($result == true)
        {
            $ret["status"] = "OK";
            $ret["msg"] = "Modifica avvenuta con successo!";
        }
        else
        {
            $ret["status"] = "ERR";
            $ret["msg"] = "Errore durante la modifica della password.";
        }
        
        return $ret;
    }

?>