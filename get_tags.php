<?php
include 'DB/koneksi.php';
$document_type = isset($_GET['document_type']) ? mysqli_real_escape_string($koneksi, $_GET['document_type']) : '';
$document_id = isset($_GET['document_id']) ? (int)$_GET['document_id'] : 0;
$output = '';

if ($document_type && $document_id) {
    $result = mysqli_query($koneksi, "SELECT t.id, t.tag_name FROM tb_tags t JOIN tb_document_tags dt ON t.id = dt.tag_id WHERE dt.document_type = '$document_type' AND dt.document_id = $document_id");
    while ($row = mysqli_fetch_assoc($result)) {
        $output .= "<span class='tag'>" . htmlspecialchars($row['tag_name']) . " <a href='#' class='remove-tag' data-tag-id='" . $row['id'] . "'>&times;</a></span>";
    }
}
echo $output;
?>