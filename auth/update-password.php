<?php
session_start();
require_once '../db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $user_type = $_POST['user_type'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    $table = ($user_type === 'nakes') ? 'nakes' : 'ibu_muda';

    // Cek apakah password sama
    if ($new_password !== $confirm_password) {
        $_SESSION['error'] = "Password tidak cocok";
        header("Location: ../auth/change-password.php?token=" . urlencode($token) . "&type=" . urlencode($user_type));
        exit();
    }

    // Cek panjang password
    if (strlen($new_password) < 8) {
        $_SESSION['error'] = "Password minimal 8 karakter";
        header("Location: ../auth/change-password.php?token=" . urlencode($token) . "&type=" . urlencode($user_type));
        exit();
    }

    // Update password langsung tanpa hash
    $stmt = $conn->prepare("UPDATE $table SET password = ?, reset_token = NULL WHERE reset_token = ?");
    $stmt->bind_param("ss", $new_password, $token);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Password berhasil diubah. Silakan login dengan password baru.";
        header("Location: ../auth/login.php");
    } else {
        $_SESSION['error'] = "Gagal mengubah password. Silakan coba lagi.";
        header("Location: ../auth/change-password.php?token=" . urlencode($token) . "&type=" . urlencode($user_type));
    }
    exit();
}
?>