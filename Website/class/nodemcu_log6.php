<?php
class Nodemcu_log6{

    private $conn;
    private $db_table = "forecast_hes";
    
    public $id;
    public $current_suhu;
    public $level_suhu;
    public $trend_suhu;
    public $forecast_suhu;
    
    public $current_tekanan;
    public $level_tekanan;
    public $trend_tekanan;
    public $forecast_tekanan;
    
    public $current_kelembaban;
    public $level_kelembaban;
    public $trend_kelembaban;
    public $forecast_kelembaban;
    
    public $current_cahaya;
    public $level_cahaya;
    public $trend_cahaya;
    public $forecast_cahaya;
    
    public $current_angin;
    public $level_angin;
    public $trend_angin;
    public $forecast_angin;
    
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
                    current_suhu = :current_suhu,
                    level_suhu = :level_suhu,
                    trend_suhu = :trend_suhu,
                    forecast_suhu = :forecast_suhu,
                    
                    current_tekanan = :current_tekanan,
                    level_tekanan = :level_tekanan,
                    trend_tekanan = :trend_tekanan,
                    forecast_tekanan = :forecast_tekanan,
                    
                    current_kelembaban = :current_kelembaban,
                    level_kelembaban = :level_kelembaban,
                    trend_kelembaban = :trend_kelembaban,
                    forecast_kelembaban = :forecast_kelembaban,
                    
                    current_cahaya = :current_cahaya,
                    level_cahaya = :level_cahaya,
                    trend_cahaya = :trend_cahaya,
                    forecast_cahaya = :forecast_cahaya,
                    
                    current_angin = :current_angin,
                    level_angin = :level_angin,
                    trend_angin = :trend_angin,
                    forecast_angin = :forecast_angin,
                    
                    realtime = :realtime,
                    tanggal = :tanggal";
                    
        $stmt = $this->conn->prepare($sqlQuery);

        $this->current_suhu=htmlspecialchars(strip_tags($this->current_suhu));
        $this->level_suhu=htmlspecialchars(strip_tags($this->level_suhu));
        $this->trend_suhu=htmlspecialchars(strip_tags($this->trend_suhu));
        $this->forecast_suhu=htmlspecialchars(strip_tags($this->forecast_suhu));
        
        $this->current_tekanan=htmlspecialchars(strip_tags($this->current_tekanan));
        $this->level_tekanan=htmlspecialchars(strip_tags($this->level_tekanan));
        $this->trend_tekanan=htmlspecialchars(strip_tags($this->trend_tekanan));
        $this->forecast_tekanan=htmlspecialchars(strip_tags($this->forecast_tekanan));
        
        $this->current_kelembaban=htmlspecialchars(strip_tags($this->current_kelembaban));
        $this->level_kelembaban=htmlspecialchars(strip_tags($this->level_kelembaban));
        $this->trend_kelembaban=htmlspecialchars(strip_tags($this->trend_kelembaban));
        $this->forecast_kelembaban=htmlspecialchars(strip_tags($this->forecast_kelembaban));
        
        $this->current_cahaya=htmlspecialchars(strip_tags($this->current_cahaya));
        $this->level_cahaya=htmlspecialchars(strip_tags($this->level_cahaya));
        $this->trend_cahaya=htmlspecialchars(strip_tags($this->trend_cahaya));
        $this->forecast_cahaya=htmlspecialchars(strip_tags($this->forecast_cahaya));
        
        $this->current_angin=htmlspecialchars(strip_tags($this->current_angin));
        $this->level_angin=htmlspecialchars(strip_tags($this->level_angin));
        $this->trend_angin=htmlspecialchars(strip_tags($this->trend_angin));
        $this->forecast_angin=htmlspecialchars(strip_tags($this->forecast_angin));
        
        $this->realtime=htmlspecialchars(strip_tags($this->realtime));
        $this->tanggal=htmlspecialchars(strip_tags($this->tanggal));
    
    
        $stmt->bindParam(":current_suhu", $this->current_suhu);
        $stmt->bindParam(":level_suhu", $this->level_suhu);
        $stmt->bindParam(":trend_suhu", $this->trend_suhu);
        $stmt->bindParam(":forecast_suhu", $this->forecast_suhu);
        
        $stmt->bindParam(":current_tekanan", $this->current_tekanan);
        $stmt->bindParam(":level_tekanan", $this->level_tekanan);
        $stmt->bindParam(":trend_tekanan", $this->trend_tekanan);
        $stmt->bindParam(":forecast_tekanan", $this->forecast_tekanan);
        
        $stmt->bindParam(":current_kelembaban", $this->current_kelembaban);
        $stmt->bindParam(":level_kelembaban", $this->level_kelembaban);
        $stmt->bindParam(":trend_kelembaban", $this->trend_kelembaban);
        $stmt->bindParam(":forecast_kelembaban", $this->forecast_kelembaban);
        
        $stmt->bindParam(":current_cahaya", $this->current_cahaya);
        $stmt->bindParam(":level_cahaya", $this->level_cahaya);
        $stmt->bindParam(":trend_cahaya", $this->trend_cahaya);
        $stmt->bindParam(":forecast_cahaya", $this->forecast_cahaya);
        
        $stmt->bindParam(":current_angin", $this->current_angin);
        $stmt->bindParam(":level_angin", $this->level_angin);
        $stmt->bindParam(":trend_angin", $this->trend_angin);
        $stmt->bindParam(":forecast_angin", $this->forecast_angin);
        
        $stmt->bindParam(":realtime", $this->realtime);
        $stmt->bindParam(":tanggal", $this->tanggal);
            if($stmt->execute()){
               return true;
            }
            return false;
        }
    }
?>