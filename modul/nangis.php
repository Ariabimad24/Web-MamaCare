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
    padding: 12px 30px;
    border: none;
    border-radius: 25px;
    cursor: pointer;
    transition: all 0.3s ease;
    margin: 30px auto;
    display: block;
    font-size: 0.9rem;
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
.rounded-image-container {
    max-width: 800px;  /* Sesuaikan dengan kebutuhan lebar maksimal */
    margin: 20px auto; /* Untuk center image dan memberikan margin atas bawah */
    padding: 0 15px;   /* Padding di kiri kanan */
}

.rounded-image {
    width: 100%;
    height: auto;
    border-radius: 24px; /* Untuk membuat sudut melengkung */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Menambahkan bayangan lembut */
    display: block;
}
.article {
    font-family: Arial, sans-serif;
    line-height: 1.6;
    color: #333;
    margin: 20px;
}

.sub-title {
    font-family: 'Abyssinica SIL', serif;
    font-size: 1.5em;
    font-weight: bold;
    color: #176864;
    margin-top: 20px;
    margin-bottom: 10px;
    text-align: left; /* memastikan teks di sebelah kiri */
    padding-left: 0; /* menghilangkan padding kiri */
    margin-left: 0; /* memastikan tidak ada margin kiri tambahan */
}

p {
    margin-bottom: 15px;
    text-align: justify;
}

ul {

    padding-left: 20px;
}
.article-content {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    font-family: Arial, sans-serif;
    line-height: 1.6;
}

.content-title {
    color: #0B6E4F;
    font-size: 24px;
    margin: 30px 0 20px;
    font-weight: 600;
    text-align: left;
}

.content-text {
    text-align: justify;
}

.content-list {
    margin-left: 20px;
    margin-bottom: 30px;
    padding-left: 20px;
}

.content-list li {
    margin-bottom: 12px;
    text-align: justify;
    padding-left: 10px;
}

.content-numbered p {
    margin-bottom: 20px;
    text-align: justify;
}

.content-numbered strong {
    color: #0B6E4F;
}

/* Responsif untuk layar kecil */
@media (max-width: 768px) {
    .article-content {
        padding: 15px;
    }
    
    .content-title {
        font-size: 20px;
    }
}
a{
    text-decoration: none; 
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
            <li><a href="../catatan/menu-catatan.php">Catatan Kesehatan Anak</a></li>
        </ul>
        <ul class="nav-list right">
    <li><a href="index.php">Logout</a></li>
    <img src="<?php echo '../' . $photo_profile; ?>" alt="Foto Profil">
</ul>
    </nav>

    <section class="artikel-section">
    <h2> Strategi Efektif dalam Menangani Anak dengan Kecenderungan Menangis</h2>
    <br>
    <br>
    <div class="rounded-image-container">
    <img src="../assets/images/rewel.jpg" alt="Rounded Image" class="rounded-image">
    <br>
    <div class="article-content">
    <p>Anak yang mudah menangis sering kali membuat orangtua merasa cemas atau frustasi. Namun, menangis adalah cara alami anak untuk berkomunikasi, baik itu karena rasa lapar, kelelahan, atau ketidaknyamanan. Oleh karena itu, penting bagi orangtua untuk mengetahui cara yang tepat untuk menenangkan anak agar mereka merasa lebih nyaman.</p>
    <hr style="border: none; height: 5px; background-color: transparent;" />
    
    <div class="sub-title">Penyebab Anak Menangis</div>
    <p>Beberapa penyebab umum anak menangis antara lain:</p>
    <hr style="border: none; height: 10px; background-color: transparent;" />
    <ul class="content-list">
        <li>Lapar atau haus</li>
        <li>Pengen ganti popok</li>
        <li>Keinginan untuk perhatian atau kehangatan</li>
        <li>Merasa tidak nyaman atau sakit</li>
        <li>Keletihan atau kurang tidur</li>
    </ul>
    <hr style="border: none; height: 5px; background-color: transparent;" />
    
    <div class="sub-title">Cara Menenangkan Anak yang Menangis</div>
    <p>Berikut adalah beberapa tips yang bisa membantu menenangkan anak yang sedang menangis:</p>
    <hr style="border: none; height: 10px; background-color: transparent;" />
    <ul class="content-list">
        <li>Periksa apakah popoknya sudah penuh atau ada rasa tidak nyaman lain.</li>
        <li>Memberikan pelukan atau kontak fisik untuk memberikan rasa aman.</li>
        <li>Coba susui atau berikan makan jika anak merasa lapar.</li>
        <li>Gunakan suara lembut atau nyanyian untuk menenangkan.</li>
        <li>Ciptakan lingkungan yang tenang agar anak bisa tidur dengan nyaman.</li>
    </ul>
</div>


</div>
</section>

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
