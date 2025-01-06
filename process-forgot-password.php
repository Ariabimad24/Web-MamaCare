<?php
session_start();
require_once 'config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $user_type = $_POST['user_type']; // Tambahkan input hidden di form untuk membedakan tipe user
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = "Format email tidak valid";
        header("Location: views/forgot-password.php");
        exit();
    }

    // Tentukan tabel berdasarkan tipe user
    $table = ($user_type === 'nakes') ? 'nakes' : 'ibu_muda';
    
    // Cek apakah email ada di database
    $stmt = $conn->prepare("SELECT id FROM $table WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Generate token unik
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Simpan token ke database
        $stmt = $conn->prepare("UPDATE $table SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
        $stmt->bind_param("sss", $token, $expiry, $email);
        $stmt->execute();

        // Kirim email
        $reset_link = "http://yourdomain.com/views/reset-password.php?token=" . $token . "&type=" . $user_type;
        $to = $email;
        $subject = "Reset Password - Mama Care";
        $message = "Halo,\n\nSilakan klik tautan berikut untuk mengatur ulang password Anda:\n" . $reset_link . "\n\nTautan ini akan kedaluwarsa dalam 1 jam.\n\nSalam,\nTim Mama Care";
        $headers = "From: noreply@mamacare.com";

        if(mail($to, $subject, $message, $headers)) {
            $_SESSION['success'] = "Instruksi pengaturan ulang password telah dikirim ke email Anda";
        } else {
            $_SESSION['error'] = "Gagal mengirim email reset. Silakan coba lagi nanti";
        }
    } else {
        $_SESSION['error'] = "Email tidak ditemukan";
    }
    header("Location: views/forgot-password.php");
    exit();
}
?>