<div class="pagetitle">
    <h1>Slip Gaji Anda</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active">Slip Gaji Anda</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Slip Gaji</h5>
                    <table class="table table-hover" id="tabelData">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">No Surat</th>
                                <th scope="col">Gaji Awal</th>
                                <th scope="col">Gaji Akhir</th>
                                <th scope="col">Priode</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            $nip = $_SESSION['nip'];
                            $data = mysqli_query($koneksi, "SELECT tb_surat_gaji.*, tb_gaji.*, tb_pegawai.*, tb_bidang.id AS id_bidang, tb_bidang.nama_bidang FROM tb_surat_gaji JOIN tb_gaji ON tb_surat_gaji.id_gaji = tb_gaji.id JOIN tb_pegawai ON tb_gaji.nip = tb_pegawai.nip JOIN tb_bidang ON tb_gaji.id_bidang = tb_bidang.id WHERE tb_pegawai.nip = '$nip' ORDER BY tb_surat_gaji.id DESC");
                            foreach ($data as $row) : ?>
                                <tr>
                                    <th scope="row"><?= $no++; ?></th>
                                    <td><?= $row['no_surat']; ?></td>
                                    <td><?= 'Rp ' . number_format($row['gaji_awal'], 0, ',', '.'); ?></td>
                                    <td><?= 'Rp ' . number_format($row['gaji_akhir'], 0, ',', '.'); ?></td>
                                    <td><?= $row['tanggal_surat']; ?></td>
                                    <td>
                                        <a href="report/download_slip_gaji.php?id=<?= $row['id']; ?>" target="_blank" class="btn btn-sm btn-primary"><i class="bi bi-download"></i></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>