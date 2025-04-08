<?php
session_start();
require_once '../../../config/koneksi.php';

if (!isset($_SESSION['Username']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

$id = $_GET['id'] ?? null;

if ($id) {
    // Hapus data berdasarkan ID
    mysqli_query($kon, "DELETE FROM minggu WHERE MingguID = '$id'");

    // Reset auto increment dan reindex ID
    mysqli_query($kon, "SET @num := 0");
    mysqli_query($kon, "UPDATE minggu SET MingguID = @num := @num + 1");
    mysqli_query($kon, "ALTER TABLE minggu AUTO_INCREMENT = 1");
}

header("Location: ../minggu.php");
exit();
?>
