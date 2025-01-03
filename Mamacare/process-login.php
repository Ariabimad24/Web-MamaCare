<?php
session_start();
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: auth/login.php");
    exit();
}

try {
    // Ambil dan bersihkan input
    $email = trim(htmlspecialchars($_POST['email']));
    $password = $_POST['password'];

    // Cek di tabel admin
    $stmt = $conn->prepare("
        SELECT 
            id,
            nama,
            email,
            password,
            'admin' as user_type
        FROM admin
        WHERE email = ? AND password = ?
    ");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika tidak ditemukan di tabel admin, cek di tabel ibu_muda
    if ($result->num_rows === 0) {
        $stmt = $conn->prepare("
            SELECT 
                id,
                nama,
                email,
                alamat,
                statusKecemasan,
                nama_anak_id,  -- Ganti namaAnak menjadi nama_anak_id
                noHP,
                password,
                admin_id,
                photo_profile,
                'ibu_muda' as user_type
            FROM ibu_muda 
            WHERE email = ? AND password = ?
        ");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();
    }

    // Jika tidak ditemukan di tabel ibu_muda, cek di tabel nakes
    if ($result->num_rows === 0) {
        $stmt = $conn->prepare("
            SELECT 
                id,
                nama,
                email,
                noHP,
                alamat,
                spesialisasi,
                kualifikasi_tenaga_kesehatan,
                password,
                admin_id,
                'nakes' as user_type
            FROM nakes 
            WHERE email = ? AND password = ?
        ");
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();
    }

    if ($result->num_rows === 0) {
        $_SESSION['error'] = "Email atau password salah!";
        header("Location: auth/login.php");
        exit();
    }

    $user = $result->fetch_assoc();

    // Set session sesuai tipe user
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['nama'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_type'] = $user['user_type'];
    $_SESSION['admin_id'] = $user['admin_id'];

    // Redirect based on credentials
    if ($email === 'admin@gmail.com' && $password === 'gecko123') {
        // Admin credentials match
        header("Location: /mamacare/admin/admin.php");
    } else {
        // Other users (ibu_muda, nakes)
        if ($user['user_type'] === 'ibu_muda') {
            $_SESSION['ibu_muda_id'] = $user['id']; // Tambahkan ini untuk menu pengingat
            $_SESSION['status_kecemasan'] = $user['statusKecemasan'];
            $_SESSION['nama_anak_id'] = $user['nama_anak_id'];  // Ganti namaAnak menjadi nama_anak_id
            // Redirect ke dashboard ibu
            header("Location: dashboard.php");
        } else {
            $_SESSION['spesialisasi'] = $user['spesialisasi'];
            $_SESSION['kualifikasi'] = $user['kualifikasi_tenaga_kesehatan'];
            // Redirect ke dashboard nakes
            header("Location: dashboard-nakes.php");
        }
    }

    // Regenerasi ID session untuk keamanan
    session_regenerate_id(true);

    exit();

} catch (Exception $e) {
    $_SESSION['error'] = "Terjadi kesalahan sistem: " . $e->getMessage();
    header("Location: auth/login.php");
    exit();
}
?>
