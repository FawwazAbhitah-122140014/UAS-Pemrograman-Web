<?php
include 'db.php';

class Login {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    public function authenticate($email, $password) {
        // Ambil pengguna dari database
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Set session variables
                session_start();
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                // Redirect to dashboard
                header("Location: dashboard.php");
                exit;
            } else {
                throw new Exception("Incorrect password!");
            }
        } else {
            throw new Exception("No user found with this email!");
        }
    }
}

// Controller logic
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        // Create Database and Login objects
        $database = new Database();
        $dbConnection = $database->getConnection();
        $login = new Login($dbConnection);

        // Authenticate the user
        $login->authenticate($email, $password);
    } catch (Exception $e) {
        echo "<script>alert('" . $e->getMessage() . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="style1.css">
  <script>
    // Validasi email dan password
    function validateEmail(email) {
      const re = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
      return re.test(email);
    }

    function validateForm(event) {
      const email = document.getElementById("email").value;
      const password = document.getElementById("password").value;
      let valid = true;

      if (email === "") {
        document.getElementById("emailError").innerText = "Email is required.";
        valid = false;
      } else if (!validateEmail(email)) {
        document.getElementById("emailError").innerText = "Invalid email format.";
        valid = false;
      } else {
        document.getElementById("emailError").innerText = "";
      }

      if (password === "") {
        document.getElementById("passwordError").innerText = "Password is required.";
        valid = false;
      } else {
        document.getElementById("passwordError").innerText = "";
      }

      if (!valid) {
        event.preventDefault();
      }
    }

    window.onload = function() {
      const form = document.getElementById("loginForm");
      form.addEventListener("submit", validateForm);

      const emailInput = document.getElementById("email");
      emailInput.addEventListener("focus", function() {
        document.getElementById("emailError").innerText = "";
      });
      emailInput.addEventListener("blur", function() {
        if (emailInput.value === "") {
          document.getElementById("emailError").innerText = "Email is required.";
        }
      });

      const passwordInput = document.getElementById("password");
      passwordInput.addEventListener("focus", function() {
        document.getElementById("passwordError").innerText = "";
      });
      passwordInput.addEventListener("blur", function() {
        if (passwordInput.value === "") {
          document.getElementById("passwordError").innerText = "Password is required.";
        }
      });
    };
  </script>
</head>
<body>
  <div class="container">
    <div class="login form">
      <header>Login</header>
      <form action="login.php" method="POST" id="loginForm">
        <span id="emailError" style="color: red;"></span>
        <input type="email" id="email" name="email" placeholder="Enter your email" required>
        <span id="passwordError" style="color: red;"></span>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>
        <input type="submit" class="button" name="login" value="Login">
      </form>
      <div class="signup">
        <span class="signup">Don't have an account? <a href="registrasi.php">Signup</a></span>
      </div>
    </div>
  </div>
</body>
</html>