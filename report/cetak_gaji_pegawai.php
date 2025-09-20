<script>
    window.print();
</script>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Data Gaji Pegawai</title>
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
        <h2>Provinsi Kalimantan Selatan</h2>
        <p>Jl. RE Martadinata No.1, Kertak Baru Ilir, Kec. Banjarmasin Tengah, Kota Banjarmasin, Kalimantan Selatan Kode Pos 70231</p>
        <p>Email: diskominfotik@mail.banjarmasinkota.go.id | Website: <a href="https://satudata.banjarmasinkota.go.id/">www.satudata.banjarmasinkota.go.id</a></p>
    </div>
    <hr class="line">
    <h3 style="text-align: center;">LAPORAN DATA GAJI PEGAWAI</h3>

    <?php
    include '../DB/koneksi.php';
    $data = mysqli_query($koneksi, "SELECT tb_gaji.*, tb_pegawai.nip, tb_pegawai.nama_pegawai, tb_bidang.id AS id_bidang, tb_bidang.nama_bidang FROM tb_gaji JOIN tb_pegawai ON tb_gaji.nip = tb_pegawai.nip JOIN tb_bidang ON tb_gaji.id_bidang = tb_bidang.id ORDER BY tb_gaji.id DESC");
    ?>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Pegawai | NIP</th>
                <th>Bidang</th>
                <th>Gaji Awal</th>
                <th>Gaji Akhir</th>
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
                    <td><?= 'Rp ' . number_format($row['gaji_awal'], 0, ',', '.'); ?></td>
                    <td><?= 'Rp ' . number_format($row['gaji_akhir'], 0, ',', '.'); ?></td>
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