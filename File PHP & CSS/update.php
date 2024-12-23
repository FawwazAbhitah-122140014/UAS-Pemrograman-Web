<?php
include 'db.php';

$db = new Database();
$conn = $db->getConnection();

// Mengambil ID dari parameter URL
$id = $_GET['id'];

// Mengambil data berdasarkan ID
$result = $conn->query("SELECT * FROM fraksi WHERE id = $id");
$data = $result->fetch_assoc();

if (isset($_POST['submit'])) {
    // Mengambil data dari form
    $nama = $_POST['nama'];
    $fraksi_favorit = $_POST['fraksi_favorit'];
    $map_favorit = $_POST['map_favorit'];
    $elo = $_POST['elo'];

    // Update data di database
    $conn->query("UPDATE fraksi SET nama = '$nama', fraksi_favorit = '$fraksi_favorit', map_favorit = '$map_favorit', elo = '$elo' WHERE id = $id");

    // Redirect ke halaman utama setelah update
    header('Location: dashboard.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Data Fraksi</title>
  <link rel="stylesheet" href="style2.css">
</head>
<body>
  <div class="container">
    <h1>Edit Data Fraksi</h1>
    <form method="POST">
      <label for="nama">Nama:</label>
      <input type="text" name="nama" value="<?php echo $data['nama']; ?>" required><br>

      <label for="fraksi_favorit">Fraksi Favorit:</label>
      <select name="fraksi_favorit" required>
        <option value="Byzantines" <?php echo ($data['fraksi_favorit'] == 'Byzantines') ? 'selected' : ''; ?>>Byzantines</option>
        <option value="Franks" <?php echo ($data['fraksi_favorit'] == 'Franks') ? 'selected' : ''; ?>>Franks</option>
        <option value="Mongols" <?php echo ($data['fraksi_favorit'] == 'Mongols') ? 'selected' : ''; ?>>Mongols</option>
        <option value="Britons" <?php echo ($data['fraksi_favorit'] == 'Britons') ? 'selected' : ''; ?>>Britons</option>
        <option value="Vikings" <?php echo ($data['fraksi_favorit'] == 'Vikings') ? 'selected' : ''; ?>>Vikings</option>
      </select><br>

      <label for="map_favorit">Map Favorit:</label>
      <select name="map_favorit" required>
        <option value="Arabia" <?php echo ($data['map_favorit'] == 'Arabia') ? 'selected' : ''; ?>>Arabia</option>
        <option value="Black Forest" <?php echo ($data['map_favorit'] == 'Black Forest') ? 'selected' : ''; ?>>Black Forest</option>
        <option value="Nomad" <?php echo ($data['map_favorit'] == 'Nomad') ? 'selected' : ''; ?>>Nomad</option>
        <option value="Islands" <?php echo ($data['map_favorit'] == 'Islands') ? 'selected' : ''; ?>>Islands</option>
        <option value="Arena" <?php echo ($data['map_favorit'] == 'Arena') ? 'selected' : ''; ?>>Arena</option>
      </select><br>

      <label for="elo">ELO:</label>
      <input type="number" name="elo" value="<?php echo $data['elo']; ?>" required><br>

      <input type="submit" name="submit" value="Update">
    </form>
  </div>
</body>
</html>
