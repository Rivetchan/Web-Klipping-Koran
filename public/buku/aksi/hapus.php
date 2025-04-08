<?php
session_start();
require_once "../../../config/koneksi.php";

if (!isset($_SESSION['Username']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../../login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data lama berdasarkan ID
    $query = mysqli_query($kon, "SELECT Image, PDF FROM klipping WHERE BukuID = '$id'");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        // Hapus file gambar jika ada
        $gambarPath = "../aset/foto/" . $data['Image'];
        if (!empty($data['Image']) && file_exists($gambarPath)) {
            unlink($gambarPath);
        }

        // Hapus file PDF jika ada
        $pdfPath = "../aset/pdf/" . $data['PDF'];
        if (!empty($data['PDF']) && file_exists($pdfPath)) {
            unlink($pdfPath);
        }

        // Hapus data dari database
        $delete = mysqli_query($kon, "DELETE FROM klipping WHERE BukuID = '$id'");

        if ($delete) {
            // Reset auto-increment
            mysqli_query($kon, "ALTER TABLE klipping AUTO_INCREMENT = 1");
            header("Location: ../klipping.php?hapus=berhasil");
            exit();
        } else {
            echo "Gagal menghapus data.";
        }
    } else {
        echo "Data tidak ditemukan.";
    }
} else {
    echo "ID tidak diberikan.";
}