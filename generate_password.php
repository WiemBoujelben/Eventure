<?php
$password = "superadmin123"; // This will be your superadmin password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
echo "Password: " . $password . "\n";
echo "Hashed Password: " . $hashed_password . "\n";
?>
