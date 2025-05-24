<?php
session_start();
if ($_SESSION['login'] != 'admin') {
    header("Location: ../login.php");
    exit;
}
include '../inc/db.php';

// Tambah data
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $nik = $_POST['nik'];
    $luas = $_POST['luas_lahan'];
    $alamat = $_POST['alamat'];

    $koneksi->query("INSERT INTO kelompok_tani (nama, nik, luas_lahan, alamat) 
                     VALUES ('$nama', '$nik', '$luas', '$alamat')");
    header("Location: data-user.php");
}

// Hapus data
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    // Cek apakah kelompok masih digunakan di tabel alokasi
    $cek = $koneksi->query("SELECT COUNT(*) as total FROM alokasi WHERE id_kelompok = $id");
    $data = $cek->fetch_assoc();

    if ($data['total'] > 0) {
        echo "<script>alert('Data tidak dapat dihapus karena masih digunakan di alokasi!'); window.location='data-user.php';</script>";
    } else {
        $koneksi->query("DELETE FROM kelompok_tani WHERE id_kelompok = $id");
        header("Location: data-user.php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../inc/style.css">
    <title>Data Kelompok Tani</title>
</head>
<body>
<?php include 'sidebar.php'; ?>
<h2>Data Kelompok Tani</h2>

<form method="post">
    <label>Nama:</label><br>
    <input type="text" name="nama" required><br><br>

    <label>NIK:</label><br>
    <input type="text" name="nik" required><br><br>

    <label>Luas Lahan (ha):</label><br>
    <input type="number" step="0.01" name="luas_lahan" required><br><br>

    <label>Alamat:</label><br>
    <textarea name="alamat" required></textarea><br><br>

    <button type="submit" name="tambah">Tambah</button>
</form>

<hr>

<table border="1" cellpadding="8">
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>NIK</th>
        <th>Luas Lahan (ha)</th>
        <th>Alamat</th>
        <th>Aksi</th>
    </tr>
    <?php
    $no = 1;
    $query = $koneksi->query("SELECT * FROM kelompok_tani");
    while ($row = $query->fetch_assoc()) {
        echo "<tr>
                <td>$no</td>
                <td>{$row['nama']}</td>
                <td>{$row['nik']}</td>
                <td>{$row['luas_lahan']}</td>
                <td>{$row['alamat']}</td>
                <td>
                    <a href='?hapus={$row['id_kelompok']}' onclick='return confirm(\"Yakin ingin menghapus?\")'>Hapus</a>
                </td>
              </tr>";
        $no++;
    }
    ?>
</table>

</body>
</html>
