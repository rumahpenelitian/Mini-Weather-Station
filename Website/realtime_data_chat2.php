<?php
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

$data = [
    'tegangan_dinamis' => [],
    'tegangan_statis' => [],
    'arus_dinamis' => [],
    'arus_statis' => [],
    'power_dinamis' => [],
    'power_statis' => [],
    'realtime' => []
];

$sql = "SELECT * FROM monitoring_power ORDER BY id DESC ";
$stmt = $db->prepare($sql);
$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $data['tegangan_dinamis'][] = (float)$row['tegangan_dinamis'];
    $data['tegangan_statis'][] = (float)$row['tegangan_statis'];
    $data['arus_dinamis'][] = (float)$row['arus_dinamis'];
    $data['arus_statis'][] = (float)$row['arus_statis'];
    $data['power_dinamis'][] = (float)$row['power_dinamis'];
    $data['power_statis'][] = (float)$row['power_statis'];
    $data['realtime'][] = $row['realtime'];
}

echo json_encode($data);
?>
