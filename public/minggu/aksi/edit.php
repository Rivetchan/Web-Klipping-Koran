<?php
session_start();
require_once '../../../config/koneksi.php';

if (!isset($_SESSION['Username']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

$id = $_GET['id'] ?? '';
$errors = [];

// Ambil data yang akan diedit
$query = "SELECT * FROM minggu WHERE MingguID = '$id'";
$result = mysqli_query($kon, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "Data tidak ditemukan.";
    exit();
}

// Jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaMinggu = trim($_POST['NamaMinggu']);

    if (empty($namaMinggu)) {
        $errors[] = "Nama Minggu tidak boleh kosong.";
    }

    if (empty($errors)) {
        $queryUpdate = "UPDATE minggu SET NamaMinggu = '$namaMinggu' WHERE MingguID = '$id'";
        if (mysqli_query($kon, $queryUpdate)) {
            header("Location: ../minggu.php");
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
    <title>Edit Minggu</title>
    <link rel="stylesheet" href="edit.css">
</head>
<body>

<main class="main-content">
    <h2 class="title">Edit Data Minggu</h2>

    <?php if (!empty($errors)) : ?>
        <div class="alert">
            <?php foreach ($errors as $error) : ?>
                <p><?= htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <div class="form-group">
            <label for="NamaMinggu">Nama Minggu</label>
            <input type="text" name="NamaMinggu" id="NamaMinggu" value="<?= htmlspecialchars($data['NamaMinggu']); ?>" required>
        </div>

        <div class="form-actions">
            <button type="submit" class="submit-btn">Simpan</button>
            <a href="../minggu.php" class="cancel-btn">Batal</a>
        </div>
    </form>
</main>

<footer class="footer">
    © <?= date("Y"); ?> Dinas Kominfo - Web Klipping
</footer>

</body>
</html>
