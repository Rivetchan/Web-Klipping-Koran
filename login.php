<?php 
session_start();
include_once("config/koneksi.php");

if ($kon->connect_error) {
    die("Connection failed: " . $kon->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Username = $_POST['Username'];
    $Password = $_POST['Password'];

    $sql = "SELECT UserID, Username, Password, level FROM user WHERE Username = ?";
    $stmt = $kon->prepare($sql);
    $stmt->bind_param("s", $Username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if (password_verify($Password, $row['Password'])) {
            $_SESSION['UserID'] = $row['UserID'];
            $_SESSION['Username'] = $row['Username'];
            $_SESSION['level'] = $row['level']; 

            // Arahkan ke index.php
            header("Location: index.php");
            exit();
        } else {
            $error_message = "Password salah.";
        }
    } else {
        $error_message = "Username tidak ditemukan.";
    }

    $stmt->close();
}

$kon->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Humas Kominfo</title>
    <link rel="stylesheet" href="style/css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <form method="post" class="login-form">
            <div class="logo-wrapper">
                <img src="style/images/logo.png" alt="Logo Kominfo">
            </div>

            <h1>Login Web Book</h1>

            <div class="input-group">
                <label for="Username">Username</label>
                <input type="text" name="Username" id="Username" required>
            </div>

            <div class="input-group">
                <label for="Password">Password</label>
                <div class="password-wrapper">
                    <input type="password" name="Password" id="Password" required>
                    <img src="style/images/view.png" alt="Show/Hide" id="eyeIcon" onclick="togglePassword()">
                </div>
            </div>

            <?php if (isset($error_message)) { ?>
                <p class="error"><?php echo $error_message; ?></p>
            <?php } ?>

            <button type="submit">Masuk</button>

            <p class="register-link">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
        </form>
    </div>

    <script>
        function togglePassword() {
            const password = document.getElementById("Password");
            const eyeIcon = document.getElementById("eyeIcon");
            if (password.type === "password") {
                password.type = "text";
                eyeIcon.src = "style/images/close.png";
            } else {
                password.type = "password";
                eyeIcon.src = "style/images/view.png";
            }
        }
    </script>
</body>
</html>
