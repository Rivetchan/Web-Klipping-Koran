<?php
session_start();
require_once "../../../config/koneksi.php";

if (!isset($_SESSION['Username']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: ../klipping.php");
    exit();
}

$id = intval($_GET['id']);

// Ambil data klipping berdasarkan ID
$query = mysqli_query($kon, "
    SELECT * FROM klipping WHERE BukuID = $id
");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data tidak ditemukan.";
    exit();
}

// Ambil data minggu, bulan, tahun untuk dropdown
$minggu = mysqli_query($kon, "SELECT * FROM minggu");
$bulan = mysqli_query($kon, "SELECT * FROM bulan");
$tahun = mysqli_query($kon, "SELECT * FROM tahun");

// Proses update jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $topik = htmlspecialchars($_POST['Topik']);
    $mingguID = $_POST['MingguID'];
    $bulanID = $_POST['BulanID'];
    $tahunID = $_POST['TahunID'];
    $tanggal = date('Y-m-d H:i:s');

    // Proses upload gambar jika diubah
    $image = $data['Image'];
    if (!empty($_FILES['Image']['name'])) {
        if (file_exists("../aset/foto/" . $image)) {
            unlink("../aset/foto/" . $image);
        }
        $image = uniqid() . "-" . $_FILES['Image']['name'];
        move_uploaded_file($_FILES['Image']['tmp_name'], "../aset/foto/" . $image);
    }

    // Proses upload PDF jika diubah
    $pdf = $data['PDF'];
    if (!empty($_FILES['PDF']['name'])) {
        if (file_exists("../aset/pdf/" . $pdf)) {
            unlink("../aset/pdf/" . $pdf);
        }
        $pdf = uniqid() . "-" . $_FILES['PDF']['name'];
        move_uploaded_file($_FILES['PDF']['tmp_name'], "../aset/pdf/" . $pdf);
    }

    // Simpan perubahan
    $update = mysqli_query($kon, "
        UPDATE klipping SET
            Topik = '$topik',
            MingguID = '$mingguID',
            BulanID = '$bulanID',
            TahunID = '$tahunID',
            Image = '$image',
            PDF = '$pdf',
            TanggalDibuat = '$tanggal'
        WHERE BukuID = $id
    ");

    if ($update) {
        header("Location: ../klipping.php");
        exit();
    } else {
        echo "Gagal menyimpan perubahan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Klipping</title>
    <link rel="stylesheet" href="edit.css">
</head>
<body>
<div class="container">
    <h1>Edit Data Klipping</h1>
    <form method="POST" enctype="multipart/form-data">
        <label>Topik:</label>
        <input type="text" name="Topik" value="<?= htmlspecialchars($data['Topik']); ?>" required>

        <label>Minggu:</label>
        <select name="MingguID" required>
            <?php while ($m = mysqli_fetch_assoc($minggu)) : ?>
                <option value="<?= $m['MingguID']; ?>" <?= $m['MingguID'] == $data['MingguID'] ? 'selected' : ''; ?>>
                    <?= $m['NamaMinggu']; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Bulan:</label>
        <select name="BulanID" required>
            <?php while ($b = mysqli_fetch_assoc($bulan)) : ?>
                <option value="<?= $b['BulanID']; ?>" <?= $b['BulanID'] == $data['BulanID'] ? 'selected' : ''; ?>>
                    <?= $b['NamaBulan']; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Tahun:</label>
        <select name="TahunID" required>
            <?php while ($t = mysqli_fetch_assoc($tahun)) : ?>
                <option value="<?= $t['TahunID']; ?>" <?= $t['TahunID'] == $data['TahunID'] ? 'selected' : ''; ?>>
                    <?= $t['Tahun']; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Gambar (biarkan kosong jika tidak diganti):</label>
        <input type="file" name="Image" accept="image/*">

        <label>PDF (biarkan kosong jika tidak diganti):</label>
        <input type="file" name="PDF" accept="application/pdf">

        <button type="submit" class="btn-simpan">Simpan Perubahan</button>
        <a href="../klipping.php" class="btn-kembali">← Batal</a>
    </form>
</div>
</body>
</html>
