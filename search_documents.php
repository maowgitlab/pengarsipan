<?php
include 'DB/koneksi.php';
$query = isset($_GET['q']) ? mysqli_real_escape_string($koneksi, $_GET['q']) : '';
$output = '';

if (strlen($query) >= 2) {
    $sql = "
        SELECT 'masuk' as type, sm.id, sm.no_surat, sm.perihal, sm.pengirim as name, sm.tanggal_masuk as date
        FROM tb_surat_masuk sm
        LEFT JOIN tb_document_tags dt ON dt.document_type = 'masuk' AND dt.document_id = sm.id
        LEFT JOIN tb_tags t ON dt.tag_id = t.id
        WHERE sm.no_surat LIKE '%$query%' OR sm.perihal LIKE '%$query%' OR sm.pengirim LIKE '%$query%' OR t.tag_name LIKE '%$query%'
        UNION
        SELECT 'keluar' as type, sk.id, sk.no_surat, sk.perihal, sk.penerima as name, sk.tanggal_kirim as date
        FROM tb_surat_keluar sk
        LEFT JOIN tb_document_tags dt ON dt.document_type = 'keluar' AND dt.document_id = sk.id
        LEFT JOIN tb_tags t ON dt.tag_id = t.id
        WHERE sk.no_surat LIKE '%$query%' OR sk.perihal LIKE '%$query%' OR sk.penerima LIKE '%$query%' OR t.tag_name LIKE '%$query%'
        UNION
        SELECT 'surat_cuti' as type, sc.id, sc.no_surat, c.alasan as perihal, p.nama_pegawai as name, sc.tanggal_surat as date
        FROM tb_surat_cuti sc
        JOIN tb_cuti c ON sc.id_cuti = c.id
        JOIN tb_pegawai p ON c.nip = p.nip
        LEFT JOIN tb_document_tags dt ON dt.document_type = 'surat_cuti' AND dt.document_id = sc.id
        LEFT JOIN tb_tags t ON dt.tag_id = t.id
        WHERE sc.no_surat LIKE '%$query%' OR c.alasan LIKE '%$query%' OR p.nama_pegawai LIKE '%$query%' OR t.tag_name LIKE '%$query%'
        UNION
        SELECT 'surat_gaji' as type, sg.id, sg.no_surat, 'Gaji' as perihal, p.nama_pegawai as name, sg.tanggal_surat as date
        FROM tb_surat_gaji sg
        JOIN tb_gaji g ON sg.id_gaji = g.id
        JOIN tb_pegawai p ON g.nip = p.nip
        LEFT JOIN tb_document_tags dt ON dt.document_type = 'surat_gaji' AND dt.document_id = sg.id
        LEFT JOIN tb_tags t ON dt.tag_id = t.id
        WHERE sg.no_surat LIKE '%$query%' OR p.nama_pegawai LIKE '%$query%' OR t.tag_name LIKE '%$query%'
        LIMIT 10";
    
    $result = mysqli_query($koneksi, $sql);
    if (!$result) {
        $output = "<div class='dropdown-item'>Error: " . mysqli_error($koneksi) . "</div>";
    } elseif (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $output .= "<a class='dropdown-item' href='?halaman=" . htmlspecialchars($row['type']) . "&id=" . (int)$row['id'] . "'>";
            $output .= "<strong>" . htmlspecialchars($row['name']) . "</strong><br>";
            $output .= "No: " . htmlspecialchars($row['no_surat']) . " | " . htmlspecialchars($row['perihal']) . "<br>";
            $output .= "Date: " . htmlspecialchars($row['date']);
            $output .= "</a>";
        }
    } else {
        $output = "<div class='dropdown-item'>No results found</div>";
    }
}
echo $output;
?>