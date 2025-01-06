<?php
session_start();
include 'db_connect.php'; // Pastikan koneksi database sudah benar

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

.artikel-section {
    padding: 4rem 2rem;
    text-align: center;
}

.artikel-section h2 {
    font-family: 'Inter', sans-serif;
    font-size: 1.8rem;
    color: #176864;
    font-weight: 700;
}

.artikel-section p {
    font-size: 0.95rem;
    max-width: 800px;
    margin: 0 auto;
}

.artikel-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-top: 2rem;
    padding: 1.5rem;
}

.artikel-card {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}

.artikel-card:hover {
    transform: translateY(-5px);
}

.artikel-image {
    width: 100%;
    height: 180px;
    overflow: hidden;
}

.artikel-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.artikel-card:hover .artikel-image img {
    transform: scale(1.1);
}

.artikel-content {
    padding: 1.5rem;
}

.konsultasi-section {
    position: relative;
    background-image: url('assets/images/doctor.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
    padding: 40px 20px;
    min-height: 100vh;
}

.konsultasi-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1;
}

.konsultasi-section > * {
    position: relative;
    z-index: 2;
}

.section-title {
    text-align: center;
    font-size: 2rem;
    margin-bottom: 3rem;
    color: #ffffff;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
}

.konsultasi-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 3rem;
    max-width: 1200px;
    margin: 0 auto;
}

.konsultasi-card {
    background: white;
    border-radius: 20px;
    padding: 1.5rem;
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.konsultasi-card:hover {
    transform: translateY(-10px);
}

.doctor-image {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    margin: 0 auto 1.5rem;
    object-fit: cover;
    border: 5px solid #20B2AA;
    padding: 5px;
    background: white;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.doctor-name {
    font-size: 1.1rem;
    margin: 1rem 0;
    color: #333;
}

.specialty {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    color: #666;
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
}

.specialty i {
    color: #20B2AA;
}

.see-more-container {
    text-align: center;
    margin-top: 3rem;
}

.selengkapnya-btn {
    background: #20B2AA;
    color: white;
    padding: 12px 30px;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    display: inline-block;
    font-size: 0.9rem;
}

.selengkapnya-btn:hover {
    background: #176864;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.lihat-nakes-btn {
    background: #20B2AA;
    color: white;
    padding: 12px 30px; /* Menambah padding horizontal agar lebih seimbang */
    border: none;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin: 30px auto;
    display: block;
    font-size: 0.9rem;
    max-width: 250px; /* Menambahkan batasan lebar tombol */
    width: 100%; /* Agar tombol menyesuaikan lebar, tetapi tidak lebih dari max-width */
    text-decoration: none; /* Menghapus underline jika tombol dalam <a> */
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
        text-decoration: none;
    }
}
.p-icon{
    font-size: 1.2rem; /* Menambah ukuran font */
    line-height: 1.6; /* Menambah jarak antar baris */
    color: white; /* Mengubah warna teks menjadi putih */
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Menggunakan font profesional */
    padding: 10px; /* Menambahkan padding agar teks tidak terlalu rapat dengan batas */
    border-radius: 8px; /* Memberikan sudut membulat pada latar belakang */
    text-align: justify
}
a{
    text-decoration: none; 
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
    </style>

</head>
<body>
    <header class="header">
    <div class="logo-wrapper">
    <img src="assets/images/icon.png" alt="MamaCare Logo" class="logo">
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
            <li><a href="dashboard.php">Beranda</a></li>
            <li><a href="all-artikel.php">Artikel</a></li>
            <li><a href="all-modul.php">Modul</a></li>
            <li><a href="konsultasi/konsultasi.php">Konsultasi Nakes</a></li>
            <li><a href="pengingat/menu-pengingat.php">Pengingat</a></li>
            <li><a href="catatan/menu-catatan.php">Catatan Kesehatan Anak</a></li>
        </ul>
        <ul class="nav-list right">
    <li><a href="index.php">Logout</a></li>
    <img src="<?php echo $photo_profile; ?>" alt="Foto Profil">
</ul>
    </nav>
    <div class="main-header">
        <img src="assets/images/hospitalmamacare.png" alt="Hospital" class="hospital-image">
    </div>
    <div class="info-box">
        <img src="assets/images/icon.png" alt="Mother Icon" class="info-box-icon">
        <p class="p-icon">Selamat datang di MamaCare! Website ini dirancang khusus untuk mendukung Ibu Muda dalam perjalanan perawatan anak, dengan informasi dan sumber daya yang membantu mengatasi kecemasan dan tantangan setelah melahirkan.</p>
    </div>
</div>
<script src="assets/js/notification.js"></script>
<div id="popupOverlay" class="overlay" style="display: none;">
    <div id="popupNotification" class="popup">
        <div id="popupMessage"></div>
        <div id="popupButtons"></div>
    </div>
</div>
    <section class="artikel-section">
    <h2>ARTIKEL</h2>
    <br>
    <br>
    <p>Temukan berbagai artikel informatif dan modul edukasi seputar kesehatan ibu dan anak, mulai dari kehamilan, perawatan bayi, hingga tips parenting untuk mendukung perjalanan Anda sebagai Ibu Muda</p>
    
    <div class="artikel-grid">
        <!-- Card 1 -->
        <a href="artikel/pendek-stunting.php" style="text-decoration: none; color: inherit;">
            <div class="artikel-card">
                <div class="artikel-image">
                    <img src="assets/images/stunting.png" alt="Artikel 1">
                </div>
                <div class="artikel-content">
                    <p><strong>Perbedaan Anak Pendek dan Stunting: Memahami Perkembangan Anak dengan Lebih Baik</strong></p>
                </div>
            </div>
        </a>
        <!-- Card 2 -->
        <a href="artikel/cegah-stunting.php" style="text-decoration: none; color: inherit;">
            <div class="artikel-card">
                <div class="artikel-image">
                    <img src="assets/images/kebutuhan gizi.jpg" alt="Artikel 2">
                </div>
                <div class="artikel-content">
                    <p><strong>Mencegah Stunting pada Anak: Langkah-langkah Efektif untuk Tumbuh Kembang yang Optimal</strong></p>
                </div>
            </div>
        </a>
        <!-- Card 3 -->
        <a href="artikel/gizi-stunting.php" style="text-decoration: none; color: inherit;">
            <div class="artikel-card">
                <div class="artikel-image">
                    <img src="assets/images/gizi.webp" alt="Artikel 3">
                </div>
                <div class="artikel-content">
                    <p><strong>Asuhan Gizi pada Anak Stunting: Langkah Penting untuk Masa Depan yang Lebih Baik</strong></p>
                </div>
            </div>
        </a>
    </div>

    <a href="all-artikel.php" class="selengkapnya-btn">Lihat Semua</a>
</section>
<section class="artikel-section">
    <h2>MODUL EDUKASI</h2>
    <br>
    <br>
    <p>Pelajari berbagai panduan dan tips seputar kesehatan tumbuh kembang anak, dari pencegahan stunting hingga cara merawat anak dengan baik di setiap tahapannya</p>
    
    <div class="artikel-grid">
        <!-- Card 1 -->
        <a href="modul/imunisasi.php" style="text-decoration: none; color: inherit;">
            <div class="artikel-card">
                <div class="artikel-image">
                    <img src="assets/images/imunisasi.webp" alt="Artikel 1">
                </div>
                <div class="artikel-content">
                    <p><strong>Peran Imunisasi dalam Meningkatkan Kesehatan dan Tumbuh Kembang Anak</strong></p>
                </div>
            </div>
        </a>
        <!-- Card 2 -->
        <a href="modul/nangis.php" style="text-decoration: none; color: inherit;">
            <div class="artikel-card">
                <div class="artikel-image">
                    <img src="assets/images/rewel.jpg" alt="Artikel 2">
                </div>
                <div class="artikel-content">
                    <p><strong> Strategi Efektif dalam Menangani Anak dengan Kecenderungan Menangis</strong></p>
                </div>
            </div>
        </a>
        <!-- Card 3 -->
        <a href="modul/diare.php" style="text-decoration: none; color: inherit;">
            <div class="artikel-card">
                <div class="artikel-image">
                    <img src="assets/images/diare.webp" alt="Artikel 3">
                </div>
                <div class="artikel-content">
                    <p><strong>Pencegahan dan Penanganan Diare pada Anak untuk Kesehatan Optimal</strong></p>
                </div>
            </div>
        </a>
    </div>

    <a href="all-modul.php" class="selengkapnya-btn">Lihat Semua</a>
</section>


    <section class="konsultasi-section">
    <h2 class="section-title">Konsultasi Nakes</h2>
    <br>
    <br>
    <br>
    <div class="konsultasi-grid">
        <div class="konsultasi-card">
            <img src="assets/images/dokter1.jpg" alt="Dr. Aria Bima Darmawan" class="doctor-image">
            <h3 class="doctor-name">Dr. Khalid Ahmad Jibiyas, Sp.A
            </h3>
            <p class="specialty">
                <i class="fas fa-chart-line"></i>
                Dokter Anak (Pediatri)
            </p>
        </div>
        <div class="konsultasi-card">
            <img src="assets/images/dokter2.jpg" alt="Dr. Aria Bima Darmawan" class="doctor-image">
            <h3 class="doctor-name">Dr. Sarah Natalia, Sp.OG</h3>
            <p class="specialty">
                <i class="fas fa-chart-line"></i>
                Gastroenterologi Anak
            </p>
        </div>
        <div class="konsultasi-card">
            <img src="assets/images/dokter3.jpg" alt="Dr. Aria Bima Darmawan" class="doctor-image">
            <h3 class="doctor-name">Dr. Vivi Andriani, Sp.A</h3>
            <p class="specialty">
                <i class="fas fa-chart-line"></i>
                Pulmonologi Anak
            </p>
        </div>
        <div class="konsultasi-card">
            <img src="assets/images/dokter4.jpg" alt="Dr. Aria Bima Darmawan" class="doctor-image">
            <h3 class="doctor-name">Dr. Maya Lestari, Sp.A</h3>
            <p class="specialty">
                <i class="fas fa-chart-line"></i>
                Endokrinologi Anak
            </p>
        </div>
        <div class="konsultasi-card">
            <img src="assets/images/dokter5.jpg" alt="Dr. Aria Bima Darmawan" class="doctor-image">
            <h3 class="doctor-name">Dr. Deni Putra, M.Psi</h3>
            <p class="specialty">
                <i class="fas fa-chart-line"></i>
                Psikolog Anak
            </p>
        </div>
        <div class="konsultasi-card">
            <img src="assets/images/dokter6.jpg" alt="Dr. Aria Bima Darmawan" class="doctor-image">
            <h3 class="doctor-name">Dr. Khalid Kasmiri, Sp.A
            </h3>
            <p class="specialty">
                <i class="fas fa-chart-line"></i>
                Dermatologi Anak
            </p>
        </div>
        <!-- Repeat for other doctors -->
    </div>
    <div class="see-more-container">
    <a href="konsultasi/konsultasi.php" class="lihat-nakes-btn">Lihat Semua</a>
    </div>
</section>

<footer class="footer">
    <div class="footer-content">
        <div class="brand-section">
        <div class="logo-wrapper">
    <img src="assets/images/icon.png" alt="MamaCare Logo" class="logo">
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
