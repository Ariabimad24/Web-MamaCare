<?php
include '../../../db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Mulai transaksi
    $conn->begin_transaction();
    
    try {
        // Hapus data dari tabel `nakes`
        $query_nakes = "DELETE FROM nakes WHERE id = ?";
        $stmt_nakes = $conn->prepare($query_nakes);
        $stmt_nakes->bind_param("i", $id);
        $stmt_nakes->execute();
        
        // Commit transaksi
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
