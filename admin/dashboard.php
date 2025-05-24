<?php
session_start();
if ($_SESSION['login'] != 'admin') {
    header("Location: ../login.php");
    exit;
}
include '../inc/db.php';
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../inc/style.css">
    <title>Dashboard Admin</title>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <h2>Selamat Datang, <?= $_SESSION['username']; ?> (Admin)</h2>

    <p>Informasi Ringkas:</p>
    <ul>
        <li>Total Data Pupuk: 
            <?php
                $p = $koneksi->query("SELECT COUNT(*) as total FROM pupuk")->fetch_assoc();
                echo $p['total'];
            ?>
        </li>
        <li>Total Kelompok Tani:
            <?php
                $u = $koneksi->query("SELECT COUNT(*) as total FROM kelompok_tani")->fetch_assoc();
                echo $u['total'];
            ?>
        </li>
        <li>Total Pengajuan:
            <?php
                $a = $koneksi->query("SELECT COUNT(*) as total FROM alokasi")->fetch_assoc();
                echo $a['total'];
            ?>
        </li>
    </ul>
</body>
</html>
