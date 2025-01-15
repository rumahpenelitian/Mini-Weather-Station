<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    include_once '../config/database.php';
    include_once '../class/nodemcu_log4.php';

    $database = new Database();
    $db = $database->getConnection();

    $item = new Nodemcu_log4($db);
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$data = json_decode(file_get_contents("php://input"));
		$item->arah_angin = $data->arah_angin;
		$item->rps = $data->rps;
		$item->velocity_ms = $data->velocity_ms;
		$item->velocity_kmh = $data->velocity_kmh;
        $item->realtime = $data->realtime;
        $item->tanggal = date('d-m-Y H:i:s');
	} else {
		die('Wrong request method');
	}
	
    if($item->createLogData()){
        echo 'Data Wind Weather berhasil diinputkan';
    } else{
        echo 'Data Wind Weather gagal diinputkan';
    }
?>