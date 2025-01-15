<?php
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

$data = [
    'rps_selatan' => [],
    'rps_utara' => [],
    'rps_timur' => [],
    'rps_barat' => [],
    'realtime' => []
];

// Query to fetch wind weather data
$sql = "SELECT * FROM wind_weather ORDER BY id DESC";
$stmt = $db->prepare($sql);
$stmt->execute();

// Fetch data and categorize it based on wind direction
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    if ($row['arah_angin'] == 'Selatan') {
        $data['rps_selatan'][] = $row['rps']; // Assuming column name is 'rps'
    }
    if ($row['arah_angin'] == 'Utara') {
        $data['rps_utara'][] = $row['rps'];
    }
    if ($row['arah_angin'] == 'Timur') {
        $data['rps_timur'][] = $row['rps'];
    }
    if ($row['arah_angin'] == 'Barat') {
        $data['rps_barat'][] = $row['rps'];
    }
    if ($row['arah_angin'] == 'Barat Laut') {
        $data['rps_barat'][] = $row['rps'];
    }
    if ($row['arah_angin'] == 'Tenggara') {
        $data['rps_barat'][] = $row['rps'];
    }
}

// Encode data to JSON format
echo json_encode($data);
?>
