<div class="pagetitle">
    <h1>Surat Undangan</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active">Surat Undangan</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informasi Surat Undangan</h5>
                    <table class="table table-hover" id="tabelData">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">No Surat</th>
                                <th scope="col">Jenis Surat</th>
                                <th scope="col">Perihal</th>
                                <th scope="col">Tanggal Surat</th>
                                <th scope="col">Tanggal Acara</th>
                                <th scope="col">Waktu</th>
                                <th scope="col">Tempat</th>
                                <th scope="col">Pengirim</th>
                                <th scope="col">File</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            $data = mysqli_query($koneksi, "SELECT tb_surat_undangan.*, tb_surat.id AS id_surat, tb_surat.jenis_surat FROM tb_surat_undangan JOIN tb_surat ON tb_surat_undangan.id_surat = tb_surat.id order by tb_surat_undangan.id desc");
                            foreach ($data as $row) : ?>
                                <tr>
                                    <th scope="row"><?= $no++; ?></th>
                                    <td><?= $row['no_surat']; ?></td>
                                    <td><?= $row['jenis_surat']; ?></td>
                                    <td><?= $row['perihal']; ?></td>
                                    <td><?= $row['tanggal_surat']; ?></td>
                                    <td><?= $row['tanggal_acara']; ?></td>
                                    <td><?= $row['waktu']; ?></td>
                                    <td><?= $row['tempat']; ?></td>
                                    <td><?= $row['nama_pengirim']; ?></td>
                                    <td><a href="assets/file/undangan/<?= $row['file']; ?>" target="_blank" class="btn btn-sm btn-primary"><i class="bi bi-file-earmark-pdf"></i></a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>