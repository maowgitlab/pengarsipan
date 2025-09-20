<div class="pagetitle">
    <h1>Pegawai</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item active">Pegawai</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section">
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Data Pegawai</h5>
                    <div class="d-flex justify-content-between my-3">
                        <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">Tambah Pegawai</button>
                        <?php endif; ?>
                        <a href="report/cetak_pegawai.php" target="_blank" class="btn btn-sm btn-primary"><i class="bi bi-printer"></i></a>
                    </div>
                    <table class="table table-hover" id="tabelData">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">NIP</th>
                                <th scope="col">Nama Pegawai</th>
                                <th scope="col">Bidang</th>
                                <th scope="col">Tempat Lahir</th>
                                <th scope="col">Jabaatan</th>
                                <th scope="col">Nomer HP</th>
                                <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                                    <th scope="col">Aksi</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1;
                            $data = mysqli_query($koneksi, "SELECT tb_pegawai.*, tb_bidang.nama_bidang, tb_bidang.id AS id_bidang FROM tb_pegawai JOIN tb_bidang ON tb_pegawai.id_bidang = tb_bidang.id order by tb_pegawai.id desc");
                            foreach ($data as $row) : ?>
                                <tr>
                                    <th scope="row"><?= $no++; ?></th>
                                    <td><?= $row['nip']; ?></td>
                                    <td><?= $row['nama_pegawai']; ?></td>
                                    <td><?= $row['nama_bidang']; ?></td>
                                    <td><?= $row['tempat_lahir']; ?></td>
                                    <td><?= $row['jabatan']; ?></td>
                                    <td><?= $row['no_telp']; ?></td>
                                    <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" onclick="editData('<?= $row['id']; ?>', '<?= $row['nip']; ?>', '<?= $row['nama_pegawai']; ?>', '<?= $row['id_bidang']; ?>', '<?= $row['tempat_lahir']; ?>', '<?= $row['jabatan']; ?>', '<?= $row['no_telp']; ?>', '<?= $row['username']; ?>')" data-bs-toggle="modal" data-bs-target="#modalEdit"><i class="bi bi-pencil-square"></i></button>
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
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form Tambah Pegawai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post">
                    <input type="hidden" name="tambah" value="tambah">
                    <div class="col-12">
                        <label for="nip" class="form-label">NIP</label>
                        <input type="text" class="form-control" id="nip" name="nip" required>
                    </div>
                    <div class="col-12">
                        <label for="nama_pegawai" class="form-label">Nama Pegawai</label>
                        <input type="text" class="form-control" id="nama_pegawai" name="nama_pegawai" required>
                    </div>
                    <div class="col-12">
                        <label for="id_bidang" class="form-label">Bidang</label>
                        <select class="form-select" id="id_bidang" name="id_bidang">
                            <option selected disabled>Pilih Bidang</option>
                            <?php $data = mysqli_query($koneksi, "SELECT * FROM tb_bidang");
                            foreach ($data as $row) : ?>
                                <option value="<?= $row['id']; ?>"><?= $row['nama_bidang']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                        <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" required>
                    </div>
                    <div class="col-12">
                        <label for="jabatan" class="form-label">Jabatan</label>
                        <input type="text" class="form-control" id="jabatan" name="jabatan" required>
                    </div>
                    <div class="col-12">
                        <label for="no_hp" class="form-label">Nomer HP</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                    </div>
                    <div class="col-12">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
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
                <h5 class="modal-title">Form Edit Pegawai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="row g-3" method="post">
                    <input type="hidden" name="edit" value="edit">
                    <input type="hidden" name="idEdit" id="idEdit">
                    <div class="col-12">
                        <label for="nip_edit" class="form-label">NIP</label>
                        <input type="text" class="form-control" id="nip_edit" name="nip_edit">
                    </div>
                    <div class="col-12">
                        <label for="nama_pegawai_edit" class="form-label">Nama Pegawai</label>
                        <input type="text" class="form-control" id="nama_pegawai_edit" name="nama_pegawai_edit">
                    </div>
                    <div class="col-12">
                        <label for="id_bidang_edit" class="form-label">Bidang</label>
                        <select class="form-select" id="id_bidang_edit" name="id_bidang_edit">
                            <option disabled>Pilih Bidang</option>
                            <?php $data = mysqli_query($koneksi, "SELECT * FROM tb_bidang");
                            foreach ($data as $row) : ?>
                                <option value="<?= $row['id']; ?>"><?= $row['nama_bidang']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="tempat_lahir_edit" class="form-label">Tempat Lahir</label>
                        <input type="text" class="form-control" id="tempat_lahir_edit" name="tempat_lahir_edit">
                    </div>
                    <div class="col-12">
                        <label for="jabatan_edit" class="form-label">Jabatan</label>
                        <input type="text" class="form-control" id="jabatan_edit" name="jabatan_edit">
                    </div>
                    <div class="col-12">
                        <label for="no_hp_edit" class="form-label">Nomer HP</label>
                        <input type="text" class="form-control" id="no_hp_edit" name="no_hp_edit">
                    </div>
                    <div class="col-12">
                        <label for="username_edit" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username_edit" name="username_edit">
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
            </div>
            </form>
        </div>
    </div>
</div>
</div>
<script>
    function editData(id, nip, nama_pegawai, id_bidang, tempat_lahir, jabatan, no_hp, username) {
        document.getElementById('idEdit').value = id;
        document.getElementById('nip_edit').value = nip;
        document.getElementById('nama_pegawai_edit').value = nama_pegawai;
        document.getElementById('tempat_lahir_edit').value = tempat_lahir;  
        document.getElementById('no_hp_edit').value = no_hp;
        document.getElementById('username_edit').value = username;
        let inputJabatan = document.getElementById('jabatan_edit');
        inputJabatan.value = jabatan;
        if (jabatan == 'admin') {
            inputJabatan.readOnly = true;
            inputJabatan.style.backgroundColor = '#e5e5e5';
        } else {
            inputJabatan.readOnly = false;
            inputJabatan.style.backgroundColor = '#fff';
        }

        // Menemukan elemen select bidang
        let selectBidang = document.getElementById('id_bidang_edit');

        // Mengatur nilai yang sesuai dengan id bidang pegawai
        for (let i = 0; i < selectBidang.options.length; i++) {
            if (selectBidang.options[i].value == id_bidang) {
                selectBidang.options[i].selected = true;
                break;
            }
        }
    }

    function hapusData(id) {
        document.getElementById('idHapus').value = id;
    }
</script>
<?php
if (isset($_POST['tambah'])) {
    $nip = $_POST['nip'];
    $namaPegawai = $_POST['nama_pegawai'];
    $idBidang = $_POST['id_bidang'];
    $tempatLahir = $_POST['tempat_lahir'];
    $jabatan = $_POST['jabatan'];
    $noHp = $_POST['no_hp'];
    $username = $_POST['username'];
    $password = md5('12345');

    $query = mysqli_query($koneksi, "INSERT INTO tb_pegawai VALUES(NULL, '$nip', '$idBidang', '$namaPegawai', '$tempatLahir', '$jabatan', '$noHp', '$username', '$password')");

    if ($query) {
        echo "<script>
                alert('Data Pegawai Berhasil Ditambahkan');
                window.location.href='?halaman=pegawai';
            </script>";
    }
}

if (isset($_POST['edit'])) {
    $id = $_POST['idEdit'];
    $nip = $_POST['nip_edit'];
    $namaPegawai = $_POST['nama_pegawai_edit'];
    $idBidang = $_POST['id_bidang_edit'];
    $tempatLahir = $_POST['tempat_lahir_edit'];
    $jabatan = $_POST['jabatan_edit'];
    $noHp = $_POST['no_hp_edit'];
    $username = $_POST['username_edit'];

    $query = mysqli_query($koneksi, "UPDATE tb_pegawai SET nip='$nip', nama_pegawai='$namaPegawai', id_bidang='$idBidang', tempat_lahir='$tempatLahir', jabatan='$jabatan', no_telp='$noHp', username='$username' WHERE id='$id'");

    if ($query) {
        echo "<script>
                alert('Data Pegawai Berhasil Diubah');
                window.location.href='?halaman=pegawai';
            </script>";
    }
}

if (isset($_POST['hapus'])) {
    $id = $_POST['idHapus'];

    $query = mysqli_query($koneksi, "DELETE FROM tb_pegawai WHERE id='$id'");

    if ($query) {
        echo "<script>
                alert('Data Pegawai Berhasil Dihapus');
                window.location.href='?halaman=pegawai';
            </script>";
    }
}
?>