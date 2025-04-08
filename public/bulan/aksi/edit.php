<?php
session_start();
require_once '../../../config/koneksi.php';

if (!isset($_SESSION['Username']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

$id = $_GET['id'] ?? '';
$errors = [];

$query = "SELECT * FROM bulan WHERE BulanID = '$id'";
$result = mysqli_query($kon, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "Data tidak ditemukan.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaBulan = trim($_POST['NamaBulan']);
    $image = $data['Image']; // default image lama

    if (empty($namaBulan)) {
        $errors[] = "Nama Bulan tidak boleh kosong.";
    }

    if ($_FILES['Image']['name']) {
        $targetDir = "../aset/";
        $fileName = basename($_FILES["Image"]["name"]);
        $targetFile = $targetDir . time() . '_' . $fileName;
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowedTypes)) {
            $errors[] = "Format gambar tidak valid.";
        } else {
            if (move_uploaded_file($_FILES["Image"]["tmp_name"], $targetFile)) {
                // Hapus gambar lama
                if (!empty($data['Image']) && file_exists($targetDir . $data['Image'])) {
                    unlink($targetDir . $data['Image']);
                }
                $image = basename($targetFile); // Simpan nama gambar baru
            } else {
                $errors[] = "Gagal upload gambar.";
            }
        }
    }

    if (empty($errors)) {
        $queryUpdate = "UPDATE bulan SET NamaBulan='$namaBulan', Image='$image' WHERE BulanID='$id'";
        if (mysqli_query($kon, $queryUpdate)) {
            header("Location: ../bulan.php");
            exit();
        } else {
            $errors[] = "Gagal memperbarui data.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Data Bulan</title>
    <link rel="stylesheet" href="edit.css">
</head>
<body>

<main class="main-content">
    <h2 class="title">Edit Data Bulan</h2>

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
            <input type="text" name="NamaBulan" id="NamaBulan" value="<?= htmlspecialchars($data['NamaBulan']); ?>" required>
        </div>

        <div class="form-group">
            <label for="Image">Gambar Baru (Opsional)</label>
            <input type="file" name="Image" id="Image" accept="image/*">
        </div>

        <?php if (!empty($data['Image'])): ?>
            <div class="form-group">
                <label>Gambar Lama:</label><br>
                <img src="../aset/<?= htmlspecialchars($data['Image']); ?>" alt="Gambar Lama" class="year-image" style="max-height:150px;">
            </div>
        <?php endif; ?>

        <div class="form-actions">
            <button type="submit" class="submit-btn">Simpan</button>
            <a href="../bulan.php" class="cancel-btn">Batal</a>
        </div>
    </form>
</main>

<footer class="footer">
    © <?= date("Y"); ?> Dinas Kominfo - Web Klipping
</footer>

</body>
</html>
