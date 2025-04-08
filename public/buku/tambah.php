<?php
session_start();
require_once "../../config/koneksi.php";

if (!isset($_SESSION['Username']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

// Ambil data dropdown
$minggu = mysqli_query($kon, "SELECT * FROM minggu");
$bulan = mysqli_query($kon, "SELECT * FROM bulan");
$tahun = mysqli_query($kon, "SELECT * FROM tahun");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $topik = htmlspecialchars($_POST['Topik']);
    $mingguID = $_POST['MingguID'];
    $bulanID = $_POST['BulanID'];
    $tahunID = $_POST['TahunID'];
    $userID = $_SESSION['UserID']; // Ambil UserID dari sesi login
    $tanggal = date('Y-m-d H:i:s');

    // Upload gambar ke aset/foto/
    $gambar = '';
    if ($_FILES['Image']['name'] != '') {
        $gambar = uniqid('img_') . '.' . pathinfo($_FILES['Image']['name'], PATHINFO_EXTENSION);
        move_uploaded_file($_FILES['Image']['tmp_name'], 'aset/foto/' . $gambar);
    }

    // Upload PDF ke aset/pdf/
    $pdf = '';
    if ($_FILES['PDF']['name'] != '') {
        $pdf = uniqid('pdf_') . '.' . pathinfo($_FILES['PDF']['name'], PATHINFO_EXTENSION);
        move_uploaded_file($_FILES['PDF']['tmp_name'], 'aset/pdf/' . $pdf);
    }

    // Simpan ke database
    $insert = mysqli_query($kon, "INSERT INTO klipping (Topik, MingguID, BulanID, TahunID, Image, PDF, UserID, TanggalDibuat)
                                  VALUES ('$topik', '$mingguID', '$bulanID', '$tahunID', '$gambar', '$pdf', '$userID', '$tanggal')");

    if ($insert) {
        echo "<script>alert('Data berhasil ditambahkan'); window.location.href='klipping.php';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Klipping</title>
    <link rel="stylesheet" href="tambah.css">
</head>
<body>
    <div class="form-container">
        <a href="klipping.php" class="btn-kembali">← Kembali</a>
        <h2>Tambah Data Klipping</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="Topik">Topik</label>
                <input type="text" name="Topik" id="Topik" required>
            </div>

            <div class="form-group">
                <label for="MingguID">Minggu</label>
                <select name="MingguID" id="MingguID" required>
                    <option value="">-- Pilih Minggu --</option>
                    <?php while ($m = mysqli_fetch_assoc($minggu)) : ?>
                        <option value="<?= $m['MingguID']; ?>"><?= $m['NamaMinggu']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="BulanID">Bulan</label>
                <select name="BulanID" id="BulanID" required>
                    <option value="">-- Pilih Bulan --</option>
                    <?php while ($b = mysqli_fetch_assoc($bulan)) : ?>
                        <option value="<?= $b['BulanID']; ?>"><?= $b['NamaBulan']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="TahunID">Tahun</label>
                <select name="TahunID" id="TahunID" required>
                    <option value="">-- Pilih Tahun --</option>
                    <?php while ($t = mysqli_fetch_assoc($tahun)) : ?>
                        <option value="<?= $t['TahunID']; ?>"><?= $t['Tahun']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="Image">Upload Gambar</label>
                <input type="file" name="Image" id="Image" accept="image/*">
            </div>

            <div class="form-group">
                <label for="PDF">Upload PDF</label>
                <input type="file" name="PDF" id="PDF" accept="application/pdf">
            </div>

            <button type="submit" class="btn-submit">Simpan</button>
        </form>
    </div>

    <footer class="footer">
        <div style="text-align:center; padding: 1rem; background: #61db67; color: white;">
            © <?= date('Y'); ?> Dinas Kominfo - Web Klipping
        </div>
    </footer>
</body>
</html>