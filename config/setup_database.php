<?php
try {
    $conn = new PDO("mysql:host=localhost", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "CREATE DATABASE IF NOT EXISTS eventure";
    $conn->exec($sql);
    echo "Database created successfully or already exists\n";

    $conn->exec("USE eventure");

    $sql = "CREATE TABLE IF NOT EXISTS events (
        id int(11) NOT NULL AUTO_INCREMENT,
        photo varchar(255) DEFAULT NULL,
        title varchar(255) DEFAULT NULL,
        category varchar(255) DEFAULT NULL,
        city varchar(255) DEFAULT NULL,
        date_time datetime DEFAULT NULL,
        description text DEFAULT NULL,
        supervisor_id int(11) DEFAULT NULL,
        status enum('pending','approved','canceled') DEFAULT 'pending',
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    $conn->exec($sql);
    echo "Table 'events' created successfully or already exists\n";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
