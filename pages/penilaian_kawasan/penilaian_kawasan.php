<?php
$pegawaiList = [];
$pegawaiQuery = mysqli_query(
    $koneksi,
    "SELECT p.id, p.nama_pegawai, p.jabatan, b.nama_bidang
     FROM tb_pegawai p
     LEFT JOIN tb_bidang b ON p.id_bidang = b.id
     ORDER BY p.nama_pegawai"
);

if ($pegawaiQuery) {
    while ($pegawaiRow = mysqli_fetch_assoc($pegawaiQuery)) {
        $pegawaiList[] = [
            'id' => (string) $pegawaiRow['id'],
            'nama' => $pegawaiRow['nama_pegawai'] ?? '-',
            'jabatan' => $pegawaiRow['jabatan'] ?? '',
            'bidang' => $pegawaiRow['nama_bidang'] ?? ''
        ];
    }
}
?>
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
                                <th scope="col">Detail</th>
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
                            foreach ($data as $row) :
                                $penilaianId = (int) $row['id_penilaian'];
                                $periodeLabel = '-';
                                if (!empty($row['bulan']) && !empty($row['tahun'])) {
                                    $periodeLabel = date('F', mktime(0, 0, 0, (int) $row['bulan'], 1)) . ' ' . $row['tahun'];
                                }

                                $teamMembers = [];
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
                                            'id' => (string) $teamRow['id_pegawai'],
                                            'nama' => $teamRow['nama_pegawai'] ?? '-',
                                            'jabatan' => $teamRow['jabatan'] ?? '',
                                            'bidang' => $teamRow['nama_bidang'] ?? ''
                                        ];
                                    }
                                }

                                $attachmentsRaw = [];
                                $attachmentsQuery = mysqli_query(
                                    $koneksi,
                                    "SELECT id, file_name, original_name
                                     FROM penilaian_kawasan_files
                                     WHERE id_penilaian = '$penilaianId'
                                     ORDER BY id ASC"
                                );

                                if ($attachmentsQuery) {
                                    while ($attachmentRow = mysqli_fetch_assoc($attachmentsQuery)) {
                                        $attachmentsRaw[] = $attachmentRow;
                                    }
                                }

                                if (empty($attachmentsRaw) && !empty($row['bukti_file'])) {
                                    $legacyFileName = mysqli_real_escape_string($koneksi, $row['bukti_file']);
                                    mysqli_query(
                                        $koneksi,
                                        "INSERT INTO penilaian_kawasan_files (id_penilaian, file_name, original_name)
                                         VALUES ('$penilaianId', '$legacyFileName', '$legacyFileName')"
                                    );

                                    $attachmentsRaw = [];
                                    $attachmentsQuery = mysqli_query(
                                        $koneksi,
                                        "SELECT id, file_name, original_name
                                         FROM penilaian_kawasan_files
                                         WHERE id_penilaian = '$penilaianId'
                                         ORDER BY id ASC"
                                    );

                                    if ($attachmentsQuery) {
                                        while ($attachmentRow = mysqli_fetch_assoc($attachmentsQuery)) {
                                            $attachmentsRaw[] = $attachmentRow;
                                        }
                                    }
                                }

                                $attachments = [];
                                foreach ($attachmentsRaw as $attachmentRow) {
                                    $fileName = $attachmentRow['file_name'] ?? '';
                                    $originalName = $attachmentRow['original_name'] ?? '';
                                    if (empty($fileName)) {
                                        continue;
                                    }

                                    $attachments[] = [
                                        'id' => (string) $attachmentRow['id'],
                                        'file_name' => $fileName,
                                        'original_name' => $originalName ?: $fileName,
                                        'url' => 'assets/file/bukti/' . $fileName
                                    ];
                                }

                                $attachmentsCount = count($attachments);
                                $firstAttachment = $attachments[0] ?? null;
                                $additionalAttachmentCount = $attachmentsCount > 0 ? $attachmentsCount - 1 : 0;

                                $detailPayload = [
                                    'nama_kawasan' => $row['nama_kawasan'],
                                    'nama_indikator' => $row['nama_indikator'],
                                    'periode' => $periodeLabel,
                                    'nilai' => $row['nilai'],
                                    'keterangan' => $row['keterangan'],
                                    'tanggal_penilaian' => $row['tanggal_penilaian'],
                                    'team' => $teamMembers,
                                    'attachments' => $attachments
                                ];

                                $detailJson = htmlspecialchars(json_encode($detailPayload, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP), ENT_QUOTES, 'UTF-8');

                                $editPayload = [
                                    'team_ids' => array_column($teamMembers, 'id'),
                                    'attachments' => $attachments,
                                    'primary_file' => $attachments[0]['file_name'] ?? ''
                                ];

                                $editJson = htmlspecialchars(json_encode($editPayload, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP), ENT_QUOTES, 'UTF-8');
                            ?>
                                <tr>
                                    <th scope="row"><?= $no++; ?></th>
                                    <td><?= htmlspecialchars($row['nama_kawasan']); ?></td>
                                    <td><?= htmlspecialchars($row['nama_indikator']); ?></td>
                                    <td><?= htmlspecialchars($periodeLabel); ?></td>
                                    <td><?= htmlspecialchars($row['nilai']); ?></td>
                                    <td><?= htmlspecialchars($row['keterangan']); ?></td>
                                    <td><?= htmlspecialchars($row['tanggal_penilaian']); ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#modalDetailPenilaian" data-detail="<?= $detailJson; ?>" onclick="showPenilaianDetail(this)">
                                            Detail
                                        </button>
                                    </td>
                                    <td>
                                        <?php if ($firstAttachment) : ?>
                                            <a href="<?= htmlspecialchars($firstAttachment['url']); ?>" target="_blank">
                                                <?= htmlspecialchars($firstAttachment['original_name']); ?>
                                            </a>
                                            <?php if ($additionalAttachmentCount > 0) : ?>
                                                <span class="badge bg-secondary ms-1">+<?= $additionalAttachmentCount; ?> lagi</span>
                                            <?php endif; ?>
                                        <?php else : ?>
                                            Tidak ada
                                        <?php endif; ?>
                                    </td>
                                    <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary" data-edit-detail="<?= $editJson; ?>" onclick="editData('<?= $row['id_penilaian']; ?>', '<?= $row['id_kawasan']; ?>', '<?= $row['id_indikator']; ?>', '<?= $row['id_periode']; ?>', '<?= $row['nilai']; ?>', '<?= addslashes($row['keterangan']); ?>', '<?= $row['tanggal_penilaian']; ?>', this.dataset.editDetail)" data-bs-toggle="modal" data-bs-target="#modalEdit"><i class="bi bi-pencil-square"></i></button>
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
                        <label for="tim_penilai" class="form-label">Tim Penilai</label>
                        <select class="form-select" id="tim_penilai" name="tim_penilai[]" multiple <?= empty($pegawaiList) ? '' : 'required'; ?>>
                            <?php foreach ($pegawaiList as $pegawai) :
                                $infoParts = array_filter([$pegawai['jabatan'], $pegawai['bidang']]);
                                $infoText = $infoParts ? ' (' . implode(' - ', $infoParts) . ')' : '';
                            ?>
                                <option value="<?= htmlspecialchars($pegawai['id']); ?>"><?= htmlspecialchars($pegawai['nama'] . $infoText); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-text text-muted">Pilih minimal satu anggota. Gunakan Ctrl/Cmd untuk memilih lebih dari satu.</small>
                        <?php if (empty($pegawaiList)) : ?>
                            <small class="text-danger d-block">Belum ada data pegawai yang dapat dipilih.</small>
                        <?php endif; ?>
                    </div>
                    <div class="col-12">
                        <label for="tanggal_penilaian" class="form-label">Tanggal Penilaian</label>
                        <input type="date" class="form-control" id="tanggal_penilaian" name="tanggal_penilaian" required>
                    </div>
                    <div class="col-12">
                        <label for="bukti_file" class="form-label">Bukti Penilaian (PDF/Gambar)</label>
                        <input type="file" class="form-control" id="bukti_file" name="bukti_file[]" accept=".pdf,.jpg,.jpeg,.png" multiple required>
                        <small class="form-text text-muted">Unggah minimal satu file (maks. 5MB per file). Format: PDF, JPG, JPEG, PNG.</small>
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
                        <label for="tim_penilai_edit" class="form-label">Tim Penilai</label>
                        <select class="form-select" id="tim_penilai_edit" name="tim_penilai_edit[]" multiple <?= empty($pegawaiList) ? '' : 'required'; ?>>
                            <?php foreach ($pegawaiList as $pegawai) :
                                $infoParts = array_filter([$pegawai['jabatan'], $pegawai['bidang']]);
                                $infoText = $infoParts ? ' (' . implode(' - ', $infoParts) . ')' : '';
                            ?>
                                <option value="<?= htmlspecialchars($pegawai['id']); ?>"><?= htmlspecialchars($pegawai['nama'] . $infoText); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-text text-muted">Pilih minimal satu anggota. Gunakan Ctrl/Cmd untuk memilih lebih dari satu.</small>
                        <?php if (empty($pegawaiList)) : ?>
                            <small class="text-danger d-block">Belum ada data pegawai yang dapat dipilih.</small>
                        <?php endif; ?>
                    </div>
                    <div class="col-12">
                        <label for="tanggal_penilaian_edit" class="form-label">Tanggal Penilaian</label>
                        <input type="date" class="form-control" id="tanggal_penilaian_edit" name="tanggal_penilaian_edit" required>
                    </div>
                    <div class="col-12">
                        <label for="bukti_file_edit" class="form-label">Bukti Penilaian (PDF/Gambar)</label>
                        <input type="file" class="form-control" id="bukti_file_edit" name="bukti_file_edit[]" accept=".pdf,.jpg,.jpeg,.png" multiple>
                        <small class="form-text text-muted">Tambahkan file baru bila diperlukan (maks. 5MB per file). Format: PDF, JPG, JPEG, PNG.</small>
                        <div class="mt-2 d-none" id="existingFilesWrapper">
                            <label class="form-label">Bukti Tersimpan</label>
                            <div id="existingFilesContainer" class="border rounded p-2 bg-light small"></div>
                            <small class="form-text text-muted">Centang file yang ingin dihapus.</small>
                        </div>
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

<!-- Modal Detail Penilaian -->
<div class="modal fade" id="modalDetailPenilaian" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Penilaian Kawasan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <p class="mb-1"><strong>Nama Kawasan:</strong> <span id="detailPenilaianNamaKawasan">-</span></p>
                    <p class="mb-1"><strong>Indikator:</strong> <span id="detailPenilaianIndikator">-</span></p>
                    <p class="mb-1"><strong>Periode:</strong> <span id="detailPenilaianPeriode">-</span></p>
                    <p class="mb-1"><strong>Tanggal Penilaian:</strong> <span id="detailPenilaianTanggal">-</span></p>
                    <p class="mb-1"><strong>Nilai:</strong> <span id="detailPenilaianNilai">-</span></p>
                    <p class="mb-0"><strong>Keterangan:</strong> <span id="detailPenilaianKeterangan">-</span></p>
                </div>
                <div class="row g-3">
                    <div class="col-lg-6">
                        <h6>Tim Penilai</h6>
                        <ul id="detailTimPenilai" class="list-group list-group-flush small">
                            <li class="list-group-item text-muted">Belum ada data tim.</li>
                        </ul>
                    </div>
                    <div class="col-lg-6">
                        <h6>Lampiran Bukti</h6>
                        <ul id="detailLampiranPenilaian" class="list-group list-group-flush small">
                            <li class="list-group-item text-muted">Tidak ada lampiran.</li>
                        </ul>
                    </div>
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
    function formatDisplayValue(value) {
        return value === null || value === undefined || value === '' ? '-' : value;
    }

    function showPenilaianDetail(button) {
        let detailData = {};

        try {
            detailData = JSON.parse(button.getAttribute('data-detail') || '{}');
        } catch (error) {
            detailData = {};
        }

        document.getElementById('detailPenilaianNamaKawasan').textContent = formatDisplayValue(detailData.nama_kawasan);
        document.getElementById('detailPenilaianIndikator').textContent = formatDisplayValue(detailData.nama_indikator);
        document.getElementById('detailPenilaianPeriode').textContent = formatDisplayValue(detailData.periode);
        document.getElementById('detailPenilaianTanggal').textContent = formatDisplayValue(detailData.tanggal_penilaian);
        document.getElementById('detailPenilaianNilai').textContent = formatDisplayValue(detailData.nilai);
        document.getElementById('detailPenilaianKeterangan').textContent = formatDisplayValue(detailData.keterangan);

        const teamList = document.getElementById('detailTimPenilai');
        const lampiranList = document.getElementById('detailLampiranPenilaian');

        teamList.innerHTML = '';
        const teamData = Array.isArray(detailData.team) ? detailData.team : [];
        if (teamData.length) {
            teamData.forEach(function (member) {
                const item = document.createElement('li');
                item.className = 'list-group-item';
                const name = formatDisplayValue(member.nama);
                const infoParts = [];
                if (member.jabatan) {
                    infoParts.push(member.jabatan);
                }
                if (member.bidang) {
                    infoParts.push(member.bidang);
                }
                const infoText = infoParts.length ? ' (' + infoParts.join(' - ') + ')' : '';
                item.textContent = name + infoText;
                teamList.appendChild(item);
            });
        } else {
            const emptyItem = document.createElement('li');
            emptyItem.className = 'list-group-item text-muted';
            emptyItem.textContent = 'Belum ada data tim.';
            teamList.appendChild(emptyItem);
        }

        lampiranList.innerHTML = '';
        const attachmentData = Array.isArray(detailData.attachments) ? detailData.attachments : [];
        if (attachmentData.length) {
            attachmentData.forEach(function (file) {
                const item = document.createElement('li');
                item.className = 'list-group-item';
                const link = document.createElement('a');
                link.href = file.url || '#';
                link.target = '_blank';
                link.rel = 'noopener noreferrer';
                link.textContent = formatDisplayValue(file.original_name || file.file_name || file.url);
                item.appendChild(link);
                lampiranList.appendChild(item);
            });
        } else {
            const emptyAttachment = document.createElement('li');
            emptyAttachment.className = 'list-group-item text-muted';
            emptyAttachment.textContent = 'Tidak ada lampiran.';
            lampiranList.appendChild(emptyAttachment);
        }
    }

    function editData(id, id_kawasan, id_indikator, id_periode, nilai, keterangan, tanggal_penilaian, detailJson) {
        document.getElementById('idEdit').value = id;
        document.getElementById('id_kawasan_edit').value = id_kawasan;
        document.getElementById('id_indikator_edit').value = id_indikator;
        document.getElementById('id_periode_edit').value = id_periode;
        document.getElementById('nilai_edit').value = nilai;
        document.getElementById('keterangan_edit').value = keterangan;
        document.getElementById('tanggal_penilaian_edit').value = tanggal_penilaian;

        let detailData = {};
        try {
            detailData = JSON.parse(detailJson || '{}');
        } catch (error) {
            detailData = {};
        }

        const teamSelect = document.getElementById('tim_penilai_edit');
        const teamIds = Array.isArray(detailData.team_ids) ? detailData.team_ids.map(String) : [];
        if (teamSelect) {
            Array.from(teamSelect.options).forEach(function (option) {
                option.selected = teamIds.includes(option.value);
            });
        }

        const wrapper = document.getElementById('existingFilesWrapper');
        const container = document.getElementById('existingFilesContainer');

        if (container) {
            container.innerHTML = '';
        }

        if (wrapper) {
            wrapper.classList.add('d-none');
        }

        const attachments = Array.isArray(detailData.attachments) ? detailData.attachments : [];
        if (attachments.length && container && wrapper) {
            attachments.forEach(function (file) {
                const formCheck = document.createElement('div');
                formCheck.className = 'form-check';

                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.className = 'form-check-input';
                checkbox.name = 'hapus_bukti[]';
                checkbox.value = file.id;
                const checkboxId = 'hapus_bukti_' + file.id;
                checkbox.id = checkboxId;

                const label = document.createElement('label');
                label.className = 'form-check-label';
                label.setAttribute('for', checkboxId);

                const link = document.createElement('a');
                link.href = file.url || '#';
                link.target = '_blank';
                link.rel = 'noopener noreferrer';
                link.textContent = formatDisplayValue(file.original_name || file.file_name || file.url);

                label.appendChild(link);
                formCheck.appendChild(checkbox);
                formCheck.appendChild(label);
                container.appendChild(formCheck);
            });

            wrapper.classList.remove('d-none');
        }

        document.getElementById('bukti_file_lama').value = detailData.primary_file || '';
    }

    function hapusData(id) {
        document.getElementById('idHapus').value = id;
    }
</script>

<?php
if (!function_exists('hapusFileBukti')) {
    function hapusFileBukti(array $files): void
    {
        foreach ($files as $file) {
            if (empty($file['file_name'])) {
                continue;
            }

            $path = 'assets/file/bukti/' . $file['file_name'];
            if (file_exists($path)) {
                @unlink($path);
            }
        }
    }
}

if (!function_exists('prosesUploadBuktiPenilaian')) {
    function prosesUploadBuktiPenilaian(array $fileInput, bool $required = false): array
    {
        $allowedExtensions = ['pdf', 'jpg', 'jpeg', 'png'];
        $allowedMime = ['application/pdf', 'image/jpeg', 'image/png'];
        $maxSize = 5 * 1024 * 1024;
        $uploadedFiles = [];

        if (!isset($fileInput['name']) || !is_array($fileInput['name'])) {
            return $required ? ['files' => [], 'error' => 'Bukti penilaian minimal satu file.'] : ['files' => [], 'error' => null];
        }

        $fileCount = count($fileInput['name']);
        $finfo = function_exists('finfo_open') ? finfo_open(FILEINFO_MIME_TYPE) : null;

        for ($i = 0; $i < $fileCount; $i++) {
            if (!isset($fileInput['error'][$i]) || $fileInput['error'][$i] === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            if ($fileInput['error'][$i] !== UPLOAD_ERR_OK) {
                if ($finfo) {
                    finfo_close($finfo);
                }
                hapusFileBukti($uploadedFiles);
                return ['files' => [], 'error' => 'Terjadi kesalahan saat mengunggah file bukti.'];
            }

            $tmpName = $fileInput['tmp_name'][$i];
            $originalName = $fileInput['name'][$i];
            $size = $fileInput['size'][$i];
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            $mimeType = $finfo ? finfo_file($finfo, $tmpName) : (function_exists('mime_content_type') ? mime_content_type($tmpName) : ($fileInput['type'][$i] ?? ''));

            if ($mimeType === 'image/jpg') {
                $mimeType = 'image/jpeg';
            }

            if (!in_array($extension, $allowedExtensions, true) || !in_array($mimeType, $allowedMime, true) || $size > $maxSize) {
                if ($finfo) {
                    finfo_close($finfo);
                }
                hapusFileBukti($uploadedFiles);
                return ['files' => [], 'error' => 'File tidak valid. Hanya PDF, JPG, JPEG, atau PNG dengan ukuran maksimum 5MB yang diperbolehkan.'];
            }

            $uniqueName = uniqid('bukti_', true) . '.' . $extension;
            $destination = 'assets/file/bukti/' . $uniqueName;

            if (!move_uploaded_file($tmpName, $destination)) {
                if ($finfo) {
                    finfo_close($finfo);
                }
                hapusFileBukti($uploadedFiles);
                return ['files' => [], 'error' => 'Gagal mengunggah file bukti.'];
            }

            $uploadedFiles[] = [
                'file_name' => $uniqueName,
                'original_name' => $originalName
            ];
        }

        if ($finfo) {
            finfo_close($finfo);
        }

        if ($required && empty($uploadedFiles)) {
            return ['files' => [], 'error' => 'Bukti penilaian minimal satu file.'];
        }

        return ['files' => $uploadedFiles, 'error' => null];
    }
}

if (isset($_POST['tambah'])) {
    $id_kawasan = mysqli_real_escape_string($koneksi, $_POST['id_kawasan']);
    $id_indikator = mysqli_real_escape_string($koneksi, $_POST['id_indikator']);
    $id_periode = mysqli_real_escape_string($koneksi, $_POST['id_periode']);
    $nilai = mysqli_real_escape_string($koneksi, $_POST['nilai']);
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
    $tanggal_penilaian = mysqli_real_escape_string($koneksi, $_POST['tanggal_penilaian']);

    $tim_penilai = isset($_POST['tim_penilai']) ? array_unique(array_filter(array_map('intval', $_POST['tim_penilai']))) : [];
    if (empty($tim_penilai)) {
        echo "<script>alert('Tim penilai wajib dipilih.');</script>";
        exit;
    }

    $uploadResult = prosesUploadBuktiPenilaian($_FILES['bukti_file'] ?? [], true);
    if ($uploadResult['error']) {
        echo "<script>alert('" . addslashes($uploadResult['error']) . "');</script>";
        exit;
    }

    $uploadedFiles = $uploadResult['files'];
    $primaryFile = $uploadedFiles[0]['file_name'] ?? '';
    $primaryFileEsc = mysqli_real_escape_string($koneksi, $primaryFile);

    $query = mysqli_query($koneksi, "INSERT INTO penilaian_kawasan (id_kawasan, id_indikator, id_periode, nilai, keterangan, tanggal_penilaian, bukti_file)
                                    VALUES ('$id_kawasan', '$id_indikator', '$id_periode', '$nilai', '$keterangan', '$tanggal_penilaian', '$primaryFileEsc')");

    if ($query) {
        $penilaianId = mysqli_insert_id($koneksi);

        foreach ($tim_penilai as $pegawaiId) {
            $pegawaiIdEsc = mysqli_real_escape_string($koneksi, (string) $pegawaiId);
            mysqli_query($koneksi, "INSERT INTO penilaian_kawasan_tim (id_penilaian, id_pegawai) VALUES ('$penilaianId', '$pegawaiIdEsc')");
        }

        foreach ($uploadedFiles as $file) {
            $fileNameEsc = mysqli_real_escape_string($koneksi, $file['file_name']);
            $originalEsc = mysqli_real_escape_string($koneksi, $file['original_name']);
            mysqli_query($koneksi, "INSERT INTO penilaian_kawasan_files (id_penilaian, file_name, original_name) VALUES ('$penilaianId', '$fileNameEsc', '$originalEsc')");
        }

        echo "<script>alert('Data Penilaian Kawasan Berhasil Ditambahkan'); window.location.href='?halaman=penilaian_kawasan';</script>";
    } else {
        hapusFileBukti($uploadedFiles);
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

    $tim_penilai = isset($_POST['tim_penilai_edit']) ? array_unique(array_filter(array_map('intval', $_POST['tim_penilai_edit']))) : [];
    if (empty($tim_penilai)) {
        echo "<script>alert('Tim penilai wajib dipilih.');</script>";
        exit;
    }

    $hapusBukti = isset($_POST['hapus_bukti']) ? array_unique(array_filter(array_map('intval', $_POST['hapus_bukti']))) : [];

    foreach ($hapusBukti as $fileId) {
        $fileIdEsc = mysqli_real_escape_string($koneksi, (string) $fileId);
        $fileQuery = mysqli_query($koneksi, "SELECT file_name FROM penilaian_kawasan_files WHERE id='$fileIdEsc' AND id_penilaian='$id'");
        if ($fileQuery && ($fileRow = mysqli_fetch_assoc($fileQuery))) {
            $filePath = 'assets/file/bukti/' . $fileRow['file_name'];
            if (!empty($fileRow['file_name']) && file_exists($filePath)) {
                @unlink($filePath);
            }
        }
        mysqli_query($koneksi, "DELETE FROM penilaian_kawasan_files WHERE id='$fileIdEsc' AND id_penilaian='$id'");
    }

    $uploadResult = prosesUploadBuktiPenilaian($_FILES['bukti_file_edit'] ?? [], false);
    if ($uploadResult['error']) {
        echo "<script>alert('" . addslashes($uploadResult['error']) . "');</script>";
        exit;
    }

    $newFiles = $uploadResult['files'];

    foreach ($newFiles as $file) {
        $fileNameEsc = mysqli_real_escape_string($koneksi, $file['file_name']);
        $originalEsc = mysqli_real_escape_string($koneksi, $file['original_name']);
        mysqli_query($koneksi, "INSERT INTO penilaian_kawasan_files (id_penilaian, file_name, original_name) VALUES ('$id', '$fileNameEsc', '$originalEsc')");
    }

    mysqli_query($koneksi, "DELETE FROM penilaian_kawasan_tim WHERE id_penilaian='$id'");
    foreach ($tim_penilai as $pegawaiId) {
        $pegawaiIdEsc = mysqli_real_escape_string($koneksi, (string) $pegawaiId);
        mysqli_query($koneksi, "INSERT INTO penilaian_kawasan_tim (id_penilaian, id_pegawai) VALUES ('$id', '$pegawaiIdEsc')");
    }

    $primaryFile = '';
    $primaryQuery = mysqli_query($koneksi, "SELECT file_name FROM penilaian_kawasan_files WHERE id_penilaian='$id' ORDER BY id ASC LIMIT 1");
    if ($primaryQuery && ($primaryRow = mysqli_fetch_assoc($primaryQuery))) {
        $primaryFile = $primaryRow['file_name'] ?? '';
    }

    $primaryValue = $primaryFile !== '' ? "'" . mysqli_real_escape_string($koneksi, $primaryFile) . "'" : 'NULL';

    $query = mysqli_query($koneksi, "UPDATE penilaian_kawasan SET id_kawasan='$id_kawasan', id_indikator='$id_indikator',
                                    id_periode='$id_periode', nilai='$nilai', keterangan='$keterangan', tanggal_penilaian='$tanggal_penilaian',
                                    bukti_file=$primaryValue WHERE id_penilaian='$id'");

    if ($query) {
        echo "<script>alert('Data Penilaian Kawasan Berhasil Diubah'); window.location.href='?halaman=penilaian_kawasan';</script>";
    } else {
        echo "<script>alert('Gagal mengubah data: " . addslashes(mysqli_error($koneksi)) . "');</script>";
    }
}

if (isset($_POST['hapus'])) {
    $id = mysqli_real_escape_string($koneksi, $_POST['idHapus']);

    $filesQuery = mysqli_query($koneksi, "SELECT file_name FROM penilaian_kawasan_files WHERE id_penilaian='$id'");
    if ($filesQuery) {
        while ($fileRow = mysqli_fetch_assoc($filesQuery)) {
            if (!empty($fileRow['file_name'])) {
                $filePath = 'assets/file/bukti/' . $fileRow['file_name'];
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }
        }
    }

    mysqli_query($koneksi, "DELETE FROM penilaian_kawasan_files WHERE id_penilaian='$id'");
    mysqli_query($koneksi, "DELETE FROM penilaian_kawasan_tim WHERE id_penilaian='$id'");
    $query = mysqli_query($koneksi, "DELETE FROM penilaian_kawasan WHERE id_penilaian='$id'");

    if ($query) {
        echo "<script>alert('Data Penilaian Kawasan Berhasil Dihapus'); window.location.href='?halaman=penilaian_kawasan';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data: " . addslashes(mysqli_error($koneksi)) . "');</script>";
    }
}
?>