<?php
include 'DB/koneksi.php';
session_start();

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $query = mysqli_query($koneksi, "SELECT * FROM tb_pegawai WHERE username = '$username'");
    
    if (mysqli_num_rows($query) > 0) {
        $_SESSION['reset_username'] = $username;
        header("Location: reset_password.php");
        exit();
    } else {
        echo "<script>alert('Username tidak ditemukan!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Lupa Password</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

<main>
    <div class="container">
        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                        <div class="d-flex justify-content-center py-4">
                            <a href="index.html" class="logo d-flex align-items-center w-auto">
                                <img src="assets/img/logo.png" alt="">
                                <span class="d-none d-lg-block">Aplikasi Pengarsipan</span>
                            </a>
                        </div>

                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="pt-4 pb-2">
                                    <h5 class="card-title text-center pb-0 fs-4">Lupa Password</h5>
                                    <p class="text-center small">Masukkan Username Anda</p>
                                </div>

                                <form class="row g-3 needs-validation" novalidate method="post">
                                    <div class="col-12">
                                        <label for="username" class="form-label">Username</label>
                                        <div class="input-group has-validation">
                                            <span class="input-group-text">@</span>
                                            <input type="text" name="username" class="form-control" id="username" required>
                                            <div class="invalid-feedback">Masukkan username Anda</div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-primary w-100" type="submit" name="submit">Lanjut</button>
                                    </div>
                                    <div class="text-center mt-3">
                                        <a href="login.php">Kembali ke Login</a>
                                    </div>
                                </form>

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
    </div>
</main>

<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>

</body>
</html>