<script>
    window.print();
</script>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Data Cuti Pegawai</title>
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

        .header h1,
        .header h2,
        .header p {
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

        th,
        td {
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

<body>
    <div class="header">
        <img src="../assets/img/logo.png">
        <h1>Dinas Perumahan Rakyat dan Kawasan Permukiman</h1>
        <h2>Kota Banjarmasin</h2>
        <p>Jl. RE Martadinata No.1 Blok B Lt.II, Banjarmasin 70111</p>
        <p>Website: <a href="https://dprkp.banjarmasinkota.go.id/">www.dprkp.banjarmasinkota.go.id</a></p>
    </div>
    <hr class="line">
    <h3 style="text-align: center;">LAPORAN DATA CUTI PEGAWAI</h3>

    <?php
    include '../DB/koneksi.php';
    $data = mysqli_query($koneksi, "SELECT tb_cuti.*, tb_pegawai.nama_pegawai, tb_pegawai.nip, tb_bidang.id AS id_bidang, tb_bidang.nama_bidang FROM tb_cuti JOIN tb_pegawai JOIN tb_bidang ON tb_cuti.nip = tb_pegawai.nip AND tb_cuti.id_bidang = tb_bidang.id ORDER BY tb_cuti.id DESC");
    ?>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Pegawai | NIP</th>
                <th>Bidang</th>
                <th>Alasan</th>
                <th>Tanggal Mulai</th>
                <th>Tanggal Selesai</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1;
            foreach ($data as $row) : ?>
                <tr>
                    <th scope="row"><?= $no++; ?></th>
                    <td>
                        <?= $row['nama_pegawai']; ?>
                        <div>(<u><?= $row['nip']; ?></u>)</div>
                    </td>
                    <td><?= $row['nama_bidang']; ?></td>
                    <td><?= $row['alasan']; ?></td>
                    <td><?= $row['tanggal_mulai']; ?></td>
                    <td><?= $row['tanggal_selesai']; ?></td>
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