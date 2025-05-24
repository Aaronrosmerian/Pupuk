<?php
session_start();
if ($_SESSION['login'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

include '../inc/db.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $hapus = $koneksi->query("DELETE FROM hasil_klasifikasi WHERE id_klasifikasi = $id");

    if ($hapus) {
        echo "<script>alert('Laporan berhasil dihapus'); window.location='../admin/laporan.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus laporan'); window.location='../admin/laporan.php';</script>";
    }
} else {
    header("Location: laporan.php");
    exit;
}
