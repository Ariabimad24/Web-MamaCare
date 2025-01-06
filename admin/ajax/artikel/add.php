<?php
require '../../../db_connect.php';

// Helper function to generate article file name
function generateArticleFileName($judul) {
    return strtolower(str_replace(" ", "-", $judul)) . ".php";
}

// Helper function to generate article content
function generateArticleContent($judul, $gambar, $highlights, $isis) {
    return '<?php
session_start();
include "../db_connect.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION["user_id"];
$stmt = $conn->prepare("SELECT photo_profile FROM ibu_muda WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$photo_profile = $user["photo_profile"];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>' . htmlspecialchars($judul) . ' - MamaCare</title>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Abyssinica+SIL&display=swap");

        :root {
            --primary-color: #20B2AA;
            --gradient-start: #17a2b8;
            --gradient-end: #20B2AA;
            font-size: 14px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        .header {
            background: linear-gradient(135deg, #20B2AA, #17a2b8, #48D1CC);
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .logo-wrapper {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo {
            width: 40px;
            height: 40px;
        }

        .logo-text {
            font-family: "Abyssinica SIL", serif;
            font-size: 28px;
            color: #fff;
        }

        .logo-text .care {
            color: #FFD700;
            font-family: "Abyssinica SIL", serif;
        }

        .social-links {
            display: flex;
            gap: 1rem;
        }

        .social-links a {
            color: white;
            text-decoration: none;
            font-size: 1.4rem; /* Increased icon size */
        }

        .social-links a:hover {
            opacity: 0.8;
        }

        .navigation {
            background: rgba(11, 35, 31, 0.4);
            backdrop-filter: blur(8px);
            padding: 1rem 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
        }

        .nav-list {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-list.left {
            justify-content: center;
            flex-grow: 1;
        }

        .nav-list.right {
            justify-content: flex-end;
            gap: 1rem;
            display: flex;
            align-items: center;
        }

        .nav-list a {
            color: white;
            text-decoration: none;
            padding: 0.4rem 0.8rem;
            border-radius: 4px;
            transition: all 0.3s;
            font-weight: 500;
            font-size: 0.9rem;
        }

        .nav-list a:hover {
            background-color: rgba(255,255,255,0.15);
        }

        .nav-list.right img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            object-fit: cover;
        }

        .article-content {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }

        .article-content h2 {
            color: #176864;
            font-size: 24px;
            margin: 30px 0 20px;
            font-weight: 600;
            text-align: center;
        }

        .rounded-image {
            width: 100%;
            height: auto;
            border-radius: 24px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            display: block;
            margin: 20px 0;
        }

        .content-text {
            text-align: justify;
            margin-bottom: 15px;
        }

        footer {
            background: linear-gradient(135deg, #176864 0%, #20B2AA 100%);
            padding: 3rem 2rem;
            color: white;
            margin-top: 3rem;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.1);
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: flex-start; /* Adjusted to align items to top */
        }

        .contact-section {
            text-align: center;
        }

        .contact-section h4 {
            font-size: 1rem;
            margin-bottom: 1rem;
            color: rgb(255, 255, 255);
        }

        .social-section {
            text-align: right;
        }

        .social-section h4 {
            font-size: 1rem;
            margin-bottom: 1rem;
            color: rgb(255, 255, 255);
        }

        .social-icons {
            display: flex;
            gap: 2rem;
            font-size: 1.5rem;
        }

        .social-icons a {
            color: white;
            text-decoration: none; /* Removed underline */
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
        }

        .social-icons a:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-3px);
        }

        @media (max-width: 768px) {
            .footer-content {
                flex-direction: column;
                gap: 2rem;
                text-align: center;
            }
            
            .social-section {
                text-align: center;
            }
            
            .social-icons {
                justify-content: center;
            }
        }
            .sub-title {
            font-family: "Abyssinica SIL", serif;
            font-size: 1.5em;
            font-weight: bold;
            color: #176864;
            margin-top: 20px;
            margin-bottom: 10px;
            text-align: left;
            padding-left: 0;
            margin-left: 0;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo-wrapper">
            <img src="../assets/images/icon.png" alt="MamaCare Logo" class="logo">
            <span class="logo-text">Mama<span class="care">Care</span></span>
        </div>
        <div class="social-links">
            <a href="#"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-linkedin"></i></a>
        </div>
    </header>

    <nav class="navigation">
        <ul class="nav-list left">
            <li><a href="../dashboard.php">Beranda</a></li>
            <li><a href="../all-artikel.php">Artikel</a></li>
            <li><a href="../all-modul.php">Modul</a></li>
            <li><a href="../konsultasi/konsultasi.php">Konsultasi Nakes</a></li>
            <li><a href="../pengingat/menu-pengingat.php">Pengingat</a></li>
            <li><a href="../catatan/menu-catatan.php">Catatan Kesehatan Anak</a></li>
        </ul>
        <ul class="nav-list right">
            <li><a href="../index.php">Logout</a></li>
            <img src="../<?php echo $photo_profile; ?>" alt="Foto Profil">
        </ul>
    </nav>

    <div class="article-content">
        <h2>' . htmlspecialchars($judul) . '</h2>
        <img src="../' . htmlspecialchars($gambar) . '" alt="' . htmlspecialchars($judul) . '" class="rounded-image">
        
        <?php
        $highlights = ' . var_export($highlights, true) . ';
        $isis = ' . var_export($isis, true) . ';
        
        foreach($highlights as $index => $h) {
            echo "<div class=\"sub-title\">" . htmlspecialchars($h) . "</div>";
            echo "<div class=\"content-text\">" . nl2br(htmlspecialchars($isis[$index])) . "</div>";
        }
        ?>
    </div>

    <footer>
        <div class="footer-content">
            <div class="brand-section">
                <div class="logo-wrapper">
                    <img src="../assets/images/icon.png" alt="MamaCare Logo" class="logo">
                    <span class="logo-text">Mama<span class="care">Care</span></span>
                </div>
            </div>
            <div class="contact-section">
                <h4>KONTAK</h4>
                <div class="contact-info">
                    <p>Email: MamaCare@gmail.com</p>
                    <p>No.Telp: +628111477014</p>
                </div>
            </div>
            <div class="social-section">
                <h4>SOCIAL MEDIA</h4>
                <div class="social-icons">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-whatsapp"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
    </footer>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</body>
</html>';

}

// Helper function to generate article card
function generateArticleCard($fileName, $gambar, $judul) {
    return '<a href="artikel/' . $fileName . '" style="text-decoration: none; color: inherit;">
        <div class="artikel-card">
            <div class="artikel-image">
                <img src="' . $gambar . '" alt="' . htmlspecialchars($judul) . '">
            </div>
            <div class="artikel-content">
                <p><strong>' . htmlspecialchars($judul) . '</strong></p>
            </div>
        </div>
    </a>';
}
if (isset($_POST['submit'])) {
    try {
        $judul = $_POST['judul'];
        $highlights = $_POST['highlight'];
        $isis = $_POST['isi'];
       
        $highlight = implode("\n", array_filter($highlights));
        $isi = implode("\n", array_filter($isis));
        $gambar = null;
        // Handle image upload
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../../uploads/';
            if (!file_exists($uploadDir)) {  // Changed from fileexists to file_exists
                mkdir($uploadDir, 0777, true);
            }
           
            $fileName = uniqid() . "" . basename($_FILES['gambar']['name']);
            $uploadFilePath = $uploadDir . $fileName;
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $uploadFilePath)) {
                $gambar = "uploads/" . $fileName;
            } else {
                throw new Exception("Gagal mengunggah file.");
            }
        }
        // Generate file name for the article
        $articleFileName = generateArticleFileName($judul);
        $articleFilePath = "../../../artikel/" . $articleFileName;
        // Database insertion with file path
        $sql = "INSERT INTO artikel_kesehatan (judul, isi, gambar, highlight, file_path) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("sssss", $judul, $isi, $gambar, $highlight, $articleFileName);
        if ($stmt->execute()) {
            // Generate and save article content
            $artikelContent = generateArticleContent($judul, $gambar, $highlights, $isis);
            if (!file_put_contents($articleFilePath, $artikelContent)) {
                throw new Exception("Gagal membuat file artikel");
            }
            chmod($articleFilePath, 0644);
            // Update all-artikel.php
            $allArtikelPath = "../../../all-artikel.php";
            $content = file_get_contents($allArtikelPath);
           
            $cardContent = "\n        " . generateArticleCard($articleFileName, $gambar, $judul);
            $gridStart = strpos($content, '<div class="artikel-grid">');
           
            if ($gridStart !== false) {
                $insertPosition = $gridStart + strlen('<div class="artikel-grid">');
                $newContent = substr($content, 0, $insertPosition) . $cardContent . substr($content, $insertPosition);
               
                if (!file_put_contents($allArtikelPath, $newContent)) {
                    throw new Exception("Gagal memperbarui all-artikel.php");
                }
            } else {
                throw new Exception("Tidak dapat menemukan section artikel-grid");
            }
            echo "<script>
                alert('Artikel berhasil ditambahkan');
                window.location.href = '/Mamacare/admin/artikel-admin.php';
            </script>";
        }
    } catch (Exception $e) {
        echo "<script>
            alert('Error: " . addslashes($e->getMessage()) . "');
            window.history.back();
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Data Ibu Muda</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Padding agar tidak mepet dengan sidebar */
        .content-wrapper {
            margin-left: 250px; /* Menambahkan margin untuk menghindari sidebar */
            padding: 20px; /* Menambahkan padding agar tidak mepet dengan tepi */
        }

        /* Padding tambahan untuk form */
        .form-container {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .table td, .table th {
            padding: 10px; /* Menambah padding pada tabel form */
        }
        body {
            zoom: 0.7;
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
        <h2>Form Tambah Artikel Kesehatan</h2>
        <div class="form-container">
            <form action="add.php" method="POST" enctype="multipart/form-data">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <td><label for="judul">Judul Artikel:</label></td>
                            <td><input type="text" id="judul" name="judul" class="form-control" required></td>
                        </tr>
                        <tr>
                            <td><label for="gambar">Gambar Artikel:</label></td>
                            <td><input type="file" id="gambar" name="gambar" class="form-control" required></td>
                        </tr>
                    </table>

                    <div id="highlight-section">
                        <div class="highlight-row">
                            <label for="highlight[]">Highlight:</label>
                            <input type="text" name="highlight[]" class="form-control" placeholder="Masukkan highlight" required>
                            <br>
                            <label for="isi[]">Isi Highlight:</label>
                            <textarea name="isi[]" class="form-control" placeholder="Masukkan isi highlight" required></textarea>
                        </div>
                    </div>
<br>
                    <button type="button" id="add-highlight" class="btn btn-info">Tambah Highlight</button>
                </div>
<br>
                <button type="submit" name="submit" class="btn btn-primary">Tambah Artikel</button>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
    <script>
        $(document).ready(function() {
            // Menambahkan Highlight Baru
            $("#add-highlight").click(function() {
                var highlightHTML = `
                    <div class="highlight-row">
                        <label for="highlight[]">Highlight:</label>
                        <input type="text" name="highlight[]" class="form-control" placeholder="Masukkan highlight">
                        <label for="isi[]">Isi Highlight:</label>
                        <textarea name="isi[]" class="form-control" placeholder="Masukkan isi highlight"></textarea>
                    </div>
                `;
                $("#highlight-section").append(highlightHTML);
            });
        });
    </script>


    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
</body>
</html>