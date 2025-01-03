<?php
session_start();
include '../db_connect.php'; // Pastikan koneksi database sudah benar

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php"); // Redirect jika belum login
    exit();
}

// Ambil data user_id
$user_id = $_SESSION['user_id'];

// Tentukan apakah pengguna adalah ibu_muda atau nakes
// Misalnya, Anda bisa menyimpan tipe pengguna di session, atau bisa didasarkan pada data lainnya, misalnya berdasarkan role atau jenis user
$user_type = isset($_SESSION['user_type']) ? $_SESSION['user_type'] : null; // Pastikan ini sudah di set sebelumnya

if ($user_type == 'ibu_muda') {
    // Ambil data dari tabel ibu_muda
    $stmt = $conn->prepare("SELECT photo_profile, nama FROM ibu_muda WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $photo_profile = $user['photo_profile'];
        $user_name = $user['nama'];
    } else {
        // Jika data ibu_muda tidak ditemukan, bisa diberikan pesan error atau penanganan lain
        echo "Data ibu_muda tidak ditemukan.";
    }
} elseif ($user_type == 'nakes') {
    // Ambil data dari tabel nakes
    $stmt = $conn->prepare("SELECT photo_profile, nama FROM nakes WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        $photo_profile = $user['photo_profile'];
        $user_name = $user['nama'];
    } else {
        // Jika data nakes tidak ditemukan, bisa diberikan pesan error atau penanganan lain
        echo "Data nakes tidak ditemukan.";
    }
} else {
    // Jika tipe pengguna tidak dikenali
    echo "Tipe pengguna tidak valid.";
    exit();
}

// Ambil data catatan kesehatan untuk user yang sedang login
$ibu_muda_id = $_SESSION['user_id'];
$query = "SELECT * FROM catatan_kesehatan WHERE ibu_muda_id = ? ORDER BY waktuCatatan DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $ibu_muda_id);
$stmt->execute();
$result = $stmt->get_result();
$catatan_list = $result->fetch_all(MYSQLI_ASSOC);

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
.catatan-container, .pengingat-container {
    max-width: 1000px;
    margin: 2rem auto;
    padding: 0 1rem;
}

.catatan-table, .pengingat-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 2rem;
    background: #f5f5f5;
    border-radius: 10px;
    overflow: hidden;
}

.catatan-table th, .catatan-table td, .pengingat-table th, .pengingat-table td {
    padding: 1rem;
    text-align: center;
    border: 1px solid #ddd;
}

.catatan-table th, .pengingat-table th {
    background: #20B2AA;
    color: white;
    font-weight: normal;
}

.cataton-table tr, .pengingat-table tr {
    background: #f5f5f5;
}

.cataton-table tr:nth-child(even), .pengingat-table tr:nth-child(even) {
    background: #ffffff;
}

.no-data {
    text-align: center;
    padding: 2rem;
    color: #666;
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

.status-severely-stunted {
    color:rgb(240, 13, 36);
    font-weight: bold;
}

.status-stunted {
    color:rgb(214, 118, 33);
    font-weight: bold;
}

.status-normal {
    color: #28a745;
    font-weight: bold;
}
a{
    text-decoration: none; 
}
.penjelasan-section h2 {
    font-family: 'Inter', sans-serif;
    font-size: 1.4rem;
    color: #176864;
    font-weight: 700;
}
.penjelasan-section p {
    font-size: 0.95rem;
    margin: 0 auto;
    line-height: 1.6; /* Menambahkan spasi antar teks */
}
.section-growth {
    max-width: 1000px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.section-growth > ul {
    list-style-type: none;
    padding: 0;
}

.section-growth > ul > li {
    margin-bottom: 25px;
    font-weight: bold;
    color: 	#000000;
}

.section-growth > ul > li > ul {
    margin-top: 10px;
    padding-left: 20px;
}

.section-growth > ul > li > ul > li {
    font-weight: normal;
    margin: 10px 0;
    line-height: 1.6;
    color:  	#000000;
}
.status-normal {
    color: green;
}
.status-severely-stunted,
.status-micro,
.status-macro {
    color: red;
}
.status-stunted,
.status-underweight,
.status-overweight {
    color: orange;
}
.status-obese {
    color: red;
}    

    </style>
</head>
<body>
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
    </header>

    <nav class="navigation">
        <ul class="nav-list left">
            <li><a href="../dashboard.php">Beranda</a></li>
            <li><a href="../all-artikel.php">Artikel</a></li>
            <li><a href="../all-modul.php">Modul</a></li>
            <li><a href="../konsultasi/konsultasi.php">Konsultasi Nakes</a></li>
            <li><a href="../pengingat/menu-pengingat.php">Pengingat</a></li>
            <li><a href="menu-catatan.php">Catatan Kesehatan Anak</a></li>
        </ul>
        <ul class="nav-list right">
    <li><a href="../index.php">Logout</a></li>
    <img src="<?php echo '../' . $photo_profile; ?>" alt="Foto Profil">
</ul>
    </nav>

    <div class="catatan-container">
    <table class="catatan-table">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Nama Anak</th>
                <th>Usia (bulan)</th>
                <th>TB (cm)</th>
                <th>BB (kg)</th>
                <th>LK (cm)</th>
                <th>Status Tinggi Badan</th>
                <th>Status Berat Badan</th>
                <th>Status Lingkar Kepala</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($catatan_list)): ?>
                <tr>
                    <td colspan="9" class="no-data">Belum ada catatan kesehatan</td>
                </tr>
            <?php else: ?>
                <?php foreach ($catatan_list as $catatan): ?>
                    <tr>
                        <td><?php echo date('d/m/Y', strtotime($catatan['waktuCatatan'])); ?></td>
                        <td><?php echo htmlspecialchars($catatan['namaAnak']); ?></td>
                        <td><?php echo $catatan['usia']; ?></td>
                        <td><?php echo number_format($catatan['height'], 1); ?></td>
                        <td><?php echo number_format($catatan['bb'], 1); ?></td>
                        <td><?php echo number_format($catatan['headCircumference'], 1); ?></td>
                        
                        <!-- Status Tinggi Badan -->
                        <td><?php
                            $status_class = 'status-normal';
                            $status_text = $catatan['status_height'] ?? 'Normal';
                            
                            if (strpos(strtolower($status_text), 'sangat pendek') !== false) {
                                $status_class = 'status-severely-stunted';
                            } elseif (strpos(strtolower($status_text), 'pendek') !== false) {
                                $status_class = 'status-stunted';
                            }
                            
                            echo "<span class='$status_class'>$status_text</span>";
                        ?></td>

                        <!-- Status Berat Badan -->
                        <td><?php
                            $status_class = 'status-normal';
                            $status_text = $catatan['status_weight'] ?? 'Normal';
                            
                            if (strpos(strtolower($status_text), 'kurang') !== false) {
                                $status_class = 'status-underweight';
                            } elseif (strpos(strtolower($status_text), 'lebih') !== false) {
                                $status_class = 'status-overweight';
                            } elseif (strpos(strtolower($status_text), 'obesitas') !== false) {
                                $status_class = 'status-obese';
                            }
                            
                            echo "<span class='$status_class'>$status_text</span>";
                        ?></td>

                        <!-- Status Lingkar Kepala -->
                        <td><?php
                            $status_class = 'status-normal';
                            $status_text = $catatan['status_head'] ?? 'Normal';
                            
                            if (strpos(strtolower($status_text), 'mikrosefali') !== false) {
                                $status_class = 'status-micro';
                            } elseif (strpos(strtolower($status_text), 'makrosefali') !== false) {
                                $status_class = 'status-macro';
                            }
                            
                            echo "<span class='$status_class'>$status_text</span>";
                        ?></td>
                        <td><?php echo htmlspecialchars($catatan['catatan']); ?></td>

                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
    <a href="form-catatan.php" class="tambah-btn">Tambah Catatan Kesehatan</a>
    <hr>
    <br>
    <section class="penjelasan-section">
    <h2>Apa itu Z-Score?</h2>
    <br>
    <p>Z-score adalah sebuah angka yang digunakan untuk membandingkan pertumbuhan seorang anak (misalnya, tinggi badan, berat badan, atau lingkar kepala) dengan standar pertumbuhan yang sudah ditetapkan oleh WHO (Organisasi Kesehatan Dunia). Ini membantu kita memahami apakah pertumbuhan anak sesuai dengan rata-rata anak seusianya atau tidak.</p>
    <br>
    <hr>
    <br>
    <h2>Apa yang ditunjukkan oleh z-score?</h2>
    <div class="section-growth">
    <ul>
    <li>Status Tinggi Badan:
        <ul>
            <li><span style="color: red">Sangat Pendek</span>: Anak perlu pemeriksaan ke dokter untuk evaluasi pertumbuhan</li>
            <li><span style="color: orange">Pendek</span>: Perlu peningkatan asupan gizi seimbang</li>
            <li><span style="color: green">Normal</span>: Tinggi badan sesuai dengan usia anak</li>
        </ul>
    </li>

    <li>Status Berat Badan:
        <ul>
            <li><span style="color: red">Berat Badan Sangat Kurang</span>: Perlu pemeriksaan ke dokter</li>
            <li><span style="color: orange">Berat Badan Kurang</span>: Tingkatkan porsi makan dan gizi seimbang</li>
            <li><span style="color: green">Normal</span>: Berat badan sesuai dengan usia anak</li>
            <li><span style="color: orange">Berat Badan Lebih</span>: Atur pola makan anak</li>
            <li><span style="color: red">Obesitas</span>: Konsultasikan dengan dokter untuk pengaturan pola makan</li>
        </ul>
    </li>

    <li>Status Lingkar Kepala:
        <ul>
            <li><span style="color: red">Mikrosefali</span>: Perlu pemeriksaan ke dokter anak</li>
            <li><span style="color: green">Normal</span>: Ukuran kepala sesuai dengan usia anak</li>
            <li><span style="color: red">Makrosefali</span>: Perlu pemeriksaan ke dokter anak</li>
        </ul>
    </li>
</ul>
                        </div>
                        </section>
</div>

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
