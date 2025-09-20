<?php 
    $koneksi = mysqli_connect("localhost", "root", "", "pengarsipan");
    if (!$koneksi) {
        die("Koneksi gagal: " . mysqli_connect_error());
    }