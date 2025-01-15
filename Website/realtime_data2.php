<?php
require_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

$sql = "SELECT * FROM monitoring_power ORDER BY id DESC LIMIT 12"; // Ganti 'nama_tabel' dengan nama tabel yang ingin Anda tampilkan
$stmt = $db->prepare($sql);
$stmt->execute();

// $No = 1; // Inisialisasi variabel untuk nomor urut

if ($stmt->rowCount() > 0) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        // echo "<td>" . $No++ . "</td>";
        echo "<td>" . $row['id'] . "</td>";
        echo "<td>" . $row['tegangan_dinamis'] . "</td>"; 
        echo "<td>" . $row['tegangan_statis'] . "</td>";
        echo "<td>" . $row['arus_dinamis'] . "</td>"; 
        echo "<td>" . $row['arus_statis'] . "</td>";
        echo "<td>" . $row['power_dinamis'] . "</td>";
        echo "<td>" . $row['power_statis'] . "</td>";
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
