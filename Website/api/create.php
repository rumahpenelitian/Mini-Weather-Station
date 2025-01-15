<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    include_once '../config/database.php';
    include_once '../class/nodemcu_log.php';

    $database = new Database();
    $db = $database->getConnection();

    $item = new Nodemcu_log($db);
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$data = json_decode(file_get_contents("php://input"));
		$item->suhu = $data->suhu;
		$item->altitude = $data->altitude;
		$item->tekanan = $data->tekanan;
		$item->kelembaban = $data->kelembaban;
        $item->lux = $data->lux;
        $item->raindrop = $data->raindrop;
		$item->realtime = $data->realtime;
		$item->tanggal = date('d-m-Y H:i:s');
	} else {
		die('Wrong request method');
	}
	
    if($item->createLogData()){
        echo 'Data Weahter berhasil diinputkan';
    } else{
        echo 'Data Weahter gagal diinputkan';
    }
?>