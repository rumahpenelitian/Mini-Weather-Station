<?php
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

// $sql = "SELECT * FROM weather ORDER BY id DESC LIMIT 100"; // Ganti 'weather' dengan nama tabel yang ingin Anda tampilkan
// $stmt = $db->prepare($sql);
// $stmt->execute();

// Mendapatkan nomor halaman dari parameter GET, default ke 1 jika tidak ada
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 12; // Limit data yang ingin ditampilkan
$offset = ($page - 1) * $limit; // Menghitung offset

$sql = "SELECT * FROM weather ORDER BY id DESC LIMIT :limit OFFSET :offset";
$stmt = $db->prepare($sql);
$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

$No = 1; // Inisialisasi variabel untuk nomor urut

if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $No++ . "</td>"; 
        // echo "<td>" . $row['id'] . "</td>"; 
        echo "<td>" . $row['suhu'] . " °C </td>";
        echo "<td>" . $row['altitude'] . "</td>"; 
        echo "<td>" . $row['tekanan'] . " hPa </td>";
        echo "<td>" . $row['kelembaban'] . " % </td>";
        echo "<td>" . $row['lux'] . "</td>";
        echo "<td>" . $row['raindrop'] . "</td>";
        echo "<td>" . $row['realtime'] . "</td>";
        echo "<td>" . $row['tanggal'] . "</td>";
        // echo "<td>" . $row['created_at'] . "</td>";
        // echo "<td>" . $row['update_at'] . "</td>";
        // Tambahkan baris ini untuk setiap kolom yang ingin Anda tampilkan
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='9'>Tidak ada data</td></tr>";
}
?>
