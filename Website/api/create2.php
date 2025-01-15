<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    include_once '../config/database.php';
    include_once '../class/nodemcu_log2.php';

    $database = new Database();
    $db = $database->getConnection();

    $item = new Nodemcu_log2($db);
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$data = json_decode(file_get_contents("php://input"));
		$item->tegangan_dinamis = $data->tegangan_dinamis;
		$item->tegangan_statis = $data->tegangan_statis;
		$item->arus_dinamis = $data->arus_dinamis;
		$item->arus_statis = $data->arus_statis;
		$item->power_dinamis = $data->power_dinamis;
		$item->power_statis = $data->power_statis;
        $item->realtime = $data->realtime;
        $item->tanggal = date('d-m-Y H:i:s');
	} else {
		die('Wrong request method');
	}
	
    if($item->createLogData()){
        echo 'Data Daya Power berhasil diinputkan';
    } else{
        echo 'Data Daya Power gagal diinputkan';
    }
?>