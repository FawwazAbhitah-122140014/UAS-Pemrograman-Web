<?php
include 'db.php';

$db = new Database();
$conn = $db->getConnection();

// Mulai session untuk memeriksa role
session_start();

// Fungsi untuk validasi email dan role
function validateUser($conn, $email, $role) {
    $stmt = $conn->prepare("SELECT role FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        return $data['role'] === $role;
    }
    return false;
}

// Cek session dan cookie
if (!isset($_SESSION['email']) || !isset($_SESSION['role'])) {
    if (isset($_COOKIE['email']) && isset($_COOKIE['role'])) {
        // Validasi cookie dengan database
        $email = $_COOKIE['email'];
        $role = $_COOKIE['role'];
        if (validateUser($conn, $email, $role)) {
            // Set session jika validasi berhasil
            $_SESSION['email'] = $email;
            $_SESSION['role'] = $role;
        } else {
            // Jika validasi gagal, redirect ke login dengan alert
            echo "<script>alert('Anda harus login terlebih dahulu!'); window.location.href = 'login.php';</script>";
            exit;
        }
    } else {
        // Jika session dan cookie tidak ada, redirect ke login dengan alert
        echo "<script>alert('Anda harus login terlebih dahulu!'); window.location.href = 'login.php';</script>";
        exit;
    }
}

// Perbarui cookie jika session aktif
if (isset($_SESSION['email']) && isset($_SESSION['role'])) {
    setcookie("email", $_SESSION['email'], time() + (60 * 60), "/", "", true, true); // Secure dan HttpOnly
    setcookie("role", $_SESSION['role'], time() + (60 * 60), "/", "", true, true); // Secure dan HttpOnly
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="styles4.css">
  <script>
    // Fungsi untuk konfirmasi penghapusan
    function confirmDelete(event) {
      if (!confirm("Apakah Anda yakin ingin menghapus data ini?")) {
        event.preventDefault(); // Batalkan penghapusan jika pengguna memilih "Batal"
      }
    }
  </script>
</head>
<body>
    <!-- Tombol Navigation -->
    <div class="button-wrapper">
      <a href="data.php" class="button">Tambah Data</a>

      <!-- Hanya tampilkan tombol admin jika role user adalah admin -->
      <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
        <a href="datauser.php" class="button">Admin</a>
      <?php endif; ?>

      <a href="logout.php" class="button">Logout</a>
    </div>

    <h2>Data Rank Age Of Empires 2</h2>

    <!-- Tampilkan data fraksi dalam tabel -->
    <table border="1" class="data-table">
      <tr>
        <th>Nama</th>
        <th>Fraksi Favorit</th>
        <th>Map Favorit</th>
        <th>Elo</th>
        <th>Aksi</th>
      </tr>
      <?php
      // Mengambil data dari tabel fraksi
      $result = $conn->query("SELECT * FROM fraksi");
      while ($row = $result->fetch_assoc()) {
      ?>
        <tr>
          <td><?php echo htmlspecialchars($row['nama']); ?></td>
          <td><?php echo htmlspecialchars($row['fraksi_favorit']); ?></td>
          <td><?php echo htmlspecialchars($row['map_favorit']); ?></td>
          <td><?php echo htmlspecialchars($row['elo']); ?></td>
          <td>
            <a href="update.php?id=<?php echo $row['id']; ?>" class="update-btn">Update</a>
            <a href="delete.php?id=<?php echo $row['id']; ?>" class="delete-btn" onclick="confirmDelete(event)">Hapus</a>
          </td>
        </tr>
      <?php } ?>
    </table>

</body>
</html>
