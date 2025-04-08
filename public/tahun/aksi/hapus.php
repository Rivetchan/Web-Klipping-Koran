<?php
session_start();
if (!isset($_SESSION['Username']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../../login.php");
    exit();
}

include '../../../config/koneksi.php';

$id = $_GET['id'] ?? '';

// Cek dan hapus gambar lama jika ada
$result = mysqli_query($kon, "SELECT Image FROM tahun WHERE TahunID='$id'");
if ($result && mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $gambarPath = "../aset/" . $row['Image'];
    if (!empty($row['Image']) && file_exists($gambarPath)) {
        unlink($gambarPath);
    }
}

// Hapus data dari database
$delete = mysqli_query($kon, "DELETE FROM tahun WHERE TahunID = '$id'");

if ($delete) {
    // Susun ulang ID biar urut dari 1 lagi
    mysqli_query($kon, "SET @num := 0");
    mysqli_query($kon, "UPDATE tahun SET TahunID = @num := @num + 1");
    mysqli_query($kon, "ALTER TABLE tahun AUTO_INCREMENT = 1");

    header("Location: ../tahun.php");
    exit();
} else {
    echo "Gagal menghapus data.";
}
