<?php
class Nodemcu_log5{

    private $conn;
    private $db_table = "motor_driver";

    public $id;
    public $ldrselatan;
    public $ldrutara;
    public $ldrtimur;
    public $ldrbarat;
    public $axis_a;
    public $axis_b;
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
                    ldrselatan = :ldrselatan,
                    ldrutara = :ldrutara,
                    ldrtimur = :ldrtimur,
                    ldrbarat = :ldrbarat,
                    axis_a = :axis_a, 
                    axis_b = :axis_b,
                    realtime = :realtime,
                    tanggal = :tanggal";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->ldrselatan=htmlspecialchars(strip_tags($this->ldrselatan));
        $this->ldrutara=htmlspecialchars(strip_tags($this->ldrutara));
        $this->ldrtimur=htmlspecialchars(strip_tags($this->ldrtimur));
        $this->ldrbarat=htmlspecialchars(strip_tags($this->ldrbarat));
        $this->axis_a=htmlspecialchars(strip_tags($this->axis_a));
        $this->axis_b=htmlspecialchars(strip_tags($this->axis_b));
        $this->realtime=htmlspecialchars(strip_tags($this->realtime));
        $this->tanggal=htmlspecialchars(strip_tags($this->tanggal));
    
        $stmt->bindParam(":ldrselatan", $this->ldrselatan);
        $stmt->bindParam(":ldrutara", $this->ldrutara);
        $stmt->bindParam(":ldrtimur", $this->ldrtimur);
        $stmt->bindParam(":ldrbarat", $this->ldrbarat);
        $stmt->bindParam(":axis_a", $this->axis_a);
        $stmt->bindParam(":axis_b", $this->axis_b);
        $stmt->bindParam(":realtime", $this->realtime);
        $stmt->bindParam(":tanggal", $this->tanggal);
            if($stmt->execute()){
               return true;
            }
            return false;
        }
    }
?>