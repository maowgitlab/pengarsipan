<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Aplikasi Pengarsipan</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="assets/img/logo.png" rel="icon">
    <link href="assets/img/logo.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.snow.css" rel="stylesheet">
    <link href="assets/vendor/quill/quill.bubble.css" rel="stylesheet">
    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
    <link href="assets/vendor/simple-datatables/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.css">

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet">

    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
        .search-container {
            position: relative;
            width: 300px;
            margin-right: 20px;
        }
        .search-results {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }
        .search-results .dropdown-item {
            padding: 10px;
            cursor: pointer;
        }
        .search-results .dropdown-item:hover {
            background-color: #f8f9fa;
        }
        .tag-list {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
        }
        .tag {
            background-color: #e9ecef;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 12px;
        }
        .notifications, .log-activity {
            max-height: 400px;
            overflow-y: auto;
            width: 350px;
        }
    </style>
</head>

<body>
    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center">

        <div class="d-flex align-items-center justify-content-between">
            <a href="index.html" class="logo d-flex align-items-center">
                <img src="assets/img/logo.png" alt="">
                <span class="d-none d-lg-block">DisPerkim</span>
            </a>
            <i class="bi bi-list toggle-sidebar-btn"></i>
        </div><!-- End Logo -->

        <!-- Search Bar -->
        <div class="search-container no-print">
            <input type="text" class="form-control" id="quickSearch" placeholder="Cari dokumen...">
            <div class="search-results" id="searchResults"></div>
        </div>

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">
                <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                    <!-- Notification Dropdown -->
                    <?php
                    $dataCuti = mysqli_query($koneksi, "SELECT tb_surat_cuti.*, tb_cuti.*, tb_pegawai.* FROM tb_surat_cuti JOIN tb_cuti JOIN tb_pegawai ON tb_surat_cuti.id_cuti = tb_cuti.id AND tb_cuti.nip = tb_pegawai.nip WHERE tb_surat_cuti.status = 'diajukan' LIMIT 5");
                    $notifCuti = mysqli_num_rows($dataCuti);
                    $dataMasuk = mysqli_query($koneksi, "SELECT tb_surat_masuk.*, tb_surat.* FROM tb_surat_masuk JOIN tb_surat ON tb_surat_masuk.id_surat = tb_surat.id WHERE tb_surat.jenis_surat = 'surat_masuk' ORDER BY tb_surat_masuk.tanggal_masuk DESC LIMIT 5");
                    $notifMasuk = mysqli_num_rows($dataMasuk);
                    $dataKeluar = mysqli_query($koneksi, "SELECT tb_surat_keluar.*, tb_surat.* FROM tb_surat_keluar JOIN tb_surat ON tb_surat_keluar.id_surat = tb_surat.id WHERE tb_surat.jenis_surat = 'surat_keluar' ORDER BY tb_surat_keluar.tanggal_kirim DESC LIMIT 5");
                    $notifKeluar = mysqli_num_rows($dataKeluar);
                    $totalNotif = $notifCuti + $notifMasuk + $notifKeluar;
                    ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-bell"></i>
                            <?php if ($totalNotif > 0) : ?>
                                <span class="badge bg-danger badge-number"><?= $totalNotif ?></span>
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                            <li class="dropdown-header">
                                Terdapat <?= $totalNotif ?> notifikasi masuk
                                <a href="?halaman=notifikasi"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <?php if ($notifCuti > 0) : ?>
                                <li class="notification-item">
                                    <i class="bi bi-info-circle text-info"></i>
                                    <div>
                                        <h4><a href="?halaman=surat_cuti">Pengajuan Surat Cuti</a></h4>
                                        <p>Terdapat <?= $notifCuti ?> pengajuan cuti baru</p>
                                    </div>
                                </li>
                                <?php foreach ($dataCuti as $rowCuti) : ?>
                                    <li class="notification-item">
                                        <i class="bi bi-info-circle text-info"></i>
                                        <div>
                                            <h4><?= htmlspecialchars($rowCuti['nama_pegawai']) ?></h4>
                                            <p>Mengajukan Surat Cuti (No: <?= htmlspecialchars($rowCuti['no_surat']) ?>)</p>
                                            <div class="tag-list">
                                                <?php
                                                $tags = mysqli_query($koneksi, "SELECT t.tag_name FROM tb_tags t JOIN tb_document_tags dt ON t.id = dt.tag_id WHERE dt.document_type = 'surat_cuti' AND dt.document_id = " . (int)$rowCuti['id']);
                                                while ($row = mysqli_fetch_assoc($tags)) {
                                                    echo "<span class='tag'>" . htmlspecialchars($row['tag_name']) . "</span>";
                                                }
                                                ?>
                                                <a href="#" class="badge bg-secondary ms-1 add-tag" data-type="surat_cuti" data-id="<?= (int)$rowCuti['id'] ?>">Tambah Tag</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <?php if ($notifMasuk > 0) : ?>
                                <li class="notification-item">
                                    <i class="bi bi-envelope text-primary"></i>
                                    <div>
                                        <h4><a href="?halaman=masuk">Surat Masuk</a></h4>
                                        <p>Terdapat <?= $notifMasuk ?> surat masuk baru</p>
                                    </div>
                                </li>
                                <?php foreach ($dataMasuk as $rowMasuk) : ?>
                                    <li class="notification-item">
                                        <i class="bi bi-envelope text-primary"></i>
                                        <div>
                                            <h4><?= htmlspecialchars($rowMasuk['pengirim']) ?></h4>
                                            <p>Perihal: <?= htmlspecialchars($rowMasuk['perihal']) ?> (No: <?= htmlspecialchars($rowMasuk['no_surat']) ?>)</p>
                                            <div class="tag-list">
                                                <?php
                                                $tags = mysqli_query($koneksi, "SELECT t.tag_name FROM tb_tags t JOIN tb_document_tags dt ON t.id = dt.tag_id WHERE dt.document_type = 'surat_masuk' AND dt.document_id = " . (int)$rowMasuk['id']);
                                                while ($row = mysqli_fetch_assoc($tags)) {
                                                    echo "<span class='tag'>" . htmlspecialchars($row['tag_name']) . "</span>";
                                                }
                                                ?>
                                                <a href="#" class="badge bg-secondary ms-1 add-tag" data-type="surat_masuk" data-id="<?= (int)$rowMasuk['id'] ?>">Tambah Tag</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                            <?php if ($notifKeluar > 0) : ?>
                                <li class="notification-item">
                                    <i class="bi bi-envelope-open text-warning"></i>
                                    <div>
                                        <h4><a href="?halaman=keluar">Surat Keluar</a></h4>
                                        <p>Terdapat <?= $notifKeluar ?> surat keluar baru</p>
                                    </div>
                                </li>
                                <?php foreach ($dataKeluar as $rowKeluar) : ?>
                                    <li class="notification-item">
                                        <i class="bi bi-envelope-open text-warning"></i>
                                        <div>
                                            <h4><?= htmlspecialchars($rowKeluar['penerima']) ?></h4>
                                            <p>Perihal: <?= htmlspecialchars($rowKeluar['perihal']) ?> (No: <?= htmlspecialchars($rowKeluar['no_surat']) ?>)</p>
                                            <div class="tag-list">
                                                <?php
                                                $tags = mysqli_query($koneksi, "SELECT t.tag_name FROM tb_tags t JOIN tb_document_tags dt ON t.id = dt.tag_id WHERE dt.document_type = 'surat_keluar' AND dt.document_id = " . (int)$rowKeluar['id']);
                                                while ($row = mysqli_fetch_assoc($tags)) {
                                                    echo "<span class='tag'>" . htmlspecialchars($row['tag_name']) . "</span>";
                                                }
                                                ?>
                                                <a href="#" class="badge bg-secondary ms-1 add-tag" data-type="surat_keluar" data-id="<?= (int)$rowKeluar['id'] ?>">Tambah Tag</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul><!-- End Notification Dropdown Items -->
                    </li>

                    <!-- Log Activity Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-clock-history"></i>
                            <span class="badge bg-info badge-number" id="logCount"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow log-activity">
                            <li class="dropdown-header">
                                Log Aktivitas Login
                                <a href="#" data-bs-toggle="modal" data-bs-target="#logModal"><span class="badge rounded-pill bg-primary p-2 ms-2">View Details</span></a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li class="notification-item">
                                <div id="logSummary"></div>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
                <li class="nav-item dropdown pe-3">
                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
                        <img src="assets/img/profile-img.jpg" alt="Profile" class="rounded-circle">
                        <span class="d-none d-md-block dropdown-toggle ps-2"><?= htmlspecialchars($_SESSION['nama_pegawai']) ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                        <li class="dropdown-header">
                            <h6><?= htmlspecialchars($_SESSION['username']) ?></h6>
                            <span><?= htmlspecialchars($_SESSION['jabatan']) ?></span>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="logout.php">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Logout</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav><!-- End Icons Navigation -->
    </header>

    <!-- Log Activity Modal -->
    <div class="modal fade" id="logModal" tabindex="-1" aria-labelledby="logModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logModalLabel">Log Aktivitas Login</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Username</th>
                                <th>Nama Pegawai</th>
                                <th>Terakhir Login</th>
                                <th>Total Login</th>
                                <th>IP Address</th>
                            </tr>
                        </thead>
                        <tbody id="logTableBody"></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tag Modal -->
    <div class="modal fade" id="tagModal" tabindex="-1" aria-labelledby="tagModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tagModalLabel">Kelola Tags</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="documentType" name="documentType">
                    <input type="hidden" id="documentId" name="documentId">
                    <div class="mb-3">
                        <label for="newTag" class="form-label">Tag Baru</label>
                        <input type="text" class="form-control" id="newTag" placeholder="Nama tag...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tags Sekarang</label>
                        <div id="existingTags" class="tag-list"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveTag">Save Tag</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Search, Tagging, and Log Activity -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#quickSearch').on('input', function() {
                let query = $(this).val().trim();
                if (query.length < 2) {
                    $('#searchResults').hide();
                    return;
                }
                $.ajax({
                    url: 'search_documents.php',
                    method: 'GET',
                    data: { q: query },
                    success: function(data) {
                        $('#searchResults').html(data).show();
                    }
                });
            });

            $(document).on('click', '.add-tag', function(e) {
                e.preventDefault();
                let docType = $(this).data('type');
                let docId = $(this).data('id');
                $('#documentType').val(docType);
                $('#documentId').val(docId);
                $.ajax({
                    url: 'get_tags.php',
                    method: 'GET',
                    data: { document_type: docType, document_id: docId },
                    success: function(data) {
                        $('#existingTags').html(data);
                        $('#tagModal').modal('show');
                    }
                });
            });

            $('#saveTag').click(function() {
                let tagName = $('#newTag').val().trim();
                let docType = $('#documentType').val();
                let docId = $('#documentId').val();
                if (tagName) {
                    $.ajax({
                        url: 'add_tag.php',
                        method: 'POST',
                        data: { tag_name: tagName, document_type: docType, document_id: docId },
                        success: function() {
                            $('#tagModal').modal('hide');
                            $('#newTag').val('');
                            location.reload();
                        }
                    });
                }
            });

            $(document).on('click', '.remove-tag', function() {
                let tagId = $(this).data('tag-id');
                let docType = $('#documentType').val();
                let docId = $('#documentId').val();
                $.ajax({
                    url: 'remove_tag.php',
                    method: 'POST',
                    data: { tag_id: tagId, document_type: docType, document_id: docId },
                    success: function() {
                        $('#existingTags').find(`[data-tag-id="${tagId}"]`).parent().remove();
                    }
                });
            });

            // Load log activity for admins
            <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
                $.ajax({
                    url: 'get_log_activity.php',
                    method: 'GET',
                    success: function(data) {
                        let logs = JSON.parse(data);
                        let logCount = logs.length;
                        let summaryHtml = '';
                        let tableHtml = '';
                        let no = 1;

                        // Group logs by user for summary
                        let userLogs = {};
                        logs.forEach(log => {
                            if (!userLogs[log.user_id]) {
                                userLogs[log.user_id] = {
                                    username: log.username,
                                    nama_pegawai: log.nama_pegawai,
                                    last_login: log.login_time,
                                    count: 0,
                                    ip_address: log.ip_address
                                };
                            }
                            userLogs[log.user_id].count++;
                            if (new Date(log.login_time) > new Date(userLogs[log.user_id].last_login)) {
                                userLogs[log.user_id].last_login = log.login_time;
                                userLogs[log.user_id].ip_address = log.ip_address;
                            }
                        });

                        // Generate summary for dropdown
                        for (let user_id in userLogs) {
                            let log = userLogs[user_id];
                            summaryHtml += `
                                <p><strong>${log.username}</strong>: Last login ${new Date(log.last_login).toLocaleString('id-ID')} (${log.count} logins)</p>
                            `;
                            tableHtml += `
                                <tr>
                                    <td>${no++}</td>
                                    <td>${log.username}</td>
                                    <td>${log.nama_pegawai || '-'}</td>
                                    <td>${new Date(log.last_login).toLocaleString('id-ID')}</td>
                                    <td>${log.count}</td>
                                    <td>${log.ip_address || '-'}</td>
                                </tr>
                            `;
                        }

                        $('#logCount').text(logCount > 0 ? logCount : '');
                        $('#logSummary').html(summaryHtml || '<p>Tidak ada log aktivitas</p>');
                        $('#logTableBody').html(tableHtml || '<tr><td colspan="6">Tidak ada log aktivitas</td></tr>');
                    }
                });
            <?php endif; ?>

            // Hide search results when clicking outside
            $(document).click(function(e) {
                if (!$(e.target).closest('.search-container').length) {
                    $('#searchResults').hide();
                }
            });
        });
    </script>
</body>

</html>