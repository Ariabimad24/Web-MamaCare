<?php
require '../../../db_connect.php';

if (isset($_GET['id'])) {
    try {
        $id = $_GET['id'];
        
        // Get article information before deletion
        $stmt = $conn->prepare("SELECT judul, gambar, file_path FROM artikel_kesehatan WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $article = $result->fetch_assoc();
        
        if (!$article) {
            throw new Exception("Artikel tidak ditemukan");
        }

        // Delete the article file
        $articlePath = "../../../artikel/" . $article['file_path'];
        if (file_exists($articlePath)) {
            unlink($articlePath);
        }

        // Delete the image file
        if ($article['gambar']) {
            $imagePath = "../../../" . $article['gambar'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // Remove the card from all-artikel.php
        $allArtikelPath = "../../../all-artikel.php";
        $content = file_get_contents($allArtikelPath);
        
        // Create the card pattern to search for
        $cardPattern = '/<a href="artikel\/' . preg_quote($article['file_path']) . '".*?<\/a>/s';
        $newContent = preg_replace($cardPattern, '', $content);
        
        if ($newContent !== null) {
            file_put_contents($allArtikelPath, $newContent);
        }

        // Delete from database
        $stmt = $conn->prepare("DELETE FROM artikel_kesehatan WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            echo "<script>
                alert('Artikel berhasil dihapus');
                window.location.href = '/Mamacare/admin/artikel-admin.php';
            </script>";
        } else {
            throw new Exception("Gagal menghapus artikel dari database");
        }
    } catch (Exception $e) {
        echo "<script>
            alert('Error: " . addslashes($e->getMessage()) . "');
            window.history.back();
        </script>";
    }
}
?>