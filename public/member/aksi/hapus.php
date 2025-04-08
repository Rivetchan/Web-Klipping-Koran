<?php
session_start();
if (!isset($_SESSION['Username']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../../../login.php");
    exit();
}

include '../../../config/koneksi.php';

$id = $_GET['id'] ?? null;

if ($id) {
    // Hindari SQL injection
    $id = mysqli_real_escape_string($kon, $id);

    // Pastikan user dengan ID tersebut adalah member
    $check = mysqli_query($kon, "SELECT * FROM user WHERE UserID = '$id' AND level = 'member'");

    if (mysqli_num_rows($check) > 0) {
        // Hapus user
        $delete = mysqli_query($kon, "DELETE FROM user WHERE UserID = '$id' AND level = 'member'");
    }
}

header("Location: ../member.php");
exit();
?>
