<?php
session_start();
if ($_SESSION['login'] != 'admin') {
    header("Location: ../login.php");
    exit;
}
include '../inc/db.php';

// Hapus satu laporan
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $koneksi->query("DELETE FROM hasil_klasifikasi WHERE id_klasifikasi = $id");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Hapus semua laporan
if (isset($_GET['hapus_semua'])) {
    $koneksi->query("DELETE FROM hasil_klasifikasi");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="../inc/style.css">
    <title>Laporan Hasil Klasifikasi</title>
</head>
<body>
<?php include 'sidebar.php'; ?>
<h2>Laporan Hasil Klasifikasi Pupuk</h2>

<!-- Tombol Hapus Semua -->
<form method="get" onsubmit="return confirm('Yakin ingin menghapus SEMUA laporan klasifikasi?');">
    <button type="submit" name="hapus_semua">Hapus Semua Laporan</button>
</form>
<br>

<table border="1" cellpadding="8">
    <tr>
        <th>No</th>
        <th>Nama Kelompok</th>
        <th>Jenis Pupuk</th>
        <th>Jumlah Diajukan</th>
        <th>Status Pengajuan</th>
        <th>Hasil Klasifikasi</th>
        <th>Probabilitas</th>
        <th>Aksi</th>
    </tr>
    <?php
    $no = 1;
    $query = $koneksi->query("
        SELECT h.id_klasifikasi, k.nama, p.nama_pupuk, a.jumlah_diajukan, a.status_pengajuan,
               h.hasil, h.probabilitas
        FROM hasil_klasifikasi h
        JOIN alokasi a ON h.id_alokasi = a.id_alokasi
        JOIN kelompok_tani k ON a.id_kelompok = k.id_kelompok
        JOIN pupuk p ON a.id_pupuk = p.id_pupuk
        ORDER BY h.id_klasifikasi DESC
    ");
    while ($row = $query->fetch_assoc()) {
        echo "<tr>
            <td>$no</td>
            <td>{$row['nama']}</td>
            <td>{$row['nama_pupuk']}</td>
            <td>{$row['jumlah_diajukan']}</td>
            <td>{$row['status_pengajuan']}</td>
            <td>{$row['hasil']}</td>
            <td>{$row['probabilitas']}</td>
            <td>
                <a href='?hapus={$row['id_klasifikasi']}'
                   onclick=\"return confirm('Yakin ingin menghapus laporan ini?');\">
                   Hapus
                </a>
            </td>
        </tr>";
        $no++;
    }
    ?>
</table>
</body>
</html>
