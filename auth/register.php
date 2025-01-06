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
            max-width: 800px;
        }

        .register-logo img {
            width: 100px;
            margin-bottom: 10px;
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
            margin-bottom: 40px;
        }

        .role-selection-title {
            color: #fff;
            font-size: 24px;
            margin-bottom: 30px;
        }

        .role-options {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 20px;
        }

        .role-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            padding: 20px;
            width: 250px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .role-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .role-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }

        .role-card:hover::before {
            opacity: 1;
        }

        .role-card.selected {
            background: rgba(255, 193, 7, 0.2);
            border: 2px solid #FFD700;
        }

        .role-image {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            margin: 0 auto 15px;
            overflow: hidden;
            border: 3px solid #FFD700;
            transition: all 0.3s ease;
        }

        .role-card:hover .role-image {
            transform: scale(1.1);
        }

        .role-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .role-title {
            color: #fff;
            font-size: 20px;
            margin-bottom: 10px;
        }

        .role-description {
            color: #fff;
            font-size: 14px;
            opacity: 0.8;
        }

        .continue-btn {
            background: #004d40;
            color: #fff;
            border: none;
            padding: 12px 40px;
            border-radius: 25px;
            font-size: 16px;
            margin-top: 40px;
            cursor: pointer;
            transition: all 0.3s ease;
            opacity: 0;
            transform: translateY(20px);
            visibility: hidden;
        }

        .continue-btn.visible {
            opacity: 1;
            transform: translateY(0);
            visibility: visible;
        }

        .continue-btn:hover {
            background: #01796f;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }

        .continue-btn:disabled {
            background: #cccccc;
            cursor: not-allowed;
        }

        .login-link {
            color: #ffc107;
            text-decoration: none;
            font-size: 14px;
            margin-top: 20px;
            display: inline-block;
        }

        .login-link:hover {
            text-decoration: underline;
        }

        .error-message {
            color: #ff6b6b;
            font-size: 14px;
            margin-top: 10px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-logo">
            <img src="../assets/images/icon.png" alt="Mama Care Logo">
        </div>
        <div class="mama">MAMA</div>
        <div class="care">CARE</div>
        
        <div class="role-selection-title">Pilih Jenis Akun Anda</div>
        
        <form id="roleForm" method="POST" action="process_role.php">
            <input type="hidden" name="selected_role" id="selectedRoleInput">
            
            <div class="role-options">
                <div class="role-card" data-role="nakes" onclick="selectRole('nakes', this)">
                    <div class="role-image">
                        <img src="../assets/images/nakes.png" alt="Nakes">
                    </div>
                    <div class="role-title">Tenaga Kesehatan</div>
                    <div class="role-description">
                        Bergabung sebagai tenaga kesehatan untuk memberikan konsultasi dan bantuan profesional
                    </div>
                </div>
                
                <div class="role-card" data-role="ibu" onclick="selectRole('ibu', this)">
                    <div class="role-image">
                        <img src="../assets/images/ibu.png" alt="Ibu Muda">
                    </div>
                    <div class="role-title">Ibu</div>
                    <div class="role-description">
                        Bergabung sebagai ibu untuk mendapatkan bantuan dan dukungan dalam merawat anak
                    </div>
                </div>
            </div>

            <div id="errorMessage" class="error-message">
                Silakan pilih jenis akun terlebih dahulu
            </div>

            <button type="submit" id="continueBtn" class="continue-btn">
                Lanjutkan Pendaftaran
            </button>
        </form>
        
        <a href="login.php" class="login-link">Sudah punya akun? Login</a>
    </div>

    <script>
        let selectedRole = '';
        const form = document.getElementById('roleForm');
        const errorMessage = document.getElementById('errorMessage');
        const continueBtn = document.getElementById('continueBtn');
        const selectedRoleInput = document.getElementById('selectedRoleInput');

        function selectRole(role, element) {
            selectedRole = role;
            selectedRoleInput.value = role;
            
            // Remove selected class from all cards
            document.querySelectorAll('.role-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // Add selected class to clicked card
            element.classList.add('selected');
            
            // Show continue button
            continueBtn.classList.add('visible');
            errorMessage.style.display = 'none';
        }

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!selectedRole) {
                errorMessage.style.display = 'block';
                return;
            }

            // Store the selected role in session storage for persistence
            sessionStorage.setItem('selectedRole', selectedRole);

            // Redirect to the appropriate registration page
            window.location.href = `register_${selectedRole}.php`;
        });

        // Check if there was a previously selected role (e.g., if user hits back button)
        window.addEventListener('load', function() {
            const previousRole = sessionStorage.getItem('selectedRole');
            if (previousRole) {
                const roleCard = document.querySelector(`[data-role="${previousRole}"]`);
                if (roleCard) {
                    selectRole(previousRole, roleCard);
                }
            }
        });
    </script>
</body>
</html>