<?php
session_start();

if (!isset($_SESSION['Username']) || !isset($_SESSION['level'])) {
    header("Location: login.php");
    exit();
}

require_once 'config/koneksi.php';

$username = $_SESSION['Username'];
$level = $_SESSION['level'];

$showWelcome = false;
if (!isset($_SESSION['welcome_shown'])) {
    $_SESSION['welcome_shown'] = true;
    $showWelcome = true;
}

// Ambil data Tahun Tersedia (yang punya klipping)
// Pastikan tiap TahunID hanya muncul satu kali dengan GROUP BY
$tahunTersedia = [];
$query = "SELECT t.TahunID, t.Tahun, t.Image
          FROM klipping k
          JOIN tahun t ON k.TahunID = t.TahunID
          GROUP BY t.TahunID, t.Tahun, t.Image
          ORDER BY t.Tahun DESC";
$result = mysqli_query($kon, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $tahunTersedia[] = $row;
    }
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
            <a href="profile.php" class="btn btn-profile">Profil</a>
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

        <?php if (!empty($tahunTersedia)) : ?>
            <section class="available-years">
                <h2>Tahun Tersedia</h2>
                <div class="tahun-grid">
                    <?php foreach ($tahunTersedia as $tahun): ?>
                        <a href="month.php?TahunID=<?= $tahun['TahunID']; ?>" class="tahun-card">
                            <img src="public/tahun/aset/<?= htmlspecialchars($tahun['Image']); ?>" alt="<?= htmlspecialchars($tahun['Tahun']); ?>">
                            <div class="caption"><?= htmlspecialchars($tahun['Tahun']); ?></div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php else: ?>
            <p>Belum ada data klipping yang tersedia.</p>
        <?php endif; ?>
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