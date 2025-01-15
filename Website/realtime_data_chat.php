<?php
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

$data = [
    'temperature' => [],
    'humidity' => [],
    'altitude' => [],
    'pressure' => [],
    'lux' => [],
    'realtime' => []
];

$sql = "SELECT * FROM weather ORDER BY id DESC"; // Ambil semua data dari tabel weather
$stmt = $db->prepare($sql);
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $data['temperature'][] = (float)$row['suhu']; // Pastikan data numerik
    $data['humidity'][] = (float)$row['kelembaban']; // Pastikan data numerik
    $data['altitude'][] = (float)$row['altitude']; // Pastikan data numerik
    $data['pressure'][] = (float)$row['tekanan']; // Pastikan data numerik
    $data['lux'][] = (float)$row['lux']; // Pastikan data numerik
    $data['realtime'][] = $row['realtime']; // Format kategori jika diperlukan
}

// Encode data ke format JSON
echo json_encode($data);
?>