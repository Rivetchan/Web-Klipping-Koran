<?php
session_start();

if (!isset($_SESSION['Username']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../login.php");
    exit();
}

include '../../config/koneksi.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Tahun</title>
    <link rel="stylesheet" href="tahun.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="navbar">
        <div class="navbar-left">
            <img src="../../style/images/logo.png" alt="Logo" class="logo-kominfo">
            <h1 class="navbar-title">Data Tahun</h1>
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
                    <th>Tahun</th>
                    <th>Tanggal Dibuat</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                $query = mysqli_query($kon, "SELECT * FROM tahun ORDER BY Tahun DESC");

                if (mysqli_num_rows($query) > 0) {
                    while ($row = mysqli_fetch_assoc($query)) {
                        $id = $row['TahunID'];
                        $imageName = htmlspecialchars($row['Image']);
                        $imagePath = 'aset/' . $imageName;
                        echo "<tr>";
                        echo "<td>{$no}</td>";
                        echo "<td>" . htmlspecialchars($row['Tahun']) . "</td>";
                        echo "<td>" . date('d M Y', strtotime($row['TanggalDibuat'])) . "</td>";
                        echo "<td><img src='{$imagePath}' class='year-image' alt='Gambar Tahun'></td>";
                        echo "<td>
                                <a href='aksi/edit.php?id={$id}' class='btn-edit'>Edit</a>
                                <a href='aksi/hapus.php?id={$id}' class='btn-hapus' onclick=\"return confirm('Yakin ingin menghapus data ini?')\">Hapus</a>
                              </td>";
                        echo "</tr>";
                        $no++;
                    }
                } else {
                    echo "<tr><td colspan='5'>Data tidak ditemukan.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <footer class="footer">
        © <?php echo date("Y"); ?> Dinas Kominfo - Web Klipping
    </footer>
</body>
</html>