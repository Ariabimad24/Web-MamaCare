<?php
include '../../../db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Begin transaction
    $conn->begin_transaction();
    
    try {
        // Delete from anak table first
        $query_anak = "DELETE FROM anak WHERE id = (SELECT nama_anak_id FROM ibu_muda WHERE id = ?)";
        $stmt_anak = $conn->prepare($query_anak);
        $stmt_anak->bind_param("i", $id);
        $stmt_anak->execute();
        
        // Then delete from ibu_muda table
        $query_ibu = "DELETE FROM ibu_muda WHERE id = ?";
        $stmt_ibu = $conn->prepare($query_ibu);
        $stmt_ibu->bind_param("i", $id);
        $stmt_ibu->execute();
        
        // Commit transaction
        $conn->commit();
        
        // Redirect dengan notifikasi sukses
        header("Location: ../../data-pengguna.php?message=Data berhasil dihapus");
        exit();
    } catch (Exception $e) {
        // Rollback jika ada kesalahan
        $conn->rollback();
        
        // Redirect dengan notifikasi error
        header("Location: ../../data-pengguna.php?message=Terjadi kesalahan saat menghapus data");
        exit();
    }
}
?>
