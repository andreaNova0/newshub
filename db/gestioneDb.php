<?php
   

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
            $result = $stmt->fetch();
            return $result;
        }
    
    }
    
?>