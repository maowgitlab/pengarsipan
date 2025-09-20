<?php
  include 'DB/koneksi.php';
  session_start();
  if (!isset($_SESSION['username'])) {
    header("location: halaman_utama.php");
  }
  ?>
  <?php include 'layouts/header.php'; ?>

  <?php include 'layouts/sidebar.php'; ?>

  <main id="main" class="main">
    <?php
    require_once 'vendor/autoload.php';
    $faker = Faker\Factory::create('id_ID');
    if (isset($_GET['halaman'])) {
      $halaman = $_GET['halaman'];

      switch ($halaman) {
        case 'beranda':
          include 'pages/beranda.php';
          break;
        case 'bidang':
          include 'pages/bidang/bidang.php';
          break;
        case 'pegawai':
          include 'pages/pegawai/pegawai.php';
          break;
        case 'pegawai_cetak':
          include 'pages/pegawai/cetak.php';
          break;
        case 'masuk':
          include 'pages/masuk/masuk.php';
          break;
        case 'keluar':
          include 'pages/keluar/keluar.php';
          break;
        case 'surat_gaji':
          include 'pages/gaji/surat_gaji.php';
          break;
        case 'surat_cuti':
          include 'pages/cuti/surat_cuti.php';
          break;
        case 'undangan':
          include 'pages/undangan/undangan.php';
          break;
        case 'gaji_pegawai':
          include 'pages/gaji/gaji_pegawai.php';
          break;
        case 'cuti_pegawai':
          include 'pages/cuti/cuti_pegawai.php';
          break;
        case 'pengajuan_cuti':
          include 'pages/cuti/pengajuan_cuti.php';
          break;
        // case 'surat_undangan':
        //   include 'pages/undangan/surat_undangan.php';
        //   break;
        case 'slip_gaji':
          include 'pages/gaji/slip_gaji.php';
          break;
        case 'kawasan':
          include 'pages/kawasan/kawasan.php';
          break;
        case 'indikator_penilaian':
          include 'pages/indikator_penilaian/indikator_penilaian.php';
          break;
        case 'periode_penilaian':
          include 'pages/periode_penilaian/periode_penilaian.php';
          break;
        case 'penilaian_kawasan':
          include 'pages/penilaian_kawasan/penilaian_kawasan.php';
          break;
        case 'hasil_laporan':
          include 'pages/hasil_laporan/hasil_laporan.php';
          break;
        default:
          include 'pages/beranda.php';
          break;
      }
    } else {
      include 'pages/beranda.php';
    }
    ?>

  </main><!-- End #main -->

  <?php include 'layouts/footer.php'; ?>