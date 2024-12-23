<?php
include 'db.php'; // Ganti dengan koneksi database Anda


// Mulai session untuk memeriksa role
session_start();

// Jika pengguna tidak login atau role bukan admin, redirect ke login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Anda bukan admin!');</script>";
    echo "<script>window.location.href = 'login.php';</script>";
    exit;
}

$db = new Database();
$conn = $db->getConnection();

// Mengambil data dari tabel users
$result = $conn->query("SELECT * FROM users");

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Pengguna</title>
  <link rel="stylesheet" href="datauser.css">
</head>
<body>
    <div class="button-wrapper">
        <a href="dashboard.php" class="button">kembali</a>
    </div>
    <table border="1" class="data-table">
      <tr>
        <th>Full Name</th>
        <th>Email</th>
        <th>Tanggal Pembuatan Akun</th>
        <th>Role</th>
        <th>Jenis Browser</th>
        <th>IP</th>
      </tr>
      <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
          <td><?php echo htmlspecialchars($row['full_name']); ?></td>
          <td><?php echo htmlspecialchars($row['email']); ?></td>
          <td><?php echo htmlspecialchars($row['created_at']); ?></td>
          <td><?php echo htmlspecialchars($row['role']); ?></td>
          <td><?php echo htmlspecialchars($row['browser']); ?></td>
          <td><?php echo htmlspecialchars($row['ip_address']); ?></td>
        </tr>
      <?php } ?>
    </table>
</body>
</html>
