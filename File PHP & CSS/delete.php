<?php
include 'db.php';

$db = new Database();
$conn = $db->getConnection();

// Mengambil ID dari parameter URL
$id = $_GET['id'];

// Menghapus data berdasarkan ID
$conn->query("DELETE FROM fraksi WHERE id = $id");

// Redirect ke halaman utama setelah penghapusan
header('Location: dashboard.php');
exit;
?>
