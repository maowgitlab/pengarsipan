<div class="pagetitle">
    <h1>Bidang</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active">Bidang</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Bidang</h5>
                    <div class="d-flex justify-content-between my-3">
                        <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Bidang</button>
                        <?php endif; ?>
                    </div>
                    <table class="table table-hover" id="tabelData">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Bidang</th>
                                <th scope="col">Kegiatan</th>
                                <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                                    <th scope="col">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            $data = mysqli_query($koneksi, "SELECT * FROM tb_bidang ORDER BY id DESC");
                            foreach ($data as $row) : ?>
                                <tr>
                                    <th scope="row"><?= $no++; ?></th>
                                    <td><?= $row['nama_bidang']; ?></td>
                                    <td><?= $row['kegiatan']; ?></td>
                                    <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="editData('<?= $row['id']; ?>', '<?= $row['nama_bidang']; ?>')" data-bs-toggle="modal" data-bs-target="#modalEdit"><i class="bi bi-pencil-square"></i></button>
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
                <h5 class="modal-title">Form Tambah Bidang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post">
                    <input type="hidden" name="tambah" value="tambah">
                    <div class="col-12">
                        <label for="nama_bidang" class="form-label">Nama Bidang</label>
                        <input type="text" class="form-control" id="nama_bidang" name="nama_bidang">
                    </div>
                    <div class="col-12">
                        <label for="kegiatan" class="form-label">Kegiatan</label>
                        <input type="text" class="form-control" id="kegiatan" name="kegiatan">
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
                <h5 class="modal-title">Form Edit Bidang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post">
                    <input type="hidden" name="edit" value="edit">
                    <input type="hidden" name="idEdit" id="idEdit">
                    <div class="col-12">
                        <label for="nama_bidang_edit" class="form-label">Nama Bidang</label>
                        <input type="text" class="form-control" id="nama_bidang_edit" name="nama_bidang_edit">
                    </div>
                    <div class="col-12">
                        <label for="kegiatan_edit" class="form-label">Kegiatan</label>
                        <input type="text" class="form-control" id="kegiatan_edit" name="kegiatan_edit">
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
    function editData(id, nama_bidang, kegiatan) {
        document.getElementById('idEdit').value = id;
        document.getElementById('nama_bidang_edit').value = nama_bidang;
        document.getElementById('kegiatan_edit').value = kegiatan;
    }

    function hapusData(id) {
        document.getElementById('idHapus').value = id;
    }
</script>
<?php
if (isset($_POST['tambah'])) {
    $namaBidang = $_POST['nama_bidang'];
    $kegiatan = $_POST['kegiatan'];

    $query = mysqli_query($koneksi, "INSERT INTO tb_bidang(nama_bidang, kegiatan) VALUES('$namaBidang', '$kegiatan')");

    if ($query) {
        echo "<script>
                alert('Data Bidang Berhasil Ditambahkan');
                window.location.href='?halaman=bidang';
            </script>";
    }
}

if (isset($_POST['edit'])) {
    $id = $_POST['idEdit'];
    $namaBidang = $_POST['nama_bidang_edit'];
    $kegiatan = $_POST['kegiatan_edit'];

    $query = mysqli_query($koneksi, "UPDATE tb_bidang SET nama_bidang='$namaBidang', kegiatan='$kegiatan' WHERE id='$id'");

    if ($query) {
        echo "<script>
                alert('Data Bidang Berhasil Diubah');
                window.location.href='?halaman=bidang';
            </script>";
    }
}

if (isset($_POST['hapus'])) {
    $id = $_POST['idHapus'];

    $query = mysqli_query($koneksi, "DELETE FROM tb_bidang WHERE id='$id'");

    if ($query) {
        echo "<script>
                alert('Data Bidang Berhasil Dihapus');
                window.location.href='?halaman=bidang';
            </script>";
    }
}
?>