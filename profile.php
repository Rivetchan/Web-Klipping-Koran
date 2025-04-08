<?php
session_start();
require_once "config/koneksi.php";

if (!isset($_SESSION['Username']) || !isset($_SESSION['level'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['Username'];
$level = $_SESSION['level']; // Bisa admin atau member

$query = mysqli_query($kon, "SELECT * FROM user WHERE Username = '$username'");
$data = mysqli_fetch_assoc($query);

$fotoPath = !empty($data['Image']) ? "aset-foto/" . $data['Image'] : "style/images/default-image.png";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaLengkap = $_POST['NamaLengkap'];
    $email = $_POST['Email'];
    $userId = $data['UserID'];
    $namaFotoBaru = '';

    if (!empty($_FILES['foto']['name'])) {
        $ekstensi = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $namaFotoBaru = "User-$userId.$ekstensi";
        $pathFotoBaru = "aset-foto/" . $namaFotoBaru;

        // Hapus foto lama jika ada
        if (!empty($data['Image'])) {
            $fotoLamaPath = "aset-foto/" . $data['Image'];
            if (file_exists($fotoLamaPath)) {
                unlink($fotoLamaPath);
            }
        }

        move_uploaded_file($_FILES['foto']['tmp_name'], $pathFotoBaru);
        mysqli_query($kon, "UPDATE user SET NamaLengkap='$namaLengkap', Email='$email', Image='$namaFotoBaru' WHERE UserID='$userId'");
    } else {
        mysqli_query($kon, "UPDATE user SET NamaLengkap='$namaLengkap', Email='$email' WHERE UserID='$userId'");
    }

    header("Location: profile.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Pengguna</title>
    <link rel="stylesheet" href="style/css/profile.css">
</head>
<body>

<div class="popup-container">
    <div class="popup-card">
        <h2>Profil <?= ucfirst($level); ?></h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="profile-image-wrapper">
                <img src="<?= htmlspecialchars($fotoPath); ?>" alt="Foto Profil" class="profile-image">
                <input type="file" name="foto" accept="image/*">
            </div>

            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="NamaLengkap" value="<?= htmlspecialchars($data['NamaLengkap'] ?? ''); ?>" required>
            </div>

            <div class="form-group">
                <label>Username</label>
                <input type="text" value="<?= htmlspecialchars($data['Username']); ?>" readonly>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="Email" value="<?= htmlspecialchars($data['Email'] ?? ''); ?>" required>
            </div>

            <div class="form-actions">
                <button type="submit" class="save-btn">Simpan Perubahan</button>
                <a href="edit-password.php" class="password-btn">Edit Password</a>
                <a href="index.php" class="cancel-btn">Kembali</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>