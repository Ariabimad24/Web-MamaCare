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
$pengingat = null;

// Cek tipe user dan ambil data sesuai tipe
if ($user_type == 'ibu_muda') {
    // Ambil data dari tabel ibu_muda
    $stmt = $conn->prepare("SELECT photo_profile, nama FROM ibu_muda WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if (!$user) {
        echo "Data ibu_muda tidak ditemukan.";
        exit();
    }
    
    $photo_profile = $user['photo_profile'];
    $user_name = $user['nama'];
    
    // Ambil data pengingat berdasarkan ID yang diterima dari URL
    if (isset($_GET['id'])) {
        $pengingat_id = $_GET['id'];
        $stmt = $conn->prepare("SELECT id, waktuPengingat, instruksi_arahan_dokter, status FROM pengingat_jadwal_kesehatan WHERE ibu_muda_id = ? AND id = ?");
        $stmt->bind_param("ii", $user_id, $pengingat_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $pengingat = $result->fetch_assoc();
        } else {
            echo "Pengingat tidak ditemukan.";
            exit();
        }
    } else {
        echo "ID pengingat tidak valid.";
        exit();
    }

} elseif ($user_type == 'nakes') {
    $stmt = $conn->prepare("SELECT photo_profile, nama FROM nakes WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if (!$user) {
        echo "Data nakes tidak ditemukan.";
        exit();
    }
    
    $photo_profile = $user['photo_profile'];
    $user_name = $user['nama'];
    $pengingat = null; // Nakes tidak memiliki pengingat
    
} else {
    echo "Tipe pengguna tidak valid.";
    exit();
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MamaCare</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700&family=Abyssinica+SIL&display=swap');

:root {
    --primary-color: #20B2AA;
    --gradient-start: #17a2b8;
    --gradient-end: #20B2AA;
    font-size: 14px;
}
.popup {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #fff;
    border: 1px solid #ddd;
    padding: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    z-index: 1000;
}
.popup button {
    margin: 5px;
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
    font-family: 'Abyssinica SIL', serif;
    font-size: 28px;
    color: #fff;
}

.logo-text .care {
    color: #FFD700;
    font-family: 'Abyssinica SIL', serif;
}

.social-links {
    display: flex;
    gap: 1rem;
}

.social-links a {
    color: white;
    font-size: 1.2rem;
}

.main-header {
    position: relative;
    height: 100vh;
}

.hospital-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.logo-container {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 2rem;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
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

.nav-list.right a {
    display: flex;
    align-items: center;
    height: 100%;
    padding: 0 10px;
}

.info-box {
    background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
    color: white;
    padding: 1.5rem;
    border-radius: 10px;
    max-width: 700px;
    margin: -80px auto 0;
    position: relative;
    z-index: 2;
    display: flex;
    align-items: center;
    gap: 1.5rem;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    font-size: 0.95rem;
}

.info-box .info-box-icon {
    width: 120px;
    height: auto;
    border: none;
    object-fit: contain;
}



.lihat-nakes-btn:hover {
    background: #176864;
    transform: scale(1.05);
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
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
    align-items: center;
}

.brand-section {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.logo-img {
    width: 50px;
    height: 50px;
}

.brand-name {
    font-size: 1.5rem;
    font-weight: bold;
    display: flex;
    flex-direction: column;
    line-height: 1.2;
}

.contact-section {
    text-align: center;
}

.contact-section h4 {
    font-size: 1rem;
    margin-bottom: 1rem;
    color: rgb(255, 255, 255);
}

.contact-info p {
    margin: 0.5rem 0;
    font-size: 0.9rem;
}

.social-section {
    text-align: right;
}

.social-section h4 {
    font-size: 1rem;
    margin-bottom: 1rem;
}

.social-icons {
    display: flex;
    gap: 2rem;
    font-size: 1.5rem;
}

.social-icons a {
    color: white;
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
.pengingat-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .pengingat-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
            background: #f5f5f5;
            border-radius: 10px;
            overflow: hidden;
        }

        .pengingat-table th,
        .pengingat-table td {
            padding: 1rem;
            text-align: center;
            border: 1px solid #ddd;
        }

        .pengingat-table th {
            background: #20B2AA;
            color: white;
            font-weight: normal;
        }

        .pengingat-table tr {
            background: #f5f5f5;
        }

        .pengingat-table tr:nth-child(even) {
            background: #ffffff;
        }

        .tambah-btn {
            display: block;
            width: fit-content;
            margin: 2rem auto;
            background: #20B2AA;
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 25px;
            text-decoration: none;
            text-align: center;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .tambah-btn:hover {
            background: #176864;
        }

        .no-data {
            text-align: center;
            padding: 2rem;
            color: #666;
        }
        a{
    text-decoration: none; 
}

.notification-banner {
            background: linear-gradient(135deg, #20B2AA, #17a2b8);
            color: white;
            padding: 15px;
            margin: 10px auto;
            border-radius: 8px;
            max-width: 800px;
            display: none;
            animation: slideDown 0.5s ease-out;
        }

        .popup-notification {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.3);
            z-index: 1000;
            display: none;
        }

        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            display: none;
            z-index: 999;
        }

        .close-popup {
            position: absolute;
            right: 10px;
            top: 10px;
            cursor: pointer;
            font-size: 20px;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }
        .notification-banner {
    background: linear-gradient(135deg, #20B2AA, #17a2b8);
    color: white;
    padding: 15px;
    margin: 10px auto;
    border-radius: 8px;
    max-width: 800px;
    display: none;
    animation: slideDown 0.5s ease-out;
    z-index: 999;
}

.popup-notification {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.3);
    z-index: 1000;
    display: none;
    min-width: 300px;
}

.reminder-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
    gap: 10px;
}

.reminder-actions button {
    padding: 8px 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    flex: 1;
}

.done-btn {
    background: #28a745;
    color: white;
}

.edit-btn {
    background: #ffc107;
    color: black;
}

.delete-btn {
    background: #dc3545;
    color: white;
}

.reminder-actions button:hover {
    opacity: 0.9;
}
.pengingat-table tr[data-id] {
    cursor: pointer;
    transition: background-color 0.3s;
}

.pengingat-table tr[data-id]:hover {
    background-color: #f0f0f0;
}

.notification-banner {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1000;
    background: linear-gradient(135deg, #20B2AA, #17a2b8);
    color: white;
    padding: 15px;
    border-radius: 8px;
    display: none;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    max-width: 90%;
    width: 600px;
}

.popup-notification {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.3);
    z-index: 1000;
    display: none;
    min-width: 300px;
    max-width: 90%;
}

.popup-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    display: none;
    z-index: 999;
}

.reminder-actions {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
    gap: 10px;
}

.reminder-actions button {
    padding: 8px 16px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    flex: 1;
    transition: opacity 0.3s;
}

.done-btn {
    background: #28a745;
    color: white;
}

.edit-btn {
    background: #ffc107;
    color: black;
}

.delete-btn {
    background: #dc3545;
    color: white;
}

.reminder-actions button:hover {
    opacity: 0.9;
}

.close-popup {
    position: absolute;
    right: 10px;
    top: 10px;
    cursor: pointer;
    font-size: 20px;
}
.overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
}

.popup {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 20px;
    border-radius: 5px;
    z-index: 1001;
    max-width: 500px;
    width: 90%;
}

.btn {
    margin: 5px;
    padding: 8px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

.btn-success { background: #28a745; color: white; }
.btn-danger { background: #dc3545; color: white; }
.btn-warning { background: #ffc107; }

.btn {
    padding: 5px 10px;
    text-decoration: none;
    border-radius: 4px;
    margin: 0 2px;
    color: white;
}

.btn-edit {
    background-color: #4CAF50;
}

.btn-delete {
    background-color: #f44336;
}

.btn:hover {
    opacity: 0.8;
}
.edit-pengingat-container {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .edit-pengingat-container h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
    }

    .form-group input, .form-group select, .form-group textarea {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .form-actions {
        text-align: center;
    }

    .form-actions .btn {
        padding: 10px 20px;
        margin: 5px;
        border-radius: 4px;
        text-decoration: none;
        color: white;
    }

    .btn-update {
        background-color: #4CAF50;
    }

    .btn-cancel {
        background-color: #f44336;
    }
</style>
</head>
<body>
<script src="../assets/js/notification.js"></script>
<div id="popupOverlay" class="overlay" style="display: none;">
    <div id="popupNotification" class="popup">
        <div id="popupMessage"></div>
        <div id="popupButtons"></div>
    </div>
</div>
    <header class="header">
    <div class="logo-wrapper">
    <img src="../assets/images/icon.png" alt="MamaCare Logo" class="logo">
    <span class="logo-text">
        Mama<span class="care">Care</span>
    </span>
</div>
        <div class="social-links">
            <a href="#"><i class="fab fa-facebook"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-linkedin"></i></a>
        </div>
</div>
    </header>

    <nav class="navigation">
        <ul class="nav-list left">
            <li><a href="../dashboard.php">Beranda</a></li>
            <li><a href="../all-artikel.php">Artikel</a></li>
            <li><a href="../all-modul.php">Modul</a></li>
            <li><a href="../konsultasi/konsultasi.php">Konsultasi Nakes</a></li>
            <li><a href="menu-pengingat.php">Pengingat</a></li>
            <li><a href="../catatan/menu-catatan.php">Catatan Kesehatan Anak</a></li>
        </ul>
        <ul class="nav-list right">
    <li><a href="../index.php">Logout</a></li>
    <img src="<?php echo '../' . $photo_profile; ?>" alt="Foto Profil">
</ul>
    </nav>
    <br>
    <br>
    <div class="edit-pengingat-container">
    <h2>Edit Pengingat</h2>
    <form action="update_reminder.php" method="POST">
        <input type="hidden" name="id" value="<?php echo $pengingat['id']; ?>">
        
        <div class="form-group">
            <label for="waktuPengingat">Waktu Pengingat</label>
            <input type="time" id="waktuPengingat" name="waktuPengingat" value="<?php echo date('H:i', strtotime($pengingat['waktuPengingat'])); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="tanggalPengingat">Tanggal Pengingat</label>
            <input type="date" id="tanggalPengingat" name="tanggalPengingat" value="<?php echo date('Y-m-d', strtotime($pengingat['waktuPengingat'])); ?>" required>
        </div>

        <div class="form-group">
            <label for="instruksiArahanDokter">Deskripsi Pengingat</label>
            <textarea id="instruksiArahanDokter" name="instruksiArahanDokter" required><?php echo htmlspecialchars($pengingat['instruksi_arahan_dokter']); ?></textarea>
        </div>
        
        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" required>
            <option value="active" <?php echo ($pengingat['status'] == 'active') ? 'selected' : ''; ?>>active</option>
<option value="selesai" <?php echo ($pengingat['status'] == 'selesai') ? 'selected' : ''; ?>>selesai</option>

            </select>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-update">Update Pengingat</button>
            <a href="../pengingat/menu-pengingat.php" class="btn btn-cancel">Batal</a>
        </div>
    </form>
</div>
<br>

<footer class="footer">
    <div class="footer-content">
        <div class="brand-section">
        <div class="logo-wrapper">
    <img src="../assets/images/icon.png" alt="MamaCare Logo" class="logo">
    <span class="logo-text">
        Mama<span class="care">Care</span>
    </span>
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
</html>
