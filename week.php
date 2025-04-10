<?php
session_start();

if (!isset($_SESSION['Username']) || !isset($_SESSION['level'])) {
    header("Location: login.php");
    exit();
}

require_once 'config/koneksi.php';

$username = $_SESSION['Username'];
$level = $_SESSION['level'];

if (!isset($_GET['BulanID']) || !isset($_GET['TahunID'])) {
    header("Location: index.php");
    exit();
}

$bulanID = intval($_GET['BulanID']);
$tahunID = intval($_GET['TahunID']);

// Ambil informasi bulan dan tahun
$queryInfo = "SELECT b.NamaBulan, t.Tahun 
              FROM bulan b 
              JOIN tahun t ON 1=1
              WHERE b.BulanID = $bulanID AND t.TahunID = $tahunID";
$resultInfo = mysqli_query($kon, $queryInfo);
$info = mysqli_fetch_assoc($resultInfo);
$namaBulan = $info['NamaBulan'] ?? 'Bulan Tidak Diketahui';
$namaTahun = $info['Tahun'] ?? 'Tahun Tidak Diketahui';

// Ambil data minggu dan klipping berdasarkan BukuID
$query = "SELECT k.BukuID, m.MingguID, m.NamaMinggu, k.Topik, k.PDF
          FROM klipping k
          JOIN minggu m ON k.MingguID = m.MingguID
          WHERE k.BulanID = $bulanID AND k.TahunID = $tahunID
          ORDER BY m.MingguID ASC";
$result = mysqli_query($kon, $query);

$mingguData = [];
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $mingguData[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Minggu - <?= htmlspecialchars($namaBulan . ' ' . $namaTahun); ?></title>
    <link rel="stylesheet" href="style/css/week.css">
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
            <a href="javascript:history.back()" class="btn btn-danger">Kembali</a>
        </div>
    </header>

    <main class="main-content">
        <h1><?= htmlspecialchars($namaBulan . ' ' . $namaTahun); ?></h1>
        <p class="description">Klik tombol "Here" untuk melihat file PDF mingguan.</p>

        <?php if (!empty($mingguData)) : ?>
            <div class="minggu-list">
                <?php foreach ($mingguData as $minggu): ?>
                    <div class="minggu-card">
                        <h3><?= htmlspecialchars($minggu['NamaMinggu']); ?></h3>
                        <p class="topik"><?= htmlspecialchars($minggu['Topik']); ?></p>
                        <button class="btn-view" onclick="openPDF('public/buku/aset/pdf/<?= htmlspecialchars($minggu['PDF']); ?>')">Here</button>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Tidak ada data klipping untuk bulan dan tahun ini.</p>
        <?php endif; ?>
    </main>

    <footer class="footer">
        © <?= date("Y"); ?> Dinas Kominfo - Web Klipping
    </footer>

    <!-- Popup PDF Viewer -->
    <div id="pdfPopup" class="popup">
        <div class="popup-content">
            <span class="close" onclick="closePDF()">&times;</span>
            <iframe id="pdfFrame" src="" frameborder="0"></iframe>
        </div>
    </div>

    <script>
        function openPDF(url) {
            document.getElementById('pdfFrame').src = url + '#view=FitH';
            document.getElementById('pdfPopup').style.display = 'flex';
        }


        function closePDF() {
            document.getElementById('pdfPopup').style.display = 'none';
            document.getElementById('pdfFrame').src = '';
        }
    </script>
</body>
</html>