<?php
session_start();
require_once '../../../config/koneksi.php';

if (!isset($_SESSION['Username']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

$query = "SELECT * FROM minggu ORDER BY TanggalDibuat DESC";
$result = mysqli_query($kon, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Data Minggu</title>
    <link rel="stylesheet" href="print.css">
</head>
<body>

<div class="kop-surat">
    <div class="kop-kiri">
        <img src="../../../style/images/logo.png" alt="Logo Kominfo" class="logo-kop">
    </div>
    <div class="kop-kanan">
        <h1>PEMERINTAH KOTA BATAM</h1>
        <h2>DINAS KOMUNIKASI DAN INFORMATIKA</h2>
        <p>Jl. Raja H. Fisabilillah No.1, Tlk. Tering, Kec. Batam Kota, Kota Batam, Kepulauan Riau</p>
        <p>Telp: +62 778 8073194 | Email: kominfo@batam.go.id</p>
    </div>
</div>

<div class="actions no-print">
    <a href="../minggu.php" class="btn-kembali">← Kembali</a>
    <button onclick="window.print()" class="btn-print">🖨️ Print Halaman Ini</button>
</div>

<h2 class="judul-print">Data Minggu - Dinas Kominfo</h2>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Minggu</th>
            <th>Tanggal Dibuat</th>
        </tr>
    </thead>
    <tbody>
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php $no = 1; ?>
            <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['NamaMinggu']); ?></td>
                    <td><?= htmlspecialchars($row['TanggalDibuat']); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="3">Tidak ada data minggu.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<div class="footer-print">
    Dicetak pada: <?= date("d-m-Y H:i"); ?> | &copy; <?= date("Y"); ?> Dinas Kominfo - Web Klipping
</div>

</body>
</html>