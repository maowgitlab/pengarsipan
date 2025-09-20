<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Analitik Kawasan Layak Permukiman</title>
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
        }
        th { 
            background-color: #f2f2f2; 
            font-weight: bold; 
        }
        tr:nth-child(even) { 
            background-color: #f9f9f9; 
        }
        tr.total { 
            font-weight: bold; 
            background-color: #e6e6e6; 
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
        h4 { 
            margin-top: 20px; 
            font-size: 14px; 
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
        <p class="subtitle">Laporan Analitik untuk Evaluasi Status Kawasan Layak Permukiman</p>
        <p>Periode Data: Hingga <?= date('d F Y'); ?></p>
    </div>
    <hr class="line">
    <h3 style="text-align: center;">LAPORAN ANALITIK KAWASAN LAYAK PERMUKIMAN</h3>
    <?php 
    include '../DB/koneksi.php';
    $id_kawasan_filter = isset($_GET['id_kawasan']) && $_GET['id_kawasan'] != 'all' ? mysqli_real_escape_string($koneksi, $_GET['id_kawasan']) : 'all';
    if ($id_kawasan_filter != 'all') {
        $kawasan_data = mysqli_query($koneksi, "SELECT nama_kawasan FROM kawasan WHERE id_kawasan = '$id_kawasan_filter'");
        if ($kawasan_data && mysqli_num_rows($kawasan_data) > 0) {
            $kawasan_row = mysqli_fetch_assoc($kawasan_data);
            echo "<p style='text-align: center; font-weight: bold;'>Kawasan: " . htmlspecialchars($kawasan_row['nama_kawasan']) . "</p>";
        } else {
            echo "<p style='text-align: center; font-weight: bold;'>Kawasan: Tidak Ditemukan</p>";
        }
    } else {
        echo "<p style='text-align: center; font-weight: bold;'>Kawasan: Semua Kawasan</p>";
    }
    ?>

    <!-- Summary Section -->
    <div class="summary">
        <h4>Ringkasan Analitik</h4>
        <?php
        $total_kawasan = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM kawasan" . ($id_kawasan_filter != 'all' ? " WHERE id_kawasan = '$id_kawasan_filter'" : "")));
        $total_assessments = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM hasil_laporan" . ($id_kawasan_filter != 'all' ? " WHERE id_kawasan = '$id_kawasan_filter'" : "")));
        $layak_count = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM hasil_laporan WHERE status_layak = 'Layak'" . ($id_kawasan_filter != 'all' ? " AND id_kawasan = '$id_kawasan_filter'" : "")));
        $tidak_layak_count = $total_assessments - $layak_count;
        $layak_percentage = $total_assessments > 0 ? round(($layak_count / $total_assessments) * 100, 2) : 0;
        $tidak_layak_percentage = $total_assessments > 0 ? round(($tidak_layak_count / $total_assessments) * 100, 2) : 0;
        ?>
        <p><strong>Jumlah Total Kawasan:</strong> <?= $total_kawasan; ?> kawasan</p>
        <p><strong>Jumlah Total Penilaian:</strong> <?= $total_assessments; ?> penilaian</p>
        <p><strong>Persentase Layak:</strong> <?= $layak_count; ?> penilaian (<?= $layak_percentage; ?>%)</p>
        <p><strong>Persentase Tidak Layak:</strong> <?= $tidak_layak_count; ?> penilaian (<?= $tidak_layak_percentage; ?>%)</p>
    </div>

    <!-- Distribusi Status Layak -->
    <h4>Distribusi Status Layak Kawasan</h4>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Kawasan</th>
                <th>Status Layak</th>
                <th>Jumlah</th>
                <th>Persentase</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            $total_count_all = 0;
            $query = "SELECT k.nama_kawasan, hl.status_layak, COUNT(*) as count 
                      FROM hasil_laporan hl 
                      JOIN kawasan k ON hl.id_kawasan = k.id_kawasan";
            if ($id_kawasan_filter != 'all') {
                $query .= " WHERE hl.id_kawasan = '$id_kawasan_filter'";
            }
            $query .= " GROUP BY hl.id_kawasan, hl.status_layak ORDER BY k.nama_kawasan, hl.status_layak";
            $statusLayakData = mysqli_query($koneksi, $query);
            if (!$statusLayakData) {
                echo "<tr><td colspan='5'>Error: " . htmlspecialchars(mysqli_error($koneksi)) . "</td></tr>";
            }
            $totalCounts = [];
            while ($row = mysqli_fetch_assoc($statusLayakData)) {
                $nama_kawasan = $row['nama_kawasan'];
                if (!isset($totalCounts[$nama_kawasan])) {
                    $totalCounts[$nama_kawasan] = 0;
                }
                $totalCounts[$nama_kawasan] += $row['count'];
                $total_count_all += $row['count'];
            }
            mysqli_data_seek($statusLayakData, 0);
            foreach ($statusLayakData as $row) :
                $percentage = $totalCounts[$row['nama_kawasan']] > 0 ? round(($row['count'] / $totalCounts[$row['nama_kawasan']]) * 100, 2) : 0;
            ?>
                <tr>
                    <th scope="row"><?= $no++; ?></th>
                    <td><?= htmlspecialchars($row['nama_kawasan']); ?></td>
                    <td><?= htmlspecialchars($row['status_layak']); ?></td>
                    <td><?= htmlspecialchars($row['count']); ?></td>
                    <td><?= htmlspecialchars($percentage); ?>%</td>
                </tr>
            <?php endforeach; ?>
            <?php if ($no > 1) : ?>
                <tr class="total">
                    <th scope="row" colspan="3">Total</th>
                    <td><?= $total_count_all; ?></td>
                    <td>100%</td>
                </tr>
            <?php endif; ?>
            <?php if ($no === 1) : ?>
                <tr><td colspan="5" style="text-align: center;">Tidak ada data ditemukan</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Rata-rata Nilai Penilaian per Kawasan -->
    <h4>Rata-rata Nilai Penilaian per Kawasan</h4>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Kawasan</th>
                <th>Jumlah Penilaian</th>
                <th>Rata-rata Nilai</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            $query = "SELECT k.nama_kawasan, COUNT(pk.id_penilaian) as count, AVG(pk.nilai) as avg_score 
                      FROM penilaian_kawasan pk 
                      JOIN kawasan k ON pk.id_kawasan = k.id_kawasan";
            if ($id_kawasan_filter != 'all') {
                $query .= " WHERE pk.id_kawasan = '$id_kawasan_filter'";
            }
            $query .= " GROUP BY k.id_kawasan ORDER BY k.nama_kawasan";
            $avgScoreData = mysqli_query($koneksi, $query);
            if (!$avgScoreData) {
                echo "<tr><td colspan='4'>Error: " . htmlspecialchars(mysqli_error($koneksi)) . "</td></tr>";
            }
            foreach ($avgScoreData as $row) : ?>
                <tr>
                    <th scope="row"><?= $no++; ?></th>
                    <td><?= htmlspecialchars($row['nama_kawasan']); ?></td>
                    <td><?= htmlspecialchars($row['count']); ?></td>
                    <td><?= htmlspecialchars(round($row['avg_score'], 2)); ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if ($no === 1) : ?>
                <tr><td colspan="4" style="text-align: center;">Tidak ada data ditemukan</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Tren Kawasan Layak per Periode -->
    <h4>Tren Kawasan Layak per Periode</h4>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Periode</th>
                <th>Tahun</th>
                <th>Jumlah Kawasan Layak</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            $query = "SELECT p.bulan, p.tahun, COUNT(hl.id_laporan) as layak_count 
                      FROM hasil_laporan hl 
                      JOIN periode_penilaian p ON hl.id_periode = p.id_periode 
                      WHERE hl.status_layak = 'Layak'";
            if ($id_kawasan_filter != 'all') {
                $query .= " AND hl.id_kawasan = '$id_kawasan_filter'";
            }
            $query .= " GROUP BY p.id_periode ORDER BY p.tahun, p.bulan";
            $trendData = mysqli_query($koneksi, $query);
            if (!$trendData) {
                echo "<tr><td colspan='4'>Error: " . htmlspecialchars(mysqli_error($koneksi)) . "</td></tr>";
            }
            foreach ($trendData as $row) : ?>
                <tr>
                    <th scope="row"><?= $no++; ?></th>
                    <td><?= htmlspecialchars(date('F', mktime(0, 0, 0, $row['bulan'], 1))); ?></td>
                    <td><?= htmlspecialchars($row['tahun']); ?></td>
                    <td><?= htmlspecialchars($row['layak_count']); ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if ($no === 1) : ?>
                <tr><td colspan="4" style="text-align: center;">Tidak ada data ditemukan</td></tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="footer">
        <p>Banjarmasin, <?= date('d F Y'); ?></p>
        <p>Mengetahui,</p>
        <p>Kepala Dinas Perumahan Rakyat dan Kawasan Permukiman</p>
        <br><br><br>
        <p><strong>(___________________________)</strong></p>
        <p class="note">Sumber Data: Sistem Informasi Pengarsipan Dinas Perumahan Rakyat dan Kawasan Permukiman Kota Banjarmasin</p>
    </div>
</body>
</html>