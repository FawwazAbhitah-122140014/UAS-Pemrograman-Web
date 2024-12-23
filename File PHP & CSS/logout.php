<?php
// Mulai session
session_start();

// Menghancurkan semua session
session_unset();
session_destroy();

// Mengarahkan kembali ke halaman login
header("Location: login.php");
exit;
?>
