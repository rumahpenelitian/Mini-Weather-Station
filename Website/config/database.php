<?php 
date_default_timezone_set('Asia/Jakarta');

    class Database {
        private $host = "localhost";
        private $database_name = "";
        private $username = "";
        private $password = "";

        public $conn;

        public function getConnection(){
            $this->conn = null;
            try{
                $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database_name, $this->username, $this->password);
                $this->conn->exec("set names utf8");
            }catch(PDOException $exception){
                echo "Gagal terhubung ke database" . $exception->getMessage();
            }
            return $this->conn;
        }
    }  
?>