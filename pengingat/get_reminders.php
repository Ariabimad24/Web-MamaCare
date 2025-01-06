<?php
// Pengingat/get_reminders.php
require_once '../db_connect.php'; // Mengambil koneksi dari db_connect.php

// Set timezone sesuai dengan zona waktu yang diinginkan (misalnya Asia/Jakarta)
date_default_timezone_set('Asia/Jakarta');

// Query untuk mengambil data pengingat yang aktif dan waktunya sudah lewat
$query = "SELECT 
    id,
    namaAnak,
    waktuPengingat,
    instruksi_arahan_dokter,
    status 
FROM pengingat_jadwal_kesehatan 
WHERE status = 'active' 
AND waktuPengingat <= NOW() 
ORDER BY waktuPengingat DESC";

// Menggunakan $conn untuk menjalankan query
$result = $conn->query($query); // Gantilah $koneksi dengan $conn
$reminders = array();

if ($result) {
    while ($row = $result->fetch_assoc()) {
        // Format waktuPengingat menjadi format ISO 8601 (YYYY-MM-DDTHH:MM:SS+00:00)
        $formattedTime = date('c', strtotime($row['waktuPengingat']));
        
        $reminders[] = array(
            'id' => $row['id'],
            'namaAnak' => $row['namaAnak'],
            'waktuPengingat' => $formattedTime, // Menggunakan format ISO 8601
            'instruksi_arahan_dokter' => $row['instruksi_arahan_dokter'],
            'status' => $row['status']
        );
    }
}

header('Content-Type: application/json');
echo json_encode($reminders);
?>
