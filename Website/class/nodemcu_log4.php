<?php
    class Nodemcu_log4{

        private $conn;
        private $db_table = "wind_weather";

        public $id;
        public $arah_angin;
        public $rps;
        public $velocity_ms;
        public $velocity_kmh;
        public $realtime;
        public $tanggal;
        public $created_at;
        public $updated_at;

        public function __construct($db){
            $this->conn = $db;
        }

        public function createLogData(){
            $sqlQuery = "INSERT INTO
                        ". $this->db_table ."
                    SET
                        arah_angin = :arah_angin, 
                        rps = :rps,
                        velocity_ms = :velocity_ms, 
                        velocity_kmh = :velocity_kmh,
                        realtime = :realtime,
                        tanggal = :tanggal";
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->arah_angin=htmlspecialchars(strip_tags($this->arah_angin));
            $this->rps=htmlspecialchars(strip_tags($this->rps));
            $this->velocity_ms=htmlspecialchars(strip_tags($this->velocity_ms));
            $this->velocity_kmh=htmlspecialchars(strip_tags($this->velocity_kmh));
            $this->realtime=htmlspecialchars(strip_tags($this->realtime));
            $this->tanggal=htmlspecialchars(strip_tags($this->tanggal));
        
            $stmt->bindParam(":arah_angin", $this->arah_angin);
            $stmt->bindParam(":rps", $this->rps);
            $stmt->bindParam(":velocity_ms", $this->velocity_ms);
            $stmt->bindParam(":velocity_kmh", $this->velocity_kmh);
            $stmt->bindParam(":realtime", $this->realtime);
            $stmt->bindParam(":tanggal", $this->tanggal);
            if($stmt->execute()){
               return true;
            }
            return false;
        }
    }
?>