<?php
session_start();
require_once '../db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $user_type = $_POST['user_type'];
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Format email tidak valid";
        header("Location: views/forgot.php");
        exit();
    }

    $table = ($user_type === 'nakes') ? 'nakes' : 'ibu_muda';
    
    // Cek email di database
    $stmt = $conn->prepare("SELECT id FROM $table WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate token
        $token = bin2hex(random_bytes(32));
        
        // Simpan token
        $stmt = $conn->prepare("UPDATE $table SET reset_token = ? WHERE email = ?");
        $stmt->bind_param("ss", $token, $email);
        $stmt->execute();

        // Redirect ke halaman change password dengan token
        header("Location: change-password.php?token=" . $token . "&type=" . $user_type);
    } else {
        $_SESSION['error'] = "Email tidak ditemukan";
        header("Location: forgot.php");
    }
    exit();
}