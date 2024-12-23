<?php
class Database {
    private $host = "localhost:3308";
    private $user = "root";
    private $password = "";
    private $dbname = "Aoe2";
    private $connection;

    // Constructor untuk menginisialisasi koneksi database
    public function __construct() {
        $this->connect();
    }

    // Method untuk membuat koneksi ke database
    private function connect() {
        $this->connection = new mysqli($this->host, $this->user, $this->password, $this->dbname);

        if ($this->connection->connect_error) {
            die("Koneksi gagal: " . $this->connection->connect_error);
        }
    }

    // Method untuk mendapatkan koneksi
    public function getConnection() {
        return $this->connection;
    }

    // Method untuk menutup koneksi
    public function closeConnection() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}
?>
