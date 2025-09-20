<?php
include 'DB/koneksi.php';
$tag_id = isset($_POST['tag_id']) ? (int)$_POST['tag_id'] : 0;
$document_type = isset($_POST['document_type']) ? mysqli_real_escape_string($koneksi, $_POST['document_type']) : '';
$document_id = isset($_POST['document_id']) ? (int)$_POST['document_id'] : 0;

if ($tag_id && $document_type && $document_id) {
    mysqli_query($koneksi, "DELETE FROM tb_document_tags WHERE tag_id = $tag_id AND document_type = '$document_type' AND document_id = $document_id");
}
?>