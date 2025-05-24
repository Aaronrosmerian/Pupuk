<?php
session_start();
if ($_SESSION['login'] != 'kelompok') {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../inc/style.css">
    <title>Dashboard Kelompok Tani</title>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <h2>Selamat Datang, <?= $_SESSION['nama']; ?>!</h2>
</body>
</html>
