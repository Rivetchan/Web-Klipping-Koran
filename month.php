<?php
session_start();

if (!isset($_SESSION['Username']) || !isset($_SESSION['level'])) {
    header("Location: login.php");
    exit();
}

require_once 'config/koneksi.php';

$username = $_SESSION['Username'];
$level = $_SESSION['level'];

if (!isset($_GET['TahunID'])) {
    header("Location: index.php");
    exit();
}

$tahunID = intval($_GET['TahunID']);

// Ambil informasi tahun
$queryTahun = "SELECT Tahun FROM tahun WHERE TahunID = $tahunID";
$resultTahun = mysqli_query($kon, $queryTahun);
$tahunData = mysqli_fetch_assoc($resultTahun);
$namaTahun = $tahunData ? $tahunData['Tahun'] : 'Tidak Diketahui';

// Ambil daftar Bulan yang memiliki klipping berdasarkan TahunID
$bulanTersedia = [];
$query = "SELECT b.BulanID, b.NamaBulan, b.Image
          FROM klipping k
          JOIN bulan b ON k.BulanID = b.BulanID
          WHERE k.TahunID = $tahunID
          GROUP BY b.BulanID, b.NamaBulan, b.Image
          ORDER BY b.BulanID ASC";
$result = mysqli_query($kon, $query);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $bulanTersedia[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bulan - Tahun <?= htmlspecialchars($namaTahun); ?></title>
    <link rel="stylesheet" href="style/css/month.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <header class="navbar">
        <div class="navbar-left">
            <a href="index.php">
                <img src="style/images/logo.png" alt="Kominfo" class="logo">
            </a>
        </div>
        <div class="navbar-right">
            <a href="profile.php" class="btn btn-profile">Profil</a>
            <a href="index.php" class="btn btn-danger">Kembali</a> 
        </div>
    </header>

    <main class="main-content">
        <h1>Bulan Tahun <?= htmlspecialchars($namaTahun); ?></h1>
        <p class="description">Pilih bulan untuk melihat data mingguan.</p>

        <?php if (!empty($bulanTersedia)) : ?>
            <section class="available-months">
                <div class="bulan-grid">
                    <?php foreach ($bulanTersedia as $bulan): ?>
                        <a href="week.php?BulanID=<?= $bulan['BulanID']; ?>" class="bulan-card">
                            <img src="public/bulan/aset/<?= htmlspecialchars($bulan['Image']); ?>" alt="<?= htmlspecialchars($bulan['NamaBulan']); ?>">
                            <div class="caption"><?= htmlspecialchars($bulan['NamaBulan']); ?></div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </section>
        <?php else: ?>
            <p>Tidak ada data bulan untuk tahun ini.</p>
        <?php endif; ?>
    </main>

    <footer class="footer">
        © <?= date("Y"); ?> Dinas Kominfo - Web Klipping
    </footer>
</body>
</html>
