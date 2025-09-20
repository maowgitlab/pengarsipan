<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Data Surat Gaji</title>
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
    // Assuming $koneksi is already defined dynamically
    $filter_type = isset($_GET['filter_type']) ? $_GET['filter_type'] : 'all';
    $where_clause = '';

    if ($filter_type === 'monthly') {
        $month = isset($_GET['month']) ? $_GET['month'] : date('m');
        $year = isset($_GET['year_monthly']) ? $_GET['year_monthly'] : date('Y');
        $where_clause = "WHERE MONTH(sg.tanggal_surat) = '$month' AND YEAR(sg.tanggal_surat) = '$year'";
        $title = "LAPORAN DATA SURAT GAJI BULAN " . date('F', mktime(0, 0, 0, $month, 1)) . " $year";
    } elseif ($filter_type === 'period') {
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-d');
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');
        $where_clause = "WHERE sg.tanggal_surat BETWEEN '$start_date' AND '$end_date'";
        $title = "LAPORAN DATA SURAT GAJI PERIODE " . date('d F Y', strtotime($start_date)) . " - " . date('d F Y', strtotime($end_date));
    } elseif ($filter_type === 'yearly') {
        $year = isset($_GET['year']) ? $_GET['year'] : date('Y');
        $where_clause = "WHERE YEAR(sg.tanggal_surat) = '$year'";
        $title = "LAPORAN DATA SURAT GAJI TAHUN $year";
    } else {
        $title = "LAPORAN DATA SURAT GAJI";
    }

    $query = "SELECT 
                sg.id, 
                sg.no_surat,
                s.id AS id_surat,
                s.jenis_surat, 
                g.id AS id_gaji,
                g.nip, 
                b.id AS id_bidang,
                b.nama_bidang, 
                sg.tanggal_surat
              FROM tb_surat_gaji sg
              LEFT JOIN tb_surat s ON sg.id_surat = s.id
              LEFT JOIN tb_gaji g ON sg.id_gaji = g.id
              LEFT JOIN tb_bidang b ON sg.id_bidang = b.id
              $where_clause 
              ORDER BY sg.id DESC";
    $data = mysqli_query($koneksi, $query);
    ?>

    <h3 style="text-align: center;"><?= $title; ?></h3>
    
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>No Surat</th>
                <th>Jenis Surat</th>
                <th>NIP</th>
                <th>Bidang</th>
                <th>Tanggal Surat</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1;
            foreach ($data as $row) : ?>
                <tr>
                    <th scope="row"><?= $no++; ?></th>
                    <td><?= $row['no_surat']; ?></td>
                    <td><?= $row['jenis_surat']; ?></td>
                    <td><?= $row['nip']; ?></td>
                    <td><?= $row['nama_bidang']; ?></td>
                    <td><?= $row['tanggal_surat']; ?></td>
                </tr>
            <?php endforeach; ?>
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