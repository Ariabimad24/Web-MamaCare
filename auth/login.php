<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mama Care</title>
    <style>
        /* Global Styles */
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #01796f, #004d40);
        }

        .login-container {
            text-align: center;
            background: transparent;
            padding: 20px;
            border-radius: 10px;
            width: 100%;
            max-width: 360px;
        }

        .login-logo img {
            width: 100px;
        }

        .login-title {
            font-size: 36px;
            font-weight: bold;
            color: #fff;
        }

        .login-subtitle {
            font-size: 24px;
            color: #ffc107;
            margin-bottom: 30px;
        }

        .login-form {
            text-align: center;
            position: relative;
        }

        .login-form input {
            display: block;
            width: 80%;
            padding: 10px 15px;
            margin: 10px auto;
            border: 2px solid #ccc; /* Default border */
            border-radius: 25px;
            font-size: 14px;
            background-color: transparent;
            color: #fff;
            transition: all 0.3s ease;
        }

        .login-form input:focus {
            outline: none;
            border: 2px solid #ffc107; /* Border color when focused */
            box-shadow: 0 0 8px rgba(255, 193, 7, 0.8); /* Soft glow effect */
        }

        .login-form input:hover {
            border: 2px solid #01796f; /* Border color when hovered */
            background-color: rgba(0, 77, 64, 0.1); /* Light background on hover */
        }

        .login-form .login-btn {
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
            margin: 15px auto;
            display: block;
            margin-top: 35px;
            margin-bottom: -25px
        }

        .login-form .login-btn:hover {
            background-color: #01796f;
        }

        .register-link {
            font-size: 12px;
            color: #ffc107;
            text-decoration: none;
            margin-left: 120px;  /* Adjusted margin for more space */
            display: inline-block;
        }

        .register-link:hover {
            text-decoration: underline;
        }

        .mama {
            font-family: 'Abyssinica SIL', serif;
            font-size: 35px;
            color: #fff;
        }

        .care {
            font-family: 'Abyssinica SIL', serif;
            font-size: 35px;
            color: #FFD700;
            margin-bottom: 40px
        }
        
        /* Pesan error dan sukses */
        .alert {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
            text-align: center;
        }
        .alert.error {
            background-color: #ffcccb;
            color: #a94442;
        }
        .alert.success {
            background-color: #d4edda;
            color: #155724;
        }
        .login-form .forgot-btn {
    width: 60%;
    padding: 10px 15px;
    background-color: #43a047; /* Warna hijau muda */
    border: none;
    border-radius: 25px;
    color: #fff;
    font-size: 14px;
    font-weight: bold;
    cursor: pointer;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
    transition: 0.3s;
    margin: 15px auto;
    display: block;
    margin-top: 35px;
    margin-bottom: -25px;
}

.login-form .forgot-btn:hover {
    background-color: #2e7d32; /* Warna hijau lebih gelap saat hover */
}
a {
  text-decoration: none; /* Menghilangkan underline */
}



    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-logo">
            <img src="../assets/images/icon.png" alt="Mama Care Logo">
        </div>
        <div class="mama">MAMA</div>
        <div class="care">CARE</div>
        
        <!-- Menampilkan pesan error atau sukses -->
        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="alert error">' . htmlspecialchars($_SESSION['error']) . '</div>';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo '<div class="alert success">' . htmlspecialchars($_SESSION['success']) . '</div>';
            unset($_SESSION['success']);
        }
        ?>

<form class="login-form" action="../process-login.php" method="POST">
    <input type="email" name="email" placeholder="Alamat Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <a href="register.php" class="register-link">Don't have an account? Register</a>
    <button type="submit" class="login-btn">Login</button>
    <a href="forgot.php" class="forgot-btn">Forgot Password</a>
</form>
    </div>
</body>
</html>
