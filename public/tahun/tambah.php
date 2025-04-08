<?php
session_start();
if (!isset($_SESSION['Username']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

include '../../config/koneksi.php';

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tahun = htmlspecialchars($_POST['tahun']);
    $tanggal = date('Y-m-d H:i:s');

    // Upload gambar
    $targetDir = 'aset/';
    $fileName = basename($_FILES['gambar']['name']);
    $targetFile = $targetDir . $fileName;
    $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    if (!empty($tahun) && in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetFile)) {
            // Simpan nama file saja (bukan path lengkap)
            $query = mysqli_query($kon, "INSERT INTO tahun (Tahun, TanggalDibuat, Image) 
                                         VALUES ('$tahun', '$tanggal', '$fileName')");

            if ($query) {
                $success = "Data berhasil ditambahkan!";
            } else {
                $error = "Gagal menyimpan data ke database.";
            }
        } else {
            $error = "Gagal mengunggah gambar.";
        }
    } else {
        $error = "Isi tahun dan upload gambar (jpg/jpeg/png/gif).";
    }
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Tahun</title>
    <link rel="stylesheet" href="tambah.css">
</head>
<body>
    <div class="form-container">
        <h2>Tambah Data Tahun</h2>

        <?php if ($success): ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php elseif ($error): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <label for="tahun">Tahun:</label>
            <input type="number" name="tahun" id="tahun" required>

            <label for="gambar">Gambar Tahun:</label>
            <input type="file" name="gambar" id="gambar" accept="image/*" required>

            <button type="submit" class="submit-btn">Simpan</button>
            <a href="tahun.php" class="cancel-btn">Kembali</a>
        </form>
    </div>

    <footer class="footer">
        © <?php echo date("Y"); ?> Dinas Kominfo - Web Klipping
    </footer>
</body>
</html>