<?php
session_start();
include '../db_connect.php';

// Menerima id baik dari GET maupun POST
$id = isset($_POST['id']) ? $_POST['id'] : (isset($_GET['id']) ? $_GET['id'] : null);

if ($id) {
    // Pastikan id adalah angka
    $id = intval($id);
    
    $stmt = $conn->prepare("DELETE FROM pengingat_jadwal_kesehatan WHERE id = ?");
    $stmt->bind_param("i", $id);
   
    if ($stmt->execute()) {
        $_SESSION['message'] = "Pengingat berhasil dihapus";
    } else {
        $_SESSION['error'] = "Gagal menghapus pengingat: " . $conn->error;
    }
} else {
    $_SESSION['error'] = "ID pengingat tidak valid";
}

header('Location: ../pengingat/menu-pengingat.php');
exit();