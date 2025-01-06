<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Mama Care</title>
    <style>
        /* Global Styles */
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
            margin-bottom: 10px; /* Reduced from 20px */
        }
        .register-logo img {
            width: 100px;
            height: 100px;
            object-fit: contain;
        }
        .brand-text {
            margin-bottom: 30px; /* Added spacing after MAMA CARE text */
        }
        .register-title {
            font-size: 36px;
            font-weight: bold;
            color: #fff;
        }
        .register-form {
            text-align: center;
            position: relative;
            margin-top: 20px; /* Added spacing before form inputs */
        }
        .register-form input, .register-form select {
            display: block;
            width: 80%;
            padding: 10px 15px;
            margin: 15px auto; /* Increased from 10px */
            border: 2px solid #ccc;
            border-radius: 25px;
            font-size: 14px;
            background-color: transparent;
            color: #000;
            transition: all 0.3s ease;
        }
        .register-form input {
            color: #fff;
            background: transparent;
        }
        .register-form select {
            background-color: #fff;
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
            margin: 25px auto; /* Increased from 15px */
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
        .mama {
            font-family: 'Abyssinica SIL', serif;
            font-size: 35px;
            color: #fff;
            margin-top: -10px
        }
        .care {
            font-family: 'Abyssinica SIL', serif;
            font-size: 35px;
            color: #FFD700;
            margin-bottom: 25px;
        }
        .label-left {
            text-align: left;
            width: 80%;
            display: inline-block;
            margin-left: 10%;
            font-size: 14px;
            color: #fff;
            margin-top: 10px; /* Decreased margin to move closer to the dropdown */
        }

        .profile {
            text-align: left;
            width: 80%;
            display: inline-block;
            font-size: 14px;
            color: #fff;
            margin-top: 10px; /* Decreased margin to move closer to the dropdown */
        }
        .children-wrapper {
            width: 80%;
            margin-left: 20px;
        }
        .child-container {
            margin-bottom: 10px;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .child-input-group {
            width: 100%;
            margin-bottom: 15px; /* Increased spacing between child inputs */
        }
        .child-input-group input {
            width: 100%;
            margin: 0;
        }
        .add-child-btn {
            margin: 20px auto; /* Increased spacing */
            background-color: #01796f;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
            width: 80%;
            display: block;
        }
        .add-child-btn:hover {
            background-color: #004d40;
        }
        .error-message {
            color: #ff4d4d;
            font-size: 12px;
            margin-top: 5px;
            display: none;
        }
        .child-input-group.error input {
            border-color: #ff4d4d;
        }
        .child-input-group.error .error-message {
            display: block;
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
        <form class="register-form" action="../process-register.php" method="POST" id="registerForm" enctype="multipart/form-data">
    <input type="text" name="name" placeholder="Nama Lengkap" required>
    <input type="email" name="email" placeholder="Alamat Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="text" name="address" placeholder="Alamat" required>
    
    <label for="anxiety" class="label-left">Status Kecemasan:</label>
    <select name="anxiety" id="anxiety" required>
        <option value="stunting">Kekhawatiran Stunting</option>
        <option value="underweight">Berat Badan Kurang</option>
        <option value="growth">Pertumbuhan Anak</option>
    </select>

    <div class="children-wrapper">
        <div id="children-container">
            <div class="child-input-group" data-child-id="1">
                <input type="text" name="child_name[]" placeholder="Nama Anak" required>
            </div>
        </div>
    </div>
    
    <button type="button" onclick="addChild()" class="add-child-btn">Tambah Anak</button>

    <input type="text" name="phone" placeholder="Nomor HP" required>
<label for="photo_profile" class="profile">Upload Foto untuk Foto Profile:</label>
<input type="file" name="photo_profile" id="photo_profile" accept="image/*" required>

    <button type="submit" class="register-btn">Register</button>
</form>

        <a href="login.php" class="login-link">Already have an account? Login</a>
    </div>
    <script>
        let childCounter = 1;

        function addChild() {
            childCounter++;
            const childrenContainer = document.getElementById("children-container");
            const childInputGroup = document.createElement("div");
            childInputGroup.classList.add("child-input-group");
            childInputGroup.setAttribute("data-child-id", childCounter);
            
            childInputGroup.innerHTML = `
                <input type="text" name="child_name[]" placeholder="Nama Anak" required>
                <div class="error-message">Nama anak tidak boleh kosong</div>
            `;
            
            childrenContainer.appendChild(childInputGroup);

            // Add event listener to the new input
            const newInput = childInputGroup.querySelector('input');
            newInput.addEventListener('input', validateChildName);
        }

        function validateChildName(event) {
            const input = event.target;
            const inputGroup = input.parentElement;
            
            if (input.value.trim() === '') {
                inputGroup.classList.add('error');
            } else {
                inputGroup.classList.remove('error');
            }
        }

        // Add validation to existing child inputs
        document.querySelectorAll('.child-input-group input').forEach(input => {
            input.addEventListener('input', validateChildName);
        });

        // Form submission validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            const childInputs = document.querySelectorAll('.child-input-group input');
            let isValid = true;

            childInputs.forEach(input => {
                if (input.value.trim() === '') {
                    input.parentElement.classList.add('error');
                    isValid = false;
                }
            });

            if (!isValid) {
                e.preventDefault();
            }
        });
    </script>
</body>
</html>
