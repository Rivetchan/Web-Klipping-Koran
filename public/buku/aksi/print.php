<?php
session_start();
require_once "../../../config/koneksi.php";

if (!isset($_SESSION['Username']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../../login.php");
    exit();
}

$query = mysqli_query($kon, "
    SELECT k.*, 
           m.NamaMinggu, 
           b.NamaBulan, 
           t.Tahun, 
           u.Username
    FROM klipping k
    LEFT JOIN minggu m ON k.MingguID = m.MingguID
    LEFT JOIN bulan b ON k.BulanID = b.BulanID
    LEFT JOIN tahun t ON k.TahunID = t.TahunID
    LEFT JOIN user u ON k.UserID = u.UserID
    ORDER BY k.TanggalDibuat DESC
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Data Klipping</title>
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

    <h2 class="title">LAPORAN DATA KLIPPING</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Topik</th>
                <th>Minggu</th>
                <th>Bulan</th>
                <th>Tahun</th>
                <th>User</th>
                <th>Tanggal Dibuat</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['Topik']); ?></td>
                    <td><?= htmlspecialchars($row['NamaMinggu']); ?></td>
                    <td><?= htmlspecialchars($row['NamaBulan']); ?></td>
                    <td><?= htmlspecialchars($row['Tahun']); ?></td>
                    <td><?= htmlspecialchars($row['Username']); ?></td>
                    <td><?= htmlspecialchars($row['TanggalDibuat']); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <script>
        window.onload = function () {
            window.print();
        };
    </script>
</body>
</html>