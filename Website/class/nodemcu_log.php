<?php
    class Nodemcu_log{

        private $conn;
        private $db_table = "weather";

        public $id;
        public $suhu;
        public $altitude;
        public $tekanan;
        public $kelembaban;
        public $lux;
        public $raindrop;
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
                        suhu = :suhu, 
                        altitude = :altitude,
                        tekanan = :tekanan, 
                        kelembaban = :kelembaban,
                        lux = :lux,
                        raindrop = :raindrop,
                        realtime = :realtime,
                        tanggal = :tanggal";

            $stmt = $this->conn->prepare($sqlQuery);

            $this->suhu=htmlspecialchars(strip_tags($this->suhu));
            $this->altitude=htmlspecialchars(strip_tags($this->altitude));
            $this->tekanan=htmlspecialchars(strip_tags($this->tekanan));
            $this->kelembaban=htmlspecialchars(strip_tags($this->kelembaban));
            $this->lux=htmlspecialchars(strip_tags($this->lux));
            $this->raindrop=htmlspecialchars(strip_tags($this->raindrop));
            $this->realtime=htmlspecialchars(strip_tags($this->realtime));
            $this->tanggal=htmlspecialchars(strip_tags($this->tanggal));

            $stmt->bindParam(":suhu", $this->suhu);
            $stmt->bindParam(":altitude", $this->altitude);
            $stmt->bindParam(":tekanan", $this->tekanan);
            $stmt->bindParam(":kelembaban", $this->kelembaban);
            $stmt->bindParam(":lux", $this->lux);
            $stmt->bindParam(":raindrop", $this->raindrop);
            $stmt->bindParam(":realtime", $this->realtime);
            $stmt->bindParam(":tanggal", $this->tanggal);
            if($stmt->execute()){
               return true;
            }
            return false;
        }
    }
?>