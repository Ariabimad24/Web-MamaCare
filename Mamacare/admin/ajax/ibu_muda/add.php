<?php
require '../../../db_connect.php'; // Pastikan koneksi terhubung sebelum digunakan

if (isset($_POST['submit'])) {
    // Data ibu muda
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $alamat = $_POST['alamat'];
    $statusKecemasan = $_POST['statusKecemasan'];
    $noHP = $_POST['noHP'];
    $password = $_POST['password']; // Ambil password dari form
    $photo_profile = null;

    // Cek apakah file gambar diupload
    if (isset($_FILES['photo_profile']) && $_FILES['photo_profile']['error'] === UPLOAD_ERR_OK) {
        // Tentukan direktori tempat upload file
        $uploadDir = '../../../uploads/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Membuat folder jika belum ada
        }

        // Mengambil nama file dan menambahkan prefix agar unik
        $fileName = uniqid() . "_" . basename($_FILES['photo_profile']['name']);
        $uploadFilePath = $uploadDir . $fileName;

        // Validasi file gambar
        $allowedFileTypes = ['jpg', 'jpeg', 'png', 'gif'];
        $imageFileType = strtolower(pathinfo($uploadFilePath, PATHINFO_EXTENSION));

        // Cek apakah file yang diupload adalah gambar
        $check = getimagesize($_FILES["photo_profile"]["tmp_name"]);
        if($check === false) {
            echo "<script>alert('File bukan merupakan gambar.'); window.history.back();</script>";
            exit();
        }

        // Cek ukuran file (maksimal 5MB)
        if ($_FILES["photo_profile"]["size"] > 5000000) {
            echo "<script>alert('Ukuran file terlalu besar (maksimal 5MB).'); window.history.back();</script>";
            exit();
        }

        // Cek apakah ekstensi file diperbolehkan
        if (!in_array($imageFileType, $allowedFileTypes)) {
            echo "<script>alert('Hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.'); window.history.back();</script>";
            exit();
        }

        // Jika lolos semua pengecekan, pindahkan file ke folder tujuan
        if (move_uploaded_file($_FILES["photo_profile"]["tmp_name"], $uploadFilePath)) {
            $photo_profile = "uploads/" . $fileName; // Simpan path relatif ke folder uploads
        } else {
            echo "<script>alert('Maaf, terjadi kesalahan saat mengunggah file.'); window.history.back();</script>";
            exit();
        }
    }

    // Query untuk menambahkan data ibu muda
    $sql = "INSERT INTO ibu_muda (nama, email, alamat, statusKecemasan, noHP, photo_profile, password) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $nama, $email, $alamat, $statusKecemasan, $noHP, $photo_profile, $password); // Bind parameter sesuai tipe data
    $stmt->execute();

    // Mendapatkan ibu_muda_id yang baru saja ditambahkan
    $ibu_muda_id = $stmt->insert_id;

    // Data anak
    $nama_anak = $_POST['nama_anak'];
    $umur_anak = $_POST['umur_anak'];

    // Query untuk menambahkan data anak
    $sql_anak = "INSERT INTO anak (nama, umur, ibu_muda_id) VALUES (?, ?, ?)";
    $stmt_anak = $conn->prepare($sql_anak);
    $stmt_anak->bind_param("sii", $nama_anak, $umur_anak, $ibu_muda_id); // Bind parameter sesuai tipe data
    $stmt_anak->execute(); // Eksekusi query untuk menambahkan data anak

    // Menampilkan pesan sukses jika data berhasil ditambahkan
    echo "<script>alert('Data ibu muda dan anak berhasil ditambahkan.'); window.location.href = '/Mamacare/admin/data-pengguna.php';</script>";
    exit();
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Ibu Muda</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
        }
        .form-container {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .table td, .table th {
            padding: 10px;
        }
        body {
            zoom: 0.7;
        }
    </style>
</head>
<body class="hold-transition sidebar-mini">
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="../../../auth/login.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </nav>
    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="index.php" class="brand-link">
            <img src="../../../assets/images/icon.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">Admin MamaCare</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <li class="nav-item">
                        <a href="../../admin.php" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../../data-pengguna.php" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Data Pengguna</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../../artikel-admin.php" class="nav-link">
                            <i class="nav-icon fas fa-newspaper"></i>
                            <p>Artikel</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="../../modul-admin.php" class="nav-link">
                            <i class="nav-icon fas fa-heartbeat"></i>
                            <p>Modul Kesehatan</p>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <div class="content-wrapper">
        <h2>Form Tambah Data Ibu dan Anak</h2>
        <div class="form-container">
        <form action="add.php" method="POST" enctype="multipart/form-data">
    <div class="table-responsive">
        <table class="table table-bordered">
            <!-- Form untuk ibu muda -->
            <tr>
                <td><label for="nama">Nama:</label></td>
                <td><input type="text" id="nama" name="nama" class="form-control" required></td>
            </tr>
            <tr>
                <td><label for="email">Email:</label></td>
                <td><input type="email" id="email" name="email" class="form-control" required></td>
            </tr>
            <tr>
                <td><label for="password">Password:</label></td>
                <td><input type="password" id="password" name="password" class="form-control" required></td>
            </tr>
            <tr>
                <td><label for="alamat">Alamat:</label></td>
                <td><textarea id="alamat" name="alamat" class="form-control" required></textarea></td>
            </tr>
            <tr>
                <td><label for="statusKecemasan">Status Kecemasan:</label></td>
                <td>
                    <select id="statusKecemasan" name="statusKecemasan" class="form-control" required>
                        <option value="stunting">Kekhawatiran Stunting</option>
                        <option value="underweight">Berat Badan Kurang</option>
                        <option value="growth">Pertumbuhan Anak</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td><label for="noHP">Nomor HP:</label></td>
                <td><input type="text" id="noHP" name="noHP" class="form-control" required></td>
            </tr>
            <tr>
                <td><label for="photo_profile">Photo Profile:</label></td>
                <td><input type="file" id="photo_profile" name="photo_profile" class="form-control"></td>
            </tr>
            <!-- Form untuk data anak -->
            <tr>
                <td><label for="nama_anak">Nama Anak:</label></td>
                <td><input type="text" id="nama_anak" name="nama_anak" class="form-control" required></td>
            </tr>
            <tr>
                <td><label for="umur_anak">Umur Anak:</label></td>
                <td><input type="number" id="umur_anak" name="umur_anak" class="form-control" required></td>
            </tr>
        </table>
    </div>
    <button type="submit" name="submit" class="btn btn-primary">Tambah</button>
</form>
<script>
    document.getElementById('form-add').onsubmit = function() {
        document.getElementById('submit-btn').disabled = true;
        document.getElementById('form-add').submit();  // Submit form only once
    };
</script>

        </div>
    </div>
</body>
</html>
