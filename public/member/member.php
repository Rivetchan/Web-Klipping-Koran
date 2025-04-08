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
    <title>Manajemen Member</title>
    <link rel="stylesheet" href="member.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        @media print {
            .navbar, .footer, .aksi-col {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="navbar">
            <h2>Manajemen Member</h2>
            <div class="navbar-actions">
                <a href="../../index.php" class="btn-back">Kembali</a>
                <button onclick="window.print()" class="btn-print">🖨️ Cetak</button>
            </div>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th>Email</th>
                        <th class="aksi-col">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $query = mysqli_query($kon, "SELECT * FROM user WHERE level = 'member'");
                    if (mysqli_num_rows($query) > 0) {
                        while ($row = mysqli_fetch_assoc($query)) {
                            echo "<tr>";
                            echo "<td>{$no}</td>";
                            echo "<td>" . htmlspecialchars($row['Username']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['NamaLengkap']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['Email']) . "</td>";
                            echo "<td class='aksi-col'>
                                    <a href='aksi/edit.php?id={$row['UserID']}' class='btn-edit'>Edit Profile</a>
                                    <a href='aksi/hapus.php?id={$row['UserID']}' class='btn-hapus' onclick=\"return confirm('Yakin ingin menghapus member ini?')\">Hapus</a>
                                  </td>";
                            echo "</tr>";
                            $no++;
                        }
                    } else {
                        echo "<tr><td colspan='5'>Tidak ada data member.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <footer class="footer">
            © <?php echo date("Y"); ?> Dinas Kominfo - Web Klipping
        </footer>
    </div>
</body>
</html>