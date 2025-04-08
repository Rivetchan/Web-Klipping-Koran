<?php
session_start();
require_once '../../../config/koneksi.php';

if (!isset($_SESSION['Username']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

$query = "SELECT * FROM bulan ORDER BY TanggalDibuat DESC";
$result = mysqli_query($kon, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Print Data Bulan</title>
    <link rel="stylesheet" href="print.css">
</head>
<body>

    <div class="print-header no-print">
        <a href="../bulan.php" class="btn-back">← Kembali</a>
        <button onclick="window.print()" class="btn-print">Print Halaman Ini</button>
    </div>

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

    <hr class="garis-pemisah">

    <h2 class="judul-print">Data Bulan - Dinas Kominfo</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Bulan</th>
                <th>Tanggal Dibuat</th>
                <th>Gambar</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php $no = 1; ?>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['NamaBulan']); ?></td>
                        <td><?= htmlspecialchars($row['TanggalDibuat']); ?></td>
                        <td>
                            <?php if (!empty($row['Image'])) : ?>
                                <img src="../aset/<?= htmlspecialchars($row['Image']); ?>" alt="Gambar Bulan">
                            <?php else : ?>
                                <em>Tidak Ada Gambar</em>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Tidak ada data bulan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        Dicetak pada <?= date("d-m-Y H:i"); ?> | &copy; <?= date("Y"); ?> Dinas Kominfo - Web Klipping
    </div>

</body>
</html>