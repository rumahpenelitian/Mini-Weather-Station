<?php
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

$limit = 12; // Jumlah data yang ditampilkan per halaman
$page = isset($_GET['page']) ? $_GET['page'] : 1; // Halaman saat ini
$offset = ($page - 1) * $limit; // Data yang ditampilkan pertama kali

$sql = "SELECT * FROM motor_driver ORDER BY id DESC LIMIT $limit OFFSET $offset"; // Ganti 'nama_tabel' dengan nama tabel yang ingin Anda tampilkan
$stmt = $db->prepare($sql);
$stmt->execute();

$No = $offset + 1;

if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $No++ . "</td>";
        // echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['ldrselatan'] . "</td>"; 
        echo "<td>" . $row['ldrutara'] . "</td>"; 
        echo "<td>" . $row['ldrtimur'] . "</td>"; 
        echo "<td>" . $row['ldrbarat'] . "</td>"; 
        echo "<td>" . $row['axis_a'] . "</td>"; 
        echo "<td>" . $row['axis_b'] . "</td>";
        echo "<td>" . $row['realtime'] . "</td>";
        echo "<td>" . $row['tanggal'] . "</td>";
        // echo "<td>" . $row['created_at'] . "</td>";
        // echo "<td>" . $row['update_at'] . "</td>";
        // Tambahkan baris ini untuk setiap kolom yang ingin Anda tampilkan
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>Tidak ada data</td></tr>";
}
?>
