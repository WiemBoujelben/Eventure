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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $user = $this->userModel->login($email, $password);
            if ($user) {
                $_SESSION['user'] = $user;
                header('Location: index.php?page=profile');
            } else {
                echo "Invalid credentials.";
            }
        }
        include_once 'app/view/user/login.php';
    }

    public function profile() {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit();
        }
        include_once 'app/view/user/profile.php';
    }
}
?>