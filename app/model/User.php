<?php 
require_once __DIR__ . "/../../config/Database.php"; // Adjusted path for portability

class User {
    protected $pdo;

    public function __construct() {
        // Get PDO connection using the Singleton pattern
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function register($name, $email, $password) {
        // Check if the email already exists
        $query = "SELECT COUNT(*) FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['email' => $email]);
        $emailExists = $stmt->fetchColumn();

        if ($emailExists) {
            throw new Exception("Email already exists.");
        }

        // Prepare the SQL query for user registration
        $insertQuery = "INSERT INTO users (name, email, password) VALUES (:name, :email, :password)";
        $insertStmt = $this->pdo->prepare($insertQuery);
        
        // Bind the parameters to the prepared statement
        $insertStmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ]);

        // Execute the query and handle success/failure
        if ($insertStmt->rowCount() > 0) {
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
            unset($user['password']); // Don't send password to session
            return $user; // Login successful
        }

        return false; // Invalid credentials
    }

    public function getUserById($id) {
        // Prepare the SQL query to fetch user by ID
        $query = "SELECT id, name, email, age, role, rating, photo, profile_details FROM users WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // Fetch the user record
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $user ?: false;
    }

    public function updateUser($id, $data) {
        // Build update query dynamically based on provided data
        $updateFields = [];
        $params = [':id' => $id];
        
        foreach ($data as $field => $value) {
            if ($value !== null) {
                $updateFields[] = "$field = :$field";
                $params[":$field"] = $value;
            }
        }
        
        if (empty($updateFields)) {
            return false;
        }
        
        $query = "UPDATE users SET " . implode(', ', $updateFields) . " WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        
        return $stmt->execute($params);
    }

    public function updateProfilePhoto($id, $photoPath) {
        $query = "UPDATE users SET photo = :photo WHERE id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':photo', $photoPath);
        
        return $stmt->execute();
    }
}
?>
