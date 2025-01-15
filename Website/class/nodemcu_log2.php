<?php
    class Nodemcu_log2{

        private $conn;
        private $db_table = "monitoring_power";

        public $id;
        public $tegangan_dinamis;
        public $tegangan_statis;
        public $arus_dinamis;
        public $arus_statis;
        public $power_dinamis;
        public $power_statis;
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
                        tegangan_dinamis = :tegangan_dinamis, 
                        tegangan_statis = :tegangan_statis,
                        arus_dinamis = :arus_dinamis, 
                        arus_statis = :arus_statis,
                        power_dinamis = :power_dinamis,
                        power_statis = :power_statis,
                        realtime = :realtime,
                        tanggal = :tanggal";
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->tegangan_dinamis=htmlspecialchars(strip_tags($this->tegangan_dinamis));
            $this->tegangan_statis=htmlspecialchars(strip_tags($this->tegangan_statis));
            $this->arus_dinamis=htmlspecialchars(strip_tags($this->arus_dinamis));
            $this->arus_statis=htmlspecialchars(strip_tags($this->arus_statis));
            $this->power_dinamis=htmlspecialchars(strip_tags($this->power_dinamis));
            $this->realtime=htmlspecialchars(strip_tags($this->realtime));
            $this->tanggal=htmlspecialchars(strip_tags($this->tanggal));
        
            $stmt->bindParam(":tegangan_dinamis", $this->tegangan_dinamis);
            $stmt->bindParam(":tegangan_statis", $this->tegangan_statis);
            $stmt->bindParam(":arus_dinamis", $this->arus_dinamis);
            $stmt->bindParam(":arus_statis", $this->arus_statis);
            $stmt->bindParam(":power_dinamis", $this->power_dinamis);
            $stmt->bindParam(":power_statis", $this->power_statis);
            $stmt->bindParam(":realtime", $this->realtime);
            $stmt->bindParam(":tanggal", $this->tanggal);
            if($stmt->execute()){
               return true;
            }
            return false;
        }
    }
?>