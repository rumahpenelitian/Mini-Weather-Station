
<?php
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

$data = [
    'ldr_selatan' => [],
    'ldr_utara' => [],
    'ldr_timur' => [],
    'ldr_barat' => [],
    'realtime' => []
];

$sql = "SELECT * FROM motor_driver ORDER BY id DESC LIMIT 50"; // Ambil semua data dari tabel wind_weather
$stmt = $db->prepare($sql);
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Ganti nama kolom sesuai dengan kolom yang ada di tabel wind_weather
    $data['ldr_selatan'][] = (float)$row['ldrselatan'];
    $data['ldr_utara'][] = (float)$row['ldrutara'];
    $data['ldr_timur'][] = (float)$row['ldrtimur'];
    $data['ldr_barat'][] = (float)$row['ldrbarat'];
    $data['realtime'][] = $row['realtime']; // Format kategori jika diperlukan
}

// Encode data ke format JSON
echo json_encode($data);
?>

