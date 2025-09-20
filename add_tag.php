<?php
include 'DB/koneksi.php';
$tag_name = isset($_POST['tag_name']) ? mysqli_real_escape_string($koneksi, $_POST['tag_name']) : '';
$document_type = isset($_POST['document_type']) ? mysqli_real_escape_string($koneksi, $_POST['document_type']) : '';
$document_id = isset($_POST['document_id']) ? (int)$_POST['document_id'] : 0;

if ($tag_name && $document_type && $document_id) {
    // Check if tag exists, or insert new one
    $result = mysqli_query($koneksi, "SELECT id FROM tb_tags WHERE tag_name = '$tag_name'");
    if (mysqli_num_rows($result) > 0) {
        $tag = mysqli_fetch_assoc($result);
        $tag_id = $tag['id'];
    } else {
        mysqli_query($koneksi, "INSERT INTO tb_tags (tag_name) VALUES ('$tag_name')");
        $tag_id = mysqli_insert_id($koneksi);
    }
    // Link tag to document
    mysqli_query($koneksi, "INSERT INTO tb_document_tags (tag_id, document_type, document_id) VALUES ($tag_id, '$document_type', $document_id)");
}
?>