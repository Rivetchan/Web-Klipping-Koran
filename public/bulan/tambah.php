<?php
session_start();
require_once '../../config/koneksi.php';

if (!isset($_SESSION['Username']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaBulan = trim($_POST['NamaBulan']);
    $image = '';

    if (empty($namaBulan)) {
        $errors[] = "Nama Bulan tidak boleh kosong.";
    }

    if ($_FILES['Image']['name']) {
        $targetDir = "aset/";
        $fileName = basename($_FILES["Image"]["name"]);
        $targetFile = $targetDir . time() . '_' . $fileName;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowedTypes)) {
            $errors[] = "Format gambar tidak valid. Hanya JPG, PNG, GIF.";
        } else {
            if (move_uploaded_file($_FILES["Image"]["tmp_name"], $targetFile)) {
                $image = basename($targetFile);
            } else {
                $errors[] = "Gagal mengupload gambar.";
            }
        }
    }

    if (empty($errors)) {
        $tanggalSekarang = date('Y-m-d');
        $query = "INSERT INTO bulan (NamaBulan, Image, TanggalDibuat) VALUES ('$namaBulan', '$image', '$tanggalSekarang')";
        $result = mysqli_query($kon, $query);

        if ($result) {
            header("Location: bulan.php");
            exit();
        } else {
            $errors[] = "Gagal menambahkan data.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Data Bulan</title>
    <link rel="stylesheet" href="tambah.css">
</head>
<body>

<main class="main-content">
    <h2 class="title">Tambah Data Bulan</h2>

    <?php if (!empty($errors)) : ?>
        <div class="alert">
            <?php foreach ($errors as $error) : ?>
                <p><?= htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="NamaBulan">Nama Bulan</label>
            <input type="text" name="NamaBulan" id="NamaBulan" required>
        </div>

        <div class="form-group">
            <label for="Image">Gambar (Opsional)</label>
            <input type="file" name="Image" id="Image" accept="image/*">
        </div>

        <div class="form-actions">
            <button type="submit" class="submit-btn">Simpan</button>
            <a href="bulan.php" class="cancel-btn">Batal</a>
        </div>
    </form>
</main>

<footer class="footer">
    © <?= date("Y"); ?> Dinas Kominfo - Web Klipping
</footer>

</body>
</html>
