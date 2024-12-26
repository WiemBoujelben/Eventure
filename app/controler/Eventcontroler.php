<?php
require_once __DIR__ . '/../model/Event.php';

class Eventcontroler {
    private $event;

    public function __construct() {
        $this->event = new Event();
    }

    public function index() {
        try {
            $category = isset($_GET['category']) ? $_GET['category'] : null;
            
            $result = $this->event->read($category);
            $events = $result->fetchAll(PDO::FETCH_ASSOC);
            
            $categories = [];
            $categoryQuery = $this->event->read();
            while ($row = $categoryQuery->fetch(PDO::FETCH_ASSOC)) {
                if (!in_array($row['category'], $categories)) {
                    $categories[] = $row['category'];
                }
            }
            
            include __DIR__ . '/../view/event/EventList.php';
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    public function Create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $targetDir = __DIR__ . '/../../public/images/';
                if (!file_exists($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                
                $fileName = basename($_FILES["photo"]["name"]);
                $targetFilePath = $targetDir . $fileName;
                
                if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
                    $this->event->photo = 'public/images/' . $fileName;
                    $this->event->title = $_POST['title'];
                    $this->event->category = $_POST['category'];
                    $this->event->city = $_POST['city'];
                    $this->event->date_time = $_POST['date_time'];
                    $this->event->description = $_POST['description'];
                    $this->event->supervisor_id = 1;
                    $this->event->status = 'pending';
    
                    if ($this->event->create()) {
                        header('Location: index.php');
                        exit();
                    }
                }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        
        include __DIR__ . '/../view/event/CreateEvent.php';
    }

    public function Edit() {
        if (!isset($_GET['id'])) {
            header('Location: index.php');
            exit();
        }

        $this->event->id = $_GET['id'];
        $result = $this->event->read_single();
        $event = $result->fetch(PDO::FETCH_ASSOC);

        if (!$event) {
            header('Location: index.php');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->event->title = $_POST['title'];
                $this->event->category = $_POST['category'];
                $this->event->city = $_POST['city'];
                $this->event->date_time = $_POST['date_time'];
                $this->event->description = $_POST['description'];
                $this->event->status = $_POST['status'];

                if (isset($_FILES['photo']) && $_FILES['photo']['size'] > 0) {
                    $targetDir = __DIR__ . '/../../public/images/';
                    if (!file_exists($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }
                    
                    $fileName = basename($_FILES["photo"]["name"]);
                    $targetFilePath = $targetDir . $fileName;
                    
                    if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFilePath)) {
                        $this->event->photo = 'public/images/' . $fileName;
                    }
                } else {
                    $this->event->photo = $event['photo']; 
                }

                if ($this->event->update()) {
                    header('Location: index.php');
                    exit();
                }
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }

        include __DIR__ . '/../view/event/EditEvent.php';
    }

    public function Delete() {
        if (!isset($_GET['id'])) {
            header('Location: index.php');
            exit();
        }

        try {
            $this->event->id = $_GET['id'];
            if ($this->event->delete()) {
                header('Location: index.php');
                exit();
            }
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>