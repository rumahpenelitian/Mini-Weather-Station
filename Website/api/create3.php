<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    include_once '../config/database.php';
    include_once '../class/nodemcu_log3.php';

    $database = new Database();
    $db = $database->getConnection();

    $item = new Nodemcu_log3($db);
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$data = json_decode(file_get_contents("php://input"));
		$item->jumlah_tip = $data->jumlah_tip;
		$item->curah_hujan_hari_ini = $data->curah_hujan_hari_ini;
		$item->curah_hujan_per_menit = $data->curah_hujan_per_menit;
		$item->curah_hujan_per_jam = $data->curah_hujan_per_jam;
		$item->curah_hujan_per_hari = $data->curah_hujan_per_hari;
		$item->cuaca = $data->cuaca;
        $item->realtime = $data->realtime;
        $item->tanggal = date('d-m-Y H:i:s');
	} else {
		die('Wrong request method');
	}
	
    if($item->createLogData()){
        echo 'Data Tip Bucket berhasil diinputkan';
    } else{
        echo 'Data Tip Bucket gagal diinputkan';
    }
?>