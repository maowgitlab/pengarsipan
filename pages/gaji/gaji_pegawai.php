<div class="pagetitle">
    <h1>Gaji Pegawai</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active">Gaji Pegawai</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Gaji Pegawai</h5>
                    <div class="d-flex justify-content-between my-3">
                        <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Gaji</button>
                        <?php endif; ?>
                        <!-- <a href="report/cetak_gaji_pegawai.php" target="_blank" class="btn btn-sm btn-primary"><i class="bi bi-printer"></i></a> -->
                    </div>
                    <table class="table table-hover" id="tabelData">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Pegawai | NIP</th>
                                <th scope="col">Bidang</th>
                                <th scope="col">Gaji Awal</th>
                                <th scope="col">Gaji Akhir</th>
                                <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                                    <th scope="col">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            $data = mysqli_query($koneksi, "SELECT tb_gaji.*, tb_pegawai.nip, tb_pegawai.nama_pegawai, tb_bidang.id AS id_bidang, tb_bidang.nama_bidang FROM tb_gaji JOIN tb_pegawai ON tb_gaji.nip = tb_pegawai.nip JOIN tb_bidang ON tb_gaji.id_bidang = tb_bidang.id ORDER BY tb_gaji.id DESC");
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
                                    <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="editData('<?= $row['id']; ?>', '<?= $row['nip']; ?>', '<?= $row['id_bidang']; ?>', '<?= $row['gaji_awal']; ?>', '<?= $row['gaji_akhir']; ?>')" data-bs-toggle="modal" data-bs-target="#modalEdit"><i class="bi bi-pencil-square"></i></button>
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
                <h5 class="modal-title">Form Tambah Data Gaji</h5>
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
                        <label for="gaji_awal" class="form-label">Gaji Awal</label>
                        <input type="number" class="form-control" id="gaji_awal" name="gaji_awal" required>
                    </div>
                    <div class="col-12">
                        <label for="gaji_akhir" class="form-label">Gaji Akhir</label>
                        <input type="number" class="form-control" id="gaji_akhir" name="gaji_akhir" required>
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
                <h5 class="modal-title">Form Edit Data Gaji</h5>
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
                        <label for="gaji_awal_edit" class="form-label">Gaji Awal</label>
                        <input type="number" class="form-control" id="gaji_awal_edit" name="gaji_awal_edit" required>
                    </div>
                    <div class="col-12">
                        <label for="gaji_akhir_edit" class="form-label">Gaji Akhir</label>
                        <input type="number" class="form-control" id="gaji_akhir_edit" name="gaji_akhir_edit" required>
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
    function editData(id, nip, id_bidang, gaji_awal, gaji_akhir) {
        document.getElementById('idEdit').value = id;
        document.getElementById('nip_edit').value = nip;
        document.getElementById('id_bidang_edit').value = id_bidang;
        document.getElementById('gaji_awal_edit').value = gaji_awal;
        document.getElementById('gaji_akhir_edit').value = gaji_akhir;
        
    }

    function hapusData(id) {
        document.getElementById('idHapus').value = id;
    }
</script>
<?php
if (isset($_POST['tambah'])) {
    $nip = $_POST['nip'];
    $idBidang = $_POST['id_bidang'];
    $gajiAwal = $_POST['gaji_awal'];
    $gajiAkhir = $_POST['gaji_akhir'];

    $query = mysqli_query($koneksi, "INSERT INTO tb_gaji VALUES(NULL, '$nip', '$idBidang', '$gajiAwal', '$gajiAkhir')");

    if ($query) {
        echo "<script>
                alert('Data Gaji Berhasil Ditambahkan');
                window.location.href = '?halaman=gaji_pegawai';
            </script>";
    }
}

if (isset($_POST['edit'])) {
    $id = $_POST['idEdit'];
    $nip = $_POST['nip_edit'];
    $idBidang = $_POST['id_bidang_edit'];
    $gajiAwal = $_POST['gaji_awal_edit'];
    $gajiAkhir = $_POST['gaji_akhir_edit'];     

    $query = mysqli_query($koneksi, "UPDATE tb_gaji SET nip='$nip', id_bidang='$idBidang', gaji_awal='$gajiAwal', gaji_akhir='$gajiAkhir' WHERE id='$id'");

    if ($query) {
        echo "<script>
                alert('Data Gaji Berhasil Diubah');
                window.location.href = '?halaman=gaji_pegawai';
            </script>";
    }
}

if (isset($_POST['hapus'])) {
    $id = $_POST['idHapus'];

    $query = mysqli_query($koneksi, "DELETE FROM tb_gaji WHERE id='$id'");

    if ($query) {
        echo "<script>
                alert('Data Gaji Berhasil Dihapus');
                window.location.href = '?halaman=gaji_pegawai';
            </script>";
    }
}
?>