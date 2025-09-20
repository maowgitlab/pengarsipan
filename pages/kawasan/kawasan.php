<div class="pagetitle">
    <h1>Kawasan</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active">Kawasan</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Kawasan</h5>
                    <div class="d-flex justify-content-between my-3">
                        <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Kawasan</button>
                        <?php endif; ?>
                        <a href="report/cetak_kawasan.php" target="_blank" class="btn btn-sm btn-primary"><i class="bi bi-printer"></i></a>
                    </div>
                    <table class="table table-hover" id="tabelData">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Kawasan</th>
                                <th scope="col">Kecamatan</th>
                                <th scope="col">Luas (Ha)</th>
                                <th scope="col">Jumlah Penduduk</th>
                                <th scope="col">Status Layak</th>
                                <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                                    <th scope="col">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            $data = mysqli_query($koneksi, "SELECT * FROM kawasan ORDER BY id_kawasan DESC");
                            if (!$data) {
                                echo "<tr><td colspan='7'>Error: " . htmlspecialchars(mysqli_error($koneksi)) . "</td></tr>";
                            }
                            foreach ($data as $row) : ?>
                                <tr>
                                    <th scope="row"><?= $no++; ?></th>
                                    <td><?= htmlspecialchars($row['nama_kawasan']); ?></td>
                                    <td><?= htmlspecialchars($row['kecamatan']); ?></td>
                                    <td><?= htmlspecialchars($row['luas_ha']); ?></td>
                                    <td><?= htmlspecialchars($row['jumlah_penduduk']); ?></td>
                                    <td><?= htmlspecialchars($row['status_layak']); ?></td>
                                    <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="editData('<?= $row['id_kawasan']; ?>', '<?= addslashes($row['nama_kawasan']); ?>', '<?= addslashes($row['kecamatan']); ?>', '<?= $row['luas_ha']; ?>', '<?= $row['jumlah_penduduk']; ?>', '<?= $row['status_layak']; ?>')" data-bs-toggle="modal" data-bs-target="#modalEdit"><i class="bi bi-pencil-square"></i></button>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="hapusData('<?= $row['id_kawasan']; ?>')" data-bs-toggle="modal" data-bs-target="#modalHapus"><i class="bi bi-trash"></i></button>
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
                <h5 class="modal-title">Form Tambah Kawasan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post">
                    <input type="hidden" name="tambah" value="tambah">
                    <div class="col-12">
                        <label for="nama_kawasan" class="form-label">Nama Kawasan</label>
                        <input type="text" class="form-control" id="nama_kawasan" name="nama_kawasan" required>
                    </div>
                    <div class="col-12">
                        <label for="kecamatan" class="form-label">Kecamatan</label>
                        <input type="text" class="form-control" id="kecamatan" name="kecamatan" required>
                    </div>
                    <div class="col-12">
                        <label for="luas_ha" class="form-label">Luas (Ha)</label>
                        <input type="number" step="0.01" class="form-control" id="luas_ha" name="luas_ha" required>
                    </div>
                    <div class="col-12">
                        <label for="jumlah_penduduk" class="form-label">Jumlah Penduduk</label>
                        <input type="number" class="form-control" id="jumlah_penduduk" name="jumlah_penduduk" required>
                    </div>
                    <div class="col-12">
                        <label for="status_layak" class="form-label">Status Layak</label>
                        <select class="form-select" id="status_layak" name="status_layak" required>
                            <option value="Layak">Layak</option>
                            <option value="Tidak Layak">Tidak Layak</option>
                        </select>
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
                <h5 class="modal-title">Form Edit Kawasan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post">
                    <input type="hidden" name="edit" value="edit">
                    <input type="hidden" name="idEdit" id="idEdit">
                    <div class="col-12">
                        <label for="nama_kawasan_edit" class="form-label">Nama Kawasan</label>
                        <input type="text" class="form-control" id="nama_kawasan_edit" name="nama_kawasan_edit" required>
                    </div>
                    <div class="col-12">
                        <label for="kecamatan_edit" class="form-label">Kecamatan</label>
                        <input type="text" class="form-control" id="kecamatan_edit" name="kecamatan_edit" required>
                    </div>
                    <div class="col-12">
                        <label for="luas_ha_edit" class="form-label">Luas (Ha)</label>
                        <input type="number" step="0.01" class="form-control" id="luas_ha_edit" name="luas_ha_edit" required>
                    </div>
                    <div class="col-12">
                        <label for="jumlah_penduduk_edit" class="form-label">Jumlah Penduduk</label>
                        <input type="number" class="form-control" id="jumlah_penduduk_edit" name="jumlah_penduduk_edit" required>
                    </div>
                    <div class="col-12">
                        <label for="status_layak_edit" class="form-label">Status Layak</label>
                        <select class="form-select" id="status_layak_edit" name="status_layak_edit" required>
                            <option value="Layak">Layak</option>
                            <option value="Tidak Layak">Tidak Layak</option>
                        </select>
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
    function editData(id, nama_kawasan, kecamatan, luas_ha, jumlah_penduduk, status_layak) {
        document.getElementById('idEdit').value = id;
        document.getElementById('nama_kawasan_edit').value = nama_kawasan;
        document.getElementById('kecamatan_edit').value = kecamatan;
        document.getElementById('luas_ha_edit').value = luas_ha;
        document.getElementById('jumlah_penduduk_edit').value = jumlah_penduduk;
        document.getElementById('status_layak_edit').value = status_layak;
    }

    function hapusData(id) {
        document.getElementById('idHapus').value = id;
    }
</script>

<?php
if (isset($_POST['tambah'])) {
    $nama_kawasan = mysqli_real_escape_string($koneksi, $_POST['nama_kawasan']);
    $kecamatan = mysqli_real_escape_string($koneksi, $_POST['kecamatan']);
    $luas_ha = mysqli_real_escape_string($koneksi, $_POST['luas_ha']);
    $jumlah_penduduk = mysqli_real_escape_string($koneksi, $_POST['jumlah_penduduk']);
    $status_layak = mysqli_real_escape_string($koneksi, $_POST['status_layak']);

    $query = mysqli_query($koneksi, "INSERT INTO kawasan (nama_kawasan, kecamatan, luas_ha, jumlah_penduduk, status_layak) 
                                    VALUES ('$nama_kawasan', '$kecamatan', '$luas_ha', '$jumlah_penduduk', '$status_layak')");

    if ($query) {
        echo "<script>alert('Data Kawasan Berhasil Ditambahkan'); window.location.href='?halaman=kawasan';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data: " . addslashes(mysqli_error($koneksi)) . "');</script>";
    }
}

if (isset($_POST['edit'])) {
    $id = mysqli_real_escape_string($koneksi, $_POST['idEdit']);
    $nama_kawasan = mysqli_real_escape_string($koneksi, $_POST['nama_kawasan_edit']);
    $kecamatan = mysqli_real_escape_string($koneksi, $_POST['kecamatan_edit']);
    $luas_ha = mysqli_real_escape_string($koneksi, $_POST['luas_ha_edit']);
    $jumlah_penduduk = mysqli_real_escape_string($koneksi, $_POST['jumlah_penduduk_edit']);
    $status_layak = mysqli_real_escape_string($koneksi, $_POST['status_layak_edit']);

    $query = mysqli_query($koneksi, "UPDATE kawasan SET nama_kawasan='$nama_kawasan', kecamatan='$kecamatan', 
                                    luas_ha='$luas_ha', jumlah_penduduk='$jumlah_penduduk', status_layak='$status_layak' 
                                    WHERE id_kawasan='$id'");

    if ($query) {
        echo "<script>alert('Data Kawasan Berhasil Diubah'); window.location.href='?halaman=kawasan';</script>";
    } else {
        echo "<script>alert('Gagal mengubah data: " . addslashes(mysqli_error($koneksi)) . "');</script>";
    }
}

if (isset($_POST['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_POST['idHapus']);

    // Check if the kawasan has related records in penilaian_kawasan or hasil_laporan
    $checkPenilaian = mysqli_query($koneksi, "SELECT COUNT(*) as count FROM penilaian_kawasan WHERE id_kawasan='$id'");
    $checkLaporan = mysqli_query($koneksi, "SELECT COUNT(*) as count FROM hasil_laporan WHERE id_kawasan='$id'");
    
    $penilaianCount = mysqli_fetch_assoc($checkPenilaian)['count'];
    $laporanCount = mysqli_fetch_assoc($checkLaporan)['count'];

    if ($penilaianCount > 0 || $laporanCount > 0) {
        echo "<script>alert('Data kawasan tidak dapat dihapus karena masih memiliki penilaian atau laporan terkait.'); window.location.href='?halaman=kawasan';</script>";
    } else {
        $query = mysqli_query($koneksi, "DELETE FROM kawasan WHERE id_kawasan='$id'");
        if ($query) {
            echo "<script>alert('Data Kawasan Berhasil Dihapus'); window.location.href='?halaman=kawasan';</script>";
        } else {
            echo "<script>alert('Gagal menghapus data: " . addslashes(mysqli_error($koneksi)) . "');</script>";
        }
    }
}
?>