<div class="pagetitle">
    <h1>Cuti Pegawai</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active">Cuti Pegawai</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Cuti Pegawai</h5>
                    <div class="d-flex justify-content-between my-3">
                        <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Cuti</button>
                        <?php endif; ?>
                        <!-- <a href="report/cetak_cuti_pegawai.php" target="_blank" class="btn btn-sm btn-primary"><i class="bi bi-printer"></i></a> -->
                    </div>
                    <table class="table table-hover" id="tabelData">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Pegawai | NIP</th>
                                <th scope="col">Bidang</th>
                                <th scope="col">Alasan</th>
                                <th scope="col">Tanggal Mulai</th>
                                <th scope="col">Tanggal Selesai</th>
                                <th scope="col">Alasan Cuti</th>
                                <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                                    <th scope="col">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            $data = mysqli_query($koneksi, "SELECT tb_cuti.*, tb_pegawai.nama_pegawai, tb_pegawai.nip, tb_bidang.id AS id_bidang, tb_bidang.nama_bidang FROM tb_cuti JOIN tb_pegawai JOIN tb_bidang ON tb_cuti.nip = tb_pegawai.nip AND tb_cuti.id_bidang = tb_bidang.id ORDER BY tb_cuti.id DESC");
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
                                    <td><?= $row['alasan']; ?></td>
                                    <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="editData('<?= $row['id']; ?>', '<?= $row['nip']; ?>', '<?= $row['id_bidang']; ?>', '<?= $row['alasan']; ?>', '<?= $row['tanggal_mulai']; ?>', '<?= $row['tanggal_selesai']; ?>')" data-bs-toggle="modal" data-bs-target="#modalEdit"><i class="bi bi-pencil-square"></i></button>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="hapusData('<?= $row['id']; ?>')" data-bs-toggle="modal" data-bs-target="#modalHapus"><i class="bi bi-trash"></i></button>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Tambah Cuti</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="tambah" value="tambah">
                    <div class="col-12">
                        <label for="nip" class="form-label">Nama Pegawai</label>
                        <select class="form-select" id="nip" name="nip" required>
                            <option selected disabled value="">Pilih Pegawai</option>
                            <?php $data = mysqli_query($koneksi, "SELECT nip, nama_pegawai FROM tb_pegawai");
                            foreach ($data as $row) : ?>
                                <option value="<?= $row['nip']; ?>"><?= $row['nama_pegawai']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="id_bidang" class="form-label">Bidang</label>
                        <select class="form-select" id="id_bidang" name="id_bidang" required>
                            <option selected disabled value="">Pilih Bidang</option>
                            <?php $data = mysqli_query($koneksi, "SELECT * FROM tb_bidang");
                            foreach ($data as $row) : ?>
                                <option value="<?= $row['id']; ?>"><?= $row['nama_bidang']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="alasan" class="form-label">Alasan</label>
                        <input type="text" class="form-control" id="alasan" name="alasan" required>
                    </div>
                    <div class="col-12">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
                    </div>
                    <div class="col-12">
                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label> 
                        <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Edit Data Cuti</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="edit" value="edit">
                    <input type="hidden" name="idEdit" id="idEdit">
                    <div class="col-12">
                        <label for="nip_edit" class="form-label">Nama Pegawai</label>
                        <select class="form-select" id="nip_edit" name="nip_edit" required>
                            <option selected disabled value="">Pilih Pegawai</option>
                            <?php $data = mysqli_query($koneksi, "SELECT nip, nama_pegawai FROM tb_pegawai");
                            foreach ($data as $row) : ?>
                                <option value="<?= $row['nip']; ?>"><?= $row['nama_pegawai']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="id_bidang_edit" class="form-label">Bidang</label>
                        <select class="form-select" id="id_bidang_edit" name="id_bidang_edit" required>
                            <option selected disabled value="">Pilih Bidang</option>
                            <?php $data = mysqli_query($koneksi, "SELECT * FROM tb_bidang");
                            foreach ($data as $row) : ?>
                                <option value="<?= $row['id']; ?>"><?= $row['nama_bidang']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="alasan_edit" class="form-label">Alasan</label>
                        <input type="text" class="form-control" id="alasan_edit" name="alasan_edit" required>
                    </div>
                    <div class="col-12">
                        <label for="tanggal_mulai_edit" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="tanggal_mulai_edit" name="tanggal_mulai_edit" required>
                    </div>
                    <div class="col-12">
                        <label for="tanggal_selesai_edit" class="form-label">Tanggal Selesai</label> 
                        <input type="date" class="form-control" id="tanggal_selesai_edit" name="tanggal_selesai_edit" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalHapus" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Penghapusan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post">
                    <input type="hidden" name="hapus" value="hapus">
                    <input type="hidden" name="idHapus" id="idHapus">
                    <span class="m-5">Apakah anda yakin ingin menghapus data ini?</span>
                    <button type="submit" class="btn btn-danger">Hapus</button>
                     <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>
            </form>
        </div>
    </div>
</div>
</div>
<script>
    function editData(id, nip, id_bidang, alasan, tanggal_mulai, tanggal_selesai) {
        document.getElementById('idEdit').value = id;
        document.getElementById('nip_edit').value = nip;
        document.getElementById('id_bidang_edit').value = id_bidang;
        document.getElementById('alasan_edit').value = alasan;
        document.getElementById('tanggal_mulai_edit').value = tanggal_mulai;
        document.getElementById('tanggal_selesai_edit').value = tanggal_selesai;
        
    }

    function hapusData(id) {
        document.getElementById('idHapus').value = id;
    }
</script>
<?php
if (isset($_POST['tambah'])) {
    $nip = $_POST['nip'];
    $idBidang = $_POST['id_bidang'];
    $alasan = $_POST['alasan'];
    $tanggalMulai = $_POST['tanggal_mulai'];
    $tanggalSelesai = $_POST['tanggal_selesai'];

    $query = mysqli_query($koneksi, "INSERT INTO tb_cuti VALUES(NULL, '$nip', '$idBidang', '$alasan', '$tanggalMulai', '$tanggalSelesai')");

    if ($query) {
        echo "<script>
                alert('Data Cuti Pegawai Berhasil Ditambahkan');
                window.location.href = '?halaman=cuti_pegawai';
            </script>";
    }
}

if (isset($_POST['edit'])) {
    $id = $_POST['idEdit'];
    $nip = $_POST['nip_edit'];
    $idBidang = $_POST['id_bidang_edit'];
    $alasan = $_POST['alasan_edit'];
    $tanggalMulai = $_POST['tanggal_mulai_edit'];
    $tanggalSelesai = $_POST['tanggal_selesai_edit'];

    $query = mysqli_query($koneksi, "UPDATE tb_cuti SET nip='$nip', id_bidang='$idBidang', alasan='$alasan', tanggal_mulai='$tanggalMulai', tanggal_selesai='$tanggalSelesai' WHERE id='$id'");

    if ($query) {
        echo "<script>
                alert('Data Cuti Pegawai Berhasil Diubah');
                window.location.href = '?halaman=cuti_pegawai';
            </script>";
    }
}

if (isset($_POST['hapus'])) {
    $id = $_POST['idHapus'];

    $query = mysqli_query($koneksi, "DELETE FROM tb_cuti WHERE id='$id'");

    if ($query) {
        echo "<script>
                alert('Data Cuti Pegawai Berhasil Dihapus');
                window.location.href = '?halaman=cuti_pegawai';
            </script>";
    }
}
?>