<?php 
class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            if ($this->userModel->register($name, $email, $password)) {
                header('Location: index.php?page=login');
            } else {
                echo "Registration failed.";
            }
        }
        include_once 'app/view/user/register.php';
    }

    public function login() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate POST data
            if (!isset($_POST['email']) || !isset($_POST['password'])) {
                $error = "Please provide both email and password.";
            } else {
                $email = trim($_POST['email']);
                $password = $_POST['password'];

                if (empty($email) || empty($password)) {
                    $error = "Email and password cannot be empty.";
                } else {
                    $user = $this->userModel->login($email, $password);
                    if ($user) {
                        $_SESSION['user'] = $user;
                        header('Location: index.php?page=profile');
                        exit();
                    } else {
                        $error = "Invalid credentials.";
                    }
                }
            }
        }
        // Pass error to the view
        include_once 'app/view/user/login.php';
    }

    public function profile() {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit();
        }
        
        // Get the user data from the model
        $userId = $_SESSION['user']['id'];
        $user = $this->userModel->getUserById($userId);
        
        // If user data couldn't be fetched, redirect to login
        if (!$user) {
            unset($_SESSION['user']);
            header('Location: index.php?page=login');
            exit();
        }
        
        // Update session with latest user data
        $_SESSION['user'] = $user;
        
        include_once 'app/view/user/profile.php';
    }

    public function edit_profile() {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit();
        }

        $error = '';
        $success = '';
        $user = $_SESSION['user'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Collect form data
            $updateData = [
                'name' => trim($_POST['name'] ?? $user['name']),
                'age' => !empty($_POST['age']) ? (int)$_POST['age'] : null,
                'role' => trim($_POST['role'] ?? $user['role']),
                'profile_details' => trim($_POST['profile_details'] ?? '')
            ];

            // Update user data
            if ($this->userModel->updateUser($user['id'], $updateData)) {
                $success = "Profile updated successfully!";
                // Refresh user data in session
                $_SESSION['user'] = $this->userModel->getUserById($user['id']);
                header('Location: index.php?page=profile&success=1');
                exit();
            } else {
                $error = "Failed to update profile.";
            }
        }

        include_once 'app/view/user/edit_profile.php';
    }

    public function update_profile_photo() {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit();
        }

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
            $file = $_FILES['photo'];
            
            // Validate file
            $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
            $max_size = 5 * 1024 * 1024; // 5MB
            
            if (!in_array($file['type'], $allowed_types)) {
                $error = "Only JPG, PNG and GIF images are allowed.";
            } elseif ($file['size'] > $max_size) {
                $error = "File size must be less than 5MB.";
            } elseif ($file['error'] !== UPLOAD_ERR_OK) {
                $error = "Error uploading file.";
            } else {
                // Create uploads directory if it doesn't exist
                $upload_dir = 'uploads/profile_photos/';
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }

                // Generate unique filename
                $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                $filename = uniqid('profile_') . '.' . $extension;
                $filepath = $upload_dir . $filename;

                // Move uploaded file
                if (move_uploaded_file($file['tmp_name'], $filepath)) {
                    // Update database with new photo path
                    if ($this->userModel->updateProfilePhoto($_SESSION['user']['id'], $filepath)) {
                        $_SESSION['user'] = $this->userModel->getUserById($_SESSION['user']['id']);
                        header('Location: index.php?page=profile&success=1');
                        exit();
                    } else {
                        $error = "Failed to update profile photo in database.";
                    }
                } else {
                    $error = "Failed to save uploaded file.";
                }
            }
        }

        // If there was an error, redirect back to profile with error message
        if (!empty($error)) {
            header('Location: index.php?page=profile&error=' . urlencode($error));
            exit();
        }
    }

    public function requestAdmin() {
        $userId = $_SESSION['user_id']; // Assuming user ID is stored in session
        $query = "INSERT INTO admin_requests (user_id) VALUES (:user_id)";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['user_id' => $userId]);
        // Redirect or show success message
    }
}
?>