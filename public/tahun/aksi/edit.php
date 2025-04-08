<?php
session_start();
if (!isset($_SESSION['Username']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../../login.php");
    exit();
}

include '../../../config/koneksi.php';

$id = $_GET['id'] ?? '';
$query = mysqli_query($kon, "SELECT * FROM tahun WHERE TahunID = '$id'");
$data = mysqli_fetch_assoc($query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tahun = htmlspecialchars($_POST['tahun']);
    $tanggal = date('Y-m-d H:i:s');

    // Jika ada file gambar baru
    if (!empty($_FILES['gambar']['name'])) {
        $targetDir = "../aset/";
        $fileName = basename($_FILES['gambar']['name']);
        $targetFile = $targetDir . $fileName;
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileType, $allowedTypes)) {
            // Hapus gambar lama
            $oldImagePath = $targetDir . $data['Image'];
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }

            // Upload gambar baru
            move_uploaded_file($_FILES['gambar']['tmp_name'], $targetFile);
            $imagePath = $fileName;

            mysqli_query($kon, "UPDATE tahun SET Tahun='$tahun', TanggalDibuat='$tanggal', Image='$imagePath' WHERE TahunID='$id'");
        }
    } else {
        mysqli_query($kon, "UPDATE tahun SET Tahun='$tahun', TanggalDibuat='$tanggal' WHERE TahunID='$id'");
    }

    header("Location: ../tahun.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Tahun</title>
    <link rel="stylesheet" href="edit.css">
</head>
<body>
    <div class="table-container">
        <h2>Edit Data Tahun</h2>
        <form method="POST" enctype="multipart/form-data">
            <label for="tahun">Tahun:</label>
            <input type="number" name="tahun" id="tahun" value="<?= $data['Tahun'] ?>" required><br><br>

            <p>Gambar Saat Ini:</p>
            <img src="../aset/<?= $data['Image'] ?>" width="120"><br><br>

            <label for="gambar">Ganti Gambar (Opsional):</label>
            <input type="file" name="gambar" id="gambar" accept="image/*"><br><br>

            <button type="submit" class="btn-edit">Simpan Perubahan</button>
            <a href="../tahun.php" class="btn-hapus">Kembali</a>
        </form>
    </div>
</body>
</html>