<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    include_once '../config/database.php';
    include_once '../class/nodemcu_log5.php';

    $database = new Database();
    $db = $database->getConnection();

    $item = new Nodemcu_log5($db);
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$data = json_decode(file_get_contents("php://input"));
        $item->ldrselatan = $data->ldrselatan;
		$item->ldrutara = $data->ldrutara;
		$item->ldrtimur = $data->ldrtimur;
		$item->ldrbarat = $data->ldrbarat;
		$item->axis_a = $data->axis_a;
		$item->axis_b = $data->axis_b;
        $item->realtime = $data->realtime;
        $item->tanggal = date('d-m-Y H:i:s');
	} else {
		die('Wrong request method');
	}
	
    if($item->createLogData()){
        echo 'Data Motor Driver berhasil diinputkan';
    } else{
        echo 'Data Motor Driver gagal diinputkan';
    }
?>