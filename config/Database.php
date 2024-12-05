<?php
class Database {
    private $host = "localhost";
    private $db_name = "eventure";
    private $username = "root";
    private $password = "";
    private static $instance = null; 
    private $pdo;

    private function __construct() { 
        // Private constructor to prevent direct instantiation 
        try { 
            $this->pdo = new PDO("mysql:host={$this->host};dbname={$this->db_name}", $this->username, $this->password); 
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
        } 
        catch (PDOException $e) { 
            die("Database connection failed: " . $e->getMessage()); 
        } 
    }

    public static function getInstance() { 
        if (self::$instance === null) { 
            self::$instance = new self(); 
        } 
        return self::$instance; 
    }

    public function getConnection() { 
        return $this->pdo; 
    }
}
?>
