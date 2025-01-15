<?php
    class Nodemcu_log3{

        private $conn;
        private $db_table = "tipbucket";

        public $id;
        public $jumlah_tip;
        public $curah_hujan_hari_ini;
        public $curah_hujan_per_menit;
        public $curah_hujan_per_jam;
        public $curah_hujan_per_hari;
        public $cuaca;
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
                        jumlah_tip = :jumlah_tip, 
                        curah_hujan_hari_ini = :curah_hujan_hari_ini,
                        curah_hujan_per_menit = :curah_hujan_per_menit, 
                        curah_hujan_per_jam = :curah_hujan_per_jam,
                        curah_hujan_per_hari = :curah_hujan_per_hari,
                        cuaca = :cuaca,
                        realtime = :realtime,
                        tanggal = :tanggal";
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->jumlah_tip=htmlspecialchars(strip_tags($this->jumlah_tip));
            $this->curah_hujan_hari_ini=htmlspecialchars(strip_tags($this->curah_hujan_hari_ini));
            $this->curah_hujan_per_menit=htmlspecialchars(strip_tags($this->curah_hujan_per_menit));
            $this->curah_hujan_per_jam=htmlspecialchars(strip_tags($this->curah_hujan_per_jam));
            $this->curah_hujan_per_hari=htmlspecialchars(strip_tags($this->curah_hujan_per_hari));
            $this->cuaca=htmlspecialchars(strip_tags($this->cuaca));
            $this->realtime=htmlspecialchars(strip_tags($this->realtime));
            $this->tanggal=htmlspecialchars(strip_tags($this->tanggal));
        
            $stmt->bindParam(":jumlah_tip", $this->jumlah_tip);
            $stmt->bindParam(":curah_hujan_hari_ini", $this->curah_hujan_hari_ini);
            $stmt->bindParam(":curah_hujan_per_menit", $this->curah_hujan_per_menit);
            $stmt->bindParam(":curah_hujan_per_jam", $this->curah_hujan_per_jam);
            $stmt->bindParam(":curah_hujan_per_hari", $this->curah_hujan_per_hari);
            $stmt->bindParam(":cuaca", $this->cuaca);
            $stmt->bindParam(":realtime", $this->realtime);
            $stmt->bindParam(":tanggal", $this->tanggal);
            if($stmt->execute()){
               return true;
            }
            return false;
        }
    }
?>