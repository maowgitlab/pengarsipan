<?php
session_start();
include 'DB/koneksi.php';

if (!isset($_SESSION['jabatan']) || $_SESSION['jabatan'] !== 'admin') {
    http_response_code(403);
    echo json_encode([]);
    exit;
}

$query = "SELECT la.*, p.nama_pegawai 
          FROM tb_log_activity la 
          LEFT JOIN tb_pegawai p ON la.user_id = p.id 
          ORDER BY la.login_time DESC";
$result = mysqli_query($koneksi, $query);

$logs = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $logs[] = [
            'user_id' => $row['user_id'],
            'username' => htmlspecialchars($row['username']),
            'nama_pegawai' => htmlspecialchars($row['nama_pegawai']),
            'login_time' => $row['login_time'],
            'ip_address' => htmlspecialchars($row['ip_address'])
        ];
    }
}

echo json_encode($logs);
?>