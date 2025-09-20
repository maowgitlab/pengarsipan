<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar no-print">
    <ul class="sidebar-nav" id="sidebar-nav">
        <li class="nav-item">
            <a class="nav-link <?= (isset($_GET['halaman']) && $_GET['halaman'] == 'beranda' || !isset($_GET['halaman']) ? '' : 'collapsed') ?>" href="?halaman=beranda">
                <i class="bi bi-grid"></i>
                <span>Dashboard</span>
            </a>
        </li><!-- End Dashboard Nav -->
        <?php if (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'admin') : ?>
            <li class="nav-heading">Master Data</li>
            <li class="nav-item">
                <a class="nav-link <?= (isset($_GET['halaman']) && $_GET['halaman'] == 'bidang' ? '' : 'collapsed') ?>" href="?halaman=bidang">
                    <i class="bi bi-card-list"></i>
                    <span>Bidang</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (isset($_GET['halaman']) && $_GET['halaman'] == 'pegawai' ? '' : 'collapsed') ?>" href="?halaman=pegawai">
                    <i class="bi bi-people-fill"></i>
                    <span>Pegawai</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (isset($_GET['halaman']) && in_array($_GET['halaman'], ['masuk', 'keluar', 'surat_cuti', 'surat_gaji']) ? '' : 'collapsed') ?>" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                    <i class="bi bi-menu-button-wide"></i><span>Surat</span><i class="bi bi-chevron-down ms-auto"></i>
                </a>
                <ul id="components-nav" class="nav-content <?= (isset($_GET['halaman']) && in_array($_GET['halaman'], ['masuk', 'keluar', 'surat_cuti', 'surat_gaji']) ? '' : 'collapse') ?>" data-bs-parent="#sidebar-nav">
                    <li>
                        <a href="?halaman=masuk" class="<?= (isset($_GET['halaman']) && $_GET['halaman'] == 'masuk' ? 'active' : '') ?>">
                            <i class="bi bi-circle"></i><span>Masuk</span>
                        </a>
                    </li>
                    <li>
                        <a href="?halaman=keluar" class="<?= (isset($_GET['halaman']) && $_GET['halaman'] == 'keluar' ? 'active' : '') ?>">
                            <i class="bi bi-circle"></i><span>Keluar</span>
                        </a>
                    </li>
                    <li>
                        <a href="?halaman=surat_cuti" class="<?= (isset($_GET['halaman']) && $_GET['halaman'] == 'surat_cuti' ? 'active' : '') ?>">
                            <i class="bi bi-circle"></i><span>Cuti</span>
                        </a>
                    </li>
                    <li>
                        <a href="?halaman=surat_gaji" class="<?= (isset($_GET['halaman']) && $_GET['halaman'] == 'surat_gaji' ? 'active' : '') ?>">
                            <i class="bi bi-circle"></i><span>Gaji</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (isset($_GET['halaman']) && $_GET['halaman'] == 'gaji_pegawai' ? '' : 'collapsed') ?>" href="?halaman=gaji_pegawai">
                    <i class="bi bi-cash"></i>
                    <span>Gaji Pegawai</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (isset($_GET['halaman']) && $_GET['halaman'] == 'cuti_pegawai' ? '' : 'collapsed') ?>" href="?halaman=cuti_pegawai">
                    <i class="bi bi-calendar-day"></i>
                    <span>Cuti Pegawai</span>
                </a>
            </li>
            <li class="nav-heading">Kawasan Layak Permukiman</li>
            <li class="nav-item">
                <a class="nav-link <?= (isset($_GET['halaman']) && $_GET['halaman'] == 'kawasan' ? '' : 'collapsed') ?>" href="?halaman=kawasan">
                    <i class="bi bi-house"></i>
                    <span>Kawasan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (isset($_GET['halaman']) && $_GET['halaman'] == 'indikator_penilaian' ? '' : 'collapsed') ?>" href="?halaman=indikator_penilaian">
                    <i class="bi bi-list-check"></i>
                    <span>Indikator Penilaian</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (isset($_GET['halaman']) && $_GET['halaman'] == 'periode_penilaian' ? '' : 'collapsed') ?>" href="?halaman=periode_penilaian">
                    <i class="bi bi-calendar"></i>
                    <span>Periode Penilaian</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (isset($_GET['halaman']) && $_GET['halaman'] == 'penilaian_kawasan' ? '' : 'collapsed') ?>" href="?halaman=penilaian_kawasan">
                    <i class="bi bi-clipboard-data"></i>
                    <span>Penilaian Kawasan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (isset($_GET['halaman']) && $_GET['halaman'] == 'hasil_laporan' ? '' : 'collapsed') ?>" href="?halaman=hasil_laporan">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Hasil Laporan</span>
                </a>
            </li>
        <?php elseif (isset($_SESSION['jabatan']) && $_SESSION['jabatan'] == 'kadisperkim') : ?>
            <li class="nav-heading">Laporan</li>
            <li class="nav-item">
                <a class="nav-link <?= (isset($_GET['halaman']) && $_GET['halaman'] == 'pegawai' ? '' : 'collapsed') ?>" href="?halaman=pegawai">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Laporan Pegawai</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (isset($_GET['halaman']) && $_GET['halaman'] == 'masuk' ? '' : 'collapsed') ?>" href="?halaman=masuk">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Laporan Surat Masuk</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (isset($_GET['halaman']) && $_GET['halaman'] == 'keluar' ? '' : 'collapsed') ?>" href="?halaman=keluar">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Laporan Surat Keluar</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (isset($_GET['halaman']) && $_GET['halaman'] == 'surat_gaji' ? '' : 'collapsed') ?>" href="?halaman=surat_gaji">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Laporan Surat Gaji</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (isset($_GET['halaman']) && $_GET['halaman'] == 'surat_cuti' ? '' : 'collapsed') ?>" href="?halaman=surat_cuti">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Laporan Surat Cuti</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (isset($_GET['halaman']) && $_GET['halaman'] == 'kawasan' ? '' : 'collapsed') ?>" href="?halaman=kawasan">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Laporan Kawasan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (isset($_GET['halaman']) && $_GET['halaman'] == 'penilaian_kawasan' ? '' : 'collapsed') ?>" href="?halaman=penilaian_kawasan">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Laporan Penilaian Kawasan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (isset($_GET['halaman']) && $_GET['halaman'] == 'hasil_laporan' ? '' : 'collapsed') ?>" href="?halaman=hasil_laporan">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Laporan Hasil Kawasan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link collapsed" href="report/print_kawasan_analytics.php" target="_blank">
                    <i class="bi bi-file-earmark-text"></i>
                    <span>Laporan Analitik Kawasan</span>
                </a>
            </li>
        <?php else : ?>
            <li class="nav-item">
                <a class="nav-link <?= (isset($_GET['halaman']) && $_GET['halaman'] == 'pengajuan_cuti' ? '' : 'collapsed') ?>" href="?halaman=pengajuan_cuti">
                    <i class="bi bi-calendar-day"></i>
                    <span>Pengajuan Cuti</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (isset($_GET['halaman']) && $_GET['halaman'] == 'slip_gaji' ? '' : 'collapsed') ?>" href="?halaman=slip_gaji">
                    <i class="bi bi-cash-coin"></i>
                    <span>Slip Gaji</span>
                </a>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link <?= (isset($_GET['halaman']) && $_GET['halaman'] == 'surat_undangan' ? '' : 'collapsed') ?>" href="?halaman=surat_undangan">
                    <i class="bi bi-envelope"></i>
                    <span>Surat Undangan</span>
                </a>
            </li> -->
        <?php endif; ?>
    </ul>
</aside><!-- End Sidebar -->