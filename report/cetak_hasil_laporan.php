<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Hasil Laporan</title>
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
    <h3 style="text-align: center;">LAPORAN DATA HASIL LAPORAN</h3>
    <?php 
    include '../DB/koneksi.php';
    $periode_filter = isset($_GET['id_periode']) && $_GET['id_periode'] != 'all' ? mysqli_real_escape_string($koneksi, $_GET['id_periode']) : 'all';
    if ($periode_filter != 'all') {
        $periode_data = mysqli_query($koneksi, "SELECT bulan, tahun FROM periode_penilaian WHERE id_periode = '$periode_filter'");
        $periode_row = mysqli_fetch_assoc($periode_data);
        echo "<p style='text-align: center;'>Periode: " . htmlspecialchars(date('F', mktime(0, 0, 0, $periode_row['bulan'], 1)) . ' ' . $periode_row['tahun']) . "</p>";
    }
    ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Kawasan</th>
                <th>Periode</th>
                <th>Status Layak</th>
                <th>Rekomendasi</th>
                <th>Tanggal Dibuat</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            $query = "SELECT hl.*, k.nama_kawasan, p.bulan, p.tahun 
                      FROM hasil_laporan hl 
                      JOIN kawasan k ON hl.id_kawasan = k.id_kawasan 
                      JOIN periode_penilaian p ON hl.id_periode = p.id_periode";
            if ($periode_filter != 'all') {
                $query .= " WHERE hl.id_periode = '$periode_filter'";
            }
            $query .= " ORDER BY hl.id_laporan DESC";
            $data = mysqli_query($koneksi, $query);
            if (!$data) {
                echo "<tr><td colspan='6'>Error: " . htmlspecialchars(mysqli_error($koneksi)) . "</td></tr>";
            }
            foreach ($data as $row) : ?>
                <tr>
                    <th scope="row"><?= $no++; ?></th>
                    <td><?= htmlspecialchars($row['nama_kawasan']); ?></td>
                    <td><?= htmlspecialchars(date('F', mktime(0, 0, 0, $row['bulan'], 1)) . ' ' . $row['tahun']); ?></td>
                    <td><?= htmlspecialchars($row['status_layak']); ?></td>
                    <td><?= htmlspecialchars($row['rekomendasi']); ?></td>
                    <td><?= htmlspecialchars($row['tanggal_dibuat']); ?></td>
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