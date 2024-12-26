<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

spl_autoload_register(function ($class) {
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $paths = [
        __DIR__ . '/app/controler/',
        __DIR__ . '/app/model/',
        __DIR__ . '/app/view/',
        __DIR__ . '/config/'  
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

require_once __DIR__ . '/app/controler/Eventcontroler.php';

$controler = new Eventcontroler();

$action = isset($_GET['action']) ? $_GET['action'] : 'index';
switch($action) {
    case 'Create':
        $controler->Create();
        break;
    case 'Edit':
        $controler->Edit();
        break;
    case 'Delete':
        $controler->Delete();
        break;
    default:
        $controler->index();
        break;
}
?>