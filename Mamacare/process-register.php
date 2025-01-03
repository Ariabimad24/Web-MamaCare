<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize inputs
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    $anxiety = filter_var($_POST['anxiety'], FILTER_SANITIZE_STRING);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $password = $_POST['password'];

    // Handle photo_profile upload
    $photoProfile = null;
    if (isset($_FILES['photo_profile']) && $_FILES['photo_profile']['error'] === UPLOAD_ERR_OK) {
        // Create uploads directory if it doesn't exist
        $targetDir = __DIR__ . "/uploads/";
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Generate unique filename
        $fileName = uniqid() . "_" . basename($_FILES['photo_profile']['name']);
        $targetFilePath = $targetDir . $fileName;
        $uploadOk = true;
        $imageFileType = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));

        // Validate file type and size
        $allowedFileTypes = ['jpg', 'jpeg', 'png', 'gif'];
        
        // Check if image file is actual image
        $check = getimagesize($_FILES["photo_profile"]["tmp_name"]);
        if($check === false) {
            echo "<script>alert('File bukan merupakan gambar.'); window.history.back();</script>";
            exit();
        }

        // Check file size (5MB max)
        if ($_FILES["photo_profile"]["size"] > 5000000) {
            echo "<script>alert('Ukuran file terlalu besar (maksimal 5MB).'); window.history.back();</script>";
            exit();
        }

        // Allow certain file formats
        if (!in_array($imageFileType, $allowedFileTypes)) {
            echo "<script>alert('Hanya file JPG, JPEG, PNG & GIF yang diperbolehkan.'); window.history.back();</script>";
            exit();
        }

        // Try to upload file
        if (move_uploaded_file($_FILES["photo_profile"]["tmp_name"], $targetFilePath)) {
            $photoProfile = "uploads/" . $fileName; // Store relative path in database
        } else {
            echo "<script>alert('Maaf, terjadi kesalahan saat mengunggah file.'); window.history.back();</script>";
            exit();
        }
    }

    // Check if email already exists
    $check_email = $conn->prepare("SELECT email FROM ibu_muda WHERE email = ?");
    $check_email->bind_param("s", $email);
    $check_email->execute();
    $result = $check_email->get_result();
   
    if ($result->num_rows > 0) {
        echo "<script>alert('Email sudah terdaftar!'); window.location.href = 'auth/register.php';</script>";
        exit();
    }

    // Insert data into ibu_muda table
    $stmt = $conn->prepare("INSERT INTO ibu_muda (nama, email, alamat, statusKecemasan, noHP, password, photo_profile) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $email, $address, $anxiety, $phone, $password, $photoProfile);

    if ($stmt->execute()) {
        $ibu_id = $stmt->insert_id; // Get the id of ibu_muda that was just inserted

        // Insert children names into anak table and get their ids
        $nama_anak_ids = []; // To store child ids
        if (isset($_POST['child_name']) && is_array($_POST['child_name'])) {
            foreach ($_POST['child_name'] as $child_name) {
                if (!empty($child_name)) {
                    // Insert each child's name into anak table
                    $child_stmt = $conn->prepare("INSERT INTO anak (nama) VALUES (?)");
                    $child_stmt->bind_param("s", $child_name);
                    $child_stmt->execute();
                    $child_id = $child_stmt->insert_id; // Get child id after insert
                    $nama_anak_ids[] = $child_id; // Store child id
                    $child_stmt->close();
                }
            }
        }

        // Update ibu_muda with the first child id (or handle multiple children if needed)
        if (!empty($nama_anak_ids)) {
            $first_child_id = $nama_anak_ids[0]; // Assume first child for simplicity
            $update_stmt = $conn->prepare("UPDATE ibu_muda SET nama_anak_id = ? WHERE id = ?");
            $update_stmt->bind_param("ii", $first_child_id, $ibu_id); // Update ibu_muda with first child id
            $update_stmt->execute();
            $update_stmt->close();
        }

        echo "<script>alert('Registrasi berhasil!'); window.location.href = 'auth/login.php';</script>";
    } else {
        echo "<script>alert('Terjadi kesalahan: " . $stmt->error . "'); window.location.href = 'auth/register.php';</script>";
    }

    $stmt->close();
    $check_email->close();
}
?>
