<div class="pagetitle">
    <h1>Surat Gaji</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active">Surat Gaji</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Surat Gaji</h5>
                    <div class="d-flex justify-content-between my-3">
                        <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Surat</button>
                        <?php endif; ?>
                        <!-- Filter Form -->
                        <form method="get" action="report/cetak_surat_gaji.php" target="_blank" class="d-flex align-items-center">
                            <select name="filter_type" class="form-select me-2" style="width: 150px;" onchange="toggleFilterFields(this.value)">
                                <option value="monthly">Bulanan</option>
                                <option value="period">Periode</option>
                                <option value="yearly">Tahunan</option>
                            </select>
                            <div id="monthly_filter" class="me-2">
                                <select name="month" class="form-select" style="width: 120px;">
                                    <?php for ($m = 1; $m <= 12; $m++): ?>
                                        <option value="<?= sprintf('%02d', $m); ?>" <?= date('m') == $m ? 'selected' : ''; ?>>
                                            <?= date('F', mktime(0, 0, 0, $m, 1)); ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                                <select name="year_monthly" class="form-select ms-1" style="width: 100px;">
                                    <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                                        <option value="<?= $y; ?>" <?= date('Y') == $y ? 'selected' : ''; ?>><?= $y; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div id="period_filter" class="me-2" style="display: none;">
                                <input type="date" name="start_date" class="form-control" style="width: 150px;">
                                <input type="date" name="end_date" class="form-control ms-1" style="width: 150px;">
                            </div>
                            <div id="yearly_filter" class="me-2" style="display: none;">
                                <select name="year" class="form-select" style="width: 100px;">
                                    <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                                        <option value="<?= $y; ?>" <?= date('Y') == $y ? 'selected' : ''; ?>><?= $y; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-printer"></i> Cetak</button>
                        </form>
                    </div>
                    <table class="table table-hover" id="tabelData">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">No Surat</th>
                                <th scope="col">Jenis Surat</th>
                                <th scope="col">NIP</th>
                                <th scope="col">Bidang</th>
                                <th scope="col">Tanggal Surat</th>
                                <th scope="col">File</th>
                                <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                                    <th scope="col">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            $data = mysqli_query($koneksi, "SELECT 
                                                            sg.id, 
                                                            sg.no_surat,
                                                            s.id AS id_surat,
                                                            s.jenis_surat, 
                                                            g.id AS id_gaji,
                                                            g.nip, 
                                                            b.id AS id_bidang,
                                                            b.nama_bidang, 
                                                            sg.tanggal_surat,
                                                            sg.file
                                                        FROM tb_surat_gaji sg
                                                        LEFT JOIN tb_surat s ON sg.id_surat = s.id
                                                        LEFT JOIN tb_gaji g ON sg.id_gaji = g.id
                                                        LEFT JOIN tb_bidang b ON sg.id_bidang = b.id
                                                        ORDER BY sg.id DESC");
                            foreach ($data as $row) : ?>
                                <tr>
                                    <th scope="row"><?= $no++; ?></th>
                                    <td><?= $row['no_surat']; ?></td>
                                    <td><?= $row['jenis_surat']; ?></td>
                                    <td><?= $row['nip']; ?></td>
                                    <td><?= $row['nama_bidang']; ?></td>
                                    <td><?= $row['tanggal_surat']; ?></td>
                                    <td>
                                        <?php if (!empty($row['file'])) : ?>
                                            <a href="assets/file/gaji/<?= htmlspecialchars($row['file']); ?>" target="_blank" class="btn btn-sm btn-secondary">
                                                <i class="bi bi-file-earmark-pdf"></i>
                                            </a>
                                        <?php else : ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="editData('<?= $row['id']; ?>', '<?= $row['no_surat']; ?>', '<?= $row['id_surat']; ?>', '<?= $row['id_gaji']; ?>', '<?= $row['id_bidang']; ?>', '<?= $row['tanggal_surat']; ?>', '<?= htmlspecialchars($row['file'] ?? '', ENT_QUOTES); ?>')" data-bs-toggle="modal" data-bs-target="#modalEdit"><i class="bi bi-pencil-square"></i></button>
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

<!-- Modals (Tambah, Edit, Hapus) -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Tambah Surat Gaji</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="tambah" value="tambah">
                    <div class="col-12">
                        <label for="no_surat" class="form-label">No Surat</label>
                        <input type="text" class="form-control" id="no_surat" name="no_surat" readonly value="<?= $faker->numerify('##/GAJI/' . date('d') . '' . date('m') . '25'); ?>" required>
                    </div>
                    <div class="col-12">
                        <label for="id_surat" class="form-label">Jenis Surat</label>
                        <select class="form-select" id="id_surat" name="id_surat" required>
                            <option selected disabled value="">Pilih Surat</option>
                            <?php $data = mysqli_query($koneksi, "SELECT * FROM tb_surat");
                            foreach ($data as $row) : ?>
                                <option value="<?= $row['id']; ?>"><?= $row['jenis_surat']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="id_gaji" class="form-label">Nama Pegawai</label>
                        <select class="form-select" id="id_gaji" name="id_gaji" required>
                            <option selected disabled value="">Pilih Pegawai</option>
                            <?php $data = mysqli_query($koneksi, "SELECT tb_gaji.id AS id_gaji, tb_pegawai.nama_pegawai FROM tb_gaji JOIN tb_pegawai ON tb_gaji.nip = tb_pegawai.nip");
                            foreach ($data as $row) : ?>
                                <option value="<?= $row['id_gaji']; ?>"><?= $row['nama_pegawai']; ?></option>
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
                        <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
                        <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat" required>
                    </div>
                    <div class="col-12">
                        <label for="file" class="form-label">File Surat (PDF)</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".pdf" required>
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
                <h5 class="modal-title">Form Edit Surat Gaji</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="edit" value="edit">
                    <input type="hidden" name="idEdit" id="idEdit">
                    <div class="col-12">
                        <label for="no_surat_edit" class="form-label">No Surat</label>
                        <input type="text" class="form-control" id="no_surat_edit" name="no_surat_edit" readonly>
                    </div>
                    <div class="col-12">
                        <label for="id_surat_edit" class="form-label">Jenis Surat</label>
                        <select class="form-select" id="id_surat_edit" name="id_surat_edit" required>
                            <option selected disabled value="">Pilih Jenis Surat</option>
                            <?php $data = mysqli_query($koneksi, "SELECT * FROM tb_surat");
                            foreach ($data as $row) : ?>
                                <option value="<?= $row['id']; ?>"><?= $row['jenis_surat']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="id_gaji_edit" class="form-label">Nama Pegawai</label>
                        <select class="form-select" id="id_gaji_edit" name="id_gaji_edit" required>
                            <option selected disabled value="">Pilih Pegawai</option>
                            <?php $data = mysqli_query($koneksi, "SELECT tb_gaji.id AS id_gaji, tb_pegawai.nama_pegawai FROM tb_gaji JOIN tb_pegawai ON tb_gaji.nip = tb_pegawai.nip");
                            foreach ($data as $row) : ?>
                                <option value="<?= $row['id_gaji']; ?>"><?= $row['nama_pegawai']; ?></option>
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
                        <label for="tanggal_surat_edit" class="form-label">Tanggal Surat</label>
                        <input type="date" class="form-control" id="tanggal_surat_edit" name="tanggal_surat_edit" required>
                    </div>
                    <div class="col-12">
                        <label for="file_edit" class="form-label">File Surat (PDF)</label>
                        <input type="file" class="form-control" id="file_edit" name="file_edit" accept=".pdf">
                        <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah file.</small>
                        <div id="currentFileWrapper" class="mt-2 d-none">
                            <a href="#" id="currentFileLink" target="_blank" class="btn btn-sm btn-secondary">
                                <i class="bi bi-file-earmark-pdf"></i> Lihat File Saat Ini
                            </a>
                        </div>
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
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function setCurrentFileLink(linkElement, fileName) {
        linkElement.innerHTML = '';
        const icon = document.createElement('i');
        icon.className = 'bi bi-file-earmark-pdf';
        linkElement.appendChild(icon);
        linkElement.appendChild(document.createTextNode(' ' + fileName));
    }

    function editData(id, no_surat, id_surat, id_gaji, id_bidang, tanggal_surat, file) {
        document.getElementById('idEdit').value = id;
        document.getElementById('no_surat_edit').value = no_surat;
        document.getElementById('id_surat_edit').value = id_surat;
        document.getElementById('id_gaji_edit').value = id_gaji;
        document.getElementById('id_bidang_edit').value = id_bidang;
        document.getElementById('tanggal_surat_edit').value = tanggal_surat;

        const currentFileWrapper = document.getElementById('currentFileWrapper');
        const currentFileLink = document.getElementById('currentFileLink');

        if (file) {
            currentFileWrapper.classList.remove('d-none');
            currentFileLink.href = 'assets/file/gaji/' + file;
            setCurrentFileLink(currentFileLink, file);
        } else {
            currentFileWrapper.classList.add('d-none');
            currentFileLink.href = '#';
            setCurrentFileLink(currentFileLink, 'Lihat File Saat Ini');
        }
    }

    function hapusData(id) {
        document.getElementById('idHapus').value = id;
    }

    function toggleFilterFields(filterType) {
        document.getElementById('monthly_filter').style.display = filterType === 'monthly' ? 'flex' : 'none';
        document.getElementById('period_filter').style.display = filterType === 'period' ? 'flex' : 'none';
        document.getElementById('yearly_filter').style.display = filterType === 'yearly' ? 'flex' : 'none';
    }
</script>

<?php
if (isset($_POST['tambah'])) {
    $noSurat = mysqli_real_escape_string($koneksi, $_POST['no_surat']);
    $idSurat = (int) $_POST['id_surat'];
    $idGaji = (int) $_POST['id_gaji'];
    $idBidang = (int) $_POST['id_bidang'];
    $tanggalSurat = mysqli_real_escape_string($koneksi, $_POST['tanggal_surat']);

    if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        echo "<script>alert('File surat wajib diunggah.'); window.location.href='?halaman=surat_gaji';</script>";
        exit;
    }

    $allowedExtensions = ['pdf'];
    $originalName = $_FILES['file']['name'];
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

    if (!in_array($extension, $allowedExtensions, true)) {
        echo "<script>alert('File surat harus berformat PDF.'); window.location.href='?halaman=surat_gaji';</script>";
        exit;
    }

    $targetDir = 'assets/file/gaji/';
    if (!is_dir($targetDir) && !mkdir($targetDir, 0777, true)) {
        echo "<script>alert('Gagal membuat folder penyimpanan file surat.'); window.location.href='?halaman=surat_gaji';</script>";
        exit;
    }

    $newFileName = uniqid('surat_gaji_', true) . '.' . $extension;
    $targetPath = $targetDir . $newFileName;

    if (!move_uploaded_file($_FILES['file']['tmp_name'], $targetPath)) {
        echo "<script>alert('Gagal mengunggah file surat.'); window.location.href='?halaman=surat_gaji';</script>";
        exit;
    }

    $fileNameEscaped = mysqli_real_escape_string($koneksi, $newFileName);

    $query = mysqli_query($koneksi, "INSERT INTO tb_surat_gaji (no_surat, id_surat, id_gaji, id_bidang, tanggal_surat, file)
                                    VALUES ('$noSurat', '$idSurat', '$idGaji', '$idBidang', '$tanggalSurat', '$fileNameEscaped')");

    if ($query) {
        echo "<script>alert('Data Surat Gaji Berhasil Ditambahkan'); window.location.href='?halaman=surat_gaji';</script>";
    } else {
        if (file_exists($targetPath)) {
            unlink($targetPath);
        }
        echo "<script>alert('Gagal menambahkan data: " . addslashes(mysqli_error($koneksi)) . "'); window.location.href='?halaman=surat_gaji';</script>";
    }
}

if (isset($_POST['edit'])) {
    $id = (int) $_POST['idEdit'];
    $noSurat = mysqli_real_escape_string($koneksi, $_POST['no_surat_edit']);
    $idSurat = (int) $_POST['id_surat_edit'];
    $idGaji = (int) $_POST['id_gaji_edit'];
    $idBidang = (int) $_POST['id_bidang_edit'];
    $tanggalSurat = mysqli_real_escape_string($koneksi, $_POST['tanggal_surat_edit']);

    $currentFile = '';
    $fileQuery = mysqli_query($koneksi, "SELECT file FROM tb_surat_gaji WHERE id='$id'");
    if ($fileQuery) {
        $fileData = mysqli_fetch_assoc($fileQuery);
        $currentFile = $fileData['file'] ?? '';
    }

    $targetDir = 'assets/file/gaji/';
    $hasNewFile = isset($_FILES['file_edit']) && $_FILES['file_edit']['error'] === UPLOAD_ERR_OK && $_FILES['file_edit']['name'] !== '';
    $newFileName = $currentFile;
    $newFilePath = '';

    if ($hasNewFile) {
        $allowedExtensions = ['pdf'];
        $originalName = $_FILES['file_edit']['name'];
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions, true)) {
            echo "<script>alert('File surat harus berformat PDF.'); window.location.href='?halaman=surat_gaji';</script>";
            exit;
        }

        if (!is_dir($targetDir) && !mkdir($targetDir, 0777, true)) {
            echo "<script>alert('Gagal membuat folder penyimpanan file surat.'); window.location.href='?halaman=surat_gaji';</script>";
            exit;
        }

        $newFileName = uniqid('surat_gaji_', true) . '.' . $extension;
        $newFilePath = $targetDir . $newFileName;

        if (!move_uploaded_file($_FILES['file_edit']['tmp_name'], $newFilePath)) {
            echo "<script>alert('Gagal mengunggah file surat.'); window.location.href='?halaman=surat_gaji';</script>";
            exit;
        }
    }

    $fileNameEscaped = mysqli_real_escape_string($koneksi, $newFileName);

    $query = mysqli_query($koneksi, "UPDATE tb_surat_gaji SET no_surat='$noSurat', id_surat='$idSurat', id_gaji='$idGaji', id_bidang='$idBidang', tanggal_surat='$tanggalSurat', file='$fileNameEscaped' WHERE id='$id'");

    if ($query) {
        if ($hasNewFile && $currentFile && file_exists($targetDir . $currentFile)) {
            unlink($targetDir . $currentFile);
        }
        echo "<script>alert('Data Surat Gaji Berhasil Diubah'); window.location.href='?halaman=surat_gaji';</script>";
    } else {
        if ($hasNewFile && $newFilePath && file_exists($newFilePath)) {
            unlink($newFilePath);
        }
        echo "<script>alert('Gagal mengubah data: " . addslashes(mysqli_error($koneksi)) . "'); window.location.href='?halaman=surat_gaji';</script>";
    }
}

if (isset($_POST['hapus'])) {
    $id = (int) $_POST['idHapus'];

    $targetDir = 'assets/file/gaji/';
    $fileQuery = mysqli_query($koneksi, "SELECT file FROM tb_surat_gaji WHERE id='$id'");
    $fileName = '';
    if ($fileQuery) {
        $fileData = mysqli_fetch_assoc($fileQuery);
        $fileName = $fileData['file'] ?? '';
    }

    $query = mysqli_query($koneksi, "DELETE FROM tb_surat_gaji WHERE id='$id'");

    if ($query) {
        if ($fileName && file_exists($targetDir . $fileName)) {
            unlink($targetDir . $fileName);
        }
        echo "<script>alert('Data Surat Gaji Berhasil Dihapus'); window.location.href='?halaman=surat_gaji';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data: " . addslashes(mysqli_error($koneksi)) . "'); window.location.href='?halaman=surat_gaji';</script>";
    }
}
?>