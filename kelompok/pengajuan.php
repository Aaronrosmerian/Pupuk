<?php
session_start();
if ($_SESSION['login'] != 'kelompok') {
    header("Location: ../login.php");
    exit;
}
include '../inc/db.php';

$id_kelompok = $_SESSION['id'];

// Tambah pengajuan
if (isset($_POST['ajukan'])) {
    $id_pupuk = $_POST['id_pupuk'];
    $jumlah = $_POST['jumlah_diajukan'];

    // Simpan ke alokasi
    $simpan = $koneksi->query("INSERT INTO alokasi (id_kelompok, id_pupuk, jumlah_diajukan, status_pengajuan)
                               VALUES ('$id_kelompok', '$id_pupuk', '$jumlah', 'pending')");

    if ($simpan) {
        $id_alokasi = $koneksi->insert_id;

        // Ambil nama pupuk
        $get = $koneksi->query("SELECT nama_pupuk FROM pupuk WHERE id_pupuk = '$id_pupuk'");
        $pupuk = $get->fetch_assoc()['nama_pupuk'];

        // Ambil luas lahan user
        $get2 = $koneksi->query("SELECT luas_lahan FROM kelompok_tani WHERE id_kelompok = '$id_kelompok'");
        $luas = $get2->fetch_assoc()['luas_lahan'];

        // Klasifikasi
        include '../mode/naive-bayes.php';
        $hasil = naiveBayes($luas, $pupuk);

        // Simpan hasil klasifikasi
        $kelas = $hasil['kelas'];
        $nilai = $hasil['nilai'];
        $koneksi->query("INSERT INTO hasil_klasifikasi (id_alokasi, hasil, probabilitas) 
                         VALUES ('$id_alokasi', '$kelas', '$nilai')");

        header("Location: pengajuan.php");
        exit;
    } else {
        echo "<p style='color:red;'>Gagal menyimpan pengajuan: " . $koneksi->error . "</p>";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../inc/style.css">
    <title>Pengajuan Pupuk</title>
</head>
<body>
<h2>Pengajuan Pupuk</h2>
<?php include 'sidebar.php'; ?>

<form method="post">
    <label>Pilih Jenis Pupuk:</label><br>
    <select name="id_pupuk" required>
        <option value="">-- Pilih --</option>
        <?php
        $data = $koneksi->query("SELECT * FROM pupuk");
        while ($p = $data->fetch_assoc()) {
            echo "<option value='{$p['id_pupuk']}'>{$p['nama_pupuk']} (Stok: {$p['stok']})</option>";
        }
        ?>
    </select><br><br>

    <label>Jumlah Diajukan (kg):</label><br>
    <input type="number" name="jumlah_diajukan" required><br><br>

    <button type="submit" name="ajukan">Ajukan</button>
</form>

<hr>

<h3>Riwayat Pengajuan</h3>
<table border="1" cellpadding="8">
    <tr>
        <th>No</th>
        <th>Jenis Pupuk</th>
        <th>Jumlah Diajukan</th>
        <th>Status</th>
    </tr>
    <?php
    $no = 1;
    $pengajuan = $koneksi->query("
        SELECT p.nama_pupuk, a.jumlah_diajukan, a.status_pengajuan
        FROM alokasi a
        JOIN pupuk p ON a.id_pupuk = p.id_pupuk
        WHERE a.id_kelompok = '$id_kelompok'
        ORDER BY a.id_alokasi DESC
    ");
    while ($row = $pengajuan->fetch_assoc()) {
        echo "<tr>
            <td>$no</td>
            <td>{$row['nama_pupuk']}</td>
            <td>{$row['jumlah_diajukan']}</td>
            <td>{$row['status_pengajuan']}</td>
        </tr>";
        $no++;
    }
    ?>
</table>
</body>
</html>
