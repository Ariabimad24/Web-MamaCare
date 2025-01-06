<?php
session_start();
include '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    
    // Update status pengingat menjadi selesai
    $stmt = $conn->prepare("UPDATE pengingat_jadwal_kesehatan SET status = 'selesai' WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    $response = ['success' => $stmt->execute()];
    echo json_encode($response);
}