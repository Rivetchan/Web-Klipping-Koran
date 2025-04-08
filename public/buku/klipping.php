<?php
session_start();
require_once "../../config/koneksi.php";

if (!isset($_SESSION['Username']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

// Ambil data klipping lengkap dengan JOIN
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
    <title>Data Klipping</title>
    <link rel="stylesheet" href="klipping.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h1>Data Klipping</h1>

        <div class="action-buttons">
            <a href="../../index.php" class="btn-kembali">← Kembali</a>
            <div>
                <a href="tambah.php" class="btn-tambah">+ Tambah Klipping</a>
                <a href="aksi/print.php" target="_blank" class="btn-print">🖨️ Print Data</a>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Topik</th>
                    <th>Minggu</th>
                    <th>Bulan</th>
                    <th>Tahun</th>
                    <th>Gambar</th>
                    <th>PDF</th>
                    <th>User</th>
                    <th>Tanggal Dibuat</th>
                    <th>Aksi</th>
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
                        <td>
                            <?php if (!empty($row['Image'])) : ?>
                                <img src="aset/foto/<?= $row['Image']; ?>" alt="Gambar" width="80">
                            <?php else : ?>
                                Tidak ada
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (!empty($row['PDF'])) : ?>
                                <a href="aset/pdf/<?= $row['PDF']; ?>" target="_blank">Lihat PDF</a>
                            <?php else : ?>
                                Tidak ada
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($row['Username']); ?></td>
                        <td><?= htmlspecialchars($row['TanggalDibuat']); ?></td>
                        <td>
                            <a href="aksi/edit.php?id=<?= $row['BukuID']; ?>" class="btn-edit">Edit</a>
                            <a href="aksi/hapus.php?id=<?= $row['BukuID']; ?>" class="btn-hapus" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <footer class="footer">
        © <?= date("Y"); ?> Dinas Kominfo - Web Klipping
    </footer>
</body>
</html>