<?php
require '../../../db_connect.php';

// Helper functions (reuse from add.php)
function generateArticleFileName($judul) {
    return strtolower(str_replace(" ", "-", $judul)) . ".php";
}

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
            font-size: 1.4rem;
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
            align-items: flex-start;
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
            text-decoration: none;
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

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM artikel_kesehatan WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $article = $result->fetch_assoc();

    if (!$article) {
        echo "<script>alert('Artikel tidak ditemukan.'); window.location.href = '../artikel-admin.php';</script>";
        exit;
    }

    $highlights = explode("\n", $article['highlight']);
    $isis = explode("\n", $article['isi']);
}

if (isset($_POST['update'])) {
    try {
        $id = $_POST['article_id'];
        $old_judul = $_POST['old_judul'];
        $judul = $_POST['judul'];
        $highlights = $_POST['highlight'];
        $isis = $_POST['isi'];
        
        $highlight = implode("\n", array_filter($highlights));
        $isi = implode("\n", array_filter($isis));
        
        // Get old article information
        $stmt = $conn->prepare("SELECT gambar, file_path FROM artikel_kesehatan WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $old_article = $result->fetch_assoc();
        $old_file_path = $old_article['file_path'];
        $gambar = $old_article['gambar'];

        // Handle new image upload
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../../uploads/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            if ($old_article['gambar'] && file_exists('../../../' . $old_article['gambar'])) {
                unlink('../../../' . $old_article['gambar']);
            }
            
            $fileName = uniqid() . "_" . basename($_FILES['gambar']['name']);
            $uploadFilePath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $uploadFilePath)) {
                $gambar = "uploads/" . $fileName;
            } else {
                throw new Exception("Gagal mengunggah file baru.");
            }
        }

        // Generate new file path if title changed
        $new_file_path = ($judul !== $old_judul) ? generateArticleFileName($judul) : $old_file_path;

        // Update database first
        $sql = "UPDATE artikel_kesehatan SET judul = ?, isi = ?, gambar = ?, highlight = ?, file_path = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $judul, $isi, $gambar, $highlight, $new_file_path, $id);

        if ($stmt->execute()) {
            // Generate new article content
            $artikelContent = generateArticleContent($judul, $gambar, $highlights, $isis);
            
            /// Handle file updates
            $old_article_path = "../../../artikel/" . $old_file_path;
            $new_article_path = "../../../artikel/" . $new_file_path;

            // If the file name is changing, we need to create new file and delete old one
            if ($new_file_path !== $old_file_path) {
                // Delete old file if it exists
                if (file_exists($old_article_path)) {
                    unlink($old_article_path);
                }
            }

            // Write new content to file
            if (file_put_contents($new_article_path, $artikelContent) === false) {
                throw new Exception("Gagal memperbarui file artikel: " . error_get_last()['message']);
            }
            
            // Set proper permissions
            chmod($new_article_path, 0644);

            // Update all-artikel.php
            $allArtikelPath = "../../../all-artikel.php";
            if (file_exists($allArtikelPath)) {
                // Read the current content
                $content = file_get_contents($allArtikelPath);
                if ($content === false) {
                    throw new Exception("Gagal membaca all-artikel.php");
                }

                // Generate old and new cards
                $old_card = generateArticleCard($old_file_path, $old_article['gambar'], $old_judul);
                $new_card = generateArticleCard($new_file_path, $gambar, $judul);

                // Replace the old card with the new one
                $updated_content = str_replace($old_card, $new_card, $content);
                
                // Write the updated content back to the file
                if (file_put_contents($allArtikelPath, $updated_content) === false) {
                    throw new Exception("Gagal memperbarui all-artikel.php");
                }
            }

            echo "<script>
                alert('Artikel berhasil diperbarui');
                window.location.href = '/Mamacare/admin/artikel-admin.php';
            </script>";
        } else {
            throw new Exception("Gagal memperbarui database: " . $conn->error);
        }
    } catch (Exception $e) {
        error_log("Update error: " . $e->getMessage());
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
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    max-width: 1500px; /* Membatasi lebar form */
    width: 100%;
    margin: 0 auto;
    margin-right: 50px; /* Menggeser form lebih ke kanan */
    margin-top: -10px; /* Menaikkan posisi form ke atas */
    animation: fadeIn 0.5s ease-in-out;
}

.form-container h2 {
    text-align: center;
    color:rgb(0, 0, 0);
    margin-bottom: 20px;
    font-size: 24px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: bold;
    margin-bottom: 8px;
    font-size: 14px;
    color: #555;
}

.form-group input[type="text"],
.form-group input[type="file"],
.form-group textarea {
    width: 100%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
    color: #333;
    background-color: #f9f9f9;
    transition: all 0.3s ease;
}

.form-group input:focus,
.form-group textarea:focus {
    border-color: #4CAF50;
    box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
}

.highlight-isi-pair {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
    flex-wrap: wrap;
}

.highlight-isi-pair div {
    flex: 1;
    min-width: 48%;
}

.highlight-isi-pair label {
    font-weight: bold;
    margin-bottom: 5px;
    display: block;
}

.highlight-isi-pair input,
.highlight-isi-pair textarea {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: #f7f7f7;
}

button {
    display: inline-block;
    background: #4CAF50;
    color: white;
    border: none;
    padding: 10px 15px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    margin-top: 10px;
    transition: background 0.3s ease;
}

button:hover {
    background: #45a049;
}

button[type="submit"] {
    background-color: #007BFF;
}

button[type="submit"]:hover {
    background-color: #0056b3;
}

img {
    display: block;
    max-width: 100%;
    border-radius: 5px;
    margin-top: 10px;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
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
    <br>
    <div class="form-container">
        <h2>Edit Artikel</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="article_id" value="<?php echo isset($article) ? $article['id'] : ''; ?>">
            <input type="hidden" name="old_judul" value="<?php echo isset($article) ? $article['judul'] : ''; ?>">
            
            <div class="form-group">
                <label for="judul">Judul Artikel:</label>
                <input type="text" id="judul" name="judul" value="<?php echo isset($article) ? htmlspecialchars($article['judul']) : ''; ?>" required>
            </div>

            <div class="form-group">
                <label for="gambar">Gambar Artikel (Biarkan kosong jika tidak ingin mengubah):</label>
                <input type="file" id="gambar" name="gambar" accept="image/*">
                <?php if (isset($article) && $article['gambar']): ?>
                    <img src="../../../<?php echo $article['gambar']; ?>" alt="Current Image" style="max-width: 200px; margin-top: 10px;">
                <?php endif; ?>
            </div>

            <div id="highlightIsiContainer">
                <?php if (isset($highlights) && isset($isis)): ?>
                    <?php for($i = 0; $i < count($highlights); $i++): ?>
                        <div class="highlight-isi-pair">
                            <div>
                                <label>Sub Judul:</label>
                                <input type="text" name="highlight[]" value="<?php echo htmlspecialchars($highlights[$i]); ?>" required>
                            </div>
                            <div>
                                <label>Isi:</label>
                                <textarea name="isi[]" rows="4" required><?php echo htmlspecialchars($isis[$i]); ?></textarea>
                            </div>
                        </div>
                    <?php endfor; ?>
                <?php endif; ?>
            </div>

            <button type="button" onclick="addHighlightIsiPair()">Tambah Sub Judul dan Isi</button>
            <button type="submit" name="update">Update Artikel</button>
        </form>
    </div>
    <script>
        function addHighlightIsiPair() {
            const container = document.getElementById('highlightIsiContainer');
            const pair = document.createElement('div');
            pair.className = 'highlight-isi-pair';
            pair.innerHTML = `
                <div>
                    <label>Sub Judul:</label>
                    <input type="text" name="highlight[]" required>
                </div>
                <div>
                    <label>Isi:</label>
                    <textarea name="isi[]" rows="4" required></textarea>
                </div>
            `;
            container.appendChild(pair);
        }
    </script>
</body>
</html>