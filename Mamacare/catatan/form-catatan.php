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

.reminder-section {
    max-width: 600px;
    margin: 50px auto;
    padding: 20px;
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  }

  .reminder-form {
    display: flex;
    flex-direction: column;
  }

  .reminder-form label {
    margin-top: 10px;
    font-weight: bold;
  }

  .reminder-form input, 
  .reminder-form textarea {
    margin-top: 5px;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
  }

  .reminder-form button {
    margin-top: 20px;
    padding: 10px;
    background: #2c7a7b;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }

  .reminder-form button:hover {
    background: #285e5e;
  }

  footer {
    background: linear-gradient(135deg, #176864 0%, #20B2AA 100%);
    padding: 3rem 2rem;
    color: white;
    margin-top: 3rem; /* Pastikan footer ada jarak dengan konten */
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
body {
      font-family: 'Roboto', sans-serif;
      margin: 0;
      padding: 0;
      background: linear-gradient(to bottom, #f4f9ff, #d9eefa);
    }

    form {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    font-family: 'Arial', sans-serif;
}

/* Styling label */
form label {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 8px;
    display: block;
    color: #333;
}

/* Styling input text dan select */
form input, form select {
    width: 100%;
    padding: 10px;
    margin: 8px 0 16px 0;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    box-sizing: border-box;
    transition: border-color 0.3s ease;
}

/* Fokus pada input dan select */
form input:focus, form select:focus {
    border-color: #4caf50;
    outline: none;
}

/* Styling tombol submit */
form button {
    background-color: #4caf50;
    color: white;
    border: none;
    padding: 12px 20px;
    font-size: 16px;
    border-radius: 4px;
    cursor: pointer;
    width: 100%;
    transition: background-color 0.3s ease;
}

/* Hover effect pada tombol submit */
form button:hover {
    background-color: #45a049;
}

/* Styling hasil kalkulasi */
.result {
    margin-top: 20px;
    padding: 10px;
    font-size: 16px;
    border-radius: 4px;
    display: none; /* Sembunyikan hasil sampai perhitungan selesai */
}

/* Styling hasil sukses */
.result.success {
    background-color: #d4edda;
    color: #155724;
    border: 1px solid #c3e6cb;
}

/* Styling hasil error */
.result.error {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
}
.header-Z {
    font-size: 15px;
    font-weight: bold;
    color: white; /* Mengubah warna teks menjadi putih */
    text-align: center;
    margin-bottom: 20px;
    padding: 10px;
    background: linear-gradient(135deg, #20B2AA, #17a2b8, #48D1CC);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    text-transform: uppercase;
    letter-spacing: 1px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; /* Menambahkan font yang lebih profesional */
}
a{
    text-decoration: none; 
}
textarea {
        width: 100%;
        box-sizing: border-box; /* Memastikan padding masuk dalam ukuran */
        padding: 8px; /* Sama dengan input lainnya */
        font-size: 16px; /* Sama dengan input lainnya */
        border: 1px solid #ccc; /* Sama dengan input lainnya */
        border-radius: 4px; /* Sama dengan input lainnya */
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
    <div class="container">
    <div class="header-Z">Z-Score Risiko Stunting</div>
    <form id="zScoreForm" action="process-catatan.php" method="POST">
        <!-- Nama Anak -->
        <label for="nama-anak">Nama Anak</label>
        <input type="text" id="nama-anak" name="nama_anak" placeholder="Nama Anak" required>

        <!-- Waktu & Tanggal Pencatatan -->
        <label for="tanggal">Waktu & Tanggal Pencatatan</label>
        <input type="datetime-local" id="tanggal" name="waktu_catatan" required>

        <!-- Jenis Kelamin -->
        <label for="gender">Jenis Kelamin:</label>
        <select id="gender" name="gender" required>
            <option value="">Pilih...</option>
            <option value="male">Laki-laki</option>
            <option value="female">Perempuan</option>
        </select>

        <!-- Usia -->
        <label for="age">Usia (bulan):</label>
        <input type="number" id="age" name="usia" min="0" required placeholder="Masukkan usia dalam bulan">

        <!-- Tinggi Badan -->
        <label for="height">Tinggi Badan (cm):</label>
        <input type="number" id="height" name="height" step="0.1" min="0" required placeholder="Masukkan tinggi badan dalam cm">

        <!-- Berat Badan -->
        <label for="weight">Berat Badan (kg):</label>
        <input type="number" id="weight" name="bb" step="0.1" min="0" required placeholder="Masukkan berat badan dalam kg">

        <!-- Lingkar Kepala -->
        <label for="headCircumference">Lingkar Kepala (cm):</label>
        <input type="number" id="headCircumference" name="headCircumference" step="0.1" min="0" required placeholder="Masukkan lingkar kepala dalam cm">

        <!-- Catatan Tambahan -->
        <label for="catatan">Catatan Tambahan:</label>
        <textarea id="catatan" name="catatan" rows="4" placeholder="Tambahkan catatan tambahan di sini..."></textarea>

        <!-- Hidden Inputs untuk Z-Scores -->
        <input type="hidden" name="z_score_height" id="z_score_height">
        <input type="hidden" name="z_score_weight" id="z_score_weight">
        <input type="hidden" name="z_score_head" id="z_score_head">
        <input type="hidden" name="z_score_status" id="z_score_status">
    
        <!-- Submit Button -->
        <button type="submit" id="submitButton">Hitung dan Simpan</button>
        <hr style="border: none; height: 5px; background-color: transparent;" />
        <p>*Tunggu beberapa saat karena data sedang dihitung

        <!-- Hasil Perhitungan (Opsional) -->
        <div id="result" class="result"></div>
    </form>



<script>
/// WHO Standards data structure remains the same
const whoStandards = {
    male: {
        12: {
            height: [75, 5.5],
            weight: [9.5, 1.1],
            headCircumference: [45, 2]
        }
    },
    female: {
        12: {
            height: [74, 5],
            weight: [9, 1],
            headCircumference: [44.5, 1.9]
        }
    }
};

document.getElementById("submitButton").addEventListener("click", function(event) {
    calculateAndSubmit(event);
});

function getStatusFromZScore(zScore, type) {
    switch(type) {
        case 'height':
            if (zScore < -3) return "Sangat Pendek (Severely Stunted)";
            if (zScore < -2) return "Pendek (Stunted)";
            if (zScore > 2) return "Tinggi";
            return "Normal";
            
        case 'weight':
            if (zScore < -3) return "Berat Badan Sangat Kurang";
            if (zScore < -2) return "Berat Badan Kurang";
            if (zScore > 2) return "Berat Badan Lebih";
            if (zScore > 3) return "Obesitas";
            return "Berat Badan Normal";
            
        case 'head':
            if (zScore < -2) return "Mikrosefali";
            if (zScore > 2) return "Makrosefali";
            return "Normal";
    }
}

function calculateAndSubmit(event) {
    event.preventDefault();

    const gender = document.getElementById("gender").value;
    const age = parseInt(document.getElementById("age").value);
    const height = parseFloat(document.getElementById("height").value);
    const weight = parseFloat(document.getElementById("weight").value);
    const headCircumference = parseFloat(document.getElementById("headCircumference").value);
    const resultDiv = document.getElementById("result");

    if (!gender || isNaN(age) || isNaN(height) || isNaN(weight) || isNaN(headCircumference)) {
        resultDiv.className = "result error";
        resultDiv.innerHTML = "Mohon isi semua data dengan benar.";
        return;
    }

    // Find closest age standard
    const ageKey = Object.keys(whoStandards[gender]).reduce((closest, curr) => {
        return Math.abs(curr - age) < Math.abs(closest - age) ? curr : closest;
    });

    const standards = whoStandards[gender][ageKey];

    // Calculate all z-scores
    const zScoreHeight = (height - standards.height[0]) / standards.height[1];
    const zScoreWeight = (weight - standards.weight[0]) / standards.weight[1];
    const zScoreHead = (headCircumference - standards.headCircumference[0]) / standards.headCircumference[1];

    // Get status for each measurement
    const heightStatus = getStatusFromZScore(zScoreHeight, 'height');
    const weightStatus = getStatusFromZScore(zScoreWeight, 'weight');
    const headStatus = getStatusFromZScore(zScoreHead, 'head');

    // Store values in hidden inputs
    document.getElementById("z_score_height").value = zScoreHeight.toFixed(2);
    document.getElementById("z_score_weight").value = zScoreWeight.toFixed(2);
    document.getElementById("z_score_head").value = zScoreHead.toFixed(2);
    document.getElementById("z_score_status").value = JSON.stringify({
        height: heightStatus,
        weight: weightStatus,
        head: headStatus
    });

    // Create comprehensive analysis
    let overallAnalysis = "Kesimpulan Analisis:\n";
    if (heightStatus !== "Normal") {
        overallAnalysis += `- Perhatian khusus pada tinggi badan (${heightStatus})\n`;
    }
    if (weightStatus !== "Berat Badan Normal") {
        overallAnalysis += `- Perhatian khusus pada berat badan (${weightStatus})\n`;
    }
    if (headStatus !== "Normal") {
        overallAnalysis += `- Perhatian khusus pada lingkar kepala (${headStatus})\n`;
    }
    if (heightStatus === "Normal" && weightStatus === "Berat Badan Normal" && headStatus === "Normal") {
        overallAnalysis += "Semua pengukuran dalam rentang normal.";
    }

    // Display results
    resultDiv.className = "result success";
    resultDiv.innerHTML = `
        <div style="text-align: left; margin-bottom: 15px;">
            <strong>Hasil Pengukuran:</strong><br>
            1. Tinggi Badan:<br>
            • Z-Score: ${zScoreHeight.toFixed(2)}<br>
            • Status: ${heightStatus}<br><br>
            
            2. Berat Badan:<br>
            • Z-Score: ${zScoreWeight.toFixed(2)}<br>
            • Status: ${weightStatus}<br><br>
            
            3. Lingkar Kepala:<br>
            • Z-Score: ${zScoreHead.toFixed(2)}<br>
            • Status: ${headStatus}<br>
        </div>
        <div style="border-top: 1px solid #ccc; padding-top: 15px;">
            <strong>Analisis:</strong><br>
            ${overallAnalysis.replace(/\n/g, '<br>')}
        </div>
    `;

    // Submit form after delay
    setTimeout(() => {
        document.getElementById("zScoreForm").submit();
    }, 3000); // Increased to 3 seconds to give more time to read results
}
</script>


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
