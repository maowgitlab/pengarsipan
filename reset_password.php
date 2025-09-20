<?php
include 'DB/koneksi.php';
session_start();

if (!isset($_SESSION['reset_username'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['reset'])) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $username = $_SESSION['reset_username'];

    if ($password == $confirm_password) {
        $hashed_password = md5($password);
        mysqli_query($koneksi, "UPDATE tb_pegawai SET password = '$hashed_password' WHERE username = '$username'");
        
        unset($_SESSION['reset_username']);
        echo "<script>
                alert('Password berhasil diperbarui! Silakan login.');
                window.location = 'login.php';
              </script>";
    } else {
        echo "<script>alert('Password tidak cocok!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Reset Password</title>
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
                                    <h5 class="card-title text-center pb-0 fs-4">Reset Password</h5>
                                    <p class="text-center small">Masukkan password baru Anda</p>
                                </div>

                                <form class="row g-3 needs-validation" novalidate method="post">
                                    <div class="col-12">
                                        <label for="password" class="form-label">Password Baru</label>
                                        <input type="password" name="password" class="form-control" id="password" required>
                                        <div class="invalid-feedback">Masukkan password baru</div>
                                    </div>
                                    <div class="col-12">
                                        <label for="confirm_password" class="form-label">Ulangi Password Baru</label>
                                        <input type="password" name="confirm_password" class="form-control" id="confirm_password" required>
                                        <div class="invalid-feedback">Ulangi password baru</div>
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-success w-100" type="submit" name="reset">Reset Password</button>
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