<?php
session_start();
if ($_SESSION['login'] != 'admin') {
    header("Location: ../login.php");
    exit;
}
include '../inc/db.php';

// Tambah pupuk
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama_pupuk'];
    $stok = $_POST['stok'];
    $koneksi->query("INSERT INTO pupuk (nama_pupuk, stok) VALUES ('$nama', '$stok')");
    header("Location: data-pupuk.php");
}

// Hapus pupuk
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];

    // Cek apakah pupuk masih digunakan di tabel alokasi
    $cek = $koneksi->query("SELECT COUNT(*) as total FROM alokasi WHERE id_pupuk=$id");
    $data = $cek->fetch_assoc();

    if ($data['total'] > 0) {
        echo "<script>alert('Pupuk tidak dapat dihapus karena masih digunakan di alokasi!'); window.location='data-pupuk.php';</script>";
    } else {
        $koneksi->query("DELETE FROM pupuk WHERE id_pupuk=$id");
        header("Location: data-pupuk.php");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="../inc/style.css">
    <title>Data Pupuk</title>
</head>
<body>
<?php include 'sidebar.php'; ?>
<h2>Data Pupuk</h2>

<form method="post">
    <label>Nama Pupuk:</label><br>
    <select name="nama_pupuk" required>
        <option value="Urea">Urea</option>
        <option value="NPK">NPK</option>
        <option value="ZA">ZA</option>
        <option value="Organik">Organik</option>
        <option value="Ponska">Ponska</option>
    </select><br><br>

    <label>Stok:</label><br>
    <input type="number" name="stok" required><br><br>

    <button type="submit" name="tambah">Tambah</button>
</form>

<hr>

<table border="1" cellpadding="8">
    <tr>
        <th>No</th>
        <th>Nama Pupuk</th>
        <th>Stok</th>
        <th>Aksi</th>
    </tr>
    <?php
    $no = 1;
    $data = $koneksi->query("SELECT * FROM pupuk");
    while ($row = $data->fetch_assoc()) {
        echo "<tr>
            <td>$no</td>
            <td>{$row['nama_pupuk']}</td>
            <td>{$row['stok']}</td>
            <td>
                <a href='?hapus={$row['id_pupuk']}' onclick='return confirm(\"Yakin ingin menghapus data ini?\")'>Hapus</a>
            </td>
        </tr>";
        $no++;
    }
    ?>
</table>

</body>
</html>
