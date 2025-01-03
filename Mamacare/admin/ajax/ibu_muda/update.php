<?php
require '../../../db_connect.php'; // Pastikan koneksi ke database

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Query untuk mengambil data ibu berdasarkan ID
    $sql = "SELECT * FROM ibu_muda WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // Query untuk mengambil semua data anak
    $sqlAnak = "SELECT id, nama FROM anak";
    $resultAnak = $conn->query($sqlAnak);
}

if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $alamat = $_POST['alamat'];
    $statusKecemasan = $_POST['statusKecemasan'];
    $noHP = $_POST['noHP'];
    $nama_anak_id = $_POST['nama_anak_id']; // Ambil input nama anak
    $password = $_POST['password'];

    // Jalur foto default (jika tidak ada file baru yang diupload)
    $photo_profile = $row['photo_profile'];

    // Jika ada file foto yang diupload
    if (isset($_FILES['photo_profile']) && $_FILES['photo_profile']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../../../uploads/';
        $photoExt = pathinfo($_FILES['photo_profile']['name'], PATHINFO_EXTENSION);
        $uniqueFileName = uniqid() . '_' . bin2hex(random_bytes(5)) . '.' . $photoExt;
        $uploadFile = $uploadDir . $uniqueFileName;

        if (move_uploaded_file($_FILES['photo_profile']['tmp_name'], $uploadFile)) {
            $photo_profile = 'uploads/' . $uniqueFileName;
        } else {
            echo "Gagal mengunggah file foto.";
        }
    }

    // Jika password diubah
$sqlUpdatePassword = "";
$params = [$nama, $email, $alamat, $statusKecemasan, $noHP, $photo_profile, $nama_anak_id];
$types = "ssssssi";

if (!empty($password)) {
    // Tidak melakukan hashing, simpan password dalam bentuk plaintext
    $sqlUpdatePassword = ", password = ?";
    $params[] = $password; // Password plaintext
    $types .= "s";
}

$params[] = $id;
$types .= "i";

    // Query update data
    $sql = "UPDATE ibu_muda SET nama = ?, email = ?, alamat = ?, statusKecemasan = ?, noHP = ?, photo_profile = ?, nama_anak_id = ? $sqlUpdatePassword WHERE id = ?";
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
    <title>Update Data Ibu Muda</title>
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
        <h2>Form Update Data Ibu Muda</h2>
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
                            <td><label for="alamat">Alamat:</label></td>
                            <td><textarea id="alamat" name="alamat" class="form-control" required><?= htmlspecialchars($row['alamat']) ?></textarea></td>
                        </tr>
                        <tr>
                            <td><label for="statusKecemasan">Status Kecemasan:</label></td>
                            <td>
                                <select id="statusKecemasan" name="statusKecemasan" class="form-control" required>
                                    <option value="stunting" <?= ($row['statusKecemasan'] == 'stunting') ? 'selected' : '' ?>>Kekhawatiran Stunting</option>
                                    <option value="underweight" <?= ($row['statusKecemasan'] == 'underweight') ? 'selected' : '' ?>>Berat Badan Kurang</option>
                                    <option value="growth" <?= ($row['statusKecemasan'] == 'growth') ? 'selected' : '' ?>>Pertumbuhan Anak</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><label for="noHP">Nomor HP:</label></td>
                            <td><input type="text" id="noHP" name="noHP" class="form-control" value="<?= htmlspecialchars($row['noHP']) ?>" required></td>
                        </tr>
                        <tr>
    <td><label for="nama_anak_id">Nama Anak:</label></td>
    <td>
        <select id="nama_anak_id" name="nama_anak_id" class="form-control" required>
            <option value="">Pilih Nama Anak</option>
            <?php
            // Query untuk mengambil semua nama anak
            while ($anak = $resultAnak->fetch_assoc()) {
                // Menandai nama anak yang sudah dipilih sebelumnya
                $selected = ($anak['id'] == $row['nama_anak_id']) ? 'selected' : '';
                echo "<option value='" . $anak['id'] . "' $selected>" . htmlspecialchars($anak['nama']) . "</option>";
            }
            ?>
        </select>
    </td>
</tr>
                        <tr>
                            <td><label for="photo_profile">Photo Profile:</label></td>
                            <td>
                                <input type="file" id="photo_profile" name="photo_profile" class="form-control">
                                <small>Current Photo: 
                                    <img src="../../../<?= htmlspecialchars($row['photo_profile']) ?>" alt="Profile" style="max-width: 50px; max-height: 50px;">
                                </small>
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
</body>
</html>
