<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Nakes - Mama Care</title>
    <style>
        /* Global Styles */
        .profile {
            text-align: left;
            width: 80%;
            display: inline-block;
            font-size: 14px;
            color: #fff;
            margin-top: 10px; /* Decreased margin to move closer to the dropdown */
        }
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #01796f, #004d40);
        }
        .register-container {
            text-align: center;
            background: transparent;
            padding: 20px;
            border-radius: 10px;
            width: 100%;
            max-width: 360px;
            position: relative;
            z-index: 10;
            margin: 40px 0;
        }
        .register-logo {
            position: relative;
            margin-bottom: 10px;
        }
        .register-logo img {
            width: 100px;
            height: 100px;
            object-fit: contain;
        }
        .brand-text {
            margin-bottom: 30px;
        }
        .mama {
            font-family: 'Abyssinica SIL', serif;
            font-size: 35px;
            color: #fff;
            margin-top: -10px;
        }
        .care {
            font-family: 'Abyssinica SIL', serif;
            font-size: 35px;
            color: #FFD700;
            margin-bottom: 25px;
        }
        .register-form {
            text-align: center;
            position: relative;
            margin-top: 20px;
        }
        .register-form input, .register-form select {
            display: block;
            width: 80%;
            padding: 10px 15px;
            margin: 15px auto;
            border: 2px solid #ccc;
            border-radius: 25px;
            font-size: 14px;
            background-color: transparent;
            color: #fff;
            transition: all 0.3s ease;
        }
        .register-form select {
            background-color: #fff;
            color: #000;
        }
        .register-form select option {
            background-color: #fff;
            color: #000;
        }
        .register-form input:focus, .register-form select:focus {
            outline: none;
            border: 2px solid #ffc107;
            box-shadow: 0 0 8px rgba(255, 193, 7, 0.8);
        }
        .register-form .register-btn {
            width: 60%;
            padding: 10px 15px;
            background-color: #004d40;
            border: none;
            border-radius: 25px;
            color: #fff;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            transition: 0.3s;
            margin: 25px auto;
            display: block;
        }
        .register-form .register-btn:hover {
            background-color: #01796f;
        }
        .login-link {
            font-size: 12px;
            color: #ffc107;
            text-decoration: none;
            display: inline-block;
            margin-top: 15px;
        }
        .login-link:hover {
            text-decoration: underline;
        }
        .label-left {
            text-align: left;
            width: 80%;
            display: inline-block;
            margin-left: 10%;
            font-size: 14px;
            color: #fff;
            margin-top: 10px;
        }
        .file-input-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }
        .file-input-label {
            text-align: left;
            color: #fff;
            font-size: 14px;
            width: 80%;
            margin-top: 10px;
        }
        input[type="file"] {
            width: 80%;
            padding: 10px 15px;
            border: 2px solid #ccc;
            border-radius: 25px;
            font-size: 14px;
            background-color: transparent;
            color: #fff;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        input[type="file"]:hover {
            border-color: #ffc107;
        }
        input[type="file"]::-webkit-file-upload-button {
            background: #004d40;
            border: none;
            border-radius: 15px;
            color: #fff;
            padding: 8px 15px;
            cursor: pointer;
            transition: 0.3s ease;
        }
        input[type="file"]::-webkit-file-upload-button:hover {
            background: #01796f;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-logo">
            <img src="../assets/images/icon.png" alt="Mama Care Logo">
        </div>
        <div class="brand-text">
            <div class="mama">MAMA</div>
            <div class="care">CARE</div>
        </div>
        <form class="register-form" action="../process-register-nakes.php" method="POST" enctype="multipart/form-data">
            <input type="text" name="name" placeholder="Nama Lengkap" required>
            <input type="email" name="email" placeholder="Alamat Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="hidden" name="admin_id" value="1">
            <input type="text" name="phone" placeholder="Nomor Layanan Konsultasi" required>
            <input type="text" name="address" placeholder="Alamat" required>
            
            <label for="specialization" class="label-left">Spesialisasi:</label>
            <select name="specialization" id="specialization" required>
                <option value="">Pilih Spesialisasi</option>
                <option value="pediatrician">Dokter Spesialis Anak Umum</option>
                <option value="obgyn">Dokter Anak Spesialis Jantung (Kardiologi Anak)</option>
                <option value="nutritionist">Ahli Gizi</option>
                <option value="psychologist">Psikolog Anak</option>
                <option value="midwife">Bidan</option>
            </select>

            <div class="file-input-container">
                <label class="file-input-label">Sertifikat Kedokteran:</label>
                <input type="file" name="sertifikat_kedokteran" accept=".pdf,.jpg,.jpeg,.png" required>
            </div>

            <label for="qualification" class="label-left">Kualifikasi:</label>
            <select name="qualification" id="qualification" required>
                <option value="">Pilih Kualifikasi</option>
                <option value="specialist">Dokter Spesialis</option>
                <option value="general">Dokter Umum</option>
                <option value="professional">Tenaga Profesional</option>
            </select>
<label for="photo_profile" class="profile">Upload Foto untuk Foto Profile:</label>
<input type="file" name="photo_profile" id="photo_profile" accept="image/*" required>
            <button type="submit" class="register-btn">Register</button>
        </form>
        <a href="login.php" class="login-link">Already have an account? Login</a>
    </div>
</body>
</html>
