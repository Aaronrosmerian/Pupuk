<?php
session_start();
include 'inc/db.php';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    // Cek ke tabel admin
    $admin = $koneksi->query("SELECT * FROM admin WHERE username='$username' AND password='$password'");
    if ($admin->num_rows > 0) {
        $a = $admin->fetch_assoc();
        $_SESSION['login'] = 'admin';
        $_SESSION['id'] = $a['id_admin'];
        $_SESSION['username'] = $a['username'];
        header("Location: admin/dashboard.php");
        exit;
    }

    // Cek ke tabel kelompok tani
    $kelompok = $koneksi->query("SELECT * FROM kelompok_tani WHERE nik='$username'");
    if ($kelompok->num_rows > 0) {
        $k = $kelompok->fetch_assoc();
        $_SESSION['login'] = 'kelompok';
        $_SESSION['id'] = $k['id_kelompok'];
        $_SESSION['nama'] = $k['nama'];
        header("Location: kelompok/dashboard.php");
        exit;
    }

    // Jika tidak ditemukan
    $error = "Username atau password salah!";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login | Sistem Pupuk</title>
</head>
<link rel="stylesheet" type="text/css" href="inc/style.css">
<body>
    <h2>Login Sistem Pupuk</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post">
        <label>Username :</label><br>
        <input type="text" name="username" required><br><br>

        <label>Password :</label><br>
        <input type="password" name="password"><br><br>

        <button type="submit" name="login">Login</button>
    </form>
</body>
</html>
