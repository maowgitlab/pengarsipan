<?php
$detailPenilaianKawasan = [];
$detailQuery = mysqli_query(
    $koneksi,
    "SELECT pk.id_penilaian,
            pk.id_kawasan,
            i.nama_indikator,
            pk.nilai,
            pk.keterangan,
            pk.tanggal_penilaian,
            pk.bukti_file,
            p.bulan,
            p.tahun
     FROM penilaian_kawasan pk
     LEFT JOIN indikator_penilaian i ON pk.id_indikator = i.id_indikator
     LEFT JOIN periode_penilaian p ON pk.id_periode = p.id_periode"
);

if ($detailQuery) {
    while ($detailRow = mysqli_fetch_assoc($detailQuery)) {
        $periodeLabel = '-';
        if (!empty($detailRow['bulan']) && !empty($detailRow['tahun'])) {
            $periodeLabel = date('F', mktime(0, 0, 0, (int) $detailRow['bulan'], 1)) . ' ' . $detailRow['tahun'];
        }

        $penilaianId = (int) ($detailRow['id_penilaian'] ?? 0);

        $teamMembers = [];
        if ($penilaianId > 0) {
            $teamQuery = mysqli_query(
                $koneksi,
                "SELECT t.id_pegawai, p.nama_pegawai, p.jabatan, b.nama_bidang
                 FROM penilaian_kawasan_tim t
                 JOIN tb_pegawai p ON t.id_pegawai = p.id
                 LEFT JOIN tb_bidang b ON p.id_bidang = b.id
                 WHERE t.id_penilaian = '$penilaianId'
                 ORDER BY p.nama_pegawai"
            );

            if ($teamQuery) {
                while ($teamRow = mysqli_fetch_assoc($teamQuery)) {
                    $teamMembers[] = [
                        'nama' => $teamRow['nama_pegawai'] ?? '-',
                        'jabatan' => $teamRow['jabatan'] ?? '',
                        'bidang' => $teamRow['nama_bidang'] ?? ''
                    ];
                }
            }
        }

        $attachments = [];
        if ($penilaianId > 0) {
            $attachmentQuery = mysqli_query(
                $koneksi,
                "SELECT file_name, original_name
                 FROM penilaian_kawasan_files
                 WHERE id_penilaian = '$penilaianId'
                 ORDER BY id ASC"
            );

            if ($attachmentQuery) {
                while ($attachmentRow = mysqli_fetch_assoc($attachmentQuery)) {
                    if (empty($attachmentRow['file_name'])) {
                        continue;
                    }

                    $attachments[] = [
                        'file_name' => $attachmentRow['file_name'],
                        'original_name' => $attachmentRow['original_name'] ?: $attachmentRow['file_name'],
                        'url' => 'assets/file/bukti/' . $attachmentRow['file_name']
                    ];
                }
            }

            if (empty($attachments) && !empty($detailRow['bukti_file'])) {
                $legacyName = mysqli_real_escape_string($koneksi, $detailRow['bukti_file']);
                mysqli_query(
                    $koneksi,
                    "INSERT INTO penilaian_kawasan_files (id_penilaian, file_name, original_name)
                     VALUES ('$penilaianId', '$legacyName', '$legacyName')"
                );

                $attachmentQuery = mysqli_query(
                    $koneksi,
                    "SELECT file_name, original_name
                     FROM penilaian_kawasan_files
                     WHERE id_penilaian = '$penilaianId'
                     ORDER BY id ASC"
                );

                if ($attachmentQuery) {
                    while ($attachmentRow = mysqli_fetch_assoc($attachmentQuery)) {
                        if (empty($attachmentRow['file_name'])) {
                            continue;
                        }

                        $attachments[] = [
                            'file_name' => $attachmentRow['file_name'],
                            'original_name' => $attachmentRow['original_name'] ?: $attachmentRow['file_name'],
                            'url' => 'assets/file/bukti/' . $attachmentRow['file_name']
                        ];
                    }
                }
            }
        }

        $detailPenilaianKawasan[$detailRow['id_kawasan']][] = [
            'indikator' => $detailRow['nama_indikator'] ?? '-',
            'nilai' => $detailRow['nilai'],
            'keterangan' => $detailRow['keterangan'] ?? '-',
            'periode' => $periodeLabel,
            'tanggal_penilaian' => $detailRow['tanggal_penilaian'] ?? '-',
            'bukti' => $detailRow['bukti_file'] ? 'assets/file/bukti/' . $detailRow['bukti_file'] : null,
            'bukti_nama' => $detailRow['bukti_file'] ?? '',
            'tim' => $teamMembers,
            'lampiran' => $attachments
        ];
    }
}
?>
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
                                <th scope="col">Detail Penilaian</th>
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
                                    <?php
                                    $detailData = $detailPenilaianKawasan[$row['id_kawasan']] ?? [];
                                    $detailJson = htmlspecialchars(json_encode(array_values($detailData), JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP), ENT_QUOTES, 'UTF-8');
                                    ?>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalDetail" data-detail="<?= $detailJson; ?>" data-kawasan="<?= htmlspecialchars($row['nama_kawasan'], ENT_QUOTES); ?>" data-status="<?= htmlspecialchars($row['status_layak'], ENT_QUOTES); ?>" onclick="showDetail(this)">
                                            Detail
                                        </button>
                                    </td>
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

<!-- Modal Detail -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Penilaian Kawasan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Nama Kawasan:</strong> <span id="detailNamaKawasan">-</span></p>
                <p><strong>Status Layak:</strong> <span id="detailStatusLayak">-</span></p>
                <div id="detailContent" class="table-responsive">
                    <p class="text-muted mb-0">Belum ada penilaian yang tercatat.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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
    function escapeHtml(value) {
        if (typeof value !== 'string') {
            return value === null || value === undefined || value === '' ? '-' : value;
        }

        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };

        return value.replace(/[&<>"']/g, function (m) {
            return map[m];
        });
    }

    function showDetail(button) {
        const namaKawasan = button.getAttribute('data-kawasan') || '-';
        const statusLayak = button.getAttribute('data-status') || '-';
        let detailData = [];

        try {
            detailData = JSON.parse(button.getAttribute('data-detail') || '[]');
        } catch (error) {
            detailData = [];
        }

        document.getElementById('detailNamaKawasan').textContent = namaKawasan;
        document.getElementById('detailStatusLayak').textContent = statusLayak;

        const detailContainer = document.getElementById('detailContent');

        if (!Array.isArray(detailData) || detailData.length === 0) {
            detailContainer.innerHTML = '<p class="text-muted mb-0">Belum ada penilaian yang tercatat.</p>';
            return;
        }

        const rows = detailData.map(function (item, index) {
            const indikator = escapeHtml(item.indikator || '-');
            const nilai = item.nilai !== null && item.nilai !== undefined && item.nilai !== '' ? item.nilai : '-';
            const keterangan = escapeHtml(item.keterangan || '-');
            const periode = escapeHtml(item.periode || '-');
            const tanggal = escapeHtml(item.tanggal_penilaian || '-');
            const timData = Array.isArray(item.tim) ? item.tim : [];
            const timContent = timData.length ? timData.map(function (member) {
                const name = escapeHtml(member.nama || '-');
                const infoParts = [];
                if (member.jabatan) {
                    infoParts.push(escapeHtml(member.jabatan));
                }
                if (member.bidang) {
                    infoParts.push(escapeHtml(member.bidang));
                }
                const infoText = infoParts.length ? ' (' + infoParts.join(' - ') + ')' : '';
                return '<div>' + name + infoText + '</div>';
            }).join('') : '<span class="text-muted">-</span>';

            const lampiranData = Array.isArray(item.lampiran) ? item.lampiran : [];
            let lampiranContent;
            if (lampiranData.length) {
                lampiranContent = lampiranData.map(function (file) {
                    const url = escapeHtml(file.url || '#');
                    const label = escapeHtml(file.original_name || file.file_name || 'Lampiran');
                    return '<div><a href="' + url + '" target="_blank">' + label + '</a></div>';
                }).join('');
            } else if (item.bukti) {
                const legacyLabel = escapeHtml(item.bukti_nama || 'Lihat');
                lampiranContent = '<div><a href="' + escapeHtml(item.bukti) + '" target="_blank">' + legacyLabel + '</a></div>';
            } else {
                lampiranContent = '<span class="text-muted">-</span>';
            }

            return '<tr>' +
                '<td>' + (index + 1) + '</td>' +
                '<td>' + indikator + '</td>' +
                '<td>' + nilai + '</td>' +
                '<td>' + keterangan + '</td>' +
                '<td>' + periode + '</td>' +
                '<td>' + tanggal + '</td>' +
                '<td>' + timContent + '</td>' +
                '<td>' + lampiranContent + '</td>' +
            '</tr>';
        }).join('');

        detailContainer.innerHTML = '<div class="table-responsive">' +
            '<table class="table table-sm table-striped mb-0">' +
                '<thead>' +
                    '<tr>' +
                        '<th>#</th>' +
                        '<th>Indikator</th>' +
                        '<th>Nilai</th>' +
                        '<th>Keterangan</th>' +
                        '<th>Periode</th>' +
                        '<th>Tanggal Penilaian</th>' +
                        '<th>Tim Penilai</th>' +
                        '<th>Lampiran</th>' +
                    '</tr>' +
                '</thead>' +
                '<tbody>' + rows + '</tbody>' +
            '</table>' +
        '</div>';
    }

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