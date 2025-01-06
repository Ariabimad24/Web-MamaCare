<?php
// pengingat/snooze_reminder.php
require_once '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];
    
    $query = "UPDATE pengingat_jadwal_kesehatan 
              SET waktuPengingat = DATE_ADD(NOW(), INTERVAL 5 MINUTE) 
              WHERE id = ?";
    
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $koneksi->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}