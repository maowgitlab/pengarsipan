<div class="pagetitle">
    <h1>Surat Keluar</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active">Surat Keluar</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Surat Keluar</h5>
                    <div class="d-flex justify-content-between my-3">
                        <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Surat</button>
                        <?php endif; ?>
                        <!-- Filter Form -->
                        <form method="get" action="report/cetak_surat_keluar.php" target="_blank" class="d-flex align-items-center" id="filterForm">
                            <select name="filter_type" class="form-select me-2" style="width: 150px;" onchange="toggleFilterFields(this.value)">
                                <option value="all" <?= isset($_GET['filter_type']) && $_GET['filter_type'] === 'all' ? 'selected' : ''; ?>>Semua</option>
                                <option value="monthly" <?= isset($_GET['filter_type']) && $_GET['filter_type'] === 'monthly' ? 'selected' : ''; ?>>Bulanan</option>
                                <option value="period" <?= isset($_GET['filter_type']) && $_GET['filter_type'] === 'period' ? 'selected' : ''; ?>>Periode</option>
                                <option value="yearly" <?= isset($_GET['filter_type']) && $_GET['filter_type'] === 'yearly' ? 'selected' : ''; ?>>Tahunan</option>
                            </select>
                            <select name="letter_type" class="form-select me-2" style="width: 150px;" onchange="applyLetterTypeFilter(this)">
                                <option value="all" <?= isset($_GET['letter_type']) && $_GET['letter_type'] === 'all' ? 'selected' : ''; ?>>Semua Surat</option>
                                <option value="surat_keluar" <?= isset($_GET['letter_type']) && $_GET['letter_type'] === 'surat_keluar' ? 'selected' : ''; ?>>Surat Keluar</option>
                                <option value="surat_undangan" <?= isset($_GET['letter_type']) && $_GET['letter_type'] === 'surat_undangan' ? 'selected' : ''; ?>>Surat Undangan</option>
                            </select>
                            <div id="monthly_filter" class="me-2" style="display: <?= isset($_GET['filter_type']) && $_GET['filter_type'] === 'monthly' ? 'flex' : 'none'; ?>;">
                                <select name="month" class="form-select" style="width: 120px;">
                                    <?php for ($m = 1; $m <= 12; $m++): ?>
                                        <option value="<?= sprintf('%02d', $m); ?>" <?= isset($_GET['month']) && $_GET['month'] == sprintf('%02d', $m) ? 'selected' : (date('m') == $m ? 'selected' : ''); ?>>
                                            <?= date('F', mktime(0, 0, 0, $m, 1)); ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                                <select name="year_monthly" class="form-select ms-1" style="width: 100px;">
                                    <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                                        <option value="<?= $y; ?>" <?= isset($_GET['year_monthly']) && $_GET['year_monthly'] == $y ? 'selected' : (date('Y') == $y ? 'selected' : ''); ?>><?= $y; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div id="period_filter" class="me-2" style="display: <?= isset($_GET['filter_type']) && $_GET['filter_type'] === 'period' ? 'flex' : 'none'; ?>;">
                                <input type="date" name="start_date" class="form-control" style="width: 150px;" value="<?= isset($_GET['start_date']) ? htmlspecialchars($_GET['start_date']) : ''; ?>">
                                <input type="date" name="end_date" class="form-control ms-1" style="width: 150px;" value="<?= isset($_GET['end_date']) ? htmlspecialchars($_GET['end_date']) : ''; ?>">
                            </div>
                            <div id="yearly_filter" class="me-2" style="display: <?= isset($_GET['filter_type']) && $_GET['filter_type'] === 'yearly' ? 'flex' : 'none'; ?>;">
                                <select name="year" class="form-select" style="width: 100px;">
                                    <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                                        <option value="<?= $y; ?>" <?= isset($_GET['year']) && $_GET['year'] == $y ? 'selected' : (date('Y') == $y ? 'selected' : ''); ?>><?= $y; ?></option>
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
                                <th scope="col">Tanggal Kirim</th>
                                <th scope="col">Penerima</th>
                                <th scope="col">Instansi</th>
                                <th scope="col">Perihal</th>
                                <th scope="col">File</th>
                                <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                                    <th scope="col">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            $letter_type = isset($_GET['letter_type']) ? mysqli_real_escape_string($koneksi, $_GET['letter_type']) : 'all';
                            $where_clause = '';
                            if ($letter_type === 'surat_keluar') {
                                $where_clause = "WHERE tb_surat.jenis_surat = 'surat_keluar'";
                            } elseif ($letter_type === 'surat_undangan') {
                                $where_clause = "WHERE tb_surat.jenis_surat = 'surat_undangan'";
                            }
                            $query = "SELECT tb_surat_keluar.*, tb_surat.jenis_surat 
                                      FROM tb_surat_keluar 
                                      JOIN tb_surat ON tb_surat_keluar.id_surat = tb_surat.id 
                                      $where_clause 
                                      ORDER BY tb_surat_keluar.id DESC";
                            $data = mysqli_query($koneksi, $query);
                            if (!$data) {
                                echo "<tr><td colspan='9'>Error: " . htmlspecialchars(mysqli_error($koneksi)) . "</td></tr>";
                            }
                            foreach ($data as $row) : ?>
                                <tr>
                                    <th scope="row"><?= $no++; ?></th>
                                    <td><?= htmlspecialchars($row['no_surat']); ?></td>
                                    <td><?= htmlspecialchars($row['jenis_surat']); ?></td>
                                    <td><?= htmlspecialchars($row['tanggal_kirim']); ?></td>
                                    <td><?= htmlspecialchars($row['penerima']); ?></td>
                                    <td><?= htmlspecialchars($row['instansi'] ?: '-'); ?></td>
                                    <td><?= htmlspecialchars($row['perihal']); ?></td>
                                    <td><a href="assets/file/keluar/<?= htmlspecialchars($row['file']); ?>" target="_blank" class="btn btn-sm btn-primary"><i class="bi bi-file-earmark-pdf"></i></a></td>
                                    <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="editData('<?= $row['id']; ?>', '<?= $row['id_surat']; ?>', '<?= addslashes($row['no_surat']); ?>', '<?= $row['tanggal_kirim']; ?>', '<?= addslashes($row['penerima']); ?>', '<?= addslashes($row['instansi']); ?>', '<?= addslashes($row['perihal']); ?>')" data-bs-toggle="modal" data-bs-target="#modalEdit"><i class="bi bi-pencil-square"></i></button>
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

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Tambah Surat Keluar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="tambah" value="tambah">
                    <div class="col-12">
                        <label for="no_surat" class="form-label">No Surat</label>
                        <input type="text" class="form-control" id="no_surat" name="no_surat" readonly value="<?= $faker->numerify('##/KLR/' . date('d') . date('m') . '25'); ?>" required>
                    </div>
                    <div class="col-12">
                        <label for="id_surat" class="form-label">Jenis Surat</label>
                        <select class="form-select" id="id_surat" name="id_surat" required onchange="toggleInstansiField(this)">
                            <option selected disabled value="">Pilih Jenis Surat</option>
                            <?php 
                            $data = mysqli_query($koneksi, "SELECT * FROM tb_surat WHERE jenis_surat IN ('surat_keluar', 'surat_undangan')");
                            if (!$data) {
                                echo "<option value=''>Error: " . htmlspecialchars(mysqli_error($koneksi)) . "</option>";
                            }
                            foreach ($data as $row) : ?>
                                <option value="<?= $row['id']; ?>" data-jenis="<?= $row['jenis_surat']; ?>"><?= $row['jenis_surat']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12" id="instansi_field" style="display: none;">
                        <label for="instansi" class="form-label">Instansi Tujuan</label>
                        <input type="text" class="form-control" id="instansi" name="instansi">
                    </div>
                    <div class="col-12">
                        <label for="tanggal_kirim" class="form-label">Tanggal Kirim</label>
                        <input type="date" class="form-control" id="tanggal_kirim" name="tanggal_kirim" required>
                    </div>
                    <div class="col-12">
                        <label for="penerima" class="form-label">Penerima</label>
                        <input type="text" class="form-control" id="penerima" name="penerima" required>
                    </div>
                    <div class="col-12">
                        <label for="perihal" class="form-label">Perihal</label>
                        <input type="text" class="form-control" id="perihal" name="perihal" required>
                    </div>
                    <div class="col-12">
                        <label for="file" class="form-label">File</label>
                        <input type="file" class="form-control" id="file" name="file" accept=".pdf" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="reset" class="btn btn-secondary" onclick="resetForm()">Reset</button>
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
                <h5 class="modal-title">Form Edit Surat Keluar</h5>
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
                        <select class="form-select" id="id_surat_edit" name="id_surat_edit" required onchange="toggleInstansiFieldEdit(this)">
                            <option selected disabled value="">Pilih Jenis Surat</option>
                            <?php 
                            $data = mysqli_query($koneksi, "SELECT * FROM tb_surat WHERE jenis_surat IN ('surat_keluar', 'surat_undangan')");
                            if (!$data) {
                                echo "<option value=''>Error: " . htmlspecialchars(mysqli_error($koneksi)) . "</option>";
                            }
                            foreach ($data as $row) : ?>
                                <option value="<?= $row['id']; ?>" data-jenis="<?= $row['jenis_surat']; ?>"><?= $row['jenis_surat']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12" id="instansi_field_edit" style="display: none;">
                        <label for="instansi_edit" class="form-label">Instansi Tujuan</label>
                        <input type="text" class="form-control" id="instansi_edit" name="instansi_edit">
                    </div>
                    <div class="col-12">
                        <label for="tanggal_kirim_edit" class="form-label">Tanggal Kirim</label>
                        <input type="date" class="form-control" id="tanggal_kirim_edit" name="tanggal_kirim_edit" required>
                    </div>
                    <div class="col-12">
                        <label for="penerima_edit" class="form-label">Penerima</label>
                        <input type="text" class="form-control" id="penerima_edit" name="penerima_edit" required>
                    </div>
                    <div class="col-12">
                        <label for="perihal_edit" class="form-label">Perihal</label>
                        <input type="text" class="form-control" id="perihal_edit" name="perihal_edit" required>
                    </div>
                    <div class="col-12">
                        <label for="file_edit" class="form-label">File</label>
                        <input type="file" class="form-control" id="file_edit" name="file_edit" accept=".pdf">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <button type="reset" class="btn btn-secondary" onclick="resetEditForm()">Reset</button>
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
    function editData(id, id_surat, no_surat, tanggal_kirim, penerima, instansi, perihal) {
        document.getElementById('idEdit').value = id;
        document.getElementById('id_surat_edit').value = id_surat;
        document.getElementById('no_surat_edit').value = no_surat;
        document.getElementById('tanggal_kirim_edit').value = tanggal_kirim;
        document.getElementById('penerima_edit').value = penerima;
        document.getElementById('instansi_edit').value = instansi || '';
        document.getElementById('perihal_edit').value = perihal;

        const select = document.getElementById('id_surat_edit');
        const selectedOption = select.options[select.selectedIndex];
        const jenisSurat = selectedOption ? selectedOption.getAttribute('data-jenis') : '';
        document.getElementById('instansi_field_edit').style.display = jenisSurat === 'surat_undangan' ? 'block' : 'none';
        document.getElementById('instansi_edit').required = jenisSurat === 'surat_undangan';
    }

    function hapusData(id) {
        document.getElementById('idHapus').value = id;
    }

    function toggleFilterFields(filterType) {
        document.getElementById('monthly_filter').style.display = filterType === 'monthly' ? 'flex' : 'none';
        document.getElementById('period_filter').style.display = filterType === 'period' ? 'flex' : 'none';
        document.getElementById('yearly_filter').style.display = filterType === 'yearly' ? 'flex' : 'none';
    }

    function applyLetterTypeFilter(select) {
        const letterType = select.value;
        const url = new URL(window.location.href);
        url.searchParams.set('letter_type', letterType);
        window.location.href = url.toString();
    }

    function toggleInstansiField(select) {
        const instansiField = document.getElementById('instansi_field');
        const selectedOption = select.options[select.selectedIndex];
        const jenisSurat = selectedOption ? selectedOption.getAttribute('data-jenis') : '';
        instansiField.style.display = jenisSurat === 'surat_undangan' ? 'block' : 'none';
        document.getElementById('instansi').required = jenisSurat === 'surat_undangan';
        const noSuratInput = document.getElementById('no_surat');
        noSuratInput.value = `<?php echo $faker->numerify('##/'); ?>${jenisSurat === 'surat_undangan' ? 'UDG' : 'KLR'}/<?php echo date('d') . date('m') . '25'; ?>`;
    }

    function toggleInstansiFieldEdit(select) {
        const instansiField = document.getElementById('instansi_field_edit');
        const selectedOption = select.options[select.selectedIndex];
        const jenisSurat = selectedOption ? selectedOption.getAttribute('data-jenis') : '';
        instansiField.style.display = jenisSurat === 'surat_undangan' ? 'block' : 'none';
        document.getElementById('instansi_edit').required = jenisSurat === 'surat_undangan';
        const noSuratInput = document.getElementById('no_surat_edit');
        noSuratInput.value = noSuratInput.value.replace(/\/(KLR|UDG)\//, `/${jenisSurat === 'surat_undangan' ? 'UDG' : 'KLR'}/`);
    }

    function resetForm() {
        document.getElementById('instansi_field').style.display = 'none';
        document.getElementById('instansi').required = false;
        document.getElementById('instansi').value = '';
        document.getElementById('no_surat').value = '<?php echo $faker->numerify('##/KLR/' . date('d') . date('m') . '25'); ?>';
    }

    function resetEditForm() {
        document.getElementById('instansi_field_edit').style.display = 'none';
        document.getElementById('instansi_edit').required = false;
        document.getElementById('instansi_edit').value = '';
        const select = document.getElementById('id_surat_edit');
        toggleInstansiFieldEdit(select);
    }
</script>

<?php
if (isset($_POST['tambah'])) {
    $noSurat = mysqli_real_escape_string($koneksi, $_POST['no_surat']);
    $idSurat = mysqli_real_escape_string($koneksi, $_POST['id_surat']);
    $tanggalKirim = mysqli_real_escape_string($koneksi, $_POST['tanggal_kirim']);
    $penerima = mysqli_real_escape_string($koneksi, $_POST['penerima']);
    $instansi = mysqli_real_escape_string($koneksi, $_POST['instansi'] ?? '');
    $perihal = mysqli_real_escape_string($koneksi, $_POST['perihal']);
    $file = $_FILES['file']['name'];
    $tmpFile = $_FILES['file']['tmp_name'];
    $fileType = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    if ($fileType !== 'pdf') {
        echo "<script>alert('Hanya file PDF yang diperbolehkan.');</script>";
        exit;
    }

    if (!move_uploaded_file($tmpFile, "assets/file/keluar/$file")) {
        echo "<script>alert('Gagal mengunggah file.');</script>";
        exit;
    }

    $query = mysqli_query($koneksi, "INSERT INTO tb_surat_keluar (id, id_surat, no_surat, tanggal_kirim, penerima, instansi, perihal, file) VALUES (NULL, '$idSurat', '$noSurat', '$tanggalKirim', '$penerima', '$instansi', '$perihal', '$file')");

    if ($query) {
        echo "<script>
                alert('Data Surat Keluar Berhasil Ditambahkan');
                window.location.href='?halaman=keluar';
            </script>";
    } else {
        echo "<script>alert('Gagal menambahkan data surat: " . addslashes(mysqli_error($koneksi)) . "');</script>";
    }
}

if (isset($_POST['edit'])) {
    $id = mysqli_real_escape_string($koneksi, $_POST['idEdit']);
    $noSurat = mysqli_real_escape_string($koneksi, $_POST['no_surat_edit']);
    $idSurat = mysqli_real_escape_string($koneksi, $_POST['id_surat_edit']);
    $tanggalKirim = mysqli_real_escape_string($koneksi, $_POST['tanggal_kirim_edit']);
    $penerima = mysqli_real_escape_string($koneksi, $_POST['penerima_edit']);
    $instansi = mysqli_real_escape_string($koneksi, $_POST['instansi_edit'] ?? '');
    $perihal = mysqli_real_escape_string($koneksi, $_POST['perihal_edit']);
    $file = $_FILES['file_edit']['name'];
    $tmpFile = $_FILES['file_edit']['tmp_name'];

    $data = mysqli_query($koneksi, "SELECT file FROM tb_surat_keluar WHERE id='$id'");
    if (!$data) {
        echo "<script>alert('Gagal mengambil data file: " . addslashes(mysqli_error($koneksi)) . "');</script>";
        exit;
    }
    $fileLama = mysqli_fetch_assoc($data);

    if ($file) {
        $fileType = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if ($fileType !== 'pdf') {
            echo "<script>alert('Hanya file PDF yang diperbolehkan.');</script>";
            exit;
        }
        if ($fileLama['file']) {
            unlink("assets/file/keluar/" . $fileLama['file']);
        }
        move_uploaded_file($tmpFile, "assets/file/keluar/$file");
        $query = mysqli_query($koneksi, "UPDATE tb_surat_keluar SET no_surat='$noSurat', id_surat='$idSurat', tanggal_kirim='$tanggalKirim', penerima='$penerima', instansi='$instansi', perihal='$perihal', file='$file' WHERE id='$id'");
    } else {
        $query = mysqli_query($koneksi, "UPDATE tb_surat_keluar SET no_surat='$noSurat', id_surat='$idSurat', tanggal_kirim='$tanggalKirim', penerima='$penerima', instansi='$instansi', perihal='$perihal' WHERE id='$id'");
    }

    if ($query) {
        echo "<script>
                alert('Data Surat Keluar Berhasil Diubah');
                window.location.href='?halaman=keluar';
            </script>";
    } else {
        echo "<script>alert('Gagal mengubah data surat: " . addslashes(mysqli_error($koneksi)) . "');</script>";
    }
}

if (isset($_POST['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_POST['idHapus']);

    $data = mysqli_query($koneksi, "SELECT file FROM tb_surat_keluar WHERE id='$id'");
    if (!$data) {
        echo "<script>alert('Gagal mengambil data file: " . addslashes(mysqli_error($koneksi)) . "');</script>";
        exit;
    }
    $file = mysqli_fetch_assoc($data);
    
    if ($file['file']) {
        unlink("assets/file/keluar/" . $file['file']);
    }

    $query = mysqli_query($koneksi, "DELETE FROM tb_surat_keluar WHERE id='$id'");

    if ($query) {
        echo "<script>
                alert('Data Surat Keluar Berhasil Dihapus');
                window.location.href='?halaman=keluar';
            </script>";
    } else {
        echo "<script>alert('Gagal menghapus data surat: " . addslashes(mysqli_error($koneksi)) . "');</script>";
    }
}
?>