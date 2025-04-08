<?php
session_start();
if (!isset($_SESSION['Username']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../../login.php");
    exit();
}

include '../../../config/koneksi.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $id = mysqli_real_escape_string($kon, $id);

    // Cek apakah user dengan ID tersebut adalah member
    $check = mysqli_query($kon, "SELECT * FROM user WHERE UserID = '$id' AND level = 'member'");

    if (mysqli_num_rows($check) > 0) {
        // Hapus user member
        $delete = mysqli_query($kon, "DELETE FROM user WHERE UserID = '$id' AND level = 'member'");

        // Reset auto increment dengan cara:
        // 1. Buat urutan ulang ID
        // 2. Reset auto increment ke angka selanjutnya

        // Urutkan ulang ID secara berurutan
        mysqli_query($kon, "
            SET @num := 0;
        ");
        mysqli_query($kon, "
            UPDATE user 
            SET UserID = (@num := @num + 1)
            WHERE level = 'member'
            ORDER BY UserID;
        ");

        // Reset Auto Increment ke nilai berikutnya
        $result = mysqli_query($kon, "SELECT MAX(UserID) AS max_id FROM user");
        $row = mysqli_fetch_assoc($result);
        $nextId = $row['max_id'] + 1;

        mysqli_query($kon, "ALTER TABLE user AUTO_INCREMENT = $nextId");
    }
}

header("Location: ../member.php");
exit();
?>
