<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Ambil data dari form
        $nama_anak = trim(htmlspecialchars($_POST['nama_anak']));
        $waktu_catatan = $_POST['waktu_catatan'];
        $height = floatval($_POST['height']);
        $bb = floatval($_POST['bb']);
        $headCircumference = floatval($_POST['headCircumference']);
        $usia = intval($_POST['usia']);
        $jenis_kelamin = ($_POST['gender'] === 'male') ? 'Laki-laki' : 'Perempuan';
        $catatan = trim(htmlspecialchars($_POST['catatan'])); // Ambil data kolom catatan
        
        // Ambil z-scores
        $z_score_height = floatval($_POST['z_score_height']);
        $z_score_weight = floatval($_POST['z_score_weight']);
        $z_score_head = floatval($_POST['z_score_head']);
        
        // Decode status JSON dari form
        $status_data = json_decode($_POST['z_score_status'], true);
        $status_height = $status_data['height'];
        $status_weight = $status_data['weight'];
        $status_head = $status_data['head'];
        
        $ibu_muda_id = $_SESSION['user_id'];

        // Validasi input
        if (empty($nama_anak) || empty($waktu_catatan)) {
            throw new Exception("Semua field harus diisi!");
        }

        // Prepare SQL statement dengan mencocokkan jumlah parameter
        $sql = "INSERT INTO catatan_kesehatan (
            namaAnak,
            waktuCatatan,
            height,
            bb,
            usia,
            jenis_kelamin,
            z_score,
            z_score_weight,
            z_score_head,
            status_height,
            status_weight,
            status_head,
            headCircumference,
            catatan,           -- Tambahkan kolom catatan
            ibu_muda_id
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare statement gagal: " . $conn->error);
        }

        // Bind parameters dengan tipe data yang sesuai
        $stmt->bind_param("ssddisdddsssdsi",
            $nama_anak,         // s (string)
            $waktu_catatan,     // s (string)
            $height,            // d (double)
            $bb,                // d (double)
            $usia,              // i (integer)
            $jenis_kelamin,     // s (string)
            $z_score_height,    // d (double)
            $z_score_weight,    // d (double)
            $z_score_head,      // d (double)
            $status_height,     // s (string)
            $status_weight,     // s (string)
            $status_head,       // s (string)
            $headCircumference, // d (double)
            $catatan,           // s (string) untuk kolom catatan
            $ibu_muda_id        // i (integer)
        );

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Catatan kesehatan berhasil disimpan!";
            header("Location: menu-catatan.php");
            exit();
        } else {
            throw new Exception("Gagal menyimpan data: " . $stmt->error);
        }

    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
} else {
    header("Location: form-catatan.php");
    exit();
}
?>
