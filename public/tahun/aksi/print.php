<?php
session_start();
if (!isset($_SESSION['Username']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../../login.php");
    exit();
}

include '../../../config/koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Data Tahun</title>
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

    <h2>Data Tahun</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tahun</th>
                <th>Tanggal Dibuat</th>
                <th>Gambar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $query = mysqli_query($kon, "SELECT * FROM tahun ORDER BY Tahun DESC");
            while ($row = mysqli_fetch_assoc($query)) {
                $imagePath = '../aset/' . htmlspecialchars($row['Image']);
                echo "<tr>";
                echo "<td>{$no}</td>";
                echo "<td>" . htmlspecialchars($row['Tahun']) . "</td>";
                echo "<td>" . date('d M Y', strtotime($row['TanggalDibuat'])) . "</td>";
                echo "<td><img src='{$imagePath}' alt='Gambar Tahun'></td>";
                echo "</tr>";
                $no++;
            }
            ?>
        </tbody>
    </table>

    <div class="actions">
        <a href="../tahun.php" class="btn-back">Kembali</a>
        <button onclick="window.print()">Cetak</button>
    </div>
</body>
</html>