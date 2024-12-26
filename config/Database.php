<?php
class Database {
    private $host = "localhost";
    private $db_name = "eventure";
    private $username = "root";
    private $password = "";
    private static $instance = null;
    private $conn = null;

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO(
                    "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                    $this->username,
                    $this->password,
                    array(
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    )
                );
            } catch(PDOException $e) {
                echo "Connection Error: " . $e->getMessage();
            }
        }
        return $this->conn;
    }
}
?>
