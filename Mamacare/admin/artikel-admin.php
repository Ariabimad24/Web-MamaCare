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
if (isset($_GET['message'])) {
    if ($_GET['message'] == 'success') {
        echo "<div class='alert alert-success'>Data berhasil dihapus!</div>";
    } elseif ($_GET['message'] == 'error') {
        echo "<div class='alert alert-danger'>Gagal menghapus data. Silakan coba lagi.</div>";
    }
}
$sql = "SELECT * FROM artikel_kesehatan";
$result = $conn->query($sql);

$base_url = "http://localhost/Mamacare/";
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
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
    
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
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        h1 {
            color: #343a40;
        }
        table {
            background-color: #ffffff;
        }
        form {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        button.btn-primary {
            background-color: #007bff;
            border: none;
        }
        button.btn-primary:hover {
            background-color: #0056b3;
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
</body>
<script>
function editIbuMuda(id) {
    $.ajax({
        url: 'ajax/get_ibu_muda.php',
        type: 'POST',
        data: {id: id},
        success: function(response) {
            const data = JSON.parse(response);
            $('#edit_id_ibu_muda').val(data.id);
            $('#edit_nama_ibu_muda').val(data.nama);
            $('#edit_email_ibu_muda').val(data.email);
            $('#edit_noHP_ibu_muda').val(data.noHP);
            $('#edit_alamat_ibu_muda').val(data.alamat);
            $('#edit_statusKecemasan_ibu_muda').val(data.statusKecemasan);
            $('#edit_nama_anak').val(data.nama_anak);
            $('#current_photo_ibu_muda').attr('src', `${base_url}${data.photo_profile}`);
            $('#editIbuMudaModal').modal('show');
            // Lanjutan script sebelumnya

        }
    });
}

function editNakes(id) {
    $.ajax({
        url: 'ajax/get_nakes.php',
        type: 'POST',
        data: {id: id},
        success: function(response) {
            const data = JSON.parse(response);
            $('#edit_id_nakes').val(data.id);
            $('#edit_nama_nakes').val(data.nama);
            $('#edit_email_nakes').val(data.email);
            $('#edit_noHP_nakes').val(data.noHP);
            $('#edit_alamat_nakes').val(data.alamat);
            $('#edit_spesialisasi_nakes').val(data.spesialisasi);
            $('#edit_kualifikasi_nakes').val(data.kualifikasi_tenaga_kesehatan);
            $('#current_sertifikat_link').attr('href', `${base_url}${data.sertifikat_kedokteran}`);
            $('#editNakesModal').modal('show');
        }
    });
}

function deleteIbuMuda(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data ibu muda akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'ajax/delete_ibu_muda.php',
                type: 'POST',
                data: {id: id},
                success: function(response) {
                    if(response === 'success') {
                        Swal.fire(
                            'Terhapus!',
                            'Data ibu muda berhasil dihapus.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            'Terjadi kesalahan saat menghapus data.',
                            'error'
                        );
                    }
                },
                error: function() {
                    Swal.fire(
                        'Error!',
                        'Terjadi kesalahan pada server.',
                        'error'
                    );
                }
            });
        }
    });
}

function deleteNakes(id) {
    Swal.fire({
        title: 'Apakah Anda yakin?',
        text: "Data nakes akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: 'ajax/delete_nakes.php',
                type: 'POST',
                data: {id: id},
                success: function(response) {
                    if(response === 'success') {
                        Swal.fire(
                            'Terhapus!',
                            'Data nakes berhasil dihapus.',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire(
                            'Error!',
                            'Terjadi kesalahan saat menghapus data.',
                            'error'
                        );
                    }
                },
                error: function() {
                    Swal.fire(
                        'Error!',
                        'Terjadi kesalahan pada server.',
                        'error'
                    );
                }
            });
        }
    });
}

// Handle form submissions
$(document).ready(function() {
    // Form Ibu Muda
    $('#editIbuMudaForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Data ibu muda berhasil diupdate'
                    }).then(() => {
                        $('#editIbuMudaModal').modal('hide');
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat mengupdate data'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan pada server'
                });
            }
        });
    });

    // Form Nakes
    $('#editNakesForm').on('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if(response === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: 'Data nakes berhasil diupdate'
                    }).then(() => {
                        $('#editNakesModal').modal('hide');
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat mengupdate data'
                    });
                }
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan pada server'
                });
            }
        });
    });

    // Preview image when selected
    $('#edit_photo_profile_ibu_muda').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#current_photo_ibu_muda').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
    });
});
</script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

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
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Data Artikel</h1>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content" style="width: 100%; margin: 0 auto;">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Artikel Kesehatan Terbaru</h3>
            </div>
            <div class="card-body p-0">
                <table id="tableArtikel" class="table table-hover">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Gambar</th>
                            <th>Highlight</th>
                            <th>Isi Artikel</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td class='align-middle'>" . $row['judul'] . "</td>";
                        
                        // Path gambar, jika tidak ada, gunakan gambar default
                        $base_url = "http://localhost/Mamacare/";
                        $image_path = !empty($row['gambar']) ? $row['gambar'] : 'uploads/default.jpg';

                        echo "<td class='align-middle text-center'>
                                <img src='" . $base_url . htmlspecialchars($image_path) . "' alt='Image' style='width: 50px; height: 50px; object-fit: cover;'>
                              </td>";
                        
                        echo "<td class='align-middle'>" . substr($row['highlight'], 0, 100) . (strlen($row['highlight']) > 100 ? '...' : '') . "</td>";
                        echo "<td class='align-middle'>" . substr($row['isi'], 0, 100) . (strlen($row['isi']) > 100 ? '...' : '') . "</td>";

                        echo "<td class='align-middle text-center'>
                        <div class='btn-group'>
                            <a href='ajax/artikel/update.php?id=" . htmlspecialchars($row['id'], ENT_QUOTES, 'UTF-8') . "' 
                               class='btn btn-sm btn-info' 
                               title='Edit Artikel'>
                                <i class='fas fa-edit'></i>
                            </a>
                            <a href='ajax/artikel/delete.php?id=" . $row['id'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Apakah Anda yakin ingin menghapus artikel ini?\")'>
                                        <i class='fas fa-trash'></i>
                                    </a>
                                </div>
                              </td>";
                        echo "</tr>";
                    }
                    ?>
                    <tr>
                        <td colspan="4" class="text-center">Tambah Artikel Baru</td>
                        <td class="align-middle text-center">
                            <a href="ajax/artikel/add.php" class="btn btn-sm btn-success">
                                <i class="fas fa-plus"></i> Add
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
<!-- Footer -->
<footer class="main-footer">
        <strong>Copyright &copy; 2024 <a href="#">MamaCare</a>.</strong>
        All rights reserved.
    </footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
