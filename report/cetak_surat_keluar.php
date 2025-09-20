<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Data Surat Keluar</title>
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
    
    <?php
    include '../DB/koneksi.php';

    $filter_type = isset($_GET['filter_type']) ? mysqli_real_escape_string($koneksi, $_GET['filter_type']) : 'all';
    $letter_type = isset($_GET['letter_type']) ? mysqli_real_escape_string($koneksi, $_GET['letter_type']) : 'all';
    $where_clause = '';

    if ($letter_type === 'surat_keluar') {
        $where_clause = "WHERE tb_surat.jenis_surat = 'surat_keluar'";
    } elseif ($letter_type === 'surat_undangan') {
        $where_clause = "WHERE tb_surat.jenis_surat = 'surat_undangan'";
    }

    if ($filter_type === 'monthly') {
        $month = isset($_GET['month']) ? mysqli_real_escape_string($koneksi, $_GET['month']) : date('m');
        $year = isset($_GET['year_monthly']) ? mysqli_real_escape_string($koneksi, $_GET['year_monthly']) : date('Y');
        $where_clause .= ($where_clause ? ' AND ' : 'WHERE ') . "MONTH(tb_surat_keluar.tanggal_kirim) = '$month' AND YEAR(tb_surat_keluar.tanggal_kirim) = '$year'";
        $title = "LAPORAN DATA SURAT " . ($letter_type === 'surat_keluar' ? 'KELUAR' : ($letter_type === 'surat_undangan' ? 'UNDANGAN' : '')) . " BULAN " . date('F', mktime(0, 0, 0, $month, 1)) . " $year";
    } elseif ($filter_type === 'period') {
        $start_date = isset($_GET['start_date']) ? mysqli_real_escape_string($koneksi, $_GET['start_date']) : date('Y-m-d');
        $end_date = isset($_GET['end_date']) ? mysqli_real_escape_string($koneksi, $_GET['end_date']) : date('Y-m-d');
        $where_clause .= ($where_clause ? ' AND ' : 'WHERE ') . "tb_surat_keluar.tanggal_kirim BETWEEN '$start_date' AND '$end_date'";
        $title = "LAPORAN DATA SURAT " . ($letter_type === 'surat_keluar' ? 'KELUAR' : ($letter_type === 'surat_undangan' ? 'UNDANGAN' : '')) . " PERIODE " . date('d F Y', strtotime($start_date)) . " - " . date('d F Y', strtotime($end_date));
    } elseif ($filter_type === 'yearly') {
        $year = isset($_GET['year']) ? mysqli_real_escape_string($koneksi, $_GET['year']) : date('Y');
        $where_clause .= ($where_clause ? ' AND ' : 'WHERE ') . "YEAR(tb_surat_keluar.tanggal_kirim) = '$year'";
        $title = "LAPORAN DATA SURAT " . ($letter_type === 'surat_keluar' ? 'KELUAR' : ($letter_type === 'surat_undangan' ? 'UNDANGAN' : '')) . " TAHUN $year";
    } else {
        $title = "LAPORAN DATA SURAT " . ($letter_type === 'surat_keluar' ? 'KELUAR' : ($letter_type === 'surat_undangan' ? 'UNDANGAN' : ''));
    }

    $query = "SELECT tb_surat_keluar.*, tb_surat.jenis_surat 
              FROM tb_surat_keluar 
              JOIN tb_surat ON tb_surat_keluar.id_surat = tb_surat.id 
              $where_clause 
              ORDER BY tb_surat_keluar.id DESC";
    $data = mysqli_query($koneksi, $query);
    if (!$data) {
        echo "<p>Error: " . htmlspecialchars(mysqli_error($koneksi)) . "</p>";
        exit;
    }
    ?>

    <h3 style="text-align: center;"><?= htmlspecialchars($title); ?></h3>
    
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>No Surat</th>
                <th>Jenis Surat</th>
                <th>Tanggal Kirim</th>
                <th>Penerima</th>
                <th>Instansi</th>
                <th>Perihal</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1; 
            foreach ($data as $row) : ?>
                <tr>
                    <th scope="row"><?= $no++; ?></th>
                    <td><?= htmlspecialchars($row['no_surat']); ?></td>
                    <td><?= htmlspecialchars($row['jenis_surat']); ?></td>
                    <td><?= htmlspecialchars($row['tanggal_kirim']); ?></td>
                    <td><?= htmlspecialchars($row['penerima']); ?></td>
                    <td><?= htmlspecialchars($row['instansi'] ?: '-'); ?></td>
                    <td><?= htmlspecialchars($row['perihal']); ?></td>
                </tr>
            <?php endforeach; ?>
            <?php if ($no === 1) : ?>
                <tr><td colspan="7" style="text-align: center;">Tidak ada data ditemukan</td></tr>
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