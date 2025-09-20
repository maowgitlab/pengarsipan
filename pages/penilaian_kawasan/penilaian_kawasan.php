<div class="pagetitle">
    <h1>Penilaian Kawasan</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active">Penilaian Kawasan</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Penilaian Kawasan</h5>
                    <div class="d-flex justify-content-between my-3">
                        <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Penilaian</button>
                        <?php endif; ?>
                        <a href="report/cetak_penilaian_kawasan.php" target="_blank" class="btn btn-sm btn-primary"><i class="bi bi-printer"></i></a>
                    </div>
                    <table class="table table-hover" id="tabelData">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nama Kawasan</th>
                                <th scope="col">Indikator</th>
                                <th scope="col">Periode</th>
                                <th scope="col">Nilai</th>
                                <th scope="col">Keterangan</th>
                                <th scope="col">Tanggal Penilaian</th>
                                <th scope="col">Bukti</th>
                                <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                                    <th scope="col">Aksi</th>
                                <?php endif; ?>
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
                                echo "<tr><td colspan='9'>Error: " . htmlspecialchars(mysqli_error($koneksi)) . "</td></tr>";
                            }
                            foreach ($data as $row) : ?>
                                <tr>
                                    <th scope="row"><?= $no++; ?></th>
                                    <td><?= htmlspecialchars($row['nama_kawasan']); ?></td>
                                    <td><?= htmlspecialchars($row['nama_indikator']); ?></td>
                                    <td><?= htmlspecialchars(date('F', mktime(0, 0, 0, $row['bulan'], 1)) . ' ' . $row['tahun']); ?></td>
                                    <td><?= htmlspecialchars($row['nilai']); ?></td>
                                    <td><?= htmlspecialchars($row['keterangan']); ?></td>
                                    <td><?= htmlspecialchars($row['tanggal_penilaian']); ?></td>
                                    <td>
                                        <?php if ($row['bukti_file']) : ?>
                                            <a href="assets/file/bukti/<?= htmlspecialchars($row['bukti_file']); ?>" target="_blank">Lihat Bukti</a>
                                        <?php else : ?>
                                            Tidak ada
                                        <?php endif; ?>
                                    </td>
                                    <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="editData('<?= $row['id_penilaian']; ?>', '<?= $row['id_kawasan']; ?>', '<?= $row['id_indikator']; ?>', '<?= $row['id_periode']; ?>', '<?= $row['nilai']; ?>', '<?= addslashes($row['keterangan']); ?>', '<?= $row['tanggal_penilaian']; ?>', '<?= htmlspecialchars($row['bukti_file'] ?? ''); ?>')" data-bs-toggle="modal" data-bs-target="#modalEdit"><i class="bi bi-pencil-square"></i></button>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="hapusData('<?= $row['id_penilaian']; ?>')" data-bs-toggle="modal" data-bs-target="#modalHapus"><i class="bi bi-trash"></i></button>
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
                <h5 class="modal-title">Form Tambah Penilaian Kawasan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post" enctype="multipart/form-data">
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
                        <label for="id_indikator" class="form-label">Indikator</label>
                        <select class="form-select" id="id_indikator" name="id_indikator" required>
                            <option value="" disabled selected>Pilih Indikator</option>
                            <?php 
                            $indikator = mysqli_query($koneksi, "SELECT * FROM indikator_penilaian");
                            foreach ($indikator as $i) : ?>
                                <option value="<?= $i['id_indikator']; ?>"><?= htmlspecialchars($i['nama_indikator']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="id_periode" class="form-label">Periode</label>
                        <select class="form-select" id="id_periode" name="id_periode" required>
                            <option value="" disabled selected>Pilih Periode</option>
                            <?php 
                            $periode = mysqli_query($koneksi, "SELECT * FROM periode_penilaian");
                            foreach ($periode as $p) : ?>
                                <option value="<?= $p['id_periode']; ?>"><?= htmlspecialchars(date('F', mktime(0, 0, 0, $p['bulan'], 1)) . ' ' . $p['tahun']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="nilai" class="form-label">Nilai</label>
                        <input type="number" step="0.01" class="form-control" id="nilai" name="nilai" required>
                    </div>
                    <div class="col-12">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan" name="keterangan" rows="4"></textarea>
                    </div>
                    <div class="col-12">
                        <label for="tanggal_penilaian" class="form-label">Tanggal Penilaian</label>
                        <input type="date" class="form-control" id="tanggal_penilaian" name="tanggal_penilaian" required>
                    </div>
                    <div class="col-12">
                        <label for="bukti_file" class="form-label">Bukti Penilaian (PDF/Gambar)</label>
                        <input type="file" class="form-control" id="bukti_file" name="bukti_file" accept=".pdf,.jpg,.jpeg,.png" required>
                        <small class="form-text text-muted">Maksimum 5MB. Format: PDF, JPG, JPEG, PNG.</small>
                    </div>
                    <div classissé="text-center">
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
                <h5 class="modal-title">Form Edit Penilaian Kawasan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post" enctype="multipart/form-data">
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
                        <label for="id_indikator_edit" class="form-label">Indikator</label>
                        <select class="form-select" id="id_indikator_edit" name="id_indikator_edit" required>
                            <option value="" disabled>Pilih Indikator</option>
                            <?php 
                            $indikator = mysqli_query($koneksi, "SELECT * FROM indikator_penilaian");
                            foreach ($indikator as $i) : ?>
                                <option value="<?= $i['id_indikator']; ?>"><?= htmlspecialchars($i['nama_indikator']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="id_periode_edit" class="form-label">Periode</label>
                        <select class="form-select" id="id_periode_edit" name="id_periode_edit" required>
                            <option value="" disabled>Pilih Periode</option>
                            <?php 
                            $periode = mysqli_query($koneksi, "SELECT * FROM periode_penilaian");
                            foreach ($periode as $p) : ?>
                                <option value="<?= $p['id_periode']; ?>"><?= htmlspecialchars(date('F', mktime(0, 0, 0, $p['bulan'], 1)) . ' ' . $p['tahun']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="nilai_edit" class="form-label">Nilai</label>
                        <input type="number" step="0.01" class="form-control" id="nilai_edit" name="nilai_edit" required>
                    </div>
                    <div class="col-12">
                        <label for="keterangan_edit" class="form-label">Keterangan</label>
                        <textarea class="form-control" id="keterangan_edit" name="keterangan_edit" rows="4"></textarea>
                    </div>
                    <div class="col-12">
                        <label for="tanggal_penilaian_edit" class="form-label">Tanggal Penilaian</label>
                        <input type="date" class="form-control" id="tanggal_penilaian_edit" name="tanggal_penilaian_edit" required>
                    </div>
                    <div class="col-12">
                        <label for="bukti_file_edit" class="form-label">Bukti Penilaian (PDF/Gambar)</label>
                        <input type="file" class="form-control" id="bukti_file_edit" name="bukti_file_edit" accept=".pdf,.jpg,.jpeg,.png">
                        <small class="form-text text-muted">Maksimum 5MB. Format: PDF, JPG, JPEG, PNG. Kosongkan jika tidak ingin mengganti bukti.</small>
                        <input type="hidden" name="bukti_file_lama" id="bukti_file_lama">
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
    function editData(id, id_kawasan, id_indikator, id_periode, nilai, keterangan, tanggal_penilaian, bukti_file) {
        document.getElementById('idEdit').value = id;
        document.getElementById('id_kawasan_edit').value = id_kawasan;
        document.getElementById('id_indikator_edit').value = id_indikator;
        document.getElementById('id_periode_edit').value = id_periode;
        document.getElementById('nilai_edit').value = nilai;
        document.getElementById('keterangan_edit').value = keterangan;
        document.getElementById('tanggal_penilaian_edit').value = tanggal_penilaian;
        document.getElementById('bukti_file_lama').value = bukti_file;
    }

    function hapusData(id) {
        document.getElementById('idHapus').value = id;
    }
</script>

<?php
if (isset($_POST['tambah'])) {
    $id_kawasan = mysqli_real_escape_string($koneksi, $_POST['id_kawasan']);
    $id_indikator = mysqli_real_escape_string($koneksi, $_POST['id_indikator']);
    $id_periode = mysqli_real_escape_string($koneksi, $_POST['id_periode']);
    $nilai = mysqli_real_escape_string($koneksi, $_POST['nilai']);
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
    $tanggal_penilaian = mysqli_real_escape_string($koneksi, $_POST['tanggal_penilaian']);

    $bukti_file = '';
    if (isset($_FILES['bukti_file']) && $_FILES['bukti_file']['error'] == UPLOAD_ERR_OK) {
        $allowed_types = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
        $max_size = 5 * 1024 * 1024; // 5MB
        $file_type = $_FILES['bukti_file']['type'];
        $file_size = $_FILES['bukti_file']['size'];
        $file_tmp = $_FILES['bukti_file']['tmp_name'];
        $file_name = $_FILES['bukti_file']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $unique_file_name = uniqid('bukti_') . '.' . $file_ext;
        $upload_path = 'assets/file/bukti/' . $unique_file_name;

        if (in_array($file_type, $allowed_types) && $file_size <= $max_size) {
            if (move_uploaded_file($file_tmp, $upload_path)) {
                $bukti_file = $unique_file_name;
            } else {
                echo "<script>alert('Gagal mengunggah file bukti.');</script>";
                exit;
            }
        } else {
            echo "<script>alert('File tidak valid. Hanya PDF, JPG, JPEG, atau PNG dengan ukuran maksimum 5MB yang diperbolehkan.');</script>";
            exit;
        }
    } else {
        echo "<script>alert('Bukti file diperlukan.');</script>";
        exit;
    }

    $query = mysqli_query($koneksi, "INSERT INTO penilaian_kawasan (id_kawasan, id_indikator, id_periode, nilai, keterangan, tanggal_penilaian, bukti_file) 
                                    VALUES ('$id_kawasan', '$id_indikator', '$id_periode', '$nilai', '$keterangan', '$tanggal_penilaian', '$bukti_file')");

    if ($query) {
        echo "<script>alert('Data Penilaian Kawasan Berhasil Ditambahkan'); window.location.href='?halaman=penilaian_kawasan';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data: " . addslashes(mysqli_error($koneksi)) . "');</script>";
    }
}

if (isset($_POST['edit'])) {
    $id = mysqli_real_escape_string($koneksi, $_POST['idEdit']);
    $id_kawasan = mysqli_real_escape_string($koneksi, $_POST['id_kawasan_edit']);
    $id_indikator = mysqli_real_escape_string($koneksi, $_POST['id_indikator_edit']);
    $id_periode = mysqli_real_escape_string($koneksi, $_POST['id_periode_edit']);
    $nilai = mysqli_real_escape_string($koneksi, $_POST['nilai_edit']);
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan_edit']);
    $tanggal_penilaian = mysqli_real_escape_string($koneksi, $_POST['tanggal_penilaian_edit']);
    $bukti_file_lama = mysqli_real_escape_string($koneksi, $_POST['bukti_file_lama']);
    $bukti_file = $bukti_file_lama;

    if (isset($_FILES['bukti_file_edit']) && $_FILES['bukti_file_edit']['error'] == UPLOAD_ERR_OK) {
        $allowed_types = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
        $max_size = 5 * 1024 * 1024; // 5MB
        $file_type = $_FILES['bukti_file_edit']['type'];
        $file_size = $_FILES['bukti_file_edit']['size'];
        $file_tmp = $_FILES['bukti_file_edit']['tmp_name'];
        $file_name = $_FILES['bukti_file_edit']['name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $unique_file_name = uniqid('bukti_') . '.' . $file_ext;
        $upload_path = 'assets/file/bukti/' . $unique_file_name;

        if (in_array($file_type, $allowed_types) && $file_size <= $max_size) {
            if (move_uploaded_file($file_tmp, $upload_path)) {
                // Delete old file if exists
                if ($bukti_file_lama && file_exists('assets/file/bukti/' . $bukti_file_lama)) {
                    unlink('assets/file/bukti/' . $bukti_file_lama);
                }
                $bukti_file = $unique_file_name;
            } else {
                echo "<script>alert('Gagal mengunggah file bukti baru.');</script>";
                exit;
            }
        } else {
            echo "<script>alert('File tidak valid. Hanya PDF, JPG, JPEG, atau PNG dengan ukuran maksimum 5MB yang diperbolehkan.');</script>";
            exit;
        }
    }

    $query = mysqli_query($koneksi, "UPDATE penilaian_kawasan SET id_kawasan='$id_kawasan', id_indikator='$id_indikator', 
                                    id_periode='$id_periode', nilai='$nilai', keterangan='$keterangan', tanggal_penilaian='$tanggal_penilaian', 
                                    bukti_file='$bukti_file' WHERE id_penilaian='$id'");

    if ($query) {
        echo "<script>alert('Data Penilaian Kawasan Berhasil Diubah'); window.location.href='?halaman=penilaian_kawasan';</script>";
    } else {
        echo "<script>alert('Gagal mengubah data: " . addslashes(mysqli_error($koneksi)) . "');</script>";
    }
}

if (isset($_POST['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_POST['idHapus']);
    
    // Get the file to delete
    $query_file = mysqli_query($koneksi, "SELECT bukti_file FROM penilaian_kawasan WHERE id_penilaian='$id'");
    $row_file = mysqli_fetch_assoc($query_file);
    $bukti_file = $row_file['bukti_file'];

    $query = mysqli_query($koneksi, "DELETE FROM penilaian_kawasan WHERE id_penilaian='$id'");

    if ($query) {
        // Delete file if exists
        if ($bukti_file && file_exists('assets/file/bukti/' . $bukti_file)) {
            unlink('assets/file/bukti/' . $bukti_file);
        }
        echo "<script>alert('Data Penilaian Kawasan Berhasil Dihapus'); window.location.href='?halaman=penilaian_kawasan';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data: " . addslashes(mysqli_error($koneksi)) . "');</script>";
    }
}
?>