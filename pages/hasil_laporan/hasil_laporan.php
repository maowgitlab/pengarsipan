<div class="pagetitle">
    <h1>Hasil Laporan Kawasan</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active">Hasil Laporan Kawasan</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Hasil Laporan Kawasan</h5>
                    <div class="d-flex justify-content-between my-3">
                        <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Hasil Laporan</button>
                        <?php endif; ?>
                        <form method="get" action="" class="d-flex align-items-center">
                            <input type="hidden" name="halaman" value="hasil_laporan">
                            <select name="id_periode" class="form-select me-2" style="width: 150px;">
                                <option value="all" <?= isset($_GET['id_periode']) && $_GET['id_periode'] == 'all' ? 'selected' : '' ?>>Semua Periode</option>
                                <?php 
                                $periode = mysqli_query($koneksi, "SELECT * FROM periode_penilaian ORDER BY tahun DESC, bulan DESC");
                                foreach ($periode as $p) : ?>
                                    <option value="<?= $p['id_periode']; ?>" <?= isset($_GET['id_periode']) && $_GET['id_periode'] == $p['id_periode'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars(date('F', mktime(0, 0, 0, $p['bulan'], 1)) . ' ' . $p['tahun']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                            <?php if (isset($_GET['id_periode']) && $_GET['id_periode'] != 'all') : ?>
                                <a href="report/cetak_hasil_laporan.php?id_periode=<?= urlencode($_GET['id_periode']); ?>" target="_blank" class="btn btn-sm btn-primary ms-2"><i class="bi bi-printer"></i> Cetak</a>
                            <?php else : ?>
                                <a href="report/cetak_hasil_laporan.php?id_periode=all" target="_blank" class="btn btn-sm btn-primary ms-2"><i class="bi bi-printer"></i></a>
                            <?php endif; ?>
                        </form>
                    </div>
                    <table class="table table-hover" id="tabelData">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Kawasan</th>
                                <th scope="col">Periode</th>
                                <th scope="col">Status Layak</th>
                                <th scope="col">Rekomendasi</th>
                                <th scope="col">Tanggal Dibuat</th>
                                <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                                    <th scope="col">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            $query = "SELECT hl.*, k.nama_kawasan, p.bulan, p.tahun 
                                      FROM hasil_laporan hl 
                                      JOIN kawasan k ON hl.id_kawasan = k.id_kawasan 
                                      JOIN periode_penilaian p ON hl.id_periode = p.id_periode";
                            if (isset($_GET['id_periode']) && $_GET['id_periode'] != 'all') {
                                $id_periode = mysqli_real_escape_string($koneksi, $_GET['id_periode']);
                                $query .= " WHERE hl.id_periode = '$id_periode'";
                            }
                            $query .= " ORDER BY hl.id_laporan DESC";
                            $data = mysqli_query($koneksi, $query);
                            if (!$data) {
                                echo "<tr><td colspan='7'>Error: " . htmlspecialchars(mysqli_error($koneksi)) . "</td></tr>";
                            }
                            foreach ($data as $row) : ?>
                                <tr>
                                    <th scope="row"><?= $no++; ?></th>
                                    <td><?= htmlspecialchars($row['nama_kawasan']); ?></td>
                                    <td><?= htmlspecialchars(date('F', mktime(0, 0, 0, $row['bulan'], 1)) . ' ' . $row['tahun']); ?></td>
                                    <td><?= htmlspecialchars($row['status_layak']); ?></td>
                                    <td><?= htmlspecialchars($row['rekomendasi']); ?></td>
                                    <td><?= htmlspecialchars($row['tanggal_dibuat']); ?></td>
                                    <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="editData('<?= $row['id_laporan']; ?>', '<?= $row['id_kawasan']; ?>', '<?= $row['id_periode']; ?>', '<?= $row['status_layak']; ?>', '<?= addslashes($row['rekomendasi']); ?>', '<?= $row['tanggal_dibuat']; ?>')" data-bs-toggle="modal" data-bs-target="#modalEdit"><i class="bi bi-pencil-square"></i></button>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="hapusData('<?= $row['id_laporan']; ?>')" data-bs-toggle="modal" data-bs-target="#modalHapus"><i class="bi bi-trash"></i></button>
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
                <h5 class="modal-title">Form Tambah Hasil Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post">
                    <input type="hidden" name="tambah" value="tambah">
                    <div class="col-12">
                        <label for="id_kawasan" class="form-label">Nama Kawasan</label>
                        <select class="form-select" id="id_kawasan" name="id_kawasan" required>
                            <option value="" disabled selected>Pilih Kawasan</option>
                            <?php 
                            $kawasan = mysqli_query($koneksi, "SELECT * FROM kawasan");
                            foreach ($kawasan as $k) : ?>
                                <option value="<?= $k['id_kawasan']; ?>"><?= htmlspecialchars($k['nama_kawasan']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="id_periode" class="form-label">Periode</label>
                        <select class="form-select" id="id_periode" name="id_periode" required>
                            <option value="" disabled selected>Pilih Periode</option>
                            <?php 
                            $periode = mysqli_query($koneksi, "SELECT * FROM periode_penilaian ORDER BY tahun DESC, bulan DESC");
                            foreach ($periode as $p) : ?>
                                <option value="<?= $p['id_periode']; ?>"><?= htmlspecialchars(date('F', mktime(0, 0, 0, $p['bulan'], 1)) . ' ' . $p['tahun']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="status_layak" class="form-label">Status Layak</label>
                        <select class="form-select" id="status_layak" name="status_layak" required>
                            <option value="Layak">Layak</option>
                            <option value="Tidak Layak">Tidak Layak</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="rekomendasi" class="form-label">Rekomendasi</label>
                        <textarea class="form-control" id="rekomendasi" name="rekomendasi" rows="4" required></textarea>
                    </div>
                    <div class="col-12">
                        <label for="tanggal_dibuat" class="form-label">Tanggal Dibuat</label>
                        <input type="date" class="form-control" id="tanggal_dibuat" name="tanggal_dibuat" required>
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
                <h5 class="modal-title">Form Edit Hasil Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post">
                    <input type="hidden" name="edit" value="edit">
                    <input type="hidden" name="idEdit" id="idEdit">
                    <div class="col-12">
                        <label for="id_kawasan_edit" class="form-label">Nama Kawasan</label>
                        <select class="form-select" id="id_kawasan_edit" name="id_kawasan_edit" required>
                            <option value="" disabled>Pilih Kawasan</option>
                            <?php 
                            $kawasan = mysqli_query($koneksi, "SELECT * FROM kawasan");
                            foreach ($kawasan as $k) : ?>
                                <option value="<?= $k['id_kawasan']; ?>"><?= htmlspecialchars($k['nama_kawasan']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="id_periode_edit" class="form-label">Periode</label>
                        <select class="form-select" id="id_periode_edit" name="id_periode_edit" required>
                            <option value="" disabled>Pilih Periode</option>
                            <?php 
                            $periode = mysqli_query($koneksi, "SELECT * FROM periode_penilaian ORDER BY tahun DESC, bulan DESC");
                            foreach ($periode as $p) : ?>
                                <option value="<?= $p['id_periode']; ?>"><?= htmlspecialchars(date('F', mktime(0, 0, 0, $p['bulan'], 1)) . ' ' . $p['tahun']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="status_layak_edit" class="form-label">Status Layak</label>
                        <select class="form-select" id="status_layak_edit" name="status_layak_edit" required>
                            <option value="Layak">Layak</option>
                            <option value="Tidak Layak">Tidak Layak</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="rekomendasi_edit" class="form-label">Rekomendasi</label>
                        <textarea class="form-control" id="rekomendasi_edit" name="rekomendasi_edit" rows="4" required></textarea>
                    </div>
                    <div class="col-12">
                        <label for="tanggal_dibuat_edit" class="form-label">Tanggal Dibuat</label>
                        <input type="date" class="form-control" id="tanggal_dibuat_edit" name="tanggal_dibuat_edit" required>
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
    function editData(id, id_kawasan, id_periode, status_layak, rekomendasi, tanggal_dibuat) {
        document.getElementById('idEdit').value = id;
        document.getElementById('id_kawasan_edit').value = id_kawasan;
        document.getElementById('id_periode_edit').value = id_periode;
        document.getElementById('status_layak_edit').value = status_layak;
        document.getElementById('rekomendasi_edit').value = rekomendasi;
        document.getElementById('tanggal_dibuat_edit').value = tanggal_dibuat;
    }

    function hapusData(id) {
        document.getElementById('idHapus').value = id;
    }
</script>

<?php
if (isset($_POST['tambah'])) {
    $id_kawasan = mysqli_real_escape_string($koneksi, $_POST['id_kawasan']);
    $id_periode = mysqli_real_escape_string($koneksi, $_POST['id_periode']);
    $status_layak = mysqli_real_escape_string($koneksi, $_POST['status_layak']);
    $rekomendasi = mysqli_real_escape_string($koneksi, $_POST['rekomendasi']);
    $tanggal_dibuat = mysqli_real_escape_string($koneksi, $_POST['tanggal_dibuat']);

    $query = mysqli_query($koneksi, "INSERT INTO hasil_laporan (id_kawasan, id_periode, status_layak, rekomendasi, tanggal_dibuat) 
                                    VALUES ('$id_kawasan', '$id_periode', '$status_layak', '$rekomendasi', '$tanggal_dibuat')");

    if ($query) {
        echo "<script>alert('Data Hasil Laporan Berhasil Ditambahkan'); window.location.href='?halaman=hasil_laporan';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data: " . addslashes(mysqli_error($koneksi)) . "');</script>";
    }
}

if (isset($_POST['edit'])) {
    $id = mysqli_real_escape_string($koneksi, $_POST['idEdit']);
    $id_kawasan = mysqli_real_escape_string($koneksi, $_POST['id_kawasan_edit']);
    $id_periode = mysqli_real_escape_string($koneksi, $_POST['id_periode_edit']);
    $status_layak = mysqli_real_escape_string($koneksi, $_POST['status_layak_edit']);
    $rekomendasi = mysqli_real_escape_string($koneksi, $_POST['rekomendasi_edit']);
    $tanggal_dibuat = mysqli_real_escape_string($koneksi, $_POST['tanggal_dibuat_edit']);

    $query = mysqli_query($koneksi, "UPDATE hasil_laporan SET id_kawasan='$id_kawasan', id_periode='$id_periode', 
                                    status_layak='$status_layak', rekomendasi='$rekomendasi', tanggal_dibuat='$tanggal_dibuat' 
                                    WHERE id_laporan='$id'");

    if ($query) {
        echo "<script>alert('Data Hasil Laporan Berhasil Diubah'); window.location.href='?halaman=hasil_laporan';</script>";
    } else {
        echo "<script>alert('Gagal mengubah data: " . addslashes(mysqli_error($koneksi)) . "');</script>";
    }
}

if (isset($_POST['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_POST['idHapus']);

    $query = mysqli_query($koneksi, "DELETE FROM hasil_laporan WHERE id_laporan='$id'");

    if ($query) {
        echo "<script>alert('Data Hasil Laporan Berhasil Dihapus'); window.location.href='?halaman=hasil_laporan';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data: " . addslashes(mysqli_error($koneksi)) . "');</script>";
    }
}
?>