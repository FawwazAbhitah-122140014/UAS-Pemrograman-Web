<?php
include 'db.php';

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $fraksi_favorit = $_POST['fraksi_favorit'];
    $map_favorit = $_POST['map_favorit'];
    $elo = $_POST['elo'];

    // Insert data into the database
    $stmt = $conn->prepare("INSERT INTO fraksi (nama, fraksi_favorit, map_favorit, elo) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nama, $fraksi_favorit, $map_favorit, $elo);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit;
    } else {
        echo "Registration failed: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Signup</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <div class="form">
      <header>Age Of Empires</header>
      <form action="data.php" method="POST">
        <input type="text" name="nama" placeholder="Enter your full name" required>
        
        <!-- Dropdown for Fraksi Favorit -->
        <select name="fraksi_favorit" required>
          <option value="" disabled selected>Choose your favorite faction</option>
          <option value="Byzantines">Byzantines</option>
          <option value="Franks">Franks</option>
          <option value="Mongols">Mongols</option>
          <option value="Britons">Britons</option>
          <option value="Vikings">Vikings</option>
        </select>

        <!-- Dropdown for Map Favorit -->
        <select name="map_favorit" required>
          <option value="" disabled selected>Choose your favorite map</option>
          <option value="Arabia">Arabia</option>
          <option value="Black Forest">Black Forest</option>
          <option value="Nomad">Nomad</option>
          <option value="Islands">Islands</option>
          <option value="Arena">Arena</option>
        </select>

        <!-- ELO input -->
        <input type="number" name="elo" placeholder="Enter your ELO" required>
        <input type="submit" class="button" name="submit" value="Submit">
      </form>
    </div>
  </div>
</body>
</html>
