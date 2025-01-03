<?php
session_start();
include '../db_connect.php';

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Tambahkan error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['ibu_muda_id'])) {
    $_SESSION['error_message'] = "Anda tidak memiliki akses untuk membuat pengingat.";
    header("Location: ../dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form dengan nama field yang benar
    $nama_anak = trim(htmlspecialchars($_POST['nama_anak']));
    $waktu_pengingat = $_POST['waktu_pengingat']; // Sesuaikan dengan name di form
    $instruksi = trim(htmlspecialchars($_POST['instruksi']));
    $ibu_muda_id = $_SESSION['ibu_muda_id'];
   
    // Validasi input yang wajib saja
    if (empty($nama_anak) || empty($waktu_pengingat)) {
        $_SESSION['error_message'] = "Nama anak dan waktu pengingat harus diisi!";
        header("Location: form-pengingat.php");
        exit();
    }

    try {
        $sql = "INSERT INTO pengingat_jadwal_kesehatan (namaAnak, waktuPengingat, instruksi_arahan_dokter, ibu_muda_id)
                VALUES (?, ?, ?, ?)";
       
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare statement gagal: " . $conn->error);
        }

        $stmt->bind_param("sssi", $nama_anak, $waktu_pengingat, $instruksi, $ibu_muda_id);
       
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Pengingat berhasil dibuat!";
            header("Location: menu-pengingat.php");
            exit(); // Tambahkan exit() setelah redirect
        } else {
            throw new Exception($stmt->error);
        }
       
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Gagal membuat pengingat: " . $e->getMessage();
        header("Location: form-pengingat.php");
        exit();
    }
}
?>