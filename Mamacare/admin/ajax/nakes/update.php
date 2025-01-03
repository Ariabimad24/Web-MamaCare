<?php
require '../../../db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Query untuk mengambil data nakes berdasarkan ID
    $sql = "SELECT * FROM nakes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        die("Data tidak ditemukan.");
    }
}

if (isset($_POST['submit'])) {
    $id = $_GET['id']; // Ambil ID dari URL
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $noHP = $_POST['noHP'];
    $alamat = $_POST['alamat'];
    $spesialisasi = $_POST['spesialisasi'];
    $kualifikasi = $_POST['kualifikasi_tenaga_kesehatan'];
    $password = $_POST['password'];

    // Validasi input email dan nomor HP
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Format email tidak valid.");
    }

    if (!preg_match("/^[0-9]{10,15}$/", $noHP)) {
        die("Nomor HP tidak valid. Harus terdiri dari 10-15 digit.");
    }

    // Jalur sertifikat default (jika tidak ada file baru yang diupload)
    $sertifikat_kedokteran = $row['sertifikat_kedokteran'];

    // Handle upload sertifikat jika ada
    if (isset($_FILES['sertifikat_kedokteran']) && $_FILES['sertifikat_kedokteran']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../../uploads/sertifikat/';
        
        // Buat folder jika belum ada
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $sertifikat_kedokteran_name = uniqid() . '_' . basename($_FILES['sertifikat_kedokteran']['name']);
        $uploadFile = $uploadDir . $sertifikat_kedokteran_name;

        // Validasi file yang diupload
        $fileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        if (!in_array($fileType, ['pdf', 'jpg', 'jpeg', 'png'])) {
            die("Hanya file PDF, JPG, JPEG, dan PNG yang diperbolehkan.");
        }

        if (move_uploaded_file($_FILES['sertifikat_kedokteran']['tmp_name'], $uploadFile)) {
            $sertifikat_kedokteran = 'uploads/sertifikat/' . $sertifikat_kedokteran_name;
        } else {
            echo "Gagal mengupload sertifikat.";
        }
    }

    // Persiapkan query dan parameter
    $sqlUpdatePassword = "";
    $params = [$nama, $email, $noHP, $alamat, $spesialisasi, $kualifikasi, $sertifikat_kedokteran];
    $types = "sssssss";

    if (!empty($password)) {
        // Tidak melakukan hashing, simpan password dalam bentuk plaintext
        $sqlUpdatePassword = ", password = ?";
        $params[] = $password; // Password plaintext
        $types .= "s";
    }

    $params[] = $id;
    $types .= "i";

    // Query update data
    $sql = "UPDATE nakes SET 
            nama = ?, 
            email = ?, 
            noHP = ?, 
            alamat = ?, 
            spesialisasi = ?, 
            kualifikasi_tenaga_kesehatan = ?, 
            sertifikat_kedokteran = ? 
            $sqlUpdatePassword 
            WHERE id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo "<script>alert('Data berhasil diperbarui.'); window.location.href = '/Mamacare/admin/data-pengguna.php';</script>";
    } else {
        echo "Gagal memperbarui data: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Data Nakes</title>
    <!-- Bootstrap CSS -->
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
    <!-- Navbar -->
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
        <a href="index.php" class="brand-link">
            <img src="../../../assets/images/icon.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">Admin MamaCare</span>
        </a>
        <div class="sidebar">
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

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <h2>Update Data Nakes</h2>
        <div class="form-container">
            <form action="update.php?id=<?= $id ?>" method="POST" enctype="multipart/form-data">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <td><label for="nama">Nama:</label></td>
                            <td><input type="text" id="nama" name="nama" class="form-control" value="<?= htmlspecialchars($row['nama']) ?>" required></td>
                        </tr>
                        <tr>
                            <td><label for="email">Email:</label></td>
                            <td><input type="email" id="email" name="email" class="form-control" value="<?= htmlspecialchars($row['email']) ?>" required></td>
                        </tr>
                        <tr>
                            <td><label for="password">Password:</label></td>
                            <td><input type="password" id="password" name="password" class="form-control"></td>
                        </tr>
                        <tr>
                            <td><label for="noHP">Nomor HP:</label></td>
                            <td><input type="text" id="noHP" name="noHP" class="form-control" value="<?= htmlspecialchars($row['noHP']) ?>" required></td>
                        </tr>
                        <tr>
                            <td><label for="alamat">Alamat:</label></td>
                            <td><textarea id="alamat" name="alamat" class="form-control" required><?= htmlspecialchars($row['alamat']) ?></textarea></td>
                        </tr>
                        <tr>
                            <td><label for="spesialisasi">Spesialisasi:</label></td>
                            <td><input type="text" id="spesialisasi" name="spesialisasi" class="form-control" value="<?= htmlspecialchars($row['spesialisasi']) ?>" required></td>
                        </tr>
                        <tr>
                            <td><label for="kualifikasi_tenaga_kesehatan">Kualifikasi:</label></td>
                            <td><input type="text" id="kualifikasi_tenaga_kesehatan" name="kualifikasi_tenaga_kesehatan" class="form-control" value="<?= htmlspecialchars($row['kualifikasi_tenaga_kesehatan']) ?>" required></td>
                        </tr>
                        <tr>
                            <td><label for="sertifikat_kedokteran">Sertifikat Kedokteran:</label></td>
                            <td>
                                <input type="file" id="sertifikat_kedokteran" name="sertifikat_kedokteran" class="form-control">
                                <small>Current File: <?= htmlspecialchars($row['sertifikat_kedokteran']) ?></small>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button type="submit" name="submit" class="btn btn-primary">Update</button>
                                <a href="/Mamacare/admin/data-pengguna.php" class="btn btn-secondary">Cancel</a>
                            </td>
                        </tr>
                    </table>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
</body>
</html>