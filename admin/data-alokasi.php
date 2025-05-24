<?php
session_start();
if ($_SESSION['login'] != 'admin') {
    header("Location: ../login.php");
    exit;
}
include '../inc/db.php';

// Update status pengajuan
if (isset($_GET['status']) && isset($_GET['id'])) {
    $status = $_GET['status']; // diterima / ditolak
    $id = $_GET['id'];
    $koneksi->query("UPDATE alokasi SET status_pengajuan='$status' WHERE id_alokasi='$id'");
    header("Location: data-alokasi.php");
}

// Hapus satu data alokasi
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $koneksi->query("DELETE FROM alokasi WHERE id_alokasi='$id'");
    header("Location: data-alokasi.php");
}

// Hapus semua data alokasi
if (isset($_GET['hapus_semua'])) {
    $koneksi->query("DELETE FROM alokasi");
    header("Location: data-alokasi.php");
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../inc/style.css">
    <title>Data Pengajuan Pupuk</title>
</head>
<body>
<?php include 'sidebar.php'; ?>
<h2>Data Pengajuan Pupuk</h2>

<!-- Tombol Hapus Semua -->
<form method="get" onsubmit="return confirm('Yakin ingin menghapus seluruh data alokasi?');">
    <button type="submit" name="hapus_semua">Hapus Semua Alokasi</button>
</form>
<br>

<table border="1" cellpadding="8">
    <tr>
        <th>No</th>
        <th>Nama Kelompok Tani</th>
        <th>Jenis Pupuk</th>
        <th>Jumlah Diajukan</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>
    <?php
    $no = 1;
    $query = $koneksi->query("
        SELECT a.id_alokasi, k.nama, p.nama_pupuk, a.jumlah_diajukan, a.status_pengajuan 
        FROM alokasi a
        JOIN kelompok_tani k ON a.id_kelompok = k.id_kelompok
        JOIN pupuk p ON a.id_pupuk = p.id_pupuk
        ORDER BY a.id_alokasi DESC
    ");
    while ($row = $query->fetch_assoc()) {
        echo "<tr>
            <td>{$no}</td>
            <td>{$row['nama']}</td>
            <td>{$row['nama_pupuk']}</td>
            <td>{$row['jumlah_diajukan']}</td>
            <td>{$row['status_pengajuan']}</td>
            <td>";

        if ($row['status_pengajuan'] == 'pending') {
            echo "
                <a href='?id={$row['id_alokasi']}&status=diterima'>Setujui</a> |
                <a href='?id={$row['id_alokasi']}&status=ditolak' onclick='return confirm(\"Tolak pengajuan ini?\")'>Tolak</a> |
            ";
        }

        echo "<a href='?hapus={$row['id_alokasi']}' onclick='return confirm(\"Hapus data alokasi ini?\")'>Hapus</a>";
        echo "</td></tr>";
        $no++;
    }
    ?>
</table>
</body>
</html>
