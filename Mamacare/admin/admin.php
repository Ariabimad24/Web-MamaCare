<?php
include '../db_connect.php';

if(isset($_GET['type']) && isset($_GET['id'])) {
    $type = $_GET['type'];
    $id = $_GET['id'];
    
    if($type == 'ibu') {
        $table = 'ibu_muda';
    } else if($type == 'nakes') {
        $table = 'nakes';
    } else {
        die("Tipe pengguna tidak valid");
    }
    
    $query = "DELETE FROM $table WHERE id = $id";
    
    if($conn->query($query) === TRUE) {
        echo "<script>
                alert('Data berhasil dihapus');
                window.location.href = 'data_pengguna.php';
              </script>";
    } else {
        echo "<script>
                alert('Error: " . $conn->error . "');
                window.location.href = 'data_pengguna.php';
              </script>";
    }
}
$query_count_ibu = "SELECT COUNT(*) as total_ibu FROM ibu_muda";
$result_ibu = $conn->query($query_count_ibu);
$total_ibu = $result_ibu->fetch_assoc()['total_ibu'];

// Mengambil jumlah data nakes
$query_count_nakes = "SELECT COUNT(*) as total_nakes FROM nakes";
$result_nakes = $conn->query($query_count_nakes);
$total_nakes = $result_nakes->fetch_assoc()['total_nakes'];

// Mengambil jumlah artikel
$query_count_artikel = "SELECT COUNT(*) as total_artikel FROM artikel_kesehatan";
$result_artikel = $conn->query($query_count_artikel);
$total_artikel = $result_artikel->fetch_assoc()['total_artikel'];

// Mengambil jumlah artikel
$query_count_modul = "SELECT COUNT(*) as total_modul FROM modul_kesehatan";
$result_modul = $conn->query($query_count_modul);
$total_modul = $result_modul->fetch_assoc()['total_modul'];

// Total pengguna (ibu + nakes)
$total_pengguna = $total_ibu + $total_nakes;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
body {
    zoom: 0.7;
    background: #f4f6f9;
}

/* Layout Structure */
.wrapper {
    display: flex;
    min-height: 100vh;
}

/* Sidebar */
.main-sidebar {
    position: fixed;
    top: 0;
    left: 0;
    width: 250px;
    height: 100%;
    background: #343a40;
    z-index: 1000;
}

.sidebar {
    height: 100%;
    overflow-y: auto;
    padding-bottom: 60px;
}

.brand-link {
    color: #fff;
    padding: 15px;
    display: block;
    font-size: 20px;
}

.brand-image {
    width: 33px;
    height: 33px;
    margin-right: 10px;
}

/* Main Content */
.content-wrapper {
    margin-left: 250px;
    width: calc(100% - 250px);
    min-height: calc(100vh - 60px);
    padding-top: 60px;
    padding-bottom: 60px;
}

/* Header */
.main-header {
    position: fixed;
    top: 0;
    right: 0; /* Membuatnya melebar ke tepi kanan */
    left: 0; /* Membuatnya melebar ke tepi kiri */
    height: 60px;
    background: #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    z-index: 999;
}


/* Content Area */
.content {
    padding: 20px;
}

.card {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.05);
    margin-bottom: 20px;
}

.card-header {
    padding: 15px 20px;
    border-bottom: 1px solid #eee;
}

.card-header h3 {
    margin: 0;
    font-size: 18px;
    color: #333;
}

.card-body {
    padding: 20px;
}

/* Tables */
.table {
    width: 100%;
    margin-bottom: 0;
}

.table th {
    background: #f8f9fa;
    font-weight: 600;
    padding: 12px 15px;
    font-size: 13px;
    border-bottom: 2px solid #dee2e6;
}

.table td {
    padding: 12px 15px;
    vertical-align: middle;
    font-size: 13px;
}

/* DataTables */
.dataTables_wrapper {
    padding: 0;
}

.dataTables_length,
.dataTables_filter {
    padding: 15px;
}

/* Footer */
.main-footer {
    position: fixed;
    bottom: 0;
    left: 0; /* Mengatur posisi ke tepi kiri layar */
    right: 0; /* Mengatur posisi ke tepi kanan layar */
    padding: 15px;
    background: #fff;
    border-top: 1px solid #dee2e6;
    text-align: center;
    z-index: 998;
}


/* Utilities */
.btn-group .btn {
    padding: 5px 10px;
    font-size: 12px;
}

.status-badge {
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 12px;
}

/* Fix Empty Table Message */
.dataTables_info {
    text-align: left; /* Menyelaraskan teks ke kiri */
    font-size: 14px; /* Menyesuaikan ukuran font */
    color: #333; /* Menyesuaikan warna teks */
    margin-top: 5px; /* Menambahkan sedikit jarak dari atas */
    margin-left: 20px; /* Menambahkan margin kiri agar elemen bergerak ke kanan */
    margin-bottom: 10px; /* Menambahkan jarak bawah */
}


/* Fix Table Search */
.dataTables_filter {
    margin-bottom: 2px;
}

.dataTables_filter input {
    margin-left: 5px;
    padding: 4px 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

/* Fix Navigation Menu */
.nav-sidebar .nav-link {
    color: #c2c7d0;
    padding: 12px 15px;
}

.nav-sidebar .nav-link.active {
    color: #fff;
    background: #007bff;
}
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    margin: 0;
    padding: 0;
    background-color: #eef2f7;
}

.container {
    max-width: 1000px;
    margin: 30px auto;
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

h1 {
    text-align: left;
    font-size: 24px;
    color: #333;
    margin-bottom: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    font-size: 14px;
}

th, td {
    text-align: left;
    padding: 12px;
    border: 1px solid #ddd;
}

th {
    background-color: #f4f4f4;
    font-weight: bold;
}

td {
    color: #555;
}

tr:hover {
    background-color: #f1f1f1;
}

.btn {
    display: inline-block;
    padding: 8px 12px;
    color: #fff;
    background-color: #007bff;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    cursor: pointer;
}

.btn:hover {
    background-color: #0056b3;
}

.btn-danger {
    background-color: #dc3545;
}

.btn-danger:hover {
    background-color: #a71d2a;
}

</style>
</head>
<body class="hold-transition sidebar-mini">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="../auth/login.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="index.php" class="brand-link">
            <img src="../assets/images/icon.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">Admin MamaCare</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <li class="nav-item">
                        <a href="admin.php" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="data-pengguna.php" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Data Pengguna</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="artikel-admin.php" class="nav-link">
                            <i class="nav-icon fas fa-newspaper"></i>
                            <p>Artikel</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="modul-admin.php" class="nav-link">
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
        <!-- Content Header -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div>
                </div>
            </div>
        </div>
        

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
            <div class="row">
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-info"><i class="fas fa-users"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Pengguna</span>
                <span class="info-box-number"><?php echo $total_pengguna; ?></span>
                <small>Ibu: <?php echo $total_ibu; ?> | Nakes: <?php echo $total_nakes; ?></small>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-success"><i class="fas fa-newspaper"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total Artikel</span>
                <span class="info-box-number"><?php echo $total_artikel; ?></span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-md-3">
        <div class="info-box">
            <span class="info-box-icon bg-warning"><i class="fas fa-heartbeat"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Modul Kesehatan</span>
                <span class="info-box-number"><?php echo $total_modul; ?></span>
            </div>
        </div>
    </div>
</div>

                <!-- Main row -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Selamat Datang di Panel Admin Mamacare</h3>
                            </div>
                            <img src="../assets/images/foto welcome.png" style="width: 52%; height: auto; display: block; margin: auto;">


                            <div class="card-body">
                                <p>Silakan pilih menu di sidebar untuk mengelola konten website MamaCare</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
<footer class="main-footer">
        <strong>Copyright &copy; 2024 <a href="#">MamaCare</a>.</strong>
        All rights reserved.
    </footer>
<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
</body>
</html>