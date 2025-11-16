<?php
$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_NAME = "db_miesetan"; 

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

class Database {
    private $host = "localhost";
    private $user = "root";
    private $pass = "";
    private $dbname = "db_miesetan";
    private $conn;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
        if ($this->conn->connect_error) {
            die("Koneksi gagal: " . $this->conn->connect_error);
        }
    }

    public function getConnection() {
        return $this->conn;
    }

    public function close() {
        if ($this->conn) {
            $this->conn->close();
        }
    }
}
?>
