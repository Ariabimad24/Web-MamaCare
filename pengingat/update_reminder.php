<?php
session_start();
include '../db_connect.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

// Ambil data user_id dan user_type
$user_id = $_SESSION['user_id'];
$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : null;

// Pastikan data ID pengingat ada
if (!isset($_POST['id']) || empty($_POST['id'])) {
    echo "ID pengingat tidak ditemukan.";
    exit();
}

$pengingat_id = $_POST['id'];
$waktuPengingat = $_POST['waktuPengingat'];
$tanggalPengingat = $_POST['tanggalPengingat'];
$instruksiArahanDokter = $_POST['instruksiArahanDokter'];
$status = $_POST['status'];

// Periksa apakah data pengingat valid
if (empty($waktuPengingat) || empty($tanggalPengingat) || empty($instruksiArahanDokter) || empty($status)) {
    echo "Semua data harus diisi.";
    exit();
}

// Format tanggal dan waktu untuk memasukkan ke dalam database
$waktuPengingatFormatted = date('Y-m-d H:i:s', strtotime("$tanggalPengingat $waktuPengingat"));

// Update pengingat di database
if ($user_type == 'ibu_muda') {
    // Update pengingat untuk ibu_muda
    $stmt = $conn->prepare("UPDATE pengingat_jadwal_kesehatan SET waktuPengingat = ?, instruksi_arahan_dokter = ?, status = ? WHERE id = ? AND ibu_muda_id = ?");
    $stmt->bind_param("sssii", $waktuPengingatFormatted, $instruksiArahanDokter, $status, $pengingat_id, $user_id);
} elseif ($user_type == 'nakes') {
    // Update pengingat untuk nakes (misalnya hanya bisa mengubah status atau hal lainnya)
    // Asumsikan hanya bisa mengubah status
    $stmt = $conn->prepare("UPDATE pengingat_jadwal_kesehatan SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $pengingat_id);
} else {
    echo "Tipe pengguna tidak valid.";
    exit();
}

if ($stmt->execute()) {
    // Redirect kembali ke menu pengingat setelah sukses update
    header("Location: ../pengingat/menu-pengingat.php?update=success");
    exit();
} else {
    echo "Gagal memperbarui pengingat. Silakan coba lagi.";
}
?>
