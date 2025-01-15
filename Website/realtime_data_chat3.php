<?php
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

$data = [
    'total_tip' => [],
    'curah_hujan_hari_ini' => [],
    'curah_hujan_per_hari' => [],
    'realtime' => []
];

$sql = "SELECT * FROM tipbucket ORDER BY id DESC "; // Ambil semua data dari tabel tipbucket
$stmt = $db->prepare($sql);
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $data['total_tip'][] = (float)$row['jumlah_tip'];
    $data['curah_hujan_hari_ini'][] = (float)$row['curah_hujan_hari_ini'];
    $data['curah_hujan_per_hari'][] = (float)$row['curah_hujan_per_hari'];
    $data['realtime'][] = $row['realtime']; // Format kategori jika diperlukan
}

// Encode data ke format JSON
echo json_encode($data);
?>
