
<div class="pagetitle">
    <h1>Surat Cuti</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active">Surat Cuti</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Surat Cuti</h5>
                    <div class="d-flex justify-content-between my-3">
                        <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Surat</button>
                        <?php endif; ?>
                        <!-- Filter Form -->
                        <form method="get" action="report/cetak_surat_cuti.php" target="_blank" class="d-flex align-items-center">
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
                                <th scope="col">Tanggal Surat</th>
                                <th scope="col">Alasan Cuti</th>
                                <th scope="col">Status</th>
                                <th scope="col">File</th>
                                <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                                    <th scope="col">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            $data = mysqli_query($koneksi, "SELECT tb_surat_cuti.*, tb_surat.jenis_surat, tb_surat.id AS id_surat, tb_cuti.id AS id_cuti, tb_cuti.nip, tb_cuti.alasan FROM tb_surat_cuti JOIN tb_cuti JOIN tb_surat ON tb_surat_cuti.id_cuti = tb_cuti.id AND tb_surat_cuti.id_surat = tb_surat.id ORDER BY tb_surat_cuti.id DESC");
                            foreach ($data as $row) : ?>
                                <tr>
                                    <th scope="row"><?= $no++; ?></th>
                                    <td><?= $row['no_surat']; ?></td>
                                    <td><?= $row['jenis_surat']; ?></td>
                                    <td><?= $row['nip']; ?></td>
                                    <td><?= $row['tanggal_surat']; ?></td>
                                    <td class="text-warning text"><?= $row['alasan']; ?></td>
                                    <?php 
                                        if ($row['status'] == 'diajukan') {
                                            echo '<td><span class="badge bg-warning">Diajukan</span></td>';
                                        } elseif ($row['status'] == 'diterima') {
                                            echo '<td><span class="badge bg-success">Diterima</span></td>';
                                        } else {
                                            echo '<td><span class="badge bg-danger">Ditolak</span></td>';
                                        }
                                    ?>
                                    <td><a href="assets/file/cuti/<?= $row['file']; ?>" target="_blank" class="btn btn-sm btn-primary"><i class="bi bi-file-earmark-pdf"></i></a></td>
                                    <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="editData('<?= $row['id']; ?>', '<?= $row['no_surat']; ?>', '<?= $row['id_surat']; ?>', '<?= $row['id_cuti']; ?>', '<?= $row['tanggal_surat']; ?>', '<?= $row['status']; ?>')" data-bs-toggle="modal" data-bs-target="#modalEdit"><i class="bi bi-pencil-square"></i></button>
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
                <h5 class="modal-title">Form Tambah Surat Cuti</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="tambah" value="tambah">
                    <div class="col-12">
                        <label for="no_surat" class="form-label">No Surat</label>
                        <input type="text" class="form-control" id="no_surat" name="no_surat" readonly value="<?= $faker->numerify('##/CUTI/' . date('d') . '' . date('m') . '25'); ?>" required>
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
                        <label for="id_cuti" class="form-label">Nama Pegawai</label>
                        <select class="form-select" id="id_cuti" name="id_cuti" required>
                            <option selected disabled value="">Pilih Pegawai</option>
                            <?php $data = mysqli_query($koneksi, "SELECT tb_cuti.id AS id_cuti, tb_cuti.nip, tb_pegawai.nama_pegawai FROM tb_cuti JOIN tb_pegawai ON tb_cuti.nip = tb_pegawai.nip");
                            foreach ($data as $row) : ?>
                                <option value="<?= $row['id_cuti']; ?>"><?= $row['nama_pegawai']; ?> - <?= $row['nip']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="tanggal_surat" class="form-label">Tanggal Surat</label>
                        <input type="date" class="form-control" id="tanggal_surat" name="tanggal_surat" required>
                    </div>
                    <div class="col-12">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option selected disabled value="">Pilih Status</option>
                            <option value="diajukan">Diajukan</option>
                            <option value="diterima">Diterima</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
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
                <h5 class="modal-title">Form Edit Surat Cuti</h5>
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
                        <label for="id_cuti_edit" class="form-label">Nama Pegawai</label>
                        <select class="form-select" id="id_cuti_edit" name="id_cuti_edit" required>
                            <option selected disabled value="">Pilih Pegawai</option>
                            <?php $data = mysqli_query($koneksi, "SELECT tb_cuti.id AS id_cuti, tb_cuti.nip, tb_pegawai.nama_pegawai FROM tb_cuti JOIN tb_pegawai ON tb_cuti.nip = tb_pegawai.nip");
                            foreach ($data as $row) : ?>
                                <option value="<?= $row['id_cuti']; ?>"><?= $row['nama_pegawai']; ?> - <?= $row['nip']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="tanggal_surat_edit" class="form-label">Tanggal Surat</label>
                        <input type="date" class="form-control" id="tanggal_surat_edit" name="tanggal_surat_edit" required>
                    </div>
                    <div class="col-12">
                        <label for="status_edit" class="form-label">Status</label>
                        <select class="form-select" id="status_edit" name="status_edit" required>
                            <option selected disabled value="">Pilih Status</option>
                            <option value="diajukan">Diajukan</option>
                            <option value="diterima">Diterima</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
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
    function editData(id, no_surat, id_surat, id_cuti, tanggal_surat, status) {
        document.getElementById('idEdit').value = id;
        document.getElementById('no_surat_edit').value = no_surat;
        document.getElementById('id_surat_edit').value = id_surat;
        document.getElementById('id_cuti_edit').value = id_cuti;
        document.getElementById('tanggal_surat_edit').value = tanggal_surat;
        document.getElementById('status_edit').value = status;
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
    $idCuti = $_POST['id_cuti'];
    $tanggalSurat = $_POST['tanggal_surat'];
    $status = $_POST['status'];
    $file = $_FILES['file']['name'];
    $tmpFile = $_FILES['file']['tmp_name'];

    if ($file) {
        $fileType = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if ($fileType == 'pdf') {
            move_uploaded_file($tmpFile, "assets/file/cuti/$file");
        } else {
            echo "<script>alert('Hanya file PDF yang diperbolehkan.');</script>";
        }
    }

    $query = mysqli_query($koneksi, "INSERT INTO tb_surat_cuti VALUES(NULL, '$noSurat', '$idSurat', '$idCuti', '$tanggalSurat', '$status', '$file')");
    if ($query) {
        echo "<script>alert('Data Surat Cuti berhasil ditambahkan.'); window.location.href='?halaman=surat_cuti';</script>";
    }
}

if (isset($_POST['edit'])) {
    $id = $_POST['idEdit'];
    $noSurat = $_POST['no_surat_edit'];
    $idSurat = $_POST['id_surat_edit'];
    $idCuti = $_POST['id_cuti_edit'];
    $tanggalSurat = $_POST['tanggal_surat_edit'];
    $status = $_POST['status_edit'];
    $file = $_FILES['file_edit']['name'];
    $tmpFile = $_FILES['file_edit']['tmp_name'];

    $data = mysqli_query($koneksi, "SELECT file FROM tb_surat_cuti WHERE id='$id'");
    $fileLama = mysqli_fetch_assoc($data);

    if ($file) {
        $fileType = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        if ($fileType == 'pdf') {
            unlink("assets/file/cuti/" . $fileLama['file']);
            move_uploaded_file($tmpFile, "assets/file/cuti/$file");
            $query = mysqli_query($koneksi, "UPDATE tb_surat_cuti SET no_surat='$noSurat', id_surat='$idSurat', id_cuti='$idCuti', tanggal_surat='$tanggalSurat', status='$status', file='$file' WHERE id='$id'");
            if ($query) {
                echo "<script>
                        alert('Data Surat Cuti Berhasil Diubah');
                        window.location.href='?halaman=surat_cuti';
                    </script>";
            }
        } else {
            echo "<script>alert('Hanya file PDF yang diperbolehkan.');</script>";
        }
    } else {
        $query = mysqli_query($koneksi, "UPDATE tb_surat_cuti SET no_surat='$noSurat', id_surat='$idSurat', id_cuti='$idCuti', tanggal_surat='$tanggalSurat', status='$status' WHERE id='$id'");
        if ($query) {
            echo "<script>
                    alert('Data Surat Cuti Berhasil Diubah');
                    window.location.href='?halaman=surat_cuti';
                </script>";
        }
    }
}

if (isset($_POST['hapus'])) {
    $id = $_POST['idHapus'];

    $data = mysqli_query($koneksi, "SELECT file FROM tb_surat_cuti WHERE id='$id'");
    $file = mysqli_fetch_assoc($data);
    
    if ($file['file']) {
        unlink("assets/file/cuti/" . $file['file']);
    }

    $query = mysqli_query($koneksi, "DELETE FROM tb_surat_cuti WHERE id='$id'");

    if ($query) {
        echo "<script>
                alert('Data Surat Cuti Berhasil Dihapus');
                window.location.href='?halaman=surat_cuti';
            </script>";
    }
}
?>