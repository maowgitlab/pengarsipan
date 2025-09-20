<div class="pagetitle">
    <h1>Surat Undangan</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active">Surat Undangan</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Surat Undangan</h5>
                    <div class="d-flex justify-content-between my-3">
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Surat Undangan</button>
                        <!-- Filter Form -->
                        <form method="get" action="report/cetak_surat_undangan.php" target="_blank" class="d-flex align-items-center">
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
                                <th scope="col">Perihal</th>
                                <th scope="col">Tanggal Surat</th>
                                <th scope="col">Tanggal Acara</th>
                                <th scope="col">Waktu</th>
                                <th scope="col">Tempat</th>
                                <th scope="col">Pengirim</th>
                                <th scope="col">File</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            $data = mysqli_query($koneksi, "SELECT tb_surat_undangan.*, tb_surat.id AS id_surat, tb_surat.jenis_surat FROM tb_surat_undangan JOIN tb_surat ON tb_surat_undangan.id_surat = tb_surat.id ORDER BY tb_surat_undangan.id DESC");
                            foreach ($data as $row) : ?>
                                <tr>
                                    <th scope="row"><?= $no++; ?></th>
                                    <td><?= $row['no_surat']; ?></td>
                                    <td><?= $row['jenis_surat']; ?></td>
                                    <td><?= $row['perihal']; ?></td>
                                    <td><?= $row['tanggal_surat']; ?></td>
                                    <td><?= $row['tanggal_acara']; ?></td>
                                    <td><?= $row['waktu']; ?></td>
                                    <td><?= $row['tempat']; ?></td>
                                    <td><?= $row['nama_pengirim']; ?></td>
                                    <td><a href="assets/file/undangan/<?= $row['file']; ?>" target="_blank" class="btn btn-sm btn-primary"><i class="bi bi-file-earmark-pdf"></i></a></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary" onclick="editData('<?= $row['id'] ?>', '<?= $row['no_surat'] ?>', '<?= $row['id_surat'] ?>', '<?= $row['perihal'] ?>', '<?= $row['tanggal_surat'] ?>', '<?= $row['tanggal_acara'] ?>', '<?= $row['waktu'] ?>', '<?= $row['tempat'] ?>', '<?= $row['nama_pengirim'] ?>')" data-bs-toggle="modal" data-bs-target="#modalEdit"><i class="bi bi-pencil-square"></i></button>
                                        <button type="button" class="btn btn-sm btn-danger" onclick="hapusData('<?= $row['id']; ?>')" data-bs-toggle="modal" data-bs-target="#modalHapus"><i class="bi bi-trash"></i></button>
                                    </td>
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
                <h5 class="modal-title">Form Tambah Surat Undangan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="tambah" value="tambah">
                    <div class="col-12">
                        <label for="no_surat" class="form-label">No Surat</label>
                        <input type="text" class="form-control" id="no_surat" name="no_surat" readonly value="<?= $faker->numerify('##/UND/' . date('d') . '' . date('m') . '25'); ?>" required>
                    </div>
                    <div class="col-12">
                        <label for="id_surat" class="form-label">Jenis Surat</label>
                        <select class="form-select" id="id_surat" name="id_surat" required>
                            <option selected disabled value="">Pilih Jenis Surat</option>
                            <?php $data = mysqli_query($koneksi, "SELECT * FROM tb_surat");
                            foreach ($data as $row) : ?>
                                <option value="<?= $row['id']; ?>"><?= $row['jenis_surat']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="perihal" class="form-label">Perihal</label>
                        <input type="text" class="form-control" id="perihal" name="perihal" required>
                    </div>
                    <div class="col-12">
                        <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
                        <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat" required>
                    </div>
                    <div class="col-12">
                        <label for="tanggal_acara" class="form-label">Tanggal Acara</label>
                        <input type="date" class="form-control" id="tanggal_acara" name="tanggal_acara" required>
                    </div>
                    <div class="col-12">
                        <label for="waktu" class="form-label">Waktu</label>
                        <input type="time" class="form-control" id="waktu" name="waktu" required>
                    </div>
                    <div class="col-12">
                        <label for="tempat" class="form-label">Tempat</label>
                        <input type="text" class="form-control" id="tempat" name="tempat" required>
                    </div>
                    <div class="col-12">
                        <label for="pengirim" class="form-label">Pengirim</label>
                        <input type="text" class="form-control" id="pengirim" name="pengirim" required>
                    </div>
                    <div class="col-12">
                        <label for="file" class="form-label">File</label>
                        <input type="file" class="form-control" id="file" name="file" required>
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
                <h5 class="modal-title">Form Edit Surat Undangan</h5>
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
                        <label for="perihal_edit" class="form-label">Perihal</label>
                        <input type="text" class="form-control" id="perihal_edit" name="perihal_edit" required>
                    </div>
                    <div class="col-12">
                        <label for="tanggal_surat_edit" class="form-label">Tanggal Surat</label>
                        <input type="date" class="form-control" id="tanggal_surat_edit" name="tanggal_surat_edit" required>
                    </div>
                    <div class="col-12">
                        <label for="tanggal_acara_edit" class="form-label">Tanggal Acara</label>
                        <input type="date" class="form-control" id="tanggal_acara_edit" name="tanggal_acara_edit" required>
                    </div>
                    <div class="col-12">
                        <label for="waktu_edit" class="form-label">Waktu</label>
                        <input type="time" class="form-control" id="waktu_edit" name="waktu_edit" required>
                    </div>
                    <div class="col-12">
                        <label for="tempat_edit" class="form-label">Tempat</label>
                        <input type="text" class="form-control" id="tempat_edit" name="tempat_edit" required>
                    </div>
                    <div class="col-12">
                        <label for="pengirim_edit" class="form-label">Pengirim</label>
                        <input type="text" class="form-control" id="pengirim_edit" name="pengirim_edit" required>
                    </div>
                    <div class="col-12">
                        <label for="file_edit" class="form-label">File</label>
                        <input type="file" class="form-control" id="file_edit" name="file_edit">
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
    function editData(id, no_surat, id_surat, perihal, tanggal_surat, tanggal_acara, waktu, tempat, pengirim) {
        document.getElementById('idEdit').value = id;
        document.getElementById('no_surat_edit').value = no_surat;
        document.getElementById('id_surat_edit').value = id_surat;
        document.getElementById('perihal_edit').value = perihal;
        document.getElementById('tanggal_surat_edit').value = tanggal_surat;
        document.getElementById('tanggal_acara_edit').value = tanggal_acara;
        document.getElementById('waktu_edit').value = waktu;
        document.getElementById('tempat_edit').value = tempat;
        document.getElementById('pengirim_edit').value = pengirim;
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
    $noSurat = $_POST['no_surat'];
    $idSurat = $_POST['id_surat'];
    $perihal = $_POST['perihal'];
    $tanggalSurat = $_POST['tanggal_surat'];
    $tanggalAcara = $_POST['tanggal_acara'];
    $waktu = $_POST['waktu'];
    $tempat = $_POST['tempat'];
    $pengirim = $_POST['pengirim'];
    $file = $_FILES['file']['name'];
    $tmpFile = $_FILES['file']['tmp_name'];
    $fileType = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    if ($fileType == 'pdf') {
        move_uploaded_file($tmpFile, "assets/file/undangan/$file");
    } else {
        echo "<script>alert('Hanya file PDF yang diperbolehkan.');</script>";
    }

    $query = mysqli_query($koneksi, "INSERT INTO tb_surat_undangan VALUES(NULL, '$noSurat', '$idSurat', '$perihal', '$tanggalSurat', '$tanggalAcara', '$waktu', '$tempat', '$pengirim', '$file')");

    if ($query) {
        echo "<script>
                alert('Data Surat Undangan Berhasil Ditambahkan');
                window.location.href='?halaman=undangan';
            </script>";
    }
}

if (isset($_POST['edit'])) {
    $id = $_POST['idEdit'];
    $noSurat = $_POST['no_surat_edit'];
    $idSurat = $_POST['id_surat_edit'];
    $perihal = $_POST['perihal_edit'];
    $tanggalSurat = $_POST['tanggal_surat_edit'];
    $tanggalAcara = $_POST['tanggal_acara_edit'];
    $waktu = $_POST['waktu_edit'];
    $tempat = $_POST['tempat_edit'];
    $pengirim = $_POST['pengirim_edit'];
    $file = $_FILES['file_edit']['name'];
    $tmpFile = $_FILES['file_edit']['tmp_name'];

    $data = mysqli_query($koneksi, "SELECT file FROM tb_surat_undangan WHERE id='$id'");
    $fileLama = mysqli_fetch_assoc($data);

    if ($file) {
        $fileType = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if ($fileType == 'pdf') {
            unlink("assets/file/undangan/" . $fileLama['file']);
            move_uploaded_file($tmpFile, "assets/file/undangan/$file");
            $query = mysqli_query($koneksi, "UPDATE tb_surat_undangan SET no_surat='$noSurat', id_surat='$idSurat', perihal='$perihal', tanggal_surat='$tanggalSurat', tanggal_acara='$tanggalAcara', waktu='$waktu', tempat='$tempat', nama_pengirim='$pengirim', file='$file' WHERE id='$id'");
            if ($query) {
                echo "<script>
                        alert('Data Surat Undangan Berhasil Diubah');
                        window.location.href='?halaman=undangan';
                    </script>";
            }
        } else {
            echo "<script>alert('Hanya file PDF yang diperbolehkan.');</script>";
        }
    } else {
        $query = mysqli_query($koneksi, "UPDATE tb_surat_undangan SET no_surat='$noSurat', id_surat='$idSurat', perihal='$perihal', tanggal_surat='$tanggalSurat', tanggal_acara='$tanggalAcara', waktu='$waktu', tempat='$tempat', nama_pengirim='$pengirim' WHERE id='$id'");
        if ($query) {
            echo "<script>
                    alert('Data Surat Undangan Berhasil Diubah');
                    window.location.href='?halaman=undangan';
                </script>";
        }
    }
}

if (isset($_POST['hapus'])) {
    $id = $_POST['idHapus'];

    $data = mysqli_query($koneksi, "SELECT file FROM tb_surat_undangan WHERE id='$id'");
    $file = mysqli_fetch_assoc($data);
    
    if ($file['file']) {
        unlink("assets/file/undangan/" . $file['file']);
    }

    $query = mysqli_query($koneksi, "DELETE FROM tb_surat_undangan WHERE id='$id'");

    if ($query) {
        echo "<script>
                alert('Data Surat Undangan Berhasil Dihapus');
                window.location.href='?halaman=undangan';
            </script>";
    }
}
?>