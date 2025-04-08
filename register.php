<?php 
    include_once("config/koneksi.php");

    $success_message = "";
    $error_message = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $Username = trim($_POST['Username']);
        $Email = trim($_POST['Email']);
        $Password = $_POST['Password'];
        $ConfirmPassword = $_POST['ConfirmPassword'];
        $Level = 'member'; // Otomatis jadi member saat daftar

        if ($Password !== $ConfirmPassword) {
            $error_message = "Password dan konfirmasi tidak cocok.";
        } else {
            // Cek username/email sudah dipakai atau belum
            $check = $kon->query("SELECT * FROM user WHERE Username = '$Username' OR Email = '$Email'");
            if ($check->num_rows > 0) {
                $error_message = "Username atau Email sudah digunakan.";
            } else {
                // Gunakan password_hash untuk keamanan
                $hashedPassword = password_hash($Password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO user (Username, Email, Password, level) VALUES ('$Username', '$Email', '$hashedPassword', '$Level')";
                if ($kon->query($sql) === TRUE) {
                    $success_message = "Registrasi berhasil. Silakan login.";
                } else {
                    $error_message = "Terjadi kesalahan saat menyimpan data.";
                }
            }
        }

        $kon->close();
    }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi</title>
    <link rel="stylesheet" href="style/css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <form method="post" class="login-form">
            <div class="logo-wrapper">
                <img src="style/images/logo.png" alt="Logo Kominfo">
            </div>

            <h1>Registrasi</h1>

            <div class="input-group">
                <label for="Username">Username</label>
                <input type="text" name="Username" id="Username" required>
            </div>

            <div class="input-group">
                <label for="Email">Email</label>
                <input type="email" name="Email" id="Email" required>
            </div>

            <div class="input-group">
                <label for="Password">Password</label>
                <input type="password" name="Password" id="Password" required>
            </div>

            <div class="input-group">
                <label for="ConfirmPassword">Konfirmasi Password</label>
                <input type="password" name="ConfirmPassword" id="ConfirmPassword" required>
            </div>

            <!-- Level tidak ditampilkan karena default otomatis 'member' -->

            <?php if (!empty($error_message)) { ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php } ?>
            <?php if (!empty($success_message)) { ?>
                <p style="color: green; text-align: center;"><?php echo $success_message; ?></p>
            <?php } ?>

            <button type="submit">Daftar</button>

            <p class="register-link">Sudah punya akun? <a href="login.php">Login</a></p>
        </form>
    </div>
</body>
</html>