<?php
session_start();
if (!isset($_SESSION['Username']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../../login.php");
    exit();
}

include '../../../config/koneksi.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: ../member.php");
    exit();
}

$query = mysqli_query($kon, "SELECT * FROM user WHERE UserID = '$id' AND level = 'member'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    header("Location: ../member.php");
    exit();
}

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $nama = htmlspecialchars($_POST['nama']);
    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];

    $sql = "UPDATE user SET Username='$username', NamaLengkap='$nama', Email='$email'";

    if (!empty($password)) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql .= ", Password='$hashed'";
    }

    $sql .= " WHERE UserID='$id'";

    if (mysqli_query($kon, $sql)) {
        $success = "Data berhasil diperbarui.";
    } else {
        $error = "Gagal memperbarui data.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Member</title>
    <link rel="stylesheet" href="edit.css">
</head>
<body>
    <div class="form-container">
        <h2>Edit Data Member</h2>

        <?php if ($success): ?>
            <div class="alert success"><?php echo $success; ?></div>
        <?php elseif ($error): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($data['Username']); ?>" required>

            <label for="nama">Nama Lengkap:</label>
            <input type="text" name="nama" value="<?php echo htmlspecialchars($data['NamaLengkap']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($data['Email']); ?>" required>

            <label for="password">Password (kosongkan jika tidak ingin diganti):</label>
            <input type="password" name="password">

            <button type="submit" class="submit-btn">Simpan</button>
            <a href="../member.php" class="cancel-btn">Kembali</a>
        </form>
    </div>
</body>
</html>
