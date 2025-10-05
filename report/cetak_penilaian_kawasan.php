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
        td ul {
            margin: 0;
            padding-left: 18px;
        }
        td ul li {
            margin-bottom: 4px;
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
        $total_with_bukti = mysqli_num_rows(mysqli_query($koneksi, "SELECT DISTINCT id_penilaian FROM penilaian_kawasan_files"));
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
                <th>Tim Penilai</th>
                <th>Lampiran</th>
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
                $teamDisplay = [];
                $teamQuery = mysqli_query($koneksi, "SELECT p.nama_pegawai, p.jabatan, b.nama_bidang
                                                     FROM penilaian_kawasan_tim t
                                                     JOIN tb_pegawai p ON t.id_pegawai = p.id
                                                     LEFT JOIN tb_bidang b ON p.id_bidang = b.id
                                                     WHERE t.id_penilaian = '" . $row['id_penilaian'] . "'
                                                     ORDER BY p.nama_pegawai");
                if ($teamQuery) {
                    while ($teamRow = mysqli_fetch_assoc($teamQuery)) {
                        $infoParts = [];
                        if (!empty($teamRow['jabatan'])) {
                            $infoParts[] = $teamRow['jabatan'];
                        }
                        if (!empty($teamRow['nama_bidang'])) {
                            $infoParts[] = $teamRow['nama_bidang'];
                        }
                        $infoText = $infoParts ? ' (' . implode(' - ', $infoParts) . ')' : '';
                        $teamDisplay[] = ($teamRow['nama_pegawai'] ?? '-') . $infoText;
                    }
                }

                $attachments = [];
                $attachmentQuery = mysqli_query($koneksi, "SELECT file_name, original_name
                                                          FROM penilaian_kawasan_files
                                                          WHERE id_penilaian = '" . $row['id_penilaian'] . "'
                                                          ORDER BY id ASC");
                if ($attachmentQuery) {
                    while ($attachmentRow = mysqli_fetch_assoc($attachmentQuery)) {
                        if (empty($attachmentRow['file_name'])) {
                            continue;
                        }

                        $attachments[] = [
                            'file_name' => $attachmentRow['file_name'],
                            'original_name' => $attachmentRow['original_name'] ?: $attachmentRow['file_name']
                        ];
                    }
                }

                if (empty($attachments) && !empty($row['bukti_file'])) {
                    $attachments[] = [
                        'file_name' => $row['bukti_file'],
                        'original_name' => $row['bukti_file']
                    ];
                }
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
                        <?php if (!empty($teamDisplay)) : ?>
                            <ul>
                                <?php foreach ($teamDisplay as $teamMember) : ?>
                                    <li><?= htmlspecialchars($teamMember); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else : ?>
                            <span>-</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if (!empty($attachments)) : ?>
                            <ul>
                                <?php foreach ($attachments as $attachment) :
                                    $fileExt = strtolower(pathinfo($attachment['file_name'], PATHINFO_EXTENSION));
                                    $isImage = in_array($fileExt, ['jpg', 'jpeg', 'png']);
                                ?>
                                    <li>
                                        <?php if ($isImage) : ?>
                                            <img src="../assets/file/bukti/<?= htmlspecialchars($attachment['file_name']); ?>" alt="Bukti Penilaian" class="bukti">
                                            <div><?= htmlspecialchars($attachment['original_name']); ?></div>
                                        <?php else : ?>
                                            <?= htmlspecialchars($attachment['original_name']); ?>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else : ?>
                            <span>Tidak ada bukti</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if ($no === 1) : ?>
                <tr><td colspan="9" style="text-align: center;">Tidak ada data ditemukan</td></tr>
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