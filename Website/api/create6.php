<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    include_once '../config/database.php';
    include_once '../class/nodemcu_log6.php';

    $database = new Database();
    $db = $database->getConnection();

    $item = new Nodemcu_log6($db);
	
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		$data = json_decode(file_get_contents("php://input"));
		
        $item->current_suhu = $data->current_suhu;
		$item->level_suhu = $data->level_suhu;
		$item->trend_suhu = $data->trend_suhu;
		$item->forecast_suhu = $data->forecast_suhu;
		
		$item->current_tekanan = $data->current_tekanan;
		$item->level_tekanan = $data->level_tekanan;
		$item->trend_tekanan = $data->trend_tekanan;
		$item->forecast_tekanan = $data->forecast_tekanan;
		
		$item->current_kelembaban = $data->current_kelembaban;
		$item->level_kelembaban = $data->level_kelembaban;
		$item->trend_kelembaban = $data->trend_kelembaban;
		$item->forecast_kelembaban = $data->forecast_kelembaban;
		
		$item->current_cahaya = $data->current_cahaya;
		$item->level_cahaya = $data->level_cahaya;
		$item->trend_cahaya = $data->trend_cahaya;
		$item->forecast_cahaya = $data->forecast_cahaya;
		
		$item->current_angin = $data->current_angin;
		$item->level_angin = $data->level_angin;
		$item->trend_angin = $data->trend_angin;
		$item->forecast_angin = $data->forecast_angin;
		
        $item->realtime = $data->realtime;
        $item->tanggal = date('d-m-Y H:i:s');
	} else {
		die('Wrong request method');
	}
	
    if($item->createLogData()){
        echo 'Data Forecast berhasil diinputkan';
    } else{
        echo 'Data Forecast Driver gagal diinputkan';
    }
?>