<div class="pagetitle">
    <h1>Periode Penilaian</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active">Periode Penilaian</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Periode Penilaian</h5>
                    <div class="d-flex justify-content-between my-3">
                        <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Periode</button>
                        <?php endif; ?>
                    </div>
                    <table class="table table-hover" id="tabelData">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Bulan</th>
                                <th scope="col">Tahun</th>
                                <th scope="col">Keterangan</th>
                                <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                                    <th scope="col">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            $data = mysqli_query($koneksi, "SELECT * FROM periode_penilaian ORDER BY id_periode DESC");
                            if (!$data) {
                                echo "<tr><td colspan='5'>Error: " . htmlspecialchars(mysqli_error($koneksi)) . "</td></tr>";
                            }
                            foreach ($data as $row) : ?>
                                <tr>
                                    <th scope="row"><?= $no++; ?></th>
                                    <td><?= htmlspecialchars(date('F', mktime(0, 0, 0, $row['bulan'], 1))); ?></td>
                                    <td><?= htmlspecialchars($row['tahun']); ?></td>
                                    <td><?= htmlspecialchars($row['keterangan']); ?></td>
                                    <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="editData('<?= $row['id_periode']; ?>', '<?= $row['bulan']; ?>', '<?= $row['tahun']; ?>', '<?= addslashes($row['keterangan']); ?>')" data-bs-toggle="modal" data-bs-target="#modalEdit"><i class="bi bi-pencil-square"></i></button>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="hapusData('<?= $row['id_periode']; ?>')" data-bs-toggle="modal" data-bs-target="#modalHapus"><i class="bi bi-trash"></i></button>
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
                <h5 class="modal-title">Form Tambah Periode Penilaian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post">
                    <input type="hidden" name="tambah" value="tambah">
                    <div class="col-12">
                        <label for="bulan" class="form-label">Bulan</label>
                        <select class="form-select" id="bulan" name="bulan" required>
                            <?php for ($m = 1; $m <= 12; $m++) : ?>
                                <option value="<?= $m; ?>"><?= date('F', mktime(0, 0, 0, $m, 1)); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="tahun" class="form-label">Tahun</label>
                        <input type="number" class="form-control" id="tahun" name="tahun" required>
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
                <h5 class="modal-title">Form Edit Periode Penilaian</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post">
                    <input type="hidden" name="edit" value="edit">
                    <input type="hidden" name="idEdit" id="idEdit">
                    <div class="col-12">
                        <label for="bulan_edit" class="form-label">Bulan</label>
                        <select class="form-select" id="bulan_edit" name="bulan_edit" required>
                            <?php for ($m = 1; $m <= 12; $m++) : ?>
                                <option value="<?= $m; ?>"><?= date('F', mktime(0, 0, 0, $m, 1)); ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="tahun_edit" class="form-label">Tahun</label>
                        <input type="number" class="form-control" id="tahun_edit" name="tahun_edit" required>
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
    function editData(id, bulan, tahun, keterangan) {
        document.getElementById('idEdit').value = id;
        document.getElementById('bulan_edit').value = bulan;
        document.getElementById('tahun_edit').value = tahun;
        document.getElementById('keterangan_edit').value = keterangan;
    }

    function hapusData(id) {
        document.getElementById('idHapus').value = id;
    }
</script>

<?php
if (isset($_POST['tambah'])) {
    $bulan = mysqli_real_escape_string($koneksi, $_POST['bulan']);
    $tahun = mysqli_real_escape_string($koneksi, $_POST['tahun']);
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);

    $query = mysqli_query($koneksi, "INSERT INTO periode_penilaian (bulan, tahun, keterangan) 
                                    VALUES ('$bulan', '$tahun', '$keterangan')");

    if ($query) {
        echo "<script>alert('Data Periode Penilaian Berhasil Ditambahkan'); window.location.href='?halaman=periode_penilaian';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data: " . addslashes(mysqli_error($koneksi)) . "');</script>";
    }
}

if (isset($_POST['edit'])) {
    $id = mysqli_real_escape_string($koneksi, $_POST['idEdit']);
    $bulan = mysqli_real_escape_string($koneksi, $_POST['bulan_edit']);
    $tahun = mysqli_real_escape_string($koneksi, $_POST['tahun_edit']);
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan_edit']);

    $query = mysqli_query($koneksi, "UPDATE periode_penilaian SET bulan='$bulan', tahun='$tahun', keterangan='$keterangan' 
                                    WHERE id_periode='$id'");

    if ($query) {
        echo "<script>alert('Data Periode Penilaian Berhasil Diubah'); window.location.href='?halaman=periode_penilaian';</script>";
    } else {
        echo "<script>alert('Gagal mengubah data: " . addslashes(mysqli_error($koneksi)) . "');</script>";
    }
}

if (isset($_POST['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_POST['idHapus']);

    // Check if the periode_penilaian has related records in penilaian_kawasan or hasil_laporan
    $checkPenilaian = mysqli_query($koneksi, "SELECT COUNT(*) as count FROM penilaian_kawasan WHERE id_periode='$id'");
    $checkLaporan = mysqli_query($koneksi, "SELECT COUNT(*) as count FROM hasil_laporan WHERE id_periode='$id'");
    
    $penilaianCount = mysqli_fetch_assoc($checkPenilaian)['count'];
    $laporanCount = mysqli_fetch_assoc($checkLaporan)['count'];

    if ($penilaianCount > 0 || $laporanCount > 0) {
        echo "<script>alert('Data periode penilaian tidak dapat dihapus karena masih digunakan dalam penilaian kawasan atau laporan.'); window.location.href='?halaman=periode_penilaian';</script>";
    } else {
        $query = mysqli_query($koneksi, "DELETE FROM periode_penilaian WHERE id_periode='$id'");
        if ($query) {
            echo "<script>alert('Data Periode Penilaian Berhasil Dihapus'); window.location.href='?halaman=periode_penilaian';</script>";
        } else {
            echo "<script>alert('Gagal menghapus data: " . addslashes(mysqli_error($koneksi)) . "');</script>";
        }
    }
}
?>