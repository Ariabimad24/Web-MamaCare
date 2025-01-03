<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    $specialization = filter_var($_POST['specialization'], FILTER_SANITIZE_STRING);
    $qualification = filter_var($_POST['qualification'], FILTER_SANITIZE_STRING);
    $password = $_POST['password']; // Store password as plaintext
    $admin_id = intval($_POST['admin_id']);

    // Handle sertifikat_kedokteran upload
    $sertifikatKedokteran = null;
    if (isset($_FILES['sertifikat_kedokteran']) && $_FILES['sertifikat_kedokteran']['error'] === UPLOAD_ERR_OK) {
        $targetDir = __DIR__ . "/uploads/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = uniqid() . "_" . basename($_FILES['sertifikat_kedokteran']['name']);
        $targetFilePath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        if ($_FILES['sertifikat_kedokteran']['size'] > 10000000) {
            echo "<script>alert('Ukuran file terlalu besar (maksimal 10MB).'); window.history.back();</script>";
            exit();
        }

        $allowedFileTypes = ['pdf', 'jpg', 'jpeg', 'png'];
        if (!in_array($fileType, $allowedFileTypes)) {
            echo "<script>alert('Hanya file PDF, JPG, JPEG, & PNG yang diperbolehkan.'); window.history.back();</script>";
            exit();
        }

        if (move_uploaded_file($_FILES['sertifikat_kedokteran']['tmp_name'], $targetFilePath)) {
            $sertifikatKedokteran = "uploads/" . $fileName;
        } else {
            echo "<script>alert('Maaf, terjadi kesalahan saat mengunggah file.'); window.history.back();</script>";
            exit();
        }
    } else {
        echo "<script>alert('Harap unggah sertifikat kedokteran.'); window.history.back();</script>";
        exit();
    }

    // Handle photo_profile upload
    $photoProfile = null;
    if (isset($_FILES['photo_profile']) && $_FILES['photo_profile']['error'] === UPLOAD_ERR_OK) {
        // Create uploads directory if it doesn't exist
        $targetDir = __DIR__ . "/uploads/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        $fileName = uniqid() . "_" . basename($_FILES['photo_profile']['name']);
        $targetFilePath = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        if ($_FILES['photo_profile']['size'] > 5000000) { // 5MB max
            echo "<script>alert('Ukuran foto terlalu besar (maksimal 5MB).'); window.history.back();</script>";
            exit();
        }

        $allowedFileTypes = ['jpg', 'jpeg', 'png'];
        if (!in_array($fileType, $allowedFileTypes)) {
            echo "<script>alert('Hanya file JPG, JPEG, & PNG yang diperbolehkan untuk foto profil.'); window.history.back();</script>";
            exit();
        }

        if (move_uploaded_file($_FILES['photo_profile']['tmp_name'], $targetFilePath)) {
            $photoProfile = "uploads/" . $fileName;
        } else {
            echo "<script>alert('Maaf, terjadi kesalahan saat mengunggah foto profil.'); window.history.back();</script>";
            exit();
        }
    }

    // Check if email already exists
    $check_email = $conn->prepare("SELECT email FROM nakes WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $result = $check_email->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email sudah terdaftar!'); window.location.href = 'auth/register-nakes.php';</script>";
        exit();
    }

    // Insert data into nakes table
    $stmt = $conn->prepare("INSERT INTO nakes (nama, email, noHP, alamat, spesialisasi, sertifikat_kedokteran, kualifikasi_tenaga_kesehatan, password, admin_id, photo_profile) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $name, $email, $phone, $address, $specialization, $sertifikatKedokteran, $qualification, $password, $admin_id, $photoProfile);

    if ($stmt->execute()) {
        echo "<script>alert('Registrasi berhasil!'); window.location.href = 'auth/login.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan: " . $stmt->error . "'); window.location.href = 'auth/register-nakes.php';</script>";
    }

    $stmt->close();
    $check_email->close();
}
?>
