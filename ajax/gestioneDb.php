<?php
   if(!isset($_SESSION))
        session_start();

    class gestioneDb {
        private static $instance = null;
        private $conn;
    
        private function __construct() {
            $this->conn = new mysqli("localhost","root","","newshub");
            if($this->conn->connect_errno)
                die;
        }
    
        public static function getInstance() {
            if (self::$instance === null) {
                self::$instance = new gestioneDb();
            }
            return self::$instance;
        }

        public function getCategories()
        {
            $query = "SELECT * FROM categorie";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->get_result();
        
            $categories = [];
            while ($row = $result->fetch_assoc()) {
                $categories[] = $row;
            }
        
            return $categories;
        }

        public function registrazione($nome,$cognome,$email,$password)
        {   

            $pass = md5($password);
            $query = "INSERT INTO utenti (nome,cognome,email,password) VALUES (?,?,?,?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssss",$nome,$cognome,$email,$pass);
            if($stmt->execute())
                return true;
            else
                return $stmt->error;
        }   

        //true = la mail può essere utilizzata (non è già registrata)	
        //false = la mail è già registrata
        public function checkEmail($email)
        {
            $query = "SELECT * FROM utenti WHERE email = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("s",$email);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if($result->num_rows == 0)
                return true;
            else
                return false;
        }

        public function login($email,$password)
        {
            $pass = md5($password);
            $query = "SELECT * FROM utenti WHERE email = ? AND password = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ss",$email,$pass);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if($result->num_rows == 1)
            {
                $row = $result->fetch_assoc();
                return $row["id"];
            }
            else
                return -1;
        }

        public function isNewsPresente($news)
        {
            $query = "SELECT * FROM notizie WHERE titolo = ? AND descrizione = ? AND url = ? AND urlToImage = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssss", $news["title"], $news["description"], $news["url"], $news["urlToImage"]);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if($result->num_rows == 0)
                return -1;
            else
            {
                $row = $result->fetch_assoc();
                return $row["id"];
            }
        }

        public function isNewsAndUtentePresente($id_notizia,$id_utente)
        {
            $query = "SELECT * FROM utenti_notizie WHERE id_utente = ? AND id_notizia = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ii", $id_utente, $id_notizia);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if($result->num_rows == 0)
                return false;
            else
                return true;
        }

        public function saveAssociazione($id_utente,$id_notizia)
        {
            $query = "INSERT INTO utenti_notizie (id_utente,id_notizia) VALUES (?,?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ii", $id_utente, $id_notizia);
            if($stmt->execute())
                return true;
            else
                return false;
        }

        public function saveNews($news)
        {
            $query = "INSERT INTO notizie (titolo,descrizione,url,urlToImage) VALUES (?,?,?,?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssss", $news["title"], $news["description"], $news["url"], $news["urlToImage"]);
            if($stmt->execute())
                return true;
            else
                return false;
        }

        public function getLastId()
        {
            return $this->conn->insert_id;
        }
           
        
    
    }
    
?>