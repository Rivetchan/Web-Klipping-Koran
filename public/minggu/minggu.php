<?php
session_start();
require_once '../../config/koneksi.php';

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
    <title>Data Minggu</title>
    <link rel="stylesheet" href="minggu.css">
</head>
<body>

<div class="navbar">
    <div class="navbar-left">
        <img src="../../style/images/logo.png" alt="Logo Kominfo" class="logo-kominfo">
        <div class="navbar-title">Data Minggu</div>
    </div>
    <div class="navbar-right">
        <a href="../../index.php" class="nav-btn">Home</a>
        <a href="aksi/print.php" class="nav-btn print-btn">Print</a>
        <a href="tambah.php" class="nav-btn">Tambah</a>
    </div>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Minggu</th>
                <th>Tanggal Dibuat</th>
                <th>Aksi</th>
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
                        <td>
                            <a href="aksi/edit.php?id=<?= $row['MingguID']; ?>" class="btn-edit">Edit</a>
                            <a href="aksi/hapus.php?id=<?= $row['MingguID']; ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus data ini?');">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Tidak ada data minggu.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<footer class="footer">
    © <?= date("Y"); ?> Dinas Kominfo - Web Klipping
</footer>

</body>
</html>
