<div class="pagetitle">
    <h1>Pengajuan Cuti</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active">Pengajuan Cuti</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Pengajuan Cuti Anda</h5>
                    <div class="d-flex justify-content-between my-3">
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Buat Pengajuan Baru</button>
                    </div>
                    <table class="table table-hover" id="tabelData">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">No Surat</th>
                                <th scope="col">Jenis Surat</th>
                                <th scope="col">NIP</th>
                                <th scope="col">Tanggal Surat</th>
                                <th scope="col">Status</th>
                                <th scope="col">Alasan Cuti</th>
                                <th scope="col">File</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            $nip = $_SESSION['nip'];
                            $data = mysqli_query($koneksi, "SELECT tb_surat_cuti.*, tb_surat.jenis_surat, tb_surat.id AS id_surat, tb_cuti.id AS id_cuti, tb_cuti.nip, tb_cuti.alasan, tb_cuti.tanggal_mulai, tb_cuti.tanggal_selesai FROM tb_surat_cuti JOIN tb_cuti JOIN tb_surat ON tb_surat_cuti.id_cuti = tb_cuti.id AND tb_surat_cuti.id_surat = tb_surat.id WHERE tb_cuti.nip = '$nip' ORDER BY tb_surat_cuti.id DESC");
                            foreach ($data as $row) : ?>
                                <tr>
                                    <th scope="row"><?= $no++; ?></th>
                                    <td><?= $row['no_surat']; ?></td>
                                    <td><?= $row['jenis_surat']; ?></td>
                                    <td><?= $row['nip']; ?></td>
                                    <td><?= $row['tanggal_surat']; ?></td>
                                    <?php 
                                        if ($row['status'] == 'diajukan') {
                                            echo '<td><span class="badge bg-warning">Diajukan</span></td>';
                                        } elseif($row['status'] == 'diterima') {
                                            echo '<td><span class="badge bg-success">Diterima</span></td>';
                                        } else {
                                            echo '<td><span class="badge bg-danger">Ditolak</span></td>';
                                        }
                                    ?>
                                    <td><?= $row['alasan']; ?></td>
                                    <td><a href="assets/file/cuti/<?= $row['file']; ?>" target="_blank" class="btn btn-sm btn-primary"><i class="bi bi-file-earmark-pdf"></i></a></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary"
                                            onclick="editData(
                '<?= $row['id']; ?>', // id surat_cuti
                '<?= $row['id_cuti']; ?>', 
                '<?= $row['no_surat']; ?>', 
                '<?= $row['id_surat']; ?>', 
                '<?= $row['alasan']; ?>', 
                '<?= $row['tanggal_mulai']; ?>', 
                '<?= $row['tanggal_selesai']; ?>'
            )"
                                            data-bs-toggle="modal" data-bs-target="#modalEdit">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
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
                    <input type="hidden" name="nip" value="<?= $_SESSION['nip']; ?>">
                    <input type="hidden" name="id_bidang" value="<?= $_SESSION['id_bidang']; ?>">
                    <div class="col-12">
                        <label for="no_surat" class="form-label">No Surat</label>
                        <input type="text" class="form-control" id="no_surat" name="no_surat" readonly
                            value="<?= $faker->numerify('##/CUTI/' . date('d') . '' . date('m') . '25'); ?>" required>
                    </div>
                    <div class="col-12">
                        <label for="id_surat" class="form-label">Jenis Surat</label>
                        <select class="form-select" id="id_surat" name="id_surat" required>
                            <option selected disabled value="">Pilih Jenis Surat</option>
                            <?php
                            $data = mysqli_query($koneksi, "SELECT * FROM tb_surat");
                            foreach ($data as $row) :
                            ?>
                                <option value="<?= $row['id']; ?>"><?= $row['jenis_surat']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="alasan" class="form-label">Alasan</label>
                        <input type="text" class="form-control" id="alasan" name="alasan" required>
                    </div>
                    <div class="col-12">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
                    </div>
                    <div class="col-12">
                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" required>
                    </div>
                    <div class="col-12">
                        <label for="file" class="form-label">Pernyataan Cuti (PDF)</label>
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
                <h5 class="modal-title">Form Edit Surat Cuti</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="edit" value="edit">
                    <input type="hidden" name="id_surat_cuti" id="id_surat_cuti">
                    <input type="hidden" name="id_cuti" id="id_cuti">
                    <input type="hidden" name="nip" value="<?= $_SESSION['nip']; ?>">
                    <input type="hidden" name="id_bidang" value="<?= $_SESSION['id_bidang']; ?>">

                    <div class="col-12">
                        <label for="no_surat_edit" class="form-label">No Surat</label>
                        <input type="text" class="form-control" id="no_surat_edit" name="no_surat_edit" readonly>
                    </div>
                    <div class="col-12">
                        <label for="id_surat_edit" class="form-label">Jenis Surat</label>
                        <select class="form-select" id="id_surat_edit" name="id_surat_edit" required>
                            <option selected disabled value="">Pilih Jenis Surat</option>
                            <?php
                            $data = mysqli_query($koneksi, "SELECT * FROM tb_surat");
                            foreach ($data as $row) :
                            ?>
                                <option value="<?= $row['id']; ?>"><?= $row['jenis_surat']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="nama_pegawai_edit" class="form-label">Nama Pegawai</label>
                        <input type="text" name="nama_pegawai_edit" id="nama_pegawai_edit" value="<?= $_SESSION['nama_pegawai']; ?>" readonly class="form-control">
                    </div>
                    <div class="col-12">
                        <label for="alasan_edit" class="form-label">Alasan</label>
                        <input type="text" class="form-control" id="alasan_edit" name="alasan_edit" required>
                    </div>
                    <div class="col-12">
                        <label for="tanggal_mulai_edit" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="tanggal_mulai_edit" name="tanggal_mulai_edit" required>
                    </div>
                    <div class="col-12">
                        <label for="tanggal_selesai_edit" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="tanggal_selesai_edit" name="tanggal_selesai_edit" required>
                    </div>
                    <div class="col-12">
                        <label for="file_edit" class="form-label">Pernyataan Cuti (PDF)</label>
                        <input type="file" class="form-control" id="file_edit" name="file_edit" accept=".pdf">
                        <small class="text-muted">Biarkan kosong jika tidak ingin mengubah file</small>
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

<!-- Delete Modal -->
<div class="modal fade" id="modalHapus" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Penghapusan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post">
                    <input type="hidden" name="hapus" value="hapus">
                    <input type="hidden" name="id_hapus" id="id_hapus">
                    <p class="text-center mb-3">Apakah anda yakin ingin menghapus data ini?</p>
                    <div class="text-center">
                        <button type="submit" class="btn btn-danger">Hapus</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    function editData(idSuratCuti, idCuti, noSurat, idSurat, alasan, tanggalMulai, tanggalSelesai) {
        document.getElementById('id_surat_cuti').value = idSuratCuti;
        document.getElementById('id_cuti').value = idCuti;
        document.getElementById('no_surat_edit').value = noSurat;
        document.getElementById('id_surat_edit').value = idSurat;
        document.getElementById('alasan_edit').value = alasan;
        document.getElementById('tanggal_mulai_edit').value = tanggalMulai;
        document.getElementById('tanggal_selesai_edit').value = tanggalSelesai;
    }

    function hapusData(id) {
        document.getElementById('id_hapus').value = id;
    }
</script>
<?php
if (isset($_POST['tambah'])) {
    // Get form data
    $nip = mysqli_real_escape_string($koneksi, $_POST['nip']);
    $idBidang = mysqli_real_escape_string($koneksi, $_POST['id_bidang']);
    $alasan = mysqli_real_escape_string($koneksi, $_POST['alasan']);
    $tanggalMulai = mysqli_real_escape_string($koneksi, $_POST['tanggal_mulai']);
    $tanggalSelesai = mysqli_real_escape_string($koneksi, $_POST['tanggal_selesai']);

    // Start transaction
    mysqli_begin_transaction($koneksi);

    try {
        // Insert into tb_cuti first
        $queryCuti = mysqli_query($koneksi, "INSERT INTO tb_cuti (nip, id_bidang, alasan, tanggal_mulai, tanggal_selesai) 
                                            VALUES ('$nip', '$idBidang', '$alasan', '$tanggalMulai', '$tanggalSelesai')");

        if (!$queryCuti) {
            throw new Exception("Error inserting into tb_cuti: " . mysqli_error($koneksi));
        }

        // Get the id_cuti from the last insert
        $idCuti = mysqli_insert_id($koneksi);

        // Process file upload
        $file = $_FILES['file'];
        $fileName = $file['name'];
        $tmpName = $file['tmp_name'];
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Validate file type
        if ($fileType !== 'pdf') {
            throw new Exception("Hanya file PDF yang diperbolehkan.");
        }

        // Generate unique filename
        $newFileName = uniqid() . '_' . $fileName;

        // Move uploaded file
        if (!move_uploaded_file($tmpName, "assets/file/cuti/$newFileName")) {
            throw new Exception("Gagal mengupload file.");
        }

        // Insert into tb_surat_cuti
        $noSurat = mysqli_real_escape_string($koneksi, $_POST['no_surat']);
        $idSurat = mysqli_real_escape_string($koneksi, $_POST['id_surat']);
        $tanggalSurat = date('Y-m-d');
        $status = 'diajukan';

        $querySuratCuti = mysqli_query($koneksi, "INSERT INTO tb_surat_cuti 
                                                 (id_surat, id_cuti, no_surat, tanggal_surat, status, file) 
                                                 VALUES 
                                                 ('$idSurat', '$idCuti', '$noSurat', '$tanggalSurat', '$status', '$newFileName')");

        if (!$querySuratCuti) {
            throw new Exception("Error inserting into tb_surat_cuti: " . mysqli_error($koneksi));
        }

        // If everything is successful, commit the transaction
        mysqli_commit($koneksi);
        echo "<script>
                alert('Pengajuan cuti berhasil disimpan.');
                window.location.href='?halaman=pengajuan_cuti';
              </script>";
    } catch (Exception $e) {
        // If there's an error, rollback the transaction
        mysqli_rollback($koneksi);

        // Delete uploaded file if it exists
        if (isset($newFileName) && file_exists("assets/file/cuti/$newFileName")) {
            unlink("assets/file/cuti/$newFileName");
        }

        echo "<script>
                alert('Error: " . addslashes($e->getMessage()) . "');
                window.location.href='?halaman=pengajuan_cuti';
              </script>";
    }
}
if (isset($_POST['edit'])) {
    $idSuratCuti = mysqli_real_escape_string($koneksi, $_POST['id_surat_cuti']);
    $idCuti = mysqli_real_escape_string($koneksi, $_POST['id_cuti']);
    $noSurat = mysqli_real_escape_string($koneksi, $_POST['no_surat_edit']);
    $idSurat = mysqli_real_escape_string($koneksi, $_POST['id_surat_edit']);
    $alasan = mysqli_real_escape_string($koneksi, $_POST['alasan_edit']);
    $tanggalMulai = mysqli_real_escape_string($koneksi, $_POST['tanggal_mulai_edit']);
    $tanggalSelesai = mysqli_real_escape_string($koneksi, $_POST['tanggal_selesai_edit']);

    mysqli_begin_transaction($koneksi);

    try {
        // Update tb_cuti
        $queryCuti = mysqli_query($koneksi, "UPDATE tb_cuti 
                                            SET alasan = '$alasan',
                                                tanggal_mulai = '$tanggalMulai',
                                                tanggal_selesai = '$tanggalSelesai'
                                            WHERE id = '$idCuti'");

        if (!$queryCuti) {
            throw new Exception("Error updating tb_cuti: " . mysqli_error($koneksi));
        }

        // Handle file upload if new file is provided
        if (!empty($_FILES['file_edit']['name'])) {
            $file = $_FILES['file_edit'];
            $fileName = $file['name'];
            $tmpName = $file['tmp_name'];
            $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // Validate file type
            if ($fileType !== 'pdf') {
                throw new Exception("Hanya file PDF yang diperbolehkan.");
            }

            // Get old file name to delete later
            $queryOldFile = mysqli_query($koneksi, "SELECT file FROM tb_surat_cuti WHERE id = '$idSuratCuti'");
            $oldFile = mysqli_fetch_assoc($queryOldFile)['file'];

            // Generate unique filename
            $newFileName = uniqid() . '_' . $fileName;

            // Move uploaded file
            if (!move_uploaded_file($tmpName, "assets/file/cuti/$newFileName")) {
                throw new Exception("Gagal mengupload file.");
            }

            // Update tb_surat_cuti with new file
            $querySuratCuti = mysqli_query($koneksi, "UPDATE tb_surat_cuti 
                                                     SET no_surat = '$noSurat',
                                                         id_surat = '$idSurat',
                                                         file = '$newFileName'
                                                     WHERE id = '$idSuratCuti'");

            // Delete old file
            if ($oldFile && file_exists("assets/file/cuti/$oldFile")) {
                unlink("assets/file/cuti/$oldFile");
            }
        } else {
            // Update tb_surat_cuti without changing file
            $querySuratCuti = mysqli_query($koneksi, "UPDATE tb_surat_cuti 
                                                     SET no_surat = '$noSurat',
                                                         id_surat = '$idSurat'
                                                     WHERE id = '$idSuratCuti'");
        }

        if (!$querySuratCuti) {
            throw new Exception("Error updating tb_surat_cuti: " . mysqli_error($koneksi));
        }

        mysqli_commit($koneksi);
        echo "<script>
                alert('Data cuti berhasil diperbarui.');
                window.location.href='?halaman=pengajuan_cuti';
              </script>";
    } catch (Exception $e) {
        mysqli_rollback($koneksi);

        // Delete newly uploaded file if it exists
        if (isset($newFileName) && file_exists("assets/file/cuti/$newFileName")) {
            unlink("assets/file/cuti/$newFileName");
        }

        echo "<script>
                alert('Error: " . addslashes($e->getMessage()) . "');
                window.location.href='?halaman=pengajuan_cuti';
              </script>";
    }
}

// Delete Process
if (isset($_POST['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_POST['id_hapus']);
    
    mysqli_begin_transaction($koneksi);
    
    try {
        // Ambil data yang diperlukan sebelum menghapus
        $queryData = mysqli_query($koneksi, "SELECT tb_surat_cuti.file, tb_surat_cuti.id_cuti 
                                           FROM tb_surat_cuti 
                                           WHERE tb_surat_cuti.id = '$id'");
        $data = mysqli_fetch_assoc($queryData);
        $file = $data['file'];
        $idCuti = $data['id_cuti'];
        
        // Hapus file jika ada
        if ($file && file_exists("assets/file/cuti/$file")) {
            unlink("assets/file/cuti/$file");
        }
        
        // Hapus dari tb_surat_cuti
        $querySuratCuti = mysqli_query($koneksi, "DELETE FROM tb_surat_cuti WHERE id = '$id'");
        if (!$querySuratCuti) {
            throw new Exception("Error deleting from tb_surat_cuti: " . mysqli_error($koneksi));
        }
        
        // Hapus dari tb_cuti
        $queryCuti = mysqli_query($koneksi, "DELETE FROM tb_cuti WHERE id = '$idCuti'");
        if (!$queryCuti) {
            throw new Exception("Error deleting from tb_cuti: " . mysqli_error($koneksi));
        }
        
        mysqli_commit($koneksi);
        echo "<script>
                alert('Data cuti berhasil dihapus.');
                window.location.href='?halaman=pengajuan_cuti';
              </script>";
        
    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        echo "<script>
                alert('Error: " . addslashes($e->getMessage()) . "');
                window.location.href='?halaman=pengajuan_cuti';
              </script>";
    }
}
?>