<?php
session_start();
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    
    // Pastikan user yang sedang login yang memiliki pengingat ini
    $stmt = $conn->prepare("UPDATE pengingat_jadwal_kesehatan 
                           SET status = ? 
                           WHERE id = ? AND ibu_muda_id = ?");
    
    $stmt->bind_param("sii", $status, $id, $_SESSION['ibu_muda_id']);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
}