<div class="pagetitle">
    <h1>Indikator Penilaian</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active">Indikator Penilaian</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Indikator Penilaian</h5>
                    <div class="d-flex justify-content-between my-3">
                        <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Indikator</button>
                        <?php endif; ?>
                    </div>
                    <table class="table table-hover" id="tabelData">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Indikator</th>
                                <th scope="col">Keterangan</th>
                                <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                                    <th scope="col">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            $data = mysqli_query($koneksi, "SELECT * FROM indikator_penilaian ORDER BY id_indikator DESC");
                            if (!$data) {
                                echo "<tr><td colspan='4'>Error: " . htmlspecialchars(mysqli_error($koneksi)) . "</td></tr>";
                            }
                            foreach ($data as $row) : ?>
                                <tr>
                                    <th scope="row"><?= $no++; ?></th>
                                    <td><?= htmlspecialchars($row['nama_indikator']); ?></td>
                                    <td><?= htmlspecialchars($row['keterangan']); ?></td>
                                    <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="editData('<?= $row['id_indikator']; ?>', '<?= addslashes($row['nama_indikator']); ?>', '<?= addslashes($row['keterangan']); ?>')" data-bs-toggle="modal" data-bs-target="#modalEdit"><i class="bi bi-pencil-square"></i></button>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="hapusData('<?= $row['id_indikator']; ?>')" data-bs-toggle="modal" data-bs-target="#modalHapus"><i class="bi bi-trash"></i></button>
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

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Tambah Indikator Penilaian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post">
                    <input type="hidden" name="tambah" value="tambah">
                    <div class="col-12">
                        <label for="nama_indikator" class="form-label">Nama Indikator</label>
                        <input type="text" class="form-control" id="nama_indikator" name="nama_indikator" required>
                    </div>
                    <div class="col-12">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="4"></textarea>
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

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Edit Indikator Penilaian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post">
                    <input type="hidden" name="edit" value="edit">
                    <input type="hidden" name="idEdit" id="idEdit">
                    <div class="col-12">
                        <label for="nama_indikator_edit" class="form-label">Nama Indikator</label>
                        <input type="text" class="form-control" id="nama_indikator_edit" name="nama_indikator_edit" required>
                    </div>
                    <div class="col-12">
                        <label for="keterangan_edit" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan_edit" name="keterangan_edit" rows="4"></textarea>
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

<!-- Modal Hapus -->
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
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function editData(id, nama_indikator, keterangan) {
        document.getElementById('idEdit').value = id;
        document.getElementById('nama_indikator_edit').value = nama_indikator;
        document.getElementById('keterangan_edit').value = keterangan;
    }

    function hapusData(id) {
        document.getElementById('idHapus').value = id;
    }
</script>

<?php
if (isset($_POST['tambah'])) {
    $nama_indikator = mysqli_real_escape_string($koneksi, $_POST['nama_indikator']);
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

    $query = mysqli_query($koneksi, "INSERT INTO indikator_penilaian (nama_indikator, keterangan) 
                                    VALUES ('$nama_indikator', '$keterangan')");

    if ($query) {
        echo "<script>alert('Data Indikator Penilaian Berhasil Ditambahkan'); window.location.href='?halaman=indikator_penilaian';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data: " . addslashes(mysqli_error($koneksi)) . "');</script>";
    }
}

if (isset($_POST['edit'])) {
    $id = mysqli_real_escape_string($koneksi, $_POST['idEdit']);
    $nama_indikator = mysqli_real_escape_string($koneksi, $_POST['nama_indikator_edit']);
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan_edit']);

    $query = mysqli_query($koneksi, "UPDATE indikator_penilaian SET nama_indikator='$nama_indikator', keterangan='$keterangan' 
                                    WHERE id_indikator='$id'");

    if ($query) {
        echo "<script>alert('Data Indikator Penilaian Berhasil Diubah'); window.location.href='?halaman=indikator_penilaian';</script>";
    } else {
        echo "<script>alert('Gagal mengubah data: " . addslashes(mysqli_error($koneksi)) . "');</script>";
    }
}

if (isset($_POST['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_POST['idHapus']);

    // Check if the indikator_penilaian has related records in penilaian_kawasan
    $checkPenilaian = mysqli_query($koneksi, "SELECT COUNT(*) as count FROM penilaian_kawasan WHERE id_indikator='$id'");
    $penilaianCount = mysqli_fetch_assoc($checkPenilaian)['count'];

    if ($penilaianCount > 0) {
        echo "<script>alert('Data indikator penilaian tidak dapat dihapus karena masih digunakan dalam penilaian kawasan.'); window.location.href='?halaman=indikator_penilaian';</script>";
    } else {
        $query = mysqli_query($koneksi, "DELETE FROM indikator_penilaian WHERE id_indikator='$id'");
        if ($query) {
            echo "<script>alert('Data Indikator Penilaian Berhasil Dihapus'); window.location.href='?halaman=indikator_penilaian';</script>";
        } else {
            echo "<script>alert('Gagal menghapus data: " . addslashes(mysqli_error($koneksi)) . "');</script>";
        }
    }
}
?>