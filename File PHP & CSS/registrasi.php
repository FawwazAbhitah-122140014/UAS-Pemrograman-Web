<?php
include 'db.php';

class Registration {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function registerUser($fullName, $email, $password) {
      $role = "user";
      $browser = $_SERVER['HTTP_USER_AGENT']; // Mendapatkan informasi browser
      $ipAddress = $_SERVER['REMOTE_ADDR']; // Mendapatkan alamat IP
  
      // Hash the password
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
  
      // Insert user into the database
      $stmt = $this->db->prepare("INSERT INTO users (full_name, email, password, role, browser, ip_address) VALUES (?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("ssssss", $fullName, $email, $hashedPassword, $role, $browser, $ipAddress);
  
      if ($stmt->execute()) {
          // Tampilkan alert dan redirect ke halaman login
          echo "<script>alert('Akun Anda telah dibuat!'); window.location.href = 'login.php';</script>";
          exit;
      } else {
          throw new Exception("Registration failed: " . $stmt->error);
      }  
  }
}

// Controller logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register'])) {
    $fullName = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Create Database and Registration objects
        $database = new Database();
        $dbConnection = $database->getConnection();
        $registration = new Registration($dbConnection);

        // Register the user
        $registration->registerUser($fullName, $email, $password);
    } catch (Exception $e) {
        echo "Terjadi kesalahan: " . $e->getMessage();
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
  <script>
      document.addEventListener('DOMContentLoaded', () => {
      const form = document.querySelector('form');
      const password = form.querySelector('input[name="password"]');
      const confirmPassword = form.querySelector('input[name="confirm_password"]');
      const fullName = form.querySelector('input[name="full_name"]');
      const email = form.querySelector('input[name="email"]');
      const submitButton = form.querySelector('input[type="submit"]');
      const termsCheckbox = form.querySelector('input[type="checkbox"]');
      const emailError = document.getElementById('emailError');
      const passwordError = document.getElementById('passwordError');
      const confirmPasswordError = document.getElementById('confirmPasswordError');
      const fullNameError = document.getElementById('fullNameError');

      // Validasi konfirmasi password
      confirmPassword.addEventListener('input', () => {
        if (password.value !== confirmPassword.value) {
          confirmPassword.setCustomValidity("Passwords do not match!");
        } else {
          confirmPassword.setCustomValidity("");
        }
      });

      // Validasi full name
      fullName.addEventListener('blur', () => {
        if (fullName.value.trim() === "") {
          fullNameError.textContent = "Nama lengkap harus diisi!";
        } else {
          fullNameError.textContent = "";
        }
      });
      // Validasi email
      email.addEventListener('blur', () => {
        if (!validateEmail(email.value)) {
          emailError.textContent = "Format email tidak valid!";
        } else {
          emailError.textContent = "";
        }
      });

      // Validasi password
      password.addEventListener('blur', () => {
        if (password.value.length < 6) {
          passwordError.textContent = "Password harus minimal 6 karakter!";
        } else {
          passwordError.textContent = "";
        }
      });

      // Validasi form sebelum submit
      form.addEventListener('submit', (event) => {
        let valid = true;

        // Cek email
        if (!validateEmail(email.value)) {
          emailError.textContent = "Format email tidak valid!";
          valid = false;
        }

        // Cek full name
        if (fullName.value.trim() === "") {
          fullNameError.textContent = "Nama lengkap harus diisi!";
          valid = false;
        }

        // Cek password
        if (password.value === "") {
          passwordError.textContent = "Password harus diisi!";
          valid = false;
        } else {
          passwordError.textContent = "";
        }

        // Cek konfirmasi password
        if (confirmPassword.value !== password.value) {
          confirmPasswordError.textContent = "Password tidak cocok!";
          valid = false;
        } else {
          confirmPasswordError.textContent = "";
        }

        // Cek apakah checkbox sudah dicentang
        if (!termsCheckbox.checked) {
          alert("Anda harus menyetujui syarat dan ketentuan.");
          valid = false;
        }

        if (!valid) {
          event.preventDefault(); // Mencegah form dikirim jika ada kesalahan
        }
      });
    });
    // Fungsi untuk validasi email
    function validateEmail(email) {
      const re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
      return re.test(email);
    }
  </script>
</head>
<body>
  <div class="container">
    <div class="registration form">
      <header>Age Of Empires</header>
      <form action="registrasi.php" method="POST">
        <span id="fullNameError" style="color: red;"></span>
        <input type="text" name="full_name" placeholder="Enter your full name" required>
        <span id="emailError" style="color: red;"></span>
        <input type="email" name="email" placeholder="Enter your email" required>
        <span id="passwordError" style="color: red;"></span>
        <input type="password" name="password" placeholder="Create a password" required>
        <input type="password" name="confirm_password" placeholder="Confirm your password" required>
        <span id="confirmPasswordError" style="color: red;"></span>
        <label>
          <input type="checkbox" required> I agree to the terms and conditions
        </label>
        <input type="submit" class="button" name="register" value="Signup">
      </form>
      <div class="signup">
        <span class="signup">Already have an account? <a href="login.php">Login</a></span>
      </div>
    </div>
  </div>
</body>
</html>
