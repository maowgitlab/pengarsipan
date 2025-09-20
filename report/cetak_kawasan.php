<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Kawasan</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .header img { float: left; width: 100px; height: 100px; }
        .header h1, .header h2, .header p { margin: 0; }
        .line { border: 2px double black; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .footer { text-align: right; margin-top: 30px; }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <img src="../assets/img/logo.png">
        <h1>Dinas Perumahan Rakyat dan Kawasan Permukiman</h1>
        <h2>Provinsi Kalimantan Selatan</h2>
        <p>Jl. RE Martadinata No.1, Kertak Baru Ilir, Kec. Banjarmasin Tengah, Kota Banjarmasin, Kalimantan Selatan Kode Pos 70231</p>
        <p>Email: diskominfotik@mail.banjarmasinkota.go.id | Website: <a href="https://satudata.banjarmasinkota.go.id/">www.satudata.banjarmasinkota.go.id</a></p>
    </div>
    <hr class="line">
    <h3 style="text-align: center;">LAPORAN DATA KAWASAN</h3>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Kawasan</th>
                <th>Kecamatan</th>
                <th>Luas (Ha)</th>
                <th>Jumlah Penduduk</th>
                <th>Status Layak</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            include '../DB/koneksi.php';
            $no = 1;
            $data = mysqli_query($koneksi, "SELECT * FROM kawasan ORDER BY id_kawasan DESC");
            if (!$data) {
                echo "<tr><td colspan='6'>Error: " . htmlspecialchars(mysqli_error($koneksi)) . "</td></tr>";
            }
            foreach ($data as $row) : ?>
                <tr>
                    <th scope="row"><?= $no++; ?></th>
                    <td><?= htmlspecialchars($row['nama_kawasan']); ?></td>
                    <td><?= htmlspecialchars($row['kecamatan']); ?></td>
                    <td><?= htmlspecialchars($row['luas_ha']); ?></td>
                    <td><?= htmlspecialchars($row['jumlah_penduduk']); ?></td>
                    <td><?= htmlspecialchars($row['status_layak']); ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if ($no === 1) : ?>
                <tr><td colspan="6" style="text-align: center;">Tidak ada data ditemukan</td></tr>
            <?php endif; ?>
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