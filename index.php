<?php
session_start();

if (!isset($_SESSION['Username']) || !isset($_SESSION['level'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['Username'];
$level = $_SESSION['level'];

$showWelcome = false;
if (!isset($_SESSION['welcome_shown'])) {
    $_SESSION['welcome_shown'] = true;
    $showWelcome = true;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Utama | <?= ucfirst($level); ?></title>
    <link rel="stylesheet" href="style/css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <header class="navbar">
        <div class="navbar-left">
            <a href="index.php">
                <img src="style/images/logo.png" alt="Kominfo" class="logo">
            </a>
            <nav class="nav-links">
                <?php if ($level === 'admin') : ?>
                    <a href="public/buku/klipping.php">Data Klipping</a>
                    <a href="public/member/member.php">Data Member</a>
                    <a href="public/minggu/minggu.php">Minggu</a>
                    <a href="public/bulan/bulan.php">Bulan</a>
                    <a href="public/tahun/tahun.php">Tahun</a>
                <?php elseif ($level === 'member') : ?>
                    <a href="public/buku/data-klipping.php">Klipping Tersedia</a>
                <?php endif; ?>
            </nav>
        </div>
        <div class="navbar-right">
            <a href="profil.php" class="btn btn-profile">Profil</a>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
    </header>

    <main class="main-content">
        <?php if ($showWelcome): ?>
        <div class="alert success" id="welcomeAlert">
            👋 Selamat datang, <strong><?= htmlspecialchars($username); ?></strong>! Anda login sebagai <strong><?= ucfirst($level); ?></strong>.
        </div>
        <?php endif; ?>

        <h1>Halaman Utama</h1>
        <p class="description">Selamat datang di Sistem Web Klipping Dinas Kominfo. Silakan gunakan navigasi di atas untuk mengelola data.</p>
    </main>

    <footer class="footer">
        © <?= date("Y"); ?> Dinas Kominfo - Web Klipping
    </footer>

    <?php if ($showWelcome): ?>
    <script>
        setTimeout(() => {
            const alertBox = document.getElementById("welcomeAlert");
            if (alertBox) {
                alertBox.style.opacity = "0";
                setTimeout(() => alertBox.remove(), 500);
            }
        }, 3000);
    </script>
    <?php endif; ?>
</body>
</html>
