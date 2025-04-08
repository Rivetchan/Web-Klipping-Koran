<?php
session_start();
require_once '../../../config/koneksi.php';

if (!isset($_SESSION['Username']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: ../bulan.php");
    exit();
}

$id = $_GET['id'];

// Ambil gambar lama jika ada
$getData = mysqli_query($kon, "SELECT Image FROM bulan WHERE BulanID = '$id'");
if ($getData && mysqli_num_rows($getData) > 0) {
    $data = mysqli_fetch_assoc($getData);
    if (!empty($data['Image']) && file_exists('../aset/' . $data['Image'])) {
        unlink('../aset/' . $data['Image']); // Hapus gambar dari folder
    }
}

// Hapus data
$delete = mysqli_query($kon, "DELETE FROM bulan WHERE BulanID = '$id'");

if ($delete) {
    // Susun ulang ID agar berurutan dari 1
    mysqli_query($kon, "SET @num := 0");
    mysqli_query($kon, "UPDATE bulan SET BulanID = @num := @num + 1");
    mysqli_query($kon, "ALTER TABLE bulan AUTO_INCREMENT = 1");

    header("Location: ../bulan.php");
    exit();
} else {
    echo "Gagal menghapus data.";
}