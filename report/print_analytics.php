<script>
    window.print();
</script>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Analitik</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            float: left;
            width: 100px;
            height: 100px;
        }
        .header h1, .header h2, .header p {
            margin: 0;
        }
        .line {
            border: 2px double black;
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .footer {
            text-align: right;
            margin-top: 30px;
        }
        h4 {
            margin-top: 20px;
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="../assets/img/logo.png">
        <h1>Dinas Perumahan Rakyat dan Kawasan Permukiman</h1>
        <h2>Provinsi Kalimantan Selatan</h2>
        <p>Jl. RE Martadinata No.1, Kertak Baru Ilir, Kec. Banjarmasin Tengah, Kota Banjarmasin, Kalimantan Selatan Kode Pos 70231</p>
        <p>Email: diskominfotik@mail.banjarmasinkota.go.id | Website: <a href="https://satudata.banjarmasinkota.go.id/">www.satudata.banjarmasinkota.go.id</a></p>
    </div>
    <hr class="line">
    <h3 style="text-align: center;">LAPORAN ANALITIK</h3>

    <?php
    include '../DB/koneksi.php';
    $surat_masuk = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_surat_masuk"));
    $surat_keluar = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_surat_keluar"));
    $surat_undangan = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_surat_undangan"));
    $surat_cuti = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_surat_cuti"));
    $surat_gaji = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_surat_gaji"));
    $deptData = mysqli_query($koneksi, "SELECT tb_bidang.nama_bidang, COUNT(tb_pegawai.id) as count FROM tb_bidang LEFT JOIN tb_pegawai ON tb_bidang.id = tb_pegawai.id_bidang GROUP BY tb_bidang.id");
    $leaveStatusData = mysqli_query($koneksi, "SELECT status, COUNT(*) as count FROM tb_surat_cuti GROUP BY status");
    ?>

    <!-- Distribusi Jenis Surat -->
    <h4>Distribusi Jenis Surat</h4>
    <table>
        <thead>
            <tr>
                <th>Jenis Surat</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Surat Masuk</td>
                <td><?= $surat_masuk ?></td>
            </tr>
            <tr>
                <td>Surat Keluar</td>
                <td><?= $surat_keluar ?></td>
            </tr>
            <tr>
                <td>Surat Undangan</td>
                <td><?= $surat_undangan ?></td>
            </tr>
            <tr>
                <td>Surat Cuti</td>
                <td><?= $surat_cuti ?></td>
            </tr>
            <tr>
                <td>Surat Gaji</td>
                <td><?= $surat_gaji ?></td>
            </tr>
        </tbody>
    </table>

    <!-- Distribusi Pegawai per Bidang -->
    <h4>Distribusi Pegawai per Bidang</h4>
    <table>
        <thead>
            <tr>
                <th>Bidang</th>
                <th>Jumlah Pegawai</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($deptData)) : ?>
                <tr>
                    <td><?= $row['nama_bidang'] ?></td>
                    <td><?= $row['count'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Status Pengajuan Cuti -->
    <h4>Status Pengajuan Cuti</h4>
    <table>
        <thead>
            <tr>
                <th>Status</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($leaveStatusData)) : ?>
                <tr>
                    <td><?= $row['status'] ?></td>
                    <td><?= $row['count'] ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Banjarmasin, <?= date('d F Y'); ?></p>
        <p>Mengetahui,</p>
        <br><br><br>
        <p><strong>Kadisperkim Banjarmasin</strong></p>
    </div>
</body>

</html>