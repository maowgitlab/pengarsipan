<div class="pagetitle">
    <h1>Dashboard</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
    </nav>
</div><!-- End Page Title -->

<section class="section dashboard">
    <?php if (isset($_SESSION['jabatan']) && ($_SESSION['jabatan'] == 'admin' || $_SESSION['jabatan'] == 'kadisperkim')) : ?>
        <div class="row">
            <?php 
            $pegawai = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_pegawai"));
            $surat_masuk = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_surat_masuk"));
            $surat_keluar = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_surat_keluar"));
            $surat_cuti = mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM tb_surat_cuti"));
            $id_kawasan_filter = isset($_GET['id_kawasan']) && $_GET['id_kawasan'] != 'all' ? mysqli_real_escape_string($koneksi, $_GET['id_kawasan']) : 'all';
            ?>
            <!-- Left side columns -->
            <div class="col">
                <div class="row">
                    <!-- Pegawai Card -->
                    <div class="col">
                        <div class="card info-card sales-card">
                            <div class="card-body">
                                <h5 class="card-title">Pegawai</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-people-fill"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6><?= $pegawai ?></h6>
                                        <span class="text-muted small pt-2 ps-1"><a href="?halaman=pegawai">Selengkapnya</a></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Pegawai Card -->

                    <!-- Surat Masuk Card -->
                    <div class="col">
                        <div class="card info-card revenue-card">
                            <div class="card-body">
                                <h5 class="card-title">Surat Masuk</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-arrow-up"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6><?= $surat_masuk ?></h6>
                                        <span class="text-muted small pt-2 ps-1"><a href="?halaman=masuk">Selengkapnya</a></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Surat Masuk Card -->

                    <!-- Surat Keluar Card -->
                    <div class="col">
                        <div class="card info-card customers-card">
                            <div class="card-body">
                                <h5 class="card-title">Surat Keluar</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-arrow-down"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6><?= $surat_keluar ?></h6>
                                        <span class="text-muted small pt-2 ps-1"><a href="?halaman=keluar">Selengkapnya</a></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Surat Keluar Card -->

                    <!-- Surat Cuti Card -->
                    <div class="col">
                        <div class="card info-card undangan-card">
                            <div class="card-body">
                                <h5 class="card-title">Surat Cuti</h5>
                                <div class="d-flex align-items-center">
                                    <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="bi bi-envelope"></i>
                                    </div>
                                    <div class="ps-3">
                                        <h6><?= $surat_cuti ?></h6>
                                        <span class="text-muted small pt-2 ps-1"><a href="?halaman=surat_cuti">Selengkapnya</a></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Surat Cuti Card -->
                </div>

                <!-- Kawasan Layak Permukiman Analytics Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Laporan Analitik Kawasan Layak Permukiman <span class="no-print">| <a href="report/print_kawasan_analytics.php?id_kawasan=<?= urlencode($id_kawasan_filter); ?>" target="_blank" class="btn btn-sm btn-primary"><i class="bi bi-printer"></i></a></span></h5>
                                <div class="d-flex justify-content-end mb-3">
                                    <form method="get" action="" class="d-flex align-items-center">
                                        <input type="hidden" name="halaman" value="beranda">
                                        <select name="id_kawasan" class="form-select me-2" style="width: 200px;" onchange="this.form.submit()">
                                            <option value="all" <?= $id_kawasan_filter == 'all' ? 'selected' : '' ?>>Semua Kawasan</option>
                                            <?php 
                                            $kawasan = mysqli_query($koneksi, "SELECT * FROM kawasan ORDER BY nama_kawasan");
                                            foreach ($kawasan as $k) : ?>
                                                <option value="<?= $k['id_kawasan']; ?>" <?= $id_kawasan_filter == $k['id_kawasan'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($k['nama_kawasan']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </form>
                                </div>
                                <div class="row">
                                    <?php
                                    if ($id_kawasan_filter == 'all') {
                                        $kawasan_list = mysqli_query($koneksi, "SELECT * FROM kawasan ORDER BY nama_kawasan");
                                    } else {
                                        $kawasan_list = mysqli_query($koneksi, "SELECT * FROM kawasan WHERE id_kawasan = '$id_kawasan_filter'");
                                    }
                                    foreach ($kawasan_list as $k) :
                                        $current_kawasan_id = $k['id_kawasan'];
                                        $kawasan_name = htmlspecialchars($k['nama_kawasan']);
                                    ?>
                                        <!-- Pie Chart: Status Layak for Each Kawasan -->
                                        <div class="col-lg-4">
                                            <canvas id="statusLayakChart_<?= $current_kawasan_id ?>" style="max-height: 300px;"></canvas>
                                        </div>
                                    <?php endforeach; ?>
                                    <?php if (mysqli_num_rows($kawasan_list) == 0) : ?>
                                        <div class="col-12 text-center">Tidak ada data kawasan ditemukan</div>
                                    <?php endif; ?>
                                    <!-- Bar Chart: Average Assessment Score per Kawasan -->
                                    <div class="col-lg-4">
                                        <canvas id="avgScoreKawasanChart" style="max-height: 300px;"></canvas>
                                    </div>
                                    <!-- Line Chart: Trend of Status Layak Over Time -->
                                    <div class="col-lg-4">
                                        <canvas id="statusLayakTrendChart" style="max-height: 300px;"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div><!-- End Left side columns -->
        </div>

        <!-- Chart.js and Chart.js Datalabels Plugin -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
        <script>
            // Register the datalabels plugin
            Chart.register(ChartDataLabels);

            // Pie Charts: Status Layak for Each Kawasan
            <?php
            foreach ($kawasan_list as $k) :
                $current_kawasan_id = $k['id_kawasan'];
                $kawasan_name = $k['nama_kawasan'];
                $query = "SELECT status_layak, COUNT(*) as count FROM hasil_laporan WHERE id_kawasan = '$current_kawasan_id' GROUP BY status_layak";
                $statusLayakData = mysqli_query($koneksi, $query);
                $statusLayakLabels = [];
                $statusLayakCounts = [];
                $totalCount = 0;
                while ($row = mysqli_fetch_assoc($statusLayakData)) {
                    $statusLayakLabels[] = $row['status_layak'];
                    $statusLayakCounts[] = $row['count'];
                    $totalCount += $row['count'];
                }
                $statusLayakPercentages = array_map(function($count) use ($totalCount) {
                    return $totalCount > 0 ? round(($count / $totalCount) * 100, 2) : 0;
                }, $statusLayakCounts);
            ?>
                const statusLayakCtx_<?= $current_kawasan_id ?> = document.getElementById('statusLayakChart_<?= $current_kawasan_id ?>').getContext('2d');
                const statusLayakChart_<?= $current_kawasan_id ?> = new Chart(statusLayakCtx_<?= $current_kawasan_id ?>, {
                    type: 'pie',
                    data: {
                        labels: <?= json_encode($statusLayakLabels) ?>,
                        datasets: [{
                            data: <?= json_encode($statusLayakCounts) ?>,
                            backgroundColor: ['#4BC0C0', '#FF6384'],
                            borderColor: '#fff',
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            title: {
                                display: true,
                                text: 'Status Layak: <?= $kawasan_name ?>'
                            },
                            datalabels: {
                                formatter: (value, ctx) => {
                                    let percentage = <?= json_encode($statusLayakPercentages) ?>[ctx.dataIndex];
                                    return `${value} (${percentage}%)`;
                                },
                                color: '#fff',
                                font: {
                                    weight: 'bold'
                                }
                            }
                        }
                    }
                });
            <?php endforeach; ?>

            // Bar Chart: Average Assessment Score per Kawasan
            <?php
            $query = "SELECT k.nama_kawasan, AVG(pk.nilai) as avg_score 
                      FROM penilaian_kawasan pk 
                      JOIN kawasan k ON pk.id_kawasan = k.id_kawasan";
            if ($id_kawasan_filter != 'all') {
                $query .= " WHERE pk.id_kawasan = '$id_kawasan_filter'";
            }
            $query .= " GROUP BY k.id_kawasan";
            $avgScoreData = mysqli_query($koneksi, $query);
            $kawasanLabels = [];
            $avgScores = [];
            while ($row = mysqli_fetch_assoc($avgScoreData)) {
                $kawasanLabels[] = $row['nama_kawasan'];
                $avgScores[] = round($row['avg_score'], 2);
            }
            ?>
            const avgScoreKawasanCtx = document.getElementById('avgScoreKawasanChart').getContext('2d');
            const avgScoreKawasanChart = new Chart(avgScoreKawasanCtx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($kawasanLabels) ?>,
                    datasets: [{
                        label: 'Rata-rata Nilai Penilaian',
                        data: <?= json_encode($avgScores) ?>,
                        backgroundColor: '#9966FF',
                        borderColor: '#9966FF',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Rata-rata Nilai Penilaian per Kawasan'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 10
                            }
                        }
                    }
                }
            });

            // Line Chart: Trend of Status Layak Over Time
            <?php
            $query = "SELECT p.bulan, p.tahun, COUNT(hl.id_laporan) as layak_count 
                      FROM hasil_laporan hl 
                      JOIN periode_penilaian p ON hl.id_periode = p.id_periode 
                      WHERE hl.status_layak = 'Layak'";
            if ($id_kawasan_filter != 'all') {
                $query .= " AND hl.id_kawasan = '$id_kawasan_filter'";
            }
            $query .= " GROUP BY p.id_periode ORDER BY p.tahun, p.bulan";
            $trendData = mysqli_query($koneksi, $query);
            $trendLabels = [];
            $trendCounts = [];
            while ($row = mysqli_fetch_assoc($trendData)) {
                $trendLabels[] = date('F', mktime(0, 0, 0, $row['bulan'], 1)) . ' ' . $row['tahun'];
                $trendCounts[] = $row['layak_count'];
            }
            ?>
            const statusLayakTrendCtx = document.getElementById('statusLayakTrendChart').getContext('2d');
            const statusLayakTrendChart = new Chart(statusLayakTrendCtx, {
                type: 'line',
                data: {
                    labels: <?= json_encode($trendLabels) ?>,
                    datasets: [{
                        label: 'Jumlah Kawasan Layak',
                        data: <?= json_encode($trendCounts) ?>,
                        borderColor: '#4BC0C0',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'Tren Kawasan Layak per Periode'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        </script>
    <?php else : ?>
        <div class="row">
            <div class="col text-center">
                <img src="assets/img/logo.png" alt="" width="150px">
                <h2 class="fw-bold my-3">SISTEM INFORMASI PENGARSIPAN PADA DINAS PERUMAHAN RAKYAT DAN KAWASAN PEMUKIMAN</h2>
                <h2 class="fw-bold my-3">KOTA BANJARMASIN</h2>
            </div>
        </div>
    <?php endif; ?>
</section>