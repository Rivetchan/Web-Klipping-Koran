<?php
session_start();
require_once "config/koneksi.php";

if (!isset($_SESSION['Username']) || !isset($_SESSION['level'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['Username'];
$query = mysqli_query($kon, "SELECT * FROM user WHERE Username = '$username'");
$data = mysqli_fetch_assoc($query);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $passwordBaru = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi'];

    if ($passwordBaru !== $konfirmasi) {
        $error = "Konfirmasi password tidak sesuai.";
    } else {
        $passwordHash = password_hash($passwordBaru, PASSWORD_DEFAULT);
        $userId = $data['UserID'];

        mysqli_query($kon, "UPDATE user SET Password = '$passwordHash' WHERE UserID = '$userId'");
        $sukses = "Password berhasil diperbarui.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Password</title>
    <link rel="stylesheet" href="style/css/profile.css">
</head>
<body>

<div class="popup-container">
    <div class="popup-card">
        <h2>Edit Password</h2>

        <?php if (!empty($error)) : ?>
            <div class="alert error"><?= htmlspecialchars($error); ?></div>
        <?php elseif (!empty($sukses)) : ?>
            <div class="alert success"><?= htmlspecialchars($sukses); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Password Baru</label>
                <input type="password" name="password" required minlength="5" placeholder="Masukkan password baru">
            </div>

            <div class="form-group">
                <label>Konfirmasi Password</label>
                <input type="password" name="konfirmasi" required minlength="5" placeholder="Ulangi password">
            </div>

            <div class="form-actions">
                <button type="submit" class="save-btn">Simpan Password</button>
                <a href="profile.php" class="cancel-btn">Kembali</a>
            </div>
        </form>
    </div>
</div>

</body>
</html>