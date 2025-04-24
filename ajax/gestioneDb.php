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
            $query = "SELECT * FROM notizie WHERE title = ? AND description = ? AND url = ? AND urlToImage = ?";
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
            $query = "INSERT INTO notizie (title,description,url,urlToImage) VALUES (?,?,?,?)";
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

        public function getNomeCognomeUtente($id_utente)
        {
            $query = "SELECT nome,cognome FROM utenti WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $id_utente);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if($result->num_rows == 1)
            {
                $row = $result->fetch_assoc();
                return ["nome" => $row["nome"], "cognome" => $row["cognome"]];
            }
            else
                return null;
        }
           
        public function checkSavedNews($id_utente, $title, $description, $url, $urlToImage)
        {
            $query = "SELECT N.* FROM notizie N
            JOIN utenti_notizie UN ON UN.id_notizia = N.id
            JOIN utenti U ON U.id = UN.id_utente 
            WHERE N.title = ? AND 
            N.description = ? AND
            N.urlToImage = ? AND
            N.url = ? AND
            U.id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssssi", $title, $description, $urlToImage, $url,$id_utente);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if($result->num_rows == 0)
                return false;
            else
                return true;
        }

        function deleteSavedNews($id_utente, $id_notizia)
        {
            $query = "DELETE FROM utenti_notizie WHERE id_utente = ? AND id_notizia = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ii", $id_utente, $id_notizia);
            if($stmt->execute())
                return true;
            else
                return false;
        }

        function getIdNews($title, $description, $url, $urlToImage)
        {
            $query = "SELECT id FROM notizie WHERE title = ? AND description = ? AND url = ? AND urlToImage = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssss", $title, $description, $url, $urlToImage);
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

        function getSavedNews($id_utente)
        {
            $query = "SELECT N.* FROM notizie N
            JOIN utenti_notizie UN ON UN.id_notizia = N.id
            WHERE UN.id_utente = ?
            ORDER BY n.id DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $id_utente);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if($result->num_rows == 0)
                return null;
            else
            {
                $notizie = [];
                while ($row = $result->fetch_assoc()) {
                    $notizie[] = $row;
                }
                return $notizie;
          }
        }
        
        function modificaDatiPersonali($id_utente,$nome,$cognome)
        {
            $query = "UPDATE utenti SET nome = ?, cognome = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ssi", $nome, $cognome, $id_utente);
            if($stmt->execute())
                return true;
            else
                return false;
        }

        function cambiaPassword($id_utente,$Newpassword)
        {
           $query = "UPDATE utenti SET password = ? WHERE id = ?";
            $stmt = $this->conn->prepare($query);
            $pass = md5($Newpassword);
            $stmt->bind_param("si", $pass, $id_utente);
            if($stmt->execute())
                return true;
            else
                return false;
         
        }

        function checkPassword($id_utente,$password)
        {
            $query = "SELECT password FROM utenti WHERE id = ? AND password = ?";
            $stmt = $this->conn->prepare($query);
            $pass = md5($password);
            $stmt->bind_param("is", $id_utente, $pass);
            $stmt->execute();
            $result = $stmt->get_result();
        
            if($result->num_rows == 1)
                return true;
            else
                return false;
        }
        
        
        
    
    }
    
?>