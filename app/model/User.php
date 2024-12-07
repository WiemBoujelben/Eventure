<?php 
require_once __DIR__ . "/../../config/Database.php"; // Adjusted path for portability

class User {
    protected $pdo;

    public function __construct() {
        // Get PDO connection using the Singleton pattern
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function register($name, $email, $password) {
        // Prepare the SQL query for user registration
        $query = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        $stmt = $this->pdo->prepare($query);
        
        // Bind the parameters to the prepared statement
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', password_hash($password, PASSWORD_DEFAULT));

        // Execute the query and handle success/failure
        if ($stmt->execute()) {
            return true; // Registration successful
        } else {
            return false; // Registration failed
        }
    }

    public function login($email, $password) {
        // Prepare the SQL query for user login
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Fetch the user record
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify the password
        if ($user && password_verify($password, $user['password'])) {
            return $user; // Login successful
        }

        return false; // Invalid credentials
    }
}
?>
