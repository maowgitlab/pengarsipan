<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penilaian Kawasan</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            line-height: 1.6; 
            margin: 20px; 
        }
        .header { 
            text-align: center; 
            margin-bottom: 20px; 
        }
        .header img { 
            float: left; 
            width: 100px; 
            height: 100px; 
            margin-right: 20px; 
        }
        .header h1, .header h2, .header p { 
            margin: 0; 
        }
        .header p.subtitle { 
            font-style: italic; 
            margin-top: 5px; 
        }
        .line { 
            border: 2px double black; 
            margin: 20px 0; 
        }
        .summary { 
            margin-bottom: 20px; 
            font-size: 14px; 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px; 
            font-size: 12px; 
        }
        th, td { 
            border: 1px solid black; 
            padding: 8px; 
            text-align: left; 
            vertical-align: middle; 
        }
        th { 
            background-color: #f2f2f2; 
            font-weight: bold; 
        }
        tr:nth-child(even) { 
            background-color: #f9f9f9; 
        }
        img.bukti { 
            width: 100px; 
            height: auto; 
            display: block; 
            margin: 0 auto; 
        }
        .footer { 
            text-align: right; 
            margin-top: 40px; 
            font-size: 12px; 
        }
        .footer .note { 
            text-align: left; 
            margin-top: 20px; 
            font-style: italic; 
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <img src="../assets/img/logo.png" alt="Logo">
        <h1>Dinas Perumahan Rakyat dan Kawasan Permukiman</h1>
        <h2>Provinsi Kalimantan Selatan</h2>
        <p>Jl. RE Martadinata No.1, Kertak Baru Ilir, Kec. Banjarmasin Tengah, Kota Banjarmasin, Kalimantan Selatan Kode Pos 70231</p>
        <p>Email: diskominfotik@mail.banjarmasinkota.go.id | Website: <a href="https://satudata.banjarmasinkota.go.id/">www.satudata.banjarmasinkota.go.id</a></p>
        <p class="subtitle">Laporan Penilaian Kawasan Layak Permukiman</p>
        <p>Periode Data: Hingga <?= date('d F Y'); ?></p>
    </div>
    <hr class="line">
    <h3 style="text-align: center;">LAPORAN DATA PENILAIAN KAWASAN</h3>

    <!-- Summary Section -->
    <div class="summary">
        <h4>Ringkasan Penilaian</h4>
        <?php
        include '../DB/koneksi.php';
        $total_assessments = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM penilaian_kawasan"));
        $total_with_bukti = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM penilaian_kawasan WHERE bukti_file IS NOT NULL AND bukti_file != ''"));
        ?>
        <p><strong>Jumlah Total Penilaian:</strong> <?= $total_assessments; ?> penilaian</p>
        <p><strong>Penilaian dengan Bukti:</strong> <?= $total_with_bukti; ?> penilaian</p>
    </div>

    <!-- Data Penilaian Kawasan -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Kawasan</th>
                <th>Indikator</th>
                <th>Periode</th>
                <th>Nilai</th>
                <th>Keterangan</th>
                <th>Tanggal Penilaian</th>
                <th>Bukti</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            $data = mysqli_query($koneksi, "SELECT pk.*, k.nama_kawasan, i.nama_indikator, p.bulan, p.tahun 
                                            FROM penilaian_kawasan pk 
                                            JOIN kawasan k ON pk.id_kawasan = k.id_kawasan 
                                            JOIN indikator_penilaian i ON pk.id_indikator = i.id_indikator 
                                            JOIN periode_penilaian p ON pk.id_periode = p.id_periode 
                                            ORDER BY pk.id_penilaian DESC");
            if (!$data) {
                echo "<tr><td colspan='8'>Error: " . htmlspecialchars(mysqli_error($koneksi)) . "</td></tr>";
            }
            foreach ($data as $row) : 
                $bukti_file = $row['bukti_file'];
                $file_ext = $bukti_file ? strtolower(pathinfo($bukti_file, PATHINFO_EXTENSION)) : '';
                $is_image = in_array($file_ext, ['jpg', 'jpeg', 'png']);
                $is_pdf = $file_ext === 'pdf';
            ?>
                <tr>
                    <th scope="row"><?= $no++; ?></th>
                    <td><?= htmlspecialchars($row['nama_kawasan']); ?></td>
                    <td><?= htmlspecialchars($row['nama_indikator']); ?></td>
                    <td><?= htmlspecialchars(date('F', mktime(0, 0, 0, $row['bulan'], 1)) . ' ' . $row['tahun']); ?></td>
                    <td><?= htmlspecialchars($row['nilai']); ?></td>
                    <td><?= htmlspecialchars($row['keterangan']); ?></td>
                    <td><?= htmlspecialchars($row['tanggal_penilaian']); ?></td>
                    <td>
                        <?php if ($bukti_file && $is_image) : ?>
                            <img src="../assets/file/bukti/<?= htmlspecialchars($bukti_file); ?>" alt="Bukti Penilaian" class="bukti">
                        <?php elseif ($bukti_file && $is_pdf) : ?>
                            <?= htmlspecialchars($bukti_file); ?>
                        <?php else : ?>
                            Tidak ada bukti
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if ($no === 1) : ?>
                <tr><td colspan="8" style="text-align: center;">Tidak ada data ditemukan</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Banjarmasin, <?= date('d F Y'); ?></p>
        <p>Mengetahui,</p>
        <p>Kepala Dinas Perumahan Rakyat dan Kawasan Permukiman</p>
        <br><br><br>
        <p><strong>(___________________________)</strong></p>
    </div>
</body>
</html>